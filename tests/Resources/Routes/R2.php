<?php
/**
 * Route test rule 2
 * User: moyo
 * Date: 2019-01-08
 * Time: 10:36
 */

namespace Carno\Web\Tests\Resources\Routes;

use Carno\Web\Router\Configure;
use Carno\Web\Router\Setup;

class R2 extends Configure
{
    protected function normal(Setup $setup)
    {
        $setup->get('/cors1/get', function () {
            //
        });

        $setup->get('/cors2/get', function () {
            //
        });
    }

    protected function cors(Setup $setup)
    {
        $setup->cors('/cors1')
            ->methods('get')
        ;

        $setup->cors('/cors2')
            ->methods('get')
            ->credentials(true)
        ;

        $setup->cors('/cors3', 'http://localhost')
            ->methods('get', 'post')
            ->headers('header1', 'header2')
        ;

        $setup->cors('/cors4')
            ->exposes('expose1', 'expose2')
            ->expired(3600)
        ;
    }
}
