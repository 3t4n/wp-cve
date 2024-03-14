<?php
/**
 * @package Really Disable Emojis
 * @author Christian Leuenberg <christian@l-net.biz>
 * @license GPLv3
 * @copyright 2018 by Christian Leuenberg
 */
/*
Plugin Name: Really Disable Emojis
Plugin URI: 
Description: Disables the automatic emojis (smilies) replacement function. Really! :-)
Author: Christian Leuenberg, L.net Web Solutions
Author URI: https://www.l-net.biz/
Version: 1.1
Text Domain: reallydisableemojis
Domain Path: /languages/
License: GPLv3

	Really Disable Emojis for WordPress
    Copyright (C) 2018 Christian Leuenberg

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* Remove core emoji stylesheets */

function rde_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'rde_disable_emojis' );

/* Disable smilies on plugin activation */

function rde_deactivate_smilies() {
    //set the option to disable smilies
    update_option( 'use_smilies', 0, true );
}
register_activation_hook( __FILE__, 'rde_deactivate_smilies' );


/* Enable smilies on plugin deactivation */

function rde_activate_smilies() {
    //set the option to enable smilies
    update_option( 'use_smilies', 1, true );
}
register_deactivation_hook( __FILE__, 'rde_activate_smilies' );


/* Admin notice on plugin activation */

register_activation_hook( __FILE__, 'rde_admin_notice_activation_hook' );

function rde_admin_notice_activation_hook() {
    // Create transient data
    set_transient( 'rde-admin-notice', true, 5 );
}
add_action( 'admin_notices', 'rde_admin_notice' );
 
function rde_admin_notice(){
 
    /* Check transient, if available display notice */
    if( get_transient( 'rde-admin-notice' ) ) {
        ?>
        <div class="updated notice is-dismissible">
            <p>Alright, automatic replacement of emojis is now <strong>disabled</strong>! :-)</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'rde-admin-notice' );
    }
}