<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Condition_Type_Customer' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Condition_Type_Customer {

        public function can_validate( $condition_type ) {

            $condition_types = array(
                'logged_in_user',
                'users',
                'user_roles',
            );

            return in_array( $condition_type, $condition_types );
        }

        public function validate( $condition, $data ) {

            $condition_type = $condition[ 'condition_type' ];

            if ( 'logged_in_user' == $condition_type ) {

                return $this->validate_logged_in_user( $condition, $data );
            }

            if ( 'users' == $condition_type ) {

                return $this->validate_users( $condition, $data );
            }

            if ( 'user_roles' == $condition_type ) {

                return $this->validate_user_roles( $condition, $data );
            }

            return true;
        }

        private function validate_logged_in_user( $condition, $data ) {

            $rule_is_logged_in = $condition[ 'is_logged_in' ];

            $is_logged_in = WModes_Customer_Util::get_is_logged_in( $data );

            return WModes_Validation_Util::validate_yes_no( $is_logged_in, $rule_is_logged_in );
        }

        private function validate_users( $condition, $data ) {

            if ( !isset( $condition[ 'user_emails' ] ) ) {
                return false;
            }

            $rule_user_emails = $condition[ 'user_emails' ];

            if ( !count( $rule_user_emails ) ) {
                return false;
            }

            $user_email = WModes_Customer_Util::get_user_email( $data );

            $rule_compare = $condition[ 'compare' ];

            return WModes_Validation_Util::validate_value_list( $user_email, $rule_user_emails, $rule_compare );
        }

        private function validate_user_roles( $condition, $data ) {

            if ( !isset( $condition[ 'user_roles' ] ) ) {
                return false;
            }

            $rule_user_roles = $condition[ 'user_roles' ];

            if ( !count( $rule_user_roles ) ) {
                return false;
            }


            $user_roles = WModes_Customer_Util::get_user_roles( $data );

            $rule_compare = $condition[ 'compare' ];

            return WModes_Validation_Util::validate_list_list( $user_roles, $rule_user_roles, $rule_compare );
        }

    }

}