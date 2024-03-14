<?php
/*
Plugin Name: custom progress bar
Plugin URI: http://design.hellothirteen.com/custom-progress-bar/
Description: Add various designed progressbar in your post/ page. also you can use this on widget and customize with your own color and styles 
Author: arif hassan
Author URI: http://design.hellothirteen.com
Version: 2.0
*/



/*Seting Up*/
define('CUSTOM_PROGRESS_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



/* Adding Latest jQuery from Wordpress */

function custom_progressbar_latest_jquery_wp() {
	wp_enqueue_script('jquery');
}
add_action('init', 'custom_progressbar_latest_jquery_wp');



// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');



/* Plugin javascript Main file */

function custom_progressbar_wp_enqueue_jquery() {

	wp_enqueue_script('custom-progress-bootstrap-script', CUSTOM_PROGRESS_PLUGIN_PATH.'js/bootstrap.min.js', array('jquery'));
	wp_enqueue_script('custom-progress-circle-script', CUSTOM_PROGRESS_PLUGIN_PATH.'js/jquery.circlechart.js', array('jquery'));
	wp_enqueue_script('custom-progress-goal-script', CUSTOM_PROGRESS_PLUGIN_PATH.'js/goalProgress.js', array('jquery'));
	wp_enqueue_script('custom-progress-main-script', CUSTOM_PROGRESS_PLUGIN_PATH.'js/main.js', array('jquery'));
}

add_action ('init', 'custom_progressbar_wp_enqueue_jquery');



/* Adding Plugin custm CSS file */
function custom_progressbar_stylesheet_to_admin() {
	wp_enqueue_style('custom-progressbar-bootstrap-style', CUSTOM_PROGRESS_PLUGIN_PATH.'css/custom.bootstrap.css');
	wp_enqueue_style('custom-progressbar-plugin-style', CUSTOM_PROGRESS_PLUGIN_PATH.'custom-progressbar.css');
}
add_action ('init', 'custom_progressbar_stylesheet_to_admin');



function custom_progressbar_shortcode( $atts, $content = null  ) {

	extract( shortcode_atts( array(
		'width' => '40',
		'text' => '',
		'bg_color' => '#2980b9',
		'color' => '#fff'
	), $atts ) );

	return '
		<div class="progress">
			 <div class="progress-bar" role="progressbar" aria-valuenow="'.$width.'" aria-valuemin="0" aria-valuemax="100"
				style="width: '.$width.'%; background-color: '.$bg_color.'">
				<span style="color: '.$color.'" class="sr-only">'.$text.'</span>
			</div>
		</div>
	';
}	
add_shortcode('progressbar_simple', 'custom_progressbar_shortcode');


function custom_progressbar_striped_shortcode( $atts, $content = null  ) {

	extract( shortcode_atts( array(
		'width' => '50',
		'text' => '',
		'bg_color' => '#06d288',
		'color' => '#fff',
		'class' => ''
	), $atts ) );

	return '
		<div class="progress progress-striped '.$class.'">
		  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$width.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$width.'%; background-color: '.$bg_color.'">
			<span style="color: '.$color.'" class="sr-only">'.$text.'</span>
		  </div>
		</div>
	';
}	
add_shortcode('progressbar_striped', 'custom_progressbar_striped_shortcode');


function custom_progressbar_circled( $atts, $content = ''  ) {
	
	extract( shortcode_atts( array(
		'percent' => '60'
	), $atts ) );
	
	
	return $output= '<div class="circle_progress"><div class="percentcircle" data-percent="'.$percent.'"></div></div>';
	
	
}	
add_shortcode('progressbar_circle', 'custom_progressbar_circled');



function custom_multi_progressbar_shortcode( $atts, $content = null  ) {

	extract( shortcode_atts( array(
		'width_1' => '25',
		'width_2' => '20',
		'width_3' => '30',
		'text_1' => '',
		'text_2' => '',
		'text_3' => '',
		'bg_color_1' => '#0f67b9',
		'bg_color_2' => '#2582d9',
		'bg_color_3' => '#6ba1d3',
		'color' => '#fff',
		'class' => ''
	), $atts ) );

	return '
		<div class="progress">
		  <div class="progress-bar progress-bar-success" style="width: '.$width_1.'%; background-color: '.$bg_color_1.'">
			<span style="color: '.$color.'" class="sr-only">'.$text_1.'</span>
		  </div>
		  <div class="progress-bar progress-bar-warning" style="width: '.$width_2.'%; background-color: '.$bg_color_2.'">
			<span style="color: '.$color.'" class="sr-only">'.$text_2.'</span>
		  </div>
		  <div class="progress-bar progress-bar-danger" style="width: '.$width_3.'%; background-color: '.$bg_color_3.'">
			<span style="color: '.$color.'" class="sr-only">'.$text_3.'</span>
		  </div>
		</div>
	';
}	
add_shortcode('pregressbar_multicolor', 'custom_multi_progressbar_shortcode');

?>