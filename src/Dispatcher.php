<?php
/**
 * Web dispatcher
 * User: moyo
 * Date: 2018/5/29
 * Time: 12:12 PM
 */

namespace Carno\Web;

use function Carno\Coroutine\ctx;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Chips\Dispatcher\Executor;
use Carno\Web\Chips\Dispatcher\Policing;
use Carno\Web\Chips\Dispatcher\Responsive;
use Carno\Web\Exception\InternalServerException;
use Carno\Web\Exception\MethodNotAllowedException;
use Carno\Web\Exception\RouterNotFoundException;
use FastRoute\Dispatcher as FRDispatcher;
use Throwable;

class Dispatcher
{
    use Policing, Executor, Responsive;

    /**
     * @var FRDispatcher
     */
    private $router = null;

    /**
     * @param FRDispatcher $dispatcher
     */
    public function dispatched(FRDispatcher $dispatcher) : void
    {
        $this->router = $dispatcher;
    }

    /**
     * @param ServerRequest $sr
     * @return Response
     * @throws Throwable
     */
    public function invoke(ServerRequest $sr)
    {
        $routed = $this->router->dispatch($sr->getMethod(), $sr->getUri()->getPath());

        $ctx = yield ctx();

        if ($this->policy
            && $response = $this->policy->request(
                $ctx,
                $sr,
                $routed[0],
                $routed[0] === FRDispatcher::FOUND ? $routed[1] : null,
                $routed[0] === FRDispatcher::FOUND ? $routed[2] : []
            )
        ) {
            return $response;
        }

        switch ($routed[0]) {
            case FRDispatcher::FOUND:
                $respond = $this->responding(
                    $sr,
                    ...(yield $this->calling($ctx, $sr, $routed[1], $routed[2]))
                );
                return $this->policy ? $this->policy->response($sr, $respond) : $respond;
            case FRDispatcher::NOT_FOUND:
                throw new RouterNotFoundException;
            case FRDispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException;
            default:
                throw new InternalServerException('Routing failed');
        }
    }
}
