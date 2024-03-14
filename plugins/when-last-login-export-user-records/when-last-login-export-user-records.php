<?php
/**
 * Plugin Name: When Last Login - Export User Records
 * Description: Exports all login records into a CSV file
 * Author: Yoohoo Plugins
 * Author URI: https://yoohooplugins.com
 * Version: 1.1
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: when-last-login-export-user-records
 * Domain Path: /languages
 */

/**
 * 1.0.1
 * Added in the ability to export user records
 * Separated export between login records and user records
 * Export tab has been added to the 'When Last Login' menu
 *
 * 1.0.0
 * Launch
 */
class WhenLastLoginExportUserRecords {

	public function __construct() {

		add_filter( 'wll_settings_page_tabs', array( $this, 'wll_eur_settings_tab' ) );
		add_filter( 'wll_settings_page_content', array( $this, 'wll_eur_settings_content' ) );
		add_action( 'init', array( $this, 'wll_eur_export_records' ) );
		add_action( 'admin_menu', array( $this, 'wll_eur_menu_item' ) );

	}

	public function wll_eur_menu_item() {

		add_submenu_page( 'when-last-login-settings', __( 'Export Records', 'when-last-login-export-user-records' ), __( 'Export Records', 'when-last-login-export-user-records' ), 'manage_options', '?page=when-last-login-settings&tab=export-user-records' );

	}

	public function wll_eur_settings_tab( $array ) {

		$array['export-user-records'] = array(
			'title' => __( 'Export User Records', 'when-last-login-export-user-records' ),
			'icon'  => '',
		);

		return $array;

	}

	public function wll_eur_settings_content( $content ) {

		$content['export-user-records'] = plugin_dir_path( __FILE__ ) . '/when-last-login-export-user-records-settings.php';

		return $content;

	}

	/**
	 * Export All Login and All User Records.
	 * 
	 * @since 1.0
	 */
	public function wll_eur_export_records() {

		$export_array = array();

		if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'export-user-records' ) {

			if ( isset( $_GET['export'] ) && isset( $_GET['type'] ) ) {

				if ( $_GET['export'] == 'login-records' ) {

					
					if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wll_all_login_records_nonce' ) ) {
						return;
					}

					$args = array(
						'posts_per_page' => -1,
						'post_type'      => 'wll_records',
					);

					$the_query = new WP_Query( $args );

					$export_array[] = array( 'title', 'author', 'email_address', 'date', 'ip_address' );

					if ( $the_query->have_posts() ) {

						while ( $the_query->have_posts() ) {

							$the_query->the_post();

							$ip_address = get_post_meta( get_the_ID(), 'wll_user_ip_address', true );

							if ( $ip_address == '' ) {
								$ip_address = esc_html__( 'No IP Address Recorded', 'when-last-login-export-user-records' );
							}

							$email_address = get_the_author_meta( 'user_email' );

							$export_array[] = array(
								'title'         => sanitize_text_field( get_the_title() ),
								'author'        => sanitize_text_field( get_the_author() ),
								'email_address' => sanitize_email( $email_address ),
								'date'          => sanitize_text_field( get_the_date() ),
								'ip_address'    => sanitize_text_field( $ip_address ),
							);

							$export_array = apply_filters( 'wll_export_login_records_user_login_each', $export_array );

						}

						wp_reset_postdata();

					}
				} elseif ( $_GET['export'] == 'user-records' ) {

					if ( ! wp_verify_nonce( $_REQUEST['user_nonce'], 'wll_all_user_records_nonce' ) ) {
						return;
					}

					$users = get_users();

					$export_array[] = array( 'display_name', 'email_address', 'last_login', 'login_count' );

					foreach ( $users as $user ) {

						$last_logged_in  = get_user_meta( $user->ID, 'when_last_login', true );
						$logged_in_count = get_user_meta( $user->ID, 'when_last_login_count', true );

						if ( $last_logged_in == 0 ) {
							$formatted_logged_in = __( 'Never', 'when-last-login-export-user-records' );
						} else {
							$formatted_logged_in = date( 'Y-m-d H:i:s', (int) $last_logged_in );
						}

						$export_array[] = array(
							'display_name'  => sanitize_text_field( $user->data->display_name ),
							'email_address' => sanitize_email( $user->data->user_email ),
							'last_login'    => sanitize_text_field( $formatted_logged_in ),
							'login_count'   => sanitize_text_field( $logged_in_count ),
						);

						$export_array = apply_filters( 'wll_export_user_records_user_login_each', $export_array );

					}
				}

				if ( $_GET['type'] == 'csv' ) {

					$fileName = time() . '-when-last-login-export-' . sanitize_text_field( $_GET['export'] ) . '.csv';

					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-Description: File Transfer' );
					header( 'Content-type: text/csv' );
					header( 'Content-Disposition: attachment; filename=' . sanitize_text_field( $fileName ) );
					header( 'Expires: 0' );
					header( 'Pragma: public' );
					$fh = @fopen( 'php://output', 'w' );

					foreach ( $export_array as $record ) {
						fputcsv( $fh, $record, ',', '"' );
					}

					fclose( $fh );

					exit();

				} elseif ( $_GET['type'] == 'json' ) {

					$fileName = time() . '-when-last-login-export-' . sanitize_text_field( $_GET['export'] ) . '.json';

					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-Description: File Transfer' );
					header( 'Content-type: text/json' );
					header( 'Content-Disposition: attachment; filename=' . sanitize_text_field( $fileName ) );
					header( 'Expires: 0' );
					header( 'Pragma: public' );
					$fh = @fopen( 'php://output', 'w' );

					fputs( $fh, json_encode( $export_array ) );

					fclose( $fh );

					exit();

				}
			}
		}

	}

}

new WhenLastLoginExportUserRecords();
