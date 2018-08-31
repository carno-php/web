<?php
/**
 * Router params
 * User: moyo
 * Date: 2018/5/29
 * Time: 11:05 PM
 */

namespace Carno\Web\Chips\Controller;

use Carno\Web\Controller\VGetter;

trait VParams
{
    /**
     * @var VGetter
     */
    private $vpg = null;

    /**
     * @return VGetter
     */
    public function params() : VGetter
    {
        return $this->vpg ?? $this->vpg = new VGetter($this->params);
    }
}
