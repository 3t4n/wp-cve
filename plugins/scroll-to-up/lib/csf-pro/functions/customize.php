<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * WP Customize custom panel
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'WP_Customize_Panel_CSF' ) ) {
  class WP_Customize_Panel_CSF extends WP_Customize_Panel {
    public $type = 'csf';
  }
}

/**
 *
 * WP Customize custom section
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'WP_Customize_Section_CSF' ) ) {
  class WP_Customize_Section_CSF extends WP_Customize_Section {
    public $type = 'csf';
  }
}

/**
 *
 * WP Customize custom control
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'WP_Customize_Control_CSF' ) ) {
  class WP_Customize_Control_CSF extends WP_Customize_Control {

    public $type   = 'csf';
    public $field  = '';
    public $unique = '';

    public function render_content() {

      $unallows   = array( 'wysiwyg' );
      $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
      $complex    = apply_filters( 'csf/customize/complex', array( 'hb', 'checkbox', 'sorter', 'image_select', 'background', 'typography', 'fieldset', 'group', 'repeater', 'tabbed' ) );
      $custom     = ( ! empty( $this->field['customizer'] ) ) ? true : false;
      $is_complex = ( in_array( $this->field['type'], $complex ) ) ? true : false;
      $class      = ( $is_complex || $custom ) ? ' csf-customize-complex' : '';

      echo '<div class="csf-customize-field'. $class .'" data-unique-id="'. $this->unique .'" data-option-id="'. $field_id .'">';

      if( ! $is_complex && ! $custom ) {
        $this->field['attributes']['data-customize-setting-link'] = $this->settings['default']->id;
      }

      $this->field['name'] = $this->settings['default']->id;

      if( in_array( $this->field['type'], $unallows ) ) { $this->field['_notice'] = true; }

      echo csf_add_field( $this->field, $this->value(), $this->unique, 'customize' );

      echo '</div>';

    }

  }
}

function cs_customize_controls_print_footer_scripts() {
  echo '
  <div id="hb" class="hb">

    <div class="csf-text-left"><a href="#" class="hb-edit-1 button">Edit #1</a></div>
    <div class="csf-text-center"><a href="#" class="hb-edit-2 button">Edit #2</a></div>
    <div class="csf-text-right"><a href="#" class="hb-edit-3 button">Edit #3</a></div>

    <div class="csf-text-center"><a href="#" class="hb-close button">Close</a></div>

  </div>
  ';
}
add_action( 'customize_controls_print_footer_scripts', 'cs_customize_controls_print_footer_scripts' );
