<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://beescripts.com
 * @since             1.0.0
 * @package           Bee_Slider
 *
 * @wordpress-plugin
 * Plugin Name:       Bee Layer Slider
 * Plugin URI:        http://beescripts.com
 * Description:       Responsive layer slider with multi text layer,background option for each slide and animations .
 * Version:           1.1
 * Author:            aumsrini
 * Author URI:        http://beescripts.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bee-slider
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'RW_Meta_Box' ) )
   { 
	require_once plugin_dir_path( __FILE__ ) . 'includes/framework/meta-box.php'; // Path to the plugin's main file

	
	}
	
if ( ! class_exists( 'MB_Columns' ) )
{
	require_once  plugin_dir_path( __FILE__ ) . 'includes/framework/extn/meta-box-columns/meta-box-columns.php'; // Path to the extension's main file
	}
	
	if ( ! function_exists( 'mb_settings_page_load' ) ) {
		require_once  plugin_dir_path( __FILE__ ) . 'includes/framework/extn/mb-settings-page/mb-settings-page.php';
	}
	
	if ( ! class_exists( 'RWMB_Group' ) ) {
	require_once  plugin_dir_path( __FILE__ ) . 'includes/framework/extn/meta-box-group/meta-box-group.php'; // Path to the extension's main file
	}
	
	if ( ! class_exists( 'MB_Tabs' ) )
{	
	require_once  plugin_dir_path( __FILE__ ) . 'includes/framework/extn/meta-box-tabs/meta-box-tabs.php'; // Path to the extension's main file
	
	}

	require_once  plugin_dir_path( __FILE__ ) . 'includes/bee-slider-functions.php';
	

add_filter( 'rwmb_meta_boxes', 'bee_slider_form' );
function bee_slider_form( $bee_meta_boxes ) {
	$bee_meta_boxes[] = array(
		'title'  => __( 'Slides Details' ),
		'post_types' => 'beeslider',
		'fields' => array(
			array(
				'id'     => 'bee_slide_details',
				// Group field
				'type'   => 'group',
				// Clone whole group?
				'clone'  => true,
				// Drag and drop clones to reorder them?
				'sort_clone' => true,
				// Sub-fields
				'fields' => array(
				
				array(
						'name' => __( 'Slide Background Image', 'rwmb' ),
						'id'   => 'bee_slide_bg_img',
						'type' => 'file_input',
						'columns' => 4,
						
						
					),
					array(
						'name' => __( 'Image layer', 'rwmb' ),
						'id'   => 'bee_slide_img',
						'type' => 'file_input',
						'columns' => 4,
						
						
					),
							
					
						array(
						'name' => __( 'Text layer', 'rwmb' ),
						'id'   => 'bee_text_layer',
						'type' => 'text',
						'columns' => 4,
						'clone'   =>true,
						
					),
					
								
					
				),
				
			),
		),
		
	);
	return $bee_meta_boxes;
	

}
add_filter( 'rwmb_group_add_clone_button_text', 'bee_add_clone_button_text', 10, 2 );
function bee_add_clone_button_text( $text, $field ) {
   return __( '+ Add Slide', 'textdomain' );

/*    
     if ( 'group_id' == $field['id'] ) {
         $text = __( '+ Add more chapter', 'textdomain' );
     }
     return $text;*/
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bee-slider-activator.php
 */
function activate_bee_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bee-slider-activator.php';

	Bee_Slider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bee-slider-deactivator.php
 */
function deactivate_bee_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bee-slider-deactivator.php';
	Bee_Slider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bee_slider' );
register_deactivation_hook( __FILE__, 'deactivate_bee_slider' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bee-slider.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
 
function run_bee_slider() {

	$plugin = new Bee_Slider();
	$plugin->run();

}



run_bee_slider();
