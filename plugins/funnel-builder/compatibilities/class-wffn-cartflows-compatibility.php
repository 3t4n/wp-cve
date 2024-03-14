<?php

if ( ! class_exists( 'WFFN_CartFlows_Compatibility' ) ) {
	class WFFN_CartFlows_Compatibility {
		public function __construct() {
			add_action( 'template_redirect', array( $this, 'maybe_unhook' ), - 1 );
		}

		public function is_enable() {

			return class_exists( 'Cartflows_Checkout_Markup' );
		}

		public function maybe_unhook() {
			if ( ! WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return;
			}
			WFFN_Common::remove_actions( 'template_redirect', 'Cartflows_Checkout_Markup', 'global_checkout_template_redirect' );

		}
	}

	if ( wffn_is_wc_active() ) {
		WFFN_Plugin_Compatibilities::register( new WFFN_CartFlows_Compatibility(), 'cf' );
	}
}

