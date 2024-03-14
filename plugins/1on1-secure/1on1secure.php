<?php
/**
 * @package     1on1 Secure
 * @author      1on1 Secure
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: 1on1 Secure
 * Plugin URI:  https://www.1on1Secure.com/
 * Description: Visitor verification and screening for known bad actors. No CAPTCHA required to prevent comment spam, prevent contact page spam, and reduce wordpress vulnerabilty scans from bad actors.
 * Version:     1.1.10
 * Author:      1on1Secure.com
 * Text Domain: 1on1-Secure
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

/*
1on1 Secure is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

1on1 Secure is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with 1on1 Secure. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

if (!defined('ABSPATH')) exit;

  require_once('includes/1on1secure-wp-admin.php');
  require_once('includes/1on1secure-functions.php');

  add_action('template_redirect', 'CheckVisitorWith1On1Secure');
  add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'OneOn1SecureAdminSettings');
  add_action('admin_menu', 'OneOn1SecureAdminSettingsPage');       // Add a new settings page to the WordPress admin menu

  $redirectoption = absint(get_option("DataAnalysis1on1Secure"));               //sanitize the input

  if ($redirectoption < 1) {                                                    //if web form data analysis is opted in
    add_action('init', 'CaptureAllFormSubmissionsWith1On1Secure');              //hook into form submissions for spam filter

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if (is_plugin_active('ninja-forms/ninja-forms.php')) {
      add_action('ninja_forms_after_submission', 'CaptureNinjaformsSubmissionWith1On1Secure');
    }

    if (is_plugin_active('wpforms-lite/wpforms.php')) {
      add_action('wpforms_process', 'CaptureWpformsSubmissionWith1On1Secure', 10, 4);
    }

    if (is_plugin_active('wpforms/wpforms.php')) {
      add_action('wpforms_process', 'CaptureWpformsSubmissionWith1On1Secure', 10, 4);
    }
  }

  register_activation_hook( __FILE__, 'OneOn1SecureActivate');
  register_uninstall_hook( __FILE__, 'OneOn1SecureUninstall' );

