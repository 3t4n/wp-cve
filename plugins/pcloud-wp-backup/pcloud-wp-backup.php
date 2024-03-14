<?php
/**
 * Pcloud WP Backup plugin
 *
 * @package pcloud_wp_backup
 * @author pCloud
 *
 * Plugin Name: pCloud WP Backup
 * Plugin URI: https://www.pcloud.com
 * Summary: pCloud WP Backup plugin
 * Description: pCloud WP Backup has been created to make instant backups of your blog and its data, regularly.
 * Version: 1.4.0
 * Author: pCloud
 * URI: https://www.pcloud.com
 * License: Copyright 2013-2023 - pCloud
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

use Pcloud\Classes\wp2pclouddbbackup;
use Pcloud\Classes\wp2pclouddebugger;
use Pcloud\Classes\wp2pcloudfilebackup;
use Pcloud\Classes\wp2pcloudfilerestore;
use Pcloud\Classes\wp2pcloudfuncs;
use Pcloud\Classes\wp2pcloudlogger;

require plugin_dir_path( __FILE__ ) . 'Pcloud/class-autoloader.php';

if ( ! defined( 'PCLOUD_API_LOCATIONID' ) ) {
	define( 'PCLOUD_API_LOCATIONID', 'wp2pcl_api_locationid' );
}
if ( ! defined( 'PCLOUD_AUTH_KEY' ) ) {
	define( 'PCLOUD_AUTH_KEY', 'wp2pcl_auth' );
}
if ( ! defined( 'PCLOUD_AUTH_MAIL' ) ) {
	define( 'PCLOUD_AUTH_MAIL', 'wp2pcl_auth_mail' );
}
if ( ! defined( 'PCLOUD_SCHDATA_KEY' ) ) {
	define( 'PCLOUD_SCHDATA_KEY', 'wp2pcl_schdata' );
}
if ( ! defined( 'PCLOUD_SCHDATA_INCLUDE_MYSQL' ) ) {
	define( 'PCLOUD_SCHDATA_INCLUDE_MYSQL', 'wp2pcl_include_mysql' );
}
if ( ! defined( 'PCLOUD_OPERATION' ) ) {
	define( 'PCLOUD_OPERATION', 'wp2pcl_operation' );
}
if ( ! defined( 'PCLOUD_HAS_ACTIVITY' ) ) {
	define( 'PCLOUD_HAS_ACTIVITY', 'wp2pcl_has_activity' );
}
if ( ! defined( 'PCLOUD_LOG' ) ) {
	define( 'PCLOUD_LOG', 'wp2pcl_logs' );
}
if ( ! defined( 'PCLOUD_DBG_LOG' ) ) {
	define( 'PCLOUD_DBG_LOG', 'wp2pcl_dbg_logs' );
}
if ( ! defined( 'PCLOUD_LAST_BACKUPDT' ) ) {
	define( 'PCLOUD_LAST_BACKUPDT', 'wp2pcl_last_backupdt' );
}
if ( ! defined( 'PCLOUD_QUOTA' ) ) {
	define( 'PCLOUD_QUOTA', 'wp2pcl_quota' );
}
if ( ! defined( 'PCLOUD_USEDQUOTA' ) ) {
	define( 'PCLOUD_USEDQUOTA', 'wp2pcl_usedquota' );
}
if ( ! defined( 'PCLOUD_MAX_NUM_FAILURES_NAME' ) ) {
	define( 'PCLOUD_MAX_NUM_FAILURES_NAME', 'wp2pcl_max_num_failures' );
}
if ( ! defined( 'PCLOUD_ASYNC_UPDATE_VAL' ) ) {
	define( 'PCLOUD_ASYNC_UPDATE_VAL', 'wp2pcl_async_upd_item' );
}
if ( ! defined( 'PCLOUD_OAUTH_CLIENT_ID' ) ) {
	define( 'PCLOUD_OAUTH_CLIENT_ID', 'beFbFDM0paj' );
}
if ( ! defined( 'PCLOUD_TEMP_DIR' ) ) {
	$backup_dir = rtrim( WP_CONTENT_DIR, '/' ) . '/pcloud_tmp';
	define( 'PCLOUD_TEMP_DIR', $backup_dir );
}
if ( ! defined( 'PCLOUD_DEBUG' ) ) {
	define( 'PCLOUD_DEBUG', false );
}

// The maximum number of failures allowed.
$max_num_failures = 1200;

/**
 * This hack will increase the wp_remote_request timeout, which otherwise dies after 5-10sec.
 *
 * @return int
 * @noinspection PhpUnused
 */
function pcl_wb_bkup_timeout_extend(): int {
	return 180;
}

add_filter( 'http_request_timeout', 'pcl_wb_bkup_timeout_extend' );

$sitename = preg_replace( '/http(s?):\/\//', '', get_bloginfo( 'url' ) );
$sitename = str_replace( '.', '_', $sitename );

define( 'PCLOUD_BACKUP_DIR', 'WORDPRESS_BACKUPS/' . strtoupper( $sitename ) );

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$plugin_path_base = __DIR__;

$num_failures = wp2pcloudfuncs::get_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME );
if ( empty( $num_failures ) ) {
	wp2pcloudfuncs::set_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME, $max_num_failures );
}

/**
 * This function creates a menu item
 *
 * @return void
 * @noinspection PhpUnused
 */
function backup_to_pcloud_admin_menu() {
	$img_url = rtrim( plugins_url( '/assets/img/logo_16.png', __FILE__ ) );
	add_menu_page( 'WP2pCloud', 'pCloud Backup', 'administrator', 'wp2pcloud_settings', 'wp2pcloud_display_settings', $img_url );
}

/**
 * This function handles all ajax request sent back to the plugin
 *
 * @throws Exception Standart exception will be thrown.
 * @noinspection PhpUnused
 */
function wp2pcl_ajax_process_request() {

	global $sitename;

	$result = array(
		'status'  => 1, // 0: OK, 1+: error
		'message' => '',
	);

	$m = isset( $_GET['method'] ) ? sanitize_text_field( wp_unslash( $_GET['method'] ) ) : false;

	$dbg_mode = false;
	if ( isset( $_GET['dbg'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['dbg'] ) ) ) {
		$dbg_mode = true;
	}

	if ( 'unlink_acc' === $m ) {

		wp2pcloudfuncs::set_storred_val( PCLOUD_AUTH_KEY, '' );
		wp2pcloudfuncs::set_storred_val( PCLOUD_AUTH_MAIL, '' );
		wp2pcloudfuncs::set_storred_val( PCLOUD_QUOTA, '1' );
		wp2pcloudfuncs::set_storred_val( PCLOUD_USEDQUOTA, '1' );
		wp2pcloudfuncs::set_storred_val( PCLOUD_API_LOCATIONID, '1' );
		wp2pcloudfuncs::set_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL, '1' );

		$result['status'] = 0;

	} elseif ( 'set_with_mysql' === $m ) {

		if ( ! isset( $_POST['wp2pcl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['wp2pcl_nonce'] ) ) ) {
			$result['status']   = 15;
			$result['msg']      = '<p>Failed to validate the request!</p>';
			$result['sitename'] = $sitename;

			echo wp_json_encode( $result );

			return;
		}

		$withmysql = isset( $_POST['wp2pcl_withmysql'] ) ? '1' : '0';

		wp2pcloudfuncs::set_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL, $withmysql );

		$result['status'] = 0;

	} elseif ( 'userinfo' === $m ) {

		if ( ! isset( $_GET['wp2pcl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['wp2pcl_nonce'] ) ) ) {
			$result['status']   = 15;
			$result['msg']      = '<p>Failed to validate the request!</p>';
			$result['sitename'] = $sitename;

			echo wp_json_encode( $result );

			return;
		}

		$result['status'] = 0;

		$authkey  = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
		$apiep    = rtrim( 'https://' . wp2pcloudfuncs::get_api_ep_hostname() );
		$url      = $apiep . '/userinfo?access_token=' . $authkey;
		$response = wp_remote_get( $url );
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$response_body_list = json_decode( $response['body'] );
			if ( property_exists( $response_body_list, 'result' ) ) {
				$resp_result = intval( $response_body_list->result );
				if ( 0 === $resp_result ) {
					$result['data'] = $response_body_list;
				}
			}
		}
	} elseif ( 'listfolder' === $m ) {

		if ( ! isset( $_GET['wp2pcl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['wp2pcl_nonce'] ) ) ) {
			$result['status']   = 15;
			$result['msg']      = '<p>Failed to validate the request!</p>';
			$result['sitename'] = $sitename;

			echo wp_json_encode( $result );

			return;
		}

		$result['status']   = 0;
		$result['contents'] = array();

		$authkey  = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
		$apiep    = rtrim( 'https://' . wp2pcloudfuncs::get_api_ep_hostname() );
		$url      = $apiep . '/listfolder?path=/' . PCLOUD_BACKUP_DIR . '&access_token=' . $authkey;
		$response = wp_remote_get( $url );
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$response_body_list = json_decode( $response['body'] );
			if ( property_exists( $response_body_list, 'result' ) ) {
				$resp_result = intval( $response_body_list->result );
				if ( ( 0 === $resp_result ) && property_exists( $response_body_list, 'metadata' ) && property_exists( $response_body_list->metadata, 'contents' ) ) {
					$result['folderid'] = $response_body_list->metadata->folderid;
					$result['contents'] = $response_body_list->metadata->contents;
				} else {
					pcl_verify_directory_structure();
				}
			}
		} else {
			$result['status'] = 65;
			$result['msg']    = '<p>Failed to get backup files list!</p>';
		}
	} elseif ( 'set_schedule' === $m ) {

		if ( ! isset( $_POST['wp2pcl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['wp2pcl_nonce'] ) ) ) {
			$result['status']   = 15;
			$result['msg']      = '<p>Failed to validate the request!</p>';
			$result['sitename'] = $sitename;

			echo wp_json_encode( $result );

			return;
		}

		$freq = isset( $_POST['freq'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['freq'] ) ) ) : 't';

		if ( 't' === $freq ) {

			wp2pclouddebugger::log( 'Test initiated !' );

			$freq = 'daily';

			wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, '0' );

			wp_clear_scheduled_hook( 'init_autobackup' );

			wp2pcl_run_pcloud_backup_hook();
		}

		wp2pcloudfuncs::set_storred_val( PCLOUD_SCHDATA_KEY, $freq );

		$result['status'] = 0;

	} elseif ( 'restore_archive' === $m ) {

		wp2pclouddebugger::generate_new( 'restore_archive at: ' . gmdate( 'Y-m-d H:i:s' ) );

		$memlimit    = ( defined( 'WP_MEMORY_LIMIT' ) ? WP_MEMORY_LIMIT : '---' );
		$memlimitini = ini_get( 'memory_limit' );
		wp2pclouddebugger::log( 'Memory limits: ' . $memlimit . ' / ' . $memlimitini );

		if ( ! isset( $_POST['wp2pcl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['wp2pcl_nonce'] ) ) ) {

			$result['status']   = 15;
			$result['msg']      = '<p>Failed to validate the request!</p>';
			$result['sitename'] = $sitename;

			echo wp_json_encode( $result );

			return;
		}

		wp2pcloudfuncs::set_execution_limits();

		wp2pcloudfuncs::set_storred_val( PCLOUD_HAS_ACTIVITY, '1' );

		wp2pcloudlogger::generate_new( "<span class='pcl_transl' data-i10nk='start_restore_at'>Start restore at</span> " . gmdate( 'Y-m-d H:i:s' ) );
		wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='prep_dwl_file_wait'>Preparing Download file request, please wait...</span>" );

		$file_id = isset( $_POST['file_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['file_id'] ) ) ) : 0;
		$size    = isset( $_POST['size'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['size'] ) ) ) : 0;

		$doc_root_arr = explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) );
		array_pop( $doc_root_arr );
		array_pop( $doc_root_arr );
		array_pop( $doc_root_arr );
		$doc_root = implode( DIRECTORY_SEPARATOR, $doc_root_arr );
		$archive  = rtrim( $doc_root, '/' ) . '/restore_' . time() . '.zip';

		if ( $file_id > 0 ) {

			$authkey = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
			$apiep   = rtrim( 'https://' . wp2pcloudfuncs::get_api_ep_hostname() );

			$url = $apiep . '/getfilelink?fileid=' . $file_id . '&access_token=' . $authkey;

			$response = wp_remote_get( $url );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$r = json_decode( $response['body'] );
				if ( intval( $r->result ) === 0 ) {
					$url = 'https://' . reset( $r->hosts ) . $r->path;
				}
			} else {
				$result['status'] = 75;
				$result['msg']    = '<p>Failed to get backup file!</p>';
			}

			$op_data = array(
				'operation' => 'download',
				'state'     => 'init',
				'mode'      => 'manual',
				'file_id'   => $file_id,
				'size'      => $size,
				'dwlurl'    => $url,
				'archive'   => $archive,
				'offset'    => 0,
			);

			wp2pcloudfuncs::set_operation( $op_data );

		} else {

			$result['status'] = 80;
			$result['msg']    = '<p>File ID not provided!</p>';

		}
	} elseif ( 'get_log' === $m ) {

		$operation = wp2pcloudfuncs::get_operation();

		if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {

			$path = trim( $operation['write_filename'] );

			$rawsize = 0;
			if ( is_file( $path ) ) {
				$rawsize = filesize( $path );
			}
			if ( is_bool( $rawsize ) ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='failed_to_get_bk_filesz'>ERROR: failed to get the size of backup file:</span> " . $rawsize );

				wp2pcloudfuncs::set_operation();
				wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() );
				wp2pcloudfuncs::set_storred_val( PCLOUD_HAS_ACTIVITY, '0' );

				wp2pclouddebugger::log( 'ERROR: failed to get the size of backup file: ' . $rawsize );
				wp2pclouddebugger::log( 'UPLOAD COMPLETED with errors, file issue! [ ' . $path . ' ]' );

			} else {

				$size = abs( $rawsize );

				$result['offset']    = $operation['offset'];
				$result['size']      = $size;
				$result['sizefancy'] = '~' . round( ( $size / 1024 / 1024 ), 2 ) . ' MB';

				$result['perc'] = 0;
				if ( $size > 0 ) {
					$result['perc'] = round( abs( $result['offset'] / ( $size / 100 ) ), 2 );
				}
			}
		} else {
			$proc                = wp2pcl_event_processor();
			$result['operation'] = $operation;
			$result              = $proc['result'];
		}

		$result['hasactivity'] = wp2pcloudfuncs::get_storred_val( PCLOUD_HAS_ACTIVITY, '0' );

		if ( $dbg_mode ) {
			$result['log'] = wp2pclouddebugger::read_last_log( false );
		} else {
			$result['log'] = wp2pcloudlogger::read_last_log( false );
		}

		$quota     = wp2pcloudfuncs::get_storred_val( PCLOUD_QUOTA, '1' );
		$usedquota = wp2pcloudfuncs::get_storred_val( PCLOUD_USEDQUOTA, '1' );

		if ( $quota > 0 && $usedquota > 0 ) {
			$perc                = round( ( $usedquota / ( $quota / 100 ) ), 2 );
			$result['quotaperc'] = $perc;
		}

		if ( isset( $operation['mode'] ) && 'nothing' !== $operation['mode'] ) {
			$result['operation'] = $operation;
		}

		// If strategy - auto, remove the progress bar!
		if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {
			$result['percdbg'] = $result['perc'];
			unset( $result['perc'] );
		}

		$result['memlimit']    = ( defined( 'WP_MEMORY_LIMIT' ) ? WP_MEMORY_LIMIT : '---' );
		$result['memlimitini'] = ini_get( 'memory_limit' );
		$result['failures']    = $operation['failures'] ?? 0;
		$result['maxfailures'] = wp2pcloudfuncs::get_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME );

	} elseif ( 'check_can_restore' === $m ) {

		$pl_dir_arr = dirname( __FILE__ );

		if ( ! is_writable( $pl_dir_arr . '/' ) ) {
			$result['status'] = 80;
			$result['msg']    = '<p>Path ' . $pl_dir_arr . '/ is not writable!</p>';
		} elseif ( ! is_writable( sys_get_temp_dir() ) ) {
			$result['status'] = 82;
			$result['msg']    = '<p>Path ' . sys_get_temp_dir() . ' is not writable!</p>';
		} else {
			$result['status'] = 0;
		}
	} elseif ( 'start_backup' === $m ) {

		wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() );
		wp2pcloudfuncs::set_storred_val( PCLOUD_HAS_ACTIVITY, '1' );

		wp2pclouddebugger::generate_new( 'start_backup at: ' . gmdate( 'Y-m-d H:i:s' ) );

		$memlimit    = ( defined( 'WP_MEMORY_LIMIT' ) ? WP_MEMORY_LIMIT : '---' );
		$memlimitini = ini_get( 'memory_limit' );

		wp2pclouddebugger::log( 'Memory limits: ' . $memlimit . ' / ' . $memlimitini );

		wp2pcl_perform_manual_backup();

		echo '{}';
		die();

	}

	$result['sitename'] = $sitename;

	echo wp_json_encode( $result );
	die();
}


/**
 * This function handles the processes required by the plugin
 *
 * @throws Exception Standart exception will be thrown.
 */
function wp2pcl_event_processor(): array {

	global $plugin_path_base;

	$result = array(
		'status'  => 1, // 0: OK, 1+: error
		'message' => '',
	);

	$operation = wp2pcloudfuncs::get_operation();

	if ( 'upload' === $operation['operation'] ) {
		wp2pclouddebugger::log( 'uploading' );
	} else {
		if ( 'nothing' !== $operation['operation'] ) {
			wp2pclouddebugger::log( 'wp2pcl_event_processor() - op:' . $operation['operation'] );
		}
	}

	if ( isset( $operation['operation'] ) ) {

		if ( isset( $operation['cleanat'] ) ) {

			unset( $operation['perc'] );

			if ( time() > $operation['cleanat'] ) {
				wp2pcloudlogger::clear_log();
				wp2pcloudfuncs::set_storred_val( PCLOUD_HAS_ACTIVITY, '0' );
			}
		} else {

			if ( 'upload' === $operation['operation'] || 'download' === $operation['operation'] ) {
				wp2pcloudfuncs::set_execution_limits();
			}

			if ( 'upload' === $operation['operation'] && 'ready_to_push' === $operation['state'] ) {

				wp2pclouddebugger::log( 'Upload: ready_to_push!<br/>' );

				$operation['state'] = 'uploading_chunks';
				wp2pcloudfuncs::set_operation( $operation );

			} elseif ( 'upload' === $operation['operation'] && 'preparing' === $operation['state'] ) {

				$operation['failures'] += 1;

				wp2pcloudfuncs::set_operation( $operation );

				$max_num_failures = wp2pcloudfuncs::get_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME );

				if ( $operation['failures'] > intval( $max_num_failures ) ) {

					wp2pclouddebugger::log( '== ERROR == Too many failures ( ' . $operation['failures'] . ' / ' . $max_num_failures . ' ), leaving.. !' );

					wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='too_many_failures'>ERROR: Too many failures, try to disable/enable the plugin !</span>" );
					wp2pcloudfuncs::set_operation();

					if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {
						wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() - 5 );
					}
				}
			} elseif ( 'upload' === $operation['operation'] && 'uploading_chunks' === $operation['state'] ) {

				$path      = trim( $operation['write_filename'] );
				$folder_id = intval( $operation['folder_id'] );
				$upload_id = intval( $operation['upload_id'] );
				$offset    = intval( $operation['offset'] );

				if ( ! file_exists( $path ) ) {

					wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='err_arch_file_nf'>ERROR: Archive file not found!</span> [ " . $path . ']' );
					wp2pcloudfuncs::set_operation();

					$result['newoffset'] = $offset + 99999;

					if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {
						wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() - 5 );
					}
				} else {

					$size = abs( filesize( $path ) );

					$result['offset']    = $offset;
					$result['size']      = $size;
					$result['sizefancy'] = '~' . round( ( $size / 1024 / 1024 ), 2 ) . ' MB';

					if ( 'OK' === $operation['chunkstate'] ) {

						$operation['chunkstate'] = 'uploading';

						wp2pcloudfuncs::set_operation( $operation );

						$file_op = new wp2pcloudfilebackup( $plugin_path_base );

						if ( isset( $operation['mode'] ) && 'manual' === $operation['mode'] ) {
							$newoffset = $file_op->upload_chunk( $path, $folder_id, $upload_id, $offset, $operation['failures'] );
						} else {
							$time_limit = ini_get( 'max_execution_time' );
							if ( ! is_bool( $time_limit ) && intval( $time_limit ) === 0 ) {
								$newoffset = $file_op->upload( $path, $folder_id, $upload_id, $offset );
							} else {
								$newoffset = $file_op->upload_chunk( $path, $folder_id, $upload_id, $offset, $operation['failures'] );
							}
						}

						$result['newoffset']     = $newoffset;
						$operation['chunkstate'] = 'OK';

					} else {
						$newoffset           = $offset;
						$result['newoffset'] = $offset;
					}

					if ( $newoffset <= $offset ) {
						if ( ! isset( $operation['failures'] ) ) {
							$operation['failures'] = 1;
						}
						$operation['failures'] ++;
					} else {
						$operation['failures'] = 0;
					}

					if ( $newoffset > 0 ) {

						$operation['offset'] = $newoffset;
						$result['perc']      = 0;

						if ( $size > 0 ) {
							$result['perc'] = round( abs( $newoffset / ( $size / 100 ) ), 2 );
						}
					}

					wp2pcloudfuncs::set_operation( $operation );

					$num_failures = wp2pcloudfuncs::get_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME );

					if ( $operation['failures'] > intval( $num_failures ) ) {

						wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='too_many_failures'>ERROR: Too many failures, try to disable/enable the plugin !</span>" );
						wp2pcloudfuncs::set_operation();

						if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {

							wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() );

							wp2pclouddebugger::log( 'UPLOAD COMPLETED, scheduler should be OFF!' );
						}
					} else {

						if ( $newoffset > $size ) {

							wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='upload_completed'>Upload completed!</span>" );
							wp2pcloudfuncs::set_operation();

							if ( isset( $operation['mode'] ) && 'auto' === $operation['mode'] ) {

								wp2pcloudfuncs::set_storred_val( PCLOUD_LAST_BACKUPDT, time() );

								wp2pclouddebugger::log( 'UPLOAD COMPLETED, scheduler should be OFF!' );
							}
						}
					}
				}
			}

			if ( 'download' === $operation['operation'] && 'init' === $operation['state'] ) {

				$operation['state'] = 'download_chunks';
				wp2pcloudfuncs::set_operation( $operation );

			} elseif ( 'download' === $operation['operation'] && 'extract' === $operation['state'] ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='start_extr_file_folders'>Start extracting files and folders, please wait...</span>" );

				$file_op = new wp2pcloudfilerestore();
				$file_op->extract( $operation['archive'] );

				$operation['state'] = 'restoredb';
				wp2pcloudfuncs::set_operation( $operation );

			} elseif ( 'download' === $operation['operation'] && 'restoredb' === $operation['state'] ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='start_extr_db'>Start reconstructing the database, please wait...</span>" );

				$file_op = new wp2pcloudfilerestore();
				$file_op->restore_db();

				$operation['state'] = 'restorefiles';
				wp2pcloudfuncs::set_operation( $operation );

			} elseif ( 'download' === $operation['operation'] && 'restorefiles' === $operation['state'] ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='start_extr_db'>Start reconstructing the files, please wait...</span>" );

				$home_path = get_home_path();

				$file_op = new wp2pcloudfilerestore();
				$file_op->restore_files( PCLOUD_TEMP_DIR . '/', $home_path );

				$operation['state'] = 'cleanup';
				wp2pcloudfuncs::set_operation( $operation );

			} elseif ( 'download' === $operation['operation'] && 'cleanup' === $operation['state'] ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='clean_up_pls_wait'>Cleaning up, please wait...</span>" );

				$file_op = new wp2pcloudfilerestore();
				$file_op->remove_files( $operation['archive'] );

				wp2pcloudfuncs::set_operation();

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='bk_restored'>Backup - restored! You can refresh the page now!</span>" );

			} elseif ( 'download' === $operation['operation'] && 'download_chunks' === $operation['state'] ) {

				if ( PCLOUD_DEBUG ) {
					$result['msg'] = 'Download chunks ...!';
				}

				$dwlurl  = trim( $operation['dwlurl'] );
				$size    = intval( $operation['size'] );
				$offset  = intval( $operation['offset'] );
				$archive = trim( $operation['archive'] );

				$result['offset']    = $offset;
				$result['size']      = $size;
				$result['sizefancy'] = '~' . round( ( $size / 1024 / 1024 ), 2 ) . ' MB';

				$file_op             = new wp2pcloudfilerestore();
				$newoffset           = $file_op->download_chunk_curl( $dwlurl, $offset, $archive );
				$result['newoffset'] = $newoffset;

				if ( $newoffset > 0 ) {

					$operation['offset'] = $newoffset;
					wp2pcloudfuncs::set_operation( $operation );

					$result['perc'] = 0;
					if ( $size > 0 ) {
						$result['perc'] = round( abs( $newoffset / ( $size / 100 ) ), 2 );
					}
				}

				if ( $newoffset > $size ) {

					wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='dwl_completed'>Download completed!</span>" );
					wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='unzip_pls_wait'>Unzipping the archive, please wait:</span>" );

					$operation['state'] = 'extract';
					wp2pcloudfuncs::set_operation( $operation );
				}
			}

			if ( isset( $result['perc'] ) && $result['perc'] > 100 ) {
				$result['perc'] = 100;
			}
		}
	}

	return array(
		'operation' => $operation,
		'result'    => $result,
	);
}

/**
 * Start manual backup procedure
 *
 * @throws Exception Standart exception will be thrown.
 */
function wp2pcl_perform_manual_backup() {

	global $plugin_path_base;

	wp2pcloudfuncs::set_execution_limits();

	wp2pcloudlogger::generate_new( "<span class='pcl_transl' data-i10nk='start_backup_at'>Start backup at</span> " . gmdate( 'Y-m-d H:i:s' ) );

	$f = new wp2pcloudfilebackup( $plugin_path_base );

	$wp2pcl_withmysql = wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL );
	if ( ! empty( $wp2pcl_withmysql ) && 1 === intval( $wp2pcl_withmysql ) ) {
		wp2pclouddebugger::log( 'Database backup will start now!' );
		$b    = new wp2pclouddbbackup();
		$file = $b->start();
		$f->set_mysql_backup_filename( $file );

		wp2pclouddebugger::log( 'Database backup - ready!' );
	}

	wp2pclouddebugger::log( 'File backup will start now!' );

	$f->start();
}


/**
 * This function performce auto-backup
 *
 * @throws Exception Standart exception will be thrown.
 */
function wp2pcl_perform_auto_backup() {

	global $plugin_path_base;

	$operation = wp2pcloudfuncs::get_operation();

	if ( 'init' === $operation['state'] ) {

		pcl_verify_directory_structure();

		wp2pclouddebugger::log( 'wp2pcl_perform_auto_backup() - op:init !' );

		wp2pcloudlogger::generate_new( "<span class='pcl_transl' data-i10nk='start_auto_backup_at'>Start auto backup at</span> " . gmdate( 'Y-m-d H:i:s' ) );

		$f = new wp2pcloudfilebackup( $plugin_path_base );

		$wp2pcl_withmysql = wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL );
		if ( ! empty( $wp2pcl_withmysql ) && 1 === intval( $wp2pcl_withmysql ) ) {
			$b    = new wp2pclouddbbackup();
			$file = $b->start();
			$f->set_mysql_backup_filename( $file );
		}

		$f->start( 'auto' );

		wp2pcloudfuncs::set_storred_val( PCLOUD_HAS_ACTIVITY, '1' );

	} else {

		wp2pclouddebugger::log( 'wp2pcl_perform_auto_backup() - op:processor !' );

		wp2pcl_event_processor();

	}
}


/**
 * Auto-backup hook function
 *
 * @throws Exception Standart exception will be thrown.
 */
function wp2pcl_run_pcloud_backup_hook() {

	$lastbackupdt_tm = intval( wp2pcloudfuncs::get_storred_val( PCLOUD_LAST_BACKUPDT ) );

	$freq = wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_KEY );

	$rejected = false;

	if ( $lastbackupdt_tm > 0 ) {

		if ( '2_minute' === $freq ) {
			if ( $lastbackupdt_tm > ( time() - 120 ) ) {
				$rejected = true;
			}
		} elseif ( '1_hour' === $freq ) {
			if ( $lastbackupdt_tm > ( time() - 3600 ) ) {
				$rejected = true;
			}
		} elseif ( '4_hours' === $freq ) {
			if ( $lastbackupdt_tm > ( time() - ( 3600 * 4 ) ) ) {
				$rejected = true;
			}
		} elseif ( 'daily' === $freq ) {
			if ( $lastbackupdt_tm > ( time() - 86400 ) ) {
				$rejected = true;
			}
		} elseif ( 'weekly' === $freq ) {
			if ( $lastbackupdt_tm > strtotime( '-1 week' ) ) {
				$rejected = true;
			}
		} elseif ( 'monthly' === $freq ) {
			if ( $lastbackupdt_tm > strtotime( '-1 month' ) ) {
				$rejected = true;
			}
		} else { // Unexpected value for $freq. or none, skipping.
			$rejected = true;
		}
	}

	$operation = wp2pcloudfuncs::get_operation();

	if ( $rejected ) {

		if ( isset( $operation['operation'] ) && ( 'upload' === $operation['operation'] ) && ( 'auto' === $operation['mode'] ) ) {
			wp2pcloudfuncs::set_operation();
		}

		return;
	}

	if ( isset( $operation['operation'] ) && ( 'nothing' === $operation['operation'] ) ) {

		wp2pclouddebugger::log( 'wp2pcl_run_pcloud_backup_hook() - op:nothing, going to init !' );

		$op_data = array(
			'operation'      => 'upload',
			'state'          => 'init',
			'mode'           => 'auto',
			'status'         => '',
			'chunkstate'     => 'OK',
			'write_filename' => '',
			'failures'       => 0,
			'folder_id'      => 0,
			'offset'         => 0,
		);

		$json_data = wp_json_encode( $op_data );

		wp2pcloudfuncs::set_storred_val( 'wp2pcl_operation', $json_data );

		if ( ! wp_next_scheduled( 'init_autobackup' ) ) { // This will always be false.
			wp_schedule_event( time(), '10_sec', 'init_autobackup', array( false ) );
		}
	} else {

		wp2pclouddebugger::log( 'wp2pcl_run_pcloud_backup_hook() - uploading... ' );

		wp2pcl_perform_auto_backup();
	}
}

/**
 * This function calls the settings page file and loads some JS and CSS files
 *
 * @throws Exception Standart exception will be thrown.
 * @noinspection PhpUnused
 */
function wp2pcloud_display_settings() {

	if ( ! extension_loaded( 'zip' ) ) {
		print( '<h2 style="color: red">PHP ZIP extension not loaded</h2><small>Please, contact the server administrator!</small>' );
		return;
	}

	$do         = '';
	$auth_key   = '';
	$locationid = 1;

	if ( isset( $_GET['do'] ) ) { // phpcs:ignore
		$do = sanitize_text_field( wp_unslash( $_GET['do'] ) ); // phpcs:ignore
	}
	if ( isset( $_GET['access_token'] ) ) { // phpcs:ignore
		$auth_key = trim( sanitize_text_field( wp_unslash( $_GET['access_token'] ) ) ); // phpcs:ignore
	}
	if ( isset( $_GET['locationid'] ) ) { // phpcs:ignore
		$locationid = intval( sanitize_key( wp_unslash( $_GET['locationid'] ) ) ); // phpcs:ignore
	}

	if ( ( 'pcloud_auth' === $do ) && ! empty( $auth_key ) ) {

		if ( $locationid > 0 && $locationid < 100 ) {
			wp2pcloudfuncs::set_storred_val( PCLOUD_API_LOCATIONID, $locationid );
			$result['status'] = 0;
		}

		wp2pcloudfuncs::set_storred_val( PCLOUD_AUTH_KEY, $auth_key );

		pcl_verify_directory_structure();

		print '<h2 style="color: green;text-align: center" class="wp2pcloud-login-succcess">You are successfully logged in!</h2>';

	}

	$static_files_ver = '1.0.20';

	wp_enqueue_script( 'wp2pcl-scr', plugins_url( '/assets/js/wp2pcl.js', __FILE__ ), array(), $static_files_ver, true );
	wp_enqueue_style( 'wpb2pcloud', plugins_url( '/assets/css/wpb2pcloud.css', __FILE__ ), array(), $static_files_ver );

	$auth_key = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );

	$data = array(
		'pcloud_auth'       => $auth_key,
		'blog_name'         => get_bloginfo( 'name' ),
		'blog_url'          => get_bloginfo( 'url' ),
		'archive_icon'      => plugins_url( '/assets/img/zip.png', __FILE__ ),
		'api_hostname'      => wp2pcloudfuncs::get_api_ep_hostname(),
		'PCLOUD_BACKUP_DIR' => PCLOUD_BACKUP_DIR,
	);

	wp_localize_script( 'wp2pcl-scr', 'php_data', $data );

	$plugin_path = plugins_url( '/', __FILE__ );

	include 'views/wp2pcl-config.php';
}

/**
 * This function will be called after the plugins is installed
 *
 * @return void
 * @noinspection PhpUnused
 */
function wp2pcl_install() {

	global $max_num_failures;

	wp2pcloudfuncs::get_storred_val( PCLOUD_API_LOCATIONID, '1' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
	wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_MAIL );
	wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_KEY, 'daily' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL, '1' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_OPERATION );
	wp2pcloudfuncs::get_storred_val( PCLOUD_HAS_ACTIVITY, '0' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_LOG );
	wp2pcloudfuncs::get_storred_val( PCLOUD_DBG_LOG );
	wp2pcloudfuncs::get_storred_val( PCLOUD_LAST_BACKUPDT, strval( time() ) );
	wp2pcloudfuncs::get_storred_val( PCLOUD_QUOTA, '1' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_USEDQUOTA, '1' );
	wp2pcloudfuncs::get_storred_val( PCLOUD_MAX_NUM_FAILURES_NAME, strval( $max_num_failures ) );
	wp2pcloudfuncs::get_storred_val( PCLOUD_ASYNC_UPDATE_VAL );
	wp2pcloudfuncs::get_storred_val( PCLOUD_OAUTH_CLIENT_ID );
	wp2pcloudfuncs::get_storred_val( PCLOUD_TEMP_DIR );

	add_filter(
		'cron_schedules',
		function ( $schedules ) {
			$schedules['10_sec']   = array(
				'interval' => 10,
				'display'  => __( '10 seconds' ),
			);
			$schedules['2_minute'] = array(
				'interval' => 120,
				'display'  => __( '2 minute' ),
			);
			$schedules['1_hour']   = array(
				'interval' => 3600,
				'display'  => __( '1 hour' ),
			);
			$schedules['4_hours']  = array(
				'interval' => 3600 * 4,
				'display'  => __( '4 hours' ),
			);

			return $schedules;
		}
	);

	wp_schedule_event( time(), '2_minute', 'init_autobackup', array( false ) );
}

/**
 * Cleaning up after uninstall of the plugin
 *
 * @return void
 * @noinspection PhpUnused
 */
function wp2pcl_uninstall() {

	delete_option( PCLOUD_API_LOCATIONID );
	delete_option( PCLOUD_AUTH_KEY );
	delete_option( PCLOUD_AUTH_MAIL );
	delete_option( PCLOUD_SCHDATA_KEY );
	delete_option( PCLOUD_SCHDATA_INCLUDE_MYSQL );
	delete_option( PCLOUD_OPERATION );
	delete_option( PCLOUD_HAS_ACTIVITY );
	delete_option( PCLOUD_LOG );
	delete_option( PCLOUD_DBG_LOG );
	delete_option( PCLOUD_LAST_BACKUPDT );
	delete_option( PCLOUD_MAX_NUM_FAILURES_NAME );
	delete_option( PCLOUD_QUOTA );
	delete_option( PCLOUD_USEDQUOTA );
	delete_option( PCLOUD_ASYNC_UPDATE_VAL );
	delete_option( PCLOUD_OAUTH_CLIENT_ID );
	delete_option( PCLOUD_TEMP_DIR );
	wp_clear_scheduled_hook( 'init_autobackup' );
	spl_autoload_unregister( '\Pcloud\Autoloader::loader' );
}

/**
 * This func creates
 *
 * @param array|null $schedules Array of previews schedulles.
 *
 * @return array
 * @noinspection PhpUnused
 */
function backup_to_pcloud_cron_schedules( ?array $schedules ): array {

	$new_schedules = array(
		'30_sec'   => array(
			'interval' => 30,
			'display'  => __( '30 seconds' ),
		),
		'2_minute' => array(
			'interval' => 120,
			'display'  => __( '2 minute' ),
		),
		'1_hour'   => array(
			'interval' => 3600,
			'display'  => __( '1 hour' ),
		),
		'4_hours'  => array(
			'interval' => 3600 * 4,
			'display'  => __( '4 hours' ),
		),
		'daily'    => array(
			'interval' => 86400,
			'display'  => __( 'Daily' ),
		),
		'weekly'   => array(
			'interval' => 604800,
			'display'  => __( 'Weekly' ),
		),
		'monthly'  => array(
			'interval' => 2592000,
			'display'  => __( 'Monthly' ),
		),
	);

	return array_merge( $schedules, $new_schedules );
}

/**
 * Verify that the folder exists on pCloud servers.
 *
 * @return void
 */
function pcl_verify_directory_structure() {

	$authkey = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
	if ( ! is_string( $authkey ) || empty( $authkey ) ) {
		return;
	}

	$hostname = wp2pcloudfuncs::get_api_ep_hostname();
	if ( empty( $hostname ) ) {
		return;
	}

	$apiep    = 'https://' . rtrim( $hostname );
	$url      = $apiep . '/listfolder?path=/' . PCLOUD_BACKUP_DIR . '&access_token=' . $authkey;
	$response = wp_remote_get( $url );
	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		$response_body_list = json_decode( $response['body'] );
		if ( property_exists( $response_body_list, 'result' ) ) {
			$resp_result = intval( $response_body_list->result );
			if ( 2005 === $resp_result ) {

				$backup_directories = explode( '/', PCLOUD_BACKUP_DIR );

				if ( is_array( $backup_directories ) && 0 < count( $backup_directories ) ) {
					$url                       = $apiep . '/createfolder?path=/' . $backup_directories[0] . '&name=' . $backup_directories[0] . '&access_token=' . $authkey;
					$response_main_folder      = wp_remote_get( $url );
					$response_main_folder_body = json_decode( $response_main_folder['body'] );
					if ( property_exists( $response_main_folder_body, 'result' ) && ( 0 === intval( $response_main_folder_body->result ) ) ) {
						$url = $apiep . '/createfolder?path=/' . PCLOUD_BACKUP_DIR . '&name=' . $backup_directories[1] . '&access_token=' . $authkey;
						wp_remote_get( $url );
					}
				}
			}
		}
	}
}

add_filter( 'cron_schedules', 'backup_to_pcloud_cron_schedules' );

if ( ! function_exists( 'wp2pcl_load_scripts' ) ) {

	/**
	 * We are attempting to load main plugin js file
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	function wp2pcl_load_scripts() {
		wp_register_script( 'wp2pcl-wp2pcljs', plugins_url( '/assets/js/wp2pcl.js', __FILE__ ), array(), '1.0.3', true );
		wp_enqueue_script( 'jquery' );
	}
}

register_activation_hook( __FILE__, 'wp2pcl_install' );
register_deactivation_hook( __FILE__, 'wp2pcl_uninstall' );
add_action( 'admin_menu', 'backup_to_pcloud_admin_menu' );
add_action( 'wp_enqueue_scripts', 'wp2pcl_load_scripts' );
add_action( 'init_autobackup', 'wp2pcl_run_pcloud_backup_hook' );
if ( is_admin() ) {
	add_action( 'wp_ajax_pcloudbackup', 'wp2pcl_ajax_process_request' );
}
