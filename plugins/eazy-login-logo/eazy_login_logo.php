<?php
/*
Plugin Name: Eazy Login Logo
Plugin URI: http://robjscott.com/wordpress/
Description:  Changes Login Logo Link & Image
Version: 1.0.0
Author: Rob Scott, LLC
Author URI: http://robjscott.com
License: GPLv2
Copyright: Rob Scott, LLC
*/

// Add Login Logo URL 
add_filter('login_headerurl', 'eazy_login_logo_url');
function eazy_login_logo_url() {
	return get_home_url();
}

// Add Login Logo Title
add_filter('login_headertitle', 'eazy_login_logo_title');
function eazy_login_logo_title() {
	return get_option('blogname');
}

// Add Login Logo Image
add_action( 'login_enqueue_scripts', 'eazy_login_logo' );
function eazy_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo plugins_url('images/login-logo.png', __FILE__ ); ?> );
        }
    </style>
<?php }

?>