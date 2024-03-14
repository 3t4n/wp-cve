<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Option_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/product-option-types-groups.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-stock.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-saleschedule.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-shipping.php');
    
    require_once (dirname( __FILE__ ) . '/product-option-types-label.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-textblock.php');
    

    class WModes_Admin_Product_Option_Types {

        private static $groups = array();

        public static function get_groups( $args ) {

            if ( count( self::$groups ) ) {

                return self::$groups;
            }

            self::$groups = apply_filters( 'wmodes-admin/product-options/get-option-type-groups', array(), $args );

            return self::$groups;
        }

        public static function get_types( $args ) {

            $options = array();

            foreach ( self::get_group_ids( $args ) as $group_id ) {

                $options = self::get_group_types( $options, $group_id, $args );
            }

            return $options;
        }

        public static function get_shop_loop_types( $args ) {

            $shop_loop_types = array(
                'main' => esc_html__( 'Main Shop &amp; Archive Loops', 'wmodes-tdm' ),
                'widgets' => esc_html__( 'Widget Loops', 'wmodes-tdm' ),
                'shortcode' => esc_html__( 'Shortcode Loops', 'wmodes-tdm' ),
            );


            return apply_filters( 'wmodes-admin/product-options/get-shop-loop-types', $shop_loop_types, $args );
        }

        private static function get_group_types( $in_options, $group_id, $args ) {

            $options = apply_filters( 'wmodes-admin/product-options/get-' . $group_id . '-option-types', array(), $args );

            foreach ( $options as $key => $option ) {

                $in_options[ $key ] = array(
                    'title' => $option[ 'title' ],
                    'list_title' => $option[ 'title' ],
                    'group_id' => $group_id,
                );

                if ( isset( $option[ 'tooltip' ] ) ) {
                    $in_options[ $key ][ 'tooltip' ] = $option[ 'tooltip' ];
                }
            }

            return $in_options;
        }

        private static function get_group_ids( $args ) {

            $groups = self::get_groups( $args );

            return array_keys( $groups );
        }

        public static function get_based_on( $args ) {

            return apply_filters( 'wmodes-admin/product-options/get-based-on-types', array(), $args );
        }

        public static function get_adjustment_types( $args ) {

            return apply_filters( 'wmodes-admin/product-options/get-adjustment-types', array(), $args );
        }

        public static function get_adjustment_based_on_types( $args ) {

            return apply_filters( 'wmodes-admin/product-options/get-adjustment-based-on-types', array(), $args );
        }

        public static function get_adjustment_based_on_visibility( $args ) {

            return apply_filters( 'wmodes-admin/product-options/get-adjustment-based-on-visibility', array(), $args );
        }

        public static function get_calculate_from_visibility( $args ) {

            return apply_filters( 'wmodes-admin/product-options/get-calculate-from-visibility', array(), $args );
        }

    }

}