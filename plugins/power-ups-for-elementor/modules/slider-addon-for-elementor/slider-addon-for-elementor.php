<?php
/*
Plugin Name: Slider Addon For Elementor
Plugin URI: http://wppug.com
Description: Adds the Bearr SlideShow from ThemeBear.
Author: Diego Pereira @ WpPug
Version: 1.0
Author URI: http://wppug.com
*/

function elpug_slider_module() {
	/*
	 * Elementor
	 */
	require ('elementor/extend-elementor.php');
}
elpug_slider_module();