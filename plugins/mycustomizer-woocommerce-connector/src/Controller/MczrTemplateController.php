<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrPathResolver;

MczrAccess::isAuthorized();

class MczrTemplateController {

	public function __construct() {
		$this->factory  = new MczrFactory();
		$this->twig     = $this->factory->getTwig();
		$this->resolver = new MczrPathResolver();
	}

	public function init() {
		add_filter( 'woocommerce_locate_template', array( $this, 'mczrLocateTemplate' ), 10, 3 );
	}
	/*
	 * For Mczr products type, Mczr templates takes precedence over
	 * WooCommerce ones.
	 * Look in : current theme folder or fallback to Mczr plugin templates.
	 * This will not look in WooCommerce templates.
	 * add_action('mczrIframe') to display Mczr iframe wherever you want.
	 */

	public function mczrLocateTemplate( $template, $templateName, $templatePath ) {
		global $woocommerce;
		global $post;
		$product = \wc_get_product();

		if ( 'boolean' == gettype( $product ) ) {
			return $template;
		}

		// This filter only apply on mczr product type
		if ( ! ( 'product' == $post->post_type && $product->is_type( 'mczr' ) ) ) {
			return $template;
		}
		$pluginPath = $this->resolver->plugin() . '/src/Resources/MyCustomizer/';
		$themePath  = $this->resolver->theme() . '/MyCustomizer';

		// Theme always takes precedence
		if ( is_file( "$themePath/$templateName" ) ) {
			return "$themePath/$templateName";
		} elseif ( is_file( "$pluginPath/$templateName" ) ) {
			return "$pluginPath/$templateName";
		}

		return $template;
	}
}
