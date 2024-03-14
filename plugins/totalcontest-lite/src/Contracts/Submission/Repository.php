<?php

namespace TotalContest\Contracts\Submission;


/**
 * Interface Repository
 * @package TotalContest\Contracts\Submission
 */
interface Repository {
	/**
	 * @param $query
	 *
	 * @return mixed
	 */
	public function get( $query );

	/**
	 * @param $query
	 *
	 * @return mixed
	 */
	public function paginate( $query );

	/**
	 * @param $submission
	 *
	 * @return mixed
	 */
	public function getById( $submission );

	/**
	 * @param $query
	 *
	 * @return mixed
	 */
	public function count( $query );
}