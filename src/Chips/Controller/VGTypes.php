<?php
/**
 * VGetter types
 * User: moyo
 * Date: 2018/5/31
 * Time: 2:38 PM
 */

namespace Carno\Web\Chips\Controller;

trait VGTypes
{
    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function string(string $name, string $default = '') : string
    {
        return (string) $this->$name ?? $default;
    }

    /**
     * @param string $name
     * @param int $default
     * @return int
     */
    public function integer(string $name, int $default = 0) : int
    {
        return (int) $this->$name ?? $default;
    }

    /**
     * @param string $name
     * @param bool $default
     * @return bool
     */
    public function boolean(string $name, bool $default = false) : bool
    {
        if (is_string($got = $this->$name)) {
            return filter_var($got, FILTER_VALIDATE_BOOLEAN);
        } else {
            return is_null($got) ? $default : (bool) $got;
        }
    }
}
