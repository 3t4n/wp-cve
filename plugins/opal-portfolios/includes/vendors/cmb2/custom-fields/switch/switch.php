<?php

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Switch {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public static function init() {
        add_filter( 'cmb2_render_opal_switch', array( __CLASS__, 'render' ), 10, 5 );
        //        add_filter( 'cmb2_sanitize_opal_switch', 'cmb2_sanitize_text_email_callback', 10, 2 );
    }

    /**
     * Render field
     */
    public static function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $checked     = $field_escaped_value != '0' ? 'checked' : '';
        $label       = ( isset( $field->args['label'] ) && is_array( $field->args['label'] ) ) ? $field->args['label'] : array();
        $label       = wp_parse_args( $label, array(
            'yes' => __( 'YES', 'ocbee-core' ),
            'no'  => __( 'NO', 'ocbee-core' ),
        ) );
        $show_fields = json_encode( ( isset( $field->args['show_fields'] ) && is_array( $field->args['show_fields'] ) ) ? $field->args['show_fields'] : array() );
        echo "
<div class='opal-onoffswitch-wrapper'>
    <div class=\"opal-onoffswitch\">
        <input type=\"checkbox\" class=\"onoffswitch-checkbox\" id=\"{$field->_id()}-switch\"  data-show-fields='{$show_fields}' {$checked}>
        <label class=\"onoffswitch-label\" for=\"{$field->_id()}-switch\">
            <span class=\"onoffswitch-inner\" data-yes='" . esc_attr( $label['yes'] ) . "' data-no='" . esc_attr( $label['no'] ) . "'></span>
            <span class=\"onoffswitch-switch\"></span>
        </label>
    </div>
    {$field_type_object->input( array( 'type' => 'hidden', 'class' => 'onoffswitch-input') )}
</div>
";
    }
}
OSF_Field_Switch::init();
