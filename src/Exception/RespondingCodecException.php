<?php
/**
 * Responding codec error
 * User: moyo
 * Date: 2018/5/30
 * Time: 10:31 AM
 */

namespace Carno\Web\Exception;

class RespondingCodecException extends HTTPException
{
    protected $code = 500;
}
