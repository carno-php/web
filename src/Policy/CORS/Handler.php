<?php
/**
 * CORS handler
 * User: moyo
 * Date: 2019-01-08
 * Time: 19:08
 */

namespace Carno\Web\Policy\CORS;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Contracts\Policy;
use FastRoute\Dispatcher;

class Handler implements Policy
{
    /**
     * @var string
     */
    private $prefix = null;

    /**
     * @var Processor
     */
    private $rule = null;

    /**
     * CORS constructor.
     * @param string $prefix
     * @param Processor $rule
     */
    public function __construct(string $prefix, Processor $rule)
    {
        $this->prefix = $prefix;
        $this->rule = $rule;
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function allowed(string $uri) : bool
    {
        return substr($uri, 0, strlen($this->prefix)) === $this->prefix;
    }

    /**
     * @param Context $ctx
     * @param ServerRequest $sr
     * @param int $route
     * @param callable $handler
     * @param array $params
     * @return Response|null
     */
    public function request(
        Context $ctx,
        ServerRequest $sr,
        int $route,
        callable $handler = null,
        array $params = []
    ) : ?Response {
        if ($route === Dispatcher::FOUND) {
            return null;
        }

        if ($sr->getMethod() !== 'OPTIONS') {
            return null;
        }

        return $this->response($sr, new Response);
    }

    /**
     * @param ServerRequest $sr
     * @param Response $respond
     * @return Response
     */
    public function response(ServerRequest $sr, Response $respond) : Response
    {
        return $this->rule->process($sr, $respond);
    }
}
