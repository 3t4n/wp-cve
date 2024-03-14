<?php
/**
* Font Library class to store and display Font Families
*/
class FontSq_TinyMce {
	public function print_stylesheets( $mce_css ){
		$fonts = FontSq_Font::get_installed_fonts();
		foreach( $fonts as $font ) {
			if ( ! empty( $mce_css ) ) $mce_css .= ',';
			$mce_css .= $font->stylesheet;
		}
		return $mce_css;
	}

	public function font_buttons($buttons) {
		$fonts = FontSq_Font::get_installed_fonts();
		if( sizeof($fonts) ){
			/**
			 * Add in a core button that's disabled by default
			 */
			$buttons[] = 'fontsizeselect';
			$buttons[] = 'fontselect';
		}
		return $buttons;
	}

	public function font_list( $mceInit ){
		$fonts = FontSq_Font::get_installed_fonts();
		foreach( $fonts as $font ) {
			if( !isset($mceInit['font_formats']) ) $mceInit['font_formats'] = '';
			$mceInit['font_formats'] .= $font->title.'='.$font->name.';';
		}
		return $mceInit;
	}
}
