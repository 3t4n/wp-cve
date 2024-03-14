<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline' ) ) {

    require_once 'pipeline-loops.php';

    require_once 'pipeline-option-stock.php';
    require_once 'pipeline-option-saleschedule.php';
    require_once 'pipeline-option-shipping.php';
    require_once 'pipeline-shop-view.php';
    require_once 'pipeline-single-product-view.php';

    require_once 'pipeline-prices.php';
    require_once 'pipeline-variation-prices.php';

    require_once 'pipeline-shop-catalog.php';
    require_once 'pipeline-cart-catalog.php';

    require_once 'pipeline-templates.php';
    require_once 'pipeline-query.php';

    class WModes_Pipeline {

        private static $instance;
        private $product_pipeline_started;
        private $wmodes;

        public function __construct() {

            $this->product_pipeline_started = false;

            $this->wmodes = new WModes();
        }

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function run_data() {

            $this_obj = self::get_instance();

            return $this_obj->wmodes->get_data();
        }

        public static function run_product_data( $product, $variation = null ) {

            $this_obj = self::get_instance();

            if ( !$this_obj->can_run_product_data() ) {

                return array();
            }

            $this_obj->product_pipeline_started = true;

            $pipeline_data = $this_obj->wmodes->get_product_data( $product, $variation );

            $this_obj->product_pipeline_started = false;

            return $pipeline_data;
        }

        public static function get_hash_data( $product_id ) {

            $this_obj = self::get_instance();

            return $this_obj->wmodes->get_product_hash( $product_id );
        }

        public static function get_site_modes() {

            $pipeline_data = self::run_data();

            if ( !isset( $pipeline_data[ 'modes' ] ) ) {

                return false;
            }

            if ( !count( $pipeline_data[ 'modes' ] ) ) {

                return false;
            }

            return $pipeline_data[ 'modes' ];
        }

        public static function get_product_modes( $product ) {

            $pipeline_data = self::run_product_data( $product );

            if ( !isset( $pipeline_data[ 'modes' ] ) ) {

                return false;
            }

            if ( !count( $pipeline_data[ 'modes' ] ) ) {

                return false;
            }

            return $pipeline_data[ 'modes' ];
        }

        public static function get_product_option( $product, $option_type, $single = true ) {

            $pipeline_data = self::run_product_data( $product );

            $options = array();

            if ( !isset( $pipeline_data[ 'options' ] ) ) {

                return false;
            }

            $this_obj = self::get_instance();

            foreach ( $this_obj->process_overrides( $pipeline_data[ 'options' ] ) as $option ) {

                if ( $option_type == $option[ 'option_type' ] ) {

                    if ( $single ) {

                        return $option;
                    }

                    $options[] = $option;
                }
            }

            if ( !count( $options ) ) {

                return false;
            }

            return $options;
        }

        public static function get_product_views( $product, $page_id ) {

            $this_obj = self::get_instance();

            $pipeline_data = self::run_product_data( $product );

            $options = array();

            if ( !isset( $pipeline_data[ 'options' ] ) ) {

                return false;
            }

            foreach ( $pipeline_data[ 'options' ] as $option ) {

                $option_type = $option[ 'option_type' ];

                if ( !isset( $option[ $option_type ][ 'views' ][ $page_id ] ) ) {

                    continue;
                }

                if ( !$this_obj->validate_view_for_loops( $option, $option_type, $page_id ) ) {

                    continue;
                }

                $options[] = $option;
            }

            if ( !count( $options ) ) {

                return false;
            }



            return $this_obj->process_overrides( $options );
        }

        private function validate_view_for_loops( $option, $option_type, $page_id ) {

            $view = $option[ $option_type ][ 'views' ][ $page_id ];

            if ( 'shop' != $page_id ) {

                return true;
            }

            if ( !isset( $view[ 'shop_loop_args' ] ) ) {

                return true;
            }

            $shop_loop_args = $view[ 'shop_loop_args' ];

            $pipeline_loops = WModes_Pipeline_Loops::get_instance();

            return $pipeline_loops->validate( $shop_loop_args );
        }

        private function process_overrides( $product_options ) {

            $option_types = array();

            $options = array();

            foreach ( $product_options as $key => $product_option ) {

                $option_type = $product_option[ 'option_type' ];


                if ( $product_option[ 'override' ] && isset( $option_types[ $option_type ] ) ) {

                    $options = $this->remove_prev_options( $options, $option_types[ $option_type ] );

                    unset( $option_types[ $option_type ] );
                }

                $options[ $key ] = $product_option;

                $option_types[ $product_option[ 'option_type' ] ][] = $key;
            }

            return $options;
        }

        private function remove_prev_options( $options, $option_keys ) {

            foreach ( $option_keys as $option_key ) {

                if ( isset( $options[ $option_key ] ) ) {

                    unset( $options[ $option_key ] );
                }
            }

            return $options;
        }

        private function can_run_product_data() {

            if ( is_admin() && !(defined( 'DOING_AJAX' ) && DOING_AJAX) ) {

                return false ;
            }

            if ( true == $this->product_pipeline_started ) {

                return false;
            }

            return true;
        }

    }

    new WModes_Pipeline();
}