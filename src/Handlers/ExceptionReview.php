<?php
/**
 * Exception review
 * User: moyo
 * Date: 2018/6/1
 * Time: 2:37 PM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use Carno\Coroutine\Context;
use Throwable;

class ExceptionReview implements Layered
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
        return $message;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     */
    public function exception(Throwable $e, Context $ctx)
    {
        debug() && dump($e);
    }
}
