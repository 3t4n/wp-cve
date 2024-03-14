<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_WMC_Pipeline_Prices' ) ) {

    class WModes_WMC_Pipeline_Prices {

        public function __construct() {

            add_filter( 'wmodes/get-sale-price', array( $this, 'get_price' ), 10, 3 );
            add_filter( 'wmodes/get-price', array( $this, 'get_price' ), 10, 3 );
        }

        public function get_price( $pipeline_price, $product, $variation ) {

            $converter = $this->get_converter();

            return $converter->convert_amount( $pipeline_price );
        }

        private function get_converter() {

            return WModes_WMC::get_instance();
        }

    }

    new WModes_WMC_Pipeline_Prices();
}