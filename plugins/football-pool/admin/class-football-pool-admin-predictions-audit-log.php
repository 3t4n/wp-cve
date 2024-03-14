<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Admin_Predictions_Audit_Log extends Football_Pool_Admin {
	public function __construct() {}

	public static function screen_options() {
		$screen = get_current_screen();
		$args = array(
			'label' => __( 'Log lines', 'football-pool' ),
			'default' => FOOTBALLPOOL_ADMIN_DEFAULT_PER_PAGE,
			'option' => 'footballpool_log_lines_per_page'
		);
		$screen->add_option( 'per_page', $args );
	}

	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$reset = Football_Pool_Utils::post_str( 'reset' );
		if ( $reset !== '' ) $search = '';

		$subtitle = self::get_search_subtitle( $search );
		$action = Football_Pool_Utils::request_str( 'action', 'view' );

		self::admin_header( __( 'Predictions Audit Log', 'football-pool' ), $subtitle );

		switch ( $action ) {
			case 'truncate':
				self::truncate();
				break;
			case 'truncate-confirmed':
				self::truncate_confirmed();
			default:
				self::view();
		}

		self::admin_footer();
	}

	private static function truncate() {
		echo '<p>', __( 'Are you sure?', 'football-pool' ), '</p>';
		echo '<p class="submit">';
		self::cancel_button();
		self::primary_button( __( 'OK' ), 'truncate-confirmed' );
		echo '</p>';
	}

	private static function truncate_confirmed() {
		self::empty_table( 'predictions_audit_log' );
	}

	private static function view() {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		// search in username or logs
		$search = Football_Pool_Utils::request_str( 's' );
		$user_id = Football_Pool_Utils::request_int( 'user' );

		$reset = Football_Pool_Utils::post_str( 'reset' );
		if ( $reset !== '' ) {
			$search = '';
			$user_id = 0;
		}

		$users = $pool->get_users( 0 );
		$options = [];
		$options[0] = __( 'all users', 'football-pool' );
		foreach ( $users as $user ) {
			$options[$user['user_id']] = "{$user['user_name']} (id: {$user['user_id']})";
		}

		// User search
		echo '<p>';
		echo __( 'User', 'football-pool' ) , ': ';
		echo Football_Pool_Utils::select( 'user', $options, $user_id );
		echo '&nbsp;';
		submit_button( __( 'Search', 'football-pool' ), 'secondary', null, false );
		submit_button( __( 'Refresh', 'football-pool' ), 'secondary', null, false, ['style' => 'float:right;'] );
		echo '</p>';

		// Textual search in log
		echo '<p>';
		self::text_input( __( 'Search log', 'football-pool' ) . ': ', 's', $search );
		echo '&nbsp;';
		submit_button( __( 'Search', 'football-pool' ), 'secondary', null, false );
		submit_button( __( 'Reset', 'football-pool' ), 'secondary', 'reset', false, ['style' => 'float:right;'] );
		echo '</p>';

		$sql = $wpdb->prepare(
			"SELECT COUNT(*) FROM {$prefix}predictions_audit_log 
             WHERE ( 0 = %d OR user_id = %d ) AND ( %s = '' OR log_value LIKE %s )",
			$user_id, $user_id, $search, '%' . $wpdb->esc_like( $search ) . '%'
		);
		$rows = $wpdb->get_var( $sql );
		$num_log_lines = is_null( $rows ) ? 0 : (int) $rows;

		$pagination = new Football_Pool_Pagination( $num_log_lines );
		$pagination->wrap = true;
		$pagination->set_page_size( self::get_screen_option( 'per_page' ) );
		$pagination->add_query_arg( 's', $search );
		$pagination->add_query_arg( 'user', $user_id );

		$page_size = $pagination->get_page_size();
		$offset = ( $pagination->current_page - 1 ) * $pagination->get_page_size();

		$sql = $wpdb->prepare(
			"SELECT
                log_date AS `log date`,
                user_id AS `user`,
                type AS `type`,
                source_id AS `ID`,
                result_code AS `result`,
                log_value AS `log`
			 FROM {$prefix}predictions_audit_log 
             WHERE ( user_id = %d OR 0 = %d ) AND ( %s = '' OR log_value LIKE %s )
             ORDER BY log_date DESC
             LIMIT {$offset}, {$page_size}",
			$user_id, $user_id, $search, '%' . $wpdb->esc_like( $search ) . '%'
		);
		$rows = $wpdb->get_results( $sql, ARRAY_A );

		$pagination->show();

		echo '<table class="wp-list-table widefat striped table-view-list">';
		if ( ! is_null( $rows ) && $num_log_lines > 0 ) {
			// Since the log messsages itself are not translated, it seems a bit odd to translate the types and result.
			$types = ['match', 'question'];
			$results = ['error', 'success'];
//			$types = [__( 'match', 'football-pool' ), __( 'question', 'football-pool' )];
//			$results = [__( 'error', 'football-pool' ), __( 'success', 'football-pool' )];

			echo '<thead><tr>';
			foreach( array_keys( $rows[0] ) as $th ) {
				echo "<th>{$th}</th>";
			}
			echo '</tr></thead><tbody>';
			foreach( $rows as $row ) {
				$user = $pool->user_name( $row['user'] );
				$log = nl2br( Football_Pool_Utils::xssafe( $row['log'], null, false ) );
				$log_date = Football_Pool_Utils::date_from_gmt( $row['log date'], 'Y-m-d H:i:s' );

				echo '<tr>';
				echo "<td>{$log_date}</td>";
				echo "<td>{$user}</td>";
				echo "<td>{$types[$row['type']]}</td>";
				echo "<td>{$row['ID']}</td>";
				echo "<td>{$results[$row['result']]}</td>";
				echo "<td>{$log}</td>";
				echo '</tr>';
			}
			echo '</tbody>';
		} else {
			echo '<tbody><tr><td>';
			echo __( 'no data', 'football-pool' );
			echo '</td></tr></tbody>';
		}
		echo '</table>';

		self::primary_button( __( 'Empty the log', 'football-pool' ), 'truncate', true );
	}

}
