<?php
/**
 * It fires on Ajax requests.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

do_action( 'fdp_before_ajax_request' );

add_action( 'wp_ajax_eos_dp_save_settings', 'eos_dp_save_settings' );
// Saves activation/deactivation settings for each post.
function eos_dp_save_settings() {
	eos_dp_check_intentions_and_rights( 'eos_dp_setts' );
	if ( isset( $_POST['eos_dp_setts'] ) && ! empty( $_POST['eos_dp_setts'] ) ) {
		$opts      = $_POST['eos_dp_setts']; // @codingStandardsIgnoreLine.
		$ids2paths = isset( $opts['eos_dp_urls'] ) ? json_decode( stripslashes( sanitize_text_field( str_replace( '%', '', $opts['eos_dp_urls'] ) ) ), true ) : array();
		foreach ( $opts as $post_id => $opt ) {
			if ( false !== strpos( $post_id, 'post_id_' ) ) {
				$post_id = absint( str_replace( 'post_id_', '', $post_id ) );
				if ( $post_id > 0 ) {
					update_post_meta( $post_id, '_eos_deactive_plugins_key', sanitize_text_field( $opt ) );
					if ( in_array( $post_id, array_keys( $ids2paths ) ) ) {
						$post = get_post( $post_id );
						eos_dp_update_url_options( $ids2paths[ $post_id ], $post_id, $opt, $opts['post_type'], $post && is_object( $post ) && isset( $post->post_status ) ? sanitize_key( $post->post_status ) : 'public' );
					}
				}
			}
		}
		if ( isset( $opts['eos_dp_need_custom_url'] ) && $opts['eos_dp_need_custom_url'] ) {
			$eos_dp_need_custom_url = json_decode( stripslashes( $opts['eos_dp_need_custom_url'] ), true );
			$urls                   = array();
			$cu_opts                = get_site_option( 'eos_dp_by_url' );
			if ( ! is_array( $cu_opts ) ) {
				$cu_opts = array();
			}
			$post_types_opts = eos_dp_get_updated_plugins_table();
			$post_type       = $opts['post_type'];
			$post_types_opts = $post_types_opts[ $opts['post_type'] ];
			$ids_locked      = $opts['ids_locked'];
			foreach ( $eos_dp_need_custom_url as $ncu_id => $url ) {
				$urls[ sanitize_key( $ncu_id ) ] = esc_url( $url );
			}
			$cu_opts['need_url'] = $urls;
			eos_dp_update_option( 'eos_dp_by_url', $cu_opts );
		}
		if ( isset( $opts['post_type'] ) ) {
			$post_types_matrix = eos_dp_get_updated_plugins_table();
			if ( empty( $post_types_matrix ) ) {
				$post_types_empty = eos_dp_post_types_empty();
			}
			$post_type = sanitize_key( $opts['post_type'] );

			if ( isset( $post_types_matrix[ $post_type ] ) ) {
				$post_types_matrix_pt = $post_types_matrix[ $post_type ];
				if ( isset( $post_types_matrix_pt[3] ) && is_array( $post_types_matrix_pt[3] ) && ! empty( $post_types_matrix_pt[3] ) ) {
					if ( isset( $opts['ids_locked'] ) ) {
						foreach ( $opts['ids_locked'] as $id_locked ) {
							if ( ! in_array( $id_locked, $post_types_matrix_pt[3] ) ) {
								$post_types_matrix_pt[3] = array_merge( $post_types_matrix_pt[3], array( $id_locked ) );
							}
						}
					}
					if ( isset( $opts['ids_unlocked'] ) ) {
						foreach ( $opts['ids_unlocked'] as $id_unlocked ) {
							if ( in_array( $id_unlocked, $post_types_matrix_pt[3] ) ) {
								$post_types_matrix_pt[3] = array_diff( $post_types_matrix_pt[3], array( $id_unlocked ) );
							}
						}
					}
				} else {
					$post_types_matrix_pt[3]                 = $opts['ids_locked'];
					$post_types_matrix[ $opts['post_type'] ] = $post_types_matrix_pt;
				}
				$post_types_matrix[ $post_type ] = $post_types_matrix_pt;
				eos_dp_update_option( 'eos_post_types_plugins', $post_types_matrix );
			}
		}
		eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
	}
	do_action( 'fdp_singles_saved', $opts );
	echo 1;
	die();
	exit;
}
add_action( 'wp_ajax_eos_dp_save_archives_settings', 'eos_dp_save_archives_settings' );
// Saves activation/deactivation settings for each archive.
function eos_dp_save_archives_settings() {
	eos_dp_check_intentions_and_rights( 'eos_dp_arch_setts' );
	if ( isset( $_POST['archivesUrls'] ) && ! empty( $_POST['archivesUrls'] ) ) {
		foreach ( $_POST['archivesUrls'] as $archive_path => $arr ) { // @codingStandardsIgnoreLine. We sanitize later.
			eos_dp_update_url_options( $archive_path, 'archive', sanitize_text_field( $arr[1] ), sanitize_text_field( $arr[0] ) );
		}
	}
	if ( isset( $_POST['archives'] ) && ! empty( $_POST['archives'] ) ) {
		$archiveSetts = $_POST['archives']; // @codingStandardsIgnoreLine. We sanitize later.
		foreach ( $archiveSetts as $k => $v ) {
			unset( $archiveSetts[ $k ] );
			$kArr = explode( '//', $k );
			if ( isset( $kArr[1] ) ) {
				$k = rtrim( $kArr[1], '/' );
			}
			$k                   = str_replace( '/', '__', $k );
			$permalink_structure = eos_dp_get_option( 'permalink_structure' );
			if ( false !== strpos( $permalink_structure, '%category%' ) && '%postname%' === basename( $permalink_structure ) && '.' === eos_dp_get_option( 'category_base' ) ) {
				$k = str_replace( '__.__', '__', $k );
			}

			$archiveSetts[ sanitize_key( $k ) ] = sanitize_text_field( $v );
		}
		$currentOpts = eos_dp_get_option( 'eos_dp_archives' );
		if ( null !== $currentOpts && ! empty( $currentOpts ) && null !== $archiveSetts && ! empty( $archiveSetts ) ) {
			$archiveSetts = array_merge( $currentOpts, $archiveSetts );
		}
		if ( ! defined( 'FDP_SKIP_DB_FOR_ARCHIVES' ) || ! FDP_SKIP_DB_FOR_ARCHIVES ) {
			$opts    = eos_dp_get_option( 'eos_dp_opts' );
			$skip_db = isset( $opts['filesystem_last_status'] ) && 'ok' === $opts['filesystem_last_status'] && isset( $opts['skip_db_for_archives'] ) && 'true' === $opts['skip_db_for_archives'];
			if ( ! $skip_db ) {
				eos_dp_update_option( 'eos_dp_archives', $archiveSetts );
			} else {
				eos_dp_update_option( 'eos_dp_archives', '' );
			}
		}
		eos_dp_save_option_to_filesystem( 'eos_dp_archives', $archiveSetts );
		eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
	}
	echo 1;
	die();
	exit;
}
add_action( 'wp_ajax_eos_dp_save_post_type_settings', 'eos_dp_save_post_type_settings' );
// Saves activation/deactivation settings for each post type.
function eos_dp_save_post_type_settings() {
	eos_dp_check_intentions_and_rights( 'eos_dp_pt_setts' );
	if ( isset( $_POST['eos_dp_pt_setts'] ) && ! empty( $_POST['eos_dp_pt_setts'] ) ) {
		$opts = eos_dp_get_updated_plugins_table();
		if ( ! is_array( $opts ) ) {
			$opts = array();
		}
		$eos_dp_pt_setts = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['eos_dp_pt_setts'] ) ), true );

		foreach ( $eos_dp_pt_setts as $post_type => $data ) {
			$opts_post_type                     = isset( $opts[ sanitize_key( $post_type ) ] ) ? $opts[ sanitize_key( $post_type ) ] : false;
			$locked_ids                         = $opts_post_type && isset( $opts_post_type[3] ) ? $opts_post_type[3] : array();
			$opts[ sanitize_key( $post_type ) ] = array( absint( $data[0] ), sanitize_text_field( $data[1] ), absint( $data[2] ), $locked_ids );
		}
		eos_dp_update_option( 'eos_post_types_plugins', $opts );
		eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
		echo 1;
		die();
	}
	echo 0;
	die();
}

add_action( 'wp_ajax_eos_dp_save_url_settings', 'eos_dp_save_url_settings' );
// Saves activation/deactivation settings by URL.
function eos_dp_save_url_settings() {
	if ( isset( $_POST['page_slug'] ) && isset( $_POST['setts'] ) && ! empty( $_POST['setts'] ) ) {
		eos_dp_check_intentions_and_rights( sanitize_key( $_POST['page_slug'] ) . '_setts' );
		$rows  = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['setts'] ) ), true );
		$n     = 0;
		$urls  = array();
		$setts = array();
		foreach ( $rows as $arr ) {
			$urls[]      = $arr['url'];
			$setts[ $n ] = array(
				'url'     => sanitize_text_field( $arr['url'] ),
				'plugins' => sanitize_text_field( $arr['plugins'] ),
				'f'       => sanitize_text_field( $arr['f'] ),
			);
			++$n;
		}
		if ( 'eos_dp_admin_url' === $_POST['page_slug'] ) {
			eos_dp_update_fdp_admin_menu(
				eos_dp_user_headers(
					array(
						'timeout'   => 5,
						'sslverify' => false,
					),
					true
				)
			);
		}
		eos_dp_update_option( sanitize_key( str_replace( 'eos_dp_', 'eos_dp_by_', sanitize_text_field( $_POST['page_slug'] ) ) ), $setts );
		if ( isset( $_POST['theme_activation'] ) ) {
			eos_dp_update_option( 'eos_dp_by_rest_api_theme', json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['theme_activation'] ) ), true ) );
		}
		eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
		echo 1;
		die();
	}
	echo 0;
	die();
}
add_action( 'wp_ajax_eos_dp_save_one_col_settings', 'eos_dp_save_one_col_settings' );
// Saves activation/deactivation settings for mobile.
function eos_dp_save_one_col_settings() {
	if ( isset( $_POST['opt_name'] ) ) {
		eos_dp_check_intentions_and_rights( 'eos_dp_' . sanitize_key( $_POST['opt_name'] ) . '_setts' );
		if ( isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ) {
			$opts = array_filter( explode( ',', sanitize_text_field( $_POST['data'] ) ) );
			eos_dp_update_option( 'eos_dp_' . sanitize_key( $_POST['opt_name'] ), $opts );
			echo 1;
			die();
		}
	}
	echo 0;
	die();
}

add_action( 'wp_ajax_eos_dp_save_admin_settings', 'eos_dp_save_admin_settings' );
// Saves admin options.
function eos_dp_save_admin_settings() {
	eos_dp_check_intentions_and_rights( 'eos_dp_admin_setts' );
	$opts = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['eos_dp_admin_setts'] ) ), true );
	eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
	$from_db = eos_dp_get_option( 'eos_dp_admin_setts' );
	foreach ( $opts as $k => $v ) {
		$from_db[ $k ] = $v;
	}
	eos_dp_update_option( 'eos_dp_admin_setts', array_map( 'sanitize_text_field', $from_db ) );
	if ( isset( $_POST['theme_activation'] ) ) {
		eos_dp_update_option( 'eos_dp_admin_theme', json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['theme_activation'] ) ), true ) );
	}
	eos_dp_update_fdp_admin_menu(
		eos_dp_user_headers(
			array(
				'timeout'   => 5,
				'sslverify' => false,
			),
			true
		)
	);
	echo 1;
	die();
	exit;
}
add_action( 'wp_ajax_eos_dp_one_place_save', 'eos_dp_one_place_save' );
// Saves one place options.
function eos_dp_one_place_save() {
	if( ! isset( $_POST['data'] ) || ! isset( $_POST['page_slug'] ) ) {
		echo '0';
		die();
		exit;
	}
	eos_dp_check_intentions_and_rights( sanitize_key( $_POST['page_slug'] ) . '_nonce' );
	eos_dp_update_option( sanitize_key( $_POST['page_slug'] ), stripslashes( str_replace( get_home_url(), '[home]', sanitize_text_field( $_POST['data'] ) ) ) );
	echo 1;
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_save_integration_actions_settings', 'eos_dp_save_integration_actions_settings' );
// Saves integration actions options.
function eos_dp_save_integration_actions_settings() {
	eos_dp_check_intentions_and_rights( 'eos_dp_integration_actions_setts' );
	$opts = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['integration_plugins'] ) ), true );
	eos_dp_update_option( 'eos_dp_new_plugin_activated', false );
	$integration_actions = eos_dp_get_option( 'eos_dp_integration_actions' );
	if ( ! is_array( $integration_actions ) ) {
		$integration_actions = array();
	}
	foreach ( $opts as $action => $value ) {
		$integration_actions[ $action ] = sanitize_text_field( $value );
	}
	eos_dp_update_option( 'eos_dp_integration_actions', $integration_actions );
	if ( isset( $_POST['integration_theme'] ) ) {
		$integration_actions_theme = eos_dp_get_option( 'eos_dp_integretion_actions_theme' );
		if ( ! is_array( $integration_actions_theme ) ) {
			$integration_actions_theme = array();
		}
		$opts = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['integration_theme'] ) ), true );
		foreach ( $opts as $action => $value ) {
			$integration_actions_theme[ $action ] = sanitize_text_field( $value );
		}
		eos_dp_update_option( 'eos_dp_integretion_actions_theme', $integration_actions_theme );
	}
	echo 1;
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_save_firing_order', 'eos_dp_save_firing_order' );
// Saves activation/deactivation settings for search.
function eos_dp_save_firing_order() {
	eos_dp_check_intentions_and_rights( 'eos_dp_firing_order_setts' );
	if ( isset( $_POST['eos_dp_plugins'] ) && ! empty( $_POST['eos_dp_plugins'] ) ) {
		$opts = array_map( 'sanitize_text_field', $_POST['eos_dp_plugins'] );
		$fdp  = EOS_DP_PLUGIN_BASE_NAME;
		if ( ! in_array( $fdp, $opts ) ) {
			array_unshift( $opts, $fdp );
		}
		eos_dp_update_option( 'active_plugins', $opts );
		echo 1;
		die();
	}
	echo 0;
	die();
}
add_action( 'wp_ajax_eos_dp_preview', 'eos_dp_preview' );
// Prepare the transient for the preview.
function eos_dp_preview() {
	$nonceStr = isset( $_POST['post_id'] ) ? 'eos_dp_setts' : 'eos_dp_arch_setts';
	$nonceStr = isset( $_POST['admin_page'] ) ? 'eos_dp_admin_setts' : $nonceStr;
	eos_dp_check_intentions_and_rights( $nonceStr );
	if ( isset( $_POST['plugin_path'] ) && isset( $_POST['microtime'] ) ) {
		$microtime = sanitize_text_field( $_POST['microtime'] );
		if ( isset( $_POST['admin_page'] ) && esc_url( sanitize_text_field( $_POST['admin_page'] ) ) === $_POST['admin_page'] ) {
			$admin_page_key = sanitize_key(
				str_replace(
					'.',
					'-',
					str_replace(
						'admin.php?page=',
						'eos_dp_tlp-',
						str_replace( admin_url(), '', sanitize_text_field( $_POST['admin_page'] ) )
					)
				)
			);
			set_transient( 'fdp_test_' . $admin_page_key . '_' . $microtime, sanitize_text_field( $_POST['plugin_path'] ), 60 );
		}
		if ( isset( $_POST['post_id'] ) && absint( $_POST['post_id'] ) > 0 ) {
			set_transient( 'fdp_test_' . sanitize_key( $_POST['post_id'] ) . '_' . $microtime, sanitize_text_field( $_POST['plugin_path'] ), 60 );
		}
		if ( isset( $_POST['post_type'] ) && '' !== $_POST['post_type'] ) {
			set_transient( 'fdp_test_' . sanitize_key( $_POST['post_type'] ) . '_' . $microtime, sanitize_text_field( $_POST['plugin_path'] ), 60 );
		}
		if ( isset( $_POST['tax'] ) && '' !== $_POST['tax'] ) {
			set_transient( 'fdp_test_' . sanitize_key( $_POST['tax'] ) . '_' . $microtime, sanitize_text_field( $_POST['plugin_path'] ), 60 );
		}
		if ( isset( $_POST['page_speed_insights'] ) && 'true' === $_POST['page_speed_insights'] ) {
			set_transient( 'fdp_testing_nonce_' . sanitize_key( $_POST['post_id'] ), 1000 * ( absint( time() / 1000 ) ), 60 );
		}
	}
	echo 1;
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_check_single_padlock', 'eos_dp_check_single_padlock' );
// Check if post type overrides single settings.
function eos_dp_check_single_padlock() {
	eos_dp_check_intentions_and_rights( 'eos_dp_setts' );
	if ( isset( $_POST['post_type'] ) ) {
			$plugins_table = eos_dp_plugins_table();
			$arr           = $plugins_table[ sanitize_key( $_POST['post_type'] ) ];
			echo ! isset( $arr[0] ) || $arr[0] == '1' ? 'override' : 'not-override';
			die();
			exit;
	}
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_pro_auto_settings', 'eos_dp_pro_auto_settings' );
// Auto settings. It will be deprecated and replaced with eos_dp_auto_settings.
function eos_dp_pro_auto_settings( $post_args = false, $plugins = false ) {
	$opts = eos_dp_pro_get_option( 'eos_dp_pro_main' );
	$sleep_time = 300000;
	if( $opts ) {
		$opts = isset( $opts['eos_dp_general_setts'] ) ? $opts['eos_dp_general_setts'] : array();
		$sleep_times = array(
			'very_fast' => 0,
			'fast' => 100000,
			'medium' => 300000,
			'slow' => 600000,
			'very_slow' => 1000000
		);
		$sleep_time = isset( $opts['autosuggestion_speed'] ) && in_array( sanitize_text_field( $opts['autosuggestion_speed'] ), array_keys( $sleep_times ) ) ? $sleep_times[ sanitize_text_field( $opts['autosuggestion_speed'] ) ] : 300000;

	}
	$post_args     = $post_args ? $post_args : $_POST;
	$internal_call = isset( $post_args['internal_call'] ) && true === $post_args['internal_call'];
	$cron          = $internal_call ? 'cron_' : '';
	if ( ! isset( $post_args['nonce_checked'] ) || true !== $post_args['nonce_checked'] ) {
		eos_dp_check_intentions_and_rights( 'eos_dp_pro_auto_settings' );
	}
	if ( ! isset( $post_args['post_id'] ) && ! isset( $post_args['post_type'] ) && ! isset( $post_args['tax'] ) ) {
		echo 0;
		die();
		exit;
	}
	if ( isset( $post_args['stop'] ) && '1' === $post_args['stop'] && isset( $post_args['post_id'] ) ) {
		delete_site_transient( 'eos_dp_all_count_' . absint( $post_args['post_id'] ) );
		die();
		exit;
	}
	set_site_transient( 'eos_dp_pro_scanning_unused_plugins' . $cron, 'true' );
	$time0        = time();
	$maxExeTime   = absint( ini_get( 'max_execution_time' ) );
	$memory_limit = ini_get( 'memory_limit' );
	@ini_set( 'memory_limit', '1024M' );
	$changedMaxExeTime = false;
	$offset            = isset( $post_args['offset'] ) ? absint( $post_args['offset'] ) : 0;
	if ( ! $plugins && isset( $post_args['plugins'] ) ) {
		$plugins = explode( ',', esc_attr( $post_args['plugins'] ) );
	}
	$plugins = ! $plugins ? eos_dp_active_plugins() : $plugins;
	if ( $maxExeTime < 600 && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', 600 );
		$changedMaxExeTime = true;
	}
	$is_mobile = false;
	$microtime = 10000 * microtime( 1 );
	$args      = array(
		'eos_dp_debug'     => 'no_errors',
		'eos_dp_preview'   => 'true',
		'eos_dp_offset'    => $offset,
		'eos_dp_pro_id'    => md5( EOS_DP_PRO_TESTING_UNIQUE_ID ),
		'test_id'          => $microtime,
		'site_in_progress' => substr( md5( esc_attr( get_option( 'comingsoon_input_psw_email' ) ) . '_' ), 0, 8 ),
	);
	
	if ( isset( $post_args['post_id'] ) ) {
		$post_id             = absint( $post_args['post_id'] );
		$permalink           = get_permalink( $post_id, false );
		$transient_name      = 'fdp_test_' . $cron . $post_id . '_';
		$args['fdp_post_id'] = $post_id;
		if ( in_array( 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php', $plugins ) ) {
			$desktop_id = absint( get_post_meta( $post_id, apply_filters( 'eos_scfm_desktop_post_id', 'eos_scfm_desktop_post_id' ), true ) );
			$mobile_id  = absint( get_post_meta( $post_id, apply_filters( 'eos_scfm_mobile_post_id', 'eos_scfm_mobile_post_id' ), true ) );
			if ( $desktop_id > 0 || $mobile_id > 0 ) {
				$is_mobile = true;
			}
		}
	} elseif ( isset( $post_args['post_type'] ) ) {
		$permalink             = get_post_type_archive_link( esc_attr( $post_args['post_type'] ) );
		$transient_name        = 'fdp_test_' . $cron . sanitize_key( $post_args['post_type'] ) . '_';
		$args['fdp_post_type'] = esc_attr( $post_args['post_type'] );
	} elseif ( isset( $post_args['term_type'] ) && isset( $post_args['tax'] ) && isset( $post_args['href'] ) ) {
		$permalink       = esc_url( sanitize_text_field( $post_args['href'] ) );
		$transient_name  = 'fdp_test_' . $cron . sanitize_key( $post_args['tax'] ) . '_';
		$args['fdp_tax'] = esc_attr( sanitize_text_field( $post_args['tax'] ) );
	}
	require_once EOS_DP_PLUGIN_DIR . '/admin/eos-dp-plugins-info.php';

	$plugins_to_skip = array_merge( $plugins_to_skip, $backend_plugins );
	if ( $is_mobile && in_array( 'specific-content-for-mobile/specific-content-for-mobile.php', $plugins ) ) {
		$plugins_to_skip[] = 'specific-content-for-mobile/specific-content-for-mobile.php';
	}
	if ( in_array( 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php', $plugins ) && $is_mobile ) {
		$plugins_to_skip[] = 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php';
	}
	if ( $plugins ) {
		$plugins        = array_slice( $plugins, $offset, 4 );
		$unused_plugins = array();
		@ini_set( 'display_errors', 0 );
		if ( $internal_call ) {
			$args['internal_call'] = 'true';
		}
		$args['test_id']        = $microtime;
		$url                    = urldecode( add_query_arg( $args, wp_nonce_url( $permalink, 'eos_dp_preview', 'eos_dp_preview' ) ) );
		$body_all_plugins_Count = false;
		if ( isset( $post_args['post_id'] ) ) {
			if ( 0 === $offset ) {
				delete_site_transient( 'eos_dp_all_count_' . $post_id );
			}
			$body_all_plugins_Count = get_site_transient( 'eos_dp_all_count_' . $post_id );
			if ( ! $body_all_plugins_Count ) {
				set_transient( $transient_name . $microtime, ';pn:' . implode( ';pn:', $disable_on_process ), 600 );
				$body_all_plugins_Count = eos_dp_pro_count_by_url( $url, 'all', false, false, $sleep_time );
				set_site_transient( 'eos_dp_all_count_' . $post_id . '_' . $time0, absint( $body_all_plugins_Count ), 600 );
			}
		}
		if ( $body_all_plugins_Count ) {
			$body_all_plugins_Count = absint( $body_all_plugins_Count );
		} else {
			set_transient( $transient_name . $microtime, ';pn:' . implode( ';pn:', $disable_on_process ), 600 );
			$body_all_plugins_Count = eos_dp_pro_count_by_url( $url, 'all', false, false, $sleep_time );
		}
		$n            = 0;
		$dependencies = false;
		if ( isset( $post_args['dependencies'] ) ) {
			$dependencies = json_decode( sanitize_text_field( stripslashes( $post_args['dependencies'] ) ), true );
		}
		$parent_plugins = array_values( array_diff( scandir( EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents' ), array( '.', '..', 'index.php' ) ) );

		foreach ( $plugins as $plugin ) {
			$microtime = 10000 * microtime( 1 );
			if ( ! in_array( $plugin, $plugins_to_skip ) ) {

				$add_ons = false;
				if ( $dependencies && isset( $dependencies[ $plugin ] ) ) {
					$strings = $dependencies[ $plugin ]['strings'];
					$add_ons = array_filter(
						eos_dp_active_plugins(),
						function( $var ) use ( $strings, $plugin ) {
							return ( $var !== $plugin && true === eos_dp_strposA( $var, $strings ) );
						}
					);
				}

				if ( in_array( dirname( $plugin ) . '.php', $parent_plugins ) && file_exists( EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents/' . dirname( $plugin ) . '.php' ) ) {
					$deps = array();
					require_once EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents/' . dirname( $plugin ) . '.php';
					$filtered_deps = array_filter(
						$deps,
						function( $el ) {
							$active_plugins          = eos_dp_active_plugins();
							$active_plugin_basenames = $active_plugins && is_array( $active_plugins ) ? array_map( 'dirname', eos_dp_active_plugins() ) : array();
							return in_array( $el, $active_plugin_basenames );
						}
					);
						$add_ons   = $add_ons && is_array( $add_ons ) ? array_unique( array_merge( $add_ons, $filtered_deps ) ) : $filtered_deps;
				}
				if ( $add_ons ) {
					$paths_var = ';pn:' . implode( ';pn:', array_merge( array_merge( array( $plugin ), $disable_on_process, $add_ons ) ) );
				} else {
					$paths_var = ';pn:' . implode( ';pn:', array_merge( array( $plugin ), $disable_on_process ) );
				}
				set_transient( $transient_name . $microtime, $paths_var, 600 );
				$args['test_id'] = $microtime;
				$url             = urldecode( add_query_arg( $args, wp_nonce_url( $permalink, 'eos_dp_preview', 'eos_dp_preview' ) ) );
				$bodyCount       = eos_dp_pro_count_by_url( $url, $plugin, false, false, $sleep_time );
				if ( 'error' === $bodyCount ) {
					break;
				}
				if ( 'redirected' !== $bodyCount && $bodyCount > 2 && $bodyCount === $body_all_plugins_Count ) {
					$unused_plugins[] = $plugin;
				}
			} elseif ( in_array( $plugin, $backend_plugins ) ) {
					$unused_plugins[] = $plugin;
			}
			++$n;
		}
		if ( ! $internal_call ) {
			echo json_encode( $unused_plugins );
		}
	}
	delete_site_transient( 'eos_dp_pro_scanning_unused_plugins' . $cron );
	$display_errors = defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY;
	if ( $changedMaxExeTime && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', $maxExeTime );
		@ini_set( 'memory_limit', $memory_limit );
	}
	if ( $internal_call && $n === count( $plugins ) ) {
		return $unused_plugins;
	}
	if ( $n !== count( $plugins ) ) {
		echo 'error';
	}
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_auto_settings', 'eos_dp_auto_settings' );
// Auto settings New.
function eos_dp_auto_settings( $post_args = false, $plugins = false ) {
	if ( ! $post_args ) {
		$post_args = json_decode( stripslashes( sanitize_text_field( $_POST['data'] ) ), true );
	}
	$internal_call = isset( $post_args['internal_call'] ) && true === $post_args['internal_call'];
	$cron          = $internal_call ? 'cron_' : '';
	if ( ! isset( $post_args['nonce_checked'] ) || true !== $post_args['nonce_checked'] ) {
		eos_dp_check_intentions_and_rights( 'eos_dp_pro_auto_settings' );
	}
	if ( ! isset( $post_args['post_id'] ) && ! isset( $post_args['post_type'] ) && ! isset( $post_args['tax'] ) ) {
		echo 0;
		die();
		exit;
	}
	if ( isset( $post_args['stop'] ) && '1' === $post_args['stop'] && isset( $post_args['post_id'] ) ) {
		delete_site_transient( 'eos_dp_all_count_' . absint( $post_args['post_id'] ) );
		die();
		exit;
	}
	$time0        = time();
	$maxExeTime   = absint( ini_get( 'max_execution_time' ) );
	$memory_limit = ini_get( 'memory_limit' );
	@ini_set( 'memory_limit', '1024M' );
	$changedMaxExeTime = false;
	$offset            = isset( $post_args['offset'] ) ? absint( $post_args['offset'] ) : 0;
	if ( ! $plugins && isset( $post_args['plugins'] ) ) {
		$plugins = explode( ',', esc_attr( $post_args['plugins'] ) );
	}
	$plugins = eos_dp_active_plugins();
	if ( $maxExeTime < 600 && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', 600 );
		$changedMaxExeTime = true;
	}
	$is_mobile = false;
	$microtime = 10000 * microtime( 1 );
	$args      = array(
		'eos_dp_debug'     => 'no_errors',
		'eos_dp_preview'   => 'true',
		'eos_dp_offset'    => $offset,
		'eos_dp_pro_id'    => md5( EOS_DP_PRO_TESTING_UNIQUE_ID ),
		'test_id'          => $microtime,
		'site_in_progress' => substr( md5( esc_attr( get_option( 'comingsoon_input_psw_email' ) ) . '_' ), 0, 8 ),
	);
	if ( isset( $post_args['post_id'] ) ) {
		$post_id             = absint( $post_args['post_id'] );
		$permalink           = get_permalink( $post_id, false );
		$transient_name      = 'fdp_test_' . $cron . $post_id . '_';
		$args['fdp_post_id'] = $post_id;
		if ( in_array( 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php', $plugins ) ) {
			$desktop_id = absint( get_post_meta( $post_id, apply_filters( 'eos_scfm_desktop_post_id', 'eos_scfm_desktop_post_id' ), true ) );
			$mobile_id  = absint( get_post_meta( $post_id, apply_filters( 'eos_scfm_mobile_post_id', 'eos_scfm_mobile_post_id' ), true ) );
			if ( $desktop_id > 0 || $mobile_id > 0 ) {
				$is_mobile = true;
			}
		}
	} elseif ( isset( $post_args['post_type'] ) ) {
		$permalink             = get_post_type_archive_link( esc_attr( $post_args['post_type'] ) );
		$transient_name        = 'fdp_test_' . $cron . sanitize_key( $post_args['post_type'] ) . '_';
		$args['fdp_post_type'] = esc_attr( $post_args['post_type'] );
	} elseif ( isset( $post_args['term_type'] ) && isset( $post_args['tax'] ) && isset( $post_args['href'] ) ) {
		$permalink       = esc_url( sanitize_text_field( $post_args['href'] ) );
		$transient_name  = 'fdp_test_' . $cron . sanitize_key( $post_args['tax'] ) . '_';
		$args['fdp_tax'] = esc_attr( sanitize_text_field( $post_args['tax'] ) );
	}
	require_once EOS_DP_PLUGIN_DIR . '/admin/eos-dp-plugins-info.php';
	set_site_transient( 'eos_dp_pro_scanning_unused_plugins' . $cron, 'true' );
	$plugins_to_skip = array_merge( $plugins_to_skip, $backend_plugins );
	if ( $is_mobile && in_array( 'specific-content-for-mobile/specific-content-for-mobile.php', $plugins ) ) {
		$plugins_to_skip[] = 'specific-content-for-mobile/specific-content-for-mobile.php';
	}
	if ( in_array( 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php', $plugins ) && $is_mobile ) {
		$plugins_to_skip[] = 'specific-content-for-mobile-PRO/specific-content-for-mobile-pro.php';
	}
	if ( $plugins ) {
		$count = isset( $post_args['counter'] ) ? absint( $post_args['counter'] ) : 0;
		if ( $count === count( $plugins ) ) {
			echo 'stop';
			die();
		}
		$unused_plugins = array();
		@ini_set( 'display_errors', 0 );
		if ( $internal_call ) {
			$args['internal_call'] = 'true';
		}
		$args['test_id']        = $microtime;
		$url                    = urldecode( add_query_arg( $args, wp_nonce_url( $permalink, 'eos_dp_preview', 'eos_dp_preview' ) ) );
		$body_all_plugins_Count = false;
		if ( isset( $post_args['post_id'] ) ) {
			if ( 0 === $offset ) {
				delete_site_transient( 'eos_dp_all_count_' . $post_id );
			}
			$body_all_plugins_Count = get_site_transient( 'eos_dp_all_count_' . $post_id );
			if ( ! $body_all_plugins_Count ) {
				set_transient( $transient_name . $microtime, ';pn:' . implode( ';pn:', $disable_on_process ), 600 );
				$body_all_plugins_Count = eos_dp_pro_count_by_url( $url, 'all', false, false, $sleep_time );
				set_site_transient( 'eos_dp_all_count_' . $post_id . '_' . $time0, absint( $body_all_plugins_Count ), 600 );
			}
		}
		if ( $body_all_plugins_Count ) {
			$body_all_plugins_Count = absint( $body_all_plugins_Count );
		} else {
			set_transient( $transient_name . $microtime, ';pn:' . implode( ';pn:', $disable_on_process ), 600 );
			$body_all_plugins_Count = eos_dp_pro_count_by_url( $url, 'all', false, false, $sleep_time );
		}
		$n            = 0;
		$dependencies = false;
		if ( isset( $post_args['dependencies'] ) ) {
			$dependencies = json_decode( sanitize_text_field( stripslashes( $post_args['dependencies'] ) ), true );
		}
		$parent_plugins = array_values( array_diff( scandir( EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents' ), array( '.', '..', 'index.php' ) ) );

		$plugins   = array_slice( $plugins, absint( $count ), 1 );
		$plugin    = $plugins[0];
		$microtime = 10000 * microtime( 1 );
		if ( ! in_array( $plugin, $plugins_to_skip ) ) {

			$add_ons = false;
			if ( $dependencies && isset( $dependencies[ $plugin ] ) ) {
				$strings = $dependencies[ $plugin ]['strings'];
				$add_ons = array_filter(
					eos_dp_active_plugins(),
					function( $var ) use ( $strings, $plugin ) {
						return ( $var !== $plugin && true === eos_dp_strposA( $var, $strings ) );
					}
				);
			}

			if ( in_array( dirname( $plugin ) . '.php', $parent_plugins ) && file_exists( EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents/' . dirname( $plugin ) . '.php' ) ) {
				$deps = array();
				require_once EOS_DP_PLUGIN_DIR . '/inc/plugin-dependents/' . dirname( $plugin ) . '.php';
				$filtered_deps = array_filter(
					$deps,
					function( $el ) {
						$active_plugins          = eos_dp_active_plugins();
						$active_plugin_basenames = $active_plugins && is_array( $active_plugins ) ? array_map( 'dirname', eos_dp_active_plugins() ) : array();
						return in_array( $el, $active_plugin_basenames );
					}
				);
				$add_ons       = $add_ons && is_array( $add_ons ) ? array_unique( array_merge( $add_ons, $filtered_deps ) ) : $filtered_deps;
			}
			if ( $add_ons ) {
				$paths_var = ';pn:' . implode( ';pn:', array_merge( array_merge( array( $plugin ), $disable_on_process, $add_ons ) ) );
			} else {
				$paths_var = ';pn:' . implode( ';pn:', array_merge( array( $plugin ), $disable_on_process ) );
			}
			set_transient( $transient_name . $microtime, $paths_var, 600 );
			$args['test_id'] = $microtime;
			$url             = urldecode( add_query_arg( $args, wp_nonce_url( $permalink, 'eos_dp_preview', 'eos_dp_preview' ) ) );
			$bodyCount       = eos_dp_pro_count_by_url( $url, $plugin, false, false, $sleep_time );
			if ( 'error' === $bodyCount ) {
				echo 'error';
				die();
				exit;
			}
			if ( 'redirected' !== $bodyCount && $bodyCount > 2 && $bodyCount === $body_all_plugins_Count ) {
				$unused_plugins[] = $plugin;
			}
		} elseif ( in_array( $plugin, $backend_plugins ) ) {
				$unused_plugins[] = $plugin;
		}
		if ( ! $internal_call ) {
			echo json_encode( $unused_plugins );
		}
	}
	delete_site_transient( 'eos_dp_pro_scanning_unused_plugins' . $cron );
	if ( $changedMaxExeTime && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', $maxExeTime );
		@ini_set( 'memory_limit', $memory_limit );
	}
	if ( $internal_call && $n === count( $plugins ) ) {
		return $unused_plugins;
	}
	die();
	exit;
}


add_action( 'wp_ajax_eos_dp_pro_auto_settings_admin', 'eos_dp_pro_auto_settings_admin' );
// Auto settings.
function eos_dp_pro_auto_settings_admin( $post_args = false, $plugins = false ) {
	$post_args = $post_args ? $post_args : $_POST;
	$offset    = isset( $post_args['offset'] ) ? absint( $post_args['offset'] ) : 0;
	eos_dp_check_intentions_and_rights( 'eos_dp_pro_auto_settings_admin' );
	$internal_call     = isset( $post_args['internal_call'] ) && true === $post_args['internal_call'];
	$time0             = time();
	$maxExeTime        = absint( ini_get( 'max_execution_time' ) );
	$changedMaxExeTime = false;
	if ( $maxExeTime < 300 && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', 300 );
		$changedMaxExeTime = true;
	}
	$url            = esc_url( $post_args['admin_page'] );
	$admin_page_key = sanitize_key(
		str_replace(
			'.',
			'-',
			str_replace(
				'admin.php?page=',
				'eos_dp_tlp-',
				str_replace( admin_url(), '', $post_args['admin_page'] )
			)
		)
	);
	delete_expired_transients();
	set_site_transient( 'eos_dp_pro_scanning_unused_plugins_admin', 'true' );
	$plugins_to_skip     = array(
		'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',
		'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',
	);
	$disable_on_process  = array();
	$disable_on_process0 = array(
		'query-monitor/query-monitor.php',
	);
	if ( ! $plugins && isset( $post_args['plugins'] ) ) {
		$plugins = explode( ',', esc_attr( $post_args['plugins'] ) );
	}
	$plugins = ! $plugins ? eos_dp_active_plugins() : $plugins;
	foreach ( $plugins as $plugin ) {
		if ( in_array( $plugin, $disable_on_process0 ) ) {
			$disable_on_process[] = $plugin;
		}
	}
	if ( $plugins ) {
		$time           = microtime( 1 );
		$plugins        = array_slice( $plugins, $offset, 4 );
		$unused_plugins = array();
		@ini_set( 'display_errors', 0 );
		$args                   = array(
			'eos_dp_debug'     => 'no_errors',
			'admin_page_key'   => $admin_page_key,
			'eos_dp_pro_id'    => md5( EOS_DP_PRO_TESTING_UNIQUE_ID ),
			'test_id'          => $time,
			'site_in_progress' => substr( md5( esc_attr( get_option( 'comingsoon_input_psw_email' ) ) . '_' ), 0, 8 ),
		);
		$url                    = str_replace( '&amp;', '&', add_query_arg( $args, wp_nonce_url( $url, 'eos_dp_preview', 'eos_dp_preview' ) ) );
		$body_all_plugins_Count = eos_dp_pro_count_by_url( $url, 'all', true, false, $sleep_time );
		$n                      = 0;
		$unused_plugins         = array();
		foreach ( $plugins as $plugin ) {
			$time = microtime( 1 );
			if ( ! in_array( $plugin, $plugins_to_skip ) ) {
				$paths_var = ';pn:' . implode( ';pn:', array_merge( array( $plugin ), $disable_on_process ) );
				set_transient( 'fdp_test_' . $admin_page_key . '_' . $time, $paths_var, 300 ) . '_';
				$args['test_id'] = $time;
				$url             = str_replace( '&amp;', '&', add_query_arg( $args, wp_nonce_url( $url, 'eos_dp_preview', 'eos_dp_preview' ) ) );
				$bodyCount       = eos_dp_pro_count_by_url( $url, $plugin, true, false, $sleep_time );
				if ( 'redirected' !== $bodyCount && $bodyCount > 2 && $bodyCount === $body_all_plugins_Count ) {
					$unused_plugins[] = $plugin;
				}
			}
			++$n;
		}
		if ( $internal_call && $n === count( $plugins ) ) {
			return $unused_plugins;
		}
		echo json_encode( $unused_plugins );
		die();
		exit;
	}
	delete_site_transient( 'eos_dp_pro_scanning_unused_plugins_admin' );
	$display_errors = defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY;
	if ( $changedMaxExeTime && $maxExeTime > 0 ) {
		@ini_set( 'max_execution_time', $maxExeTime );
	}
	@ini_set( 'display_errors', $display_errors );
	die();
}

// It retrieves the body html given the url.
function eos_dp_pro_count_by_url( $url, $plugin, $admin = false, $headers = false, $sleep_time = 300000 ) {
	$url      = add_query_arg(
		array(
			'display_usage'      => 'false',
			'fdp-autosuggestion' => 'on',
		),
		$url
	);
	$args     = array(
		'timeout'   => 5,
		'sslverify' => false,
	);
	$args     = eos_dp_user_headers( $args, true );
	usleep( absint( $sleep_time ) ); // Sleep to don't stress too much the CPU.
	$response = wp_remote_request( $url, $args );
	if ( ! is_wp_error( $response ) ) {
		$html = wp_remote_retrieve_body( $response );
		if ( ! $html || false === strpos( $html, '<' ) ) {
			return false;
		}
		$html    = preg_replace( '/<!--(.|\\s)*?-->/', '', $html );
		$replace = array(
			'/\/\*(.*?)\*\//'                         => '',
			"/\/\*(?:(?!\*\/)[\s\S])*\*\/|[\r\n\t]+/" => '',
			'/<!--(.*)-->/Uis'                        => '',
			"\n"                                      => '',
			"\t"                                      => '',
			"\r"                                      => '',
			'  '                                      => ' ',
			'> <'                                     => '><',
		);
		$search  = array_keys( $replace );
		$html    = str_replace( $search, $replace, $html );
		if ( ! is_wp_error( $html ) ) {
			$metaN = 0;
			libxml_use_internal_errors( true );
			$dom = new DOMDocument();
			$dom->loadHTML( $html );
			if ( null === $dom ) {
				return;
			}
			$metas = $dom->getElementsByTagName( 'meta' );
			foreach ( $metas as $meta ) {
				if ( $meta->hasAttribute( 'name' ) ) {
					$metaName = $meta->getAttribute( 'name' );
					if (
						in_array(
							$metaName,
							array(
								'description',
							)
						)
						|| false !== strpos( $metaName, 'og:' )
						|| false !== strpos( $metaName, 'twitter:' )
					) {
						++$metaN;
					}
				}
			}
			foreach ( array( 'head', 'script', 'link', 'style' ) as $key ) {
				$remove = array();
				$type   = $dom->getElementsByTagName( $key );
				foreach ( $type as $item ) {
					$remove[] = $item;
				}
				foreach ( $remove as $item ) {
					$item->parentNode->removeChild( $item );
				}
			}
			if ( $admin ) {
				$remove = array();
				$ids    = array( 'adminmenuwrap', 'wp-admin-bar-root-default' );
				foreach ( $ids as $id ) {
					$el = $dom->getElementById( $id );
					if ( $el ) {
						$el->parentNode->removeChild( $el );
					}
				}
				if ( class_exists( 'DomXPath' ) ) {
					$finder    = new DomXPath( $dom );
					$classname = 'notice';
					$els       = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]" );
					foreach ( $els as $item ) {
						$item->parentNode->removeChild( $item );
					}
					$type = 'hidden';
					$els  = $finder->query( "//*[contains(concat(' ', normalize-space(@type), ' '), ' $type ')]" );
					foreach ( $els as $item ) {
						$item->parentNode->removeChild( $item );
					}
				}
			}
			$body = $dom->getElementsByTagName( 'body' );
			if ( $body && isset( $body[0] ) ) {
				$body_class = $body[0]->getAttribute( 'class' );
				if ( 'all' !== $plugin && $body_class && false !== strpos( $body_class, ' fdp-autosuggestion-class-end' ) ) {
					$url_classA = explode( 'fdp-autosuggestion-class-start', explode( ' fdp-autosuggestion-class-end', $body_class )[0] );
					if ( $url_classA && isset( $url_classA[1] ) ) {
						$url_class  = str_replace( ' ', '', $url_classA[1] );
						if( isset( $_SERVER['HTTP_HOST'] ) ){
							$home_urlA = explode( '://', sanitize_text_field( get_home_url() ) );
							if( isset( $home_urlA[1] ) ){
								$home_url = $home_urlA[1];
								if( str_replace( '/', '', $home_url ) !== $_SERVER['HTTP_HOST'] ){
									$subfolder = sanitize_text_field( substr( $home_url, strpos( $home_url, $_SERVER['HTTP_HOST'] ) + strlen( $_SERVER['HTTP_HOST'] ) + 1 ) );
									$url_class = str_replace( '-'.$subfolder, '', $url_class );
								}
							}
						}
						$url_server = str_replace( array( get_home_url(), '/' ), array( '', '-' ), strtok( $url, '?' ) );
						if ( $url_class !== $url_server && '-' !== $url_class && '-' !== $url_server ) {
							// this is a redirection.
							return 'redirected';
						}
					}
				}
				$body[0]->setAttribute( 'class', 'none' );
				$html = $dom->getElementsByTagName( 'html' );
				$html[0]->setAttribute( 'class', 'none' );
			}
			$string = $dom->saveHTML();
			$n      = substr_count( $string, '<' );
			$n     += substr_count( $string, '</' );
			if ( ! $admin ) {
				$n += substr_count( $string, 'class="' );
				$n += substr_count( $string, 'id="' );
				$n += substr_count( $string, '[' );
				$n += substr_count( $string, '[/' );
				$n += $metaN;
			}
			if ( function_exists( 'wp_cache_flush_runtime' ) ) {
				wp_cache_flush_runtime();
			}
			return $n;
		}
	} else {
		return 'error';
	}
	return false;
}

add_action( 'wp_ajax_eos_dp_debug_options', 'eos_dp_debug_options' );
// It returns the disabled plugins.
function eos_dp_debug_options() {
	eos_dp_check_intentions_and_rights( 'eos_dp_debug_options' );
	if ( ! isset( $_POST['url'] ) || ! class_exists( 'DOMDocument' ) ) {
		echo 0;
		die();
		exit;
	}
	$url      = add_query_arg( 'eos_dp_debug_options', 'true', str_replace( '%', '', sanitize_text_field( $_POST['url'] ) ) );
	$response = wp_remote_get( esc_url( $url ), eos_dp_user_headers( array( 'sslverify' => false ), true ) );
	if ( ! is_wp_error( $response ) ) {
		$html = wp_remote_retrieve_body( $response );
		if ( ! $html || false === strpos( $html, '<' ) ) {
			echo 0;
			die();
			exit;
		}
		if ( ! is_wp_error( $html ) ) {
			libxml_use_internal_errors( true );
			$msg = '';
			$dom = new DOMDocument();
			$dom->loadHTML( $html );
			$wrp = $dom->getElementById( 'eos-dp-debug-options-wrapper' );
			if ( $wrp ) {
				$json        = json_decode( $wrp->textContent, true );
				$json['url'] = remove_query_arg( 'eos_dp_debug_options', esc_url( $url ) );
				echo json_encode( $json );
			} else {
				$plugins = eos_dp_active_plugins();
				require_once EOS_DP_PLUGIN_DIR . '/admin/eos-dp-plugins-info.php';
				$active_plugins = eos_dp_active_plugins();
				$msg            = 'error-' . esc_html__( 'It was not possible to get the disabled plugins. Maybe this page redirects to another page or something went wrong.', 'freesoul-deactivate-plugins' );
				foreach ( $maintenance_plugins as $plugin ) {
					if ( in_array( $plugin, $active_plugins ) ) {
						$plugin = strtoupper( str_replace( '-', ' ', dirname( $plugin ) ) );
						$msg   .= '<br /><br />' . sprintf( esc_html__( 'Maybe the plugin %s is preventing seeing this page to non-logged users.', 'freesoul-deactivate-plugins' ), esc_html( $plugin ) );
						break;
					}
				}
			}
			echo wp_kses_post( $msg );
			die();
			exit;
		}
	}
	die();
	exit;
}
add_action( 'wp_ajax_eos_dp_msg_never_again', 'eos_dp_msg_never_again' );
// It prevents future notifications to the same user.
function eos_dp_msg_never_again() {
	eos_dp_check_intentions_and_rights( 'eos_dp_never_again_msg_user' );
	if ( ! isset( $_POST['msg'] ) ) {
		echo 0;
		die();
		exit;
	}
	$user = wp_get_current_user();
	if ( ! is_object( $user ) ) {
		die();
		exit;
	}
	$user_opts                                      = get_site_option( 'eos_dp_user_options' );
	$user_opts                                      = is_array( $user_opts ) && ! empty( $user_opts ) ? $user_opts : array();
	$user_opts[ sanitize_key( $user->user_login ) ] = array( sanitize_key( $_POST['msg'] ) => 'never_again' );
	update_site_option( 'eos_dp_user_options', $user_opts );
	die();
	exit;
}

// Check for intentions and rights.
function eos_dp_check_intentions_and_rights( $nonce_action ) {
	if ( isset( $_REQUEST['eos_dp_advanced_setup'] ) || isset( $GLOBALS['eos_dp_advanced_setup'] ) ) {
		$eos_dp_advanced_setup = isset( $GLOBALS['eos_dp_advanced_setup'] ) ? $GLOBALS['eos_dp_advanced_setup'] : sanitize_text_field( $_REQUEST['eos_dp_advanced_setup'] );
		$opts                  = eos_dp_get_option( 'eos_dp_opts' );
		$password              = isset( $opts['advanced_help_password'] ) ? esc_attr( $opts['advanced_help_password'] ) : '';
		if ( '' !== $password && strlen( $password ) > 9 && $password === $eos_dp_advanced_setup ) {
			return;
		}
	}
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : false;
	if (
		false === $nonce
		|| ! wp_verify_nonce( $nonce, $nonce_action ) // check for intentions.
		|| ! current_user_can( 'activate_plugins' ) // check for rights.
	) {
		echo 0;
		die();
		exit;
	}
}

// Convert string size to numeric size.
function eos_let_to_num( $size ) {
	$l   = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );
	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}
	return $ret;
}
add_action( 'wp_ajax_eos_dp_create_plugin', 'eos_dp_create_plugin' );
// It creates a new plugin.
function eos_dp_create_plugin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	eos_dp_check_intentions_and_rights( 'fdp_create_plugin' );
	if ( ! is_writable( WP_PLUGIN_DIR ) ) {
		echo json_encode( array( 'error' => esc_html__( 'No writing rights', 'freesoul-deactivate-plugins' ) ) );
		die();
		exit;
	}
	$user        = wp_get_current_user();
	$plugin_name = isset( $_REQUEST['plugin_name'] ) && '' !== $_REQUEST['plugin_name'] ? sanitize_text_field( $_REQUEST['plugin_name'] ) : esc_html__( 'My custom plugin', 'freesoul-deactivate-plugins' );
	$plugin_name = strtolower( str_replace( ' ', '-', $plugin_name ) );
	$description = isset( $_REQUEST['plugin_description'] ) && '' !== $_REQUEST['plugin_description'] ? sanitize_text_field( $_REQUEST['plugin_description'] ) : esc_html__( 'My custom code.', 'freesoul-deactivate-plugins' );
	$author      = isset( $_REQUEST['plugin_author'] ) && '' !== $_REQUEST['plugin_author'] ? sanitize_text_field( $_REQUEST['plugin_author'] ) : $user->user_login;
	$txt         = '<?php';
	$txt        .= PHP_EOL . '/*';
	$txt        .= PHP_EOL . 'Plugin Name: ' . str_replace( '-', ' ', sanitize_text_field( ucwords( $plugin_name ) ) );
	$txt        .= PHP_EOL . 'Description: ' . sanitize_text_field( $description );
	$txt        .= PHP_EOL . 'Author: ' . sanitize_text_field( ucfirst( $author ) );
	if ( isset( $_REQUEST['plugin_author_uri'] ) && '' !== $_REQUEST['plugin_author_uri'] && esc_url( sanitize_text_field( $_REQUEST['plugin_author_uri'] ) ) === $_REQUEST['plugin_author_uri'] ) {
		$txt .= PHP_EOL . 'Author URI: ' . esc_url( sanitize_text_field( $_REQUEST['plugin_author_uri'] ) );
	}
	$txt        .= PHP_EOL . 'Domain Path: /languages/';
	$txt        .= PHP_EOL . 'Text Domain: ' . sanitize_key( $plugin_name );
	$txt        .= PHP_EOL . 'Version: 0.0.1';
	$txt        .= PHP_EOL . '*/';
	$txt        .= PHP_EOL . '/*  This program is free software; you can redistribute it and/or modify';
	$txt        .= PHP_EOL . 'it under the terms of the GNU General Public License as published by';
	$txt        .= PHP_EOL . 'the Free Software Foundation; either version 2 of the License, or';
	$txt        .= PHP_EOL . '(at your option) any later version.';
	$txt        .= PHP_EOL . 'This program is distributed in the hope that it will be useful,';
	$txt        .= PHP_EOL . 'but WITHOUT ANY WARRANTY; without even the implied warranty of';
	$txt        .= PHP_EOL . 'MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the';
	$txt        .= PHP_EOL . 'GNU General Public License for more details.';
	$txt        .= PHP_EOL . '*/';
	$txt        .= PHP_EOL . "defined( 'ABSPATH' ) || exit; // Exit if accessed directly.";
	$txt        .= PHP_EOL . PHP_EOL . '// Definitions';
	$txt        .= PHP_EOL . "define( '" . str_replace( '-', '_', strtoupper( sanitize_key( $plugin_name ) ) ) . "_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );";
	$txt        .= PHP_EOL . "define( '" . str_replace( '-', '_', strtoupper( sanitize_key( $plugin_name ) ) ) . "_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );";
	$readme_txt  = '=== ' . sanitize_text_field( ucwords( $plugin_name ) ) . ' ===';
	$readme_txt .= PHP_EOL . sprintf( 'Tested up to: %s', get_bloginfo( 'version' ) );
	$readme_txt .= PHP_EOL . 'Stable tag: 0.0.1';
	$readme_txt .= PHP_EOL . 'License: GPLv2 or later';
	$readme_txt .= PHP_EOL . 'License URI: http://www.gnu.org/licenses/gpl-2.0.html';
	$readme_txt .= PHP_EOL . PHP_EOL . sanitize_text_field( $description );
	$readme_txt .= PHP_EOL . PHP_EOL . PHP_EOL . '== Description ==';
	$readme_txt .= PHP_EOL . PHP_EOL . sanitize_text_field( $description );
	$readme_txt .= PHP_EOL . PHP_EOL . PHP_EOL . '== Changelog ==';
	$readme_txt .= PHP_EOL . PHP_EOL . '= 0.0.1 =' . PHP_EOL . '*Initial release';
	$plugin_name = sanitize_key( $plugin_name );
	$n           = -1;
	$suff        = '';
	do {
		++$n;
		$suff = str_replace( '-0', '', '-' . $n );
		if ( $n > 20 ) {
			echo json_encode( array( 'error' => esc_html__( 'Too many custom plugins', 'freesoul-deactivate-plugins' ) ) );
			die();
			exit;
		}
	} while ( is_dir( WP_PLUGIN_DIR . '/' . $plugin_name . $suff ) && file_exists( WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' ) );
	eos_dp_write_file( EOS_DP_PLUGIN_DIR . '/index.php', WP_PLUGIN_DIR . '/' . $plugin_name . $suff, WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/index.php' );
	if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/index.php' ) && ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' ) ) {
		global $wp_filesystem;
		$wp_filesystem->put_contents(
			WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/' . $plugin_name . $suff . '.php',
			$txt,
			FS_CHMOD_FILE
		);
		$wp_filesystem->put_contents(
			WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/readme.txt',
			$readme_txt,
			FS_CHMOD_FILE
		);
		$url      = sprintf( admin_url( 'plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s' ), $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' );
		$url      = current_user_can( 'activate_plugins' ) ? wp_nonce_url( $url, 'activate-plugin_' . $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' ) : false;
		$edit_url = current_user_can( 'edit_plugins' ) && ( ! defined( 'DISALLOW_FILE_EDIT' ) || true !== DISALLOW_FILE_EDIT ) ? admin_url( 'plugin-editor.php?plugin=' . urlencode( $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' ) ) : false;
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_name . $suff . '/' . $plugin_name . $suff . '.php' ) ) {
			echo wp_json_encode(
				array(
					'activate' => $url,
					'edit'     => add_query_arg( 'fdp-iframe', 'true', $edit_url ),
				)
			);
			die();
			exit;
		}
	}
	echo json_encode( array( 'error' => esc_html__( 'Something went wrong.', 'freesoul-deactivate-plugins' ) ) );
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_install_plugin', 'eos_dp_install_plugin' );
// Install plugin.
function eos_dp_install_plugin() {
	if ( ! isset( $_REQUEST['plugin'] ) || ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'fdp_install_plugin' ) ) {
		die();
		exit;
	}
	$plugin_slug = sanitize_text_field( $_REQUEST['plugin'] );
	if ( ! is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		wp_cache_flush();
		$upgrader   = new Plugin_Upgrader();
		$plugin_zip = 'https://downloads.wordpress.org/plugin/' . $plugin_slug . '.zip';
		$installed  = $upgrader->install( $plugin_zip );
		if ( $installed ) {
			printf( esc_html__( 'Installed %s', 'freesoul-deactivate-plugins' ), esc_attr( strtoupper( str_replace( '-', ' ', $plugin_slug ) ) ) );
		} else {
			printf( esc_html__( 'Something went wrong during the installation of %s', 'freesoul-deactivate-plugins' ), esc_attr( strtoupper( str_replace( '-', ' ', $plugin_slug ) ) ) );
		}
		die();
		exit;
	}
}
add_action( 'wp_ajax_eos_dp_import_plugins_list', 'eos_dp_import_plugins_list' );
// Import list of plugins.
function eos_dp_import_plugins_list() {
	if ( ! isset( $_REQUEST['id'] ) || ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'fdp_import_plugins_list' ) ) {
		die();
		exit;
	}
	$url = wp_get_attachment_url( absint( $_REQUEST['id'] ) );
	if ( $url ) {
		$file = str_replace( get_home_url(), ABSPATH, $url );
		if ( file_exists( $file ) ) {
			ob_start();
			require_once $file;
			$list       = ob_get_clean();
			$list_array = explode( PHP_EOL, $list );
			if ( ! empty( $list_array ) ) {
				$clean_list = '';
				$key        = $list_array[0];
				unset( $list_array[0] );
				foreach ( $list_array as $plugin ) {
					$clean_list .= esc_attr( $plugin ) . PHP_EOL;
				}
				echo esc_attr( implode( ';', $list_array ) );
				die();
				exit;
			}
		}
		die();
		exit;
	}
}
add_action( 'wp_ajax_eos_dp_export_plugins_list', 'eos_dp_export_plugins_list' );
// Export list of plugins.
function eos_dp_export_plugins_list() {
	if ( ! isset( $_REQUEST['data'] ) ) {
		die();
		exit;
	}
	$data = json_decode( sanitize_text_field( stripslashes( $_REQUEST['data'] ) ), true ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	if ( ! isset( $data['nonce'] ) || ! isset( $data['plugins'] ) || ! wp_verify_nonce( esc_attr( $data['nonce'] ), 'fdp_export_plugins_list' ) ) {
		die();
		exit;
	}
	$plugins = explode( ';', $data['plugins'] );
	if ( empty( $plugins ) ) {
		die();
		exit;
	}
	$plugins = array_unique( $plugins );
	$list    = '';
	foreach ( $plugins as $plugin ) {
		$list .= esc_attr( $plugin ) . PHP_EOL;
	}
	$list        = md5( $list ) . PHP_EOL . $list;
	$writeAccess = false;
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		$admin_url = is_multisite() ? network_admin_url() : admin_url();
		$creds     = request_filesystem_credentials( $admin_url, '', false, false, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			die();
			exit;
		}
		global $wp_filesystem;
		$upload_dir = wp_upload_dir();
		$uniqid     = substr( sanitize_key( md5( time() ) ), 0, 12 );
		$arrFiles   = glob( $upload_dir['basedir'] . '/fdp-favorite-plugins*.txt' );
		foreach ( $arrFiles as $file ) {
			$wp_filesystem->delete( $file );
		}
		$wp_filesystem->put_contents( $upload_dir['basedir'] . '/fdp-favorite-plugins-' . $uniqid . '.txt', $list );
		echo esc_url( $upload_dir['baseurl'] . '/fdp-favorite-plugins-' . $uniqid . '.txt' );
		die();
		exit;
	}
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_pro_save_settings', 'eos_dp_pro_save_settings' );
// Save settings.
function eos_dp_pro_save_settings() {
	if ( ! current_user_can( 'activate_plugins' ) || ! isset( $_POST['data'] ) ) {
		return;
	}
	$data = json_decode( sanitize_text_field( stripslashes( $_POST['data'] ) ) ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	if ( ! isset( $data->opts_key ) || ! isset( $data->nonce ) || ! wp_verify_nonce( esc_attr( $data->nonce ), 'fdp_setts_nonce' ) ) {
		die();
		exit;
	}
	$opts_key = sanitize_key( $data->opts_key );
	unset( $data->nonce );
	unset( $data->opts_key );
	$main_opts = eos_dp_get_option( 'eos_dp_pro_main' );
	$opts      = array();
	foreach ( $data as $key => $value ) {
		if ( '' !== $value ) {
			if ( is_object( $value ) ) {
				$value = json_encode( $value );
			}
			$opts[ sanitize_key( $key ) ] = sanitize_text_field( $value );
		}
	}
	$main_opts[ $opts_key ] = $opts;
	eos_dp_update_option( 'eos_dp_pro_main', $main_opts );
	if ( in_array( $opts_key, array( 'eos_dp_logged_conditions' ) ) ) {
		$headers = array();
		if ( isset( $_POST['headers'] ) && ! empty( $_POST['headers'] ) ) {
			$headers = json_decode( sanitize_text_field( stripslashes( $_POST['headers'] ) ), true ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
		}
		eos_dp_update_fdp_admin_menu(
			eos_dp_user_headers(
				array(
					'timeout'   => 5,
					'sslverify' => false,
				),
				true
			)
		);
	}
	echo 1;
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_save_addon_settings', 'eos_dp_save_addon_settings' );
// Save settings.
function eos_dp_save_addon_settings() {
	if ( ! current_user_can( 'activate_plugins' ) || ! isset( $_POST['data'] ) ) {
		return;
	}
	$data = json_decode( sanitize_text_field( stripslashes( $_POST['data'] ) ) ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	if ( ! isset( $data->opts_key ) || ! isset( $data->nonce ) || ! wp_verify_nonce( esc_attr( $data->nonce ), sanitize_key( $data->opts_key ) ) ) {
		die();
		exit;
	}
	$opts_key = sanitize_key( $data->opts_key );
	if ( isset( $data->autoload ) && 'true' === $data->autoload ) {
		add_filter(
			'fdp_autoloaded_options',
			function( $arr ) {
				$arr[] = $opts_key;
				return $arr;
			}
		);
	}
	unset( $data->nonce );
	unset( $data->opts_key );
	$opts = array();
	foreach ( $data as $key => $value ) {
		if ( '' !== $value ) {
			if ( is_object( $value ) ) {
				$value = json_encode( $value );
			}
			$opts[ sanitize_key( $key ) ] = sanitize_text_field( $value );
		}
	}
	eos_dp_update_option( $opts_key, $opts );
	echo 1;
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_code_profiler_save', 'eos_dp_code_profiler_save' );
// Save Code Profiler preferences.
function eos_dp_code_profiler_save() {
	if ( ! isset( $_POST['plugins'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'fdp-cp-nonce' ) ) {
		die();
		exit;
	}
	$plugins = '' !== $_POST['plugins'] && false !== strpos( $_POST['plugins'], ',' ) ? array_values( array_filter( explode( ',', sanitize_text_field( $_POST['plugins'] ) ) ) ) : array(); // @codingStandardsIgnoreLine.
	$opts    = eos_dp_get_option( 'fdp_code_profiler' );
	echo esc_attr(
		( $opts['fdp_cp'] === $_POST['fdp_cp'] && $opts['plugins'] === $plugins ) || eos_dp_update_option(
			'fdp_code_profiler',
			array(
				'fdp_cp'  => sanitize_text_field( $_POST['fdp_cp'] ),
				'plugins' => $plugins,
			)
		)
	);
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_generate_critical_css', 'eos_dp_generate_critical_css' );
// Generate critical CSS from CriticalCSS.com.
function eos_dp_generate_critical_css() {
	if ( ! isset( $_REQUEST['url'] ) || ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'fdp_generate_critical_css' ) ) {
		die();
		exit;
	}
	$page_url = sanitize_text_field( $_REQUEST['url'] );
	$opts     = eos_dp_get_option( 'eos_dp_critical_css' );
	$valueArr = json_decode( stripslashes( $opts['fdp-opt-api_key'] ), true );
	if ( $valueArr && ! empty( $valueArr ) && isset( $valueArr['value'] ) ) {
		$api_key  = $valueArr['value'];
		$endpoint = 'https://criticalcss.com/api/premium/generate';
		$args     = array(
			'headers' => apply_filters(
				'fdp_critical_css_headers',
				array(
					'User-Agent'    => 'Freesoul Deactivate Plugins v' . EOS_DP_VERSION,
					'Content-type'  => 'application/json; charset=utf-8',
					'Authorization' => 'JWT ' . $api_key,
					'Connection'    => 'close',
				)
			),
			// Body must be JSON.
			'body'    => json_encode(
				apply_filters(
					'fdp_critical_css_api_generate_body',
					array(
						'url'    => $page_url,
						'aff'    => 1,
						'aocssv' => 'FDP_' . EOS_DP_VERSION,
					)
				),
				JSON_UNESCAPED_SLASHES
			),
		);
		$req  = wp_remote_post( $endpoint, $args );
		$code = wp_remote_retrieve_response_code( $req );
		$body = json_decode( wp_remote_retrieve_body( $req ), true );
		// todo.
	}
}

add_action( 'wp_ajax_eos_dp_check_license_status', 'eos_dp_check_license_status' );
// Check license status.
function eos_dp_check_license_status() {
	if ( ! current_user_can( 'activate_plugins' ) || ! isset( $_POST['data'] ) ) {
		return;
	}
	$data = json_decode( sanitize_text_field( stripslashes( $_POST['data'] ) ) ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	if ( ! isset( $data->license_code ) || ! isset( $data->license_email ) || ! isset( $data->nonce ) || ! wp_verify_nonce( esc_attr( $data->nonce ), 'fdp_check_license_status' ) ) {
		die();
		exit;
	}
	$response = array();
	if ( sanitize_email( $data->license_email ) !== $data->license_email ) {
		$response['error'] = esc_html__( 'Email not valid', 'eos-dp-pro' );
		echo wp_json_encode( $response );
		die();
		exit;
	}
	if ( esc_attr( $data->license_code ) !== $data->license_code ) {
		$response['error'] = esc_html__( 'License not valid', 'eos-dp-pro' );
		echo wp_json_encode( $response );
		die();
		exit;
	}
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-license-manager.php';
	$licenseCode  = esc_attr( $data->license_code );
	$licenseEmail = sanitize_email( $data->license_email );
	if ( FDPProLicenseManager::CheckWPPlugin( $licenseCode, $licenseEmail, $error, $responseObj, EOS_DP_PRO_PLUGIN_FILE ) ) {
		$expire_date          = isset( $responseObj->expire_date ) && $responseObj->expire_date ? $responseObj->expire_date : false;
		$support_date         = isset( $responseObj->support_end ) && $responseObj->support_end ? $responseObj->support_end : false;
		$response['is_valid'] = isset( $responseObj->is_valid ) && $responseObj->is_valid ? esc_html__( 'License successfully vefified', 'eos-dp-pro' ) : esc_html__( 'License not valid', 'eos-dp-pro' );
		$response['updates']  = $expire_date ? sprintf( esc_html__( 'This license will expire on %s.' ), $expire_date ) : sprintf( esc_html__( 'The access to updates was expired on %s', 'eos-dp-pro' ), $expire_date );
		$response['support']  = $support_date ? sprintf( esc_html__( 'The access to premium support will expire on %s.' ), $support_date ) : sprintf( esc_html__( 'The access to premium support was expired on %s', 'eos-dp-pro' ), $support_date );
		echo wp_json_encode( $response );
		die();
		exit;
	}
}

add_action( 'wp_ajax_eos_dp_dismiss_fatal_error_notice', 'eos_dp_dismiss_fatal_error_notice' );
// Dismiss fatal errror notice.
function eos_dp_dismiss_fatal_error_notice() {
	if ( isset( $_POST['nonce'] ) && current_user_can( 'activate_plugins' ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'eos_dp_dismiss_fatal_error_notice' ) ) {
		delete_site_transient( 'fdp_plugin_disabledd_fatal_error' );
	}
}
add_action( 'wp_ajax_eos_dp_dismiss_notice', 'eos_dp_dismiss_notice' );
// Dismiss notice.
function eos_dp_dismiss_notice() {
	if ( ! isset( $_POST['data'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'eos_dp_dismiss_notice' ) ) {
		die();
		exit;
	}
	$data = json_decode( sanitize_text_field( stripcslashes( $_POST['data'] ) ), true ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	if ( isset( $data['key'] ) ) {
		$user_id   = get_current_user_id();
		$user_meta = get_user_meta( $user_id, 'fdp_admin_notices', true );
		if ( ! $user_meta || ! is_array( $user_meta ) ) {
			$user_meta = array();
		}
		$user_meta[ sanitize_key( $data['key'] ) ] = 'dismissed';
		if ( 'plugin_fatal_error' === $data['key'] ) {
			delete_site_transient( 'fdp_plugin_disabledd_fatal_error' );
		}
		update_user_meta( $user_id, 'fdp_admin_notices', $user_meta );
		delete_site_transient( 'fdp_admin_notice_rewrite_rules' );
	}
}

add_action( 'wp_ajax_eos_dp_move_option_to_filesystem', 'eos_dp_move_option_to_filesystem' );
// Move option to the filesystem.
function eos_dp_move_option_to_filesystem() {
	eos_dp_check_intentions_and_rights( 'eos_dp_move_option_to_filesystem' );
	$opts = eos_dp_get_option( 'eos_dp_opts' );
	if ( isset( $opts['filesystem_last_status'] ) && 'ok' === $opts['filesystem_last_status'] ) {
		$opts['skip_db_for_archives'] = 'true';
		eos_dp_update_option( 'eos_dp_opts', $opts );
		delete_site_option( 'eos_dp_archives' );
		echo 'success';
	} else {
		'error';
	}
	die();
	exit;
}
add_action( 'wp_ajax_eos_dp_reset_fdp', 'eos_dp_reset_fdp' );
// Reset all the settings of FDP.
function eos_dp_reset_fdp() {
	eos_dp_check_intentions_and_rights( 'eos_dp_reset_fdp' );
	if ( isset( $_REQUEST['data'] ) && 'reset' === sanitize_text_field( $_REQUEST['data'] ) ) {
		define( 'FDP_RESET_SETTINGS', true );
		require_once EOS_DP_PLUGIN_DIR . '/uninstall.php';
		if ( file_exists( EOS_DP_PLUGIN_DIR . '-pro/uninstall.php' ) ) {
			require_once EOS_DP_PLUGIN_DIR . '-pro/uninstall.php';
		}
		eos_dp_update_plugins_slugs_names();
		echo 1;
	}
	die();
	exit;
}

add_action( 'wp_ajax_eos_dp_filesystem_db', 'eos_dp_filesystem_db' );
// Move option from filesystem to DB.
function eos_dp_filesystem_db() {
	if ( ! isset( $_POST['data'] ) ) {
		die();
		exit;
	}
	eos_dp_check_intentions_and_rights( 'eos_dp_filesystem_db' );
	$opts   = eos_dp_get_option( 'eos_dp_opts' );
	$arr    = json_decode( sanitize_text_field( stripslashes( wp_json_encode( $_POST['data'] ) ) ), true ); // @codingStandardsIgnoreLine. We sanitize after stripslashes.
	$option = sanitize_key( $arr['option'] );
	$to     = sanitize_key( $arr['to'] );

	$skip_db = isset( $opts[ 'skip_db_for' . str_replace( 'eos_dp', '', $option ) ] ) && 'true' === $opts[ 'skip_db_for' . str_replace( 'eos_dp', '', $option ) ];

	$value = eos_dp_get_option( $option );
	if ( $skip_db && 'database' === $to ) {
		eos_dp_from_filesystem_to_db( $option, $value );
	} elseif ( ! $skip_db && 'filesystem' === $to ) {
		$moved = eos_dp_save_option_to_filesystem( $option, $value );
		if ( $moved ) {
			$main_opts = eos_dp_get_option( 'eos_dp_opts' );
			$main_opts[ 'skip_db_for_' . str_replace( 'eos_dp_', '', $option ) ] = 'true';
			eos_dp_update_option( 'eos_dp_opts', $main_opts );
			eos_dp_update_option( $option, '' );
		}
	}
	echo 1;
	die();
	exit;
}

// Move option values from DB to filesystem.
function eos_dp_save_option_to_filesystem( $option, $value ) {
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		$updated = false;
		$creds   = request_filesystem_credentials( admin_url(), '', false, false, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			return false;
		}
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$upload_dirs = wp_upload_dir();
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP' );
		}
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP/fdp-options' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP/fdp-options' );
			$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/fdp-options/index.html', '', FS_CHMOD_FILE );
		}
		if ( ! file_exists( $upload_dirs['basedir'] . '/FDP/fdp-options/index.html' ) ) {
			$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/fdp-options/index.html', '', FS_CHMOD_FILE );
		}
		$arrFiles = glob( $upload_dirs['basedir'] . '/FDP/fdp-options/' . sanitize_key( substr( md5( $option ), 0, 8 ) ) . '*.json' );
		foreach ( $arrFiles as $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}
		return $wp_filesystem->put_contents(
			$upload_dirs['basedir'] . '/FDP/fdp-options/' . sanitize_key( substr( md5( $option ), 0, 8 ) ) . '-key-' . substr( md5( time() ), 0, 8 ) . '.json',
			wp_json_encode( $value ),
			FS_CHMOD_FILE
		);
	}
}

// Move option values from filesystem to DB.
function eos_dp_from_filesystem_to_db( $option, $value ) {
	$upload_dirs = wp_upload_dir();
	$arrFiles    = glob( $upload_dirs['basedir'] . '/FDP/fdp-options/' . sanitize_key( substr( md5( $option ), 0, 8 ) ) . '*.json' );
	foreach ( $arrFiles as $file ) {
		if ( file_exists( $file ) ) {
			$opts_from_file = file_get_contents( $file );
			if ( $opts_from_file && '' !== $opts_from_file ) {
				$opts = json_decode( sanitize_text_field( $opts_from_file ), true );
				eos_dp_update_option( $option, $opts );
				$main_opts = eos_dp_get_option( 'eos_dp_opts' );
				if ( isset( $main_opts[ 'skip_db_for_' . str_replace( 'eos_dp_', '', $option ) ] ) ) {
					unset( $main_opts[ 'skip_db_for_' . str_replace( 'eos_dp_', '', $option ) ] );
				}
				if ( eos_dp_update_option( 'eos_dp_opts', $main_opts ) ) {
					unlink( $file );
					break;
				}
			}
		}
	}
}

add_action(
	'updated_option',
	// Update FDP last saved timestamp.
	function( $option, $old_value, $value ) {
		if ( false !== strpos( $option, 'eos_dp_' ) || in_array( $option, array( 'eos_post_types_plugins' ) ) ) {
			eos_dp_update_option( 'fdp_site_id', sanitize_text_field( fdp_site_id() ) );
			eos_dp_update_option( 'fdp_last_save', time() );
		}
	},
	10,
	3
);
