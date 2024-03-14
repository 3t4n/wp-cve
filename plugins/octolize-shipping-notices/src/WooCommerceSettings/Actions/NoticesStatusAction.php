<?php
/**
 * Class ChangeStatusAndOrderAction
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Actions;

use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\Helpers\WooCommerceSettingsPageChecker;
use Octolize\Shipping\Notices\ShippingNotice\SingleNoticeOption;
use Octolize\Shipping\Notices\WooCommerceSettings\SingleSectionSettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class NoticesStatusAction implements Hookable {
	public const STATUSES_NAME = 'shipping_notices_statuses';

	/**
	 * @var WooCommerceSettingsPageChecker
	 */
	private $settings_page_checker;

	/**
	 * @var SingleNoticeOption
	 */
	private $single_notice_option;

	/**
	 * @param WooCommerceSettingsPageChecker $settings_page_checker .
	 * @param SingleNoticeOption             $single_notice_option  .
	 */
	public function __construct( WooCommerceSettingsPageChecker $settings_page_checker, SingleNoticeOption $single_notice_option ) {
		$this->settings_page_checker = $settings_page_checker;
		$this->single_notice_option  = $single_notice_option;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_settings_save_shipping', [ $this, 'update_notices_status' ] );
	}

	/**
	 * @return void
	 */
	public function update_notices_status(): void {
		check_admin_referer( 'woocommerce-settings' );

		if ( ! $this->settings_page_checker->is_settings_page_section( WooCommerceSettingsPage::SECTION_ID ) ) {
			return;
		}

		if ( ! isset( $_POST[ self::STATUSES_NAME ] ) || ! is_array( $_POST[ self::STATUSES_NAME ] ) ) {
			return;
		}

		$statuses = array_map( 'sanitize_text_field', wp_unslash( $_POST[ self::STATUSES_NAME ] ) );

		foreach ( $statuses as $notice_id => $status ) {
			if ( get_post_type( $notice_id ) !== CustomPostType::POST_TYPE ) {
				continue;
			}

			$this->update_status( $notice_id, $status );
		}
	}

	/**
	 * @param int    $notice_id .
	 * @param string $status    .
	 *
	 * @return void
	 */
	private function update_status( int $notice_id, string $status ): void {
		$this->single_notice_option->update_option( $notice_id, SingleSectionSettingsFields::ENABLED_FIELD, $status );
	}
}
