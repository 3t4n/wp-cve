<?php


/**
 * メディアライブラリをリスト表示した時の追加列
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 画像エディタに追加したClassを加える
add_filter( 'wp_image_editors', function( $image_editors ) {

	if( ! is_array( $image_editors ) ) {
		return $image_editors;
	}

	// Is Enabled cwebp?
	$is_enabled_cwebp = stillbe_iqc_is_extended()
	                      && apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY );

	// Editors
	$new_editors = $image_editors;

	// Add the Edited Classes
	if( $is_enabled_cwebp ) {
		@ array_unshift( $new_editors, 'StillBE_WP_Image_Editor_Imagick', 'StillBE_WP_Image_Editor_GD' );
	} elseif( class_exists( 'StillBE_WP_Image_Editor_Imagick' )
	            && StillBE_WP_Image_Editor_Imagick::test()
	            && StillBE_WP_Image_Editor_Imagick::supports_mime_type( 'image/webp' ) ) {
		@ array_unshift( $new_editors, 'StillBE_WP_Image_Editor_Imagick', 'StillBE_WP_Image_Editor_GD' );
	} elseif( class_exists( 'StillBE_WP_Image_Editor_GD' )
	            && StillBE_WP_Image_Editor_GD::test()
	            && StillBE_WP_Image_Editor_GD::supports_mime_type( 'image/webp' ) ) {
		@ array_unshift( $new_editors, 'StillBE_WP_Image_Editor_GD', 'StillBE_WP_Image_Editor_Imagick' );
	} else {
		@ array_unshift( $new_editors, 'StillBE_WP_Image_Editor_Imagick', 'StillBE_WP_Image_Editor_GD' );
	}

	return count( $new_editors ) > count( $image_editors ) ? $new_editors : $image_editors;

} );





// END of the File



