<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;

MczrAccess::isAuthorized();

class MczrSocialController {

	public function __construct() {
		$this->request  = Request::createFromGlobals();
		$this->response = new Response();
		$this->settings = new MczrSettings();
		$this->mczr     = new MczrConnect();
		$this->factory  = new MczrFactory();
		$this->twig     = $this->factory->getTwig();
	}

	public function init() {
		add_action( 'wp_head', array( $this, 'getOpenGraphMetas' ) );
	}

	private function buildMetas( $metas ) {
		$return  = PHP_EOL;
		$return .= '<!-- MCZR socials metas -->' . PHP_EOL;
		foreach ( $metas as $name => $content ) {
			$return .= '<meta property="' . $name . '" content="' . $content . '" />' . PHP_EOL;
		}
		return $return;
	}

	public function getOpenGraphMetas() {
		$designId = $this->request->query->get( 'designId' );
		$metas    = array();
		$product  = \wc_get_product();

		if ( 'boolean' == gettype( $product ) ) {
			return;
		}

		if ( ! $product ) {
			return;
		}

		if ( ! $product->is_type( 'mczr' ) ) {
			return;
		}

		$productId               = ( $product->get_id() );
		$metas['og:url']         = get_permalink( $productId );
		$metas['og:type']        = 'product';
		$metas['og:title']       = 'Customizable product';
		$metas['og:description'] = 'This is a customizable product';
		$settings                = $this->settings->getAll();
		$apiBaseUrl              = MczrConfig::getInstance()['apiBaseUrl'];

		if ( $designId ) {
			$url               = "{$apiBaseUrl}/brands/{$settings['brand']}/designs/$designId/image";
			$metas['og:image'] = $url;
		}
		$builtMetas = $this->buildMetas( $metas );
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
			'br' => array(),
			'button' => array(
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'type' => array(),
			),
			'div' => array(
				'class' => array(),
			),
			'fieldset' => array(
			),
			'form' => array(
				'name' => array(),
				'method' => array(),
			),
			'h1' => array(
			),
			'h2' => array(
			),
			'h3' => array(
			),
			'h4' => array(
			),
			'hr' => array(
			),
			'iframe' => array(
				'id' => array(),
				'class' => array(),
				'data-product-id' => array(),
				'height' => array(),
				'frameBorder' => array(),
				'width' => array(),
				'src' => array(),
			),
			'img' => array(
				'alt' => array(),
				'class' => array(),
				'src' => array(),
				'width' => array(),
			),
			'input' => array(
				'id' => array(),
				'class' => array(),
				'name' => array(),
				'type' => array(),
				'readonly' => array(),
				'value' => array(),
			),
			'label' => array(
				'class' => array(),
				'for' => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'meta' => array(
				'content' => array(),
				'property' => array(),
			),
			'textarea' => array(
				'class' => array(),
				'id' => array(),
				'help' => array(),
				'name' => array(),
			),
			'script' => array(
			),
			'small' => array(
				'class' => array(),
			),
			'strong' => array(
			),
			'style' => array(
			),
			'ul' => array(
				'class' => array(),
			),
		);
		echo wp_kses($builtMetas, $allowed_html);
	}
}
