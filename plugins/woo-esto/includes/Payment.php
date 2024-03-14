<?php

function init_woocommerce_esto_payment() {

    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    if( ! class_exists('EstoRequest')) {
        require_once('Request.php');
    }

    /**
     * Gateway class
     */
    class WC_Esto_Payment extends WC_Payment_Gateway {

        protected static $plugin_url;
        protected static $plugin_dir;

        public $id = 'esto';
        public $method_title = '';
        public $method_description = '';

        /**
         * Client has signed a contract with ESTO and the
         * purchase has been made
         */
        const TRX_APPROVED  = 'APPROVED';

        /**
         * ESTO has transferred payment to the merchant
         */
        const TRX_COMPLETED = 'COMPLETED';

        /**
         * Something went wrong
         */
        const TRX_FAILED = 'FAILED';

        /**
         * The customer received a negative answer and was rejected.
         */
        const TRX_REJECTED = 'REJECTED';

        /** @var EstoRequest */
        protected $_request;

        /** @var WC_Esto_Calculator */
        protected $_calculator;

        public $disabled_countries_for_this_method = [];

        /**
         * The minimum and maximum amount that can be used for hire purchase
         */
        const MIN_ORDER_TOTAL = 0.1;
        const MAX_ORDER_TOTAL = 10000;

        const MODE_TEST = 'test';
        const MODE_LIVE = 'live';

        protected $connection_mode;
        protected $endpoint;
        protected $shop_id;
        protected $secret_key;
        protected $disabled_countries;

        public function __construct()
        {
            global $esto_plugin_dir, $esto_plugin_url;

            self::$plugin_url = $esto_plugin_url;
            self::$plugin_dir = $esto_plugin_dir;

            $this->has_fields = false;

            // Load form fields and settings
            $this->init_form_fields();
            $this->init_settings();

            $this->admin_page_title = __( 'ESTO payment gateway', 'woo-esto' );

            // child classes can override method title in admin by setting their own value before calling parent construct
            if ( ! $this->method_title ) {
                $this->method_title = $this->admin_page_title;
            }

            if ( ! $this->method_description ) {
                $this->method_description = __( 'Payment module for paying with ESTO regular hire purchase', 'woo-esto' );
            }

            // Variables set by the user
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            $this->logo = $this->get_option( 'logo' );

            $current_language = apply_filters( 'wpml_current_language', false );
            if ( ! $current_language ) {
                $current_language = substr( get_locale(), 0, 2 );
            }
            if ( $current_language ) {
                $logo_url_key = 'logo_' . $current_language;
                $language_logo_src = $this->get_option( $logo_url_key );
                if ( $language_logo_src ) {
                    $this->logo = $language_logo_src;
                }
            }

            // $desc_logo_src = $this->get_option( $logo_src );

            if($this->get_option('show_logo') !== 'no') {
                $this->icon = $this->logo ? $this->logo : self::$plugin_url . 'assets/images/icons/logo-' . $this->id . '.svg';
                $this->icon = apply_filters( 'woocommerce_' . $this->id . '_icon', $this->icon );
            }

            $this->disabled_countries_for_this_method = $this->get_option( 'disabled_countries_for_this_method' );

            $this->min_amount = $this->get_option( 'min_amount', self::MIN_ORDER_TOTAL );
            $this->max_amount = $this->get_option( 'max_amount', self::MAX_ORDER_TOTAL );

            $this->set_on_hold_status = $this->get_option( 'set_on_hold_status' ) === 'yes';

            $this->_request = new EstoRequest($this);

            // Add actions
            add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'handle_callback'));
            add_action('woocommerce_receipt_' . $this->id, array(&$this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
            add_action( 'admin_print_scripts-woocommerce_page_wc-settings', [ $this, 'enqueue_settings_js'] );
            add_filter( 'woocommerce_valid_order_statuses_for_payment', [$this, 'add_on_hold_as_valid_status_for_order'], 10, 2 );
            add_action( 'wp_enqueue_scripts', [$this, 'enqueue_shared_css'] );
        }

        function enqueue_settings_js() {
            wp_enqueue_script( 'esto_settings_js', plugin_dir_url( __DIR__ ) . 'assets/js/settings.js', ['jquery'] );
        }

        /**
         * Initialize Form Fields
         */
        function init_form_fields() {

            $connectionModes = array(
                self::MODE_LIVE => __('Live mode', 'woo-esto'),
                self::MODE_TEST => __('Test mode', 'woo-esto'),
            );

            $endpoints = [
                WOO_ESTO_API_URL_EE => __( 'Estonia', 'woo-esto' ),
                WOO_ESTO_API_URL_LT => __( 'Lithuania', 'woo-esto' ),
                WOO_ESTO_API_URL_LV => __( 'Latvia', 'woo-esto' ),
            ];

            $this->form_fields = array(
                'enabled' => array(
                    'title'     => __('Enable/Disable', 'woo-esto'),
                    'type'      => 'checkbox',
                    'label'     => __('Enable ESTO payment module', 'woo-esto'),
                    'default'   => 'no',
                ),
                'title' => array(
                    'title'         => __('Title', 'woo-esto'),
                    'type'          => 'text',
                    'description'   => __( 'This controls the title which the user sees during checkout.', 'woo-esto' ),
                    'default'       => __( 'ESTO hire purchase', 'woo-esto' ),
                ),
                'description' => array(
                    'title'         => __('Description', 'woo-esto'),
                    'type'          => 'textarea',
                    'description'   => __( 'This controls the description which the user sees during checkout.', 'woo-esto' ),
                    'default'       => __( 'Confirm a purchase in 60 seconds and pay in installments, conveniently and quickly. Just pay later!', 'woo-esto' ),
                ),
                'show_logo' => array(
                    'title'     => __('Show logo', 'woo-esto'),
                    'type'      => 'checkbox',
                    'label'     => __('Show ESTO logo in checkout', 'woo-esto'),
                    'default'   => 'yes',
                ),
                'logo' => [
                    'title'       => __( 'Logo', 'woo-esto' ),
                    'type'        => 'image_upload',
                    'default'     => plugins_url( 'woo-esto/assets/images/icons/logo-' . $this->id . '.svg', 'woo-esto' ),
                    'description' => __( 'Click on logo to replace it.', 'woo-esto' ),
                ],
                'logo_below' => [
                    'title'       => __( 'Logo below title', 'woo-esto' ),
                    'type'      => 'checkbox',
                    'label'     => __( 'Place logos below titles instead of on the right', 'woo-esto' ),
                    'default'   => 'no',
                ],
                'connection_mode' => array(
                    'title'         => __('Payment method mode', 'woo-esto'),
                    'type'          => 'select',
                    'options'       => $connectionModes,
                    'description'   => __('Test mode is for development purposes and does not create an actual transaction', 'woo-esto' ),
                    'default'       => self::MODE_LIVE,
                ),
                'endpoint' => array(
                    'title'       => __( 'Select endpoint', 'woo-esto' ),
                    'type'        => 'select',
                    'options'     => $endpoints,
                    'description' => __( 'Choose the correct endpoint to use, depending on your country', 'woo-esto' ),
                    'default'     => WOO_ESTO_API_URL_EE,
                ),
                'shop_id' => array(
                    'title'         => __('Shop ID', 'woo-esto'),
                    'type'          => 'text',
                    'default'       => '',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.ee/stores/', __('Shop ID is set by ESTO and can be obtained here', 'woo-esto')),
                ),
                'secret_key' => array(
                    'title'         => __('Secret API key', 'woo-esto'),
                    'type'          => 'text',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.ee/stores/', __('Secret API key can be obtained here and should be kept secret', 'woo-esto')),
                    'default'       => '',
                ),
                'use_secondary_endpoint_ee' => [
                    'title'     => __( 'Endpoint for Estonia', 'woo-esto' ),
                    'type'      => 'checkbox',
                    'label'     => __( 'Use different endpoint for Estonia?', 'woo-esto' ),
                    'default'   => 'no',
                ],
                'shop_id_ee' => array(
                    'title'         => __('Shop ID for Estonia', 'woo-esto'),
                    'type'          => 'text',
                    'default'       => '',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.ee/stores/', __('Shop ID is set by ESTO and can be obtained here', 'woo-esto')),
                ),
                'secret_key_ee' => array(
                    'title'         => __('Secret API key for Estonia', 'woo-esto'),
                    'type'          => 'text',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.ee/stores/', __('Secret API key can be obtained here and should be kept secret', 'woo-esto')),
                    'default'       => '',
                ),
                'use_secondary_endpoint_lv' => [
                    'title'     => __( 'Endpoint for Latvia', 'woo-esto' ),
                    'type'      => 'checkbox',
                    'label'     => __( 'Use different endpoint for Latvia?', 'woo-esto' ),
                    'default'   => 'no',
                ],
                'shop_id_lv' => array(
                    'title'         => __('Shop ID for Latvia', 'woo-esto'),
                    'type'          => 'text',
                    'default'       => '',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.lv', __('Shop ID is set by ESTO and can be obtained here', 'woo-esto')),
                ),
                'secret_key_lv' => array(
                    'title'         => __('Secret API key for Latvia', 'woo-esto'),
                    'type'          => 'text',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.esto.lv', __('Secret API key can be obtained here and should be kept secret', 'woo-esto')),
                    'default'       => '',
                ),
                'use_secondary_endpoint_lt' => [
                    'title'     => __( 'Endpoint for Lithuania', 'woo-esto' ),
                    'type'      => 'checkbox',
                    'label'     => __( 'Use different endpoint for Lithuania?', 'woo-esto' ),
                    'default'   => 'no',
                ],
                'shop_id_lt' => array(
                    'title'         => __('Shop ID for Lithuania', 'woo-esto'),
                    'type'          => 'text',
                    'default'       => '',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.estopay.lt', __('Shop ID is set by ESTO and can be obtained here', 'woo-esto')),
                ),
                'secret_key_lt' => array(
                    'title'         => __('Secret API key for Lithuania', 'woo-esto'),
                    'type'          => 'text',
                    'description'   => sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", 'https://partner.estopay.lt', __('Secret API key can be obtained here and should be kept secret', 'woo-esto')),
                    'default'       => '',
                ),
                'min_amount' => array(
                    'title'         => __('Min amount', 'woo-esto'),
                    'type'          => 'number',
                    'description'   => __( 'The minimum amount, when this module will be shown in checkout', 'woo-esto' ),
                    'default'       => self::MIN_ORDER_TOTAL,
                ),
                'max_amount' => array(
                    'title'         => __('Max amount', 'woo-esto'),
                    'type'          => 'number',
                    'description'   => __( 'The maximum amount, when this module will be shown in checkout', 'woo-esto' ),
                    'default'       => self::MAX_ORDER_TOTAL,
                ),
                'disabled_countries' => array(
                    'title'       => __( 'Disabled countries', 'woo-esto' ),
                    'type'        => 'multiselect',
                    'class'       => 'wc-enhanced-select',
                    'options'     => WC()->countries ? WC()->countries->get_countries() : esto_get_countries(),
                    'default'     => [],
                    'description' => __( 'Specify countries where ESTO methods should be disabled. Leave empty to sell everywhere.', 'woo-esto' ),
                    'desc_tip'    => true
                ),
                'disabled_countries_for_this_method' => array(
                    'title'       => __( 'Disabled countries for this method', 'woo-esto' ),
                    'type'        => 'multiselect',
                    'class'       => 'wc-enhanced-select',
                    'options'     => WC()->countries ? WC()->countries->get_countries() : esto_get_countries(),
                    'default'     => [],
                    'description' => __( 'Specify countries where this method should be disabled. Leave empty to sell everywhere.', 'woo-esto' ),
                    'desc_tip'    => true
                ),
                'set_on_hold_status' => [
                    'title'   => __( 'Set order status to "on-hold" until payment confirmation', 'woo-esto' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Check this option to have orders created with "on-hold" status instead of "pending"', 'woo-esto' ),
                    'default' => 'no',
                ],
                'order_prefix' => [
                    'title'         => __( 'Order prefix', 'woo-esto' ),
                    'type'          => 'text',
                    'description'   => __( 'A prefix to add to all future order numbers done via this payment method. Prefix "T" makes order ID "T-12345"', 'woo-esto' ),
                    'default'       => '',
                ],
            );

            $this->description_logos = [
                'show_desc_logo' => array(
                    'title'     => __( 'Show description logo', 'woo-esto'),
                    'type'      => 'checkbox',
                    'label'     => __('Show ESTO logo in payment method description', 'woo-esto'),
                    'default'   => 'no',
                ),
            ];

            $this->language_specific_logos = [];

            $languages = apply_filters( 'wpml_active_languages', false );

            if ( $languages ) {
                foreach ( $languages as $language_key => $language ) {
                    $this->description_logos['desc_logo_' . $language_key] = [
                        'title'       => sprintf( __( 'Description logo in %s', 'woo-esto' ), isset( $language['translated_name'] ) ? $language['translated_name'] : $language['display_name'] ),
                        'type'        => 'image_upload',
                        'default'     => '',
                        'description' => __( 'Click on logo to replace it or enter url above.', 'woo-esto' ),
                    ];

                    $default_lang_logo = '';
                    $lang_logo_names = [
                        'et' => [
                            'esto' => 'logo-esto.svg',
                            'esto_x' => 'logo-esto_x.svg',
                            'pay_later' => 'logo-pay_later.svg',
                        ],
                        'en' => [
                            'esto' => 'logo-esto-en.svg',
                            'esto_x' => 'logo-esto_x-en.svg',
                            'pay_later' => 'logo-pay_later-en.svg',
                        ],
                        'lv' => [
                            'esto' => 'logo-esto-lv.svg',
                            'esto_x' => 'logo-esto_x-lv.svg',
                            'pay_later' => 'logo-pay_later-lv.svg',
                        ],
                        'lt' => [
                            'esto' => 'logo-esto-lt.svg',
                            'esto_x' => 'logo-esto_x-lt.svg',
                            'pay_later' => 'logo-pay_later-lt.svg',
                        ],
                        'ru' => [
                            'esto' => 'logo-esto-ru.svg',
                            'esto_x' => 'logo-esto_x-ru.svg',
                            'pay_later' => 'logo-pay_later-ru.svg',
                        ],
                    ];

                    if ( isset( $lang_logo_names[ $language_key ] ) && isset( $lang_logo_names[ $language_key ][ $this->id ] ) ) {
                        $default_lang_logo = self::$plugin_url . 'assets/images/icons/' . $lang_logo_names[ $language_key ][ $this->id ];
                    }

                    $this->language_specific_logos['logo_' . $language_key] = [
                        'title'       => sprintf( __( 'Main logo in %s', 'woo-esto' ), isset( $language['translated_name'] ) ? $language['translated_name'] : $language['display_name'] ),
                        'type'        => 'image_upload',
                        'default'     => $default_lang_logo,
                        'description' => __( 'Click on logo to replace it or enter url above.', 'woo-esto' ),
                    ];
                }
            }
            else {
                $this->description_logos['desc_logo'] = [
                    'title'       => __( 'Description logo', 'woo-esto' ),
                    'type'        => 'image_upload',
                    'default'     => '',
                    'description' => __( 'Click on logo to replace it or enter url above.', 'woo-esto' ),
                ];
            }

            $this->form_fields = array_slice( $this->form_fields, 0, 3, true )
                + $this->description_logos
                + array_slice( $this->form_fields, 3, count( $this->form_fields ) - 1, true );

            if ( ! empty( $this->language_specific_logos ) ) {
                $insert_position = array_search( 'logo', array_keys( $this->form_fields ) ) + 1;
                $this->form_fields = array_slice( $this->form_fields, 0, $insert_position, true )
                    + $this->language_specific_logos
                    + array_slice( $this->form_fields, $insert_position, count( $this->form_fields ) - 1, true );
            }
        }

        /**
         * Load shop configuration
         */
        function init_settings() {
            parent::init_settings();

            // extending classes can use main gateway configuration
            $settings = get_option( $this->plugin_id  . 'esto_settings', null );

            if ( $settings ) {
                $this->connection_mode    = isset( $settings['connection_mode'] ) ? $settings['connection_mode'] : self::MODE_TEST;
                $this->disabled_countries = isset( $settings['disabled_countries'] ) ? (array)$settings['disabled_countries'] : [];

                $this->endpoint = esto_get_api_url();
                $this->shop_id = esto_get_api_field( 'shop_id' );
                $this->secret_key = esto_get_api_field( 'secret_key' );

                if ( ! $this->endpoint ) {
                    $this->endpoint = 'https://api.esto.ee/';
                }
            }
        }

        /**
         * https://stackoverflow.com/a/22876571
         */
        function generate_image_upload_html( $key, $data ) {

            ob_start();
            $image_src = $this->get_option( $key );
            if ( ! $image_src ) {
                $image_src = 'https://via.placeholder.com/100x26';
            }

            if ( substr( $key, 0, 4 ) == 'logo' ) {
                $style = 'max-width: 100px; max-height: 26px;';
            }
            elseif ( substr( $key, 0, 9 ) == 'desc_logo' ) {
                $style = 'max-width: 200px; max-height: 200px;';
            }
            else {
                $style = 'max-width: 100%; height: auto;';
            }

            ?>
            <img src="<?= $image_src ?>" class="current_logo_img" data-key="<?= $key ?>" style="<?= $style ?>">
            <?php
            $img_html = ob_get_clean();

            ob_start();
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $( '.current_logo_img[data-key="<?= $key ?>"]' ).on( 'click', function( e ) {
                        e.preventDefault();

                        var _this = $( this );

                        var logo_uploader = wp.media({
                            title: '<?php _e( 'Choose logo', 'woo-esto' ) ?>',
                            button: {
                                text: '<?php _e( 'Set Logo', 'woo-esto' ) ?>'
                            },
                            multiple: false
                        })
                            .on( 'select', function() {
                                var attachment = logo_uploader.state().get( 'selection' ).first().toJSON();
                                _this.attr( 'src', attachment.url );
                                _this.closest( 'td' ).find( 'input' ).val( attachment.url );
                            })
                            .open();
                    });
                });
            </script>
            <?php
            $image_upload_js = ob_get_clean();

            return str_replace( ['</td>', 'type="image_upload"'], [$img_html . '</td>', 'type="text"'], $this->generate_text_html( $key, $data ) ) . $image_upload_js;
        }

        function generate_text_html( $key, $data ) {
            $parent_html = parent::generate_text_html( $key, $data );
            if ( ( in_array( $key, ['shop_id_ee', 'secret_key_ee'] ) && $this->get_option( 'use_secondary_endpoint_ee' ) != 'yes' )
                || ( in_array( $key, ['shop_id_lv', 'secret_key_lv'] ) && $this->get_option( 'use_secondary_endpoint_lv' ) != 'yes' )
                || ( in_array( $key, ['shop_id_lt', 'secret_key_lt'] ) && $this->get_option( 'use_secondary_endpoint_lt' ) != 'yes' )
            ) {
                $parent_html = str_replace( '<tr ', '<tr class="hidden"', $parent_html );
            }
            return $parent_html;
        }

        public function is_available() {
            if( ! parent::is_available()) {
                return false;
            }

            $disabled_countries                 = (array)$this->disabled_countries;
            $disabled_countries_for_this_method = (array)$this->disabled_countries_for_this_method;

            if ( ! empty( $disabled_countries ) || ! empty( $disabled_countries_for_this_method ) ) {
                $customer = WC()->customer;
                if ( ! $customer ) {
                    return false;
                }

                if ( method_exists( WC()->customer, 'get_billing_country' ) ) {
                    $country = WC()->customer->get_billing_country();
                }
                else {
                    $country = WC()->customer->get_country();
                }

                if ( $country && ( in_array( $country, $disabled_countries ) || in_array( $country, $disabled_countries_for_this_method ) ) ) {
                    return false;
                }
            }

            // If in checkout
            if($cart = $this->_getWooCommerce()->cart) {
                if($this->_isWoo32())
                {
                    $total = $this->get_order_total();
                }
                else
                {
                    $total = $cart->cart_contents_total;
                }

                if ( $total < $this->min_amount ) {
                    return false;
                }

                if ( $total > $this->max_amount ) {
                    return false;
                }
            }

            return true;
        }

        /**
         * Make sure the correct fields have been entered by
         * the admin
         */
        public function validate_fields() {

            if ( empty( $this->shop_id ) ) {
                $this->errorNotice(__('Shop ID is required!', 'woo-esto'));
                return false;
            }

            if ( empty( $this->secret_key ) ) {
                $this->errorNotice(__('Secret key is required!', 'woo-esto'));
                return false;
            }

            // min can be 0, e.g. for "pay later" submethod
            if ( ! isset( $this->min_amount ) ) {
                $this->errorNotice(__('Min amount is required!', 'woo-esto'));
                return false;
            }

            if ( empty( $this->max_amount ) ) {
                $this->errorNotice(__('Max amount is required!', 'woo-esto'));
                return false;
            }

            return true;

        }

        /**
         * Admin Panel Options
         */
        function admin_options() {
            ?>
            <h3><?= $this->admin_page_title ?></h3>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table>
            <?php
        }

        /**
         * ESTO payment has no fields, but we want to
         * show description if given
         */
        public function payment_fields() {
            $description = $this->get_description();
            if ( $description ) {
                echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine.
            }

            if ( $this->get_option( 'show_desc_logo' ) === 'yes' ) {
                $option_key = 'desc_logo';

                $current_language = apply_filters( 'wpml_current_language', false );
                if ( $current_language ) {
                    $option_key .= '_' . $current_language;
                }

                $desc_logo_src = $this->get_option( $option_key );

                if ( $desc_logo_src ) : ?>
                    <img src="<?= $this->get_option( $option_key ) ?>" class="esto-description-logo">
                <?php endif;
            }
        }

        /**
         * Process the payment and return the result
         * and redirect url
         */
        public function process_payment($orderId) {

            $order = new WC_Order($orderId);

            if ( $this->set_on_hold_status && $order && method_exists( $order, 'update_status' ) ) {
                // disable admin new order email in this stage
                add_filter( 'woocommerce_email_enabled_new_order', function( $email_obj, $email ) {
                    return false;
                }, 10, 2 );

                $order->add_meta_data( 'esto_on_hold_status_is_valid', true, true ); // update_status calls 'save' function
                $order->update_status( 'on-hold' );
            }

            return array(
                'result'    => 'success',
                'redirect'  => $this->_getOrderConfirmationUrl($order),
            );

        }

        protected function _getOrderConfirmationUrl(WC_Order $order) {
            $url = $order->get_checkout_payment_url(true);

            return $url;
        }

        function receipt_page($orderId) {

            echo '<p>' . __('Thank you for the order, please click on the button to proceed.', 'woo-esto') . '</p>';

            echo $this->generateRedirectForm($orderId);

        }

        /**
         * Generate the form that will automatically be clicked
         * after the user is redirected to an intermediate page
         * between the shop and ESTO
         */
        protected function generateRedirectForm($orderId) {

            $order = new WC_Order($orderId);
            $this->_request->setOrder($order);

            $jsBlock = '
                if (window.jQuery) {
                    // jQuery is loaded
                    jQuery("body").block({ 
                            message: "'
                . __('Thank you for the order. You will be redirected to ESTO...', 'woo-esto')
                . '<br><input form=\"esto_payment_form\" type=\"submit\" class=\"button-alt\" id=\"esto_payment_submit_msg\" value=\"' . __($this->title, 'woo-esto') . '\" />", 
                            overlayCSS: 
                            { 
                                background: "#fff", 
                                opacity: 0.6 
                            },
                            css: { 
                                padding:        20, 
                                textAlign:      "center", 
                                color:          "#555", 
                                border:         "3px solid #aaa", 
                                backgroundColor:"#fff", 
                                lineHeight:     "32px"
                            } 
                        });
                    jQuery("#esto_payment_submit").click();

                }
                // jQuery is not loaded OR refuses to run the code above
                document.getElementById("esto_payment_submit").click();
                ';
            if ($this->_isWoo23()) {
                wc_enqueue_js($jsBlock);
            } else {
                $this->_getWooCommerce()->add_inline_js($jsBlock);
            }

            return $this->_request->getRedirectBlock();
        }

        /**
         * Check the response from ESTO
         */
        public function handle_callback() {

            if(isset($_REQUEST['json']) && $_REQUEST['json']) {
                @ob_clean();
                $_REQUEST = stripslashes_deep($_REQUEST);

                $validationResult = $this->_request->validateCallback($_REQUEST);
                woo_esto_log( 'payment callback: ' . print_r( $validationResult, true ) );

                if($this->checkCallbackIsValid($validationResult)) {
                    $this->onSuccessfulRequest($validationResult);
                } else {
                    wp_redirect(get_option('home'));
                }
            }

        }

        public function checkCallbackIsValid($validationResult)
        {
            return $validationResult['status'] != self::TRX_FAILED;
        }

        public function get_order_from_callback( $validationResult ) {
            $order_id = $validationResult['reference'];

            if ( strpos( $order_id, '-' ) !== false ) {
                // order id was prefixed

                if ( function_exists( 'wc_get_orders' )
                    && is_callable( 'Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled' )
                    && Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
                    $orders_query = wc_get_orders( [
                        'meta_query' => [
                            [
                                'key'   => 'esto_prefixed_order_id',
                                'value' => $order_id,
                            ],
                        ],
                        'return'      => 'ids',
                    ] );
                }
                else {
                    $orders_query = get_posts( [
                        'post_type'   => 'shop_order',
                        'meta_key'    => 'esto_prefixed_order_id',
                        'meta_value'  => $order_id,
                        'fields'      => 'ids',
                        'post_status' => 'any',
                    ] );
                }

                if ( $orders_query ) {
                    $order_id = reset( $orders_query );
                }
            }


            // check if an order nr was passed to Esto instead of order id

            if ( function_exists( 'wc_get_orders' )
                && is_callable( 'Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled' )
                && Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
                $orders_query = wc_get_orders( [
                    'meta_query' => [
                        [
                            'key'   => 'esto_order_nr',
                            'value' => $order_id,
                            'compare' => '='
                        ],
                    ],
                    'return'      => 'ids',
                ] );
            }
            else {
                $orders_query = get_posts( [
                    'post_type'   => 'shop_order',
                    'meta_key'    => 'esto_order_nr',
                    'meta_value'  => $order_id,
                    'fields'      => 'ids',
                    'post_status' => 'any',
                ] );
            }

            if ( $orders_query ) {
                $order_id = reset( $orders_query );
            }

            return new WC_Order( (int)$order_id );
        }

        /**
         * Callback request from ESTO was successful
         */
        public function onSuccessfulRequest($validationResult)
        {
            $url = get_option('home');

            // If the payment was completed successfully
            if($validationResult['status'] == self::TRX_APPROVED || $validationResult['status'] == self::TRX_COMPLETED) {
                $order = $this->get_order_from_callback( $validationResult );

                if ( $order->get_total() > 0 ) {
                    $epsilon = 0.001;

                    if ( abs( (float)$order->get_total() - (float)$validationResult['amount'] ) > $epsilon ) {
                        echo 'Order amount ' . $order->get_total() . ' does not match the actual paid amount ' . $validationResult['amount'];
                        exit;
                    }
                }

                // Reduce order stock
                if ( ! $order->has_status('processing') && ! $order->has_status('completed') ) {
                    // if($order->has_status('pending')) {
                    if($validationResult['is_test']) {
                        $order->add_order_note('TEST MODE');
                    }

                    //$order->add_order_note(__('User completed payment'), 'woo-esto');

                    // send admin new order email if order was automatically put on hold until payment confirmation
                    if ( method_exists( $order, 'get_meta' ) && $order->get_meta( 'esto_on_hold_status_is_valid' ) ) {
                        $emails = WC()->mailer()->get_emails();
                        if ( isset( $emails['WC_Email_New_Order'] ) ) {
                            $emails['WC_Email_New_Order']->trigger( $order->get_id(), $order );
                        }
                    }

                    // payment_complete() takes care of stock
                    if ( ! $this->_isWoo32() ) {
                        $order->reduce_order_stock();
                    }

                    // Payment complete
                    $this->_getWooCommerce()->cart->empty_cart();
                    $order->payment_complete();
                }

                $url = $this->get_return_url($order);
            }
            elseif ( $validationResult['status'] == self::TRX_REJECTED ) {
                $order = $this->get_order_from_callback( $validationResult );

                // change on-hold order status to cancelled, to release stock
                if ( $order->has_status( 'on-hold' ) && method_exists( $order, 'get_meta' ) && $order->get_meta( 'esto_on_hold_status_is_valid' ) ) {
                    $order->update_status( 'cancelled' );
                }
            }

            if ( $validationResult['auto'] ) {
                $url = add_query_arg( 'esto_auto_callback', '1', $url );
            }

            wp_redirect($url);
            exit;
        }

        function errorNotice($text) {
            wc_add_notice($text, 'error');
        }

        function successNotice($text) {
            wc_add_notice($text, 'success');
        }

        /* Helpers for Woocommerce */

        /**
         * <p>Returns true, if WooCommerce version is 2.0</p>
         * @return bool
         */
        protected function _isWoo20() {
            return substr($this->_getWooCommerce()->version, 0, 3) == "2.0";
        }
        /**
         * <p>Returns true, if WooCommerce version is 2.3 or greater</p>
         * @return bool
         */
        protected function _isWoo21() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '2.1', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '2.1', '>=');
        }
        /**
         * <p>Returns true, if WooCommerce version is 2.3 or greater</p>
         * @return bool
         */
        protected function _isWoo23() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '2.3', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '2.3', '>=');
        }
        /**
         * <p>Returns true, if WooCommerce version is 2.3 or greater</p>
         * @return bool
         */
        protected function _isWoo24() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '2.4', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '2.4', '>=');
        }
        /**
         * <p>Returns true, if WooCommerce version is 2.5 or greater</p>
         * @return bool
         */
        protected function _isWoo25() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '2.5', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '2.5', '>=');
        }
        /**
         * <p>Returns true, if WooCommerce version is 2.5 or greater</p>
         * @return bool
         */
        protected function _isWoo30() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '3.0', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '3.0', '>=');
        }

        /**
         * <p>Returns true, if WooCommerce version is 3.0 or greater</p>
         * @return bool
         */
        protected function _isWoo32() {
            if (defined('WOOCOMMERCE_VERSION')) {
                return version_compare(WOOCOMMERCE_VERSION, '3.2', '>=');
            }
            return version_compare($this->_getWooCommerce()->version, '3.2', '>=');
        }

        protected function _getWooCommerce() {
            global $woocommerce;
            return $woocommerce;
        }

        public function __get( $property ) {
            switch ( $property ) {
                case 'shop_id':
                    return $this->shop_id;
                case 'secret_key':
                    return $this->secret_key;
                case 'endpoint':
                    return $this->endpoint;
                case 'connection_mode':
                    return $this->connection_mode;
            }
        }

        public function add_on_hold_as_valid_status_for_order( $statuses, $order ) {
            if ( ! in_array( 'on-hold', $statuses ) && method_exists( $order, 'get_meta' ) && $order->get_meta( 'esto_on_hold_status_is_valid' ) ) {
                $statuses[] = 'on-hold';
            }

            return $statuses;
        }

        public function enqueue_shared_css() {
            if ( $this->id == 'esto' && is_checkout() && $this->get_option( 'logo_below' ) === 'yes' ) {
                wp_enqueue_style( 'woo-esto-checkout-shared-css', plugins_url( 'assets/css/checkout-shared.css', dirname( __FILE__ ) ), false, filemtime( dirname( __FILE__, 2 ) . '/assets/css/checkout-shared.css' ) );
            }
        }
    }

    require_once ( 'class-wc-esto-x-payment.php' );
    require_once ( 'class-wc-pay-later-payment.php' );
    require_once ( 'class-wc-esto-pay-payment.php' );
    require_once ( 'class-wc-esto-card-payment.php' );
}

function woocommerce_esto_payment_addmethod($methods) {
    /**
     * Add the gateway to WooCommerce
     * */
    $methods[] = 'WC_Esto_Pay_Payment';
    $methods[] = 'WC_Esto_Card_Payment';
    $methods[] = 'WC_Esto_Payment';
    $methods[] = 'WC_Esto_X_Payment';
    $methods[] = 'WC_Pay_Later_Payment';

    return $methods;
}

add_filter('woocommerce_payment_gateways', 'woocommerce_esto_payment_addmethod');
