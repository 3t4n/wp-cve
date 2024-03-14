<?php
/**
 * Zibal Gateway for Easy Digital Downloads
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EDD_Zibal_Gateway' ) ) :


    class EDD_Zibal_Gateway {
        /**
         * Gateway keyname
         *
         * @var 				string
         */
        public $keyname;

        /**
         * Initialize gateway and hook
         *
         * @return 				void
         */
        public function __construct() {
            $this->keyname = 'zibal';

            add_filter( 'edd_payment_gateways', array( $this, 'add' ) );
            add_action( $this->format( 'edd_{key}_cc_form' ), array( $this, 'cc_form' ) );
            add_action( $this->format( 'edd_gateway_{key}' ), array( $this, 'process' ) );
            add_action( $this->format( 'edd_verify_{key}' ), array( $this, 'verify' ) );
            add_filter( 'edd_settings_gateways', array( $this, 'settings' ) );

            add_action( 'edd_payment_receipt_after', array( $this, 'receipt' ) );

            add_action( 'init', array( $this, 'listen' ) );
        }

        /**
         * Add gateway to list
         *
         * @param 				array $gateways Gateways array
         * @return 				array
         */
        public function add( $gateways ) {
            global $edd_options;

            $gateways[ $this->keyname ] = array(
                'checkout_label' 		=>	isset( $edd_options['zibal_label'] ) ? $edd_options['zibal_label'] : 'پرداخت آنلاین زیبال',
                'admin_label' 			=>	'زیبال'
            );

            return $gateways;
        }

        /**
         * CC Form
         * We don't need it anyway.
         *
         * @return 				bool
         */
        public function cc_form() {
            return;
        }

        /**
         * @param $action (PaymentRequest, )
         * @param $params string
         *
         * @return mixed
         */
        public function sendRequestToZibal($action, $params)
        {
            try {

                $number_of_connection_tries = 3;
                $response = null;
                while ( $number_of_connection_tries>0 ) {
                    $response = wp_safe_remote_post('https://gateway.zibal.ir/v1/' . $action,array(
                        'body'=> $params,
                        'headers'=>array(
                            'Content-Type'=>'application/json'
                        )
                    ));
                    if ( is_wp_error( $response ) ) {
                        $number_of_connection_tries --;
                        continue;
                    } else {
                        break;
                    }
                }

                $body = wp_remote_retrieve_body($response);
                return json_decode($body, true);
            } catch (Exception $ex) {
                return false;
            }
        }

        /**
         * Process the payment
         *
         * @param 				array $purchase_data
         * @return 				void
         */
        public function process( $purchase_data ) {
            global $edd_options;
            @ session_start();
            $payment = $this->insert_payment( $purchase_data );

            if ( $payment ) {

                $zibaldirect = ( isset( $edd_options[ $this->keyname . '_zibaldirect' ] ) ? $edd_options[ $this->keyname . '_zibaldirect' ] : false );
                if ( $zibaldirect )
                    $redirect = 'https://gateway.zibal.ir/start/%s/direct';
                else
                    $redirect = 'https://gateway.zibal.ir/start/%s';

                $merchant = ( isset( $edd_options[ $this->keyname . '_merchant' ] ) ? $edd_options[ $this->keyname . '_merchant' ] : '' );
                $desc = 'خریدار: '.$purchase_data['post_data']['edd_first'].' '.$purchase_data['post_data']['edd_last'];
                $callback = add_query_arg( 'verify_' . $this->keyname, '1', get_permalink( $edd_options['success_page'] ) );

                $amount = intval( $purchase_data['price'] );
                if ( edd_get_currency() == 'IRT' )
                    $amount = $amount * 10; // Return back to original one.

                $data = array(
                    'merchant' 			=>	$merchant,
                    'amount' 				=>	$amount,
                    'description' 			=>	$desc,
		    'orderId'=>$payment,
                    'callbackUrl' 			=>	$callback
                );

                $result = $this->SendRequestToZibal('request', json_encode($data));


                if ( $result==false ) {
                    edd_insert_payment_note( $payment, ' خطای CURL#'  );
                    edd_update_payment_status( $payment, 'failed' );
                    edd_set_error( 'zibal_connect_error', 'در اتصال به درگاه مشکلی پیش آمد.' );
                    edd_send_back_to_checkout();
                    return false;
                }

                if ( $result['result'] == 100) {
                    edd_insert_payment_note( $payment, 'کد تراکنش زیبال: ' . $result['trackId'] );
                    edd_update_payment_meta( $payment, 'zibal_track_id', $result['trackId'] );
                    $_SESSION['zibal_payment'] = $payment;

                    wp_redirect( sprintf( $redirect, $result['trackId'] ) );
                } else {
                    edd_insert_payment_note( $payment, 'کدخطا: ' . $result['result'] );
                    edd_insert_payment_note( $payment, 'علت خطا: ' . ( $result['message'] ) );
                    edd_update_payment_status( $payment, 'failed' );

                    edd_set_error( 'zibal_connect_error', 'در اتصال به درگاه مشکلی پیش آمد. علت: ' . ( $result['result'] ) );
                    edd_send_back_to_checkout();
                }
            } else {
                edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
            }
        }

        /**
         * Verify the payment
         *
         * @return 				void
         */
        public function verify() {
            global $edd_options;

            if ( isset( $_GET['trackId'] ) ) {
                $trackId = sanitize_text_field( $_GET['trackId'] );
                $success = sanitize_text_field( $_GET['success'] );
                @ session_start();
                $payment = edd_get_payment( $_SESSION['zibal_payment'] );
                unset( $_SESSION['zibal_payment'] );
                if ( ! $payment ) {
                    wp_die( 'رکورد پرداخت موردنظر وجود ندارد!' );
                }
                if ( $payment->status == 'complete' ) return false;

                if($success!='1'){
                    edd_update_payment_status( $payment->ID, 'failed' );
                    wp_redirect( get_permalink( $edd_options['failure_page'] ) );
                    exit(1);
                }

                $amount = intval( edd_get_payment_amount( $payment->ID ) );
                if ( edd_get_currency() == 'IRT' )
                    $amount = $amount * 10; // Return back to original one.

                $merchant = ( isset( $edd_options[ $this->keyname . '_merchant' ] ) ? $edd_options[ $this->keyname . '_merchant' ] : '' );

                $data =  array(
                    'merchant' 			=>	$merchant,
                    'trackId' 				=>	$trackId
                );

                $result = $this->SendRequestToZibal('verify', json_encode($data));

                edd_empty_cart();

                if ( version_compare( EDD_VERSION, '2.1', '>=' ) )
                    edd_set_payment_transaction_id( $payment->ID, $trackId );

                if ( $result['result'] == 100  && $amount==$result['amount'] ) {
                    edd_insert_payment_note( $payment->ID, 'شماره تراکنش بانکی: ' . $trackId );
                    edd_update_payment_meta( $payment->ID, 'zibal_refnum', $trackId );
                    edd_update_payment_status( $payment->ID, 'publish' );
                    edd_send_to_success_page();
                } else {
                    edd_update_payment_status( $payment->ID, 'failed' );
                    wp_redirect( get_permalink( $edd_options['failure_page'] ) );
                }
            }
        }

        /**
         * Receipt field for payment
         *
         * @param 				object $payment
         * @return 				void
         */
        public function receipt( $payment ) {
            $refid = edd_get_payment_meta( $payment->ID, 'zibal_refid' );
            if ( $refid ) {
                echo '<tr class="zibal-ref-id-row ezp-field ehsaan-me"><td><strong>شماره تراکنش بانکی:</strong></td><td>' . $refid . '</td></tr>';
            }
        }

        /**
         * Gateway settings
         *
         * @param 				array $settings
         * @return 				array
         */
        public function settings( $settings ) {
            return array_merge( $settings, array(
                $this->keyname . '_header' 		=>	array(
                    'id' 			=>	$this->keyname . '_header',
                    'type' 			=>	'header',
                    'name' 			=>	'<strong>درگاه زیبال</strong>'
                ),
                $this->keyname . '_merchant' 		=>	array(
                    'id' 			=>	$this->keyname . '_merchant',
                    'name' 			=>	'مرچنت‌کد',
                    'type' 			=>	'text',
                    'size' 			=>	'regular'
                ),
                $this->keyname . '_zibaldirect' 		=>	array(
                    'id' 			=>	$this->keyname . '_zibaldirect',
                    'name' 			=>	'استفاده از زیبال دایرکت',
                    'type' 			=>	'checkbox',
                    'desc' 			=>	'استفاده از درگاه مستقیم زیبال'
                ),

                $this->keyname . '_label' 	=>	array(
                    'id' 			=>	$this->keyname . '_label',
                    'name' 			=>	'نام درگاه در صفحه پرداخت',
                    'type' 			=>	'text',
                    'size' 			=>	'regular',
                    'std' 			=>	'پرداخت آنلاین زیبال'
                )
            ) );
        }

        /**
         * Format a string, replaces {key} with $keyname
         *
         * @param 			string $string To format
         * @return 			string Formatted
         */
        private function format( $string ) {
            return str_replace( '{key}', $this->keyname, $string );
        }

        /**
         * Inserts a payment into database
         *
         * @param 			array $purchase_data
         * @return 			int $payment_id
         */
        private function insert_payment( $purchase_data ) {
            global $edd_options;

            $payment_data = array(
                'price' => $purchase_data['price'],
                'date' => $purchase_data['date'],
                'user_email' => $purchase_data['user_email'],
                'purchase_key' => $purchase_data['purchase_key'],
                'currency' => $edd_options['currency'],
                'downloads' => $purchase_data['downloads'],
                'user_info' => $purchase_data['user_info'],
                'cart_details' => $purchase_data['cart_details'],
                'status' => 'pending'
            );

            // record the pending payment
            $payment = edd_insert_payment( $payment_data );

            return $payment;
        }

        /**
         * Listen to incoming queries
         *
         * @return 			void
         */
        public function listen() {
            if ( isset( $_GET[ 'verify_' . $this->keyname ] ) && $_GET[ 'verify_' . $this->keyname ] ) {
                do_action( 'edd_verify_' . $this->keyname );
            }
        }


    }

endif;

new EDD_Zibal_Gateway;
