<?php

if ( ! class_exists( 'WFFN_NextMove_Compatibility' ) ) {
	class WFFN_NextMove_Compatibility {
		public function __construct() {
			add_action( 'wp', array( $this, 'maybe_unhook' ), 2 );
		}

		public function is_enable() {

			return class_exists( 'xlwcty' );
		}

		public function maybe_unhook() {

			if ( ! $this->is_enable() ) {
				return;
			}
			if ( ! WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return;
			}

			WFFN_Common::remove_actions( 'wp', 'XLWCTY_Data', 'load_order_wp' );

		}
	}

	if ( wffn_is_wc_active() ) {
		WFFN_Plugin_Compatibilities::register( new WFFN_NextMove_Compatibility(), 'xlwcty' );
	}
}

