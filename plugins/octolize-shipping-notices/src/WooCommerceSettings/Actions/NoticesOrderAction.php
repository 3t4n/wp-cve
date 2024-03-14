<?php
/**
 * Class ChangeOrderAction
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Actions;

use Octolize\Shipping\Notices\Helpers\WooCommerceSettingsPageChecker;
use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class NoticesOrderAction implements Hookable {
	public const ORDERS_NAME = 'shipping_notices_order';

	/**
	 * @var WooCommerceSettingsPageChecker
	 */
	private $settings_page_checker;

	/**
	 * @param WooCommerceSettingsPageChecker $settings_page_checker .
	 */
	public function __construct( WooCommerceSettingsPageChecker $settings_page_checker ) {
		$this->settings_page_checker = $settings_page_checker;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_settings_save_shipping', [ $this, 'update_notices_order' ] );
	}

	/**
	 * @return void
	 */
	public function update_notices_order(): void {
		check_admin_referer( 'woocommerce-settings' );

		if ( ! $this->settings_page_checker->is_settings_page_section( WooCommerceSettingsPage::SECTION_ID ) ) {
			return;
		}

		if ( ! isset( $_POST[ self::ORDERS_NAME ] ) ) {
			return;
		}

		$orders = array_flip( wp_parse_id_list( wp_unslash( $_POST[ self::ORDERS_NAME ] ?? [] ) ) );

		foreach ( $orders as $notice_id => $order ) {
			if ( get_post_type( $notice_id ) !== CustomPostType::POST_TYPE ) {
				continue;
			}

			wp_update_post(
				[
					'ID'         => $notice_id,
					'menu_order' => ++$order,
				]
			);
		}
	}
}
