<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'egs_get_icons' ) ) {
  function egs_get_icons() {

    do_action( 'eds_add_icons_before' );

    $jsons = apply_filters( 'eds_add_icons_json', glob( EDS_F_DIR . '/fields/icon/*.json' ) );

    if( ! empty( $jsons ) ) {

      foreach ( $jsons as $path ) {

        $object = egs_get_icon_fonts( 'fields/icon/'. basename( $path ) );

        if( is_object( $object ) ) {

          echo ( count( $jsons ) >= 2 ) ? '<h4 class="cs-icon-title">'. $object->name .'</h4>' : '';

          foreach ( $object->icons as $icon ) {
            echo '<a class="cs-icon-tooltip" data-cs-icon="'. $icon .'" data-title="'. $icon .'"><span class="cs-icon cs-selector"><i class="'. $icon .'"></i></span></a>';
          }

        } else {
          echo '<h4 class="cs-icon-title">'. __( 'Error! Can not load json file.', 'eds-framework' ) .'</h4>';
        }

      }

    }

    do_action( 'eds_add_icons' );
    do_action( 'eds_add_icons_after' );

    die();
  }
  add_action( 'wp_ajax_eds-get-icons', 'egs_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'eds_export_options' ) ) {
  function eds_export_options() {

    header('Content-Type: plain/text');
    header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo eds_encode_string( get_option( EDS_OPTION ) );

    die();
  }
  add_action( 'wp_ajax_cs-export-options', 'eds_export_options' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'eds_set_icons' ) ) {
  function eds_set_icons() {

    echo '<div id="cs-icon-dialog" class="cs-dialog" title="'. __( 'Add Icon', 'eds-framework' ) .'">';
    echo '<div class="cs-dialog-header cs-text-center"><input type="text" placeholder="'. __( 'Search a Icon...', 'eds-framework' ) .'" class="cs-icon-search" /></div>';
    echo '<div class="cs-dialog-load"><div class="cs-icon-loading">'. __( 'Loading...', 'eds-framework' ) .'</div></div>';
    echo '</div>';

  }
  add_action( 'admin_footer', 'eds_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'eds_set_icons' );
}
