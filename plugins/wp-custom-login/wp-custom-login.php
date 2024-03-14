<?php
/*
Plugin Name: WP Custom Login
Description: This plugin is adding the header and footer to the login page
Author: Ninos Ego
Version: 1.4.9
Author URI: http://ninosego.de/
*/

if( !isset($_REQUEST['interim-login']) )
{
	if( file_exists( get_theme_root() . '/' . get_stylesheet() . '/wp-custom-login.css' ) )
		wp_register_style( 'wp-custom-login', get_stylesheet_directory_uri() . '/wp-custom-login.css' );

	add_action( 'login_head', 'wp_custom_login_head_javascript' );
	function wp_custom_login_head_javascript() {
?>
		<script type="text/javascript">
			wp_custom_login_remove_element('wp-admin-css');
			wp_custom_login_remove_element('colors-fresh-css');

			function wp_custom_login_remove_element(id) {
				var element = document.getElementById(id);
				if( typeof element !== 'undefined' && element != null && element.value == '' ) {
					element.parentNode.removeChild(element);
				}
			}
		</script>
<?php
	}

	add_action( 'login_head', 'wp_custom_login_header' );
	function wp_custom_login_header() {
		wp_enqueue_style( 'wp-custom-login' );

		do_action('wp_custom_login_header_before');
		get_header();
		do_action('wp_custom_login_header_after');
	}

	add_action( 'login_footer', 'wp_custom_login_footer' );
	function wp_custom_login_footer() {
		do_action('wp_custom_login_footer_before');
		get_footer();
		do_action('wp_custom_login_footer_after');
	}

	function wp_custom_login_is_login_page() {
		return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	}
}