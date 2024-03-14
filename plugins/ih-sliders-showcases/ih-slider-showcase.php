<?php

/*
Plugin Name: IH Sliders & Showcases
Description: Add Options for Sliders, Showcases, Testimonials, Portfolio & Much More. Designed to Work with Inkhive.com Themes. 
Version:0.9
Author: Rohit Tripathi
Author URI: http://rohitink.com
License: GPLv3


++ Note For Developers/Users ++
--------------------------------
All This Plugin Does Create Sections in themes for users to create and set up their sliders, showcases, testiomnials, etc.
What Kind of Slider to be used, or its styling, will be completely done by the themes which support this plugin.

This ensures that Users do not loose their data once, they switch themes.

Naming of Variables:
All instances of ihss, which are for plugin use only, are named ihss. 
All Those to be referenced by themes are named ihss.
*/

if(!defined('IHSS_URL')){
	define('IHSS_URL', plugin_dir_url(__FILE__) );
	define( 'IHSS_PATH', plugin_dir_path( __FILE__ ) );
}


//The Purpose of creating a class here, is also to help theme check if plugin is installed by
// calling a class_exists() functions.
class IHSS {
	public function __construct() {
	    add_action( 'admin_notices', array($this, 'ihss_admin_notice__error' ) );
    }   
    
    //Admin Notice - If theme does not support the plugin
	public function ihss_admin_notice__error() {
		if (!get_theme_support('ihss-all')) :
	    ?>
	    <div class="notice notice-error is-dismissible">
	        <p><?php _e( 'Your Theme Does not Support this IH Sliders & Showcases Plugin. If you are looking for a Theme which works with this Plugin, try <a href="https://wordpress.org/themes/ih-business-pro/" target="_blank">IH Business Pro</a>', 'ih-slider-showcase' ); ?></p>
	    </div>
	    <?php
		endif; 
	}
	
	//The Function which is to be called by themes.
	public static function render($param1, $param2 = null) {
		global $ihss_inst; //Global Variable of the Instance
		if ( $ihss_inst->is_ihss_enabled() )
			get_template_part( $param1, $param2 );
	}
	
	//The Function to Fetch Various Slider Settings and Values
	public static function fetch( $param, $slide_number = null ) {
		
		$return_value = null;
		
		if ($slide_number == null) {		
			$return_value = get_theme_mod($param);
		} elseif ($slide_number > 0) {
			$return_value = get_theme_mod($param.$slide_number);
		}
		
		return $return_value;	
	}
	
	//Sanitization
	public function sanitize_checkbox( $input ) {
	    if ( $input == 1 ) {
	        return 1;
	    } else {
	        return '';
	    }
	}
	
	public function sanitize_positive_number( $input ) {
		if ( ($input >= 0) && is_numeric($input) )
			return $input;
		else
			return '';	
	}
	

} //END CLASS

//Initialize and Instance of the Slider.
$ihss_inst = new IHSS();
require_once IHSS_PATH.'/style.php';
require_once IHSS_PATH.'/sections/slider.php';
require_once IHSS_PATH.'/sections/showcase.php';
require_once IHSS_PATH.'/sections/testimonials.php';
require_once IHSS_PATH.'/sections/team.php';
require_once IHSS_PATH.'/sections/counters.php';
require_once IHSS_PATH.'/sections/parallax.php';

function ihss_init() {
	if (get_theme_support('ih-sliders')) :
		$slider_inst = new IHSS_Slider();
	endif;
	
	if (get_theme_support('ih-showcase')) :
		$showcase_inst = new IHSS_Showcase();
	endif;
	
	if (get_theme_support('ih-testimonials')) :
		$test_inst = new IHSS_Testimonials();
	endif;
	
	if (get_theme_support('ih-team')) :
		$team_inst = new IHSS_Team();
	endif;
	
	if (get_theme_support('ih-counters')) :	
		$counter_inst = new IHSS_Counter();
	endif;
	
	if (get_theme_support('ih-parallax')) :
		$parallax_inst = new IHSS_Parallax();
	endif;
}
add_action( 'init', 'ihss_init' );