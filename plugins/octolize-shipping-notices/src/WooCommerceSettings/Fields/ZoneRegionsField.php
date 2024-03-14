<?php
/**
 * Class ZoneRegionsField
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Fields;

use Octolize\Shipping\Notices\Model\World;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WC_Countries;

/**
 * Adding new field.
 */
class ZoneRegionsField implements Hookable {
	public const FIELD_TYPE = 'zone_regions';

	/**
	 * @var WC_Countries
	 */
	private $countries;

	/**
	 * @var World
	 */
	private $region_all;

	/**
	 * @param WC_Countries $countries  .
	 * @param World        $region_all .
	 */
	public function __construct( WC_Countries $countries, World $region_all ) {
		$this->countries  = $countries;
		$this->region_all = $region_all;
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
		$shipping_continents = $this->countries->get_shipping_continents();
		$allowed_countries   = $this->countries->get_shipping_countries();
		$region_all          = $this->region_all;
		$wc_countries        = $this->countries;

		require __DIR__ . '/views/html-zone-regions-field.php';
	}
}
