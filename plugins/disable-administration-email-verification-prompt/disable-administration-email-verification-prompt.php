<?php
/**
 * Plugin Name: Disable Administration Email Verification Prompt
 * Plugin URI: https://wordpres.org/plugins/disable-admin-email-verification-prompt
 * Description: Disables the administration email verification prompt introduced in WordPress 5.3.
 * Author: ModularWP
 * Author URI: https://modularwp.com/
 * Version: 1.0.3
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'admin_email_check_interval', '__return_false' );
