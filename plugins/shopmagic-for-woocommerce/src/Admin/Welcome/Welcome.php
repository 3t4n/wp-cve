<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Welcome;

final class Welcome {
	/**
	 * @var string
	 */
	private const _WELCOME_REDIRECT_SHOPMAGIC = '_welcome_redirect_shopmagic';

	/**
	 * Safe Welcome Page Redirect.
	 * Safe welcome page redirect which happens only
	 * once and if the site is not a network or MU.
	 *
	 * @since    1.0.0
	 */
	public function safe_welcome_redirect(): void {
		// Bail if no activation redirect transient is present. (if ! true).
		if ( ! get_transient( self::_WELCOME_REDIRECT_SHOPMAGIC ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( self::_WELCOME_REDIRECT_SHOPMAGIC );
		// Bail if activating from network or bulk sites.
		if ( is_network_admin() ) {
			return;
		}
		if ( isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirects to Welcome Page.
		wp_safe_redirect(
			add_query_arg(
				[
					'page' => 'shopmagic-admin/#/welcome',
				],
				admin_url( 'admin.php' )
			)
		);
	}

	/**
	 * Add the transient.
	 * Add the welcome page transient.
	 *
	 * @since 1.0.0
	 */
	public function welcome_activate(): void {
		set_transient( self::_WELCOME_REDIRECT_SHOPMAGIC, true, MINUTE_IN_SECONDS );
	}

	/**
	 * Delete the Transient on plugin deactivation.
	 * Delete the welcome page transient.
	 *
	 * @since   2.0.0
	 */
	public function welcome_deactivate(): void {
		delete_transient( self::_WELCOME_REDIRECT_SHOPMAGIC );
	}
}
