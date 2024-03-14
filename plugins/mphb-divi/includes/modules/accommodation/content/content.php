<?php

class MPHB_Divi_Accommodation_Type_Content_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-content';

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Content', 'mphb-divi' );
    }

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        $query = new \WP_Query(array(
            'p' => $id,
            'post_type' => 'mphb_room_type',
        ));

        ob_start();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                the_content();
            }
        }

        wp_reset_postdata();

        return ob_get_clean();

    }

}

new MPHB_Divi_Accommodation_Type_Content_Module();
