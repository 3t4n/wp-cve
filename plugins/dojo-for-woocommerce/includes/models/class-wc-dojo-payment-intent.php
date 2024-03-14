<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_Payment_Intent')) {

	require_once __DIR__ . '/../class-wc-dojo-utils.php';

	/**
	 * Dojo ApiClient
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	class WC_Dojo_Payment_Intent
	{

		const URL_CHECKOUT    = 'https://pay.dojo.tech/checkout/%s';
		/**
		 * Payment intent statuses
		 */
		const PI_STATUS_NOT_AVAILABLE = '';
		const PI_STATUS_CREATED       = 'Created';
		const PI_STATUS_AUTHORIZED    = 'Authorized';
		const PI_STATUS_CAPTURED      = 'Captured';
		const PI_STATUS_REVERSED      = 'Reversed';
		const PI_STATUS_REFUNDED      = 'Refunded';
		const PI_STATUS_CANCELED      = 'Canceled';
		const PI_STATUS_DECLINED      = 'Declined';

		/**
		 * Payment intent event type
		 */
		const PI_EVENT_TYPE_DECLINED = 'Declined';

		/**
		 * Payment status codes
		 */
		public const PAYMENT_STATUS_CODE_UNKNOWN = 0;
		public const PAYMENT_STATUS_CODE_SUCCESS = 1;
		public const PAYMENT_STATUS_CODE_FAIL    = 2;

		public $id;

		public $status;

		public $payment_details;

		public $message;

		public $payment_status;

		public $payment_events;

		public function __construct($json_array)
		{
			$this->id              = WC_Dojo_Utils::get_array_element($json_array, 'id', null);
			$this->status          = WC_Dojo_Utils::get_array_element($json_array, 'status', '');
			$this->payment_details = WC_Dojo_Utils::get_array_element($json_array, 'paymentDetails', []);
			$this->message         = WC_Dojo_Utils::get_array_element($this->payment_details, 'message', '');
			$this->payment_events  = WC_Dojo_Utils::get_array_element($json_array, 'paymentEvents', []);

			$this->payment_status  = self::get_payment_status($this->status);
		}

		public function get_payment_hosted_page_url()
		{
			return sanitize_url(sprintf(self::URL_CHECKOUT, $this->id));
		}

		public function has_last_payment_attempt_declined()
		{
			$lastEventType = '';

			$payment_events_length = count($this->payment_events);

			if (self::PI_STATUS_CREATED == $this->status && $payment_events_length > 0) {
				$lastEventType = $this->payment_events[$payment_events_length - 1]['eventType'];
			}

			return $lastEventType == self::PI_EVENT_TYPE_DECLINED;
		}

		private static function get_payment_status($status_code)
		{
			switch ($status_code) {
				case self::PI_STATUS_AUTHORIZED:
				case self::PI_STATUS_CAPTURED:
					$result = self::PAYMENT_STATUS_CODE_SUCCESS;
					break;
				case self::PI_STATUS_DECLINED:
					$result = self::PAYMENT_STATUS_CODE_FAIL;
					break;
				default:
					$result = self::PAYMENT_STATUS_CODE_UNKNOWN;
					break;
			}
			return $result;
		}
	}
}
