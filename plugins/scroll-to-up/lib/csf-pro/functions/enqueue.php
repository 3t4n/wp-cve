<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_admin_enqueue_scripts' ) ) {
  function csf_admin_enqueue_scripts() {

    // check for developer mode
    $min = ( defined( 'CSF_DEV_MODE' ) && CSF_DEV_MODE ) ? '' : '.min';

    // admin utilities
    wp_enqueue_media();

    // wp core styles
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'jquery-ui-datepicker' );

    // framework core styles
    wp_enqueue_style( 'faw', CSF_PLUGIN_URL .'/assets/css/font-awesome'. $min .'.css', array(), '4.2.0', 'all' );
    wp_enqueue_style( 'csf', CSF_PLUGIN_URL .'/assets/css/csf'. $min .'.css', array(), '1.0.0', 'all' );

    if ( is_rtl() ) {
      wp_enqueue_style( 'csf-rtl', CSF_PLUGIN_URL .'/assets/css/csf-rtl'. $min .'.css', array(), '1.0.0', 'all' );
    }

    // wp core scripts
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // framework core scripts
    wp_enqueue_script( 'csf-plugins', CSF_PLUGIN_URL .'/assets/js/csf-plugins'. $min .'.js', array(), '1.0.0', true );
    wp_enqueue_script( 'csf',  CSF_PLUGIN_URL .'/assets/js/csf'. $min .'.js', array( 'csf-plugins' ), '1.0.0', true );

  }
  add_action( 'admin_enqueue_scripts', 'csf_admin_enqueue_scripts' );
}
