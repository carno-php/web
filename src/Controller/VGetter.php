<?php
/**
 * Vars getter
 * User: moyo
 * Date: 2018/5/29
 * Time: 10:54 PM
 */

namespace Carno\Web\Controller;

use Carno\Web\Chips\Controller\VGOps;
use Carno\Web\Chips\Controller\VGTypes;
use ArrayObject;

class VGetter extends ArrayObject
{
    use VGOps, VGTypes;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->offsetExists($name) ? $this->offsetGet($name) : null;
    }
}
