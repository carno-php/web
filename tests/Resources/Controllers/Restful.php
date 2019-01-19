<?php
/**
 * Restful controller
 * User: moyo
 * Date: 2018-11-26
 * Time: 10:52
 */

namespace Carno\Web\Tests\Resources\Controllers;

use Carno\Web\Controller\Based;

class Restful extends Based
{
    public function post()
    {
        return 'post';
    }

    public function get()
    {
        return 'get';
    }

    public function put()
    {
        return 'put';
    }

    public function patch()
    {
        return 'patch';
    }

    public function delete()
    {
        return 'delete';
    }
}
