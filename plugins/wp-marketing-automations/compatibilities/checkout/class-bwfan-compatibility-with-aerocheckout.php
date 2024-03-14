<?php

/**
 * WooFunnels Checkout
 * https://wordpress.org/plugins/funnel-builder/
 * https://funnelkit.com/wordpress-funnel-builder/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Aero_Checkout' ) ) {
	class BWFAN_Compatibility_With_Aero_Checkout {

		public function __construct() {
			add_filter( 'bwfan_get_global_settings', [ $this, 'disable_abandonment' ], 99 );
		}

		/**
		 * Disable cart abandonment tracking on checkout admin builder pages
		 *
		 * @param $global_settings
		 *
		 * @return mixed
		 */
		public function disable_abandonment( $global_settings ) {
			if ( true === WFACP_Common::is_theme_builder() ) {
				$global_settings['bwfan_ab_enable'] = 0;
			}

			return $global_settings;
		}
	}

	if ( class_exists( 'WFACP_Common' ) && method_exists( 'WFACP_Common', 'is_theme_builder' ) ) {
		new BWFAN_Compatibility_With_Aero_Checkout();
	}
}
