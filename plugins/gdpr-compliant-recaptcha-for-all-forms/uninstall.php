<?php

namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

if ( ! defined('WP_UNINSTALL_PLUGIN' ) ) {
    die( 'Are you ok?' );
}

/** Class Uninstall
 * 
 */
class Uninstall
{
    /**
     * @return void
     */
    public static function run()
    {
        require_once dirname( __FILE__ ) . '/includes/class-option.php';

        /*//Wenn erneute Aktivierung
        $data = array(
            'grp_long_id' => get_option( Option::POW_KEY ),
            'grp_state' => 'deleted',
            'grp_current_version' => get_option( Option::POW_VERSION ),
            'grp_reason_long' => '',
            'grp_reason_short' => '',
        );
        $options = array(
            'body' => http_build_query($data),
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        );
        $response = wp_remote_post('https://programmiere.de/GDPRCompliantRecaptcha/update.php', $options);*/

        $constants = (new \ReflectionClass(Option::class))->getConstants();

        foreach ( $constants as $constant ) {
            $constPrefix = substr( $constant, 0, strlen( Option::PREFIX ) );

            if ( $constPrefix === Option::PREFIX ) {
                delete_option( $constant );
            }
        }

        global $wpdb;
        $table_name_mail = $wpdb->prefix.'recaptcha_gdpr_message_rgm';
        $table_name_details = $wpdb->prefix.'recaptcha_gdpr_details_rgd';
        $table_name_stamp = $wpdb->prefix.'recaptcha_gdpr_stamp_rgs';

        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_mail ) );

        if ( $wpdb->get_var( $query ) == $table_name_mail ) {

            $results = $wpdb->query( "
                    DROP TABLE ".$table_name_mail.";
            " );

        }

        
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_details ) );

        if ( $wpdb->get_var( $query ) == $table_name_details ) {

            $results = $wpdb->query( "
                DROP TABLE ".$table_name_details.";
            " );
        }

        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_stamp ) );

        if ( $wpdb->get_var( $query ) == $table_name_stamp ) {

            $results = $wpdb->query( "
                DROP TABLE ".$table_name_stamp.";
            " );
        }
    }
}

Uninstall::run();
