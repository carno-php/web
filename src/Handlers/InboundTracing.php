<?php
/**
 * Request tracing
 * User: moyo
 * Date: 2018/6/6
 * Time: 2:10 PM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use Carno\Coroutine\Context;
use Carno\HTTP\Server\Connection;
use Carno\HTTP\Standard\Response;
use Carno\Tracing\Contracts\Platform;
use Carno\Tracing\Contracts\Vars\EXT;
use Carno\Tracing\Contracts\Vars\FMT;
use Carno\Tracing\Contracts\Vars\TAG;
use Carno\Tracing\Standard\Endpoint;
use Carno\Tracing\Utils\SpansCreator;
use Carno\Tracing\Utils\SpansExporter;
use Throwable;

class InboundTracing implements Layered
{
    use SpansCreator, SpansExporter;

    /**
     * @var Platform
     */
    private $platform = null;

    /**
     * RequestTracing constructor.
     * @param Platform $platform
     */
    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    /**
     * @param Connection $ingress
     * @param Context $ctx
     * @return mixed
     */
    public function inbound($ingress, Context $ctx)
    {
        $this->newSpan(
            $ctx,
            $ingress->request()->getUri()->getPath(),
            [
                TAG::SPAN_KIND => TAG::SPAN_KIND_RPC_SERVER,
                TAG::HTTP_URL => (string) $ingress->request()->getUri(),
                TAG::HTTP_METHOD => $ingress->request()->getMethod(),
                EXT::LOCAL_ENDPOINT => new Endpoint($ingress->serviced(), $ingress->local()),
                EXT::REMOTE_ENDPOINT => new Endpoint($ingress->serviced(), $ingress->remote()),
            ],
            [],
            FMT::HTTP_HEADERS,
            $ingress->request(),
            null,
            $this->platform
        );

        return $ingress;
    }

    /**
     * @param mixed $response
     * @param Context $ctx
     * @return mixed
     */
    public function outbound($response, Context $ctx)
    {
        $this->closeSpan($ctx, $this->tags($ctx));
        return $response;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     * @throws Throwable
     */
    public function exception(Throwable $e, Context $ctx)
    {
        $this->errorSpan($ctx, $e, $this->tags($ctx));
        throw $e;
    }

    /**
     * @param Context $ctx
     * @return array
     */
    private function tags(Context $ctx) : array
    {
        /**
         * @var Response $response
         */

        if ($response = $ctx->get(IngressWrapper::RESPONDING)) {
            $this->spanToHResponse($ctx, $response);
            return [
                TAG::HTTP_STATUS_CODE => $response->getStatusCode(),
            ];
        }

        return [];
    }
}
