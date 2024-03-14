<?php

if ( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists('WPDesk_Apaczka_Shipping') ) {
    class WPDesk_Apaczka_Shipping extends WC_Shipping_Method
    {

        const APACZKA_PICKUP_COURIER = 1;

        const APACZKA_PICKUP_SELF = 2;

        const APACZKA_PICKUP_PAeRCEL_MACHINE = 3;

        public $api = false;

        static $services = [];

        static $order_status_completed_auto;

        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct($instance_id = 0)
        {
            $this->instance_id = absint($instance_id);
            $this->id = 'apaczka';

            self::$services = [
                'UPS_K_STANDARD' => __('UPS Standard', 'apaczka'),
                'DHLSTD' => __('DHL Standard', 'apaczka'),
                'KEX_EXPRESS' => __('K-EX Express', 'apaczka'),
                'DPD_CLASSIC' => __('DPD', 'apaczka'),
                'FEDEX' => __('FedEx', 'apaczka'),
                'TNT' => __('TNT', 'apaczka'),
                'POCZTA_POLSKA_E24' => __('Pocztex 24', 'apaczka'),
                'INPOST' => __('Inpost', 'apaczka'),
                'UPS_Z_STANDARD' => __('UPS Standard Zagranica', 'apaczka'),
                'TNT_Z' => __('TNT Zagranica', 'apaczka'),
                'DPD_CLASSIC_FOREIGN' => __('DPD Classic Foreign', 'apaczka'),
                'APACZKA_DE' => __('Apaczka Niemcy', 'apaczka'),
                'PACZKOMAT' => __('InPost Paczkomaty', 'apaczka'),
            ];

            $this->method_title = __('Apaczka', 'apaczka');
            $this->method_description = __('Apaczka', 'apaczka');
            $this->method_description
                = __(' Zarejestruj się na <a href="https://www.apaczka.pl/?register=1&register_promo_code=WooCommerce" target="_blank">www.apaczka.pl &rarr;</a>',
                'apaczka');
            $this->enabled = $this->get_option('enabled');
            $this->title = $this->get_option('title');
            self::$order_status_completed_auto
                = $this->get_option('order_status_completed_auto');
            $this->init();

            add_action('woocommerce_update_options_shipping_' . $this->id,
                [$this, 'process_admin_options']);

            add_action('add_meta_boxes', [$this, 'add_meta_boxes'], 10, 2);

            add_action('woocommerce_checkout_update_order_meta',
                [$this, 'woocommerce_checkout_update_order_meta'], 100, 2);

            add_action('save_post', [$this, 'save_post']);

            add_action('woocommerce_after_checkout_validation',
                [$this, 'woocommerce_checkout_process'], 10, 2);

        }

        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        function init()
        {

            // Load the settings API
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->tax_status = $this->get_option('tax_status');

            $this->login = $this->get_option('login');
            $this->password = $this->get_option('password');
            $this->api_key = $this->get_option('api_key');
            $this->test_mode = false;

            $this->cost = $this->get_option('cost');
            $this->cost_cod = $this->get_option('cost_cod');

        }

        /**
         * Initialise Settings Form Fields
         */
        public function init_form_fields()
        {
            $this->form_fields = include('settings-apaczka.php');
        }

        public function get_api()
        {
            if ( $this->api === false ) {
                if ( $this->login != '' && $this->password != ''
                    && $this->api_key != ''
                ) {
                    $this->api = new apaczkaApi($this->login, $this->password,
                        $this->api_key, $this->test_mode == 'yes');
                }
            }

            return $this->api;
        }

        public function display_errors_config()
        {
            $class = 'notice notice-error';
            try {
                $api = $this->get_api();
            } catch (Exception $e) {
                $message = __('Błąd połączenia z API Apaczka.', 'apaczka');
                $message .= ' ' . $e->getMessage();
                printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
                $api = false;
            }
            if ( $api !== false ) {
                try {
                    $validate = $api->parse_return($api->validateAuthData());
                    if ( $validate[ 'return' ][ 'isValid' ] != '1' ) {
                        $message = __('Błąd połączenia z API Apaczka.',
                            'apaczka');
                        $message .= ' '
                            . $validate[ 'return' ][ 'result' ][ 'messages' ][ 'Message' ][ 'description' ];
                        printf('<div class="%1$s"><p>%2$s</p></div>', $class,
                            $message);
                    }
                } catch (Exception $e) {
                    $message = __('Błąd połączenia z API Apaczka.', 'apaczka');
                    $message .= ' ' . $e->getMessage();
                    printf('<div class="%1$s"><p>%2$s</p></div>', $class,
                        $message);
                }
            }
        }

        public function generate_settings_html($form_fields = [], $echo = true)
        {
            parent::generate_settings_html($form_fields);
            $this->display_errors_config();
        }

        public function add_meta_boxes($post_type, $post)
        {
            if ( $post->post_type == 'shop_order' ) {
                $order_id = $post->ID;
                $order = wc_get_order($post->ID);
                $apaczka = get_post_meta($order_id, '_apaczka', true);

                $delivery_point = get_post_meta($order_id, 'apaczka_delivery_point', true);

	            if ( isset( $delivery_point[ 'apm_foreign_access_point_id' ] ) && isset( $delivery_point[ 'apm_supplier' ] ) && 'INPOST' === $delivery_point[ 'apm_supplier' ] ) {
		            $parcel_machine_to = $delivery_point[ 'apm_foreign_access_point_id' ];
	            }

                if ( $apaczka == '' ) {
                    $data = [
                        'service' => $this->get_option('service', ''),
                        'package_width' => $this->get_option('package_width',
                            ''),
                        'package_depth' => $this->get_option('package_depth',
                            ''),
                        'package_height' => $this->get_option('package_height',
                            ''),
                        'package_weight' => $this->get_option('package_weight',
                            ''),
                        'package_contents' => $this->get_option('package_contents',
                            ''),
                        'cod_amount' => '',
                        'insurance' => $this->get_option('insurance', ''),
                        'pickup_date' => '',
                        'pickup_hour_from' => $this->get_option('pickup_hour_from',
                            ''),
                        'pickup_hour_to' => $this->get_option('pickup_hour_to',
                            ''),
                        'parcel_machine_from' => $this->get_option('default_parcel_locker',
                            ''),

                    ];

                    if ( isset( $parcel_machine_to ) ) {
	                    $data[ 'parcel_machine_to' ] = $parcel_machine_to;
                    }

                    if ( $order->get_payment_method() == 'cod' ) {
                        $data[ 'cod_amount' ] = $order->get_total();
                        $data[ 'insurance' ] = 'yes';
                    }
                    $apaczka = [];
                    $apaczka[ 1 ] = $data;
                }
                if ( $apaczka != '' ) {
                    foreach ($apaczka as $id => $data) {
                        add_meta_box(
                            'apaczka_' . $id,
                            __('Apaczka', 'woocommerca-apaczka'),
                            [$this, 'order_metabox'],
                            'shop_order',
                            'side',
                            'default',
                            ['id' => $id, 'data' => $data]
                        );
                    }
                }
            }
        }

        public function order_metabox($post, $metabox_data)
        {
            self::order_metabox_content($post, $metabox_data);
        }

        public static function order_metabox_content(
            $post,
            $metabox_data,
            $output = true
        ) {

            if ( ! $output ) {
                ob_start();
            }

            $order_id = $post->ID;

            $order = wc_get_order($order_id);

            $service = $order->get_meta('service');

            $has_order_parcel_machine
                = $order->get_meta('_apaczka_parcel_machine_id');


            $apaczka = $metabox_data[ 'args' ][ 'data' ];
            $id = $metabox_data[ 'args' ][ 'id' ];

            $services = self::$services;
            $package_send = false;

            if ( isset($apaczka[ 'apaczka_order' ]) ) {
                $package_send = true;
                $url_waybill
                    = admin_url('admin-ajax.php?action=apaczka&apaczka_action=get_waybill&security='
                    . wp_create_nonce('apaczka_ajax_nonce')
                    . '&apaczka_order_id='
                    . $apaczka[ 'apaczka_order' ][ 'id' ]);
            }

            if ( false === $package_send ) {
                $is_parcel_locker = get_post_meta($order_id,
                    '_is_parcel_locker');
                if ( isset($is_parcel_locker[ 0 ])
                    && '1' === $is_parcel_locker[ 0 ]
                ) {
                    $apaczka[ 'service' ] = 'PACZKOMAT';
                }
            }

            $options_hours = [
            ];
            for ($h = 9; $h < 20; $h++) {
                if ( $h < 10 ) {
                    $h = '0' . $h;
                }
                $options_hours[ $h . ':00' ] = $h . ':00';
                if ( $h < 19 ) {
                    $options_hours[ $h . ':30' ] = $h . ':30';
                }
            }

            $shipx_api = new shipxApi();
            $parcel_machine_parcel_sizes = $shipx_api->get_parcel_machine_parcel_sizes();

            wp_nonce_field(plugin_basename(__FILE__), 'apaczka_nonce');
            include('views/html-order-metabox.php');

            if ( ! $output ) {
                $out = ob_get_clean();

                return $out;
            }
        }

        public function save_post($post_id)
        {
            // verify if this is an auto save routine.
            // If it is our form has not been submitted, so we dont want to do anything
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }
            // verify this came from the our screen and with proper authorization,
            // because save_post can be triggered at other times
            if ( (isset ($_POST[ 'apaczka_nonce' ]))
                && ( ! wp_verify_nonce($_POST[ 'apaczka_nonce' ],
                    plugin_basename(__FILE__)))
            ) {
                return;
            }
            // Check permissions
            if ( (isset ($_POST[ 'post_type' ]))
                && ('shop_order' != $_POST[ 'post_type' ])
            ) {
                return;
            }
            // OK, we're authenticated: we need to find and save the data
            if ( isset ($_POST[ '_apaczka' ]) ) {

                $stop = true;

                if ( isset($_POST[ 'parcel_machine_id' ]) ) {
                    $parcel_machine_id = $_POST[ 'parcel_machine_id' ];
                    
                    $parcel_machine_address['city'] = sanitize_text_field( $_POST[ 'parcel_machine_city' ] );
	                $parcel_machine_address['street'] = sanitize_text_field( $_POST[ 'parcel_machine_street' ] );
	                $parcel_machine_address['building_number'] = sanitize_text_field( $_POST[ 'parcel_machine_building_number' ] );
	                $parcel_machine_address['post_code'] = sanitize_text_field( $_POST[ 'parcel_machine_post_code' ] );
	                $parcel_machine_address['machine_province'] = sanitize_text_field( $_POST[ 'parcel_machine_province' ] );
	                
                    update_post_meta($post_id, '_apaczka_parcel_machine_id',
                        $parcel_machine_id);
                    update_post_meta($post_id, '_apaczka_parcel_machine_address',
	                    $parcel_machine_address);
                }

                $apaczka_post = $_POST[ '_apaczka' ];
                $_apaczka = get_post_meta($post_id, '_apaczka', true);
                if ( $_apaczka != '' ) {
                    foreach ($_apaczka as $id => $data) {
                        if ( empty($data[ 'apaczka_order' ]) ) {
                            $_apaczka[ $id ] = $apaczka_post[ $id ];
                        }
                    }
                } else {
                    $_apaczka = $apaczka_post;
                }

                update_post_meta($post_id, '_apaczka', $_apaczka);
            }
        }

        public function woocommerce_checkout_update_order_meta(
            $order_id,
            $posted
        ) {
            $order = new WC_Order($order_id);
            $shippings = $order->get_shipping_methods();
            $apaczka = [];
            //todo tutaj należy poprawnie wykryć apaczkkę!!!!


            $selected_method_in_cart
                = $this->flexible_shipping_method_selected($order, 'apaczka');

            $selected_method_in_cart_cod
                = $this->flexible_shipping_method_selected($order,
                'apaczka_cod');

            if ( false === $selected_method_in_cart
                && false == $selected_method_in_cart_cod
            ) {
                return;
            }


            $data = [
                'service' => $this->get_option('service', ''),
                'package_width' => $this->get_option('package_width',
                    ''),
                'package_depth' => $this->get_option('package_depth',
                    ''),
                'package_height' => $this->get_option('package_height',
                    ''),
                'package_weight' => $this->get_option('package_weight',
                    ''),
                'package_contents' => $this->get_option('package_contents',
                    ''),
                'cod_amount' => '',
                'insurance' => $this->get_option('insurance', ''),
                'pickup_date' => '',
                'pickup_hour_from' => $this->get_option('pickup_hour_from',
                    ''),
                'pickup_hour_to' => $this->get_option('pickup_hour_to',
                    ''),
            ];
            if ( $order->get_payment_method() == 'cod' ) {
                $data[ 'cod_amount' ] = $order->get_total();
                $data[ 'insurance' ] = 'yes';
            }
            $apaczka[ $this->id ] = $data;
            update_post_meta($order_id, '_apaczka', $apaczka);
        }


        private function flexible_shipping_method_selected(
            $order,
            $shipping_method_integration
        ) {
            if ( is_numeric($order) ) {
                $order = wc_get_order($order);
            }
            $shippings = $order->get_shipping_methods();
            $all_shipping_methods
                = flexible_shipping_get_all_shipping_methods();
            if ( isset($all_shipping_methods[ 'flexible_shipping' ]) ) {
                $flexible_shipping_rates
                    = $all_shipping_methods[ 'flexible_shipping' ]->get_all_rates();
                foreach ($shippings as $id => $shipping) {
                    if ( isset($flexible_shipping_rates[ $shipping[ 'method_id' ] ]) ) {
                        $shipping_method
                            = $flexible_shipping_rates[ $shipping[ 'method_id' ] ];
                        if ( $shipping_method[ 'method_integration' ]
                            == $shipping_method_integration
                        ) {
                            return $shipping_method;
                        }
                    }
                }
            }

            return false;
        }

        private static function toBool($value)
        {
            return 'true' === (string)$value;
        }

        public static function ajax_create_package(
            $pickupMethod = self::APACZKA_PICKUP_COURIER
        ) {
            $ret = ['status' => 'ok'];

            $order_id = $_POST[ 'order_id' ];
            $order = wc_get_order($order_id);
            $post = get_post($order_id);
            $id = $_POST[ 'id' ];

            $_apaczka = get_post_meta($order_id, '_apaczka', true);

            if ( "" === $_apaczka ) {
                $_apaczka = [];
                $data = [];
            } else {
                $data = $_apaczka[ $id ];
            }

            $data[ 'service' ] = $_POST[ 'service' ];
            $data[ 'parcel_dimensions_template' ] = $_POST[ 'parcel_dimensions_template' ];
            $data[ 'package_width' ] = $_POST[ 'package_width' ];
            $data[ 'package_depth' ] = $_POST[ 'package_depth' ];
            $data[ 'package_height' ] = $_POST[ 'package_height' ];
            $data[ 'package_weight' ] = $_POST[ 'package_weight' ];
            $data[ 'package_contents' ] = $_POST[ 'package_contents' ];
            $data[ 'cod_amount' ] = $_POST[ 'cod_amount' ];
            $data[ 'insurance' ] = $_POST[ 'insurance' ];
            $data[ 'pickup_date' ] = $_POST[ 'pickup_date' ];
            $data[ 'pickup_hour_from' ] = $_POST[ 'pickup_hour_from' ];
            $data[ 'pickup_hour_to' ] = $_POST[ 'pickup_hour_to' ];
            $data[ 'przes_nietyp' ] = isset($_POST[ 'przes_nietyp' ])
                ? self::toBool($_POST[ 'przes_nietyp' ]) : false;
            $data[ 'zwrot_dok' ] = isset($_POST[ 'zwrot_dok' ])
                ? self::toBool($_POST[ 'zwrot_dok' ]) : false;
            $data[ 'dost_sob' ] = isset($_POST[ 'dost_sob' ])
                ? self::toBool($_POST[ 'dost_sob' ]) : false;
            $data[ 'parcel_machine_to' ] = isset( $_POST[ 'parcel_machine_to' ] ) ? $_POST[ 'parcel_machine_to' ] : '';
            $data[ 'parcel_machine_from' ] = isset( $_POST[ 'parcel_machine_from' ] ) ? $_POST[ 'parcel_machine_from' ] : '';


            if ( $data[ 'cod_amount' ] != '' ) {
                $data[ 'insurance' ] = 'yes';
            }


            $_apaczka[ $id ] = $data;


            update_post_meta($order_id, '_apaczka', $_apaczka);


            $shipping_methods = WC()->shipping()->get_shipping_methods();


            if ( empty($shipping_methods) ) {
                $shipping_methods = WC()->shipping()->load_shipping_methods();
            }
            $shipping_method = $shipping_methods[ 'apaczka' ];
            $country = $order->get_shipping_country();
            if ( 'PL' === $country ) {
                $isDomestic = 'true';
            } else {
                $isDomestic = 'false';
            }
            $apaczka_order = new ApaczkaOrder($isDomestic);

            $apaczka_order->notificationDelivered
                = $apaczka_order->createNotification(true, false, false, false);
            $apaczka_order->notificationException
                = $apaczka_order->createNotification(true, false, true, false);
            $apaczka_order->notificationNew
                = $apaczka_order->createNotification(true, false, false, false);
            $apaczka_order->notificationSent
                = $apaczka_order->createNotification(true, false, false, false);

            // Zamowienie kuriera

            if ( self::APACZKA_PICKUP_SELF === $pickupMethod ) {

                if ( 'PACZKOMAT' === $data[ 'service' ] ) {
                    $apaczka_order->setPickup('BOX_MACHINE',
                        null, null, null);
                } else {
                    $apaczka_order->setPickup('SELF',
                        null, null, null);

                }

            } else {
                $apaczka_order->setPickup('COURIER',
                    $data[ 'pickup_hour_from' ],
                    $data[ 'pickup_hour_to' ], $data[ 'pickup_date' ]);
            }


            $order_shipment = new ApaczkaOrderShipment('PACZ',
                $data[ 'package_width' ], $data[ 'package_depth' ],
                $data[ 'package_height' ], $data[ 'package_weight' ]);

            if ( true === $data[ 'przes_nietyp' ] ) {
                $order_shipment->addOrderOption('PRZES_NIETYP');
            }

            if ( true === $data[ 'zwrot_dok' ] ) {
                $apaczka_order->addOrderOption('ZWROT_DOK');
            }

            if ( true === $data[ 'dost_sob' ] ) {
                $apaczka_order->addOrderOption('DOST_SOB');
            }


            if ( ! empty($data[ 'parcel_machine_from' ]) ) {
                $order_shipment->addParcelLockerId($data[ 'parcel_machine_from' ]);
            }

            if ( ! empty($data[ 'parcel_machine_to' ]) ) {
                $order_shipment->addParcelLockerId($data[ 'parcel_machine_to' ]);
            }


            if ( $data[ 'insurance' ] == 'yes' ) {
                $order_shipment->addOrderOption('UBEZP');
                $order_shipment->setShipmentValue(floatval($order->get_total())
                    * 100);
            }

            $apaczka_order->referenceNumber = sprintf(__('Zamówienie %s',
                'apaczka'), $order->get_order_number());


            $shipping_name = $order->get_shipping_company();
            $shipping_contact = '';
            if ( $shipping_name == '' ) {
                $shipping_name = $order->get_shipping_first_name() . ' '
                    . $order->get_shipping_last_name();
                $shipping_contact = $order->get_shipping_first_name() . ' '
                    . $order->get_shipping_last_name();

            } else {
                $shipping_contact = $order->get_shipping_first_name() . ' '
                    . $order->get_shipping_last_name();
            }

            $apaczka_api = $shipping_method->get_api();

            $countries = $apaczka_api->getCountriesFromCache();

            $shipping_country_id = 0;
            $country = $order->get_shipping_country();

            if ( $order->get_shipping_address_1()
                || $order->get_shipping_address_2()
            ) {
                foreach ($countries->return->countries->Country as $country) {
                    if ( $country->code == $order->get_shipping_country() ) {
                        $shipping_country_id = $country->id;
                    }
                }
                $shipping_name = $order->get_shipping_company();
                $shipping_contact = '';
                if ( $shipping_name == '' ) {
                    $shipping_name = $order->get_shipping_first_name() . ' '
                        . $order->get_shipping_last_name();
                    $shipping_contact = $order->get_shipping_first_name() . ' '
                        . $order->get_shipping_last_name();

                } else {
                    $shipping_contact = $order->get_shipping_first_name() . ' '
                        . $order->get_shipping_last_name();
                }

                $apaczka_order->setReceiverAddress(
                    $shipping_name, $shipping_contact,
                    $order->get_shipping_address_1(),
                    $order->get_shipping_address_2(),
                    $order->get_shipping_city(),
                    $shipping_country_id,
                    $order->get_shipping_postcode(),
                    '',
                    $order->get_billing_email(),
                    $order->get_billing_phone()
                );
            } else {
                foreach ($countries->return->countries->Country as $country) {
                    if ( $country->code == $order->billing_country ) {
                        $shipping_country_id = $country->id;
                    }
                }
                $shipping_name = $order->billing_company;
                $shipping_contact = '';
                if ( $shipping_name == '' ) {
                    $shipping_name = $order->billing_first_name . ' '
                        . $order->billing_last_name;
                    $shipping_contact = $order->billing_first_name . ' '
                        . $order->billing_last_name;

                } else {
                    $shipping_contact = $order->billing_first_name . ' '
                        . $order->billing_last_name;
                }
                $apaczka_order->setReceiverAddress(
                    $shipping_name, $shipping_contact,
                    $order->billing_address_1,
                    $order->billing_address_2,
                    $order->billing_city,
                    $shipping_country_id,
                    $order->billing_postcode,
                    '',
                    $order->get_billing_email(),
                    $order->get_billing_phone()
                );
            }

            $apaczka_order->setSenderAddress(
                $shipping_method->get_option('sender_name'),
                $shipping_method->get_option('sender_contact_name'),
                $shipping_method->get_option('sender_address_line1'),
                $shipping_method->get_option('sender_address_line2'),
                $shipping_method->get_option('sender_city'),
                '0', /* PL */
                $shipping_method->get_option('sender_postal_code'),
                '',
                $shipping_method->get_option('sender_email'),
                $shipping_method->get_option('sender_phone')
            );

            $apaczka_order->contents = $data[ 'package_contents' ];

            try {


                $apaczka_order->setServiceCode($data[ 'service' ]);

                if ( $data[ 'cod_amount' ] != '' ) {
                    $apaczka_order->setPobranie($shipping_method->get_option('sender_account_number'),
                        floatval($data[ 'cod_amount' ]) * 100);
                    $order_shipment->addOrderOption('UBEZP');
                    $order_shipment->setShipmentValue(floatval($order->get_total())
                        * 100);
                }

                //var_dump($order_shipment->options['string']);die;

                $apaczka_order->addShipment($order_shipment);
                //todo zbadać apaczka order
                $apaczka_response = $apaczka_api->placeOrder($apaczka_order);
                $apaczka_response
                    = $apaczka_api->parse_return($apaczka_response);
                $data[ 'error_messages' ] = '';
                if ( empty($apaczka_response[ 'return' ][ 'order' ])
                    || $apaczka_response[ 'return' ][ 'order' ] == ''
                ) {
                    $messages
                        = $apaczka_response[ 'return' ][ 'result' ][ 'messages' ];
                    foreach ($messages as $message) {
                        $data[ 'error_messages' ] .= $message[ 'description' ]
                            . ', ';
                    }
                    $data[ 'error_messages' ] = trim($data[ 'error_messages' ],
                        ' ');
                    $data[ 'error_messages' ] = trim($data[ 'error_messages' ],
                        ',');
                } else {
                    $data[ 'apaczka_order' ]
                        = $apaczka_response[ 'return' ][ 'order' ];
                    $data[ 'apaczka_order_number' ]
                        = $apaczka_response[ 'return' ][ 'order' ][ 'orderNumber' ];
                }

                $data[ 'apaczka_response' ] = $apaczka_response;
            } catch (Exception $e) {
                $data[ 'error_messages' ] = $e->getMessage();
            }

            $_apaczka[ $id ] = $data;

            $ret[ 'apaczka_response' ] = $apaczka_response;


            update_post_meta($order_id, '_apaczka', $_apaczka);

            $metabox_data = ['args' => ['id' => $id, 'data' => $data]];

            if ( $ret[ 'status' ] == 'ok' ) {

                if ( $data[ 'error_messages' ] == '' ) {
                    $order->add_order_note(__('Apaczka: przesyłka została utworzona',
                        'apaczka'), false);

                    if ( 'yes' === self::$order_status_completed_auto ) {
                        //$order->set_status('completed');
                        $order->update_status('completed');
                    }
                }

                $metabox_content = [];

                $ret[ 'content' ] = self::order_metabox_content($post,
                    $metabox_data, false);
            }
            echo json_encode($ret);
            wp_die();
        }


        public static function ajax_get_waybill()
        {
            $apaczka_order_id = $_REQUEST[ 'apaczka_order_id' ];

            $shipping_methods = WC()->shipping()->get_shipping_methods();
            if ( empty($shipping_methods) ) {
                $shipping_methods = WC()->shipping()->load_shipping_methods();
            }
            $shipping_method = $shipping_methods[ 'apaczka' ];

            $apaczka_api = $shipping_method->get_api();

            $waybill = $apaczka_api->getWaybillDocument($apaczka_order_id);

            if ( isset($waybill->return->waybillDocument) ) {

                header('Content-type: application/pdf');
                header('Content-Disposition: attachment; filename="apaczka_'
                    . $apaczka_order_id . '.pdf"');
                header('Content-Transfer-Encoding: binary');
                //				header( 'Content-Length: ' . filesize($file) );
                //				header( 'Accept-Ranges: bytes' );

                echo $waybill->return->waybillDocument;
            }

            die();

        }

        function is_available($package)
        {

            if ( 'no' == $this->enabled ) {
                return false;
            }


            global $woocommerce;

            $is_available = true;

            /**
             * @var WooCommerce $woocommerce
             */
            if ( (($woocommerce->customer->get_shipping_country() <> 'PL')
                || ($woocommerce->customer->get_billing_country() <> 'PL'
                    && empty($woocommerce->customer->shipping_country)))
            ) {
                $is_available = false;
            }

            return apply_filters('woocommerce_shipping_' . $this->id
                . '_is_available', $is_available);
        }

        public function calculate_shipping($package = [])
        {
            $this->add_rate([
                'id' => $this->id,
                'label' => $this->title,
                'cost' => $this->cost,
                'meta_data' => [
                    'service' => $this->get_option('service'),
                ],
            ]);

            if ( isset($this->cost_cod) && $this->cost_cod != '' ) {
                $this->add_rate([
                    'id' => $this->id . '_cod',
                    'label' => $this->title . __(' (Za pobraniem)', 'apaczka'),
                    'cost' => $this->cost_cod,
                ]);
            }
        }

        /**
         * @return bool
         */
        private function isParcelLockerChosen()
        {
            if ( 'PACZKOMAT' === $this->get_option('service') ) {
                return true;
            }

            return false;
        }

        public function woocommerce_checkout_process($data, $errors)
        {
            if ( true === $this->isParcelLockerChosen() ) {

                $selected_method_in_cart
                    = flexible_shipping_method_selected_in_cart('apaczka');

                $selected_method_in_cart_cod
                    = flexible_shipping_method_selected_in_cart('apaczka_cod');

                if ( false === $selected_method_in_cart
                    && false == $selected_method_in_cart_cod
                ) {
                    return;
                }

                $method = new WPDesk_Apaczka_Shipping();

                $service = $method->get_option('service');

                if ( 'PACZKOMAT' !== $service ) {
                    return;
                };


                if ( empty($_POST[ 'parcel_machine_id' ]) ) {
                    $errors->add('validation',
                        __('Paczkomat nie został wybrany', 'apaczka'));
                }
            }
        }
    }


    class WPDesk_Apaczka_Shipping_COD extends WC_Shipping_Method
    {

        public function __construct($instance_id = 0)
        {

            $this->instance_id = absint($instance_id);
            $this->id = 'apaczka_cod';

            $this->title = 'Apaczka (pobranie)';
            $this->enabled = 'yes';

            $this->has_settings = false;

            $this->supports = [
                'settings' => false,
            ];

        }

        public function set_title($title)
        {
            $this->title = $title;
        }

        public function calculate_shipping($package = [])
        {

        }
    }

}
