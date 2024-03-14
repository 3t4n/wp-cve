<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/product-option-types-stock.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-saleschedule.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-shipping.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-label.php');
    require_once (dirname( __FILE__ ) . '/product-option-types-textblock.php');

    class WModes_Product_Option_Types {

        private static $instance;

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function get_options( $options_args, $data ) {

            $this_obj = self::get_instance();

            $options = array();

            foreach ( $options_args as $option_args ) {

                if ( !$this_obj->validate_product( $option_args, $data ) ) {

                    continue;
                }

                $option_id = $option_args[ 'id' ];

                $options[ $option_id ] = $this_obj->get_option( $option_args, $data );
            }

            return $options;
        }

        public static function get_price_adjustment( $adjustment_args, $product_data ) {

            $price = 0;

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $price = WModes_Premium_Product_Option_Types::get_price_adjustment( $price, $adjustment_args, $product_data );
            }

            $filter_key = 'wmodes/product-options/calculate-product-price';

            if ( has_filter( $filter_key ) ) {

                $prices = apply_filters( $filter_key, $price, $adjustment_args, $product_data );
            }

            return $price;
        }

        private function get_option( $option_args, $data ) {

            $option_type = '';

            if ( isset( $option_args[ 'option_type' ] ) ) {

                $option_type = $option_args[ 'option_type' ];
            }

            $option = array(
                'option_type' => $option_type,
                'override' => true
            );

            if ( '' == $option_type ) {

                return $option;
            }

            switch ( $option_type ) {

                case 'stock':

                    $stock = new WModes_Product_Option_Type_Stock();

                    return $stock->get_option( $option, $option_args, $data );

                case 'sale_schedule':

                    $saleschedule = new WModes_Product_Option_Type_SaleSchedule();

                    return $saleschedule->get_option( $option, $option_args, $data );

                case 'shipping':

                    $shipping = new WModes_Product_Option_Type_Shipping();

                    return $shipping->get_option( $option, $option_args, $data );

                case 'label':

                    $label = new WModes_Product_Option_Type_Label();

                    return $label->get_option( $option, $option_args, $data );

                case 'textblock':

                    $textblock = new WModes_Product_Option_Type_TextBlock();

                    return $textblock->get_option( $option, $option_args, $data );

                default:

                    if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                        return WModes_Premium_Product_Option_Types::get_option( $option, $option_args, $data );
                    } else {

                        return apply_filters( 'wmodes/product-options/process-' . $option_type . '-option', $option, $option_args, $data );
                    }
            }
        }

        private function validate_product( $option_args, $data ) {

            //defualt all applies to all products
            if ( !isset( $option_args[ 'product_args' ] ) ) {
                return true;
            }

            $product_args = $option_args[ 'product_args' ];

            //validate product
            return WModes_Product_Filter_Types::validate_product( $data, $product_args );
        }

    }

}

