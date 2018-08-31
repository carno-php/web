<?php
/**
 * HTTP server routing
 * User: moyo
 * Date: 2018/5/29
 * Time: 12:03 PM
 */

namespace Carno\Web\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;
use Carno\Web\Dispatcher;
use Carno\Web\Router\Initializer;
use Carno\Web\Router\Records;

class Routing extends Component implements Bootable
{
    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        /**
         * @var Records $rec
         * @var Dispatcher $dsp
         */

        DI::set(Records::class, $rec = DI::object(Records::class));
        DI::set(Dispatcher::class, $dsp = DI::object(Dispatcher::class));

        // starting works

        $app->starting()->add(static function () use ($rec, $dsp) {
            // routes parsing
            if (defined('CWD') && is_file($rf = CWD . '/routes.php')) {
                (new Initializer)->loads(...(array) include $rf);
            }
            // dispatcher init
            $dsp->dispatched($rec->dispatcher());
        });
    }
}
