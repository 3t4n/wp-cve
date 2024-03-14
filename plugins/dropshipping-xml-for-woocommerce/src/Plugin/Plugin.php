<?php

/**
 * Plugin main class.
 *
 * @package WPDesk\DropshippingXmlFree
 */

namespace WPDesk\DropshippingXmlFree;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
use DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\AssetsConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\FilesConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\TemplateConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\PluginConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\ConfigInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerAwareInterface;
use WPDesk\DropshippingXmlFree\Config\ServiceConfig;
use WPDesk\DropshippingXmlFree\Config\ActionConfig;
use DropshippingXmlFreeVendor\WPDesk\Dashboard\DashboardWidget;


/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @package WPDesk\DropshippingXmlFree
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	const MARKETING_SLUG = 'woocommerce-dropshipping-xml-free';

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );
		$this->setLogger( new NullLogger() );

		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
	}

	/**
	 * Initializes plugin external state.
	 *
	 * The plugin internal state is initialized in the constructor and the plugin should be internally consistent after creation.
	 * The external state includes hooks execution, communication with other plugins, integration with WC etc.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$config = $this->init_config();

		$service_container_class = $config->get_param( 'service.container' )->get();

		$service_container_class            = $config->get_param( 'service.container' )->get();
		$dependency_resolver_class          = $config->get_param( 'service.resolver' )->get();
		$dependency_binder_collection_class = $config->get_param( 'service.binder_collection' )->get();
		$listener_collection_class          = $config->get_param( 'service.listener_collection' )->get();

		$dependency_binder = new $dependency_binder_collection_class();
		$dependency_binder->add( $config->get_param( 'service.bind' )->get() );
		$dependency_resolver = new $dependency_resolver_class( $dependency_binder );

		$listener_collection = new $listener_collection_class();
		$listener_collection->add( $config->get_param( 'service.listeners' )->get() );

		$service_container = new $service_container_class( $dependency_resolver, $listener_collection );
		if ( $dependency_resolver instanceof ServiceContainerAwareInterface ) {
			$dependency_resolver->set_service_container( $service_container );
		}

		$service_container->add_forbidden( $config->get_param( 'service.forbidden' )->get() );

		$service_container->register_from_array( $config->get_param( 'action' )->get() );
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
		$widget = new DashboardWidget();
		$widget->hooks();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function init_config(): ConfigInterface {
		$config = new Config();
		$config->register_config(
			[
				new PluginConfig(
					$this->plugin_info->get_plugin_name(),
					$this->plugin_info->get_text_domain(),
					$this->plugin_info->get_version(),
					$this->plugin_info->get_plugin_file_name(),
					$this->plugin_info->get_plugin_dir(),
					$this->plugin_info->get_plugin_slug(),
					self::MARKETING_SLUG
				),
				new TemplateConfig(),
				new AssetsConfig(),
				new FilesConfig(),
				new ServiceConfig(),
				new ActionConfig(),
				new MenuConfig(),
			]
		);

		return $config;
	}
}
