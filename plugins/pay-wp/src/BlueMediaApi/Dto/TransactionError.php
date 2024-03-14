<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Dto;

class TransactionError extends AbstractDto {
	/**
	 * @var string
	 * @Type("integer")
	 */
	protected $statusCode;

	/**
	 * Ze względu na dużą zmienność listy możliwych błędów, nie jest utrzymywana jej pełna dokumentacja. Pole description, dokładnie opisuje każdy z błędów (pole statusCode i name mogą być ignorowane).
	 *
	 * @var string
	 * @Type("string")
	 */
	protected $name;

	/**
	 * @var string
	 * @Type("string")
	 */
	protected $description;

	/**
	 * @param string $statusCode
	 * @param string $name
	 * @param string $description
	 */
	public function __construct( string $statusCode, string $name, string $description ) {
		$this->statusCode  = $statusCode;
		$this->name        = $name;
		$this->description = $description;
	}
}