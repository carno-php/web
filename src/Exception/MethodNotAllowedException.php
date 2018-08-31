<?php
/**
 * Method now allowed
 * User: moyo
 * Date: 2018/5/29
 * Time: 3:51 PM
 */

namespace Carno\Web\Exception;

class MethodNotAllowedException extends HTTPException
{
    protected $code = 405;
}
