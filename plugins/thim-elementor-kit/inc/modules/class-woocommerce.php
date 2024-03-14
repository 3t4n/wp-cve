<?php
namespace Thim_EL_Kit\Modules;

use Thim_EL_Kit\SingletonTrait;

class WooCommerce {
	use SingletonTrait;

	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );

		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === sanitize_text_field( $_REQUEST['action'] ) && is_admin() ) {
			add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
		}

		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );
	}

	/** Load WC_Cart in Elementor Editor */
	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/** Include WC Hook in Editor */
	public function register_wc_hooks() {
		if ( function_exists( 'WC' ) ) {
			WC()->frontend_includes();
		}
	}

	public function enqueue_scripts() {
		$id = get_the_ID();

		if ( ! empty( $id ) && ( \Elementor\Plugin::instance()->preview->is_preview_mode( $id ) || is_preview() || isset( $_GET['thim_elementor_kit'] ) ) ) {
			global $product;

			$theme = wp_get_theme();

			if ( is_child_theme() ) {
				$theme = wp_get_theme( $theme->parent()->template );
			}

			if ( is_singular( 'product' ) ) {
				$product = wc_get_product();
			}

			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}

			if ( current_theme_supports( 'wc-product-gallery-slider' ) || $theme->get( 'TextDomain' ) === 'eduma' ) {
				wp_enqueue_script( 'flexslider' );
			}

			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				add_action( 'wp_footer', 'woocommerce_photoswipe' );
			}
			wp_enqueue_script( 'wc-single-product' );

			wp_enqueue_style( 'photoswipe' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_style( 'woocommerce_prettyPhoto_css' );
		}
	}

	public function add_to_cart_fragments( $fragments ) {
		ob_start();
		$this->render_cart_subtotal();
		$subtotal = ob_get_clean();

		if ( ! empty( $subtotal ) ) {
			$fragments['.thim-ekits-mini-cart span.cart-items-number'] = $subtotal;
		}

		return $fragments;
	}

	public function render_cart_subtotal() {
		?>
		<span class="cart-items-number"><?php echo esc_html( is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
		<?php
	}
}

WooCommerce::instance();
