<?php

namespace MyCustomizer\WooCommerce\Connector\Types;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrProductTypeController;
use Symfony\Component\HttpFoundation\Request;

MczrAccess::isAuthorized();

class WC_Product_Mczr extends \WC_Product {

	const TYPE_NAME = 'mczr';

	private $request;
	private $twig;

	public function __construct( $product = null ) {
		parent::__construct( $product );
		$this->request      = Request::createFromGlobals();
		$this->product_type = self::TYPE_NAME;
		$this->factory      = new MczrFactory();
		$this->twig         = $this->factory->getTwig();
	}

	public function init() {
		// Register Tab and panel
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'addMczrTab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'addMczrTabContent' ) );
		// Retrieve used class
		add_filter( 'woocommerce_product_class', array( $this, 'mczrProductTypeClass' ), 10, 2 );
		// Process custom fields and save
		add_action( 'woocommerce_process_product_meta_' . self::TYPE_NAME, array( $this, 'processAndSaveTabMetaField' ) );
		// Additionnal Js for admin view
		add_filter( 'product_type_selector', array( $this, 'registerMczrType' ) );
	}

	public function mczrProductTypeClass( $classname, $product_type ) {
		if ( self::TYPE_NAME == $product_type ) {
			$classname = self::class; // 'MyCustomizer\WooCommerce\Connector\Types\WC_Product_Mczr';
		}
		return $classname;
	}

	public function registerMczrType( $types ) {
		$types['mczr'] = __( 'MyCustomizer Product' );
		add_filter( 'product_type_selector', array( $this, 'addMczrCustomizableTypeInProductTypeList' ) );
		return $types;
	}

	public function addMczrCustomizableTypeInProductTypeList( $types ) {
		$types[ $this->get_type() ] = __( 'MyCustomizer Product' );
		return $types;
	}

	public static function removeTabs( $sections ) {
		$sections['wcslider'] = __( 'WC Slider', 'text-domain' );
		return $sections;
	}

	public function get_type() {
		return self::TYPE_NAME;
	}

	public function addMczrTab( $tabs ) {
		$tabs[ self::TYPE_NAME ] = array(
			'label'  => __( 'MyCustomizer', 'woocommerce' ),
			'target' => self::TYPE_NAME . '_data',
			'class'  => array( 'show_if_' . self::TYPE_NAME ),
		);
		return $tabs;
	}

	public function processAndSaveTabMetaField( $post_id ) {
		$controller = new MczrProductTypeController();
		$controller->processAndSaveTabMetaField();
		return;
	}

	public function addMczrTabContent() {
		$productId = ( (int) $this->request->get( 'post' ) ); // TODO : filter this

		$vars = array(
			'mczrStartingPoint' => array(
				'value'   => \esc_attr( \get_post_meta( $productId, 'mczrStartingPoint', true ) ),
				'typeKey' => $this->get_type(),
			),
			'mczrQuantityMin'   => array(
				'value'   => \esc_attr( \get_post_meta( $productId, 'mczrQuantityMin', true ) ),
				'typeKey' => $this->get_type(),
			),
			'mczrQuantityMax'   => array(
				'value'   => \esc_attr( \get_post_meta( $productId, 'mczrQuantityMax', true ) ),
				'typeKey' => $this->get_type(),
			),
			'mczrQuantityStep'  => array(
				'value'   => \esc_attr( \get_post_meta( $productId, 'mczrQuantityStep', true ) ),
				'typeKey' => $this->get_type(),
			),
			'mczrQuantityStart' => array(
				'value'   => \esc_attr( \get_post_meta( $productId, 'mczrQuantityStart', true ) ),
				'typeKey' => $this->get_type(),
			),
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
				'id' => array(),
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
				'placeholder' => array(),
				'readonly' => array(),
				'type' => array(),
				'value' => array(),
			),
			'label' => array(
				'class' => array(),
				'for' => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'p' => array(
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
		echo wp_kses( $this->twig->render( 'Form/mczr-tab-fields.html.twig', $vars ), $allowed_html );
	}
}
