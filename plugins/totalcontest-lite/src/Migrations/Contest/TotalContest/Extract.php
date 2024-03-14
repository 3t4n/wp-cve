<?php

namespace TotalContest\Migrations\Contest\TotalContest;

use TotalContest\Contracts\Migrations\Contest\Extract as ExtractContract;
use TotalContest\Contracts\Migrations\Contest\Template\Contest;

/**
 * Extract Contests.
 * @package TotalContest\Migrations\Contest\TotalContest
 */
class Extract implements ExtractContract {
	/**
	 * Count contests.
	 *
	 * @return int
	 */
	public function getCount() {
		return count( $this->getContestsIds() );
	}

	/**
	 * Get contests.
	 *
	 * @return array
	 */
	public function getContests() {
		$contestsIds       = array_slice( $this->getContestsIds(), 0, 5 );
		$extractedContests = [];

		if ( ! empty( $contestsIds ) ):

			foreach ( $contestsIds as $contestId ):
				$contest['id']      = $contestId;
				$contest['title']   = get_the_title( $contestId );
				$contest['content'] = json_decode( get_post( $contestId )->post_content, true );

				$extractedContests[] = $contest;
			endforeach;

		endif;

		return $extractedContests;
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function getOptions() {
		return (array) get_option( 'totalcontest_options_repository', [] );
	}

	/**
	 * Get contests ids array.
	 *
	 * @return array
	 */
	private function getContestsIds() {
		$filter = function ( $where, \WP_Query $wp_query ) {
			if ( $like_where_clause = $wp_query->get( 'content_not_like' ) ) {
				$like_where_clause = TotalContest('database')->esc_like( $like_where_clause );
				$where             .= " AND post_content NOT LIKE '%{$like_where_clause}%' ";
			}

			return $where;
		};

		add_filter( 'posts_where', $filter, 10, 2 );
		$posts = get_posts(
			[
				'suppress_filters' => false,
				'post_type'        => 'contest',
				'post_status'      => 'any',
				'posts_per_page'   => - 1,
				'fields'           => 'ids',
				'content_not_like' => '"schema":',
				'meta_query'       => [
					'key'     => '_migrated',
					'value'   => 'migrated',
					'compare' => 'NOT EXISTS',
				],
			]
		);
		remove_filter( 'posts_where', $filter );

		return $posts;
	}

	/**
	 * Get submissions ids array.
	 *
	 * @param $contest
	 *
	 * @return array
	 */
	private function getSubmissionsIds( Contest $contest ) {
		$filter = function ( $where, \WP_Query $wp_query ) {
			if ( $like_where_clause = $wp_query->get( 'content_not_like' ) ) {
				$like_where_clause = TotalContest('database')->esc_like( $like_where_clause );
				$where             .= " AND post_content NOT LIKE '%{$like_where_clause}%' ";
			}

			return $where;
		};

		add_filter( 'posts_where', $filter, 10, 2 );
		$posts = get_posts(
			[
				'suppress_filters' => false,
				'post_type'        => 'contest_submission',
				'post_status'      => 'any',
				'post_parent'      => $contest->getId(),
				'posts_per_page'   => - 1,
				'fields'           => 'ids',
				'content_not_like' => '"schema":',
				'meta_query'       => [
					'key'     => '_migrated',
					'value'   => 'migrated',
					'compare' => 'NOT EXISTS',
				],
			]
		);
		remove_filter( 'posts_where', $filter );

		return $posts;
	}

	/**
	 * @param Contest $contest
	 *
	 * @return array
	 */
	public function getLogEntries( Contest $contest ) {
		return [];
	}

	/**
	 * @param Contest $contest
	 *
	 * @return array
	 */
	public function getSubmissions( Contest $contest ) {
		$submissionsIds       = array_slice( $this->getSubmissionsIds( $contest ), 0, 5 );
		$extractedSubmissions = [];

		if ( ! empty( $submissionsIds ) ):

			foreach ( $submissionsIds as $submissionId ):
				$submission['id']      = $submissionId;
				$submission['title']   = get_the_title( $submissionId );
				$submission['content'] = json_decode( get_post( $submissionId )->post_content, true );

				$extractedSubmissions[] = $submission;
			endforeach;

		endif;

		return $extractedSubmissions;
	}

	/**
	 * Get migrated contests ids.
	 *
	 * @return array
	 */
	public function getMigratedContestsIds() {
		return [];
	}
}
