<?php
/**
 * Setup assigned methods
 * User: moyo
 * Date: 2018/5/29
 * Time: 10:53 AM
 */

namespace Carno\Web\Chips\Router;

trait STAMethods
{
    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function options(string $uri, callable $processor) : self
    {
        return $this->add('options', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function head(string $uri, callable $processor) : self
    {
        return $this->add('head', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function get(string $uri, callable $processor) : self
    {
        return $this->add('get', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function post(string $uri, callable $processor) : self
    {
        return $this->add('post', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function put(string $uri, callable $processor) : self
    {
        return $this->add('put', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function patch(string $uri, callable $processor) : self
    {
        return $this->add('patch', $uri, $processor);
    }

    /**
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    public function delete(string $uri, callable $processor) : self
    {
        return $this->add('delete', $uri, $processor);
    }
}
