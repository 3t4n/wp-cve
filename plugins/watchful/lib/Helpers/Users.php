<?php
/**
 * Users helper.
 *
 * @version     2020-07-06 10:08 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;


class Users
{

    /**
     * get all Administrator users
     *
     * User roles are:
     * Super Admin (for Multisite WP)
     * Administrator (for single site)
     * Editor
     * Author
     * Contributor
     * Subscriber
     *
     * @return array
     */
    public function  get_administrators_user(){
        $args = array(
            'role' => 'administrator',
            'orderby' => 'user_nicename',
            'order' => 'ASC'
        );
        $userList = get_users( $args );
        $administrators = array();
        if ( is_array( $userList ) && count( $userList ) > 0 ){
            foreach ( $userList as $user ){
	            $caps = array();
	            if ( !empty( $user->caps ) ) {
		            foreach ( $user->caps as $key => $val ){
			            $caps[] = $key;
		            }
	            }
                $administrator_array = array(
                    "user_login" => $user->user_login,
                    "user_nicename" => $user->user_nicename,
                    "user_email" => $user->user_email,
                    "user_url" => $user->user_url,
                    "id" => $user->ID,
                    "display_name" => $user->display_name,
                    "caps" => $caps
                );
                $administrators[] = $administrator_array;
            }
        }
        return $administrators;
    }
}
