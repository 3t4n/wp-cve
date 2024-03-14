<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Theme_Vitrine {

	private static $ins = null;

	public function __construct() {
		add_action( 'wp_head', array( $this, 'remove_spinner' ) );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Hide loading spinner on Thank you component admin page using JS
	 */
	public function remove_spinner() {

		if ( ! defined( 'EPICOMEDIA_THEME_SLUG' ) || EPICOMEDIA_THEME_SLUG !== 'vitrine' ) {
			return;
		}

		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			if( jQuery("#preloader").length > 0 ) {
				jQuery("body").removeClass("fade");				
                jQuery("#preloader").addClass("hide-preloader");
                jQuery("#preloader.firstload").hide();
			}
		});
		</script>';
	}
}

XLWCTY_Theme_Vitrine::get_instance();