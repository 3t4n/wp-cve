<?php
/*
Plugin Name: WP User Profile Restriction
Plugin URI: https://wordpress.org/plugins/wp-user-profile-restriction/
Description: Disallow users to edit their own profile if he is not an Admin/Editor/Author.
Author: Shawon
Version: 1.0.5
Author URI: http://shawon.co
*/


// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;


function wpupr_disable_editing_my_profile () {
   $user = wp_get_current_user();
   $allowed_roles = array('editor', 'administrator', 'author');
   
   if( !array_intersect($allowed_roles, $user->roles ) )

   {
       wp_die( 'You are not permitted to change your Profile information. Please contact Site Admin to update your profile if necessary.' );
   }
}

add_action( 'load-profile.php', 'wpupr_disable_editing_my_profile' );
