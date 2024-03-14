<?php
/**
 * Plugin Name:       Yandex Mail SMTP Server for WordPress
 * Plugin URI:        http://wordpress.org/plugins/smtp-yandex-mail-server
 * Description:       Easily send email from your WordPress site via Yandex SMTP server
 * Version:           1.0.2
 * Author:            Ozan Canakli
 * Author URI:        http://www.ozanwp.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl.html
 * Text Domain:       yandex-smtp
 * Domain Path:       /languages
 */

defined('ABSPATH') or exit;

define( 'YANDEX_SMTP_DIR', plugin_dir_path( __FILE__ ) );

// Load plugin textdomain
add_action( 'init', 'yandex_smtp_textdomain' );
function yandex_smtp_textdomain() {
  load_plugin_textdomain( 'yandex-smtp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

// Classes
if (is_admin()) {
    require YANDEX_SMTP_DIR . '/class-yandex-smtp.php';
}

// Mail functions
add_action('phpmailer_init', function($phpmailer) {
    require YANDEX_SMTP_DIR . '/init.php';
});
