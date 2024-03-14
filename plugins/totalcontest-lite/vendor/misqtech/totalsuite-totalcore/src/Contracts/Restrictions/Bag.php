<?php

namespace TotalContestVendors\TotalCore\Contracts\Restrictions;

/**
 * Interface Bag
 * @package TotalContestVendors\TotalCore\Contracts\Restrictions
 */
interface Bag {
	/**
	 * Add restriction.
	 *
	 * @param             $name
	 * @param Restriction $restriction
	 */
	public function add( $name, Restriction $restriction );

	/**
	 * Get restriction.
	 *
	 * @param             $name
	 *
	 * @return  Restriction|null
	 */
	public function get( $name );

	/**
	 * Remove restriction.
	 *
	 * @param $name
	 */
	public function remove( $name );

	/**
	 * Check restrictions.
	 *
	 * @return bool
	 */
	public function check();

	/**
	 * Apply restrictions.
	 */
	public function apply();

	/**
	 * Is restrictions already applied.
	 *
	 * @return bool
	 */
	public function isApplied();
}