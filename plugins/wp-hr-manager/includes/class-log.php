<?php
namespace WPHR\HR_MANAGER;

/**
 * wphr Log reporting class API
 *
 * @since 0.1
 *
 * @package wp-wphr
 */
class Log {

	/**
     * Initializes the Log class
     *
     * Checks for an existing Log instance
     * and if it doesn't find one, creates it.
     */
    public static function instance() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Add a new log
     *
     * @since 0.1
     *
     * @param array $data
     *
     * @return inserted_id
     */
	public function add( $data ) {
		return $this->insert_log( $data );
	}

	/**
	 * Get logs base on criteria
	 *
     * @since 0.1
	 * @since 1.2.0 Returns log count when $count param is true
	 *
     * @param  array   $data
	 * @param  boolean $count
	 *
	 * @return object Collection of log
	 */
	public function get( $args = array(), $count = false ) {
		global $wpdb;

	    $defaults = array(
	        'number'     => 20,
	        'offset'     => 0,
	        'no_object'  => false,
            'orderby'    => 'id',
            'order'      => 'DESC'
	    );

	    $args  = wp_parse_args( $args, $defaults );
	    $where = $results = [];

	    $audits = new \WPHR\HR_MANAGER\Admin\Models\Audit_Log();
	    $audit_log = $audits->leftjoin( $wpdb->users, 'created_by', '=', $wpdb->users . '.ID' )->select( $wpdb->users.'.display_name', $wpdb->prefix . 'wphr_audit_log.*' );

	    if ( isset( $args['component'] ) && ! empty( $args['component'] ) ) {
	        $audit_log = $audit_log->where( 'component', $args['component'] );
	    }

        if ( isset( $args['sub_component'] ) && ! empty( $args['sub_component'] ) ) {
            $audit_log = $audit_log->where( 'sub_component', $args['sub_component'] );
        }

	    if ( isset( $args['data_id'] ) && ! empty( $args['data_id'] ) ) {
	        $audit_log = $audit_log->where( 'data_id', $args['data_id'] );
	    }

	    if ( isset( $args['old_value'] ) && ! empty( $args['old_value'] ) ) {
	        $audit_log = $audit_log->where( 'old_value', $args['old_value'] );
	    }

	    if ( isset( $args['new_value'] ) && ! empty( $args['new_value'] ) ) {
	        $audit_log = $audit_log->where( 'new_value', $args['new_value'] );
	    }

		if ( isset( $args['changetype'] ) && ! empty( $args['changetype'] ) ) {
	        $audit_log = $audit_log->where( 'changetype', $args['changetype'] );
	    }

		if ( isset( $args['created_by'] ) && ! empty( $args['created_by'] ) ) {
	        $audit_log = $audit_log->where( 'created_by', (int)$args['created_by'] );
	    }

		// get item with date
		if ( isset( $args['start'] ) && isset( $args['end'] ) && ! empty( $args['start'] ) && ! empty( $args['end'] ) ) {
			$audit_log = $audit_log->where( 'created_at', '>=', $args['start'].' 00:00:00'  )
				->where( 'created_at', '<=', $args['end'].' 23:59:59' );
		}


	    $cache_key = 'wphr-get-audit-log' . md5( serialize( $args ) );
	    $results   = wp_cache_get( $cache_key, 'wphr' );
	    $users     = array();

	    if ( false === $results ) {
	        $results = $audit_log->skip( $args['offset'] )
	                    ->take( $args['number'] )
                        ->orderBy( $args['orderby'], $args['order'] );

            $results = $results->get()->toArray();

	        $results = wphr_array_to_object( $results );

	        wp_cache_set( $cache_key, $results, 'wphr', HOUR_IN_SECONDS );

	    } else if ( $count ) {
            return $audit_log->skip( $args['offset'] )->take( $args['number'] )->count();
        }

	    return $results;
	}

	/**
	 * Insert a new log record
	 *
	 * @since 0.1
	 *
	 * @param  array $args
	 *
	 * @return integer [inserted id]
	 */
	public function insert_log( $args ) {
		global $wpdb;

		$table = $wpdb->prefix . 'wphr_audit_log';

		$defaults = array(
			'component'     => 'HRM',
            'sub_component' => '',
			'data_id'       => null,
			'old_value'     => '',
			'new_value'     => '',
			'message'       => '',
			'changetype'    => 'add',
			'created_by'    => '',
			'created_at'    => current_time('mysql')
	    );

	    $formated = ['%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d'];

	    $fields = wp_parse_args( $args, $defaults );

	    do_action( 'wphr_after_before_audit_log', $fields );

	    $id = $wpdb->insert( $table, $fields, $formated );

	    //$inserted = \WPHR\HR_MANAGER\Admin\Models\Audit_Log::create( $fields );

	    do_action( 'wphr_after_insert_audit_log', $id, $fields );

	    return $id;
	}

}
