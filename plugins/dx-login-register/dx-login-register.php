<?php
/*
Plugin Name: DX Login Register
Plugin URI: http://www.daxiawp.com/dx-login-register.html
Description: Sign custom page content, custom password, authentication code detection, login redirection function, etc. 自定义登录注册页面内容，自定义密码，验证码检测，登录重定向等功能。
Version: 1.0.1
Author: 大侠WP
Author URI: http://www.daxiawp.com/dx-login-register.html
Copyright: daxiawp原创插件，任何个人或组织不得擅自更改版权或者盗用代码。
*/


/**
 * Define constant
 */
define( 'DXLORE_FILE', plugin_basename( __FILE__ ) );	// Plugin basename
define( 'DXLORE_PRE', 'dx_login_register' );		// Plugin name prefix


// Internationalization
function dx_login_register_internationalization() {
	load_plugin_textdomain( DXLORE_PRE, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'dx_login_register_internationalization' );


// Settings
require_once( 'class-settings.php' );

// Custom form
require_once( 'extends/class-custom-form.php' );

// Verify password captcha etc.
require_once( 'extends/class-verify.php' );