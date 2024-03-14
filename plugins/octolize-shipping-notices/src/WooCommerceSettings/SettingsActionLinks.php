<?php
/**
 * Class ArchiveSettingsFields
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings;

use Octolize\Shipping\Notices\Model\ShippingNotice;
use Octolize\Shipping\Notices\SettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\ShippingNoticesField;

/**
 * .
 */
class SettingsActionLinks {
	public const NOTICE_DELETED = 'shipping-notices-deleted';
	public const NOTICE_ADD_NEW = 'shipping-notices-add';
	public const NOTICE_DELETE  = 'shipping-notices-delete';
	public const NONCE_ACTION   = 'shipping-notices';
	public const NOTICE_ID      = 'notice_id';

	/**
	 * @param int $notice_id
	 *
	 * @return string
	 */
	public function get_edit_notice_url( int $notice_id ): string {
		return add_query_arg( self::NOTICE_ID, $notice_id, $this->get_settings_url() );
	}

	/**
	 * @return string
	 */
	public function get_add_notice_url(): string {
		return wp_nonce_url(
			$this->get_settings_url(),
			self::NONCE_ACTION,
			self::NOTICE_ADD_NEW
		);
	}

	/**
	 * @param int $notice_id .
	 */
	public function get_delete_notice_url( int $notice_id ): string {
		return wp_nonce_url(
			add_query_arg( self::NOTICE_ID, $notice_id, $this->get_settings_url() ),
			self::NONCE_ACTION,
			self::NOTICE_DELETE
		);
	}

	/**
	 * @return string
	 */
	public function get_deleted_notice_url(): string {
		return add_query_arg( self::NOTICE_DELETED, 1, $this->get_settings_url() );
	}

	/**
	 * @return string
	 */
	private function get_settings_url(): string {
		return admin_url( 'admin.php?page=wc-settings&tab=shipping&section=' . WooCommerceSettingsPage::SECTION_ID );
	}
}
