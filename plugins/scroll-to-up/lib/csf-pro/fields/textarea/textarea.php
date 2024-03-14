<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_textarea' ) ) {
  class CSF_Field_textarea extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();
      echo $this->shortcode_generator();
      echo '<textarea name="'. $this->element_name() .'"'. $this->element_class() . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';
      echo $this->element_after();

    }

    public function shortcode_generator() {

      if( ! empty( $this->field['shortcode'] ) ) {

        $btn    = $this->field['shortcode'];
        $unique = $btn['id'];
        $title  = $btn['title'];

        echo '<a href="#" class="button button-primary csf-shortcode-button" data-modal-button-id="'. $unique .'">'. $title .'</a>';

      }

    }
  }
}
