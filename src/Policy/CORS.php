<?php
/**
 * CORS policy handler
 * User: moyo
 * Date: 2019-01-08
 * Time: 19:08
 */

namespace Carno\Web\Policy;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Contracts\Policy;
use Carno\Web\Policy\Rules\CORS as Rule;
use FastRoute\Dispatcher;

class CORS implements Policy
{
    /**
     * @var string
     */
    private $prefix = null;

    /**
     * @var Rule
     */
    private $rule = null;

    /**
     * CORS constructor.
     * @param string $prefix
     * @param Rule $rule
     */
    public function __construct(string $prefix, Rule $rule)
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
