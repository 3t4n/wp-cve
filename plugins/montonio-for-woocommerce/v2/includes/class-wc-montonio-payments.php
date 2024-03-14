<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Payments extends WC_Payment_Gateway {

    /**
	 * Notices (array)
	 *
	 * @var array
	 */
    protected $admin_notices = array();

    /**
	 * Is test mode active?
	 *
	 * @var bool
	 */
    public $sandbox_mode;

    /**
	 * Payment Handle Style
	 *
	 * @var string
	 */
    public $handle_style;

    /**
     * Bank list
     *
     * @var string
     */
    public $bank_list;

    /**
     * Default Eurozone country
     *
     * @var string
     */
    public $default_country;

    /**
     * Should we hide country dropdown?
     *
     * @var bool
     */
    public $hide_country_select;

    /**
     * Should we echo scripts?
     *
     * @var bool
     */
    public $script_mode;

    /**
     * Should we preselect country by user data?
     *
     * @var bool
     */
    public $preselect_country;

    public function __construct() {
        $this->id                 = 'wc_montonio_payments';
        $this->icon               = 'https://public.montonio.com/logo/montonio-logomark-s.png';
        $this->has_fields         = true;
        $this->method_title       = __( 'Montonio Bank Payments (2024)', 'montonio-for-woocommerce' );
        $this->method_description = __( 'Allows bank payments via the Montonio Payment Initiation Service.', 'montonio-for-woocommerce' );
        $this->supports           = array(
            'products', 
            'refunds'
        );

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();

        // Get settings
        $this->title               = __( $this->get_option( 'title', __( 'Pay with your bank', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );
        $this->description         = $this->get_option( 'description' );
        $this->enabled             = $this->get_option( 'enabled' );
        $this->sandbox_mode        = $this->get_option( 'sandbox_mode' );
        $this->handle_style        = $this->get_option( 'handle_style' );
        $this->bank_list           = $this->get_option( 'bank_list' );
        $this->default_country     = $this->get_option( 'default_country', 'EE' );
        $this->hide_country_select = $this->get_option( 'hide_country_select' );
        $this->script_mode         = $this->get_option( 'script_mode' );

        $this->preselect_country = $this->get_option( 'preselect_country' );
     
        // Hooks
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'validate_settings' ) );
        add_action( 'woocommerce_api_' . $this->id, array( $this, 'get_order_response' ) );
        add_action( 'woocommerce_api_' . $this->id . '_notification', array( $this, 'get_order_notification' ) );
        add_filter( 'woocommerce_gateway_icon', array( $this, 'add_icon_class' ), 10, 3 );
        add_action( 'admin_notices', array( $this, 'display_admin_notices' ), 999 );
    }

    /**
	 * Edit gateway icon.
	 */
    public function add_icon_class( $icon, $id ) {
        if ( $id == $this->id ) {
            return str_replace( 'src="', 'class="montonio-payment-method-icon montonio-pis-icon" src="', $icon );
        }
        
        return $icon;
    }

    /**
    * Plugin options, we deal with it in Step 3 too
    */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled'         => array(
                'title'       => __( 'Enable/Disable', 'montonio-for-woocommerce' ),
                'label'       => __( 'Enable Montonio Bank Payments', 'montonio-for-woocommerce' ),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no',
            ),
            'sandbox_mode'        => array(
                'title'       => 'Test mode',
                'label'       => 'Enable Test Mode',
                'type'        => 'checkbox',
                'description' => __( 'Use the Sandbox environment for testing only.', 'montonio-for-woocommerce' ),
                'default'     => 'no',
                'desc_tip'    => true,
            ),
            'title'           => array(
                'title'       => __( 'Title', 'montonio-for-woocommerce' ),
                'type'        => 'text',
                'default'     => __( 'Pay with your bank', 'montonio-for-woocommerce' ),
                'description' => __( 'Payment method title which the user sees during checkout.', 'montonio-for-woocommerce' ),
                'desc_tip'    => true,
            ),
            'description'      => array(
                'title'       => __( 'Description', 'montonio-for-woocommerce' ),
                'type'        => 'textarea',
                'css'         => 'width: 400px;',
                'default'     => '',
                'description' => __( 'Payment method description which the user sees during checkout.', 'montonio-for-woocommerce' ),
                'desc_tip'    => true,
            ),
            'handle_style'    => array(
                'title'       => __( 'Payment Handle Style', 'montonio-for-woocommerce' ),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'grid',
                'description' => __( 'This controls how to display bank logos at checkout', 'montonio-for-woocommerce' ),
                'options'     => array(
                    'grid'   => 'Display bank logos in grid',
                    'list'   => 'Display bank logos in list',
                    'hidden' => 'Hide bank logos',
                ),
                'desc_tip' => true,
            ),
            'default_country'   => array(
                'title'       => __( 'Default Eurozone Country', 'montonio-for-woocommerce' ),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'EE',
                'description' => __( 'The country whose banks to show first at checkout when using Euro (€) as currency.<br/>When using a different currency, e.g Polish Zloty (PLN), this option will be disregarded and the correct currency will be shown.', 'montonio-for-woocommerce' ),
                'options'     => array(
                    'EE' => 'Estonia',
                    'FI' => 'Finland',
                    'LV' => 'Latvia',
                    'LT' => 'Lithuania',
                    'DE' => 'Germany',
                ),
            ),
            'hide_country_select' => array(
                'title'   => __( 'Hide Country Select', 'montonio-for-woocommerce' ),
                'label'   => __( 'Enable', 'montonio-for-woocommerce' ),
                'type'    => 'checkbox',
                'default' => 'no',
            ),
            'preselect_country' => array(
                'title'       => __( 'Preselect country by user data', 'montonio-for-woocommerce' ),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'disable',
                'description' => __( 'Automatically change the selected bank country based on customer\'s data? If unsuccessful, fallback to "Default Eurozone Country".', 'montonio-for-woocommerce' ),
                'options'     => array(
                    'disable' => __( 'Don\'t select automatically', 'montonio-for-woocommerce' ),
                    'billing' => __( 'Select based on user billing country', 'montonio-for-woocommerce' ),
                    'locale'  => __( 'Select based on site language', 'montonio-for-woocommerce' ),
                ),
            ),
            'custom_payment_description' => array(
                'title'       => __( 'Use custom payment description', 'montonio-for-woocommerce' ),
                'label'       => __( 'Enable', 'montonio-for-woocommerce' ),
                'type'        => 'checkbox',
                'default'     => 'no',
                'description' => __( 'This allows you to customize the payment description that will be relayed to the bank\'s payment order. If not enabled, defaults to order ID.', 'montonio-for-woocommerce' ),
            ),
            'payment_description' => array(
                'title'       => __( 'Custom payment description', 'montonio-for-woocommerce' ),
                'type'        => 'text',
                'default'     => __( 'Payment for order {order_number}', 'montonio-for-woocommerce' ),
                'description' => __( 'Available placeholders: {order_number}', 'montonio-for-woocommerce' ),
            ),
            'script_mode' => array(
                'title'       => __( 'Echo CSS & JS', 'montonio-for-woocommerce' ),
                'label'       => __( 'Enable', 'montonio-for-woocommerce' ),
                'type'        => 'checkbox',
                'description' => __( '<strong>[ADVANCED]</strong> If enqueue CSS and JavaScript is not working properly, please enable this option.', 'montonio-for-woocommerce' ),
                'default'     => 'no',
            ),
        );
    }

    /**
	 * Check if Montonio Payments should be available
	 */
    public function is_available() {
        if ( $this->enabled !== 'yes' ) {
            return false;
        }

        if ( ! WC_Montonio_Helper::is_client_currency_supported() ) {
            return false;
        }
    
        $settings = get_option( 'woocommerce_wc_montonio_payments_settings' );
        if ( ! empty( $settings['bank_list_fetch_datetime'] ) ) {
            $bank_list_fetch_datetime = $settings['bank_list_fetch_datetime'];
        } else {
            $bank_list_fetch_datetime = null;
        }

        if( empty( $bank_list_fetch_datetime ) || $bank_list_fetch_datetime < time() - 86400 ) {
            $bank_list = $this->sync_banks( $settings );
            if ( isset( $bank_list ) ){
                $settings['bank_list'] = $bank_list;
                $settings[ 'bank_list_fetch_datetime' ] = time();
                update_option( 'woocommerce_wc_montonio_payments_settings', $settings );
            } else {
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
                    $this->add_admin_notice( sprintf( __( 'Sandbox API keys missing. Montonio Bank Payments was disabled. <a href="%s">Add API keys here</a>.', 'montonio-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_montonio_api' ) ), 'error' );
                    $settings['enabled'] = 'no';

                    return $settings;
                }
            } else {
                if ( empty( $api_settings['access_key'] ) || empty( $api_settings['secret_key'] ) ) {
                    $this->add_admin_notice( sprintf( __( 'Live API keys missing. Montonio Bank Payments was disabled. <a href="%s">Add API keys here</a>.', 'montonio-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_montonio_api' ) ), 'error' );
                    $settings['enabled'] = 'no';
                    
                    return $settings; 
                }
            }

            $bank_list = $this->sync_banks( $settings );

            if ( isset ( $bank_list ) ){
                $settings['bank_list'] = $bank_list;
                $settings['bank_list_fetch_datetime'] = time();
            }
        }

        return $settings;       
    }

   /**
     * Fetch list of available payment methods for Checkout page
     *
     * @param array $settings - New Admin Settings data
     * 
     * @return string - the JSON-encoded banklist array
     */
    public function sync_banks( $settings ) {
        $api_settings = get_option( 'woocommerce_wc_montonio_api_settings' );
        
        try {
            $montonio_api = new WC_Montonio_API( $settings['sandbox_mode'] );
            $response = json_decode( $montonio_api->fetch_payment_methods() );

            if ( ! isset( $response->paymentMethods->paymentInitiation ) ) {
                throw new Exception( __( 'PIS not enabled in partner system', 'montonio-for-woocommerce' ) );
            }
            
            return json_encode( $response->paymentMethods->paymentInitiation->setup );
        } catch (Exception $e) {
            if ( ! empty( $e->getMessage() ) ) {
                $this->add_admin_notice( __( 'Montonio API response: ', 'montonio-for-woocommerce' ) . $e->getMessage(), 'error' );
                WC_Montonio_Logger::log( 'Bank list sync failed: ' . $e->getMessage() );
            }
            $this->add_admin_notice( __( 'Bank list sync failed. Please try again later.', 'montonio-for-woocommerce' ), 'error' );
            return null;
        }
    }
    
    /**
     * Banks to show at checkout
     */
    public function payment_fields() {
        $currency    = WC_Montonio_Helper::get_currency();
        $description = $this->get_description();

        $available_countries = array(
            'EE' => __( 'Estonia', 'montonio-for-woocommerce' ),
            'FI' => __( 'Finland', 'montonio-for-woocommerce' ),
            'LV' => __( 'Latvia', 'montonio-for-woocommerce' ),
            'LT' => __( 'Lithuania', 'montonio-for-woocommerce' ),
            'PL' => __( 'Poland', 'montonio-for-woocommerce' ),
            'DE' => __( 'Germany', 'montonio-for-woocommerce' )
        );

        if ( $this->preselect_country == 'locale' || $this->preselect_country == 'billing' ) { 
            if ( $this->preselect_country == 'billing' && WC()->customer && WC()->customer->get_billing_country() ) { 
                $user_country = WC()->customer->get_billing_country();
            } else {
                $user_country = strtoupper( WC_Montonio_Helper::get_locale( apply_filters( 'wpml_current_language', get_locale() ) ) );
            }

            if ( $user_country == 'ET' ) {
                $this->default_country = 'EE';
            } else {
                foreach ( $available_countries as $locale => $country_name ) {
                    if ( $locale == $user_country ) {
                        $this->default_country = $user_country;
                    }
                }
            }
        }

        if ( $currency == 'PLN' ) {
            $this->default_country = 'PL';
        }

        do_action( 'wc_montonio_before_payment_desc', $this->id );

        if ( $this->sandbox_mode === 'yes' ) {
            echo '<strong>' . __( 'TEST MODE ENABLED!', 'montonio-for-woocommerce' ) . '</strong><br>' . __( 'When test mode is enabled, payment providers do not process payments.', 'montonio-for-woocommerce' ) . '<br>';
		}

        if ( ! empty( $description ) ) {
            echo apply_filters( 'wc_montonio_description', wp_kses_post( $description ), $this->id );
        }

        do_action( 'wc_montonio_before_bank_list', $this->id );

        if ( ! empty( $this->bank_list ) && $this->handle_style != 'hidden' ) {

            echo '<div class="montonio-bank-payments-form">';

            $bank_list = apply_filters( 'wc_montonio_bank_list', json_decode( $this->bank_list, true ) );

            // Filter out countries that doesn't support current currency
            $countries = array_filter( $bank_list, function( $country ) use ( $currency ) {
                return array_search( $currency, $country['supportedCurrencies'] ) !== false;
            });
            
            if ( $this->hide_country_select !== 'yes' && count( $countries ) > 1 ) {
                echo '<select class="montonio-payments-country-dropdown" name="montonio_payments_prefered_country">';
                foreach ( $countries as $r => $list ) {
                    echo '<option ' . ( $r == $this->default_country ? 'selected="selected"' : '' ) . ' value="' . $r . '">' . $available_countries[$r] . '</option>';
                }
                echo '</select>';
            } else {
                echo '<input type="hidden" name="montonio_payments_prefered_country" value="' . $this->default_country . '">';          
            }

            echo '<div id="montonio-payments-description" class="montonio-bank-items montonio-bank-items--' . $this->handle_style . '">';
            $default_country = array_keys( $countries )[0];
            foreach ( $countries as $country => $value ) {
                if ( $country === $this->default_country ) {
                    $default_country = $country;
                }
            }

            foreach ( $countries as $r => $list ) {
                foreach ( $list['paymentMethods'] as $key => $value ) {
                    if ( in_array( $currency, $value['supportedCurrencies'] ) ) {
                        echo '<div class="bank-region-' . $r . ' montonio-bank-item' . ( $r == $default_country ? '' : ' montonio-bank-item--hidden' ) . '" data-bank="' . $value['code'] . '"><img class="montonio-bank-item-img" src="' . $value['logoUrl'] . '"  alt="' . $value['name'] . '"></div>';
                    }
                }
            }
            echo '</div>';
            echo '<input type="hidden" name="montonio_payments_preselected_bank" id="montonio_payments_preselected_bank">';
            echo '</div>';
        }
        
        do_action( 'wc_montonio_after_payment_desc', $this->id );

        if ( $this->script_mode !== 'yes' ) {
            wp_enqueue_style( 'montonio-style' );
            wp_enqueue_script( 'montonio-pis' );
        } else {
            echo '<link rel="stylesheet" href="' . WC_MONTONIO_PLUGIN_URL . '/v2/assets/css/montonio-style.css?ver=' . WC_MONTONIO_PLUGIN_VERSION . '">';
            echo '<script type="text/javascript" src="' . WC_MONTONIO_PLUGIN_URL . '/v2/assets/js/montonio-pis.js?ver=' . WC_MONTONIO_PLUGIN_VERSION . '"></script>';
        }
    }

    /*
    * Process woocommerce orders
    */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );

        try {
            $payment_data = array(
                'paymentMethodId' => $this->id,
                'payment'         => array(
                    'method'        => 'paymentInitiation',
                    'methodDisplay' => $this->get_title(),
                    'methodOptions' => array(
                        'paymentReference'   => (string) apply_filters( 'wc_montonio_payment_reference', '', $this->id ),
                        'paymentDescription' => (string) WC_Montonio_Helper::get_payment_description( $this->get_option( 'custom_payment_description', 'no' ), $order_id, $this->get_option( 'payment_description' ) ),
                        'preferredCountry'   => (string) $this->default_country
                    ),
                ),
            );

            if ( isset( $_POST['montonio_payments_preselected_bank'] ) ) {
                $payment_data['payment']['methodOptions']['preferredProvider'] = sanitize_text_field( $_POST['montonio_payments_preselected_bank'] );
            }

            if ( isset( $_POST['montonio_payments_prefered_country'] ) ) {
                $payment_data['payment']['methodOptions']['preferredCountry'] = sanitize_text_field( $_POST['montonio_payments_prefered_country'] );
            }
        
            // Create new Montonio API instance
            $montonio_api = new WC_Montonio_API( $this->sandbox_mode );
            $montonio_api->order = $order;
            $montonio_api->payment_data = $payment_data;

            $response = $montonio_api->create_order();

            $order->update_meta_data( '_montonio_uuid', $response->uuid );

            if ( is_callable( array( $order, 'save' ) ) ) {
                $order->save();
            }
            
            // Return response after which redirect to Montonio Payments will happen
            return array(
                'result'   => 'success',
                'redirect' => $response->paymentUrl,
            );
        } catch ( Exception $e ) {
            wc_add_notice( __( 'There was a problem processing this payment. Please refresh the page and try again.', 'montonio-for-woocommerce' ), 'error' );

            if ( ! empty( $e->getMessage() ) ) {
                $order->add_order_note( __( 'Montonio: There was a problem processing the payment. Response: ', 'montonio-for-woocommerce' ) . $e->getMessage() );
                wc_add_notice( __( 'Montonio API response: ', 'montonio-for-woocommerce' ) . $e->getMessage(), 'error' );
                WC_Montonio_Logger::log( 'Failure - Order ID: ' . $order_id . ' Response: ' . $e->getMessage() . ' ' . $this->id );
            }
        }
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
     * Edit settings page layout
     */
    public function admin_options() {
        WC_Montonio_Display_Admin_Options::display_options( 
            $this->method_title, 
            $this->generate_settings_html( array(), false ),
            $this->id,
            $this->sandbox_mode
        );
    }

    /**
     * Display admin notices
     */
    public function add_admin_notice( $message, $class ) {
        $this->admin_notices[] = array( 'message' => $message, 'class' => $class );
	}

    public function display_admin_notices() {
		foreach ( $this->admin_notices as $notice ) {
			echo '<div class="notice notice-' . esc_attr( $notice['class'] ) . '">';
			echo '	<p>' . wp_kses_post( $notice['message'] ) . '</p>';
			echo '</div>';
		}
	}
}
