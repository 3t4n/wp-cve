<?php
/**
 * This file contains generate captcha code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
global $wpdb, $captcha_array, $meta_data_array;

// defining the image type to be shown in browser window.
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
header( 'Content-Type: image/png' );

// Settings: You can customize the captcha here.
if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php' ) ) {
	include_once CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php';
}

switch ( $captcha_type ) {
	case 'only_digits':
		$charset = '0123456789';
		break;

	case 'only_alphabets':
		if ( 'random' === $text_case ) {
			$charset = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz';
		} elseif ( 'upper_case' === $text_case ) {
			$charset = 'ABCDEFGHKLMNPRSTUVWYZ';
		} else {
			$charset = 'abcdefghklmnprstuvwyz';
		}
		break;

	case 'alphabets_and_digits':
		if ( 'random' === $text_case ) {
			$charset = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz0123456789';
		} elseif ( 'upper_case' === $text_case ) {
			$charset = 'ABCDEFGHKLMNPRSTUVWYZ0123456789';
		} else {
			$charset = 'abcdefghklmnprstuvwyz0123456789';
		}
		break;
}

$captcha_enable_space = 5;
$code                 = '';
$code_string          = '';
$space                = '';
$captcha_fonts        = CAPTCHA_BANK_DIR_PATH . 'fonts/font.ttf';
$signature_fonts      = CAPTCHA_BANK_DIR_PATH . 'fonts/font-signature.ttf';
if ( 1 === $captcha_enable_space ) {
	$space = ' ';
}
$count = 0;
while ( $count < $captcha_character ) {
	$str          = substr( $charset, mt_rand( 0, strlen( $charset ) - 1 ), 1 );
	$code_string .= $str . $space;
	$code        .= $str;
	$count++;
}

/* create image */
$size_font         = $captcha_height * ( $captcha_font_style[0] / 50 );
$image             = imagecreatetruecolor( $captcha_width, $captcha_height );
$tmpimg_distortion = imagecreate( $captcha_width * 5, $captcha_height * 5 );

/* setting the captcha text transparency */
if ( $text_transparency > 0 ) {
	$alpha            = intval( $text_transparency / 100 * 127 );
	$arr_text_color   = hexrgb( $captcha_font_style[1] );
	$image_text_color = imagecolorallocatealpha( $image, $arr_text_color['red'], $arr_text_color['green'], $arr_text_color['blue'], $alpha );
} else {
	$arr_text_color   = hexrgb( $captcha_font_style[1] );
	$image_text_color = imagecolorallocate( $image, $arr_text_color['red'], $arr_text_color['green'], $arr_text_color['blue'] );
}
/* Displaying background image for Captcha */
set_background( $image, $captcha_width, $captcha_height, $captcha_background );

$textbox      = imagettfbbox( $size_font, 0, $captcha_fonts, $code_string );
$width        = ( $captcha_width - $textbox[4] ) / 2;
$height       = ( $captcha_height - $textbox[5] ) / 2;
$width_shadow = ( $captcha_width - $textbox[4] ) / 2.5;

/* create shadow for captcha text */
$arr_shadow_color   = hexrgb( $text_shadow_color );
$image_shadow_color = imagecolorallocate( $image, $arr_shadow_color['red'], $arr_shadow_color['green'], $arr_shadow_color['blue'] );
imagettftext( $image, $size_font, 2, $width_shadow, $height, $image_shadow_color, $captcha_fonts, $code_string );
imagettftext( $image, $size_font, 2, $width, $height, $image_text_color, $captcha_fonts, $code_string );

/* generating lines randomly in background of image */
$arr_line_color   = hexrgb( $lines_color );
$image_line_color = imagecolorallocate( $image, $arr_line_color['red'], $arr_line_color['green'], $arr_line_color['blue'] );
for ( $count = 0; $count < $lines; $count++ ) {
	imagesetthickness( $image, rand( 1, 3 ) );
	imageline( $image, mt_rand( 0, $captcha_width ), mt_rand( 0, $captcha_height ), mt_rand( 0, $captcha_width ), mt_rand( 0, $captcha_height ), $image_line_color );
}

/* generating the dots randomly in background */

$arr_dots_color   = hexrgb( $noise_color );
$image_dots_color = imagecolorallocate( $image, $arr_dots_color['red'], $arr_dots_color['green'], $arr_dots_color['blue'] );
for ( $count = 0; $count < $noise_level; $count++ ) {
	imagefilledellipse( $image, mt_rand( 0, $captcha_width ), mt_rand( 0, $captcha_height ), 2, 3, $image_dots_color );
}

/* create signature on captcha */
$arr_signature_color   = hexrgb( $signature_style[1] );
$image_signature_color = imagecolorallocate( $image, $arr_signature_color['red'], $arr_signature_color['green'], $arr_signature_color['blue'] );
if ( trim( $signature_text ) !== '' ) {
	$bbox    = imagettfbbox( $signature_style[0], 0, $signature_fonts, $signature_text );
	$textlen = $bbox[2] - $bbox[0];
	$width   = $captcha_width - $textlen - 5;
	$height  = $captcha_height - 3;

	imagettftext( $image, $signature_style[0], 0, $width, $height, $image_signature_color, $signature_fonts, $signature_text );
}
/**
 * Generating background image for Captcha.
 *
 * @param string $image .
 * @param string $captcha_width .
 * @param string $captcha_height .
 * @param string $captcha_background .
 */
function set_background( $image, $captcha_width, $captcha_height, $captcha_background ) {
	$bg_img = CAPTCHA_BANK_DIR_PATH . '/backgrounds/' . $captcha_background;
	$dat    = @getimagesize( $bg_img );// @codingStandardsIgnoreLine.
	if ( false === $dat ) {
		return;
	}
	switch ( $dat[2] ) {
		case 1:
			$newim = @imagecreatefromgif( $bg_img );// @codingStandardsIgnoreLine.
			break;
		case 2:
			$newim = @imagecreatefromjpeg( $bg_img );// @codingStandardsIgnoreLine.
			break;
		case 3:
			$newim = @imagecreatefrompng( $bg_img );// @codingStandardsIgnoreLine.
			break;
		default:
			return;
	}
	if ( ! $newim ) {
		return;
	}
	imagecopyresampled( $image, $newim, 0, 0, 0, 0, $captcha_width, $captcha_height, imagesx( $newim ), imagesy( $newim ) );
}
/* Show captcha image in the page html page */
imagepng( $image );
imagedestroy( $image );
$_SESSION['captcha_code'][] = $code;// @codingStandardsIgnoreLine.

/**
 * Change hexa values into colors.
 *
 * @param string $hexstr .
 */
function hexrgb( $hexstr ) {
	$int = hexdec( $hexstr );
	return array(
		'red'   => 0xFF & ( $int >> 0x10 ),
		'green' => 0xFF & ( $int >> 0x8 ),
		'blue'  => 0xFF & $int,
	);
}
