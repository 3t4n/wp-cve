<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_MetaBox_Panel_Options' ) ) {

    class WModes_Admin_Product_Prices_MetaBox_Panel_Options {

        private static $mode_types = array();

        public static function init() {

            add_filter( 'wmodes-admin/product-pricing/get-mbx-panel-option-fields', array( new self(), 'get_fields' ), 10, 2 );
            add_filter( 'wmodes-admin/product-pricing/get-mbx-panels', array( new self(), 'get_mode_fields' ), 10, 2 );
        }

        public static function get_fields( $in_fields, $product_id ) {

            $args = array(
                'module' => 'product-pricing',
                'is_global' => false,
                'product_id' => $product_id,
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 2,
                'merge_fields' => false,
                'fields' => array(
                    array(
                        'id' => 'mode',
                        'type' => 'select2',
                        'column_size' => 1,
                        'column_title' => esc_html__( 'Mode', 'wmodes-tdm' ),
                        'tooltip' => esc_html__( 'Controls product prices mode', 'wmodes-tdm' ),
                        'default' => 'sale',
                        'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                        'options' => self::get_mode_types( $args ),
                        'width' => '100%',
                        'fold_id' => 'product_prices_mode',
                    ),
                    array(
                        'id' => 'admin_note',
                        'type' => 'textbox',
                        'tooltip' => esc_html__( 'Adds a private note for reference purposes', 'wmodes-tdm' ),
                        'column_size' => 1,
                        'column_title' => esc_html__( 'Admin Note', 'wmodes-tdm' ),
                        'default' => '',
                        'placeholder' => esc_html__( 'Type here...', 'wmodes-tdm' ),
                        'width' => '100%',
                    ),
                ),
            );

            return $in_fields;
        }

        public static function get_mode_fields( $in_fields, $product_id ) {

            $args = array(
                'module' => 'product-pricing',
                'is_global' => false,
                'product_id' => $product_id,
            );

            foreach ( array_keys( self::get_mode_types( $args ) ) as $mode_type ) {
                $fields = apply_filters( 'wmodes-admin/product-pricing/get-mode-type-' . $mode_type . '-fields', array(), $args );
                foreach ( $fields as $field ) {
                    if ( !isset( $field[ 'fold' ] ) ) {
                        $field[ 'fold' ] = array(
                            'target' => 'product_prices_mode',
                            'attribute' => 'value',
                            'value' => $mode_type,
                            'oparator' => 'eq',
                        );
                    }
                    if ( !isset( $field[ 'css_class' ] ) ) {
                        $field[ 'css_class' ] = array();
                    }
                    $field[ 'css_class' ][] = 'wmodes-padded-panel';
                    $in_fields[] = $field;
                }
            }

            return $in_fields;
        }

        private static function get_mode_types( $args ) {
            if ( count( self::$mode_types ) > 0 ) {
                return self::$mode_types;
            }

            self::$mode_types = WModes_Admin_Product_Prices_Types::get_modes( $args );;

            return self::$mode_types;
        }

    }

    WModes_Admin_Product_Prices_MetaBox_Panel_Options::init();
}
