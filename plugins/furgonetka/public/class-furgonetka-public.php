<?php

require_once plugin_dir_path( __FILE__ ) . '../includes/api/class-furgonetka-cart.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/api/class-furgonetka-settings.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/api/class-furgonetka-order.php';

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://furgonetka.pl
 * @since      1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/public
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * Furgonetka_Public_View Class
     *
     * @var \Furgonetka_Public_View()
     */
    private $view;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private static $class_base = 'furgonetka-checkout-btn';
    /** construction classes */
    private static $base_class;
    private static $widget_class;
    private static $minicart_widget_class;
    private static $backend_class;
    private static $frontend_class;
    /** origin classes */
    private static $userdefined_class;
    private static $builder_class;
    /** page position classes */
    private static $product_class;
    private static $cart_class;
    private static $order_class;
    private static $minicart_class;
    /** hidden container class */
    private static $hidden_class = 'furgonetka-hidden-checkout-btn';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        self::setup_classes();

        $this->include_view();
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        if ( ! is_checkout() ) {
            return;
        }

        $file_path = 'css/furgonetka-public.css';

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . $file_path,
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . $file_path ),
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $furgonetkaBaseUrl = Furgonetka_Admin::get_test_mode() ? 'https://sandbox.furgonetka.pl' : 'https://furgonetka.pl';

        if ( Furgonetka_Admin::is_checkout_active() ) {
            $checkout_file_path = 'js/woocommerce-checkout' . ( Furgonetka_Admin::get_test_mode()
                    ? '-sandbox' : '-prod' ) . '.js';
            wp_enqueue_script(
                "$this->plugin_name-checkout",
                plugin_dir_url( __FILE__ ) . $checkout_file_path,
                array(),
                filemtime( plugin_dir_path( __FILE__ ) . $checkout_file_path ),
                true
            );
            $cart_btn_position = Furgonetka_Admin::get_portmonetka_cart_button_position();
            $cart_btn_width = Furgonetka_Admin::get_portmonetka_cart_button_width();
            $cart_btn_css = Furgonetka_Admin::get_portmonetka_cart_button_css();

            wp_localize_script(
                "$this->plugin_name-checkout",
                'portmonetka_settings',
                array(
                    'portmonetka_uuid'   => Furgonetka_Admin::get_checkout_uuid(),
                    'is_test_mode'       => (int) Furgonetka_Admin::is_checkout_test_mode(),
                    'product_selector'   => Furgonetka_Admin::get_portmonetka_product_selector(),
                    'cart_selector'      => Furgonetka_Admin::get_portmonetka_cart_selector(),
                    'minicart_selector'  => Furgonetka_Admin::get_portmonetka_minicart_selector(),
                    'ajaxurl'            => admin_url( 'admin-ajax.php' ),
                    'checkout_details'   => Furgonetka_Admin::get_checkout_details(),
                    'checkout_rest_urls' => Furgonetka_Admin::get_checkout_rest_urls(),
                    'cart_btn_position'  => $cart_btn_position ?? false,
                    'cart_btn_width'     => $cart_btn_width ?? false,
                    'cart_btn_css'       => $cart_btn_css ?? false,
                    'classes'            => [
                        'base_class'         => self::$base_class,
                        'widget_class'       => self::$widget_class,
                        'minicart_widget_class'       => self::$minicart_widget_class,
                        'backend_class'      => self::$backend_class,
                        'frontend_class'     => self::$frontend_class,
                        'userdefined_class'  => self::$userdefined_class,
                        'builder_class'      => self::$builder_class,
                        'product_class'      => self::$product_class,
                        'cart_class'         => self::$cart_class,
                        'order_class'        => self::$order_class,
                        'minicart_class'     => self::$minicart_class,
                        'hidden_class'       => self::$hidden_class
                    ],
                    'pages_urls' => [
                        'cart' => parse_url(wc_get_cart_url())['path'] ?? null,
                    ]
                )
            );
        }

        if ( ! is_checkout() ) {
            return;
        }

        $file_path = 'js/furgonetka-public.js';

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . $file_path,
            array( 'jquery' ),
            filemtime( plugin_dir_path( __FILE__ ) . $file_path ),
            false
        );

        wp_enqueue_script( 'furgonetka_map', $furgonetkaBaseUrl . '/js/dist/map/map.js"', array(), '1.0', true );

        wp_localize_script(
            $this->plugin_name,
            'settings',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    /**
     * Include view for pbulic
     */
    public function include_view()
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/view/class-furgonetka-public-view.php';
        $this->view = new Furgonetka_Public_View();
    }

    /**
     * Add map script to shipping options and current selected point from session.
     * Dosen't load map if there is only virtual products in cart
     *
     * @since    1.1.0
     */
    public function furgonetka_totals_after_shipping()
    {
        $virtual_product = false;
        $normal_product  = false;
        foreach ( WC()->session->cart as $item ) {
            $product = wc_get_product( $item['product_id'] );
            if ( ! $product ) {
                continue;
            }
            $product->is_virtual() ? $virtual_product = true : $normal_product = true;
        }

        if (
            ! $virtual_product && $normal_product ||
            $virtual_product && $normal_product
        ) {
            $chosen_method_array = WC()->session->get( 'chosen_shipping_methods' );
            $delivery_to_type    = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

            if ( isset( $delivery_to_type[ $chosen_method_array[0] ] ) ) {
                $selected_point = $this->get_selected_point_from_session(
                    $delivery_to_type[ $chosen_method_array[0] ],
                    ( WC()->session->get( 'chosen_payment_method' ) === 'cod' )
                );
            } else {
                $selected_point = $this->get_selected_point_from_session( '', false );
            }
            $this->view->render_map( $this->plugin_name, $selected_point );
        } else {
            return false;
        }
    }

    /**
     * Save selected point to order.
     *
     * @param \WC_Order $order - WC_Order Class.
     * @param mixed     $posted - posted.
     *
     *  @since  1.0.0
     */
    public function save_point_to_order( $order, $posted )
    {
        //phpcs:ignore
        if ( isset( $_POST['furgonetkaPoint'] ) ) {
            //phpcs:ignore
            $order->update_meta_data(
                '_furgonetkaPoint',
                sanitize_text_field( wp_unslash( $_POST['furgonetkaPoint'] ) )
            );
        }
        //phpcs:ignore
        if ( isset( $_POST['furgonetkaPointName'] ) ) {
            //phpcs:ignore
            $order->update_meta_data(
                '_furgonetkaPointName',
                sanitize_text_field( wp_unslash( $_POST['furgonetkaPointName'] ) )
            );
        }
        //phpcs:ignore
        if ( isset( $_POST['furgonetkaService'] ) ) {
            //phpcs:ignore
            $order->update_meta_data(
                '_furgonetkaService',
                sanitize_text_field( wp_unslash( $_POST['furgonetkaService'] ) )
            );
        }
    }

    /**
     * Save selected point to woocommerce session
     *
     * @since    1.0.0
     */
    public function save_point_to_session()
    {
        if ( ! check_ajax_referer( $this->plugin_name . '_setPointAction', 'security', false ) === false ) {
            $current_service = isset( $_POST['currentService'] ) ?
                sanitize_text_field( wp_unslash( $_POST['currentService'] ) ) : '';
            $name            = isset( $_POST['name'] ) ?
                sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
            $code            = isset( $_POST['code'] ) ?
                sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '';
            $cod             = isset( $_POST['cod'] ) ?
                sanitize_text_field( wp_unslash( $_POST['cod'] ) ) : '';
        } else {
            wp_send_json_error();
        }

        $this->save_point_to_session_internal( $current_service, $code, $name, $cod === 'true' );

        wp_send_json_success();
    }

    /**
     * Save selected point to WooCommerce (internal)
     */
    public function save_point_to_session_internal( $current_service, $code, $name, $cod )
    {
        $current_selection = WC()->session->get( $this->plugin_name . '_pointTo' );

        if ( $cod ) {
            $current_selection = WC()->session->get( $this->plugin_name . '_pointToCod' );
        }

        if ( ! $current_selection ) {
            $current_selection = array();
        }

        $current_selection[ $current_service ] = array(
            'service' => $current_service,
            'name'    => $name,
            'code'    => $code,
        );

        if ( $cod ) {
            WC()->session->set( $this->plugin_name . '_pointToCod', $current_selection );
        } else {
            WC()->session->set( $this->plugin_name . '_pointTo', $current_selection );
        }
    }

    /**
     *
     * Get selected point from woocommerce session
     */
    public function get_point_to_payment()
    {
        //phpcs:ignore
        if ( isset( $_POST['cod'] ) ) {
            $cod = sanitize_text_field( wp_unslash( $_POST['cod'] ) );
        }

        if ( check_ajax_referer( $this->plugin_name . '_setPointAction', 'security', false ) === false ) {
            wp_send_json_error();
        }

        $chosen_method_array = WC()->session->get( 'chosen_shipping_methods' );

        $delivery_to_type = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

        $selected_point = $this->get_selected_point_from_session(
            $delivery_to_type[ $chosen_method_array[0] ],
            'true' === $cod
        );

        $data = array(
            'button' => $this->generate_delivery_button(
                $delivery_to_type[ $chosen_method_array[0] ],
                'true' === $cod
            ),
            'code'   => $selected_point['code']
        );

        wp_send_json_success( $data );
    }

    /**
     * Add map to shipping option.
     *
     * @param mixed $method - method.
     * @param mixed $index - index.
     * @return void
     */
    public function after_shipping_rate( $method, $index )
    {
        if ( ! is_checkout() ) {
            return;
        }
        $chosen_method_array = WC()->session->get( 'chosen_shipping_methods' );

        if ( $chosen_method_array[0] !== $method->id ) {
            return;
        }
        $delivery_to_type = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

        if ( isset( $method->id ) && isset( $delivery_to_type[ $method->id ] ) ) {
            // all variables are escaped in generate_delivery_button method.
            //phpcs:ignore
            echo '<p id="select-point-container">' . $this->generate_delivery_button(
                $delivery_to_type[$method->id],
                ( WC()->session->get( 'chosen_payment_method' ) === 'cod' )
            ) . '</p>';
        }
    }

    /**
     * Select Point button in delivery list
     *
     * @param string $generate_delivery_button - ethod type.
     * @param mixed  $is_cod - check if ic COD.
     * @since    1.0.0
     * @return string
     */
    public function generate_delivery_button( $generate_delivery_button, $is_cod )
    {
        $selected_point = $this->get_selected_point_from_session( $generate_delivery_button, $is_cod );
        $customer       = WC()->session->get( 'customer' );

        return sprintf(
            '<a id="select-point" href="#" onclick=\'openFurgonetkaMap("%1$s","%4$s","%5$s");return false\'>%2$s</a><span id="selected-point">%3$s</span>',
            esc_html( $generate_delivery_button ),
            __( 'Select point', 'furgonetka' ),
            esc_html( $selected_point['name'] ),
            esc_html( $customer['shipping_city'] ),
            esc_html( $customer['shipping_address_1'] ) . ' ' . esc_html( $customer['shipping_address_2'] )
        );
    }

    /**
     * Get selected point from woocommerce session
     *
     * @param mixed $service - name of services.
     * @param mixed $is_cod - checkif is cod.
     * @return string[]
     */
    public function get_selected_point_from_session( $service, $is_cod )
    {
        $return_selection  = array(
            'service' => '',
            'name'    => '',
            'code'    => '',
        );
        $current_selection = WC()->session->get( $this->plugin_name . '_pointTo' );
        if ( $is_cod ) {
            $current_selection = WC()->session->get( $this->plugin_name . '_pointToCod' );
        }

        if ( isset( $current_selection[ $service ] ) ) {
            return $current_selection[ $service ];
        }

        return $return_selection;
    }

    /**
     * Validate point selection in php
     *
     * @since    1.0.7
     */
    public function woocommerce_checkout_process()
    {
        $delivery_to_type = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

        $chosen_method_array = WC()->session->get( 'chosen_shipping_methods' );
        if ( isset( $chosen_method_array[0] ) && isset( $delivery_to_type[ $chosen_method_array[0] ] ) ) {
            //phpcs:ignore
            if ( isset(  $_POST['furgonetkaPoint'] ) ) {
                //phpcs:ignore
                $point = sanitize_text_field( wp_unslash( $_POST['furgonetkaPoint'] ) );
            }
            if ( empty( $point ) && true === WC()->cart->needs_shipping() ) {
                wc_add_notice( __( 'Please select delivery point.', 'furgonetka' ), 'error' );
            }
        }
    }

    /**
     * Add package information to woocomerce thank you page
     *
     * @param int $order_id - order id.
     *
     * @since 1.1.0
     */
    public function add_package_information_to_thank_you_page( $order_id )
    {
        $order               = wc_get_order( $order_id );
        $package_information = $order->get_meta( '_furgonetkaPointName' );

        if ( ! empty( $package_information ) ) {
            $this->view->render_package_information( $package_information );
        }
    }

    public static function add_checkout_button_product()
    {
        if ( Furgonetka_Admin::is_checkout_active() === false  ||
             Furgonetka_Admin::is_product_page_button_visible() === false) {
            return;
        }

        $dataProductId = '';
        self::setup_classes();

        $classes = [
            self::$base_class,
            self::$backend_class
        ];

        if ( is_single() ) {
            $dataProductId = 'data-product-id="' . get_the_ID() . '"';
            $classes[] = self::$product_class;
        }

        echo '<div class="' . implode(' ', $classes) . '" ' . $dataProductId . '></div>';
    }

    public static function add_checkout_button_order()
    {
        if ( Furgonetka_Admin::is_checkout_active() === false) {
            return;
        }

        self::setup_classes();

        $classes = [
            self::$base_class,
            self::$backend_class,
            self::$order_class
        ];

        echo '<div class="' . implode(' ', $classes) . '"></div>';
    }

    public static function add_checkout_button_shopping_cart_widget( $args = null )
    {
        if ( Furgonetka_Admin::is_checkout_active() === false ) {
            return;
        }

        self::setup_classes();

        $classes = [
            self::$widget_class,
            self::$backend_class,
            self::$cart_class
        ];

        echo '<div class="' . implode(' ', $classes) . '"></div>';
    }

    public static function add_checkout_button_shopping_minicart_widget( $args = null )
    {
        if ( Furgonetka_Admin::is_checkout_active() === false ) {
            return;
        }

        self::setup_classes();

        $classes = [
            self::$minicart_widget_class,
            self::$backend_class,
            self::$minicart_class
        ];

        echo '<div class="' . implode(' ', $classes) . '"></div>';
    }

    public static function add_hidden_container_for_cart_widget()
    {
        if ( Furgonetka_Admin::is_checkout_active() === false ) {
            return;
        }

        self::setup_classes();

        $classes = [
            self::$hidden_class,
            self::$hidden_class . '__backend'
        ];

        echo '<div style="display:none;" class="' . implode(' ', $classes) . '">';
        echo '<script>window.dispatchEvent(new Event("furgonetka_checkout_shopping_cart_widget_ready"));</script>';
        echo '</div>';
    }

    public function clear_cart()
    {
        WC()->cart->empty_cart();
    }

    public function init()
    {
        add_action( 'wp_loaded', array( $this, 'rest_api_includes' ), 5 );
        add_action( 'rest_api_init', array( $this, 'register_rest_api_endpoints' ) );
    }

    /**
     * Furgonetka permission callback
     *
     * @see \Furgonetka_Endpoint_Abstract permission_callback method
     *
     * @param \WP_REST_Request $request
     * @return bool
     */
    public function permission_callback( \WP_REST_Request $request )
    {
        // Auth header.
        if ( ! empty( $request->get_header( 'authorization' ) ) ) {

            $auth_data = str_replace( 'Basic ', '', $request->get_header( 'authorization' ) );
            //phpcs:ignore
            $auth_array = explode( ':', base64_decode( $auth_data ) );

            $key    = $auth_array[0];
            $secret = $auth_array[1];

            // Query params.
        } else {
            $request_params = $request->get_query_params();

            $key    = $request_params['consumer_key'];
            $secret = $request_params['consumer_secret'];
        }

        if ( Furgonetka_Admin::get_rest_customer_key() === $key
            && password_verify( $secret, Furgonetka_Admin::get_rest_customer_secret() )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Auth API permission callback
     *
     * @param \WP_REST_Request $request
     * @return bool
     */
    public function permission_callback_auth_api( $request )
    {
        $data = $request->get_json_params();

        if ( ! isset( $data[ 'user_id' ]) ) {
            return false;
        }

        return $this->verify_auth_api_nonce( $data[ 'user_id' ] );
    }

    /**
     * Verify & discard nonce with the saved one
     *
     * @param $nonce
     * @return bool
     */
    private function verify_auth_api_nonce( $nonce )
    {
        $stored_nonce = get_option( $this->plugin_name . '_auth_api_nonce' );

        if ( ! $stored_nonce ) {
            return false;
        }

        delete_option( $this->plugin_name . '_auth_api_nonce' );

        return $nonce === $stored_nonce;
    }

    public function register_rest_api_endpoints()
    {
        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/all_in_one',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_all_in_one' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/cart',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_cart_items' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/shippings',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_shipping' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/payments',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_payments' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/coupons',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_coupons' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/totals',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_totals' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/cart/shipping-method',
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( new Furgonetka_Cart(), 'get_cart_shipping_method' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/cart/add_coupon',
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( new Furgonetka_Cart(), 'maybe_add_coupon' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/cart/remove_coupons',
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( new Furgonetka_Cart(), 'remove_coupons' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/checkout/settings',
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( new Furgonetka_Settings(), 'updateSettings' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );

        register_rest_route(
            FURGONETKA_REST_NAMESPACE,
            '/authorize/callback',
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( new Furgonetka_Settings(), 'authorize_callback' ),
                'permission_callback' => array( $this, 'permission_callback_auth_api' ),
            )
        );
    }

    function rest_api_includes()
    {
        if ( empty( WC()->cart ) ) {
            WC()->frontend_includes();
            wc_load_cart();
        }
    }

    public static function setup_classes() {
        self::$base_class = self::$class_base . '-container';
        self::$widget_class = self::$class_base . '-container-widget';
        self::$minicart_widget_class = self::$class_base . '-container-minicart-widget';
        self::$backend_class = self::$class_base . '__wp';
        self::$frontend_class = self::$class_base . '__js';
        self::$userdefined_class = self::$class_base . '__user-defined';
        self::$builder_class = self::$class_base . '__builder';
        self::$product_class = self::$class_base . '__product';
        self::$cart_class = self::$class_base . '__cart';
        self::$minicart_class = self::$class_base . '__minicart';
        self::$order_class = self::$class_base . '__order';
    }
}
