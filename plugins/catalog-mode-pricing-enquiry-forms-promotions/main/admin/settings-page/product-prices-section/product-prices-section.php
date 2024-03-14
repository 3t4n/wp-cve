<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_Page' ) ) {

    class WModes_Admin_Product_Prices_Page {

        public static function init() {
            $option_name = WModes_Admin_Page::get_option_name();

            if ( !defined( 'WMODES_PREMIUM_ADDON' ) ) {
                add_filter( 'get-option-page-' . $option_name . 'section-product_prices-fields', array( new self(), 'get_page_settings' ), 10, 2 );
            }

            add_filter( 'get-option-page-' . $option_name . 'section-product_prices-fields', array( new self(), 'get_page_options' ), 20, 2 );

            add_filter( 'reon/get-repeater-field-product_pricings-templates', array( new self(), 'get_templates' ), 10, 2 );
            add_filter( 'roen/get-repeater-template-product_pricings-product_pricing-fields', array( new self(), 'get_fields' ), 10, 2 );
            add_filter( 'roen/get-repeater-template-product_pricings-product_pricing-head-fields', array( new self(), 'get_head_fields' ), 10, 2 );
        }

        public static function get_page_settings( $in_fields, $section_id ) {

            $in_fields[] = array(
                'id' => 'product_pricing_settings',
                'type' => 'panel',
                'last' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'field_css_class' => array( 'wmodes_apply_mode' ),
                'fields' => array(
                    array(
                        'id' => 'product_prices_any_id',
                        'type' => 'columns-field',
                        'columns' => 8,
                        'merge_fields' => false,
                        'fields' => array(
                            array(
                                'id' => 'limit_type',
                                'type' => 'select2',
                                'column_size' => 2,
                                'column_title' => esc_html__( 'Pricing Limit (Per Product)', 'wmodes-tdm' ),
                                'tooltip' => esc_html__( 'Controls pricing limits per product', 'wmodes-tdm' ),
                                'default' => 'no',
                                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                                'options' => array(
                                    'no' => esc_html__( 'No limit', 'wmodes-tdm' ),
                                ),
                                'width' => '100%',
                            ),
                            array(
                                'id' => 'mode',
                                'type' => 'select2',
                                'column_size' => 3,
                                'column_title' => esc_html__( 'Apply Method (Per Product)', 'wmodes-tdm' ),
                                'tooltip' => esc_html__( 'Controls product pricing apply method per product', 'wmodes-tdm' ),
                                'default' => 'all',
                                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                                'options' => self::get_apply_method(),
                                'width' => '100%',
                            ),
                        ),
                    ),
                ),
            );

            return $in_fields;
        }

        public static function get_page_options( $in_fields, $section_id ) {

            $max_sections = 3;
            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $max_sections = 99999;
            }

            $in_fields[] = array(
                'id' => 'product_pricings',
                'type' => 'repeater',
                'white_repeater' => false,
                'repeater_size' => 'small',
                'accordions' => true,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => true,
                'max_sections' => $max_sections,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more options', 'wmodes-tdm' ),
                'width' => '100%',
                'field_css_class' => array( 'wmodes_options' ),
                'css_class' => 'wmodes_extension_options',
                'auto_expand' => array(
                    'new_section' => true,
                    'cloned_section' => true,
                ),
                'sortable' => array(
                    'enabled' => true,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => false,
                    'button_text' => esc_html__( 'New Product Pricing', 'wmodes-tdm' ),
                ),
            );

            return $in_fields;
        }

        public static function get_templates( $in_templates, $repeater_args ) {
            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {

                $in_templates[] = array(
                    'id' => 'product_pricing',
                    'head' => array(
                        'title' => '',
                        'defaut_title' => esc_html__( 'Product Pricing', 'wmodes-tdm' ),
                        'title_field' => 'admin_note',
                        'subtitle_field' => 'mode',
                    )
                );
            }

            return $in_templates;
        }

        public static function get_fields( $in_fields, $repeater_args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-panels', array() );
        }

        public static function get_head_fields( $in_fields, $repeater_args ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'group-field',
                'position' => 'right',
                'width' => '100%',
                'merge_fields' => false,
                'fields' => array(
                    array(
                        'id' => 'apply_mode',
                        'type' => 'select2',
                        'default' => 'with_others',
                        'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                        'options' => self::get_apply_modes(),
                        'width' => '280px',
                    ),
                    array(
                        'id' => 'enable',
                        'type' => 'select2',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => esc_html__( 'Enable', 'wmodes-tdm' ),
                            'no' => esc_html__( 'Disable', 'wmodes-tdm' ),
                        ),
                        'width' => '95px',
                    ),
                ),
            );

            return $in_fields;
        }

        private static function get_apply_method() {

            $apply_methods = array(
                'all' => esc_html__( 'Apply all valid product pricing', 'wmodes-tdm' ),
            );

            $apply_methods = apply_filters( 'wmodes-admin/product-pricing/get-apply-methods', $apply_methods );

            $apply_methods[ 'no' ] = esc_html__( 'Do not apply any product pricing', 'wmodes-tdm' );

            return $apply_methods;
        }

        private static function get_apply_modes() {

            $apply_modes = array(
                'with_others' => esc_html__( 'Apply this and other product pricing', 'wmodes-tdm' ),
            );

            $apply_modes = apply_filters( 'wmodes-admin/product-pricing/get-apply-modes', $apply_modes );

            return $apply_modes;
        }

    }

}
    