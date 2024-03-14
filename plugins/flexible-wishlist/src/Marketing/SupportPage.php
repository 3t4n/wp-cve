<?php
declare(strict_types=1);

namespace WPDesk\FlexibleWishlist\Marketing;

use FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Assets;
use FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;
use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Service\TemplateLoader;
use WPDesk\FlexibleWishlist\Settings\SettingsPageGenerator;

/**
 * Add remote page with support and marketing content. This will be overwritten by the PRO version
 * of the plugin.
 */
class SupportPage implements Hookable {

	private TemplateLoader $template;

	public function __construct( TemplateLoader $template ) {
		$this->template = $template;
	}

	public function hooks(): void {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ], 9999 );
	}

	public function add_menu_page() {
		if ( is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) ) {
			return;
		}
		add_submenu_page(
			SettingsPageGenerator::MENU_PAGE_SLUG,
			esc_html__( 'Start Here', 'flexible-wishlist' ),
			'<span style="color:#00FFC2;font-weight: bold">' . esc_html__( 'Start Here', 'flexible-wishlist' ) . '</span>',
			'manage_options',
			'flexible-wishlist-marketing',
			[ $this, 'render_page_action' ],
			0
		);
		Assets::enqueue_assets();
		Assets::enqueue_owl_assets();
	}

	public function render_page_action() {
		$local = get_locale();
		if ( $local === 'en_US' ) {
			$local = 'en';
		}
		$this->template->load_template(
			'marketing-page',
			[
				'boxes' => new MarketingBoxes( 'flexible-wishlist', $local ),
			]
		);
	}
}
