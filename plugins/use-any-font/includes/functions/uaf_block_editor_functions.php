<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

// FOR GUTENBERG BLOCK EDITOR
// CODE BEING USING FROM BSF CUSTOM FONT PLUGIN.

function uaf_block_editor_custom_fonts(){
	$fontsData		= uaf_group_fontdata_by_fontname(uaf_get_uploaded_font_data());

	$uaf_upload_path	= uaf_path_details();
	$uaf_upload_dir 	= $uaf_upload_path['dir'];
	$uaf_upload_url 	= $uaf_upload_path['url'];
	if (is_ssl()){
		$uaf_upload_url 	= preg_replace('#^https?:#', 'https:', $uaf_upload_path['url']);
	}

	if (!empty($fontsData)):
		$uaf_fonts = array();
		foreach ($fontsData as $fontName=>$fontData):
			$font_faces = array();
			foreach ($fontData as $fontVariationKey => $fontVariationData){
				if (array_key_exists('font_weight',$fontVariationData)){
					$font_faces[] = array(
						'fontFamily'  => $fontName,
						'fontStretch' => '',
						'fontStyle'   => $fontVariationData['font_style'],
						'fontWeight'  => $fontVariationData['font_weight'],
						'src'         => esc_url($uaf_upload_url.$fontVariationData['font_path']).'.woff2'
					);
				} else {
					$font_faces[] = array(
						'fontFamily'  => $fontName,
						'fontStretch' => '',
						'fontStyle'   => 'normal',
						'fontWeight'  => '400',
						'src'         => esc_url($uaf_upload_url.$fontVariationData['font_path']).'.woff2'
					);
				}				
			}

			$uaf_fonts[] = array(
				'fontFamily' => $fontName,
				'slug'       => $fontName,
				'fontFace'   => $font_faces,
				'isUAF'      => true,
			);

		endforeach;
		uaf_update_theme_json_font($uaf_fonts);
	endif;	
}

function uaf_update_theme_json_font($uaf_fonts) {
	if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && wp_theme_has_theme_json() ) {
		// Get the current theme.json and fontFamilies defined (if any).
		$theme_json_raw      = json_decode( file_get_contents( get_stylesheet_directory() . '/theme.json' ), true );
		$theme_font_families = isset( $theme_json_raw['settings']['typography']['fontFamilies'] ) ? $theme_json_raw['settings']['typography']['fontFamilies'] : array();

		$theme_font_families = array_filter($theme_font_families, function($font) { // REMOVE UAF FONTS
		    return !isset($font['isUAF']);
		});
		$theme_font_families 			= array_values($theme_font_families);		
		$theme_font_families            = array_merge( $theme_font_families, $uaf_fonts);

		// Overwrite the previous fontFamilies with the new ones.
		$theme_json_raw['settings']['typography']['fontFamilies'] = $theme_font_families;

		// @codingStandardsIgnoreStart
		$theme_json        = wp_json_encode( $theme_json_raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		$theme_json_string = preg_replace( '~(?:^|\G)\h{4}~m', "\t", $theme_json );

		// Write the new theme.json to the theme folder.
		file_put_contents( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions
			get_stylesheet_directory() . '/theme.json',
			$theme_json_string
		);
		// @codingStandardsIgnoreEnd
	}
}