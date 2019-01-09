<?php
/**
 * Setup CORS
 * User: moyo
 * Date: 2019-01-08
 * Time: 13:54
 */

namespace Carno\Web\Chips\Router;

use Carno\Web\Policy\CORS as Policy;
use Carno\Web\Policy\Inspector;
use Carno\Web\Policy\Rules\CORS as Rule;

trait STCors
{
    /**
     * @param string $prefix
     * @param string $origin
     * @return Rule
     */
    public function cors(string $prefix, string $origin = '*') : Rule
    {
        /**
         * @var Inspector $p
         */

        $p = $this->policy;

        $p->join(new Policy($prefix, $r = new Rule($origin)));

        return $r;
    }
}
