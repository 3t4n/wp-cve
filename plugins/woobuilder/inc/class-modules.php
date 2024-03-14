<?php
class WooBuilder_Modules {

	public $class = 'WooBuilder';

	/** @var WooBuilder_Modules Instance */
	private static $_instance = null;

	/**
	 * Gets WooBuilder_Modules instance
	 * @return WooBuilder_Modules instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * PootlePB_Meta_Slider constructor.
	 */
	function __construct() {
		if ( class_exists( $this->class ) ) {
			// Adding modules to live editor sidebar
			add_action( 'pootlepb_modules', array( $this, 'module' ), 25 );
		}
	}

	public function module( $mods ) {

		$token = WooBuilder::$token;

		if ( ! WooBuilder::is_ppb_product() ) {
			return $mods;
		}

		$mods['ppb-product-details']       = array(
			'label'       => 'WC - Product details',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_details',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-add-to-cart']       = array(
			'label'       => 'WC - Add to Cart Button',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_a2c',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-short-description'] = array(
			'label'       => 'WC - Short Description',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_desc',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-tabs']              = array(
			'label'       => 'WC - Product tabs',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_tabs',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-related']           = array(
			'label'       => 'WC - Related products',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_related',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-images']           = array(
			'label'       => 'WC - Product images',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_images',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-rating']           = array(
			'label'       => 'WC - Product rating',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_rating',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-reviews']           = array(
			'label'       => 'WC - Product reviews',
			'icon_class'  => 'dashicons dashicons-cart',
			'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_reviews',
			'ActiveClass' => $this->class,
		);

		return $mods;
	}
}

WooBuilder_Modules::instance();