<?php

/* Sửa khoảng cách padding top cho video mp4 */
add_action( 'init', function () {
	remove_shortcode( 'ux_video' );
	add_shortcode("ux_video", function ($atts) {

	    extract( shortcode_atts( array(
	        'class' => '',
	        'visibility' => '',
	        'url' => 'https://www.youtube.com/watch?v=AoPiLg8DZ3A',
	        'height' => '56.25%',
	        'depth' => '',
	        'depth_hover' => ''
	    ), $atts ) );


	    $classes = array('video','video-fit','mb');
	    if ( $class ) $classes[] = $class;
	    if ( $visibility ) $classes[] = $visibility;

	    // start custom
	    $is_pt0 = "pt-0";
        if(str_contains($url, 'youtube.com')){
            $is_pt0="";
        }
        if(str_contains($url, 'youtu.be')){
            $is_pt0="";
        }
        if(str_contains($url, 'vimeo.com')){
            $is_pt0="";
        }

        if(isset( $_POST['ux_builder_action'] )){
        	$is_pt0= '';
        }

        if ( $is_pt0 ) $classes[] = $is_pt0;
        // end custom

	    $video = apply_filters('the_content', $url);

	    if($depth) $classes[] = 'box-shadow-'.$depth;
	    if($depth_hover) $classes[] = 'box-shadow-'.$depth_hover.'-hover';

	    $classes = implode(' ', $classes);

	    $height = array(
	      array( 'attribute' => 'padding-top', 'value' => $height),
	    );
	    
	    return '<div class="'.$classes.'" '.get_shortcode_inline_css($height).'>'.$video.'</div>';
	});
},20 );

