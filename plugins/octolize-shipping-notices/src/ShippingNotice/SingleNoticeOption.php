<?php
/**
 * Class SingleNoticeOption
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;

/**
 * Manage notice settings.
 */
class SingleNoticeOption {
	/**
	 * @param int $notice_id
	 *
	 * @return string[]
	 */
	public function get_single_notice_options( int $notice_id ): array {
		// @phpstan-ignore-next-line
		return (array) get_option( $this->get_single_package_option_name( $notice_id ), [] );
	}

	/**
	 * @param int    $notice_id .
	 * @param string $key       .
	 * @param mixed  $value     .
	 *
	 * @return bool
	 */
	public function update_option( int $notice_id, string $key, $value ): bool {
		$options         = $this->get_single_notice_options( $notice_id );
		$options[ $key ] = $value;

		return update_option( $this->get_single_package_option_name( $notice_id ), $options );
	}

	/**
	 * @param int $notice_id
	 *
	 * @return string
	 */
	private function get_single_package_option_name( int $notice_id ): string {
		return WooCommerceSettingsPage::OPTION_NAME . '_' . $notice_id;
	}
}
