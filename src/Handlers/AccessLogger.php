<?php
/**
 * Access logger
 * User: moyo
 * Date: 2018/6/1
 * Time: 3:48 PM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use Carno\Coroutine\Context;
use Carno\HTTP\Server\Connection;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Net\Address;
use Throwable;

class AccessLogger implements Layered
{
    /**
     * ctx vars
     */
    private const REMOTE = 'log-r-remote';
    private const START = 'log-r-start-at';

    /**
     * @param Connection $ingress
     * @param Context $ctx
     * @return mixed
     */
    public function inbound($ingress, Context $ctx)
    {
        $ctx->set(self::REMOTE, $ingress->remote());
        $ctx->set(self::START, microtime(true));
        return $ingress;
    }

    /**
     * @param Response $response
     * @param Context $ctx
     * @return mixed
     */
    public function outbound($response, Context $ctx)
    {
        $this->requestFIN($ctx, null);
        return $response;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     * @throws Throwable
     */
    public function exception(Throwable $e, Context $ctx)
    {
        $this->requestFIN($ctx, $e);
        throw $e;
    }

    /**
     * @param Context $ctx
     * @param Throwable $error
     */
    private function requestFIN(Context $ctx, Throwable $error = null) : void
    {
        /**
         * @var ServerRequest $request
         * @var Response $response
         * @var Address $remote
         * @var float $start
         */

        $request = $ctx->get(IngressWrapper::REQUESTING);
        $response = $ctx->get(IngressWrapper::RESPONDING);
        $remote = $ctx->get(self::REMOTE);
        $start = $ctx->get(self::START);

        if (!$request || !$response) {
            return;
        }

        $meta = [
            'method' => $request->getMethod(),
            'path' => $request->getUri()->getPath(),
            'status' => $response->getStatusCode(),
            'from' => (string) $remote,
            'cost' => $start ? intval((microtime(true) - $start) * 1000) : 0,
        ];

        if ($error) {
            $meta = array_merge($meta, [
                'error' => sprintf('%s::%s', get_class($error), $error->getMessage()),
                'file' => sprintf('%s:%d', $error->getFile(), $error->getLine()),
            ]);
            logger('web')->notice('Request failed', $meta);
        } else {
            logger('web')->debug('Request finished', $meta);
        }
    }
}
