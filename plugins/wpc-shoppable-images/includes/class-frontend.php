<?php
defined( 'ABSPATH' ) || exit;

class WPCleverWpcsi_Frontend {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_shortcode( 'wpcsi_shoppable_image', [ $this, 'shortcode' ] );

		// shortcodes
		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );
		add_shortcode( 'wpcsi_cart', [ $this, 'shortcode_cart' ] );
		add_shortcode( 'wpcsi_search', [ $this, 'shortcode_search' ] );
	}

	public function shortcode( $attrs ) {
		$attrs = shortcode_atts( [
			'id' => 0,
		], $attrs, 'wpcsi_shoppable_image' );

		extract( $attrs );
		ob_start();
		self::render( $id );

		return ob_get_clean();
	}

	function cart_fragments( $fragments ) {
		$fragments['.wpcsi-cart'] = self::get_cart();

		return $fragments;
	}

	function get_cart() {
		if ( ! isset( WC()->cart ) ) {
			return '';
		}

		$count    = WC()->cart->get_cart_contents_count();
		$subtotal = WC()->cart->get_cart_subtotal();
		$cart     = '<span class="wpcsi-cart" data-count="' . esc_attr( $count ) . '" data-subtotal="' . esc_attr( $subtotal ) . '"><a href="' . wc_get_cart_url() . '" class="woofc-cart"><span class="wpcsi-cart-subtotal">' . $subtotal . '</span> <span class="wpcsi-cart-count">' . sprintf( _n( '(%s item)', '(%s items)', $count, 'wpc-shoppable-images' ), number_format_i18n( $count ) ) . '</span></a></span>';

		return apply_filters( 'wpcsi_cart', $cart, $count, $subtotal );
	}

	function shortcode_cart() {
		return apply_filters( 'wpcsi_shortcode_cart', self::get_cart() );
	}

	function shortcode_search() {
		$search = '<div class="wpcsi-product-search">';
		$search .= '<form role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">';
		$search .= '<input type="search" placeholder="' . esc_attr__( 'Search products&hellip;', 'wpc-shoppable-images' ) . '" value="' . get_search_query() . '" name="s" />';
		$search .= '<button type="submit" value="' . esc_attr_x( 'Search', 'submit button', 'wpc-shoppable-images' ) . '">' . esc_html_x( 'Search', 'submit button', 'wpc-shoppable-images' ) . '</button>';
		$search .= '<input type="hidden" name="post_type" value="product" />';
		$search .= '</form>';
		$search .= '</div>';

		return apply_filters( 'wpcsi_shortcode_search', $search );
	}

	public function render( $post_id ) {
		if ( ! $post_id || ! get_post_status( $post_id ) ) {
			return '';
		}

		$items = get_post_meta( $post_id, 'wpcsi-items', true );

		if ( ! $items ) {
			$items = '[]';
		}

		$tags = json_decode( $items, true );

		echo '<div class="wpcsi-shoppable-image-wrapper"><div class="wpcsi-shoppable-image">';
		echo get_the_post_thumbnail( $post_id, 'full' );

		foreach ( $tags as $tag ) {
			if ( ! isset( $tag['settings'] ) ) {
				continue;
			}

			$settings = self::get_settings( $tag['settings'] );
			echo '<span class="wpcsi-tag wpcsi-trigger-' . esc_attr( $settings['trigger'] ) . ' ' . ( ! empty( $settings['label'] ) ? 'has-label ' . ( ! empty( $settings['lpos'] ) ? 'label-' . $settings['lpos'] : '' ) : '' ) . '" style="top:' . esc_attr( $tag['position']['top'] ) . '%;left:' . esc_attr( $tag['position']['left'] ) . '%" data-label="' . esc_attr( ! empty( $settings['label'] ) ? $settings['label'] : '' ) . '">+</span>';
			echo '<div class="wpcsi-popup wpcsi-trigger-' . esc_attr( $settings['trigger'] ) . ' ' . esc_attr( $settings['position'] ) . '" style="top:' . esc_attr( $tag['position']['top'] ) . '%;left:' . esc_attr( $tag['position']['left'] ) . '%">';
			self::render_popup( $settings );
			echo '</div>';
		}

		echo '</div></div>';
	}

	private function render_popup( $settings ) {
		if ( $settings['content'] === 'text' ) {
			// text
			echo '<div class="wpcsi-text">' . do_shortcode( $settings['text'] ) . '</div>';
		} else {
			// product
			if ( ! is_array( $settings['products'] ) || count( $settings['products'] ) <= 0 ) {
				return;
			}

			$products = wc_get_products( [ 'include' => $settings['products'] ] );

			if ( is_array( $products ) && ! empty( $products ) ) {
				echo '<ul class="wpcsi-product-list' . ( $settings['carousel'] === 'yes' ? ' style-carousel' : ' style-list' ) . ' image-' . esc_attr( $settings['image'] ) . '">';

				foreach ( $products as $product ) {
					echo '<li><div class="product-inner">';

					if ( $settings['image'] !== 'hide' ) {
						if ( $settings['link'] !== 'no' ) {
							echo '<div class="product-image"><a ' . ( $settings['link'] === 'quickview' ? 'class="woosq-link" data-id="' . $product->get_id() . '" data-context="wpcsi"' : '' ) . ' href="' . esc_url( $product->get_permalink() ) . '" ' . ( $settings['link'] === 'new' ? 'target="_blank"' : '' ) . '>' . wp_kses_post( $product->get_image() ) . '</a></div>';
						} else {
							echo '<div class="product-image">' . wp_kses_post( $product->get_image() ) . '</div>';
						}
					}

					echo '<div class="product-info">';

					if ( $settings['link'] !== 'no' ) {
						echo '<div class="product-name"><a ' . ( $settings['link'] === 'quickview' ? 'class="woosq-link" data-id="' . $product->get_id() . '" data-context="wpcsi"' : '' ) . ' href="' . esc_url( $product->get_permalink() ) . '" ' . ( $settings['link'] === 'new' ? 'target="_blank"' : '' ) . '>' . wp_kses_post( $product->get_name() ) . '</a></div>';
					} else {
						echo '<div class="product-name">' . wp_kses_post( $product->get_name() ) . '</div>';
					}

					if ( $settings['price'] === 'yes' ) {
						echo '<div class="product-price">' . wp_kses_post( $product->get_price_html() ) . '</div>';
					}

					if ( $settings['cart'] === 'yes' ) {
						echo '<div class="product-atc">' . do_shortcode( '[add_to_cart style="" show_price="false" id="' . esc_attr( $product->get_id() ) . '"]' ) . '</div>';
					}

					echo '</div><!-- /product-info -->';
					echo '</div></li>';
				}

				echo '</ul>';
			}
		}
	}

	private function get_settings( $settings ) {
		return wp_parse_args( $settings, [
			'content'  => 'products',
			'products' => [],
			'text'     => '',
			'position' => 'top-center',
			'carousel' => 'no',
			'cart'     => 'no',
			'price'    => 'yes',
			'trigger'  => 'click',
			'image'    => 'left',
			'link'     => 'same',
		] );
	}

	public function scripts() {
		wp_enqueue_style( 'slick', WPCSI_URI . 'assets/slick/slick.css' );
		wp_enqueue_script( 'slick', WPCSI_URI . 'assets/slick/slick.min.js', [ 'jquery' ], WPCSI_VERSION, true );
		wp_enqueue_style( 'wpcsi-frontend', WPCSI_URI . 'assets/css/frontend.css', [], WPCSI_VERSION );
		wp_enqueue_script( 'wpcsi-frontend', WPCSI_URI . 'assets/js/frontend.js', [ 'jquery', ], WPCSI_VERSION, true );
	}
}

return WPCleverWpcsi_Frontend::instance();
