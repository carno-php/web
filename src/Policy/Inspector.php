<?php
/**
 * Policy inspector (global)
 * User: moyo
 * Date: 2019-01-08
 * Time: 19:31
 */

namespace Carno\Web\Policy;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Contracts\Policy;

class Inspector implements Policy
{
    /**
     * @var Policy[]
     */
    private $policies = [];

    /**
     * @param Policy ...$policies
     */
    public function join(Policy ...$policies) : void
    {
        $this->policies = array_merge($this->policies, $policies);
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function allowed(string $uri) : bool
    {
        return true;
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
        foreach ($this->policies as $policy) {
            if ($policy->allowed($sr->getUri()->getPath())) {
                if ($result = $policy->request(...func_get_args())) {
                    return $result;
                }
            }
        }
        return null;
    }

    /**
     * @param ServerRequest $sr
     * @param Response $respond
     * @return Response
     */
    public function response(ServerRequest $sr, Response $respond) : Response
    {
        foreach ($this->policies as $policy) {
            if ($policy->allowed($sr->getUri()->getPath())) {
                $respond = $policy->response($sr, $respond);
            }
        }
        return $respond;
    }
}
