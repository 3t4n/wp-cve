<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Dto;

use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;

class TransactionRefund extends AbstractDto {
	/**
	 * Transaction service id.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $serviceID;

	/**
	 * Pseudolosowy identyfikator komunikatu o długości 32 znaków alfanumerycznych alfabetu łacińskiego (np. na bazie UID), wartość pola musi być unikalna i wskazywać konkretne zlecenie wypłaty w Serwisie Partnera. Weryfikacja unikalności po stronie Systemu pozwala na ponawianie MessageID w przypadku problemów z komunikacją (ponowienie tej wartości skutkować będzie potwierdzeniem zlecenia, bez ponownego wykonania w Systemie).
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $messageID;

	/**
	 * Alfanumeryczny identyfikator zwracanej transakcji wejściowej nadany przez System oraz przekazywany do Partnera w komunikacie ITN transakcji wejściowej.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $remoteID;

	/**
	 * Kwota zwrotu (nie może być większa niż kwota transakcji oraz aktualne saldo serwisu + ew. kwota prowizji za zwrot); nie podanie tego parametru skutkuje zwrotem do Klienta całości środków wpłaconych na rzecz zwracanej transakcji; jako separator dziesiętny używana jest kropka - '.' Format: 0.00.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $amount;

	/**
	 * Waluta zwrotu; domyślną walutą jest PLN (użycie innej waluty musi być uzgodnione w trakcie integracji); w ramach ServiceID obsługiwana jest jedna waluta.
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $currency;

	/**
	 * @param string $serviceID
	 */
	public function set_service_ID( string $serviceID ): void {
		$this->serviceID = $serviceID;
	}

	/**
	 * @param string $messageID
	 * @param string $remoteID
	 * @param string $amount
	 * @param string $currency
	 */
	public function __construct( string $messageID, string $remoteID, string $amount, string $currency ) {
		$this->messageID = $messageID;
		$this->remoteID  = $remoteID;
		$this->amount    = $amount;
		$this->currency  = $currency;
	}
}