<?php

namespace CTXFeed\V5\Tracker;

use CTXFeed\V5\Utility\Settings;

class PinterestTracker implements TrackerInterface {
	private $trackingId;
	
	public function __construct() {
  
		$this->trackingId = Settings::get( 'pinterest_tag_id' );
		
		if ( $this->is_activated() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScript' ] );
			add_action( 'wp_head', [ $this, 'loadBaseScript' ] );
		}
		
		// Ajax adds to cart
		add_action( 'wp_ajax_add_to_cart_pinterest_tag', [ $this, 'ajax_add_to_cart_data' ] );
		add_action( 'wp_ajax_nopriv_add_to_cart_pinterest_tag', [ $this, 'ajax_add_to_cart_data' ] );
	}
	
	/**
	 * Is Pinterest Tracking Enabled.
	 *
	 * @return bool
	 */
	public function is_activated() {
		return ! empty( $this->trackingId ) && 'enable' === Settings::get( 'pinterest_conversion_tracking' );
	}
	
	/**
	 * Enqueue Ajax Add to Cart Event Code.
	 *
	 * @return void
	 */
	public function enqueueScript() {
		wp_enqueue_script( 'woo-feed-pinterest-tag,', WOO_FEED_PLUGIN_URL . 'admin/js/woo-feed-pinterest-tag.min.js', [
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
		$email        = ' ';
		$current_user = wp_get_current_user();
		if ( ! is_null( $current_user ) && isset( $current_user->user_email ) && ! empty( $current_user->user_email ) ) {
			$email = $current_user->user_email;
		}
		
		
		// @ToDo Language Code check. Currently passing all to `en_US`
		?>
        <!-- Pinterest Tag -->
        <script>
            !function (e) {
                if (!window.pintrk) {
                    window.pintrk = function () {
                        window.pintrk.queue.push(Array.prototype.slice.call(arguments));
                    };
                    var
                        n = window.pintrk;
                    n.queue = [], n.version = "3.0";
                    var
                        t = document.createElement("script");
                    t.async = !0, t.src = e;
                    var
                        r = document.getElementsByTagName("script")[0];
                    r.parentNode.insertBefore(t, r);
                }
            }("https://s.pinimg.com/ct/core.js");
            pintrk('load', <?php echo $this->trackingId; ?>, {em: <?php echo $email; ?>});
            pintrk('page');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none;" alt=""
                 src="https://ct.pinterest.com/v3/?event=init&tid=<?php echo $this->trackingId; ?>&pd[em]=<?php echo hash( 'sha256', $email ); ?>&noscript=1"/>
        </noscript>
        <!-- end Pinterest Tag -->
		<?php
		
		$this->PageView();
		$this->ViewContent();
		$this->AddToCart();
		$this->AddToCarts();
		$this->Purchase();
	}
	
	/**
	 * Get item info by Ids.
	 *
	 * @param array  $ids
	 * @param string $event
	 *
	 * @return array|false
	 */
	private function get_content_info( $ids = [], $event = '' ) {
		if ( ! empty( $ids ) ) {
			$data['currency'] = get_woocommerce_currency();
			
			if ( $event === 'checkout' ) {
				$data['order_quantity'] = '1';
			}
			
			$value = 0;
			foreach ( $ids as $id ) {
				$product = wc_get_product( $id );
				if ( ! is_object( $product ) ) {
					continue;
				}
				$data['line_items'][]['product_id'] = $product->get_id();
				$value                              += (int) $product->get_price();
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
		$url = $this->make_url( [], 'pagevisit' )
		?>
        <script>
            pintrk('track', 'pagevisit');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none;" alt=""
                 src="<?php echo $url; ?>"/>
        </noscript>
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
			$_product = wc_get_product( (int) $post->ID );
			
			$id  = $_product->get_ID();
			$ids = [ $id ];
			
			if ( "variable" === $_product->get_type() ) {
				$ids = array_merge( $ids, $_product->get_children() );
			}
			
			$data = $this->get_content_info( $ids );
			
			$url = $this->make_url( $data, 'pagevisit' );
			
			if ( $data ) {
				?>
                <script>
                    pintrk('track', 'pagevisit',<?php echo json_encode( $data ); ?>);
                </script>
                <noscript>
                    <img height="1" width="1" style="display:none;" alt=""
                         src="<?php echo $url; ?>"/>
                </noscript>
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
			$product_id = esc_attr( sanitize_text_field( $_POST['add-to-cart'] ) );
			$data       = $this->get_content_info( [ $product_id ], 'addtocart' );
			$url        = $this->make_url( $data, 'addtocart' );
			if ( $data ) {
				?>
                <script>
                    pintrk('track', 'addtocart', <?php echo json_encode( $data ); ?> );
                </script>
                <noscript>
                    <img height="1" width="1" style="display:none;" alt=""
                         src="<?php echo $url; ?>"/>
                </noscript>
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
			$data = $this->get_content_info( $ids, 'addtocart' );
			$url  = $this->make_url( $data, 'addtocart' );
			if ( $data ) {
				?>
                <script>
                    pintrk('track', 'addtocart', <?php echo json_encode( $data ); ?> );
                </script>
                <noscript>
                    <img height="1" width="1" style="display:none;" alt=""
                         src="<?php echo $url; ?>"/>
                </noscript>
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
				$data = $this->get_content_info( $ids, 'checkout' );
				$url  = $this->make_url( $data, 'checkout' );
				if ( $data ) {
					?>
                    <script>
                        pintrk('track', 'checkout', <?php echo json_encode( $data ); ?>);
                        pintrk('track', 'signup');
                    </script>
                    <noscript>
                        <img height="1" width="1" style="display:none;" alt=""
                             src="<?php echo $url; ?>"/>
                        <img height="1" width="1" style="display:none;" alt=""
                             src="https://ct.pinterest.com/v3/?tid=<?php echo $this->trackingId; ?>&event=signup&noscript=1"/>
                    </noscript>
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
		
		wp_send_json_success( json_encode( $data ) );
	}
	
	/**
	 * Make noscript args
	 *
	 * @param $args
	 * @param $event
	 *
	 * @return string
	 */
	private function make_url( $args, $event ) {
		$base_url  = "https://ct.pinterest.com/v3/";
		$base_args = [
			'tid'      => $this->trackingId,
			'event'    => $event,
			'noscript' => '1',
		];
		
		$newArgs = [];
		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $arg ) {
				$newArgs[ "ed[" . $key . "]" ] = $arg;
			}
		}
		
		$args = array_merge( $base_args, $newArgs );
		
		return add_query_arg( array_filter( $args ), $base_url );
	}
	
}