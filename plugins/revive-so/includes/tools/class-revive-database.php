<?php 
/**
 * Plugin Tools.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin Notice class.
 */
class REVIVESO_Database
{
	use REVIVESO_Ajax;
    use REVIVESO_Hooker;
    use REVIVESO_HelperFunctions;
    use REVIVESO_Scheduler;
	
	/**
	 * Register functions.
	 */
	public function register() {

		$this->action( 'admin_notices', 'admin_notice', 8 );
		add_action( 'admin_post_reviveso_export_settings', array( $this, 'export_settings' ) );
		add_action( 'admin_post_reviveso_import_settings', array( $this, 'import_settings' ) );
		add_action( 'admin_post_reviveso_remove_data', array( $this, 'remove_data' ) );
		add_action( 'admin_post_reviveso_remove_meta', array( $this, 'run_cleanup' ) );
		add_action( 'admin_post_reviveso_deschedule_posts', array( $this, 'deschedule_posts' ) );
		add_action( 'admin_post_reviveso_regenerate_interval', array( $this, 'regenerate_interval' ) );
		add_action( 'admin_post_reviveso_regenerate_schedule', array( $this, 'regenerate_schedule' ) );
		add_action( 'admin_post_reviveso_recreate_tables', array( $this, 'maybe_recreate_actionscheduler_tables' ) );
		$this->action( 'reviveso_deschedule_posts_task', 'deschedule_posts_task' );
		$this->action( 'action_scheduler_canceled_action', 'action_cancelled' );
		$this->action( 'action_scheduler_deleted_action', 'action_removed' );

	}
	
	/**
     * Process a settings export that generates a .json file
     */
	public function export_settings() {

		if ( ! isset( $_POST['reviveso_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['reviveso_export_nonce'] ) ), 'reviveso_export_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = get_option( 'reviveso_plugin_settings' );
		$url = get_home_url();
		$find = array( 'http://', 'https://' );
		$replace = '';
		$output = str_replace( $find, $replace, $url );

		ignore_user_abort( true );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . str_replace( '/', '-', $output ) . '-reviveso-export-' . date( 'm-d-Y', $this->current_timestamp() ) . '.json' );
		header( "Expires: 0" );
		echo wp_json_encode( $settings );
		exit;
	}

	/**
     * Process a settings import from a json file
     */
	public function import_settings() {

    	if ( ! isset( $_POST['reviveso_import_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['reviveso_import_nonce'] ) ), 'reviveso_import_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
    		return;
		}

    	if ( ! current_user_can( 'manage_options' ) ) {
    		return;
		}

		if ( empty( $_FILES['import_file']['name'] ) || empty( $_FILES['import_file']['tmp_name'] ) ) {
    		wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a file to import.', 'revive-so' ) ) );
    	}

        $extension = explode( '.', sanitize_text_field(wp_unslash( $_FILES['import_file']['name'] ) ) );
        $file_extension = end( $extension );
    	if ( 'json' !== $file_extension ) {
    		wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a valid .json file to import settings in this website.', 'revive-so' ) ) );
    	}

    	$import_file = sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) );
    	if ( empty( $import_file ) ) {
    		wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a file to import.', 'revive-so' ) ) );
    	}

    	// Retrieve the settings from the file and convert the json object to an array.
    	$settings = ( array ) json_decode( file_get_contents( $import_file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		update_option( 'reviveso_plugin_settings', $settings );

		// set temporary transient for admin notice
		set_transient( 'reviveso_import_db_done', true );
        
		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
     * Process reset plugin settings
     */
	public function admin_notice() {
    	if ( get_transient( 'reviveso_import_db_done' ) !== false ) { ?>
			<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e( 'Success! Plugin Settings has been imported successfully.', 'revive-so' ); ?></strong></p></div><?php 
		    delete_transient( 'reviveso_import_db_done' );
	    }
	}

	/**
	 * Trigger when Action Scheduler action is cancelled.
	 * 
	 * @param int   $action_id  Action ID
	 */
	public function action_cancelled( $action_id ) {
		$run_remove_hook = true;

		if ( \ActionScheduler::is_initialized() ) {
			$action = \ActionScheduler::store()->fetch_action( $action_id );

			if ( $action || ! is_a( $action, 'ActionScheduler_NullAction' ) ) {
				$run_remove_hook = false;
			}
		}

		if ( $run_remove_hook ) {
			$this->action_removed( $action_id );
		} else {
			$hook  = $action->get_hook();
			$args  = $action->get_args();
			$group = $action->get_group();

			$action_list = array(
				'reviveso_global_republish_single_post',
				'reviveso_run_single_republish',
				'reviveso_run_republish_rule_event',
			);

			if ( in_array( $hook, $action_list, true ) ) {
				$post = get_post( $args[0] );

				if ( ! is_object( $post ) ) {
					$this->action_removed( $action_id );
				} else {
					$is_saving = $this->get_meta( $post->ID, 'reviveso_post_is_saving' );
					if ( ! $is_saving ) {
						$this->perform_cleanup_regeneration( $post->ID );
					}
				}
			}
		}
	}

	/**
	 * Trigger when Action Scheduler action is deleted.
	 * 
	 * @param int   $action_id  Action ID
	 */
	public function action_removed( $action_id ) {
		$post_ids = $this->get_posts( array(
			'posts_per_page' => -1,
			'post_status'    => 'any',
    		'post_type'      => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'reviveso_post_is_saving',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'reviveso_republish_as_action_id',
					'value'   => $action_id,
					'compare' => '=',
				),
			),
		) );

		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$this->perform_cleanup_regeneration( $post_id );
			}
		}
	}

	/**
	 * Trigger when Action Scheduler action is cancelled or deleted.
	 * 
	 * @param int   $post_id  Post ID
	 */
	private function perform_cleanup_regeneration( $post_id ) {
		$this->do_action( 'as_action_removed', $post_id ); // post meta removal

		$this->delete_meta( $post_id, 'reviveso_global_republish_status' );
		$this->delete_meta( $post_id, '_reviveso_global_republish_datetime' );
	}

	/**
     * Process reset plugin settings
     */
	public function remove_data() {
		delete_option( 'reviveso_plugin_settings' );
		delete_option( 'reviveso_republish_log_history' );
		delete_option( 'reviveso_dashboard_widget_options' );
		delete_option( 'reviveso_last_global_cron_run' );
		delete_option( 'reviveso_global_republish_post_ids' );
		delete_option( 'reviveso_social_credentials' );

		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
     * Process regenerate global republish interval
     */
	public function regenerate_interval() {
    	// remove last data
		\delete_option( 'reviveso_next_scheduled_timestamp' );
		
		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
     * Process regenerate republish schedules
     */
	public function regenerate_schedule() {
		$this->do_action( 'process_regenerate_schedule');
		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Post meta cleanup.
	 */
	public function run_cleanup() {
		global $wpdb;

		// Remove schedules
		$this->unschedule_all_actions( 'reviveso_global_republish_single_post', array(), '' );

		$this->do_action( 'run_cleanup' ); // post meta removal
		$post_types = $this->get_post_types( true );
		$args = array(
			'post_type'   => $post_types,
			'numberposts' => -1,
			'post_status' => array( 'publish', 'future', 'private' ),
			'fields'      => 'ids',
		);

		$post_ids = $this->get_posts( $args );
		if ( ! empty( $post_ids ) ) {
			$post_ids_placeholders = implode( ', ', array_fill( 0, count( $post_ids ), '%d' ) );

			$where = $wpdb->prepare( 
				sprintf( " WHERE post_id IN ( %s )", $post_ids_placeholders ),
				$post_ids
			);

			$wpdb->query( $wpdb->prepare( 
				"DELETE FROM {$wpdb->postmeta} {$where} AND ( meta_key LIKE %s OR meta_key LIKE %s )",
				'%' . $wpdb->esc_like( 'reviveso_' ) . '%',
				'%' . $wpdb->esc_like( 'revs_' ) . '%',
			) );
		}
		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Remove actions.
	 */
	public function deschedule_posts() {
		$post_types = $this->get_post_types( true );
		
		$args = $this->do_filter( 'deschedule_posts_args', array(
			'post_type'   => $post_types,
			'numberposts' => -1,
			'post_status' => array( 'publish', 'future', 'private' ),
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
				    'key'     => '_reviveso_original_pub_date',
    			    'compare' => 'EXISTS',
			    ),
			),
		) );
	
		$post_ids = $this->get_posts( $args );
		$this->schedule_batch_actions( $post_ids, 'reviveso_deschedule_posts_task' );

		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Remove actions.
	 */
	public function deschedule_posts_task( array $post_ids ) {
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				// get original published date
				$pub_date = $this->get_meta( $post_id, '_reviveso_original_pub_date' );
				
				// update posts
				\wp_update_post( array( 
					'ID'            => $post_id,
					'post_date'     => $pub_date,
	    	        'post_date_gmt' => \get_gmt_from_date( $pub_date ),
				) );

				// delete old meta
				$this->delete_meta( $post_id, '_reviveso_original_pub_date' );
			}
		}
	}

    /**
	 * Recreate ActionScheduler tables if missing.
	 */
	public function maybe_recreate_actionscheduler_tables() {
		global $wpdb;

		if ( $this->is_woocommerce_active() ) {
			wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
			exit;
		}

		if (
			! class_exists( 'ActionScheduler_HybridStore' )
			|| ! class_exists( 'ActionScheduler_StoreSchema' )
			|| ! class_exists( 'ActionScheduler_LoggerSchema' )
		) {
			wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
			exit;
		}

		$table_list = array(
			'actionscheduler_actions',
			'actionscheduler_logs',
			'actionscheduler_groups',
			'actionscheduler_claims',
		);

		$found_tables = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}actionscheduler%'" );
		foreach ( $table_list as $table_name ) {
			if ( ! in_array( $wpdb->prefix . $table_name, $found_tables, true ) ) {
				$this->recreate_actionscheduler_tables();
				break;
			}
		}

		wp_safe_redirect( add_query_arg( array( 'page' => 'reviveso', 'tab' => 'tools' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Force the data store schema updates.
	 */
	private function recreate_actionscheduler_tables() {
		$store = new \ActionScheduler_HybridStore(); // @phpstan-ignore-line
		add_action( 'action_scheduler/created_table', array( $store, 'set_autoincrement' ), 10, 2 );

		$store_schema  = new \ActionScheduler_StoreSchema(); // @phpstan-ignore-line
		$logger_schema = new \ActionScheduler_LoggerSchema(); // @phpstan-ignore-line
		$store_schema->register_tables( true ); // @phpstan-ignore-line
		$logger_schema->register_tables( true ); // @phpstan-ignore-line

		remove_action( 'action_scheduler/created_table', array( $store, 'set_autoincrement' ), 10 );
	}

	/**
	 * Is WooCommerce Installed
	 *
	 * @return bool
	 */
	private function is_woocommerce_active() {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		// @codeCoverageIgnoreEnd
		return is_plugin_active( 'woocommerce/woocommerce.php' ) && function_exists( 'is_woocommerce' );
	}
}