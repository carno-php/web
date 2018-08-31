<?php
/**
 * Router initializer
 * User: moyo
 * Date: 2018/5/29
 * Time: 12:18 PM
 */

namespace Carno\Web\Router;

use Carno\Container\DI;
use Carno\Web\Contracts\RConfiguration;

class Initializer
{
    /**
     * @param string ...$configures
     */
    public function loads(string ...$configures) : void
    {
        foreach ($configures as $configure) {
            if (($c = DI::object($configure)) && $c instanceof RConfiguration) {
                $c->process();
            }
        }
    }
}
