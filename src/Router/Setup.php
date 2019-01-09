<?php
/**
 * Router setup
 * User: moyo
 * Date: 2018/5/28
 * Time: 10:05 PM
 */

namespace Carno\Web\Router;

use Carno\Web\Chips\Router\STAssigns;
use Carno\Web\Chips\Router\STCors;
use Carno\Web\Chips\Router\STGroup;
use Carno\Web\Policy\Inspector;

class Setup
{
    use STGroup, STCors, STAssigns;

    /**
     * @var Records
     */
    private $records = null;

    /**
     * @var Inspector
     */
    private $policy = null;

    /**
     * Setup constructor.
     * @param Records $records
     * @param Inspector $policy
     */
    public function __construct(Records $records, Inspector $policy)
    {
        $this->records = $records;
        $this->policy = $policy;
    }
}
