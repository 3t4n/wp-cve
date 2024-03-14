<?php
/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Mp_Register_Login
 */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

//Delete options from db
$options = array(
    'wpmp_redirect_settings',
    'wpmp_display_settings',
    'wpmp_form_settings',
    'wpmp_email_settings');

foreach ($options as $key => $option) {
    delete_option($option);
}


