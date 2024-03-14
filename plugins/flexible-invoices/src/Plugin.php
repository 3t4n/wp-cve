<?php
/**
 * Plugin main class.
 *
 * @package InvoicesWooCommerce
 */

namespace WPDesk\FlexibleInvoices;

use WPDesk\FlexibleInvoices\Addons\Filters\AdvancedFiltersAddon;
use WPDesk\FlexibleInvoices\Addons\Sending\SendingSettingsAddon;
use WPDesk\FlexibleInvoices\Marketing\SupportMenuPage;
use WPDeskFIVendor\WPDesk\Dashboard\DashboardWidget;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\Psr\Log\LoggerAwareInterface;
use WPDeskFIVendor\Psr\Log\LoggerAwareTrait;
use WPDeskFIVendor\Psr\Log\NullLogger;
use WPDeskFIVendor\WPDesk\Logger\WPDeskLoggerFactory;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
use WPDeskFIVendor\WPDesk_Plugin_Info;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use HookableParent;
	use LoggerAwareTrait;

	/**
	 * @var string
	 */
	private $plugin_text_domain;
	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $pro_url;

	/**
	 * @var Renderer
	 */
	private $renderer;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );

		$this->plugin_url         = trailingslashit( $this->plugin_info->get_plugin_url() );
		$this->plugin_path        = trailingslashit( $this->plugin_info->get_plugin_dir() );
		$this->plugin_text_domain = $this->plugin_info->get_text_domain();
		$this->plugin_namespace   = $this->plugin_text_domain;
		$this->init_renderer();
		$this->setLogger( $this->is_debug_mode() ? ( new WPDeskLoggerFactory() )->createWPDeskLogger() : new NullLogger() );
	}

	/**
	 * Init renderer.
	 */
	private function init_renderer() {
		$resolver = new ChainResolver();
		$resolver->appendResolver( new DirResolver( $this->plugin_path . '/templates/' ) );
		$this->renderer = new SimplePhpRenderer( $resolver );
	}

	/**
	 * Fires hooks
	 */
	public function hooks() {
		parent::hooks();
		$integration = new InvoicesIntegration( $this->plugin_info, $this->logger );
		$this->add_hookable( $integration );
		$this->add_hookable( new SupportMenuPage( $this->plugin_url . '/assets/' ) );

		if ( WooCommerce::is_active() ) {
			( new Tracker\Tracker( $this->plugin_info->get_plugin_file_name() ) )->hooks();
			( new Tracker\UsageDataTracker( $this->plugin_info->get_plugin_file_name() ) )->hooks();
			Translator::$text_domain = $this->plugin_text_domain;
			Translator::init( $this->plugin_info );
		}

		$this->add_hookable( new AdvancedFiltersAddon() );
		$this->add_hookable( new SendingSettingsAddon() );
		( new DashboardWidget() )->hooks();
		$this->hooks_on_hookable_objects();
	}

	/**
	 * Returns true when debug mode is on.
	 *
	 * @return bool
	 */
	private function is_debug_mode() {
		$helper_options = get_option( 'wpdesk_helper_options', [] );

		return isset( $helper_options['debug_log'] ) && '1' === $helper_options['debug_log'];
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links List of links.
	 *
	 * @return array
	 */
	public function links_filter( $links ) {
		unset( $links['0'] );
		$is_pl        = 'pl_PL' === get_locale();
		$support_url  = $is_pl ? 'https://wordpress.org/support/plugin/flexible-invoices/' : 'https://flexibleinvoices.com/support/';
		$start_here_url = admin_url( 'edit.php?post_type=inspire_invoice&page=wpdesk-marketing' );
		$settings_url = admin_url( 'edit.php?post_type=inspire_invoice&page=invoices_settings' );
		$docs_url     = $is_pl ? 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/' : 'https://docs.flexibleinvoices.com/';
		$pro_url      = $is_pl ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/' : 'https://www.flexibleinvoices.com/';
		$pro_url      .= '?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link';

		$plugin_links['start-here'] = '<a href="' . $start_here_url . '" style="color:#005D47;font-weight:700;">' . esc_html__( 'Start Here', 'flexible-invoices' ) . '</a>';
		$plugin_links['settings'] = '<a href="' . $settings_url . '">' . esc_html__( 'Settings', 'flexible-invoices' ) . '</a>';
		$plugin_links['docs']     = '<a href="' . $docs_url . '" target="_blank">' . esc_html__( 'Docs', 'flexible-invoices' ) . '</a>';
		$plugin_links['upgrade']  = '<a href="' . $pro_url . '" target="_blank" style="color:#d64e07;font-weight:bold;">' . esc_html__( 'Upgrade to PRO →', 'flexible-invoices' ) . '</a>';
		//$plugin_links['support']  = '<a href="' . $support_url . '" target="_blank">' . esc_html__( 'Support', 'flexible-invoices' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}

}
