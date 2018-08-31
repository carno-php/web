<?php
/**
 * Controller stats
 * User: moyo
 * Date: 2018/6/15
 * Time: 12:20 PM
 */

namespace Carno\Web\Controller;

class Stats
{
    /**
     * @var int
     */
    private static $working = 0;

    /**
     * @return int
     */
    public static function working() : int
    {
        return self::$working;
    }

    /**
     */
    public static function started() : void
    {
        self::$working ++;
    }

    /**
     */
    public static function finished() : void
    {
        self::$working --;
    }
}
