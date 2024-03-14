<?php
/*
Plugin Name: Only for registered users 
Plugin URI: http://wordpress.org/plugins/only-for-registered-users/
Description: Make post, page or partial content visible to registered users only
Version: 1.0
Author: Sunny Verma
Author URI: http://99webtools.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require(plugin_dir_path( __FILE__ ).'post_meta_box.php');

function regUserOnly_content_filter($content) {
	$value = get_post_meta( get_the_ID(), '_regUserOnly', true );
	if ( is_user_logged_in() || empty($value)  ) 
	{
		return $content;
	}
	else
	{
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		return '<code style="color:red;border:1px solid red;padding: 3px;">'.sprintf(__("Content is available only for registered users. Please <a href='%s'>login</a> or <a href='%s'>register</a>","regUserOnly"),wp_login_url( $current_url ),wp_registration_url()).'</code>';
	}
}
function regUserOnly_load_textdomain(){
	load_plugin_textdomain( 'regUserOnly', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
function regUserOnly_shortcode( $atts, $content = null ) {
	if ( is_user_logged_in()  ) 
	{
		return do_shortcode($content);
	}
	else
	{
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		return '<code style="color:red;border:1px solid red;padding: 3px;">'.sprintf(__("Content is available only for registered users. Please <a href='%s'>login</a> or <a href='%s'>register</a>","regUserOnly"),wp_login_url( $current_url ),wp_registration_url()).'</code>';
	}
}
function regUserOnly_shortcode_button_init() {

      //Abort early if the user will never see TinyMCE
      if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
           return;

      //Add a callback to regiser our tinymce plugin   
      add_filter("mce_external_plugins", "regUserOnly_register_tinymce_plugin"); 

      // Add a callback to add our button to the TinyMCE toolbar
      add_filter('mce_buttons', 'regUserOnly_add_tinymce_button');
}
//This callback registers our plug-in
function regUserOnly_register_tinymce_plugin($plugin_array) {
    $plugin_array['regUserOnly_button'] = plugins_url( 'js/tinymce-plugin.js' , __FILE__ );
    return $plugin_array;
}

//This callback adds our button to the toolbar
function regUserOnly_add_tinymce_button($buttons) {
            //Add the button ID to the $button array
    $buttons[] = "regUserOnly_button";
    return $buttons;
}
add_action('init', 'regUserOnly_shortcode_button_init');
add_shortcode( 'RegUserOnly', 'regUserOnly_shortcode' );
add_action( 'plugins_loaded', 'regUserOnly_load_textdomain' );
add_filter( 'the_content', 'regUserOnly_content_filter',1,1 );
?>