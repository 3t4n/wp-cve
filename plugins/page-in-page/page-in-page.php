<?php

/**
 * Plugin Name: Page In Page
 * Plugin URI: http://cyriltata.blogspot.com/2013/11/wordpress-plugin-page-in-page.html
 * Description: Just another plugin that helps you insert the content of a page in another page. Widget support included. <br />And hey, this plugin helps you bring your facebook and twitter posts to your WordPress pages, see <a href="options-general.php?page=wp-pip-admin-settings">settings</a>
 * Version: 2.0.3
 * Author: Cyril Tata
 * Author URI: http://cyriltata.blogspot.com
 * License: GPL2
 */

require_once dirname(__FILE__) . '/setup.php';

// Register activation hook
register_activation_hook(__FILE__, 'twl_pip_activate');

// Initialize plugin functionality
TWL_Page_IN_Page_Page::init();

// Initialize admin functionality
if (is_admin()) {
	TWL_Page_In_Page_Admin::init();
}