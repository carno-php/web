<?php
/**
 * CORS test
 * User: moyo
 * Date: 2019-01-09
 * Time: 10:05
 */

namespace Carno\Web\Tests;

use Carno\Container\DI;
use function Carno\Coroutine\go;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\HTTP\Standard\Uri;
use Carno\Web\Dispatcher;
use Carno\Web\Exception\MethodNotAllowedException;
use Carno\Web\Exception\RouterNotFoundException;
use Carno\Web\Policy\Inspector;
use Carno\Web\Router\Initializer;
use Carno\Web\Router\Records;
use Carno\Web\Tests\Routes\R2;
use PHPUnit\Framework\TestCase;
use Throwable;

class CORSTest extends TestCase
{
    public function testController()
    {
        /**
         * @var Records $routes
         * @var Inspector $inspector
         */

        DI::set(Records::class, $routes = DI::object(Records::class));
        DI::set(Inspector::class, $inspector = DI::object(Inspector::class));

        (new Initializer)->loads(R2::class);

        $dispatcher = new Dispatcher;
        $dispatcher->policing($inspector);
        $dispatcher->dispatched($routes->dispatcher());

        go(function () use ($dispatcher) {
            yield $this->case1($dispatcher);
        });
    }

    private function case1(Dispatcher $dsp)
    {
        $this->asserts(yield $this->invoke($dsp, 'OPTIONS', '/cors1/get'), '*', ['get']);
        $this->asserts(yield $this->invoke($dsp, 'GET', '/cors1/get'), '*', ['get']);

        $this->asserts(yield $this->invoke($dsp, 'OPTIONS', '/cors2/get'), 'http://host', ['get'], null, null, 'true');
        $this->asserts(yield $this->invoke($dsp, 'GET', '/cors2/get'), 'http://host', ['get'], null, null, 'true');


        $this->asserts(
            yield $this->invoke($dsp, 'OPTIONS', '/cors3/test'),
            'http://localhost',
            ['get', 'post'],
            ['header1', 'header2']
        );

        $this->asserts(
            yield $this->invoke($dsp, 'OPTIONS', '/cors4/test'),
            '*',
            null,
            null,
            ['expose1', 'expose2'],
            null,
            3600
        );

        /**
         * @var Response $resp
         */

        // err case 1
        $resp = yield $this->invoke($dsp, 'POST', '/cors1/get');
        $this->assertEquals(500, $resp->getStatusCode());
        $this->assertEquals(MethodNotAllowedException::class, $resp->getHeaderLine('x-err-class'));

        // err case 2
        $resp = yield $this->invoke($dsp, 'GET', '/cors3/test');
        $this->assertEquals(500, $resp->getStatusCode());
        $this->assertEquals(RouterNotFoundException::class, $resp->getHeaderLine('x-err-class'));
    }

    private function asserts(
        Response $resp,
        string $origin,
        array $methods = null,
        array $headers = null,
        array $exposes = null,
        string $credentials = null,
        string $expired = null
    ) {
        $this->assertEquals($origin, $resp->getHeaderLine('Access-Control-Allow-Origin'));

        if ($methods) {
            $methods = array_map('strtoupper', $methods);
            $this->assertEquals(implode(',', $methods), $resp->getHeaderLine('Access-Control-Allow-Methods'));
        }

        if ($headers) {
            $this->assertEquals(implode(',', $headers), $resp->getHeaderLine('Access-Control-Allow-Headers'));
        }

        if ($exposes) {
            $this->assertEquals(implode(',', $exposes), $resp->getHeaderLine('Access-Control-Expose-Headers'));
        }

        if ($credentials) {
            $this->assertEquals($credentials, $resp->getHeaderLine('Access-Control-Allow-Credentials'));
        }

        if ($expired) {
            $this->assertEquals($expired, $resp->getHeaderLine('Access-Control-Max-Age'));
        }
    }

    /**
     * @param Dispatcher $dsp
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    private function invoke(Dispatcher $dsp, string $method, string $uri, array $headers = [])
    {
        $scheme = 'http';
        $host = 'host';

        $headers = array_merge($headers, ['Origin' => sprintf('%s://%s', $scheme, $host)]);

        $srq = (new ServerRequest([], [], [], $method, $headers));

        $srq->withUri(new Uri($scheme, $host, null, $uri));

        try {
            return yield $dsp->invoke($srq);
        } catch (Throwable $e) {
            return new Response(
                500,
                ['x-err-class' => get_class($e)],
                sprintf('%s::%s', get_class($e), $e->getMessage())
            );
        }
    }
}
