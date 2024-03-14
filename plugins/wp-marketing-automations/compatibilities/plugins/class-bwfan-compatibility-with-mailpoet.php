<?php

/**
 * MailPoet – emails and newsletters in WordPress
 * https://wordpress.org/plugins/mailpoet/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_MailPoet' ) ) {
	class BWFAN_Compatibility_With_MailPoet {
		public function __construct() {
			if ( ! empty( WooFunnels_AS_DS::$unique ) || true === BWFAN_Common::$change_data_strore ) {
				BWFAN_Common::remove_actions( 'init', 'MailPoet\Config\Initializer', 'initialize' );
			}
		}
	}

	if ( class_exists( 'MailPoet\Config\Initializer' ) ) {
		new BWFAN_Compatibility_With_MailPoet();
	}
}
