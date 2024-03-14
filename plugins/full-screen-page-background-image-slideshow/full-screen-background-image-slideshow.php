<?php
/*
Plugin Name: Full Screen (Page) Background Image Slideshow
Plugin URI: http://WPDevSnippets.com/full-screen-page-background-image-slideshow-plugin
Description: This plugin allows you to add single or multiple backgrounds that spans behind whole website contents. 
Version: 1.1
Author: Mohsin Rasool
Author URI: http://WPDevSnippets.com
License: GPL2
 
    Copyright 2013  WPDevSnippets.com (email : contact@wpdevsnippets.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

*/

define('FSI_IMAGES_ALLOWED', 10);
define('FSI_PLUGIN_URL', WP_PLUGIN_URL.'/full-screen-page-background-image-slideshow');

include 'admin-settings.php';
include 'inc/scripts.php';
include 'inc/styles.php';
include 'inc/page-metabox.php';

add_filter('wp_footer', 'fsi_full_screen_images');
function fsi_full_screen_images(){
    $images = get_option('fsi_images');
    
    if(empty($images))  return;
    global $post;
    
    if(is_page() && get_post_meta($post->ID, 'fsi_fs_images',true)){
        $images = array();
        for($i=0; $i<WPDS_NUM_ADD_IMAGES; $i++){
            if(has_fsi_bg_image($post->ID,$i)){
                $images[] = the_fsi_bg_image_url($post->ID,$i);
            }
        }        
    }

    if(get_option('fsi_random'))
        shuffle($images);
    
    
    if( wpdev_fsi_should_display() ) {
?>
    <div id="fsi-full-bg">
    <?php  for($i=0; $i<count($images); $i++){   
            if(empty($images[$i])) continue;
     ?>
            <img src="<?php echo $images[$i];?>" />
    <?php  } ?>
    </div>
<?php

        if(get_option('fsi_overlay')== 1){
            echo '<div id="fsi-full-bg-overlay"></div>';
        }
    }

}

function wpdev_fsi_should_display(){
    
    if(is_page()) {
        global $post;
        $images = array();
        for($i=0; $i<WPDS_NUM_ADD_IMAGES; $i++){
            if(has_fsi_bg_image($post->ID,$i)){
                $images[] = the_fsi_bg_image_url($post->ID,$i);
            }
        }  
        if(!empty($images))
            return 11;
    }
    
    $display_on = get_option('fsi_display_on');

	if(!is_array($display_on))
		$display_on = array($display_on);

    if(is_front_page()) {
        if(in_array('frontpage', $display_on))
            return 1;
    }
    elseif (is_home() ) {
        if(in_array('home', $display_on))
            return 2;
    }
    elseif (is_page()) {
        if(in_array('pages', $display_on))
            return 3;
       
    }
    elseif (is_single()) {
        if(in_array('posts', $display_on))
            return 4;
    }
    elseif (is_category() ) {
        if(in_array('category', $display_on))
            return 5;
    }
    elseif (is_tag()) {
        if(in_array('tag', $display_on))
            return 6;
    }
     elseif (is_archive()) {
        if(in_array('archive', $display_on))
            return 7;
    }
   
    return false;
}


// Plugin Activation Hook
function wpdev_fsi_activate(){
    // Check if its a first install

    if(get_option('fsi_images')===false)
        add_option('fsi_images','');

    if(get_option('fsi_random')===false)
        add_option('fsi_random','1');

    if(get_option('fsi_overlay')===false)
        add_option('fsi_overlay','1');

    if(get_option('fsi_opacity')===false)
        add_option('fsi_opacity','100');

    if(get_option('fsi_animation_delay')===false)
        add_option('fsi_animation_delay','4');

    if(get_option('fsi_animation_duration')===false)
        add_option('fsi_animation_duration','5');
  
    if(get_option('fsi_display_on')===false)
        add_option('fsi_display_on',  array('frontpage','home','pages','posts','category','tag'));
    

}
register_activation_hook( __FILE__, 'wpdev_fsi_activate' );

?>