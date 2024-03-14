<?php
/**
 * Plugin main class.
 *
 * @package Octolize\Shipping\Notices;
 */

namespace Octolize\Shipping\Notices;

use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;
use OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\ShippingExtensions;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use OctolizeShippingNoticesVendor\Psr\Log\LoggerAwareInterface;
use OctolizeShippingNoticesVendor\Psr\Log\LoggerAwareTrait;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @codeCoverageIgnore
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Init hooks.
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public function hooks() {
		parent::hooks();

		$this->add_hookable( new CustomPostType() );
		$this->add_hookable( new ShippingNoticesInitHooks( $this->get_plugin_assets_url(), $this->plugin_info ) );
		$this->add_hookable( new ShippingExtensions( $this->plugin_info ) );

		$this->hooks_on_hookable_objects();
	}

	/**
	 * Quick links on plugins page.
	 *
	 * @param string[] $links .
	 *
	 * @return string[]
	 */
	public function links_filter( $links ): array {
		$docs_link    = __( 'https://octol.io/notices-docs', 'octolize-shipping-notices' );
		$support_link = __( 'https://octol.io/notices-support', 'octolize-shipping-notices' );
		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=' . WooCommerceSettingsPage::SECTION_ID );

		$external_attributes = ' target="_blank" ';

		$plugin_links = [
			'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'octolize-shipping-notices' ) . '</a>',
			'<a href="' . esc_url( $docs_link ) . '"' . $external_attributes . '>' . __( 'Docs', 'octolize-shipping-notices' ) . '</a>',
			'<a href="' . esc_url( $support_link ) . '"' . $external_attributes . '>' . __( 'Support', 'octolize-shipping-notices' ) . '</a>',
		];

		return array_merge( $plugin_links, $links );
	}
}
