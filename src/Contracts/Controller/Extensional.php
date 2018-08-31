<?php
/**
 * Controller extensional
 * User: moyo
 * Date: 2018/5/31
 * Time: 10:34 AM
 */

namespace Carno\Web\Contracts\Controller;

interface Extensional
{
    /**
     * @return Extensions
     */
    public function extension() : Extensions;
}
