<?php

namespace TotalContestVendors\TotalCore\Limitations;


use TotalContestVendors\TotalCore\Contracts\Limitations\Limitation as LimitationContract;

/**
 * Class Limitation
 * @package TotalContestVendors\TotalCore\Limitations
 */
abstract class Limitation implements LimitationContract {
	/**
	 * @var array $args
	 */
	protected $args = [];

	/**
	 * Limitation constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {
		$this->args = $args;
	}
}