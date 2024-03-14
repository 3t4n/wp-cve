<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Condition_Type_Cart' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Condition_Type_Cart {

        public function can_validate( $condition_type ) {

            $condition_types = array(
                'cart_line_count',
            );

            return in_array( $condition_type, $condition_types );
        }

        public function validate( $condition, $data ) {

            $condition_type = $condition[ 'condition_type' ];

            if ( 'cart_line_count' == $condition_type ) {

                return $this->validate_line_count( $condition, $data );
            }

            return true;
        }

        private function validate_line_count( $condition, $data ) {

            $condition_type = $condition[ 'condition_type' ];

            $line_count = $this->get_line_count( $data );

            $rule_compare = $condition[ 'compare' ];

            $rule_line_count = 0;

            if ( is_numeric( $condition[ 'line_count' ] ) ) {

                $rule_line_count = $condition[ 'line_count' ];
            }

            return WModes_Validation_Util::validate_value( $rule_compare, $line_count, $rule_line_count );
        }

        private function get_line_count( $data ) {

            if ( !isset( $data[ 'items' ] ) ) {
                
                return 0;
            }

            return count( $data[ 'items' ] );
        }

    }

}
