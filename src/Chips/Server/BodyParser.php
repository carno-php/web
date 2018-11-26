<?php
/**
 * Server request body parser
 * User: moyo
 * Date: 2018/6/11
 * Time: 11:41 AM
 */

namespace Carno\Web\Chips\Server;

use Psr\Http\Message\ServerRequestInterface;

trait BodyParser
{
    /**
     * @param ServerRequestInterface $srq
     */
    protected function parsedBody(ServerRequestInterface $srq) : void
    {
        $srm = $srq->getMethod();

        if ($srm === 'GET' || $srm === 'HEAD' || $srm === 'OPTIONS') {
            return;
        }

        switch ($srq->getHeaderLine('Content-Type')) {
            case 'application/json':
                $srq->withParsedBody(json_decode((string)$srq->getBody(), true));
                break;
            case 'application/x-www-form-urlencoded':
                parse_str((string)$srq->getBody(), $posted);
                $srq->withParsedBody($posted);
                break;
        }
    }
}
