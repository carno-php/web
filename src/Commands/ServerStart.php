<?php
/**
 * Web server -> start
 * User: moyo
 * Date: 12/12/2017
 * Time: 12:45 PM
 */

namespace Carno\Web\Commands;

use Carno\Console\Based;
use Carno\Console\Contracts\Application;
use Carno\Net\Address;
use Carno\Serving\Chips\HWIGet;
use Carno\Serving\Contracts\Options;
use Carno\Serving\Options as Opt;
use Carno\Serving\Plugins\LiveReloading;
use Carno\Serving\Plugins\MetricsExporter;
use Carno\Serving\Plugins\ServerMonitor;
use Carno\Web\Components\Serving;
use Carno\Web\Components\Monitor;
use Carno\Web\Components\Routing;
use Carno\Web\Server;

class ServerStart extends Based
{
    use HWIGet;
    use Opt\Common, Opt\Metrics, Opt\Discovery, Opt\Listener;

    /**
     * @var string
     */
    protected $name = 'server:start';

    /**
     * @var string
     */
    protected $description = 'Start the HTTP server';

    /**
     * @var array
     */
    protected $components = [
        Routing::class,
        Serving::class,
        Monitor::class,
    ];

    /**
     * @var bool
     */
    protected $ready = false;

    /**
     * @param Application $app
     */
    protected function firing(Application $app) : void
    {
        (new Server(
            $app->name(),
            new Address($app->input()->getOption(Options::LISTEN))
        ))
            ->plugins(
                new LiveReloading,
                new ServerMonitor,
                new MetricsExporter
            )
            ->wants($app->starting(), $app->stopping())
            ->run($app->input()->getOption(Options::WORKERS) ?: $this->numCPUs())
        ;
    }
}
