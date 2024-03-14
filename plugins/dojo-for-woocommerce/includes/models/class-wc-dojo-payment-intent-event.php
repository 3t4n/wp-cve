<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_Payment_Intent_Event')) {

	require_once __DIR__ . '/../class-wc-dojo-utils.php';

	/**
	 * Dojo ApiClient
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	class WC_Dojo_Payment_Intent_Event
	{

		private const PAYMENT_INTENT_UPDATED_EVENT = 'payment_intent.status_updated';

		public $id;

		public $event;

		public $payment_intent_id;

		public function __construct($json_array)
		{
			$this->id    = WC_Dojo_Utils::get_array_element($json_array, 'id', null);
			$this->event = WC_Dojo_Utils::get_array_element($json_array, 'event', '');

			$data = WC_Dojo_Utils::get_array_element($json_array, 'data', []);
			$this->payment_intent_id = WC_Dojo_Utils::get_array_element($data, 'paymentIntentId', '');
		}

		public function is_payment_status_update()
		{
			return $this->event == self::PAYMENT_INTENT_UPDATED_EVENT;
		}
	}
}
