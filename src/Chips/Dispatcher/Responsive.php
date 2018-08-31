<?php
/**
 * Response parser
 * User: moyo
 * Date: 2018/5/30
 * Time: 9:58 AM
 */

namespace Carno\Web\Chips\Dispatcher;

use Carno\HTTP\Standard\Response;
use Carno\HTTP\Standard\ServerRequest;
use Carno\HTTP\Standard\Streams\Body;
use Carno\Web\Exception\RespondingCodecException;
use Google\Protobuf\Internal\Message;
use ArrayObject;

trait Responsive
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param mixed $result
     * @return Response
     */
    protected function responding(ServerRequest $request, Response $response, $result) : Response
    {
        switch (gettype($result)) {
            case 'array':
                if (($cc = $this->codec($request)) === 'json') {
                    return $this->ccJson(
                        $response,
                        json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    );
                }
                throw new RespondingCodecException('Unknown responding codec');
            case 'string':
                return $response->withBody(new Body($result));
            case 'object':
                if ($result instanceof Response) {
                    return $result;
                } elseif ($result instanceof Message) {
                    return $this->ccJson($response, $result->serializeToJsonString());
                } elseif ($result instanceof ArrayObject) {
                    return $this->ccJson(
                        $response,
                        json_encode((array)$result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    );
                } elseif (method_exists($result, '__toString')) {
                    return $response->withBody(new Body((string)$result));
                }
                throw new RespondingCodecException('Unacceptable object');
            case 'NULL':
                return $response;
            default:
                return $response->withBody(new Body((string)$result));
        }
    }

    /**
     * @param ServerRequest $request
     * @return string
     */
    private function codec(ServerRequest $request) : string
    {
        // TODO currently forced to "json"
        return 'json';
    }

    /**
     * @param Response $response
     * @param string $data
     * @return Response
     */
    private function ccJson(Response $response, string $data) : Response
    {
        $response = $response->withBody(new Body($data));
        $response->withHeader('Content-Type', 'application/json');
        return $response;
    }
}
