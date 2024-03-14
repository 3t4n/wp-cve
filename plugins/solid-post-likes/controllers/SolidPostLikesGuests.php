<?php
namespace OACS\SolidPostLikes\Controllers;

if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesGuests
{
    /**
     * Utility retrieves post meta ip likes (ip array),
     * then adds new ip to retrieved array
     * @since    0.5
     */
    public function oacs_spl_post_ip_likes( $user_ip, $post_id, $is_comment ) {
        $post_users = '';
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_oacs_spl_user_comment_IP" ) : get_post_meta( $post_id, "_oacs_spl_user_IP" );
        // Retrieve post information
        if ( count( $post_meta_users ) != 0 ) {
            $post_users = $post_meta_users[0];
        }
        if ( !is_array( $post_users ) ) {
            $post_users = array();
        }
        if ( !in_array( $user_ip, $post_users ) ) {
            $post_users['ip-' . $user_ip] = $user_ip;
        }
        return $post_users;
    }

    private function oacs_spl_get_anonym_ip( $ip ) {
        return preg_replace('/[0-9]+\z/', '0', $ip);
    }

    /**
     * Utility to retrieve IP address from browser and save anonymously. All IP Addresses will always end with .0
     */
    public function oacs_spl_get_ip_and_encrypt() {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = self::oacs_spl_get_anonym_ip($_SERVER['HTTP_CLIENT_IP']);
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = self::oacs_spl_get_anonym_ip($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $ip = self::oacs_spl_get_anonym_ip(( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0');
        }
        $ip = filter_var( $ip, FILTER_VALIDATE_IP );
        $ip = ( $ip === false ) ? '0.0.0.0' : $ip;

        return $ip;
    }
}