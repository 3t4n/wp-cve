<?php

/*
Plugin Name: Always Edit in HTML
Plugin URI: https:/zeropointdevelopment.com/wordpress-plugins/always-edit-in-html-wordpress-plugin/
Description: Opens page and post editor in HTML mode to preserve formatting.  Hides Visual editor tab on Classic Editor.
Version: 2.4.5
Author: DeveloperWil
Author URI: https://profiles.wordpress.org/developerwil

    Copyright © 2016 Zero Point Development.  zeropointdevelopment.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
global $post;

/**
 * Add language options
 */
load_plugin_textdomain( 'always-edit-in-html', false, basename( dirname( __FILE__ ) ) . '/lang' );

// Add actions for adding to post/page and saving the option
if ( is_admin() ){
    add_action( 'admin_init', 'always_edit_in_html_create_options_box' );
    add_action( 'admin_head', 'always_edit_in_html_handler' );
    add_action( 'save_post', 'always_edit_in_html_save_postdata', $post );
}


/**
 * Turn off the rich editing capability
 *
 * Removes the tab that switches to the visual editor
 */
function always_edit_in_html_handler(){
    global $post;

    // Check that we have a post object here, else return
    if( $post == null ) return;

    echo '<style type="text/css">';
    echo '#always-edit-in-html .inside{background: url('.plugins_url( '/images/zeropointdevelopment-mark.png', __FILE__ ).') no-repeat 98% 0%;padding-right:42px;}';
    echo '</style>';


    // Get the meta value and check that it's switched on
    $editInHTML = always_edit_in_html_get_html_edit_status( $post->ID );
    if ( $editInHTML ){
        // Hide "Visual" tab
        echo '<style type="text/css">';
        echo '#content-tmce.wp-switch-editor.switch-tmce{display:none;}';
        echo '</style>';

        // Set the editor to HTML mode ("Text")
        add_filter( 'wp_default_editor', function () {
            return 'html';
        });
    }
}

/**
 * Adds the option box to Pages, Posts and any other publicly declared Custom Post Types in the RHS column
 */
function always_edit_in_html_create_options_box(){

    // Add to pages
    add_meta_box( 'always-edit-in-html', __( 'Always edit in HTML', 'always-edit-in-html' ),
        'always_edit_in_html_custom_box', 'page' , 'side');

    // Add to posts
    add_meta_box( 'always-edit-in-html', __( 'Always edit in HTML', 'always-edit-in-html' ),
        'always_edit_in_html_custom_box','post','side');

    // Add to any other public declared post types
    $args = array(
        'public'   => true,
        '_builtin' => false
    );
    $post_types = get_post_types( $args, 'names' );

    foreach ( $post_types as $post_type ) {
        add_meta_box( 'always-edit-in-html', __( 'Always edit in HTML', 'always-edit-in-html' ),
            'always_edit_in_html_custom_box',$post_type,'side');
    }

}


/**
 * Creates the Edit in HTML options box on post/page
 *
 * @param $post
 */
function always_edit_in_html_custom_box( $post ){

    // Check that data is from this post
    wp_nonce_field( plugin_basename( __FILE__ ), 'always_edit_in_html_noncename' );

    // Get the current status for this post
    $editInHTML = always_edit_in_html_get_html_edit_status( $post->ID );

    // Create the form  with the options field and brief explaination of what it does.
    echo '<p>'.__( 'Removes the Visual and Text editor tabs and opens this page/post in HTML mode', 'always-edit-in-html' ).'</p>';
    echo '<label for="always_edit_in_html">'.__( 'Always edit in HTML?', 'always-edit-in-html' ).'</label> ';
    echo '<input type="checkbox" id="always_edit_in_html" name="always_edit_in_html" value="on" ';

    // If the option is currently being used then check the options box
    if ( $editInHTML ){
        echo 'checked="checked"';
    }
    echo ' />';
}

/**
 * Grabs the Always Edit in HTML option field and checks to see if it's set
 *
 * @param $id
 * @return bool
 */
function always_edit_in_html_get_html_edit_status( $id ){
    $editInHTML=get_post_meta( $id, 'editInHTML', true);

    if( $editInHTML === "on" ){
        return true;
    }
    else{
        return false;
    }
}

/**
 * Save the Always Edit in HTML options along with the post update
 *
 * @param $post_id
 * @return mixed
 */
function always_edit_in_html_save_postdata( $post_id ){
    // Quick check to make sure data belongs to this post
    if( ( !isset($_POST['always_edit_in_html_noncename']) ) || ( !wp_verify_nonce( $_POST['always_edit_in_html_noncename'], plugin_basename( __FILE__ ) ) ) ){
        return $post_id;
    }

    // Don't do anything for an autosave
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return $post_id;
    }

    // Make sure we have the permissions to update a post or page
    if( 'page' === $_POST['post_type'] ){
        if( !current_user_can( 'edit_page', $post_id ) ){
            return $post_id;
        }
    }
    else{
        if( !current_user_can( 'edit_post', $post_id ) ){
            return $post_id;
        }
    }

    // Checks all done so save the option
    if( isset( $_POST['always_edit_in_html'] ) ){
        update_post_meta( $post_id, 'editInHTML', 'on' );
    }
    else{
        update_post_meta( $post_id, 'editInHTML', 'off' );
    }

    // Returns $post_id to preserve other filters
    return $post_id	;
}
