<?php

namespace CTXFeed\V5\Tracker;

use CTXFeed\V5\Utility\Settings;

class GoogleTracker implements TrackerInterface {
	
	private $trackingId;
	private $sendTo;
	
	public function __construct() {
        
        //TODO: Remarketing Settings not saving to DB.
        
		$this->trackingId = Settings::get( 'remarketing_id' );
		$this->sendTo = Settings::get( 'remarketing_label' );
  
		
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
		return ! empty( $this->trackingId ) && ( 'enable' === Settings::get( 'disable_remarketing' ) );
	}
	
	/**
	 * Enqueue Ajax Add to Cart Event Code.
	 *
	 * @return void
	 */
	public function enqueueScript() {
		wp_enqueue_script( 'woo-feed-google-remarketing,', WOO_FEED_PLUGIN_URL . 'admin/js/woo-feed-google-remarketing.min.js', [
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

        <!-- Global site tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->trackingId; ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '<?php echo $this->trackingId; ?>');
			
			<?php
			// Always trigger PageView Event.
			//			$this->PageView();
			
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
			
			$data['send_to']             = $this->sendTo;
			$data['aw_remarketing_only'] = true;
			$value                       = 0;
			foreach ( $ids as $id ) {
				$product = wc_get_product( $id );
				if ( ! is_object( $product ) ) {
					continue;
				}
				$data['items'][]['id'] = $product->get_id();
				$value                 += (int) $product->get_price();
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
			$_product = wc_get_product( $post->ID );
			
			$id  = $_product->get_ID();
			$ids = [ $id ];
			
			if ( "variable" === $_product->get_type() ) {
				$ids = $_product->get_children();
			}
			
			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
                gtag( 'event', 'view_item', <?php echo json_encode( $data ); ?> );
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
            gtag( 'event', 'add_to_cart', <?php echo json_encode( $data ); ?> );
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
			$data = $this->get_content_info( $ids );
			if ( $data ) {
				?>
                gtag( 'event', 'add_to_cart', <?php echo json_encode( $data ); ?> );
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
                gtag( 'event', 'purchase', <?php echo json_encode( $data ); ?> );
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