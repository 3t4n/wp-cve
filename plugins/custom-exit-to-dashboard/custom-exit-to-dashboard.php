<?php

/*
 * Plugin name: Custom exit to Dashboard
 * Plugin URI: N/A
 * Description: This plugin is giving you a chance to edit the exit to dashboard link. For Elmentor editor, also elementor pro. For example, for exiting from
 * editor to all posts listing page, not the current post. Similar with pages, etc.
 * Version: 1.0
 * Author: Martin Tee
 */

function my_exit_to_dashboard(){
    if ( get_post_type( get_the_ID() ) == 'post' ) {
        $url=admin_url() . 'edit.php';
    }
    elseif( get_post_type( get_the_ID() ) == 'page' ) {
        $url=admin_url().'edit.php?post_type=page';
    }
    elseif ( get_post_type( get_the_ID() ) == 'elementor_library' ) {
        $url=admin_url().'edit.php?post_type=elementor_library&tabs_group=theme';
    }
    else {
        $url=admin_url();
    }

    return $url;
}
add_filter('elementor/document/urls/exit_to_dashboard' , 'my_exit_to_dashboard');

?>