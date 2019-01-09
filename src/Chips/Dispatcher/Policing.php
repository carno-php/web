<?php
/**
 * Policing manager
 * User: moyo
 * Date: 2019-01-08
 * Time: 19:20
 */

namespace Carno\Web\Chips\Dispatcher;

use Carno\Web\Policy\Inspector;

trait Policing
{
    /**
     * @var Inspector
     */
    private $policy = null;

    /**
     * @param Inspector $policy
     */
    public function policing(Inspector $policy) : void
    {
        $this->policy = $policy;
    }
}
