<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Api' ) ) {
	return;
}

use Payever\Sdk\Core\ClientConfiguration;
use Payever\Sdk\Core\Logger\FileLogger;
use Payever\Sdk\Core\Enum\ChannelSet;
use Payever\Sdk\Core\Lock\FileLock;
use Payever\Sdk\Core\Lock\LockInterface;
use Payever\Sdk\Inventory\InventoryApiClient;
use Payever\Sdk\Payments\PaymentsApiClient;
use Payever\Sdk\Payments\ThirdPartyPluginsApiClient;
use Payever\Sdk\Payments\WidgetsApiClient;
use Payever\Sdk\Plugins\Command\PluginCommandExecutorInterface;
use Payever\Sdk\Plugins\Command\PluginCommandManager;
use Payever\Sdk\Plugins\PluginsApiClient;
use Payever\Sdk\Plugins\WhiteLabelPluginsApiClient;
use Payever\Sdk\Products\ProductsApiClient;
use Payever\Sdk\ThirdParty\Action;
use Payever\Sdk\ThirdParty\ThirdPartyApiClient;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class WC_Payever_Api {

	use WC_Payever_WP_Wrapper_Trait;

	/** @var ClientConfiguration */
	private $client_configuration;

	/** @var WC_Payever_Api_TokenList */
	private $token_list;

	/** @var FileLock */
	private $locker;

	/** @var LoggerInterface */
	private $logger;

	/** @var WC_Payever_Plugin_Registry_Info_Provider */
	private $registry_info_provider;

	/** @var PluginsApiClient */
	private $plugins_api_client;

	/** @var PluginCommandManager */
	private $plugin_command_manager;

	/** @var PluginCommandExecutorInterface */
	private $plugin_command_executor;

	/** @var self */
	private static $instance;

	/** @var ClientConfiguration */
	private $TP_client_configuration;

	/** @var InventoryApiClient */
	private $inventory_api_client;

	/** @var InventoryApiClient */
	private $products_api_client;

	/** @var ThirdPartyPluginsApiClient */
	private $payments_api_client;

	/** @var ThirdPartyPluginsApiClient */
	private $third_party_plugins_api_client;

	/** @var WidgetsApiClient */
	private $payment_widgets_api_client;

	/** @var WhiteLabelPluginsApiClient */
	private $white_label_plugin_api_client;

	/** @var ThirdPartyApiClient */
	private $third_party_api_client;

	/** @var WC_Payever_Api_Apm_Secret_Service */
	private $apm_secret_service;

	private function __construct() {
		$this->__init();
	}

	private function __init() {
		$this->apm_secret_service      = new WC_Payever_Api_Apm_Secret_Service();
		$this->client_configuration    = $this->prepare_client_configuration();
		$this->TP_client_configuration = $this->prepare_TP_client_configuration();
		$this->token_list              = new WC_Payever_Api_TokenList();
		$this->locker                  = $this->prepare_locker();
		$this->registry_info_provider  = new WC_Payever_Plugin_Registry_Info_Provider();
		$this->plugin_command_executor = new WC_Payever_Plugin_Command_Executor();
		$this->prepare_logger();
	}

	/**
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::reload();
		}

		return self::$instance;
	}

	public static function reload() {
		self::$instance = new self();
	}

	public static function reinit() {
		self::$instance->__init();
	}

	/**
	 * @return PaymentsApiClient
	 * @throws Exception
	 */
	public function get_payments_api_client() {
		if ( null === $this->payments_api_client ) {
			$this->payments_api_client = new ThirdPartyPluginsApiClient(
				$this->client_configuration,
				$this->token_list
			);
		}

		return $this->payments_api_client;
	}

	/**
	 * @return ThirdPartyPluginsApiClient
	 * @throws Exception
	 */
	public function get_third_party_plugins_api_client() {
		if ( null === $this->third_party_plugins_api_client ) {
			$this->third_party_plugins_api_client = new ThirdPartyPluginsApiClient(
				$this->client_configuration,
				$this->token_list
			);
		}

		return $this->third_party_plugins_api_client;
	}

	/**
	 * @return WidgetsApiClient
	 * @throws Exception
	 */
	public function get_payment_widgets_api_client() {
		if ( null === $this->payment_widgets_api_client ) {
			$this->payment_widgets_api_client = new WidgetsApiClient(
				$this->client_configuration,
				$this->token_list
			);
		}

		return $this->payment_widgets_api_client;
	}

	/**
	 * @return WhiteLabelPluginsApiClient
	 * @throws Exception
	 */
	public function get_white_label_plugin_api_client() {
		if ( null === $this->white_label_plugin_api_client ) {
			$this->white_label_plugin_api_client = new WhiteLabelPluginsApiClient();
		}

		return $this->white_label_plugin_api_client;
	}

	/**
	 * @return ThirdPartyApiClient
	 * @throws Exception
	 */
	public function get_third_party_api_client() {
		if ( null === $this->third_party_api_client ) {
			$this->third_party_api_client = new ThirdPartyApiClient(
				$this->TP_client_configuration,
				$this->token_list
			);
		}

		return $this->third_party_api_client;
	}

	/**
	 * @return ProductsApiClient
	 * @throws Exception
	 */
	public function get_products_api_client() {
		if ( null === $this->products_api_client ) {
			$this->products_api_client = new ProductsApiClient(
				$this->TP_client_configuration,
				$this->token_list
			);
		}

		return $this->products_api_client;
	}

	/**
	 * @return InventoryApiClient
	 * @throws Exception
	 */
	public function get_inventory_api_client() {
		if ( null === $this->inventory_api_client ) {
			$this->inventory_api_client = new InventoryApiClient(
				$this->TP_client_configuration,
				$this->token_list
			);
		}

		return $this->inventory_api_client;
	}

	/**
	 * @return PluginsApiClient
	 * @throws Exception
	 */
	public function get_plugins_api_client() {
		if ( null === $this->plugins_api_client ) {
			$this->plugins_api_client = new PluginsApiClient(
				$this->registry_info_provider,
				$this->prepare_client_configuration(),
				$this->token_list
			);
		}

		return $this->plugins_api_client;
	}

	/**
	 * @return PluginCommandManager
	 * @throws Exception
	 */
	public function get_plugin_command_manager() {
		if ( null === $this->plugin_command_manager ) {
			$this->plugin_command_manager = new PluginCommandManager(
				$this->get_plugins_api_client(),
				$this->plugin_command_executor,
				$this->get_logger()
			);
		}

		return $this->plugin_command_manager;
	}

	/**
	 * @return LoggerInterface
	 */
	public function get_logger() {
		return $this->logger;
	}

	/**
	 * @return void
	 */
	private function prepare_logger() {
		if ( version_compare( WOOCOMMERCE_VERSION, '2.7.0', '<' ) ) {
			$wrapper = new WC_Payever_WP_Wrapper();

			$this->logger = new FileLogger(
				wp_upload_dir()['basedir'] . '/wc-logs/payever.log',
				$wrapper->get_option( WC_Payever_Helper::PAYEVER_LOG_LEVEL ) ?: 'debug'
			);
		}
		if ( version_compare( WOOCOMMERCE_VERSION, '2.7.0', '>=' ) ) {
			$this->logger = new WC_Payever_Logger();
		}

		$this->client_configuration->setLogger( $this->logger );
		$this->logger = $this->client_configuration->getLogger();
	}

	/**
	 * @return LockInterface
	 */
	public function get_locker() {
		return $this->locker;
	}

	private function prepare_locker() {
		$basedir     = wp_upload_dir()['basedir'];
		$payever_dir = $basedir . '/payever';

		if ( wp_mkdir_p( $payever_dir ) && $this->get_wp_wrapper()->is_writable( $payever_dir ) ) {
			return new FileLock( $payever_dir );
		}

		return new FileLock( $basedir );
	}

	/**
	 * @return ClientConfiguration
	 * @throws Exception
	 */
	private function prepare_client_configuration() {
		$client_configuration = new ClientConfiguration();
		$api_mode             = $this->get_environment( get_option( WC_Payever_Helper::PAYEVER_ENVIRONMENT ) );

		$client_configuration->setChannelSet( ChannelSet::CHANNEL_WOOCOMMERCE )
							->setApiMode( $api_mode )
							->setClientId( get_option( WC_Payever_Helper::PAYEVER_CLIENT_ID ) )
							->setClientSecret( get_option( WC_Payever_Helper::PAYEVER_CLIENT_SECRET ) )
							->setBusinessUuid( get_option( WC_Payever_Helper::PAYEVER_BUSINESS_ID ) )
							->setLogDiagnostic( get_option( WC_Payever_Helper::PAYEVER_LOG_DIAGNOSTIC ) )
							->setApmSecretService( $this->apm_secret_service )
							->setApiVersion( ClientConfiguration::API_VERSION_2 );

		if ( $this->get_wp_wrapper()->get_option( WC_Payever_Helper::SANDBOX_URL_CONFIG_KEY ) ) {
			$client_configuration->setCustomSandboxUrl( get_option( WC_Payever_Helper::SANDBOX_URL_CONFIG_KEY ) );
		}
		if ( $this->get_wp_wrapper()->get_option( WC_Payever_Helper::LIVE_URL_CONFIG_KEY ) ) {
			$client_configuration->setCustomLiveUrl( get_option( WC_Payever_Helper::LIVE_URL_CONFIG_KEY ) );
		}

		return $client_configuration;
	}

	/**
	 * @return ClientConfiguration
	 */
	private function prepare_TP_client_configuration() {
		$tp_client_configuration = $this->client_configuration;
		$tp_client_configuration->setCustomSandboxUrl( null );
		$sandbox_url = $this->get_wp_wrapper()->get_option( WC_Payever_Helper::SANDBOX_THIRD_PARTY_PLODUCTS_URL_KEY );
		if ( $sandbox_url ) {
			$tp_client_configuration->setCustomSandboxUrl( $sandbox_url );
		}
		$tp_client_configuration->setCustomLiveUrl( null );
		$live_url = $this->get_wp_wrapper()->get_option( WC_Payever_Helper::LIVE_THIRD_PARTY_PLODUCTS_URL_KEY );
		if ( $live_url ) {
			$tp_client_configuration->setCustomLiveUrl( $live_url );
		}

		return $tp_client_configuration;
	}

	/**
	 * @param $mode
	 *
	 * @return int
	 */
	private function get_environment( $mode ) {
		switch ( $mode ) {
			case 1:
				$environment = ClientConfiguration::API_MODE_SANDBOX;
				break;
			default:
				$environment = ClientConfiguration::API_MODE_LIVE;
		} // End switch().

		return $environment;
	}

	/**
	 * @return Action\InwardActionProcessor
	 */
	public function get_inward_sync_action_processor() {
		$handlers = array(
			new WC_Payever_Synchronization_Action_Handler_CreateProduct(),
			new WC_Payever_Synchronization_Action_Handler_UpdateProduct(),
			new WC_Payever_Synchronization_Action_Handler_RemoveProduct(),
			new WC_Payever_Synchronization_Action_Handler_AddInventory(),
			new WC_Payever_Synchronization_Action_Handler_SubtractInventory(),
			new WC_Payever_Synchronization_Action_Handler_SetInventory(),
		);
		foreach ( $handlers as $handler ) {
			if ( $handler instanceof LoggerAwareInterface ) {
				$handler->setLogger( $this->logger );
			}
		}

		return new Action\InwardActionProcessor(
			new Action\ActionHandlerPool( $handlers ),
			new Action\ActionResult(),
			$this->get_logger()
		);
	}

	/**
	 * @return Action\BidirectionalActionProcessor
	 *
	 * @throws Exception
	 */
	public function get_bidirectional_sync_action_processor() {
		return new Action\BidirectionalActionProcessor(
			$this->get_inward_sync_action_processor(),
			$this->get_outward_sync_action_processor()
		);
	}

	/**
	 * @return Action\OutwardActionProcessor
	 *
	 * @throws Exception
	 */
	public function get_outward_sync_action_processor() {
		return new Action\OutwardActionProcessor(
			$this->get_products_api_client(),
			$this->get_inventory_api_client(),
			$this->get_logger()
		);
	}
}
