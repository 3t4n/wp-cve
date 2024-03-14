<?php 
/*
Plugin Name: CSH Login
Plugin URI: http://demo.cmssuperheroes.com/csh-plugins/csh-login
Description: Modal login form with redirect and styling options.
Version: 1.0
Author: Tony
Author URI: https://codecanyon.net/user/cmssuperheroes
Text Domain: cshlogin
License: GPLv2 or later
Copyright 2017 CmsSuperHeroes 
*/

/* Return login option data */
$cshlg_options = get_option( 'CSHLogin' );

/* Define Constants */
define( 'CSHLOGIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CSHLOGIN_PLUGIN_URL', plugins_url("", __FILE__) );

define( 'CSHLOGIN_PLUGIN_INCLUDES_DIR', CSHLOGIN_PLUGIN_DIR . "/inc/" );
define( 'CSHLOGIN_PLUGIN_INCLUDES_URL', CSHLOGIN_PLUGIN_URL . "/inc/" );

define( 'CSHLOGIN_PLUGIN_ASSETS_DIR', CSHLOGIN_PLUGIN_DIR . "/assets/" );
define( 'CSHLOGIN_PLUGIN_ASSETS_URL', CSHLOGIN_PLUGIN_URL . "/assets/" );

define( 'CSHLOGIN_PLUGIN_CORE_DIR', CSHLOGIN_PLUGIN_DIR . "/core/" );
define( 'CSHLOGIN_PLUGIN_CORE_URL', CSHLOGIN_PLUGIN_URL . "/core/" );

/* Load admin setting class */
require_once CSHLOGIN_PLUGIN_INCLUDES_DIR. 'AdminSettings.php';

/* Load register scripts file */
require_once CSHLOGIN_PLUGIN_INCLUDES_DIR. 'register-scripts.php';

/* Load hook declare file */
require_once CSHLOGIN_PLUGIN_CORE_DIR. 'declare-hooks.php';

/* Load widget login form file */
require_once CSHLOGIN_PLUGIN_CORE_DIR. 'widget-show-login-form.php';

/* Load add fields setting file */
require_once CSHLOGIN_PLUGIN_CORE_DIR. 'add-fields-admin-setting.php';


?>