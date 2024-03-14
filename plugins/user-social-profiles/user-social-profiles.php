<?php
/*
Plugin Name: User Social Profiles
Plugin URI: http://wordpress.org/plugins/user-social-profiles
Description: Adds some social profiles links to user account.
Version: 0.1.5
Author: BlogOnYourOwn
Author URI: https://blogonyourown.com/
License: GPLv2
*/

if ( ! class_exists( 'UserSocialProfiles' ) ) {

class UserSocialProfiles {
    
    //Additional contact methods
    function new_contactmethods( $user_contact ) {
        //Twitter
        $user_contact['twitter'] = __( 'Twitter', 'user-social-profiles');
        //Facebook
        $user_contact['facebook'] = __( 'Facebook' , 'user-social-profiles');
        //Google plus
        $user_contact['googleplus'] = __( 'Google Plus', 'user-social-profiles');
        //Instagram
        $user_contact['instagram'] = __( 'Instagram' , 'user-social-profiles');
        //Pinterest
        $user_contact['pinterest'] = __( 'Pinterest', 'user-social-profiles');

        return $user_contact;
    }

    function __construct() {
        add_filter('user_contactmethods', array( $this, 'new_contactmethods' ) ,10,1);
        load_plugin_textdomain( 'user-social-profiles', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }
    
}

new UserSocialProfiles;

}

