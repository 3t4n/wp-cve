<?php

  if( !defined( 'ABSPATH' ) ){
      exit;
  }

    $item_no                = get_post_meta($postid, 'item_no', true);
    $loop                   = get_post_meta($postid, 'loop', true);
    $margin                 = get_post_meta($postid, 'margin', true);
    $navigation             = get_post_meta($postid, 'navigation', true);
    $navigation_position    = get_post_meta($postid, 'navigation_position', true);
    $pagination             = get_post_meta($postid, 'pagination', true);
    $pagination_position    = get_post_meta($postid, 'pagination_position', true);
    $autoplay               = get_post_meta($postid, 'autoplay', true);
    $autoplay_speed         = get_post_meta($postid, 'autoplay_speed', true);
    $stop_hover             = get_post_meta($postid, 'stop_hover', true);
    $autoplaytimeout        = get_post_meta($postid, 'autoplaytimeout', true);
    $itemsdesktop           = get_post_meta($postid, 'itemsdesktop', true);
    $itemsdesktopsmall      = get_post_meta($postid,'itemsdesktopsmall', true);
    $itemsmobile            = get_post_meta($postid, 'itemsmobile', true); 
    $font_size              = get_post_meta($postid, 'font_size', true);
    $heading_color_picker   = get_post_meta($postid, 'heading_color_picker', true);
    $content_color          = get_post_meta($postid, 'content_color', true);
    $img_opc_color          = get_post_meta($postid, 'img_opc_color', true);
    $opacity                = get_post_meta($postid, 'opacity', true);
    $excerpt_lenght         = get_post_meta($postid, 'excerpt_lenght', true);
    $btn_readmore         	= get_post_meta($postid, 'btn_readmore', true);
    $excerpt_color          = get_post_meta($postid, 'excerpt_color', true);
    $img_show_hide          = get_post_meta($postid, 'img_show_hide', true);
    $img_height         	= get_post_meta($postid, 'img_height', true);
    $nav_text_color         = get_post_meta($postid, 'nav_text_color', true);
    $nav_hover_text_color   = get_post_meta($postid, 'nav_hover_text_color', true);
    $nav_bg_color        	= get_post_meta($postid, 'nav_bg_color', true);
    $nav_hover_bg_color     = get_post_meta($postid, 'nav_hover_bg_color', true);
    $pagination_color     	= get_post_meta($postid, 'pagination_color', true);
    $pagination_bg_color    = get_post_meta($postid, 'pagination_bg_color', true);
    $pagination_style    	= get_post_meta($postid, 'pagination_style', true);
    $img_show_hide_captions = get_post_meta($postid, 'img_show_hide_captions', true);
	
	
	
	
    function get_excerpt($excerpt_lenght = 62){
        $excerpt = get_the_content();
        $excerpt = preg_replace(" ([.*?])",'',$excerpt);
        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $excerpt_lenght);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim(preg_replace( '/s+/', ' ', $excerpt));
        return $excerpt;
    }