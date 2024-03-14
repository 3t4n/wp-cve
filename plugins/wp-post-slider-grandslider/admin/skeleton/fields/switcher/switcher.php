<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WPPSGS_Field_switcher' ) ) {
  class WPPSGS_Field_switcher extends WPPSGS_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' wppsgs--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'wppsgs' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'wppsgs' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo $this->field_before();

      echo '<div class="wppsgs--switcher'. esc_attr( $active ) .'"'. $text_width .'>';
      echo '<span class="wppsgs--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="wppsgs--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="wppsgs--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />';
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="wppsgs--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo $this->field_after();

    }

  }
}
