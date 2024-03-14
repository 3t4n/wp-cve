<?php
namespace OACS\SolidPostLikes\Controllers;
/** This class gets all user IDs of users liking a post and adds any new user ID */
if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesUsers
{

   public function oacs_spl_post_user_likes( $user_id, $post_id, $is_comment ) {

        $post_users = '';
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_oacs_spl_user_comment_liked" ) : get_post_meta( $post_id, "_oacs_spl_user_liked" );

        if ( count( $post_meta_users ) != 0 ) {
            $post_users = $post_meta_users[0];
        }
        if ( !is_array( $post_users ) ) {
            $post_users = array();
        }
        if ( !in_array( $user_id, $post_users ) ) {
            $post_users['oacs-spl-user-' . $user_id] = $user_id;
        }
        return $post_users;
    }
}