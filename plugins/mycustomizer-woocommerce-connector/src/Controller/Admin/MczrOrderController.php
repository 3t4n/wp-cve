<?php

namespace MyCustomizer\WooCommerce\Connector\Controller\Admin;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;

MczrAccess::isAuthorized();

class MczrOrderController {

	public function __construct() {
		$this->factory = MczrFactory::getInstance();
		$this->twig    = $this->factory->getTwig();
	}

	public function init() {
		add_action( 'woocommerce_before_order_itemmeta', array( $this, 'addMczrMetasAfterAdminOrderItemsTitle' ), 10, 3 );
	}

	public function addMczrMetasAfterAdminOrderItemsTitle( $item_id, $item, $_product ) {
		$vars = array(
			'props' => wc_get_order_item_meta( $item_id, 'mczr', true ),
		);
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
		echo wp_kses( $this->twig->render( 'Admin/order-properties.html.twig', $vars ), $allowed_html );
	}
}
