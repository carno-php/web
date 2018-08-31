<?php
/**
 * Configuration base
 * User: moyo
 * Date: 2018/5/28
 * Time: 10:07 PM
 */

namespace Carno\Web\Router;

use Carno\Container\DI;
use Carno\Web\Chips\Router\SUExecutor;
use Carno\Web\Contracts\RConfiguration;

abstract class Configure implements RConfiguration
{
    use SUExecutor;

    /**
     */
    final public function process() : void
    {
        foreach ($this->setups() as $setup) {
            $this->$setup(DI::object(Setup::class));
        }
    }
}
