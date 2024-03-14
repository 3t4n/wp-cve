<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Filter_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/product-filter-types-products.php');
        
    class WModes_Admin_Product_Filter_Types {

        public static function get_groups( $args ) {

            return apply_filters( 'wmodes-admin/get-product-filter-groups', array(), $args );
        }

        public static function get_product_filters( $group_id, $args ) {

            return apply_filters( 'wmodes-admin/get-' . $group_id . '-group-product-filters', array(), $args );
        }

        public static function get_product_filter_fields( $filter_id, $args ) {

            $args[ 'filter_id' ] = $filter_id;

            $in_fields = array(
                array(
                    'id' => 'is_req',
                    'type' => 'select2',
                    'default' => '>=',
                    'options' => array(
                        'yes' => esc_html__( 'Required', 'wmodes-tdm' ),
                        'no' => esc_html__( 'Optional', 'wmodes-tdm' ),
                    ),
                    'width' => '98%',
                    'box_width' => '19%',
                ),
            );

            return apply_filters( 'wmodes-admin/get-' . $filter_id . '-product-filter-fields', $in_fields, $args );
        }

    }

}