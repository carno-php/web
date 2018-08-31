<?php
/**
 * Request mixed vars (merged from "get" "post" "cookies")
 * User: moyo
 * Date: 2018/6/11
 * Time: 11:23 AM
 */

namespace Carno\Web\Chips\Controller;

use Carno\Web\Controller\VGetter;

trait VMixed
{
    /**
     * @var VGetter
     */
    private $vgm = null;

    /**
     * @return VGetter
     */
    public function mixed() : VGetter
    {
        return $this->vgm ?? $this->vgm = new VGetter(array_merge(
            (array) $this->get(),
            (array) $this->post(),
            (array) $this->cookies()
        ));
    }
}
