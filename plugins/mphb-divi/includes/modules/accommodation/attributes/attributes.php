<?php

use MPHB\Views\SingleRoomTypeView;

class MPHB_Divi_Accommodation_Type_Attributes_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-attributes';
    private static $custom_attributes = [];
    private static $hidden_attributes = [];
    private static $removed_actions = [];
    private static $setting_to_action = [
        'adults' => [ 'renderAdults' ],
        'children' => [ 'renderChildren' ],
        'capacity' => [ 'renderTotalCapacity' ],
        'amenities' => [ 'renderFacilities' ],
        'view' => [ 'renderView' ],
        'size' => [ 'renderSize' ],
        'bed-types' => [ 'renderBedType' ],
        'categories' => [ 'renderCategories' ]
    ];

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Attributes', 'mphb-divi' );
    }

    public function mphb_get_fields() {

        $attributes = self::get_accommodations_attributes_to_select();

        $default = implode( ',', array_keys( $attributes ) );

        return array(
            'selected_attributes' => array(
                'label' => esc_html__( 'Attributes', 'mphb-divi' ),
                'type' => 'mphb_multiple_checkboxes',
                'options' => $attributes,
                'default' => $default,
                'computed_affects' => array(
                    '__html',
                ),
            ),
        );
    }

    public function mphb_render_depends_on() {
        return array( 'selected_attributes' );
    }

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        $available_attributes = array_keys( self::get_accommodations_attributes_to_select() );
        $selected_attributes = !empty( $attrs['selected_attributes'] ) ? explode( ',', $attrs['selected_attributes'] ) : $available_attributes;

        self::$hidden_attributes = array_diff( $available_attributes, $selected_attributes );

        $current_accommodation = MPHB()->getCurrentRoomType();

        ob_start();

        MPHB()->setCurrentRoomType( $id );
        self::apply_attributes_params();

        SingleRoomTypeView::renderAttributes();

        self::restore_attributes_params();

        MPHB()->setCurrentRoomType( $current_accommodation ? $current_accommodation->getId() : self::get_current_post_id() );

        return ob_get_clean();
    }

    private static function apply_attributes_params() {
        add_action( 'mphb_render_single_room_type_before_attributes', array( self::class, 'remove_attributes_title' ), 0 );
        add_action( 'mphb_render_single_room_type_before_attributes', array( self::class, 'filter_attributes' ) );

        global $mphbAttributes;
        self::$custom_attributes = $mphbAttributes;

        foreach ( self::$custom_attributes as $slug => $attribute ) {
            if ( self::should_hide_attr( $slug ) ) {
                $mphbAttributes[ $slug ][ 'visible' ] = false;
            }
        }
    }

    private static  function restore_attributes_params() {
        foreach( self::$removed_actions as $action => $priority ) {
            add_action( 'mphb_render_single_room_type_attributes', array( '\MPHB\Views\SingleRoomTypeView', $action ), $priority );
        }

        global $mphbAttributes;
        $mphbAttributes = self::$custom_attributes;
    }

    public static function remove_attributes_title() {
        $titlePriority = has_action( 'mphb_render_single_room_type_before_attributes', array( '\MPHB\Views\SingleRoomTypeView', '_renderAttributesTitle' ) );
        remove_action( 'mphb_render_single_room_type_before_attributes', array( '\MPHB\Views\SingleRoomTypeView', '_renderAttributesTitle' ), $titlePriority );
        remove_action( 'mphb_render_single_room_type_before_attributes', array( self::class, 'remove_attributes_title' ), 0 );
    }

    public static function filter_attributes() {
        foreach ( self::$setting_to_action as $setting => $actions ) {
            if ( self::should_hide_attr( $setting ) ) {
                foreach ( $actions as $action ) {
                    $priority = has_action( 'mphb_render_single_room_type_attributes', array( '\MPHB\Views\SingleRoomTypeView', $action ) );

                    if ( $priority ) {
                        remove_action( 'mphb_render_single_room_type_attributes', array( '\MPHB\Views\SingleRoomTypeView', $action ), $priority );
                        self::$removed_actions[ $action ] = $priority;
                    }
                }
            }
        }
    }

    private static function should_hide_attr( $attr ) {
        return in_array( $attr, self::$hidden_attributes );
    }

}

new MPHB_Divi_Accommodation_Type_Attributes_Module();
