<?php
/*
Plugin Name: Connect SendGrid for Emails
Description: Connect SendGrid to your WordPress site to send emails using SendGrid's cloud-based email platform.
Version: 1.11.12
Author: WP Zone
Author URI: https://wpzone.co
Text Domain: connect-sendgrid-for-emails
License: GPLv3+
*/

/*
SendGrid Plugin
Copyright (C) 2023 WP Zone

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

====

CREDITS:

This plugin is forked from the original SendGrid plugin, copyright (c) SendGrid.

This plugin includes code based on parts of WordPress by Automattic, released
under GPLv2+, licensed under GPLv3 or later (see wp-license.txt in the license directory
for the license and additional credits applicable to WordPress, and the license.txt
file in the license directory for GPLv3 text).

====

NOTE:

Connect SendGrid for Emails is a third-party fork of the official SendGrid plugin. This plugin is not endorsed by or affiliated with SendGrid.

*/

// SendGrid configurations
define( 'SENDGRID_CATEGORY', 'wp_sendgrid_plugin' );
define( 'SENDGRID_PLUGIN_SETTINGS', 'settings_page_sendgrid-settings' );
define( 'SENDGRID_PLUGIN_STATISTICS', 'dashboard_page_sendgrid-statistics' );

if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
  add_action( 'admin_notices', 'php_version_error' );

  /**
  * Display the notice if PHP version is lower than plugin need
  *
  * return void
  */
  function php_version_error()
  {
    echo '<div class="error"><p>' . esc_html__('SendGrid: Plugin requires PHP >= 5.4.0.', 'connect-sendgrid-for-emails') . '</p></div>';
  }

  return;
}

if ( function_exists('wp_mail') )
{
  /**
   * wp_mail has been declared by another process or plugin, so you won't be able to use SENDGRID until the problem is solved.
   */
  add_action( 'admin_notices', 'wp_mail_already_declared_notice' );

  /**
  * Display the notice that wp_mail function was declared by another plugin
  *
  * return void
  */
  function wp_mail_already_declared_notice()
  {
    echo '<div class="error"><p>' . esc_html__( 'SendGrid: wp_mail has been declared by another process or plugin, so you won\'t be able to use SendGrid until the conflict is solved.', 'connect-sendgrid-for-emails' ) . '</p></div>';
  }

  return;
}

// Load plugin files
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-tools.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-mc-optin.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-statistics.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/sendgrid/sendgrid-wp-mail.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-nlvx-widget.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-virtual-pages.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/class-sendgrid-filters.php';

// Widget Registration
if ( 'true' == Sendgrid_Tools::get_mc_auth_valid() ) {
  add_action( 'widgets_init', 'register_sendgrid_widgets' );
} else {
  add_action( 'widgets_init', 'unregister_sendgrid_widgets' );
}

// Initialize SendGrid Settings
new Sendgrid_Settings( plugin_basename( __FILE__ ) );

// Initialize SendGrid Statistics
new Sendgrid_Statistics();

// Initialize SendGrid Filters
new Sendgrid_Filters();