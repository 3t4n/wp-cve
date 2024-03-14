<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/catalog-mode-types-groups.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-shop.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-product.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-cart.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-checkout.php');

    class WModes_Admin_Catalog_Mode_Types {

        private static $groups = array();

        public static function get_groups( $args ) {

            if ( count( self::$groups ) ) {

                return self::$groups;
            }

            self::$groups = apply_filters( 'wmodes-admin/catalog-modes/get-mode-type-groups', array(), $args );

            return self::$groups;
        }

        public static function get_types( $args ) {

            $options = array();

            foreach ( self::get_group_ids( $args ) as $group_id ) {

                $options = self::get_group_types( $options, $group_id, $args );
            }

            return $options;
        }

        private static function get_group_types( $in_options, $group_id, $args ) {

            $options = apply_filters( 'wmodes-admin/catalog-modes/get-' . $group_id . '-mode-types', array(), $args );

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

    }

}