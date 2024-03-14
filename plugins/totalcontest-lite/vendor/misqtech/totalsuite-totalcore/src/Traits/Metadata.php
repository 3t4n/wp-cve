<?php

namespace TotalContestVendors\TotalCore\Traits;


/**
 * Trait Metadata
 * @package TotalContestVendors\TotalCore\Traits
 */
trait Metadata {
	/**
	 * Add meta data.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param bool   $unique
	 *
	 * @return false|int
	 */
	public function addMetadata( $key, $value, $unique = true ) {
		return add_post_meta( $this->getId(), $key, $value, $unique );
	}

	/**
	 * Update meta data.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param null   $match
	 *
	 * @return bool|int
	 */
	public function updateMetadata( $key, $value, $match = null ) {
		return update_post_meta( $this->getId(), $key, $value, $match );
	}

	/**
	 * Delete meta data.
	 *
	 * @param string $key
	 * @param null   $match
	 *
	 * @return bool
	 */
	public function deleteMetadata( $key, $match = null ) {
		return delete_post_meta( $this->getId(), $key, $match );
	}

	/**
	 * Get meta data.
	 *
	 * @param string $key
	 * @param bool   $single
	 *
	 * @return mixed
	 */
	public function getMetadata( $key, $single = true ) {
		return get_post_meta( $this->getId(), $key, $single );
	}

	/**
	 * Increment meta data.
	 *
	 * @param     $key
	 * @param int $incrementBy
	 *
	 * @return int
	 */
	public function incrementMetadata( $key, $incrementBy = 1 ) {
		$value = (int) $this->getMetadata( $key );
		$value += $incrementBy;

		$this->updateMetadata( $key, $value );

		return $value;
	}
}