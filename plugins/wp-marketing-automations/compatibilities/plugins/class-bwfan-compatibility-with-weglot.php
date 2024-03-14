<?php

/**
 * Weglot Translate – Translate your WordPress website and go multilingual
 * https://weglot.com/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Weglot' ) ) {
	class BWFAN_Compatibility_With_Weglot {

		public function __construct() {

			if ( false === strpos( $_SERVER['REQUEST_URI'], BWFAN_API_NAMESPACE . '/automation/' ) ) {
				return;
			}
			/** Disable weglot translation on automation page */
			add_filter( 'weglot_active_translation_before_process', '__return_false' );
		}
	}

	if ( bwfan_is_weglot_active() ) {
		new BWFAN_Compatibility_With_Weglot();
	}
}
