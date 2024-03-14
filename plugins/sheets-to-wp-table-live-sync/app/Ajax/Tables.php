<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS\Ajax;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for handling table operations.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Tables {

	/**
	 * Class constructor.
	 *
	 * @since 2.12.15
	 */
	public function __construct() {
		add_action( 'wp_ajax_gswpts_sheet_create', [ $this, 'sheet_creation' ] );
		add_action( 'wp_ajax_nopriv_gswpts_sheet_create', [ $this, 'sheet_creation' ] );
		add_action( 'wp_ajax_gswpts_manage_tab_toggle', [ $this, 'tab_name_toggle' ] );
		add_action( 'wp_ajax_gswpts_ud_table', [ $this, 'update_name' ] );

		add_action( 'wp_ajax_swptls_create_table', [ $this, 'create' ] );
		add_action( 'wp_ajax_swptls_edit_table', [ $this, 'edit' ] );
		add_action( 'wp_ajax_swptls_delete_table', [ $this, 'delete' ] );
		add_action( 'wp_ajax_swptls_get_tables', [ $this, 'get_all' ] );

		add_action( 'wp_ajax_swptls_save_table', [ $this, 'save' ] );

		add_action( 'wp_ajax_swptls_update_sorting', [ $this, 'update_sorting' ] );
		add_action( 'wp_ajax_swptls_update_sorting_fe', [ $this, 'update_sorting_fe' ] );

		add_action( 'wp_ajax_gswpts_sheet_fetch', [ $this, 'get' ] );
		add_action( 'wp_ajax_nopriv_gswpts_sheet_fetch', [ $this, 'get' ] );
		add_action( 'wp_ajax_swptls_get_table_preview', [ $this, 'get_table_preview' ] );
	}

	/**
	 * Save table by id.
	 */
	public function save() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : false;
			$settings = ! empty( $_POST['settings'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ), true ) : false;
			$settings['table_settings'] = wp_json_encode( $settings['table_settings'] );

		if ( ! $id || ! $settings ) {
			wp_send_json_error([
				'message' => __( 'Invalid data to save.', 'sheetstowptable' ),
			]);
		}

		$response = swptls()->database->table->update( $id, $settings );

		wp_send_json_success([
			'message' => __( 'Table updated successfully.', 'sheetstowptable' ),
			'table_name'     => esc_attr( $settings['table_name'] ),
			'source_url'     => esc_url( $settings['source_url'] ),
			'table_settings' => json_decode( $settings['table_settings'], true ),
		]);
	}

	/**
	 * Sorting disabled BE.
	 */
	public function update_sorting() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : false;
		$allow_sorting = isset( $_POST['allow_sorting'] ) ? filter_var( wp_unslash( $_POST['allow_sorting'] ), FILTER_VALIDATE_BOOLEAN ) : false;

		if ( false !== $id && true !== $allow_sorting ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'gswpts_tables';

			// Fetch the existing table_settings value for the specified ID.
			$current_settings = $wpdb->get_var( $wpdb->prepare(
				"SELECT table_settings FROM $table_name WHERE id = %d", // phpcs:ignore
				$id
			) );

			if ( null !== $current_settings ) {
				// Decode the JSON string into an associative array.
				$current_settings_array = json_decode( $current_settings, true );

				// Update the 'allow_sorting' property.
				$current_settings_array['allow_sorting'] = $allow_sorting;

				// Encode the array back to JSON.
				$new_settings = json_encode( $current_settings_array );

				// Update the 'table_settings' column for the specified ID.
				$wpdb->update(
					$table_name,
					array( 'table_settings' => $new_settings ),
					array( 'id' => $id ),
					array( '%s' ),
					array( '%d' )
				);

				// Check if the update was successful.
				if ( $wpdb->rows_affected > 0 ) {
					wp_send_json_success([
						'message' => __( 'Sorting updated successfully', 'sheetstowptable' ),
					]);
				} else {
					wp_send_json_error([
						'message' => __( 'Failed to update sorting', 'sheetstowptable' ),
					]);
				}
			} else {
				wp_send_json_error([
					'message' => __( 'Record not found', 'sheetstowptable' ),
				]);
			}
		} else {
			wp_send_json_error([
				'message' => __( 'Invalid ID or sorting value', 'sheetstowptable' ),
			]);
		}
	}
	/**
	 * Sorting disabled FE.
	 */
	public function update_sorting_fe() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'gswpts_sheet_nonce_action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;
		$allow_sorting = isset( $_POST['allow_sorting'] ) ? filter_var( wp_unslash( $_POST['allow_sorting'] ), FILTER_VALIDATE_BOOLEAN ) : false;

		if ( false !== $id && true !== $allow_sorting ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'gswpts_tables';

			// Fetch the existing table_settings value for the specified ID.
			$current_settings = $wpdb->get_var( $wpdb->prepare(
				"SELECT table_settings FROM $table_name WHERE id = %d", // phpcs:ignore
				$id
			) );

			if ( null !== $current_settings ) {
				// Decode the JSON string into an associative array.
				$current_settings_array = json_decode( $current_settings, true );

				// Update the 'allow_sorting' property.
				$current_settings_array['allow_sorting'] = $allow_sorting;

				// Encode the array back to JSON.
				$new_settings = json_encode( $current_settings_array );

				// Update the 'table_settings' column for the specified ID.
				$wpdb->update(
					$table_name,
					array( 'table_settings' => $new_settings ),
					array( 'id' => $id ),
					array( '%s' ),
					array( '%d' )
				);

				// Check if the update was successful.
				if ( $wpdb->rows_affected > 0 ) {
					wp_send_json_success([
						'message' => __( 'Sorting updated successfully', 'sheetstowptable' ),
					]);
				} else {
					wp_send_json_error([
						'message' => __( 'Failed to update sorting', 'sheetstowptable' ),
					]);
				}
			} else {
				wp_send_json_error([
					'message' => __( 'Record not found', 'sheetstowptable' ),
				]);
			}
		} else {
			wp_send_json_error([
				'message' => __( 'Invalid ID or sorting value', 'sheetstowptable' ),
			]);
		}
	}

	/**
	 * Delete table by id.
	 */
	public function delete() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : false;
		$tables = swptls()->database->table->get_all();

		if ( $id ) {
			$response = swptls()->database->table->delete( $id );

			if ( $response ) {
				wp_send_json_success([// phpcs:ignore
					'message'      => sprintf( __( '%s table deleted.', 'sheetstowptable' ), $response ),// phpcs:ignore
					'tables'       => $tables,
					'tables_count' => count( swptls()->database->table->get_all() ),
				]);
			}

			wp_send_json_error([
				'message'      => sprintf( __( 'Failed to delete table with id %d' ), $id ),// phpcs:ignore
				'tables'       => $tables,
				'tables_count' => count( swptls()->database->table->get_all() ),
			]);
		}

		wp_send_json_error([
			'message'      => sprintf( __( 'Invalid table to perform delete.' ), $id ),
			'tables'       => $tables,
			'tables_count' => count( swptls()->database->table->get_all() ),
		]);
	}

	/**
	 * Get all tables on ajax request.
	 *
	 * @since 3.0.0
	 */
	public function get_all() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$tables = swptls()->database->table->get_all();

		wp_send_json_success([
			'tables'       => $tables,
			'tables_count' => count( $tables ),
		]);
	}

	/**
	 * Create table on ajax request.
	 *
	 * @since 3.0.0
	 */
	public function create() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$sheet_url = isset( $_POST['sheet_url'] ) ? sanitize_text_field( wp_unslash($_POST['sheet_url'] )) : '';

		if ( empty( $sheet_url ) ) {
			wp_send_json_error([
				'message' => __( 'Empty or invalid google sheet url.', 'sheetstowptable' ),
			]);
		}

		$settings = ! empty( $_POST['settings'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] )), true ) : [];
		$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash($_POST['name'] )) : __( 'Untitled', 'sheetstowptable' );

		if ( ! is_array( $settings ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid settings to save.', 'sheetstowptable' ),
			]);
		}

		$gid = swptls()->helpers->get_grid_id( $sheet_url );

		if ( false === $gid && swptls()->helpers->is_pro_active() ) {
			wp_send_json_error([
				'message'       => __( 'Copy the Google sheet URL from browser URL bar that includes <i>gid</i> parameter', 'sheetstowptable' ),
				'response_type' => esc_html( 'invalid_request' ),
			]);
		}

		$sheet_id = swptls()->helpers->get_sheet_id( $sheet_url );
		$response = swptls()->helpers->get_csv_data( $sheet_url, $sheet_id, $gid );

		if ( is_string( $response ) && ( strpos( $response, 'request-storage-access' ) !== false || strpos( $response, 'show-error' ) !== false ) ) {
			wp_send_json_error([
				'message' => __( 'The spreadsheet is restricted. Please make it public by clicking on share button at the top of the spreadsheet', 'sheetstowptable' ),
				'type'    => 'private_sheet',
			]);
		}

		$table = [
			'table_name'     => sanitize_text_field( $name ),
			'source_url'     => esc_url_raw( $sheet_url ),
			'source_type'    => 'spreadsheet',
			'table_settings' => wp_json_encode( $settings ),
		];

		$table_id = swptls()->database->table->insert( $table );

		$context = ! empty( $_POST['context'] ) ? sanitize_text_field( wp_unslash( $_POST['context'] )) : false;

		if ( 'wizard' === $context ) {
			update_option( 'swptls_ran_setup_wizard', true );
		}

		if ( 'block' === $context ) {
			$this->get_plain( $table_id );
			die();
		}

		wp_send_json_success([
			'id'      => absint( $table_id ),
			'url'     => $sheet_url,
			'message' => esc_html__( 'Table created successfully', 'sheetstowptable' ),
		]);
	}

	/**
	 * Edit table on ajax request.
	 *
	 * @since 3.0.0
	 */
	public function edit() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$table_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		if ( ! $table_id ) {
			wp_send_json_error([
				'message' => __( 'Invalid table to edit.', 'sheetstowptable' ),
			]);
		}

		$table = swptls()->database->table->get( $table_id );

		if ( ! $table ) {
			wp_send_json_error([
				'type'   => 'invalid_request',
				'output' => esc_html__( 'Request is invalid', 'sheetstowptable' ),
			]);
		}

		$settings   = json_decode( $table['table_settings'], true );
		$settings   = null !== $settings ? $settings : unserialize( $table['table_settings'] ); // phpcs:ignore

		wp_send_json_success([
			'table_name'     => esc_attr( $table['table_name'] ),
			'source_url'     => esc_url( $table['source_url'] ),
			'table_settings' => $settings,
		]);
	}

	/**
	 * Edit table on ajax request.
	 *
	 * @since 3.0.0
	 */
	public function get_table_preview() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$settings   = ! empty( $_POST['table_settings'] ) && ! is_array( $_POST['table_settings'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['table_settings'] )), 1 ) : [];
			$sheet_url  = ! empty( $_POST['source_url'] ) ? esc_url_raw( wp_unslash($_POST['source_url'] )) : '';
			$table_name = isset($_POST['table_name']) ? esc_attr(wp_unslash($_POST['table_name'])) : ''; // phpcs:ignore
			$table_id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		$sheet_id   = swptls()->helpers->get_sheet_id( $sheet_url );
		$sheet_gid  = swptls()->helpers->get_grid_id( $sheet_url );
		$styles     = [];

		/**
		 * Check if create table is private.
		 */
		 $sheet_id = swptls()->helpers->get_sheet_id( $sheet_url );
		 $gid = swptls()->helpers->get_grid_id( $sheet_url );
		 $response = swptls()->helpers->get_csv_data( $sheet_url, $sheet_id, $gid );

		 $isPrivate = is_string($response) && ( strpos($response, 'request-storage-access') !== false || strpos($response, 'show-error') !== false ) ? true : false;

		if ( swptls()->helpers->is_pro_active() ) {
			$table_data = swptlspro()->helpers->load_table_data( $sheet_url, $table_id );
			$response   = swptlspro()->helpers->generate_html( $table_name, $settings, $table_data );
		} else {
			$table_data = swptls()->helpers->get_csv_data( $sheet_url, $sheet_id, $sheet_gid );
			$response = swptls()->helpers->generate_html( $table_data, $settings, $table_name, false );
		}

		if ( empty( $response ) ) {
			wp_send_json_error([
				'type'   => 'invalid_request',
				'output' => esc_html__( 'Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable' ),
			]);
		}

		wp_send_json_success( [
			'html'     => $response,
			'settings' => $settings,
			'is_private' => $isPrivate,
		] );
	}

	/**
	 * Responsible for fetching tables.
	 *
	 * @since 2.12.15
	 */
	public function table_fetch() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'tables_related_nonce_action' ) ) {
			wp_send_json_error([
				'message' => __( 'Action is invalid', 'sheetstowptable' ),
			]);
		}

		$page_slug = isset($_POST['page_slug']) ? sanitize_text_field(wp_unslash($_POST['page_slug'])) : '';

		if ( empty( $page_slug ) ) {
			wp_send_json_error([
				'message' => __( 'Action is invalid', 'sheetstowptable' ),
			]);
		}

		$fetched_tables = swptls()->database->get_all();
		$tables_html    = $this->table_html( $fetched_tables );

		wp_send_json_success([
			'message' => __( 'Tables fetched successfully.', 'sheetstowptable' ),
			'output'  => $tables_html,
			'no_data' => ! $fetched_tables,
		]);
	}

	/**
	 * Populates table html.
	 *
	 * @param array $fetched_tables Fetched tables from db.
	 * @since 2.12.15
	 */
	public function table_html( array $fetched_tables ) {
		$table = '<table id="manage_tables" class="ui celled table">
			<thead>
				<tr>
					<th class="text-center">
						<input data-show="false" type="checkbox" name="manage_tables_main_checkbox"  id="manage_tables_checkbox">
					</th>
					<th class="text-center">' . esc_html__( 'Table ID', 'sheetstowptable' ) . '</th>
					<th class="text-center">' . esc_html__( 'Type', 'sheetstowptable' ) . '</th>
					<th class="text-center">' . esc_html__( 'Shortcode', 'sheetstowptable' ) . '</th>
					<th class="text-center">' . esc_html__( 'Table Name', 'sheetstowptable' ) . '</th>
					<th class="text-center">' . esc_html__( 'Delete', 'sheetstowptable' ) . '</th>
				</tr>
			</thead>
		<tbody>';

		foreach ( $fetched_tables as $table_data ) {
			$table .= '<tr>';
				$table .= '<td class="text-center">';
					$table .= '<input type="checkbox" value="' . esc_attr( $table_data->id ) . '" name="manage_tables_checkbox" class="manage_tables_checkbox">';
				$table .= '</td>';
				$table .= '<td class="text-center">' . esc_attr( $table_data->id ) . '</td>';
				$table .= '<td class="text-center">';
					/* translators: %s: The table type. */
					$table .= swptls()->helpers->get_table_type( $table_data->source_type );
				$table .= '</td>';
				$table .= '<td class="text-center" style="display: flex; justify-content: center; align-items: center; height: 35px;">';
						$table .= '<input type="hidden" class="table_copy_sortcode" value="[gswpts_table id=' . esc_attr( $table_data->id ) . ']">';
						$table .= '<span class="gswpts_sortcode_copy" style="display: flex; align-items: center; white-space: nowrap; margin-right: 12px">[gswpts_table id=' . esc_attr( $table_data->id ) . ']</span>';
						$table .= '<i class="fas fa-copy gswpts_sortcode_copy" style="font-size: 20px;color: #b7b8ba; cursor: copy"></i>';
				$table .= '</td>';
				$table .= '<td class="text-center">';
				$table .= '<div style="line-height: 38px;">';
					$table .= '<div class="ui input table_name_hidden">';
						$table .= '<input type="text" class="table_name_hidden_input" value="' . esc_attr( $table_data->table_name ) . '" />';
					$table .= '</div>';

					$table .= '<a style="margin-right: 5px; padding: 5px 15px;white-space: nowrap;"
					class="table_name" href="' . esc_url( admin_url( 'admin.php?page=gswpts-dashboard&subpage=create-table&id=' . esc_attr( $table_data->id ) . '' ) ) . '">';
						/* translators: %s: The table type. */
						$table .= esc_html( $table_data->table_name );
					$table .= '</a>';
					$table .= '<button type="button" value="edit" class="copyToken ui right icon button gswpts_edit_table ml-1" id="' . esc_attr( $table_data->id ) . '" style="width: 50px;height: 38px;">';
						$table .= '<img src="' . SWPTLS_BASE_URL . 'assets/public/icons/rename.svg" width="24px" height="15px" alt="rename-icon"/>';
					$table .= '</button>';

					$table .= '</div>';
				$table .= '</td>';
				$table .= '<td class="text-center">';
					$table .= '<button data-id="' . esc_attr( $table_data->id ) . '" id="table-' . esc_attr( $table_data->id ) . '" class="negative ui button gswpts_table_delete_btn">';
						$table .= esc_html__( 'Delete', 'sheetstowptable' );
						$table .= '<i class="fas fa-trash"></i>';
				$table .= '</button>';
				$table .= '</td>';
			$table .= '</tr>';
		}
			$table .= '</tbody>';
		$table .= '</table>';

		return $table;
	}

	/**
	 * Handles tab name toggle.
	 *
	 * @return void
	 */
	public function tab_name_toggle() {
		$nonce = isset($_POST['nonce']) ? sanitize_key(wp_unslash($_POST['nonce'])) : '';

		if ( ! wp_verify_nonce($nonce, 'swptls_tabs_nonce') || ! isset( $_POST['show_name']) ) {
			wp_send_json_error([
				'response_type' => 'invalid_action',
				'output' => __('Action is invalid', 'sheetstowptable'),
			]);
		}
		$id = isset($_POST['tabID']) ? sanitize_text_field(wp_unslash($_POST['tabID'])) : '';
		$name = isset($_POST['show_name']) ? rest_sanitize_boolean(wp_unslash($_POST['show_name'])) : ''; // phpcs:ignore
		$response = swptls()->database->update_tab_name_toggle( $id, $name );

		if ( $response ) {
			wp_send_json_success([
				'response_type' => 'success',
				'output'        => __( 'Tab updated successfully', 'sheetstowptable' ),
			]);
		} else {
			wp_send_json_error([
				'response_type' => 'error',
				'output'        => __( 'Tab could not be updated. Try again', 'sheetstowptable' ),
			]);
		}
	}

	/**
	 * Handle sheet fetching.
	 *
	 * @since 2.12.15
	 */
	public function get() {
		// phpcs:ignore
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'gswpts_sheet_nonce_action' ) ) {
			wp_send_json_error([
				'message' => __( 'Action is invalid', 'sheetstowptable' ),
			]);
		}
		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		if ( ! $id ) {
			wp_send_json_error([
				'type'    => 'invalid_request',
				'message' => __( 'Request is invalid', 'sheetstowptable' ),
			]);
		}

		$table = swptls()->database->table->get( $id );

		if ( ! $table ) {
			wp_send_json_error([
				'type'    => 'no_table_found',
				'message' => esc_html__( 'No table found.', 'sheetstowptable' ),
			]);
		}

		$from_block = isset( $_POST['fromGutenBlock'] ) ? wp_validate_boolean( wp_unslash( $_POST['fromGutenBlock'] ) ) : false; // phpcs:ignore
		$url        = esc_url( $table['source_url'] );
		$name       = esc_attr( $table['table_name'] );
		$sheet_id   = swptls()->helpers->get_sheet_id( $table['source_url'] );
		$sheet_gid  = swptls()->helpers->get_grid_id( $table['source_url'] );
		$settings   = json_decode( $table['table_settings'], true );
		$settings   = null !== $settings ? $settings : unserialize( $table['table_settings'] ); // phpcs:ignore
		$styles     = [];

		if ( swptls()->helpers->is_pro_active() ) {
			$table_data = swptlspro()->helpers->load_table_data( $url, $id );
			$response   = swptlspro()->helpers->generate_html( $name, $settings, $table_data );
		} else {
			$response = swptls()->helpers->get_csv_data( $table['source_url'], $sheet_id, $sheet_gid );
			$response = swptls()->helpers->generate_html( $response, $settings, $name, $from_block );
		}

		if ( empty( $response ) ) {
			wp_send_json_error([
				'type'   => 'invalid_request',
				'output' => esc_html__( 'Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable' ),
			]);
		}

		wp_send_json_success([
			'output'         => $response,
			'table_settings' => $settings,
			'name'           => $name,
			'source_url'     => $url,
			'type'           => 'success',
		]);
	}

	/**
	 * Get sheet fetching in plain values.
	 *
	 * @param int $id The table id.
	 *
	 * @since 2.12.15
	 */
	public function get_plain( int $id ) {
		if ( ! $id ) {
			wp_send_json_error([
				'type'    => 'invalid_request',
				'message' => __( 'Request is invalid', 'sheetstowptable' ),
			]);
		}

		$table = swptls()->database->table->get( $id );

		if ( ! $table ) {
			wp_send_json_error([
				'type'    => 'no_table_found',
				'message' => esc_html__( 'No table found.', 'sheetstowptable' ),
			]);
		}

		$url        = esc_url( $table['source_url'] );
		$name       = esc_attr( $table['table_name'] );
		$sheet_id   = swptls()->helpers->get_sheet_id( $table['source_url'] );
		$sheet_gid  = swptls()->helpers->get_grid_id( $table['source_url'] );
		$settings   = json_decode( $table['table_settings'], true );
		$settings   = null !== $settings ? $settings : unserialize( $table['table_settings'] ); // phpcs:ignore
		$styles     = [];

		if ( swptls()->helpers->is_pro_active() ) {
			$table_data = swptlspro()->helpers->load_table_data( $url, $id );
			$response   = swptlspro()->helpers->generate_html( $name, $settings, $table_data );
		} else {
			$response = swptls()->helpers->get_csv_data( $table['source_url'], $sheet_id, $sheet_gid );
			$response = swptls()->helpers->generate_html( $response, $settings, $name );
		}

		if ( empty( $response ) ) {
			wp_send_json_error([
				'type'   => 'invalid_request',
				'output' => esc_html__( 'Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable' ),
			]);
		}

		wp_send_json_success([
			'id'             => $id,
			'output'         => $response,
			'table_settings' => $settings,
			'name'           => $name,
			'source_url'     => $url,
			'type'           => 'success',
		]);
	}

	/**
	 * Handles sheet creation.
	 *
	 * @return mixed
	 */
	public function sheet_creation() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'swptls_sheet_creation_nonce' ) ) { // phpcs:ignore
			wp_send_json_error([
				'message' => __( 'Action is invalid', 'sheetstowptable' ),
			]);
		}

		if ( isset( $_POST['gutenberg_req'] ) && sanitize_text_field( wp_unslash($_POST['gutenberg_req'] )) ) {
			$this->table_creation_for_gutenberg();
		} else {

			if ( isset( $_POST['form_data'] ) ) {
				$unslashed_form_data = wp_unslash( $_POST['form_data'] ); // phpcs:ignore
				parse_str( $unslashed_form_data, $parsed_data );
				$parsed_data = array_map( 'sanitize_text_field', $parsed_data );

				$sheet_url   = sanitize_text_field( $parsed_data['file_input'] );
				$raw_settings = ! empty( $_POST['table_settings'] ) ? sanitize_text_field( wp_unslash( $_POST['table_settings'] ) ) : '';
				$settings = json_decode( $raw_settings, true );
				$name        = isset( $_POST['table_name'] ) ? sanitize_text_field( wp_unslash($_POST['table_name'] )) : __( 'Untitled', 'sheetstowptable' );

				if ( ! is_array( $settings ) ) {
					wp_send_json_error([
						'message' => __( 'Invalid settings to save.', 'sheetstowptable' ),
					]);
				}

				if ( empty( $sheet_url ) ) {
					wp_send_json_error([
						'message' => __( 'Form field is empty. Please fill out the field', 'sheetstowptable' ),
					]);
				}

				if ( ! empty( $_POST['type'] ) && 'fetch' === sanitize_text_field( wp_unslash($_POST['type'] ) ) ) {
					$this->generate_sheet_html( $sheet_url, $settings, $name, false );
				}

				if ( 'save' === sanitize_text_field( wp_unslash( $_POST['type'] )) || 'saved' === sanitize_text_field( wp_unslash($_POST['type'] )) ) {
					$this->save_table( $sheet_url, $name, $settings );
				}

				if ( isset( $_POST['type'] ) && 'save_changes' === sanitize_text_field( wp_unslash($_POST['type'] )) && isset( $_POST['id'] ) ) {
					$this->update_changes( absint( $_POST['id'] ), $settings );
				}
			}
		}
	}

	/**
	 * Handles sheet html.
	 *
	 * @param string $url The sheet url.
	 *
	 * @param string $settings The sheet settings.
	 *
	 * @param string $name The sheet name.
	 */
	public static function generate_table_html_for_gt( string $url, $settings, $name ) {
		$gid = swptls()->helpers->get_grid_id( $url );

		if ( false === $gid && swptls()->helpers->is_pro_active() ) {
			wp_send_json_error([
				'message'       => __( 'Copy the Google sheet URL from browser URL bar that includes <i>gid</i> parameter', 'sheetstowptable' ),
				'response_type' => esc_html( 'invalid_request' ),
			]);
		}

		$sheet_id = swptls()->helpers->get_sheet_id( $url );
		$response = swptls()->helpers->get_csv_data( $url, $sheet_id, $gid );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error([
				'message' => __( 'The spreadsheet is restricted. Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable' ),
				'type'    => 'private_sheet',
			]);
		}

		if ( swptls()->helpers->is_pro_active() ) {
			$with_style  = wp_validate_boolean( $settings['importStyles'] ?? false );
			$table_style = $with_style ? 'default-style' : ( ! empty( $settings['table_style'] ) ? 'gswpts_' . $settings['table_style'] : '' );
			$images_data = json_decode( swptlspro()->helpers->get_images_data( $sheet_id, $gid ), true );
			$response    = swptlspro()->helpers->generate_html( $name, $settings, $response, $images_data, true );
		} else {
			$response = swptls()->helpers->generate_html( $response, $settings, $name );
		}

		wp_send_json_success( $response );
	}

	/**
	 * Handles sheet html.
	 *
	 * @param string $url The sheet url.
	 *
	 * @param array  $settings The sheet settings.
	 *
	 * @param string $name The sheet name.
	 *
	 * @param array  $from_block The sheet block.
	 */
	public static function generate_sheet_html( string $url, $settings, $name, $from_block ) {
		$gid = swptls()->helpers->get_grid_id( $url );

		if ( false === $gid && swptls()->helpers->is_pro_active() ) {
			wp_send_json_error([
				'message'       => __( 'Copy the Google sheet URL from browser URL bar that includes <i>gid</i> parameter', 'sheetstowptable' ),
				'response_type' => esc_html( 'invalid_request' ),
			]);
		}

		$sheet_id = swptls()->helpers->get_sheet_id( $url );
		$response = swptls()->helpers->get_csv_data( $url, $sheet_id, $gid );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error([
				'message' => __( 'The spreadsheet is restricted. Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable' ),
				'type'    => 'private_sheet',
			]);
		}

		if ( swptls()->helpers->is_pro_active() ) {
			$images_data = json_decode( swptlspro()->helpers->get_images_data( $sheet_id, $gid ), true );
			$response    = swptlspro()->helpers->generate_html( $response, [], 'untitled', [], $images_data, $from_block );
		} else {
			$response = swptls()->helpers->generate_html( $response, $settings, $name, $from_block );
		}

		wp_send_json_success( $response );
	}

	/**
	 * Handle saving table.
	 *
	 * @param string $url The parsed data to save.
	 * @param string $table_name  The table name.
	 * @param array  $settings    The table settings to save.
	 */
	public function save_table( string $url, string $table_name, array $settings ) {
		if ( ! swptls()->helpers->is_pro_active() && swptls()->database->has( $url ) ) {
			wp_send_json_error([
				'type'   => 'sheet_exists',
				'output' => esc_html__( 'This Google sheet already saved. Try creating a new one', 'sheetstowptable' ),
			]);
		}

		$settings = $this->migrate_settings( $settings );

		$data = [
			'table_name'     => sanitize_text_field( $table_name ),
			'source_url'     => esc_url_raw( $url ),
			'source_type'    => 'spreadsheet',
			'table_settings' => wp_json_encode( $settings ),
		];

		$response = swptls()->database->table->insert( $data );

		wp_send_json_success([
			'type'   => 'saved',
			'id'     => absint( $response ),
			'url'    => $url,
			'output' => esc_html__( 'Table saved successfully', 'sheetstowptable' ),
		]);
	}

	/**
	 * Handles update changes.
	 *
	 * @param int   $table_id The table id.
	 * @param array $settings Settings to update.
	 */
	public function update_changes( int $table_id, array $settings ) {
		$settings = $this->migrate_settings( $settings );
		$response = swptls()->database->update( $table_id, $settings );

		wp_send_json_success([
			'type'   => 'updated',
			'output' => esc_html__( 'Table changes updated successfully', 'sheetstowptable' ),
		]);
	}

	/**
	 * Retrieves table settings.
	 *
	 * @param  array $table_settings The table settings.
	 * @return array
	 */
	public static function migrate_settings( array $table_settings ) {
		$settings = [
			'table_title'           => isset( $table_settings['table_title'] ) ? wp_validate_boolean( $table_settings['table_title'] ) : false,
			'default_rows_per_page' => isset( $table_settings['defaultRowsPerPage'] ) ? absint( $table_settings['defaultRowsPerPage'] ) : 10,
			'show_info_block'       => isset( $table_settings['showInfoBlock'] ) ? wp_validate_boolean( $table_settings['showInfoBlock'] ) : false,
			'show_x_entries'        => isset( $table_settings['showXEntries'] ) ? wp_validate_boolean( $table_settings['showXEntries'] ) : false,
			'swap_filter_inputs'    => isset( $table_settings['swapFilterInputs'] ) ? wp_validate_boolean( $table_settings['swapFilterInputs'] ) : false,
			'swap_bottom_options'   => isset( $table_settings['swapBottomOptions'] ) ? wp_validate_boolean( $table_settings['swapBottomOptions'] ) : false,
			'allow_sorting'         => isset( $table_settings['allowSorting'] ) ? wp_validate_boolean( $table_settings['allowSorting'] ) : false,
			'search_bar'            => isset( $table_settings['searchBar'] ) ? wp_validate_boolean( $table_settings['searchBar'] ) : false,
		];

		return apply_filters( 'gswpts_table_settings', $settings, $table_settings );
	}

	/**
	 * Table creation for gutenberg.
	 *
	 * @since 2.12.15
	 *
	 * @phpcs:disable WordPress.Security.NonceVerification
	 */
	public function table_creation_for_gutenberg() {
		$url = isset( $_POST['file_input'] ) ? sanitize_text_field( wp_unslash($_POST['file_input'] )) : '';
		$action = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash($_POST['type'] )) : 'fetch';

		if ( ! $url && 'fetch' === $action ) {
			wp_send_json_error([
				'response_type' => 'empty_field',
				'output'        => __( 'Form field is empty. Please fill out the field', 'sheetstowptable' ),
			]);
		}

		$table_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$name     = ! empty( $_POST['table_name'] ) ? sanitize_text_field( wp_unslash($_POST['table_name'] )) : '';
		$settings = ! empty( $_POST['table_settings'] ) && is_array( $_POST['table_settings'] ) ? sanitize_text_field( wp_unslash( $_POST['table_settings'] ) ) : [];
		$action   = sanitize_text_field( wp_unslash($_POST['type'] ));
		$from_block = isset( $_POST['fromGutenBlock'] ) ? sanitize_text_field( wp_validate_boolean( wp_unslash( $_POST['fromGutenBlock'] ) ) ) : false; // phpcs:ignore

		switch ( $action ) {
			case 'fetch':
				$this->generate_table_html_for_gt( $url, $settings, $name, true );
				break;

			case 'save':
			case 'saved':
				$this->save_table( $url, $name, $settings );
				break;

			case 'save_changes':
				$this->update_changes( $table_id, $settings );
				break;
		}
	}

	/**
	 * Performs delete operations on given ids.
	 *
	 * @param int[] $ids The given int ids.
	 */
	public function delete_all( $ids ) {
		foreach ( $ids as $id ) {
			$response = $this->delete( $id );

			if ( ! $response ) {
				wp_send_json_error([
					'type'   => 'invalid_request',
					'output' => __( 'Request is invalid', 'sheetstowptable' ),
				]);

				break;
			}
		}

		wp_send_json_success([
			'output' => __( 'Selected tables deleted successfully', 'sheetstowptable' ),
		]);
	}

	/**
	 * Performs updates on tables and tabs.
	 *
	 * @param string $name    The name to update.
	 * @param int    $id      The id where to update.
	 */
	public function update_name( $name, $id ) {
		global $wpdb;

		$table  = $wpdb->prefix . 'gswpts_tables';
		$data   = [ 'table_name' => $name ];
		$output = __( 'Table name updated successfully', 'sheetstowptable' );

		$response = $wpdb->update(
			$table,
			$data,
			[ 'id' => $id ],
			[ '%s' ],
			[ '%d' ]
		);

		if ( $response ) {
			wp_send_json_success([
				'output' => $output,
				'type'   => 'updated',
			]);
		}

		wp_send_json_success([
			'output' => __( 'Could not update the data.', 'sheetstowptable' ),
			'type'   => 'invalid_action',
		]);
	}
}// phpcs:ignore
