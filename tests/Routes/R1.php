<?php
/**
 * Route test rule 1
 * User: moyo
 * Date: 2018-11-26
 * Time: 10:41
 */

namespace Carno\Web\Tests\Routes;

use Carno\Web\Router\Configure;
use Carno\Web\Router\Setup;
use Carno\Web\Tests\Controllers\Restful;

class R1 extends Configure
{
    protected function case1(Setup $setup)
    {
        $setup->get('/url/get', function () {
            //
        });

        $setup->any('/url/any', function () {
            //
        });

        $setup->match(['get', 'post'], '/url/get+post', function () {
            //
        });

        $setup->rest('/url/rest', new Restful);
    }

    protected function case2(Setup $setup)
    {
        $g = $setup->group('/prefix');

        $g->get('/get', function () {
            //
        });

        $g->match(['get', 'post'], '/get+post', function () {
            //
        });
    }

    protected function case3(Setup $setup)
    {
        $setup->get('/w-opts-1/{name}/{id:\d+}', function () {
            //
        });

        $setup->post('/w-opts-2/{name:\w+}[/{id:\d+}]', function () {
            //
        });
    }
}
