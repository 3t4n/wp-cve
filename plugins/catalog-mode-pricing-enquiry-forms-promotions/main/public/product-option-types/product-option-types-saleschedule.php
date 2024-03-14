<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Type_SaleSchedule' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Option_Type_SaleSchedule {

        public function get_option( $option, $option_args, $data ) {

            switch ( $option_args[ 'mode' ] ) {

                case 'date':
                case 'from_date':
                case 'to_date':
                case 'range_date':

                    $option[ 'sale_schedule' ] = $this->get_date_value( $option_args );

                    break;

                default:
                    break;
            }

            return $option;
        }

        private function get_date_value( $option_args ) {

            if ( 'date' == $option_args[ 'mode' ] ) {

                if ( !empty( $option_args[ 'date' ] ) ) {

                    return array(
                        'from' => $option_args[ 'date' ] . ' 00:00:00',
                        'to' => $option_args[ 'date' ] . ' 23:59:59',
                    );
                }
            }

            if ( 'from_date' == $option_args[ 'mode' ] ) {

                if ( !empty( $option_args[ 'from_date' ] ) ) {

                    return array(
                        'from' => $option_args[ 'from_date' ] . ' 00:00:00',
                    );
                }
            }

            if ( 'to_date' == $option_args[ 'mode' ] ) {

                if ( !empty( $option_args[ 'to_date' ] ) ) {

                    return array(
                        'to' => $option_args[ 'to_date' ] . ' 23:59:59',
                    );
                }
            }

            if ( 'range_date' == $option_args[ 'mode' ] ) {

                $range = array(
                    'from' => false,
                    'to' => false
                );

                if ( !empty( $option_args[ 'from_date' ] ) ) {

                    $range[ 'from' ] = $option_args[ 'from_date' ] . ' 00:00:00';
                }

                if ( !empty( $option_args[ 'to_date' ] ) ) {

                    $range[ 'to' ] = $option_args[ 'to_date' ] . ' 23:59:59';
                }

                return $range;
            }

            return array();
        }

    }

}