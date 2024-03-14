<?php

use MPHB\Views\LoopRoomTypeView;
use MPHB\Views\SingleRoomTypeView;

class MPHB_Divi_Accommodation_Type_Gallery_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-gallery';

    public static $gallery_params = [];
    public static $is_slider = false;

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Gallery', 'mphb-divi' );
    }

    public function mphb_get_fields() {
        return array(
            'image_size' => array(
                'label' => esc_html__('Image size', 'mphb-divi'),
                'type' => 'select',
                'options' => $this->get_image_sizes_select(),
                'computed_affects' => array(
                    '__html',
                ),
            ),
            'is_slider' => array(
                'label' => esc_html__('Display as slider', 'mphb-divi'),
                'description' => esc_html__('Check it out on the frontend once applied.', 'mphb-divi'),
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
            'columns' => array(
                'label' => esc_html__('Columns', 'mphb-divi'),
                'type' => 'range',
                'default' => '4',
                'range_settings'   => array(
                    'min' => '1',
                    'max' => '9',
                    'step' => '1',
                ),
                'computed_affects' => array(
                    '__html',
                ),
            ),
            'link_to' => array(
                'label' => esc_html__('Link to', 'mphb-divi'),
                'type' => 'select',
                'options' => array(
                    '' => esc_html__('Default', 'mphb-divi'),
                    'none' => esc_html__('None', 'mphb-divi'),
                    'file' => esc_html__('File', 'mphb-divi'),
                ),
                'computed_affects' => array(
                    '__html',
                ),
            ),
            'is_lightbox' => array(
                'label' => esc_html__('Open in lightbox', 'mphb-divi'),
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
        return array( 'columns', 'image_size', 'link_to', 'is_lightbox' );
    }

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        $defaults = array(
            'link_to' => '',
            'columns' => '4',
            'image_size' => '',
            'is_lightbox' => ''
        );

        self::$gallery_params = wp_parse_args( $attrs, $defaults );
        self::$is_slider = isset( $attrs['is_slider'] );

        $currentRoomType = MPHB()->getCurrentRoomType();

        MPHB()->setCurrentRoomType( $id );
        self::apply_gallery_params();

        $galleryHTML = '';
        ob_start();

        if ( self::$is_slider ) {
            LoopRoomTypeView::renderGallery();
        } else {
            SingleRoomTypeView::renderGallery();
        }

        $galleryHTML = ob_get_clean();

        self::restore_gallery_params();
        MPHB()->setCurrentRoomType($currentRoomType ? $currentRoomType->getId() : self::get_current_post_id());

        return $galleryHTML;
    }

    private static function apply_gallery_params() {
        if ( self::$is_slider ) {
            add_filter('mphb_loop_room_type_gallery_main_slider_image_link', array(self::class, 'filter_gallery_link'));
            add_filter('mphb_loop_room_type_gallery_main_slider_columns', array(self::class, 'filter_gallery_columns'));
            add_filter('mphb_loop_room_type_gallery_main_slider_image_size', array(self::class, 'filter_gallery_image_size'));
            add_filter('mphb_loop_room_type_gallery_use_nav_slider', array(self::class, 'filter_gallery_nav_slider'));

            add_action('mphb_render_loop_room_type_before_gallery', array(self::class, 'remove_default_slider_wrapper'), 1);
            add_action('mphb_render_loop_room_type_before_gallery', array(self::class, 'render_slider_wrapper_open'));
            add_action('mphb_render_loop_room_type_after_gallery', array(self::class, 'render_slider_wrapper_close'));
            add_filter('mphb_loop_room_type_gallery_main_slider_wrapper_class', array(self::class, 'filter_slider_classes'));
            add_filter('mphb_loop_room_type_gallery_main_slider_flexslider_options', array(self::class, 'filter_slider_attributes'));
        } else {
            add_filter('mphb_single_room_type_gallery_image_link', array(self::class, 'filter_gallery_link'));
            add_filter('mphb_single_room_type_gallery_columns', array(self::class, 'filter_gallery_columns'));
            add_filter('mphb_single_room_type_gallery_image_size', array(self::class, 'filter_gallery_image_size'));
            add_filter('mphb_single_room_type_gallery_use_magnific', array(self::class, 'filter_gallery_lightbox'));
        }
    }

    private static function restore_gallery_params() {
        if ( self::$is_slider ) {
            remove_filter('mphb_loop_room_type_gallery_main_slider_image_link', array(self::class, 'filter_gallery_link'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_columns', array(self::class, 'filter_gallery_columns'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_image_size', array(self::class, 'filter_gallery_image_size'));
            remove_filter('mphb_loop_room_type_gallery_use_nav_slider', array(self::class, 'filter_gallery_nav_slider'));

            remove_action('mphb_render_loop_room_type_before_gallery', array(self::class, 'remove_default_slider_wrapper'), 1);
            remove_action('mphb_render_loop_room_type_before_gallery', array(self::class, 'render_slider_wrapper_open'));
            remove_action('mphb_render_loop_room_type_after_gallery', array(self::class, 'render_slider_wrapper_close'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_wrapper_class', array(self::class, 'filter_slider_classes'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_flexslider_options', array(self::class, 'filter_slider_attributes'));
        } else {
            remove_filter('mphb_single_room_type_gallery_image_link', array(self::class, 'filter_gallery_link'));
            remove_filter('mphb_single_room_type_gallery_columns', array(self::class, 'filter_gallery_columns'));
            remove_filter('mphb_single_room_type_gallery_image_size', array(self::class, 'filter_gallery_image_size'));
            remove_filter('mphb_single_room_type_gallery_use_magnific', array(self::class, 'filter_gallery_lightbox'));
        }
    }

    public static function filter_gallery_link($link) {
        if ( self::$gallery_params['link_to'] ) {
            $link = self::$gallery_params['link_to'];
        }

        if ( self::$is_slider ) {
            $link = 'none';
        }

        return $link;
    }

    public static function filter_gallery_columns($columns) {
        if (self::$gallery_params['columns']) {
            $columns = self::$gallery_params['columns'];
        }

        return $columns;
    }

    public static function filter_gallery_image_size($size) {
        if (self::$gallery_params['image_size']) {
            return self::$gallery_params['image_size'];
        }

        return $size;
    }

    public static function filter_gallery_lightbox($lightbox) {
        if (self::$gallery_params['is_lightbox']) {
            return self::$gallery_params['is_lightbox'] == 'yes';
        }

        return $lightbox;
    }

    public static function filter_gallery_nav_slider() {
        return false;
    }

    public static function remove_default_slider_wrapper() {
        remove_action('mphb_render_loop_room_type_before_gallery', array('\MPHB\Views\LoopRoomTypeView', '_renderImagesWrapperOpen'), 10);
        remove_action('mphb_render_loop_room_type_after_gallery', array('\MPHB\Views\LoopRoomTypeView', '_renderImagesWrapperClose'), 20);
    }

    public static function filter_slider_classes($class) {
        return 'mphb-flexslider-gallery-wrapper';
    }

    public static function render_slider_wrapper_open() {
        ?>
        <div class="mphb-room-type-gallery-wrapper mphb-single-room-type-gallery-wrapper">
        <?php
    }

    public static function render_slider_wrapper_close() {
        ?>
        </div>
        <?php
    }

    public static function filter_slider_attributes($atts) {
        $atts['minItems'] = 1;
        $atts['maxItems'] = (int)self::$gallery_params['columns'] ? (int)self::$gallery_params['columns'] : 1;
        $atts['move'] = 1;

        $atts['itemWidth'] = floor(100 / $atts['maxItems']);

        return $atts;
    }

}

new MPHB_Divi_Accommodation_Type_Gallery_Module();
