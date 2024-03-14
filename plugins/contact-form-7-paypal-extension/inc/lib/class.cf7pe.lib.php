<?php
/**
 * CF7PE_Lib Class
 *
 * Handles the Library functionality.
 *
 * @package WordPress
 * @subpackage Accept PayPal Payments using Contact Form 7
 * @since 3.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\Capture;

if ( !class_exists( 'CF7PE_Lib' ) ) {

	class CF7PE_Lib {

		var $context = '';

		function __construct() {

			add_action( 'init', array( $this, 'action__init' ) );

			add_action( 'wpcf7_before_send_mail', array( $this, 'action__wpcf7_before_send_mail' ), 20, 3 );
			add_filter( 'wpcf7_ajax_json_echo',   array( $this, 'filter__wpcf7_ajax_json_echo'   ), 20, 2 );
			add_action( 'wpcf7_init', array( $this, 'action__wpcf7_verify_version' ), 10, 0 );
		}

		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		 * Action: init
		 *
		 * - Fire the email when return back from the paypal.
		 *
		 * @method action__init
		 *
		 */
		function action__init() {

			if ( !isset( $_SESSION ) || session_status() == PHP_SESSION_NONE ) {
				session_start();
			}

			/**
			 * Fire email after failed/canle payment from paypal
			 */
			if (
				    isset( $_GET['token' ] )
				&& !isset($_GET['paymentId'])
				&& !isset($_GET['PayerID'])
				&& isset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] )
			) {

				$from_data = unserialize( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] );
				$form_ID = $from_data->get_contact_form()->id();

				add_filter( 'wpcf7_mail_components', array( $this, 'filter__wpcf7_mail_components' ), 888, 3 );
				remove_filter( 'wpcf7_mail_components', array( $this, 'filter__wpcf7_mail_components' ), 888, 3 );

				/*if ( isset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] ) ) {
					unset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] );
				}*/

				if ( isset( $_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ] ) ) {
					unset( $_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ] );
				}

				if ( !empty( $this->get_form_attachments( $form_ID ) ) ) {
					$this->zw_remove_uploaded_files( $this->get_form_attachments( $form_ID ) );
				}
			}

			/**
			 * Fire email after success payment from paypal
			 */
			if (
				!empty($_GET['paymentId'])
				&& !empty($_GET['PayerID'])
				&& isset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] )
			) {

				$from_data = unserialize( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] );
				$form_ID = $from_data->get_contact_form()->id();

				if ( !empty( $form_ID ) ) {

					/*$apiContext = $_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ];*/

					$paymentId = $_GET['paymentId'];

					$mode_sandbox           = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'mode_sandbox', true );
					$sandbox_client_id      = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'sandbox_client_id', true );
					$sandbox_client_secret  = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'sandbox_client_secret', true );
					$live_client_id         = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'live_client_id', true );
					$live_client_secret     = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'live_client_secret', true );
					$currency               = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'currency', true );
				}

				$paypalConfig = [
					'client_id'     => ( !empty( $mode_sandbox ) ? $sandbox_client_id : $live_client_id ),
					'client_secret' => ( !empty( $mode_sandbox ) ? $sandbox_client_secret : $live_client_secret ),
					'mode' => ( !empty( $mode_sandbox ) ? 'sandbox' : 'live' )
				];

				/*$apimode = ( $mode_sandbox ) ? 'sandbox' : 'live';*/
				$apiContext = $this->getApiContext( $paypalConfig['client_id'], $paypalConfig['client_secret'], $mode_sandbox );

				/*$apiContext->setConfig(
					array(
						'mode' => $apimode
					)
				);*/

				$payment = Payment::get($paymentId, $apiContext);
				/**
				 * Add transctions to Paypal Account
				 */
				$amountPayable = $payment->transactions[0]->amount->total;
				$execution = new PaymentExecution();
				$execution->setPayerId($_GET['PayerID']);
				//call all the require API class
				$transaction = new Transaction();
				$amount = new Amount();
				$details = new Details();

				//Details data
				$details->setSubtotal( $amountPayable );
				//Amount data
				$amount->setCurrency( $currency )
					->setTotal( $amountPayable )
					->setDetails($details);
				//Transaction Data
				$transaction->setAmount( $amount );
				// Add the above transaction object inside our Execution object.
				$execution->addTransaction($transaction);
				try {
					// Execute the payment
					// (See bootstrap.php for more on `ApiContext`)
					$result = $payment->execute($execution, $apiContext);

					try {
						//$payment = Payment::get($paymentId, $apiContext);
					} catch (Exception $ex) {
						//echo "<pre>"; print_r($ex);
						echo $ex->getCode(); // Prints the Error Code
    					echo $ex->getData(); // Prints the detailed error message
						return;
					}
				} catch (Exception $ex) {
					//echo "<pre>"; print_r($ex);
					echo $ex->getCode(); // Prints the Error Code
    				echo $ex->getData(); // Prints the detailed error message
					return;
				}

				$data = [
					'transaction_id' => $payment->getId(),
					'payment_amount' => $payment->transactions[0]->amount->total,
					'payment_status' => $payment->getState(),
					'invoice_id' => $payment->transactions[0]->invoice_number
				];

				add_filter( 'wpcf7_mail_components', array( $this, 'filter__wpcf7_mail_components' ), 888, 3 );
				$this->mail( $from_data, $from_data->get_posted_data() );
				remove_filter( 'wpcf7_mail_components', array( $this, 'filter__wpcf7_mail_components' ), 888, 3 );

				if ( isset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] ) ) {
					unset( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] );
				}

				if ( isset( $_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ] ) ) {
					unset( $_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ] );
				}

				if ( !empty( $this->get_form_attachments( $form_ID ) ) ) {
					$this->zw_remove_uploaded_files( $this->get_form_attachments( $form_ID ) );
				}

			}
		}

		/**
		 * PayPal Verify CF7 dependencies.
		 *
		 * @method action__wpcf7_verify_version
		 *
		 */
		function action__wpcf7_verify_version(){

			$cf7_verify = $this->wpcf7_version();
			if ( version_compare($cf7_verify, '5.2') >= 0 ) {
				add_filter( 'wpcf7_feedback_response',   array( $this, 'filter__wpcf7_ajax_json_echo'   ), 20, 2 );
			} else{
				add_filter( 'wpcf7_ajax_json_echo',   array( $this, 'filter__wpcf7_ajax_json_echo'   ), 20, 2 );
			}

		}

		/**
		 * Action: CF7 before send email
		 *
		 * @method action__wpcf7_before_send_mail
		 *
		 * @param  object $contact_form WPCF7_ContactForm::get_instance()
		 *
		 */
		function action__wpcf7_before_send_mail( $contact_form ) {

			$submission    = WPCF7_Submission::get_instance(); // CF7 Submission Instance
			$form_ID       = $contact_form->id();
			$form_instance = WPCF7_ContactForm::get_instance($form_ID); // CF7 From Instance

			if ( $submission ) {
				// CF7 posted data
				$posted_data = $submission->get_posted_data();
			}

			if ( !empty( $form_ID ) ) {

				$use_paypal = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'use_paypal', true );

				if ( empty( $use_paypal ) )
					return;

				$mode_sandbox           = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'mode_sandbox', true );
				$sandbox_client_id      = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'sandbox_client_id', true );
				$sandbox_client_secret  = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'sandbox_client_secret', true );
				$live_client_id         = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'live_client_id', true );
				$live_client_secret     = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'live_client_secret', true );
				$amount                 = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'amount', true );
				$quantity               = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'quantity', true );
				$description            = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'description', true );
				$success_returnURL      = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'success_returnurl', true );
				$cancle_returnURL       = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'cancel_returnurl', true );

				// Set some example data for the payment.
				$currency               = get_post_meta( $form_ID, CF7PE_META_PREFIX . 'currency', true );

				add_filter( 'wpcf7_skip_mail', array( $this, 'filter__wpcf7_skip_mail' ), 20 );

				$amount_val  = ( ( !empty( $amount ) && array_key_exists( $amount, $posted_data ) ) ? floatval( $posted_data[$amount] ) : '0' );
				$quanity_val = ( ( !empty( $quantity ) && array_key_exists( $quantity, $posted_data ) ) ? floatval( $posted_data[$quantity] ) : '' );

				$description_val = ( ( !empty( $description ) && array_key_exists( $description, $posted_data ) ) ? $posted_data[$description] : get_bloginfo( 'name' ) );

                if (
					!empty( $amount )
					&& array_key_exists( $amount, $posted_data )
					&& is_array( $posted_data[$amount] )
					&& !empty( $posted_data[$amount] )
				) {
					$val = 0;
					foreach ( $posted_data[$amount] as $k => $value ) {
						$val = $val + floatval($value);
					}
					$amount_val = $val;
				}

				if (
					!empty( $quantity )
					&& array_key_exists( $quantity, $posted_data )
					&& is_array( $posted_data[$quantity] )
					&& !empty( $posted_data[$quantity] )
				) {
					$qty_val = 0;
					foreach ( $posted_data[$quantity] as $k => $qty ) {
						$qty_val = $qty_val + floatval($qty);
					}
					$quanity_val = $qty_val;
				}

				if ( empty( $amount_val ) ) {
					$_SESSION[ CF7PE_META_PREFIX . 'amount_error' . $form_ID ] = __( 'Empty Amount field or Invalid configuration.', CF7PE_PREFIX );
					return;
				}

				// PayPal settings. Change these to your account details and the relevant URLs
				// for your site.
				$paypalConfig = [
					'client_id'     => ( !empty( $mode_sandbox ) ? $sandbox_client_id : $live_client_id ),
					'client_secret' => ( !empty( $mode_sandbox ) ? $sandbox_client_secret : $live_client_secret ),
					'return_url'    => ( !empty( $success_returnURL ) ? esc_url( $success_returnURL ) : site_url() ),
					'cancel_url'    => ( !empty( $cancle_returnURL ) ? esc_url( $cancle_returnURL ) : site_url() ),
				];

				$apimode = ( $mode_sandbox ) ? 'sandbox' : 'live';
				$apiContext = $this->getApiContext( $paypalConfig['client_id'], $paypalConfig['client_secret'], $apimode );

				$apiContext->setConfig(
					array(
						'log.LogEnabled' => true,
						'log.FileName'   => CF7PE_DIR . '/inc/lib/log/paypal.log',
						'log.LogLevel'   => 'DEBUG',
						'mode'			 => $apimode
					)
				);

				$_SESSION[ CF7PE_META_PREFIX . 'context_' . $form_ID ] = $apiContext;

				$payer = new Payer();
				$payer->setPaymentMethod( 'paypal' );

				// Set some example data for the payment.
				$amountPayable = (float) ( empty( $quanity_val ) ? $amount_val : ( $quanity_val * $amount_val ) );
				$invoiceNumber = uniqid();

				$item = new Item();
				$item->setName( $description_val )
					->setCurrency( $currency )
					->setQuantity( ( empty( $quanity_val ) ? 1 : $quanity_val ) )
					->setPrice( $amount_val );

				$itemList = new ItemList();
				$itemList->setItems( array( $item ) );

				$details = new Details();
				$details->setSubtotal( $amountPayable );

				$amount = new Amount();
				$amount->setCurrency( $currency )
					->setTotal( $amountPayable )
					->setDetails($details);

				$transaction = new Transaction();
				$transaction->setAmount( $amount )
					->setItemList( $itemList )
					->setDescription( $description_val )
					->setInvoiceNumber( $invoiceNumber );

				$redirectUrls = new RedirectUrls();
				$redirectUrls->setReturnUrl( $paypalConfig[ 'return_url' ] )
					->setCancelUrl( $paypalConfig[ 'cancel_url' ] );

				$payment = new Payment();
				$payment->setIntent( 'sale' )
					->setPayer( $payer )
					->setId( $invoiceNumber )
					->setTransactions( array( $transaction ) )
					->setRedirectUrls( $redirectUrls );

				$request = clone $payment;

				try {
					$payment->create( $apiContext );
				} catch ( Exception $e ) {
					$_SESSION[ CF7PE_META_PREFIX . 'exception_' . $form_ID ] = $e->getData();
					remove_filter( 'wpcf7_skip_mail', array( $this, 'filter__wpcf7_skip_mail' ), 20 );
					return;
				}

				if( !empty( $submission->uploaded_files() ) ) {

					$cf7_verify = $this->wpcf7_version();

					if ( version_compare( $cf7_verify, '5.4' ) >= 0 ) {
						$uploaded_files = $this->zw_cf7_upload_files( $submission->uploaded_files(), 'new' );
					}else{
						$uploaded_files = $this->zw_cf7_upload_files( array( $submission->uploaded_files() ), 'old' );
					}

					if ( !empty( $uploaded_files ) ) {
						$_SESSION[ CF7PE_META_PREFIX . 'form_attachment_' . $form_ID ] = serialize( $uploaded_files );
					}
				}

				if ( $payment->getApprovalLink() ) {
					$_SESSION[ CF7PE_META_PREFIX . 'paypal_url' . $form_ID ] = $payment->getApprovalLink();
				}

				$_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] = serialize( $submission );

				if ( !$submission->is_restful() ) {
					wp_redirect( $payment->getApprovalLink() );
					exit;
				}


			}

		}


		/*
		######## #### ##       ######## ######## ########   ######
		##        ##  ##          ##    ##       ##     ## ##    ##
		##        ##  ##          ##    ##       ##     ## ##
		######    ##  ##          ##    ######   ########   ######
		##        ##  ##          ##    ##       ##   ##         ##
		##        ##  ##          ##    ##       ##    ##  ##    ##
		##       #### ########    ##    ######## ##     ##  ######
		*/

		/**
		 * Filter: Skip email when paypal enable.
		 *
		 * @method filter__wpcf7_skip_mail
		 *
		 * @param  bool $bool
		 *
		 * @return bool
		 */
		function filter__wpcf7_skip_mail( $bool ) {
			return true;
		}

		/**
		 * Filter: Modify the contact form 7 response.
		 *
		 * @method filter__wpcf7_ajax_json_echo
		 *
		 * @param  array $response
		 * @param  array $result
		 *
		 * @return array
		 */
		function filter__wpcf7_ajax_json_echo( $response, $result ) {

			if (
				   array_key_exists( 'contact_form_id' , $result )
				&& array_key_exists( 'status' , $result )
				&& !empty( $result[ 'contact_form_id' ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'paypal_url' . $result[ 'contact_form_id' ] ] )
				&& $result[ 'status' ] == 'mail_sent'
			) {
				$response[ 'redirection_url' ] = $_SESSION[ CF7PE_META_PREFIX . 'paypal_url' . $result[ 'contact_form_id' ] ];
				$response[ 'message' ] = __( 'You are redirecting to PayPal.', CF7PE_PREFIX );
				unset( $_SESSION[ CF7PE_META_PREFIX . 'paypal_url' . $result[ 'contact_form_id' ] ] );
			}

			if (
				   array_key_exists( 'contact_form_id' , $result )
				&& array_key_exists( 'status' , $result )
				&& !empty( $result[ 'contact_form_id' ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'exception_' . $result[ 'contact_form_id' ] ] )
				&& $result[ 'status' ] == 'mail_sent'
			) {
				$exception = (array)json_decode( $_SESSION[ CF7PE_META_PREFIX . 'exception_' . $result[ 'contact_form_id' ] ] );
				$response[ 'message' ] = ( !empty( $exception ) && array_key_exists( 'error_description', $exception ) ? '<strong style="color: #ff0000; ">' . $exception['error_description']. '</strong>' : '' ) . '<br/>' . $response[ 'message' ];
				unset( $_SESSION[ CF7PE_META_PREFIX . 'exception_' . $result[ 'contact_form_id' ] ] );
			}

			if (
				   array_key_exists( 'contact_form_id' , $result )
				&& array_key_exists( 'status' , $result )
				&& !empty( $result[ 'contact_form_id' ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'amount_error' . $result[ 'contact_form_id' ] ] )
				&& $result[ 'status' ] == 'mail_sent'
			) {

				$response[ 'message' ] = $_SESSION[ CF7PE_META_PREFIX . 'amount_error' . $result[ 'contact_form_id' ] ];
				$response[ 'status' ] = 'mail_failed';
				unset( $_SESSION[ CF7PE_META_PREFIX . 'amount_error' . $result[ 'contact_form_id' ] ] );
			}

			return $response;
		}

		/**
		 * Filter: Modify the email components.
		 *
		 * @method filter__wpcf7_mail_components
		 *
		 * @param  array $components
		 * @param  object $current_form WPCF7_ContactForm::get_current()
		 * @param  object $mail WPCF7_Mail::get_current()
		 *
		 * @return array
		 */
		function filter__wpcf7_mail_components( $components, $current_form, $mail ) {

			$from_data = unserialize( $_SESSION[ CF7PE_META_PREFIX . 'form_instance' ] );
			$form_ID = $from_data->get_contact_form()->id();

			if (
				   !empty( $mail->get( 'attachments', true ) )
				&& !empty( $this->get_form_attachments( $form_ID ) )
			) {
				$components['attachments'] = $this->get_form_attachments( $form_ID );
			}

			return $components;
		}

		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/
		/**
		 * Set up a connection to the API
		 *
		 * @param string $clientId
		 *
		 * @param string $clientSecret
		 *
		 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
		 *
		 * @return \PayPal\Rest\ApiContext
		 */
		function getApiContext( $clientId, $clientSecret, $enableSandbox = false ) {
			$apiContext = new ApiContext( new OAuthTokenCredential( $clientId, $clientSecret ) );

			$apiContext->setConfig([ 'mode' => $enableSandbox ? 'sandbox' : 'live' ]);

			return $apiContext;
		}

		/**
		 * Copy the attachment into the plugin folder.
		 *
		 * @method zw_cf7_upload_files
		 *
		 * @param  array $attachment
		 *
		 * @uses $this->zw_wpcf7_upload_tmp_dir(), WPCF7::wpcf7_maybe_add_random_dir()
		 *
		 * @return array
		 */
		function zw_cf7_upload_files( $attachment, $version ) {

			if( empty( $attachment ) )
			return;

			$new_attachment = $attachment;

			foreach ( $attachment as $key => $value ) {
				$tmp_name = $value;
				$uploads_dir = wpcf7_maybe_add_random_dir( $this->zw_wpcf7_upload_tmp_dir() );
				foreach ($tmp_name as $newkey => $file_path) {
					$get_file_name = explode( '/', $file_path );
					$new_uploaded_file = path_join( $uploads_dir, end( $get_file_name ) );
					if ( copy( $file_path, $new_uploaded_file ) ) {
						chmod( $new_uploaded_file, 0755 );
						if($version == 'old'){
							$new_attachment_file[$newkey] = $new_uploaded_file;
						}else{
							$new_attachment_file[$key] = $new_uploaded_file;
						}
					}
				}
			}
			return $new_attachment_file;
		}

		/**
		 * Get the attachment upload directory from plugin.
		 *
		 * @method zw_wpcf7_upload_tmp_dir
		 *
		 * @return string
		 */
		function zw_wpcf7_upload_tmp_dir() {

            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $cf7pe_upload_dir = $upload_dir . '/cf7pe-uploaded-files';

            if (! is_dir( $cf7pe_upload_dir ) ) {
                mkdir( $cf7pe_upload_dir, 0755 );
            }

            return $cf7pe_upload_dir;
		}

		/**
		 * Email send
		 *
		 * @method mail
		 *
		 * @param  object $contact_form WPCF7_ContactForm::get_instance()
		 * @param  [type] $posted_data  WPCF7_Submission::get_posted_data()
		 *
		 * @uses $this->prop(), $this->mail_replace_tags(), $this->get_form_attachments(),
		 *
		 * @return bool
		 */
		function mail( $contact_form, $posted_data ) {

			if( empty( $contact_form ) ) {
				return false;
			}

			$contact_form_data = $contact_form->get_contact_form();
			$mail = $this->prop( 'mail', $contact_form_data );
			$mail = $this->mail_replace_tags( $mail, $posted_data );

			$result = WPCF7_Mail::send( $mail, 'mail' );

			if ( $result ) {
				$additional_mail = array();

				if (
					$mail_2 = $this->prop( 'mail_2', $contact_form_data )
					and $mail_2['active']
				) {

					$mail_2 = $this->mail_replace_tags( $mail_2, $posted_data );
					$additional_mail['mail_2'] = $mail_2;
				}

				$additional_mail = apply_filters( 'wpcf7_additional_mail',
					$additional_mail, $contact_form_data );

				foreach ( $additional_mail as $name => $template ) {
					WPCF7_Mail::send( $template, $name );
				}

				return true;
			}

			return false;
		}

		/**
		 * get the property from the
		 *
		 * @method prop    used from WPCF7_ContactForm:prop()
		 *
		 * @param  string $name
		 * @param  object $class_object WPCF7_ContactForm:get_current()
		 *
		 * @return mixed
		 */
		public function prop( $name, $class_object ) {
			$props = $class_object->get_properties();
			return isset( $props[$name] ) ? $props[$name] : null;
		}

		/**
		 * Mail tag replace
		 *
		 * @method mail_replace_tags
		 *
		 * @param  array $mail
		 * @param  array $data
		 *
		 * @return array
		 */
		function mail_replace_tags( $mail, $data ) {
			$mail = ( array ) $mail;
			$data = ( array ) $data;

			$new_mail = array();
			if ( !empty( $mail ) && !empty( $data ) ) {
				foreach ( $mail as $key => $value ) {
					if( $key != 'attachments' ) {
						foreach ( $data as $k => $v ) {
							if ( isset( $v ) && is_array( $v ) ) {
								$array_string = implode(", ",$v);
								$value = str_replace( '[' . $k . ']' , $array_string, $value );
							} else {
								$value = str_replace( '[' . $k . ']' , $v, $value );
							}
						}
					}
					$new_mail[$key] = $value;
				}
			}

			return $new_mail;
		}

		/**
		 * Get attachment for the from
		 *
		 * @method get_form_attachments
		 *
		 * @param  int $form_ID form_id
		 *
		 * @return array
		 */
		function get_form_attachments( $form_ID ) {
			if(
				!empty( $form_ID )
				&& isset( $_SESSION[ CF7PE_META_PREFIX . 'form_attachment_' . $form_ID ] )
				&& !empty( $_SESSION[ CF7PE_META_PREFIX . 'form_attachment_' . $form_ID ] )
			) {
				return unserialize( $_SESSION[ CF7PE_META_PREFIX . 'form_attachment_' . $form_ID ] );
			}
		}

		function zw_remove_uploaded_files( $files ) {

			if (
				   !is_array( $files )
				&& empty( $files )
			)
				return;

			foreach ( (array) $files as $name => $path ) {
				wpcf7_rmdir_p( $path );

				if ( $dir = dirname( $path )
				and false !== ( $files = scandir( $dir ) )
				and ! array_diff( $files, array( '.', '..' ) ) ) {
					// remove parent dir if it's empty.
					rmdir( $dir );
				}
			}
		}

		/**
		 * Get current conatct from 7 version.
		 *
		 * @method wpcf7_version
		 *
		 * @return string
		 */
		function wpcf7_version() {

			$wpcf7_path = plugin_dir_path( CF7PE_DIR ) . 'contact-form-7/wp-contact-form-7.php';

			if( ! function_exists('get_plugin_data') ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_data = get_plugin_data( $wpcf7_path );

			return $plugin_data['Version'];
		}

	}

	add_action( 'plugins_loaded', function() {
		CF7PE()->lib = new CF7PE_Lib;
	} );
}
