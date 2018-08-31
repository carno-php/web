<?php
/**
 * Setup group
 * User: moyo
 * Date: 2018/5/29
 * Time: 10:43 AM
 */

namespace Carno\Web\Chips\Router;

trait STGroup
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var string
     */
    private $group = 'default';

    /**
     * @param string $prefix
     * @param string|null $name
     * @return static
     */
    public function group(string $prefix, string $name = null) : self
    {
        $setup = clone $this;

        $setup->prefix = rtrim($prefix, '/');
        $setup->group = $name ?? $this->group;

        return $setup;
    }
}
