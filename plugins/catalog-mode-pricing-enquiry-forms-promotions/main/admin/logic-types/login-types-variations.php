<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Logic_Types_Product_Variations' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Logic_Types_Product_Variations {

        public static function get_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 1,
                'merge_fields' => false,
                'fields' => array(
                    array(
                        'id' => 'is_any',
                        'type' => 'textblock',
                        'column_size' => 1,
                        'column_title' => esc_html__( 'Product Variations', 'wmodes-tdm' ),
                        'tooltip' => sprintf( esc_html__( 'Applies this %s to the specified product variations', 'wmodes-tdm' ), $args[ 'text' ] ),
                        'show_box' => true,
                        'text' => WModes_Admin_Page::get_premium_messages(),
                        'width' => '100%',
                    ),
                ),
                'fold' => array(
                    'target' => 'product-type',
                    'attribute' => 'value',
                    'value' => 'variable',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

    }

}