<?php

namespace WPDesk\DropshippingXmlFree\Action\Loader\Plugin;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Plugin\PluginLinksLoaderAction as PluginLinksLoaderActionCore;
/**
 * Class PluginLinksLoaderAction, plugin links loader.
 */
class PluginLinksLoaderAction extends PluginLinksLoaderActionCore {

	public function links_filter( array $links ) : array {
		
		$url = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=plugin-list' : 'https://wpdesk.net/products/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=plugin-list';
		foreach( $links as $key => $val ){
			if (strpos($val, 'https://www.wpdesk.pl/support/') !== false || strpos($val, 'https://www.wpdesk.net/support') !== false) {
				unset( $links[$key] );
			}
		}

		$free_plugin_links = [
			'<a href="' . $url . '" target="_blank" style="color:#FF9743;font-weight:bold;">' . __( 'Upgrade to PRO', 'dropshipping-xml-for-woocommerce' ) . ' &rarr; </a>',
		];

		return \array_merge( $free_plugin_links, $links );
	}
}
