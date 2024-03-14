<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Factories;
use ShopWP\Utils;

class Bootstrap
{
    public function __construct()
    {
    }

    public function initialize()
    {
        $is_admin_page = is_admin();

        $results = [];

        Factories\Config_Factory::build()->init();
        Factories\Activator_Factory::build()->init();

        if ($is_admin_page) {
            if (!is_plugin_active(SHOPWP_BASENAME)) {
                return;
            }
        }

        // Plugin settings available here. Activator responsible for creating tables.
        $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();

        // The init action fires after plugins_loaded
        add_action('init', function () use ($plugin_settings, $results) {
            $this->build_plugin($plugin_settings, $results);
        });

        add_action('admin_init', [$this, 'maybe_flush_rewrite_rules']);
    }

    public function maybe_flush_rewrite_rules() {

      if (!apply_filters('shopwp_flush_permalinks_after_sync', true)) {
         return;
      }

      if (get_option('shopwp_should_flush_rewrite_rules')) {
         flush_rewrite_rules();
         update_option('shopwp_should_flush_rewrite_rules', 0);
      }

    }

    public function build_plugin($plugin_settings, $results)
    {

        $results['Deactivator'] = Factories\Deactivator_Factory::build(
            $plugin_settings
        );

        $results['Backend'] = Factories\Backend_Factory::build(
            $plugin_settings
        );

        $results['Admin_Menus'] = Factories\Admin_Menus_Factory::build(
            $plugin_settings
        );

        $results['Hooks'] = Factories\Hooks_Factory::build($plugin_settings);


        $results['Templates'] = Factories\Templates_Factory::build(
            $plugin_settings
        );

        $results['Shortcodes'] = Factories\Shortcodes\Shortcodes_Factory::build(
            $plugin_settings
        );

        $results['CPT'] = Factories\CPT_Factory::build($plugin_settings);

        $results['I18N'] = Factories\I18N_Factory::build($plugin_settings);
        
        $results[
            'API_Items_Products_Factory'
        ] = Factories\API\Items\Products_Factory::build($plugin_settings);

        $results[
            'API_Items_Orders_Factory'
        ] = Factories\API\Items\Orders_Factory::build($plugin_settings);

        $results[
            'API_Items_Shop_Factory'
        ] = Factories\API\Items\Shop_Factory::build($plugin_settings);

        $results['Frontend'] = Factories\Frontend_Factory::build(
            $plugin_settings
        );

        $results[
            'API_Options_Components_Factory'
        ] = Factories\API\Options\Components_Factory::build($plugin_settings);

        $results[
            'API_Items_Cart_Factory'
        ] = Factories\API\Items\Cart_Factory::build($plugin_settings);


        $results[
            'API_Settings_Collections_Factory'
        ] = Factories\API\Settings\Collections_Factory::build($plugin_settings);

        $results[
            'API_Settings_License_Factory'
        ] = Factories\API\Settings\License_Factory::build($plugin_settings);
        $results[
            'API_Settings_General_Factory'
        ] = Factories\API\Settings\General_Factory::build($plugin_settings);
        $results[
            'API_Settings_Connection_Factory'
        ] = Factories\API\Settings\Connection_Factory::build($plugin_settings);
        $results[
            'API_Status_Factory'
        ] = Factories\API\Syncing\Status_Factory::build($plugin_settings);
        $results[
            'API_Indicator_Factory'
        ] = Factories\API\Syncing\Indicator_Factory::build($plugin_settings);
        $results[
            'API_Counts_Factory'
        ] = Factories\API\Syncing\Counts_Factory::build($plugin_settings);

        $results[
            'API_Items_Collections_Factory'
        ] = Factories\API\Items\Collections_Factory::build($plugin_settings);
        $results[
            'API_Misc_Notices_Factory'
        ] = Factories\API\Misc\Notices_Factory::build($plugin_settings);
        $results[
            'API_Tools_Cache_Factory'
        ] = Factories\API\Tools\Cache_Factory::build($plugin_settings);
        $results[
            'API_Tools_Clear_Factory'
        ] = Factories\API\Tools\Clear_Factory::build($plugin_settings);

        return $this->init_classes($results);
    }

    public function init_classes($classes)
    {
        $results = [];

        foreach ($classes as $class_name => $class) {
            if (method_exists($class, 'init')) {
                if ($class_name === 'Activator') {
                    $results[$class_name] = true;
                } else {
                    $results[$class_name] = $class->init();
                }
            }
        }

        do_action('shopwp_is_ready');

        return $results;
    }
}
