<?php
/**
 * Extensions API
 * User: moyo
 * Date: 2018/5/31
 * Time: 10:40 AM
 */

namespace Carno\Web\Contracts\Controller;

use Carno\HTTP\Standard\Response;
use Carno\Web\Controller\Based;
use Throwable;

interface Extensions
{
    /**
     * @param Based $session
     * @return Response|null
     */
    public function requesting(Based $session);

    /**
     * @param Based $session
     * @param mixed $result
     * @return Response|null
     */
    public function responding(Based $session, $result);

    /**
     * @param Based $session
     * @param Throwable $e
     * @return Response|null
     */
    public function exceptions(Based $session, Throwable $e);
}
