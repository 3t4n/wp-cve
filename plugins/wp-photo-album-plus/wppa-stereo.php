<?php
/* wppa-stereo.php
* Package: wp-photo-album-plus
*
* Contains all the stereo stuff
* Version 8.1.08.003
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

add_action( 'init', 'wppa_init_stereo' );
function wppa_init_stereo() {
global $wppa_supported_stereo_types;
global $wppa_supported_stereo_glasses;
global $wppa_supported_stereo_type_names;
global $wppa_supported_stereo_glass_names;

	$wppa_supported_stereo_types 		= array(
													'color',
													'halfcolor',
													'gray',
													'true',
													'optimized',
													'_flat',
												);
	$wppa_supported_stereo_glasses 		= array( 	'redcyan',
													'greenmagenta', // Green - Magenta
												);
	$wppa_supported_stereo_type_names 	= array(
													__( 'Color', 'wp-photo-album-plus' ),
													__( 'Half color', 'wp-photo-album-plus' ),
													__( 'Gray', 'wp-photo-album-plus' ),
													__( 'True anaglyph', 'wp-photo-album-plus' ),
													__( 'Optimized', 'wp-photo-album-plus' ),
													__( 'Flat', 'wp-photo-album-plus' ),
												);
	$wppa_supported_stereo_glass_names 	= array( 	__( 'Red - Cyan', 'wp-photo-album-plus' ),
													__( 'Green - Magenta', 'wp-photo-album-plus' ),
												);
}

function wppa_create_stereo_images( $id ) {
global $wppa_supported_stereo_types;
global $wppa_supported_stereo_glasses;

	// Feature enabled?
	if ( ! wppa_switch( 'enable_stereo' ) ) return;

	// Is it a stereo photo?
	if ( ! wppa_is_stereo( $id ) ) {

		// Maybe no longer, delete any anaglyphs
		if ( wppa_delete_stereo_images( $id ) ) {

			// Is no longer stereo, create new thumbnail
			wppa_create_thumbnail( $id );
		}
		return;
	}

	// Now make the anaglyphs
	foreach( $wppa_supported_stereo_types as $type ) {
		foreach( $wppa_supported_stereo_glasses as $glas ) {
			wppa_create_stereo_image( $id, $type, $glas );
		}
	}
}

function wppa_create_stereo_image( $id, $type, $glass ) {
static $f299;
static $f587;
static $f114;

	// Init
	if ( $glass == 'rc' ) $glass = 'redcyan';
	if ( ! is_array( $f299 ) ) {
		$i = 0;
		while ( $i < 256 ) {
			$f299[$i] = floor( 0.299 * $i + 0.5 );
			$f587[$i] = floor( 0.587 * $i + 0.5 );
			$f114[$i] = floor( 0.114 * $i + 0.5 );
			$i++;
		}
	}

	// Feature enabled?
	if ( ! wppa_switch( 'enable_stereo' ) ) return;

	// Init.
	$is_stereo 	= wppa_is_stereo( $id );
	if ( ! $is_stereo ) return;
	$stereodir 	= WPPA_UPLOAD_PATH . '/stereo';
	if ( ! wppa_is_dir( $stereodir ) ) {
		wppa_mkdir( $stereodir );
	}
	$fromfile 	= wppa_get_photo_path( $id );
	$tofile 	= wppa_get_stereo_path( $id, $type, $glass );
	$sizes 		= getimagesize( $fromfile );
	$width 		= $sizes['0'] / 2;
	$height 	= $sizes['1'];

	$fromimage 	= wppa_imagecreatefromjpeg( $fromfile );
	if ( ! $fromimage ) return false;

	$toimage 	= imagecreatetruecolor( $width, $height );
	if ( $is_stereo == 1 ) {
		$offset1 	= 0;
		$offset2 	= $width;
	}
	else {
		$offset1 	= $width;
		$offset2 	= 0;
	}

	// Do the dirty work
	switch( $type ) {
		case 'color':
			for ( $y=0; $y < $height; $y++ ) {
				for ( $x=0; $x < $width; $x++ ) {
					$rgb1 = imagecolorat($fromimage, $x + $offset1, $y);
					$r1 = ($rgb1 >> 16) & 0xFF;
					$g1 = ($rgb1 >> 8) & 0xFF;
					$b1 = $rgb1 & 0xFF;
					$rgb2 = imagecolorat($fromimage, $x + $offset2, $y);
					$r2 = ($rgb2 >> 16) & 0xFF;
					$g2 = ($rgb2 >> 8) & 0xFF;
					$b2 = $rgb2 & 0xFF;

					// Red - Cyan glass
					if ( $glass == 'redcyan' ) {
						$newpix = ($r2 << 16) | ($g1 << 8) | $b1;
					}

					// Green - magenta glass
					else {
						$newpix = ($r1 << 16) | ($g2 << 8) | $b1;
					}

					imagesetpixel($toimage, $x, $y, $newpix);
				}
			}
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

		case 'gray':
			for ( $y=0; $y < $height; $y++ ) {
				for ( $x=0; $x < $width; $x++ ) {
					$rgb1 = imagecolorat($fromimage, $x + $offset1, $y);
					$r1 = ($rgb1 >> 16) & 0xFF;
					$g1 = ($rgb1 >> 8) & 0xFF;
					$b1 = $rgb1 & 0xFF;
					$rgb2 = imagecolorat($fromimage, $x + $offset2, $y);
					$r2 = ($rgb2 >> 16) & 0xFF;
					$g2 = ($rgb2 >> 8) & 0xFF;
					$b2 = $rgb2 & 0xFF;

					// Red - Cyan glass
					if ( $glass == 'redcyan' ) {
						$r = $f299[$r2] + $f587[$g2] + $f114[$b2];
						$g = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$b = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					// Green - magenta glass
					else {
						$r = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$g = $f299[$r2] + $f587[$g2] + $f114[$b2];
						$b = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					imagesetpixel($toimage, $x, $y, $newpix);
				}
			}
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

		case 'true':
			for ( $y=0; $y < $height; $y++ ) {
				for ( $x=0; $x < $width; $x++ ) {
					$rgb1 = imagecolorat($fromimage, $x + $offset1, $y);
					$r1 = ($rgb1 >> 16) & 0xFF;
					$g1 = ($rgb1 >> 8) & 0xFF;
					$b1 = $rgb1 & 0xFF;
					$rgb2 = imagecolorat($fromimage, $x + $offset2, $y);
					$r2 = ($rgb2 >> 16) & 0xFF;
					$g2 = ($rgb2 >> 8) & 0xFF;
					$b2 = $rgb2 & 0xFF;

					// Red - Cyan glass
					if ( $glass == 'redcyan' ) {
						$r = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$g = 0;
						$b = $f299[$r2] + $f587[$g2] + $f114[$b2];
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					// Green - magenta glass
					else {
						$r = $f299[$r2] + $f587[$g2] + $f114[$b2];
						$g = $f299[$r1] + $f587[$g1] + $f114[$b1];
						$b = 0;
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					imagesetpixel($toimage, $x, $y, $newpix);
				}
			}
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

		case 'halfcolor':
			for ( $y=0; $y < $height; $y++ ) {
				for ( $x=0; $x < $width; $x++ ) {
					$rgb1 = imagecolorat($fromimage, $x + $offset1, $y);
					$r1 = ($rgb1 >> 16) & 0xFF;
					$g1 = ($rgb1 >> 8) & 0xFF;
					$b1 = $rgb1 & 0xFF;
					$rgb2 = imagecolorat($fromimage, $x + $offset2, $y);
					$r2 = ($rgb2 >> 16) & 0xFF;
					$g2 = ($rgb2 >> 8) & 0xFF;
					$b2 = $rgb2 & 0xFF;

					// Red - Cyan glass
					if ( $glass == 'redcyan' ) {
						$r = $f299[$r1] + $f587[$g1] + $f114[$b1];
					//	$g = $g2;
					//	$b = $b2;
						$newpix = ($r << 16) | ($g2 << 8) | $b2;
					}

					// Green - magenta glass
					else {
						$r = $f299[$r2] + $f587[$g2] + $f114[$b2];
//						$g = $g1;
//						$b = $b2;
						$r = ceil( min( $r, 255 ) );
						$newpix = ($r << 16) | ($g1 << 8) | $b2;
					}

					imagesetpixel($toimage, $x, $y, $newpix);
				}
			}
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

		case 'optimized':
			for ( $y=0; $y < $height; $y++ ) {
				for ( $x=0; $x < $width; $x++ ) {
					$rgb1 = imagecolorat($fromimage, $x + $offset1, $y);
					$r1 = ($rgb1 >> 16) & 0xFF;
					$g1 = ($rgb1 >> 8) & 0xFF;
					$b1 = $rgb1 & 0xFF;
					$rgb2 = imagecolorat($fromimage, $x + $offset2, $y);
					$r2 = ($rgb2 >> 16) & 0xFF;
					$g2 = ($rgb2 >> 8) & 0xFF;
					$b2 = $rgb2 & 0xFF;

					// Red - Cyan glass
					if ( $glass == 'redcyan' ) {
						$r = 0.7 * $g1 + 0.3 * $b1;
						$g = $g2;
						$b = $b2;
						$r = ceil( min( $r, 255 ) );
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					// Green - magenta glass
					else {
						$r = 0.7 * $g2 + 0.3 * $b2;
						$g = $g1;
						$b = $b2;
						$r = ceil( min( $r, 255 ) );
						$newpix = ($r << 16) | ($g << 8) | $b;
					}

					imagesetpixel($toimage, $x, $y, $newpix);
				}
			}
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

		case '_flat':
			imagecopy( $toimage, $fromimage, 0, 0, 0, 0, $width, $height );
			wppa_imagejpeg( $toimage, $tofile, wppa_opt( 'jpeg_quality' ) );
			break;

			default:
			break;
	}

	// Bump version
	wppa_bump_photo_rev();
}

function wppa_get_stereo_path( $id, $type, $glass ) {

	if ( $glass == 'rc' || $glass == 'redcyan' ) {
		$gl = 'rc';
	}
	else {
		$gl = 'gm';
	}

	if ( $type == '_flat' ) {
		$result = WPPA_UPLOAD_PATH . '/stereo/' . $id . '-' . $type . '.jpg';
	}
	else {
		$result = WPPA_UPLOAD_PATH . '/stereo/' . $id . '-' . $type . '-' . $gl . '.jpg';
	}
	return $result;
}

function wppa_delete_stereo_images( $id ) {

	$files = wppa_glob( WPPA_UPLOAD_PATH . '/stereo/' . $id . '-*.*' );
	if ( $files ) foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			unlink( $file );
		}
	}

	return ( count( $files ) > 0 );
}

function wppa_is_stereo( $id ) {

	// Feature enabled?
	if ( ! wppa_switch( 'enable_stereo' ) ) return false;

	return wppa_get_photo_item( $id, 'stereo' );
}
