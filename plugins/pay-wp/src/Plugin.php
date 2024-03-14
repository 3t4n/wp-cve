<?php
/**
 * Plugin main class.
 */

namespace WPDesk\GatewayWPPay;

use WPDesk\GatewayWPPay\Blocks\AutopayBlock;
use WPDesk\GatewayWPPay\Blocks\BlikBlock;
use WPDesk\GatewayWPPay\Blocks\RecurringBlock;
use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;
use WPDesk\GatewayWPPay\BlueMediaApi\Handlers\APIHandler;
use WPDesk\GatewayWPPay\WooCommerceGateway\BlikZeroEmbedGateway;
use WPDesk\GatewayWPPay\WooCommerceGateway\CardEmbedGateway;
use WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway;
use WPDesk\GatewayWPPay\WooCommerceGateway\SubscriptionGateway;
use WPPayVendor\Psr\Log\LoggerAwareInterface;
use WPPayVendor\Psr\Log\LoggerAwareTrait;
use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\Psr\Log\NullLogger;
use WPPayVendor\WPDesk\Dashboard\DashboardWidget;
use WPPayVendor\WPDesk\Logger\WPDeskLoggerFactory;
use WPPayVendor\WPDesk\View\Renderer\Renderer;
use WPPayVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPPayVendor\WPDesk\View\Resolver\DirResolver;
use WPPayVendor\WPDesk_Plugin_Info;
use WPPayVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use WPPayVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPPayVendor\WPDesk\PluginBuilder\Plugin\HookableParent;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	private $scripts_version = 3;

	const WPPAY_REGISTER_URL                    = 'https://wpde.sk/wppay-plugin-rejestracja';
	const NONCE_AJAX_ACTION_GET_SINGLE_CURRENCY = 'nonce_wppay_get_single_currency';
	private StandardPaymentGateway $autopay_gateway;
	private BlikZeroEmbedGateway $blik_gateway;
	private SubscriptionGateway $subscription_gateway;
	private CardEmbedGateway $card_gateway;

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );

		$this->setLogger( ( new WPDeskLoggerFactory )->createWPDeskLogger( 'autopay_log' ) );

		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
	}

	public function init(): void {
		$this->settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wppay' );
		$this->support_url  = 'https://www.wpdesk.pl/support/';

		require_once __DIR__ . "/../vendor_prefixed/guzzlehttp/guzzle/src/functions_include.php";
		require_once __DIR__ . "/../vendor_prefixed/guzzlehttp/promises/src/functions_include.php";

		parent::init();
	}

	public function hooks(): void {
		parent::hooks();

		$plugin_url = $this->get_plugin_url();

		$client_factory = new BlueMediaClientFactory();

		$gateway              = new StandardPaymentGateway( $client_factory, $plugin_url, $this->get_logger() );
		$blik_gateway         = new BlikZeroEmbedGateway( $client_factory, $plugin_url, $this->get_renderer(), $this->get_logger() );
		$subscription_gateway = new SubscriptionGateway( $client_factory, $plugin_url, $this->get_logger() );

		$this->autopay_gateway      = $gateway;
		$this->blik_gateway         = $blik_gateway;
		$this->subscription_gateway = $subscription_gateway;

		$api_handler = new APIHandler( $this->autopay_gateway, $this->subscription_gateway, $this->get_logger() );

		$client_factory->set_settings( $this->autopay_gateway );

		$api_handler->hooks();
		$this->autopay_gateway->hooks();
		$this->blik_gateway->hooks();
		$this->subscription_gateway->hooks();

		add_filter( 'woocommerce_payment_gateways', [ $this, 'register_payment_gateways' ] );
		add_action( 'woocommerce_blocks_loaded', [ $this, 'register_block_support' ] );

		( new DashboardWidget() )->hooks();
	}

	public function register_payment_gateways( $methods ) {
		$methods[] = $this->autopay_gateway;
		$methods[] = $this->blik_gateway;
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$methods[] = $this->subscription_gateway;
		}

		return $methods;
	}

	private function get_renderer( string $folder = '' ): Renderer {
		return new SimplePhpRenderer( new DirResolver( $this->plugin_info->get_plugin_dir() . '/src/views/' . $folder ) );
	}
	/**
	 * For external use.
	 *
	 * @param string $currency
	 *
	 * @return bool
	 */
	public static function has_credentials( string $currency = 'PLN' ): bool {
		$client_factory = new BlueMediaClientFactory();
		$client_factory->set_settings( new StandardPaymentGateway( $client_factory, '') );

		return $client_factory->has_credentials( $currency );
	}

	/**
	 * Admin enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();
		$suffix         = ''; //defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		if ( in_array( $current_screen->id, [ 'woocommerce_page_wc-settings' ] )
		     && isset( $_GET['tab'] ) && $_GET['tab'] === 'checkout'
		     && isset( $_GET['section'] ) && $_GET['section'] === 'wppay'
		) {
			wp_register_style( 'wppay_admin_css', $this->get_plugin_assets_url() . 'css/admin' . $suffix . '.css', [],
				$this->scripts_version );
			wp_enqueue_style( 'wppay_admin_css' );
			wp_enqueue_script( 'wppay_admin_js', $this->get_plugin_assets_url() . 'js/admin' . $suffix . '.js',
				[ 'jquery' ], $this->scripts_version, true );
			$protocol = is_ssl() ? 'https://' : 'http://';
			wp_localize_script( 'wppay_admin_js', 'wppay_admin_object', [
				'site_url'    => str_replace( $protocol, '', site_url() ),
				'protocol'    => $protocol,
				'wppay_nonce' => wp_create_nonce( self::NONCE_AJAX_ACTION_GET_SINGLE_CURRENCY ),
			] );

			wp_register_style( 'jquery-ui-style', WC()->plugin_url() . '/assets/css/jquery-ui/jquery-ui.min.css',
				[], $this->scripts_version );
			wp_enqueue_script( 'jquery-ui-accordion' );
		}
	}

	public function register_block_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			add_action( 'woocommerce_blocks_payment_method_type_registration',
				function ( \Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new AutopayBlock( $this->autopay_gateway, $this->get_plugin_assets_url() . 'js/checkout-blocks/autopay_block', $this->get_plugin_assets_url() . 'images/icon.svg' ) );
					$payment_method_registry->register( new BlikBlock( $this->blik_gateway, $this->get_plugin_assets_url() . 'js/checkout-blocks/blik_block', $this->get_plugin_assets_url() . 'images/icon-blik.png' ) );
					$payment_method_registry->register( new RecurringBlock( $this->subscription_gateway, $this->get_plugin_assets_url() . 'js/checkout-blocks/recurring_block', $this->get_plugin_assets_url() . 'images/icon.svg' ) );
				}
			);
		}
	}

	public function get_logger(): ?LoggerInterface {
		$logger_enabled = apply_filters( 'autopay_logger_enabled', false);

		if(!$logger_enabled){
			return new NullLogger();
		}
		return $this->logger;
	}
}
