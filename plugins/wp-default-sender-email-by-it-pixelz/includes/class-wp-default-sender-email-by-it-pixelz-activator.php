<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Default_Sender_Email_By_It_Pixelz
 * @subpackage Wp_Default_Sender_Email_By_It_Pixelz/includes
 * @author     Umar Draz <umar.draz001@gmail.com>
 */
class Wp_Default_Sender_Email_By_It_Pixelz_Activator {

	/**
	 * fire on activation
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
		add_option( 'wdsei_activation_redirect', true );

		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return;
		}

		$site_url        = get_site_url();
		$site_url_parsed = parse_url( $site_url );
		$sender_name     = get_option( 'blogname' );
		$sender_email    = 'support@' . $site_url_parsed['host'];

		$settings_defaults = [
			'sender_name' => $sender_name,
			'sender_mail' => $sender_email,
		];

		if ( get_option( WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY ) === false ) {
			$deprecated = null;
			$autoload   = 'no';
			add_option( WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY, $settings_defaults, $deprecated, $autoload );
			wp_cache_delete( 'alloptions', 'options' );
		}
	}
}
