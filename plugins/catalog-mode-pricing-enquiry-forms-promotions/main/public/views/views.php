<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes_Views' ) ) {

    class WModes_Views {

        private $view_paths;
        private $view_locations;
        private static $instance;

        public function __construct() {

            $this->view_paths = array();

            $this->view_locations = array();
        }

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function render_view( $view_data ) {

            $view_id = $view_data[ 'view_id' ];

            $view_path = self::get_instance()->get_view_path( $view_id );

            if ( $view_path ) {

                $view_rendered = true;

                include $view_path;

                return $view_rendered;
            }

            return false;
        }

        public static function get_view_locations() {

            $this_obj = self::get_instance();

            if ( count( $this_obj->view_locations ) ) {

                return $this_obj->view_locations;
            }

            $this_obj->view_locations = array(
                'shop' => $this_obj->get_shop_locations(),
                'single-product' => $this_obj->get_single_product_locations(),
            );

            if ( has_filter( 'wmodes/register-view-locations' ) ) {

                $this_obj->view_locations = apply_filters( 'wmodes/register-view-locations', $this_obj->view_locations );
            }

            return $this_obj->view_locations;
        }

        public static function get_wc_template_path( $template_name ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Views::get_wc_template_path( $template_name );
            }

            return dirname( __FILE__ ) . '/wc_templates/' . $template_name;
        }

        private function get_shop_locations() {

            return array(
                'product_thumbnail' => array(
                    'show_in_admin' => false,
                    'title' => esc_html__( 'Product Thumbnail', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_before_shop_loop_item_title',
                    'priority' => 11,
                ),
                'before_title' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'Before Loop Title', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_shop_loop_item_title',
                    'priority' => 9,
                ),
                'after_title' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Loop Title', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_after_shop_loop_item_title',
                    'priority' => 4,
                ),
                'after_rating' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Loop Rating', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_after_shop_loop_item_title',
                    'priority' => 6,
                ),
                'after_price' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Loop Price', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_after_shop_loop_item_title',
                    'priority' => 11,
                ),
                'after_add_to_cart' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Loop Add to Cart', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_after_shop_loop_item',
                    'priority' => 11,
                ),
            );
        }

        private function get_single_product_locations() {

            return array(
                'product_thumbnail' => array(
                    'show_in_admin' => false,
                    'title' => esc_html__( 'Product Thumbnail', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_product_thumbnails',
                    'priority' => 10,
                ),
                'before_summary' => array(
                    'show_in_admin' => false,
                    'title' => esc_html__( 'Before Product Summary', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_before_single_product_summary',
                    'priority' => 1,
                ),
                'before_title' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'Before Summary Title', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 4,
                ),
                'after_title' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Title', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 6,
                ),
                'after_rating' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Rating &amp; Price', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 11,
                ),
                'after_excerpt' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Excerpts', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 21,
                ),
                'after_add_to_cart' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Add to Cart', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 31,
                ),
                'after_meta' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Meta', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 41,
                ),
                'after_share' => array(
                    'show_in_admin' => true,
                    'title' => esc_html__( 'After Summary Share', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_single_product_summary',
                    'priority' => 51,
                ),
                'after_summary' => array(
                    'show_in_admin' => false,
                    'title' => esc_html__( 'After Product Summary', 'wmodes-tdm' ),
                    'hook' => 'woocommerce_after_single_product_summary',
                    'priority' => 1,
                ),
            );
        }

        private function get_view_path( $view_id ) {

            $view_paths = $this->get_view_paths();

            if ( !count( $view_paths ) ) {

                return false;
            }

            if ( !isset( $view_paths[ $view_id ] ) ) {

                return false;
            }

            return $view_paths[ $view_id ];
        }

        private function get_view_paths() {

            if ( count( $this->view_paths ) ) {

                return $this->view_paths;
            }

            //Label Views
            $view_paths = array(
                'shop-label' => dirname( __FILE__ ) . '/shop/label.php',
                'single-label' => dirname( __FILE__ ) . '/single/label.php',
            );

            //TextBlock Views
            $view_paths[ 'shop-textblock' ] = dirname( __FILE__ ) . '/shop/textblock.php';
            $view_paths[ 'single-textblock' ] = dirname( __FILE__ ) . '/single/textblock.php';

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $view_paths = WModes_Premium_Views::get_view_paths( $view_paths );
            }

            if ( has_filter( 'wmodes/register-view-paths' ) ) {

                $view_paths = apply_filters( 'wmodes/register-view-paths', $view_paths );
            }

            $this->view_paths = $view_paths;

            return $this->view_paths;
        }

    }

}

