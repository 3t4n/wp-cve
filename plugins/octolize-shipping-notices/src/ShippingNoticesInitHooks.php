<?php
/**
 * Class ShippingNoticesInitHooks
 */

namespace Octolize\Shipping\Notices;

use Octolize\Shipping\Notices\Helpers\WooCommerceSettingsPageChecker;
use Octolize\Shipping\Notices\Model\World;
use Octolize\Shipping\Notices\Plugin\DeactivationTracker;
use Octolize\Shipping\Notices\Plugin\TextPetitionDisplayDecision;
use Octolize\Shipping\Notices\Repository\ShippingNoticeRepository;
use Octolize\Shipping\Notices\ShippingNotice\ContinentFactory;
use Octolize\Shipping\Notices\ShippingNotice\CountryFactory;
use Octolize\Shipping\Notices\ShippingNotice\NoShippingAvailableMessage;
use Octolize\Shipping\Notices\ShippingNotice\RegionFactory;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFinder;
use Octolize\Shipping\Notices\ShippingNotice\SingleNoticeOption;
use Octolize\Shipping\Notices\ShippingNotice\StateFactory;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\AddNoticeAction;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\DeleteNoticeAction;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\NoticesOrderAction;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\NoticesStatusAction;
use Octolize\Shipping\Notices\WooCommerceSettings\ArchiveSectionSettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\PostCodesField;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\ShippingNoticesField;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\WysiwygField;
use Octolize\Shipping\Notices\WooCommerceSettings\Fields\ZoneRegionsField;
use Octolize\Shipping\Notices\WooCommerceSettings\SettingsActionLinks;
use Octolize\Shipping\Notices\WooCommerceSettings\SettingsAssets;
use Octolize\Shipping\Notices\WooCommerceSettings\SingleSectionSettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;
use OctolizeShippingNoticesVendor\Octolize\Tracker\SenderRegistrator;
use OctolizeShippingNoticesVendor\Octolize\Tracker\TrackerInitializer;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use OctolizeShippingNoticesVendor\WPDesk\RepositoryRating\RepositoryRatingPetitionText;
use OctolizeShippingNoticesVendor\WPDesk\RepositoryRating\TextPetitionDisplayer;
use OctolizeShippingNoticesVendor\WPDesk_Plugin_Info;
use WC_Cart;

/**
 * Register woocommerce hooks.
 *
 * @codeCoverageIgnore
 */
class ShippingNoticesInitHooks implements Hookable {

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	/**
	 * @var string
	 */
	private $plugin_assets_url;

	/**
	 * @param string             $plugin_assets_url .
	 * @param WPDesk_Plugin_Info $plugin_info       .
	 */
	public function __construct( string $plugin_assets_url, WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info       = $plugin_info;
		$this->plugin_assets_url = $plugin_assets_url;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_init', [ $this, 'init_shipping_notices' ] );
	}

	/**
	 * @return void
	 */
	public function init_shipping_notices(): void {
		$cart      = WC()->cart;
		$countries = WC()->countries;

		$plugin_settings = new PluginSettings();

		$single_notice_option = new SingleNoticeOption();

		// Regions.
		$all_regions       = new World( 'ALL', __( 'All regions', 'octolize-shipping-notices' ) );
		$country_factory   = new CountryFactory( $countries );
		$continent_factory = new ContinentFactory( $countries, $country_factory );
		$state_factory     = new StateFactory( $countries, $country_factory );

		$region_factory             = new RegionFactory( $all_regions, $continent_factory, $country_factory, $state_factory );
		$shipping_notice_repository = new ShippingNoticeRepository( $single_notice_option, $region_factory );

		TrackerInitializer::create_from_plugin_info_for_shipping_method( $this->plugin_info, WooCommerceSettingsPage::SECTION_ID )->hooks();

		if ( is_admin() ) {
			$settings_page_checker = new WooCommerceSettingsPageChecker();
			$settings_action_links = new SettingsActionLinks();

			( new WysiwygField() )->hooks();
			( new ZoneRegionsField( $countries, $all_regions ) )->hooks();
			( new PostCodesField() )->hooks();
			( new ShippingNoticesField( $settings_action_links ) )->hooks();

			( new SettingsAssets(
				$this->plugin_assets_url,
				OCTOLIZE_SHIPPING_NOTICES_VERSION . OCTOLIZE_SHIPPING_NOTICES_SCRIPT_VERSION,
				$settings_page_checker
			) )->hooks();

			( new WooCommerceSettingsPage(
				new ArchiveSectionSettingsFields( $shipping_notice_repository ),
				new SingleSectionSettingsFields()
			) )->hooks();

			( new AddNoticeAction( $settings_action_links ) )->hooks();
			( new DeleteNoticeAction( $settings_action_links ) )->hooks();
			( new NoticesStatusAction( $settings_page_checker, $single_notice_option ) )->hooks();
			( new NoticesOrderAction( $settings_page_checker ) )->hooks();

			// Trackers.
			( new SenderRegistrator( $this->plugin_info->get_plugin_slug() ) )->hooks();

			( new TextPetitionDisplayer(
				'woocommerce_after_settings_shipping',
				new TextPetitionDisplayDecision( $settings_page_checker ),
				new RepositoryRatingPetitionText(
					'Octolize',
					__( 'Shipping Notices', 'octolize-shipping-notices' ),
					'https://octol.io/rate-sn',
					'center'
				)
			) )->hooks();
		}

		if ( $cart instanceof WC_Cart ) {
			( new NoShippingAvailableMessage(
				$cart->get_customer(),
				new ShippingNoticeFinder( $shipping_notice_repository ),
				$plugin_settings
			) )->hooks();
		}
	}
}
