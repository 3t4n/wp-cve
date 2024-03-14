<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_typography' ) ) {
  class CSF_Field_typography extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $defaults_value = apply_filters( 'csf/field/fonts/defaults', array(
        'family' => 'Arial',
        'weight' => '',
      ));

      $weights = apply_filters( 'csf/field/fonts/weights', array(
        ''    => 'Default',
        '100' => '100 | Thin',
        '200' => '200 | Extra Light',
        '300' => '300 | Light',
        '400' => '400 | Normal',
        '500' => '500 | Medium',
        '600' => '600 | Semi Bold',
        '700' => '700 | Bold',
        '800' => '800 | Extra Bold',
      ));

      $websafe_fonts = apply_filters( 'csf/field/fonts/websafe', array(
        'Arial',
        'Arial Black',
        'Comic Sans MS',
        'Impact',
        'Lucida Sans Unicode',
        'Tahoma',
        'Trebuchet MS',
        'Verdana',
        'Courier New',
        'Lucida Console',
        'Georgia, serif',
        'Palatino Linotype',
        'Times New Roman'
      ));

      $value        = wp_parse_args( $this->element_value(), $defaults_value );
      $family_value = $value['family'];
      $weight_value = $value['weight'];
      $is_weight    = ( isset( $this->field['weight'] ) && $this->field['weight'] === false ) ? false : true;
      $is_chosen    = ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
      $google_json  = csf_get_google_fonts();
      $chosen_rtl   = ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

      if( is_object( $google_json ) ) {

        echo '<label class="csf-typography-family">';
        echo '<select name="'. $this->element_name( '[family]' ) .'" class="'. $is_chosen . $chosen_rtl .'csf-typo-family" data-atts="family"'. $this->element_attributes() .'>';

        do_action( 'csf/typography/family', $family_value, $this );

        echo '<optgroup label="'. __( 'Web Safe Fonts', 'csf' ) .'">';
        foreach ( $websafe_fonts as $websafe_font ) {
          echo '<option value="'. $websafe_font .'">'. $websafe_font .'</option>';
        }
        echo '</optgroup>';

        echo '<optgroup label="'. __( 'Google Fonts', 'csf' ) .'">';
        foreach ( $google_json->items as $google_font ) {
          echo '<option value="'. $google_font->family .'"'. selected( $google_font->family, $family_value, true ) .'>'. $google_font->family .'</option>';
        }
        echo '</optgroup>';

        echo '</select>';
        echo '</label>';

        if( ! empty( $is_weight ) ) {

          echo '<label class="csf-typography-weight">';
          echo '<select name="'. $this->element_name( '[weight]' ) .'" class="'. $is_chosen . $chosen_rtl .'csf-typo-weight" data-atts="weight">';
          foreach ( $weights as $weight_key => $weight ) {
            echo '<option value="'. $weight_key .'"'. $this->checked( $weight_value, $weight_key, 'selected' ) .'>'. $weight .'</option>';
          }
          echo '</select>';
          echo '</label>';

        }

      } else {

        echo __( 'Error! Can not load json file.', 'csf' );

      }

      echo $this->element_after();

    }

  }
}
