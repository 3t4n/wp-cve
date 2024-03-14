<?php
/**
 * The cronjobs frunctionality of the plugin.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

// define file path constant.
define( 'LOCATION_FILE_NAME', plugin_dir_path( __FILE__ ) . 'locations/pudo_locations.json' );
define( 'COUNTIES_FILE_NAME', plugin_dir_path( __FILE__ ) . 'locations/counties.json' );
/**
 * The cronjobs frunctionality of the plugin.
 *
 * Defines the plugin wp crons.
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 * @author     Cargus <contact@cargus.ro>
 */
class Cargus_Cron {

	/**
	 * Include the cargus shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function load_dependencies() {

		/**
		 * The class responsible for creating the Cargus api.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-api.php';
	}

	/**
	 * Function used to get the Ship&Go points and put them in json.
	 */
	public function cargus_load_pudo_points() {
		$this->load_dependencies();
		$cargus = new Cargus_Api();

		if ( property_exists( $cargus, 'token' ) && ! is_array( $cargus->token ) ) {
			$locations = $cargus->get_pudo_points();

			$this->cargus_write_json( 'locations', $locations, LOCATION_FILE_NAME, false );

		} elseif ( get_option( 'cargus_locations_loaded' ) ) {
			update_option( 'cargus_locations_loaded', false, false );
		}
	}

	/**
	 * Function used to get the Ship&Go points and put them in json.
	 */
	public function cargus_load_counties() {
		$this->load_dependencies();
		$cargus         = new Cargus_Api();
		$cargus_options = get_option( 'woocommerce_cargus_settings' );

		if ( property_exists( $cargus, 'token' ) && ! is_array( $cargus->token ) && 'yes' === $cargus_options['locations-select'] ) {
			// obtin lista de judete din api.
			$counties_ids = array();
			$counties     = $cargus->get_counties();

			$this->cargus_write_json( 'counties', $counties, COUNTIES_FILE_NAME, false );

		} elseif ( get_option( 'cargus_counties_loaded' ) ) {
			update_option( 'cargus_counties_loaded', false, false );
		}
	}

	/**
	 * Function used to write in json information.
	 *
	 * @param string $type The type of the information to be written.
	 * @param array  $array The data array.
	 * @param string $file_name The file_name in which to write.
	 * @param bool   $is_array Check if the data comes in array.
	 */
	public function cargus_write_json( $type, $array, $file_name, $is_array ) {

		if ( is_array( $array ) && ! empty( $array ) ) {
			$json = '[';
			// empty the file contents.
			//phpcs:disable
			file_put_contents( $file_name, '' );
			// write the beginning of the json file.
			file_put_contents( $file_name, $json, FILE_APPEND );

			$i   = 0;
			$len = count( $array );

			// iterate through returned counties_array.
			foreach ( $array as $key => $location ) {
				if ( $is_array ) {
					$json = wp_json_encode( array( $key => $location ), JSON_UNESCAPED_SLASHES );
				} else {
					$json = wp_json_encode( $location, JSON_UNESCAPED_SLASHES );
				}

				if ( $i != $len - 1 ) {
					$json .= ',';
				}
				file_put_contents( $file_name, $json, FILE_APPEND );
				$i++;
			}
			$json = ']';
			file_put_contents( $file_name, $json, FILE_APPEND );
			//phpcs:enable

			update_option( 'cargus_' . $type . '_loaded', true, false );
			update_option( 'cargus_scheduled_load_' . $type . '_last_run', time() );
			add_action( 'admin_notices', array( $this, 'cargus_admin_notice_' . $type ) );
		}
	}

	/**
	 * Function used to schedule the action needed to get the ship_and_go locations.
	 */
	public function cargus_schedulle_cron() {
		//phpcs:disable
		// run the function for the first time when the plugin is activated.
		if ( is_admin() && ! get_option( 'cargus_scheduled_single_load_locations' ) &&
			( isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings' ) &&
			( isset( $_GET['tab'] ) && $_GET['tab'] === 'shipping' ) &&
			get_option( 'cargus_login_token' ) && get_option( 'cargus_login_token' ) !== 'error'
		) {
			update_option( 'cargus_scheduled_single_load_locations', time() );
			wp_schedule_single_event( time(), 'cargus_get_ship_and_go_locations_initial_sync' );
		}

		// run the function for the first time when the plugin is activated.
		if ( is_admin() && ! get_option( 'cargus_scheduled_single_load_counties' ) &&
			( isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings' ) &&
			( isset( $_GET['tab'] ) && $_GET['tab'] === 'shipping' ) &&
			get_option( 'cargus_login_token' ) && get_option( 'cargus_login_token' ) !== 'error'
		) {
			update_option( 'cargus_scheduled_single_load_counties', time() );
			wp_schedule_single_event( time(), 'cargus_get_counties_initial_sync' );
		}

		// launch the action "cargus_get_ship_and_go_locations" every hour.
		if ( ! wp_next_scheduled( 'cargus_get_ship_and_go_locations' ) ) {
			wp_schedule_event( time(), 'hourly', 'cargus_get_ship_and_go_locations' );
			update_option( 'cargus_scheduled_load_locations', time() );
		}
		//phpcs:enable
	}

	/**
	 * Function used to add a confirmation notice after the locations have been uploaded.
	 */
	public function cargus_admin_notice_locations() {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Locațiile cargus Ship&Go au fost incarcate cu success.' ) . '</p></div>';
	}

	/**
	 * Function used to add a confirmation notice after the counties have been uploaded.
	 */
	public function cargus_admin_notice_counties() {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Localitațile de livrare cargus au fost incarcate cu success.' ) . '</p></div>';
	}

	/**
	 * Generate an action shook that runs twice a day.
	 *
	 * @since 1.0.0
	 */
	public function cargus_refresh_login_token_action_hook() {
		// launch the action "cargus_refresh_login_token_action" every 12 hours.
		if ( ! wp_next_scheduled( 'cargus_refresh_login_token_action' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'cargus_refresh_login_token_action' );
			update_option( 'cargus_scheduled_refresh_login', time() );
		}
	}

	/**
	 * Refreshes the cargus login token.
	 *
	 * @since 1.0.0
	 */
	public function cargus_refresh_login_token() {

		$cargus = new Cargus_Api();

		if ( ! empty( $cargus->get_url() ) && ! empty( $cargus->get_api_key() ) ) {

			$fields = array(
				'UserName' => get_option( 'woocommerce_cargus_settings' )['username'],
				'Password' => get_option( 'woocommerce_cargus_settings' )['password'],
			);

			$token = $cargus->login_user( $fields, true );
			if ( ! is_object( $token ) && ! is_array( $token ) ) {
				update_option( 'cargus_scheduled_refresh_login_last_run', time() );
				update_option( 'cargus_login_token', $token, false );
			}
		}
	}
}
