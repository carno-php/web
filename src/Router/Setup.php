<?php
/**
 * Router setup
 * User: moyo
 * Date: 2018/5/28
 * Time: 10:05 PM
 */

namespace Carno\Web\Router;

use Carno\Web\Chips\Router\STAssigns;
use Carno\Web\Chips\Router\STGroup;

class Setup
{
    use STGroup, STAssigns;

    /**
     * @var Records
     */
    private $records = null;

    /**
     * Setup constructor.
     * @param Records $records
     */
    public function __construct(Records $records)
    {
        $this->records = $records;
    }
}
