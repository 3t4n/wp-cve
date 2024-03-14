<?php

namespace TotalContest\Contracts\Log;

/**
 * Interface Repository
 * @package TotalContest\Contracts\Log
 */
interface Repository {
	/**
	 * Get logs matching query.
	 *
	 * @param $args
	 *
	 * @return  Model[]
	 */
	public function get( $args );

	/**
	 * Get log by ID.
	 *
	 * @param $id
	 *
	 * @return Model|null
	 */
	public function getById( $id );

	/**
	 * Count logs.
	 *
	 * @param $args
	 *
	 * @return int
	 */
	public function count( $args );

	/**
	 * Create log entry.
	 *
	 * @param $attributes
	 *
	 * @return Model
	 */
	public function create( $attributes );

	/**
	 * Delete logs where conditions are met.
	 *
	 * @param $conditions
	 *
	 * @return mixed
	 */
	public function delete( $conditions );


	/**
	 * Anonymize log entries.
	 *
	 * @param $query
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function anonymize( $query );
}