<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes_Customer_Util' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Customer_Util {
        
        private static $users = array();

        public static function get_is_logged_in( $data ) {
            if ( !isset( $data[ 'customer' ][ 'id' ] ) ) {
                return false;
            }

            $user_id = $data[ 'customer' ][ 'id' ];

            return ($user_id > 0);
        }

        public static function get_user_email( $data ) {

            if ( !isset( $data[ 'customer' ][ 'email' ] ) ) {
                return '';
            }

            return $data[ 'customer' ][ 'email' ];
        }

        public static function get_user_roles( $data ) {

            $user = self::get_user_by( $data );

            if ( !$user ) {
                return array();
            }

            return $user->roles;
        }

        private static function get_user_by( $data ) {

            if ( !isset( $data[ 'customer' ][ 'id' ] ) ) {
                return false;
            }

            $user_id = $data[ 'customer' ][ 'id' ];

            if ( 0 >= $user_id ) {
                return false;
            }

            if ( isset( self::$users[ $user_id ] ) ) {

                return self::$users[ $user_id ];
            }

            $user = get_user_by( 'id', $user_id );

            if ( $user ) {
                self::$users[ $user_id ] = $user;
            }

            return $user;
        }

    }

}