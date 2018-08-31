<?php
/**
 * Setup assigns
 * User: moyo
 * Date: 2018/5/29
 * Time: 10:49 AM
 */

namespace Carno\Web\Chips\Router;

use Carno\Web\Router\Records;

trait STAssigns
{
    use STAMethods, STAHelper;

    /**
     * @param string $method
     * @param string $uri
     * @param callable $processor
     * @return static
     */
    protected function add(string $method, string $uri, callable $processor) : self
    {
        /**
         * @var Records $r
         */

        $r = $this->records;

        $r->accept(strtoupper($method), $this->prefix . $uri, $processor);

        return $this;
    }
}
