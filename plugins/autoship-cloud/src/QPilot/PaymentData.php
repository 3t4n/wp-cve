<?php
/**
* The QPilot Payment Data Class.
*/
class QPilotPaymentData {

	public $description;
	public $type;
	public $last_four;
	public $expiration;
	public $gateway_payment_id;
	public $gateway_payment_type;
	public $gateway_customer_id;

  /**
   * The timeout for the Calls.
   */
	public function __construct(
		$type 								= NULL,
		$gateway_payment_id 	= NULL,
		$gateway_payment_type = 7,
		$gateway_customer_id 	= NULL,
		$expiration 					= NULL,
		$last_four 						= NULL,
		$description 					= NULL
	) {

		$this->description 					= $description;
		$this->type 								= $type;
		$this->last_four 						= $last_four;
		$this->expiration 					= $expiration;
		$this->gateway_payment_id 	= $gateway_payment_id;
		$this->gateway_payment_type = $gateway_payment_type;
		$this->gateway_customer_id 	= $gateway_customer_id;

  }

}
