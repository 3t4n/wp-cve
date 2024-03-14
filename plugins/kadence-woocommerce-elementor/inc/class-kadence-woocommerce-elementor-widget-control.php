<?php
/**
 * Class Kadence_Woocommerce_Elementor_Widget_Control
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Kadence_Woocommerce_Elementor_Widget_Control
 *
 * @category class.
 */
class Kadence_Woocommerce_Elementor_Widget_Control {

	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Constructor
	 */
	public function __construct() {
		// Add widgets.
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
		// add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ), 1 );.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'support_scripts' ) );

	}
	/**
	 * Support scripts.
	 */
	public function support_scripts() {
		if ( Kadence_Woocommerce_Elementor::$elementor_instance->preview->is_preview_mode() ) {
			wp_enqueue_script( 'kt-woo-ele-gallery', KT_WOOELE_URL . 'assets/js/kadence-woocommerce-elementor-gallery.js', array( 'jquery' ), KT_WOOELE_VERSION, true );
		}
	}

	/**
	 * Add category for product widgets.
	 *
	 * @param object $elements_manager the category manager.
	 */
	public function add_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'product-elements',
			array(
				'title' => __( 'Single Product Elements', 'kadence-woocommerce-elementor' ),
				'icon'  => 'eicon-woocommerce',
			),
			1
		);
	}
	/**
	 * Add category for product widgets.
	 */
	public function widgets_registered() {
		$post_type = get_post_type();
		if ( 'product' == $post_type || 'ele-product-template' == $post_type || 'elementor_library' == $post_type || empty( $post_type ) ) {
			$kadence_wooele_elements = array(
				'product-breadcrumbs',
				'product-title',
				'product-gallery',
				'product-price',
				'product-rating',
				'product-single-category',
				'product-short-description',
				'product-add-to-cart',
				'product-meta',
				'product-social',
				'product-tabs',
				'product-description',
				'product-additional-information',
				'product-reviews',
				'product-related',
				'product-upsell',
				'product-navigation-arrows',
			);
			if ( class_exists( 'Kadence_Related_Content' ) ) {
				$kadence_wooele_elements[] = 'kadence-product-related';
			}
			foreach ( $kadence_wooele_elements as $element_name ) {
				require_once KT_WOOELE_PATH . 'inc/elementor-widgets/' . $element_name . '.php';
			}
		}
	}

}
Kadence_Woocommerce_Elementor_Widget_Control::get_instance();
