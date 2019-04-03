<?php
/**
 * Setup CORS
 * User: moyo
 * Date: 2019-01-08
 * Time: 13:54
 */

namespace Carno\Web\Chips\Router;

use Carno\Web\Policy\CORS\Handler;
use Carno\Web\Policy\CORS\Processor;
use Carno\Web\Policy\Inspector;

trait STCors
{
    /**
     * @param string $prefix
     * @param string $origin
     * @return Processor
     */
    public function cors(string $prefix, string $origin = '*') : Processor
    {
        /**
         * @var Inspector $p
         */

        $p = $this->policy;

        $p->join(new Handler($prefix, $r = new Processor($origin)));

        return $r;
    }
}
