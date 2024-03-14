<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Hire_Purchase extends WC_Payment_Gateway {

    /**
	 * Notices (array)
	 *
	 * @var array
	 */
    protected $admin_notices = [];

    /**
	 * Is test mode active?
	 *
	 * @var bool
	 */
    public $sandbox_mode;

    public function __construct() {
        $this->id                 = 'wc_montonio_hire_purchase';
        $this->icon               = 'https://public.montonio.com/images/logos/inbank-general.svg';
        $this->has_fields         = false;
        $this->method_title       = __( 'Montonio Financing', 'montonio-for-woocommerce' );
        $this->method_description = __( 'Hire purchase provided in co-operation with Inbank', 'montonio-for-woocommerce' );
        $this->supports           = [
            'products', 
            'refunds'
        ];

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();

        // Get settings
        $this->title           = __( $this->get_option( 'title', __( 'Financing', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );
        $this->description     = __( $this->get_option( 'description' ), 'montonio-for-woocommerce' );
        $this->enabled         = $this->get_option( 'enabled' );
        $this->sandbox_mode    = $this->get_option( 'sandbox_mode' );

        if ( $this->sandbox_mode === 'yes' ) {
            $this->description = '<strong>' . __( 'TEST MODE ENABLED!', 'montonio-for-woocommerce' ) . '</strong><br>' . __( 'When test mode is enabled, payment providers do not process payments.', 'montonio-for-woocommerce' ) . '<br>' . $this->description;
		}

        // Hooks
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
        add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, [ $this, 'validate_settings' ] );
        add_action( 'woocommerce_api_' . $this->id, [ $this, 'get_order_response' ] );
        add_action( 'woocommerce_api_' . $this->id . '_notification', [ $this, 'get_order_notification' ] );
        add_filter( 'woocommerce_gateway_icon', [ $this, 'add_icon_class' ], 10, 3 );
        add_action( 'wp_enqueue_scripts', [ $this, 'payment_scripts' ] );
        add_action( 'admin_notices', [ $this, 'display_admin_notices' ], 999 );
    }

    /**
     * Edit gateway icon.
     */
    public function add_icon_class( $icon, $id ) {
        if ( $id == $this->id ) {
            return str_replace( 'src="', 'class="montonio-payment-method-icon montonio-hire-purchase-icon" src="', $icon );
        }
        
        return $icon;
    }

    /**
    * Plugin options, we deal with it in Step 3 too
    */
    public function init_form_fields() {
        $this->form_fields = [
            'enabled'         => [
                'title'       => __( 'Enable/Disable', 'montonio-for-woocommerce' ),
                'label'       => __( 'Enable Montonio Financing', 'montonio-for-woocommerce' ),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no',
            ],
            'sandbox_mode'        => [
                'title'       => 'Test mode',
                'label'       => 'Enable Test Mode',
                'type'        => 'checkbox',
                'description' => __( 'Use the Sandbox environment for testing only.', 'montonio-for-woocommerce' ),
                'default'     => 'no',
                'desc_tip'    => true,
            ],
            'title'           => [
                'title'       => __( 'Title', 'montonio-for-woocommerce' ),
                'type'        => 'text',
                'default'     => __( 'Financing', 'montonio-for-woocommerce' ),
                'description' => __( 'Payment method title which the user sees during checkout.', 'montonio-for-woocommerce' ),
                'desc_tip'    => true,
            ],
            'description'      => [
                'title'       => __( 'Description', 'montonio-for-woocommerce' ),
                'type'        => 'textarea',
                'css'         => 'width: 400px;',
                'default'     => __( 'Pay in 3-72 months.', 'montonio-for-woocommerce' ),
                'description' => __( 'Payment method description which the user sees during checkout.', 'montonio-for-woocommerce' ),
                'desc_tip'    => true,
            ],
        ];
    }

    /**
	 * Check if Montonio Card Payments should be available
	 */
    public function is_available() {
        if ( $this->enabled !== 'yes' ) {
            return false;
        }

        if ( ! WC_Montonio_Helper::is_client_currency_supported( [ 'EUR' ] ) ) {
            return false;
        }

        if ( WC()->cart ) {
            $cart_total = $this->get_order_total();
            
            if ( $cart_total < 100 || $cart_total > 10000 ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Perform validation on settings after saving them
     */
    public function validate_settings( $settings ) {
        if ( is_array( $settings ) ) {

            if ( $settings['enabled'] === 'no' ) {
                return $settings;
            }

            $api_settings = get_option( 'woocommerce_wc_montonio_api_settings' );

            // Disable the payment gateway if API keys are not provided
            if ( $settings['sandbox_mode'] === 'yes' ) {
                if ( empty( $api_settings['sandbox_access_key'] ) || empty( $api_settings['sandbox_secret_key'] ) ) {
                    $this->add_admin_notice( sprintf( __( 'Sandbox API keys missing. Montonio Card Payments was disabled. <a href="%s">Add API keys here</a>.', 'montonio-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_montonio_api' ) ), 'error' );
                    $settings['enabled'] = 'no';

                    return $settings;
                }
            } else {
                if ( empty( $api_settings['access_key'] ) || empty( $api_settings['secret_key'] ) ) {
                    $this->add_admin_notice( sprintf( __( 'Live API keys missing. Montonio Card Payments was disabled. <a href="%s">Add API keys here</a>.', 'montonio-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_montonio_api' ) ), 'error' );
                    $settings['enabled'] = 'no';
                    
                    return $settings; 
                }
            }

            try {
                $montonio_api = new WC_Montonio_API( $settings['sandbox_mode'] );
                $response = json_decode( $montonio_api->fetch_payment_methods() );

                if ( ! isset( $response->paymentMethods->hirePurchase ) ) {
                    throw new Exception( __( 'Financing is not enabled in Montonio partner system.', 'montonio-for-woocommerce' ) );
                }
            } catch (Exception $e) {
                $settings['enabled'] = 'no';

                if ( ! empty( $e->getMessage() ) ) {
                    $this->add_admin_notice( __( 'Montonio API response: ', 'montonio-for-woocommerce' ) . $e->getMessage(), 'error' );
                    WC_Montonio_Logger::log( $e->getMessage() );
                }
            }
        }

        return $settings;       
    }

    /*
    * We're processing the payments here
    */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );

        try {
            // Prepare Payment Data for Montonio Payments
            $payment_data = [
                'paymentMethodId' => $this->id,
                'payment'         => [
                    'method'        => 'hirePurchase',
                    'methodDisplay' => $this->get_title(),
                    'methodOptions' => null,
                ],
            ];
        
            // Create new Montonio API instance
            $montonio_api = new WC_Montonio_API( $this->sandbox_mode );
            $montonio_api->order = $order;
            $montonio_api->payment_data = $payment_data;

            $response = $montonio_api->create_order();

            $order->update_meta_data( '_montonio_uuid', $response->uuid );

            if ( is_callable( [ $order, 'save' ] ) ) {
                $order->save();
            }
            
            // Return response after which redirect to Montonio Payments will happen
            return [
                'result'   => 'success',
                'redirect' => $response->paymentUrl,
            ];
        } catch ( Exception $e ) {
            wc_add_notice( __( 'There was a problem processing this payment. Please refresh the page and try again.', 'montonio-for-woocommerce' ), 'error' );

            if ( ! empty( $e->getMessage() ) ) {
                $order->add_order_note( __( 'Montonio: There was a problem processing the payment. Response: ', 'montonio-for-woocommerce' ) . $e->getMessage() );
                wc_add_notice( __( 'Montonio API response: ', 'montonio-for-woocommerce' ) . $e->getMessage(), 'error' );
                WC_Montonio_Logger::log( 'Failure - Order ID: ' . $order_id . ' Response: ' . $e->getMessage() . ' ' . $this->id );
            }
        }
    }

    public function payment_scripts() {
        if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && ! is_add_payment_method_page() ) {
			return;
		}
        
        wp_enqueue_style( 'montonio-style' );
    }
    
    /**
	 * Refunds amount from Montonio and return true/false as result
	 *
	 * @param string $order_id order id.
	 * @param string $amount refund amount.
	 * @param string $reason reason of refund.
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
        $montonio_refund = new WC_Montonio_Refund( $this->sandbox_mode );
		return $montonio_refund->init_refund($order_id, $amount, $reason );
    }
    
    /**
     * Check webhook notfications from Montonio
     */
    public function get_order_notification() {
        new WC_Montonio_Callbacks( 
            $this->sandbox_mode,
            true 
        );
    }

    /**
     * Check callback from Montonio
     * and redirect user: thankyou page for success, checkout on declined/failure
     */
    public function get_order_response() {
        new WC_Montonio_Callbacks( 
            $this->sandbox_mode,
            false 
        );
    }

    /**
     * Edit settings page layout
     */
    public function admin_options() {
        WC_Montonio_Display_Admin_Options::display_options( 
            $this->method_title, 
            $this->generate_settings_html( [], false ),
            $this->id,
            $this->sandbox_mode
        );
    }

    /**
     * Display admin notices
     */
    public function add_admin_notice( $message, $class ) {
        $this->admin_notices[] = [ 'message' => $message, 'class' => $class ];
	}

    public function display_admin_notices() {
		foreach ($this->admin_notices as $notice) {
			echo '<div id="message" class="' . esc_attr( $notice['class'] ) . '">';
			echo '	<p>' . wp_kses_post( $notice['message'] ) . '</p>';
			echo '</div>';
		}
	}
}
