<?php
namespace WPDesk\DropshippingXmlFree\Action\Installer;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;

/**
 * Class PluginUnistallerAction, checks if pro version is installed.
 */
class PluginUnistallerAction implements Hookable {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct( Config $config, Request $request ) {
		$this->config  = $config;
		$this->request = $request;
	}

	public function hooks() {
		add_action( 'admin_init', [ $this, 'check_if_pro_version_is_installed' ] );
	}

	public function check_if_pro_version_is_installed() {

		if ( is_admin() && current_user_can( 'activate_plugins' ) && is_plugin_active( 'woocommerce-dropshipping-xml/woocommerce-dropshipping-xml.php' ) ) {
			add_action( 'admin_notices', [ $this, 'plugin_notice' ] );

			deactivate_plugins( plugin_basename( $this->config->get_param( 'plugin.file' )->get() ) );
		}
	}

	public function plugin_notice() {
		$allowed_tags = [
			'p'   => [],
			'div' => [
				'class' => [],
			],
		];
		echo wp_kses( '<div class="error"><p>' . __( 'Free version of plugin Dropshipping XML for WooCommerce was deactivated because Pro version is active.', 'dropshipping-xml-for-woocommerce' ) . '</p></div>', $allowed_tags );
	}

}
