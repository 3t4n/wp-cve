<?php

namespace WPDesk\FlexibleInvoices\Marketing;

use WPDeskFIVendor\WPDesk\Library\Marketing\Boxes\Assets;
use WPDeskFIVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;
use WPDeskFIVendor\WPDesk\Library\Marketing\RatePlugin\RateBox;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;

class SupportMenuPage implements Hookable {

	const SCRIPTS_VERSION = 2;
	const PLUGIN_SLUG = 'flexible-invoices';

	/**
	 * @var string
	 */
	private $assets_url;

	public function __construct( string $assets_url ) {
		$this->assets_url = $assets_url;
		$this->init_renderer();
	}

	public function hooks() {
		add_action( 'admin_menu', function () {
			add_submenu_page(
					RegisterPostType::POST_TYPE_MENU_URL,
					esc_html__( 'Start Here', 'flexible-invoices' ),
					esc_html__( 'Start Here', 'flexible-invoices' ),
					'manage_options',
					'wpdesk-marketing',
					[ $this, 'render_page_action' ],
					11
			);
		}, 9999 );

		add_action( 'admin_footer', [ $this, 'append_plugin_rate' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		Assets::enqueue_assets();
		Assets::enqueue_owl_assets();
	}

	/**
	 * Init renderer.
	 */
	private function init_renderer() {
		$resolver = new ChainResolver();
		$resolver->appendResolver( new DirResolver( __DIR__ . '/Views/' ) );
		$this->renderer = new SimplePhpRenderer( $resolver );
	}

	public function render_page_action() {
		$local = get_locale();
		if ( $local === 'en_US' ) {
			$local = 'en';
		}
		$boxes = new MarketingBoxes( self::PLUGIN_SLUG, $local );
		echo  $this->renderer->render( 'marketing-page', [ 'boxes' => $boxes ] );
	}

	/**
	 * @return bool
	 */
	private function should_show_rate_notice(): bool {
		global $current_screen;

		return $current_screen->post_type === 'inspire_invoice';
	}

	/**
	 * Add plugin rate box to settings & support page
	 */
	public function append_plugin_rate() {
		if ( $this->should_show_rate_notice() ) {
			$rate_box = new RateBox();
			echo $this->renderer->render( 'rate-box-footer', [ 'rate_box' => $rate_box ] );
		}
	}

	/**
	 * @param string $screen_id
	 */
	public function admin_enqueue_scripts( $screen_id ) {
		if ( in_array( $screen_id, array( 'inspire_invoice_page_wpdesk-marketing' ), true ) ) {
			wp_enqueue_style( 'marketing-page', $this->assets_url . 'css/marketing.css', array(), self::SCRIPTS_VERSION );
			wp_enqueue_script( 'marketing-page', $this->assets_url . 'js/modal.js', [ 'jquery' ], self::SCRIPTS_VERSION, true );
		}
	}

}
