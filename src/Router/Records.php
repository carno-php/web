<?php
/**
 * Router records
 * User: moyo
 * Date: 2018/5/29
 * Time: 11:23 AM
 */

namespace Carno\Web\Router;

use FastRoute\DataGenerator\GroupCountBased as GCBGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased as GCBDispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

class Records
{
    /**
     * @var RouteCollector
     */
    private $collector = null;

    /**
     * @var Dispatcher
     */
    private $dispatcher = null;

    /**
     * Records constructor.
     */
    public function __construct()
    {
        $this->collector = new RouteCollector(new Std, new GCBGenerator);
    }

    /**
     * @return Dispatcher
     */
    public function dispatcher() : Dispatcher
    {
        return $this->dispatcher ?? $this->dispatcher = new GCBDispatcher($this->collector->getData());
    }

    /**
     * @param string $method
     * @param string $uri
     * @param callable $processor
     */
    public function accept(string $method, string $uri, callable $processor) : void
    {
        $this->collector->addRoute($method, $uri, $processor);
    }
}
