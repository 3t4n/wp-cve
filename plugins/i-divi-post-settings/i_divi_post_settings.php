<?php
/*
 * Plugin Name: i-Divi Post Settings
 * Plugin URI:  http://www.howidivit.com/divi_post_settings
 * Description: The plugin add some fields in Divi Theme Customizer from which you can set your favorite default post, page and project settings.
 * Author:      Dan Mardis - Howidivit.com
 * Version:     1.3.3
 * Author URI:  http://www.howidivit.com
 */

 // Prevent file from being loaded directly
 if ( ! defined( 'WPINC' ) ) {
 	die( 'Sorry. No sufficient permissions.' );
 }

 /**
  * The code that runs during plugin activation.
  */
 function activate_idivi_post_settings() {
 	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-settings-activator.php';
 	idivi_post_settings_Activator::activate();
 }

 /**
  * The code that runs during plugin deactivation.
  */
  function deactivate_idivi_post_settings() {
  	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-settings-deactivator.php';
  	idivi_post_settings_Deactivator::deactivate();
  }

 register_activation_hook( __FILE__, 'activate_idivi_post_settings' );
 register_deactivation_hook( __FILE__, 'deactivate_idivi_post_settings' );


/**
 * The core plugin class that is used to define admin-specific hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-settings.php';

/**
 * Custom meta-box alert the user where new Divi Post Settings can be changed.
  *
	* @since   1.3
	*
 */
function idivi_single_settings_meta_box() {
  $post_id = get_the_ID();
  $post_type = get_post_type( $post_id );

  $image_path = plugin_dir_url(__FILE__) . 'admin/images/redArrow.png';
  echo '<div class="idivi_metabox"><p>You can set Divi ' . ucfirst($post_type) . ' Settings both from the Default editor Page Settings and <b>from the Visual Builder</b> Page Settings!</p></div>';
  //echo '<img style="max-height: 50px;" src="' . $image_path . '" alt="">';
}

/**
 * Begins execution of the plugin.
 */
function run_idivi_post_settings() {

	$plugin = new idivi_post_settings();
	$plugin->run();

}
run_idivi_post_settings();
?>
