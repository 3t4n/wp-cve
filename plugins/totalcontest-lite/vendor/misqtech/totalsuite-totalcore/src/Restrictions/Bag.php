<?php

namespace TotalContestVendors\TotalCore\Restrictions;


use TotalContestVendors\TotalCore\Contracts\Restrictions\Bag as BagContract;
use TotalContestVendors\TotalCore\Contracts\Restrictions\Restriction as RestrictionContract;

/**
 * Class Bag
 * @package TotalContestVendors\TotalCore\Restrictions
 */
class Bag implements BagContract {
	/**
	 * @var array $bag
	 */
	protected $bag = [];
	/**
	 * @var bool $applied
	 */
	protected $applied = false;

	/**
	 * Add restriction.
	 *
	 * @param                     $name
	 * @param Restriction         $restriction
	 */
	public function add( $name, RestrictionContract $restriction ) {
		$this->bag[ (string) $name ] = $restriction;
	}

	/**
	 * Get restriction.
	 *
	 * @param            $name
	 *
	 * @return Restriction|null
	 */
	public function get( $name ) {
		return isset( $this->bag[ (string) $name ] ) ? $this->bag[ (string) $name ] : null;
	}

	/**
	 * Remove restriction.
	 *
	 * @param $name
	 */
	public function remove( $name ) {
		unset( $this->bag[ (string) $name ] );
	}

	/**
	 * Check restrictions.
	 *
	 * @return bool
	 */
	public function check() {
		foreach ( $this->bag as $restriction ):
			$result = $restriction->check();
			if ( $result instanceof \WP_Error ):
				return $result;
			endif;
		endforeach;

		return true;
	}

	/**
	 * Apply restrictions.
	 */
	public function apply() {
		$this->applied = ! empty( $this->bag );

		foreach ( $this->bag as $restriction ):
			$restriction->apply();
		endforeach;
	}

	/**
	 * Is restrictions already applied.
	 *
	 * @return bool
	 */
	public function isApplied() {
		return $this->applied;
	}
}