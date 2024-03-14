<?php
namespace OACS\SolidPostLikes\Controllers;

use OACS\SolidPostLikes\Controllers\SolidPostLikesGuests as Guest;

if ( ! defined( 'WPINC' ) ) { die; }

//** Helper function that checks whether a post has already been liked for logged in and anonymous users. */

class SolidPostLikesChecker
{
    public function oacs_spl_already_liked($post_id, $is_comment)
    {
        $post_users = null;
        $user_id = null;
        if (is_user_logged_in()) {
            // user is logged in
            $user_id = get_current_user_id();
            $post_meta_users = ($is_comment == 1) ? get_comment_meta($post_id, "_oacs_spl_user_comment_liked") : get_post_meta($post_id, "_oacs_spl_user_liked");
            if (!empty($post_meta_users)) {
                $post_users = $post_meta_users[0];
            }
        } else {
            // user is anonymous
            $guest = new Guest;
            $user_id = $guest->oacs_spl_get_ip_and_encrypt();
            $post_meta_users = ($is_comment == 1) ? get_comment_meta($post_id, "_oacs_spl_user_comment_IP") : get_post_meta($post_id, "_oacs_spl_user_IP");
            if (!empty($post_meta_users)) {
                // meta exists, set up values
                $post_users = $post_meta_users[0];
            }
        }

        if (is_array($post_users) && in_array($user_id, $post_users)) {
            // dd('liked');
            return true;
        } else {
            // dd('not liked');
            return false;
        }
    }
}