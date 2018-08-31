<?php
/**
 * Invalid controller handler
 * User: moyo
 * Date: 2018/5/30
 * Time: 5:52 PM
 */

namespace Carno\Web\Exception;

class InvalidControllerException extends HTTPException
{
    protected $code = 500;

    protected $message = 'Invalid controller';
}
