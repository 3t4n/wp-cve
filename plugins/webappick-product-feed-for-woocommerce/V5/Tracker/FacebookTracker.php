<?php

namespace CTXFeed\V5\Tracker;

use CTXFeed\V5\Utility\Settings;

class FacebookTracker implements TrackerInterface {
	/**
	 * @var int
	 */
	private $trackingId;
	
	public function __construct() {
		$this->trackingId = Settings::get( 'pixel_id' );
		
		if ( $this->is_activated() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScript' ] );
			add_action( 'wp_head', [ $this, 'loadBaseScript' ] );
		}
		
		// Ajax adds to cart
		add_action( 'wp_ajax_add_to_cart_facebook_pixel', [ $this, 'ajax_add_to_cart_data' ] );
		add_action( 'wp_ajax_nopriv_add_to_cart_facebook_pixel', [ $this, 'ajax_add_to_cart_data' ] );
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
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments);
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
                s.parentNode.insertBefore(t, s);
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo $this->trackingId; ?>');
			<?php
			
			// Always trigger PageView Event.
			$this->PageView();
			
			// Trigger ViewContent event on Product Page.
			$this->ViewContent();
			
			// Trigger on Product Added to Cart.
			if ( isset( $_POST['add-to-cart'] ) ) {
				$addToCart = sanitize_text_field( $_POST['add-to-cart'] );
				$this->AddToCart( $addToCart );
			}
			
			// Trigger on Cart Page.
			$this->AddToCarts();
			
			// Trigger on Order Complete Page.
			$this->Purchase();
			?>

        </script>
		<?php
		
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
	 * Load PageView Event Script.
	 *
	 * @return void
	 */
	private function PageView() {
		?>
        fbq( 'track', 'PageView' );
		<?php
	}
	
	/**
	 * Load ViewContent Event Script.
	 *
	 * @return void
	 */
	public function ViewContent() {
		if ( is_product() ) {
			global $post;
			$_product = wc_get_product( $post->ID );
			
			$id  = $_product->get_ID();
			$ids = [ $id ];
			
			if ( "variable" === $_product->get_type() ) {
				$ids = $_product->get_children();
			}
			
			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
                fbq( 'track', 'ViewContent', <?php echo json_encode( $data ); ?> );
				<?php
			}
		}
	}
	
	/**
	 * Load AddToCart Event Script.
	 *
	 * @return void
	 */
	public function AddToCart( $addToCart ) {
		$product_id = $addToCart;
		$data       = $this->get_content_info( [ $product_id ] );
		if ( $data ) {
			?>
            fbq( 'track', 'AddToCart', <?php echo json_encode( $data ); ?> );
			<?php
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
			
			$data['content_ids']  = $ids;
			$data['content_type'] = 'product';
			$data['currency']     = get_woocommerce_currency();
			$data['value']        = WC()->cart->get_cart_contents_total();
			
			if ( $data ) {
				?>
                fbq( 'track', 'AddToCart', <?php echo json_encode( $data ); ?> );
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
		if ( is_wc_endpoint_url( 'order-received' ) ) {
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
                    fbq( 'track', 'Purchase', <?php echo json_encode( $data ); ?> );
                    fbq( 'track', 'CompleteRegistration', <?php echo json_encode( $data ); ?> );
					<?php
				}
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
		
		wp_send_json_success( json_encode($data) );
	}
	
}