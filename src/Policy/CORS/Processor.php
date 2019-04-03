<?php
/**
 * CORS processor
 * User: moyo
 * Date: 2019-01-08
 * Time: 15:22
 */

namespace Carno\Web\Policy\CORS;

use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;

class Processor
{
    /**
     * @var string
     */
    private $origin = '*';

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $exposes = [];

    /**
     * @var bool
     */
    private $credentials = false;

    /**
     * @var int
     */
    private $expired = 0;

    /**
     * CORS constructor.
     * @param string $origin
     */
    public function __construct(string $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @param string ...$methods
     * @return static
     */
    public function methods(string ...$methods) : self
    {
        $this->methods = array_map('strtoupper', $methods);
        return $this;
    }

    /**
     * @param string ...$headers
     * @return static
     */
    public function headers(string ...$headers) : self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string ...$headers
     * @return static
     */
    public function exposes(string ...$headers) : self
    {
        $this->exposes = $headers;
        return $this;
    }

    /**
     * @param bool $yes
     * @return static
     */
    public function credentials(bool $yes) : self
    {
        $this->credentials = $yes;
        return $this;
    }

    /**
     * @param int $seconds
     * @return static
     */
    public function expired(int $seconds) : self
    {
        $this->expired = $seconds;
        return $this;
    }

    /**
     * @param ServerRequest $sr
     * @param Response $respond
     * @return Response
     */
    final public function process(ServerRequest $sr, Response $respond) : Response
    {
        if ($this->origin === '*' && $this->credentials) {
            $origin = $sr->getHeaderLine('Origin') ?: '*';
        }

        $respond->withHeader('Access-Control-Allow-Origin', $origin ?? $this->origin);

        $this->methods && $respond->withHeader('Access-Control-Allow-Methods', $this->methods);
        $this->headers && $respond->withHeader('Access-Control-Allow-Headers', $this->headers);
        $this->exposes && $respond->withHeader('Access-Control-Expose-Headers', $this->exposes);

        $this->credentials && $respond->withHeader('Access-Control-Allow-Credentials', 'true');
        $this->expired > 0 && $respond->withHeader('Access-Control-Max-Age', $this->expired);

        return $respond;
    }
}
