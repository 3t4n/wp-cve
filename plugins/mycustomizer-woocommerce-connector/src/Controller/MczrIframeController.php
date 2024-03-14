<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use Symfony\Component\HttpFoundation\Request;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrProduct;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrIframeController {

	public function __construct() {
		$this->request        = Request::createFromGlobals();
		$this->factory        = new MczrFactory();
		$this->twig           = $this->factory->getTwig();
		$this->settings       = new MczrSettings();
		$this->productToolkit = new MczrProduct();
		$this->customizerDisplayed = false;
		$this->productId = null;
		$this->iFrameHook = null;
		$this->iFrameHookPriority = 0;
	}

	public function init() {
		$this->iFrameHook = $this->settings->get( 'iframeHook' );
		$this->iFrameHookPriority = 0;
		try {
			$this->iFrameHookPriority = $this->settings->get( 'iframeHookPriority' );
		} catch (Exception $e) {
		}
		add_action( $this->iFrameHook, array( $this, 'displayAction' ), $this->iFrameHookPriority );
		add_action( 'mczrIframe', array( $this, 'displayAction' ), $this->iFrameHookPriority );
		if ($this->iFrameHook != 'woocommerce_before_main_content'
			&& $this->iFrameHook != 'woocommerce_after_main_content') {
			add_action( 'woocommerce_before_main_content', array( $this, 'displayFallbackAction' ), $this->iFrameHookPriority );
			add_action( 'woocommerce_after_main_content', array( $this, 'displayAction' ), $this->iFrameHookPriority );
		}
	}

	public function displayFallbackAction() {
		$product   = \wc_get_product();
		if ( 'boolean' == gettype( $product ) ) {
			return;
		}

		if ( ! $product->is_type( 'mczr' ) ) {
			return;
		}

		if (is_archive()
			|| is_search()) {
			return;
		}

		$this->productId = $product->get_id();

		$allowed_html = array (
			'div' => array(
				'id' => array(),
			)
		);
		echo wp_kses( $this->twig->render( 'Iframe/displayFallback.html.twig' ), $allowed_html );
	}

	public function displayAction() {
		if ($this->customizerDisplayed) {
			return;
		}
		$product   = \wc_get_product();
		if ( 'boolean' == gettype( $product ) ) {
			return;
		}

		if ( ! $product->is_type( 'mczr' ) ) {
			return;
		}

		if (is_archive()
			|| is_search()) {
			return;
		}

		// Check for the mandatory mczrShopId option
		if ( ! $this->settings->get( 'shopId' ) ) {
			throw new \Exception( 'Shop id is not defined. Please, connect the shop in MyCustomizer admin prior to access this page.' );
		}

		$productId          = ( $product->get_id() );
		$settings           = $this->settings->getAll();
		$baseCustomizerUrl  = str_replace( '{{brand}}', $settings['brand'], MczrConfig::getInstance()['customizerUrlPattern'] );
		$defaultProductName = get_post_meta( $productId, 'mczrStartingPoint', true );
		$designId           = $this->request->query->get( 'designId' );
		$vars               = array();
		$vars['settings']   = $settings;
		$lang               = substr( get_bloginfo( 'language' ), 0, 2 );
		$vars['iframeSrc']  = "{$baseCustomizerUrl}/customize/startingpoint/$defaultProductName?shopid={$settings['shopId']}&lang=$lang";
		if ( $designId ) {
			$vars['iframeSrc'] = "{$baseCustomizerUrl}/customize/design/$designId?shopid={$settings['shopId']}&lang=$lang";
		}
		$vars['iframeSrc'] .= '&storeProductUrl=' . $this->productToolkit->buildProductUrl( $productId );
		$vars['product']    = array(
			'id' => $product->get_id(),
		);
		$allowed_html = array(
			'iframe' => array(
				'id' => array(),
				'class' => array(),
				'data-product-id' => array(),
				'height' => array(),
				'frameBorder' => array(),
				'width' => array(),
				'src' => array(),
				'allow' => array(),
			),
			'script' => array(
			),
			'style' => array(
			),
		);
		if (current_filter() === 'woocommerce_after_main_content'
			&& !$this->customizerDisplayed
			&& $this->productId == $product->get_id()) {
			echo wp_kses( $this->twig->render( 'Iframe/displayScript.html.twig', $vars ), $allowed_html );
		} else if (!$this->customizerDisplayed
			&& current_filter() === $this->iFrameHook) {
			$this->customizerDisplayed = true;
			echo wp_kses( $this->twig->render( 'Iframe/display.html.twig', $vars ), $allowed_html );
		}
		return;
	}
}
