<?php

/**
 *
 * @link              		https://profiles.wordpress.org/webbuilder143/
 * @since             		1.0.0
 * @package           		Wb_Custom_Product_Tabs_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       		Custom Product tabs for WooCommerce
 * Plugin URI:        		https://wordpress.org/plugins/wb-custom-product-tab-for-wooCommerce/
 * Description:       		Create your own product tabs and assign it to your WooCommerce products 
 * Version:           		1.1.12
 * WC requires at least:	3.0.0
 * WC tested up to: 		8.5
 * Author:            		Web Builder 143
 * Author URI:        		https://profiles.wordpress.org/webbuilder143/
 * License:           		GPL-2.0+
 * License URI:       		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       		wb-custom-product-tabs-for-woocommerce
 * Domain Path:       		/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION', '1.1.12' );
define ('WB_TAB_PLUGIN_FILENAME', __FILE__);
define( 'WB_TAB_ROOT_PATH', plugin_dir_path( __FILE__) );
define( 'WB_TAB_POST_TYPE', 'wb-custom-tabs' );
define( 'WB_TAB_SLUG', 'wb-custom-product-tabs-for-woocommerce' );

/**
* Check WooCommerce is active
*/
if(!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) 
    && !array_key_exists('woocommerce/woocommerce.php', apply_filters('active_plugins', get_site_option('active_sitewide_plugins', array())))) { 

    add_action('admin_footer', 'wb_custom_product_tab_wc_missing_warning');
    function wb_custom_product_tab_wc_missing_warning(){
        global $pagenow;
        if($pagenow!='plugins.php'){
            return;
        }
        $warn_msg='WooCommerce is required for this plugin.';
        if(file_exists(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php')){
            $warn_msg='Activate WooCommerce to use this plugin.';
        }
        ?>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    var plugin_row=$('#the-list').find('tr[data-slug="<?php echo WB_TAB_SLUG;?>"]');
                    if(plugin_row.length>0){
                        plugin_row.addClass('update').after('<tr class="plugin-update-tr active"><td colspan="4"><div class="notice inline notice-warning notice-alt"><p> <span class="dashicons dashicons-warning"></span> <?php echo $warn_msg;?> </p></div></td></tr>');
                    }
                });
            }(jQuery));
        </script>
        <?php
    }
    return;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wb-custom-product-tabs-for-woocommerce-activator.php
 */
function activate_wb_custom_product_tabs_for_woocommerce() {
	require_once WB_TAB_ROOT_PATH . 'includes/class-wb-custom-product-tabs-for-woocommerce-activator.php';
	Wb_Custom_Product_Tabs_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wb-custom-product-tabs-for-woocommerce-deactivator.php
 */
function deactivate_wb_custom_product_tabs_for_woocommerce() {
	require_once WB_TAB_ROOT_PATH . 'includes/class-wb-custom-product-tabs-for-woocommerce-deactivator.php';
	Wb_Custom_Product_Tabs_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wb_custom_product_tabs_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_wb_custom_product_tabs_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WB_TAB_ROOT_PATH . 'includes/class-wb-custom-product-tabs-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wb_custom_product_tabs_for_woocommerce() {

	$plugin = new Wb_Custom_Product_Tabs_For_Woocommerce();
	$plugin->run();

}

/**
 *  Declare compatibility with WooCommerce High-Performance order storage (COT).
 * 
 *  @since 1.1.4 
 */
add_action(
    'before_woocommerce_init', function(){ 
        if(class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil'))
        {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables',  __FILE__, true);
        }
    }
);


run_wb_custom_product_tabs_for_woocommerce();
