<?php
/**
 * Controller test
 * User: moyo
 * Date: 2018-11-26
 * Time: 11:40
 */

namespace Carno\Web\Tests;

use Carno\Container\DI;
use Carno\Coroutine\Context;
use function Carno\Coroutine\ctx;
use function Carno\Coroutine\go;
use Carno\HTTP\Server\Connection;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\HTTP\Standard\Uri;
use Carno\Web\Controller\Based;
use Carno\Web\Dispatcher;
use Carno\Web\Handlers\IngressWrapper;
use Carno\Web\Router\Configure;
use Carno\Web\Router\Initializer;
use Carno\Web\Router\Records;
use Carno\Web\Router\Setup;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function testRemote()
    {
        $router = new class extends Configure {
            protected function init(Setup $setup)
            {
                $setup->get('/remote/ip', static function (Based $session) {
                    return $session->remote()->peer()->host();
                });
            }
        };

        /**
         * @var Records $routes
         */

        DI::set(Records::class, $routes = DI::object(Records::class));

        (new Initializer)->loads(get_class($router));

        $dispatcher = new Dispatcher;
        $dispatcher->dispatched($routes->dispatcher());

        go(function () use ($dispatcher) {
            /**
             * @var Context $ctx
             */
            $ctx = yield ctx();

            $srq = (new ServerRequest([], [], [], 'GET', []));
            $srq->withUri(new Uri('http', 'host', null, '/remote/ip'));

            /**
             * @var Response $response
             */

            // without client info
            $response = yield $dispatcher->invoke($srq);
            $this->assertEquals('0.0.0.0', $response->getBody());

            // with ingress connection
            $ctx->set(IngressWrapper::CONNECTION, (new Connection)->setRemote('172.16.0.1', 233));
            $response = yield $dispatcher->invoke($srq);
            $this->assertEquals('172.16.0.1', $response->getBody());

            // with xff headers
            $srq->withHeader('X-Forwarded-For', ['172.16.1.1', '172.16.1.2', '172.16.1.3']);
            $response = yield $dispatcher->invoke($srq);
            $this->assertEquals('172.16.1.1', $response->getBody());
        });
    }
}
