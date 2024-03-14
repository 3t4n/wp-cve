<?php

namespace IC\Plugin\CartLinkWooCommerce\Notice;

class NoticeAction {
	public const DISMISS_NOTICE_ACTION = 'ic_dismiss_notice';
	public const NONCE_ACTION          = 'ic_dismiss_notice';
	public const DISMISS_ACTION        = 'dismiss_action';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'admin_post_' . self::DISMISS_NOTICE_ACTION, [ $this, 'dismiss_notice' ] );
	}

	/**
	 * @return void
	 */
	public function dismiss_notice() {
		check_admin_referer( self::NONCE_ACTION );

		do_action( 'ic_notice_dismiss/' . wp_unslash( $_GET[ self::DISMISS_ACTION ] ?? '' ) );

		wp_safe_redirect( wp_get_referer(), 301 );
		die();
	}
}
