<?php

namespace WpifyWoo;

use Exception;
use WpifyWoo\Managers\ApiManager;
use WpifyWoo\Managers\ModulesManager;
use WpifyWoo\Managers\PostTypesManager;
use WpifyWoo\Managers\RepositoriesManager;
use WpifyWoo\Managers\TaxonomiesManager;
use WpifyWooDeps\Wpify\Asset\AssetFactory;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractPlugin;
use WpifyWooDeps\Wpify\Core\Exceptions\ContainerInvalidException;
use WpifyWooDeps\Wpify\Core\Exceptions\ContainerNotExistsException;
use WpifyWooDeps\Wpify\Core\Interfaces\RepositoryInterface;
use WpifyWooDeps\Wpify\Core\WordpressMonologHandler;
use WpifyWooDeps\Wpify\CustomFields\CustomFields;
use WpifyWooDeps\Wpify\PluginUtils\PluginUtils;

/**
 * Class Plugin
 *
 * @package Wpify
 */
class Plugin extends AbstractPlugin {

	/** Plugin version */
	public const VERSION = '4.0.9';

	/** Plugin slug name */
	public const PLUGIN_SLUG = 'wpify-woo';

	/** Plugin namespace */
	public const PLUGIN_NAMESPACE = '\\' . __NAMESPACE__;

	/** @var Admin */
	private $admin;

	/** @var PostTypesManager */
	private $post_types_manager;

	/** @var TaxonomiesManager */
	private $taxonomies_manager;

	/** @var RepositoriesManager */
	private $repositories_manager;

	/** @var ApiManager */
	private $api_manager;

	/** @var Assets */
	private $assets;

	/** @var ModulesManager */
	private $modules_manager;

	/** @var WooCommerceIntegration */
	private $woocommerce_integration;

	/** @var Logger */
	private $logger;
	/**
	 * @var License
	 */
	private $license;
	/**
	 * @var Premium
	 */
	private $premium;

	private $wcf;

	private $cli = CLI::class;

	/**
	 * @var AssetFactory
	 */
	private $asset_factory;

	/**
	 * Plugin constructor.
	 *
	 * @param Admin $admin
	 * @param RepositoriesManager $repositories_manager
	 * @param ApiManager $api_manager
	 * @param PostTypesManager $post_types_manager
	 * @param TaxonomiesManager $taxonomies_manager
	 * @param Assets $assets
	 * @param ModulesManager $modules_manager
	 * @param WooCommerceIntegration $woocommerce_integration
	 * @param Logger $logger
	 * @param License $license
	 * @param Premium $premium
	 * @param CustomFields $wcf
	 *
	 * @throws ContainerInvalidException
	 * @throws ContainerNotExistsException
	 */
	public function __construct(
		Admin $admin,
		RepositoriesManager $repositories_manager,
		ApiManager $api_manager,
		PostTypesManager $post_types_manager,
		TaxonomiesManager $taxonomies_manager,
		Assets $assets,
		ModulesManager $modules_manager,
		WooCommerceIntegration $woocommerce_integration,
		Logger $logger,
		License $license,
		Premium $premium,
		CustomFields $wcf,
		AssetFactory $asset_factory
	) {
		$this->admin                   = $admin;
		$this->post_types_manager      = $post_types_manager;
		$this->taxonomies_manager      = $taxonomies_manager;
		$this->repositories_manager    = $repositories_manager;
		$this->api_manager             = $api_manager;
		$this->assets                  = $assets;
		$this->modules_manager         = $modules_manager;
		$this->woocommerce_integration = $woocommerce_integration;
		$this->logger                  = $logger;
		$this->license                 = $license;
		$this->premium                 = $premium;
		$this->wcf                     = $wcf;
		$this->asset_factory           = $asset_factory;

		parent::__construct();
	}

	public function get_admin(): Admin {
		return $this->admin;
	}

	public function get_repositories_manager(): RepositoriesManager {
		return $this->repositories_manager;
	}

	/**
	 * @param string $class
	 *
	 * @return RepositoryInterface
	 */
	public function get_repository( string $class ) {
		return $this->repositories_manager->get_module( $class );
	}

	public function get_api_manager(): ApiManager {
		return $this->api_manager;
	}

	public function get_api( string $class ) {
		return $this->api_manager->get_module( $class );
	}

	public function get_post_types_manager(): PostTypesManager {
		return $this->post_types_manager;
	}

	public function get_post_type( string $class ) {
		return $this->post_types_manager->get_module( $class );
	}

	public function get_taxonomies_manager(): TaxonomiesManager {
		return $this->taxonomies_manager;
	}

	public function get_taxonomy( string $class ) {
		return $this->taxonomies_manager->get_module( $class );
	}

	public function get_assets(): Assets {
		return $this->assets;
	}

	/**
	 * Print styles in theme
	 *
	 * @param $handles
	 */
	public function print_assets( string ...$handles ) {
		$this->assets->print_assets( $handles );
	}

	/**
	 * @return ModulesManager
	 */
	public function get_modules_manager(): ModulesManager {
		return $this->modules_manager;
	}

	/**
	 * @return ModulesManager
	 */
	public function get_module( $name ) {
		$module = $this->modules_manager->get_module( $name );
		if ( $module ) {
			return $module;
		}

		$path = explode( '\\', $name );

		return $this->modules_manager->get_module( array_pop( $path ) );
	}

	/**
	 * @return WooCommerceIntegration
	 */
	public function get_woocommerce_integration(): WooCommerceIntegration {
		return $this->woocommerce_integration;
	}

	/**
	 * @return Logger
	 */
	public function get_logger(): Logger {
		return $this->logger;
	}

	public function get_wcf(): CustomFields {
		return $this->wcf;
	}

	/**
	 * Plugin activation and upgrade
	 *
	 * @param $network_wide
	 *
	 * @return void
	 */
	public function activate( $network_wide ) {
		$wordPressHandler = new WordpressMonologHandler( $this->wpdb, $this->logger->table(), array( 'data' ) );
		$wordPressHandler->set_max_table_rows( 250000 );
		$wordPressHandler->initialize( array() );
	}

	/**
	 * Plugin de-activation
	 *
	 * @param $network_wide
	 *
	 * @return void
	 */
	public function deactivate( $network_wide ) {
	}

	/**
	 * Plugin uninstall
	 *
	 * @return void
	 */
	public function uninstall() {
	}

	/**
	 * @return License
	 */
	public function get_license(): License {
		return $this->license;
	}

	/**
	 * @return Premium
	 */
	public function get_premium(): Premium {
		return $this->premium;
	}

	/**
	 * @return string
	 */
	public function get_cli() {
		return $this->cli;
	}

	/**
	 * @param string $cli
	 */
	public function set_cli( $cli ): void {
		$this->cli = $cli;
	}

	public function get_asset_factory(): AssetFactory {
		return $this->asset_factory;
	}

	public function set_asset_factory( AssetFactory $asset_factory ): void {
		$this->asset_factory = $asset_factory;
	}

	/**
	 * Method to check if plugin has its dependencies. If not, it silently aborts
	 *
	 * @return bool
	 */
	protected function get_dependencies_exist() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	protected function load_components() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->load( 'cli' );
		}
	}
}
