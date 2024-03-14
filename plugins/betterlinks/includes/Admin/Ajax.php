<?php

namespace BetterLinks\Admin;

use BetterLinks\Cron;

class Ajax {

	use \BetterLinks\Traits\Links;
	use \BetterLinks\Traits\Terms;
	use \BetterLinks\Traits\Clicks;
	use \BetterLinks\Traits\ArgumentSchema;

	public function __construct() {
		// link & clicks.
		add_action( 'wp_ajax_betterlinks/admin/search_clicks_data', array( $this, 'search_clicks_data' ) );
		add_action( 'wp_ajax_betterlinks/admin/links_reorder', array( $this, 'links_reorder' ) );
		add_action( 'wp_ajax_betterlinks/admin/links_move_reorder', array( $this, 'links_move_reorder' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_links_by_short_url', array( $this, 'get_links_by_short_url' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_links_by_permalink', array( $this, 'get_links_by_permalink' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_cat_by_link_id', array( $this, 'get_category_by_link_id' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_autolink_create_settings', array( $this, 'get_auto_link_create_settings' ) );
		add_action( 'wp_ajax_betterlinks/admin/write_json_links', array( $this, 'write_json_links' ) );
		add_action( 'wp_ajax_betterlinks/admin/write_json_clicks', array( $this, 'write_json_clicks' ) );
		add_action( 'wp_ajax_betterlinks/admin/analytics', array( $this, 'analytics' ) );
		add_action( 'wp_ajax_betterlinks/admin/short_url_unique_checker', array( $this, 'short_url_unique_checker' ) );
		add_action( 'wp_ajax_betterlinks/admin/cat_slug_unique_checker', array( $this, 'cat_slug_unique_checker' ) );
		add_action( 'wp_ajax_betterlinks/admin/reset_analytics', array( $this, 'reset_analytics' ) );
		// prettylinks.
		add_action( 'wp_ajax_betterlinks/admin/get_prettylinks_data', array( $this, 'get_prettylinks_data' ) );
		add_action( 'wp_ajax_betterlinks/admin/run_prettylinks_migration', array( $this, 'run_prettylinks_migration' ) );
		add_action( 'wp_ajax_betterlinks/admin/migration_prettylinks_notice_hide', array( $this, 'migration_prettylinks_notice_hide' ) );
		add_action( 'wp_ajax_betterlinks/admin/deactive_prettylinks', array( $this, 'deactive_prettylinks' ) );
		// simple 301.
		add_action( 'wp_ajax_betterlinks/admin/get_simple301redirects_data', array( $this, 'get_simple301redirects_data' ) );
		add_action( 'wp_ajax_betterlinks/admin/run_simple301redirects_migration', array( $this, 'run_simple301redirects_migration' ) );
		add_action( 'wp_ajax_betterlinks/admin/migration_simple301redirects_notice_hide', array( $this, 'migration_simple301redirects_notice_hide' ) );
		add_action( 'wp_ajax_betterlinks/admin/deactive_simple301redirects', array( $this, 'deactive_simple301redirects' ) );
		// Thirsty affiliates.
		add_action( 'wp_ajax_betterlinks/admin/get_thirstyaffiliates_data', array( $this, 'get_thirstyaffiliates_data' ) );
		add_action( 'wp_ajax_betterlinks/admin/run_thirstyaffiliates_migration', array( $this, 'run_thirstyaffiliates_migration' ) );
		add_action( 'wp_ajax_betterlinks/admin/deactive_thirstyaffiliates', array( $this, 'deactive_thirstyaffiliates' ) );
		// API Fallbck Ajax.
		add_action( 'wp_ajax_betterlinks/admin/get_all_links', array( $this, 'get_all_links' ) );
		add_action( 'wp_ajax_betterlinks/admin/create_link', array( $this, 'create_new_link' ) );
		add_action( 'wp_ajax_betterlinks/admin/update_link', array( $this, 'update_existing_link' ) );
		add_action( 'wp_ajax_betterlinks/admin/handle_favorite', array( $this, 'handle_links_favorite_option' ) );
		add_action( 'wp_ajax_betterlinks/admin/delete_link', array( $this, 'delete_existing_link' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_settings', array( $this, 'get_settings' ) );
		add_action( 'wp_ajax_betterlinks/admin/update_settings', array( $this, 'update_settings' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_terms', array( $this, 'get_terms' ) );
		add_action( 'wp_ajax_betterlinks/admin/create_new_term', array( $this, 'create_new_term' ) );
		add_action( 'wp_ajax_betterlinks/admin/update_term', array( $this, 'update_existing_term' ) );
		add_action( 'wp_ajax_betterlinks/admin/delete_term', array( $this, 'delete_existing_term' ) );
		add_action( 'wp_ajax_betterlinks/admin/fetch_analytics', array( $this, 'fetch_analytics' ) );

		// post type, tags, categories.
		add_action( 'wp_ajax_betterlinks/admin/get_post_types', array( $this, 'get_post_types' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_post_tags', array( $this, 'get_post_tags' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_post_categories', array( $this, 'get_post_categories' ) );

		// Affiliate Disclosure Text.
		add_action( 'wp_ajax_betterlinks/admin/set_affiliate_link_disclosure_post', array( $this, 'set_affiliate_link_disclosure_post' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_affiliate_link_disclosure_post', array( $this, 'get_affiliate_link_disclosure_post' ) );
		add_action( 'wp_ajax_betterlinks/admin/set_affiliate_link_disclosure_text', array( $this, 'set_affiliate_link_disclosure_text' ) );
		add_action( 'wp_ajax_betterlinks/admin/get_affiliate_link_disclosure_text', array( $this, 'get_affiliate_link_disclosure_text' ) );

		// Auto create links settings.
		add_action( 'wp_ajax_betterlinks/admin/get_auto_create_links_settings', array( $this, 'get_auto_create_links_settings' ) );
		// External Analytics settings.
		add_action( 'wp_ajax_betterlinks/admin/get_external_analytics', array( $this, 'get_external_analytics' ) );

		// Analytics
		add_action( 'wp_ajax_betterlinks__admin_fetch_analytics_graph', array( $this, 'fetch_analytics_graph' ) );

		// Notices
		add_action( 'wp_ajax_betterlinks__admin_menu_notice', array( $this, 'admin_menu_notice' ) );
		add_action( 'wp_ajax_betterlinks__admin_dashboard_notice', array( $this, 'admin_dashboard_notice' ) );
	}

	public function admin_dashboard_notice() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}

		$dashboard_notice = get_option('betterlinks_dashboard_notice', 0);
		if( BETTERLINKS_MENU_NOTICE !== $dashboard_notice ){
			update_option('betterlinks_dashboard_notice', BETTERLINKS_MENU_NOTICE);
		}
		wp_send_json([
			'result' => BETTERLINKS_MENU_NOTICE
		]);
	}
	public function admin_menu_notice() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		wp_send_json([
			'result' => get_option('betterlinks_menu_notice', 0)
		]);
	}
	public function fetch_analytics_graph() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}

		$from = isset( $_POST['from'] ) ? sanitize_text_field( wp_unslash( $_POST['from'] ) ) : '';
		$to = isset( $_POST['to'] ) ? sanitize_text_field( wp_unslash( $_POST['to'] ) ) : '';
		global $wpdb;
		$query =  "SELECT id,link_id,ip,created_at FROM {$wpdb->prefix}betterlinks_clicks WHERE created_at BETWEEN '{$from} 00:00:00' AND '{$to} 00:00:00'";
		$results = $wpdb->get_results($query);
		wp_send_json([
			'results' => $results,
		]);
	}

	public function get_prettylinks_data() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$links_count  = \BetterLinks\Helper::get_prettylinks_links_count();
		$clicks_count = \BetterLinks\Helper::get_prettylinks_clicks_count();
		set_transient(
			'betterlinks_migration_data_prettylinks',
			array(
				'links_count'  => $links_count,
				'clicks_count' => $clicks_count,
			),
			60 * 5
		);
		wp_send_json_success(
			array(
				'links_count'  => $links_count,
				'clicks_count' => $clicks_count,
			)
		);
	}

	public function run_prettylinks_migration() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		// give betterlinks a lot of time to properly set the migration work for background.
		set_time_limit( 300 );

		if ( \BetterLinks\Helper::btl_get_option( 'btl_prettylink_migration_should_not_start_in_background' ) ) {
			// preventing multiple migration call to prevent duplicate datas from migrating.
			wp_send_json_error( array( 'duplicate_migration_detected__so_prevented_it_here' => true ) );
		}
		\BetterLinks\Helper::btl_update_option( 'btl_prettylink_migration_should_not_start_in_background', true, true );
		global $wpdb;
		$query = "DELETE FROM {$wpdb->prefix}options WHERE option_name IN(
                'betterlinks_notice_ptl_migration_running_in_background',
                'btl_failed_migration_prettylinks_links',
                'btl_failed_migration_prettylinks_clicks',
                'btl_migration_prettylinks_current_successful_links_count',
                'btl_migration_prettylinks_current_successful_clicks_count'
        )";
		$wpdb->query( $query ); // phpcs:ignore.
		\BetterLinks\Helper::btl_update_option( 'btl_failed_migration_prettylinks_links', array(), true );
		\BetterLinks\Helper::btl_update_option( 'btl_failed_migration_prettylinks_clicks', array(), true );
		\BetterLinks\Helper::btl_update_option( 'btl_migration_prettylinks_current_successful_links_count', 0, true );
		\BetterLinks\Helper::btl_update_option( 'btl_migration_prettylinks_current_successful_clicks_count', 0, true );

		$type                  = isset( $_POST['type'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['type'] ) ) ) : '';
		$total_links_clicks    = get_transient( 'betterlinks_migration_data_prettylinks' );
		$should_migrate_links  = ! ( strpos( $type, 'links' ) === false );
		$should_migrate_clicks = ! ( strpos( $type, 'clicks' ) === false );

		$installer = new \BetterLinks\Installer();
		if ( $should_migrate_links && ! empty( $total_links_clicks['links_count'] ) ) {
			$links_count = absint( $total_links_clicks['links_count'] );
			$installer   = \BetterLinks\Helper::run_migration_for_ptrl_links_in_background( $installer, $links_count );
		}

		if ( $should_migrate_clicks && ! empty( $total_links_clicks['clicks_count'] ) ) {
			$clicks_count = absint( $total_links_clicks['clicks_count'] );
			$installer    = \BetterLinks\Helper::run_migration_for_ptrl_clicks_in_background( $installer, $clicks_count );
		}

		$installer->data( array( 'betterlinks_notice_ptl_migrate' ) )->save();
		$installer->dispatch();
		\BetterLinks\Helper::btl_update_option( 'betterlinks_notice_ptl_migration_running_in_background', true, true );
		wp_send_json_success( array( 'btl_prettylinks_migration_running_in_background' => true ) );
	}

	public function migration_prettylinks_notice_hide() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		if ( 'deactive' === $type ) {
			update_option( 'betterlinks_hide_notice_ptl_deactive', true );
		} elseif ( 'migrate' === $type ) {
			update_option( 'betterlinks_hide_notice_ptl_migrate', true );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function deactive_prettylinks() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$deactivate = deactivate_plugins( 'pretty-link/pretty-link.php' );
		wp_send_json_success( $deactivate );
	}
	public function write_json_links() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinks/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) ) { // phpcs:ignore.
			$Cron    = new Cron();
			$resutls = $Cron->write_json_links();
			wp_send_json_success( $resutls );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function write_json_clicks() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinks/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) && ! BETTERLINKS_EXISTS_CLICKS_JSON ) {
			$emptyContent = '{}';
			$file_handle  = @fopen( trailingslashit( BETTERLINKS_UPLOAD_DIR_PATH ) . 'clicks.json', 'wb' );
			if ( $file_handle ) {
				fwrite( $file_handle, $emptyContent );
				fclose( $file_handle );
			}
			wp_send_json_success( true );
		}
		wp_send_json_error( false );
	}
	public function analytics() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinks/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) ) {
			$Cron    = new Cron();
			$resutls = $Cron->analytics();
			wp_send_json_success( $resutls );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function short_url_unique_checker() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinks/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) ) {
			$ID            = isset( $_POST['ID'] ) ? sanitize_text_field( $_POST['ID'] ) : '';
			$slug          = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
			$alreadyExists = false;
			$resutls       = array();
			if ( ! empty( $slug ) ) {
				$resutls = \BetterLinks\Helper::get_link_by_short_url( $slug );
				if ( count( $resutls ) > 0 ) {
					$alreadyExists = true;
					$resutls       = current( $resutls );
					if ( $resutls['ID'] == $ID ) {
						$alreadyExists = false;
					}
				}
			}
			wp_send_json_success( $alreadyExists );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function cat_slug_unique_checker() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$ID            = isset( $_POST['ID'] ) ? sanitize_text_field( $_POST['ID'] ) : '';
		$slug          = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
		$alreadyExists = false;
		$resutls       = array();
		if ( ! empty( $slug ) ) {
			$resutls = \BetterLinks\Helper::get_term_by_slug( $slug );
			if ( count( $resutls ) > 0 ) {
				$alreadyExists = true;
				$resutls       = current( $resutls );
				if ( $resutls['ID'] == $ID ) {
					$alreadyExists = false;
				}
			}
		}
		wp_send_json_success( $alreadyExists );
	}
	public function get_simple301redirects_data() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$links = get_option( '301_redirects' );
		wp_send_json_success( $links );
	}
	public function run_simple301redirects_migration() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		try {
			$simple_301_redirects = get_option( '301_redirects' );
			$migrator             = new \BetterLinks\Tools\Migration\S301ROneClick();
			$resutls              = $migrator->run_importer( array_reverse( $simple_301_redirects ) );
			do_action( 'betterlinks/admin/after_import_data' );
			update_option( 'betterlinks_notice_s301r_migrate', true );
			wp_send_json_success( $resutls );
		} catch ( \Throwable $th ) {
			wp_send_json_error( $th->getMessage() );
		}
	}
	public function migration_simple301redirects_notice_hide() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		if ( $type == 'deactive' ) {
			update_option( 'betterlinks_hide_notice_s301r_deactive', true );
		} elseif ( $type == 'migrate' ) {
			update_option( 'betterlinks_notice_s301r_migrate', true );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function deactive_simple301redirects() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$deactivate = deactivate_plugins( 'simple-301-redirects/wp-simple-301-redirects.php' );
		wp_send_json_success( $deactivate );
	}
	public function search_clicks_data() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$title   = isset( $_GET['title'] ) ? sanitize_text_field( $_GET['title'] ) : '';
		$results = \BetterLinks\Helper::search_clicks_data( $title );


		wp_send_json_success(
			array(
				'clicks'           => $results,
			)
		);
	}
	public function links_reorder() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$links = ( isset( $_POST['links'] ) ? explode( ',', sanitize_text_field( $_POST['links'] ) ) : array() );
		if ( count( $links ) > 0 ) {
			foreach ( $links as $key => $value ) {
				\BetterLinks\Helper::insert_link(
					array(
						'ID'         => $value,
						'link_order' => $key,
					),
					true
				);
			}
		}
		wp_send_json_success( array() );
	}
	public function links_move_reorder() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$source      = ( isset( $_POST['source'] ) ? explode( ',', sanitize_text_field( $_POST['source'] ) ) : array() );
		$destination = ( isset( $_POST['destination'] ) ? explode( ',', sanitize_text_field( $_POST['destination'] ) ) : array() );
		if ( count( $source ) > 0 ) {
			foreach ( $source as $key => $value ) {
				\BetterLinks\Helper::insert_link(
					array(
						'ID'         => $value,
						'link_order' => $key,
					),
					true
				);
			}
		}
		if ( count( $destination ) > 0 ) {
			foreach ( $destination as $key => $value ) {
				\BetterLinks\Helper::insert_link(
					array(
						'ID'         => $value,
						'link_order' => $key,
					),
					true
				);
			}
		}
		wp_send_json_success( array() );
	}

	public function get_thirstyaffiliates_data() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$response = \BetterLinks\Helper::get_thirstyaffiliates_links();
		wp_send_json_success( $response );
	}

	public function run_thirstyaffiliates_migration() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		try {
			$links    = \BetterLinks\Helper::get_thirstyaffiliates_links();
			$migrator = new \BetterLinks\Tools\Migration\TAOneClick();
			$resutls  = $migrator->run_importer( $links );
			do_action( 'betterlinks/admin/after_import_data' );
			update_option( 'betterlinks_notice_ta_migrate', true );
			wp_send_json_success( $resutls );
		} catch ( \Throwable $th ) {
			wp_send_json_error( $th->getMessage() );
		}
	}

	public function deactive_thirstyaffiliates() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$deactivate = deactivate_plugins( 'thirstyaffiliates/thirstyaffiliates.php' );
		wp_send_json_success( $deactivate );
	}

	public function get_links_by_short_url() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$short_url = ( isset( $_POST['short_url'] ) ? sanitize_text_field( $_POST['short_url'] ) : '' );
		$results   = \BetterLinks\Helper::get_link_by_short_url( $short_url );
		wp_send_json_success( is_array( $results ) ? current( $results ) : false );
	}
	public function get_links_by_permalink() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$short_url = ( isset( $_POST['target_url'] ) ? sanitize_text_field( $_POST['target_url'] ) : '' );
		$results   = \BetterLinks\Helper::get_link_by_permalink( $short_url );
		wp_send_json_success( is_array( $results ) ? current( $results ) : false );
	}

	public function get_category_by_link_id() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$ID      = ( isset( $_POST['ID'] ) ? sanitize_text_field( $_POST['ID'] ) : '' );
		$results = \BetterLinks\Helper::get_terms_by_link_ID_and_term_type( $ID, 'category' );
		return wp_send_json( $results );
	}

	public function get_auto_link_create_settings() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$data = get_option( 'betterlinkspro_auto_link_create', array() );
		if ( is_string( $data ) ) {
			$data = json_decode( $data, true );
		}
		wp_send_json_success( $data );
	}

	public function get_all_links() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/links_get_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$cache_data = get_transient( BETTERLINKS_CACHE_LINKS_NAME );
		if ( empty( $cache_data ) || ! json_decode( $cache_data, true ) ) {
			$results = \BetterLinks\Helper::get_prepare_all_links();
			set_transient( BETTERLINKS_CACHE_LINKS_NAME, json_encode( $results ) );
			wp_send_json_success(
				array(
					'success' => true,
					'cache'   => false,
					'data'    => $results,
				),
				200
			);
		}
		wp_send_json_success(
			array(
				'success' => true,
				'cache'   => true,
				'data'    => json_decode( $cache_data ),
			),
			200
		);
	}
	public function create_new_link() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/links_create_item_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args    = $this->sanitize_links_data( $_POST );
		$results = $this->insert_link( $args );
		if ( $results ) {
			wp_send_json_success(
				$results,
				200
			);
		}
		wp_send_json_error(
			$results,
			200
		);
	}
	public function update_existing_link() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/links_update_item_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args    = $this->sanitize_links_data( $_POST );
		$results = $this->update_link( $args );
		if ( $results ) {
			wp_send_json_success(
				$results,
				200
			);
		}
		wp_send_json_error(
			$args,
			200
		);
	}
	public function handle_links_favorite_option() {
		if ( isset( $_POST['favForAll'] ) && isset( $_POST['ID'] ) ) {
			check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
			if ( ! apply_filters( 'betterlinks/api/links_update_favorite_permissions_check', current_user_can( 'manage_options' ) ) ) {
				wp_die( "You don't have permission to do this." );
			}
			delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
			$params   = array(
				'ID'   => absint( $_POST['ID'] ),
				'data' => array(
					'favForAll' => $_POST['favForAll'] === 'true' ? true : false,
				),
			);
			$result   = $this->update_link_favorite( $params );
			$response = array(
				'ID'        => $params['ID'],
				'favForAll' => $params['data']['favForAll'],
			);
			if ( $result ) {
				wp_send_json_success(
					$response,
					200
				);
			}
			wp_send_json_error(
				$response,
				200
			);
		}
	}
	public function delete_existing_link() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args = array(
			'ID'        => ( isset($_REQUEST['ID']) ? sanitize_text_field( $_REQUEST['ID'] ) : '' ),
			'short_url' => ( isset($_REQUEST['short_url']) ? sanitize_text_field( $_REQUEST['short_url'] ) : '' ),
			'term_id'   => ( isset($_REQUEST['term_id']) ? sanitize_text_field( $_REQUEST['term_id'] ) : '' ),
		);
		$this->delete_link( $args );

		wp_send_json_success(
			$args,
			200
		);
	}
	public function get_settings() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/settings_get_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$results = get_option( BETTERLINKS_LINKS_OPTION_NAME, '[]' );
		if ( $results ) {
			wp_send_json_success(
				$results,
				200
			);
		}
		wp_send_json_success(
			array(
				'success' => false,
				'data'    => '{}',
			),
			200
		);
	}
	public function update_settings() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/settings_update_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$response                         = \BetterLinks\Helper::fresh_ajax_request_data( $_POST );
		$response                         = \BetterLinks\Helper::sanitize_text_or_array_field( $response );
		$response['uncloaked_categories'] = isset( $response['uncloaked_categories'] ) && is_string( $response['uncloaked_categories'] ) ? json_decode( $response['uncloaked_categories'] ) : array();

		$enable_password_protection = !empty($response['enable_password_protection']) ? $response['enable_password_protection'] : false;
		$enable_customize_meta_tag = !empty( $response['enable_customize_meta_tags'] ) ? $response['enable_customize_meta_tags'] : false;

        if( class_exists('\BetterLinksPro\Helper')) {
            if( $enable_password_protection ){
                (new \BetterLinksPro\Helper)->add_password_protect_page();
            }else {
                (new \BetterLinksPro\Helper)->delete_password_protect_page();
            }

			if( $enable_customize_meta_tag ){
				(new \BetterLinksPro\Helper)->add_customized_meta_tag_page();
			}else {
				(new \BetterLinksPro\Helper)->delete_custom_page('customized-meta-tags');
			}
        }

		$response                         = json_encode( $response );
		if ( $response ) {
			update_option( BETTERLINKS_LINKS_OPTION_NAME, $response );
		}
		// regenerate links for wildcards option update
		\BetterLinks\Helper::write_links_inside_json(); // it's better to write the links instantly here than scheduling/corning it
		wp_send_json_success(
			$response,
			200
		);
	}
	public function get_terms() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/settings_get_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$args = array();
		if ( isset( $_REQUEST['ID'] ) ) {
			$args['ID'] = sanitize_text_field( $_REQUEST['ID'] );
		}
		if ( isset( $_REQUEST['term_type'] ) ) {
			$args['term_type'] = sanitize_text_field( $_REQUEST['term_type'] );
		}

		$results = $this->get_all_terms_data( $args );
		if ( $results ) {
			wp_send_json_success(
				$results,
				200
			);
		}
		wp_send_json_error(
			array(),
			200
		);
	}
	public function create_new_term() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args    = array(
			'ID'        => ( isset( $_REQUEST['ID'] ) ? absint( sanitize_text_field( $_REQUEST['ID'] ) ) : 0 ),
			'term_name' => ( isset( $_REQUEST['term_name'] ) ? sanitize_text_field( $_REQUEST['term_name'] ) : '' ),
			'term_slug' => ( isset( $_REQUEST['term_slug'] ) ? sanitize_text_field( $_REQUEST['term_slug'] ) : '' ),
			'term_type' => ( isset( $_REQUEST['term_type'] ) ? sanitize_text_field( $_REQUEST['term_type'] ) : '' ),
		);
		$results = $this->create_term( $args );
		wp_send_json_success(
			$results,
			200
		);
	}
	public function update_existing_term() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args = array(
			'cat_id'   => ( isset( $_REQUEST['ID'] ) ? absint( sanitize_text_field( $_REQUEST['ID'] ) ) : 0 ),
			'cat_name' => ( isset( $_REQUEST['term_name'] ) ? sanitize_text_field( $_REQUEST['term_name'] ) : '' ),
			'cat_slug' => ( isset( $_REQUEST['term_slug'] ) ? sanitize_text_field( $_REQUEST['term_slug'] ) : '' ),
		);
		$this->update_term( $args );
		wp_send_json_success(
			array(
				'ID'        => $args['cat_id'],
				'term_name' => $args['cat_name'],
				'term_slug' => $args['cat_slug'],
			),
			200
		);
	}
	public function delete_existing_term() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		$args = array(
			'cat_id' => ( isset( $_REQUEST['cat_id'] ) ? absint( sanitize_text_field( $_REQUEST['cat_id'] ) ) : 0 ),
		);
		$this->delete_term( $args );
		wp_send_json_success(
			$args,
			200
		);
	}
	public function fetch_analytics() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/analytics_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$from = isset( $_REQUEST['from'] ) ? sanitize_text_field( $_REQUEST['from'] ) : date( 'Y-m-d', strtotime( ' - 30 days' ) );
		$to   = isset( $_REQUEST['to'] ) ? sanitize_text_field( $_REQUEST['to'] ) : date( 'Y-m-d' );
		$ID   = ( isset( $_REQUEST['ID'] ) ? sanitize_text_field( $_REQUEST['ID'] ) : '' );
		if ( ! empty( $ID ) && class_exists( 'BetterLinksPro' ) ) {
			$results = \BetterLinksPro\Helper::get_individual_link_analytics(
				array(
					'id'   => $ID,
					'from' => $from,
					'to'   => $to,
				)
			);
		} else {
			$results = $this->get_clicks_data( $from, $to );
		}
		wp_send_json_success(
			$results,
			200
		);
	}
	public function reset_analytics() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! apply_filters( 'betterlinks/api/analytics_items_permissions_check', current_user_can( 'manage_options' ) ) ) {
			wp_die( "You don't have permission to do this." );
		}
		global $wpdb;
		$prefix          = $wpdb->prefix;
		$days_older_than = isset( $_REQUEST['days_older_than'] ) ? sanitize_text_field( $_REQUEST['days_older_than'] ) : false;
		$from            = isset( $request['from'] ) ? sanitize_text_field( $request['from'] ) : date( 'Y-m-d', strtotime( ' - 30 days' ) );
		$to              = isset( $request['to'] ) ? sanitize_text_field( $request['to'] ) : date( 'Y-m-d' );
		$query           = '';
		if ( $days_older_than ) {
			$range_days_in_seconds           = $days_older_than * 24 * 60 * 60;
			$gmt_timestamp_of_the_range_time = time() - $range_days_in_seconds;
			$query                           = "DELETE FROM {$prefix}betterlinks_clicks WHERE UNIX_TIMESTAMP(created_at_gmt) < %d";
			$query                           = $wpdb->prepare( $query, $gmt_timestamp_of_the_range_time );
		} else {
			$query = "DELETE FROM {$prefix}betterlinks_clicks";
		}
		$count = $wpdb->query( $query );
		if ( $count === false ) {
			wp_send_json_error( $count );
		}
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
		\BetterLinks\Helper::update_links_analytics();
		$new_clicks_data = \BetterLinks\Helper::get_clicks_by_date( $from, $to );
		$new_links_data  = \BetterLinks\Helper::get_prepare_all_links();
		set_transient( BETTERLINKS_CACHE_LINKS_NAME, json_encode( $new_links_data ) );
		wp_send_json_success(
			array(
				'count'           => $count,
				'new_clicks_data' => $new_clicks_data,
				'new_links_data'  => $new_links_data,
			),
			200
		);
	}
	public function get_post_types() {
		$post_types = get_post_types();
		wp_send_json_success(
			$post_types,
			200
		);
	}
	public function get_post_tags() {
		$tags = get_tags( array( 'get' => 'all' ) );
		$tags = wp_list_pluck( $tags, 'name', 'slug' );
		wp_send_json_success(
			$tags,
			200
		);
	}
	public function get_post_categories() {
		$categories = get_categories(
			array(
				'orderby' => 'name',
			)
		);
		$categories = wp_list_pluck( $categories, 'name', 'slug' );
		wp_send_json_success(
			$categories,
			200
		);
	}

	public function set_affiliate_link_disclosure_post() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}

		$ID    = ( isset( $_POST['ID'] ) ? intval( $_POST['ID'] ) : '' );
		$value = ( isset( $_POST['value'] ) ? sanitize_text_field( $_POST['value'] ) : '' );

		update_post_meta( $ID, 'betterlinks_enable_affiliate_link_disclosure', $value );

		wp_send_json(
			array(
				'ID'    => $ID,
				'value' => $value,
			)
		);
	}

	public function get_affiliate_link_disclosure_post() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}
		$ID        = ( isset( $_POST['ID'] ) ? intval( sanitize_text_field( $_POST['ID'] ) ) : '' );
		$post_meta = get_post_meta( $ID, 'betterlinks_enable_affiliate_link_disclosure' );
		wp_send_json( $post_meta );
	}
	public function set_affiliate_link_disclosure_text() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}

		$ID    = isset( $_POST['ID'] ) ? sanitize_text_field( $_POST['ID'] ) : '';
		$value = isset( $_POST['value'] ) ? $_POST['value'] : '';

		$meta_key = 'betterlinks_enable_affiliate_link_disclosure_text';

		if ( ! empty( get_post_meta( $ID, $meta_key ) ) ) {
			update_post_meta( $ID, $meta_key, $value );
		} else {
			add_post_meta( $ID, $meta_key, $value );
		}

		wp_send_json( $value );
	}

	public function get_affiliate_link_disclosure_text() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( "You don't have permission to do this." );
		}

		$ID       = isset( $_POST['ID'] ) ? sanitize_text_field( $_POST['ID'] ) : '';
		$meta_key = 'betterlinks_enable_affiliate_link_disclosure_text';

		$data           = array();
		$affiliate_text = get_post_meta( $ID, $meta_key );
		if ( count( $affiliate_text ) > 0 ) {
			$data = json_decode( html_entity_decode( $affiliate_text[0] ), true );
		}

		$settings                  = json_decode( get_option( BETTERLINKS_LINKS_OPTION_NAME ), true );
		$affiliate_disclosure_text = ! empty( $settings['affiliate_disclosure_text'] ) ? $settings['affiliate_disclosure_text'] : '';
		$affiliate_link_position   = ! empty( $settings['affiliate_link_position'] ) ? sanitize_text_field( $settings['affiliate_link_position'] ) : '';

		wp_send_json(
			array(
				'affiliate_disclosure_text' => empty( $data['affiliate_disclosure_text'] ) ? $affiliate_disclosure_text : str_replace( ' rn ', '', $data['affiliate_disclosure_text'] ),
				'affiliate_link_position'   => empty( $data['affiliate_link_position'] ) ? $affiliate_link_position : $data['affiliate_link_position'],
			)
		);
	}

	public function get_auto_create_links_settings() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinkspro/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) ) {
			$data = get_option( BETTERLINKS_PRO_AUTO_LINK_CREATE_OPTION_NAME, array() );
			if ( is_string( $data ) ) {
				$data = json_decode( $data, true );
			}
			wp_send_json_success( $data );
		}
		wp_die( "You don't have permission to do this." );
	}
	public function get_external_analytics() {
		check_ajax_referer( 'betterlinks_admin_nonce', 'security' );
		if ( apply_filters( 'betterlinkspro/admin/current_user_can_edit_settings', current_user_can( 'manage_options' ) ) ) {
			$data = get_option( BETTERLINKS_PRO_EXTERNAL_ANALYTICS_OPTION_NAME, array() );
			if ( is_string( $data ) ) {
				$data = json_decode( $data, true );
			}
			wp_send_json_success( $data );
		}
		wp_die( "You don't have permission to do this." );
	}
}
