<?php

use MPHB\Views\SingleRoomTypeView;

class MPHB_Divi_Accommodation_Type_Title_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-title';

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Title', 'mphb-divi' );
    }

    public function mphb_get_fields() {
        return array(
            'link_to_post' => array(
                'label' => esc_html__('Link to post', 'mphb-divi'),
                'type' => 'yes_no_button',
                'options' => array(
                    'on' => esc_html__('Yes', 'mphb-divi'),
                    'off' => esc_html__('No', 'mphb-divi'),
                ),
                'default' => 'off',
                'computed_affects' => array(
                    '__html',
                ),
            ),
        );
    }

    public function mphb_render_depends_on() {
        return array( 'link_to_post' );
    }

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();

        $link_to_post = isset( $attrs['link_to_post'] ) ? $attrs['link_to_post'] === 'on' : false;

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        $title_HTML = '';

        self::apply_title_params( $link_to_post );

        if ( $id ) {
            $query = new \WP_Query(array(
                'p' => $id,
                'post_type' => 'mphb_room_type',
            ));

            if ( $query->have_posts() ) {
                ob_start();

                while ( $query->have_posts() ) {
                    $query->the_post();
                    SingleRoomTypeView::renderTitle();
                }

                $title_HTML = ob_get_clean();
            }
            wp_reset_postdata();
        } else {
            ob_start();
                SingleRoomTypeView::renderTitle();
            $title_HTML = ob_get_clean();
        }

        self::restore_title_params( $link_to_post );

        return $title_HTML;
    }

    private static function apply_title_params( $link_to_post ) {
        if ( $link_to_post ) {
            add_action('mphb_render_single_room_type_before_title', array('MPHB_Divi_Accommodation_Type_Title_Module', 'render_link_open'), 15);
            add_action('mphb_render_single_room_type_after_title', array('MPHB_Divi_Accommodation_Type_Title_Module', 'render_link_close'), 5);
        }
    }

    private static function restore_title_params( $link_to_post ) {
        if ( $link_to_post ) {
            remove_action('mphb_render_single_room_type_before_title', array('MPHB_Divi_Accommodation_Type_Title_Module', 'render_link_open'), 15);
            remove_action('mphb_render_single_room_type_after_title', array('MPHB_Divi_Accommodation_Type_Title_Module', 'render_link_close'), 5);
        }
    }

    public static function render_link_open() {
        ?>
        <a href="<?php the_permalink(); ?>">
        <?php
    }

    public static function render_link_close() {
        ?>
        </a>
        <?php
    }

}

new MPHB_Divi_Accommodation_Type_Title_Module();
