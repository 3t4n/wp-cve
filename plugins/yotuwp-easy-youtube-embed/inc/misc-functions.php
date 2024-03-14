<?php

function yotuwp_doing_cron() {

	// Bail if not doing WordPress cron (>4.8.0)
	if ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) {
		return true;

	// Bail if not doing WordPress cron (<4.8.0)
	} elseif ( defined( 'DOING_CRON' ) && ( true === DOING_CRON ) ) {
		return true;
	}

	// Default to false
	return false;
}

function yotuwp_video_title( $video ) {
	return apply_filters('yotuwp_video_title', $video->snippet->title, $video );
}

function yotuwp_video_description( $video ) {
	$desc = apply_filters( 'yotuwp_video_description', nl2br(strip_tags($video->snippet->description)), $video );
	return wp_kses_post( $desc );
}

function yotuwp_video_thumb( $video ) {
	$url = (isset( $video->snippet->thumbnails) && isset( $video->snippet->thumbnails->standard) )? $video->snippet->thumbnails->standard->url : $video->snippet->thumbnails->high->url;
	return apply_filters( 'yotuwp_video_thumbnail', $url, $video );
}


function yotuwp_kses( $content ) {
	//return $content;

	$allowed_html = wp_kses_allowed_html( 'post' );
	
	// iframe
	$allowed_html['iframe'] = array(
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	);
	// form fields - input
	$allowed_html['input'] = array(
		'class'     => array(),
		'data-*'  => 1,
		'id'        => array(),
		'name'      => array(),
		'value'     => array(),
		'type'      => array(),
		'selected'  => array(),
		'checked'   => array(),
	);
	// select
	$allowed_html['select'] = array(
		'class'  => array(),
		'data-*'  => 1,
		'id'     => array(),
		'name'   => array(),
		'value'  => array(),
		'type'   => array(),
	);
	// select options
	$allowed_html['option'] = array(
		'selected' => array(),
		'value' => array(),
	);
	// style
	$allowed_html['style'] = array(
		'types' => array(),
	);

	// style
	$allowed_html['script'] = array(
		'src' => array(),
		'type' => array(),
	);
	

	return wp_kses( $content, $allowed_html );
}

