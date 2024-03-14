<?php     namespace ng_navgoco;

/*
Plugin Name: Navgoco Vertical Multilevel Slide Menu
Plugin URI: http://wpbeaches.com/
Description: Using Navgoco Vertical Multilevel Slide Menu in WordPress
Author: Neil Gee
Version: 1.1.0
Author URI: http://wpbeaches.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: navgoco-menu
Domain Path: /languages/
*/


  // If called direct, refuse
  if ( ! defined( 'ABSPATH' ) ) {
          die;
  }

/* Assign global variables */

$plugin_url = WP_PLUGIN_URL . '/navgoco';
$options = array();

/**
 * Register our text domain.
 *
 * @since 1.0.0
 */


function load_textdomain() {
  load_plugin_textdomain( 'navgoco-menu', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );

/**
 * Register and Enqueue Scripts and Styles
 *
 * @since 1.0.0
 */

//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued
function scripts_styles() {

$options = get_option( 'navgoco_settings' );

  wp_register_script ( 'navgocojs' , plugins_url( '/js/jquery.navgoco.js',  __FILE__ ), array( 'jquery' ), '0.2.1', false );
  wp_register_script ( 'navgococookie' , plugins_url( '/js/jquery.cookie.min.js',  __FILE__ ), array( 'jquery' ), '1.4.1', false );
  wp_register_style ( 'navgococss' , plugins_url( '/css/navgoco.css',  __FILE__ ), '' , '0.2.1', 'all' );
  wp_register_script ( 'navgoco-init' , plugins_url( '/js/navgoco-init.js',  __FILE__ ), array( 'navgocojs' ), '1.0.0', false );
  wp_register_style ( 'fontawesome' , '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', '' , '4.4.0', 'all' );

  // Add new plugin options defaults here, set them to blank, this will avoid PHP notices of undefined, if new options are introduced to the plugin and are not saved or udated then the setting will be defined.
  $options_default = array(

      'ng_menu_save'          => '',
      'ng_menu_disable_style' => '',
      'ng_menu_selection'     => '',
      'ng_menu_accordion'     => '',
      'ng_menu_html_carat'    => '',
      'ng_slide_easing'       => '',
      'ng_slide_duration'     => '',
  );

  $options = wp_parse_args( $options, $options_default );


  wp_enqueue_script( 'navgocojs' );
   if( (bool) $options['ng_menu_save'] == true ) {
  wp_enqueue_script( 'navgococookie' );
    }
   if( (bool) $options['ng_menu_disable_style'] == false ) {
  wp_enqueue_style( 'navgococss' );
  wp_enqueue_style( 'fontawesome' );
    }

     $data = array (

      'ng_navgo' => array(

			'ng_menu_selection'  => esc_html($options['ng_menu_selection']),
			'ng_menu_accordion'  => (bool)$options['ng_menu_accordion'],
			'ng_menu_html_carat' => $options['ng_menu_html_carat'],
			'ng_slide_easing'    => esc_html($options['ng_slide_easing']),
			'ng_slide_duration'  => (int)$options['ng_slide_duration'],
			'ng_menu_save'       => (bool)$options['ng_menu_save'],

      ),
  );

     //add filter
    $data = apply_filters( 'ng_navgoco_navgocoVars', $data );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'navgoco-init', 'navgocoVars', $data );

    wp_enqueue_script( 'navgoco-init' );

}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_styles' );

/**
 * Register our option fields
 *
 * @since 1.0.0
 */

function plugin_settings(){
  register_Setting(
        'ng_settings_group', //option name
        'navgoco_settings',// option group setting name and option name
        __NAMESPACE__ . '\\navgoco_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'ng_navgoco_section', //declare the section id
        'Navgoco Settings', //page title
         __NAMESPACE__ . '\\ng_navgoco_section_callback', //callback function below
        'navgoco' //page that it appears on

    );

  add_settings_field(
        'ng_menu_selection', //unique id of field
        'Add Menu ID or Class', //title
         __NAMESPACE__ . '\\ng_menu_id_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_menu_accordion', //unique id of field
        'Accordion Effect', //title
         __NAMESPACE__ . '\\ng_menu_accordion_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );

   add_settings_field(
        'ng_menu_html_carat', //unique id of field
        'HTML Carat Markup', //title
         __NAMESPACE__ . '\\ng_menu_html_carat_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
     add_settings_field(
        'ng_slide_duration', //unique id of field
        'Slide Duration', //title
         __NAMESPACE__ . '\\ng_slide_duration_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
     add_settings_field(
        'ng_slide_easing', //unique id of field
        'Slide Transition', //title
         __NAMESPACE__ . '\\ng_slide_easing_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
     add_settings_field(
        'ng_menu_save', //unique id of field
        'Save Menu State', //title
         __NAMESPACE__ . '\\ng_menu_save_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
     add_settings_field(
        'ng_menu_disable_style', //unique id of field
        'Disable Navgoco Default CSS Style', //title
         __NAMESPACE__ . '\\ng_menu_disable_style_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
}
add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');

/**
 * Sanitize our inputs
 *
 * @since 1.0.0
 */

function navgoco_validate_input( $input ) {
   // Create our array for storing the validated options
    $output = array();

    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
    	if( isset( $input['ng_menu_html_carat'] ) ) {

            // Keep HTML in this field
           $output['ng_menu_html_carat'] = wp_kses_post($input['ng_menu_html_carat']);
			//$output['ng_menu_html_carat'] = wp_filter_post_kses( wp_slash( $input['ng_menu_html_carat'] ) ); // wp_filter_post_kses() expects slashed

        } // end if

        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {

            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

        } // end if


    } // end foreach

    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'navgoco_validate_input' , $output, $input );
}

/**
 * Register our section call back
 * (not much happening here)
 * @since 1.0.0
 */

function ng_navgoco_section_callback() {

}

/**
 * Register Menu ID to use as Navgoco Menu
 *
 * @since 1.0.0
 */

function ng_menu_id_callback() {
$options = get_option( 'navgoco_settings' );

if( !isset( $options['ng_menu_selection'] ) ) $options['ng_menu_selection'] = '';


echo '<input type="text" id="ng_menu_selection" name="navgoco_settings[ng_menu_selection]" value="' . sanitize_text_field($options['ng_menu_selection']) . '" placeholder="Add Menu ID to use as Navgoco Vertical Menu" class="regular-text" >';
echo '<label for="ng_menu_selection">' . esc_attr_e( 'Add Menu ID or Class to use as Navgoco Vertical Menu, comma separate multiple menus','navgoco') . '</label>';
}

/**
 *  Menu Accordion
 *
 * @since 1.0.0
 */

function ng_menu_accordion_callback() {
$options = get_option( 'navgoco_settings' );

if( !isset( $options['ng_menu_accordion'] ) ) $options['ng_menu_accordion'] = '';


  echo'<input type="checkbox" id="ng_menu_accordion" name="navgoco_settings[ng_menu_accordion]" value="1"' . checked( 1, $options['ng_menu_accordion'], false ) . '/>';
  echo'<label for="ng_menu_accordion">' . esc_attr_e( 'Check to enable Accordion effect on menu, (closes menu item when you open a new one)','navgoco') . '</label>';

}

/**
 * Insert HTML for carat
 *
 * @since 1.0.0
 */

function ng_menu_html_carat_callback() {
$options = get_option( 'navgoco_settings' );

if( !isset( $options['ng_menu_html_carat'] ) ) $options['ng_menu_html_carat'] = '';

echo '<input type="text" id="ng_menu_html_carat" name="navgoco_settings[ng_menu_html_carat]" value="' . esc_attr($options['ng_menu_html_carat']) . '" placeholder="Add custom HTML mark up" class="regular-text">';
echo '<label for="ng_menu_html_carat">' . esc_attr_e( 'Insert additional HTML for the dropdown Carat','navgoco') . '</label>';
}

/**
 * Register our Speed Duration callback
 *
 * @since 1.0.0
 */

function ng_slide_duration_callback(){
$options = get_option( 'navgoco_settings' );

  if( !isset( $options['ng_slide_duration'] ) ) $options['ng_slide_duration'] = 400;

  ?>

  <select name="navgoco_settings[ng_slide_duration]" id="ng_slide_duration">
    <option value="200" <?php selected($options['ng_slide_duration'], '200'); ?>>200</option>
    <option value="400" <?php selected($options['ng_slide_duration'], '400'); ?>>400</option>
    <option value="600" <?php selected($options['ng_slide_duration'], '600'); ?>>600</option>
    <option value="800" <?php selected($options['ng_slide_duration'], '800'); ?>>800</option>
    <option value="1000" <?php selected($options['ng_slide_duration'], '1000'); ?>>1000</option>
    <option value="2000" <?php selected($options['ng_slide_duration'], '2000'); ?>>2000</option>
  </select>
   <label for="ng_slide_duration"><?php esc_attr_e( 'Speed of scroll (Lower numbers are faster)', 'navgoco' ); ?></label>
  <?php
}

/**
 * Register our Easing Transition
 *
 * @since 1.0.0
 */

function ng_slide_easing_callback(){
$options = get_option( 'navgoco_settings' );

  if( !isset( $options['ng_slide_easing'] ) ) $options['ng_slide_easing'] = 'swing';

  ?>

  <select name="navgoco_settings[ng_slide_easing]" id="ng_slide_easing">
    <option value="swing" <?php selected($options['ng_slide_easing'], 'swing'); ?>>swing</option>
    <option value="linear" <?php selected($options['ng_slide_easing'], 'linear'); ?>>linear</option>

  </select>
   <label for="ng_slide_easing"><?php esc_attr_e( 'Easing Transitions', 'navgoco' ); ?></label>
  <?php
}

/**
 *  Menu State Between Sessions
 *
 * @since 1.0.0
 */

function ng_menu_save_callback() {
$options = get_option( 'navgoco_settings' );

if( !isset( $options['ng_menu_save'] ) ) $options['ng_menu_save'] = '';


  echo'<input type="checkbox" id="ng_menu_save" name="navgoco_settings[ng_menu_save]" value="1"' . checked( 1, $options['ng_menu_save'], false ) . '/>';
  echo'<label for="ng_menu_save">' . esc_attr_e( 'Check to save Menu state between browser sessions','navgoco') . '</label>';

}

/**
 *  Disable Navgoco Default Style
 *
 * @since 1.0.0
 */

function ng_menu_disable_style_callback() {
$options = get_option( 'navgoco_settings' );

if( !isset( $options['ng_menu_disable_style'] ) ) $options['ng_menu_disable_style'] = '';

  echo'<input type="checkbox" id="ng_menu_disable_style" name="navgoco_settings[ng_menu_disable_style]" value="1"' . checked( 1, $options['ng_menu_disable_style'], false ) . '/>';
  echo'<label for="ng_menu_disable_style">' . esc_attr_e( 'Check to Disable Default Navgoco CSS Stylin and DIY','navgoco') . '</label>';

}

/**
 * Create the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_page() {

    /*
     * Use the add options_page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
     */

     add_options_page(
        __( 'Navgoco Options Plugin','navgoco' ), //$page_title
        __( 'Navgoco Menu', 'navgoco' ), //$menu_title
        'manage_options', //$capability
        'navgoco', //$menu-slug
        __NAMESPACE__ . '\\plugin_options_page' //$function
      );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );

/**
 * Include the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}
