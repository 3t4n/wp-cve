<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_icon' ) ) {
  class CSF_Field_icon extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $value  = $this->element_value();
      $hidden = ( empty( $value ) ) ? ' hidden' : '';

      echo '<div class="csf-icon-select">';
      echo '<span class="csf-icon-preview'. $hidden .'"><i class="'. $value .'"></i></span>';
      echo '<a href="#" class="button button-primary csf-icon-add">'. __( 'Add Icon', 'csf' ) .'</a>';
      echo '<a href="#" class="button csf-warning-primary csf-icon-remove'. $hidden .'">'. __( 'Remove Icon', 'csf' ) .'</a>';
      echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class( 'csf-icon-value' ) . $this->element_attributes() .' />';
      echo '</div>';

      echo $this->element_after();

    }

  }
}
