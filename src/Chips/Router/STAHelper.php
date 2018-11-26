<?php
/**
 * Setup assigned helper
 * User: moyo
 * Date: 2018/5/29
 * Time: 11:18 AM
 */

namespace Carno\Web\Chips\Router;

use Carno\Web\Contracts\Router\Methods;

trait STAHelper
{
    /**
     * @param array $methods
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function match(array $methods, string $uri, callable $processor) : self
    {
        foreach ($methods as $method) {
            $this->$method($uri, $processor);
        }
        return $this;
    }

    /**
     * @param string $uri
     * @param object $controller
     * @return static
     */
    public function rest(string $uri, object $controller) : self
    {
        foreach (Methods::RESTFUL as $method) {
            $this->$method($uri, [$controller, $method]);
        }
        return $this;
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function any(string $uri, callable $processor) : self
    {
        foreach (array_merge(Methods::META, Methods::RESTFUL) as $method) {
            $this->$method($uri, $processor);
        }
        return $this;
    }
}
