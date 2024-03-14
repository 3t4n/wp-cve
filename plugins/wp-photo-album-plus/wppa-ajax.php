<?php
/* wppa-ajax.php
*
* Functions used in ajax requests
* Version: 8.6.04.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Load admin-ajax.php if wppa_ajax is in query args
function wppa_ajax_include() {
global $wp_query;

	if ( $wp_query->get( 'wppa_ajax' ) === '1' ) {
		include_once ABSPATH . '/wp-admin/admin-ajax.php';
		exit;
	}
}
add_action( 'template_redirect', 'wppa_ajax_include' );

// Add rewrite rule
function wppa_ajax_rewrite_rule() {

	add_rewrite_rule( 'wppaajax/?$', 'index.php?wppa_ajax=1', 'top' );
	$rewrite_rules = wppa_get_option( 'rewrite_rules', array() );
	if ( ! is_array( $rewrite_rules ) || ! in_array( 'index.php?wppa_ajax=1', $rewrite_rules ) ) {
		flush_rewrite_rules();
	}
}
add_action( 'init', 'wppa_ajax_rewrite_rule' );

// Add wppa_ajax to query vars
function wppa_ajax_query_vars( $query_vars ) {

	$query_vars[] = 'wppa_ajax';
	return $query_vars;
}
add_filter( 'query_vars', 'wppa_ajax_query_vars' );

// Link ajax callback function
add_action( 'wp_ajax_wppa', 'wppa_ajax_callback' );
add_action( 'wp_ajax_nopriv_wppa', 'wppa_ajax_callback' );

function wppa_ajax_callback() {
global $wpdb;
global $wppa_session;
global $wppa_log_file;
global $wppa_opt;
global $wppa;

	wppa( 'ajax', true );
	if ( ! defined( 'DOING_WPPA_AJAX' ) ) {
		define( 'DOING_WPPA_AJAX', true );
	}
	wppa( 'error', '0' );
	wppa( 'out', '' );
	if ( ! isset( $wppa_session['page'] ) ) {
//		wppa_begin_session();
	}
	if ( ! isset( $wppa_session['page'] ) ) {
		$wppa_session['page'] = '0';
		$wppa_session['ajax'] = '0';
	}
	$wppa_session['page']--;
	$wppa_session['ajax']++;

	$wppa_action = wppa_get( 'action' );

	if ( wppa_switch( 'log_ajax' ) && wppa_get( 'action' ) != 'heartbeat' && wppa_get( 'option' ) != 'heartbeat' ) {
		wppa_log( 'ajax', 'Script = ' . basename( $_SERVER['SCRIPT_FILENAME'] ) . ', Args = ' . var_export($_REQUEST,true) );
	}

	// Any runtime modifyable settings?
	foreach( array_keys( $_GET ) as $key ) {
		$value = sanitize_text_field( $_GET[$key] );
		if ( substr( $key, 0, 5 ) == 'wppa_' ) {
			if ( isset( $wppa_opt[$key] ) ) {
				$wppa_opt[$key] = $value;
			}
		}
	}

	switch ( $wppa_action ) {
		case 'log':
			if ( ! wppa_get( 'message' ) ) wppa_exit();
			wppa_log( 'cli', wppa_get( 'message' ) );
			wppa_exit();
			break;
		case 'mailinglist':

			// Sanitize input
			$nonce 		= wppa_get( 'ntfy-nonce' );
			$crypt 		= wppa_get( 'crypt' );
			$list_type 	= wppa_get( 'list' );
			$onoff 		= wppa_get( 'onoff' );
			$user_id 	= wppa_get( 'user' );

			// If not loggedin, send message and quit
			if ( ! is_user_logged_in() ) {
				wppa_email_quit_message( __( 'You must be loggedin to unsubscribe from a mailinglist', 'wp-photo-album-plus' ), 'red' );
			}

			// If can not edit email subscriptions, can only change self
			if ( ! current_user_can( 'wppa_edit_email' ) || ! $user_id ) {
				if ( $user_id != wppa_get_user( 'id' ) ) {
					wppa_email_quit_message( __( 'You can only unsubscribe yourself from a mailinglist', 'wp-photo-album-plus' ), 'red' );
				}
			}

			// From edit_email admin page or dashboard widget?
			if ( $nonce ) {
				if ( ! wp_verify_nonce( $nonce, 'wppa-ntfy-nonce' ) ) {
					wppa_secfail( '80' );
				}
			}

			// From email unsubscribe link
			elseif ( $crypt ) {
				$user = get_user_by( 'ID', $user_id );
				if ( $user ) {
					if ( $crypt != crypt( $list_type . $user->ID . $user->login_name, $user->display_name ) ) {
						wppa_secfail( '81' ); // Crypt tampered
					}
				}
				else { // No existing user
					wppa_secfail( '83' ); // Should never get here
				}
			}

			// From nothing valid
			else {
				wppa_secfail( '82' );
			}

			// Existing list type?
			if ( ! in_array( $list_type, array( 'newalbumnotify',
												'feuploadnotify',
												'photoapproved',
												'commentnotify',
												'commentapproved',
												'commentprevious',
												'moderatephoto',
												'moderatecomment',
												'subscribenotify',
												) ) ) {
				wppa_email_quit_message( __( 'Requested mailinglist does not exist', 'wp-photo-album-plus' ), 'red' );
			}

			// On/Off valid?
			if ( ! in_array( $onoff, array( 'on', 'off' ) ) ) {
				wppa_email_quit_message( __( 'Invalid data found', 'wp-photo-album-plus' ), 'red' );
			}

			// Prepare additional data
			$mailinglist 	= wppa_get_option( 'wppa_mailinglist_' . $list_type, '' );
			$userarray 		= wppa_index_string_to_array( $mailinglist );

			$email_types = array(
					'newalbumnotify' 	=> __('New album', 'wp-photo-album-plus'),
					'feuploadnotify' 	=> __('Upload', 'wp-photo-album-plus'),
					'commentnotify' 	=> __('Comment', 'wp-photo-album-plus'),
					'commentprevious' 	=> __('Comment previous', 'wp-photo-album-plus'),
					'moderatephoto' 	=> __('Moderate photo', 'wp-photo-album-plus'),
					'moderatecomment' 	=> __('Moderate comment', 'wp-photo-album-plus'),
					'photoapproved' 	=> __('Photo approved', 'wp-photo-album-plus'),
					'commentapproved' 	=> __('Comment approved', 'wp-photo-album-plus'),
					'subscribenotify' 	=> __('Subscribe/unsubscribe', 'wp-photo-album-plus'),
				);

			// Dispatch on list type
			switch( $list_type ) {
				case 'newalbumnotify':
				case 'feuploadnotify':
				case 'photoapproved':
				case 'commentnotify':
				case 'commentapproved':
				case 'commentprevious':
				case 'moderatephoto':
				case 'moderatecomment':
				case 'subscribenotify':

					if ( $onoff == 'on' ) {
						$userarray[] = $user_id;
						$msg = sprintf( __( 'You have been added to mailinglist %s', 'wp-photo-album-plus' ), $email_types[$list_type] );
					}
					else {
						$userarray = array_diff( $userarray, array( $user_id ) );
						$msg = sprintf( __( 'You have been removed from mailinglist %s', 'wp-photo-album-plus' ), $email_types[$list_type] );
					}
					$mailinglist = wppa_index_array_to_string( $userarray );

					update_option( 'wppa_mailinglist_' . $list_type, $mailinglist );
					wppa_schedule_mailinglist( 'subscribenotify', $list_type, $user_id, $onoff, 0, 0, 5 );

					// If unsubscribe link from email. format and send output
					if ( $crypt ) {
						wppa_email_quit_message( $msg, 'green' );
					}
					break;

				default:

					$msg = __( 'Requested mailinglist is not implemented', 'wp-photo-album-plus' );
					if ( $crypt ) {
						wppa_email_quit_message( $msg, 'red' );
					}
					break;
			}
			wppa_exit();
			break;
		case 'delexportzips':
			$dir = WPPA_DEPOT_PATH;
			$files = wppa_glob( $dir . '/*.zip' );
			if ( $files ) {
				foreach( $files as $file ) {
					wppa_unlink( $file );
					$id = substr( wppa_strip_ext( basename( $file ) ), 6 );
					$usr = wppa_get_user();
					$tr = "wppa-album-$id-last-export-$usr";
					delete_transient( $tr );
				}
			}
			wppa_exit();
			break;
		case 'getqrcode':
			$nonce 	= wppa_get( 'qr-nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-qr-nonce' ) ) {
				die( 'Security check failure' );
			}
			$url = wppa_get( 'url' );
			$result = wppa_create_qrcode_cache( $url, wppa_opt( 'qr_size' ) );
			wppa_echo( $result . '|' . wppa_convert_to_pretty( $url ) );
			wppa_exit();
			break;
		case 'gettogo':
			$slug 	= 	wppa_get( 'slug' );
			$result = 	wppa_get_option( $slug . '_togo', '' ) .
						'|' .
						wppa_get_option( $slug . '_status', '' );
			wppa_echo( $result );
			if ( wppa_get_option( $slug . '_status', '' ) == __( 'Ready', 'wp-photo-album-plus' ) ) {
				delete_option( $slug . '_status' );
			}
			wppa_exit();
			break;

		case 'getssiptclist':
			$tag 		= '';
			$mocc 		= '1';
			$oldvalue 	= '';

			$tag 	= str_replace( 'H', '#', wppa_get( 'iptctag' ) );
			$mocc 	= wppa_get( 'occur' );

			if ( strpos( $wppa_session['supersearch'], ',' ) !== false ) {
				$ss_data = explode( ',', $wppa_session['supersearch'] );
				if ( count( $ss_data ) == '4' ) {
					if ( $ss_data['0'] == 'p' ) {
						if ( $ss_data['1'] == 'i' ) {
							if ( $ss_data['2'] == wppa_get( 'iptctag' ) ) {
								$oldvalue = $ss_data['3'];
							}
						}
					}
				}
			}
			$iptcdata 	= $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT description
															   FROM $wpdb->wppa_iptc
															   WHERE photo > 0 AND tag = %s
															   ORDER BY description", $tag ), ARRAY_A );
			$last 		= '';
			$any 		= false;
			$result 	= '';
			if ( is_array( $iptcdata ) ) foreach( $iptcdata as $item ) {
				$desc = sanitize_text_field( $item['description'] );

				if ( $desc != $last ) {
					$sel = ( $oldvalue && $oldvalue == $desc ) ? 'selected' : '';
					if ( $sel ) $result .= 'selected:' . $oldvalue;
					$ddesc = strlen( $desc ) > '32' ? substr( $desc, 0, 30 ) . '...' : $desc;
					$result .= 	'
					<option
						value="' . esc_attr( $desc ) . '"
						class="wppa-iptclist-' . $mocc . '" ' .
						$sel . '
						>' .
						$ddesc . '
					</option>';
					$last = $desc;
					$any = true;
				}
			}
			if ( ! $any ) {
				$query = $wpdb->prepare( "UPDATE $wpdb->wppa_iptc
										  SET status = 'hide'
										  WHERE photo = 0 AND tag = %s", $tag );
				$wpdb->query( $query );
			}
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'getssexiflist':

			$tag 		= '';
			$brand 		= '';
			$mocc 		= '1';
			$oldvalue 	= '';
			$ss_data 	= array();
			$result 	= '';

			$tag 	= str_replace( 'H', '#', substr( wppa_get( 'exiftag' ), 0, 6 ) );
			$brand 	= substr( wppa_get( 'exiftag' ), 6 );
			$mocc 	= wppa_get( 'occur' );

			if ( strpos( $wppa_session['supersearch'], ',' ) !== false ) {
				$data = explode( ',', $wppa_session['supersearch'] );
				if ( count( $data ) >= '4' ) {

					// Value may contain commas
					for ( $i=0; $i<3; $i++ ){
						$ss_data[$i] = $data[$i];
						unset( $data[$i] );
					}
					$ss_data[3] = implode( ',', $data );

					if ( $ss_data['0'] == 'p' ) {
						if ( $ss_data['1'] == 'e' ) {
							if ( $ss_data['2'] == wppa_get( 'exiftag' ) ) {
								$oldvalue = $ss_data['3'];
							}
						}
					}
				}
			}

			if ( $brand ) {
				$exifdata = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT f_description
																 FROM $wpdb->wppa_exif
																 WHERE photo > 0
																 AND tag = %s
																 AND brand = %s
																 AND f_description <> ''
																 ORDER BY f_description", $tag, $brand ), ARRAY_A );
			}
			else {
				$exifdata = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT f_description
																 FROM $wpdb->wppa_exif
																 WHERE photo > 0
																 AND tag = %s
																 AND f_description <> ''
																 ORDER BY f_description", $tag ), ARRAY_A );
			}

			// Make the data sortable.
			foreach ( array_keys( $exifdata ) as $key ) {
				$temp = $exifdata[$key]['f_description'];
				$temp = trim( $temp, ' smf/.px' );

				if ( strpos( $temp, '/' ) ) {

					$t = explode( '/', $temp );
					if ( count( $t ) == 2 && is_numeric( $t[0] ) && is_numeric( $t[1] ) ) {
						$temp = $t;
						if ( $temp[1] != 0 ) {
							$temp = $temp[0] / $temp[1];
						}
						else {
							$temp = 999999;
						}
					}

				}
				if ( is_numeric( $temp ) ) {
					$exifdata[$key]['sort'] = sprintf( '%020.12f', $temp );
				}
				else {
					$exifdata[$key]['sort'] = $exifdata[$key]['f_description'];
				}
			}

			// Sort
			$exifdata = wppa_array_sort( $exifdata, 'sort' );

			// Make the selectionbox content
			$any 		= false;
			if ( ! empty( $exifdata ) ) foreach( $exifdata as $item ) {
				$desc = sanitize_text_field( $item['f_description'] );

				if ( $desc ) {

					$sel = ( $oldvalue && $oldvalue == $desc ) ? 'selected' : '';
					$ddesc = strlen( $desc ) > '42' ? substr( $desc, 0, 40 ) . '...' : $desc;
					if ( wppa_is_valid_rational( $ddesc, false  ) ) {
						$t = explode( '/', $ddesc );
						$l = strlen( $ddesc );
						$ddesc = '(' . sprintf( '%5.2f', $t[0]/$t[1] ) . ') ' . $ddesc;
					}

					$result .= '
					<option
						value="' . esc_attr( $desc ) . '"
						class="wppa-exiflist-' . $mocc . '" ' .
						$sel . '
						>' .
						$ddesc . '
					</option>';
					$any = true;
				}
			}

			// Cleanup possible unused label
			if ( ! $any ) {
				$query = $wpdb->prepare( "UPDATE $wpdb->wppa_exif
										  SET status = 'hide'
										  WHERE photo = 0
										  AND tag = %s", $tag );
				$wpdb->query( $query );
			}
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'front-edit':				// Fetch the html for edit dialog

			// Is the call valid?
			$photo = wppa_get( 'photo-id' );
			if ( ! $photo ) {
				die( 'Missing required argument' );
			}

			// Is this user allowed to edit thisphoto?
			$ok = wppa_may_user_fe_edit( $photo );

			// No rights, die
			if ( ! $ok ) die( 'You do not have sufficient rights to do this' );

			// Do it
			require_once 'wppa-photo-admin-autosave.php';

			wppa_fe_edit_photo( $photo );

			// Done
			wppa_exit();
			break;

		case 'update-photo-new':			// Do the actual edit update

			// Get photo id
			$photo = wppa_get( 'photo-id' );

			// Is the call valid?
			$nonce 	= wppa_get( 'nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce-' . $photo ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				die( 'Security check falure' );
			}
			if ( ! $photo ) {
				die( 'Missing required argument' );
			}
			if ( ! wppa_may_user_fe_edit( $photo ) ) {
				die( 'Insufficient accessrights' );
			}

			// Reload after?
			if ( wppa_get( 'upn-reload', '', 'text' ) ) {
				set_transient( 'wppa_rela_' . wppa_get_user(), 'on', MONTH_IN_SECONDS );
			}
			else {
				set_transient( 'wppa_rela_' . wppa_get_user(), 'off', MONTH_IN_SECONDS );
			}

			// Init updatable fields
			$fields = array();

			// Name
			if ( wppa_get( 'upn-name' ) ) {
				$name = wppa_get( 'upn-name' );
				$old_name = wppa_get_photo_item( $photo, 'name' );
				if ( $name != $old_name ) {
					$fields['name'] = $name;
				}
			}

			// Description
			if ( wppa_get( 'upn-description' ) ) {
				$desc = wppa_get( 'upn-description' );
				$old_desc = wppa_get_photo_item( $photo, 'description' );
				if ( $desc != $old_desc ) {
					$fields['description'] = $desc;
				}
			}

			// Tags
			if ( wppa_get( 'upn-tags' ) ) {
				$tags = wppa_get( 'upn-tags' );
				$old_tags = wppa_get_photo_item( $photo, 'tags' );
				if ( $tags != $old_tags ) {
					$fields['tags'] = $tags;
				}
			}

			// Custom fields
			$custom = wppa_get_photo_item( $photo, 'custom' );
			if ( $custom ) {
				$custom_data = wppa_unserialize( $custom );
			}
			else {
				$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
			}
			for ( $i=0;$i<10;$i++ ) {
				if ( wppa_get( 'custom_' . $i ) && wppa_opt( 'custom_caption_' . $i ) && wppa_switch( 'custom_edit_' . $i ) ) {
					$custom_data[$i] = wppa_get( 'custom_' . $i );
				}
			}
			$custom = serialize( $custom_data );
			$old_custom = wppa_get_photo_item( $photo, 'custom' );
			if ( $custom != $old_custom ) {
				$fields['custom'] = $custom;
			}

			// Do the update
			wppa_update_photo( $photo, $fields );

			// Report results back to client
			wppa_json_photo_update( $photo, '', '0', $fields );

			wppa_exit();
			break;

		case 'do-comment':
			$data = json_decode( stripslashes( $_POST['data'] ), true );
			if ( is_array( $data ) ) {
				$_REQUEST = array_merge( $_REQUEST, $data );
			}

			// Validate args
			$mocc 		= wppa_get( 'occur' );
			$nonce 		= $data['nonce']; //wppa_get( 'nonce' );
			$photoid 	= wppa_get( 'photoid' );
			$commentid 	= wppa_get( 'comid' );

			// Security check
			if ( wppa_switch( 'direct_comment' ) ) {
				if ( ! $photoid || ( wppa_get_photo_item( $photoid, 'album' ) < '1' ) ) {
					_e( 'Missing or invalid photo id' , 'wp-photo-album-plus' );
					wppa_exit();
				}
				wppa_log('dbg', 'Bypassed nonce');
			}
			else {
				if ( ! wp_verify_nonce( $nonce, 'wppa-check' ) ) {
					wppa_secfail( '70' );
				}
				if ( ! $photoid || ( wppa_get_photo_item( $photoid, 'album' ) < '1' ) ) {
					wppa_secfail( '71' );
				}
			}

			// Check login
			if ( ! is_user_logged_in() ) {
				wppa_secfail( '72' );
			}

			// Check role
			if ( ! wppa_check_user_comment_role() ) {
				wppa_secfail( '73' );
			}

			wppa( 'mocc', $mocc );
			wppa( 'comment_photo', $photoid );
			wppa( 'comment_id', $commentid );

			$comment_allowed = ! wppa_user_is_basic() && is_user_logged_in();
			if ( wppa_switch( 'show_comments' ) && $comment_allowed ) {
				wppa_do_comment( $photoid );		// Process the comment
				if ( wppa_switch( 'search_comments' ) ) wppa_index_update( 'photo', $photoid );
			}
			wppa( 'no_esc', true );
			$result = wppa_comment_html( $photoid, $comment_allowed );
			wppa_echo( $result );	// Retrieve the new commentbox content
			wppa_exit();
			break;

		case 'import':
			require_once 'wppa-import.php';
			wppa_import_photos();
			wppa_exit();
			break;

		case 'approve':
			$iret = 0;
			$pid = wppa_get( 'photo-id' );
			$cid = wppa_get( 'comment-id' );

			if ( ! current_user_can( 'wppa_moderate' ) && ! current_user_can( 'wppa_comments' ) ) {
				_e( 'You do not have the rights to moderate photos this way' , 'wp-photo-album-plus' );
				wppa_exit();
			}

			if ( $pid && current_user_can( 'wppa_moderate' ) ) {
				$iret = wppa_update_photo( $pid, ['status' => 'publish'] );
				if ( $iret ) {
					wppa_flush_upldr_cache( 'photoid', $pid );
					$alb = $wpdb->get_var( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos
															WHERE id = %d", $pid ) );
					wppa_clear_taglist();
					wppa_invalidate_treecounts( $alb );
					wppa_schedule_mailinglist( 'photoapproved', $alb, $pid );
				}
			}

			if ( $cid && current_user_can( 'wppa_moderate' ) ) {
				$iret = wppa_update_comment( $cid, ['status' => 'approved'] );
				if ( $iret ) {
					wppa_schedule_mailinglist( 'commentapproved', 0, 0, $cid );
					wppa_add_credit_points( wppa_opt( 'cp_points_comment_appr' ),
											__( 'Photo comment approved' , 'wp-photo-album-plus' ),
											$pid,
											'',
											wppa_get_photo_item( $pid, 'owner' )
											);
				}
			}

			if ( $iret ) {
				if ( wppa_switch( 'search_comments' ) ) {
					wppa_update_photo( $pid );
				}
				wppa_echo( 'OK' );
			}
			else {
				if ( $pid ) {
					if ( current_user_can( 'wppa_moderate' ) ) {
						wppa_echo( sprintf( __( 'Failed to update status of photo %d', 'wp-photo-album-plus' ), $pid )."\n".__( 'Please refresh the page', 'wp-photo-album-plus' ) );
					}
					else {
						wppa_secfail( '21' );
					}
				}
				if ( $cid ) {
					wppa_echo( sprintf( __( 'Failed to update stutus of comment %d' , 'wp-photo-album-plus' ), $cid )."\n".__( 'Please refresh the page', 'wp-photo-album-plus' ) );
				}
			}
			wppa_exit();

		case 'remove':

			$pid = wppa_get( 'photo-id' );
			$cid = wppa_get( 'comment-id' );

			// Remove photo
			if ( $pid ) {
				if ( wppa_may_user_fe_delete( $pid ) ) {

					wppa_delete_photo( $pid );
					wppa_echo( 'OK||' . __( 'Photo removed', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
			}

			// Remove comment
			elseif ( $cid ) {

				// Am i allowed to do this?
				if ( ! current_user_can( 'wppa_moderate' ) && ! current_user_can( 'wppa_comments' ) ) {
					_e( 'You do not have the rights to moderate photos this way', 'wp-photo-album-plus' );
					wppa_exit();
				}

				$photo = $wpdb->get_var( $wpdb->prepare( "SELECT photo FROM $wpdb->wppa_comments
														  WHERE id = %d", $cid ) );

				$iret = wppa_del_row( WPPA_COMMENTS, 'id', $cid );

				if ( $iret ) {
					if ( wppa_opt( 'search_comments' ) ) {
						wppa_update_photo( $pid );
					}
					wppa_echo( 'OK||' . __( 'Comment removed', 'wp-photo-album-plus' ) );
				}
				else {
					wppa_echo( __( 'Could not remove comment', 'wp-photo-album-plus' ) );
				}
				wppa_exit();
			}

			// Remove request issued, but it is not a photo and not a comment
			wppa_echo( __( 'Unexpected error', 'wp-photo-album-plus' ) );
			wppa_exit();

		case 'downloadalbum':

			// Feature enabled?
			if ( ! wppa_switch( 'allow_download_album' ) ) {
				wppa_echo( '||ER||' . __( 'This feature is not enabled on this website', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Restricted and not admin?
			if ( wppa_switch( 'download_album_is_restricted' ) && ! wppa_user_is_admin() ) {
				wppa_echo( '||ER||' . __( 'This feature is restricted to administrators', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Validate args
			$alb = wppa_get( 'album-id' );

			// Get all items in the album
			$photos = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE album = %d", $alb ) );

			// Only visible photos are downloadable
			if ( is_array( $photos ) ) foreach( array_keys( $photos ) as $i ) {
				if ( ! wppa_is_photo( $photos[$i] ) || ! wppa_is_photo_visible( $photos[$i] ) ) {
					unset( $photos[$i] );
				}
			}

			// Anything left?
			if ( ! $photos || count( $photos ) == '0' ) {
				wppa_echo( '||ER||' . __( 'The album is empty', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Open zipfile
			if ( ! class_exists( 'ZipArchive' ) ) {
				wppa_echo( '||ER||' . __( 'Unable to create zip archive', 'wp-photo-album-plus' ) );
				wppa_exit();
			}
			$zipfilename = wppa_get_album_name( $alb );
			$zipfilename = sanitize_file_name( $zipfilename . '.zip' ); 				// Remove illegal chars
			$zipfilepath = WPPA_UPLOAD_PATH . '/temp/' . $zipfilename;
			$wppa_zip = new ZipArchive;
			$iret = $wppa_zip->open( $zipfilepath, 1 );
			if ( $iret !== true ) {
				wppa_echo( '||ER||'.sprintf( __( 'Unable to create zip archive. code = %s' , 'wp-photo-album-plus' ), $iret ) );
				wppa_exit();
			}

			// Add photos to zip
			$stop = false;
			foreach ( $photos as $id ) {
				if ( wppa_is_time_up() ) {
					wppa_log( 'war', 'Time up during album to zip creation' );
					$stop = true;
				}
				else {
					$p = wppa_cache_photo( $id );
					$source = ( wppa_switch( 'download_album_source' ) && is_file( wppa_get_source_path( $id ) ) ) ? wppa_get_source_path( $id ) : wppa_get_photo_path( $id );
					if ( is_file( $source ) ) {
						$dest = $p['filename'] ? wppa_sanitize_file_name( $p['filename'] ) : wppa_sanitize_file_name( wppa_strip_ext( $p['name'] ).'.'.$p['ext'] );
						$dest = wppa_fix_poster_ext( $dest, $id );
						$iret = $wppa_zip->addFile( $source, $dest );

						// To prevent too may files open, and to have at least a file when there are too many photos, close and re-open
						$wppa_zip->close();
						$wppa_zip->open( $zipfilepath );
					}
				}
				if ( $stop ) break;
			}

			// Close zip and return
			$zipcount = $wppa_zip->numFiles;
			$wppa_zip->close();

			// A zip is created
			$desturl = WPPA_UPLOAD_URL.'/temp/'.$zipfilename;
			wppa_echo( $desturl.'||OK||' );
			if ( $zipcount != count( $photos ) ) {
				wppa_echo( sprintf( __( 'Only %s out of %s photos could be added to the zipfile' , 'wp-photo-album-plus' ), $zipcount, count( $photos ) ) );
			}
			wppa_exit();
			break;

		case 'getalbumzipurl':
			$alb = wppa_get( 'album-id' );
			$zipfilename = wppa_get_album_name( $alb );
			$zipfilename = wppa_sanitize_file_name( $zipfilename . '.zip' ); 				// Remove illegal chars
			$zipfilepath = WPPA_UPLOAD_PATH . '/temp/' . $zipfilename;
			$zipfileurl  = WPPA_UPLOAD_URL . '/temp/' . $zipfilename;
			if ( is_file( $zipfilepath ) ) {
				wppa_echo( $zipfileurl );
			}
			else {
				wppa_echo( 'ER' );
			}
			wppa_exit();
			break;

			// Admins choice
		case 'addtozip':

			$photo 			= wppa_get( 'photo-id' );
			$donetoalbum 	= false;
			$alert 			= '';
			$status 		= 'OK'; // assume success

			// Check if the user is allowed to do this
			$choice = wppa_opt( 'admins_choice' );
			if ( ( wppa_user_is_admin() && $choice != 'none' ) ||
				 ( is_user_logged_in() && $choice == 'login' ) ) {
					 // Its ok
				 }
			else {
				$alert .= __( 'You are not allowed to do this (2)', 'wp-photo-album-plus' );
				$status = 'ER';
				wppa_echo( $status . '||' . $alert );
				wppa_exit();
			}

			// Do the copy to album
			if ( wppa_opt( 'admins_choice_action' ) == 'album' || wppa_opt( 'admins_choice_action' ) == 'both' ) {
				$album = wppa_my_get_first_grant_album();
				if ( $album ) {
					$err = wppa_copy_photo( $photo, $album );
					if ( $err ) {
						$alert .= __( 'Could not copy photo', 'wp-photo-album-plus' ) . '. ';
						$status = 'ER';
					}
					else {
						$alert .= __( 'Photo copied to album', 'wp-photo-album-plus' ) . ' ' . str_replace( ["'",'"'], '', wppa_get_album_name( $album ) ) . '. ';
						$donetoalbum = true;
					}
				}
				else {
					$alert .= __( 'No album available to copy to', 'wp-photo-album-plus' ) . '. ';
					$status = 'ER';
				}
			}
			if ( wppa_opt( 'admins_choice_action' ) == 'album' ) {
				wppa_echo( $status . '||' . $alert );
				wppa_exit();
			}

			// Do we have ziparchive on board?
			if ( ! class_exists( 'ZipArchive' ) ) {
				$status = 'ER';
				$alert .= __( 'Unable to create zip archive' , 'wp-photo-album-plus' ) . '. ';
				wppa_echo( $status . '||' . $alert );
				wppa_exit();
			}

			// Verify existance of zips dir
			$zipsdir = WPPA_UPLOAD_PATH . '/zips/';
			if ( ! wppa_is_dir( $zipsdir ) ) wppa_mkdir( $zipsdir );
			if ( ! wppa_is_dir( $zipsdir ) ) {
				$status = 'ER';
				$alert .= __( 'Unable to create zipsdir' , 'wp-photo-album-plus' ) . '. ';;
				wppa_echo( $status . '||' . $alert );
				wppa_exit();
			}

			// Compose the users zip filename
			$zipfile = $zipsdir.wppa_get_user().'.zip';

			// Find the photo data
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE id = %d", $photo ), ARRAY_A );

			// Find the photo file
			if ( is_file ( wppa_get_source_path( $photo ) ) ) {
				$source = wppa_get_source_path( $photo );
			}
			else {
				$source = wppa_get_photo_path( $photo );
			}

			// Add photo to zip
			$wppa_zip = new ZipArchive;
			$wppa_zip->open( $zipfile, 1 );
			$wppa_zip->addFile( $source, wppa_fix_poster_ext( $data['filename'], $photo ) );
			$wppa_zip->close();

			// Add user display name as tag to the item if configured
			if ( wppa_switch( 'choice_is_tag' ) ) {
				$tags = wppa_get_photo_item( $photo, 'tags' );
				$user = wppa_get_user( 'display' );
				$newtags = wppa_sanitize_tags( $tags . $user );
				if ( $tags == $newtags ) {
					$alert .= __( 'Item is already tagged with username', 'wp-photo-album-plus' ) . ' ' . $user . '. ';
				}
				else {
					wppa_update_photo( $photo, ['tags' => $newtags] );
					wppa_clear_taglist();
					$alert .= __( 'Item tagged with username', 'wp-photo-album-plus' ) . ' ' . $user . '. ';
				}
			}

			$alert .= __( 'Photo copied to zipfile', 'wp-photo-album-plus' ) . '. ||' . __( 'Selected', 'wp-photo-album-plus' );
			$status = 'OK';

			// Done!
			wppa_echo( $status . '||' . $alert );
			wppa_exit();
			break;

		case 'removefromzip':

			// Check if the user is allowed to do this
			$photo 	= wppa_get( 'photo-id' );
			$choice = wppa_opt( 'admins_choice' );
			if ( ( wppa_user_is_admin() && $choice != 'none' ) ||
				 ( is_user_logged_in() && $choice == 'login' ) ) {
					 // Its ok
				 }
			else {
				wppa_echo( 'ER||You are not allowed to do this' );
				wppa_exit();
			}

			// Compose the users zip filename
			$zipsdir = WPPA_UPLOAD_PATH.'/zips/';
			$zipfile = $zipsdir.wppa_get_user().'.zip';

			// Find the photo data
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE id = %d", $photo ), ARRAY_A );

			// Remove photo from zip
			$wppa_zip = new ZipArchive;
			$wppa_zip->open( $zipfile );
			$bret = $wppa_zip->deleteName( wppa_fix_poster_ext( $data['filename'], $photo ) );
			$wppa_zip->close();

			// Remove user id as tag to the item if configured
			if ( wppa_switch( 'choice_is_tag' ) ) {
				$tags = preg_replace( '/,'.wppa_get_user( 'display' ).',/siu', ',', $tags );
				wppa_update_photo( $photo, ['tags' => $tags] );
			}

			wppa_echo( 'OK||'.__( 'Removed', 'wp-photo-album-plus' ) );
			wppa_exit();
			break;

		case 'delmyzip':

			// Verify existance of zips dir
			$zipsdir = WPPA_UPLOAD_PATH . '/zips/';
			if ( wppa_is_dir( $zipsdir ) ) {

				// Compose the users zip filename
				$zipfile = $zipsdir.wppa_get_user().'.zip';

				// Check file existance and remove
				if ( is_file( $zipfile ) ) {
					@ unlink( $zipfile );
				}
			}

			// Remove all User displayname tags
			$tag = wppa_get_user( 'display' );
			$items = $wpdb->get_results( "SELECT id, tags FROM $wpdb->wppa_photos
										  WHERE tags LIKE '%" . str_replace( "'", "\'", ',' . $wpdb->esc_like( $tag ) . ',' ) . "%'", ARRAY_A );
			foreach( $items as $item ) {
				$id = $item['id'];
				$tags = preg_replace( '/,'.$tag.',/siu', ',', $item['tags'] );
				$tags = wppa_sanitize_tags( $tags );
				wppa_update_photo( $id, ['tags' => $tags] );
			}
			wppa_clear_taglist();
			wppa_exit();
			break;

		case 'requestinfo':

			// Check if the user is allowed to do this
			if ( ! is_user_logged_in() ) {
				wppa_echo( 'ER||You must be logged in to request info' );
				wppa_exit();
			}

			// Find the photo
			$photo 	= wppa_get( 'photo-id' );

			// The mail content
			$content =
			sprintf( __( 'User %s requested more info about item #%d (%s)', 'wp-photo-album-plus' ),
					 wppa_get_user( 'display' ),
					 $photo,
					 wppa_get_photo_name( $photo )
					 );
			$content .=
			'<br><br>' . __('His request specification is', 'wp-photo-album-plus' ) . '<br>' .

			'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
				<em> ' . sanitize_text_field( wppa_get( 'emailtext', 'text' ) ) . '</em>
			</blockquote>';

			if ( ! function_exists( 'wppa_send_mail' ) ) {
				require_once( 'wppa-mailing.php' );
			}

			// Send the mail
			wppa_send_mail( array(	'to' => get_bloginfo( 'admin_email' ),
									'subj' => __('Request for info'),
									'cont' => $content,
									'photo' => $photo,
									'email' => wppa_get_user( 'email' ),
									'listtype' => 'showemail',
									'replyurl' => '',
									'unsubscribe' => '',
									) );

			// Done
			wppa_echo( 'OK||Request issued' );
			wppa_exit();
			break;

		case 'tinymcedialog':
			$result = wppa_make_tinymce_dialog();
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'tinymcephotodialog':
			$result = wppa_make_tinymce_photo_dialog();
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'tinymcephotodialogfront':
			$result = wppa_make_tinymce_photo_dialog( 'front' );
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'gutenbergphotodialog':
			$result = wppa_make_gutenberg_photo_dialog();
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'gutenbergwppadialog':
			$result = wppa_make_gutenberg_wppa_dialog();
			wppa_echo( $result );
			wppa_exit();
			break;

		case 'getshortcodedrendered':

			// Used by gutenberg and cover preview in album admin

			// Force direct script tags
			global $wppa_gutenberg_preview;
			$wppa_gutenberg_preview = true;

			global $wppa_preview_container_width;

			// On widgets page?
			if ( strpos( $_SERVER['REQUEST_URI'], 'widgets.php' ) !== false ) {
				$wppa_preview_container_width = 650;
				$type = 'widget';
			}

			// On post/page page
			else {
				$wppa_preview_container_width = 880;
				$type = 'page';
			}

			// Ignore lazy, cache and delay
			$wppa_opt['wppa_lazy'] = 'none';
			$wppa_opt['wppa_cache_overrule'] = 'never';
			$wppa_opt['wppa_delay_overrule'] = 'never';

			// Make sure slideshow do not run
			$wppa_opt['wppa_start_slide'] = 'still';

			// Get the shortcode
			$shortcode = wppa_get( 'shortcode', '', 'gutsc' );

			// Prepare environment for rendering
			wppa_load_theme();

			// Render the shortcode
			$result = do_shortcode( $shortcode );

			// Make links and clicks unusable in the result
			$result = str_replace( 'href=', 'data-disabled1=', $result );
			$result = str_replace( 'onclick="', 'onclick="return false;', $result );
			$result = str_replace( 'data-lbtitle=', 'data-disabled2=', $result );
			$result = str_replace( 'data-rel=', 'data-disabled3=', $result );
			$result = str_replace( ['cursor:pointer', 'cursor: pointer'], 'cursor:help', $result );

			// Output the result
			echo ( '
			<style>
				#wppa-gutenberg-preview div {
					position:relative;
				}

				.filmwindow {
					width:' . ( $type == 'widget' ? '622px' : '825px' ) . ' !important;
				}

				.wppa-container-wrapper {
					max-width: 840px;
				}

				.wppa-container-wrapper img {
					cursor: help;
				}

			</style>
			<div
				id="wppa-container-' . $wppa['mocc'] . '"
				style="position:relative;width:100%" >
				<div class="wppa-preview-caption" style="font-size:12px;color:green;margin:12px 0;width:100%;text-align:center;">
					<i>' . esc_html( __('In this preview: links and buttons will not work', 'wp-photo-album-plus' ) ) . ',<br>' .
						   esc_html( __('optional size and alignment settings are ignored', 'wp-photo-album-plus' ) ) . '.
					</i>
				</div>' .
				$result . '
			</div>' );

			$wppa_gutenberg_preview = false;

			// Done
			wppa_exit();
			break;

		case 'getshortcodedrenderedfenodelay':

			// Used by delay feature
			$nonce = wppa_get( 'nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-check' ) ) {
				$shouldbe = wp_create_nonce( 'wppa-check' );
				wppa_log('err', 'Nonce expected = '.$shouldbe.', found = '.$nonce);
				echo $nonce;
				wppa_secfail( '99' );
			}

			// Yes
			ob_start();
			wppa_load_theme();
			$shortcode = wppa_get( 'shortcode' );
			wppa( 'mocc', wppa_get( 'mocc' ) - 1 );
			$result = do_shortcode( str_replace( '%23', '#', $shortcode ) );

			// Get the JS
			$js = wppa_print_psjs( true );

			// Split result into HTML and JS (if any, it should not contain js)
			$result = wppa_split_html_js( $result );

			// Combine standard js and optional js found during split
			if ( $result['js'] ) {
				$js .= $result['js'];

				// Issue warning message
				wppa_log( 'dbg', strlen( $result['js'] ) . ' chars of unexpected js found in ajax getshortcodedrenderedfenodelay. $_REQUEST = ' . htmlspecialchars( var_export( $_REQUEST, true ) ) );
			}

			// Compress html
			$html = wppa_compress_html( $result['html'] );

			// Compress js
			$js = wppa_compress_js( $js );

			// Get possible premature output
			$unexpected = ob_get_clean();
			if ( $unexpected ) {
				wppa_log( 'err', strlen( $unexpected ) . ' chars of unexpected output captured while doing ajax render: ' . str_replace( '&', '8', htmlspecialchars( $unexpected ) ) );
//				wppa_dump( $unexpected );
			}

			// Output
			$the_result = wp_json_encode( ['html' => $html, 'js' => $js] );
			echo $the_result;



			/*
			// Get the JS
			$js = wppa_print_psjs( true );

			// Add the js
			$result .= '<script>'.$js.'</script>';

			echo( $result );
			*/
			wppa_exit();
			break;

		case 'bumpviewcount':
			$nonce  = wppa_get( 'nonce' );
			if ( wp_verify_nonce( $nonce, 'wppa-check' ) ) {
				wppa_bump_viewcount( 'photo', wppa_get( 'photo' ) );
			}
			else {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( __( 'Security check failure', 'wp-photo-album-plus' ) );
			}
			wppa_exit();
			break;

		case 'bumpclickcount':
			$nonce  = wppa_get( 'nonce' );
			$photo = wppa_get( 'photo-id' );
			if ( $photo && wp_verify_nonce( $nonce, 'wppa-check' ) ) {
				wppa_bump_clickcount( $photo );
			}
			else {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( __( 'Security check failure', 'wp-photo-album-plus' ) );
			}
			wppa_exit();
			break;

		case 'rate':

			// Get commandline args
			$photo  = wppa_get( 'rating-id' );
			$rating = wppa_get( 'rating' );
			$occur  = wppa_get( 'occur' );
			$index  = wppa_get( 'index' );
			$nonce  = wppa_get( 'nonce' );

			// Make errortext
			$errtxt = __( 'An error occurred while processing you rating request.' , 'wp-photo-album-plus' );
			$errtxt .= "\n".__( 'Maybe you opened the page too long ago to recognize you.' , 'wp-photo-album-plus' );
			$errtxt .= "\n".__( 'You may refresh the page and try again.' , 'wp-photo-album-plus' );
			$wartxt = __( 'Althoug an error occurred while processing your rating, your vote has been registered.' , 'wp-photo-album-plus' );
			$wartxt .= "\n".__( 'However, this may not be reflected in the current pageview' , 'wp-photo-album-plus' );

			// Security check
			if ( wppa_switch( 'direct_comment' ) ) {
				if ( ! $photo || ( wppa_get_photo_item( $photo, 'album' ) < '1' ) ) {
					wppa_echo( '0||100||'.__( 'Missing or invalid photo id', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
				wppa_log('dbg', 'Bypassed nonce');
			}
			else {
				if ( ! wp_verify_nonce( $nonce, 'wppa-check' ) ) {
					wppa_echo( '0||100||'.$errtxt );
					wppa_exit();
				}
				if ( ! $photo || ( wppa_get_photo_item( $photo, 'album' ) < '1' ) ) {
					wppa_echo( __( 'Missing or invalid photo id', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
			}

			// Check login
			if ( ! is_user_logged_in() ) {
				wppa_secfail( '40' );
			}

			// Check on validity
			if ( wppa_opt( 'rating_max' ) == '1' && $rating != '1' ) {
				wppa_echo( '0||106||'.$errtxt.':'.$rating );
				wppa_exit();																// Value out of range
			}
			elseif ( wppa_opt( 'rating_max' ) == '5' && ! in_array( $rating, array( '-1', '1', '2', '3', '4', '5' ) ) ) {
				wppa_echo( '0||106||'.$errtxt.':'.$rating );
				wppa_exit();																// Value out of range
			}
			elseif ( wppa_opt( 'rating_max' ) == '10' && ! in_array( $rating, array( '-1', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ) ) {
				wppa_echo( '0||106||'.$errtxt.':'.$rating );
				wppa_exit();																// Value out of range
			}

			// Check for one rating per period
			$wait_text = wppa_get_rating_wait_text( $photo );
			if ( $wait_text ) {
				wppa_echo( '0||900||'.$wait_text );	// 900 is recoverable error
				wppa_exit();
			}

			// Get other data
			if ( ! wppa_photo_exists( $photo ) ) {
				wppa_echo( '0||999||'.__( 'Photo has been removed.', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			$mylast   = wppa_get_my_last_vote( $photo );

			$myavgrat = '0';			// Init

			$user     = wppa_get_user( 'display' );

			// Rate own photo?
			if ( wppa_get_photo_item( $photo, 'owner' ) == wppa_get_user( 'login' ) && ! wppa_switch( 'allow_owner_votes' ) ) {
				wppa_echo( '0||900||' . __( 'Sorry, you can not rate your own photos', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Already a pending one?
			$pending = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
														WHERE photo = %d
														AND user = %s
														AND status = 'pending'", $photo, $user ) );

			// Has user motivated his vote?
			$hascommented = wppa_has_user_commented( $photo );

			// If the user has commented and comment needs vote is active, publish his comment
			if ( $hascommented && wppa_switch( 'comment_need_vote' ) ) {
				$comments_to_approve = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_comments
														WHERE photo = %d
														AND user = %s", $photo, wppa_get_user( 'display' ) ) );

				// Set the statusses to approved
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_comments
											   SET status = 'approved'
											   WHERE photo = %d
											   AND user = %s", $photo, wppa_get_user( 'display' ) ) );

				// Do the points and do the mailing
				$photo_owner = wppa_get_photo_item( $photo, 'owner' );
				if ( $comments_to_approve ) foreach( $comments_to_approve as $com ) {

					// The points to the commenter
					if ( $photo_owner != wppa_get_user() ) {
						wppa_add_credit_points( wppa_opt( 'cp_points_comment' ),
												__( 'Photo comment' , 'wp-photo-album-plus' ),
												$photo
												);
					}

					// Add points to the owner
					wppa_add_credit_points( wppa_opt( 'cp_points_comment_appr' ),
											__( 'Photo comment approved' , 'wp-photo-album-plus' ),
											$photo,
											'',
											$photo_owner
											);

					// Do the mailing
					wppa_schedule_mailinglist( 'commentnotify', 0, $photo, $com, wppa_get( 'returnurl' ) );
				}
			}

			if ( $pending ) {
				if ( ! $hascommented ) {
					wppa_echo( '0||900||' . __( 'Please enter a comment.', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
				else {
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_rating
												   SET status = 'publish'
												   WHERE photo = %d
												   AND user = %s", $photo, $user ) );
				}
			}

			if ( wppa_switch( 'vote_needs_comment' ) ) {
				$ratingstatus = $hascommented ? 'publish' : 'pending';
			}
			else {
				$ratingstatus = 'publish';
			}

			// When done, we have to print $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.$allavgrat.'||'.$discount.'||'.$hascommented.'||'.$message;
			// So we have to do: process rating and find new $myavgrat, $allavgrat and $discount ( $occur, $photo and $index are known )
			// Error message format: '0||<errcode>||<errtext>
			// errcode = 900: user error, other codes: real errors

			// Case -1: Likes only
			if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {

				// If i liked this, i do no longer like this
				if ( $mylast ) {

					// Remove my like
					$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_rating
												   WHERE photo = %d
												   AND user = %s", $photo, $user ) );
					$myavgrat = '0';
				}
				else {

					// Add my like
					wppa_create_rating_entry( array( 'photo' => $photo, 'value' => '1', 'user' => $user ) );
					$myavgrat = '1';
				}

				// Update photo data
				wppa_rate_photo( $photo );

				// Get callback data
				$lt = wppa_get_like_title_a( $photo );
				$allavgratcombi = $lt['title'] . '|' . $lt['display'];

				// Output and quit
				wppa_echo( $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.esc_attr( $allavgratcombi ).'||||||likes' );
				wppa_exit();
			}

			// Case 0: Test for Illegal second vote. Frontend takes care of this, but a hacker could enter an ajaxlink manually or a program error cause this to happen
			elseif ( $mylast ) {

				// I did vote already

				// Can vote only once
				if ( ! wppa_switch( 'rating_change' ) && ! wppa_switch( 'rating_multi' ) ) {
					wppa_echo( '0||900||'.__( 'You can not change your vote', 'wp-photo-album-plus' ) );
					wppa_exit();
				}

				// I did a dislike, can not modify
				if ( $mylast < '0' ) {
					wppa_echo( '0||900||'.__('You can not change a dislike', 'wp-photo-album-plus' ) );
					wppa_exit();
				}

				// I did a rating, can not change into dislike
				if ( $mylast > '0' && $rating == '-1' ) {
					wppa_echo( '0||900||'.__('You can not change your vote into a dislike', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
			}

			// Case 1: value = -1 this is a legal dislike vote
			if ( $rating == '-1' ) {

				// Add my dislike
				$iret = wppa_create_rating_entry( array( 'photo' => $photo, 'value' => $rating, 'user' => $user, 'status' => $ratingstatus ) );
				if ( ! $iret ) {
					wppa_echo( '0||101||'.$errtxt );
					wppa_exit();															// Fail on storing vote
				}

				// Add points
				wppa_add_credit_points( wppa_opt( 'cp_points_rating' ), __( 'Photo rated' , 'wp-photo-album-plus' ), $photo, $rating );

				// Check for email to be sent every .. dislikes
				wppa_dislike_check( $photo );

				// Photo is removed?
				if ( ! is_file( wppa_get_thumb_path( $photo ) ) ) {
					 wppa_echo( $occur.'||'.$photo.'||'.$index.'||-1||-1|0||'.wppa_opt( 'dislike_delete' ) );
					 wppa_exit();
				}
			}

			// Case 2: This is my first vote for this photo
			elseif ( ! $mylast ) {
				// Add my vote
				$iret = wppa_create_rating_entry( array( 'photo' => $photo, 'value' => $rating, 'user' => $user, 'status' => $ratingstatus ) );
				if ( ! $iret ) {
					wppa_echo( '0||102||'.$errtxt );
					wppa_exit();															// Fail on storing vote
				}
				// Add points
				wppa_add_credit_points( wppa_opt( 'cp_points_rating' ), __( 'Photo rated' , 'wp-photo-album-plus' ), $photo, $rating );
			}

			// Case 3: I will change my previously given vote
			elseif ( wppa_switch( 'rating_change' ) ) {					// Votechanging is allowed
				$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_rating
													   SET value = %s
													   WHERE photo = %d
													   AND user = %s
													   LIMIT 1", $rating, $photo, $user ) );

				wppa_clear_cache( ['photo' => $photo, 'other' => 'R'] );

				if ( $iret === false ) {
					wppa_echo( '0||103||' . $errtxt );
					wppa_exit();															// Fail on update
				}
			}

			// Case 4: Add another vote from me
			elseif ( wppa_switch( 'rating_multi' ) ) {					// Rating multi is allowed
				$iret = wppa_create_rating_entry( array( 'photo' => $photo, 'value' => $rating, 'user' => $user, 'status' => $ratingstatus ) );
				if ( ! $iret ) {
					wppa_echo( '0||104||'.$errtxt );
					wppa_exit();															// Fail on storing vote
				}
			}

			else { 																	// Should never get here....
				wppa_echo( '0||110||'.__( 'Unexpected error' , 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Compute my avg rating
			$myrats = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
														   WHERE photo = %d
														   AND user = %s
														   AND status = 'publish'", $photo, $user ), ARRAY_A );

			if ( $myrats ) {
				$sum = 0;
				$cnt = 0;
				foreach ( $myrats as $rat ) {
					if ( $rat['value'] == '-1' ) {
						$sum += wppa_opt( 'dislike_value' );
					}
					else {
						$sum += $rat['value'];
					}
					$cnt ++;
				}
				$myavgrat = $sum/$cnt;
				$i = wppa_opt( 'rating_prec' );
				$j = $i + '1';
				$myavgrat = sprintf( '%'.$j.'.'.$i.'f', $myavgrat );
			}
			else {
				$myavgrat = '0';
			}

			// Compute new allavgrat
			$ratings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
															WHERE photo = %d
															AND status = %s", $photo, 'publish' ), ARRAY_A );
			if ( $ratings ) {
				$sum = 0;
				$cnt = 0;
				foreach ( $ratings as $rat ) {
					if ( $rat['value'] == '-1' ) {
						$sum += wppa_opt( 'dislike_value' );
					}
					else {
						$sum += $rat['value'];
					}
					$cnt++;
				}
				$allavgrat = $sum/$cnt;
				if ( $allavgrat == '10' ) $allavgrat = '9.99999999';	// For sort order reasons text field
			}
			else $allavgrat = '0';

			// Store it in the photo info
			$iret = wppa_update_photo( $photo, ['mean_rating' => $allavgrat] );
			if ( $iret === false ) {
				wppa_echo( '0||106||'.$wartxt );
				wppa_exit();																// Fail on save
			}

			// Compute rating_count and store in the photo info
			$ratcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
														 WHERE photo = %d
														 AND status = 'publish'", $photo ) );
			if ( $ratcount !== false ) {
				$iret = wppa_update_photo( $photo, ['rating_count' => $ratcount] );
				if ( $iret === false ) {
					wppa_echo( '0||107||'.$wartxt );
					wppa_exit();																// Fail on save
				}
			}

			// Format $allavgrat for output
			$allavgratcombi = $allavgrat.'|'.$ratcount;

			// Compute dsilike count
			$discount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
														 WHERE photo = %d
														 AND value = -1
														 AND status = 'publish'", $photo ) );
			if ( $discount === false ) {
				wppa_echo( '0||108||'.$wartxt );
				wppa_exit();																// Fail on save
			}
			$distext = wppa_get_distext( $discount, $rating );
			if ( ! $distext ) {
				$distext = '0';
			}

			// Test for possible medal
			wppa_test_for_medal( $photo );

			// Success!
			wppa_clear_cache( ['photo' => $photo] );

			if ( wppa_switch( 'vote_needs_comment' ) && ! $hascommented ) {
				$message = __( "Please explain your vote in a comment.\nYour vote will be discarded if you don't.\n\nAfter completing your comment,\nyou can refresh the page to see\nyour vote became effective." , 'wp-photo-album-plus' );
			}
			else {
				$message = '';
			}

			wppa_echo( $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.$allavgratcombi.'||'.$distext.'||'.$hascommented.'||'.$message );
			break;

		case 'render':
			ob_start();
			$tim_1 	= microtime( true );
			$nq_1 	= get_num_queries();

			require_once 'wppa-non-admin.php';
			wppa_load_theme();

			// Render
			$result = wppa_albums();

			// Make relative urls if configured
			$result = wppa_make_relative( $result );

			// Get the JS
			$js = wppa_print_psjs( true );

			// Split result into HTML and JS (if any, it should not contain js)
			$result = wppa_split_html_js( $result );

			// Combine standard js and optional js found during split
			if ( $result['js'] ) {
				$js .= $result['js'];
			}

			// Compress html
			$html = wppa_compress_html( $result['html'] );

			// Compress js
			$js = wppa_compress_js( $js );

			// Get possible premature output
			$unexpected = ob_get_clean();
			if ( $unexpected ) {
				wppa_log( 'err', strlen( $unexpected ) . ' chars of unexpected output captured while doing ajax render: ' . str_replace( '&', '8', htmlspecialchars( $unexpected ) ) );
			}

			// Output
			$the_result = wp_json_encode( ['html' => $html, 'js' => $js] );
			echo $the_result;

			$tim_2 	= microtime( true );
			$nq_2 	= get_num_queries();
			$mem 	= memory_get_peak_usage( true ) / 1024 / 1024;

			$msg 	= sprintf( 'WPPA Ajax render: db queries: WP:%d, WPPA+: %d in %4.2f seconds, using %4.2f MB memory max', $nq_1, $nq_2 - $nq_1, $tim_2 - $tim_1, $mem );
			wppa_log( 'ajax', $msg );
			break;

		case 'delete-photo':
			$photo = wppa_get( 'photo-id' );
			$nonce = wppa_get( 'nonce' );
			$immediate = wppa_get( 'immediate', false, 'bool' );

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( '||0||'.__( 'You do not have the rights to delete a photo' , 'wp-photo-album-plus' ) );
				wppa_exit();																// Nonce check failed
			}
			if ( ! is_numeric( $photo ) ) {
				wppa_echo( '||0||'.__( 'Security check failure' , 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Non admins/superusers can only delete their own photos
			if ( ! wppa_user_is_admin() && wppa_get_photo_item( $photo, 'owner' ) != wppa_get_user() ) {
				wppa_echo( '||0||'.__( 'You do not have the rights to delete this photo' , 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			$album = $wpdb->get_var( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos
													  WHERE id = %d", $photo ) );
			wppa_delete_photo( $photo, $immediate );
			wppa_clear_cache( ['photo' => $photo] );
			wppa_clear_cache( ['album' => $album] );
			$edit_link = wppa_ea_url( 'single' ) . '&photo=' . $photo;
			$a = wppa_allow_uploads( $album );
			if ( $immediate ) {
				wppa_echo( '||1||<span style="color:red" >' .
						sprintf( __( 'Photo %s has been permanently removed' ,'wp-photo-album-plus' ), '<a href="'.$edit_link.'" target="_blank" >' . $photo . '</a>' ) .
						'</span>||' . ( $a ? 'notfull||'.$a : 'full' ) );
			}
			else {
				wppa_echo( '||1||<span style="color:red" >' .
						sprintf( __( 'Photo %s has been deleted' ,'wp-photo-album-plus' ), '<a href="'.$edit_link.'" target="_blank" >' . $photo . '</a>' ) .
						'</span>||' . ( $a ? 'notfull||'.$a : 'full' ) );
			}
			break;

		case 'undelete-photo':
			$photo = wppa_get( 'photo-id' );
			$nonce = wppa_get( 'nonce' );

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( '||0||'.__( 'You do not have the rights to undelete a photo' , 'wp-photo-album-plus' ) );
				wppa_exit();																// Nonce check failed
			}
			if ( ! is_numeric( $photo ) ) {
				wppa_echo( '||0||'.__( 'Security check failure' , 'wp-photo-album-plus' ) );
				wppa_exit();																// Nonce check failed
			}

			wppa_undelete_photo( $photo, true );

			break;

		case 'update-album':
			$album = wppa_get( 'album-id' );
			$nonce = wppa_get( 'nonce' );
			$item  = wppa_get( 'item', '', 'text' );
			if ( $item == 'description' ) {
				$value = wppa_get( 'value', '', 'html' );
			}
			elseif( $item == 'cover_link' ) {
				$value = wppa_get( 'value', '', 'url' );
			}
			else {
				$value = wppa_get( 'value', '', 'text' );
			}
			$value = wppa_decode( $value );

			if ( ! current_user_can( 'unfiltered_html' ) ) {
				$value = strip_tags( $value );
			}
			else {
				$value = balanceTags( $value, true );
			}

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$album ) ) {
				wppa_echo( '||0||'.__( 'You do not have the rights to update album information' , 'wp-photo-album-plus' ).$nonce );
				wppa_exit();																// Nonce check failed
			}

			switch ( $item ) {
				case 'clear_ratings':
					$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																   WHERE album = %d", $album ), ARRAY_A );
					if ( $photos ) foreach ( $photos as $photo ) {
						$iret1 = wppa_del_row( WPPA_RATING, 'photo', $photo['id'] );
						$iret2 = wppa_update_photo( $photo['id'], ['mean_rating' => ''] );
					}
					if ( $photos && $iret1 !== false && $iret2 !== false ) {
						wppa_echo( '||0||'.__( 'Ratings cleared' , 'wp-photo-album-plus' ).'||'.__( 'No ratings for this photo.' , 'wp-photo-album-plus' ) );
					}
					elseif ( $photos ) {
						wppa_echo( '||1||'.__( 'An error occurred while clearing ratings' , 'wp-photo-album-plus' ) );
					}
					else {
						wppa_echo( '||0||'.__( 'No photos in this album' , 'wp-photo-album-plus' ).'||'.__( 'No ratings for this photo.' , 'wp-photo-album-plus' ) );
					}
					wppa_exit();
					break;
				case 'set_deftags':	// to be changed for large albums
					$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																   WHERE album = %d", $album ), ARRAY_A );

					$deftag = $wpdb->get_var( $wpdb->prepare( "SELECT default_tags FROM $wpdb->wppa_albums
															   WHERE id = %d", $album ) );

					if ( is_array( $photos ) ) foreach ( $photos as $photo ) {

						$tags = wppa_sanitize_tags( wppa_filter_iptc( wppa_filter_exif( $deftag, $photo['id'] ), $photo['id'] ) );
						$iret = wppa_update_photo( $photo['id'], ['tags' => $tags] );
					}
					if ( $photos && $iret !== false ) {
						wppa_echo( '||0||'.__( 'Tags set to defaults' , 'wp-photo-album-plus' ) );
						wppa_update_album( $album );
					}
					elseif ( $photos ) {
						wppa_echo( '||1||'.__( 'An error occurred while setting tags' , 'wp-photo-album-plus' ) );
					}
					else {
						wppa_echo( '||0||'.__( 'No photos in this album' , 'wp-photo-album-plus' ) );
					}
					wppa_exit();
					break;
				case 'add_deftags':
					$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																   WHERE album = %d", $album ), ARRAY_A );

					$deftag = $wpdb->get_var( $wpdb->prepare( "SELECT default_tags FROM $wpdb->wppa_albums
															   WHERE id = %d", $album ) );

					if ( is_array( $photos ) ) foreach ( $photos as $photo ) {

						$tags = wppa_sanitize_tags( wppa_filter_iptc( wppa_filter_exif( $photo['tags'].','.$deftag, $photo['id'] ), $photo['id'] ) );
						$iret = wppa_update_photo( $photo['id'], ['tags' => $tags] );
					}
					if ( $photos && $iret !== false ) {
						wppa_update_album( $album );
						wppa_echo( '||0||'.__( 'Tags added with defaults' , 'wp-photo-album-plus' ) );
					}
					elseif ( $photos ) {
						wppa_echo( '||1||'.__( 'An error occurred while adding tags' , 'wp-photo-album-plus' ) );
					}
					else {
						wppa_echo( '||0||'.__( 'No photos in this album' , 'wp-photo-album-plus' ) );
					}
					wppa_clear_taglist();
					wppa_exit();
					break;
				case 'inherit_cats';
				case 'inhadd_cats':
					$albids = wppa_expand_enum( wppa_alb_to_enum_children( $album ) );
					$albarr = explode( '.', $albids );
					$cats = wppa_get_album_item( $album, 'cats' );
					if ( $cats || $item == 'inherit_cats' ) {
						if ( count( $albarr ) > 1 ) {
							foreach( $albarr as $alb ) if ( $album != $alb ) {
								if ( $item == 'inherit_cats' ) {
									wppa_update_album( $alb, ['cats' => $cats] );
								}
								else { // 'inhadd_cats'
									$mycats = wppa_get_album_item( $alb, 'cats' );
									wppa_update_album( $alb, ['cats' => $mycats . $cats] );
								}
							}
						}
						else {
							wppa_echo( '||0||' . __( 'No sub albums found to process', 'wp-photo-album-plus' ) );
							wppa_exit();
						}
					}
					else {
						wppa_echo( '||0||' . __( 'No categories found to process', 'wp-photo-album-plus' ) );
						wppa_exit();
					}
					$n = count( $albarr ) - 1;
					wppa_echo( '||0||' . sprintf( _n( '%d album updated', '%d albums updated', $n, 'wp-photo-album-plus' ), $n ) );
					wppa_exit();
					break;
				case 'name':
					$itemname = __( 'Name' , 'wp-photo-album-plus' );
					break;
				case 'description':
					$itemname = __( 'Description' , 'wp-photo-album-plus' );
					break;
				case 'a_order':
					$itemname = __( 'Album sequence order #' , 'wp-photo-album-plus' );
					break;
				case 'main_photo':
					$itemname = __( 'Cover photo' , 'wp-photo-album-plus' );
					break;
				case 'a_parent':
					$itemname = __( 'Parent album' , 'wp-photo-album-plus' );
					break;
				case 'p_order_by':
					$itemname = __( 'Photo order' , 'wp-photo-album-plus' );
					break;
				case 'alt_thumbsize':
					$itemname = __( 'Use Alt thumbsize' , 'wp-photo-album-plus' );
					break;
				case 'cover_type':
					$itemname = __( 'Cover Type' , 'wp-photo-album-plus' );
					break;
				case 'cover_linktype':
					$itemname = __( 'Link type' , 'wp-photo-album-plus' );
					break;
				case 'cover_linkpage':
					$itemname = __( 'Link to' , 'wp-photo-album-plus' );
					break;
				case 'cover_link':
					$itemname = __( 'Link target (url)', 'wp-photo-album-plus' );
					break;
				case 'owner':
					$itemname = __( 'Owner' , 'wp-photo-album-plus' );
					break;
				case 'upload_limit_count':
					wppa_ajax_check_range( $value, false, '-1', false, __( 'Upload limit count' , 'wp-photo-album-plus' ) );
					if ( wppa( 'error' ) ) {
						wppa_echo( '||7||'.__('Invalid value', 'wp-photo-album-plus' ) );
						wppa_exit();
					}
					$oldval = $wpdb->get_var( $wpdb->prepare( "SELECT upload_limit FROM $wpdb->wppa_albums
															   WHERE id = %d", $album ) );
					$temp = explode( '/', $oldval );
					$value = $value.'/'.$temp[1];
					$item = 'upload_limit';
					$itemname = __( 'Upload limit count' , 'wp-photo-album-plus' );
					break;
				case 'upload_limit_time':
					$oldval = $wpdb->get_var( $wpdb->prepare( "SELECT upload_limit FROM $wpdb->wppa_albums
															   WHERE id = %d", $album ) );
					$temp = explode( '/', $oldval );
					$value = $temp[0].'/'.$value;
					$item = 'upload_limit';
					$itemname = __( 'Upload limit time' , 'wp-photo-album-plus' );
					break;
				case 'default_tags':
					$itemname = __( 'Default tags' , 'wp-photo-album-plus' );
					break;
				case 'cats':
					$itemname = __( 'Categories' , 'wp-photo-album-plus' );
					break;
				case 'suba_order_by':
					$itemname = __( 'Sub albums sort order' , 'wp-photo-album-plus' );
					break;

				case 'year':
				case 'month':
				case 'day':
				case 'hour':
				case 'min':
					$itemname = __( 'Schedule date/time' , 'wp-photo-album-plus' );
					$scheduledtm = $wpdb->get_var( $wpdb->prepare( "SELECT scheduledtm
																	FROM $wpdb->wppa_albums
																	WHERE id = %d", $album ) );
					if ( ! $scheduledtm ) {
						$scheduledtm = wppa_get_default_scheduledtm();
					}
					$temp = explode( ',', $scheduledtm );
					if ( $item == 'year' ) 	$temp[0] = $value;
					if ( $item == 'month' ) $temp[1] = $value;
					if ( $item == 'day' ) 	$temp[2] = $value;
					if ( $item == 'hour' ) 	$temp[3] = $value;
					if ( $item == 'min' ) 	$temp[4] = $value;
					$value = implode( ',', $temp );
					$item = 'scheduledtm';
					break;

				case 'delyear':
				case 'delmonth':
				case 'delday':
				case 'delhour':
				case 'delmin':
					$itemname = __( 'Delete date/time' , 'wp-photo-album-plus' );
					$scheduledtm = $wpdb->get_var( $wpdb->prepare( "SELECT scheduledel
																	FROM $wpdb->wppa_albums
																	WHERE id = %d", $album ) );
					if ( ! $scheduledtm ) {
						$scheduledtm = wppa_get_default_scheduledtm();
					}
					$temp = explode( ',', $scheduledtm );
					if ( $item == 'delyear' ) 	$temp[0] = $value;
					if ( $item == 'delmonth' ) 	$temp[1] = $value;
					if ( $item == 'delday' ) 	$temp[2] = $value;
					if ( $item == 'delhour' ) 	$temp[3] = $value;
					if ( $item == 'delmin' ) 	$temp[4] = $value;
					$value = implode( ',', $temp );
					$item = 'scheduledel';
					break;

				case 'setallscheduled':
					$scheduledtm = $wpdb->get_var( $wpdb->prepare( "SELECT scheduledtm
																	FROM $wpdb->wppa_albums
																	WHERE id = %d", $album ) );
					if ( $scheduledtm ) {
						$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_photos
															   SET status = 'scheduled', scheduledtm = %s
															   WHERE album = %d", $scheduledtm, $album ) );
						wppa_echo( '||0||'.__( 'All photos set to scheduled per date', 'wp-photo-album-plus' ) . ' ' . wppa_format_scheduledtm( $scheduledtm ) );
					}
					wppa_exit();
					break;

				case 'displayopt0':
				case 'displayopt1':
				case 'displayopt2':
				case 'displayopt3':
					$itemname = __( 'Display options', 'wp-photo-album-plus' );
					$dispopts = wppa_get_album_item( $album, 'displayopts' );
					if ( $dispopts ) {
						$opts = explode( ',', $dispopts );
					}
					for ( $i = 0; $i < 4; $i++ ) {
						if ( ! isset( $opts[$i] ) ) {
							$opts[$i] = '0';
						}
					}
					$i = substr( $item , 10 );
					$opts[$i] = $value;
					$value = implode( ',', $opts );
					$item = 'displayopts';
					break;

				case 'album_custom_0':
				case 'album_custom_1':
				case 'album_custom_2':
				case 'album_custom_3':
				case 'album_custom_4':
				case 'album_custom_5':
				case 'album_custom_6':
				case 'album_custom_7':
				case 'album_custom_8':
				case 'album_custom_9':
					$index 		= substr( $item, -1 );
					$itemname = sprintf( __( 'Custom field %s' , 'wp-photo-album-plus' ), $index );
					$custom 	= wppa_get_album_item( $album, 'custom' );
					if ( $custom ) {
						$custom_data = wppa_unserialize( $custom );
					}
					if ( ! is_array( $custom_data ) ) {
						$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
					}
					$custom_data[$index] = wppa_sanitize_custom_field( $value );
					$value = serialize( $custom_data );
					$item = 'custom';
					break;

				case 'scheduledel':
					$value = '';
					$itemname = __( 'Delete date/time' , 'wp-photo-album-plus' );
					break;

				case 'scheduledtm':
					$value = '';
					$itemname = __( 'Schedule date/time' , 'wp-photo-album-plus' );
					break;

				case 'wmfile':
					$itemname = __( 'Watermark file', 'wp-photo-album-plus' );
					break;

				case 'wmpos':
					$itemname = __( 'Watermark position', 'wp-photo-album-plus' );
					break;

				default:
					$itemname = $item;
			}

			// Do the update
			$iret = wppa_update_album( $album, [$item => $value] );

			if ( $iret === 0 ) {
				wppa_echo( '||1||' . sprintf( __( '%s of album %s NOT updated', 'wp-photo-album-plus' ), $itemname, $album ) );
			}
			elseif ( $iret === false ) {
				wppa_echo( '||2||' . sprintf( __( 'An error occurred while trying to update %s of album %s' , 'wp-photo-album-plus' ), $itemname, $album ) );
			}
			else {
				wppa_echo( '||0||'.sprintf( __( '%s of album %s updated', 'wp-photo-album-plus' ), $itemname, $album ) );
				if ( $item == 'upload_limit' ) {
					$a = wppa_allow_uploads( $album );
					if ( $a ) wppa_echo( '||notfull||' . $a );
					else wppa_echo( '||full' );
				}
			}
			wppa_exit();
			break;

		case 'update-comment-status':
			$photo 		= wppa_get( 'photo-id' );
			$nonce 		= wppa_get( 'nonce' );
			$comid 		= wppa_get( 'comment-id' );
			$comstat 	= wppa_get( 'comment-status' );

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				wppa_echo( '||0||'.__( 'You do not have the rights to update comment status' , 'wp-photo-album-plus' ).$nonce );
				wppa_exit();																// Nonce check failed
			}

			$iret = wppa_update_comment( $comid, ['status' => $comstat] );

			if ( wppa_switch( 'search_comments' ) ) {
				wppa_update_photo( $photo );
			}

			if ( $iret !== false ) {
				if ( $comstat == 'approved' ) {
					wppa_schedule_mailinglist( 'commentapproved', 0, 0, $comid );
					wppa_add_credit_points( 	wppa_opt( 'cp_points_comment_appr' ),
												__( 'Photo comment approved' , 'wp-photo-album-plus' ),
												$photo,
												'',
												wppa_get_photo_item( $photo, 'owner' )
											);
				}
				wppa_echo( '||0||'.sprintf( __( 'Status of comment #%s updated' , 'wp-photo-album-plus' ), $comid ) );
			}
			else {
				wppa_echo( '||1||'.sprintf( __( 'Error updating status comment #%s' , 'wp-photo-album-plus' ), $comid ) );
			}
			wppa_exit();
			break;

		case 'watermark-photo':
			$photo = wppa_get( 'photo-id' );
			$nonce = wppa_get( 'nonce' );

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				wppa_echo( '||1||'.__( 'You do not have the rights to change photos', 'wp-photo-album-plus' ) );
				wppa_exit();																// Nonce check failed
			}

			wppa_cache_photo( $photo );
			if ( wppa_add_watermark( $photo ) ) {
				if ( wppa_switch( 'watermark_thumbs' ) ) {
					wppa_create_thumbnail( $photo );	// create new thumb
				}
				wppa_bump_thumb_rev();
				wppa_bump_photo_rev();
				wppa_echo( '||0||'.__( 'Watermark applied. Reloading the page...', 'wp-photo-album-plus' ) );
				wppa_exit();
			}
			else {
				wppa_echo( '||1||'.__( 'An error occurred while trying to apply a watermark', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

		case 'update-photo':

			// Init
			$photo 		= wppa_get( 'photo-id' );
			$nonce 		= wppa_get( 'nonce' );
			$item  		= wppa_get( 'item' );
			$err 		= '0';
			$txt 		= '';
			$dbfields 	= array(); 	// Fields for update
			$jsfields 	= array(); 	// Fields for JSON response
			$itemname 	= '';

			// Get and sanitize value
			if ( $item == 'description' ) {
				$value = wppa_get( 'value', '', 'html' );
			}
			else {
				$value = wppa_get( 'value', '', 'text' );
			}
			$value = wppa_decode( $value );

			if ( ! current_user_can( 'unfiltered_html' ) ) {
				$value = strip_tags( $value );
			}
			else {
				$value = balanceTags( $value );
			}

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				$txt = __( 'Security check failure: wrong noncefield value' , 'wp-photo-album-plus' );
				wppa_json_photo_update( $photo, $txt, '1' );															// Nonce check failed
				wppa_exit();
			}

			// Special case: watemark file or position. This is user specific
			if ( substr( $item, 0, 20 ) == 'wppa_watermark_file_' || substr( $item, 0, 19 ) == 'wppa_watermark_pos_' ) {
				wppa_update_option( $item, $value );
				if ( substr( $item, 0, 20 ) == 'wppa_watermark_file_' ) {
					$item = __( 'Your personal watermark file', 'wp-photo-album-plus' );
				}
				else {
					$item = __( 'Your personal watermark position', 'wp-photo-album-plus' );
				}
				$txt = sprintf( __( '%s updated to %s.' , 'wp-photo-album-plus' ), $item, $value );
				wppa_json_photo_update( $photo, $txt );
				wppa_exit();
			}

			// Dispatch on photo item
			switch ( $item ) {
				case 'exifdtm':
					$itemname 	= __( 'Exif date/time', 'wp-photo-album-plus' );
					$format 	= '0000:00:00 00:00:00';

					// Length ok?
					if ( strlen( $value ) != 19 ) {
						$err = '1';
					}

					// Check on digits, colons and space
					if ( ! $err ) for ( $i = 0; $i < 19; $i++ ) {
						$d = substr( $value, $i, 1 );
						$f = substr( $format, $i, 1 );
						switch ( $f ) {
							case '0':
								if ( ! in_array( $d, array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ) ) ) {
									$err = '2';
								}
								break;
							case ':':
							case ' ':
								if ( $d != $f ) {
									$err = '3';
								}
								break;
							default:
								$err = '9';
								break;
						}
					}

					// Check on values if format correct, report first error only
					if ( ! $err ) {
						$temp = explode( ':', str_replace( ' ', ':', $value ) );
						if ( $temp['0'] < '1970' ) 					$err = '11';	// Before UNIX epoch
						if ( ! $err && $temp['0'] > date( 'Y' ) ) 	$err = '12';	// Future
						if ( ! $err && $temp['1'] < '1' )			$err = '13'; 	// Before january
						if ( ! $err && $temp['1'] > '12' )			$err = '14';	// After december
						if ( ! $err && $temp['2'] < '1' ) 			$err = '15'; 	// Before first of month
						if ( ! $err && $temp['2'] > '31' ) 			$err = '17';	// After 31st ( forget about feb and months with 30 days )
						if ( ! $err && $temp['3'] < '0' ) 			$err = '18'; 	// Before first hour
						if ( ! $err && $temp['3'] > '23' )			$err = '19'; 	// Hour > 23
						if ( ! $err && $temp['4'] < '0' ) 			$err = '20';	// Min < 0
						if ( ! $err && $temp['4'] > '59' ) 			$err = '21';	// Min > 59
						if ( ! $err && $temp['5'] < '0' ) 			$err = '22';	// Sec < 0
						if ( ! $err && $temp['5'] > '59' ) 			$err = '23';	// Sec > 59
					}
					if ( $err ) {
						$txt = sprintf( __( 'Format error %s. Must be yyyy:mm:dd hh:mm:ss' , 'wp-photo-album-plus' ), $err );
					}
					else {
						$dbfields[$item] = $value;
					}
					$jsfields[$item] = true;
					break;
				case 'lat':
					$itemname = __( 'Latitude', 'wp-photo-album-plus' );
					if ( ! is_numeric( $value ) || $value < '-90.0' || $value > '90.0' ) {
						$txt = __( 'Enter a value > -90 and < 90' , 'wp-photo-album-plus' );
						$err = '1';
					}
					else {
						$geo = $wpdb->get_var( $wpdb->prepare( "SELECT location FROM $wpdb->wppa_photos WHERE id = %d", $photo ) );
						if ( ! $geo ) $geo = '///';
						$geo = explode( '/', $geo );
						$geo = wppa_format_geo( $value, $geo['3'] );
						$dbfields['location'] = $geo;
					}
					$jsfields[$item] = true;
					break;
				case 'lon':
					$itemname = __( 'Longitude', 'wp-photo-album-plus' );
					if ( ! is_numeric( $value ) || $value < '-180.0' || $value > '180.0' ) {
						$txt = __( 'Enter a value > -180 and < 180' , 'wp-photo-album-plus' );
						$err = '1';
					}
					else {
						$geo = $wpdb->get_var( $wpdb->prepare( "SELECT location FROM $wpdb->wppa_photos WHERE id = %d", $photo ) );
						if ( ! $geo ) $geo = '///';
						$geo = explode( '/', $geo );
						$geo = wppa_format_geo( $value, $geo['3'] );
						$dbfields['location'] = $geo;
					}
					$jsfields[$item] = true;
					break;
				case 'remake':
					if ( wppa_get_photo_item( $photo, 'thumblock' ) ) {
						$extra = '<span style=\"color:red;\" > ' . __( 'The thumbnail could not be remade', 'wp-photo-album-plus' ) . '</span>';
					}
					else {
						$extra = '';
					}
					if ( wppa_remake_files( '', $photo ) ) {
						$txt = __( 'Photo files remade' , 'wp-photo-album-plus' ) . $extra;
					}
					else {
						$txt = __( 'Could not remake files' , 'wp-photo-album-plus' );
					}
					$jsfields['thumbmod'] = true;
					$jsfields['photomod'] = true;
					break;
				case 'remakethumb':
					if ( wppa_create_thumbnail( $photo ) ) {
						$txt = __( 'Thumbnail remade' , 'wp-photo-album-plus' );
					}
					else {
						$txt = __( 'Could not remake thumbnail', 'wp-photo-album-plus' );
						$err ='1';
					}
					$jsfields['thumbmod'] = true;
					break;
				case 'rotright':
				case 'rot180':
				case 'rotleft':
				case 'flip':
				case 'flop':
					switch ( $item ) {
						case 'rotleft':
							$dir = __( 'left' , 'wp-photo-album-plus' );
							break;
						case 'rot180':
							$dir = __( '180&deg;' , 'wp-photo-album-plus' );
							break;
						case 'rotright':
							$dir = __( 'right' , 'wp-photo-album-plus' );
							break;
						case 'flip':
						case 'flop':
						default:
							$dir = '';
							break;
					}
					$err = wppa_rotate( $photo, $item );
					wppa( 'error', $err );
					if ( ! $err || $err == '30' ) {
						if ( wppa_get_photo_item( $photo, 'thumblock' ) ) {
							$extra = '<span style=\"color:red\" > ' . __( 'The thumbnail could not be remade', 'wp-photo-album-plus' ) . '</span>';
						}
						else {
							$extra = '';
						}
						if ( $item == 'flip' ) {
							$txt = sprintf( __( 'Photo flipped' , 'wp-photo-album-plus' ), $photo ) . $extra;
						}
						elseif ( $item == 'flop' ) {
							$txt = sprintf( __( 'Photo flipped' , 'wp-photo-album-plus' ), $photo ) . $extra;
						}
						else {
							$txt = sprintf( __( 'Photo %s rotated %s' , 'wp-photo-album-plus' ), $photo, $dir ) . $extra;
						}
					}
					else {
						$txt = __( 'An error occurred while trying to rotate or flip photo' , 'wp-photo-album-plus' );
						$err = '1';
					}
					$jsfields['thumbmod'] = true;
					$jsfields['photomod'] = true;
					break;
				case 'magickrotleft':
				case 'magickrot180':
				case 'magickrotright':
				case 'magickflip':
				case 'magickflop':
				case 'enhance':
				case 'sharpen':
				case 'blur':
				case 'auto-gamma':
				case 'auto-level':
				case 'contrast-p':
				case 'contrast-m':
				case 'brightness-p':
				case 'brightness-m':
				case 'despeckle':
				case 'lineargray':
				case 'nonlineargray':
				case 'charcoal':
				case 'paint':
				case 'sepia':
				case 'skyleft':
				case 'skyright':
				case 'crop':
					$id = $photo;
					$src_o1_path = wppa_get_o1_source_path( $id );
					$src_path = wppa_get_source_path( $id );
					if ( ! is_file( $src_o1_path ) && ! is_file( $src_path ) ) {

						// Make a backup
						$src_alb_dir = dirname( $src_path );
						//$src_alb_dir = $src_dir . '/album-' . wppa_get_photo_item( $id, 'album' );
						if ( ! wppa_is_dir( $src_alb_dir ) ) {

							// Make source album folder
							wppa_mktree( $src_alb_dir );
						}
						$filename = wppa_get_photo_item( $id, 'filename' );
						$filename = wppa_strip_ext( $filename ) . '.' . wppa_get_photo_item( $id, 'ext' );
						$path = wppa_get_photo_path( $id );
						wppa_copy( $path, $src_alb_dir . '/' . basename( $filename ) );
						wppa_log( 'fso', 'Backup created for magic: ' . $src_alb_dir . '/' . $filename );
					}
					switch ( $item ) {
						case 'magickrotleft':
							$command = '-rotate -90';
							break;
						case 'magickrot180':
							$command = '-rotate 180';
							break;
						case 'magickrotright':
							$command = '-rotate 90';
							break;
						case 'magickflip':
							$command = '-flip';
							break;
						case 'magickflop':
							$command = '-flop';
							break;
						case 'enhance':
							$command = '-enhance';
							break;
						case 'sharpen':
							$command = '-sharpen 0x1';
							break;
						case 'blur':
							$command = '-blur 0x1';
							break;
						case 'auto-gamma':
							$command = '-auto-gamma';
							break;
						case 'auto-level':
							$command = '-auto-level';
							break;
						case 'contrast-p':
							$command = '-brightness-contrast 0x5';
							break;
						case 'contrast-m':
							$command = '-brightness-contrast 0x-5';
							break;
						case 'brightness-p':
							$command = '-brightness-contrast 5';
							break;
						case 'brightness-m':
							$command = '-brightness-contrast -5';
							break;
						case 'despeckle':
							$command = '-despeckle';
							break;
						case 'lineargray':
							$command = '-colorspace gray';
							break;
						case 'nonlineargray':
							$command = '-grayscale Rec709Luma';
							break;
						case 'charcoal':
							$command = '-charcoal 1';
							break;
						case 'paint':
							$command = '-paint 4';
							break;
						case 'sepia':
							$command = '-sepia-tone 80%';
							break;
						case 'skyleft':
							$command = '-rotate -0.5 -shave ' . ( ceil( 0.0087 * wppa_get_photoy( $photo ) ) + 1 ) . 'x' . ( ceil( 0.0087 * wppa_get_photox( $photo ) ) + 1 );
							break;
						case 'skyright':
							$command = '-rotate 0.5 -shave ' . ( ceil( 0.0087 * wppa_get_photoy( $photo ) ) + 1 ) . 'x' . ( ceil( 0.0087 * wppa_get_photox( $photo ) ) + 1 );
							break;
						case 'crop':
							$command = '-crop ' . $value;
							break;
						default:
							break;
					}

					$path = wppa_get_photo_path( $id );

					// If jpg, apply jpeg quality
					$q = wppa_opt( 'jpeg_quality' );
					$quality = '';
					if ( wppa_get_ext( $path ) == 'jpg' ) {
						$quality = '-quality ' . $q;
					}

					// Do the magick command
					$err = wppa_image_magick( 'convert ' . $path . ' ' . $quality . ' ' . $command . ' ' . $path );

					// Error?
					if ( $err ) {
						$txt = __( 'An error occurred while trying to process photo', 'wp-photo-album-plus' );
					}

					// Housekeeping
					else {

						// Horizon correction shaves size.
						if ( $item == 'skyleft' || $item == 'skyright' ) {
							wppa_get_photox( $id, true );
						}

						wppa_create_thumbnail( $id, false );
						$stack = wppa_get_photo_item( $id, 'magickstack' );
						if ( ! $stack ) {
							$stack = $command;
						}
						else {
							$stack .= ' | ' . $command;
						}
						$dbfields['magickstack'] = $stack;

						// Update CDN
						$cdn = wppa_cdn( 'admin' );
						if ( $cdn ) {
							switch ( $cdn ) {
								case 'cloudinary':
									wppa_upload_to_cloudinary( $id );
									break;
								case 'local':
									break;
								default:
							}
						}

						$txt = sprintf( __( 'Command %s magically executed on photo %s', 'wp-photo-album-plus' ), '<span style=\"color:blue;\" ><i>'.$command.'</i></span>', $id );
					}
					$jsfields = array_merge( $jsfields, ['thumbmod' => true, 'photomod' => true, 'magickmod' => true] );
					break;

				case 'magickundo':
					$path = wppa_get_photo_path( $photo );
					$stack = wppa_get_photo_item( $photo, 'magickstack' );

					// Revert all
					wppa_remake_files( '', $photo );

					// Redo all except last
					$commands = explode( '|', $stack );
					$i = 0;
					$newstack = '';
					while ( $i < ( count( $commands ) - 1 ) ) {

						// Do the magick command
						$err = wppa_image_magick( 'convert ' . $path . ' ' . trim( $commands[$i] ) . ' ' . $path );
						$newstack .= ( $i != '0' ? ' | ' : '' ) . $commands[$i];
						$i++;
					}

					// Housekeeping
					wppa_bump_photo_rev();
					wppa_create_thumbnail( $photo, false );
					$dbfields['magickstack'] = $newstack;

					$txt = sprintf( __( 'Command %s magically executed on photo %s', 'wp-photo-album-plus' ), '<span style=\"color:blue;\" ><i>magickundo</i></span>', $photo );
					$jsfields = array_merge( $jsfields, ['thumbmod' => true, 'photomod' => true, 'magickmod' => true] );
					break;

				case 'moveto':
					$photodata = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																  WHERE id = %d", $photo ), ARRAY_A );

					if ( wppa_switch( 'void_dups' ) ) {	// Check for already exists
						$exists = wppa_is_file_duplicate_photo( $photodata['filename'], $value );
						if ( $exists ) {	// Already exists
							$txt = sprintf ( __( 'A photo with filename %s already exists in album %s.' , 'wp-photo-album-plus' ), $photodata['filename'], $value );
							$err = '1';
						}
					}
					if ( ! wppa_album_exists( $value ) ) {
						$txt = sprintf( __( 'Album %s does not exist', 'wp-photo-album-plus' ), $value );
					}
					wppa_invalidate_treecounts( $photodata['album'] );	// Current album
					wppa_invalidate_treecounts( $value );				// New album
					$edit_link = wppa_ea_url( 'single' ) . '&photo=' . $photodata['id'];
					if ( ! $err ) {
						$iret = wppa_update_photo( $photo, ['album' => $value] );
						if ( $iret !== false ) {
							wppa_move_source( $photodata['filename'], $photodata['album'], $value );
							wppa_echo( '||99||'.sprintf( __( 'Photo %s has been moved to album %s (%s)', 'wp-photo-album-plus' ), '<a href="'.$edit_link.'" target="_blank" >' . $photodata['id'] . '</a>', wppa_get_album_name( $value ), $value ) );
							wppa_exit();
						}
					}
					if ( $err ) {
						$txt = sprintf( __( 'An error occurred while trying to move photo %s' , 'wp-photo-album-plus' ), $photo );
					}
					break;

				case 'copyto':
					$photodata = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																  WHERE id = %d", $photo ), ARRAY_A );

					if ( wppa_switch( 'void_dups' ) ) {	// Check for already exists
						$exists = wppa_is_file_duplicate_photo( $photodata['filename'], $value );
						if ( $exists ) {	// Already exists
							$txt = sprintf( __( 'A photo with filename %s already exists in album %s.' , 'wp-photo-album-plus' ), $photodata['filename'], $value );
							$err = '1';
						}
					}
					if ( ! wppa_album_exists( $value ) ) {
						$txt = sprintf( __( 'Album %s does not exist', 'wp-photo-album-plus' ), $value );
						$err = '1';
					}
					wppa( 'error', wppa_copy_photo( $photo, $value ) );
					wppa_invalidate_treecounts( $value );				// New album
					if ( ! wppa( 'error' ) ) {
						$txt = sprintf( __( 'Photo %s copied to album %s (%s)' , 'wp-photo-album-plus' ), $photo, wppa_get_album_name( $value ), $value );
					}
					else {
						$txt = sprintf( __( 'An error occurred while trying to copy photo %s' , 'wp-photo-album-plus' ), $photo );
						$err = '1';
					}
					break;

				case 'status':
				case 'owner':
				case 'name':
				case 'description':
				case 'p_order':
				case 'linkurl':
				case 'linktitle':
				case 'linktarget':
				case 'tags':
				case 'alt':
				case 'videox':
				case 'videoy':
					switch ( $item ) {
						case 'status':
							$itemname = __( 'Status', 'wp-photo-album-plus' );
							if ( ! current_user_can( 'wppa_moderate' ) && ! current_user_can( 'wppa_admin' ) ) {
								$txt = __( 'Security check failure', 'wp-photo-album-plus' ) . ' #78';
								$err = '1';
							}
							else {
								wppa_invalidate_treecounts( wppa_get_photo_item( $photo, 'album' ) );
								if ( wppa_get_photo_item( $photo, 'status' ) == 'pending' && $value == 'publish' ) {
									wppa_schedule_mailinglist( 'photoapproved', 0, $photo );
								}
							}
							break;
						case 'name':
							$itemname = __( 'Name', 'wp-photo-album-plus' );
							break;
						case 'description':
							$itemname = __( 'Description', 'wp-photo-album-plus' );
							break;
						case 'p_order':
							$itemname = __( 'Photo order #' , 'wp-photo-album-plus' );
							break;
						case 'owner':
							$itemname = __( 'Owner' , 'wp-photo-album-plus' );
							$usr = wppa_get_user_by( 'login', $value );
							if ( ! $usr ) {
								$txt = sprintf( __( 'User %s does not exist' , 'wp-photo-album-plus' ), $value );
								$err = '4';
							}
							else {
								$value = $usr->user_login;	// Correct possible case mismatch
								wppa_flush_upldr_cache( 'photoid', $photo ); 		// Current owner
								wppa_flush_upldr_cache( 'username', $value );		// New owner
							}
							break;
						case 'linkurl':
							$itemname = __( 'Link url' , 'wp-photo-album-plus' );
							break;
						case 'linktitle':
							$itemname = __( 'Link title' , 'wp-photo-album-plus' );
							break;
						case 'linktarget':
							$itemname = __( 'Link target' , 'wp-photo-album-plus' );
							break;
						case 'tags':
							$itemname = __( 'Photo Tags' , 'wp-photo-album-plus' );
							break;
						case 'status':
							$itemname = __( 'Status' , 'wp-photo-album-plus' );
							break;
						case 'alt':
							$itemname = __( 'HTML Alt' , 'wp-photo-album-plus' );
							break;
						case 'videox':
							$itemname = __( 'Video width' , 'wp-photo-album-plus' );
							if ( ! wppa_is_int( $value ) || $value < '0' ) {
								$txt = __( 'Please enter an integer value >= 0', 'wp-photo-album-plus' );
								wppa_json_photo_update( $photo, $txt, '1' );
							}
							break;
						case 'videoy':
							$itemname = __( 'Video height', 'wp-photo-album-plus' );
							if ( ! wppa_is_int( $value ) || $value < '0' ) {
								$txt = __( 'Please enter an integer value >= 0', 'wp-photo-album-plus' );
								wppa_json_photo_update( $photo, $txt, '1' );
							}
							break;

						default:
							$itemname = $item;
					}

					if ( ! $err ) $dbfields[$item] = $value;

					// If set to featured, try to copy to wp media
					if ( $item == 'status' && $value == 'featured' ) {

						require_once( ABSPATH . 'wp-admin/includes/media.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						$media_data = wppa_get_media_data( $photo );
						$image_path = $media_data['path'];
						$ext 		= $media_data['ext'];
						$type 		= $media_data['mime'];

						if ( wppa_is_file( $image_path ) ) {

							// To avoid media_handle_sideload() to delete the from file, make a temp copy
							$tempdir 	= WPPA_UPLOAD_PATH . '/temp';
							$temp_file 	= $tempdir . '/' . basename( $image_path );
							wppa_mktree( $tempdir );
							wppa_copy( $image_path, $temp_file );

							$image_size = wppa_filesize( $image_path );
							if ( is_file( $temp_file ) ) {
								$file = array(
								   'name' 		=> wppa_strip_ext( wppa_get_photo_item( $photo, 'filename' ) ) . '.' . $ext,
								   'type' 		=> $type,
								   'tmp_name' 	=> $temp_file,
								   'error' 		=> 0,
								   'size' 		=> $image_size
								);
								$att_id = media_handle_sideload( $file, 0, wppa_get_photo_name( $photo ) );
								if ( is_wp_error( $att_id ) ) {
									wppa_log( 'err', 'Could not export '. $file['name'] . ' to wp media. err = ' . $att_id->get_error_message() . ' Filespec = ' . var_export( $file, true )  );
								}
								else {

									$post = get_post( $att_id );
									$post->post_title 	= wppa_get_photo_name( $photo );
									$post->post_content = wppa_get_photo_desc( $photo );
									$aret = wp_update_post( $post );
									if ( $aret != $att_id ) {
										wppa_log( 'err', 'Could not set name and desc to attachment ' . $att_id );
									}
								}
							}
							else {
								wppa_log( 'err', 'Tempfile ' . $temp_file . ' not found while exporting to wp media');
							}
						}
						else {
							wppa_log('err', 'Image path ' . $image_path . ' not found while trying to copy to wp media' );
						}
					}

					$jsfields['tagsmod'] = true;
					break;

				case 'year':
				case 'month':
				case 'day':
				case 'hour':
				case 'min':
					$itemname = __( 'Schedule date/time' , 'wp-photo-album-plus' );
					$scheduledtm = $wpdb->get_var( $wpdb->prepare( "SELECT scheduledtm FROM $wpdb->wppa_photos WHERE id = %s", $photo ) );
					if ( ! $scheduledtm ) {
						$scheduledtm = wppa_get_default_scheduledtm();
					}
					$temp = explode( ',', $scheduledtm );
					if ( $item == 'year' ) 	$temp[0] = $value;
					if ( $item == 'month' ) $temp[1] = $value;
					if ( $item == 'day' ) 	$temp[2] = $value;
					if ( $item == 'hour' ) 	$temp[3] = $value;
					if ( $item == 'min' ) 	$temp[4] = $value;
					$scheduledtm = implode( ',', $temp );
					$dbfields['scheduledtm'] = $scheduledtm;
					$dbfields['status'] = 'scheduled';
					wppa_invalidate_treecounts( $wpdb->get_var( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos WHERE id = %s", $photo ) ) );
					wppa_flush_upldr_cache( 'photoid', $photo );
					break;

				case 'delyear':
				case 'delmonth':
				case 'delday':
				case 'delhour':
				case 'delmin':
					$itemname = __( 'Delete date/time' , 'wp-photo-album-plus' );
					$scheduledel = $wpdb->get_var( $wpdb->prepare( "SELECT scheduledel FROM $wpdb->wppa_photos WHERE id = %s", $photo ) );
					if ( ! $scheduledel ) {
						$scheduledel = wppa_get_default_scheduledtm();
					}
					$temp = explode( ',', $scheduledel );
					if ( $item == 'delyear' ) 	$temp[0] = $value;
					if ( $item == 'delmonth' ) 	$temp[1] = $value;
					if ( $item == 'delday' ) 	$temp[2] = $value;
					if ( $item == 'delhour' ) 	$temp[3] = $value;
					if ( $item == 'delmin' ) 	$temp[4] = $value;
					$scheduledel = implode( ',', $temp );

					// Make sure not deleted yet
					$alb = $wpdb->get_var( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos WHERE id = %s", $photo ) );
					if ( $alb < '-9' ) {
						$alb = - ( $alb + '9' );
						$dbfields['album'] = $alb;
					}
					$dbfields['scheduledel'] = $scheduledel;
					wppa_invalidate_treecounts( $alb );
					wppa_flush_upldr_cache( 'photoid', $photo );
					break;
				case 'removescheduledel':
					if ( ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) ) ) {
						$dbfields['scheduledel'] = '';
						$txt = sprintf( __( 'Scheduled deletion of photo %s cancelled' , 'wp-photo-album-plus' ), $photo );
					}
					else {
						$txt = __( 'No rights', 'wp-photo-album-plus' );
						$err = '1';
					}
					break;

				case 'custom_0':
				case 'custom_1':
				case 'custom_2':
				case 'custom_3':
				case 'custom_4':
				case 'custom_5':
				case 'custom_6':
				case 'custom_7':
				case 'custom_8':
				case 'custom_9':
					$index 		= substr( $item, -1 );
					$custom 	= wppa_get_photo_item( $photo, 'custom' );
					if ( $custom ) {
						$custom_data = wppa_unserialize( $custom );
					}
					else {
						$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
					}
					$custom_data[$index] = wppa_sanitize_custom_field( $value );
					$custom = serialize( $custom_data );
					$dbfields['custom'] = $custom;
					$txt = sprintf( __( 'Custom field %s of photo %s updated' , 'wp-photo-album-plus' ), wppa_opt( 'custom_caption_'.$index ), $photo );
					break;

				case 'file':

					// Check on upload error
					if ( $_FILES['photo']['error'] ) {
						$txt = __( 'Error during upload.', 'wp-photo-album-plus' );
						wppa_json_photo_update( $photo, $txt, '1' );
						wppa_exit();
					}

					// Make new source filename
					$filename = wppa_fix_poster_ext( wppa_get_photo_item( $photo, 'filename' ), $photo );

					// If very old, no filename, take new name
					if ( ! $filename ) {
						$filename = sanitize_file_name( $_FILES['photo']['name'] );
						$dbfields['filename'] = $filename;
					}
					wppa_save_source( $_FILES['photo']['tmp_name'], $filename, wppa_get_photo_item( $photo, 'album') );

					// Make proper oriented source
					wppa_make_o1_source( $photo );

					// Make the files
					wppa( 'unsanitized_filename', $_FILES['photo']['name'] );
					$alb = wppa_get_photo_item( $photo, 'album' );
					$source = wppa_get_source_album_dir( $alb ).'/'.$filename;
					if ( is_file( $source ) ) {
						$from = $source;
					}
					else {
						$from = $_FILES['photo']['tmp_name'];
					}
					$bret = wppa_make_the_photo_files( $from, $photo, strtolower( wppa_get_ext( $_FILES['photo']['name'] ) ) );
					if ( $bret ) {

						// Update timestamps and sizes
						$alb = wppa_get_photo_item( $photo, 'album' );
						wppa_update_album( $alb );
						$dbfields = array_merge( $dbfields, ['modified' => time(), 'thumbx' => '0', 'thumby' => '0', 'photox' => '0', 'photoy' => '0'] );

						// Report success
						$txt = __( 'Photo files updated.', 'wp-photo-album-plus' );
					}
					else {

						// Report fail
						$txt = __( 'Could not update files.', 'wp-photo-album-plus' );
						$err = '1';
					}
					$jsfields['thumbmod'] = true;
					$jsfields['photomod'] = true;
					break;

				case 'stereo':
					$itemname = __( 'Stereo mode', 'wp-photo-album-plus' );
					wppa_create_stereo_images( $photo );
					wppa_create_thumbnail( $photo );
					$dbfields['stereo'] = $value;
					$jsfields['thumbmod'] = true;
					$jsfields['photomod'] = true;
					break;

				case 'panorama':
					$itemname = __( 'Panorama mode', 'wp-photo-album-plus' );

					// If it was 360 and now different, delete o1 file and remake files
					$cur = wppa_get_photo_item( $photo, 'panorama' );
					if ( $cur == '1' && $value != '1' ) {
						$s = wppa_get_o1_source_path( $photo );
						if ( wppa_is_file( $s ) ) {
							wppa_unlink( $s );
						}
						wppa_remake_files( '', $photo );
					}

					// Make sure x and y are correct
					$x = wppa_get_photox( $photo, true );
					$y = wppa_get_photoy( $photo, true );

					// See if spheric and needs conversion, assume its for 360
					if ( $value == '1' && $x > 2.01 * $y ) {
						$bret = wppa_make_360( $photo, 360 );
						if ( $bret ) {
							$x = wppa_get_photox( $photo, true );
							$y = wppa_get_photoy( $photo, true );
							$dbfields = array_merge( $dbfields, ['panorama' => '1', 'angle' => '360', 'photox' => $x, 'photoy' => $y] );
							wppa_remake_files( '', $photo );
							$txt = sprintf( __( 'Panorama set to %s and converted', 'wp-photo-album-plus' ), $value );
						}
						else {
							$txt = sprintf( __( 'Panorama set to %s but failed to convert', 'wp-photo-album-plus' ), $value );
						}
					}

					// Not spheric or no conversion needed
					else {
						$dbfields = array_merge( $dbfields, ['panorama' => $value, 'photox' => $x, 'photoy' => $y, 'angle' => '0'] );

						$txt = sprintf( __( 'Panorama set to %s', 'wp-photo-album-plus' ), $value );
					}

					break;

				case 'thumblock':
					$dbfields['thumblock'] = $value ? '1' : '0';
					$txt = __( 'Thumbfile', 'wp-photo-album-plus' ) . ( $value ? ' ' : ' un' ) . 'locked';
					break;

				case 'make360':

					// If degs == 0, remove it
					$s = wppa_get_o1_source_path( $photo );
					if ( wppa_is_file( $s ) ) wppa_unlink( $s );

					if ( $value ) {
						$bret = wppa_make_360( $photo, $value );
						if ( $bret ) {
							$x = wppa_get_photox( $photo, true );
							$y = wppa_get_photoy( $photo, true );
							$dbfields = array_merge( $dbfields, ['angle' => $value, 'photox' => $x, 'photoy' => $y] );
							wppa_remake_files( '', $photo );
							$txt = __( 'Photo converted', 'wp-photo-album-plus' );
						}
						else {
							$txt = __( 'Photo conversion failed', 'wp-photo-album-plus' );
						}
					}
					else {
						$dbfields['angle'] = '0';
						wppa_remake_files( '', $photo );
						$txt = __( 'Converted photo removed', 'wp-photo-album-plus' );
					}
					$jsfields['thumbmod'] = true;
					$jsfields['photomod'] = true;
					break;

				case 'misc':
					$dbfields['misc'] = $value;
					$txt = __( 'Page indicator updated', 'wp-photo-album-plus' );
					break;

				// Pdf to jpg
				case 'pdftojpg':
					$id 	= wppa_get( 'photo-id' );
					$nonce 	= wppa_get( 'nonce' );

					$pat = wppa_strip_ext( wppa_get_source_path( $id ) );
					$jpg = $pat . '.jpg';
					$pdf = $pat . '.pdf';
					if ( wppa_is_file( $jpg ) && wppa_is_file( $pdf ) && ! wppa_is_pdf_multiple( $id ) ) {
						wppa_unlink( $pdf );
						wppa_update_photo( $id, ['ext' => 'jpg', 'filename' => basename( $jpg )] );
						$txt = __( 'Conversion complete', 'wp-photo-album-plus' );
						$jsfields['filename'] = true;
						$jsfields['ext'] = true;
					}
					else {
						$err = 0;
						if ( ! wppa_is_file( $jpg ) ) $err += 100;
						if ( ! wppa_is_file( $pdf ) ) $err += 10;
						if ( wppa_is_pdf_multiple( $id ) ) $err += 1;
						$txt = __( 'Conversion failed', 'wp-photo-album-plus' ) . ' ' . $err;
						$err = '1';
					}
					wppa_json_photo_update( $photo, $txt, $err, $jsfields );
					wppa_exit();
					break;

				default:
					$txt = 'This update action is not implemented yet (' . $item . ')';
					$err = '1';
				break;
			}

			// Update db optionally
			if ( ! empty( $dbfields ) ) {
				$iret = wppa_update_photo( $photo, $dbfields );
			}
			else {
				$iret = true;
			}

			// Prepare json text
			if ( $iret === false ) {
				$txt = sprintf( __( 'An error occurred while trying to update %s of photo %s', 'wp-photo-album-plus' ), $itemname, $photo );
				$err = '1';
			}
			if ( ! $txt ) {
				if ( $err ) {
					if ( wppa_is_video( $photo ) ) {
						$txt = sprintf( __( 'Could not update %s of video %s', 'wp-photo-album-plus' ), $itemname, $photo );
					}
					else {
						$txt = sprintf( __( 'Could not update %s of photo %s', 'wp-photo-album-plus' ), $itemname, $photo );
					}
				}
				else {
					if ( wppa_is_video( $photo ) ) {
						$txt = sprintf( __( '%s of video %s updated' , 'wp-photo-album-plus' ), $itemname, $photo );
					}
					else {
						$txt = sprintf( __( '%s of photo %s updated' , 'wp-photo-album-plus' ), $itemname, $photo );
					}
				}
			}
			wppa_json_photo_update( $photo, $txt, $err, $jsfields );
			wppa_exit();
			break;

		// Pdf to album
		case 'pdftoalbum':

			// The item id
			$id 		= wppa_get( 'photo-id' );

			// Check validity
			$nonce 		= wppa_get( 'nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$id ) ) {
				echo __( 'You do not have the rights to do this' , 'wp-photo-album-plus' );
				wppa_exit();
			}

			// Get possible switches
			$contin 	= wppa_get( 'continue', false, 'bool' );
			$stop 		= wppa_get( 'stop', false, 'bool' );

			// Stop request?
			if ( $stop ) {
				update_option( 'stop-pdfcnv-' . $id, 'yes' );
				echo __( 'Conversion stopped', 'wp-photo-album-plus' );
				wppa_exit();
			}

			// Start or continue Request
			delete_option( 'stop-pdfcnv-' . $id );

			// Start (over): erase ready switch and set start page at first page
			if ( ! $contin ) {
				wppa_update_pdf_conv_parms( $id, ['pagesdone' => 0, 'ready' => false] );
			}

			// Get the required parms
			$cnvparms 	= wppa_get_pdf_conv_parms( $id );
			$alb 		= $cnvparms['album'];
			$page 		= $cnvparms['pagesdone'];

			// Album vanished? start all over
			if ( $alb && ! wppa_album_exists( $alb ) ) {
				wppa_update_pdf_conv_parms( $id, ['album' => 0, 'pagesdone' => 0, 'ready' => false] );
				$alb = 0;
				$page = 0;
			}

			// Invoke process
			$result = wppa_pdf_to_album( $id, $alb, $page );

			// Output result
			wppa_echo( $result );
			wppa_exit();
			break;

		// Update iptc
		case 'update-iptc':
			$photo 	= wppa_get( 'photo-id' );
			$nonce 	= wppa_get( 'nonce' );
			$item  	= wppa_get( 'item' );
			$value 	= wppa_get( 'value' );
			$value 	= wppa_decode( $value );
			$tag 	= wppa_get( 'tagname' );

			// Check validity
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$photo ) ) {
				$txt = __( 'You do not have the rights to update photo information' , 'wp-photo-album-plus' );
				wppa_json_photo_update( $photo, $txt, '1' );
			}

			// Valid update request
			else {
				$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_iptc
													   SET description = %s
													   WHERE id = %d", $value, $item ) );

				$txt = sprintf( __( 'IPTC Tag %s updated', 'wp-photo-album-plus' ), $tag );
				wppa_json_photo_update( $photo, $txt );
			}

			break;

		// The wppa-settings page calls ajax with $wppa_action == 'update-option';
		case 'update-option':

			$ok = false;

			// Verify that we are legally here
			if ( current_user_can( 'wppa_settings' ) ) {
				$ok = true;
			}
			if ( current_user_can( 'wppa_edit_tags' ) ) {
				if ( in_array( wppa_get( 'option' ), ['tag_to_edit', 'new_tag_value'] ) ) {
					$ok = true;
				}
			}
			if ( current_user_can( 'wppa_edit_email' ) ) {
				if ( in_array( wppa_get( 'option' ),
					['newalbumnotify',
					 'feuploadnotify',
					 'commentnotify',
					 'commentprevious',
					 'moderatephoto',
					 'moderatecomment',
					 'photoapproved',
					 'commentapproved',
					 'clear-newalbumnotify',
					 'clear-feuploadnotify',
					 'clear-commentnotify',
					 'clear-commentprevious',
					 'clear-moderatephoto',
					 'clear-moderatecomment',
					 'clear-photoapproved',
					 'clear-commentapproved',
					] ) ) {
						$ok = true;
					}
			}

			if ( ! $ok ) {
				wppa_echo( '||1||'.__( 'Insufficient access rights', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			$nonce  = wppa_get( 'nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce' ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( '||1||'.__( 'Security check failure', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			// Initialize
			$old_minisize = wppa_get_minisize();			// Remember for later, maybe we do something that requires regen
			$option = 'wppa_' . wppa_get( 'option' );		// The option to be processed
			$filter = 'text';
			if ( in_array( $option, ['wppa_newphoto_description', 'wppa_bc_txt', 'wppa_custom_content', 'wppa_copyright_notice'] ) ) {
				$filter = 'html';
			}
			if ( in_array( $option, ['wppa_custom_content',
									 'wppa_search_toptext',
									 'wppa_search_selbox_0',
									 'wppa_search_selbox_1',
									 'wppa_search_selbox_2',
									 'wppa_copyright_notice',
									 'wppa_newphoto_description',
									 'wppa_admin_inline_css',
									 'wppa_custom_album_proc',
									 'wppa_custom_photo_proc',
									 'wppa_textual_watermark_text'] ) ) {
				$filter = 'textarea';
			}
			$value  = wppa_decode( wppa_get( 'value', '', $filter ) );	// The new value, may also contain & # and +
			$value  = stripslashes( $value );

			if ( $option == 'wppa_nicescroll_opts' ) {
				$value = wppa_decode( $_REQUEST['value'] );
				$value = stripslashes( $value );
			}

			$value 	= trim( $value ); 	// Remaove surrounding spaces
			$alert  = '';				// Init the return string data
			wppa( 'error', '0' );		//
			$title  = '';				//


			// If it is a font family, change all double quotes into single quotes as this destroys much more than you would like
			if ( strpos( $option, 'wppa_fontfamily_' ) !== false ) $value = str_replace( '"', "'", $value );

			$option = wppa_decode( $option );

			// Dispatch on option
			if ( $option == 'wppa_getspinnerpreview' ) {
				if ( wppa_get( 'type' ) == 'normal' ) {
					wppa_echo( wppa_get_spinner_svg_html( array( 	'size' 		=> 60,
																	'display' 	=> 'inline',
																	'lightbox' 	=> false,
																	'position' 	=> 'relative',
																	'left' 		=> '0',
																	'top' 		=> '0',
																	'margin' 	=> '0',
					) ) );
				}
				elseif ( wppa_get( 'type' ) == 'lightbox' ) {
					wppa_echo( wppa_get_spinner_svg_html( array( 	'size' 		=> 60,
																	'display' 	=> 'inline',
																	'lightbox' 	=> true,
																	'position' 	=> 'relative',
																	'left' 		=> '0',
																	'top' 		=> '0',
																	'margin' 	=> '0',
					) ) );
				}
				else {
					wppa_echo( 'Error' );
				}
				wppa_exit();
			}

			// Clear mailinglist
			if ( in_array( wppa_get( 'option' ),
					['clear-newalbumnotify',
					 'clear-feuploadnotify',
					 'clear-commentnotify',
					 'clear-commentprevious',
					 'clear-moderatephoto',
					 'clear-moderatecomment',
					 'clear-photoapproved',
					 'clear-commentapproved',] ) ) {
				$type = substr( wppa_get( 'option' ), 6 );

				// Do it
				delete_option( 'wppa_mailinglist_' . $type );
				wppa_echo("Mailinglist $type successfully cleared");
				wppa_exit();
			}

			if ( substr( $option, 0, 16 ) == 'wppa_iptc_label_' ) {
				$tag = substr( $option, 16 );
				$q = $wpdb->prepare( "UPDATE $wpdb->wppa_iptc SET description = %s WHERE tag = %s AND photo = '0'", $value, $tag );
				$bret = $wpdb->query( $q );
				// Produce the response text
				if ( $bret ) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				wppa_echo( $output );
				wppa_exit();
			}
			elseif ( substr( $option, 0, 17 ) == 'wppa_iptc_status_' ) {
				$tag = substr( $option, 17 );
				$q = $wpdb->prepare( "UPDATE $wpdb->wppa_iptc SET status = %s WHERE tag = %s AND photo = '0'", $value, $tag );
				$bret = $wpdb->query( $q );
				// Produce the response text
				if ( $bret ) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				wppa_echo( $output );
				wppa_exit();
			}
			elseif ( substr( $option, 0, 16 ) == 'wppa_exif_label_' ) {
				$tag = substr( $option, 16 );
				$q = $wpdb->prepare( "UPDATE $wpdb->wppa_exif SET description = %s WHERE tag = %s AND photo = '0'", $value, $tag );
				$bret = $wpdb->query( $q );
				// Produce the response text
				if ( $bret ) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				wppa_echo( $output );
				wppa_exit();
			}
			elseif ( substr( $option, 0, 17 ) == 'wppa_exif_status_' ) {
				$tag = substr( $option, 17 );
				$q = $wpdb->prepare( "UPDATE $wpdb->wppa_exif SET status = %s WHERE tag = %s AND photo = '0'", $value, $tag );
				$bret = $wpdb->query( $q );
				// Produce the response text
				if ( $bret ) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				wppa_echo( $output );
				wppa_exit();
			}
			elseif ( substr( $option, 0, 10 ) == 'wppa_caps-' ) {	// Is capability setting
				global $wp_roles;
				//$R = new WP_Roles;
				$setting = explode( '-', $option );
				if ( $value == 'yes' ) {
					$wp_roles->add_cap( $setting[2], $setting[1] );
					wppa_echo( '||0||'.__( 'Capability granted' , 'wp-photo-album-plus' ).'||' );
					wppa_exit();
				}
				elseif ( $value == 'no' ) {
					$wp_roles->remove_cap( $setting[2], $setting[1] );
					wppa_echo( '||0||'.__( 'Capability withdrawn' , 'wp-photo-album-plus' ).'||' );
					wppa_exit();
				}
				else {
					wppa_echo( '||1||Invalid value: '.$value.'||' );
					wppa_exit();
				}
			}
			elseif ( substr( $option, 0, 8 ) == 'wppa_qr_' ) { // Is qr code setting
				if ( wppa_is_dir( WPPA_UPLOAD_PATH . '/qr' ) ) {
					$caches = wppa_glob( WPPA_UPLOAD_PATH . '/qr/*.svg' );
					if ( $caches ) foreach ( $caches as $cache ) {
						unlink( $cache );
					}
				}
				wppa_update_option( 'wppa_qr_cache_hits', '0' );
				wppa_update_option( 'wppa_qr_cache_miss', '0' );
				wppa_update_option( $option, $value );
				$title = sprintf( __( 'Setting %s updated to %s', 'wp-photo-album-plus' ), $option, $value );

				// Something to do after changing the setting?
				wppa_initialize_runtime( true );	// force reload new values

				// Produce the response text
				$output = '||0||'.esc_attr( $title ).'||';

				wppa_echo( $output );
				wppa_clear_cache( ['qr' => true] );
				wppa_exit();
				break;	// End update qr setting
			}

			else switch ( $option ) {

				// Custom mainetance procedures
				case 'wppa_custom_album_proc':
				case 'wppa_custom_photo_proc':
					$value = wppa_get( 'value', '', 'php' );
					$err = false;
					$path = WPPA_UPLOAD_PATH . '/procs/' . $option . '.php';
					if ( ! wppa_is_dir( dirname( $path ) ) ) {
						wppa_mkdir( dirname( $path ) );
					}
					$bret = wppa_put_contents( $path, '<?php ' . $value );

					if ( $bret ) {
						$title = __( 'Code successfully saved', 'wp-photo-album-plus' );
					}
					else {
						$title = __( 'Failed to save code', 'wp-photo-album-plus' );
						$alert = $title;
					}
					break;

				// Changing potd_album_type ( physical / virtual ) also clears potd_album
				case 'wppa_potd_album_type':
					if ( ! in_array( $value, array( 'physical', 'virtual' ) ) ) {
						wppa_echo( '||1||Invalid value: '.$value.'||' );
						wppa_exit();
					}
					if ( $value == 'physical' ) {
						wppa_update_option( 'wppa_potd_album', '' );
					}
					else {
						wppa_update_option( 'wppa_potd_album', 'all' );
					}
					wppa_update_option( 'wppa_potd_id_cache', false );
					break;
				case 'wppa_potd_album':
					if ( wppa_opt( 'potd_album_type' ) == 'physical' ) {
						$value = str_replace( '.', ',', ( wppa_expand_enum( str_replace( ',', '.', $value ) ) ) );
					}
					wppa_update_option( 'wppa_potd_id_cache', false );
					break;

				case 'wppa_initial_colwidth'://??  fixed   low	  high	  title
					wppa_ajax_check_range( $value, false, '100', false, __( 'Initial width.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_fullsize':
					wppa_ajax_check_range( $value, false, '100', false, __( 'Full size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_maxheight':
					wppa_ajax_check_range( $value, false, '100', false, __( 'Max height.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_film_thumbsize':
				case 'wppa_thumbsize':
				case 'wppa_thumbsize_alt':
					wppa_ajax_check_range( $value, false, '50', false, __( 'Thumbnail size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_tf_width':
				case 'wppa_tf_width_alt':
					wppa_ajax_check_range( $value, false, '50', false, __( 'Thumbnail frame width' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_tf_height':
				case 'wppa_tf_height_alt':
					wppa_ajax_check_range( $value, false, '50',false,  __( 'Thumbnail frame height' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_tn_margin':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Thumbnail Spacing' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_thumb_page_size':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Thumb page size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_smallsize':
					wppa_ajax_check_range( $value, false, '50', false, __( 'Cover photo size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_smallsize_percentage':
					wppa_ajax_check_range( $value, false, '10', '100', __( 'Cover photo size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_album_page_size':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Album page size.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_topten_count':
					wppa_ajax_check_range( $value, false, '2', false, __( 'Number of TopTen photos' , 'wp-photo-album-plus' ), '40' );
					break;
				case 'wppa_topten_size':
					wppa_ajax_check_range( $value, false, '32', false, __( 'Widget image thumbnail size' , 'wp-photo-album-plus' ), wppa_get_minisize() );
					break;
				case 'wppa_max_cover_width':
					wppa_ajax_check_range( $value, false, '150', false, __( 'Max Cover width' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_text_frame_height':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Minimal description height' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_cover_minheight':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Minimal cover height' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_head_and_text_frame_height':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Minimal text frame height' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_bwidth':
					wppa_ajax_check_range( $value, '', '0', false, __( 'Border width' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_bradius':
					wppa_ajax_check_range( $value, '', '0', false, __( 'Border radius' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_box_spacing':
					wppa_ajax_check_range( $value, '', '-20', '100', __( 'Box spacing' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_popupsize':
					$floor = wppa_opt( 'thumbsize' );
					$temp  = wppa_opt( 'smallsize' );
					if ( $temp > $floor ) $floor = $temp;
					wppa_ajax_check_range( $value, false, $floor, wppa_opt( 'fullsize' ), __( 'Popup size' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_fullimage_border_width':
					wppa_ajax_check_range( $value, '', '0', false, __( 'Fullsize border width' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_lightbox_bordersize':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Lightbox Bordersize' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_ovl_border_width':
					wppa_ajax_check_range( $value, false, '0', '16', __( 'Lightbox Borderwidth' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_ovl_border_radius':
					wppa_ajax_check_range( $value, false, '0', '16', __( 'Lightbox Borderradius' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_comment_count':
					wppa_ajax_check_range( $value, false, '2', '40', __( 'Number of Comment widget entries' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_comment_size':
					wppa_ajax_check_range( $value, false, '32', wppa_get_minisize(), __( 'Comment Widget image thumbnail size' , 'wp-photo-album-plus' ), wppa_get_minisize() );
					break;
				case 'wppa_thumb_opacity':
					wppa_ajax_check_range( $value, false, '0', '100', __( 'Opacity.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_cover_opacity':
					wppa_ajax_check_range( $value, false, '0', '100', __( 'Opacity.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_star_opacity':
					wppa_ajax_check_range( $value, false, '0', '50', __( 'Opacity.' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_gravatar_size':
					wppa_ajax_check_range( $value, false, '10', '256', __( 'Avatar size' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_watermark_opacity':
					wppa_ajax_check_range( $value, false, '0', '100', __( 'Watermark opacity' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_watermark_opacity_text':
					wppa_ajax_check_range( $value, false, '0', '100', __( 'Watermark opacity' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_ovl_txt_lines':
					wppa_ajax_check_range( $value, 'auto', '0', '24', __( 'Number of text lines' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_ovl_opacity':
					wppa_ajax_check_range( $value, false, '0', '100', __( 'Overlay opacity' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_upload_limit_count':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Upload limit' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_dislike_mail_every':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Notify inappropriate' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_dislike_set_pending':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Dislike pending' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_dislike_delete':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Dislike delete' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_cp_points_comment':
				case 'wppa_cp_points_comment_appr':
				case 'wppa_cp_points_rating':
				case 'wppa_cp_points_upload':
					wppa_ajax_check_range( $value, false, '0', false, __( 'myCRED / Cube Points' , 'wp-photo-album-plus' ) );
					break;
				case 'wppa_jpeg_quality':
					wppa_ajax_check_range( $value, false, '20', '100', __( 'JPG Image quality' , 'wp-photo-album-plus' ) );
					if ( wppa_cdn( 'admin' ) == 'cloudinary' && ! wppa( 'out' ) ) {
						wppa_delete_derived_from_cloudinary();
					}
					break;
				case 'wppa_imgfact_count':
					wppa_ajax_check_range( $value, false, '1', '24', __( 'Number of coverphotos', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_dislike_value':
					wppa_ajax_check_range( $value, false, '-10', '0', __( 'Dislike value', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_slideshow_pagesize':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Slideshow pagesize', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_slideonly_max':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Slideonly max', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_pagelinks_max':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Max Pagelinks', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_area_size':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Thumbnail area max size', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_area_size_slide':
					wppa_ajax_check_range( $value, false, '0', false, __( 'Slideshow area max size', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_cover_spacing':
					wppa_ajax_check_range( $value, false, '0', '50', __( 'Cover spacing', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_user_create_max_level':
					wppa_ajax_check_range( $value, false, '0', '99', __( 'Max nesting level', 'wp-photo-album-plus' ) );
					break;
				case 'wppa_sticky_header_size':
					wppa_ajax_check_range( $value, false, '0', '400', __('Sticky header size', 'wp-photo-album-plus' ) );
					break;

				case 'wppa_rating_clear':
					$iret = wppa_clear_table( WPPA_RATING ) &&
							wppa_clear_col( WPPA_PHOTOS, 'mean_rating', '0' ) &&
							wppa_clear_col( WPPA_PHOTOS, 'rating_count', '0' );

					if ( $iret !== false ) {
						delete_option( 'wppa_'.WPPA_RATING.'_lastkey' );
						$title = __( 'Ratings cleared' , 'wp-photo-album-plus' );
					}
					else {
						$title = __( 'Could not clear ratings' , 'wp-photo-album-plus' );
						$alert = $title;
						wppa( 'error', '1' );
					}
					break;
				case 'wppa_viewcount_clear':
					$iret = wppa_clear_col( WPPA_PHOTOS, 'views', '0' ) &&
							wppa_clear_col( WPPA_ALBUMS, 'views', '0' );
					if ( $iret !== false ) {
						$title = __( 'Viewcounts cleared' , 'wp-photo-album-plus' );
					}
					else {
						$title = __( 'Could not clear viewcounts' , 'wp-photo-album-plus' );
						$alert = $title;
						wppa( 'error', '1' );
					}
					break;

				case 'wppa_iptc_clear':
					$iret = wppa_clear_table( WPPA_IPTC );
					if ( $iret !== false ) {
						delete_option( 'wppa_'.WPPA_IPTC.'_lastkey' );
						$title = __( 'IPTC data cleared' , 'wp-photo-album-plus' );
						wppa_update_option( 'wppa_index_need_remake', 'yes' );
					}
					else {
						$title = __( 'Could not clear IPTC data' , 'wp-photo-album-plus' );
						$alert = $title;
						wppa( 'error', '1' );
					}
					break;

				case 'wppa_exif_clear':
					$iret = wppa_clear_table( WPPA_EXIF );
					if ( $iret !== false ) {
						delete_option( 'wppa_'.WPPA_EXIF.'_lastkey' );
						$title = __( 'EXIF data cleared' , 'wp-photo-album-plus' );
						wppa_update_option( 'wppa_index_need_remake', 'yes' );
					}
					else {
						$title = __( 'Could not clear EXIF data' , 'wp-photo-album-plus' );
						$alert = $title;
						wppa( 'error', '1' );
					}
					break;

				case 'wppa_recup':
					$result = wppa_recuperate_iptc_exif();
					wppa_echo( '||0||'.__( 'Recuperation performed' , 'wp-photo-album-plus' ).'||'.$result );
					wppa_exit();
					break;

				case 'wppa_bgcolor_thumbnail':
					$value = trim( strtolower( $value ) );
					if ( strlen( $value ) != '7' || substr( $value, 0, 1 ) != '#' ) {
						wppa( 'error', '1' );
					}
					else for ( $i=1; $i<7; $i++ ) {
						if ( ! in_array( substr( $value, $i, 1 ), array( '0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f' ) ) ) {
							wppa( 'error', '1' );
						}
					}
					if ( ! wppa( 'error' ) ) $old_minisize--;	// Trigger regen message
					else $alert = __( 'Illegal format. Please enter a 6 digit hexadecimal color value. Example: #77bbff' , 'wp-photo-album-plus' );
					break;

				case 'wppa_thumb_aspect':
					$old_minisize--;	// Trigger regen message
					break;

				case 'wppa_rating_max':
					if ( $value == '5' && wppa_opt( 'rating_max' ) == '10' ) {
						$rats = $wpdb->get_results( 'SELECT id, value FROM '.WPPA_RATING.'', ARRAY_A );
						if ( $rats ) {
							foreach ( $rats as $rat ) {
								wppa_update_rating( $rat['id'], ['value' => $rat['value'] / 2] );
							}
						}
					}
					if ( $value == '10' && wppa_opt( 'rating_max' ) == '5' ) {
						$rats = $wpdb->get_results( 'SELECT id, value FROM '.WPPA_RATING.'', ARRAY_A );
						if ( $rats ) {
							foreach ( $rats as $rat ) {
								wppa_update_rating( $rat['id'], ['value' => $rat['value'] * 2] );
							}
						}
					}

					wppa_update_option ( 'wppa_rerate_status', 'Required' );
					$alert .= __( 'You just changed a setting that requires the recalculation of ratings.' , 'wp-photo-album-plus' );
					$alert .= ' '.__( 'Please run the appropriate maintenance procedure.' , 'wp-photo-album-plus' );

					wppa_update_option( $option, $value );
					wppa( 'error', '0' );
					break;

				case 'wppa_newphoto_description':
					if ( wppa_switch( 'wppa_compress_newdesc' ) ) {
						$value = wppa_compress_html( $value );
					}
					wppa_update_option( $option, $value );
					wppa( 'error', '0' );
					$alert = '';
					wppa_index_compute_skips();
					break;

				case 'wppa_keep_source':
					$dir = wppa_opt( 'source_dir' );
					if ( ! wppa_is_dir( $dir ) ) wppa_mkdir( $dir );
					if ( ! wppa_is_dir( $dir ) || ! is_writable( $dir ) ) {
						wppa( 'error', '1' );
						$alert = sprintf( __( 'Unable to create or write to %s' , 'wp-photo-album-plus' ), $dir );
					}
					break;

				case 'wppa_source_dir':
					$olddir = wppa_opt( 'source_dir' );
					$value = rtrim( $value, '/' );
					if ( strpos( $value.'/', WPPA_UPLOAD_PATH.'/' ) !== false ) {
						wppa( 'error', '1' );
						$alert = sprintf( __( 'Source can not be inside the wppa folder.' , 'wp-photo-album-plus' ) );
					}
					else {
						$dir = $value;
						if ( ! wppa_is_dir( $dir ) ) wppa_mkdir( $dir );
						if ( ! wppa_is_dir( $dir ) || ! is_writable( $dir ) ) {
							wppa( 'error', '1' );
							$alert = sprintf( __( 'Unable to create or write to %s' , 'wp-photo-album-plus' ), $dir );
						}
					}
					break;

				case 'wppa_newpag_content':
					if ( strpos( $value, 'w#album' ) === false ) {
						$alert = __( 'The content must contain w#album' , 'wp-photo-album-plus' );
						wppa( 'error', '1' );
					}
					break;

				case 'wppa_gpx_shortcode':
					if ( strpos( $value, 'w#lat' ) === false || strpos( $value, 'w#lon' ) === false ) {
						$alert = __( 'The content must contain w#lat and w#lon' , 'wp-photo-album-plus' );
						wppa( 'error', '1' );
					}
					break;

				case 'wppa_excl_sep':
				case 'wppa_search_desc':
				case 'wppa_search_tags':
				case 'wppa_search_cats':
				case 'wppa_search_comments':
					wppa_clear_taglist();
					wppa_schedule_maintenance_proc( 'wppa_remake_index_photos', true );
					wppa_schedule_maintenance_proc( 'wppa_remake_index_albums', true );
					break;

				case 'wppa_blacklist_user':
					// Does user exist?
					$value = trim ( $value );
					$user = wppa_get_user_by ( 'login', $value );	// seems to be case insensitive
					if ( $user && $user->user_login === $value ) {
						if ( user_can( $user->ID, 'administrator' ) ) {
							$alert = esc_js( __( 'An administrator can not be blacklisted', 'wp-photo-album-plus' ) );
						}
						else {
							$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_photos SET status = 'pending' WHERE owner = %s", $value ) );
							$black_listed_users = wppa_get_option( 'wppa_black_listed_users', array() );
							if ( ! in_array( $value, $black_listed_users ) ) {
								$black_listed_users[] = $value;
								wppa_update_option( 'wppa_black_listed_users', $black_listed_users );
							}
							$alert = esc_js( sprintf( __( 'User %s has been blacklisted.' , 'wp-photo-album-plus' ), $value ) );
						}
					}
					else {
						$alert = esc_js( sprintf( __( 'User %s does not exist.' , 'wp-photo-album-plus' ), $value ) );
					}
					$value = '';
					break;

				case 'wppa_un_blacklist_user':
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_photos SET status = 'publish' WHERE owner = %s", $value ) );
					$black_listed_users = wppa_get_option( 'wppa_black_listed_users', array() );
					if ( in_array( $value, $black_listed_users ) ) {
						foreach ( array_keys( $black_listed_users ) as $usr ) {
							if ( $black_listed_users[$usr] == $value ) unset ( $black_listed_users[$usr] );
						}
						wppa_update_option( 'wppa_black_listed_users', $black_listed_users );
					}
					$value = '0';
					break;

				case 'wppa_superuser_user':
					// Does user exist?
					$value = trim ( $value );
					$user = wppa_get_user_by ( 'login', $value );	// seems to be case insensitive
					if ( $user && $user->user_login === $value ) {
						if ( user_can( $user->ID, 'administrator' ) ) {
							$alert = esc_js( __( 'An administrator can not be a superuser', 'wp-photo-album-plus' ) );
						}
						else {
							$super_users = wppa_get_option( 'wppa_super_users', array() );
							if ( ! in_array( $value, $super_users ) ) {
								$super_users[] = $value;
								wppa_update_option( 'wppa_super_users', $super_users );
							}
							$alert = esc_js( sprintf( __( 'User %s is now superuser.' , 'wp-photo-album-plus' ), $value ) );
						}
					}
					else {
						$alert = esc_js( sprintf( __( 'User %s does not exist.' , 'wp-photo-album-plus' ), $value ) );
					}
					$value = '';
					break;

				case 'wppa_un_superuser_user':
					$super_users = wppa_get_option( 'wppa_super_users', array() );
					if ( in_array( $value, $super_users ) ) {
						foreach ( array_keys( $super_users ) as $usr ) {
							if ( $super_users[$usr] == $value ) unset ( $super_users[$usr] );
						}
						wppa_update_option( 'wppa_super_users', $super_users );
					}
					$value = '0';
					break;

				case 'wppa_fotomoto_on':
					if ( $value == 'yes' ) {
						$custom_content = wppa_opt( 'custom_content' );
						if ( strpos( $custom_content, 'w#fotomoto' ) === false ) {
							$custom_content = 'w#fotomoto '.$custom_content;
							wppa_update_option( 'wppa_custom_content', $custom_content );
							$alert = __( 'The content of the Custom box has been changed to display the Fotomoto toolbar.' , 'wp-photo-album-plus' ).' ';
						}
						if ( ! wppa_switch( 'custom_on' ) ) {
							wppa_update_option( 'wppa_custom_on', 'yes' );
							$alert .= __( 'The display of the custom box has been enabled' , 'wp-photo-album-plus' );
						}
					}
					break;

				case 'wppa_save_gpx':
					if ( $value == 'yes' ) {
						$custom_content = wppa_opt( 'custom_content' );
						if ( strpos( $custom_content, 'w#location' ) === false ) {
							$custom_content = 'w#location '.$custom_content;
							wppa_update_option( 'wppa_custom_content', $custom_content );
							$alert = __( 'The content of the Slideshow component Custom box has been changed to display the location map.' , 'wp-photo-album-plus' ).' ';
						}
						if ( ! wppa_switch( 'custom_on' ) ) {
							wppa_update_option( 'wppa_custom_on', 'yes' );
							$alert .= __( 'The display of the custom box has been enabled' , 'wp-photo-album-plus' );
						}
					}
					break;

				case 'wppa_gpx_implementation':
					if ( $value != 'none' ) {
						$custom_content = wppa_opt( 'custom_content' );
						if ( strpos( $custom_content, 'w#location' ) === false ) {
							$custom_content = $custom_content.' w#location';
							wppa_update_option( 'wppa_custom_content', $custom_content );
							$alert = __( 'The content of the Slideshow component Custom box has been changed to display maps.' , 'wp-photo-album-plus' ).' ';
						}
						if ( ! wppa_switch( 'custom_on' ) ) {
							wppa_update_option( 'wppa_custom_on', 'yes' );
							$alert .= __( 'The display of the custom box has been enabled.' , 'wp-photo-album-plus' );
						}
					}
					break;

				case 'wppa_regen_thumbs_skip_one':
					$last = wppa_get_option( 'wppa_regen_thumbs_last', '0' );
					$skip = $last + '1';
					wppa_update_option( 'wppa_regen_thumbs_last',  $skip );
					break;

				case 'wppa_remake_skip_one':
					$last = wppa_get_option( 'wppa_remake_last', '0' );
					$skip = $last + '1';
					wppa_update_option( 'wppa_remake_last',  $skip );
					break;

				case 'wppa_create_o1_files_skip_one':
					$last = wppa_get_option( 'wppa_create_o1_files_last', '0' );
					$skip = $last + '1';
					wppa_update_option( 'wppa_create_o1_files_last',  $skip );
					break;

				case 'wppa_optimize_ewww_skip_one':
					$last = wppa_get_option( 'wppa_optimize_ewww_last', '0' );
					$skip = $last + '1';
					wppa_update_option( 'wppa_optimize_ewww_last',  $skip );
					break;

				case 'wppa_errorlog_purge':
					if ( wppa_is_file( $wppa_log_file ) ) {
						wppa_unlink( $wppa_log_file );
					}
					delete_option( 'wppa_recursive_log' );
					delete_option( 'wppa_last_error' );
					break;

				case 'wppa_debuglog_purge':
					$debug_log = WP_CONTENT_DIR . '/debug.log';
					if ( is_writable( $debug_log ) ) {
						wppa_unlink( $debug_log );
					}

				case 'wppa_pl_dirname':
					$value = wppa_sanitize_file_name( $value );
					$value = trim( $value, ' /' );

					// Remove old file if it exists
					$oldfile = WPPA_CONTENT_PATH . '/' . wppa_get_option( 'wppa_pl_dirname' ) . '/.htaccess';
					if ( wppa_is_file( $oldfile ) ) {
						wppa_unlink( $oldfile );
					}

					if ( $value ) {
						wppa_create_pl_htaccess( $value );
					}
					break;

				case 'wppa_new_tag_value':
					$value = wppa_sanitize_tags( $value, false, true );
					break;

				case 'wppa_up_tagselbox_content_1':
				case 'wppa_up_tagselbox_content_2':
				case 'wppa_up_tagselbox_content_3':
				case 'wppa_up_tagbox_new':
					$value = trim( wppa_sanitize_tags( $value ), ',' );
					break;

				case 'wppa_enable_video':
					// if off: set all statusses of videos to pending
					break;

				case 'wppa_twitter_account':
					$value = sanitize_text_field( $value );
					$value = str_replace( ' ', '', $value );
					if ( $value != '' && substr( $value, 0, 1 ) != '@' ) {
						wppa( 'error', '4712' );
						$alert .= __( 'A Twitter account name must start with an at sign: @', 'wp-photo-album-plus' );
					}
					break;

				case 'wppa_rating_display_type':
					if ( $value == 'likes' ) {
						wppa_update_option( 'wppa_rating_multi', 'yes' );
						wppa_update_option( 'wppa_rating_dayly', '0' );
						wppa_update_option( 'wppa_vote_needs_comment', 'no' );
					}
					break;

				case 'wppa_search_numbers_void':
				case 'wppa_index_ignore_slash':
					if ( $value == 'yes' ) {

						// Cleanup index
						wppa_schedule_maintenance_proc( 'wppa_cleanup_index', true );
					}
					else {

						// Remake index
						wppa_schedule_maintenance_proc( 'wppa_remake_index_albums', true );
						wppa_schedule_maintenance_proc( 'wppa_remake_index_photos', true );
					}
					break;
				case 'wppa_search_user_void':
					wppa_schedule_maintenance_proc( 'wppa_remake_index_albums', true );
					wppa_schedule_maintenance_proc( 'wppa_remake_index_photos', true );
					wppa_schedule_maintenance_proc( 'wppa_cleanup_index', true );
					break;
				case 'wppa_image_magick':
					$value = rtrim( $value, '/' );
					if ( $value && $value != 'none' ) {
						$out = array();
						exec( escapeshellcmd( $value . '/convert' ), $out, $err );
						$ok = ( count( $out ) != 0 );
						if ( $ok ) {
							$out = array_reverse( $out );
							array_push( $out, 'Setting magick path returned:' );
							wppa_log( 'dbg', var_export( $out, true ) );
						}
						else {
							wppa( 'error', '4713' );
							$alert .= __( 'This path does not contain ImageMagick commands', 'wp-photo-album-plus' );
						}
					}
					break;
				case 'wppa_grant_cats':
				case 'wppa_grant_tags':
					$value = wppa_sanitize_tags( $value );
					break;
				case 'wppa_maint_ignore_cron':
					if ( $value == 'no' ) {
						wppa_update_option( 'wppa_maint_ignore_cron', 'no' );
						wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
						wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
						wppa_schedule_treecount_update();
					}
					break;
				case 'wppa_minimum_tags':
					$value = trim( wppa_sanitize_tags( $value ), ',' );
					wppa_clear_taglist();
					break;
				case 'wppa_retry_mails':
					$all = array_merge( wppa_get_option( 'wppa_failed_mails', array() ), wppa_get_option( 'wppa_perm_failed_mails', array() ) );
					$value = max( $value, '1' );
					foreach( array_keys( $all ) as $key ) {
						$all[$key]['retry'] = $value;
					}
					wppa_update_option( 'wppa_failed_mails', $all );
					wppa_update_option( 'wppa_perm_failed_mails', array() );
					break;
				case 'wppa_main_photo_reset':
					wppa_clear_col( WPPA_ALBUMS, 'main_photo', '0' );
					$value = 'no';
					$alert = __('All album cover images set to default', 'wp-photo-album-plus' );
					break;
				case 'wppa_nicescroll_opts':
					$value = wppa_sanitize_nso( $value );
					break;
				case 'wppa_admin_extra_css':
					if ( $value != sanitize_url( $value, is_ssl() ? ['https'] : ['http'] ) ) {
						$value = '';
						$alert = __('Not a valid url', 'wp-photo-album-plus');
					}
					break;

				default:

					wppa( 'error', '0' );
					$alert = '';
			}

			if ( wppa( 'error' ) ) {
				if ( ! $title ) $title = sprintf( __( 'Failed to set %s to %s', 'wp-photo-album-plus' ), $option, $value );
				if ( ! $alert ) $alert .= wppa( 'out' );
			}

			// Do not re-init dynamic files on heartbeat: no wppa_update_option() call
			elseif ( $option == 'wppa_heartbeat' ) {
				wppa_update_option( $option, $value );
			}
			else {
				wppa_update_option( $option, $value );
				if ( ! $title ) $title = sprintf( __( 'Setting %s updated to %s', 'wp-photo-album-plus' ), $option, $value );
			}

			// Save possible error
			$error = wppa( 'error' );

			// Something to do after changing the setting?
			wppa_initialize_runtime( true );	// force reload new values

			if ( $option == 'wppa_cre_uploads_htaccess' ) {
				wppa_create_wppa_htaccess();
			}

			// Thumbsize
			$new_minisize = wppa_get_minisize();
			if ( $old_minisize != $new_minisize ) {
				wppa_update_option ( 'wppa_regen_thumbs_status', 'Required' );
				$alert .= __( 'You just changed a setting that requires the regeneration of thumbnails.' , 'wp-photo-album-plus' );
				$alert .= ' '.__( 'Please run the appropriate maintenance procedure.' , 'wp-photo-album-plus' );
			}

			// Compose the cron job status and togo fields
			$crondata = '';
			global $wppa_cron_maintenance_slugs;
			foreach ( $wppa_cron_maintenance_slugs as $slug ) {
				$crondata .= $slug . '_status:' . wppa_get_option( $slug . '_status' ) . ';';
				$crondata .= $slug . '_togo:' . wppa_get_option( $slug . '_togo' ) . ';';
			}
			$crondata = rtrim ( $crondata, ';' );

			// Produce the response text
			$output = '||'.$error.'||'.esc_attr( $title ).'||'.esc_js( $alert ).'||'.$crondata;

			wppa_echo( $output );
			if ( $option != 'wppa_heartbeat' &&
				$option != 'wppa_errorlog_purge' &&
				substr( $option, 0, 9 ) != 'wppa_log_' ) {
					wppa_clear_cache( ['force' => true] );
			}
			wppa_exit();
			break;	// End update-option

		case 'maintenance':

			// Get args
			$slug 	= wppa_get( 'slug' );
			$nonce  = wppa_get( 'nonce' );
			$cron 	= wppa_get( 'cron' );

			// Security check
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce' ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( 'Security check failure||'.$slug.'||Error||0' );
				wppa_exit();
			}

			// If cron request, schedule
			if ( $cron ) {
				wppa_schedule_maintenance_proc( $slug, true );

				// Remove in case this is a re-start of a crashed cron job
				delete_option( $slug . '_lasttimestamp' );
			}

			// Not a cron job, run realtime
			else {
				wppa_echo( wppa_do_maintenance_proc( $slug ) );
			}

			wppa_exit();
			break;

		case 'maintenancepopup':
			$slug 	= wppa_get( 'slug' );
			$nonce  = wppa_get( 'nonce' );
			if ( ! wp_verify_nonce( $nonce, 'wppa-nonce' ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( 'Security check failure||'.$slug.'||Error||0' );
				wppa_exit();
			}
			wppa_echo( wppa_do_maintenance_popup( $slug ) );
			wppa_exit();
			break;

		case 'do-fe-upload':
			require_once 'wppa-non-admin.php';

			wppa_user_upload();

			global $wppa_upload_succes_id;
			if ( ( wppa_get( 'fromtinymce' ) || wppa_get( 'fromgutenberg' ) ) && $wppa_upload_succes_id ) {
				wppa_echo( '||' . $wppa_upload_succes_id . '||' );
				wppa_echo( wppa_get_myphotos_selection_body_for_tinymce( $wppa_upload_succes_id ) );
			}
			wppa_exit();
			break;

		case 'do-import-upload':
			require_once 'wppa-import.php';
			wppa_do_import_upload();
			wppa_exit();
			break;

		case 'sanitizetags':
			$tags 		= wppa_get( 'tags' );
			$album 		= wppa_get( 'album' );
			$deftags 	= ( wppa_is_int( $album ) && $album > '0' ) ? wppa_get_album_item( $album, 'default_tags' ) : '';
			$tags 		= $deftags ? $tags . ',' . $deftags : $tags;
			wppa_echo( wppa_sanitize_tags( $tags, false, true ) );
			wppa_exit();
			break;

		case 'destroyalbum':
			$album = wppa_get( 'album' );
			if ( ! $album ) {
				_e('Missing album id', 'wp-photo-album-plus' );
				wppa_exit();
			}
			$nonce = wppa_get( 'nonce' );
			if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wppa-nonce_'.$album ) ) {
				wppa_log( 'Misc', 'nonce failed on ' . $wppa_action );
				wppa_echo( 'Security check failure #798' );
				wppa_exit();
			}

			// May I?
			$imay = true;
			if ( ! wppa_switch( 'user_destroy_on' ) ) $may = false;
			if ( ! is_user_logged_in() ) $may = false;					// Must login
			if ( ! wppa_have_access( $album ) ) {
				$may = false;						// No album access
			}
			if ( wppa_is_user_blacklisted() ) $may = false;
			if ( ! $imay ) {
				_e('You do not have the rights to delete this album', 'wp-photo-album-plus' );
				wppa_exit();
			}

			// I may
			require_once 'wppa-album-admin-autosave.php';
			wppa_del_album( $album );
			wppa_exit();
			break;

		case 'export-table':
			if ( ! wppa_user_is_admin() ) {
				wppa_echo( '||1||'.__( 'Security check failure' , 'wp-photo-album-plus' ) );
				wppa_exit();
			}
			$table = wppa_get( 'table' );
			$bret = wppa_export_table( $table );
			if ( $bret ) {
				wppa_echo( '||0||' . WPPA_UPLOAD_URL . '/temp/' . $table . '.csv' );
			}
			else {
				wppa_echo( '||2||' . __( 'An error has occurred', 'wp-photo-album-plus' ) );
			}
			wppa_exit();
			break;

		case 'updatepotddata':

			require_once 'wppa-setting-functions.php';
			// Normalize offset and seqno by calling wppa_get_potd()
			// Resuts are: ['id' => $id, 'potddata' => $photo_data, 'seqno' => $seqno, 'offset' => $offset];
			$potd_a 	= wppa_get_potd( true );
			$seqno 		= $potd_a['seqno'];
			$offset 	= $potd_a['offset'];
			$photo 		= $potd_a['potddata'];
			$preview 	= wppa_get_potd_preview_html( $photo );
			$pool 		= wppa_get_potd_pool_html();

			$result = wp_json_encode( ['offset' => $offset, 'seqno' => $seqno, 'preview' => $preview, 'pool' => $pool] );
			echo $result;
			break;

		case 'updatewatermarkpreview':

			$tr = floor( 127 * ( 100 - wppa_opt( 'watermark_opacity_text' ) ) / 100 );
			$args = array( 'id' => '0', 'url' => true, 'width' => '1000', 'height' => '400', 'transp' => $tr );
			$url = wppa_create_textual_watermark_file( $args ).'?ver='.rand(0, 4711);

			wppa_create_all_textual_watermark_files();

			$result = wp_json_encode( ['url' => $url] );
			wppa_log('misc', 'echoing:'.$result);
			echo $result;
			break;

		default:	// Unimplemented $wppa-action
		die( '-1' );
	}
	wppa_exit();
}

function wppa_decode( $string ) {

	$result = str_replace( ['%23', '%26', '%2B'], ['#', '&', '+'], $string );
	return $result;
}

function wppa_ajax_check_range( $value, $fixed, $low, $high, $title ) {

	if ( $fixed !== false && $fixed == $value ) return;						// User enetred special value correctly
	if ( !is_numeric( $value ) ) wppa( 'error', true );						// Must be numeric if not specaial value
	if ( $low !== false && $value < $low ) wppa( 'error', true );			// Must be >= given min value
	if ( $high !== false && $value > $high ) wppa( 'error' , true );		// Must be <= given max value

	if ( ! wppa( 'error' ) ) return;		// Still no error, ok

	// Compose error message
	if ( $low !== false && $high === false ) {	// Only Minimum given
		wppa_out( __( 'Please supply a numeric value greater than or equal to' , 'wp-photo-album-plus' ) . ' ' . $low . ' ' . __( 'for' , 'wp-photo-album-plus' ) . ' ' . $title );
		if ( $fixed !== false ) {
			if ( $fixed ) wppa_out( '. ' . __( 'You may also enter:' , 'wp-photo-album-plus' ) . ' ' . $fixed );
			else wppa_out( '. ' . __( 'You may also leave/set this blank' , 'wp-photo-album-plus' ) );
		}
	}
	else {	// Also Maximum given
		wppa_out( __( 'Please supply a numeric value greater than or equal to' , 'wp-photo-album-plus' ) . ' ' . $low . ' ' . __( 'and less than or equal to' , 'wp-photo-album-plus' ) . ' ' . $high . ' ' . __( 'for' , 'wp-photo-album-plus' ) . ' ' . $title );
		if ( $fixed !== false ) {
			if ( $fixed ) wppa_out( '. ' . __( 'You may also enter:' , 'wp-photo-album-plus' ) . ' ' . $fixed );
			else wppa_out( '. ' . __( 'You may also leave/set this blank' , 'wp-photo-album-plus' ) );
		}
	}
}

// Print security check failure message and exit
function wppa_secfail( $id ) {

	$text = sprintf( __( 'Security check failure %d', 'wp-photo-album-plus' ), $id );
	wppa_log( 'Misc', $text );
	wppa_echo ( $text );
	wppa_exit();
}

// Get the JSON formatted photo update data
function wppa_json_photo_update( $id, $txt, $err = '0', $flags = array() ) {

	$defaults = array( 'thumbmod' 	=> false,
					   'photomod' 	=> false,
					   'magickmod' 	=> false,
					   'namemod' 	=> false,
					   'descmod' 	=> false,
					   'tagsmod' 	=> false, );

	$mods = $defaults;
	foreach( array_keys( $flags ) as $key ) {
		$mods[$key] = true;
	}

	// Re-compute photo and thumbnail pixel sizes
	$tx = wppa_get_thumbx( $id, true );
	$ty = wppa_get_thumby( $id, true );
	$px = wppa_get_photox( $id, true );
	$py = wppa_get_photoy( $id, true );
	wppa_cache_photo( 'invalidate', $id );
	$t = wppa_cache_photo( $id );

	// Just to be sure increment version numbers
	if ( $mods['thumbmod'] || $mods['magickmod'] ) {
		wppa_bump_thumb_rev();
	}
	if ( $mods['photomod'] || $mods['magickmod'] ) {
		wppa_bump_photo_rev();
	}

	// Find and format filesizes
	$tf = wppa_fix_poster_ext( wppa_get_thumb_path( $id ), $id );
	if ( wppa_is_file( $tf ) ) {
		$tfs = wppa_get_filesize( $tf );
	}
	else {
		$tfs = __( 'Unavailable', 'wp-photo-album-plus' );
	}

	$pf = wppa_fix_poster_ext( wppa_get_photo_path( $id ), $id );
	if ( wppa_is_file( $pf ) ) {
		$pfs = wppa_get_filesize( $pf );
	}
	else {
		$pfs = __( 'Unavailable', 'wp-photo-album-plus' );
	}

	// Update CDN
	if ( $mods['thumbmod'] || $mods['photomod'] || $mods['magickmod'] ) {
		$cdn = wppa_cdn( 'admin' );
		if ( $cdn ) {
			switch ( $cdn ) {
				case 'cloudinary':
					wppa_upload_to_cloudinary( $id );
					break;
				case 'local':
					wppa_cdn_delete( $id );
					break;
				default:
			}
		}
	}

	// Clear cache
	wppa_clear_cache( ['photo' => $id] );

	// Build JSON data
	$data = array();
	$data['remark'] = htmlentities( str_replace( '"', "'", $txt ) );
	$data['modified'] = wppa_local_date( '', $t['modified'] );

	if ( $mods['thumbmod'] || $mods['magickmod'] ) {
		$data['thumbx'] = $tx;
		$data['thumby'] = $ty;
		$data['thumbfilesize'] = $tfs;
		$data['thumburl'] = wppa_get_thumb_url( $id );
	}

	if ( $mods['photomod'] || $mods['magickmod'] ) {
		$data['photox'] = $px;
		$data['photoy'] = $py;
		$data['photofilesize'] = $pfs;
		$data['photourl'] = wppa_get_photo_url( $id );
		$data['magickstack'] = $t['magickstack'];
	}

	if ( $mods['thumbmod'] || $mods['photomod'] || $mods['magickmod'] ) {
		$data['cdnfiles'] = __( 'none', 'wp-photo-album-plus' );
	}

	// The next items are for fe update photo new style
	if ( $mods['namemod'] ) {
		$t 					= wppa_get_slide_name_a( $id );
		$data['name'] 		= $t['name'];
		$data['fullname'] 	= $t['fullname'];
	}

	if ( $mods['descmod'] ) {
		$data['desc'] = str_replace( '\n', '', wppa_get_slide_desc( $id ) );
	}

	if ( $mods['tagsmod'] ) {
		$data['tags'] = trim( wppa_get_photo_item( $id, 'tags' ), ',' );
	}

	$result = '||' . $err . '||' . wp_json_encode( $data );

	wppa_log( 'dbg', $result );

	echo( $result );
	wppa_exit();
}

// Convert html with script tags to json format like {html:html-text,js:jstext-without-script-tags}
function wppa_split_html_js( $text ) {

	$text = preg_replace( '/<script[^>]*?>/i', '<script>', $text );
	$workarr = explode( '<script>', $text );

	// Any js present?
	if ( count( $workarr ) == 1 ) {

		// No
		$html = $text;
		$js = '';
	}

	else {

		// Yes
		$html = '';
		$js = '';
		foreach( $workarr as $chunk ) {
			$chunk_parts = explode( '</script>', $chunk );

			// No </script> found? html only
			if ( count( $chunk_parts ) == 1 ) {
				$html .= $chunk;
			}

			// Split html from js; ignore script tags
			else {
				$js_part = rtrim( $chunk_parts[0], ';' ) . ';';
				$js .= $js_part;
				$html_part = $chunk_parts[1];
				$html .= $html_part;
			}
		}
	}

	$js = str_replace( "\n", "", $js );

	return array( 'html' => $html, 'js' => $js );
}

function wppa_email_quit_message( $msg, $color = 'blue' ) {
	$result = '
			<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml" >
					<body>
						<div style="width:100%;height:100%;text-align:center;margin-top:100px;">
							<h2 style="border:2px solid '.$color.';background-color:light'.$color.';padding:12px;width:fit-content;margin:auto;">' .
								$msg .
							'</h2>
						</div>
					</body>
				</html>';

	wppa_echo( $result );
	wppa_exit();
}
