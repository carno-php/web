<?php
/**
 * Policy handler
 * User: moyo
 * Date: 2019-01-08
 * Time: 16:43
 */

namespace Carno\Web\Contracts;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;

interface Policy
{
    /**
     * @param string $uri
     * @return bool
     */
    public function allowed(string $uri) : bool;

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
    ) : ?Response;

    /**
     * @param ServerRequest $sr
     * @param Response $respond
     * @return Response
     */
    public function response(ServerRequest $sr, Response $respond) : Response;
}
