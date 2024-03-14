<?php
/* wppa-video.php
* Package: wp-photo-album-plus
*
* Contains all video routines
* Version 8.4.06.003
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Video files support. Define supported filetypes.
global $wppa_supported_video_extensions;
	$wppa_supported_video_extensions = array( 'mp4', 'ogv', 'webm' );

// See if a photo is a video
// Returns array with all available file extensions or false if not a video
function wppa_is_video( $id ) {
global $wppa_supported_video_extensions;

	if ( ! $id ) return false;					// No id

	$ext = wppa_get_photo_item( $id, 'ext' );
	if ( $ext != 'xxx' ) return false;	// This is not a video

	$result = array();
	$path = wppa_get_photo_path( $id, false );
	$raw_path = wppa_strip_ext( $path );
	foreach ( $wppa_supported_video_extensions as $ext ) {
		if ( is_file( $raw_path.'.'.$ext ) ) {
			$result[$ext] = $ext;
		}
	}
	if ( empty( $result ) ) {
		return false;	// Its multimedia but not video
	}

	return $result;
}

// Return the html for video display
function wppa_get_video_html( $args ) {

	extract( wp_parse_args( (array) $args, array (
					'id'			=> '0',
					'width'			=> '0',
					'widthp' 		=> '0',
					'height' 		=> '0',
					'controls' 		=> true,
					'margin_top' 	=> '0',
					'margin_bottom' => '0',
					'tagid' 		=> 'video-' . wppa( 'mocc' ),
					'cursor' 		=> '',
					'events' 		=> '',
					'title' 		=> '',
					'preload' 		=> 'metadata',
					'onclick' 		=> '',
					'lb' 			=> false,
					'class' 		=> '',
					'style' 		=> '',
					'use_thumb' 	=> false,
					'autoplay' 		=> false
					) ) );

	// No id? no go
	if ( ! $id ) return '';

	// May the current user see this video?
	if ( ! wppa_is_photo_visible( $id ) ) {
		return '';
	}

	// Not a video? no go
	if ( ! wppa_is_video( $id ) ) return '';

	extract( wp_parse_args( (array) wppa_is_video( $id ), array (
					'mp4' 	=> false,
					'ogv' 	=> false,
					'webm' 	=> false
					) ) );

	// Prepare attributes
	$w 		= $width ? 'width:'.$width.'px;' : '';
	$w 		= $widthp ? 'width:'.$widthp.'%;' : $w;
	$h 		= $height ? 'height:'.$height.'px;' : '';
	$t 		= $margin_top ? 'margin-top:'.$margin_top.'px;' : '';
	$b 		= $margin_bottom ? 'margin-bottom:'.$margin_bottom.'px;' : '';
	$ctrl 	= $controls ? ' controls' : '';
	$tit 	= $title ? ' title="'.$title.'"' : '';
	$onc 	= $onclick ? ' onclick="'.$onclick.'"' : '';
	$cls 	= $class ? ' class="'.$class.'"' : '';
	$style 	= $style ? trim( $style, '; ' ) : '';
	$play 	= $autoplay ? ' autoplay' : '';
	$cursor = $cursor ? 'cursor:'.$cursor.';' : '';

	// See if there is a poster image
	$poster_photo_path = wppa_get_photo_path( $id );
	$poster_thumb_path = wppa_get_thumb_path( $id );
	$poster_photo = is_file ( $poster_photo_path ) ? ' poster="' . wppa_get_photo_url( $id ) . '"' : '';
	$poster_thumb = is_file ( $poster_thumb_path ) ? ' poster="' . wppa_get_thumb_url( $id ) . '"' : '';
	$poster = '';	// Init to none

	// Thumbnail?
	if ( $use_thumb ) {
		$poster = $poster_thumb;
	}
	// Fullsize image
	else {
		$poster = $poster_photo;
	}

	// If the poster exists and no controls, we need no preload at all.
	if ( $poster && ! $controls ) {
		$preload = 'none';
	}

	// Do we have html5 video tag supported filetypes on board?
	if ( $mp4 || $ogv || $webm ) {

		// Assume the browser supports html5
		$result = '<video id="'.$tagid.'" '.$ctrl.$play.' style="'.$style.$w.$h.$t.$b.$cursor.'" '.$events.' '.$tit.$onc.$poster.' preload="'.$preload.'"'.$cls.' >';
// wppa_dump($result);

		$result .= wppa_get_video_body( $id, false, $width, $height );

		// Close the video tag
		$result .= '</video>';
	}

	// Done
	return $result;
}

// Get the content of the video tag for photo(video)id = $id
function wppa_get_video_body( $id ) {

	// Find extensions of available video files
	$is_video = wppa_is_video( $id, true );

	// Not a video? no go
	if ( ! $is_video ) return '';

	// Find video url with no version and no extension
//	wppa( 'no_ver', true );
	$source = wppa_strip_ext( wppa_get_photo_url( $id, false ) );
//	wppa( 'no_ver', false );

	$result = '';
	foreach ( $is_video as $ext ) {
		$result .= '<source src="' . $source . '.' . $ext . '" type="' . str_replace( 'ogv', 'ogg', 'video/' . $ext ) . '">';
	}
	$result .= esc_js( __( 'There is no filetype available for your browser, or your browser does not support html5 video', 'wp-photo-album-plus' ) );

	return $result;
}

// Copy the files only
function wppa_copy_video_files( $fromid, $toid ) {
global $wppa_supported_video_extensions;

	// Is it a video?
	if ( ! wppa_is_video( $fromid ) ) return false;

	// Get paths
	$from_path 		= wppa_get_photo_path( $fromid, false );
	$raw_from_path 	= wppa_strip_ext( $from_path );
	$to_path 		= wppa_get_photo_path( $toid, false );
	$raw_to_path 	= wppa_strip_ext( $to_path );

	// Copy the media files
	foreach ( $wppa_supported_video_extensions as $ext ) {
		$file = $raw_from_path . '.' . $ext;
		if ( is_file( $file ) ) {
			if ( ! wppa_copy( $file, $raw_to_path . '.' . $ext ) ) return false;
		}
	}

	// Done!
	return true;
}

function wppa_get_videox( $id, $where = 'prod' ) {

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) return false;

	if ( $where == 'prod' && $thumb['videox'] ) {
		return $thumb['videox'];
	}

	$exts = wppa_is_video( $id );

	if ( ! $exts ) return '0';

	if ( ! $thumb['videox'] ) {
		if ( in_array( 'mp4', $exts ) ) {
			wppa_fix_video_metadata( $id, 'get_videox' );
		}
	}
	else {
		return strval( intval( $thumb['videox'] ) );
	}

	$thumb = wppa_cache_photo( $id );
	if ( $thumb['videox'] ) {
		$result = $thumb['videox'];
	}
	else {
		$result = wppa_opt( 'video_width' );
	}
	return strval( intval( $result ) );
}

function wppa_get_videoy( $id, $where = 'prod' ) {

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) return false;

	if ( $where == 'prod' && $thumb['videoy'] ) {
		return $thumb['videoy'];
	}

	$exts = wppa_is_video( $id );

	if ( ! $exts ) return '0';

	if ( ! $thumb['videoy'] ) {
		if ( in_array( 'mp4', $exts ) ) {
			wppa_fix_video_metadata( $id, 'get_videoy' );
		}
	}
	else {
		return strval( intval( $thumb['videoy'] ) );
	}

	$thumb = wppa_cache_photo( $id );
	if ( $thumb['videoy'] ) {
		$result = $thumb['videoy'];
	}
	else {
		$result = wppa_opt( 'video_height' );
	}
	return strval( intval( $result ) );
}
