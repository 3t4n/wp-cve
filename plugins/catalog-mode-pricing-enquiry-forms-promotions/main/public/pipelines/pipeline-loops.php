<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Loops' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Loops {

        private $main_loop = 'main';
        private static $instance;
        private $loops_stack;
        private $loops_stack_pointer;

        private function __construct() {

            $this->loops_stack = array( $this->main_loop );
            $this->loops_stack_pointer = 0;

            //Shortcode Loops
            add_action( 'woocommerce_shortcode_before_products_loop', array( $this, 'before_shortcode' ), 999999 );
            add_action( 'woocommerce_shortcode_after_products_loop', array( $this, 'after_shortcode' ), 999999 );

            //Widget Loops
            add_filter( 'woocommerce_before_widget_product_list', array( $this, 'before_widget_list' ), 99999 );
            add_filter( 'woocommerce_after_widget_product_list', array( $this, 'after_widget_list' ), 99999 );
        }

        public static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function before_shortcode( $attributes ) {

            $this->push_to_stack( 'shortcode' );
        }

        public function after_shortcode( $attributes ) {

            $this->pop_stack();
        }

        public function before_widget_list( $widget_html ) {

            $this->push_to_stack( 'widgets' );

            return $widget_html;
        }

        public function after_widget_list( $widget_html ) {

            $this->pop_stack();

            return $widget_html;
        }

        public function validate( $shop_loop_args ) {

            if ( !count( $shop_loop_args[ 'loops' ] ) ) {

                return true;
            }

            $current_loop = $this->get_stack();

            $is_valid = WModes_Validation_Util::validate_value_list( $current_loop, $shop_loop_args[ 'loops' ], $shop_loop_args[ 'compare' ] );

            return apply_filters( 'wmodes/validate-pipeline-shop-loop', $is_valid, $current_loop, $shop_loop_args );
        }

        private function get_stack() {


            if ( isset( $this->loops_stack[ $this->loops_stack_pointer ] ) ) {

                return $this->loops_stack[ $this->loops_stack_pointer ];
            }

            return $this->main_loop;
        }

        private function push_to_stack( $loop_id ) {

            $this->loops_stack[] = $loop_id;

            $this->loops_stack_pointer++;
        }

        private function pop_stack() {

            if ( isset( $this->loops_stack[ $this->loops_stack_pointer ] ) ) {

                array_pop( $this->loops_stack );

                $this->loops_stack_pointer--;
            }

            if ( $this->loops_stack_pointer < 0 ) {

                $this->loops_stack_pointer = 0;

                $this->loops_stack = array( $this->main_loop );
            }
        }

    }

    WModes_Pipeline_Loops::get_instance();
}