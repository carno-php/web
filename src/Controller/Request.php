<?php
/**
 * Controller request
 * User: moyo
 * Date: 2018/5/29
 * Time: 11:15 PM
 */

namespace Carno\Web\Controller;

use Carno\HTTP\Standard\ServerRequest;
use Carno\Web\Chips\Controller\VParams;
use Carno\Web\Chips\Controller\VRequest;

class Request
{
    use VRequest, VParams;

    /**
     * @var ServerRequest
     */
    private $server = null;

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var array
     */
    private $params = [];

    /**
     * Request constructor.
     * @param ServerRequest $server
     * @param string $action
     * @param array $params
     */
    public function __construct(ServerRequest $server, string $action, array $params)
    {
        $this->server = $server;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @deprecated
     * @return ServerRequest
     */
    public function ingress() : ServerRequest
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function action() : string
    {
        return $this->action;
    }
}
