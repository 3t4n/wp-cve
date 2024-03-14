<?php
/**
 * This file is for ajax calls.
 *
 * @package broken-link-finder/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLC_Ajax' ) ) {
	/**
	 * This is ajax class.
	 */
	class MOBLC_Ajax {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'moblc_broken_link_checker_ajax' ) );
		}
		/**
		 * Function for checking broken links.
		 *
		 * @return void
		 */
		public function moblc_broken_link_checker_ajax() {
			add_action( 'wp_ajax_moblc_broken_link_checker', array( $this, 'moblc_broken_link_checker' ) );
		}
		/**
		 * Function for calling ajax functions.
		 *
		 * @return void
		 */
		public function moblc_broken_link_checker() {
			if ( ! current_user_can( 'manage_options' ) ) {
				exit;
			}

			$option = ( isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : '' );//phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verification is there seprately in function called in the switch case below.
			switch ( $option ) {
				case 'moblc_check_links_from_pages':
					$this->moblc_start_scan();
					break;
				case 'progress_bar':
					$this->moblc_get_scan_progress();
					break;
				case 'moblc_stop_scan':
					$this->moblc_stop_scan();
					break;
				case 'moblc_enable_disable_debug_log':
					$this->moblc_enable_disable_debug_log();
					break;
				case 'moblc_delete_log_file':
					$this->moblc_delete_log_file();
					break;
				case 'moblc_edit_link':
					$this->moblc_edit_link();
					break;
				case 'moblc_recheck_link':
					$this->moblc_recheck_link();
					break;
				case 'moblc_dismiss_link':
					$this->moblc_dismiss_link();
					break;
				case 'moblc_not_broken_link':
					$this->moblc_not_broken_link();
					break;
				case 'moblc_filter_link':
					$this->moblc_filter_link();
					break;
				case 'moblc_recheck_links':
					$this->moblc_recheck_links();
					break;
				case 'moblc_check_page':
					$this->moblc_check_page();
					break;
				case 'moblc_ignore_page':
					$this->moblc_ignore_page();
					break;
				case 'moblc_update_status':
					$this->moblc_update_status();
					break;
			}
		}
		/**
		 * Function for starting scan.
		 *
		 * @return void
		 */
		public function moblc_start_scan() {

			MOBLCUtility::moblc_debug_file( '--------------------------------Scan started------------------------------' );
			global $moblc_db_queries, $wpdb;
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-link-nonce' ) ) {
				wp_send_json( 'ERROR' );
			}

			if ( get_site_option( 'moblc_is_scanning' ) ) {
				wp_send_json( 'Already Scanning' );
			}

			$moblc_db_queries->moblc_delete_history();
			if ( ! $moblc_db_queries->moblc_count_of_pages() ) {
				wp_send_json( 'NO_DATA' );
			}
			MOBLCUtility::moblc_debug_file( 'In start scan ajax' );

			$moblc_db_queries->moblc_update_option( 'moblc_scan_message', 'page' );
			$moblc_db_queries->moblc_update_option( 'moblc_page_scanning', 1 );
			$moblc_db_queries->moblc_update_option( 'moblc_link_scanning', 1 );
			update_site_option( 'moblc_scan_count', get_site_option( 'moblc_scan_count', 0 ) + 1 );
			$scan_object = MOBLCUtility::moblc_return_object();
			update_site_option( 'moblc_is_scanning', true );

			$response = $scan_object->moblc_start_scan();

			MOBLCUtility::moblc_debug_file( print_r( $response, true ) );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- this code is for entering data into debug log file.
		}
		/**
		 * Function for getting scan progress.
		 *
		 * @return void
		 */
		public function moblc_get_scan_progress() {
			global $moblc_db_queries;
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-link-nonce' ) ) {
				wp_send_json( 'ERROR' );
			}
			$is_scanning = get_site_option( 'moblc_is_scanning' );
			if ( ! $is_scanning ) {
				wp_send_json( $is_scanning );
			}

			MOBLCUtility::moblc_debug_file( 'in continuous scan' );
			$scan_object       = MOBLCUtility::moblc_return_object();
			$response          = $scan_object->moblc_continuous_scan();
			$response['count'] = $moblc_db_queries->moblc_count_broken_links();

			wp_send_json( $response );
		}
		/**
		 * Function for stopping scan.
		 *
		 * @return void
		 */
		public function moblc_stop_scan() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-link-nonce' ) ) {
				wp_send_json( 'ERROR' );
			}
			$scan_object = MOBLCUtility::moblc_return_object();
			delete_site_option( 'moblc_troubleshoot_index' );
			update_site_option( 'moblc_is_scanning', false );
			$scan_object->moblc_stop_scan();
		}
		/**
		 * Function for enabling disabling debug log functionality.
		 *
		 * @return void
		 */
		public function moblc_enable_disable_debug_log() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-nonce-enable-debug-log' ) ) {
				$error = new WP_Error();
				MOBLCUtility::moblc_debug_file( 'WP_ERROR:' . strval( $error ) );
				wp_send_json( $error );
			}
			global $wpdb;
			$table  = 'table';
			$enable = ( isset( $_POST['moblc_enable_debug_log'] ) ? sanitize_text_field( wp_unslash( $_POST['moblc_enable_debug_log'] ) ) : '' );
			if ( 'true' === $enable ) {
				update_site_option( 'moblc_debug_log', 1 );
				MOBLCUtility::moblc_debug_file( '====================Logs Enabled=========================' );
				wp_send_json( 'true' );
			} else {
				MOBLCUtility::moblc_debug_file( '====================Logs Disabled=========================' );
				update_site_option( 'moblc_debug_log', 0 );
				wp_send_json( 'false' );
			}
		}
		/**
		 * Function for deleting log file.
		 *
		 * @return void
		 */
		public function moblc_delete_log_file() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-nonce-delete-log' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				$debug_log_path = wp_upload_dir();
				$debug_log_path = $debug_log_path['basedir'];
				$file_name      = 'blc_debug_log.txt';
				$status         = file_exists( $debug_log_path . DIRECTORY_SEPARATOR . $file_name );
				if ( $status ) {
					unlink( $debug_log_path . DIRECTORY_SEPARATOR . $file_name );
					wp_send_json( 'true' );
				} else {
					wp_send_json( 'false' );
				}
			}
		}

		/**
		 * Function for editing links.
		 *
		 * @return void
		 */
		public function moblc_edit_link() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-edit-link-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				MOBLCUtility::moblc_debug_file( ( isset( $_POST['link_text'] ) ? sanitize_text_field( wp_unslash( $_POST['link_text'] ) ) : '' ) );

				$link_text        = sanitize_text_field( wp_unslash( $_POST['link_text'] ) );
				$edited_link_text = ( isset( $_POST['edited_link_text'] ) ? sanitize_text_field( wp_unslash( $_POST['edited_link_text'] ) ) : null );
				$status           = '';
				if ( '' !== $edited_link_text ) {
					if ( true === strpos( $edited_link_text, '://youtube' ) || true === strpos( $edited_link_text, '://www.youtube' ) ) {
						$body = wp_remote_retrieve_body( wp_remote_post( $edited_link_text ) );
						MOBLCUtility::moblc_debug_file( '  scanning link  [tag:youtube]' );
						if ( false === strpos( $body, 'Video unavailable' ) ) {
							MOBLCUtility::moblc_debug_file( 'Calling wp_remote_post:   ' );
							$response = wp_remote_retrieve_response_code( wp_remote_head( $edited_link_text ) );
							$status   = isset( $response ) ? $response : 'invalid link';
						} else {
							$status = 404;
						}
					} else {
						MOBLCUtility::moblc_debug_file( 'Calling wp_remote_post:   ' );
						$response = wp_remote_retrieve_response_code( wp_remote_head( $edited_link_text ) );
						$status   = isset( $response ) ? $response : 'invalid link';

					}
				}

				$is_all     = ( isset( $_POST['all_pages'] ) ? sanitize_text_field( wp_unslash( $_POST['all_pages'] ) ) : '' );
				$page_title = ( isset( $_POST['page_title'] ) ? sanitize_text_field( wp_unslash( $_POST['page_title'] ) ) : '' );
				if ( ( intval( $status ) !== 0 && ( $status < 300 || '' === $edited_link_text ) ) || '#' === $edited_link_text ) {
					global $wpdb, $moblc_db_queries;
					$edit_link_table = $wpdb->prefix . 'moblc_link_details_table';
					$search_link     = str_replace( '&', '&amp;', $link_text );
					$posts           = '';
					if ( 'true' === $is_all ) {
						wp_send_json( 'UPGRADE' );
					} else {
						$posts = $moblc_db_queries->moblc_get_edit_link( $page_title, $search_link );
					}

					MOBLCUtility::moblc_debug_file( print_r( $posts, true ) );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- This is for printing logs in debug log file.
					$post_count = count( $posts );
					for ( $i = 0; $i < $post_count; $i ++ ) {
						$content_post = get_page_by_title( $posts[ $i ]->page_title, OBJECT, 'post' );

						if ( is_null( $content_post ) ) {
							$content_post = get_page_by_title( $posts[ $i ]->page_title, OBJECT, 'page' );
						}

						$content = $content_post->post_content;
						$content = apply_filters( 'the_content', $content );
						$content = str_replace( $link_text, $edited_link_text, $content );

						$edit_post = array(
							'ID'           => $content_post->ID,
							'post_title'   => $content_post->post_title,
							'post_content' => $content,
						);

						if ( wp_update_post( $edit_post ) ) {
							$table = 'wp_moblc_link_details_table';
							$moblc_db_queries->moblc_delete_entry( $search_link, $page_title, $is_all );

						} else {
							wp_send_json( 'ERROR' );
						}
					}
					wp_send_json( 'SUCCESS' );
				} elseif ( 0 !== intval( $status ) && $status <= 399 ) {
					wp_send_json( 'SUCCESS' );
				} else {
					wp_send_json( 'BROKEN' );
				}
			}
		}
		/**
		 * Function for link recheck.
		 *
		 * @return void
		 */
		public function moblc_recheck_link() {
			global $moblc_db_queries;
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-recheck-link-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {

				$moblc_link    = isset( $_POST['link_text'] ) ? sanitize_text_field( wp_unslash( $_POST['link_text'] ) ) : '';
				$moblc_link_id = isset( $_POST['link_id'] ) ? sanitize_text_field( wp_unslash( $_POST['link_id'] ) ) : '';

				if ( $moblc_link && $moblc_link_id ) {
					$response = $this->moblc_check_link( $moblc_link );
					$status   = isset( $response ) ? $response : 'invalid link';
					$moblc_db_queries->moblc_db_update_status( $moblc_link_id, $status );
					wp_send_json( $status );
				} else {
					wp_send_json( 'ERROR' );
				}
			}
		}
		/**
		 * Function for checking link.
		 *
		 * @param mixed $moblc_link link.
		 * @return mixed
		 */
		public function moblc_check_link( $moblc_link ) {

			$moblc_link         = moblc_relative_to_absolute( $moblc_link, get_site_url() );
			$max_execution_time = ini_get( 'max_execution_time' );
			$response           = wp_remote_head(
				$moblc_link,
				array(
					CURLOPT_TIMEOUT => ( $max_execution_time - 5 ) * 1000, // increase this.
				)
			);
			if ( is_wp_error( $response ) ) {

				if ( isset( $response->errors['http_request_failed'][0] ) ) {
					return $this->get_display_message( $response->errors['http_request_failed'][0] );
				}
			} else {
				$response = wp_remote_retrieve_response_code( $response );
			}

			return $response;
		}
		/**
		 * Function for displaying response message.
		 *
		 * @param mixed $curl_error_response error response.
		 * @return string
		 */
		public function get_display_message( $curl_error_response ) {
			$response         = array(
				'cURL error 28'                => 'Request Timed out',
				'cURL error 6'                 => 'Could not resolve host',
				'A valid URL was not provided' => 'Invalid link',
			);
			$response_message = 'CURL_ERROR';
			foreach ( $response as $curl_error => $message ) {
				if ( strpos( $curl_error_response, $curl_error ) !== false ) {
					return $message;
				}
			}

			return $response_message;
		}
		/**
		 * Function for dismissing link.
		 *
		 * @return void
		 */
		public function moblc_dismiss_link() {
			global $moblc_db_queries;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-dismiss-link-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {

				$moblc_link    = isset( $_POST['link_text'] ) ? sanitize_text_field( wp_unslash( $_POST['link_text'] ) ) : '';
				$moblc_link_id = isset( $_POST['link_id'] ) ? sanitize_text_field( wp_unslash( $_POST['link_id'] ) ) : '';

				if ( $moblc_link_id ) {

					$result = $moblc_db_queries->moblc_remove_link( $moblc_link_id );

					if ( - 1 === $result ) {
						wp_send_json( 'ERROR' );
					} else {
						wp_send_json( 'SUCCESS' );
					}
				} else {
					wp_send_json( 'ERROR' );
				}
			}
		}
		/**
		 * Function for marking links which are not broken.
		 *
		 * @return void
		 */
		public function moblc_not_broken_link() {
			global $moblc_db_queries;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-not-broken-link-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {

				$moblc_link    = isset( $_POST['link_text'] ) ? esc_url_raw( wp_unslash( $_POST['link_text'] ) ) : '';
				$moblc_link_id = isset( $_POST['link_id'] ) ? sanitize_text_field( wp_unslash( $_POST['link_id'] ) ) : '';

				if ( $moblc_link && $moblc_link_id ) {
					$response = wp_remote_retrieve_response_code( wp_remote_head( $moblc_link ) );

					$status = isset( $response ) ? $response : 'invalid link';

					$result = $moblc_db_queries->moblc_mark_not_broken( $moblc_link_id, $status );

					if ( - 1 === $result ) {
						wp_send_json( 'ERROR' );
					} else {
						wp_send_json( 'SUCCESS' );
					}
				} else {
					wp_send_json( 'ERROR' );
				}
			}
		}
		/**
		 * Function for filtering links
		 *
		 * @return void
		 */
		public function moblc_filter_link() {
			global $moblc_db_queries;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-filter-link-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				if ( isset( $_POST['status'] ) ) {
					update_site_option( 'moblc_show_3xx', isset( $_POST['status']['status_300'] ) ? sanitize_text_field( wp_unslash( $_POST['status']['status_300'] ) ) === 'true' : 'false' );
					update_site_option( 'moblc_show_4xx', isset( $_POST['status']['status_400'] ) ? sanitize_text_field( wp_unslash( $_POST['status']['status_400'] ) ) === 'true' : 'false' );
					update_site_option( 'moblc_show_5xx', isset( $_POST['status']['status_500'] ) ? sanitize_text_field( wp_unslash( $_POST['status']['status_500'] ) ) === 'true' : 'false' );
					update_site_option( 'moblc_show_others', isset( $_POST['status']['status_others'] ) ? sanitize_text_field( wp_unslash( $_POST['status']['status_others'] ) ) === 'true' : 'false' );
					wp_send_json( 'SUCCESS' );
				} else {
					wp_send_json( 'ERROR' );
				}
			}
		}
		/**
		 * Function for rechecking links.
		 *
		 * @return void
		 */
		public function moblc_recheck_links() {

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'moblc-recheck-links-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );

			}
			global $moblc_db_queries;
			$max_execution_time = ini_get( 'max_execution_time' );
			$prev               = null !== get_site_option( 'moblc_last_recheck_links' ) ? get_site_option( 'moblc_last_recheck_links' ) : 0;
			$now                = time();
			$diff               = abs( $now - $prev );
			if ( $diff < $max_execution_time - 5 ) {
				return;
			}

			update_site_option( 'moblc_last_recheck_links', time() );

			$result = $moblc_db_queries->moblc_get_bad_responses();

			foreach ( $result as $link_obj ) {
				$response = $this->moblc_check_link( $link_obj->link );
				if ( $response >= 0 ) {
					$moblc_db_queries->moblc_db_update_status( $link_obj->id, $response );
				}
			}
			wp_send_json( 'SUCCESS' );
		}
		/**
		 * Function for checking page.
		 *
		 * @return void
		 */
		public function moblc_check_page() {

			global $moblc_db_queries, $wpdb;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-check-page-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				$moblc_link    = isset( $_POST['link_text'] ) ? esc_url_raw( wp_unslash( $_POST['link_text'] ) ) : null;
				$moblc_page_id = isset( $_POST['link_id'] ) ? sanitize_text_field( wp_unslash( $_POST['link_id'] ) ) : null;
				$page_id       = url_to_postid( $moblc_link );
				$title         = get_the_title( url_to_postid( $moblc_link ) );
				$base          = $moblc_link;

				$content = $wpdb->get_results( $wpdb->prepare( 'select `post_content`,`post_title` from %1sposts where `id`=%1s', array( $wpdb->prefix, $page_id ) ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- Database caching is not required here and using %s will add quotes so %1s is required.
				$title   = isset( $content[0]->post_title ) ? $content[0]->post_title : null;
				$content = isset( $content[0]->post_content ) ? $content[0]->post_content : null;
				$s_time  = time();

				$link_array = array();
				$links      = preg_split( '/<a | <link/', $content );
				MOBLCUtility::moblc_get_links( 'href', $links, $link_array );

				$links = preg_split( '/<img | <iframe/', $content );
				MOBLCUtility::moblc_get_links( 'src', $links, $link_array );
				$response = 'SUCCESS';
				if ( count( $link_array ) ) {
					$response = $this->moblc_scan_links( $link_array, $s_time, $base, $moblc_page_id, $title );
				} else {
					$moblc_db_queries->moblc_remove_broken_pages( $moblc_page_id );
					$response = 'NO_LINK_IN_PAGE';
				}

				wp_send_json( $response );

			}

		}
		/**
		 * Function for scanning links.
		 *
		 * @param array  $link_array link array.
		 * @param string $s_time scan time.
		 * @param string $base base.
		 * @param string $moblc_page_id page id.
		 * @param string $title title.
		 * @return string
		 */
		public function moblc_scan_links( $link_array, $s_time, $base, $moblc_page_id, $title ) {
			global $moblc_db_queries, $wpdb;

			$lsize = count( $link_array );
			$moblc_db_queries->moblc_update_option( 'moblc_total_links', $lsize );

			// Preparing Query.
			$link_details_table = $wpdb->prefix . 'moblc_link_details_table';
			$query              = $wpdb->prepare( 'INSERT INTO %1s (`link`,`page_title`,`status_code`) VALUES', array( $link_details_table ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- complex palceholder required here for unquoted table name.
			$count              = 0;
			$flag               = 0;
			// Iterating Links One by One.
			for ( $link_index = 0; $link_index < $lsize; $link_index ++ ) {

				if ( $this->moblc_check_time( $s_time ) ) {
					$query  = substr( $query, 0, - 1 );
					$query .= ' ON DUPLICATE KEY UPDATE `status_code`= VALUES(status_code);';
					if ( $flag ) {
						$wpdb->query( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Query prepared in above code and no caching is required here.
						$moblc_db_queries->moblc_remove_broken_pages( $moblc_page_id );
					}
					exit();
				}
				$count ++;

				$link = $link_array[ $link_index ];

				if ( ! empty( $link ) && filter_var( $link, FILTER_VALIDATE_URL ) ) {
					$link   = trim( moblc_relative_to_absolute( $link, $base ) );
					$stime  = time();
					$status = 'LINK_TO_BE_CHECKED';
					if ( 'LINK_TO_BE_CHECKED' === $status ) {
						$flag   = true;
						$query .= $wpdb->prepare( ' ( %s , %s , %s ),', array( $link, $title, $status ) );
					}
				}
			}

			$query  = substr( $query, 0, - 1 );
			$query .= ' ON DUPLICATE KEY UPDATE `status_code`= VALUES(status_code);';

			if ( $wpdb->query( $query ) >= 0 ) { //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Query is prepared already, Caching is not required here.
				$res = $moblc_db_queries->moblc_remove_broken_pages( $moblc_page_id );
				return ( $res >= 0 ? 'SUCCESS' : 'ERROR' );
			}

		}
		/**
		 * Function for checking scanning time.
		 *
		 * @param int $s_time scan time.
		 * @return bool
		 */
		public function moblc_check_time( $s_time ) {
			$max_time = ini_get( 'max_execution_time' );
			if ( ( $max_time - ( time() - $s_time ) ) <= 10 ) {
				return true;
			}

			return false;
		}
		/**
		 * Function for ignore pages.
		 *
		 * @return void
		 */
		public function moblc_ignore_page() {

			global $moblc_db_queries, $wpdb;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-ignore-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				$moblc_link    = isset( $_POST['link_text'] ) ? esc_url_raw( wp_unslash( $_POST['link_text'] ) ) : null;
				$moblc_page_id = isset( $_POST['link_id'] ) ? sanitize_text_field( wp_unslash( $_POST['link_id'] ) ) : null;

				$moblc_db_queries->moblc_remove_link( $moblc_page_id );

				wp_send_json( 'SUCCESS' );
			}

		}
		/**
		 * Function for updating status.
		 *
		 * @return void
		 */
		public function moblc_update_status() {
			global $moblc_db_queries, $wpdb;
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc_update_status-nonce' ) ) {
				$error = new WP_Error();
				wp_send_json( $error );
			} else {
				$moblc_ids = ( isset( $_POST['moblc_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['moblc_ids'] ) ) : '' );
				$result    = $moblc_db_queries->moblc_check_status( $moblc_ids );
				wp_send_json( $result );
			}

		}
	}
	new MOBLC_Ajax();
}


