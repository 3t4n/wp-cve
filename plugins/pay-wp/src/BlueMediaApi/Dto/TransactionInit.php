<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Dto;

class TransactionInit extends AbstractDto {
	/**
	 * Transaction service id.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $serviceID;
	/**
	 * Transaction order id.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $orderID;
	/**
	 * Transaction amount.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $amount;
	/**
	 * Transaction description.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $description;
	/**
	 * Transaction gateway id.
	 *
	 * @var int
	 * @Type("int")
	 */
	protected $gatewayID;
	/**
	 * @var DateTime
	 * @Type("DateTime<'Y-m-d H:i:s'>")
	 */
	protected $defaultRegulationAcceptanceTime;
	/**
	 * @var string
	 * @Type("string")
	 */
	protected $defaultRegulationAcceptanceState;
	/**
	 * @var string
	 * @Type("string")
	 */
	protected $defaultRegulationAcceptanceID;
	/**
	 * Transaction currency.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $currency;
	/**
	 * Transaction customer e-mail address.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $customerEmail;
	/**
	 * Customer IP address.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $customerIP;
	/**
	 * Transaction title.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $title;
	/**
	 * Transaction validity time.
	 *
	 * @var DateTime
	 * @Type("DateTime<'Y-m-d H:i:s'>")
	 */
	protected $validityTime;
	/**
	 * Transaction link validity time.
	 *
	 * @var DateTime
	 * @Type("DateTime<'Y-m-d H:i:s'>")
	 */
	protected $linkValidityTime;
	/**
	 * Transaction authorization code.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $authorizationCode;
	/**
	 * Screen tpe for payment authorization (only for card payment).
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $screenType;
	/**
	 * Transaction customer bank account number.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $customerNRB;
	/**
	 * Transaction tax country.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $taxCountry;
	/**
	 * Transaction receiver name.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $receiverName;
	/**
	 * BLIK Alias UID key.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $blikUIDKey;
	/**
	 * BLIK Alias UID label.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $blikUIDLabel;
	/**
	 * BLIK banks mobile application key.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $blikAMKey;
	/**
	 * Receiver bank account number.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $receiverNRB;
	/**
	 * Receiver address.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $receiverAddress;
	/**
	 * Remote order id.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $remoteID;
	/**
	 * Transaction hash.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $hash;
	/**
	 * Banks system URL.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $bankHref;
	/**
	 * return address.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $returnURL;
	/**
	 * @param string $serviceID
	 * @return Transaction
	 */
}