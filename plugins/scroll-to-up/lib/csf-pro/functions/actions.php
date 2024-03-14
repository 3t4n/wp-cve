<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_get_icons' ) ) {
  function csf_get_icons() {

    do_action( 'csf/load/icons/before' );

    $jsons = apply_filters( 'csf/load/icons/json', glob( CSF_PLUGIN_DIR . '/fields/icon/*.json' ) );

    if( ! empty( $jsons ) ) {

      foreach ( $jsons as $path ) {

        $object = csf_get_icon_fonts( $path );

        if( is_object( $object ) ) {

          echo ( count( $jsons ) >= 2 ) ? '<h4 class="csf-icon-title">'. $object->name .'</h4>' : '';

          foreach ( $object->icons as $icon ) {
            echo '<a class="csf-icon-tooltip" data-csf-icon="'. $icon .'" title="'. $icon .'"><span class="csf-icon csf-selector"><i class="'. $icon .'"></i></span></a>';
          }

        } else {
          echo '<h4 class="csf-icon-title">'. __( 'Error! Can not load json file.', 'csf' ) .'</h4>';
        }

      }

    }

    do_action( 'csf/load/icons/after' );

    die();
  }
  add_action( 'wp_ajax_csf-get-icons', 'csf_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_export_options' ) ) {
  function csf_export_options() {

    if( isset( $_GET['export'] ) && isset( $_GET['wpnonce'] ) && wp_verify_nonce( $_GET['wpnonce'], 'csf_backup' ) ) {

      header('Content-Type: plain/text');
      header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
      header('Content-Transfer-Encoding: binary');
      header('Pragma: no-cache');
      header('Expires: 0');

      echo csf_encode_string( get_option( $_GET['export'] ) );

    }

    die();
  }
  add_action( 'wp_ajax_csf-export-options', 'csf_export_options' );
}

/**
 *
 * Import options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_import_options' ) ) {
  function csf_import_options() {

    if( isset( $_POST['unique'] ) && ! empty( $_POST['value'] ) && isset( $_POST['wpnonce'] ) && wp_verify_nonce( $_POST['wpnonce'], 'csf_backup' ) ) {
      update_option( $_POST['unique'], csf_decode_string( $_POST['value'] ) );
    }

    die();
  }
  add_action( 'wp_ajax_csf-import-options', 'csf_import_options' );
}

/**
 *
 * Reset options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_reset_options' ) ) {
  function csf_reset_options() {

    if( isset( $_POST['unique'] ) && isset( $_POST['wpnonce'] ) && wp_verify_nonce( $_POST['wpnonce'], 'csf_backup' ) ) {
      delete_option( $_POST['unique'] );
    }

    die();
  }
  add_action( 'wp_ajax_csf-reset-options', 'csf_reset_options' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_set_icons' ) ) {
  function csf_set_icons() {
    ?>
    <div id="csf-modal-icon" class="csf-modal csf-modal-icon">
      <div class="csf-modal-table">
        <div class="csf-modal-table-cell">
          <div class="csf-modal-overlay"></div>
          <div class="csf-modal-inner">
            <div class="csf-modal-title">
              <?php _e( 'Add Icon', 'csf' ); ?>
              <div class="csf-modal-close csf-icon-close"></div>
            </div>
            <div class="csf-modal-header csf-text-center">
              <input type="text" placeholder="<?php _e( 'Search a Icon...', 'csf' ); ?>" class="csf-icon-search" />
            </div>
            <div class="csf-modal-content"><div class="csf-icon-loading"><?php _e( 'Loading...', 'csf' ); ?></div></div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  add_action( 'admin_footer', 'csf_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'csf_set_icons' );
}
