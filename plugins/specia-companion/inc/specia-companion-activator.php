<?php
/**
 * Fired during plugin activation
 *
 * @package    Specia Companion
 */

/**
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
class Specia_Companion_Activator {

	public static function activate() {

        $item_details_page = get_option('item_details_page'); 
		$theme = wp_get_theme(); // gets the current theme
		if(!$item_details_page){
			
			require SPECIA_COMPANION_PLUGIN_DIR . 'inc/specia/default-pages/upload-media.php';
			require SPECIA_COMPANION_PLUGIN_DIR . 'inc/specia/default-pages/home-page.php';
			require SPECIA_COMPANION_PLUGIN_DIR . 'inc/specia/default-pages/default-pages.php';
			require SPECIA_COMPANION_PLUGIN_DIR . 'inc/specia/default-widgets/default-widget.php';
			require SPECIA_COMPANION_PLUGIN_DIR . 'inc/specia/specia.php';
			
			update_option( 'item_details_page', 'Done' );
		}
	}

}