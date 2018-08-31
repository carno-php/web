<?php
/**
 * Setup executor
 * User: moyo
 * Date: 2018/5/29
 * Time: 2:14 PM
 */

namespace Carno\Web\Chips\Router;

use Carno\Web\Router\Setup;
use ReflectionClass;
use ReflectionMethod;

trait SUExecutor
{
    /**
     * @return array
     */
    protected function setups() : array
    {
        $methods = [];

        foreach ((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PROTECTED) as $method) {
            foreach ($method->getParameters() as $parameter) {
                if ($required = $parameter->getClass()) {
                    if ($required->getName() === Setup::class) {
                        $methods[] = $method->getName();
                    }
                }
            }
        }

        return $methods;
    }
}
