<?php

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Text_Price {

    public function __construct() {
        add_filter( 'cmb2_render_opal_text_price', array( $this, 'render' ), 10, 5 );
    }

    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        echo '<input type="text" class="regular-text" opal-format-price="true" data-id="#' . esc_attr( $field->_id() ) . '" value="' . esc_attr( $field_escaped_value ) . '">';
        echo $field_type_object->input( array( 'type' => 'hidden' ) );
    }
}

new OSF_Field_Text_Price();