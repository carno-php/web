<?php
/**
 * Controller runtime
 * User: moyo
 * Date: 2018/5/29
 * Time: 5:04 PM
 */

namespace Carno\Web\Chips\Controller;

use Carno\Coroutine\Context;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\HTTP\Standard\Streams\Body;
use Carno\Web\Controller\Remote;
use Carno\Web\Controller\Request;
use Carno\Web\Controller\Stats;

trait Runtime
{
    use Commands;

    /**
     * @var Context|null
     */
    private $context = null;

    /**
     * @var ServerRequest|null
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
     * @var Remote|null
     */
    private $remote = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var Response|null
     */
    private $response = null;

    /**
     * @param Context $context
     * @param ServerRequest $server
     * @param string $action
     * @param array $params
     * @return static
     */
    final public function initialize(Context $context, ServerRequest $server, string $action, array $params) : self
    {
        $this->context = $context;
        $this->server = $server;
        $this->action = $action;
        $this->params = $params;

        Stats::started();

        return $this;
    }

    /**
     * @return Context
     */
    final public function ctx() : Context
    {
        return $this->context;
    }

    /**
     * @return ServerRequest
     */
    final public function ingress() : ServerRequest
    {
        return $this->server;
    }

    /**
     * @return Remote
     */
    final public function remote() : Remote
    {
        return $this->remote ?? $this->remote = new Remote($this->context, $this->server);
    }

    /**
     * @return Request
     */
    final public function request() : Request
    {
        return $this->request ?? $this->request = new Request($this->server, $this->action, $this->params);
    }

    /**
     * @param string $data
     * @return Response
     */
    final public function response(string $data = null) : Response
    {
        return
            $this->response
                ? $this->response = (is_null($data) ? $this->response : $this->response->withBody(new Body($data)))
                : $this->response = new Response(200, [], $data)
            ;
    }

    /**
     */
    final public function __clone()
    {
        $this->context = null;
        $this->server = null;
        $this->action = '';
        $this->params = [];

        $this->request = null;
        $this->response = null;
    }

    /**
     */
    final public function __destruct()
    {
        Stats::finished();
    }
}
