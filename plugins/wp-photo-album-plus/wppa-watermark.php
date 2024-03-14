<?php
/* wppa-watermark.php
*
* Functions used for the application of watermarks
* Version 8.6.03.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function wppa_create_all_textual_watermark_files() {

	$sopts = array( __('TV subtitle style', 'wp-photo-album-plus' ), __('White text on black background', 'wp-photo-album-plus' ), __('Black text on white background', 'wp-photo-album-plus' ), __('Reverse TV style (Utopia)', 'wp-photo-album-plus' ), __('White on transparent background', 'wp-photo-album-plus' ), __('Black on transparent background', 'wp-photo-album-plus' ) );
	$svals = array( 'tvstyle', 'whiteonblack', 'blackonwhite', 'utopia', 'white', 'black' );
	$fopts = array( 'System' );
	$fvals = array( 'system' );
	$fonts = wppa_glob( WPPA_UPLOAD_PATH . '/fonts/*.ttf' );
	sort($fonts);
	foreach ( $fonts as $font ) {
		$f = basename($font);
		$f = preg_replace('/\.[^.]*$/', '', $f);
		$F = ucfirst($f); // strtoupper(substr($f,0,1)).substr($f,1);
		$fopts[] = $F;
		$fvals[] = $f;
	}
	foreach ( array_keys( $sopts ) as $skey ) {
		foreach ( array_keys( $fopts ) as $fkey ) {
			wppa_create_textual_watermark_file( array( 'font' => $fvals[$fkey], 'text' => $sopts[$skey], 'style' => $svals[$skey], 'filebasename' => $svals[$skey].'-'.$fvals[$fkey] ) );
			wppa_create_textual_watermark_file( array( 'font' => $fvals[$fkey], 'text' => $fopts[$fkey], 'style' => $svals[$skey], 'filebasename' => $fvals[$fkey].'-'.$svals[$skey] ) );
		}
	}
}

function wppa_create_textual_watermark_file( $args ) {

	if ( ! function_exists( 'imagettfbbox' ) ) {
		wppa_log('err', 'Textual watermarks not supported by this php version');
		return false;
	}

	// See what we have
	$args = wp_parse_args( ( array ) $args, array( 	'content' 		=> '---preview---',
													'pos' 			=> 'cencen',
													'id'			=> '0',
													'font' 			=> wppa_opt( 'textual_watermark_font' ),
													'text' 			=> '',
													'style' 		=> wppa_opt( 'textual_watermark_type' ),
													'filebasename' 	=> 'dummy',
													'url' 			=> false,
													'width'			=> '',
													'height' 		=> '',
													'transp' 		=> '0',
													 ) );

	// We may have been called from wppa_get_water_file_and_pos() just to find the settings
	// In this case there is no id given.
	$id = $args['id'];
	if ( ! $id && $args['content'] != '---preview---' ) {
		return false;
	}

	// Set special values in case of preview
	if ( ! $id ) {
		$t = wppa_opt( 'watermark_file' );
		if ( in_array( $t, array( '---filename---', '---name---', '---description---', '---predef---' ) ) ) {
			$args['content'] = $t;
		}
		else {
			$args['content'] = '---predef---';
		}
		$preview 		= true;
		$fontsize 		= $args['font'] == 'system' ? 5 : 12;
		$padding   		= 6;
		$linespacing 	= ceil( $fontsize * 2 / 3 );
	}
	else {
		$preview = false;
		$fontsize 		= wppa_opt( 'textual_watermark_size' );
		if ( $args['font'] == 'system' ) $fontsize = min( $fontsize, 5 );
		$padding   		= 12;
		$linespacing 	= ceil( $fontsize * 2 / 3 );
	}

	// Set font specific vars
	$fontfile 			= $args['font'] == 'system' ? '' : WPPA_UPLOAD_PATH.'/fonts/'.$args['font'].'.ttf';

	// Output file
	if ( $preview ) $filename 	= WPPA_UPLOAD_PATH.'/fonts/wmf'.$args['filebasename'].'.png';
	else $filename 	= WPPA_UPLOAD_PATH.'/temp/wmf'.$args['filebasename'].'.png';

	// Preprocess the text
	if ( ! $args['text'] ) {
		switch ( $args['content'] ) {
			case '---filename---':
				$text = $id ? wppa_get_photo_item( $id, 'filename' ) : 'File-name.jpg';
				break;
			case '---name---':
				$text = $id ? wppa_get_photo_name( $id ) : 'The Photo Name';
				break;
			case '---description---':
				$text = $id ? strip_tags( wppa_strip_tags( wppa_get_photo_desc( $id ), 'style&script' ) ) : 'The Photo Description';
				break;
			case '---predef---':
				$text = wppa_opt( 'textual_watermark_text' );
				if ( $args['font'] != 'system' ) {
					$text = str_replace( '( c )', '&copy;', $text );
					$text = str_replace( '( R )', '&reg;', $text );
				}
				$text = html_entity_decode( $text );
				$text = str_replace( 'w#site', get_bloginfo( 'url' ), $text );
				// $usr = wppa_get_user_by( 'login', wppa_get_photo_item( $id, 'owner' ) );
				if ( $id ) {
					$text = wppa_translate_photo_keywords( $id, $text );
					$text = wppa_translate_album_keywords( wppa_get_photo_item( $id, 'album' ), $text );
					$text = wppa_filter_iptc( $text, $id );	// Render IPTC tags
					$text = wppa_filter_exif( $text, $id );	// Render EXIF tags
				}
				$text = trim( $text );
				break;

			default:
				wppa_log( 'err', 'Unimplemented arg '.serialize($args).' in wppa_create_textual_watermark_file()' );
				return false;
		}
	}
	else {
		$text = $args['text'];
	}

	// Any text anyway?
	if ( ! strlen( $text ) ) {
		wppa_log( 'err', 'No text for textual watermark. photo='.$id );
		return false;		// No text -> no watermark
	}

	// Split text on linebreaks
	$text 	= str_replace( "\n", '\n', $text );
	$lines 	= explode( '\n', $text );

	// Trim and remove empty lines
	$temp = $lines;
	$lines = array();
	foreach ( $temp as $line ) {
		$line = trim( $line );
		if ( $line ) $lines[] = $line;
	}

	// Find image width
	if ( $args['width'] ) $image_width = $args['width']; else $image_width = '';
	if ( $args['height'] ) $image_height = $args['height']; else $image_height = '';

	if ( $preview ) {
		if ( ! $image_width ) $image_width = 2000;
		if ( ! $image_height ) $image_height = 1000;
	}
	else {
		$temp = wppa_get_imagexy( $id );
		if ( ! is_array( $temp ) ) {
			wppa_log( 'err', 'Trying to apply a watermark on a non image file. Id = '.$id );
			return false;	// not an image
		}
		if ( ! $image_width ) $image_width = $temp[0];
		if ( ! $image_height ) $image_height = $temp[1];
	}

	$width_fits = false;

	while ( ! $width_fits ) {
		// Find pixel linelengths
		foreach ( array_keys( $lines ) as $key ) {
			$lines[$key] = trim( $lines[$key] );
			if ( $args['font'] == 'system' ) {
				$lengths[$key] = strlen( $lines[$key] ) * imagefontwidth( $fontsize );
			}
			else {
				$temp = imagettfbbox ( $fontsize , 0.0 , $fontfile , $lines[$key] );
				$lengths[$key] = $temp[2] - $temp[0];
			}
		}
		$maxlen = wppa_array_max( $lengths );

		// Find canvas size
		$nlines 	= count( $lines );
		if ( $args['font'] == 'system' ) {
			$lineheight 	= imagefontheight( $fontsize );
		}
		else {
			$temp = imagettfbbox ( $fontsize , 0.0 , $fontfile , $lines[0] );
			$lineheight = $temp[3] - $temp[7];
		}
		$canvas_width 	= wppa_array_max( $lengths ) + 4 * $padding;
		$canvas_height 	= ( $lineheight + $linespacing ) * count( $lines ) + 2 * $padding;

		// Does it fit?
		if ( $canvas_width > $image_width ) {
			// Break the longest line into two sublines. There should be a space in the right half, if not: fail
			$i = 0;
			$l = 0;
			foreach ( array_keys( $lines ) as $key ) {
				if ( strlen( $lines[$key] ) > $l ) {
					$i = $key;
					$l = strlen( $lines[$key] );
				}
			}
			$temp = $lines;
			$lines = array();
			$j = 0;
			while ( $j < $i ) {
				$lines[$j] = $temp[$j];
				$j++;
			}
			//
			$j = $i;
			$llen = strlen( $temp[$i] );
			$spos = floor( $llen / 2 );
			while ( $spos < $llen && substr( $temp[$i], $spos, 1 ) != ' ' ) $spos++;
			if ( $spos == $llen ) {	// Unable to find a space, give up
				wppa_log( 'err', 'Trying to apply a watermark that is too wide for the image. Id = '.$id );
				return false;	// too wide
			}
			$lines[$j] = substr( $temp[$i], 0, $spos );
			$lines[$j+1] = trim( str_replace( $lines[$j], '', $temp[$i] ) );
			$i++;
			//
			$j = $i + 1;
			while ( $j <= count( $temp ) ) {
				$lines[$j] = $temp[$i];
				$j++;
			}
		}
		else {
			$width_fits = true;
		}
		if ( $canvas_height > $image_height ) {
			wppa_log( 'err', 'Trying to apply a watermark that is too high for the image. Id = '.$id );
			return false;	// not an image
		}
	}

	// Create canvas
	$fg  = wppa_opt( 'watermark_fgcol_text' );
	$fgr = hexdec( substr( $fg, 1, 2 ) );
	$fgg = hexdec( substr( $fg, 3, 2 ) );
	$fgb = hexdec( substr( $fg, 5, 2 ) );
	$bg  = wppa_opt( 'watermark_bgcol_text' );
	$bgr = hexdec( substr( $bg, 1, 2 ) );
	$bgg = hexdec( substr( $bg, 3, 2 ) );
	$bgb = hexdec( substr( $bg, 5, 2 ) );
	$canvas 	= imagecreatetruecolor( $canvas_width, $canvas_height );
	$bgcolor 	= imagecolorallocatealpha( $canvas,   0,   0,   0, 127 );	// Transparent
	$white 		= imagecolorallocatealpha( $canvas, $bgr, $bgg, $bgb, $args['transp'] );
	$black 		= imagecolorallocatealpha( $canvas, $fgr, $fgg, $fgb, $args['transp'] );

	imagefill( $canvas, 0, 0, $bgcolor );
//	imagerectangle( $canvas, 0, 0, $canvas_width-1, $canvas_height-1, $white );	// debug

	// Define the text colors
	switch ( $args['style'] ) {
		case 'tvstyle':
		case 'whiteonblack':
			$fg = $white;
			$bg = $black;
			break;
		case 'utopia':
		case 'blackonwhite':
			$fg = $black;
			$bg = $white;
			break;
		case 'white':
			$fg = $white;
			$bg = $bgcolor;
			break;
		case 'black':
			$fg = $black;
			$bg = $bgcolor;
			break;
		default:
			$fg = $black;
			$bg = $bgcolor;
			break;
	}

	// Plot the text
	foreach ( array_keys( $lines ) as $lineno ) {
		if ( strpos( $args['pos'], 'lft' ) !== false ) $indent = 0;
		elseif ( strpos( $args['pos'], 'rht' ) !== false ) $indent = $maxlen - $lengths[$lineno];
		else $indent = floor( ( $maxlen - $lengths[$lineno] ) / 2 );
		switch ( $args['style'] ) {
			case 'tvstyle':
			case 'utopia':
				for ( $i=-1; $i<=1; $i++ ) {
					for ( $j=-1; $j<=1; $j++ ) {
						if ( $args['font'] == 'system' ) {
							imagestring( $canvas, $fontsize, 2 * $padding + $i + $indent, $padding + $lineno * ( $lineheight + $linespacing ) + $j,  $lines[$lineno], $bg );
						}
						else {
							imagettftext ( $canvas, $fontsize, 0, 2 * $padding + $i + $indent, $padding + ( $lineno + 1 ) * $lineheight + $lineno * $linespacing + $j, $bg, $fontfile, $lines[$lineno] );
						}
					}
				}
				if ( $args['font'] == 'system' ) {
					imagestring( $canvas, $fontsize, 2 * $padding + $indent, $padding + $lineno * ( $lineheight + $linespacing ),  $lines[$lineno], $fg );
				}
				else {
					imagettftext ( $canvas, $fontsize, 0, 2 * $padding + $indent, $padding + ( $lineno + 1 ) * $lineheight + $lineno * $linespacing, $fg, $fontfile, $lines[$lineno] );
				}
				break;
			case 'blackonwhite':
			case 'whiteonblack':
			case 'white':
			case 'black':
				$lft = $padding + $indent;
				$rht = 3 * $padding + $indent + $lengths[$lineno];
				$top = $lineno * ( $lineheight + $linespacing ) + $padding;
				$bot = ( $lineno + 1 ) * ( $lineheight + $linespacing ) + $padding;

				imagefilledrectangle( $canvas, $lft, $top+1, $rht, $bot, $bg );
//				imagerectangle( $canvas, $lft, $top, $rht, $bot, $fg );	// debug

				$top = $padding + $lineno * ( $lineheight + $linespacing ) + floor( $linespacing/2 );
				$lft = 2 * $padding + $indent;
				$bot = $padding + ( $lineno + 1 ) * ( $lineheight + $linespacing ) - ceil( $linespacing/2 );
				if ( $args['font'] == 'system' ) {
					imagestring( $canvas, $fontsize, $lft, $top,  $lines[$lineno], $fg );
				}
				else {
					imagettftext ( $canvas, $fontsize, 0, $lft, $bot-1, $fg, $fontfile, $lines[$lineno] );
				}
				break;
			default:
				wppa_log( 'err', 'Unimplemented textstyle: ' . $args['style'] . ' in wppa_create_textual_watermark_file()' );
				break;
		}
	}
	imagesavealpha( $canvas, true );
	wppa_imagepng( $canvas, $filename );
	imagedestroy( $canvas );
	if ( $args['url'] ) {
		$url = str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $filename );
		return $url;
	}
	else {
		return $filename;
	}
}

function wppa_array_max( $array ) {
	if ( ! is_array( $array ) ) return $array;
	$result = 0;
	foreach ( $array as $item ) if ( $item > $result ) $result = $item;
	return $result;
}

function wppa_get_water_file_and_pos( $id ) {

	// System defaults
	$result['file'] = wppa_opt( 'watermark_file' );	// default
	$result['pos'] 	= wppa_opt( 'watermark_pos' );	// default

	// Album specific overrule?
	if ( $id > '0' ) {
		$alb 		= wppa_get_photo_item( $id, 'album' );
		if ( $alb > '0' ) {
			$albfile 	= wppa_get_album_item( $alb, 'wmfile' );
			if ( $albfile ) {
				$result['file'] = $albfile;
			}
			$albpos 	= wppa_get_album_item( $alb, 'wmpos' );
			if ( $albpos ) {
				$result['pos'] = $albpos;
			}
		}
		else {
			return $result;
		}
	}
	else {
		return $result;
	}

	// User overrule?
	if ( wppa_switch( 'watermark_user' ) || wppa_get( 'watermark-file' ) ) {
		$user = wppa_get_user();
		if ( wppa_get( 'watermark-file' ) ) {
			$result['file'] = wppa_get( 'watermark-file' );
			wppa_update_option( 'wppa_watermark_file_' . $user, wppa_get( 'watermark-file' ) );
		}
		elseif ( wppa_get_option( 'wppa_watermark_file_' . $user, 'nil' ) != 'nil' ) {
			$result['file'] = wppa_get_option( 'wppa_watermark_file_' . $user );
		}
		if ( wppa_get( 'watermark-pos' ) ) {
			$result['pos'] = wppa_get( 'watermark-pos' );
			wppa_update_option( 'wppa_watermark_pos_' . $user, wppa_get( 'watermark-pos' ) );
		}
		elseif ( wppa_get_option( 'wppa_watermark_pos_' . $user, 'nil' ) != 'nil' ) {
			$result['pos'] = wppa_get_option( 'wppa_watermark_pos_' . $user );
		}
	}
	$result['select'] = $result['file'];

	if ( substr( $result['file'], 0, 3 ) == '---' && $result['file'] != '--- none ---' ) {			// Special identifier, not a file
		$result['file'] = wppa_create_textual_watermark_file( array( 'content' => $result['file'], 'pos' => $result['pos'], 'id' => $id ) );
	}
	elseif ( $result['file'] != '--- none ---' ) {
		$result['file'] = WPPA_UPLOAD_PATH . '/watermarks/' . $result['file'];
	}
	return $result;
}

function wppa_does_thumb_need_watermark( $id ) {

	// Watermarks enabled?
	if ( ! wppa_switch( 'watermark_on' ) ) {
		return false;
	}

	// Watermarks on thumbs?
	if ( ! wppa_switch( 'watermark_thumbs' ) ) {
		return false;
	}

	// If setting is ---none--- no watermark either.
	$temp = wppa_get_water_file_and_pos( $id );
	if ( ! $temp['file'] || basename( $temp['file'] ) == '--- none ---' || ! wppa_is_file( $temp['file'] ) ) {
		return false;	// No watermark this time
	}

	// Yes, we need a wm on thumb
	return true;
}

function wppa_add_watermark( $id ) {

	// Init
	if ( ! wppa_switch( 'watermark_on' ) ) return false;	// Watermarks off
//	if ( wppa_is_video( $id ) ) return false;					// Can not on a video

	// Find the watermark file and location
	$temp = wppa_get_water_file_and_pos( $id );
	$waterfile = $temp['file'];
	if ( ! $waterfile ) return false;					// an error has occurred

	$waterpos = $temp['pos'];										// default

	if ( basename( $waterfile ) == '--- none ---' ) {
		return false;	// No watermark this time
	}

	// Open the watermark file
	if ( ! wppa_is_path_safe( $waterfile ) ) {
		wppa_log( 'War', 'Unsafe path found in wppa_add_watermark(): ' . sanitize_text_field( $waterfile ) );
		return false;
	}

	$watersize = wppa_getimagesize( $waterfile );
	if ( ! is_array( $watersize ) ) return false;	// Not a valid picture file
	$waterimage = wppa_imagecreatefrompng( $waterfile );
	if ( empty( $waterimage ) or ( !$waterimage ) ) {
		wppa_log( 'Err', 'Watermark file '.$waterfile.' not found or corrupt' );
		return false;			// No image
	}
	imagealphablending( $waterimage, false );
	imagesavealpha( $waterimage, true );


	// Open the photo file
	$file = wppa_get_photo_path( $id );

	if ( ! is_file( $file ) ) return false;	// File gone

	$photosize = getimagesize( $file );
	if ( ! is_array( $photosize ) ) {
		return false;	// Not a valid photo
	}

	// Enough memory?
	if ( ! wppa_can_resize( $file, 0, false ) ) {
		wppa_log( 'War', 'Too little memory to add watermark on ' . $file );
		return false;
	}

	// Scale the watermark image to a percentage width of the photo
	$perc = wppa_opt( 'watermark_size' );
	if ( strpos( $waterfile, WPPA_UPLOAD_PATH.'/temp/wmf' ) === false && $perc != '0' ) { 	// Not for a text, not when off

		// Copy existing image
		$oldwaterimage 	= $waterimage;

		// Find target size
		$oldwidth 		= $watersize[0];
		$oldheight 		= $watersize[1];
		$newwaterwidth 	= round( $perc * $photosize[0] / 100 );
		$newwaterheight = round( $oldheight / $oldwidth * $newwaterwidth );

		// Create new canvas
		$waterimage = imagecreatetruecolor( $newwaterwidth, $newwaterheight );
		imagesavealpha( $waterimage, true );
		imagefill( $waterimage, 0, 0, imagecolorallocatealpha( $waterimage, 0, 0, 0, 127 ) );

		// Copy image
		imagecopyresampled ( $waterimage, $oldwaterimage, 0, 0, 0, 0, $newwaterwidth, $newwaterheight, $oldwidth, $oldheight);
		imagedestroy( $oldwaterimage );

		// Adjust sizes
		$watersize[0] = $newwaterwidth;
		$watersize[1] = $newwaterheight;
	}


	switch ( $photosize[2] ) {
		case 1: $tempimage = wppa_imagecreatefromgif( $file );
			$photoimage = imagecreatetruecolor( $photosize[0], $photosize[1] );
			imagecopy( $photoimage, $tempimage, 0, 0, 0, 0, $photosize[0], $photosize[1] );
			break;
		case 2: $photoimage = wppa_imagecreatefromjpeg( $file );
			break;
		case 3: $photoimage = wppa_imagecreatefrompng( $file );
			break;
		default:
			$photoimage = NULL;
			break;
	}
	if ( empty( $photoimage ) or ( ! $photoimage ) ) return false; 			// No image

	$ps_x = $photosize[0];
	$ps_y = $photosize[1];
	$ws_x = $watersize[0];
	$ws_y = $watersize[1];
	$src_x = 0;
	$src_y = 0;
	if ( $ws_x > $ps_x ) {
		$src_x = ( $ws_x - $ps_x ) / 2;
		$ws_x = $ps_x;
	}
	if ( $ws_y > $ps_y ) {
		$src_y = ( $ws_y - $ps_y ) / 2;
		$ws_y = $ps_y;
	}

	// Margin
	$marg = wppa_opt( 'watermark_margin' );
	if ( $marg >= 1.0 ) {	 // pixels
		$marg_x = $marg;
		$marg_y = $marg;
	}
	else { 					// Fraction
		$marg_x = round( $marg * $ps_x );
		$marg_y = round( $marg * $ps_y );
	}

	$loy = substr( $waterpos, 0, 3 );
	switch( $loy ) {
		case 'top': $dest_y = $marg_y;
			break;
		case 'cen': $dest_y = ( $ps_y - $ws_y ) / 2;
			break;
		case 'bot': $dest_y = $ps_y - $ws_y - $marg_y;
			break;
		default: $dest_y = 0; 	// should never get here
	}
	$lox = substr( $waterpos, 3 );
	switch( $lox ) {
		case 'lft': $dest_x = $marg_x;
			break;
		case 'cen': $dest_x = ( $ps_x - $ws_x ) / 2;
			break;
		case 'rht': $dest_x = $ps_x - $ws_x - $marg_x;
			break;
		default: $dest_x = 0; 	// should never get here
	}

	$opacity = strpos( $waterfile, '/temp/' ) === false ? intval( wppa_opt( 'watermark_opacity' ) ) : intval( wppa_opt( 'watermark_opacity_text' ) );
	wppa_imagecopymerge_alpha( $photoimage , $waterimage , $dest_x, $dest_y, $src_x, $src_y, $ws_x, $ws_y, $opacity );

	// Save the result
	switch ( $photosize[2] ) {
		case 1: wppa_imagegif( $photoimage, $file );
			break;
		case 2: wppa_imagejpeg( $photoimage, $file, wppa_opt( 'jpeg_quality' ) );
			break;
		case 3: wppa_imagepng( $photoimage, $file );
			break;
		case 18: wppa_imagewebp( $photoimage, $file );
			break;
		default:
			break;
	}

	// Cleanup
	imagedestroy( $photoimage );
	imagedestroy( $waterimage );

	return true;
}


/**
 * PNG ALPHA CHANNEL SUPPORT for imagecopymerge();
 * This is a function like imagecopymerge but it handle alpha channel well!!!
 **/

// A fix to get a function like imagecopymerge WITH ALPHA SUPPORT
// Main script by aiden dot mail at freemail dot hu
// Transformed to imagecopymerge_alpha() by rodrigo dot polo at gmail dot com
function wppa_imagecopymerge_alpha( $dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct ){
    if( !isset( $pct ) ){
        return false;
    }
    $pct /= 100;
    // Get image width and height
    $w = imagesx( $src_im );
    $h = imagesy( $src_im );
    // Turn alpha blending off
    imagealphablending( $src_im, false );
    // Find the most opaque pixel in the image ( the one with the smallest alpha value )
    $minalpha = 127;
    for( $x = 0; $x < $w; $x++ )
    for( $y = 0; $y < $h; $y++ ){
        $alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF;
        if( $alpha < $minalpha ){
            $minalpha = $alpha;
        }
    }
    //loop through image pixels and modify alpha for each
    for( $x = 0; $x < $w; $x++ ){
        for( $y = 0; $y < $h; $y++ ){
            //get current alpha value ( represents the TANSPARENCY! )
            $colorxy = imagecolorat( $src_im, $x, $y );
            $alpha = ( $colorxy >> 24 ) & 0xFF;
            //calculate new alpha
            if( $minalpha !== 127 ){
                $alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha );
            } else {
                $alpha += 127 * $pct;
            }
            //get the color index with new alpha
            $alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha );
            //set pixel with the new color + opacity
            if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){
                return false;
            }
        }
    }
    // The image copy
    imagecopy( $dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h );
}

function wppa_watermark_file_select( $key, $album = '0' ) {

	// Init
	$result = '';
	$user = wppa_get_user();

	// See what's in there
	$paths = WPPA_UPLOAD_PATH . '/watermarks/*.png';
	$files = wppa_glob( $paths );

	/* Find current selection */
	// System default
	$select = wppa_opt( 'watermark_file' );
	$default = $select;

	// Selection for album default requested?
	if ( $album ) {
		$temp = wppa_get_album_item( $album, 'wmfile' );
		if ( $temp || $key == 'album' ) {
			$select = $temp;
		}
	}

	// User specific overruleable?
	elseif ( $key == 'user' ) {
		$default = $select;
		if ( wppa_switch( 'watermark_user' ) ) {
			$temp = wppa_get_option( 'wppa_watermark_file_' . $user );
			if ( $temp ) {
				$select = $temp;
			}
		}
	}
	/* $select now contains appropriate current selection */

	/* Produce the html */
	// If not system, allow blank entry for system/album default
	if ( $key != 'system' ) {
		$sel = $select == '--- dafault ---' ? 'selected' : '';
		$result .= '<option value="" '.$sel.' >' . __( '--- default ---', 'wp-photo-album-plus' ) . '</option>';
	}
	// None
	$sel = $select == '--- none ---' ? 'selected' : '';
	$result .= '<option value="--- none ---" '.$sel.' >'.__( '--- none ---' , 'wp-photo-album-plus' ).'</option>';

	// Picture based watermarks
	if ( $files ) foreach ( $files as $file ) {
		$sel = $select == basename( $file ) ? 'selected' : '';
		$result .= '<option value="'.basename( $file ).'" '.$sel.'>'.basename( $file ).'</option>';
	}

	// Text based watermarks
	if ( function_exists( 'imagettfbbox' ) ) {
		$sel = $select == '---name---' ? 'selected' : '';
		$result .= '<option value="---name---" '.$sel.' >'.__( '--- text: name ---' , 'wp-photo-album-plus' ).'</option>';
		$sel = $select == '---filename---' ? 'selected' : '';
		$result .= '<option value="---filename---" '.$sel.' >'.__( '--- text: filename ---' , 'wp-photo-album-plus' ).'</option>';
		$sel = $select == '---description---' ? 'selected' : '';
		$result .= '<option value="---description---" '.$sel.' >'.__( '--- text: description ---' , 'wp-photo-album-plus' ).'</option>';
		$sel = $select == '---predef---' ? 'selected' : '';
		$result .= '<option value="---predef---" '.$sel.' >'.__( '--- text: pre-defined ---' , 'wp-photo-album-plus' ).'</option>';
	}

	return $result;
}

function wppa_watermark_pos_select( $key, $album = '0' ) {

	// Init
	$user = wppa_get_user();
	$result = '';
	$opt = array( 	__( 'top - left' , 'wp-photo-album-plus' ), __( 'top - center' , 'wp-photo-album-plus' ), __( 'top - right' , 'wp-photo-album-plus' ),
					__( 'center - left' , 'wp-photo-album-plus' ), __( 'center - center' , 'wp-photo-album-plus' ), __( 'center - right' , 'wp-photo-album-plus' ),
					__( 'bottom - left' , 'wp-photo-album-plus' ), __( 'bottom - center' , 'wp-photo-album-plus' ), __( 'bottom - right' , 'wp-photo-album-plus' ), );
	$val = array( 	'toplft', 'topcen', 'toprht',
					'cenlft', 'cencen', 'cenrht',
					'botlft', 'botcen', 'botrht', );
	$idx = 0;

	/* Find current selection */
	// System default
	$select = wppa_opt( 'watermark_pos' );
	$default = $select;

	// Selection for album default requested?
	if ( $album ) {
		$temp = wppa_get_album_item( $album, 'wmpos' );
		if ( $temp || $key == 'album' ) {
			$select = $temp;
		}
	}

	// User specific overruleable?
	elseif ( $key == 'user'  ) {
		$default = $select;
		if ( wppa_switch( 'watermark_user' ) ) {
			$temp = wppa_get_option( 'wppa_watermark_pos_' . $user );
			if ( $temp ) {
				$select = $temp;
			}
		}
	}
	/* $select now contains appropriate current selection */

	/* Produce the html */
	// If not system, allow blank entry for system/album default
	if ( $key != 'system' ) {
		$result .= '<option value="" >' . __( '--- default ---', 'wp-photo-album-plus' ) . '</option>';
	}

	while ( $idx < 9 ) {
		$sel = $select == $val[$idx] ? 'selected' : '';
		$result .= '<option value="'.$val[$idx].'" '.$sel.'>'.$opt[$idx].'</option>';
		$idx++;
	}

	return $result;
}
