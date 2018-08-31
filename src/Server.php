<?php
/**
 * Web server instance
 * User: moyo
 * Date: 2018/5/29
 * Time: 2:33 PM
 */

namespace Carno\Web;

use Carno\HTTP\Server as HTServer;
use Carno\Net\Address;
use Carno\Net\Contracts\Conn;
use Carno\Net\Events as NETEvs;
use Carno\Serving\Chips\Events;
use Carno\Serving\Chips\Plugins;
use Carno\Serving\Chips\Wants;
use Carno\Web\Chips\Chains;

class Server
{
    use Chains, Events, Wants, Plugins;

    /**
     * @var string
     */
    private $name = null;

    /**
     * @var Address
     */
    private $listen = null;

    /**
     * HTTP constructor.
     * @param string $name
     * @param Address $listen
     */
    public function __construct(string $name, Address $listen)
    {
        $this->name = $name;
        $this->listen = $listen;

        $this->events()->attach(NETEvs\Worker::STARTED, function (Conn $ctx) {
            $ctx->events()->attach(NETEvs\HTTP::REQUESTING, self::layers()->handler());
        });
    }

    /**
     * @param int $workers
     */
    public function run(int $workers) : void
    {
        HTServer::listen($this->listen, $this->events(), $workers, $this->name)->serve();
    }
}
