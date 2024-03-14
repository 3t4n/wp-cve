<?php
/**
 * Class ShippingNoticesField
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Fields;

use Octolize\Shipping\Notices\WooCommerceSettings\SettingsActionLinks;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Adding new field.
 */
class ShippingNoticesField implements Hookable {
	public const FIELD_TYPE = 'shipping_notices';

	/**
	 * @var SettingsActionLinks
	 */
	private $settings_action_links;

	/**
	 * @param SettingsActionLinks $settings_action_links
	 */
	public function __construct( SettingsActionLinks $settings_action_links ) {
		$this->settings_action_links = $settings_action_links;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_admin_field_' . self::FIELD_TYPE, [ $this, 'render_field' ] );
	}

	/**
	 * @param mixed $value .
	 *
	 * @return void
	 */
	public function render_field( $value ): void {
		$settings_action_links = $this->settings_action_links;

		require __DIR__ . '/views/html-shipping-notices-field.php';
	}
}
