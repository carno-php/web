<?php
/**
 * Router test
 * User: moyo
 * Date: 2018-11-26
 * Time: 10:41
 */

namespace Carno\Web\Tests;

use Carno\Container\DI;
use Carno\Web\Router\Initializer;
use Carno\Web\Router\Records;
use Carno\Web\Tests\Routes\R1;
use FastRoute\Dispatcher as FRD;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testSetup()
    {
        /**
         * @var Records $routes
         */

        DI::set(Records::class, $routes = DI::object(Records::class));

        (new Initializer)->loads(R1::class);

        $dsp = $routes->dispatcher();

        $this->route($dsp, 'GET', '/any', FRD::NOT_FOUND);

        $this->route($dsp, 'GET', '/url/get', FRD::FOUND);
        $this->route($dsp, 'POST', '/url/get', FRD::METHOD_NOT_ALLOWED);

        $this->route($dsp, 'GET', '/url/any', FRD::FOUND);
        $this->route($dsp, 'PUT', '/url/any', FRD::FOUND);
        $this->route($dsp, 'DELETE', '/url/any', FRD::FOUND);

        $this->route($dsp, 'GET', '/url/get+post', FRD::FOUND);
        $this->route($dsp, 'POST', '/url/get+post', FRD::FOUND);
        $this->route($dsp, 'PUT', '/url/get+post', FRD::METHOD_NOT_ALLOWED);
        $this->route($dsp, 'GET', '/url/get+post+any', FRD::NOT_FOUND);

        $this->route($dsp, 'GET', '/url/rest', FRD::FOUND);
        $this->route($dsp, 'POST', '/url/rest', FRD::FOUND);
        $this->route($dsp, 'PUT', '/url/rest', FRD::FOUND);
        $this->route($dsp, 'PATCH', '/url/rest', FRD::FOUND);
        $this->route($dsp, 'DELETE', '/url/rest', FRD::FOUND);
        $this->route($dsp, 'OPTIONS', '/url/rest', FRD::METHOD_NOT_ALLOWED);

        $this->route($dsp, 'GET', '/get', FRD::NOT_FOUND);
        $this->route($dsp, 'GET', '/prefix/get', FRD::FOUND);
        $this->route($dsp, 'POST', '/prefix/get', FRD::METHOD_NOT_ALLOWED);
        $this->route($dsp, 'GET', '/prefix/get+post', FRD::FOUND);
        $this->route($dsp, 'POST', '/prefix/get+post', FRD::FOUND);
        $this->route($dsp, 'PATCH', '/prefix/get+post', FRD::METHOD_NOT_ALLOWED);
        $this->route($dsp, 'GET', '/prefix', FRD::NOT_FOUND);

        $this->route($dsp, 'GET', '/w-opts-1/moyo/123', FRD::FOUND, ['name' => 'moyo', 'id' => 123]);
        $this->route($dsp, 'GET', '/w-opts-1/moyo/world', FRD::NOT_FOUND);
        $this->route($dsp, 'GET', '/w-opts-1/hello-world/233', FRD::FOUND, ['name' => 'hello-world', 'id' => 233]);

        $this->route($dsp, 'GET', '/w-opts-2/moyo/123', FRD::METHOD_NOT_ALLOWED);
        $this->route($dsp, 'POST', '/w-opts-2/moyo/123', FRD::FOUND, ['name' => 'moyo', 'id' => 123]);
        $this->route($dsp, 'POST', '/w-opts-2/moyo/world', FRD::NOT_FOUND);
        $this->route($dsp, 'POST', '/w-opts-2/moyo', FRD::FOUND, ['name' => 'moyo']);
        $this->route($dsp, 'POST', '/w-opts-2/hello-world', FRD::NOT_FOUND);
    }

    private function route(FRD $dsp, string $method, string $uri, int $matched, array $params = [])
    {
        $routed = $dsp->dispatch($method, $uri);

        $this->assertEquals($matched, $routed[0]);

        $params && $this->assertEquals($params, $routed[2]);
    }
}
