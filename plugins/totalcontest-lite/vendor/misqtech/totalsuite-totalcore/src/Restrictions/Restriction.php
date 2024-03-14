<?php

namespace TotalContestVendors\TotalCore\Restrictions;


use TotalContestVendors\TotalCore\Contracts\Restrictions\Restriction as RestrictionContract;

/**
 * Class Restriction
 * @package TotalContestVendors\TotalCore\Restrictions
 */
abstract class Restriction implements RestrictionContract {
	/**
	 * @var array $args
	 */
	protected $args = [];

	/**
	 * Restriction constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {
		$this->args = $args;
	}
}