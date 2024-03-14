<?php
/**
 * Class ArchiveSectionsSettingsFields
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings;

use Octolize\Shipping\Notices\Repository\ShippingNoticeRepository;
use Octolize\Shipping\Notices\SettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\ShippingNoticesField;

/**
 * .
 */
class ArchiveSectionSettingsFields implements SettingsFields {
	public const ENABLED_FIELD = 'enabled';

	/**
	 * @var ShippingNoticeRepository
	 */
	private $shipping_notice_repository;

	/**
	 * @param ShippingNoticeRepository $shipping_notice_repository
	 */
	public function __construct( ShippingNoticeRepository $shipping_notice_repository ) {
		$this->shipping_notice_repository = $shipping_notice_repository;
	}

	/**
	 * @return array[]
	 * @phpstan-ignore-next-line
	 */
	public function get_settings_fields(): array {
		return [
			[
				'title' => __( 'General Settings', 'octolize-shipping-notices' ),
				'type'  => 'title',
				'id'    => WooCommerceSettingsPage::SECTION_ID,
			],
			[
				'type'  => 'checkbox',
				'id'    => self::ENABLED_FIELD,
				'title' => __( 'Enable / Disable', 'octolize-shipping-notices' ),
				'desc'  => __( 'Turn on/off the custom shipping notices', 'octolize-shipping-notices' ),
			],
			[
				'type'             => ShippingNoticesField::FIELD_TYPE,
				'id'               => 'shipping_notices',
				'title'            => __( 'Notices', 'octolize-shipping-notices' ),
				'desc'             => __( 'The custom notices defined in the table below will replace the default WooCommerce \'No shipping options were found...\' notice.', 'octolize-shipping-notices' ),
				'desc_tip'         => __( 'Please mind that the order of the configured custom notices in the table does matter and if more than one notice has been configured for the given Zone region, the one placed higher in the table will be used.', 'octolize-shipping-notices' ),
				'shipping_notices' => $this->shipping_notice_repository->get_all(),
			],
			[
				'type' => 'sectionend',
				'id'   => WooCommerceSettingsPage::SECTION_ID,
			],
		];
	}
}
