<?php
/**
 * Controller remote
 * User: moyo
 * Date: 2018-11-26
 * Time: 11:31
 */

namespace Carno\Web\Controller;

use Carno\Coroutine\Context;
use Carno\HTTP\Server\Connection;
use Carno\HTTP\Standard\ServerRequest;
use Carno\Net\Address;
use Carno\Web\Handlers\IngressWrapper;

class Remote
{
    private const XFF = 'X-Forwarded-For';

    /**
     * @var Context
     */
    private $context = null;

    /**
     * @var ServerRequest
     */
    private $request = null;

    /**
     * Remote constructor.
     * @param Context $context
     * @param ServerRequest $request
     */
    public function __construct(Context $context, ServerRequest $request)
    {
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * @return Address
     */
    public function peer() : Address
    {
        /**
         * @var Connection|null $conn
         */

        $conn = $this->context->get(IngressWrapper::CONNECTION);

        $client = $conn ? $conn->remote() : new Address('0.0.0.0', 0);

        if (empty($ips = $this->request->getHeader(self::XFF))) {
            return $client;
        }

        return new Address(reset($ips), $client->port());
    }
}
