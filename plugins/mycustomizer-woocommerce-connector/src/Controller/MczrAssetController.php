<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrAssetController {

	public function __construct() {
		$this->factory  = new MczrFactory();
		$this->twig     = $this->factory->getTwig();
		$this->settings = new MczrSettings();
	}

	public function init() {
		add_action( 'wp_head', array( $this, 'constants' ) );
	}

	public function constants() {
		$vars         = array();
		$vars['data'] = array(
			'brand'             => $this->settings->get( 'brand' ),
			'customizerBaseUrl' => str_replace( '{{brand}}', $this->settings->get( 'brand' ), MczrConfig::getInstance()['customizerUrlPattern'] ),
		);
		$this->settings->getAll();
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
		echo wp_kses( $this->twig->render( 'Assets/js/constants.js.twig', $vars ), $allowed_html );
	}
}
