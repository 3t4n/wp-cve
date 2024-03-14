<?php
/**
 * @package Fontawesome Icons
 * @version4.1.2
 */
/*
Plugin Name: SS Font Awesome Icon
Plugin URI: http://sobshomoy.com/plugins/ss-font-awesome-icon
Description: Easy to integrate in your post and page also on widget. Just go <a href="http://sobshomoy.com/ss-font-awesome-icon/">http://sobshomoy.com/ss-font-awesome-icon/</a> and get more information about icon integration. 
Author: Shiful Islam
Version: 4.1.3
Author URI: http://bn.hs-bd.com/
*/

/* Plugin Root dir */
define('SS_FA_ICONS_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
//wp_enqueue_style('ss-fa-icons', SS_FA_ICONS_PATH.'css/style.css');

//include fontawesome cdn
function ss_font_awesome_icons_scripts() {
wp_enqueue_style('ss_fa_cdn_icons_cssmain', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css');

}
add_action( 'wp_enqueue_scripts', 'ss_font_awesome_icons_scripts' );
//  https://fontawesome.com/v4.7.0/icons/

//enabled shortcode for widget
add_filter('widget_text', 'do_shortcode');



//main function for fontawesome
function ss_font_awesome_function($atts){
   extract(shortcode_atts(array(
      'name' => 'twitter',
      'size' => 20,
	  'type' => '',
	  'class' => '',
	  'padding' => '',
	  'margin' => '',
	  'color' => '#212121',
	  'bg' => '#757575',
	  
   ), $atts));

   $return_string = '<i style="background:'.$bg.';color:'.$color.';font-size:'.$size.'px;padding:'.$padding.'%; margin:'.$margin.'%" class="fa'.$type.' fa-'.$name.' '.$class.'">';
   $return_string .= '</i>';
   return $return_string;
}

function register_shortcodes(){
   add_shortcode('icon', 'ss_font_awesome_function');
}
add_action( 'init', 'register_shortcodes');
?>