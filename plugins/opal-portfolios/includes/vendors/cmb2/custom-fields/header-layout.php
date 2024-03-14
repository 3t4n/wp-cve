<?php

if (!defined( 'ABSPATH' )){
    exit; // Exit if accessed directly
}

class OSF_CMB2_Field_Header_Layout {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public function __construct() {
        add_filter( 'cmb2_render_opal_header_layout', array( $this, 'render' ), 10, 5 );
    }

    /**
     * @param $field
     * @param $field_escaped_value
     * @param $field_object_id
     * @param $field_object_type
     * @param $field_type_object CMB2_Types
     */
    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $headers        = $this->get_headers();
        echo $field_type_object->select();
        $option = '<option value="global" selected>'. esc_html__('Global', 'ocbee-core') .'</option>';
        $option .= '<option value="default" selected>'. esc_html__('Default', 'ocbee-core') .'</option>';
        if ($headers){
            foreach ($headers as $header) {
                $option .= '<option value="' . esc_attr( $header->ID ) . '"' . selected( $field_escaped_value, $header->ID, false ) . '>' . esc_html( $header->post_title ) . '</option>';
            };
        }
        echo '<div class="cmb2-footer-header opal-control-image-select opal-control-footer" data-id="' . $field->_id() . '">
                <div class="select-control footer-select">
                    <select>' . $option . '</select>
                </div>
        </div>';
    }

    private function get_headers() {
        $args = array(
            'post_type'      => 'header',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        return get_posts( $args );
    }
}

new OSF_CMB2_Field_Header_Layout();
