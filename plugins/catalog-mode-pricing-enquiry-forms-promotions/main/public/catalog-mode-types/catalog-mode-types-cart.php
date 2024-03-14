<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode_Types_Cart' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Catalog_Mode_Types_Cart {

        public function get_mode( $mode, $mode_args, $data ) {

            $mode[ 'cart' ] = $this->get_cart_mode( array(), $mode_args, $data );

            return WModes_Catalog_Mode_Types::prepare_mode( $mode, $mode_args );
        }

        private function get_cart_mode( $mode_options, $mode_args, $data ) {

            if ( !self::can_process_mode( $data ) ) {

                return $mode_options;
            }
            
            $mode_options = array(
                'restrict' => $this->is_restricted( $mode_args ),
                'redirect' => $this->get_redirect( $mode_args ),
            );


            return $mode_options;
        }

        private function get_redirect( $mode_args ) {

            if ( !$this->is_restricted( $mode_args ) ) {

                return false;
            }

            return array(
                'url' => wc_get_page_permalink( 'shop' ),
            );
        }

        private function is_restricted( $mode_args ) {

            return ('yes' == $mode_args[ 'restrict_cart' ]);
        }

        private static function can_process_mode( $data ) {

            if ( $data[ 'wc' ][ 'is_product' ] ) {

                return false;
            }

            if ( isset( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }


            return true;
        }

    }

}
