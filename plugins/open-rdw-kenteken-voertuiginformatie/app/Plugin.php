<?php

namespace Tussendoor\OpenRDW;

use  Tussendoor\OpenRDW\Includes\VehiclePlateInformation;

class Plugin
{
    public $dot_config;
    public function __construct()
    {
        global $dot_config;
        $this->dot_config = $dot_config;
    }

    /**
     * echoing message to prevent activate plugin when pro plugin is active
     */
    public function prevent_to_activate_pro_exists()
    {
        // Check if the pro plugin is active.
        if (is_plugin_active($this->dot_config['plugin.pro_folder'])) {
            echo __('The free plugin cannot be activated because the pro plugin is already active. Please deactive it first', 'open-rdw-kenteken-voertuiginformatie');
            exit;
        }
    }

    /**
     * Deletes our saved admin notice so the user gets another notice for donation! :)
     *
     * @since    2.0.0
     */
    public function deactivate()
    {
        delete_option('open-rdw-notice-dismissed');
    }


    public function redirect_after_activation($plugin)
    {
        if ($plugin == $this->dot_config['plugin.basename']) {
            exit(wp_safe_redirect(admin_url('admin.php?page=open_data_rdw&tab=getting-started')));
        }
    }

    public function boot()
    {
        $base = new VehiclePlateInformation();
        $base->run();
    }
}
