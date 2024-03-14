<?php

use MPHB\Views\SingleRoomTypeView;

class MPHB_Divi_Accommodation_Type_Attribute_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-attribute';
    private static $removed_actions = [];
    private static $actions = [
        'adults' => [
            'label' => [
                'mphb_render_single_room_type_before_adults' => '_renderAdultsTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_adults' => '_renderAdultsListItemOpen',
                'mphb_render_single_room_type_after_adults' => '_renderAdultsListItemClose',
            ]

        ],
        'children' => [
            'label' => [
                'mphb_render_single_room_type_before_children' => '_renderChildrenTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_children' => '_renderChildrenListItemOpen',
                'mphb_render_single_room_type_after_children' => '_renderChildrenListItemClose',
            ]
        ],
        'capacity' => [
            'label' => [
                'mphb_render_single_room_type_before_total_capacity' => '_renderTotalCapacityTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_total_capacity' => '_renderTotalCapacityListItemOpen',
                'mphb_render_single_room_type_after_total_capacity' => '_renderTotalCapacityListItemClose',
            ]
        ],
        'amenities' => [
            'label' => [
                'mphb_render_single_room_type_before_facilities' => '_renderFacilitiesTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_facilities' => '_renderFacilitiesListItemOpen',
                'mphb_render_single_room_type_after_facilities' => '_renderFacilitiesListItemClose',
            ]
        ],
        'view' => [
            'label' => [
                'mphb_render_single_room_type_before_view' => '_renderViewTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_view' => '_renderViewListItemOpen',
                'mphb_render_single_room_type_after_view' => '_renderViewListItemClose',
            ]
        ],
        'size' => [
            'label' => [
                'mphb_render_single_room_type_before_size' => '_renderSizeTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_size' => '_renderSizeListItemOpen',
                'mphb_render_single_room_type_after_size' => '_renderSizeListItemClose',
            ]
        ],
        'bed-types' => [
            'label' => [
                'mphb_render_single_room_type_before_bed_type' => '_renderBedTypeTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_bed_type' => '_renderBedTypeListItemOpen',
                'mphb_render_single_room_type_after_bed_type' => '_renderBedTypeListItemClose',
            ]
        ],
        'categories' => [
            'label' => [
                'mphb_render_single_room_type_before_categories' => '_renderCategoriesTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_categories' => '_renderCategoriesListItemOpen',
                'mphb_render_single_room_type_after_categories' => '_renderCategoriesListItemClose',
            ]
        ],
        'custom' => [
            'label' => [
                'mphb_render_single_room_type_before_custom_attribute' => '_renderCustomAttributesTitle',
            ],
            'default' => [
                'mphb_render_single_room_type_before_custom_attribute' => '_renderCustomAttributesListItemOpen',
                'mphb_render_single_room_type_after_custom_attribute' => '_renderCustomAttributesListItemClose',
            ]
        ]
    ];

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Attribute', 'mphb-divi' );
    }

    public function mphb_get_fields() {

        $attributes = self::get_accommodations_attributes_to_select();

        return array(
            'selected_attribute' => array(
                'label' => esc_html__( 'Attributes', 'mphb-divi' ),
                'type' => 'select',
                'options' => $attributes,
                'default' => 'adults',
                'computed_affects' => array(
                    '__html',
                ),
            ),
            'show_label' => array(
                'label' => esc_html__('Show label', 'mphb-divi'),
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

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();
        $attribute =  isset( $attrs['selected_attribute'] ) ? $attrs['selected_attribute'] : 'adults';
        $show_label = isset( $attrs['show_label'] ) && $attrs['show_label'] == 'yes';

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        $current_accommodation = MPHB()->getCurrentRoomType();

        ob_start();

        MPHB()->setCurrentRoomType( $id );

        self::render_attribute( $attribute, $show_label );

        MPHB()->setCurrentRoomType( $current_accommodation ? $current_accommodation->getId() : self::get_current_post_id() );

        return ob_get_clean();
    }

    private static function render_attribute( $attribute, $show_label ) {
        $custom_attribute = '';

        global $mphbAttributes;

        if ( in_array( $attribute, array_keys( $mphbAttributes ) ) ) {
            $custom_attribute = $attribute;
            $attribute = 'custom';
        }

        do_action('mphb-templates/blocks/attribute/before', $attribute, $custom_attribute);

        switch ($attribute):
            case 'adults':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-adults-capacity"><?php
                SingleRoomTypeView::renderAdults();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'children':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-children-capacity"><?php
                SingleRoomTypeView::renderChildren();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'capacity':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-total-capacity"><?php
                SingleRoomTypeView::renderTotalCapacity();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'amenities':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-facilities"><?php
                SingleRoomTypeView::renderFacilities();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'view':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-view"><?php
                SingleRoomTypeView::renderView();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'size':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-size"><?php
                SingleRoomTypeView::renderSize();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'bed-types':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-bed-type"><?php
                SingleRoomTypeView::renderBedType();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'categories':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute mphb-room-type-categories"><?php
                SingleRoomTypeView::renderCategories();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            case 'custom':
                self::before_attribute_render( $attribute, $show_label, $custom_attribute );
                ?><div class="mphb-single-room-type-attribute <?php echo esc_attr('mphb-room-type-' . $custom_attribute . ' mphb-room-type-custom-attribute'); ?>"><?php
                SingleRoomTypeView::renderCustomAttributes();
                ?></div><?php
                self::after_attribute_render( $attribute, $show_label, $custom_attribute );
                break;
            default:
                ?><div class="mphb-single-room-type-attribute mphb-room-type-undefined-attribute"><?php
                esc_html_e('Please choose an attribute from available ones.', 'mphb-divi');
                ?></div><?php
                break;
        endswitch;

        do_action('mphb-templates/blocks/attribute/after', $attribute, $custom_attribute);
    }

    private static function before_attribute_render( $attribute, $show_label, $custom_attribute ) {
        foreach ( self::$actions[ $attribute ][ 'default' ] as $action => $callback ) {
            $priority = has_action( $action, array( '\MPHB\Views\SingleRoomTypeView', $callback ) );

            if ( $priority ) {
                remove_action( $action, array( '\MPHB\Views\SingleRoomTypeView', $callback ), $priority );
                self::$removed_actions[ $callback ] = [
                    'action' => $action,
                    'priority' => $priority
                ];
            }
        }

        if ( ! $show_label ) {
            foreach ( self::$actions[ $attribute ][ 'label' ] as $action => $callback ) {
                $priority = has_action( $action, array( '\MPHB\Views\SingleRoomTypeView', $callback ) );

                if ( $priority ) {
                    remove_action( $action, array('\MPHB\Views\SingleRoomTypeView', $callback ), $priority);
                    self::$removed_actions[ $callback ] = [
                        'action' => $action,
                        'priority' => $priority
                    ];
                }
            }
        }

        if ( 'custom' == $attribute ) {
            global $mphbAttributes;

            foreach ( $mphbAttributes as $key => $attribute ) {
                if ( $key != $custom_attribute ) {
                    $mphbAttributes[ $key ]['visible'] = false;
                }
            }
        }
    }

    private static function after_attribute_render( $attribute, $show_label, $custom_attribute ) {
        foreach ( self::$removed_actions as $callback => $hook ) {
            add_action( $hook['action'], array('\MPHB\Views\SingleRoomTypeView', $callback ), $hook['priority'] );
        }

    }

}

new MPHB_Divi_Accommodation_Type_Attribute_Module();
