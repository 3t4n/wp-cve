<?php //phpcs:ignore
/**
 * Responsible for managing plugin admin area.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS; // phpcs:ignore

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for registering admin menus.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Admin {

	/**
	 * Class constructor.
	 *
	 * @since 2.12.15
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menus' ] );
		add_action( 'admin_init', [ $this, 'migrate_stwptls_style_data' ] );
		add_action( 'admin_init', [ $this, 'migrate_stwptls_tab_data' ] );
	}

	/**
	 * Registers admin menus.
	 *
	 * @since 2.12.15
	 */
	public function admin_menus() {
		$strings_collection = Strings::get();

		add_menu_page(
			__( 'Sheets To Table', 'sheetstowptable' ),
			__( 'Sheets To Table', 'sheetstowptable' ),
			'manage_options',
			'gswpts-dashboard',
			[ $this, 'dashboard_page' ],
			SWPTLS_BASE_URL . 'assets/public/images/logo_20_20.svg'
		);

		if ( current_user_can( 'manage_options' ) ) {
			global $submenu;

			$submenu['gswpts-dashboard'][] = [ __( $strings_collection['Dashboard'], 'wppool-turnstile' ), 'manage_options', 'admin.php?page=gswpts-dashboard#/' ]; // phpcs:ignore

			$submenu['gswpts-dashboard'][] = [ __( $strings_collection['manage-tab-submenu'], 'wppool-turnstile' ), 'manage_options', 'admin.php?page=gswpts-dashboard#/tabs' ]; // phpcs:ignore

			$submenu['gswpts-dashboard'][] = [ __( $strings_collection['Settings'], 'wppool-turnstile' ), 'manage_options', 'admin.php?page=gswpts-dashboard#/settings' ]; // phpcs:ignore				

			$submenu['gswpts-dashboard'][] = [ __( $strings_collection['get-started'], 'wppool-turnstile' ), 'manage_options', 'admin.php?page=gswpts-dashboard#/doc' ]; // phpcs:ignore

			$submenu['gswpts-dashboard'][] = [ __( $strings_collection['recommended-plugins'], 'wppool-turnstile' ), 'manage_options', 'admin.php?page=gswpts-dashboard#/recommendation' ]; // phpcs:ignore
		}

		if ( ! swptls()->helpers->check_pro_plugin_exists() || ! swptls()->helpers->is_pro_active() ) {
			add_submenu_page(
				'gswpts-dashboard',
				__( 'Get PRO -sheets-to-wp-table-live-sync', 'sheetstowptable' ),// phpcs:ignore
				__( '<span style="display: flex; align-items: center; gap: 7px; color: #29be7c; font-weight: 700; text-transform:uppercase; font-size: 12px;"> Upgrade Now <svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M11.8012 7.66016L10.0813 4.26172L8.36133 7.66016C8.9104 7.69498 9.48392 7.71466 10.0813 7.71466C10.6786 7.71466 11.2522 7.69498 11.8012 7.66016Z" fill="#34D399"/>
					<path d="M13.5164 3.93457C12.5221 4.00909 11.4463 4.05114 10.3535 4.05712L12.0518 7.41268L13.5164 3.93457Z" fill="#34D399"/>
					<path d="M17.655 6.58984C18.9408 6.17882 19.7993 5.76404 20.1634 5.57337L17.6901 3.27344L16.8496 6.82924C17.1409 6.74883 17.4099 6.6682 17.655 6.58984Z" fill="#34D399"/>
					<path d="M13.8256 4.06055L12.3262 7.62137C13.8894 7.49126 15.2336 7.24006 16.3194 6.96869L13.8256 4.06055Z" fill="#34D399"/>
					<path d="M3.84375 6.96869C4.92956 7.24006 6.27375 7.49126 7.83695 7.62137L6.33749 4.06055L3.84375 6.96869Z" fill="#34D399"/>
					<path d="M14.1152 3.88492L16.5346 6.70624L17.3326 3.33008C17.0083 3.4568 16.4276 3.60162 15.3786 3.74365C14.9823 3.79731 14.5586 3.84437 14.1152 3.88492Z" fill="#34D399"/>
					<path d="M9.8103 4.05712C8.71747 4.0511 7.64174 4.00905 6.64746 3.93457L8.11206 7.41268L9.8103 4.05712Z" fill="#34D399"/>
					<path d="M10.0818 3.72428C11.9183 3.72428 13.7348 3.62004 15.1969 3.43079C16.4383 3.27009 17.054 3.09714 17.3042 2.98362C17.0203 2.8663 16.3914 2.69009 15.2275 2.52161C13.739 2.30616 11.9115 2.1875 10.0818 2.1875C8.25205 2.1875 6.42458 2.30616 4.93603 2.52161C3.77219 2.69009 3.14329 2.8663 2.85938 2.98362C3.10952 3.09718 3.7253 3.27009 4.96671 3.43079C6.42872 3.62004 8.24524 3.72428 10.0818 3.72428Z" fill="#34D399"/>
					<path d="M10.082 8.04847C9.46536 8.04847 8.87357 8.02778 8.30762 7.99121L10.082 16.352L11.8563 7.99121C11.2904 8.02782 10.6986 8.04847 10.082 8.04847Z" fill="#34D399"/>
					<path d="M6.04742 3.88492C5.60405 3.84437 5.18037 3.79731 4.78402 3.74365C3.73508 3.60167 3.15437 3.4568 2.83008 3.33008L3.62808 6.70624L6.04742 3.88492Z" fill="#34D399"/>
					<path d="M12.2028 7.96734L10.4326 16.3084L16.2509 7.33008C15.1406 7.59983 13.779 7.84508 12.2028 7.96734Z" fill="#34D399"/>
					<path d="M16.7281 7.20887L11.3818 15.4589L19.7314 6.15625C19.2695 6.36611 18.6125 6.63452 17.7695 6.9046C17.4591 7.00407 17.1111 7.10714 16.7281 7.20887Z" fill="#34D399"/>
					<path d="M3.91211 7.33008L9.73035 16.3084L7.96014 7.96734C6.38399 7.84504 5.02233 7.59979 3.91211 7.33008Z" fill="#34D399"/>
					<path d="M2.39357 6.9046C1.55059 6.63452 0.893524 6.36611 0.431641 6.15625L8.78123 15.4589L3.43499 7.20887C3.05198 7.10714 2.70398 7.00407 2.39357 6.9046Z" fill="#34D399"/>
					<path d="M3.31381 6.8292L2.47334 3.27344L0 5.57332C0.364207 5.764 1.22261 6.17878 2.50845 6.58979C2.75354 6.66816 3.02249 6.74878 3.31381 6.8292Z" fill="#34D399"/>
					<path d="M1.78207 1.06487C1.84142 1.14328 1.88961 1.23343 1.92864 1.32785C1.96768 1.23347 2.01583 1.14332 2.07522 1.06487C2.21164 0.884688 2.40578 0.753868 2.57995 0.663923C2.40578 0.573979 2.21164 0.443117 2.07522 0.262978C2.01583 0.184569 1.96768 0.0943745 1.92864 0C1.88961 0.0943745 1.84146 0.184528 1.78207 0.262978C1.64565 0.443159 1.45151 0.573979 1.27734 0.663923C1.45151 0.753868 1.64565 0.884688 1.78207 1.06487Z" fill="#34D399"/>
					<path d="M16.8194 12.844C16.76 12.7656 16.7118 12.6754 16.6728 12.5811C16.6337 12.6754 16.5856 12.7656 16.5262 12.844C16.3898 13.0242 16.1957 13.155 16.0215 13.245C16.1957 13.3349 16.3898 13.4658 16.5262 13.6459C16.5856 13.7243 16.6337 13.8145 16.6728 13.9089C16.7118 13.8145 16.76 13.7243 16.8194 13.6459C16.9558 13.4657 17.1499 13.3349 17.3241 13.245C17.1499 13.155 16.9557 13.0242 16.8194 12.844Z" fill="#34D399"/>
					</svg>
					
				</span>', 'sheetstowptable' ),// phpcs:ignore	
				'manage_options', 'https://go.wppool.dev/KfVZ', '', 9999
			);

			// Open the link in a new tab.
			add_action('admin_footer', function () {
				echo "<script>
					jQuery(document).ready(function($) {
						$('#toplevel_page_gswpts-dashboard .wp-submenu a[href=\"https://go.wppool.dev/KfVZ\"]').attr('target', '_blank');
					});
				</script>";
			});

		}
	}

	/**
	 * Displays admin page.
	 *
	 * @return void
	 */
	public static function dashboard_page() {
		echo '<div id="swptls-app-root"></div>';
		echo '<div id="swptls-app-portal"></div>';
	}

	/**
	 * Migrate style data
	 *
	 * @return void
	 */
	public static function migrate_stwptls_style_data() {
		global $wpdb;

		$collate = $wpdb->get_charset_collate();
		$table = $wpdb->prefix . 'gswpts_tables';

		/**
		 * Auto support add for old and new user.
		 */
		$code_has_run = get_option('link_support_code_has_run', 0);
		$img_link_has_run = get_option('img_link_pro_support_has_run', 0);

		if ( 0 === $code_has_run ) {
			if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") === $table ) {//phpcs:ignore

				if ( empty($wpdb->get_results("SELECT * FROM $table")) ) {//phpcs:ignore
					// The table is empty, set link_support_mode to 'smart_link'.
					update_option('link_support_mode', 'smart_link');
					update_option('link_support_code_has_run', 1);
				} else {
					// update link support mode for old user.
					$current_mode = get_option('link_support_mode', 'smart_link'); // Get the current mode.
					if ( 'smart_link' !== $current_mode ) {
						// Update link support mode for old users when it's not 'smart_link'.
						update_option('link_support_mode', 'pretty_link');
						update_option('link_support_code_has_run', 1);
					}
				}
			}
			// Set the flag to 1 to prevent the code from running again.
			update_option('link_support_code_has_run', 1);
		}

		if ( 0 === $img_link_has_run ) {
			$current_mode = get_option('link_support_mode', 'smart_link');
			if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") === $table ) {// phpcs:ignore

				$auto_active = $wpdb->get_results( "SELECT * FROM $table" );// phpcs:ignore

				if ( empty($wpdb->get_results("SELECT * FROM $table")) ) { // phpcs:ignore
					update_option('img_link_pro_support_has_run', 1);
				} else {
					// enable link and image support mode for old user.
					foreach ( $auto_active as $data ) {
						$table_settings = json_decode( $data->table_settings, true );

						if ( 'smart_link' === $current_mode ) {
							$table_settings['table_link_support'] = true;
							$table_settings['table_img_support'] = true;
						}
						if ( 'pretty_link' === $current_mode ) {
							$table_settings['table_link_support'] = false;
							$table_settings['table_img_support'] = true;
						}
						$new_settings = json_encode($table_settings);

						// Update each row with the modified settings.
						$wpdb->update(
							$table,
							[ 'table_settings' => $new_settings ],
							[ 'id' => $data->id ],
							[ '%s' ],
							[ '%d' ]
						);
					}

					update_option('img_link_pro_support_has_run', 1);
				}
			}
			update_option('img_link_pro_support_has_run', 1);
		}

		// Get the existing data from the database.
		$existing_data = $wpdb->get_results( "SELECT * FROM $table" );// phpcs:ignore

		foreach ( $existing_data as $data ) {
			// Convert JSON data to an associative array.
			$table_settings = json_decode( $data->table_settings, true );

			if ( swptls()->helpers->is_pro_active() ) {

				// If we're in Desktop.
				// phpcs:ignore
				if ( isset( $table_settings['hide_column']['desktopValues'] ) && $table_settings['hide_column']['desktopValues'] !== null ) {
					// If we're in both Desktop and mobile.
					// phpcs:ignore

					// Rare case : If both 'desktopValues' and 'mobileValues' are empty strings.
					// phpcs:ignore
					if ( $table_settings['hide_column']['desktopValues'] === '' && $table_settings['hide_column']['mobileValues'] === '' ) {// phpcs:ignore
						// If both 'desktopValues' and 'mobileValues' are empty strings.
						$table_settings['hide_column'] = [];
						$table_settings['hide_on_desktop'] = true;
						$table_settings['hide_on_mobile'] = false;
					}
					// phpcs:ignore
					if ( isset( $table_settings['hide_column']['mobileValues'] ) && $table_settings['hide_column']['mobileValues'] !== null ) {// phpcs:ignore
						// Desktop and mobile both have.
						$desktop_values = array_map( 'intval', $table_settings['hide_column']['desktopValues'] );
						$table_settings['hide_column'] = $desktop_values;
						$table_settings['hide_on_desktop'] = true;
						$table_settings['hide_on_mobile'] = true;
					} else {
						// If only in desktop.
						// phpcs:ignore
						if ( isset( $table_settings['hide_column']['desktopValues'] ) &&
							is_array( $table_settings['hide_column']['desktopValues'] ) &&
							$table_settings['hide_column']['desktopValues'] !== null ) {// phpcs:ignore
							// Desktop and mobile both have.
							$desktop_values = array_map( 'intval', $table_settings['hide_column']['desktopValues'] );
							$table_settings['hide_column'] = $desktop_values;
							$table_settings['hide_on_desktop'] = true;
							$table_settings['hide_on_mobile'] = false;
						}
					}
				} else {
					// If not in desktop but in mobile.
					// phpcs:ignore
					if ( isset( $table_settings['hide_column']['mobileValues'] ) && $table_settings['hide_column']['mobileValues'] !== null ) {
						// If not in desktop but mobile has.

						$table_settings['hide_column'] = array_map( 'intval', $table_settings['hide_column']['mobileValues'] );
						$table_settings['hide_on_desktop'] = false;
						$table_settings['hide_on_mobile'] = true;
					}
				}

				if ( is_array( $table_settings['hide_column'] ) && array_key_exists( 'desktopValues', $table_settings['hide_column'] ) && array_key_exists( 'mobileValues', $table_settings['hide_column'] ) ) {
					// Both empty desktop and Mobile from inside of IF.
					$table_settings['hide_column'] = [];
					$table_settings['hide_on_desktop'] = true;
					$table_settings['hide_on_mobile'] = false;
				}

				// vertical_scroll set.
				if ( isset( $table_settings['vertical_scroll'] ) ) {
					$table_settings['vertical_scrolling'] = $table_settings['vertical_scroll'];
					$table_settings['vertical_scroll'] = null;
				}
			}

			// Table_title set.
			if ( isset( $table_settings['table_title'] ) ) {
				$table_settings['show_title'] = $table_settings['table_title'];
				$table_settings['table_title'] = null;
			}

			// ON Free version we need to generate all default value to match latest revamp data.
			// Set first time for check if pro active but now condtion no need.
			// phpcs:ignore
			if ( $table_settings['table_title'] == null ) {

				// If we update then no need to update again. to check we're using old_update.
				$table_settings['old_update'] = isset( $table_settings['old_update'] ) ? $table_settings['old_update'] : false;
				// phpcs:ignore
				if ( $table_settings['old_update'] == false ) {// phpcs:ignore
					// Execute 1.
					$table_settings['show_title'] = isset( $table_settings['table_title'] ) ? $table_settings['table_title'] : ( isset( $table_settings['show_title'] ) ? $table_settings['show_title'] : false );

					$table_settings['table_title'] = isset( $table_settings['table_title'] ) ? $table_settings['table_title'] : null;
					$table_settings['default_rows_per_page'] = isset( $table_settings['default_rows_per_page'] ) ? $table_settings['default_rows_per_page'] : '10';
					$table_settings['show_info_block'] = isset( $table_settings['show_info_block'] ) ? $table_settings['show_info_block'] : false;
					$table_settings['show_x_entries'] = isset( $table_settings['show_x_entries'] ) ? $table_settings['show_x_entries'] : true;
					$table_settings['swap_filter_inputs'] = isset( $table_settings['swap_filter_inputs'] ) ? $table_settings['swap_filter_inputs'] : false;
					$table_settings['swap_bottom_options'] = isset( $table_settings['swap_bottom_options'] ) ? $table_settings['swap_bottom_options'] : false;
					$table_settings['allow_sorting'] = isset( $table_settings['allow_sorting'] ) ? $table_settings['allow_sorting'] : false;
					$table_settings['search_bar'] = isset( $table_settings['search_bar'] ) ? $table_settings['search_bar'] : true;
					$table_settings['responsive_style'] = isset( $table_settings['responsive_style'] ) ? $table_settings['responsive_style'] : 'default_style';

					$table_settings['import_styles'] = isset( $table_settings['import_styles'] ) ? $table_settings['import_styles'] : false;
					$table_settings['table_img_support'] = isset( $table_settings['table_img_support'] ) ? $table_settings['table_img_support'] : false;
					$table_settings['table_link_support'] = isset( $table_settings['table_link_support'] ) ? $table_settings['table_link_support'] : false;

					$table_settings['responsive_table'] = isset( $table_settings['responsive_table'] ) ? $table_settings['responsive_table'] : null;
					$table_settings['vertical_scrolling'] = isset( $table_settings['vertical_scroll'] ) ? $table_settings['vertical_scroll'] : null;
					$table_settings['table_export'] = isset( $table_settings['table_export'] ) ? $table_settings['table_export'] : [];
					$table_settings['cell_format'] = isset( $table_settings['cell_format'] ) ? $table_settings['cell_format'] : 'expand';
					$table_settings['redirection_type'] = isset( $table_settings['redirection_type'] ) ? $table_settings['redirection_type'] : '_blank';
					$table_settings['cursor_behavior'] = isset( $table_settings['cursor_behavior'] ) ? $table_settings['cursor_behavior'] : 'left_right';
					$table_settings['table_style'] = isset( $table_settings['table_style'] ) ? $table_settings['table_style'] : 'default-style';

					if ( isset( $table_settings['hide_column']['desktopValues'] ) && $table_settings['hide_column']['desktopValues'] !== null ) {// phpcs:ignore
						// If desktop has and.
						// Desktop and mobile.

						// Rare case : If both 'desktopValues' and 'mobileValues' are empty strings.
						if ( $table_settings['hide_column']['desktopValues'] === '' && $table_settings['hide_column']['mobileValues'] === '' ) {// phpcs:ignore
							// If both 'desktopValues' and 'mobileValues' are empty strings.
							$table_settings['hide_column'] = [];
							$table_settings['hide_on_desktop'] = true;
							$table_settings['hide_on_mobile'] = false;
						}

						if ( isset( $table_settings['hide_column']['mobileValues'] ) && $table_settings['hide_column']['mobileValues'] !== null ) {// phpcs:ignore
							// Desktop and mobile both have.
							$desktop_values = array_map( 'intval', $table_settings['hide_column']['desktopValues'] );
							$table_settings['hide_column'] = $desktop_values;
							$table_settings['hide_on_desktop'] = true;
							$table_settings['hide_on_mobile'] = true;
						} else {
							// If only in desktop.
							// phpcs:ignore
							if ( isset( $table_settings['hide_column']['desktopValues'] ) &&
							is_array( $table_settings['hide_column']['desktopValues'] ) &&
							$table_settings['hide_column']['desktopValues'] !== null ) {// phpcs:ignore
								// Desktop and mobile both have.
								$desktop_values = array_map( 'intval', $table_settings['hide_column']['desktopValues'] );
								$table_settings['hide_column'] = $desktop_values;
								$table_settings['hide_on_desktop'] = true;
								$table_settings['hide_on_mobile'] = false;
								$table_settings['pagination'] = true;
							}
						}
					} else {
						// Jodi desktop e nai but mobile e ase then.
						if ( isset( $table_settings['hide_column']['mobileValues'] ) && $table_settings['hide_column']['mobileValues'] !== null ) {// phpcs:ignore
							// If not in desktop but mobile has.
							$table_settings['hide_column'] = array_map( 'intval', $table_settings['hide_column']['mobileValues'] );
							$table_settings['hide_on_desktop'] = false;
							$table_settings['hide_on_mobile'] = true;
						}
					}

					if ( is_array( $table_settings['hide_column'] ) && array_key_exists( 'desktopValues', $table_settings['hide_column'] ) && array_key_exists( 'mobileValues', $table_settings['hide_column'] ) ) {
						// Both empty desktop and Mobile from inside of IF.
						$table_settings['hide_column'] = [];
						$table_settings['hide_on_desktop'] = true;
						$table_settings['hide_on_mobile'] = false;
					}
					// END.
					$table_settings['hide_rows'] = isset( $table_settings['hide_rows'] ) ? $table_settings['hide_rows'] : [];
					$table_settings['hide_cell'] = isset( $table_settings['hide_cell'] ) ? $table_settings['hide_cell'] : [];
					$table_settings['import_styles'] = isset( $table_settings['import_styles'] ) ? $table_settings['import_styles'] : false;
					$table_settings['table_cache'] = isset( $table_settings['table_cache'] ) ? $table_settings['table_cache'] : false;
					$table_settings['pagination'] = isset( $table_settings['pagination'] ) ? $table_settings['pagination'] : true;

					$table_settings['old_update'] = true;
				}
			}

			// Finall Update the data in the database with the new structure.
			$new_settings = json_encode( $table_settings );
			$wpdb->update(
				$table,
				[ 'table_settings' => $new_settings ],
				[ 'id' => $data->id ],
				[ '%s' ],
				[ '%d' ]
			);
		}
	}


	/**
	 * Migrate migrate stwptls tab data
	 *
	 * @return void
	 */
	public static function migrate_stwptls_tab_data() {
		global $wpdb;
		$collate = $wpdb->get_charset_collate();
		$table = $wpdb->prefix . 'gswpts_tabs';
		// phpcs:ignore
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {// phpcs:ignore

			// Get the existing data from the old database.
			$existing_data = $wpdb->get_results( "SELECT * FROM $table" );// phpcs:ignore

			foreach ( $existing_data as $data ) {
				// Convert JSON data to an associative array.
				$table_settings = json_decode( $data->tab_settings, true );

				// Check if the data structure matches the new one.
				// phpcs:ignore
				if ( isset( $table_settings['id'] ) && isset( $table_settings['name'] ) && isset( $table_settings['tableId'] ) && isset( $table_settings['tableID'] ) ) {
					// Update only if the data structure matches.
					$new_settings = [
						'id' => intval( $table_settings['id'] ),
						'name' => $table_settings['name'],
						'tableId' => $table_settings['tableId'],
						'tableID' => $table_settings['tableID'],
					];

					// Perform the update for rows that match the data structure.
					$wpdb->update(
						$table,
						[ 'tab_settings' => json_encode( $new_settings ) ],
						[ 'id' => $data->id ]
					);
				}
			}
		}
	}
}
