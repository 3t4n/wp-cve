<?php

defined( 'ABSPATH' ) || exit;

class Dracula_Analytics {

	private static $instance = null;
	private $table;

	public $start_date;
	public $end_date;

	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . 'dracula_analytics';

		$this->start_date = date( 'Y-m-d', strtotime( '-1 month' ) );
		$this->end_date   = date( 'Y-m-d' );

		$is_pro               = ddm_fs()->can_use_premium_code__premium_only();
		$is_analytics_enabled = dracula_get_settings( 'enableAnalytics', false );

		// Add admin menu
		if ( ! $is_pro || $is_analytics_enabled ) {
			add_action( 'dracula_admin_menu', [ $this, 'add_analytics_menu' ] );
		}

		if ( $is_pro && $is_analytics_enabled ) {

			// Handle total users and total page views analytics
			add_action( 'template_redirect', [ $this, 'track_visitor' ] );

			// Track analytics
			add_action( 'wp_ajax_dracula_track_analytics', [ $this, 'track_analytics' ] );
			add_action( 'wp_ajax_nopriv_dracula_track_analytics', [ $this, 'track_analytics' ] );

			// Get analytics
			add_action( 'wp_ajax_dracula_get_analytics', [ $this, 'get_analytics' ] );

			// Insert feedback
			add_action( 'wp_ajax_dracula_insert_feedback', [ $this, 'insert_feedback' ] );
			add_action( 'wp_ajax_nopriv_dracula_insert_feedback', [ $this, 'insert_feedback' ] );

			// Get Feedbacks
			add_action( 'wp_ajax_dracula_get_feedbacks', [ $this, 'get_feedbacks' ] );

			// Export Analytics
			add_action( 'wp_ajax_dracula_export_analytics', [ $this, 'export_analytics' ] );

			// Delete Analytics
			add_action( 'wp_ajax_dracula_clear_analytics', [ $this, 'clear_analytics' ] );
		}
	}

	public function clear_analytics() {
		$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'dracula' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'dracula-dark-mode' ) ] );
		}

		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$this->table}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}dracula_feedbacks" );

		wp_send_json_success();
	}

	public function export_analytics() {
		$start_date = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
		$end_date   = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
		$end_date   = $end_date . ' 23:59:59';

		global $wpdb;
		$table = $wpdb->prefix . 'dracula_analytics';

		$analytics = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table WHERE date BETWEEN %s AND %s ORDER BY date DESC",
			$start_date, $end_date ), ARRAY_A );

		$data = [];

		foreach ( $analytics as $analytic ) {
			$data[] = [
				'date'         => $analytic['date'],
				'total_user'   => $analytic['visitor'],
				'dark_mode'    => $analytic['activation'],
				'total_view'   => $analytic['view'],
				'dark_view'    => $analytic['dark_view'],
				'activation'   => $analytic['activation'],
				'deactivation' => $analytic['deactivation'],
			];
		}

		$filename  = 'dracula-analytics-' . date( 'Y-m-d' ) . '.csv';
		$file_path = sys_get_temp_dir() . '/' . $filename;

		$file = fopen( $file_path, 'w' );

		fputcsv( $file, array(
			__( 'Date', 'dracula-dark-mode' ),
			__( 'Total User', 'dracula-dark-mode' ),
			__( 'Dark Mode User', 'dracula-dark-mode' ),
			__( 'Total View', 'dracula-dark-mode' ),
			__( 'Dark View', 'dracula-dark-mode' ),
			__( 'Activation', 'dracula-dark-mode' ),
			__( 'Deactivation', 'dracula-dark-mode' ),
		) );

		foreach ( $data as $row ) {
			fputcsv( $file, $row );
		}

		fclose( $file );

		// save the file in a temporary location
		$file_url = 'tmp/' . $filename;

		if ( ! is_dir( 'tmp' ) ) {
			mkdir( 'tmp', 0755, true );
		}
		if ( file_exists( $file_path ) ) {
			copy( $file_path, $file_url );
		}

		unlink( $file_path );

		// Send the zip file URL in the AJAX response
		wp_send_json_success( [
			'success' => true,
			'url'     => $file_url,
		] );

	}


	public function get_feedbacks( $is_init = false ) {
		$per_page = 20;

		if ( $is_init ) {
			$start_date = $this->start_date;
			$end_date   = $this->end_date;

			$offset = 0;
		} else {
			$start_date = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
			$end_date   = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
			$end_date   = $end_date . ' 23:59:59';

			$page   = ! empty( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
			$offset = ( $page - 1 ) * $per_page;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'dracula_feedbacks';

		$feedbacks = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table WHERE date BETWEEN %s AND %s ORDER BY date DESC LIMIT %d OFFSET %d",
			$start_date, $end_date, $per_page, $offset ), ARRAY_A );

		if ( $is_init ) {
			return $feedbacks;
		}

		$data = [
			'complete'  => empty( $feedbacks ) || count( $feedbacks ) < $per_page,
			'feedbacks' => $feedbacks,
		];

		wp_send_json_success( $data );
	}

	public function insert_feedback() {
		$message = ! empty( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

		global $wpdb;
		$table = $wpdb->prefix . 'dracula_feedbacks';

		$wpdb->insert( $table, [ 'message' => $message ] );

		wp_send_json_success();
	}

	public function add_analytics_menu( Dracula_Admin $admin ) {
		$admin->admin_pages['analytics'] = add_submenu_page( 'dracula', __( 'Analytics - Dracula Dark Mode', 'dracula-dark-mode' ), __( 'Analytics', 'dracula-dark-mode' ), 'manage_options', 'dracula-analytics', array(
			$this,
			'render_analytics_page'
		) );
	}

	public function render_analytics_page() { ?>
        <div id="dracula-analytics"></div>
	<?php }

	public function get_analytics() {

		$this->start_date = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : $this->start_date;

		$end_date       = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : $this->end_date;
		$this->end_date = $end_date . ' 23:59:59';

		$data = array(
			'total_user'         => $this->get_total_user(),
			'dark_mode_user'     => $this->get_dark_mode_user(),
			'total_view'         => $this->get_total_view(),
			'dark_view'          => $this->get_dark_view(),
			'total_activation'   => $this->get_total_activation(),
			'total_deactivation' => $this->get_total_deactivation(),
			'activations'        => $this->get_activations(),
			'feedbacks'          => $this->get_feedbacks( true ),
		);

		wp_send_json_success( $data );
	}

	public function get_count( $column, $condition = "" ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT $column FROM {$this->table} WHERE date BETWEEN %s AND %s $condition",
			$this->start_date, $this->end_date
		);

		return $wpdb->get_var( $sql );
	}

	public function get_total_user() {
		return $this->get_count( 'COUNT(DISTINCT user_key)' );
	}

	public function get_dark_mode_user() {
		return $this->get_count( 'COUNT(DISTINCT user_key)', "AND activation > 0" );
	}

	public function get_total_view() {
		return $this->get_count( 'SUM(view)' );
	}

	public function get_dark_view() {
		return $this->get_count( 'SUM(dark_view)' );
	}

	public function get_total_activation() {
		return $this->get_count( 'SUM(activation)' );
	}

	public function get_total_deactivation() {
		return $this->get_count( 'SUM(deactivation)' );
	}

	public function get_activations() {
		global $wpdb;
		$table = $wpdb->prefix . 'dracula_analytics';

		return $wpdb->get_results( $wpdb->prepare(
			"SELECT date, SUM(activation) AS count FROM $table WHERE date BETWEEN %s AND %s GROUP BY date",
			$this->start_date, $this->end_date
		), ARRAY_A );
	}

	public function track_analytics() {
		$type = ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

		if ( ! $type ) {
			wp_send_json_error( 'Invalid Action' );
		}

		global $wpdb;

		$sql = "INSERT INTO 
                        {$this->table} (`unique_id`, `user_key`, `{$type}`) 
                    VALUES 
                        (%s, %s, 1)
                    ON DUPLICATE KEY UPDATE
                        `{$type}` = `{$type}` + 1
                ";

		$wpdb->query( $wpdb->prepare( $sql, [
			$this->get_unique_id( $type ),
			$this->get_user_key(),
		] ) );

		wp_send_json_success();
	}

	public function track_visitor() {
		global $wpdb;

		// Track unique users
		if ( ! isset( $_COOKIE['dracula_user_key'] ) ) {

			$sql_users = "INSERT INTO 
                        {$this->table} (`unique_id`, `user_key`, `visitor`) 
                    VALUES 
                        (%s, %s, 1)
                    ON DUPLICATE KEY UPDATE
                        `visitor` = `visitor` + 1
                ";

			$result = $wpdb->query( $wpdb->prepare( $sql_users, [
				$this->get_unique_id( 'visitor' ),
				$this->get_user_key(),
			] ) );

		}

		// Track total page views
		$sql_views = "INSERT INTO 
                    {$this->table} (`unique_id`, `user_key`, `view`) 
                VALUES 
                    (%s, %s, 1)
                ON DUPLICATE KEY UPDATE
                    `view` = `view` + 1
            ";

		$wpdb->query( $wpdb->prepare( $sql_views, [
			$this->get_unique_id( 'view' ),
			$this->get_user_key(),
		] ) );

	}

	public function get_user_key() {
		if ( isset( $_COOKIE['dracula_user_key'] ) ) {
			$unique_id = sanitize_key( $_COOKIE['dracula_user_key'] );
		} else {
			$unique_id = wp_generate_uuid4();

			setcookie( 'dracula_user_key', $unique_id, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}

		return $unique_id;
	}

	public function get_unique_id( $type ) {
		$user_key = $this->get_user_key();
		$date     = current_time( 'Y-m-d' );

		return md5( $date . $type . $user_key );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

Dracula_Analytics::instance();