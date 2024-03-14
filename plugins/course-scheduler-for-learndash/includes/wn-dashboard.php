<?php
/**
 * WN DASHBOARD SETTINGS
 *
 * @author   WooNinjas
 * @category Admin
 * @package  WN_DASHBOARD_Page
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WN_DASHBOARD_Page
 */
if( !class_exists('WN_DASHBOARD_Page') ) {
	class WN_DASHBOARD_Page {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {


		}

		/**
		 * Handle scripts and css.
		 *
		 * @return void
		 */
		function enquey_scripts() {

			wp_enqueue_style( 'wn-plugin-dashboard', "https://wordpress-475011-1491998.cloudwaysapps.com/wn-dashboard.css", array() );
			wp_enqueue_script( 'wn-plugin-dashboard', "https://wordpress-475011-1491998.cloudwaysapps.com/wn-dashboard.js", array( 'jquery' ), time(), false );

			$plugin_data = wp_cache_get( 'wn_dashboard_data' );
			if ( ! isset( $plugin_data ) || empty( $plugin_data ) ) {

				$addons      = $this->remote_data( 'addons', 'All', '' );
				$addon_tabs  = $this->remote_data( 'tab_types', 'All' );
				$addons_list = [];
				if ( isset( $addons ) && is_array( $addons ) ) {
					foreach ( $addons as $key => $addon ) {
						if ( $addon->active == 'Yes' ) {
							if ( class_exists( $addon->plugin_class ) ) {
								$addon->installed = true;
							} else {
								$addon->installed = false;
							}
							$addons_list[] = $addon;
						}
					}
				}

				$data_error = wp_cache_get( 'wn_dashboard_data_error' );

				wp_cache_set( 'wn_dashboard_data', [ 'error'             => $data_error,
				                                     'addons'            => $addons_list,
				                                     'tabs'              => $addon_tabs,
				                                     'not_active_text'   => __( 'Not Active', 'ld-adaptive-learning' ),
				                                     'active_text'       => __( 'Active', 'ld-adaptive-learning' ),
				                                     'search'            => __( 'Search...', 'ld-adaptive-learning' ),
				                                     'ajax_url'          => admin_url( 'admin-ajax.php' ),
				                                     'no_rec_found_text' => __( 'No WooNinjas Addon Found.', 'ld-adaptive-learning' )
				], '', 300 );

				$plugin_data = wp_cache_get( 'wn_dashboard_data' );
			}

			wp_localize_script( 'wn-plugin-dashboard', 'WN_Dashboard_Data', $plugin_data );
		}

		/**
		 * Ajax download handler script.
		 *
		 * @return void
		 */
		public function remote_data( $list_type = "addons", $addon_type = "All", $search = '' ) {
			$request = wp_remote_get( 'https://wordpress-475011-1491998.cloudwaysapps.com/addons-api.php?search=' . urlencode( $search ) . '&list_type=' . $list_type . '&addon_type=' . $addon_type );
			if ( is_wp_error( $request ) ) {
				wp_cache_set( 'wn_dashboard_data_error', $request->errors['http_request_failed'], '', 300 );

				return false;
			}

			wp_cache_set( 'wn_dashboard_data_error', [], '', 300 );

			$body = wp_remote_retrieve_body( $request );

			return json_decode( $body );
		}

		/**
		 * Setting page data
		 */
		public function dashboard_page() {
			$data_error = wp_cache_get( 'wn_dashboard_data_error' );
			?>
            <div class="wn-dashboard-addon">
                <h1><?php echo __( 'Dashboard', 'ld-adaptive-learning' ); ?></h1>
                <div class="wn_dashboard_header wn_dashboard_topnav"></div>
                <div class="wn_dashboard_plugins_listing">
					<?php
					$body_html = '';
					if ( isset( $data_error ) && is_array( $data_error ) && count( $data_error ) > 0 ) {
						if ( count( $data_error ) > 1 ) {
							$body_html = '<p class="wn-dashboard-no-rec-found">';
							for ( $i = 0; $i < count( $data_error ); $i ++ ) {
								$body_html .= '<br> - ' . $data_error[ $i ];
							}
							$body_html .= '</p>';
						} else {
							$body_html = '<p class="wn-dashboard-no-rec-found">' . $data_error[0] . '</p>';
						}

						echo $body_html;
					}
					?>
                </div>
            </div>
			<?php
		}
	}
}