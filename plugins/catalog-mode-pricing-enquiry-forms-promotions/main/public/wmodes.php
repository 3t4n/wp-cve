<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes' ) ) {

    require_once dirname( __FILE__ ) . '/utils/wmodes-utils.php';
    require_once dirname( __FILE__ ) . '/wmodes-product.php';
    require_once dirname( __FILE__ ) . '/wmodes-cart.php';

    require_once dirname( __FILE__ ) . '/modules/catalog-mode/catalog-mode.php';
    require_once dirname( __FILE__ ) . '/modules/catalog-mode/catalog-mode-engine.php';
    require_once dirname( __FILE__ ) . '/modules/catalog-mode/catalog-mode-engine-meta.php';

    require_once dirname( __FILE__ ) . '/modules/product-prices/product-prices.php';
    require_once dirname( __FILE__ ) . '/modules/product-prices/product-prices-engine.php';
    require_once dirname( __FILE__ ) . '/modules/product-prices/product-prices-engine-meta.php';

    require_once dirname( __FILE__ ) . '/modules/product-options/product-options.php';
    require_once dirname( __FILE__ ) . '/modules/product-options/product-options-engine.php';
    require_once dirname( __FILE__ ) . '/modules/product-options/product-options-engine-meta.php';

    require_once dirname( __FILE__ ) . '/product-option-types/product-option-types.php';
    require_once dirname( __FILE__ ) . '/product-price-types/product-price-types.php';
    require_once dirname( __FILE__ ) . '/catalog-mode-types/catalog-mode-types.php';
    require_once dirname( __FILE__ ) . '/product-filter-types/product-filter-types.php';
    require_once dirname( __FILE__ ) . '/condition-types/condition-types.php';

    class WModes {

        private static $wmodes_settings = false;
        private static $wmodes_settings_meta = array();
        private $product_options;
        private $product_prices;
        private $catalog_modes;

        public function __construct() {

            $this->product_options = new WModes_Product_Options();
            $this->product_prices = new WModes_Product_Prices();
            $this->catalog_modes = new WModes_Catalog_Mode();
        }

        public function get_data() {

            $data = self::process_data();

            return apply_filters( 'wmodes/get-site-data', $this->catalog_modes->get_site_modes( array(), $data ), $data );
        }

        public function get_product_data( $product, $variation = null ) {

            $data = self::process_product_data( $product, $variation );

            $product_data = $this->product_options->get_options( array(), $data );

            $product_data = $this->product_prices->get_prices( $product_data, $data );

            return apply_filters( 'wmodes/get-product-data', $this->catalog_modes->get_product_modes( $product_data, $data ), $data );
        }

        public function get_product_hash( $product_id ) {

            return apply_filters( 'wmodes/process-product-hash', array(), $product_id );
        }

        public static function get_option( $option_key, $default, $options = false ) {

            if ( false !== $options ) {

                if ( isset( $options[ $option_key ] ) ) {

                    return $options[ $option_key ];
                }

                return $default;
            }

            $options = self::get_all_options( array() );

            if ( isset( $options[ $option_key ] ) ) {

                return $options[ $option_key ];
            }

            return $default;
        }

        public static function get_all_options( $default = array() ) {

            if ( false !== self::$wmodes_settings ) {

                return self::$wmodes_settings;
            }

            self::$wmodes_settings = get_option( 'wmodes_settings', $default );

            return self::$wmodes_settings;
        }

        public static function get_meta_option( $product_id, $meta_key, $default ) {

            if ( isset( self::$wmodes_settings_meta[ $product_id ][ $meta_key ] ) ) {

                return self::$wmodes_settings_meta[ $product_id ][ $meta_key ];
            }

            $meta_value = get_post_meta( $product_id, $meta_key, true );

            if ( $meta_value ) {

                self::$wmodes_settings_meta[ $product_id ][ $meta_key ] = $meta_value;
            } else {

                self::$wmodes_settings_meta[ $product_id ][ $meta_key ] = $default;
            }

            return self::$wmodes_settings_meta[ $product_id ][ $meta_key ];
        }

        public static function process_product_data( $product, $variation = null ) {

            $data = array();

            // Get me something
            $data[ 'wc' ][ 'product' ] = WModes_Product::get_data( $product, $variation );

            // Guys, it's either now or never
            if ( count( $data[ 'wc' ][ 'product' ] ) ) {

                $data = apply_filters( 'wmodes/process-product-data', $data );
            }

            // That's it, i will take it from here
            return self::clean_data( $data );
        }

        public static function process_data( $context = 'view' ) {

            // Get me something
            $data[ 'wc' ] = array();

            // Guys, it's either now or never
            $data = apply_filters( 'wmodes/process-data', $data, $context );

            // That's it, i will take it from here
            return self::clean_data( $data );
        }

        private static function clean_data( $data ) {

            //removes unwanted data
            unset( $data[ 'wc' ] );
            return $data;
        }

    }

}
