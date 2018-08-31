<?php
/**
 * Controller commands
 * User: moyo
 * Date: 2018/6/11
 * Time: 10:14 AM
 */

namespace Carno\Web\Chips\Controller;

use Carno\HTTP\Standard\Response;

trait Commands
{
    /**
     * @param string $target
     * @param bool $permanently
     * @return Response
     */
    final public function redirect(string $target, bool $permanently = false) : Response
    {
        return $this->response = new Response($permanently ? 301 : 302, ['Location' => $target]);
    }
}
