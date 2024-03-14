<?php
/**
 * Base class for tracking WooCommerce
 *
 * @since 4.4.34
 */
namespace WebAppick\Feed\Tracker;

abstract Class Base{

    /**
     * If the current instance is active or not.
     * @var bool $active
     *
     * @since 4.4.34
     */
    protected $active = false;

    /**
     * Corresponding ID of the tracker
     * @var string $id
     *
     * @since 4.4.34
     */
    protected $id = '';

    /*
     * Settings store
     * @var array $settings
     */
    protected $settings;

    /*
     * Common tracker initialization
     *
     * @return void
     * @since 4.4.34
     */
    public function __construct(){

        // Loading settings
        $this->settings = woo_feed_get_options( 'all' );

        if ( $this->is_active() ) {

            add_action( 'wp_enqueue_scripts', [ &$this, 'frontend_script' ] );

        }

    }

    /**
     * Decides which event to trigger
     *
     * @return void
     * @since 4.4.34
     */
    public function trigger_event() {

        $event = 'page_view';

        if ( is_product() && isset($_POST['add-to-cart']) ) {
            $event = 'product_view';
			$add_to_cart = sanitize_text_field( $_POST['add-to-cart'] );
            if ( ! empty($add_to_cart) ) {
                $event = 'add_to_cart';
            }
        } elseif ( is_wc_endpoint_url('order-received') ) {
            $event = 'order_received';
        }
		elseif (is_cart()){
			$event = 'add_to_cart_list';
		}
		elseif ( is_checkout() ) {
            $event = 'initiate_checkout';
        }

        if ( method_exists( get_class($this), $event ) ) {
            $this->$event();
        }

    }

}