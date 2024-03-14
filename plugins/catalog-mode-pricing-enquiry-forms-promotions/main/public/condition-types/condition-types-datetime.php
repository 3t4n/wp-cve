<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Condition_Type_DateTime' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Condition_Type_DateTime {

        public function can_validate( $condition_type ) {

            $condition_types = array(
                'date_time',
            );

            return in_array( $condition_type, $condition_types );
        }

        public function validate( $condition, $data ) {

            $condition_type = $condition[ 'condition_type' ];

            if ( 'date_time' == $condition_type ) {

                return $this->validate_date_time( $condition, $data );
            }

            return true;
        }

        private function validate_date_time( $condition ) {

            $rule_date_type = $condition[ 'date_type' ];

            if ( $rule_date_type == 'between' ) {

                $rule_from_date_time = $condition[ 'from_date_time' ];
                $rule_to_date_time = $condition[ 'to_date_time' ];

                if ( $rule_from_date_time == '' || $rule_to_date_time == '' ) {
                    return false;
                }

                $current_date_time = date( "Y-m-d H:i:s", current_time( 'timestamp' ) );

                $from_is_valid = WModes_Validation_Util::validate_date( '>=', $current_date_time, $rule_from_date_time, 'Y-m-d H:i:s', 'Y-m-d H:i:s' );
                $to_is_valid = WModes_Validation_Util::validate_date( '<=', $current_date_time, $rule_to_date_time, 'Y-m-d H:i:s', 'Y-m-d H:i:s' );

                return ($from_is_valid == true && $to_is_valid == true);
            } else {

                $rule_date_time = $condition[ 'date_time' ];

                if ( $rule_date_time == '' ) {
                    return false;
                }

                $current_date_time = date( "Y-m-d H:i:s", current_time( 'timestamp' ) );

                $valid_type = '>=';
                if ( $rule_date_type == 'to' ) {
                    $valid_type = '<=';
                }

                return WModes_Validation_Util::validate_date( $valid_type, $current_date_time, $rule_date_time, 'Y-m-d H:i:s', 'Y-m-d H:i:s' );
            }
        }

    }

}