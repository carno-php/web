<?php
/**
 * HTTP server tracing
 * User: moyo
 * Date: 2018-11-26
 * Time: 14:34
 */

namespace Carno\Web\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;
use Carno\Traced\Contracts\Observer;
use Carno\Web\Handlers\InboundTracing;
use Carno\Web\Handlers\IngressWrapper;
use Carno\Web\Server;

class Tracing extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $dependencies = [Observer::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        /**
         * @var Observer $platform
         */

        $platform = DI::get(Observer::class);

        $platform->transportable(static function () {
            Server::layers()->has(InboundTracing::class)
            || Server::layers()->prepend(IngressWrapper::class, DI::object(InboundTracing::class));
        }, static function () {
            Server::layers()->remove(InboundTracing::class);
        });
    }
}
