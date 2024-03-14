<?php
/**
 * Class SingleSectionSettingsFields
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings;

use Octolize\Shipping\Notices\SettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\PostCodesField;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\WysiwygField;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\ZoneRegionsField;

/**
 * .
 */
class SingleSectionSettingsFields implements SettingsFields {
	public const ENABLED_FIELD    = 'enabled';
	public const TITLE_FIELD      = 'title';
	public const REGIONS_FIELD    = 'regions';
	public const MESSAGE_FIELD    = 'message';
	public const LOCATIONS_FIELD  = 'locations';
	public const POST_CODES_FIELD = 'post_codes';

	/**
	 * @return array[]
	 * @phpstan-ignore-next-line
	 */
	public function get_settings_fields(): array {
		return [
			[
				'title' => __( 'Shipping Notices', 'octolize-shipping-notices' ),
				'type'  => 'title',
				'id'    => WooCommerceSettingsPage::SECTION_ID,
			],
			[
				'type'  => 'checkbox',
				'id'    => self::ENABLED_FIELD,
				'title' => __( 'Enabled', 'octolize-shipping-notices' ),
				'desc'  => __( 'Activate this notice', 'octolize-shipping-notices' ),
			],
			[
				'type'              => 'text',
				'id'                => self::TITLE_FIELD,
				'title'             => __( 'Notice title', 'octolize-shipping-notices' ) . '*',
				'custom_attributes' => [ 'required' => 'required' ],
				'desc_tip'          => __( 'Enter the unique title for easy identification, which will be used only in the Shipping Notices table.', 'octolize-shipping-notices' ),
			],
			[
				'type'              => ZoneRegionsField::FIELD_TYPE,
				'id'                => self::REGIONS_FIELD,
				'title'             => __( 'Zone regions', 'octolize-shipping-notices' ) . '*',
				'custom_attributes' => [ 'required' => 'required' ],
				'desc_tip'          => __( 'Choose the Zone regions the custom shipping notice will be displayed for.', 'octolize-shipping-notices' ),
			],

			[
				'type'              => PostCodesField::FIELD_TYPE,
				'id'                => self::POST_CODES_FIELD,
				'title'             => __( 'Limit to specific ZIP/postcodes', 'octolize-shipping-notices' ),
				'desc'              => sprintf(
				// translators: documentation url.
					__( 'Postcodes containing wildcards (e.g. CB23*) or fully numeric ranges (e.g. <code>90210...99000</code>) are also supported. Please see the shipping zones <a href="%s" target="_blank">documentation</a> for more information.', 'octolize-shipping-notices' ),
					'https://docs.woocommerce.com/document/setting-up-shipping-zones/#section-3'
				),
				'custom_attributes' => [
					'rows' => '5',
					'cols' => '25',
				],
				'desc_tip'          => __( 'Choose in which Zone regions the shipping notice will be displayed.', 'octolize-shipping-notices' ),
				'placeholder'       => __( 'List 1 postcode per line', 'octolize-shipping-notices' ),
			],
			[
				'type'              => 'multiselect',
				'id'                => self::LOCATIONS_FIELD,
				'title'             => __( 'Notice display pages', 'octolize-shipping-notices' ) . '*',
				'custom_attributes' => [ 'required' => 'required' ],
				'class'             => 'wc-enhanced-select',
				'options'           => (array) apply_filters(
					'shipping-notices/pages',
					[
						'cart'     => __( 'Cart', 'octolize-shipping-notices' ),
						'checkout' => __( 'Checkout', 'octolize-shipping-notices' ),
					]
				),
				'desc_tip'          => __( 'Select pages where the default \'No shipping options were found...\' notice should be replaced with the custom one.', 'octolize-shipping-notices' ),
			],
			[
				'type'              => WysiwygField::FIELD_TYPE,
				'id'                => self::MESSAGE_FIELD,
				'title'             => __( 'Message content', 'octolize-shipping-notices' ) . '*',
				'custom_attributes' => [ 'required' => 'required' ],
				'desc_tip'          => __( 'Enter the custom shipping notice content which will be displayed to your customers. Please mind that you can also use the HTML tags here e.g. to embed the links, contact email address, phone number, etc.', 'octolize-shipping-notices' ),
			],
			[
				'type' => 'sectionend',
				'id'   => WooCommerceSettingsPage::SECTION_ID,
			],
		];
	}
}
