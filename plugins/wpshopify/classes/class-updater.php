<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Updater
{
    public $plugin_settings;

    public function __construct($plugin_settings)
    {
        $this->plugin_settings = $plugin_settings;
    }

    public function check_for_updates($license_key, $params)
    {
        if (empty($license_key)) {
            return;
        }

        // No download id set inside the database, grab the constant instead
        if (empty($license_key['download_id'])) {
            if ($license_key['item_name'] === 'ShopWP' || $license_key['item_name'] === 'ShopWP Pro' || $license_key['item_name'] === 'WP Shopify' || $license_key['item_name'] === 'WP Shopify Pro') {
                $item_id = SHOPWP_DOWNLOAD_ID;
            } else {
                $found_name = strtoupper(
                    Utils::convert_space_to_underscore(
                        $license_key['item_name']
                    )
                );

                $item_id = constant('SHOPWP_DOWNLOAD_ID_' . $found_name);
            }

        } else {
            // Download id was set inside the Database already
            $item_id = $license_key['download_id'];
        }

        $classname_with_namespace = '\\' . $params['updater_class_name'];

        $updater_params = [
            'version' => $params['current_version'],
            'license' => $license_key['license_key'],
            'item_id' => $item_id, // 35
            'author' => SHOPWP_PLUGIN_NAME_FULL, // ShopWP, Beaver Builder
            'url' => home_url(),
            'beta' => $params['enable_beta'],
        ];

        return new $classname_with_namespace(
            SHOPWP_PLUGIN_ENV,
            $params['plugin_basename'],
            $updater_params
        );
    }

    public function maybe_check_for_updates($params)
    {
        if (!class_exists($params['updater_class_name'])) {
            include $params['updater_class_path'];
        }

        $licenses = array_map(function ($l) {
            return Utils::convert_object_to_array($l);
        }, $params['licenses']);

        $found_license = array_values(
            array_filter($licenses, function ($license) use ($params) {
                return $license['download_id'] == $params['item_id'];
            })
        );

        if (!empty($found_license)) {
            $this->check_for_updates($found_license[0], $params);
        }
    }

    public function init_updates()
    {
        // If no license keys are found
        if (
            empty($this->plugin_settings) ||
            empty($this->plugin_settings['license'])
        ) {
            return;
        }

        $this->maybe_check_for_updates([
            'licenses' => $this->plugin_settings['license'],
            'plugin_basename' => SHOPWP_BASENAME,
            'current_version' => SHOPWP_NEW_PLUGIN_VERSION,
            'enable_beta' => $this->plugin_settings['general']['enable_beta'],
            'item_id' => SHOPWP_DOWNLOAD_ID,
            'updater_class_name' => 'ShopWP_EDD_SL_Plugin_Updater',
            'updater_class_path' =>
                SHOPWP_PLUGIN_DIR_PATH .
                'vendor/EDD/ShopWP_EDD_SL_Plugin_Updater.php',
        ]);
    }

    public function init()
    {
        $this->init_updates();
    }
}
