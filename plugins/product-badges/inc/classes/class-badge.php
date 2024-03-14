<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Lion_Badge class is used for displaying the badge
 */
class Lion_Badge extends Lion_Badges {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'badge_output' ) );
		add_action( 'woocommerce_product_thumbnails', array( $this, 'badge_output' ) );

		add_filter( 'woocommerce_sale_flash', array( $this, 'woocommerce_sale_flash' ), 10, 2 );
	}

	/**
	 * Enqueues front-end styles
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'lion-badges', LION_BADGES_URL . '/assets/css/badge.css' );
		wp_enqueue_script( 'lion-badges', LION_BADGES_URL . '/assets/js/badge.js', array( 'jquery' ) );

		$inline_css = $this->build_badge_css_classes();
		wp_add_inline_style( 'lion-badges', $inline_css );
	}

	/**
	 * Builds CSS classes
	 */
	protected function build_badge_css_classes() {
		$badges = get_posts( array(
	    	'post_type'      => 'lion_badge',
	    	'posts_per_page' => -1,
	    	'post_status'    => 'publish'
		) );

		$css = '';
		if ( $badges ) {
			foreach( $badges as $badge ) {
				$shape_css = Lion_Badge_Style::get_badge_shape_css( $badge->ID );
				$text_css = Lion_Badge_Style::get_badge_text_css( $badge->ID );

				$shape_inline_css = implode( ' ', $shape_css );
				$text_inline_css = implode( ' ', $text_css );

				$css .= '.lion-badge-shape-' . $badge->ID . ' {' . esc_attr( $shape_inline_css ) . '}' . "\r\n";
				$css .= '.lion-badge-text-' . $badge->ID . ' {' . esc_attr( $text_inline_css ) . '}';
			}
		}

		// Compatibility
		$css .= Lion_Badge_Compatibility::get_compatibility_css();

		return $css;
	}

	/**
	 * Get all badges
	 */
	private function get_all_badges() {
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'lion_badge',
			'post_status' => 'publish'
		);

		return get_posts( $args );
	}

	/**
	 * Badge template output
	 */
	public function badge_output() {
		global $product;

		if ( $badges = $this->get_all_badges() ) {
			foreach( $badges as $badge ) {

				if ( $this->display_badge( $badge->ID, $product ) ) {

					$badge_shape = Lion_Badge_Style::get_badge_shape( $badge->ID );
					$badge_text = Lion_Badge_Style::get_badge_text( $badge->ID );

					include LION_BADGES_PATH . '/templates/badge.php';
				}
			}
		}

	}

	/**
	 * Whether display badge or not conditional.
	 * 
	 * @param int $badge_id 
	 * @param object $product 
	 * @return bool
	 */
	public function display_badge( $badge_id, $product ) {
		$display_for_all_on_sale = get_post_meta( $badge_id, '_badge_products_display_for_all_sale_products', true );

		if ( $display_for_all_on_sale && $product->is_on_sale() ){
			return true;
		}

		$products = maybe_unserialize( get_post_meta( $badge_id, '_badge_products_select', true ) );

		if ( $products ) {
			foreach ( $products as $key => $product_id ) {
				if ( $product_id == $product->get_id() ) {
					return true;
				}
			}
		}

		$categories = maybe_unserialize( get_post_meta( $badge_id, '_badge_categories_select', true ) );

		if ( $categories ) {
			$wc_product_categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) );

			foreach ( $categories as $key => $category_id ) {
				if ( in_array( $category_id, $wc_product_categories) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Handle default WooCommerce sale text
	 * 
	 * @param string $sale_text 
	 * @param object $product 
	 * @return string
	 */
	public function woocommerce_sale_flash( $sale_text, $product ) {
		$option = get_option( 'lion_badges ');

		if ( $option['hide_default_wc_badge'] == 1 )
			return '';
		
		return $sale_text;
	}
}

new Lion_Badge();
