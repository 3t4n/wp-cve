<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Dto;

class TransactionRefundResponse extends AbstractDto {
	/**
	 * Identyfikator Serwisu Partnera; pochodzi z żądania metody.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $serviceID;

	/**
	 * Pseudolosowy identyfikator komunikatu o długości 32 znaków alfanumerycznych alfabetu łacińskiego (np. na bazie UID); pochodzi z żądania metody.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $messageID;

	/**
	 * Alfanumeryczny identyfikator transakcji rozliczeniowej nadany przez System płatności online.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $remoteOutID;

	/**
	 * Wartość funkcji skrótu dla komunikatu obliczona zgodnie z opisem w rozdziale Bezpieczeństwo. Weryfikacja zgodności wyliczonego skrótu przez Serwis Partnera jest obowiązkowa.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $hash;

	/**
	 * @param string $serviceID
	 * @param string $messageID
	 * @param string $remoteOutID
	 * @param string $hash
	 */
	public function __construct( string $serviceID, string $messageID, string $remoteOutID, string $hash ) {
		$this->serviceID   = $serviceID;
		$this->messageID   = $messageID;
		$this->remoteOutID = $remoteOutID;
		$this->hash        = $hash;
	}

	/**
	 * @return string
	 */
	public function get_remote_out_ID(): string {
		return $this->remoteOutID;
	}
}