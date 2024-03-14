<?php

namespace WPDesk\DropshippingXmlFree\Action\Loader\Assets;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
/**
 * Class PluginAssetsLoaderAction, loads plugin assets.
 */
class PluginAssetsLoaderAction implements Hookable, Conditional {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var PluginHelper
	 */
	private $plugin_helper;
	public function __construct( Config $config, Request $request, PluginHelper $helper ) {
		$this->config        = $config;
		$this->request       = $request;
		$this->plugin_helper = $helper;
	}
	public function isActive() : bool {
		return $this->plugin_helper->is_plugin_page( $this->request->get_param( 'get.page' )->getAsString(), $this->request->get_param( 'get.action' )->getAsString() );
	}
	public function hooks() {
		\add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 90 );
	}
	public function enqueue_scripts() {
		$suffix  = \true === $this->config->get_param( 'plugin.development' )->get() ? '' : '.min';
		$version = $this->config->get_param( 'plugin.version' )->get();
		\wp_register_style( 'dropshipping_free_admin', $this->config->get_param( 'assets.css.dir_url' )->get() . 'admin' . $suffix . '.css', [], $version );
		\wp_enqueue_style( 'dropshipping_free_admin' );
	}
}
