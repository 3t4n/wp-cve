<?php
/* wppa-audio.php
* Package: wp-photo-album-plus
*
* Contains all audio routines
* Version 8.4.03.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Audio files support. Define supported filetypes.
global $wppa_supported_audio_extensions;
	$wppa_supported_audio_extensions = array( 'mp3', 'wav', 'ogg' );


// See if a photo has audio
// Returns array with all available file extensions or false if not audio
function wppa_has_audio( $id ) {
global $wppa_supported_audio_extensions;

	if ( ! $id ) return false;					// No id

	$ext = wppa_get_photo_item( $id, 'ext' );
	if ( $ext != 'xxx' ) return false;	// This is not a audio

	$result = array();
	$path = wppa_get_photo_path( $id, false );
	$raw_path = wppa_strip_ext( $path );
	foreach ( $wppa_supported_audio_extensions as $ext ) {
		if ( is_file( $raw_path.'.'.$ext ) ) {
			$result[$ext] = $ext;
		}
	}
	if ( empty( $result ) ) {
		return false;				// Its multimedia but not audio
	}

	return $result;
}

// Return the html for audio display
function wppa_get_audio_html( $args ) {

	// Audio enabled?
	if ( ! wppa_switch( 'enable_audio' ) ) {
		return '';
	}

	extract( wp_parse_args( (array) $args, array (
					'id'			=> '0',
					'width'			=> '0',
					'height' 		=> '0',
					'controls' 		=> true,
					'margin_top' 	=> '0',
					'margin_bottom' => '0',
					'tagid' 		=> 'audio-' . wppa ( 'mocc' ),
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

	// Not a audio? no go
	if ( ! wppa_has_audio( $id ) ) return '';

	extract( wp_parse_args( (array) wppa_has_audio( $id ), array (
					'mp3' 	=> false,
					'wav' 	=> false,
					'ogg' 	=> false
					) ) );

	// Prepare attributes
	if ( $width ) {
		if ( wppa_is_int( $width ) ) $width .= 'px;';
		$w = 'width:'.$width.';';
	}
	else {
		if ( wppa_is_chrome() ) {
			$w = 'width:-webkit-fill-available;';
		}
		elseif ( wppa_is_firefox() ) {
			$w = 'width:-moz-available;';
		}
		else {
			$w = 'width:auto;';
		}
	}
//	$w 		= $width ? ' width:'.$width.'px;' : '';
	$h 		= $height ? 'height:'.$height.'px;' : '';
	$t 		= $margin_top ? 'margin-top:'.$margin_top.'px;' : '';
	$b 		= $margin_bottom ? 'margin-bottom:'.$margin_bottom.'px;' : '';
	$ctrl 	= $controls ? ' controls' : '';
	$tit 	= $title ? ' title="'.$title.'"' : '';
	$onc 	= $onclick ? ' onclick="'.$onclick.'"' : '';
	$cls 	= $class ? ' class="'.$class.'"' : '';
	$style 	= $style ? rtrim( trim( $style ), ';' ) . ';' : '';
	$play 	= $autoplay ? ' autoplay' : '';

	// Do we have html5 audio tag supported filetypes on board?
	if ( $mp3 || $wav || $ogg ) {

		// Assume the browser supports html5
		$result = 	'
		<audio
			id="'.$tagid.'"
			data-from="wppa" ' .
			$ctrl.
			$play.'
			style="'.$style.$w.$h.$t.$b.$cursor.'" ' .
			$events.
			$tit.$onc.'
			preload="'.$preload.'" '.
			$cls.'
			>';

		$result .= wppa_get_audio_body( $id, false, $width, $height );

		// Close the audio tag
		$result .= '</audio>';
	}

	// Done
	return $result;
}

// Get the content of the audio tag for photo(audio)id = $id
function wppa_get_audio_body( $id, $for_lb = false, $w = '0', $h = '0' ) {

	// Audio enabled?
	if ( ! wppa_switch( 'enable_audio' ) ) {
		return '';
	}

	$is_audio = wppa_has_audio( $id, true );

	// Not a audio? no go
	if ( ! $is_audio ) return '';

	// See what file types are present
	extract( wp_parse_args( $is_audio, array( 	'mp3' => false,
												'wav' => false,
												'ogg' => false
											)
							)
			);

	// Collect other data
	$width 		= $w ? $w : wppa_get_photox( $id );
	$height 	= $h ? $h : wppa_get_photoy( $id );
	$source 	= wppa_get_photo_url( $id, false );
	$source 	= substr( $source, 0, strrpos( $source, '.' ) );
	$class 		= $for_lb ? ' class="wppa-overlay-img"' : '';

	if ( isset( $_SERVER["HTTP_USER_AGENT"] ) ) {
		$is_opera 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'OPR' );
		$is_ie 		= strpos( $_SERVER["HTTP_USER_AGENT"], 'Trident' );
		$is_safari 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'Safari' );
	}
	else {
		$is_opera = false;
		$is_ie = false;
		$is_safari = false;
	}

	// Assume the browser supports html5
	$ext = '';
	if ( $is_ie ) {
		if ( $mp3 ) {
			$ext = 'mp3';
		}
	}
	elseif ( $is_safari ) {
		if ( $mp3 ) {
			$ext = 'mp3';
		}
		elseif ( $wav ) {
			$ext = 'wav';
		}
	}
	else {
		if ( $mp3 ) {
			$ext = 'mp3';
		}
		elseif( $wav ) {
			$ext = 'wav';
		}
		elseif( $ogg ) {
			$ext = 'ogg';
		}
	}

	$result = '';
	if ( $ext ) {
		$mime = str_replace( 'mp3', 'mpeg', 'audio/'.$ext );
		$result .= '<source src="'.$source.'.'.$ext.'" type="'.$mime.'">';
	}
	$result .= esc_js(__('There is no filetype available for your browser, or your browser does not support html5 audio', 'wp-photo-album-plus' ));

	return $result;
}

// Copy the files only
function wppa_copy_audio_files( $fromid, $toid ) {
global $wppa_supported_audio_extensions;

	// Is it an audio?
	if ( ! wppa_has_audio( $fromid ) ) return false;

	// Get paths
	$from_path 		= wppa_get_photo_path( $fromid, false );
	$raw_from_path 	= wppa_strip_ext( $from_path );
	$to_path 		= wppa_get_photo_path( $toid, false );
	$raw_to_path 	= wppa_strip_ext( $to_path );

	// Copy the media files
	foreach ( $wppa_supported_audio_extensions as $ext ) {
		$file = $raw_from_path . '.' . $ext;
		if ( is_file( $file ) ) {
			if ( ! wppa_copy( $file, $raw_to_path . '.' . $ext ) ) return false;
		}
	}

	// Done!
	return true;
}

function wppa_get_audio_control_height() {

	if ( ! isset( $_SERVER["HTTP_USER_AGENT"] ) ) {
		$result = '24';
	}
	elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], 'Edge' ) ) {
		$result = '30';
	}
	elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], 'Firefox' ) ) {
		$result = '40';
	}
	elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], 'Chrome' ) ) {
		if ( wppa_is_mobile() ) {
			$result = '48';
		}
		else {
			$result = '32';
		}
	}
	elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], 'Safari' ) ) {
		$result = '16';
	}
	else {
		$result = '28';
	}

	return $result;
}
