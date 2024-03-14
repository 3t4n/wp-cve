<?php
/**
 * Rate notice.
 *
 * @package WPDesk\FlexibleShippingUps
 */

namespace WPDesk\FlexibleShippingUps;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Displays rate notice.
 */
class RateNotice implements Hookable {

	const FIRST_NOTICE_MIN_ORDERS = 100;
	const CLOSE_TEMPORARY_NOTICE  = 'close-temporary-notice';

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'admin_notices', array( $this, 'maybe_show_notice' ) );
		add_action( 'wpdesk_notice_dismissed_notice', array( $this, 'maybe_reset_orders_counter' ), 10, 2 );
	}

	/**
	 * Maybe reset counter.
	 *
	 * @param string $notice_name .
	 * @param string $source .
	 */
	public function maybe_reset_orders_counter( $notice_name, $source = null ): void {
		if ( 'ups_rating' === $notice_name && ( empty( $source ) || self::CLOSE_TEMPORARY_NOTICE === $source ) ) {
			delete_option( OrderCounter::FS_UPS_COUNTER );
			delete_option( \UpsFreeVendor\WPDesk\Notice\PermanentDismissibleNotice::OPTION_NAME_PREFIX . $notice_name );
		}
	}

	/**
	 * Action links
	 *
	 * @return array
	 */
	protected function action_links() {
		$actions[] = sprintf(
			// Translators: link.
			__( '%1$sOk, you deserved it%2$s', 'flexible-shipping-ups' ),
			'<a class="fs-ups-deserved" target="_blank" href="' . esc_url( 'https://octol.io/fs-ups-rate' ) . '">',
			'</a>'
		);
		$actions[] = sprintf(
			// Translators: link.
			__( '%1$sNope, maybe later%2$s', 'flexible-shipping-ups' ),
			'<a class="fs-ups-close-temporary-notice notice-dismiss-link" data-source="' . self::CLOSE_TEMPORARY_NOTICE . '" href="#">',
			'</a>'
		);
		$actions[] = sprintf(
			// Translators: link.
			__( '%1$sI already did%2$s', 'flexible-shipping-ups' ),
			'<a class="fs-ups-already-did notice-dismiss-link" data-source="already-did" href="#">',
			'</a>'
		);
		return $actions;
	}

	/**
	 * Get notice content.
	 *
	 * @return string
	 */
	private function get_notice_content() {
		$content  = __( 'Awesome, you just crossed the 100 orders with UPS rates by Flexible Shipping UPS. Could you please do me a BIG favor and give it a 5-star rating on WordPress? ~ Peter', 'flexible-shipping-ups' );
		$content .= '<br/>';
		$content .= implode( ' | ', $this->action_links() );
		return $content;
	}

	/**
	 * Should display notice.
	 *
	 * @return bool
	 */
	private function should_display_notice() {
		$current_screen     = get_current_screen();
		$display_on_screens = [ 'shop_order', 'edit-shop_order', 'woocommerce_page_wc-settings' ];
		if ( ! empty( $current_screen ) && in_array( $current_screen->id, $display_on_screens, true ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Maybe show notice.
	 *
	 * @return void
	 */
	public function maybe_show_notice() {
		$order_counter = intval( get_option( OrderCounter::FS_UPS_COUNTER, '0' ) ); // @phpstan-ignore-line
		if ( self::FIRST_NOTICE_MIN_ORDERS <= $order_counter && $this->should_display_notice() ) {
			new \UpsFreeVendor\WPDesk\Notice\PermanentDismissibleNotice(
				$this->get_notice_content(),
				'ups_rating',
				\UpsFreeVendor\WPDesk\Notice\Notice::NOTICE_TYPE_INFO
			);
		}
	}


}
