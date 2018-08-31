<?php
/**
 * Request not found
 * User: moyo
 * Date: 2018/5/29
 * Time: 3:48 PM
 */

namespace Carno\Web\Exception;

class RouterNotFoundException extends HTTPException
{
    protected $code = 404;
}
