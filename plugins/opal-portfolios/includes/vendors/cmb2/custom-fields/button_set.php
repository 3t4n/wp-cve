<?php

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Button_Set {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    public function __construct() {
        add_filter( 'cmb2_render_opal_button_set', array( __CLASS__, 'render' ), 10, 5 );
    }

    /**
     * Render field
     */
    public static function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $value_input = explode( '|', $field_escaped_value );
        $show_fields = json_encode( ( isset( $field->args['show_fields'] ) && is_array( $field->args['show_fields'] ) ) ? $field->args['show_fields'] : array() );
        $style       = isset( $field->args['css_style'] ) ? ' ' . $field->args['css_style'] : '';
        $allow_set   = isset( $field->args['allow_set'] ) ? $field->args['allow_set'] : true;
        echo "<div class=\"btn-group\" data-allow-set='{$allow_set}' data-type='opal-button-set' data-id=\"#{$field->_id()}\" data-show-fields='{$show_fields}'>";
        foreach ($field->args['options'] as $key => $value) {
            $is_checked = in_array( $key, $value_input ) ? ' opal-checked' : '';
            echo "<button type=\"button\" data-value='{$key}' class=\"btn btn-secondary{$style}{$is_checked}\">{$value}</button>";
        }
        echo " </div > ";
        echo $field_type_object->input( array( 'type' => 'hidden' ) );
    }
}

new OSF_Field_Button_Set();