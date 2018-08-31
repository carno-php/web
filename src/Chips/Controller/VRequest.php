<?php
/**
 * Request related vars
 * User: moyo
 * Date: 2018/5/29
 * Time: 11:06 PM
 */

namespace Carno\Web\Chips\Controller;

use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Controller\VGetter;

trait VRequest
{
    use VMixed;

    /**
     * @var VGetter
     */
    private $vgg = null;

    /**
     * @var VGetter
     */
    private $vgp = null;

    /**
     * @var VGetter
     */
    private $vgc = null;

    /**
     * @return ServerRequest
     */
    private function sr() : ServerRequest
    {
        return $this->server;
    }

    /**
     * @return VGetter
     */
    public function get() : VGetter
    {
        return $this->vgg ?? $this->vgg = new VGetter($this->sr()->getQueryParams());
    }

    /**
     * @return VGetter
     */
    public function post() : VGetter
    {
        return $this->vgp ?? $this->vgp = new VGetter($this->sr()->getParsedBody());
    }

    /**
     * @return VGetter
     */
    public function cookies() : VGetter
    {
        return $this->vgc ?? $this->vgc = new VGetter($this->sr()->getCookieParams());
    }

    /**
     * @return string
     */
    public function payload() : string
    {
        return (string) $this->sr()->getBody();
    }
}
