<?php

namespace Watchful\Helpers;

use Watchful\App\Alert;

class AppAlerts
{
    public function getAppAlerts() {
        /** @var Alert[] $alerts */
        $alerts = apply_filters( 'watchful_app_alerts', [] );
        return array_map(static function($alert) {
            return array(
                'message' => $alert->getMessage(),
                'level' => $alert->getLevel(),
                'parameter1' => $alert->getParameter1(),
                'parameter2' => $alert->getParameter2(),
                'parameter3' => $alert->getParameter3(),
            );
        }, $alerts);
    }
}
