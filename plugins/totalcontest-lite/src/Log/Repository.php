<?php

namespace TotalContest\Log;

use TotalContest\Contracts\Log\Repository as RepositoryContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Sql;

/**
 * Log repository
 * @package TotalContest\Log
 * @since   1.0.0
 */
class Repository implements RepositoryContract {
	/**
	 * @var Environment $env
	 */
	protected $env;
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var \wpdb $database
	 */
	protected $database;

	public function __construct( $env, Request $request, $database ) {
		$this->env      = $env;
		$this->request  = $request;
		$this->database = $database;
	}

	/**
	 * Get log entries.
	 *
	 * @param $query
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get( $query ) {
		$args = Arrays::parse( $query, [
			'conditions'     => [],
			'page'           => 1,
			'perPage'        => 30,
			'orderBy'        => 'date',
			'orderDirection' => 'DESC',
		] );

		/**
		 * Filters the list of arguments used for get log entries query.
		 *
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$args = apply_filters( 'totalcontest/filters/log/get/query', $args, $query );

		// Models
		$logModels = [];
		// Where clause
		$where = Sql::generateWhereClause( $args['conditions'] );
		// Order
		$order = Sql::generateOrderClause( $args['orderBy'], $args['orderDirection'] );
		// Limit clause
		$limit = $args['perPage'] === - 1 ? '' : Sql::generateLimitClause( $args['page'], $args['perPage'] );
		// Finally our fancy SQL query
		$query = "SELECT * FROM `{$this->env['db.tables.log']}` {$where} {$order} {$limit}";

		// Get results
		$logEntries = (array) $this->database->get_results( $query, ARRAY_A );
		// Iterate and convert each row to log model
		foreach ( $logEntries as $logEntry ):
			$logModels[] = new Model( $logEntry );
		endforeach;

		/**
		 * Filters the results of log repository get query.
		 *
		 * @param \TotalContest\Contracts\Log\Model[] $logModels Log entries models.
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$logModels = apply_filters( 'totalcontest/filters/log/get/results', $logModels, $args, $query );

		// Return models
		return $logModels;
	}

	/**
	 * Get log entry by id.
	 *
	 * @param $logId
	 *
	 * @return \TotalContest\Contracts\Log\Model|null
	 * @since 1.0.0
	 */
	public function getById( $logId ) {
		$result = $this->get( [ 'conditions' => [ 'id' => (int) $logId ] ] );

		return empty( $result ) ? null : $result[0];
	}

	/**
	 * Count log entries.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function count( $args ) {
		$args = Arrays::parse( $args, [ 'conditions' => [] ] );

		/**
		 * Filters the list of arguments used for count log entries query.
		 *
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$args  = apply_filters( 'totalcontest/filters/log/count/query', $args );
		$joins = '';

		if ( isset( $args['conditions']['category_id'] ) ):
			$args['conditions']['term_taxonomy_id'] = absint( $args['conditions']['category_id'] );
			unset( $args['conditions']['category_id'] );

			$joins = "INNER JOIN `{$this->database->term_relationships}` ON `{$this->env['db.tables.log']}`.`submission_id` = `{$this->database->term_relationships}`.`object_id`";
		endif;
		// Where clause
		$where = Sql::generateWhereClause( $args['conditions'] );
		// Finally our fancy SQL query
		$query = "SELECT COUNT(*) FROM `{$this->env['db.tables.log']}` {$joins} {$where}";

		// Get count
		return (int) $this->database->get_var( $query );
	}

	/**
	 * Create log entry.
	 *
	 * @param $attributes
	 *
	 * @return \TotalContest\Contracts\Log\Model|\WP_Error
	 * @since 1.0.0
	 */
	public function create( $attributes ) {

		$attributes = Arrays::parse( $attributes, [
			'date'      => TotalContest( 'datetime', [ 'now', new \DateTimeZone('UTC') ] ),
			'ip'        => $this->request->ip(),
			'useragent' => $this->request->userAgent(),
			'user_id'   => get_current_user_id(),
			'details'   => [],
		] );

		/**
		 * Filters the attributes of an log entry model used for insertion.
		 *
		 * @param array $attributes Entry attributes.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$attributes = apply_filters( 'totalcontest/filters/log/insert/attributes', $attributes );

		if ( empty( $attributes['contest_id'] ) || empty( $attributes['submission_id'] ) || empty( $attributes['action'] ) ):
			return new \WP_Error( 'missing_fields', esc_html__( 'contest_id, submission_id and action are required' ) );
		endif;

		$logModelAttributes = [
			'date'          => $attributes['date']->format( 'Y-m-d H:i:s' ),
			'ip'            => (string) $attributes['ip'],
			'useragent'     => (string) $attributes['useragent'],
			'user_id'       => (int) $attributes['user_id'],
			'contest_id'    => (int) $attributes['contest_id'],
			'submission_id' => (int) $attributes['submission_id'],
			'action'        => (string) $attributes['action'],
			'status'        => (string) $attributes['status'],
			'details'       => json_encode( (array) $attributes['details'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
		];

		$inserted = $this->database->insert( $this->env['db.tables.log'], $logModelAttributes );

		if ( ! $inserted ):
			return new \WP_Error( 'insert_fail', esc_html__( 'Unable to insert the entry.', 'totalcontest' ) );
		endif;


		$logModelAttributes['id'] = $this->database->insert_id;

		/**
		 * Filters the log entry model attributes after insertion.
		 *
		 * @param array $entryModel Log entry attributes.
		 * @param array $attributes Original insertion attributes.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$logModelAttributes = apply_filters( 'totalcontest/filters/log/insert/model', $logModelAttributes );


		return new Model( $logModelAttributes );
	}

	/**
	 * Delete log entries.
	 *
	 * @param $conditions array
	 *
	 * @return bool|\WP_Error
	 * @since 1.0.0
	 */
	public function delete( $conditions ) {
		$where = Sql::generateWhereClause( $conditions );

		if ( empty( $where ) ):
			return new \WP_Error( 'no_conditions', esc_html__( 'No conditions were specified', 'totalcontest' ) );
		endif;

		$query = "DELETE FROM `{$this->env['db.tables.log']}` {$where}";

		return (bool) $this->database->query( $query );

	}

	/**
	 * Anonymize log entries.
	 *
	 * @param $query
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function anonymize( $query ) {
		$args = Arrays::parse( $query, [
			'conditions' => [],
		] );

		/**
		 * Filters the list of arguments used for anonymize log entries query.
		 *
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$args = apply_filters( 'totalcontest/filters/log/anonymize/query', $args, $query );
		// Where clause
		$where = Sql::generateWhereClause( $args['conditions'] );

		if ( empty( $where ) ):
			return new \WP_Error( 'no_conditions', esc_html__( 'No conditions were specified', 'totalcontest' ) );
		endif;

		// Finally our fancy SQL query
		$query = "UPDATE `{$this->env['db.tables.log']}` SET `user_id` = 0, `ip` = '', `useragent` = '', `details` = '{\"anonymized\":true}' {$where}";

		// Get results
		return (bool) $this->database->query( $query );

	}
}
