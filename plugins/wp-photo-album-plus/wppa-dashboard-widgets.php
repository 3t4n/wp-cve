<?php
/* wppa-dashboard-widgets.php
* Package: wp-photo-album-plus
*
* Contains dashboard widgets code
*
* Version 8.3.05.002
*
*/

// Email subscription dashboard widget
add_action( 'do_meta_boxes', 'wppa_email_subscription' );

function wppa_email_subscription() {

	if ( ! wppa_switch( 'email_on' ) ) {
		return;
	}

	$doit =
	wppa_switch( 'newalbumnotify' ) ||
	wppa_switch( 'feuploadnotify' ) ||
	( wppa_switch( 'commentnotify' ) && wppa_switch( 'show_comments' ) ) ||
	( wppa_switch( 'commentprevious' ) && wppa_switch( 'show_comments' ) )||
	( wppa_switch( 'moderatephoto' ) && current_user_can( 'wppa_moderate' ) ) ||
	( wppa_switch( 'moderatecomment' ) && wppa_switch( 'show_comments' ) && current_user_can( 'wppa_moderate' ) ) ||
	( wppa_switch( 'photoapproved' ) && wppa_switch( 'upload_moderate' ) ) ||
	( wppa_switch( 'commentapproved' ) && wppa_switch( 'show_comments' ) );

	if ( $doit && function_exists( 'wp_add_dashboard_widget' ) ) {
		wp_add_dashboard_widget( 'wppa-email-subscription', __( 'Notify me', 'wp-photo-album-plus' ), 'wppa_show_email_subscription' ); //, $control_callback = null, $callback_args = null )
	}
}

function wppa_show_email_subscription() {

	// Get the body of the widget
	$body = wppa_get_email_subscription_body();

	// Nothing to show?
	if ( ! $body ) {
		return;
	}

	wppa_echo(
	__( 'Notify me when...', 'wp-photo-album-plus' ) . '
	<br>
	' . $body . '
	<input type="hidden" id="wppa-ntfy-nonce" value="' . wp_create_nonce( 'wppa-ntfy-nonce' ) . '" />
	<div style="clear:both"></div>' );
}

// Activity feed
add_action( 'do_meta_boxes', 'wppa_activity' );

function wppa_activity(){

	// Are we configured to show the activity widgets?
	switch ( wppa_opt( 'show_dashboard_widgets' ) ) {
		case 'all': $doit = true; break;
		case 'admin': $doit = current_user_can( 'administrator' ); break;
		default: $doit = false;
	}

	if ( $doit && function_exists( 'wp_add_dashboard_widget' ) ) {
		wp_add_dashboard_widget( 'wppa-activity', __( 'Recent WPPA activity', 'wp-photo-album-plus' ), 'wppa_show_activity_feed' ); //, $control_callback = null, $callback_args = null )
	}
}

function wppa_show_activity_feed() {
global $wpdb;

	// Recently uploaded photos
	wppa_echo( '<h3>' . __( 'Recently uploaded photos', 'wp-photo-album-plus' ) . '</h3>' );

	$photos = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
								   ORDER BY timestamp DESC LIMIT 5", ARRAY_A );

	if ( ! empty( $photos ) ) {
		wppa_echo( '<table>' );
		foreach( $photos as $photo ) {

			$id = $photo['id'];
			if ( wppa_is_photo_visible( $id ) ) {
				if ( wppa_user_is_admin() ) {
					$href = get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=single&photo=' . $photo['id'] . '&nonce=' . wp_create_nonce( 'wppa-nonce' );
				}
				else {
					$href = wppa_get_photo_url( $id );
				}
				wppa_echo( '
				<tr>
					<td>
						<a href="' . esc_url( $href ) . '" target="_blank" >' );

							if ( wppa_is_video( $id ) ) {
								$url = WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'video_icon' );
								wppa_echo( '
								<div style="position:relative">' .
									wppa_get_video_html( array( 'id'			=> $id,
																	 'width'		=> '100',
																	 'controls' 	=> false,
																	 'preload' 		=> 'metadata',
																	 'use_thumb' 	=> true,
																	 'autoplay' 	=> false
																	 ) ) . '
									<img src="' . esc_url( $url ) . '" style="width:16px;height:16px;position:absolute;right:0;bottom:0;z-index:100;" />
								</div>' );
							}
							else {
								wppa_echo( '
								<img src="' . esc_url( wppa_get_thumb_url( $id ) ) . '" style="max-width:100px;" /> ' );
							}

						wppa_echo( '
						</a>
					</td>
					<td>' );
						$usr = wppa_get_user_by( 'login', $photo['owner'] );
						if ( $usr ) {
							$usr = $usr -> display_name;
						}
						else {
							$usr = sanitize_user( $photo['owner'] );
						}
						wppa_echo(
						sprintf( 	__( 'by %s in album %s', 'wp-photo-album-plus' ),
									'<b>' . $usr . '</b>',
									'<b>' . sanitize_text_field( wppa_get_album_name( $photo['album'] ) ) . '</b> (' . strval( intval( $photo['album'] ) ) . ')'
									) . '
						<br>' .
						wppa_local_date( '', $photo['timestamp'] ) . '
					</td>
				</tr>' );
			}
		}
		wppa_echo( '</table>' );
	}
	else {
		wppa_echo(
		'<p>' .
			__( 'There are no recently uploaded photos', 'wp-photo-album-plus' ) .
		'</p>' );
	}
	wppa_echo( '<br>' );

	// Recent comments
	wppa_echo( '<h3>' . __( 'Recent comments on photos', 'wp-photo-album-plus' ) . '</h3>' );
	$comments = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_comments ORDER BY timestamp DESC LIMIT 5", ARRAY_A );
	if ( ! empty( $comments ) ) {

		wppa_echo( '<table>' );
		foreach( $comments as $comment ) {
			$photo = wppa_cache_photo( $comment['photo'] );
			if ( $photo ) {
				if ( wppa_user_is_admin() ) {
					$href = get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=single&photo=' . $photo['id'] . '&nonce=' . wp_create_nonce( 'wppa-nonce' );
				}
				else {
					$href = wppa_get_photo_url( $photo['id'] );
				}
				wppa_echo( '
				<tr>
					<td>
						<a href="' . esc_url( $href ) . '" target="_blank" >
							<img src="' . esc_url( wppa_get_thumb_url( $photo['id'] ) ) . '" style="max-width:100px;" />
						</a>
					</td>
					<td>
						<i>' . sanitize_text_field( $comment['comment'] ) . '</i>
						<br>' .
						sprintf(	__( 'by %s', 'wp-photo-album-plus' ),
									'<b>' . htmlspecialchars( $comment['user'] ) . '</b>' ) . '
						<br>' .
						wppa_local_date( '', $comment['timestamp'] ) . '
					</td>
				</tr>' );
			}
		}
		wppa_echo( '</table>' );
	}
	else {
		wppa_echo(
		'<p>' .
			__( 'There are no recent comments on photos', 'wp-photo-album-plus' ) .
		'</p>' );
	}

}

// Photo of the day history. This is undocumented and no setting for available.
if ( wppa_get_option( 'wppa_potd_log', 'no' ) == 'yes' ) {
	add_action( 'do_meta_boxes', 'wppa_potdlog' );
}

function wppa_potdlog() {

	// Are we configured to show the activity widgets?
	switch ( wppa_opt( 'show_dashboard_widgets' ) ) {
		case 'all': $doit = true; break;
		case 'admin': $doit = current_user_can( 'administrator' ); break;
		default: $doit = false;
	}

	if ( $doit && function_exists( 'wp_add_dashboard_widget' ) ) {
		wp_add_dashboard_widget( 'wppa-potdlog', __( 'Photo of the day history', 'wp-photo-album-plus' ), 'wppa_show_potd_log' );
	}
}

function wppa_show_potd_log() {

	// Get data
	$his = wppa_get_option( 'wppa_potd_log_data', array() );
	if ( ! empty( $his ) ) {
		wppa_echo( '<table>' );
		foreach( $his as $item ) {
			if ( wppa_photo_exists( $item['id'] ) ) {
				$photo = wppa_cache_photo( $item['id'] );
				$time  = $item['tm'];
				if ( wppa_user_is_admin() ) {
					$href = get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=single&photo=' . $photo['id'] . '&nonce=' . wp_create_nonce( 'wppa-nonce' );
				}
				else {
					$href = wppa_get_photo_url( $photo['id'] );
				}
				wppa_echo( '
				<tr style="border-bottom:1px solid #444">
					<td>
						<a href="' . esc_url( $href ) . '" target="_blank" >
							<img src="' . esc_url( wppa_get_thumb_url( $photo['id'] ) ) . '" style="max-width:100px;" />
						</a>
					</td>
					<td>' .
						__( 'First displayed at', 'wp-photo-album-plus' ) . ': ' . wppa_local_date( '', $time ) . '<br>' .
						__( 'Name', 'wp-photo-album-plus' ) . ': ' . wppa_get_photo_name( $photo['id'] ) . '<br>' .
						__( 'Description', 'wp-photo-album-plus' ) . ':<br>' .
						htmlspecialchars( strip_tags( wppa_get_photo_desc( $photo['id'] ) ) ) . '
					</td>
				</tr>' );
			}
			else {
				wppa_echo( '
				<tr style="border-bottom:1px solid #444">
					<td>' .
						sprintf( __( 'Photo %d has been removed' ), $item['id'] ) . '
					</td>
					<td>' .
						__( 'First displayed at', 'wp-photo-album-plus' ) . ': ' . wppa_local_date( '', $item['tm'] ) . '<br>
					</td>
				</tr>' );
			}
		}
		wppa_echo( '</table>' );
	}
	else {
		wppa_echo(
		'<p>' .
			__( 'There is no photo of the day history', 'wp-photo-album-plus' ) .
		'</p>' );
	}
}