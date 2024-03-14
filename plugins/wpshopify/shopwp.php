<?php

/*

Plugin Name:         ShopWP
Plugin URI:          https://wpshop.io
Description:         Sell and build custom Shopify experiences on WordPress.
Version:             5.2.3
Author:              ShopWP
Author URI:          https://wpshop.io
License:             GPL-2.0+
License URI:         https://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:         shopwp
Domain Path:         /languages
Requires at least:   5.4
Requires PHP:        5.6

*/

global $ShopWP;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

// If this file is called directly, abort.
defined('WPINC') ?: die();

// If this file is called directly, abort.
defined('ABSPATH') ?: die();

/*

Used for both free / pro versions

*/
if (!defined('SHOPWP_BASENAME')) {
    define('SHOPWP_BASENAME', plugin_basename(__FILE__));
}

if (!defined('SHOPWP_ROOT_FILE_PATH')) {
    define('SHOPWP_ROOT_FILE_PATH', __FILE__);
}

if (!defined('SHOPWP_PLUGIN_DIR')) {
    define('SHOPWP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use ShopWP\Bootstrap;
use ShopWP\Utils;

if (!\function_exists('shopwp_bootstrap')) {
    function shopwp_bootstrap()
    {
        // initialize
        if (!isset($ShopWP)) {
            $ShopWP = new Bootstrap();
            $ShopWP->initialize();
        }

        // return
        return $ShopWP;
    }
}

shopwp_bootstrap();

add_action( 'after_plugin_row', function($file, $plugin) {

	if ($file !== 'wpshopify/shopwp.php') {
		return;
	}

	echo '
		<style>.plugins tr[data-plugin="wpshopify/shopwp.php"] td,.plugins tr[data-plugin="wpshopify/shopwp.php"] th{box-shadow:none}.plugins 
.shopwp-exp-notice.active 
td{padding-left:40px;padding-bottom:30px;position:relative}.plugins .shopwp-exp-notice.active td div{margin-top:-13px;margin-left:0;margin-bottom:-5px}.shopwp-exp-notice .update-message p:before{content:"\f534"}.shopwp-exp-notice .update-message a{text-decoration:underline}.shopwp-exp-notice .update-message .dashicon{text-decoration:none;font-size:15px;position:relative;top:1px;left:-2px}</style>

		<tr class="plugin-update-tr shopwp-exp-notice active">
			<td colspan="4"><div class="update-message notice inline notice-warning notice-alt"><p>This plugin will stop working on March 1st, 2024. Please <a href="https://wpshop.io/purchase" target="_blank">upgrade to ShopWP Pro</a> to continue using the plugin.</p>' . __('Thanks y\'all.', 'shopwp') . '<br>- Andrew</div></td>
		</tr>
	';

}, 10, 2 );

/*

Adds hooks which run on both plugin activation and deactivation.
The actions here are added during Activator->init() and Deactivator-init().

*/
register_activation_hook(__FILE__, function ($network_wide) {
    do_action('shopwp_on_plugin_activate', $network_wide);
});

register_deactivation_hook(__FILE__, function () {
    do_action('shopwp_on_plugin_deactivate');
});
