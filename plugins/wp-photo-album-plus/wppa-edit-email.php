<?php
/* wppa-edit-email.php
* Package: wp-photo-album-plus
*
* manage all comments
* Version: 8.4.01.003
*
*/

// The command admin page
function _wppa_edit_email( $page_1 = false ) {
global $wpdb;

	// Init
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
	$url = get_admin_url().'admin.php?page=wppa_edit_email';

	// Get paging parameters
	$parms 		= wppa_get_paging_parms( 'edit_email', $page_1 );
	if ( $parms['order'] == 'id' ) $parms['order'] = 'ID';

	// Icon to use
	$downimg 	= '<img src="'.wppa_get_imgdir().'Down-2.svg" alt="down" style="height:1em;" /><br>';
	$upimg   	= '<img src="'.wppa_get_imgdir().'Up-2.svg" alt="up" style="height:1em" /><br>';
	$useimg 	= $parms['dir'] == 'desc' ? $upimg : $downimg;

	// Get users
	$total 		= wppa_get_count( $wpdb->users );
	$users 		= get_users( ['ID','user_login','display_name',
								'orderby'=>$parms['order'],
								'order'=>$parms['dir'],
								'number'=>$parms['pagesize'],
								'paged'=>$parms['page']] );

	$skips = ( $parms['page'] - 1 ) * $parms['pagesize'];
	if ( $skips >= $total ) {
		_wppa_edit_email( true );
		return;
	}

	// Get mailinglist user ids
	foreach( array_keys( $email_types ) as $type ) {
		$mailinglist 		= wppa_get_option( 'wppa_mailinglist_' . $type, '' );
		$userarray[$type] 	= wppa_index_string_to_array( $mailinglist );
	}

	// Inline js
	$the_js = '
	function wppaTryClearMailingList(list) {
		var query = "' . esc_js( __( 'Are you sure you want to clear this mailinglist?', 'wp-photo-album-plus' ) ) . '";
		if ( confirm( query ) ) {
			jQuery.ajax( {
				url: 		wppaAjaxUrl,
				data: 		"action=wppa&wppa-action=update-option&wppa-option=clear-"+list+"&wppa-nonce="+document.getElementById(\'wppa-nonce\').value,
				async: 		true,
				type: 		"POST",
				timeout: 	10000,
				beforeSend:	function( xhr, settings ) {
								jQuery( "#wppa-admin-spinner" ).show();
							},
				success: 	function( result, status, xhr ) {
								wppaConsoleLog(result,"force");
								jQuery( "#wppa-admin-spinner" ).hide();
								document.location.reload(true);
							},
				error: 		function( xhr, status, error ) {
							},
				complete: 	function() {

							},
			});
		}
	}';
	wppa_add_inline_script( 'wppa-admin', $the_js, true );

	// Open page
	wppa_echo( '
	<div class="wrap">' );

		wppa_admin_spinner();

		// The nonce field
		wp_nonce_field( 'wppa-nonce', 'wppa-nonce' );
		wp_nonce_field( 'wppa-ntfy-nonce', 'wppa-ntfy-nonce' );

		// General header
		wppa_echo( '
		<h1 style="display:inline;">' .
			get_admin_page_title() .
		'</h1>' );
		if ( ! wppa_switch( 'email_on' ) ) {
			wppa_echo( '
				<span style="color:red;display:inline">' .
					esc_html__( 'Email is not enabled', 'wp-photo-album-plus' ) .
					( current_user_can( 'wppa_settings' ) ? wppa_see_also( 'general', '1', '7' ) : '' ) . '
				</span>' );
		}

		wppa_admin_pagination( $parms['pagesize'], $parms['page'], $total, $url, 'top' );

		wppa_echo( '
		<table
			class="wppa-table widefat wppa-setting-table striped"
			style="margin-top:12px;"
			>
			<colgroup>
				<col style="width:70px">
				<col style="width:132px">
				<col style="width:200px">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
				<col style="">
			</colgroup>
			<thead>
				<tr>
					<td
						style="cursor:pointer"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'edit_email', 'ID' ) . '\'">' .
						( $parms['order'] == 'ID' ? $useimg : '<br>' ) . esc_html__( 'Id', 'wp-photo-album-plus' ) . '
					</td>
					<td
						style="cursor:pointer"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'edit_email', 'user_login' ) . '\'">' .
						( $parms['order'] == 'user_login' ? $useimg : '<br>' ) . esc_html__( 'User login', 'wp-photo-album-plus' ) . '
					</td>
					<td
						style="cursor:pointer"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'edit_email', 'display_name' ) . '\'">' .
						( $parms['order'] == 'display_name' ? $useimg : '<br>' ) . esc_html__( 'User display name', 'wp-photo-album-plus' ) . '
					</td>' );
					foreach( array_keys( $email_types ) as $type ) {
						wppa_echo( '
						<td>
							<label for="' . $type . '"> ' .
								esc_html( $email_types[$type] ) . '
							</label>
							<br>' .
							wppa_checkbox( 'wppa-' . $type, 'wppaRefreshAfter();' ) . ' ' );

							// List enabled?
							if ( wppa_switch( $type ) ) {
								$c = count( $userarray[$type] );
								wppa_echo( sprintf( _n( '%d user', '%d users', $c, 'wp-photo-album-plus' ), $c ) );
							}
							else {
								wppa_echo( __( 'off', 'wp-photo-album-plus' ) . '<br style="clear:left">' );
								if ( get_option( 'wppa_mailinglist_' . $type, '' ) ) {
									wppa_echo( '
									<a
										onclick="wppaTryClearMailingList(\''.$type.'\');return false;"
										>' .
										__( 'Clear list', 'wp-photo-album-plus' ) . '
									</a>' );
								}
								else {
									wppa_echo( 'Cleared', 'wp-photo-album-plus' );
								}
							}
						wppa_echo( '
						</td>' );
					}
				wppa_echo( '
				</tr>
			</thead>
			<tbody>' );
				foreach( $users as $user ) {
					wppa_echo( '
					<tr>
						<td>' . $user -> ID . '</td>
						<td>' . $user -> user_login . '</td>
						<td>' . $user -> display_name . '</td>' );
						foreach( array_keys( $email_types ) as $type ) {
							$applicable = true;
							if ( substr( $type, 0, 8 ) == 'moderate' && ! user_can( $user, 'wppa_moderate' ) ) {
								$applicable = false;
							}
							if ( $type == 'subscribenotify' && ! wppa_user_is( 'administrator', $user -> ID ) ) {
								$applicable = false;
							}

							if ( $applicable ) {
								if ( wppa_switch( $type ) ) {
									wppa_echo( '
									<td>
										<input
											type="checkbox"
											id=""
											style="float:left"
											onchange="wppaAjaxNotify(this,\''.$type.'\','.$user -> ID.');"' .
											( in_array( $user -> ID, $userarray[$type] ) ? ' checked' : '' ) . '
										>
										<img
											id="img_'.$type.'-'.$user -> ID.'"
											src="'.wppa_get_imgdir().'star.ico"
											title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'"
											style="padding-left:4px;float:left;height:16px;width:16px"
										>
									</td>' );
								}
								else {
									wppa_echo( '
									<td
										style="background-color:lightgray;opacity:0.5;"
										>
										<input
											type="checkbox"
											id=""' .
											( in_array( $user -> ID, $userarray[$type] ) ? ' checked' : '' ) . '
											disabled
										>
									</td>' );
								}
							}
							else {
								wppa_echo( '
								<td
									style="background-color:lightgray;opacity:0.5;"
									>' .
									_x( 'n.a.', 'not applicable', 'wp-photo-album-plus' ) . '
								</td>' );
							}
						}
						wppa_echo( '
					</tr>' );
				}
				wppa_echo( '
			</tbody>
		</table>' );

		wppa_admin_pagination( $parms['pagesize'], $parms['page'], $total, $url );

		wppa_echo( '
	</div>' );
}

