<?php
if (!defined( 'ABSPATH' )){
    exit;
}

/**
 * Class OSF_CMB2Box_Slider
 */
class OSF_CMB2Box_Slider {
    const VERSION = '1.0.0';

    public function __construct() {
        add_filter( 'cmb2_render_opal_slider', array( $this, 'render' ), 10, 5 );
    }

    /**
     * Enqueue control related scripts/styles.
     *
     * @return  void
     */
    public function enqueue() {
        // Load jQuery UI
        wp_enqueue_style( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-slider' );
    }

    /**
     * @param $field             CMB2_Field
     * @param $field_escaped_value
     * @param $field_object_id
     * @param $field_object_type
     * @param $field_type_object CMB2_Types
     */
    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        // Load jQuery UI
        $field_escaped_value = $this->sanitize_value( $field_escaped_value );
        wp_enqueue_style( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-slider' );
        $atts = $field->args['attrs'];
        echo '<div class="cmb2-slider-element customize-control-content otf-customize-slider" id="' . $field->_id() . '">
            <div class="otf-slider wp-slider"
                 data-min="' . ( isset( $atts['min'] ) ? esc_attr( $atts['min'] ) : '0' ) . '"
                 data-max="' . ( isset( $atts['max'] ) ? esc_attr( $atts['max'] ) : '100' ) . '"
                 data-step="' . ( isset( $atts['step'] ) ? esc_attr( $atts['step'] ) : '1' ) . '"
                 data-unit="' . ( isset( $atts['unit'] ) ? esc_attr( $atts['unit'] ) : '' ) . '"
                 data-default-value="' . esc_attr( $field->args['default'] ) . '"
                 data-value="' . esc_attr( $field_escaped_value ) . '"
                 data-id="' . $field->_id() . '"
                 data-highlight="true">
            </div>
            ' . $field_type_object->input( array( 'type' => 'hidden', 'opal-hidden' => 'true' ) ) . '
        </div>';
    }

    public function sanitize_value($value) {
        if (!is_numeric( $value )){
            $sanitized_value = 0;
        } else{
            $sanitized_value = absint( $value );
        }

        return $sanitized_value;
    }
}

new OSF_CMB2Box_Slider();