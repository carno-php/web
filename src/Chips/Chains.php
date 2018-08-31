<?php
/**
 * Handler chains
 * User: moyo
 * Date: 2018/8/29
 * Time: 4:54 PM
 */

namespace Carno\Web\Chips;

use Carno\Chain\Layers;
use Carno\Container\DI;

trait Chains
{
    /**
     * @return Layers
     */
    final public static function layers() : Layers
    {
        return DI::has(self::class) ? DI::get(self::class) : DI::set(self::class, new Layers);
    }
}
