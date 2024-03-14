<?php
/**
 * Plugin Name: Lightbox block
 * Description: Lightbox block is an excellent choice for your WordPress Lightbox Block.
 * Version: 1.1.9
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: lightbox
  * @fs_free_only, bsdk_config.json
 */

// ABS PATH
if (!defined('ABSPATH')) {exit;}

if (function_exists('lbb_fs')) {
    register_activation_hook(__FILE__, function () {
        if (is_plugin_active('lightbox-block/index.php')) {
            deactivate_plugins('lightbox-block/index.php');
        }
        if (is_plugin_active('lightbox-block-pro/index.php')) {
            deactivate_plugins('lightbox-block-pro/index.php');
        }
    });

} else {

// Constant
    define('LBB_PLUGIN_VERSION', 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.9');
    define('LBB_ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');
    define('LBB_DIR_URL', plugin_dir_url(__FILE__));
    define('LBB_DIR_PATH', plugin_dir_path(__FILE__));
    define('LBB_IS_FREE', 'lightbox-block/index.php' === plugin_basename(__FILE__));
    define('LBB_IS_PRO', 'lightbox-block-pro/index.php' === plugin_basename(__FILE__));

    // Create a helper function for easy SDK access.
    function lbb_fs()
    {
        global $lbb_fs;

        if (!isset($lbb_fs)) {
            // Include Freemius SDK.
            if (file_exists(dirname(__FILE__) . '/bplugins_sdk/init.php')) {
                require_once dirname(__FILE__) . '/bplugins_sdk/init.php';
            }
            if (file_exists(dirname(__FILE__) . '/freemius/start.php')) {
                require_once dirname(__FILE__) . '/freemius/start.php';
            }

            $lbb_fs = fs_lite_dynamic_init(array(
                'id' => '13492',
                'slug' => 'lightbox-block',
                'premium_slug' => 'lightbox-block-pro',
                'type' => 'plugin',
                'public_key' => 'pk_8346b668170b2e4c33255d896d15c',
                'is_premium' => true,
                'premium_suffix' => 'Pro',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons' => false,
                'has_paid_plans' => true,
                'trial' => array(
                    'days' => 7,
                    'is_require_payment' => false,
                ),
                'menu' => array(
                    'slug' => 'lightbox-block.php',
                    'contact' => false,
                    'support' => false,
                ),
            ));
        }

        return $lbb_fs;
    }

    // Init Freemius.
    lbb_fs();
    // Signal that SDK was initiated.
    do_action('lbb_fs_loaded');

    if (LBB_IS_PRO) {
        // require_once LBB_DIR_PATH . 'inc/pro.php';
        require_once LBB_DIR_PATH . 'inc/AdminMenu.php';
    }

    require_once LBB_DIR_PATH . 'inc/block.php';

// Light Box
    if (!class_exists('LBBPlugin')) {
        class LBBPlugin
        {
            public function __construct()
            {
                add_action('wp_ajax_lbbPipeChecker', [$this, 'lbbPipeChecker']);
                add_action('wp_ajax_nopriv_lbbPipeChecker', [$this, 'lbbPipeChecker']);
                add_action('admin_init', [$this, 'registerSettings']);
                add_action('rest_api_init', [$this, 'registerSettings']);
            }

            public function lbbPipeChecker()
            {
                $nonce = $_POST['_wpnonce'];

                if (!wp_verify_nonce($nonce, 'wp_ajax')) {
                    wp_send_json_error('Invalid Request');
                }

                wp_send_json_success([
                    'isPipe' => LBB_IS_PRO?\lbb_fs()->is__premium_only() && \lbb_fs()->can_use_premium_code() : false,
                ]);
            }

            public function registerSettings()
            {
                register_setting('lbbUtils', 'lbbUtils', [
                    'show_in_rest' => [
                        'name' => 'lbbUtils',
                        'schema' => ['type' => 'string'],
                    ],
                    'type' => 'string',
                    'default' => wp_json_encode(['nonce' => wp_create_nonce('wp_ajax')]),
                    'sanitize_callback' => 'sanitize_text_field',
                ]);
            }
        }
        new LBBPlugin;
    }

}
