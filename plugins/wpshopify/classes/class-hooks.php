<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Hooks
{
    public $plugin_settings;
    public $DB_Settings_Syncing;

    public function __construct($plugin_settings, $DB_Settings_Syncing)
    {
        $this->plugin_settings = $plugin_settings;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function wps_syncing_settings_timeout()
    {
        if ($this->plugin_settings['general']['synchronous_sync']) {
            return 99999;
        }

        return 0.01;
    }

    public function wps_syncing_settings_blocking()
    {
        if ($this->plugin_settings['general']['synchronous_sync']) {
            return true;
        }

        return false;
    }

    /*
   
   Runs immediately after plugin updates within page reloads.
   
   */
    public function after_upgrader_process_complete($updater, $options)
    {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (!isset($options['plugins']) || empty($options['plugins'])) {
                return false;
            }

            foreach ($options['plugins'] as $each_plugin) {

                /*
                
                Our SHOPWP_BASENAME constant isn't defined at this point so we need to hardcode. Annoying!s

                Update 11/17/2021: Not sure if this is still needed
                
                */
                if (
                    $each_plugin === 'wp-shopify-pro/wp-shopify.php' ||
                    $each_plugin === 'wpshopify/wp-shopify.php' ||
                    $each_plugin === 'shopwp-pro/shopwp.php' ||
                    $each_plugin === 'shopwp/shopwp.php' ||
                    $each_plugin === 'wpshopify/shopwp.php'
                ) {
                    return $this->DB_Settings_Syncing->expire_sync();
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    public function init()
    {
        add_action(
            'upgrader_process_complete',
            [$this, 'after_upgrader_process_complete'],
            10,
            2
        );

        add_filter('wps_syncing_settings_timeout', [
            $this,
            'wps_syncing_settings_timeout',
        ]);
        add_filter('wps_syncing_settings_blocking', [
            $this,
            'wps_syncing_settings_blocking',
        ]);
    }
}
