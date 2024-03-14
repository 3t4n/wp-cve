<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_WOOCS' ) && class_exists( 'WOOCS' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    require_once 'woocs-pipeline-prices.php';

    class WModes_WOOCS {

        private static $instance = null;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function convert_amount( $amount ) {

            return apply_filters( 'woocs_convert_price', $amount );
        }

    }

}