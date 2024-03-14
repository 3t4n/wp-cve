<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_date' ) ) {
  class CSF_Field_date extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output(){

      $defaults = array(
        'dateFormat' => ( ! empty( $this->field['format'] ) ) ? $this->field['format'] : 'mm/dd/yy',
      );

      $options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

      $args = wp_parse_args( $options, $defaults );

      echo $this->element_before();
      echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
      echo '<textarea class="csf-datepicker-options hidden">'. json_encode( $args ) .'</textarea>';
      echo $this->element_after();

    }

  }
}
