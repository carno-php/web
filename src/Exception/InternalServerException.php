<?php
/**
 * Internal server error
 * User: moyo
 * Date: 2018/5/29
 * Time: 3:56 PM
 */

namespace Carno\Web\Exception;

class InternalServerException extends HTTPException
{
    protected $code = 500;
}
