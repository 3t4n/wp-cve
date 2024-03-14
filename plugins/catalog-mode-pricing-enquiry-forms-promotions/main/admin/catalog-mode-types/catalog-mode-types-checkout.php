<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_Types_Checkout' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Catalog_Mode_Types_Checkout {

        public static function init() {

            add_filter( 'wmodes-admin/catalog-modes/get-cart-mode-types', array( new self(), 'get_types' ), 10, 2 );

            add_filter( 'wmodes-admin/catalog-modes/get-mode-type-checkout-fields', array( new self(), 'get_fields' ), 10, 2 );
        }

        public static function get_types( $in_options, $args = array() ) {

            if ( !$args[ 'is_global' ] ) {

                return $in_options;
            }

            $in_options[ 'checkout' ] = array(
                'title' => esc_html__( 'Checkout Page Settings', 'wmodes-tdm' ),
            );

            return $in_options;
        }

        public static function get_fields( $in_fields, $args = array() ) {

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 2,
                'full_width' => true,
                'center_head' => true,
                'merge_fields' => false,
                'field_css_class' => array( 'rn-first' ),
                'fields' => self::get_settings_fields( $args ),
            );

            return $in_fields;
        }

        private static function get_settings_fields( $args ) {

            $in_fields = array();


            $in_fields[] = array(
                'id' => 'restrict_checkout',
                'type' => 'select2',
                'column_size' => 1,
                'column_title' => esc_html__( 'Restrict Access', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Restricts access to "Checkout" page and disable all checkout functions', 'wmodes-tdm' ),
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wmodes-tdm' ),
                    'no' => esc_html__( 'No', 'wmodes-tdm' ),
                ),
                'width' => '100%',
                'fold_id' => 'restrict_checkout'
            );

            return $in_fields;
        }

    }

    WModes_Admin_Catalog_Mode_Types_Checkout::init();
}

