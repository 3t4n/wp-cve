<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes_Product' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product {

        private $parent_products = array();
        private $variations = array();
        private $variation_ids = array();
        private $product_data;
        private static $instance;

        private static function get_instance() {

            if ( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function get_product( $product_id ) {

            $this_obj = self::get_instance();

            return $this_obj->get_parent( $product_id );
        }

        public static function get_variations_by_id( $product_id ) {

            $this_obj = self::get_instance();

            return $this_obj->get_product_variations_by_id( $product_id );
        }

        public static function get_variation_ids_by_id( $product_id ) {

            $this_obj = self::get_instance();

            return $this_obj->get_product_variation_ids_by_id( $product_id );
        }

        public static function get_variation_by_id( $product_id, $variation_id ) {

            $this_obj = self::get_instance();

            return $this_obj->get_product_variation_by_id( $product_id, $variation_id );
        }

        public static function get_variations( $product ) {

            $this_obj = self::get_instance();

            return $this_obj->get_product_variations( $product );
        }

        public static function get_data( $product, $variation = null ) {

            $this_obj = self::get_instance();

            $parent_id = $product->get_parent_id();

            if ( $parent_id > 0 && 'variation' == $product->get_type() ) {

                $parent_product = $this_obj->get_parent( $parent_id );

                if ( !$parent_product ) {
                    return array();
                }

                return $this_obj->get_all_data( $parent_product, $product );
            } else {

                return $this_obj->get_all_data( $product, $variation );
            }
        }

        private function get_all_data( $product, $variation ) {

            $product_id = $product->get_id();
            $variation_id = 0;

            if ( $variation ) {

                $variation_id = $variation->get_id();
            }


            $data_key = $product_id . '_' . $variation_id;


            if ( !isset( $this->parent_products[ $product_id ] ) ) {
                $this->parent_products[ $product_id ] = $product;
            }

            if ( $variation && !isset( $this->variations[ $product_id ][ $variation_id ] ) ) {
                $this->variations[ $product_id ][ $variation_id ] = $variation;
            }

            if ( isset( $this->product_data[ $data_key ] ) ) {
                return $this->product_data[ $data_key ];
            }

            $this->product_data[ $data_key ] = $this->get_product_data( array(), $product, $variation );

            return $this->product_data[ $data_key ];
        }

        private function get_product_data( $data, $product, $variation ) {

            $props = array(
                'type' => $product->get_type(),
                'title' => $product->get_title(),
                'product_id' => $product->get_id(),
                'variation_id' => 0,
            );

            if ( $variation ) {

                $props[ 'variation_id' ] = $variation->get_id();
            }

            if ( has_filter( 'wmodes/get-product-properties' ) ) {

                $props = apply_filters( 'wmodes/get-product-properties', $props, $product, $variation );
            }

            return $this->get_prices( $this->combine_el( $data, $props ), $product, $variation );
        }

        private function get_prices( $data, $product, $variation ) {

            $prices = array();

            if ( $variation ) {

                $prices[ 'regular_price' ] = $variation->get_regular_price( 'edit' );
                $prices[ 'sale_price' ] = $variation->get_sale_price( 'edit' );
                $prices[ 'price' ] = $variation->get_price( 'edit' );
            } else {

                $prices[ 'regular_price' ] = $product->get_regular_price( 'edit' );
                $prices[ 'sale_price' ] = $product->get_sale_price( 'edit' );
                $prices[ 'price' ] = $product->get_price( 'edit' );
            }

            if ( has_filter( 'wmodes/get-product-prices' ) ) {

                $prices = apply_filters( 'wmodes/get-product-prices', $prices, $product, $variation );
            }

            return $this->get_stock_data( $this->combine_el( $data, $prices ), $product, $variation );
        }

        private function get_stock_data( $data, $product, $variation ) {

            $stock_data = array(
                'stock_status' => $product->get_stock_status( 'edit' ),
                'stock_quantity' => $product->get_stock_quantity( 'edit' ),
            );

            if ( $variation ) {

                $stock_data[ 'stock_status' ] = $variation->get_stock_status( 'edit' );

                $v_stock_quantity = $variation->get_stock_quantity( 'edit' );

                if ( is_numeric( $v_stock_quantity ) ) {

                    $stock_data[ 'stock_quantity' ] = $v_stock_quantity;
                }
            }

            if ( has_filter( 'wmodes/get-product-stock-data' ) ) {

                $stock_data = apply_filters( 'wmodes/get-product-stock-data', $stock_data, $product, $variation );
            }

            return $this->get_terms( $this->combine_el( $data, $stock_data ), $product, $variation );
        }

        private function get_terms( $data, $product, $variation ) {

            $data[ 'category_ids' ] = $product->get_category_ids( 'edit' );

            if ( has_filter( 'wmodes/get-product-category-ids' ) ) {

                $data[ 'category_ids' ] = apply_filters( 'wmodes/get-product-category-ids', $data[ 'category_ids' ], $product, $variation );
            }

            $data[ 'tag_ids' ] = $product->get_tag_ids( 'edit' );

            if ( has_filter( 'wmodes/get-product-tag-ids' ) ) {

                $data[ 'tag_ids' ] = apply_filters( 'wmodes/get-product-tag-ids', $data[ 'tag_ids' ], $product, $variation );
            }

            return $data;
        }

        private function combine_el( $data, $new_data ) {

            foreach ( $new_data as $key => $new_item ) {

                $data[ $key ] = $new_item;
            }

            return $data;
        }

        private function get_product_variations_by_id( $product_id ) {

            $product = $this->get_parent( $product_id );

            $variation_ids = $product->get_children();

            foreach ( $variation_ids as $variation_id ) {

                $this->get_product_variation_by_id( $product_id, $variation_id );
            }

            if ( !isset( $this->variations[ $product_id ] ) ) {

                return array();
            }

            return $this->variations[ $product_id ];
        }

        private function get_product_variations( $product ) {

            $product_id = $product->get_id();

            $variation_ids = $product->get_children();

            foreach ( $variation_ids as $variation_id ) {

                $this->get_product_variation_by_id( $product_id, $variation_id );
            }

            if ( !isset( $this->variations[ $product_id ] ) ) {

                return array();
            }

            return $this->variations[ $product_id ];
        }

        private function get_product_variation_by_id( $product_id, $variation_id ) {

            if ( isset( $this->variations[ $product_id ][ $variation_id ] ) ) {

                return $this->variations[ $product_id ][ $variation_id ];
            }

            $variation = wc_get_product( $variation_id );

            if ( $variation ) {

                $this->variations[ $product_id ][ $variation_id ] = $variation;
            }

            if ( isset( $this->variations[ $product_id ][ $variation_id ] ) ) {

                return $this->variations[ $product_id ][ $variation_id ];
            }

            return null;
        }

        private function get_product_variation_ids_by_id( $product_id ) {

            if ( isset( $this->variation_ids[ $product_id ] ) ) {

                return $this->variation_ids[ $product_id ];
            }

            $product = $this->get_parent( $product_id );

            $this->variation_ids[ $product_id ] = $product->get_children();

            return $this->variation_ids[ $product_id ];
        }

        private function get_parent( $parent_id ) {

            if ( isset( $this->parent_products[ $parent_id ] ) ) {
                return $this->parent_products[ $parent_id ];
            }

            $this->parent_products[ $parent_id ] = wc_get_product( $parent_id );

            return $this->parent_products[ $parent_id ];
        }

    }

}