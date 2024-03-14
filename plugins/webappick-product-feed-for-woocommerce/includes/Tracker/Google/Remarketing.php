<?php
/**
 * Managing Google remarketing events
 *
 * @since 4.4.34
 */

namespace WebAppick\Feed\Tracker\Google;

use \WebAppick\Feed\Tracker\Base as Base;

/*
 * Class to specify google remarketing events
 *
 * @since 4.4.34
 */
class Remarketing extends Base{

    /*
     * Conversion lable for google remarketing
     * @var string $send_to
     *
     * @since 4.4.34
     */
    public $send_to;

    /*
     * Initializes google remarketing
     *
     * @return void
     * @since 4.4.34
     */
    public function __construct(){
        parent::__construct();

        if ( $this->is_active() ) {

            add_action( 'wp_head', [ &$this, 'add_remarketing_script' ] );
            add_action( 'woo_feed_after_remarketing_init', [ &$this, 'trigger_event' ], 11 );

        }

        // Ajax add to cart
        add_action( 'wp_ajax_add_to_cart_google_remarketing', [ &$this, 'product_add_to_cart_data' ] );
        add_action( 'wp_ajax_nopriv_add_to_cart_google_remarketing', [ &$this, 'product_add_to_cart_data' ] );
    }

    /*
     * Checks if Google remarketing's necessary data is available.
     * @param void
     *
     * @return void
     * @since 4.4.34
     */
    protected function is_active(){

        $this->id = $this->settings['remarketing_id'];
        $this->send_to = $this->settings['remarketing_label'];
        $this->active = $this->settings['disable_remarketing'] == 'enable' && ! empty( $this->id ) && ! empty( $this->send_to );

        return $this->active;

    }

    /*
     * Add necessary scripts to initialize Google Remarketing
     * @param void
     *
     * @return void
     * @since 4.4.34
     */
    public function add_remarketing_script(){

        ?>

        <!-- Global site tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-951598697"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo $this->id; ?>');

            <?php
                /**
                 * Action after remarketing has been initialized in a page on page header. Every event should be fired in this hook
                 */
                do_action( 'woo_feed_after_remarketing_init' );
            ?>

        </script>

        <?php

    }

    /**
     * Loads Google Remarketing assets
     *
     * @return void
     * @since 4.4.34
     */
    public function frontend_script(){

        wp_enqueue_script( 'woo-feed-google-remarketing,', WOO_FEED_PLUGIN_URL . 'admin/js/woo-feed-google-remarketing.min.js', [ 'jquery', 'wp-util' ], '1.0.0', true );

    }

    /**
     * Item View event
     *
     * @return void
     * @since 4.4.34
     */
    protected function product_view(){
        global $post;
        $_product = wc_get_product( (int) $post->ID );
        $data = [
            'send_to' => $this->send_to,
            'value'   => $_product->get_price(),
            'items'   => [
                [
                    'id' => $_product->get_ID(),
                ],
            ],
        ];
        ?>
        gtag( 'event', 'view_item', <?php echo json_encode( $data ); ?> );
        <?php
    }

    /**
     * AddToCart event
     *
     * @return void
     * @since 4.4.34
     */
    protected function add_to_cart(){

        $product_id = intval( esc_attr( $_POST['add-to-cart'] ) );
        if ( $product_id ) {
            $_product = wc_get_product( $product_id );

            $data = [
                'send_to' => $this->send_to,
                'value'   => $_product->get_price(),
                'items'   => [
                    [
                        'id' => $_product->get_ID(),
                    ],
                ],
            ];
        }
        ?>
        gtag( 'event', 'add_to_cart', <?php echo json_encode($data); ?> );
        <?php

    }

    /**
     * Sends json product details on Ajax Add to cart button.
     *
     * @return void
     * @since 4.4.34
     */
    public function product_add_to_cart_data(){

        $product_id = intval( esc_attr( $_POST['product_id'] ) );
        $_product = wc_get_product( $product_id );

        $data = [
            'send_to' => $this->send_to,
            'value'   => $_product->get_price(),
            'items'   => [
                [
                    'id' => $_product->get_ID(),
                ],
            ],
        ];

        wp_send_json_success( json_encode( $data ) );
    }

    /**
     * Purchase event
     *
     * @return void
     * @since 4.4.34
     */
    protected function order_received(){
        global $wp_query;
        $order = wc_get_order( $wp_query->query_vars['order-received'] );

        $product_ids = [];
        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product_ids[] = [ 'id' => $item->get_product_id() ];
        }

        $data = [
            'send_to' => $this->send_to,
            'value'   => $order->get_total(),
            'items'   => $product_ids,
        ];
        ?>
        gtag( 'event', 'purchase', <?php echo json_encode( $data ); ?> );
        <?php
    }

}