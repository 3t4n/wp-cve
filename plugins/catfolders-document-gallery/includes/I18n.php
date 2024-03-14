<?php

namespace CatFolder_Document_Gallery;

defined( 'ABSPATH' ) || exit;
/**
 * I18n Logic
 */
class I18n {

	public static function loadPluginTextdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		unload_textdomain( 'catfolders-document-gallery' );
		load_textdomain( 'catfolders-document-gallery', CATF_DG_DIR . '/languages/catfolders-document-gallery-' . $locale . '.mo' );
		load_plugin_textdomain( 'catfolders-document-gallery', false, CATF_DG_DIR . '/languages/' );
	}
}
