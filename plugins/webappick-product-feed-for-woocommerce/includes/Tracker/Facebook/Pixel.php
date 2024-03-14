<?php
/**
 * Managing Facebook pixel events
 *
 * @since 4.4.27
 */
namespace WebAppick\Feed\Tracker\Facebook;

use \WebAppick\Feed\Tracker\Base as Base;

/*
 * Class to specify facebook pixel events
 *
 * @since 4.4.34
 */
class Pixel extends Base {

	/**
	 * Initializes facebook pixel
	 *
	 * @return void
	 * @since 4.4.27
	 */
	public function __construct() {

		parent::__construct();

		if ( $this->is_active() ) {

			add_action( 'wp_head', array( &$this, 'add_pixel_script' ) );
			add_action( 'woo_feed_after_pixel_init', array( &$this, 'trigger_event' ), 11 );

		}

		// Ajax add to cart
		add_action( 'wp_ajax_add_to_cart_facebook_pixel', array( &$this, 'product_add_to_cart_data' ) );
		add_action( 'wp_ajax_nopriv_add_to_cart_facebook_pixel', array( &$this, 'product_add_to_cart_data' ) );

	}

	/**
	 * Checks if the corresponding tracker is active or not.
	 *
	 * @return bool
	 * @since 4.4.34
	 */
	protected function is_active() {

		$this->id     = $this->settings['pixel_id'];
		$this->active = $this->settings['disable_pixel'] == 'enable' && ! empty( $this->id );

		return $this->active;
	}

	/**
	 * Loads Facebook pixel assets
	 *
	 * @return void
	 * @since 4.4.27
	 */
	public function frontend_script() {

		wp_enqueue_script( 'woo-feed-facebook-pixel,', WOO_FEED_PLUGIN_URL . 'admin/js/woo-feed-facebook-pixel.min.js', array( 'jquery', 'wp-util' ), '1.0.0', true );

	}

	/**
	 * Adding initial pixel script on head
	 *
	 * @return void
	 * @since 4.4.27
	 */
	public function add_pixel_script() {

		// @ToDo Language Code check. Currently passing all to `en_US`
		?>
		<!-- Facebook Pixel Code -->
		<script>
			!function (f, b, e, v, n, t, s) {
				if (f.fbq) return;
				n = f.fbq = function () {
					n.callMethod ?
						n.callMethod.apply(n, arguments) : n.queue.push(arguments)
				};
				if (!f._fbq) f._fbq = n;
				n.push = n;
				n.loaded = !0;
				n.version = '2.0';
				n.queue = [];
				t = b.createElement(e);
				t.async = !0;
				t.src = v;
				s = b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t, s)
			}(window, document, 'script',
				'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo $this->id; ?>');
			<?php
			/**
			 * Action after pixel has been initialized in a page on page header. Every event should be fired in this hook
			 */
			do_action( 'woo_feed_after_pixel_init' );
			?>
		</script>
		<?php
	}

	/**
	 * Pageview event
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function page_view() {
		?>
		fbq( 'track', 'PageView' );
		<?php
	}

	/**
	 * ViewContent event
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function product_view() {
		global $post;
		$_product = wc_get_product( (int) $post->ID );

		$id  = $_product->get_ID();
		$ids = array( $id );

		if ( 'variable' === $_product->get_type() ) {
			$children_ids = $_product->get_children();
			$ids          = array_merge( $ids, $children_ids );
		}

		$data = array(
			'content_ids'  => $ids,
			'content_name' => esc_attr( $_product->get_title() ),
			'content_type' => 'product',
			'value'        => $_product->get_price(),
			'currency'     => get_option( 'woocommerce_currency' ),

		);
		?>
		fbq( 'track', 'ViewContent', <?php echo json_encode( $data ); ?> );
		<?php
	}

	/**
	 * Sends json product details on Ajax Add to cart button.
	 *
	 * @return void
	 * @since 4.4.27
	 */
	public function product_add_to_cart_data() {
		$data = array();

		$product_id = sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : '' );
		if ( ! empty( $product_id ) ) {
			$product_id = intval( esc_attr( $product_id ) );
			$_product   = wc_get_product( $product_id );

			$data = array(
				'content_id'   => $_product->get_ID(),
				'content_name' => esc_attr( $_product->get_title() ),
				'content_type' => 'product',
				'value'        => $_product->get_price(),
				'currency'     => get_option( 'woocommerce_currency' ),

			);

		}

		wp_send_json_success( json_encode( $data ) );
	}

	/**
	 * AddToCart event
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function add_to_cart() {

		$product_id = intval( esc_attr( $_POST['add-to-cart'] ) );
		if ( $product_id ) {
			$_product = wc_get_product( $product_id );

			$data = array(
				'content_id'   => $_product->get_ID(),
				'content_name' => esc_attr( $_product->get_title() ),
				'content_type' => 'product',
				'value'        => $_product->get_price(),
				'currency'     => get_option( 'woocommerce_currency' ),

			);
		}
		?>
		fbq( 'track', 'AddToCart', <?php echo json_encode( $data ); ?> );
		<?php

	}

	/**
	 * InitiateCheckout event
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function initiate_checkout() {
		?>
		fbq( 'track', 'InitiateCheckout' );
		<?php
	}

	/**
	 * Purchase event
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function order_received() {
		global $wp_query;
		$order = wc_get_order( $wp_query->query_vars['order-received'] );
		if ( $order ) {
			$data = array(
				'value'    => $order->get_total(),
				'currency' => $order->get_currency(),
			);
			?>
            fbq( 'track', 'Purchase', <?php echo json_encode( $data ); ?> );
            fbq( 'track', 'CompleteRegistration', <?php echo json_encode( $data ); ?> );
            <?php
		}
	}

	/**
	 * AddToCart event for CartList page
	 *
	 * @return void
	 * @since 4.4.27
	 */
	protected function add_to_cart_list() {
		$contents = array();
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = $cart_item['product_id'];
			$quantity   = $cart_item['quantity'];
			$contents[] = array(
				'id'       => $product_id,
				'quantity' => $quantity,
			);
		}
			$data = array(
				'contents'     => $contents,
				'content_type' => 'product',
				'value'        => WC()->cart->total,
				'currency'     => get_option( 'woocommerce_currency' ),
			);

			?>
		fbq( 'track', 'AddToCart', <?php echo json_encode( $data ); ?> );
		<?php

	}


}
