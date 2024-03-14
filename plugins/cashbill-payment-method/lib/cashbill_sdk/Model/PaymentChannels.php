<?php

/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Model;

class PaymentChannels {
	/**
	 *
	 * @var array $paymentChannels
	 * @var string $lang
	 */
	private $paymentChannels, $lang;
	
	/**
	 *
	 * @param array $paymentChannels        	
	 * @param string $lang        	
	 */
	function __construct($paymentChannels, $lang = 'pl') {
		$this->paymentChannels = $paymentChannels;
		$this->lang = $lang;
	}
	/**
	 *
	 * @return array $paymentChannels
	 *        
	 */
	function getAllPaymentChannels() {
		return $this->paymentChannels;
	}

	/**
	 *
	 * @return string $lang
	 *        
	 */
	function getLang() {
		return $this->lang;
	}

}
