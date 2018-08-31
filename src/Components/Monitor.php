<?php
/**
 * HTTP server stats reporting
 * User: moyo
 * Date: 2018/6/15
 * Time: 12:26 PM
 */

namespace Carno\Web\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Monitor\Metrics;
use Carno\Monitor\Ticker;
use Carno\Web\Controller\Stats;

class Monitor extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Metrics::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        $app->starting()->add(static function () {
            Ticker::new([
                Metrics::gauge()->named('web.ctrl.working'),
            ], static function (Metrics\Gauge $wc) {
                $wc->set(Stats::working());
            });
        });
    }
}
