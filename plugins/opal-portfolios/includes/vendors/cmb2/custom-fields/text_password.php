<?php

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Text_Password {

    public function __construct() {
        add_filter( 'cmb2_render_text_password', array( $this, 'render' ), 10, 5 );
    }

    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        echo $field_type_object->input( array( 'type' => 'password', 'class' => 'form-control' ) );
    }
}

new OSF_Field_Text_Password();