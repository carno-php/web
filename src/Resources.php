<?php
/**
 * Resources defined
 * User: moyo
 * Date: 2018/8/31
 * Time: 10:39 AM
 */

namespace Carno\Web;

use Carno\Web\Commands\ServerStart;

interface Resources
{
    public const APPS = [
        ServerStart::class,
    ];

    public const COMPONENTS = [
    ];
}
