<?php
/* wppa-mailing.php
* Package: wp-photo-album-plus
*
* Contains mailing functions
*
* Version 8.6.04.002
*
*/

/* The following mailing lists exist:
'newalbumnotify',
'feuploadnotify',
'commentnotify',
'commentprevious',
'moderatephoto',
'moderatecomment',
'photoapproved',
'commentapproved',
'subscribenotify',
*/

add_action( 'wppa_do_mailinglist_cron', 'wppa_do_mailinglist', 10, 6 );

// Call this function to schedule a mailinglist emission
function wppa_schedule_mailinglist( $type, $alb = 0, $pho = 0, $com = 0, $url = '', $start = 0, $delay = 120 ) {

	if ( ! wppa_switch( 'email_on' ) ) return;

	$log_args = " Args: $alb, $pho, $com, $url, $start.";

	// If user is an admin and void_admin is active, do nothing
	if ( wppa_user_is_admin() && wppa_switch( 'void_admin_email' ) ) {
		if ( in_array( $type, array( 'newalbumnotify', 'feuploadnotify', 'commentnotify' ) ) ) {
			wppa_log( 'Eml', "Admin email $type skipped." );
			return;
		}
	}

	// If feuploadnotify, see if one is pending, if so, do nothing
	if ( $type == 'feuploadnotify' ) {

		if ( ! $alb ) {
			$alb = wppa_get_photo_item( $pho, 'album' );
		}
		$owner = wppa_get_photo_item( $pho, 'owner' );

		$pending = get_transient( 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb );
		wppa_log( 'eml', 'Reading transient ' . 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb . ' Value = ' . var_export( $pending, true ) );

		if ( ! $pending ) {

			// Save this one to signal next that this one is pending
			set_transient( 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb, $pho, HOUR_IN_SECONDS );
			wppa_log( 'eml', 'Writing transient ' . 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb . ' Value = ' . $pho );
		}
		else {
			$pho = $pending;
			$log_args = " Args: $alb, $pho, $com, $url, $start.";
		}
	}

	// wp_schedule_single_event( int $timestamp, string $hook, array $args = array(), bool $wp_error = false )
	// wp_next_scheduled( string $hook, array $args = array() )

	// Already scheduled?
	$tm = wp_next_scheduled( 'wppa_do_mailinglist_cron', array( $type, $alb, $pho, $com, $url, $start ) );
	if ( $tm ) {
		wppa_log( 'Eml', 'Mailinglist {b}' . $type . '{/b} already scheduled to run at ' . wppa_local_date( '', $tm ) . $log_args );
		return;
	}

	// No, schedule
	else {
		$tm = wp_schedule_single_event( time() + $delay, 'wppa_do_mailinglist_cron', array( $type, $alb, $pho, $com, $url, $start ) );
	}

	// Success?
	if ( $tm === true ) {
		wppa_log( 'Eml', 'Mailinglist {b}' . $type . '{/b} ' . ( $start ? 're-' : '' ) . 'scheduled for run in ' . $delay . ' seconds.' . $log_args );
	}
	else {
		wppa_log( 'Err', 'Mailinglist {b}' . $type . '{/b} could not be scheduled for run in ' . $delay . ' seconds.' . $log_args );
	}

}

// Send the mails for a mailinglist
function wppa_do_mailinglist( $type, $alb = 0, $pho = 0, $com = 0, $url = '', $start = 0 ) {
global $wpdb;

	$log_args = " Args: $alb, $pho, $com, $url, $start.";

	// Add new users to default mailing list subscriptions
	if ( wppa_opt( 'mailinglist_policy' ) == 'opt-out' ) {

		$from 	= wppa_get_option( 'wppa_mailinglist_highest_user_auto_subscribed', 0 );
		$to 	= $wpdb->get_var( "SELECT ID from $wpdb->users ORDER BY ID DESC LIMIT 1" );

		if ( $to > $from ) {

			wppa_log( 'Eml', 'Start adding users to mailinlists' );
			$i = $from + 1;
			$mailings = array( 	'newalbumnotify',
								'feuploadnotify',
								'commentnotify',
								'commentprevious',
								'moderatephoto',
								'moderatecomment',
								'photoapproved',
								'commentapproved',
								'subscribenotify',
								);

			while ( ! wppa_is_time_up() && $i <= $to ) {
				foreach( $mailings as $list ) {
					if ( $list == 'subscribenotify' && ! wppa_user_is( 'administrator' ) ) {
					}
					elseif ( substr( $list, 0, 8 ) == 'moderate' && ! user_can( $i, 'wppa_moderate' ) ) {
					}
					else {
						wppa_subscribe_user( $i, $list );
					}
				}
				$i++;
			}
			wppa_log( 'Eml', $to - $from . ' users added to mailinglists' );
			wppa_update_option( 'wppa_mailinglist_highest_user_auto_subscribed', $to );

			// Redo the mailing
			wppa_schedule_mailinglist( $type, $alb, $pho, $com, $url, $start, 15 );
			wppa_exit();
		}
	}

	// Mailinglist enabled?
	if ( ! wppa_switch( $type ) ) {
		wppa_log( 'Eml', 'Mailinglist {b}' . $type . '{/b} is disabled and will not run' );
		wppa_exit();
	}

	// Get mailinglist user ids
	$mailinglist 	= wppa_get_option( 'wppa_mailinglist_' . $type, '' );
	$userarray 		= wppa_index_string_to_array( $mailinglist );

	// Mailinglist empty?
	if ( empty( $userarray ) ) {
		wppa_log( 'Eml', 'Mailinglist {b}' . $type . '{/b} has no subscribers and will not run' );
		wppa_exit();
	}

	// There is a bug in wp cron
	// The cron lock sometimes fails, so there is a possbility that two processes execute the same cron job simultaneously
	// To prevent duplicate emails, we have our own 'lock' that can not run stuck because it needs no release

	// Check for 'lock'
	if ( wppa_is_cron() ) {

		// Before going on, we wait a small period dependant of our process id,
		// to make sure that if there are two simultaneous cron processes,
		// they will get out of sync
		$sleep = getmypid() % 17;
		wppa_log( 'Eml', "Waiting $sleep seconds" );
		sleep( $sleep );

		// Mailinglist just done or executoing?
		$lock_file 	= WPPA_LOCKDIR . '/' . $type;
		if ( wppa_is_file( $lock_file ) ) {
			$lock_value = wppa_get_contents( $lock_file );
		}
		else {
			$lock_value = '';
		}
		$our_lock 	= $alb . '-' . $pho . '-' . $com . '-' . $start;

		// Is last mailing equal to our mailing?
		if ( $lock_value == $our_lock ) {

			// Is this lock not older than 18 second?
			if ( wppa_filetime( $lock_file ) >= ( time() - 18 ) ) {
				wppa_log( 'Eml', '{span style="color:red;" }{b}CRON ERROR{/b}{/span} Duplicate cron process detected. Aborting {b}' . $type . '{/b}' . $log_args );
			}

			// It's older, so it is a regular re-run, but unneeded
			else {
				wppa_log( 'Eml', 'Duplicate mailing detected. Aborting {b}' . $type . '{/b}' . $log_args );
			}
			wppa_exit();
		}

		// Lock mailinglist
		wppa_put_contents( $lock_file, $our_lock, false );
	}

	// Log we are in
	wppa_log( 'Eml', 'Doing mailing {b}' . $type . '{/b} for ' . count( $userarray ) . ' recipients.' . $log_args );

	// Find itemtyupe: photo, video, audio or document; translated
	if ( $com ) {
		$p = $wpdb->get_var( $wpdb->prepare( "SELECT photo FROM $wpdb->wppa_comments WHERE id = %d", $com ) );
		$itemtype = wppa_get_type( $p, true );
		if ( ! $pho ) $pho = $p;
	}
	elseif ( $pho ) {
		$itemtype = wppa_get_type( $pho, true );
	}

	// Dispatch on type of mailinglist
	switch( $type ) {

		case 'subscribenotify':
			{
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
				$list_type 	= $alb;
				$user_id 	= $pho;
				$onoff 		= $com;
				$subj 		= $onoff == 'on' ? __( 'Email subscription', 'wp-photo-album-plus' ) : __( 'Email unsubscription', 'wp-photo-album-plus' );
				$user 		= get_user_by( 'ID', $user_id );
				$b 			= '<strong>';
				$_b 		= '</strong>';

				if ( $onoff == 'on' ) {
					$cont 	= sprintf( __( 'User %s has subscribed to mailinglist %s', 'wp-photo-album-plus' ), $b.$user->display_name.$_b, $b.$email_types[$list_type].$_b );
				}
				elseif ( $onoff == 'off' ) {
					$cont 	= sprintf( __( 'User %s has unsubscribed from mailinglist %s', 'wp-photo-album-plus' ), $b.$user->display_name.$_b, $b.$email_types[$list_type].$_b );
				}
				else {
					wppa_log( 'err', 'Unknown action to mailinglist subscription/unsubscription in wppa_do_mailinglist' );
					wppa_exit();
				}

				foreach( $userarray as $usr ) {

					// Get the user data
					$user = get_user_by( 'ID', $usr );

					// If user exists, mail
					if ( $user ) {
						wppa_send_mail( array( 'to' 			=> $user->user_email,
											   'subj' 			=> $subj,
											   'cont' 			=> $cont,
											   'photo' 			=> 0,
											   'listtype' 		=> $type,
											   'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
											));
					}
					else {
						wppa_unsubscribe_all( $usr );
					}
				}
			}
			break;

		case 'newalbumnotify':
			{
				// If album removed, quit
				$album = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE id = %d", $alb ), ARRAY_A );
				if ( ! $album ) {
					wppa_log( 'Eml', 'Mailing skipped: album ' . $alb . ' vanished' );
					wppa_exit();
				}

				// Get the album items we need
				$name = wppa_get_album_name( $alb );
				$desc = wppa_get_album_desc( $alb );

				// The blog
				$blog = get_bloginfo( 'name' );

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// The content of the mail
				$content =
					sprintf( __( 'A new album: %s has been created on %s', 'wp-photo-album-plus' ),
									'<b>' . $name . '</b>',
									'<b>' . $blog . '</b>' );

					if ( $desc ) {
						$content .=
							'<br><br>' . __( 'Description', 'wp-photo-album-plus' ) . ':<br><br>' .
							'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">' .
							'<em>' . $desc . '</em><br>' .
							'</blockquote>';
					}

					// Preview
					if ( $link ) {
						$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&cover=1&occur=1' );
						$content .=
							'<br>' .
							sprintf( __( 'You can see the content %shere%s', 'wp-photo-album-plus' ), '<a href="' . $the_link . '" >', '</a>' );
					}


				// Process all subscribed users
				foreach( $userarray as $usr ) {

					if ( $usr > $start ) {

						// Get the user data
						$user = get_user_by( 'ID', $usr );

						// Send the mail
						if ( $user ) {
							wppa_send_mail( array( 	'to' 			=> $user->user_email,
													'subj' 			=> __( 'New album created', 'wp-photo-album-plus' ),
													'cont' 			=> $content,
													'listtype' 		=> 'newalbumnotify',
													'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
													) );
						}
						else {
							wppa_unsubscribe_all( $usr );
						}

						// If time up, reschedule at current user id to run in 15 sec
						if ( wppa_is_time_up() ) {
							wppa_schedule_mailinglist( $type, $alb, $pho, $com, $url, $usr, 15 );
							wppa_exit();
						}
					}
				}
			}
			break;

		case 'feuploadnotify':
			{
				// If moderation required, do the moderatephoto mailing
				if ( wppa_get_photo_item( $pho, 'status' ) == 'pending' ) {
					wppa_schedule_mailinglist( 'moderatephoto', $alb, $pho, $com, $url );
					wppa_exit();
				}

				// See if there are more directly uploaded by this user in this album
				$timestamp 	= wppa_get_photo_item( $pho, 'timestamp' );
				$owner 		= wppa_get_photo_item( $pho, 'owner' );
				$photos 	= $wpdb->get_col( "SELECT id
											   FROM $wpdb->wppa_photos
											   WHERE timestamp >= $timestamp
											   AND owner = '$owner'"
											   );
				$multi = count( $photos ) > 1;

				// The subject
				if ( $multi ) {
					$count = count( $photos );
					$subj = sprintf( __( '%d New items uploaded', 'wp-photo-album-plus' ), $count );
				}
				else {
					$subj = sprintf( __( 'New %s uploaded: %s' , 'wp-photo-album-plus' ), wppa_get_type( $pho, true ), wppa_get_photo_item( $pho, 'name' ) );
				}

				// The album
				if ( ! $alb ) {
					$alb = wppa_get_photo_item( $pho, 'album' );
				}

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// The content
				if ( $multi ) {
					$cont = sprintf( __( 'User %1$s uploaded %2$s items into album %3$s' , 'wp-photo-album-plus' ), $owner, count( $photos ), wppa_get_album_name( $alb ) );
				}
				else {
					$cont = sprintf( __( 'User %1$s uploaded %2$s %3$s into album %4$s' , 'wp-photo-album-plus' ), $owner, wppa_get_type( $pho, true ), $pho, wppa_get_album_name( $alb ) );
				}

				// Preview
				if ( $link ) {
					$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
					$cont .= '<br>' . wppa_get_preview_text( $multi ? $photos : $pho, $the_link );
				}

				// Process all subscribed users
				foreach( $userarray as $usr ) {

					if ( $usr > $start ) {

						// Get the user data
						$user = get_user_by( 'ID', $usr );

						// If user exists, mail
						if ( $user ) {
							wppa_send_mail( array( 'to' 			=> $user->user_email,
												   'subj' 			=> $subj,
												   'cont' 			=> $cont,
												   'photo' 			=> ( $multi ? $photos : $pho ),
												   'listtype' 		=> $type,
												   'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
						}
						else {
							wppa_unsubscribe_all( $usr );
						}

						// If time up, reschedule at current user id to run in 15 sec
						if ( wppa_is_time_up() ) {
							wppa_schedule_mailinglist( $type, $alb, $pho, $com, $url, $usr, 15 );
							wppa_exit();
						}
					}
				}
				wppa_log('eml', 'deleting transient: ' . 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb);
				delete_transient( 'last_feuploadnotify_scheduled-' . $owner . '-' . $alb );
			}
			break;

		case 'commentnotify':

				// Get the comment
				$comment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE id = %d", $com ), ARRAY_A );

				// Get the photo id
//				if ( ! $pho ) {
//					$pho = $comment['photo'];
//				}

				// Get the photo owner
				$owner = wppa_get_photo_item( $pho, 'owner' );
				$owuser = get_user_by('login',$owner);

				// The author by email
				$author = get_user_by( 'email', $comment['email'] );

				if ( ! $author ) {

					// Try the author by login
					$author = get_user_by( 'login', $comment['email'] );
				}

				if ( $author ) {
					$aut = $author->display_name;
				}
				else {
					$aut = $comment['user'];
				}

				// Get the photos album
				if ( ! $alb ) {
					$alb = wppa_get_photo_item( $pho, 'album' );
				}

//				// If moderation required, do the moderatecomment mailing
//				if ( $comment['status']  == 'pending' ) {
//					wppa_do_mailinglist( 'moderatecomment', $alb, $pho, $com, $url );
//					return;
//				}

				// If limited receivers activated, reduce subscriptionlist
				if ( wppa_switch( 'commentnotify_limit' ) ) {

					// Admins
					$admins = wppa_get_admin_ids_a();
					wppa_log( 'Eml', 'admins ' . implode( ',', $admins ) );

					// Photo owner
					$powner = array( $owuser->ID );
					wppa_log( 'Eml', 'photo owner ' . $owuser->ID . ',  ' . $owuser->user_login );

					// Comment owner
					if ( $author ) {
						$cowner = $author ? array( $author->ID ) : array();
						wppa_log( 'Eml', 'comment owner ' . $author->ID . ',  ' . $author->user_login );
					}

					// Superusers
					$susers = wppa_get_superuser_ids_a();
					wppa_log( 'Eml', 'superusers ' . implode( ',', $susers ) );

					// All potential receipients
					// The next line fails on https://learn.iphotography.com/
				//	$all_potential = array_unique( array_merge( $admins, $powner, $cowner, $susers ), SORT_NUMERIC );
					// So we do it manually
					$all_potential = array();
					foreach( $admins as $a ) {
						$all_potential[] = $a;
					}
					if ( isset( $powner[0] ) ) $all_potential[] = $powner[0];
					if ( isset( $cowner[0] ) ) $all_potential[] = $cowner[0];
					foreach( $susers as $s ) {
						$all_potential[] = $s;
					}
					$all_potential = array_unique( $all_potential );

					$userarray = array_intersect( $userarray, $all_potential );
					wppa_log( 'Eml', 'Potential receipients: ' . implode( ',', $all_potential ) . ', all subscribed potential: ' . implode( ',', $userarray ) );
				}

				// Subject
				$subj = sprintf( __( 'Comment on %s %s' , 'wp-photo-album-plus' ), wppa_get_type( $pho, true ), wppa_get_photo_name( $comment['photo'] ) );

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// The content
				/* Translators: <username> wrote on <photo|video|audio> <itemid> in album <albumname> */
				$cont = sprintf( __( '%1$s wrote on %2$s %3$s in album %4$s', 'wp-photo-album-plus' ), $aut, wppa_get_type( $pho, true ), wppa_get_photo_name( $comment['photo'] ), wppa_get_album_name( $alb ) ) .

					'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
						<em> ' . stripslashes( nl2br( $comment['comment'] ) ) . '</em>
					</blockquote>';

				// Preview
				if ( $link ) {
					$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
					$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
				}

				// Process all subscribed users
				foreach( $userarray as $usr ) {

					if ( $usr > $start ) {

						// Get the user data
						$user = get_user_by( 'ID', $usr );

						// If user exists
						if ( $user ) {
							wppa_send_mail( array( 'to' 			=> $user->user_email,
												   'subj' 			=> $subj,
												   'cont' 			=> $cont,
												   'photo' 			=> $pho,
												   'listtype' 		=> $type,
												   'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
						}

						// User does not exist, remove him from all lists
						else {
							wppa_unsubscribe_all( $usr );
						}

						// If time up, reschedule at current user id to run in 15 sec
						if ( wppa_is_time_up() ) {
							wppa_schedule_mailinglist( $type, $alb, $pho, $com, $url, $usr, 15 );
							wppa_exit();
						}
					}
				}

				// Now do the 'commentprevious' mailing
				wppa_schedule_mailinglist( 'commentprevious', 0, 0, $com );

			break;

		case 'photoapproved':

				// To who?
				$user = get_user_by( 'login', wppa_get_photo_item( $pho, 'owner' ) );
				if ( ! $user ) {
					wppa_log( 'Eml', 'Mailing skipped: user ' . wppa_get_photo_item( $pho, 'owner' ) . ' vanished' );
					wppa_exit();
				}
				$usr = $user->ID;

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// Did user subscribe?
				if ( wppa_is_user_in_mailinglist( $user->ID, 'photoapproved' ) ) {

					$to 	= $user->user_email;
					$subj 	= sprintf( __( '%s approved', 'wp-photo-album-plus' ), ucfirst( wppa_get_type( $pho, true ) ) );
					/* Translators: Your recently uploaded <itemtype> <itemname> in album <albumname> has been approved */
					$cont 	= sprintf( 	__( 'Your recently uploaded %1$s %2$s in album %3$s has been approved', 'wp-photo-album-plus' ),
										wppa_get_type( $pho, true ),
										'<b>' . wppa_get_photo_item( $pho, 'name' ) . '</b>',
										'<b>' . wppa_get_album_name( wppa_get_photo_item( $pho, 'album' ) ) . '</b>' );

					// Preview
					if ( $link ) {
						$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
						$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
					}

					if ( $user ) {
						wppa_send_mail( array( 	'to'			=> $to,
												'subj'			=> $subj,
												'cont'			=> $cont,
												'photo'			=> $pho,
												'listtype'		=> $type,
												'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
					}
					else {
						wppa_unsubscribe_all( $usr );
					}
				}

				// Now the photo is approved, we can mail 'feuploadnotify'
				wppa_schedule_mailinglist( 'feuploadnotify', 0, $pho );

			break;

		// A comment on my photo is approved or my comment is approved
		case 'commentapproved':

				// Get the comment
				$comment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE id = %d", $com ), ARRAY_A );

				// The photo
				if ( ! $pho ) {
					$pho = $comment['photo'];
				}

				// Find the owner of the photo
				$owner = wppa_get_photo_item( $pho, 'owner' );

				// The author
				$author = get_user_by( 'login', $comment['user'] );
				if ( $author ) {
					$aut = $author->display_name;
				}
				else {
					$aut = $comment['user'];
				}

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// Send the owner of the photo the mail if he is in the mailinglist
				if ( wppa_is_user_in_mailinglist( $owner, $type ) ) {

					// Get the user data
					$user = get_user_by( 'login', $owner );
					$usr = $user->ID;

					// If user still exists...
					if ( $user ) {

						$cont =
						$aut . ' ' . __( 'wrote on photo' , 'wp-photo-album-plus' ) . ' ' . wppa_get_photo_name( $comment['photo'] ) . ':' .
						'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
							<em> ' . stripslashes( nl2br( $comment['comment'] ) ) . '</em>
						</blockquote>';

						// Preview
						if ( $link ) {
							$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
							$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
						}

						/* Translators: Comment on <itemtype> <itemname> approved */
						$subj = sprintf( __( 'Comment on %1$s %2$s appoved' , 'wp-photo-album-plus' ), wppa_get_type( $pho, true ), wppa_get_photo_name( $comment['photo'] ) );

						wppa_send_mail( array( 	'to' 			=> $user->user_email,
												'subj' 			=> $subj,
												'cont' 			=> $cont,
												'photo' 		=> $comment['photo'],
												'replyurl' 		=> $url,
												'listtype' 		=> $type,
												'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
					}
					else {
						wppa_unsubscribe_all( $usr );
					}
				}

				// Send the author of the comment the mail if he is in the mailinglist
				if ( $author && wppa_is_user_in_mailinglist( $author->ID, $type ) ) {

					$cont = __( 'You wrote on photo', 'wp-photo-album-plus' ) . ' ' . wppa_get_photo_name( $comment['photo'] ) . ':' .
					'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
						<em> ' . stripslashes( nl2br( $comment['comment'] ) ) . '</em>
					</blockquote>';

					// Preview
					if ( $link ) {
						$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
						$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
					}

					/* Translators: Your comment on <itemtype> <itemname> approved */
					$subj = sprintf( __( 'Your comment on %1$s %2$s appoved' , 'wp-photo-album-plus' ), wppa_get_type( $pho, true ), wppa_get_photo_name( $comment['photo'] ) );

					wppa_send_mail( array( 	'to' 			=> $author->user_email,
											'subj' 			=> $subj,
											'cont' 			=> $cont,
											'photo' 		=> $comment['photo'],
											'replyurl' 		=> $url,
											'listtype' 		=> $type,
											'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
											));

				}

				// Now the comment is approved, we can mail 'commentnotify'
				wppa_schedule_mailinglist( 'commentnotify', 0, 0, $com );


			break;

		case 'commentprevious':

				// Get the comment
				$comment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE id = %d", $com ), ARRAY_A );

				if ( $comment ) {

					// Get the photo
					if ( ! $pho ) {
						$pho = $comment['photo'];
					}

					// Get teh author
					$author = get_user_by( 'login', $comment['user'] );
					if ( $author ) {
						$aut = $author->display_name;
					}
					else {
						$aut = $comment['user'];
					}

					// Get the users who commented on the photo
					$users = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT user FROM $wpdb->wppa_comments WHERE photo = %d", $pho ) );

					// If the current author is in the list: remove him, he is most likely already notified
					if ( isset( $users[$comment['user']] ) ) {
						unset( $users[$comment['user']] );
					}

					// Any users left?
					if ( empty( $users ) ) {
						wppa_log( 'Eml', 'No items for commentprevious mailing' );
						wppa_exit();
					}

					// The callback url if any
					$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

					$cont =
					$aut . ' ' . __( 'wrote on photo' , 'wp-photo-album-plus' ) . ' ' . wppa_get_photo_name( $comment['photo'] ) . ':' .
					'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
						<em> ' . stripslashes( nl2br( $comment['comment'] ) ) . '</em>
					</blockquote>';

					// Preview
					if ( $link ) {
						$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
						$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
					}

					// Process users
					foreach( $users as $usr ) {

						$user = get_user_by( 'login', $usr );

						// User still exists?
						if ( $user ) {

							wppa_send_mail( array( 	'to' 			=> $user->user_email,
													'subj' 			=> sprintf( __( 'Comment on photo %s that you commented earlier' , 'wp-photo-album-plus' ), wppa_get_photo_name( $comment['photo'] ) ),
													'cont' 			=> $cont,
													'photo' 		=> $comment['photo'],
													'replyurl' 		=> $url,
													'listtype' 		=> $type,
													'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
													));

						}
						else {
							wppa_unsubscribe_all( $usr );
						}
					}
				}

			break;

		case 'moderatephoto':

				// The subject
				$subj = sprintf( __( 'New photo moderate request: %s' , 'wp-photo-album-plus' ), wppa_get_photo_item( $pho, 'name' ) );

				// The photo owner
				$owner = get_user_by( 'login', wppa_get_photo_item( $pho, 'owner' ) );
				if ( ! $owner ) {
					$owner = new WP_User;
					$owner->display_name = __( 'Anonymus', 'wp-photo-album-plus' );
					$owner->user_email = '';
				}

				// The album
				if ( ! $alb ) {
					$alb = wppa_get_photo_item( $pho, 'album' );
				}

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				// The content
				$cont = sprintf( __( 'User %1$s uploaded photo %2$s into album %3$s' , 'wp-photo-album-plus' ),
						$owner->display_name . ( $owner->user_email ? ' (' . make_clickable( $owner->user_email ) . ') ' : '' ),
						$pho,
						wppa_get_album_name( $alb ) );

				$cont .= '<br>' . __( 'This photo needs moderation', 'wp-photo-album-plus' );

				// Preview
				if ( $link ) {
					$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
					$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
				}

				// Moderate links
				$cont .=
				'<br><a href="' . get_admin_url() . 'admin.php?page=wppa_moderate_photos&photo=' . $pho . '" >' .
					__( 'Moderate photo admin' , 'wp-photo-album-plus' ) .
				'</a>';

				// Process all subscribed users
				foreach( $userarray as $usr ) {

					// Get the user data
					$user = get_user_by( 'ID', $usr );

					// If user exists, mail
					if ( $user ) {
						if ( user_can( $usr, 'wppa_moderate' ) ) {
							wppa_send_mail( array( 'to' 			=> $user->user_email,
												   'subj' 			=> $subj,
												   'cont' 			=> $cont,
												   'photo' 			=> $pho,
												   'email' 			=> $owner->user_email,
												   'listtype' 		=> $type,
												   'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
						}
					}
					else {
						wppa_unsubscribe_all( $usr );
					}
				}

			break;

		case 'moderatecomment':

				// The comment
				$comment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE id = %d", $com ), ARRAY_A );

				// The photo
				if ( ! $pho ) {
					$pho = $comment['photo'];
				}

				// The album
				if ( ! $alb ) {
					$alb = wppa_get_photo_item( $pho, 'album' );
				}

				// If the comment is already approved by a vote when comment needs vote is on, we're done
				if ( $comment['status'] == 'approved' ) {
					wppa_log( 'Eml', 'Comment approved by voting. Mailing {b}moderatecomment{/b} aborted' );
					wppa_exit();
				}

				// The subject
				$subj = sprintf( __( 'New comment moderate request: %s' , 'wp-photo-album-plus' ), wppa_get_photo_item( $pho, 'name' ) );

				// Get teh author
				$author = get_user_by( 'email', $comment['email'] );
				if ( $author ) {
					$aut = $author->display_name;
				}
				else {
					$aut = $comment['user'];
				}

				// The callback url if any
				$link = wppa_get_option( 'wppa_mailinglist_callback_url', '' );

				$cont =
				$aut . ( strpos( $comment['email'], '@' ) ? ' (' . make_clickable( $comment['email'] ) . ') ' : ' ' ) . __( 'wrote on photo' , 'wp-photo-album-plus' ) . ' ' . wppa_get_photo_name( $pho ) . ':' .
				'<blockquote style="color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius: 4px">
					<em> ' . stripslashes( nl2br( $comment['comment'] ) ) . '</em>
				</blockquote>';

				// Preview
				if ( $link ) {
					$the_link = wppa_encrypt_url( $link . '?album=' . $alb . '&photo=' . $pho . '&occur=1' );
					$cont .= '<br>' . wppa_get_preview_text( $pho, $the_link );
				}

				// Moderate links
				$cont .=
				'<br><a href="' . get_admin_url() . 'admin.php?page=wppa_manage_comments&commentid=' . $com . '" >' .
					__( 'Moderate photo admin' , 'wp-photo-album-plus' ) .
				'</a>';

				// The commenters email, only if the user exists, i.e. we are sure the email is valid
				$email = ( $author ? $author->user_email : '' );

				// Process all subscribed users
				foreach( $userarray as $usr ) {

					// Get the user data
					$user = get_user_by( 'ID', $usr );

					// If user exists, mail
					if ( $user ) {
						if ( user_can( $usr, 'wppa_moderate' ) ) {
							wppa_send_mail( array( 'to' 			=> $user->user_email,
												   'subj' 			=> $subj,
												   'cont' 			=> $cont,
												   'photo' 			=> $pho,
												   'email' 			=> $email,
												   'listtype' 		=> $type,
												   'unsubscribe' 	=> wppa_unsubscribe_link( $usr, $type ),
												));
						}
					}
					else {
						wppa_unsubscribe_all( $usr );
					}
				}

			break;

		default:
			wppa_log( 'Err', 'Unimplemented mailinglist type found: {b}' . $type . '{/b}', true );
	}

	wppa_log( 'Eml', 'Done mailing {b}' . $type . '{/b}' . $log_args );
	wppa_exit();
}

// Is current user in mailinglist?
function wppa_am_i_in_mailinglist( $list ) {

	$my_user_id = wppa_get_user( 'id' );
	return wppa_is_user_in_mailinglist( $my_user_id, $list );
}

// Is a given user in a certain mailinglist?
// @1: user, id (numeric), or login name
// @2: list, slug indicating list type
function wppa_is_user_in_mailinglist( $usr, $list ) {

	if ( is_numeric( $usr ) ) {
		$user_id = $usr;
	}
	else {
		$user = get_user_by( 'login', $usr );
		if ( $user ) {
			$user_id = $user->ID;
		}
		else {
			$user_id = '0';
		}
	}
	$mailinglist = wppa_get_option( 'wppa_mailinglist_' . $list, '' );
	$userarray = wppa_index_string_to_array( $mailinglist );
	return ( in_array( $user_id, $userarray ) );
}

// Remove user fron mailinglist
function wppa_unsubscribe_user( $user_id, $list_type ) {

	if ( ! $user_id ) {
		return;
	}

	$mailinglist 	= wppa_get_option( 'wppa_mailinglist_' . $list_type, '' );
	$userarray 		= wppa_index_string_to_array( $mailinglist );
	if ( in_array( $user_id, $userarray ) ) {
		$userarray 		= array_diff( $userarray, array( $user_id ) );
		$mailinglist 	= wppa_index_array_to_string( $userarray );
		wppa_update_option( 'wppa_mailinglist_' . $list_type, $mailinglist );
	}
}

// Remove user from all lists
function wppa_unsubscribe_all( $user_id ) {

	$lists = array( 'newalbumnotify',
					'feuploadnotify',
					'commentnotify',
					'commentprevious',
					'moderatephoto',
					'moderatecomment',
					'photoapproved',
					'commentapproved',
					);

	foreach( $lists as $list_type ) {
		wppa_unsubscribe_user( $user_id, $list_type );
	}
}

// Add user to mailinglist
function wppa_subscribe_user( $user_id, $list_type ) {

	if ( ! $user_id ) {
		return;
	}

	$mailinglist 	= wppa_get_option( 'wppa_mailinglist_' . $list_type, '' );
	$userarray 		= wppa_index_string_to_array( $mailinglist );
	if ( ! in_array( $user_id, $userarray ) ) {
		$userarray[] = $user_id;
		sort( $userarray );
		$mailinglist 	= wppa_index_array_to_string( $userarray );
		wppa_update_option( 'wppa_mailinglist_' . $list_type, $mailinglist );
	}
}

// Get the unsubscribe link
function wppa_unsubscribe_link( $user_id, $listtype ) {

	$user = get_user_by( 'ID', $user_id );
	$crypt = crypt( $listtype . $user->ID . $user->login_name, $user->display_name );

//	switch ( wppa_opt( 'ajax_method' ) ) {
//		case 'admin':
//			$url = site_url() . '/wp-admin/admin-ajax.php';
//			break;
//		case 'extern':
//			$url = WPPA_URL . '/wppa-ajax-front.php';
//			break;
//		default:
			$url = ( wppa_switch( 'ajax_home' ) ? home_url() : site_url() ) . '/wppaajax';
//			break;
//	}
	$url .= '?action=wppa&wppa-action=mailinglist&list=' . $listtype . '&onoff=off&user=' . $user_id . '&crypt=' . $crypt;

	$link = '<a href="' . $url . '" >';
	$result = sprintf( __( 'You can %sunsubscribe%s here from this mailinglist', 'wp-photo-album-plus' ), $link, '</a>' );

	return $result;
}

// Send a mail
function wppa_send_mail( $args ) {

	// Enhance $args
	$defaults = array( 	'to' => '',
						'subj' => '',
						'cont' => '',
						'photo' => '0',
						'email' => '',
						'listtype' => '',
						'replyurl' => '',
						'unsubscribe' => '',
						);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	// User id given?
	if ( is_numeric( $to ) ) {
		$user = get_user_by( 'id', $to );
		if ( $user ) {
			$to = $user->user_email;
			$id = $user->ID;
		}
		else {
			wppa_log( 'Err', 'Attempt to mail to a non existing user', true );
			return;
		}
	}
	else {
		$user = get_user_by( 'email', $to );
		if ( $user ) {
			$id = $user->ID;
		}
		else {
			$id = 'NN';
		}
	}

	$message_part_1 = '';
	$message_part_2 = '';
	$message_part_3 = '';

	$headers 	= array( 	'From: ' . wppa_opt( 'email_from_email' ),
							'Content-Type: text/html; charset=UTF-8'
						);

	$photos = array();
	if ( is_array( $photo ) ) {
		$photos = $photo;
	}
	else {
		$photos[] = $photo;
	}

	$message_part_1	.= '
<html>
	<head>
	</head>
	<body>
		<table>
			<tbody>
				<tr>
					<td>
						<h3>' . $subj . '</h3>
					</td>
				</tr>';
				if ( ! empty( $photos ) && wppa_switch( 'show_email_thumbs' ) ) {
					$message_part_1	.= '
					<tr>
						<td style="padding:4px;text-align:center">';
						$i = 0;
						if ( count( $photos ) ) {
							foreach( $photos as $p ) if ( $p ) {
								$message_part_1	.= '
								<img
									src="' . wppa_get_thumb_url($p) . '" ' . wppa_get_imgalt($p) . '
									style="height:120px"
								/>';
								$i++;
								if ( 0 == ( $i % 5 ) ) {
									$message_part_1	.= '</td></tr><tr><td style="padding:4px;text-align:center">';
								}
							}
						}
						$message_part_1	.= '
						</td>
					</tr>';
				}


				if ( is_array( $cont ) && wppa_switch( 'show_email_thumbs' ) ) {
					foreach ( $cont as $c ) if ( $c ) {
						$message_part_1 .= '<tr><td>'.$c.'</td></tr>';
					}
				}
				else {
					$message_part_1 .= '<tr><td>'.$cont.'</td></tr>';
				}

				// Tell the moderator the email address of the originator of the photo/comment
				if ( $email && substr( $listtype, 0, 8 ) == 'moderate' || $listtype == 'showemail' ) {

					$eml = sprintf(__('The visitors email address is: <a href="mailto:%s">%s</a>', 'wp-photo-album-plus' ), $email, $email);
					$message_part_2 .= '<tr><td>'.$eml.'</td></tr>';
				}

				// Reply link
				if ( $replyurl ) {
					$message_part_2 .= '<tr><td><a href="' . $replyurl . '" >' . __( 'Reply' , 'wp-photo-album-plus' ) . '</a></td></tr>';
				}

				// Unsubscribe link
				if ( $unsubscribe ) {
					$message_part_2 .= '<tr><td>' . $unsubscribe . '</td></tr>';
				}

				// Generic message
				$message_part_3 .=
				'<tr><td>
					<small>' .
						sprintf(__('This message is automatically generated at %s. It is useless to respond to it.', 'wp-photo-album-plus' ), '<a href="'.home_url().'" >'.home_url().'</a>') .
					'</small>' .
					( defined( 'WP_DEBUG' ) ? ' <small>(' . $listtype . ')</small>' : '' ) . '
				</td></tr>
			</tbody>
		</table>' .
	'</body>' .
'</html>';

	$subject = '['.str_replace('&#039;', '', wppa_opt('email_from_site') ).'] '.$subj;
	$message = $message_part_1 . $message_part_2 . $message_part_3;

	// If this mail has already been sent, skip and report
	$hash = wppa_get_mail_hash( $to, $subject, $message, $headers );
	if ( get_transient( 'wppa_' . $hash ) ) {

		wppa_log( 'Eml', 'Hash: ' . $hash . ' Sending duplicate mail skipped to: ' . $to . ' (' . $id . ') subject: ' . $subject );
		return;
	}

	// Try to send it with extra headers and with html
	$iret = wp_mail( 	$to,
						$subject,
						$message,
						$headers
					);
	if ( $iret ) {
		wppa_log( 'Eml',
				  'Hash: ' . wppa_get_mail_hash( $to, $subject, $message, $headers ) . ', ' .
				  'Mail sent to: ' . $to . ' (' . $id . ') ' .
				  'subject: ' . $subject . ', ' .
				  'photo: ' . ( $photo ? ( is_array( $photo ) ? serialize( $photo ) : $photo ) : 'not supplied.' ) );

		// Remember this mail has been sent
		set_transient( 'wppa_' . $hash, getmypid(), WEEK_IN_SECONDS );
		return;
	}

	wppa_log( 'Err', 'Mail sending failed. Hash: ' . $hash . ', To=' . $to . ', subject=' . $subject . ', message=' . $message );

	// Failed
	if ( ! wppa_is_cron() ) {
		wppa_echo( __( 'Mail sending Failed', 'wp-photo-album-plus' ) );
	}

	// Registee failed mail
	wppa_process_failed_mail(	$to,
								$subject,
								$message,
								$headers,
								'' );
}

// Compute mail id
function wppa_get_mail_hash( $to = '', $subject = '', $message = '', $headers = '' ) {

	$mes = str_replace( array(	'newalbumnotify',
								'feuploadnotify',
								'commentnotify',
								'commentprevious',
								'moderatephoto',
								'moderatecomment',
								'photoapproved',
								'commentapproved',
							),
							'',
							$message );

	return md5( ( is_array( $to ) ? implode( '|', $to ) : $to ) . $subject . $mes );
}

// Save failed mail data to retry later
function wppa_process_failed_mail( $to = '', $subject = '', $message = '', $headers = '' ) {

	// Ignore mails that lack essential data
	if ( ! $to || ! $subject || ! $message ) {
		return;
	}

	// Compute mail id
	$id = wppa_get_mail_hash( $to, $subject, $message, $headers );

	// Get stack of failed mails
	$failed_mails = wppa_get_option( 'wppa_failed_mails', array() );

	// See if this mail appears in the failed mails list
	$found = false;
	foreach( array_keys( $failed_mails ) as $key ) {
		if ( $id == $key ) {
			$found = true;
		}
	}

	// Found? do nothing
	if ( $found ) {
		return;
	}

	// Not found, add it
	$failed_mails[$id] = array( 'to' 		=> $to,
								'subj' 		=> $subject,
								'message' 	=> $message,
								'headers' 	=> $headers,
								'retry' 	=> wppa_opt( 'retry_mails' ),
								);

	// Store list
	wppa_update_option( 'wppa_failed_mails', $failed_mails );

}

// Get the translated preview text, taking item type into account
function wppa_get_preview_text( $id, $link ) {

	if ( is_array( $id ) ) {
		return sprintf( __( 'You can see the items %shere%s', 'wp-photo-album-plus' ), '<a href="' . $link . '" >', '</a>' );
	}

	$type = wppa_get_type( $id );
	switch ( $type ) {
		case 'audio':
			return sprintf( __( 'You can hear the audio %shere%s', 'wp-photo-album-plus' ), '<a href="' . $link . '" >', '</a>' );
			break;
		case 'video':
			return sprintf( __( 'You can see the video %shere%s', 'wp-photo-album-plus' ), '<a href="' . $link . '" >', '</a>' );
			break;
		case 'document';
			return sprintf( __( 'You can see the document %shere%s', 'wp-photo-album-plus' ), '<a href="' . $link . '" >', '</a>' );
			break;
		default:
			return sprintf( __( 'You can see the photo %shere%s', 'wp-photo-album-plus' ), '<a href="' . $link . '" >', '</a>' );
			break;
	}
}