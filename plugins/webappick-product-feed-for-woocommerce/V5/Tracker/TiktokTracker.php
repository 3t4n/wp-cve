<?php
namespace CTXFeed\V5\Tracker;
use CTXFeed\V5\Utility\Settings;

class TiktokTracker implements TrackerInterface {
	private $trackingId;

	public function __construct() {
		$this->trackingId = Settings::get( 'pixel_id' );

		if ( $this->is_activated() ) {
//			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueueScript' ] );
//			add_action( 'wp_head', [ &$this, 'loadBaseScript' ] );
//			add_action( 'ctx_after_pixel_init', [ &$this, 'trigger_event' ], 11 );

		}

		// Ajax adds to cart
		add_action( 'wp_ajax_add_to_cart_facebook_pixel', [ &$this, 'ajax_add_to_cart_data' ] );
		add_action( 'wp_ajax_nopriv_add_to_cart_facebook_pixel', [ &$this, 'ajax_add_to_cart_data' ] );
	}

	/**
	 * Is Facebook Pixel Tracking Enabled.
	 *
	 * @return bool
	 */
	public function is_activated() {
		return ! empty( $this->trackingId ) && 'enable' === Settings::get( 'disable_pixel' );
	}

	/**
	 * Enqueue Ajax Add to Cart Event Code.
	 *
	 * @return void
	 */
	public function enqueueScript() {
		wp_enqueue_script( 'woo-feed-facebook-pixel,', WOO_FEED_PLUGIN_URL . 'admin/js/woo-feed-facebook-pixel.min.js', [
			'jquery',
			'wp-util'
		], '1.0.0', true );
	}

	/**
	 * Load Base Script.
	 *
	 * @return void
	 */
	public function loadBaseScript() {
		// @ToDo Language Code check. Currently passing all to `en_US`
		?>
		<script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
                ttq.load('<?php echo $this->trackingId; ?>');
                ttq.page();
            }(window, document, 'ttq');
			<?php
			/**
			 * Action after pixel has been initialized in a page on page header. Every event should be fired in this hook
			 */
			do_action( 'ctx_after_pixel_init' );
			?>
		</script>
		<?php
	}

	/**
	 * Trigger Events.
	 *
	 * @return void
	 */
	public function triggerEvents() {


		// Trigger ViewContent event on Product Page.
		if ( is_product() ) {
			$this->ViewContent();
		}

		// Trigger AddToCart event on Add to Cart by form post.
		if ( isset( $_POST['add-to-cart'] ) ) {
			$this->AddToCart();
		}

		// Trigger on Cart Page.
		if ( is_cart() ) {
			$this->AddToCarts();
		}

		if ( is_wc_endpoint_url( 'order-received' ) ) {
			$this->Purchase();
		}
	}

	/**
	 * Get item info by Ids.
	 *
	 * @param $ids
	 *
	 * @return array|false
	 */
	private function get_content_info( $ids = [] ) {
		if ( ! empty( $ids ) ) {
			$data['content_ids']  = $ids;
			$data['content_type'] = 'product';
			$data['currency']     = get_woocommerce_currency();

			$value = 0;
			foreach ( $ids as $id ) {
				$product = wc_get_product( $id );
				if ( ! is_object( $product ) ) {
					continue;
				}
				$value += (int) $product->get_price();
			}

			$data['value'] = $value;

			return $data;
		}

		return false;
	}

	/**
	 * Load ViewContent Event Script.
	 *
	 * @return void
	 */
	public function ViewContent() {
		if ( is_product() ) {
			global $post;
			$_product = wc_get_product( (int) $post->ID );

			$id  = $_product->get_ID();
			$ids = [ $id ];

			if ( "variable" === $_product->get_type() ) {
				$ids = $_product->get_children();
			}

			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
				ttq.track('ViewContent', <?php echo json_encode( $data ); ?> );
				<?php
			}
		}
	}

	/**
	 * Load AddToCart Event Script.
	 *
	 * @return void
	 */
	public function AddToCart() {
		if ( isset( $_POST['add-to-cart'] ) ) {
			$product_id = (int) esc_attr( $_POST['add-to-cart'] );
			$data       = $this->get_content_info( [ $product_id ] );
			if ( $data ) {
				?>
				ttq.track('AddToCart', <?php echo json_encode( $data ); ?> );
				<?php
			}
		}
	}

	/**
	 * Load AddToCart Event Script.
	 *
	 * @return void
	 */
	public function AddToCarts() {
		if ( is_cart() && ! WC()->cart->is_empty() ) {
			$ids = [];
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$ids[] = $cart_item['product_id'];
			}
			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
				ttq.track('AddToCart', <?php echo json_encode( $data ); ?> );
				<?php
			}
		}
	}

	/**
	 * Load Purchase Event Script.
	 *
	 * @return void
	 */
	public function Purchase() {
		global $wp_query;
		if ( isset( $wp_query->query_vars['order-received'] ) ) {
			$order = wc_get_order( $wp_query->query_vars['order-received'] );
			$ids   = [];
			foreach ( $order->get_items() as $item ) {
				$ids[] = $item->get_product_id();
			}
			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
				ttq.track('PlaceAnOrder', <?php echo json_encode( $data ); ?> );
				ttq.track('InitiateCheckout')
				ttq.track('CompleteRegistration');
				ttq.track('CompletePayment', <?php echo json_encode( $data ); ?> );
				<?php
			}
		}
	}

	/**
	 * Sends json product details on Ajax Add to cart button.
	 *
	 * @return void
	 * @since 4.4.27
	 */
	public function ajax_add_to_cart_data() {
		$data = [];

		$product_id = sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : '' );
		if ( ! empty( $product_id ) ) {
			$data = $this->get_content_info( [ $product_id ] );
		}

		wp_send_json_success( json_encode( $data ) );
	}

}