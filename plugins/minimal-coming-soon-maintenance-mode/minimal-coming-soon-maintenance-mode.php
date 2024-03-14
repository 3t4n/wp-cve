<?php
/**
 * Plugin Name: Minimal Coming Soon & Maintenance Mode
 * Plugin URI: https://comingsoonwp.com/
 * Description: Simply awesome coming soon & maintenance mode plugin. Super-simple to use. MailChimp support built-in.
 * Version: 2.38
 * Requires at least: 4.0
 * Requires PHP: 5.2
 * Tested up to: 6.4
 * Author: WebFactory Ltd
 * Author URI: https://www.webfactoryltd.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 * Minimal Coming Soon & Maintenance Mode Plugin
 * Copyright (C) 2016 - 2023, WebFactory Ltd - support@webfactoryltd.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Defining constants and activation hook.
 * If this file is called directly, abort.
 */

if (!defined('WPINC')) {
	die;
}

require_once 'wf-flyout/wf-flyout.php';
new wf_flyout(__FILE__);

/* Constants we will be using throughout the plugin. */
define('CSMM_BASENAME', plugin_basename(__FILE__));
define('CSMM_URL', plugins_url('', __FILE__));
define('CSMM_PATH', plugin_dir_path(__FILE__));
define('CSMM_POINTERS', 'csmm_pointers');
define('CSMM_FILE', __FILE__);

function csmm_default_options() {
  $default_options = array(
    'status'        => '2',
    'title'         => get_bloginfo('name') . ' is coming soon',
    'description'   => 'We are doing some maintenance on our site. Please come back later.',
    'love'          => '0',
    'header_text'       => 'Our site is coming soon',
    'secondary_text'     => 'We are doing some maintenance on our site. It won\'t take long, we promise. Come back and visit us again in a few days. Thank you for your patience!',
    'antispam_text'     => 'And yes, we hate spam too!',
    'custom_login_url'     => '/login/',
    'show_logged_in'     => '1',
    'show_login_button'     => '0',
    'exclude_se'      => '0',
    'arrange'         => 'logo,header,secondary,form,html',
    'analytics'       => '',
    'disable_adminbar' => '0',
    'settings_customized' => '0',

    'mailoptin_campaign' => 0,
    'mail_system_to_use' => 'mc',
    'mailchimp_api'      => '',
    'mailchimp_list'     => '',
    'message_noemail'     => 'Please provide a valid email address.',
    'message_subscribed'   => 'You are already subscribed!',
    'message_wrong'     => 'Oops! Something went wrong.',
    'message_done'       => 'Thank you! We\'ll be in touch!',

    'logo'          => CSMM_URL . '/framework/public/img/mm-logo.png',
    'favicon'        => CSMM_URL . '/framework/public/img/mm-favicon.png',
    'bg_cover'         => CSMM_URL . '/framework/public/img/mountain-bg.jpg',
    'content_overlay'     => 1,
    'content_width'      => '600',
    'bg_color'         => 'FFFFFF',
    'content_position'    => 'center',
    'content_alignment'    => 'left',
    'header_font'       => 'Karla',
    'secondary_font'     => 'Karla',
    'header_font_size'     => '28',
    'secondary_font_size'   => '14',
    'header_font_color'   => 'FFFFFF',
    'secondary_font_color'   => 'FFFFFF',
    'antispam_font_size'   => '13',
    'antispam_font_color'   => 'BBBBBB',

    'input_text'       => 'Enter your best email address',
    'button_text'       => 'Subscribe',
    'gdpr_text'         => 'I understand the site\'s privacy policy and am willingly sharing my email address',
    'gdpr_fail'         => 'Please confirm the subscription terms with the checkbox below.',
    'ignore_form_styles'   => 1,
    'input_font_size'    => '13',
    'button_font_size'    => '12',
    'input_font_color'    => 'FFFFFF',
    'button_font_color'    => 'FFFFFF',
    'input_bg'        => '',
    'button_bg'        => '0F0F0F',
    'input_bg_hover'    => '',
    'button_bg_hover'    => '0A0A0A',
    'input_border'      => 'EEEEEE',
    'button_border'      => '0F0F0F',
    'input_border_hover'  => 'BBBBBB',
    'button_border_hover'  => '0A0A0A',
    'success_background'   => '90C695',
    'success_color'     => 'FFFFFF',
    'error_background'     => 'E08283',
    'error_color'       => 'FFFFFF',
    'form_placeholder_color'   => 'DEDEDE',

    'disable_settings'     => '2',
    'custom_html'      => '',
    'custom_css'      => '',

    'block_se' => '',
    'tracking_pixel' => '',
    'social_preview' => '',
    'signals_ip_whitelist' => ''
  );

  return $default_options;
} // csmm_default_options


function csmm_get_options() {
  $signals_csmm_options = get_option('signals_csmm_options', array());
  if (!is_array($signals_csmm_options)) {
    $signals_csmm_options = array();
  }
  $signals_csmm_options = array_merge(csmm_default_options(), $signals_csmm_options);

  return $signals_csmm_options;
} // csmm_get_options


function csmm_get_meta() {
  $default['license_type'] = '';
  $default['license_expires'] = '';
  $default['license_active'] = false;
  $default['license_key'] = '';

  $meta = get_option('signals_csmm_meta', array());
  $meta = array_merge($default, $meta);

  return $meta;
} // csmm_get_options


/**
 * For the plugin activation & de-activation.
 * We are doing nothing over here.
 */

function csmm_plugin_activation() {

	// Checking if the options exist in the database
	$signals_csmm_options = get_option( 'signals_csmm_options' );

	// Default options for the plugin on activation
	$default_options = csmm_default_options();

	// If the options are not there in the database, then create the default options for the plugin
	if ( ! $signals_csmm_options ) {
		update_option( 'signals_csmm_options', $default_options );
	} else {
		// If present in the database, merge with the default ones
		// This is to provide compatibility with earlier versions. Although it doesn't serve the purpose completely
		$default_options = array_merge( $default_options, $signals_csmm_options );
		update_option( 'signals_csmm_options', $default_options );
	}

  // set some meta data
  $meta = get_option('signals_csmm_meta', array());

  if (!isset($meta['first_version']) || !isset($meta['first_install'])) {
    $meta['first_version'] = csmm_get_plugin_version();
    $meta['first_install_gmt'] = time();
    $meta['first_install'] = current_time('timestamp');
    update_option('signals_csmm_meta', $meta);
  }
} // csmm_plugin_activation
register_activation_hook( __FILE__, 'csmm_plugin_activation');


/* Hook for the plugin deactivation. */
function csmm_plugin_deactivation() {
  delete_option(CSMM_POINTERS);
}
register_deactivation_hook( __FILE__, 'csmm_plugin_deactivation' );


function csmm_plugin_page() {
  $current_screen = get_current_screen();

  if ($current_screen->id == 'maintenance_mode_options') {
    return true;
  } else {
    return false;
  }
} // csmm_plugin_page


/**
 * Include files necessary for the management panel of the plugin.
 */

require CSMM_PATH . 'framework/init.php';
require CSMM_PATH . 'framework/wf-licensing.php';
require CSMM_PATH . 'framework/admin/license.php';

if (is_admin()) {
	require CSMM_PATH . 'framework/admin/init.php';
}

require CSMM_PATH . 'framework/public/init.php';

function csmm_get_plugin_version() {
  $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');

  return $plugin_data['version'];
} // csmm_get_plugin_version


 function csmm_convert_ga($code) {
   if (empty($code) || strpos($code, '<script') === false) {
     return $code;
   }

   preg_match_all('/(UA-[0-9]{3,10}-[0-9]{1,3})/i', $code, $matches, PREG_SET_ORDER, 0);
   if (!empty($matches[0][0])) {
     return $matches[0][0];
   } else {
     return '';
   }
 } // csmm_convert_ga
