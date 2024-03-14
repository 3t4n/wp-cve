<?php

namespace TotalContestVendors\TotalCore\Limitations;


use TotalContestVendors\TotalCore\Contracts\Limitations\Bag as BagContract;
use TotalContestVendors\TotalCore\Contracts\Limitations\Limitation as LimitationContract;

/**
 * Class Bag
 * @package TotalContestVendors\TotalCore\Limitations
 */
class Bag implements BagContract {
	protected $bag = [];

	/**
	 * Add limitation.
	 *
	 * @param                    $name
	 * @param LimitationContract $limitation
	 *
	 * @return void
	 */
	public function add( $name, LimitationContract $limitation ) {
		$this->bag[ (string) $name ] = $limitation;
	}

	/**
	 * Get limitation.
	 *
	 * @param            $name
	 *
	 * @return LimitationContract|null
	 */
	public function get( $name ) {
		return isset( $this->bag[ (string) $name ] ) ? $this->bag[ (string) $name ] : null;
	}

	/**
	 * Remove limitation.
	 *
	 * @param $name
	 *
	 * @return void
	 */
	public function remove( $name ) {
		unset( $this->bag[ (string) $name ] );
	}

	/**
	 * Check limitations.
	 *
	 * @return bool
	 */
	public function check() {
		foreach ( $this->bag as $limitation ):
			$result = $limitation->check();
			if ( $result instanceof \WP_Error ):
				return $result;
			endif;
		endforeach;

		return true;
	}
}