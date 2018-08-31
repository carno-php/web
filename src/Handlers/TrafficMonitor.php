<?php
/**
 * Traffic monitor
 * User: moyo
 * Date: 2018/6/6
 * Time: 11:38 AM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\Monitor\Metrics;
use Throwable;

class TrafficMonitor implements Layered
{
    /**
     * @var Metrics\Counter
     */
    private $ttRequests = null;

    /**
     * @var Metrics\Histogram
     */
    private $ttResponses = null;

    /**
     * @var Metrics\Counter
     */
    private $ttExceptions = null;

    /**
     * @var Metrics\Gauge
     */
    private $ttProcessing = null;

    /**
     * @var array
     */
    private $ttStatusCodes = [];

    /**
     * TrafficMonitor constructor.
     */
    public function __construct()
    {
        $this->ttRequests = Metrics::counter()->named('http.requests.all');
        $this->ttResponses = Metrics::histogram()->named('http.responses.time')->fixed(5, 20, 50, 200, 500, 1000);
        $this->ttExceptions = Metrics::counter()->named('http.exceptions.all');
        $this->ttProcessing = Metrics::gauge()->named('http.processing.now');
    }

    /**
     * @param int $status
     * @return Metrics\Counter
     */
    private function cc(int $status) : Metrics\Counter
    {
        return $this->ttStatusCodes[$status] ?? $this->ttStatusCodes[$status] =
            Metrics::counter()
                ->named('http.responses.code')
                ->labels(['code' => $status])
        ;
    }

    /**
     * @param mixed $message
     * @param Context $ctx
     * @return mixed
     */
    public function inbound($message, Context $ctx)
    {
        $this->requestBegin($ctx);
        $this->ttRequests->inc();
        $this->ttProcessing->inc();
        return $message;
    }

    /**
     * @param mixed $message
     * @param Context $ctx
     * @return mixed
     */
    public function outbound($message, Context $ctx)
    {
        $this->ttProcessing->dec();
        $this->requestEnd($ctx);
        return $message;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     * @throws Throwable
     */
    public function exception(Throwable $e, Context $ctx)
    {
        $this->ttProcessing->dec();
        $this->ttExceptions->inc();
        $this->requestEnd($ctx);
        throw $e;
    }

    /**
     * @param Context $ctx
     */
    private function requestBegin(Context $ctx) : void
    {
        $ctx->set('tm-r-begin', microtime(true));
    }

    /**
     * @param Context $ctx
     */
    private function requestEnd(Context $ctx) : void
    {
        /**
         * @var Response $response
         */

        if ($response = $ctx->get(IngressWrapper::RESPONDING)) {
            $this->cc($response->getStatusCode())->inc();
        }

        $this->ttResponses->observe((microtime(true) - $ctx->get('tm-r-begin') ?? 0) * 1000);
    }
}
