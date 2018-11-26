<?php
/**
 * Dispatcher executor
 * User: moyo
 * Date: 2018/5/30
 * Time: 9:57 AM
 */

namespace Carno\Web\Chips\Dispatcher;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Contracts\Controller\Extensional;
use Carno\Web\Controller\Based;
use Carno\Web\Exception\InvalidControllerException;
use Closure;
use Generator;
use Throwable;

trait Executor
{
    /**
     * @param Context $ctx
     * @param ServerRequest $sr
     * @param callable $handler
     * @param array $params
     * @return Generator|array [response.http, result.raw]
     * @throws Throwable
     */
    protected function calling(Context $ctx, ServerRequest $sr, callable $handler, array $params)
    {
        if (is_array($handler)) {
            list($controller, $action) = $handler;
            return $controller instanceof Based
                ? yield $this->excController($controller, $action, $ctx, $sr, $params)
                : yield $this->excObject($controller, $action, $this->basedAnonymous($ctx, $sr, $params))
            ;
        } elseif ($handler instanceof Closure) {
            return yield $this->excClosure($handler, $this->basedAnonymous($ctx, $sr, $params));
        } else {
            throw new InvalidControllerException;
        }
    }

    /**
     * @param Based $controller
     * @param string $action
     * @param Context $ctx
     * @param ServerRequest $sr
     * @param array $params
     * @return array
     * @throws Throwable
     */
    private function excController(Based $controller, string $action, Context $ctx, ServerRequest $sr, array $params)
    {
        $session = (clone $controller)->initialize($ctx, $sr, $action, $params);

        $extension = $session instanceof Extensional ? $session->extension() : null;

        try {
            $extension && $response = yield $extension->requesting($session);
            if (is_null($response ?? null)) {
                $result = yield $session->$action();
                $extension && $response = yield $extension->responding($session, $result);
            }
        } catch (Throwable $e) {
            if ($extension) {
                $response = yield $extension->exceptions($session, $e);
            } else {
                throw $e;
            }
        }

        return [$response ?? $session->response(), $response ?? $result ?? null];
    }

    /**
     * @param Closure $handler
     * @param Based $anonymous
     * @return array
     */
    private function excClosure(Closure $handler, Based $anonymous)
    {
        return array_reverse([yield $handler($anonymous), $anonymous->response()]);
    }

    /**
     * @param object $class
     * @param string $method
     * @param Based $anonymous
     * @return array
     */
    private function excObject(object $class, string $method, Based $anonymous)
    {
        return array_reverse([yield $class->$method($anonymous), $anonymous->response()]);
    }

    /**
     * @param Context $ctx
     * @param ServerRequest $sr
     * @param array $params
     * @return Based
     */
    private function basedAnonymous(Context $ctx, ServerRequest $sr, array $params) : Based
    {
        return (new class extends Based {

        })->initialize($ctx, $sr, 'invoke', $params);
    }
}
