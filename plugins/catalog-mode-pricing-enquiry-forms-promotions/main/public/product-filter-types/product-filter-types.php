<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Filter_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/product-filter-types-products.php');

    class WModes_Product_Filter_Types {

        private static $instance;
        private $filter_type_products;

        private function __construct() {

            $this->filter_type_products = new WModes_Product_Filter_Type_Products();
        }

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function validate_product( $data, $product_args ) {

            if ( !isset( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }

            if ( !count( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }

            if ( !count( $product_args ) ) {

                return true;
            }

            return self::get_instance()->validate_filters( $data, $product_args );
        }

        private function validate_filters( $data, $product_args ) {

            $is_valid = false;

            foreach ( $this->get_filters( $product_args, true ) as $filter_args ) {

                $is_vld = $this->validate_filter( $filter_args, $data );

                if ( true === $is_vld ) {
                    $is_valid = true;
                }

                if ( true != $is_valid ) {

                    return $is_valid;
                }
            }

            if ( true === $is_valid ) {

                return $is_valid;
            }

            foreach ( $this->get_filters( $product_args, false ) as $filter_args ) {

                $is_vld_op = $this->validate_filter( $filter_args, $data );

                if ( true === $is_vld_op ) {

                    $is_valid = true;
                }
            }

            return $is_valid;
        }

        private function validate_filter( $filter_args, $data ) {


            if ( $this->filter_type_products->can_validate( $filter_args[ 'filter_type' ] ) ) {

                return $this->filter_type_products->validate( $filter_args, $data );
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Product_Filter_Types::get_instance()->validate_filter( $filter_args, $data );
            }

            // allows other plugins to validate product
            return apply_filters( 'wmodes/validate-product', true, $filter_args, $data );
        }

        private function get_filters( $product_filters, $is_required ) {

            $filters = array();

            foreach ( $product_filters as $product_filter ) {

                $filter_type = $product_filter[ 'filter_type' ];
                $filter_arg = $product_filter[ 'filter_type_' . $filter_type ];

                if ( true == $is_required && 'yes' != $filter_arg[ 'is_req' ] ) {

                    continue;
                }

                if ( true != $is_required && 'yes' == $filter_arg[ 'is_req' ] ) {

                    continue;
                }


                $filters[] = $this->prepare_filter( $filter_arg, $filter_type );
            }

            return $filters;
        }

        private function prepare_filter( $filter_args, $filter_type ) {

            $filter = array(
                'filter_type' => $filter_type
            );

            foreach ( $filter_args as $key => $value ) {
                $filter[ $key ] = $value;
            }

            return $filter;
        }

    }

}
