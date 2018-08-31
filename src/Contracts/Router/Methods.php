<?php
/**
 * Router methods
 * User: moyo
 * Date: 2018/6/11
 * Time: 10:43 AM
 */

namespace Carno\Web\Contracts\Router;

interface Methods
{
    public const META = ['options', 'head'];

    public const RESTFUL = ['post', 'get', 'put', 'patch', 'delete'];
}
