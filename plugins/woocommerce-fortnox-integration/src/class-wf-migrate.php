<?php

namespace src;

class WF_Migrate{

    /**
     * @param $upgrader_object
     * @param $options
     */
    public static function wp_update_completed( $upgrader_object, $options ) {

        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == 'woocommerce-fortnox-integration/plugin.php' ) {
                    self::maybe_update_db();
                }
            }
        }
    }

    /**
     *
     */
    public static function maybe_update_db(){
        $db_version = self::get_db_version();

        if ( $db_version === 1.0 ){
            self::update_db_license_key();
            self::update_db_version( 3.0 );
        }

    }

    /**
     *
     */
    private static function update_db_license_key() {
        $license_key = get_option( 'fortnox_api_key' );
        update_option( 'fortnox_license_key', $license_key );
    }

    /**
     * @param $version
     */
    private static function update_db_version( $version ) {
        update_option( 'fortnox_db_version', $version );
    }

    /***
     * @return float|int
     */
    private static function get_db_version() {

        $db_version = (int)get_option( 'fortnox_db_version' );

        if ( ! $db_version ) {
            return 1.0;
        }
        return $db_version;

    }
}