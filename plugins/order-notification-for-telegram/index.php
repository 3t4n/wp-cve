<?php
/*
 * Plugin Name: Order Notification for Telegram
 * Plugin URI: https://choplugins.com/product/order-notification-for-telegram
 * Version: 1.0.1
 * Description: Send a message to your Telegram account when an order is placed
 * Author: choplugins
 * Author URI: https://choplugins.com
 * Text Domain: nktgnfw
 * Domain Path: /languages
 * WC requires at least: 3.2
 * WC tested up to: 3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
define( 'NKTNFW_DIR', plugin_dir_path( __FILE__ ) );

require_once (NKTNFW_DIR .'inc/admin_ajax.php');
require_once( NKTNFW_DIR . 'autoload.php' );


function nk_telegramWC() {
    return \NineKolor\TelegramWC\Classes\Core::instance();
}
add_action( 'plugins_loaded', 'nk_telegramWC', 26 );