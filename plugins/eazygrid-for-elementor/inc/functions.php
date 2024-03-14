<?php

/**
 * Helper functions
 *
 * @package EazyGridElementor
 */
defined( 'ABSPATH' ) || die();

function ezg_ele_layout_image_list( array $list ) {
	if ( empty( $list ) ) {
		return;
	}

	$dir        = EAZYGRIDELEMENTOR_URL . 'assets/img/layout/';
	$extension  = '.svg';
	$laout_list = [];

	foreach ( $list as $key => $value ) {
		$laout_list[ $key ] = [
			'title' => $value,
			'url'   => $dir . $key . $extension,
		];
	}

	return $laout_list;
}

/**
 * @param $source
 * @param $url
 * @return mixed
 */
function ezg_ele_video_thumb( $source, $url ) {
	$vid_thumb     = '';
	$default_thumb = EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder.jpg';

	switch ( $source ) {
		case 'youtube':
			wp_parse_str( wp_parse_url( $url, PHP_URL_QUERY ), $url_params );
			$vid_thumb_get = isset( $url_params['v'] ) ? 'https://img.youtube.com/vi/' . $url_params['v'] . '/maxresdefault.jpg' : $default_thumb;
			if ( ezg_ele_check_url_exists( $vid_thumb_get ) ) {
				$vid_thumb = $vid_thumb_get;
			} else {
				$vid_thumb = 'https://img.youtube.com/vi/' . $url_params['v'] . '/0.jpg';
			}
			break;
		case 'vimeo':
			$vid = substr( $url, 18, 9 );
			$vid = ( strlen( $vid ) == 9 ) ? $vid : '';
			if ( $vid ) {
				// $hash      = unserialize( file_get_contents( "http://vimeo.com/api/v2/video/$vid.php" ) );
				$hash      = unserialize( wp_remote_get( "http://vimeo.com/api/v2/video/$vid.php" )['body'] );
				$thumb     = isset( $hash[0]['thumbnail_large'] ) ? $hash[0]['thumbnail_large'] : $hash[0]['thumbnail'];
				$full_url  = substr( $thumb, 0, -4 );
				$vid_thumb = ezg_ele_check_url_exists( $full_url ) ? $full_url : $default_thumb;
			} else {
				$vid_thumb = $default_thumb;
			}
			break;
		case 'dailymotion':
			$vid_id = str_replace( 'https://www.dailymotion.com/video/', '', $url );
			if ( $vid_id ) {
				// $vid_data  = json_decode( file_get_contents( 'https://api.dailymotion.com/video/' . $vid_id . '?fields=thumbnail_1080_url' ) );
				$vid_data  = json_decode( wp_remote_get( 'https://api.dailymotion.com/video/' . $vid_id . '?fields=thumbnail_1080_url' )['body'] );
				$vid_thumb = isset( $vid_data->thumbnail_1080_url ) ? $vid_data->thumbnail_1080_url : $default_thumb;
			} else {
				$vid_thumb = $default_thumb;
			}
			break;
		case 'hosted':
			$vid_thumb = $default_thumb;
			break;
	}

	return $vid_thumb;
}

/**
 * @param $file
 * @return mixed
 */
function ezg_ele_check_url_exists( $url ) {
	stream_context_set_default( [
		'ssl' => [
			'verify_peer' => false,
			'verify_peer_name' => false,
		],
	]);
	$file_headers = get_headers( esc_url( $url ) );
	if ( 'HTTP/1.1 404 Not Found' == $file_headers[0] ) {
		$exists = false;
	} else {
		$exists = true;
	}

	return $exists;
}

function ezg_ele_is_edit_mode() {
	return \Elementor\Plugin::$instance->editor->is_edit_mode();
}

/**
 * @param $post_id
 * @param null $length
 */
function ezg_ele_get_excerpt( $post_id = null, $length = 15 ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	return wp_trim_words( get_the_excerpt( $post_id ), $length );
}

/**
 * @param $post_id
 * @param array $args
 * @return null
 */
function ezg_ele_the_first_category( $post_id = null, $args = [], $echo = true ) {
	$args = wp_parse_args( $args, [
		'class' => 'ezg-ele-metro-post-grid__tag',
	] );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$categories = wp_get_post_terms( $post_id, 'category', [
		'fields' => 'id=>name',
	] );

	if ( is_wp_error( $categories ) || empty( $categories ) ) {
		return;
	}

	if ( true == $echo ) {
		printf(
			'<a href="%s" rel="tag" class="%s">%s</a>',
			esc_url( get_term_link( key( $categories ) ) ),
			esc_attr( $args['class'] ),
			esc_html( current( $categories ) )
		);
	} else {
		return sprintf(
			'<a href="%s" rel="tag" class="%s">%s</a>',
			esc_url( get_term_link( key( $categories ) ) ),
			esc_attr( $args['class'] ),
			esc_html( current( $categories ) )
		);
	}
}
