<?php

if (!defined( 'ABSPATH' )){
    exit; // Exit if accessed directly
}

class OSF_CMB2_Field_Footer_Layout {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public function __construct() {
        add_filter( 'cmb2_render_opal_footer_layout', array( $this, 'render' ), 10, 5 );
    }

    /**
     * Render field
     */
    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $footers        = $this->get_footers();
        echo $field_type_object->input( array( 'type' => 'hidden' ) );
        $option = '<option value="" selected>'. esc_html__('Default', 'ocbee-core') .'</option>';
        if ($footers){
            foreach ($footers as $footer) {
                $option .= '<option value="' . esc_attr( $footer->post_name ) . '"' . selected( $field_escaped_value, $footer->post_name, false ) . '>' . esc_html( $footer->post_title ) . '</option>';
            };
        }
        echo '<div class="cmb2-footer-layout opal-control-image-select opal-control-footer" data-id="' . $field->_id() . '">
                <div class="select-control footer-select">
                    <select>' . $option . '</select>
                </div>
        </div>';
    }

    private function get_footers() {
        $args = array(
            'post_type'      => 'footer',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        return get_posts( $args );
    }
}

new OSF_CMB2_Field_Footer_Layout();
