<?php
/**
 * HTTP server init
 * User: moyo
 * Date: 2018/5/29
 * Time: 2:45 PM
 */

namespace Carno\Web\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;
use Carno\Monitor\Daemon;
use Carno\Web\Handlers\AccessLogger;
use Carno\Web\Handlers\ExceptionReview;
use Carno\Web\Handlers\IngressReplier;
use Carno\Web\Handlers\IngressWrapper;
use Carno\Web\Handlers\TrafficMonitor;
use Carno\Web\Server;

class Serving extends Component implements Bootable
{
    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        // controller invoker
        Server::layers()->append(
            null,
            DI::object(AccessLogger::class),
            DI::object(IngressReplier::class),
            DI::object(IngressWrapper::class)
        );

        // exception dumps
        Server::layers()->prepend(null, DI::object(ExceptionReview::class));

        // starting wait
        $app->starting()->add(static function () {
            // add traffic monitor layer
            DI::has(Daemon::class) && Server::layers()->prepend(
                IngressWrapper::class,
                DI::object(TrafficMonitor::class)
            );
        });
    }
}
