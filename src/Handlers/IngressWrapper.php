<?php
/**
 * Controller invoker
 * User: moyo
 * Date: 2018/5/29
 * Time: 12:15 PM
 */

namespace Carno\Web\Handlers;

use Carno\Chain\Layered;
use function Carno\Coroutine\async;
use Carno\Coroutine\Context;
use function Carno\Coroutine\race;
use function Carno\Coroutine\timeout;
use Carno\HTTP\Server\Connection;
use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Promise\Promised;
use Carno\Web\Chips\Server\BodyParser;
use Carno\Web\Dispatcher;
use Carno\Web\Exception\HTTPException;
use Carno\Web\Options;
use Throwable;

class IngressWrapper implements Layered
{
    use BodyParser;

    /**
     * operated connection
     */
    public const CONNECTION = 'http-session';

    /**
     * request/response for connection
     */
    public const REQUESTING = 'http-request';
    public const RESPONDING = 'http-response';

    /**
     * @inject
     * @var Dispatcher
     */
    private $dispatcher = null;

    /**
     * @var Options
     */
    private $options = null;

    /**
     * IngressWrapper constructor.
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @param Connection $ingress
     * @param Context $ctx
     * @return Promised
     */
    public function inbound($ingress, Context $ctx) : Promised
    {
        $ctx->set(self::CONNECTION, $ingress);
        $ctx->set(self::REQUESTING, $request = $ingress->request());

        $this->parsedBody($request);

        return race(async(function (ServerRequest $sr) {
            return $this->dispatcher->invoke($sr);
        }, $ctx, $request), timeout($this->options->ttExec));
    }

    /**
     * @param Response $response
     * @param Context $ctx
     * @return Response
     */
    public function outbound($response, Context $ctx) : Response
    {
        $ctx->set(self::RESPONDING, $response);

        return $response;
    }

    /**
     * @param Throwable $e
     * @param Context $ctx
     */
    public function exception(Throwable $e, Context $ctx) : void
    {
        if ($e instanceof HTTPException) {
            $response = new Response($e->getCode(), [], $e->getMessage());
        } else {
            $response = new Response(500, [], get_class($e));
        }

        $this->outbound($response, $ctx);

        throw $e;
    }
}
