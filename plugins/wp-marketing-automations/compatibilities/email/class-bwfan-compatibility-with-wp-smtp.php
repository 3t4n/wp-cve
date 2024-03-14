<?php
/**
 * WP Mail SMTP
 * https://wordpress.org/plugins/wp-mail-smtp/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WP_SMTP' ) && defined( 'WPMS_PLUGIN_VER' ) ) {
	class BWFAN_Compatibility_With_WP_SMTP {

		/**
		 * checking for smart routing enabled
		 *
		 * @return bool
		 */
		public static function is_smart_routing_enabled() {
			if ( ! class_exists( 'WPMailSMTP\Options' ) ) {
				return false;
			}

			return (bool) WPMailSMTP\Options::init()->get( 'smart_routing', 'enabled' );
		}
	}
}
