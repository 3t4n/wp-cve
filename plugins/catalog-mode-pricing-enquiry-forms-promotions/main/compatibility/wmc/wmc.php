<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_WMC' ) && function_exists( 'wmc_get_price' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    require_once 'wmc-pipeline-prices.php';

    class WModes_WMC {

        private static $instance = null;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function convert_amount( $amount ) {

            if ( $amount == '' ) {

                return $amount;
            }

            if ( '0' == $amount ) {

                return $amount;
            }

            return wmc_get_price( $amount );
        }

    }

}

