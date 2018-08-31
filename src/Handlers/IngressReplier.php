<?php
/**
 * Ingress replier
 * User: moyo
 * Date: 2018/6/6
 * Time: 3:53 PM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use Carno\Coroutine\Context;
use Carno\HTTP\Server\Connection;
use Throwable;

class IngressReplier implements Layered
{
    /**
     * @param mixed $message
     * @param Context $ctx
     * @return mixed
     */
    public function inbound($message, Context $ctx)
    {
        return $message;
    }

    /**
     * @param mixed $message
     * @param Context $ctx
     * @return mixed
     */
    public function outbound($message, Context $ctx)
    {
        $this->replying($ctx);
        return $message;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     * @throws Throwable
     */
    public function exception(Throwable $e, Context $ctx)
    {
        $this->replying($ctx);
        throw $e;
    }

    /**
     * @param Context $ctx
     */
    private function replying(Context $ctx) : void
    {
        /**
         * @var Connection $ingress
         */

        if (($ingress = $ctx->get(IngressWrapper::CONNECTION))
            && ($response = $ctx->get(IngressWrapper::RESPONDING))
        ) {
            $ingress->reply($response);
        }
    }
}
