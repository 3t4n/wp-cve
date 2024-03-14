<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_backup' ) ) {
  class CSF_Field_backup extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      $nonce   = wp_create_nonce( 'csf_backup' );
      $options = get_option( $this->unique );
      $export  = esc_url( add_query_arg( array(
        'action'  => 'csf-export-options',
        'export'  => $this->unique,
        'wpnonce' => $nonce
      ), admin_url( 'admin-ajax.php' ) ) );

      if( ! empty( $options['_transient'] ) ) {
        unset( $options['_transient'] );
      }

      echo $this->element_before();

      echo '<textarea name="_nonce" class="csf-import-data"></textarea>';
      echo '<a href="#" class="button button-primary csf-confirm csf-import-js">'. __( 'Import a Backup', 'csf' ) .'</a>';
      echo '<small>( '. __( 'copy-paste your backup string here', 'csf' ).' )</small>';

      echo '<hr />';
      echo '<textarea name="_nonce" class="csf-export-data" disabled="disabled">'. csf_encode_string( $options ) .'</textarea>';
      echo '<a href="'. $export .'" class="button button-primary" target="_blank">'. __( 'Export and Download Backup', 'csf' ) .'</a>';

      echo '<hr />';
      echo '<a href="#" class="button button-primary csf-warning-primary csf-confirm csf-reset-js">'. __( 'Reset All Options', 'csf' ) .'</a>';
      echo '<small class="csf-text-warning">'. __( 'Please be sure for reset all of framework options.', 'csf' ) .'</small>';

      echo '<div class="csf-data" data-unique="'. $this->unique .'" data-wpnonce="'. $nonce .'"></div>';

      echo $this->element_after();

    }

  }
}
