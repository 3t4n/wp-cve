<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class EDSFramework_Option_backup extends EDSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    echo '<textarea name="'. $this->unique .'[import]"'. $this->element_class() . $this->element_attributes() .'></textarea>';
    submit_button( __( 'Import a Backup', 'eds-framework' ), 'primary cs-import-backup', 'backup', false );
    echo '<small>( '. __( 'copy-paste your backup string here', 'eds-framework' ).' )</small>';

    echo '<hr />';

    echo '<textarea name="_nonce"'. $this->element_class() . $this->element_attributes() .' disabled="disabled">'. eds_encode_string( get_option( $this->unique ) ) .'</textarea>';
    echo '<a href="'. admin_url( 'admin-ajax.php?action=cs-export-options' ) .'" class="button button-primary" target="_blank">'. __( 'Export and Download Backup', 'eds-framework' ) .'</a>';
    echo '<small>-( '. __( 'or', 'eds-framework' ) .' )-</small>';
    submit_button( __( 'Reset All Options', 'eds-framework' ), 'cs-warning-primary cs-reset-confirm', $this->unique . '[resetall]', false );
    echo '<small class="cs-text-warning">'. __( 'Please be sure for reset all of framework options.', 'eds-framework' ) .'</small>';

    echo $this->element_after();

  }

}
