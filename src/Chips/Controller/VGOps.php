<?php
/**
 * VGetter operates
 * User: moyo
 * Date: 2018/6/7
 * Time: 1:56 PM
 */

namespace Carno\Web\Chips\Controller;

trait VGOps
{
    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return $this->offsetExists($name);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->$name ?? $default;
    }
}
