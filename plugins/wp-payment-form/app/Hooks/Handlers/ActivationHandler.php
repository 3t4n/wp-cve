<?php

namespace WPPayForm\App\Hooks\Handlers;

use WPPayForm\Database\DBMigrator;

class ActivationHandler
{
    public function handle($network_wide = false)
    {
        DBMigrator::run($network_wide);

        $this->setPluginInstallTime();
    }

    public function setPluginInstallTime()
    {
        $statuses = get_option( 'wppayform_statuses', []);
        if( !isset($statuses['installed_time']) ){
            $statuses['installed_time'] = strtotime("now") ;
            update_option('wppayform_statuses', $statuses, false);
        }
    }
}
