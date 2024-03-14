<?php
/**
 * Plugin Name: Food Menu - Restaurant Menu & Online Ordering for WooCommerce
 * Plugin URI: http://demo.radiustheme.com/wordpress/plugins/food-menu/
 * Description: A Simple Food & Restaurant Menu Display Plugin for Restaurant, Cafes, Fast Food, Coffee House with WooCommerce Online Ordering.
 * Author: RadiusTheme
 * Version: 5.0.8
 * Text Domain: tlp-food-menu
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 *
 * @package RT_FoodMenu
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

define( 'TLP_FOOD_MENU_VERSION', '5.0.8' );
define( 'TLP_FOOD_MENU_AUTHOR', 'RadiusTheme' );
define( 'TLP_FOOD_MENU_PLUGIN_PATH', __FILE__ );
define( 'FOOD_MENU_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__ ) );
define( 'TLP_FOOD_MENU_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ) );
define( 'TLP_FOOD_MENU_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TLP_FOOD_MENU_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Autoload.
 */
if ( file_exists( FOOD_MENU_PLUGIN_DIR_PATH. '/vendor/autoload.php' ) ) {
	require_once FOOD_MENU_PLUGIN_DIR_PATH . '/vendor/autoload.php';
}

/**
 * App Init.
 */
if ( ! class_exists( TLPFoodMenu::class ) ) {
	require_once 'app/TLPFoodMenu.php';
}

register_activation_hook( __FILE__, 'activate_rt_food_menu' );
/**
 * Plugin activation action.
 *
 * Plugin activation will not work after "plugins_loaded" hook
 * that's why activation hooks run here.
 */
function activate_rt_food_menu() {
	\RT\FoodMenu\Helpers\Install::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_rt_food_menu' );
/**
 * Plugin deactivation action.
 *
 * Plugin deactivation will not work after "plugins_loaded" hook
 * that's why deactivation hooks run here.
 */
function deactivate_rt_food_menu() {
	\RT\FoodMenu\Helpers\Install::deactivate();
}
