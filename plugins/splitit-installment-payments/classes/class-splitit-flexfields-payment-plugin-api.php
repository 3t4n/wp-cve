<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - class-splitit-flexfields-payment-plugin-api.php
 * Class for working with SplitIt API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @Exit if accessed directly
}

require_once __DIR__ . '/../vendor/autoload.php';

// FIX: conflict of Guzzle library versions.
if ( ! function_exists( 'GuzzleHttp\describe_type' ) ) {
	require_once __DIR__ . '/../vendor/guzzlehttp/guzzle/src/functions_include.php';
}

use Splitit\Client;
use Splitit\Configuration;
use Splitit\Model\AddressDataModel;
use Splitit\Model\PlanDataModel;
use Splitit\Model\ShopperData;

/**
 * Class SplitIt_FlexFields_Payment_Plugin_API
 */
class SplitIt_FlexFields_Payment_Plugin_API {

	/**
	 * @var mixed
	 */
	protected $api_key;

	/**
	 * @var mixed
	 */
	protected $username;

	/**
	 * @var mixed
	 */
	protected $password;

	/**
	 * @var mixed
	 */
	protected $environment;

	/**
	 * @var mixed
	 */
	protected $auto_capture;

	/**
	 * @var mixed
	 */
	protected $secure_3d;

	/**
	 * @var int
	 */
	protected $default_number_of_installments;

	/**
	 * API constructor.
	 *
	 * @param array      $settings Settings from DB
	 * @param null | int $default_number_of_installments Default number of installments
	 */
	public function __construct( $settings, $default_number_of_installments = null ) {
		$this->api_key                        = $this->get_api_key( $settings );
		$this->username                       = $settings['splitit_api_username'];
		$this->password                       = $settings['splitit_api_password'];
		$this->environment                    = $settings['splitit_environment'];
		$this->auto_capture                   = $settings['splitit_auto_capture'];
		$this->secure_3d                      = $settings['splitit_settings_3d'];
		$this->default_number_of_installments = $default_number_of_installments ?? 0;
	}


	/**
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function get_api_key( $settings ) {
		if ( get_option( 'splitit_' . $settings['splitit_environment'] . '_new_login' ) ) {
			$current_api_key = get_option( 'splitit_' . $settings['splitit_environment'] . '_api_key' );
		} else {
			$current_api_key = $settings['splitit_api_key'];
		}
		return $current_api_key;
	}

	/**
	 * Login method
	 *
	 * @param bool $check_credentials For check credentials.
	 *
	 * @return array[]|string
	 * @throws Exception
	 */
	public function login( $check_credentials = false ) {
		$environment = $this->environment;

		$token_url = 'https://id.' . $environment . '.splitit.com/connect/token';

		$client_id     = get_option( 'splitit_' . $environment . '_client_id' ) ? get_option( 'splitit_' . $environment . '_client_id' ) : $this->username;
		$client_secret = get_option( 'splitit_' . $environment . '_client_secret' ) ? get_option( 'splitit_' . $environment . '_client_secret' ) : $this->password;

		$header  = array( 'Content-Type: application/x-www-form-urlencoded' );
		$content = "client_id=$client_id&client_secret=$client_secret&grant_type=client_credentials&scope=api.v3";

		try {
			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $token_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST           => true,
					CURLOPT_POSTFIELDS     => $content,
				)
			);

			$response = curl_exec( $curl );

			curl_close( $curl );

			if ( isset( json_decode( $response )->error ) ) {
				$error_message = 'Method login API Get Access Token - ' . json_decode( $response )->error;
				$info          = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'Method login API Get Access Token', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $info, $error_message, 'error' );
			}

			$access_token = json_decode( $response )->access_token;

			$this->session_id = $access_token ?? null;

			return $access_token;

		} catch ( Exception $e ) {
			$message = 'Error. File - ' . $e->getFile() . ', message - ' . $e->getMessage() . ', row' . $e->getLine();
			$data    = array(
				'user_id' => get_current_user_id(),
				'method'  => __( 'Method login API', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

			return array( 'error' => array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Initiate method
	 *
	 * @param array $data Data for initiate.
	 *
	 * @return false|string
	 * @throws Exception
	 */
	public function initiate( $data ) {
		global $plugin_version;
		try {
			$access_token = $this->login();

			$idempotency_key = wp_generate_uuid4();

			$config = Configuration::getDefaultConfiguration();

			if ( 'sandbox' === $this->environment ) {
				$config->setHost( 'https://web-api-v3.sandbox.splitit.com' );
				$config->setTokenUrl( 'https://id.sandbox.splitit.com/connect/token' );
			}

			$config->setAccessToken( $access_token );

			$client_id     = get_option( 'splitit_' . $this->environment . '_client_id' ) ? get_option( 'splitit_' . $this->environment . '_client_id' ) : $this->username;
			$client_secret = get_option( 'splitit_' . $this->environment . '_client_secret' ) ? get_option( 'splitit_' . $this->environment . '_client_secret' ) : $this->password;

			$config->setClientId( $client_id );
			$config->setClientSecret( $client_secret );

			if ( isset( $access_token ) && ! isset( $access_token['error'] ) ) {

				$splitit = new Client( '', null, null, null, '', $config );

				$auto_capture     = (bool) $this->auto_capture;
				$attempt3d_secure = (bool) $this->secure_3d;

				$plan_data = new PlanDataModel();

				$plan_data->setTerminalId( $this->api_key );

				if ( isset( $data['numberOfInstallments'] ) && ! empty( $data['numberOfInstallments'] ) ) {
					$plan_data->setNumberOfInstallments( $data['numberOfInstallments'] );
				}

				if ( isset( $data['amount'] ) && ! empty( $data['amount'] ) ) {
					$plan_data->setTotalAmount( number_format( round( $data['amount'], 2 ), 2, '.', '' ) );
				}
				if ( isset( $data['currency_code'] ) && ! empty( $data['currency_code'] ) ) {
					$plan_data->setCurrency( $data['currency_code'] );
				}

				$plan_data->setPurchaseMethod( \Splitit\Model\PurchaseMethod::E_COMMERCE );

				$billing_address     = array();
				$consumer_data       = array();
				$redirect_urls       = array();
				$x_splitit_test_mode = 'None';

				if ( isset( $data['billingAddress'] ) ) {
					$billing_address = new AddressDataModel(
						array(
							'address_line1' => $data['billingAddress']['AddressLine'],
							'address_line2' => $data['billingAddress']['AddressLine2'],
							'city'          => $data['billingAddress']['City'],
							'state'         => $data['billingAddress']['State'],
							'country'       => $data['billingAddress']['Country'],
							'zip'           => $data['billingAddress']['Zip'],
						)
					);
				}
				if ( isset( $data['consumerData'] ) ) {
					$consumer_data = new ShopperData(
						array(
							'full_name'    => $data['consumerData']['FullName'],
							'email'        => $data['consumerData']['Email'],
							'phone_number' => $data['consumerData']['PhoneNumber'],
							'culture'      => $data['consumerData']['CultureName'],
						)
					);
				}

				$ux_settings = new \Splitit\Model\UxSettingsModel();
				$ux_settings->setDisplayedInstallmentOptions( $data['installments'] );

				$events_endpoints = array(
					'CreateSucceeded' => site_url() . '/wc-api/splitit_payment_success_async',
				);

				$processing_data = array();

				$initiate_response = $splitit->installmentPlan->post(
					$auto_capture,
					$idempotency_key,
					'WooCommercePlugin.' . $plugin_version,
					$attempt3d_secure,
					$consumer_data,
					$plan_data,
					$billing_address,
					$redirect_urls,
					$ux_settings,
					(object) $events_endpoints,
					$processing_data,
					$x_splitit_test_mode
				);

				$success = $initiate_response->getStatus() && 'Initialized' == $initiate_response->getStatus();

				if ( $success ) {
					$field_data = array(
						'installmentPlanNumber' => $initiate_response->getInstallmentPlanNumber(),
						'purchaseMethod'        => $initiate_response->getPurchaseMethod(),
						'currency'              => $initiate_response->getCurrency(),
						'amount'                => $initiate_response->getAmount(),
						'checkoutUrl'           => $initiate_response->getCheckoutUrl(),
						'shopper'               => $initiate_response->getShopper(),
						'billingAddress'        => $initiate_response->getBillingAddress(),
						'numberOfInstallments'  => ( isset( $data['numberOfInstallments'] ) && ! empty( $data['numberOfInstallments'] ) ) ? $data['numberOfInstallments'] : null,
					);

					$message = 'Successful initiate';
					$data    = array(
						'user_id' => get_current_user_id(),
						'method'  => 'Method initiate API',
					);
					SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'info' );

					return wp_json_encode( $field_data );
				} else {
					$message = __( 'Failed initiate', 'splitit_ff_payment' );
					$data    = array(
						'user_id' => get_current_user_id(),
						'method'  => __( 'Method initiate API', 'splitit_ff_payment' ),
					);
					SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );
					$error_data = array( 'error' => array( 'message' => $message ) );

					return wp_json_encode( $error_data );
				}
			} else {
				$message = __( 'Initiate failed login. Please make sure that you are using the correct merchant and terminal and that you have the necessary accesses for them.', 'splitit_ff_payment' );
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'Method initiate API', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );
				$error_data = array( 'error' => array( 'message' => $message ) );

				return wp_json_encode( $error_data );
			}
		} catch ( Exception $e ) {
			$message = 'Error. File - ' . $e->getFile() . ', message - ' . $e->getMessage() . ', row' . $e->getLine();
			$data    = array(
				'user_id' => get_current_user_id(),
				'method'  => __( 'Method initiate() API', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

			$message_for_displaying = 'Initiate failed login. For more information, please contact the Splitit Support Team';

			preg_match( '/"Message":"(.*?)"/', $e->getMessage(), $matches );

			if ( isset( $matches[1] ) ) {
				$message_for_displaying = $matches[1];
			}

			return wp_json_encode( array( 'error' => array( 'message' => $message_for_displaying ) ) );
		}

	}

	/**
	 * Update method
	 *
	 * @param int $order_id Order ID.
	 * @param int $ipn Installment plan number.
	 *
	 * @throws Exception
	 */
	public function update( $order_id, $ipn ) {

		global $plugin_version;

		$attempt      = 1;
		$max_attempts = 4;

		while ( $attempt <= $max_attempts ) {
			try {
				$api_instance = $this->get_api_instance();

				$idempotency_key = wp_generate_uuid4();

				$api_instance->installmentPlan->updateOrder(
					$ipn,
					$idempotency_key,
					'WooCommercePlugin.' . $plugin_version,
					'',
					$order_id,
					\Splitit\Model\ShippingStatus::PENDING
				);

				$message = __( 'Update was successful', 'splitit_ff_payment' );
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'update() API', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'info' );

				return;
			} catch ( Exception $e ) {
				$message = 'Error. File - ' . $e->getFile() . ', message - ' . $e->getMessage() . ', row' . $e->getLine() . ', code: ' . $e->getCode();
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'Method update API', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

				$status_code = $e->getCode();

				if ( 0 === strpos( (string) $status_code, '5' ) || '422' == $status_code ) {
					SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Update attempt# ' . $attempt );
					if ( $attempt < $max_attempts ) {
						sleep( $attempt );
					}

					$attempt++;
				} else {
					return;
				}
			}
		}

		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'number of attempts exhausted. Order Id = ' . $order_id . ' ipn: ' . $ipn );
		throw new Exception( __( 'Update failed.', 'splitit_ff_payment' ) );
	}

	/**
	 * Refund method
	 *
	 * @param null   $amount Amount.
	 * @param string $currency_code Currency.
	 * @param string $ipn installments plan number.
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function refund( $amount = null, $currency_code = '', $ipn = '', $order_id = '', $reason = '', $action_type = '' ) {
		global $plugin_version;

		$api_instance = $this->get_api_instance();

		$amount          = number_format( $amount, 2, '.', '' );
		$refund_strategy = \Splitit\Model\RefundStrategy::FUTURE_INSTALLMENTS_FIRST;
		$idempotency_key = wp_generate_uuid4();

		$response = $api_instance->installmentPlan->refund(
			$amount,
			$ipn,
			$idempotency_key,
			'WooCommercePlugin.' . $plugin_version,
			$refund_strategy
		);

		if ( $response->getRefundId() && $response->getSummary()->getFailedAmount() == 0 ) {
			$message = __( 'Refund was successful, no failed amount', 'splitit_ff_payment' );
			$data    = array(
				'user_id' => get_current_user_id(),
				'method'  => __( 'refund() API', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message );

			$data['order_id']      = $order_id;
			$data['ipn']           = $ipn;
			$data['refund_id']     = $response->getRefundId();
			$data['refund_amount'] = $amount;
			$data['refund_reason'] = $reason;
			$data['action_type']   = $action_type;

			SplitIt_FlexFields_Payment_Plugin_Log::save_refund_info( $data );

			return true;
		} else {
			throw new Exception( __( 'Refund unable to be processed online, consult your Splitit Account to process manually', 'splitit_ff_payment' ) );
		}
	}

	/**
	 * Cancel method
	 *
	 * @param int $installment_plan_number Installment plan number.
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function cancel( $installment_plan_number ) {
		global $plugin_version;

		$api_instance = $this->get_api_instance();

		$idempotency_key = wp_generate_uuid4();

		$response = $api_instance->installmentPlan->cancel(
			$installment_plan_number,
			$idempotency_key,
			'WooCommercePlugin.' . $plugin_version
		);

		if ( $response->getInstallmentPlanNumber() ) {
			$message = __( 'Canceled was successful', 'splitit_ff_payment' );
			$data    = array(
				'user_id' => get_current_user_id(),
				'method'  => __( 'cancel() API', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message );

			return true;
		} else {
			SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( $response->getError() );
			throw new Exception( __( 'Cancel unable to be processed online, consult your Splitit Account to process manually', 'splitit_ff_payment' ) );
		}
	}

	/**
	 * Method for getting instance
	 *
	 * @return Client
	 */
	public function get_api_instance() {
		global $plugin_version;

		$access_token = $this->login();

		$config = Configuration::getDefaultConfiguration();

		if ( 'sandbox' === $this->environment ) {
			$config->setHost( 'https://web-api-v3.sandbox.splitit.com' );
			$config->setTokenUrl( 'https://id.sandbox.splitit.com/connect/token' );
		}

		$config->setAccessToken( $access_token );

		$client_id     = get_option( 'splitit_' . $this->environment . '_client_id' ) ? get_option( 'splitit_' . $this->environment . '_client_id' ) : $this->username;
		$client_secret = get_option( 'splitit_' . $this->environment . '_client_secret' ) ? get_option( 'splitit_' . $this->environment . '_client_secret' ) : $this->password;

		$config->setClientId( $client_id );
		$config->setClientSecret( $client_secret );

		return new Client( '', null, null, null, '', $config );
	}

	/**
	 * Method for getting information by ipn
	 *
	 * @param int $installment_plan_number Installment plan number.
	 *
	 * @throws Exception
	 */
	public function get_ipn_info( $installment_plan_number ) {
		global $plugin_version;

		$api_instance = $this->get_api_instance();

		$idempotency_key = wp_generate_uuid4();

		try {
			return $api_instance->installmentPlan->get(
				$installment_plan_number,
				$idempotency_key,
				'WooCommercePlugin.' . $plugin_version
			);
		} catch ( \Exception $e ) {
			throw new Exception( 'Exception when calling InstallmentPlanApi->get: ' . $e->getMessage() );
		}
	}

	/**
	 * @param $installment_plan_number
	 * @param $order_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function start_installments( $installment_plan_number, $order_id ) {
		global $plugin_version;

		$api_instance = $this->get_api_instance();

		$idempotency_key = wp_generate_uuid4();
		$shipping_status = \Splitit\Model\ShippingStatus::SHIPPED;

		try {

			$result = $api_instance->installmentPlan->updateOrder(
				$installment_plan_number,
				$idempotency_key,
				'WooCommercePlugin.' . $plugin_version,
				'',
				$order_id,
				$shipping_status,
				true
			);

			if ( $result->getStatus() == \Splitit\Model\PlanStatus::ACTIVE ) {
				$message = __( 'StartInstallment was successful', 'splitit_ff_payment' );
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'start_installments() API', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message );

				return true;
			} else {
				throw new Exception( 'Invalid Installment Plan Status' );
			}
		} catch ( \Exception $e ) {
			throw new Exception( 'Exception when calling InstallmentPlanApi->updateOrder in StartInstallment method: ' . $e->getMessage() );
		}
	}

	/**
	 * Verify method
	 *
	 * @param int $installment_plan_number Installment plan number.
	 *
	 * @return \Splitit\Model\VerifyAuthorizationResponse
	 * @throws Exception
	 */
	public function verify_payment( $installment_plan_number ) {

		global $plugin_version;

		$attempt      = 1;
		$max_attempts = 4;

		$error_message = '';

		while ( $attempt <= $max_attempts ) {
			$api_instance = $this->get_api_instance();

			$idempotency_key = wp_generate_uuid4();

			try {

				$result = $api_instance->installmentPlan->verifyAuthorization(
					$installment_plan_number,
					$idempotency_key,
					'WooCommercePlugin.' . $plugin_version
				);

				$message = __( 'VerifyPayment was successful', 'splitit_ff_payment' );
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'verifyPayment() API', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'info' );

				return $result;
			} catch ( \Exception $e ) {
				$error_message = $e->getMessage();

				$status_code = $e->getCode();

				if ( 0 === strpos( (string) $status_code, '5' ) || '422' == $status_code ) {
					SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Verify attempt# ' . $attempt );

					if ( $attempt < $max_attempts ) {
						sleep( $attempt );
					}

					$attempt++;
				} else {
					return;
				}
			}
		}

		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Number of attempts exhausted. Ipn: ' . $installment_plan_number . ', error: ' . $error_message );
		throw new Exception( $error_message );
	}
}
