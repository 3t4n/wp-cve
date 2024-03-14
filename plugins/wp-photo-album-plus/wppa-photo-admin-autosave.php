<?php
/* wppa-photo-admin-autosave.php
* Package: wp-photo-album-plus
*
* edit and delete photos
* Version: 8.6.04.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Edit photo for owners of the photo(s) only
function _wppa_edit_photo() {

	// Check input
	wppa_vfy_arg( 'photo' );

	// Edit one Photo
	if ( wppa_get( 'photo' ) ) {
		$photo = wppa_get( 'photo' );
		$thumb = wppa_cache_photo( $photo );
		if ( $thumb['owner'] == wppa_get_user() ) {
			wppa_echo( '
			<div class="wrap">
				<h1 class="wp-heading-inline">' .
					get_admin_page_title() . '
				</h1>' );
				wppa_album_photos( '', $photo );
			wppa_echo( '
			</div>' );
		}
		else {
			wp_die( 'You do not have the rights to do this' );
		}
	}

	// Edit all photos owned by current user
	else {
		wppa_echo( '
		<div class="wrap">
			<h1 class="wp-heading-inline">' .
				get_admin_page_title() . '
			</h1>' );
			wppa_album_photos( '', '', wppa_get_user() );
		wppa_echo( '
		</div>' );
	}
}

// Moderate photos
function _wppa_moderate_photos( $what ) {

	// Check input and get photo id if any
	$photo = wppa_vfy_arg( 'photo' );
	$just_edit = wppa_get( 'just-edit' );

	if ( $photo && $just_edit && wppa_user_is_admin() ) {
		wppa_echo( '
		<div class="wrap">
			<h1 class="wp-heading-inline">' .
				esc_html__( 'Edit', 'wp-photo-album-plus' ) . '
			</h1>' .
			wppa_get( 'just-edit' ) );
			wppa_album_photos( '', $photo, '', false );
			wppa_echo( '
		</div>' );
	}
	else {
		wppa_echo( '
		<div class="wrap">
			<h1 class="wp-heading-inline">' .
				get_admin_page_title() . '
			</h1>' );
			if ( wppa_switch( 'moderate_bulk' ) ) {
				wppa_album_photos_bulk( 'moderate' );
			}
			else {
				wppa_album_photos( '', $photo, '', $what );
			}
			wppa_echo( '
		</div>' );
	}
}

// The photo edit list. Also used in wppa-album-admin-autosave.php
function wppa_album_photos( $album = '', $photo = '', $owner = '', $moderate = false, $page_1 = false ) {
global $wpdb;

	// Init
	wppa_add_local_js( 'wppa_album_photos' );
	$a 			= wppa_is_int( $album ) ? $album : '0';
	$is_empty 	= false;

	$slug 		= 'photo_admin';
	$quick 		= wppa_get( 'quick' );
	if ( $quick )
		$slug 	= 'photo_quick';

	$parms 		= wppa_get_paging_parms( $slug, $page_1 );

	$pagesize 	= $parms['pagesize'];
	$page 		= $parms['page'];

	if ( ! is_numeric( $page ) )
		$page 	= 1;
	$skip 		= ( $page - '1' ) * $pagesize;
	$is_album 	= false;
	$photos 	= array();

	// Edit the photos in a specific album
	if ( $album ) {

		// Special album case: search (see last album line in album table)
		if ( $album == 'search' ) {
			$count 	= wppa_get_edit_search_photos( '', '', 'count_only' );
			$photos = wppa_get_edit_search_photos( $skip, $pagesize );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos( $album, $photo, $owner, $moderate, true );
				return;
			}

			$link 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=' . $album . '&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '&wppa-searchstring=' . wppa_get( 'searchstring' );
		}

		// Edit trashed photos
		elseif ( $album == 'trash' ) {
			$count 	= wppa_get_count( WPPA_PHOTOS, ['album' => '0'], ['<'] );
			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE album < '0' ORDER BY modified DESC
														   LIMIT %d, %d", $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos( $album, $photo, $owner, $moderate, true );
				return;
			}

			$link 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=trash&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
		}

		// A single photo
		elseif ( $album == 'single' ) {
			$p = wppa_get( 'photo' );
			$count 	= $p ? 1 : 0;
			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE id = %d", $p ), ARRAY_A );
			$count 	= is_array( $photos ) ? count( $photos ) : 0;
			$link 	= '';
		}

		// A physical album
		else {
			$is_album = true;
			$counts = wppa_get_treecounts_a( $album, true );
			$count 	= $counts['selfphotos'] + $counts['pendselfphotos'] + $counts['scheduledselfphotos'];
			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE album = %s
														   " . wppa_get_photo_order( $album, 'no_random' ) . "
														   LIMIT %d, %d", $album, $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos( $album, $photo, $owner, $moderate, true );
				return;
			}

			$link 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=' . $album . '&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
		}
	}

	// Edit a single photo
	elseif ( $photo && ! $moderate ) {
		$count 	= '1';
		$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													   WHERE id = %s", $photo ), ARRAY_A );
		$link 	= '';
	}

	// Edit the photos of a specific owner
	elseif ( $owner ) {
		$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													   WHERE owner = %s
													   ORDER BY timestamp DESC
													   LIMIT %d, %d", $owner, $skip, $pagesize ), ARRAY_A );

		if ( ! count( $photos ) && $parms['page'] > '1' ) {
			wppa_album_photos( $album, $photo, $owner, $moderate, true );
			return;
		}

		$count 	= is_array( $photos ) ? count( $photos ) : 0;
		$link 	= get_admin_url() . 'admin.php?page=wppa_edit_photo&wppa-nonce=' . wp_create_nonce('wppa-nonce');
	}

	// Moderate photos
	elseif ( $moderate ) {

		// Can i moderate?
		if ( ! current_user_can( 'wppa_moderate' ) ) {
			wp_die( __( 'You do not have the rights to do this', 'wp-photo-album-plus' ) );
		}

		// Moderate a single photo
		if ( $photo ) {
			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE id = %s", $photo ), ARRAY_A );
			$count 	= is_array( $photos ) ? count( $photos ) : 0;
			$link 	= '';
		}

		// Are there photos to moderate?
		elseif ( empty( $photos ) && $moderate == 'photos' ) {

			$photos = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
										   WHERE status = 'pending'
										   AND album > 0", ARRAY_A );
			$count = count( $photos );
		}

		// Are there photos with pending comments?
		if ( $moderate == 'comments' ) {

			// Find pending comments
			$cmt = $wpdb->get_col( "SELECT photo FROM $wpdb->wppa_comments
										WHERE status = 'pending'
										OR status = 'spam'" );

			$photos = array();
			if ( is_array( $cmt ) && count( $cmt ) ) {

				// Remove duplicate photo ids
				$cmt = array_unique( $cmt );

				foreach( $cmt as $id ) {
					$photos[] = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																 WHERE id = %d", $id ), ARRAY_A );
				}
			}
		}
		$what = $moderate;

		$link 	= get_admin_url() . 'admin.php?page=wppa_moderate_'.$what.'&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
	}

	// If not one of the cases above apply, log error and quit
	else {
		wppa_log( 'Err', 'Missing required argument in wppa_album_photos() 1' );
		return;
	}

	// No photos to moderate?
	if ( empty( $photos ) ) {

		$is_empty = true;

		// Single photo moderate requested
		if ( $photo ) {
			wppa_echo( '<p>' . esc_html__( 'This photo is no longer awaiting moderation' , 'wp-photo-album-plus' ) . '</p>' );
		}

		// Multiple photos to moderate requested
		elseif ( $moderate ) {
			wppa_echo( '<p>' . esc_html__( 'There are no photos awaiting moderation at this time', 'wp-photo-album-plus' ) . '</p>' );
		}

		elseif ( ! wppa_is_int( $album ) ) {
			wppa_echo( '<p>' . esc_html__( 'There are no items matching your search creteria', 'wp-photo-album-plus' ) . '</p>' );
		}

		// If i am admin, i can edit all photos here, sorted by timestamp desc
		if ( wppa_user_is_admin() && ! wppa_is_int( $album ) ) {

			wppa_echo( '<p>' . esc_html( 'Instead, here is a list of all items ordered by timestamp, most recently first', 'wp-photo-album-plus' ) . '</p>' );
			wppa_echo( '<h1>' . esc_html__( 'Manage all photos by timestamp', 'wp-photo-album-plus' ) . '</h1>' );

			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   ORDER BY timestamp DESC
														   LIMIT %d, %d", $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos( $album, $photo, $owner, $moderate, true );
				return;
			}

			$count  = wppa_get_count( WPPA_PHOTOS );
			$link 	= get_admin_url() . 'admin.php?page=wppa_moderate_photos&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
		}

		// Nothing to do
		elseif ( wppa_is_int( $album ) ) {

			wppa_echo( '<p>' . esc_html__( 'The album is empty', 'wp-photo-album-plus' ) . '</p>' );
			return;
		}

		else {
			return;
		}
	}

	// Quick edit skips a few time consuming settings like copy and move to other album
	$quick = wppa_get( 'quick' );
	if ( $link && $quick ) $link .= '&quick=1';

	// In case it is a seaerch and edit, show the search statistics
	if ( ! $is_empty ) {
		wppa_show_search_statistics();
	}

	// If no photos and page != 1, retry om page 1
	if ( empty( $photos ) && $parms['page'] > '1' ) {
		wppa_album_photos( $album, $photo, $owner, $moderate, true );
		return;
	}

	// If no photos selected produce appropriate message and quit
	if ( empty( $photos ) ) {

		// A specific photo requested
		if ( $photo ) {
			$photo = strval( intval( $photo ) );
			wppa_echo( '
			<div id="photoitem-' . $photo . '" class="photoitem" style="width:100%; background-color: rgb( 255, 255, 224 ); border-color: rgb( 230, 219, 85 )">
				<span style="color:red">' .
					sprintf( esc_html__( 'Photo %s has been removed.', 'wp-photo-album-plus' ), $photo ) . '
				</span>
			</div>' );
		}

		// A collection of photos requested
		else {

			// Search
			if ( wppa_get( 'searchstring' ) ) {
				wppa_echo( '<h1>' . esc_html__( 'No photos matching your search criteria.', 'wp-photo-album-plus' ) . ' 1</h1>' );
			}

			// Album
			else {
				wppa_echo( '<h1>' . esc_html__( 'No photos yet in this album.', 'wp-photo-album-plus' ) . '</h1>' );
			}
		}

		return;
	}

	// There are photos to display for editing
	else {

		if ( $moderate ) {
			$parms 		= wppa_get_paging_parms( 'moderate_photos', $page_1 );
			$pagesize 	= $parms['pagesize'];
			$page 		= $parms['page'];
			$skip 		= ( $page - '1' ) * $pagesize;
			$count 		= count( $photos );
			$photos 	= array_slice( $photos, $skip, $pagesize );

			if ( $skip >= $count ) {
				wppa_album_photos( $album, $photo, $owner, $moderate, true );

				return;
			}
		}

		// Display the pagelinks
		wppa_admin_pagination( $pagesize, $page, $count, $link, 'top' );

		// Horizon
		wppa_echo( '<div id="wppa-horizon"><hr></div>' );
		wppa_add_inline_script( 'wppa-admin', 'jQuery(document).ready(function(){wppaDragHorizon(document.getElementById("wppa-horizon"))});' );

		// Albun name if moderate
		static $modalbum;

		// Display all photos
		foreach ( $photos as $photo ) {

			// We may not use extract(), so we do something like it here manually, hence controlled.
			$id 			= $photo['id'];
			$timestamp 		= ( $photo['timestamp'] ? $photo['timestamp'] : '0' );
			$modified 		= $photo['modified'];
			$owner 			= $photo['owner'];
			$crypt 			= $photo['crypt'];
			$album 			= $photo['album'];
			$name 			= stripslashes( $photo['name'] );
			$description 	= stripslashes( $photo['description'] );
			$exifdtm 		= $photo['exifdtm'];
			$views 			= $photo['views'];
			$clicks 		= $photo['clicks'];
			$p_order 		= $photo['p_order'];
			$linktarget 	= $photo['linktarget'];
			$linkurl 		= $photo['linkurl'];
			$linktitle 		= stripslashes( $photo['linktitle'] );
			$alt 			= stripslashes( $photo['alt'] );
			$filename 		= $photo['filename'];
			$photox 		= wppa_get_photox( $id );
			$photoy 		= wppa_get_photoy( $id );
			$videox 		= wppa_get_videox( $id, 'admin' );
			$videoy 		= wppa_get_videoy( $id, 'admin' );
			$location 		= $photo['location'];
			$status 		= $photo['status'];
			$tags 			= trim( stripslashes( $photo['tags'] ), ',' );
			$stereo 		= $photo['stereo'];
			$panorama 		= $photo['panorama'];
			$angle 			= $photo['angle'];
			$magickstack 	= $photo['magickstack'];
			$scheduledel 	= $photo['scheduledel'];
			$ext 			= $photo['ext'];
			$sname 			= $photo['sname'];
			$dlcount 		= $photo['dlcount'];
			$thumblock 		= $photo['thumblock'];
			$duration 		= $photo['duration'];
			$indexdtm 		= $photo['indexdtm'];
			$usedby 		= $photo['usedby'] ? explode( ".", trim( $photo['usedby'], '. ' ) ) : array();
			$misc 			= $photo['misc'];

			// See if item is a multimedia item
			$is_multi 		= wppa_is_multi( $id );
			$is_photo		= wppa_is_photo( $id );
			$is_video 		= wppa_is_video( $id );			// returns array of extensions
			$b_is_video 	= empty( $is_video ) ? 0 : 1; 	// boolean
			$has_audio 		= wppa_has_audio( $id );		// returns array of extensions
			$b_has_audio 	= empty( $has_audio ) ? 0 : 1; 	// boolean
			$is_pdf 		= wppa_is_pdf( $id );
			$br 			= wppa_is_phone() ? '<br>' : ' ';
			$has_poster 	= wppa_has_poster( $id );

			if ( $is_pdf ) {
				$move 	= __( 'Move document', 'wp-photo-album-plus' );
				$delete = __( 'Delete document', 'wp-photo-album-plus' );
				$undel 	= __( 'Undelete document', 'wp-photo-album-plus' );
				$copy 	= __( 'Copy document', 'wp-photo-album-plus' );
			}
			elseif ( $is_video ) {
				$move 	= __( 'Move video', 'wp-photo-album-plus' );
				$delete = __( 'Delete video', 'wp-photo-album-plus' );
				$undel 	= __( 'Undelete video', 'wp-photo-album-plus' );
				$copy 	= __( 'Copy video', 'wp-photo-album-plus' );
			}
			else {
				$move 	= __( 'Move photo', 'wp-photo-album-plus' );
				$delete = __( 'Delete photo', 'wp-photo-album-plus' );
				$undel 	= __( 'Undelete photo', 'wp-photo-album-plus' );
				$copy 	= __( 'Copy photo', 'wp-photo-album-plus' );
			}

			// Various usefull vars
			$owner_editable = wppa_switch( 'photo_owner_change' ) && wppa_user_is_admin();
			if ( $album && $album > '0' ) {
				$order_by = wppa_get_album_item( $album, 'p_order_by' );
			}
			else {
				$order_by = wppa_opt( 'list_photos_by' );
			}
			switch ( $order_by ) {
				case '0':
					$temp = wppa_opt( 'list_photos_by' );
					$sortby_orderno = ( $temp == '-1' || $temp == '1' );
					break;
				case '-1':
				case '1':
					$sortby_orderno = true;
					break;
				default:
					$sortby_orderno = false;
			}
			$wms 	= array( 'toplft' => __( 'top - left' , 'wp-photo-album-plus' ), 'topcen' => __( 'top - center' , 'wp-photo-album-plus' ), 'toprht' => __( 'top - right' , 'wp-photo-album-plus' ),
							 'cenlft' => __( 'center - left' , 'wp-photo-album-plus' ), 'cencen' => __( 'center - center' , 'wp-photo-album-plus' ), 'cenrht' => __( 'center - right' , 'wp-photo-album-plus' ),
							 'botlft' => __( 'bottom - left' , 'wp-photo-album-plus' ), 'botcen' => __( 'bottom - center' , 'wp-photo-album-plus' ), 'botrht' => __( 'bottom - right' , 'wp-photo-album-plus' ), );

			// If ImageMagick is enabled...
			// Fake 'for social media' to use the local file here, not cloudinary.
			// Files from cloudinary do not reload, even with ?ver=...
			if ( wppa_can_admin_magick( $id ) ) {
				wppa( 'for_sm', true );
			}
			$src = wppa_get_thumb_url( $id );//, false );
			$big = wppa_get_photo_url( $id );//, false );
			if ( wppa_can_admin_magick( $id ) ) {
				wppa( 'for_sm', false );
			}

			// Album for moderate
			if ( $modalbum != $album && $album && ! wppa_get( 'just-edit' ) && wppa_get( 'edit-id' ) != 'trash' ) {
				$modalbum = $album;
			}

			// May user change status?
			if ( ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) ) ) {
				if ( wppa_switch( 'ext_status_restricted' ) && ! wppa_user_is_admin() ) {
					$may_edit_status = false;
				}
				else {
					$may_edit_status = true;
				}
			}
			else {
				$may_edit_status = false;
			}

			// May user change planned delete item?
			$may_change_delete = wppa_user_is_admin() || current_user_can( 'wppa_moderate' );

			// May user change photo sequence no?
			$may_change_porder = wppa_user_is_admin() || ! wppa_switch( 'porder_restricted' );

			// Is there exif data?
			$exifs = $quick ? array() : $wpdb->get_results( $wpdb->prepare( 	"SELECT * FROM $wpdb->wppa_exif
																				 WHERE photo = %s
																				 ORDER BY tag, id", $id ), ARRAY_A );

			// Is there iptc data?
			$iptcs = $quick ? array() : $wpdb->get_results( $wpdb->prepare( 	"SELECT * FROM $wpdb->wppa_iptc
																				 WHERE photo = %s
																				 ORDER BY tag, id", $id ), ARRAY_A );

			// Are there comments?
			$comments = $quick ? array() : $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments
																				WHERE photo = %s
																				ORDER BY timestamp DESC ", $id ), ARRAY_A );

			// Anchor for scroll to
			wppa_echo( '<a id="photo_' . $id . '"></a>' );

			/* Start the actual display area for the photo settings */
			{
				wppa_echo( '
				<div
					id="photoitem-' . $id . '"
					class="wppa-table-wrap photoitem"
					style="display:block;padding:20px;clear:both;"
					>' .

					// Photo specific nonce field
					'<input
						type="hidden"
						id="photo-nonce-' . $id . '"
						value="' . wp_create_nonce( 'wppa-nonce_' . $id ) . '"
					/>' );

					// Section 1: Preview thumbnail, photo name and stats
					{
					wppa_echo( '
					<div>' );
						if ( $is_video ) {
							reset( $is_video );
						//	$big = str_replace( 'xxx', current( $is_video ), $big );
							$big = wppa_strip_ext( $big ) . '.' . current( $is_video );
							wppa_echo( '
							<div style="float:left; margin-right: 20px;">
								<a
								href="' . esc_attr( $big ) . '"
								target="_blank"
								title="' . esc_attr( __( 'Preview fullsize video' , 'wp-photo-album-plus' ) ) . '"
								>' .
								wppa_get_video_html( array( 	'id' 		=> $id,
																'tagid' 	=> 'video-' . $id,
																'width' 	=> '160',
																'height' 	=> '160' * $videoy / $videox,
																'controls' 	=> false,
																'use_thumb' => true,
																'cursor' 	=> 'pointer',
													//			'margin_bottom' => '6',
															) ) . '
								</a>' );

								// Duratiuon
								if ( $is_video && $duration ) {
									$lbl = __( 'Duration', 'wp-photo-album-plus' );
									$duration = intval( $duration * 100 ) / 100;
									if ( $duration < 120.0 ) {
										$txt = sprintf( __( '%s seconds', 'wp-photo-album-plus' ) ,$duration );
									}
									else {
										$txt = sprintf( __( '%s minutes and %s seconds', 'wp-photo-album-plus' ),
														sprintf( '%d', floor( $duration / 60 ) ),
														sprintf( '%d', $duration % 60 ) );
									}
									wppa_echo( '<br>' . $lbl . ': ' . $txt . '' );
								}

							wppa_echo( '</div>' );
						}
						else {
							if ( $has_audio ) {
					//			$src = wppa_get_thumb_url( $id );
					//			$big = wppa_get_photo_url( $id );

								// If no duration stored, try to find it and store it
								if ( ! $duration ) {
									wppa_fix_audio_metadata( $id, 'photoadmin' );
									$duration = wppa_get_photo_item( $id, 'duration' );
								}
							}
							wppa_echo( '
							<div style="float:left; margin-right: 20px;">' );
							if ( $is_photo || $has_poster ) {
								wppa_echo( '
								<a
									id="thumba-' . $id . '"
									href="' . esc_attr( $big ) . '"
									target="_blank"
									title="' . esc_attr( __( 'Preview fullsize photo', 'wp-photo-album-plus' ) ) . '"
									>' );
							}
							wppa_echo( '
									<img
										id="thumburl-' . $id . '"' .
										( wppa_lazy() && $count > '1' ? ' data-' : ' ' ) . 'src="' . esc_url( $src ) . '"
										alt="' . esc_attr( $name ) . '"
										style="max-width:160px;vertical-align:middle;'.($has_audio?'':'margin-bottom:6px;').'"
									/>' );
							if ( $is_photo || $has_poster ) {
								wppa_echo( '</a>' );
							}

							if ( $has_audio ) {
								$audio = wppa_get_audio_html( array( 	'id' 		=> $id,
																		'tagid' 	=> 'audio-' . $id,
																		'width' 	=> '160',
																		'height' 	=> '20',
																		'controls' 	=> true
																	) );

								wppa_echo( '
								<br>' .
								( $audio ? $audio :
								'<span style="color:red">' .
									esc_html__( 'Audio disabled', 'wp-photo-album-plus' ) .
								'</span>' ) );

								if ( $duration ) {
									$lbl = __( 'Duration', 'wp-photo-album-plus' );
									$duration = intval( $duration * 100 ) / 100;
									if ( $duration < 120.0 ) {
										$txt = sprintf( __( '%s seconds', 'wp-photo-album-plus' ) ,$duration );
									}
									else {
										$txt = sprintf( __( '%s minutes and %s seconds', 'wp-photo-album-plus' ),
														sprintf( '%d', floor( $duration / 60 ) ),
														sprintf( '%d', $duration % 60 ) );
									}
									wppa_echo( '<br>' . $lbl . ': ' . $txt . '' );
								}
							}

							wppa_echo( '</div>' );
						}

						// Name
						wppa_echo( '
						<fieldset class="wppa-fieldset" style="float:left;margin-right:12px;min-height:72px;margin-bottom:6px;">
							<legend class="wppa-legend">' .
								__( 'Name', 'wp-photo-album-plus' ) . '
							</legend>
							<div  style="display:inline-block;padding-left:12px;">
							<label>' .
									__( 'Name slug', 'wp-photo-album-plus' ) . ': ' . $sname . '
								</label><br>
							<input
								type="text"
								style="width:100%;height:32px;"
								id="pname-' . $id . '"
								onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'name\', this.value)"
								value="' . esc_attr( stripslashes( $name ) ) . '"
							/>
							</div>
						</fieldset>' );

						// Status
						if ( $may_edit_status ) {
							$sel = ' selected';
							if ( wppa_switch( 'ext_status_restricted' ) && ! wppa_user_is_admin() ) {
								$dis = ' disabled';
							}
							else {
								$dis = '';
							}
							wppa_echo( '
							<fieldset class="wppa-fieldset" style="float:left;margin-right:12px;min-height:72px;margin-bottom:6px;">
								<legend class="wppa-legend">' .
									__( 'Status', 'wp-photo-album-plus' ) . '
								</legend>
								<div  style="display:inline-block;padding-left:12px;">
								<label>
									&nbsp;
								</label><br>

								<select
									style="vertical-align:inherit;height:32px;"
									id="status-' . $id . '"
									onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'status\', this.value); wppaPhotoStatusChange( ' . $id . ' )">
									<option value="pending"' .	( $status == 'pending' ? $sel : '' ) . ' >' .
										__( 'Pending', 'wp-photo-album-plus' ) . '
									</option>
									<option value="publish"' . ( $status =='publish' ? $sel : '' ) . ' >' .
										__( 'Publish', 'wp-photo-album-plus' ) . '
									</option>
									<option value="featured"' . ( $status == 'featured' ? $sel : '' ) . $dis . ' >' .
										__( 'Featured', 'wp-photo-album-plus' ) . '
									</option>
									<option value="gold"' . ( $status == 'gold' ? $sel : '' ) . $dis . ' >' .
										__( 'Gold', 'wp-photo-album-plus' ) . '
									</option>
									<option value="silver"' . ( $status == 'silver' ? $sel : '' ) . $dis . ' >' .
										__( 'Silver', 'wp-photo-album-plus' ) . '
									</option>
									<option value="bronze"' . ( $status == 'bronze' ? $sel : '' ) . $dis . ' >' .
										__( 'Bronze', 'wp-photo-album-plus' ) . '
									</option>
									<option value="scheduled"' . ( $status == 'scheduled' ? $sel : '' ) . $dis . ' >' .
										__( 'Scheduled', 'wp-photo-album-plus' ) . '
									</option>
									<option value="private"' . ( $status == 'private' ? $sel : '' ) . $dis . ' >' .
										__( 'Private', 'wp-photo-album-plus' ) . '
									</option>
								</select>
								' .
								wppa_get_date_time_select_html( 'photo', $id, true ) . '
								</div>


							</fieldset>' );
						}
						else {

							if ( $status == 'pending' ) $s = __( 'Pending', 'wp-photo-album-plus' );
							elseif ( $status == 'publish' ) $s = __( 'Publish', 'wp-photo-album-plus' );
							elseif ( $status == 'featured' ) $s = __( 'Featured', 'wp-photo-album-plus' );
							elseif ( $status == 'gold' ) $s = __( 'Gold', 'wp-photo-album-plus' );
							elseif ( $status == 'silver' ) $s = __( 'Silver', 'wp-photo-album-plus' );
							elseif ( $status == 'bronze' ) $s = __( 'Bronze', 'wp-photo-album-plus' );
							elseif ( $status == 'scheduled' ) $s = __( 'Scheduled', 'wp-photo-album-plus' );
							elseif ( $status == 'private' ) $s = __( 'Private', 'wp-photo-album-plus' );
							wppa_echo( '
							<fieldset class="wppa-fieldset" style="float:left;margin-right:12px;min-height:72px;margin-bottom:6px;">
								<legend class="wppa-legend">' .
									__( 'Status', 'wp-photo-album-plus' ) . '
								</legend>
								<div  style="display:inline-block;padding-left:12px;">
									<label>
										&nbsp;
									</label><br>' .
									$s . '
								</div>
							</fieldset>' );
						}

						// Get the statistics
						wppa_echo( '
							<fieldset class="wppa-fieldset" style="float:left;margin-right:12px;min-height:72px;margin-bottom:6px;">
								<legend class="wppa-legend">' .
									__( 'Statistics', 'wp-photo-album-plus' ) . '
								</legend>' );

						{
							$th = array();
							$td = array();

							// Clicks
							if ( wppa_switch( 'track_clickcounts' ) ) {
								$th[] = __( 'Clicks', 'wp-photo-album-plus' );
								$td[] = strval( intval( $clicks ) );
							}

							// Views
							if ( wppa_switch( 'track_viewcounts' ) ) {
								$th[] = __( 'Views', 'wp-photo-album-plus' );
								$td[] = strval( intval( $views ) );
							}

							// Downloads. Only photos are downloadable
							if ( ! wppa_is_multi( $id ) ) {
								$th[] = __( 'Downloads', 'wp-photo-album-plus' );
								$td[] = strval( intval( $dlcount ) );
							}

							// Rating
							$entries = wppa_get_rating_count_by_id( $id );
							if ( $entries ) {
								if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {
									$label = __( 'Likes', 'wp-photo-album-plus' );
									$value = strval( intval( $entries ) );
								}
								else {
									$label = __( 'Rating (#, avg)', 'wp-photo-album-plus' );
									$count = wppa_get_rating_count_by_id( $id );
									$avg   = wppa_get_rating_by_id( $id, 'nolabel' );
									$value = $count . ', ' . $avg;
								}
							}
							else {
								$label = __( 'Rating', 'wp-photo-album-plus' );
								$value = __( 'None', 'wp-photo-album-plus' );
							}

							$th[] = $label;
							$td[] = $value;

							// Dislikes
							$dislikes = wppa_dislike_get( $id );
							if ( $dislikes ) {
								$th[] = __( 'Dislikes', 'wp-photo-album-plus' );
								$td[] = strval( intval( $dislikes ) );
							}

							// Pending votes
							$pending = wppa_pendrat_get( $id );
							if ( $pending ) {
								$th[] = __( 'Pending votes', 'wp-photo-album-plus' );
								$td[] = strval( intval( $pending ) );
							}
						}

						// Display the stats
						$c = count( $th );
						for ( $i = 0; $i < $c; $i++ ) {
							wppa_echo( '
							<div style="display:inline-block;padding-left:12px;">
								<label>' .
									$th[$i] . '
								</label><br>
								<div class="wppa-ldi">' .
									$td[$i] . '
								</div>
							</div>' );
						}

						wppa_echo( '</fieldset>' );

						// Update status field
						{
						wppa_echo( '
						<fieldset class="wppa-fieldset" style="float:left;margin-right:12px;min-height:72px;margin-bottom:6px;">
							<legend class="wppa-legend">' .
								__( 'Remark', 'wp-photo-album-plus' ) . '
							</legend>
							<div style="display:inline-block;padding-left:12px;">
								<label>
									&nbsp;
								</label><br>
								<span
									id="remark-' . $id . '"
									style="font-weight:bold;color:#00AA00;font-size:1.5em;">' .
									( $is_video ? sprintf( __( 'Video %s is not modified yet', 'wp-photo-album-plus' ), $id ) :
												  sprintf( __( 'Photo %s is not modified yet', 'wp-photo-album-plus' ), $id ) ) . '
								</span>
							</div>
						</fieldset>' );
						}

					wppa_echo( '
					</div>' );
					}

					// End Thumbnail area

					// Start details area
					$details = __( 'Photo Details', 'wp-photo-album-plus' );
					if ( wppa_is_video( $id ) ) $details = __( 'Video Details', 'wp-photo-album-plus' );
					if ( wppa_is_pdf( $id ) ) $details = __( 'Document Details', 'wp-photo-album-plus' );
					if ( wppa_has_audio( $id ) ) $details = __( 'Audio Details', 'wp-photo-album-plus' );

					// Open the details area
					echo( '
						<details id="wppa-toplevel-details-'.$id.'" class="wppa-toplevel-details" ' . ( $timestamp > time() - 3600 ? 'open' : '' ) . '>
							<summary
								class="toplevel wppa-summary-sublevel"
								onclick="setTimeout(function(){jQuery(window).trigger(\'resize\');},200);"
								> ' .
								$details . '
							</summary>' );

					// The tabs
					$ptitle = __( 'Photo', 'wp-photo-album-plus' );
					if ( wppa_is_video( $id ) ) $ptitle = __( 'Video', 'wp-photo-album-plus' );
					if ( wppa_is_pdf( $id ) ) $ptitle = __( 'Document', 'wp-photo-album-plus' );
					if ( $has_audio && ! $has_poster ) $ptitle = __( 'Audio', 'wp-photo-album-plus' );
					$result = '
					<div id="tabs" style="margin-bottom:23px">
						<ul class="widefat wppa-setting-tabs">
							<li class="wppa-photoadmin-tab-'.$id.' active" onclick="wppaChangePhotoAdminTab(this,\'#photogeneral-'.$id.'\','.$id.');">' . $ptitle . '</li>
							<li class="wppa-photoadmin-tab-'.$id.'" onclick="wppaChangePhotoAdminTab(this,\'#photofiles-'.$id.'\','.$id.');">' . __( 'Files', 'wp-photo-album-plus' ) . '</li>';
							if ( wppa_can_admin_magick( $id ) && ! $quick && ( $is_photo || $has_poster ) ) {
								$result .= '
								<li
									class="wppa-photoadmin-tab-'.$id.'"
									onclick="wppaChangePhotoAdminTab(this,\'#photomagic-'.$id.'\','.$id.');wppaInitMagick(\''.$id.'\');">' .
									__( 'Image', 'wp-photo-album-plus' ) . '
								</li>';
							}
							if ( ! $quick && ! empty( $exifs ) ) {
								$result .= '
								<li
									class="wppa-photoadmin-tab-'.$id.'"
									onclick="wppaChangePhotoAdminTab(this,\'#photoexif-'.$id.'\','.$id.');">' .
									__( 'EXIF', 'wp-photo-album-plus' ) . '
								</li>';
							}
							if ( ! $quick && ! empty( $iptcs ) ) {
								$result .= '
								<li
									class="wppa-photoadmin-tab-'.$id.'"
									onclick="wppaChangePhotoAdminTab(this,\'#photoiptc-'.$id.'\','.$id.');">' .
									__( 'IPTC', 'wp-photo-album-plus' ) . '
								</li>';
							}
							if ( ! $quick && ! empty( $comments ) ) {
								$result .= '
								<li
									class="wppa-photoadmin-tab-'.$id.'"
									onclick="wppaChangePhotoAdminTab(this,\'#photocomment-'.$id.'\','.$id.');">' .
									__( 'Comments', 'wp-photo-album-plus' ) . '
								</li>';
							}
							if ( ! $quick && ! empty( $usedby ) ) {
								$result .= '
								<li
									class="wppa-photoadmin-tab-'.$id.'"
									onclick="wppaChangePhotoAdminTab(this,\'#photousedby-'.$id.'\','.$id.');">' .
									__( 'Used by', 'wp-photo-album-plus' ) . '
								</li>';
							}
							$result .= '
						</ul>
						<div style="clear:both"></div>
					</div>';
					wppa_echo( $result );

					// Tab 1 Photo general
					wppa_echo( '
					<div
						id="photogeneral-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent-'.$id.'"
						style="position:relative;padding-bottom:12px;padding-left:12px;"
						>' );

						{
						wppa_echo( '
						<div class="wppa-flex">' );

							wppa_echo( '
							<fieldset class="wppa-fieldset" style="width:100%">
								<legend class="wppa-legend">' .
									__( 'Unchangeable items', 'wp-photo-album-plus' ) . '
								</legend>' );

								// ID
								wppa_echo( '
								<div class="left">
									<label>' .
										__( 'Id', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										strval( intval( $id ) ) . '
									</div>
								</div>' .

								// Crypt
								'<div class="left">
									<label>' .
										__( 'Encrypted', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										htmlspecialchars( $crypt ) . '
									</div>
								</div>' .

								// Filename
								'<div class="left">
									<label>' .
										__( 'Filename', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										htmlspecialchars( $filename ) . '
									</div>
								</div>' .

								// Upload
								'<div class="left">
									<label>' .
										__( 'Upload', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										htmlspecialchars( wppa_local_date( '', $timestamp ) ) . '
									</div>
								</div>' .

								// Index
								'<div class="left">
									<label>' .
										__( 'Indexed', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										( $indexdtm ? htmlspecialchars( wppa_local_date( '', $indexdtm ) ) : __( 'Needs re-indexing', 'wp-photo-album-plus' ) ) . '
									</div>
								</div>' );

								// Owner
								if ( ! $owner_editable ) {

									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Owner', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											wppa_get_owner_display( $owner ) . '
										</div>
									</div>' );
								}

								$deleted = false;
								if ( $album <= '-9' ) {
									$album = - ( $album + '9' );
									$deleted = true;
								}

								// Album. Show album only when it is not evident
								if ( ! $is_album ) {
									wppa_echo( '
									<div class="left" style="margin-right: 4px;">
										<label>' .
											__( 'Album', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											htmlspecialchars( wppa_get_album_name( $album ) ) . '(' . strval( intval( $album ) ) . ')
										</div>
									</div>' );
								}

								// Modified
								{
									$txt = wppa_local_date( '', $modified );
									if ( $deleted ) $txt = __( 'Trashed', 'wp-photo-album-plus' );
									if ( $timestamp >= $modified ) $txt = __( 'Not modified', 'wp-photo-album-plus' );
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Modified', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											$txt . '
										</div>
									</div>' );
								}

								// Exif
								if ( ! wppa_user_is_admin() ) {
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'EXIF Date', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											esc_html( $exifdtm ) . '
										</div>
									</div>'	);
								}

								// Location
								if ( $photo['location'] && ! wppa_switch( 'geo_edit' ) ) {
									$loc = $location ? $location : '///';
									$geo = explode( '/', $loc );
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Location' , 'wp-photo-album-plus' ) . '
										</label>
										<div class="wppa-ldi">' .
											esc_html( $geo['0'].' '.$geo['1'].'. ' ) . '
										</div>
									</div>' );
								}

								// P_order
								if ( $sortby_orderno && ! $may_change_porder ) {
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Photo sequence #', 'wp-photo-album-plus' ) . '
										</label>
										<div class="wppa-ldi">' .
											$p_order . '
										</div>
									</div>' );
								}

								// Schedule for delete
								if ( ! wppa_user_is_admin() && $owner != wppa_get_user() ) {
									if ( $scheduledel ) {
										wppa_echo( '
										<div class="left">
											<label>' .
												__( 'Delete at', 'wp-photo-album-plus' ) . '
											</label>
											<div class="wppa-ldi">' .
												wppa_get_date_time_select_html( 'delphoto', $id, false ) . '
											</div>
										</div>' );
									}
								}

								// Shortcode
								if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
									wppa_echo( '
									<div class="left">
										<label>
											<span style="float:left">' .
												__( 'Single image shortcode example', 'wp-photo-album-plus' ) . '
											</span>
										</label><br>
										<div class="wppa-ldi">
											[wppa type="photo" photo="' . $id .'"]
										</div>
										<small>' .
											sprintf( 	__( 'See %s The documentation %s for more shortcode options.', 'wp-photo-album-plus' ),
														'<a href="https://wppa.nl/shortcode-reference/" target="_blank">',
														'</a>'
													) . '
										</small>
									</div>' );
								}

							// End fieldset
							wppa_echo( '</fieldset>' );

						// End flex div
						wppa_echo( '</div>' );
						}

						// Tab 1 Section 2
						wppa_echo( '
						<div class="wppa-flex">' );

							wppa_echo( '
							<fieldset class="wppa-fieldset" style="width:100%">
								<legend class="wppa-legend">' .
									__( 'Changeable items', 'wp-photo-album-plus' ) . '
								</legend>' );

							// Owner
							if ( $owner_editable ) {
								if ( wppa_get_user_count() > wppa_opt( 'max_users' ) ) {
									wppa_echo( '
									<div class="left">
										<label
											for="owner-' . $id . '">' .
											__( 'Owned by', 'wp-photo-album-plus' ) . '
										</label><br>
										<input
											style="max-width:150px"
											id="owner-' . $id . '"
											type="text"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'owner\', this.value )"
											value="' . esc_attr( $owner ) . '"
										/>
									</div>' );
								}
								else {
									wppa_echo( '
									<div class="left">
										<label
											for="owner-' . $id . '">' .
											__( 'Owned by', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="owner-' . $id . '"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'owner\', this.value )"
											style="max-width:200px;"
											>' .
											wppa_get_user_select( $owner ) . '
										</select>
									</div>' );
								}
							}

							// Exif date-time
							if ( wppa_user_is_admin() ) { // Admin may edit exif date
								wppa_echo( '
								<div class="left">
									<label
										for="exifdtm-' . $id . '">' .
										__( 'EXIF Date', 'wp-photo-album-plus' ) . '
									</label><br>
									<input
										id="exifdtm-' . $id . '"
										type="text"
										style="width:150px"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'exifdtm\', this.value)"
										value="' . esc_attr( $exifdtm ) . '"
									/>
								</div>' );
							}

							// Location
							if ( wppa_switch( 'geo_edit' ) ) {
								$loc = $location ? $location : '///';
								$geo = explode( '/', $loc );
								wppa_echo( '
								<div class="left">
									<label
										for="lat-' . $id . '">' .
										__( 'Location Lat' , 'wp-photo-album-plus' ) . esc_html( $geo['0'] ) . '
									</label><br>
									<input
										id="lat-' . $id . '"
										type="text"
										style="width:100px"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'lat\', this.value)"
										value="' . esc_attr( $geo['2'] ) . '"
									/>
								</div>
								<div class="left">
									<label
										for="lon-' . $id . '">' .
										__( 'Location Lon' , 'wp-photo-album-plus' ) . esc_html( $geo['1'] ) . '
									</label><br>
									<input
										id="lon-' . $id . '"
										type="text"
										style="width:100px"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'lon\', this.value)"
										value="' . esc_attr( $geo['3'] ) . '"
									/>
								</div>' );
							}

							// Changeable p_order
							if ( $sortby_orderno && $may_change_porder ) {
								wppa_echo( '
								<div class="left">
									<label
										for="porder-' . $id . '">' .
										__( 'Photo sequence #', 'wp-photo-album-plus' ) . '
									</label><br>
									<input
										type="text"
										id="porder-' . $id . '"
										value="' . esc_attr( $p_order ) . '"
										style="width:100px"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'p_order\', this.value)"
									/>
								</div>' );
							}

							// Stereo
							if ( wppa_switch( 'enable_stereo' ) && ! $is_multi ) {
								wppa_echo( '
								<div class="left" style="max-width:250px;">
									<label
										for="stereo-' . $id . '">' .
										__( 'Stereophoto', 'wp-photo-album-plus' ) . '
									</label><br>
									<select
										id="stereo-' . $id . '"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'stereo\', this.value, true)"
										>
										<option value="0"' . ( $stereo == '0' ? ' selected' : '' ) . '>' .
											__( 'No stereo image', 'wp-photo-album-plus' ) . '
										</option>
										<option value="1"' . ( $stereo == '1' ? ' selected' : '' ) . '>' .
											__( 'Left - right stereo image', 'wp-photo-album-plus' ) . '
										</option>
										<option value="-1"' . ( $stereo == '-1' ? ' selected' : '' ) . '>' .
											__( 'Right - left stereo image', 'wp-photo-album-plus' ) . '
										</option>
									</select>
								</div>' );
							}

							// Panorama
							if ( wppa_switch( 'enable_panorama' ) && ! $b_is_video ) {
								$can_panorama = $photoy && $photox / $photoy >= 1.999;
								if ( $can_panorama ) {
									wppa_echo( '
									<div class="left">
										<label
											for="panorama-' . $id . '">' .
											__( 'Panorama', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="panorama-' . $id . '"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'panorama\', this.value, true )">
											<option value="0"' . ( $panorama == '0' ? ' selected' : '' ) . '>' . __( '- none -', 'wp-photo-album-plus' ) . '</option>
											<option value="1"' . ( $panorama == '1' ? ' selected' : '' ) . '>' . __( '360&deg; Spheric', 'wp-photo-album-plus' ) . '</option>
											<option value="2"' . ( $panorama == '2' ? ' selected' : '' ) . '>' . __( 'Non 360&deg; Flat', 'wp-photo-album-plus' ) . '</option>
										</select>
									</div>' );

									$source_file = wppa_get_source_path( $id );
									if ( wppa_is_file( $source_file ) ) {
										$source_file_sizes = wppa_getimagesize( $source_file );

										if ( $panorama == '1' && $source_file_sizes[0] / $source_file_sizes[1] > 2.001 ) {
											$t = array( 120, 150, 180, 210, 240, 270, 300, 330, 340, 350, 360, 370, 380, 390, 400, 410, 420, 430, 440, 450 );
											wppa_echo( '
											<div class="left">
												<label
													for="make360-' . $id . '">' .
													__( 'Make 360 from', 'wp-photo-album-plus' ) . '
												</label><br>
												<select
													onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'make360\', this.value, true)"
													id="make360-' . $id . '"
													title="' . esc_attr( __( 'Select the closest angle you originally made the panorama', 'wp-photo-album-plus' ) ) . '">
													<option value="">'.__('Select', 'wp-photo-album-plus' ).'</option>' );
													foreach( $t as $v ) {
														wppa_echo( '<option value="'.$v.'" '.($v==$angle?'selected':'').'>'.$v.'&deg;</option>' );
													}
													wppa_echo( '
													<option value="0">' . __( 'Undo', 'wp-photo-album-plus' ) . '</option>
												</select>
											</div>' );
										}
									}
								}
							}

							// Watermark
							if ( wppa_switch( 'watermark_on' ) ) {

								// Get the current watermark file settings
								$temp 	= wppa_get_water_file_and_pos( $id );
								$wmfile = isset( $temp['file'] ) ? $temp['file'] : '';
								$wmpos 	= isset( $temp['pos'] ) && isset ( $wms[$temp['pos']] ) ? $wms[$temp['pos']] : '';

								$user = wppa_get_user();
								$has_source = wppa_is_file( wppa_get_source_path( $id ) );
								$can_remove = ( wppa_opt( 'watermark_file' ) == '--- none ---' ) ? '1' : '0';
								if ( wppa_switch( 'watermark_user' ) || current_user_can( 'wppa_settings' ) ) {
									wppa_echo( '
									<div class="left" style="max-width:250px;">
										<label
											for="wmfsel_' . $id . '">' .
											__( 'Watermark', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="wmfsel_' . $id . '"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'wppa_watermark_file_' . $user . '\', this.value)"
											>' .
											wppa_watermark_file_select( 'user', $album ) . '
										</select>
									</div>
									<div class="left">
										<label
											for="wmpsel_' . $id . '">' .
											__( 'Position', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="wmpsel_' . $id . '"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'wppa_watermark_pos_' . $user . '\', this.value)"
											>' .
											wppa_watermark_pos_select( 'user', $album ) . '
										</select>
									</div>
									<div class="left">
										<label>
											&nbsp;
										</label><br>
										<input
											type="button"
											class="button wppa-admin-button"
											value="' . esc_attr( __( 'Apply watermark', 'wp-photo-album-plus' ) ) . '"
											onclick="wppaTryWatermark( ' . $id . ', ' . $has_source . ', ' . $can_remove . ' )"
										/>
										<img
											id="wppa-water-spin-' . $id . '"
											src="' . wppa_get_imgdir() . 'spinner.gif"
											alt="Spin"
											style="visibility:hidden"
										/>
									</div>' );
								}
							}

							// Schedule for delete
							if ( wppa_user_is_admin() || $owner == wppa_get_user() ) {
								if ( $may_change_delete ) {
									wppa_echo( '
									<div class="left" style="max-width: 500px;">
										<label
											for="scheduledel-' . $id . '">' .
											__( 'Delete at', 'wp-photo-album-plus' ) . '
										</label><br>
										<input
											type="checkbox"
											id="scheduledel-' . $id . '"' .
											( $scheduledel ? ' checked' : '' ) .
											( $may_change_delete ? '' : ' disabled' ) . '
											onchange="wppaTryScheduledel( ' . $id . ' )"
										/> ' .
										wppa_get_date_time_select_html( 'delphoto', $id, true ) . '
									</div>' );
								}
							}

						wppa_echo( '<div id="psdesc-' . $id . '" class="description clear" style="margin-top:6px;display:none">' .
										__( 'Note: Featured photos should have a descriptive name, a name a search engine will look for!', 'wp-photo-album-plus' ) . '
									</div>' );

						wppa_echo( '</fieldset>' );

					// End Tab1 Section 2
					wppa_echo( '</div>' );

					wppa_echo( '
					<!-- Section 3, Actions -->
					<div class="wppa-flex">' );

						wppa_echo( '
						<fieldset class="wppa-fieldset" style="width:100%">
							<legend class="wppa-legend">' .
								__( 'Actions', 'wp-photo-album-plus' ) . '
							</legend>' );

						// Move/copy
						if ( ! $quick ) {

							$max = wppa_opt( 'photo_admin_max_albums' );
							if ( ! $max || wppa_get_total_album_count() < $max ) {

								// If not done yet, get the album options html with the current album excluded
								if ( ! isset( $album_select[$album] ) ) {
									$album_select[$album] = wppa_album_select_a( array( 	'checkaccess' 		=> true,
																							'path' 				=> true,
																							'exclude' 			=> $album,
																							'selected' 			=> '0',
																							'addpleaseselect' 	=> true,
																							'sort' 				=> true,
																						)
																				);
								}

								wppa_echo( '
								<div class="left" style="max-width:400px">
									<label>' .
										__( 'Target album for copy/move', 'wp-photo-album-plus' ) . '
									</label><br>
									<select
										id="target-' . $id . '"
										style="max-width:250px">' .
										$album_select[$album] . '
									</select>
								</div>' );
							}
							else {
								wppa_echo( '
								<div class="left">
									<label>' .
										__( 'Target album for copy/move', 'wp-photo-album-plus' ) . '
									</label><br>
									<input
										id="target-' . $id . '"
										type="number"
										style="height:20px"
										placeholder="' . __( 'Album id', 'wp-photo-album-plus' ) . '"
									/>
								</div>' );
							}

							wppa_echo( '
							<div class="left" style="max-width:500px">
								<label>
									&nbsp;
								</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="wppaTryMove( ' . $id . ', ' . $b_is_video . ' )"
									value="' . esc_attr( $move ) . '"
								/>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="wppaTryCopy( ' . $id . ', ' . $b_is_video . ' )"
									value="' . esc_attr( $copy ) . '"
								/>
							</div>' );
						}

						// Rotate
						if ( ! $b_is_video ) {
							if ( ! wppa_can_admin_magick( $id ) ) {
								wppa_echo( '
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryRotLeft( ' . $id . ' )"
										value="' . esc_attr( __( 'Rotate left', 'wp-photo-album-plus' ) ) . '"
									/>
								</div>
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryRot180( ' . $id . ' )"
										value="' . esc_attr( __( 'Rotate 180&deg;', 'wp-photo-album-plus' ) ) . '"
									/>
								</div>
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryRotRight( ' . $id . ' )"
										value="' . esc_attr( __( 'Rotate right', 'wp-photo-album-plus' ) ) . '"
									/>
								</div>
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryFlip( ' . $id . ' )"
										value="' . esc_attr( __( 'Flip', 'wp-photo-album-plus' ) ) . '&thinsp;&#8212;"
									/>
								</div>
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryFlop( ' . $id . ' )"
										value="' . esc_attr( __( 'Flip', 'wp-photo-album-plus' ) ) . ' |"
									/>
								</div>' );
							}
						}

						// Delete
						if ( wppa_user_is_admin() || wppa_get_photo_item( $id, 'owner' ) == wppa_get_user() ) {
							if ( ! wppa( 'front_edit' ) ) {
								wppa_echo( '
								<div class="left">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										style="color:' . ( $deleted ? 'green' : 'red' ) . '"
										onclick="wppaTry' . ( $deleted ? 'Undelete' : 'Delete' ) . '( ' . $id . ', ' . $b_is_video . ' )"' .
										( $deleted ?
										' value="' . esc_attr( $undel ) .'"' :
										' value="' . esc_attr( $delete ) . '"' ) . '
									/>
								</div>' );

								if ( $deleted ) {
									wppa_echo( '
									<div class="left">
										<label>
											&nbsp;
										</label><br>
										<input
											type="button"
											class="wppa-admin-button button"
											style="color:red"
											onclick="wppaTryDelete( ' . $id . ', ' . $b_is_video . ', true )"
											value="' . esc_attr( __( 'Remove permanently', 'wp-photo-album-plus' ) ) . '"
										/>
									</div>' );
								}
							}
						}

						// Re-upload
						if ( wppa_user_is_admin() || ! wppa_switch( 'reup_is_restricted' ) ) {
							wppa_echo( '
							<div class="left" style="max-width:500px;">
								<label>
									&nbsp;
								</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="jQuery( \'#re-up-' . $id . '\' ).css( \'display\', \'inline-block\' )"
									value="' . esc_attr( __( 'Re-upload file', 'wp-photo-album-plus' ) ) . '"
								/>

								<div id="re-up-' . $id . '" style="display:none">
									<form
										id="wppa-re-up-form-' . $id . '"
										onsubmit="wppaReUpload( event, ' . $id . ', \'' . $filename . '\' )"
										>
										<input
											type="file"
											id="wppa-re-up-file-' . $id . '"
										/>
										<input
											type="submit"
											class="wppa-admin-button button"
											id="wppa-re-up-butn-' . $id . '"
											value="' . esc_attr( __( 'Upload', 'wp-photo-album-plus' ) ) . '"
										/>
									</form>
								</div>
							</div>' );
						}

						// If pdf and imagic show convert to album button
						if ( ! $quick && wppa_is_pdf( $id ) && wppa_can_magick() ) {

							// Get the conversion parms
							$cnvparms = wppa_get_pdf_conv_parms( $id );

							if ( $cnvparms['pagesdone'] > 1 || wppa_is_pdf_multiple( $id ) ) {
								wppa_echo( '
								<div class="left" style="max-width:500px;">
									<label>' .
										( $cnvparms['running'] ? __( 'The conversion process is running', 'wp-photo-album-plus' ) . ' ' : '' ) .
										( $cnvparms['crashed'] ? __( 'The background process is stopped', 'wp-photo-album-plus' ) . ' ' : '' ) .
										( $cnvparms['pagesdone'] > 1 ? sprintf( __( 'There are %d pages converted', 'wp-photo-album-plus' ), $cnvparms['pagesdone'] + 1 ) : '' ) .
									'</label><br>' );
									if ( $cnvparms['running'] ) {
										wppa_echo( '
										<input
											type="button"
											class="wppa-admin-button button"
											onclick="wppaConvertToAlbum(' . $id . ', jQuery(\'#page-method-' . $id . '\').val(), false, true);jQuery(this).prop(\'disabled\',true);"
											value="' . __( 'Stop converting', 'wp-photo-album-plus' ) . '"
										/>' );
									}
									else {
										wppa_echo( '
										<input
											type="button"
											class="wppa-admin-button button"
											onclick="wppaConvertToAlbum(' . $id . ', jQuery(\'#page-method-' . $id . '\').val(), false, false);jQuery(this).prop(\'disabled\',true);"
											value="' . esc_attr( $cnvparms['album'] ? __( 'Re-convert to album', 'wp-photo-album-plus' ) : __( 'Convert to album', 'wp-photo-album-plus' ) ) . '"
										/>' );
									}
									if ( $cnvparms['crashed'] ) {
										wppa_echo( '
										<input
											type="button"
											class="wppa-admin-button button"
											onclick="wppaConvertToAlbum(' . $id . ', jQuery(\'#page-method-' . $id . '\').val(), true, false);jQuery(this).prop(\'disabled\',true);"
											value="' . esc_attr( __( 'Continue converting', 'wp-photo-album-plus' ) ) . '"
										/>' );
									}
								wppa_echo( '
								</div>' );

								wppa_echo( '
								<div class="left" style="max-width:500px;">
									<label>' .
										__( 'Specify the page indicator for the <i>second</i> image', 'wp-photo-album-plus' ) . '
									</label><br>
									<select
										id="page-method-' . $id . '"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'misc\', this.value)"
										>
										<option value="0" ' . ( $cnvparms['pagtype'] == '0' ? 'selected' : '' ) . '>' . __( 'Page', 'wp-photo-album-plus' ) . ' 1</option>
										<option value="1" ' . ( $cnvparms['pagtype'] == '1' ? 'selected' : '' ) . '>' . __( 'Page', 'wp-photo-album-plus' ) . ' 2</option>
										<option value="10" ' . ( $cnvparms['pagtype'] == '10' ? 'selected' : '' ) . '>' . __( 'Cover - page 1', 'wp-photo-album-plus' ) . '</option>
										<option value="11" ' . ( $cnvparms['pagtype'] == '11' ? 'selected' : '' ) . '>' . __( 'Page 2-3', 'wp-photo-album-plus' ) . '</option>
										<option value="12" ' . ( $cnvparms['pagtype'] == '12' ? 'selected' : '' ) . '>' . __( 'Page 4-5', 'wp-photo-album-plus' ) . '</option>
									</select>
								</div>' );
							}
							else {
								wppa_echo( '
								<div
									id="pdftojpg-' . $id . '"
									class="left" style="max-width:500px;">
									<label>
										&nbsp;
									</label><br>
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaAjaxUpdatePhoto(' . $id . ', \'pdftojpg\',true);setTimeout(function(){jQuery(\'#pdftojpg-' . $id . '\').css(\'display\',\'none\')},1000);"
										value="' . __( 'Convert to jpg', 'wp-photo-album-plus' ) . '"
									/>
								</div>' );
							}
						}

						wppa_echo( '</fieldset>' );

					// End flex div
					wppa_echo( '</div>' );

					// Section 4
					wppa_echo( '
					<div class="wppa-flex-column">' );

						// Description editable
						if ( ! wppa_switch( 'desc_is_restricted' ) || wppa_user_is_admin() ) {
							wppa_echo( '
							<fieldset class="wppa-fieldset">
								<legend class="wppa-legend">' .
									__( 'Description', 'wp-photo-album-plus' ) . '
								</legend>' );

								// WP editor
								if ( wppa_switch( 'use_wp_editor' ) ) {
									$alfaid = wppa_alfa_id( $id );

									wp_editor( 	$description,
												'wppaphotodesc'.$alfaid,
												array( 	'wpautop' 		=> true,
														'media_buttons' => false,
														'textarea_rows' => '6',
														'tinymce' 		=> true
														)
											);

									wppa_echo( '
									<input
										type="button"
										style="clear:left"
										class="button button-secundary"
										value="' . esc_attr( __( 'Update Photo description', 'wp-photo-album-plus' ) ) . '"
										onclick="wppaAjaxUpdatePhoto( ' . $id . ', \'description\', wppaGetTinyMceContent(\'wppaphotodesc' . $alfaid . '\') )"
									/>
									<img
										id="wppa-photo-spin-' . $id . '"
										src="' . wppa_get_imgdir() . 'spinner.gif"
										style="visibility:hidden"
									/>' );
								}

								// Textarea
								else {
									wppa_echo( '
									<div>
										<textarea
											style="width:100%;height:60px"
											onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'description\', this.value)"
											>' .
											esc_textarea( stripslashes( $description ) ) . '
										</textarea>
									</div>' );
								}
							wppa_echo( '
							</fieldset>' );
						}

						// Description not editable
						else {
							wppa_echo( '
							<fieldset class="wppa-fieldset">
								<legend class="wppa-legend">' .
									__( 'Description', 'wp-photo-album-plus' ) . '
								</legend>
								<textarea
									style="width:100%;height:60px"
									readonly
									>' .
										esc_textarea( stripslashes( $description ) ) . '
								</textarea>
							</fieldset>' );
						}

						// Tags
						{
						$allowed = ! wppa_switch( 'newtags_is_restricted' ) || wppa_user_is_admin();
						wppa_echo( '
						<fieldset class="wppa-fieldset">
							<legend class="wppa-legend">' .
								__( 'Tags', 'wp-photo-album-plus' ) . '
							</legend>
							<input
								id="tags-' . $id . '"
								type="text"
								style="width:100%"
								onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'tags\', this.value)"
								value="' . esc_attr( $tags ) . '"' .
								( $allowed ? '' : ' readonly="readonly"' ) . '
							/>' );
							if ( $allowed ) {
								wppa_echo( '
								<br>
								<span class="description">' .
									__( 'Separate tags with commas.', 'wp-photo-album-plus' ) . '
								</span>' );
							}
							wppa_echo( '
							<select
								onchange="wppaAddTag( this.value, \'tags-' . $id . '\' ); wppaAjaxUpdatePhoto( ' . $id . ', \'tags\', document.getElementById( \'tags-' . $id . '\' ).value )">' );

								if ( wppa_switch( 'predef_tags_only' ) ) {
									$keys = explode( ',', trim( wppa_opt( 'minimum_tags' ) ) );
									$taglist = array();
									foreach ( $keys as $key ) {
										$taglist[$key]['tag'] = $key;
									}
								}
								else {
									$taglist = wppa_get_taglist();
								}

								if ( is_array( $taglist ) ) {
									wppa_echo( '<option value="">' . __( '- select to add -', 'wp-photo-album-plus' ) . '</option>' );
									foreach ( $taglist as $tag ) {
										wppa_echo( '<option value="' . esc_attr( $tag['tag'] ) . '">' . htmlspecialchars( $tag['tag'] ) . '</option>' );
									}
									if ( ! $allowed ) {
										wppa_echo( '<option value="-clear-">' . __( '- clear -', 'wp-photo-album-plus' ) . '</option>' );
									}
								}
								else {
									wppa_echo( '<option value="0">' . __( 'No tags yet', 'wp-photo-album-plus' ) . '</option>' );
								}
							wppa_echo( '
							</select>
						</fieldset>' );
						}

						// Custom
						if ( wppa_switch( 'custom_fields' ) ) {
							$custom = wppa_get_photo_item( $photo['id'], 'custom' );
							if ( $custom ) {
								$custom_data = wppa_unserialize( $custom );
							}
							else {
								$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
							}

							// Open fieldset
							wppa_echo( '
							<fieldset class="wppa-fieldset">
								<legend class="wppa-legend">' .
									__( 'Custom data fields', 'wp-photo-album-plus' ) . '
								</legend>' );

								foreach( array_keys( $custom_data ) as $key ) {
									if ( wppa_opt( 'custom_caption_' . $key ) ) {

										wppa_echo( '
										<div>
											<label>
												<span style="float:left">' .
													apply_filters( 'translate_text', wppa_opt( 'custom_caption_' . $key ) ) . ' (w#cc' . $key . ')
												</span>
												<span style="float:right">
													(w#cd' . $key . ')
												</span>
											</label><br>
											<input
												type="text"
												style="width:100%"
												id="custom_' . $key . '-' . $id . '"
												onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'custom_' . $key . '\', this.value)"
												value="' . esc_attr( stripslashes( $custom_data[$key] ) ) . '"
											/>
										<div>' );

									}
								}

							wppa_echo( '
							</fieldset>' );
						}

						// Links etc. Open fieldset
						wppa_echo( '
						<fieldset class="wppa-fieldset">
							<legend class="wppa-legend">' .
								__( 'Photo specific links', 'wp-photo-album-plus' ) . '
							</legend>' );

							// -- Auto Page --
							if ( wppa_switch( 'auto_page' ) && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) {
								$appl = get_permalink( wppa_get_the_auto_page( $id ) );
								wppa_echo( '
								<div>
									<label>
										<span style="float:left">' .
											__( 'Autopage Permalink', 'wp-photo-album-plus' ) . '
										</span>
									</label><br>
									<div class="wppa-ldi">
										<a href="' . $appl . '" target="_blank">' .
											$appl . '
										</a>
									</div>
								</div>' );
							}

							// -- Photo specific link --
							if ( ! wppa_switch( 'link_is_restricted' ) || wppa_user_is_admin() ) {

								// -- Link url --
								wppa_echo( '
								<div>
									<label>
										<span style="float:left">' .
											__( 'Photo specific link url', 'wp-photo-album-plus' ) . '
										</span>
									</label><br>
									<input
										type="text"
										id="pislink-' . $id . '"
										style="width:100%"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'linkurl\', this.value)"
										value="' . esc_attr( $linkurl ) . '"
									/>
									<select
										id="pistarget-' . $id . '"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'linktarget\', this.value)"
										>
										<option
											value="_self"' .
											( $linktarget == '_self' ? ' selected' : '' ) . '
											>' .
											__( 'Same tab', 'wp-photo-album-plus' ) . '
										</option>
										<option
											value="_blank"' .
											( $linktarget == '_blank' ? ' selected' : '' ) . '
											>' .
											__( 'New tab', 'wp-photo-album-plus' ) . '
										</option>
									</select>
									<input
										type="button"
										class="button wppa-admin-button"
										onclick="window.open( jQuery( \'#pislink-' . $id . '\' ).val(), jQuery( \'#pistarget-' . $id . '\' ).val() )"
										value="' . __( 'Tryit!', 'wp-photo-album-plus' ) . '"
									/>
								</div>' );

								// -- Link title --
								wppa_echo( '
								<div>
									<label>
										<span style="float:left">' .
											__( 'Photo specific link title', 'wp-photo-album-plus' ) . '
										</span>
									</label><br>
									<input
										type="text"
										style="width:100%"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'linktitle\', this.value)"
										value="' . esc_attr( $linktitle ) . '"
									/>' );

									if ( current_user_can( 'wppa_settings' ) ) {
										wppa_echo( '
										<br>
										<small>' .
											sprintf( __( 'If you want this link to be used, check \'PS Overrule\' checkbox in %s.' , 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'links' ) . ' II, III' ) . '
										</small>' );
									}
									wppa_echo( '
								</div>' );
							}

							// -- Custom ALT field --
							if ( wppa_opt( 'alt_type' ) == 'custom' ) {
								wppa_echo( '
								<div>
									<label>
										<span style="float:left">' .
											__( 'HTML Alt attribute' , 'wp-photo-album-plus' ) . '
										</span>
									</label><br>
									<input
										type="text"
										style="width:100%"
										onchange="wppaAjaxUpdatePhoto( ' . $id . ', \'alt\', this.value)"
										value="' . esc_attr( $alt ) . '"
									/>
								</div>' );
							}

						// End PS Links etc
						wppa_echo( '
						</fieldset>' );

					wppa_echo( '
					</div>' );

					// End Tab 1
					wppa_echo( '</div>' );

					// Tab 2 Files
					{
						wppa_echo( '
						<div
							id="photofiles-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding:10px;display:none"
							>' );

							wppa_echo( '
							<div class="wppa-flex">' );

							wppa_echo( '
							<fieldset class="wppa-fieldset">
								<legend class="wppa-legend">' .
									__( 'Available files', 'wp-photo-album-plus' ) . '
								</legend>' );
								{
								wppa_echo( '
								<table class="wppa-table">
									<thead>
										<td>' . __( 'Type', 'wp-photo-album-plus' ) . '</td>
										<td>' . __( 'Size', 'wp-photo-album-plus' ) . '</td>
										<td>' . __( 'Path', 'wp-photo-album-plus' ) . '</td>
										<td>' . __( 'Url', 'wp-photo-album-plus' ) . '</td>
									</thead>
									<tbody>' );

									// Video
									if ( $b_is_video ) {

										$formats 	= '';
										$paths 		= '';
										$urls 		= '';
										foreach ( $is_video as $fmt ) {
											$formats 	.= $fmt . ' (' . wppa_get_filesize( str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) ) . ')<br>';
											$paths 		.= str_replace( WPPA_UPLOAD_PATH, '.../wppa', str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) ) . '<br>';
											$url 		= str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) );
											$urls 		.= '<a href="'. $url .'" target="_blank">' . $url . '<br>';
										}

										wppa_echo( '
										<tr>
											<td>' .
												$formats . '
											</td>
											<td>' );

												// If video sizes are correct and retieved from the file,
												// and possible rotation has been into account, field needs not to be editable
												if ( $photo['videox'] == $videox && $photo['videoy'] == $videoy ) {
													wppa_echo( sprintf( __( 'Width: %d pixels, height: %d pixels', 'wp-photo-album-plus' ), $videox, $videoy ) );
												}
												else {
													wppa_echo( '
													<input
														type="text"
														style="width:50px;margin:0 4px;padding-left:8px!important"
														onchange="wppaAjaxUpdatePhoto( ' . strval( intval( $id ) ) . ', \'videox\', this.value)"
														value="' . esc_attr( $videox ) . '"
													/>px W' .
													sprintf( __( '(0=default:%s)', 'wp-photo-album-plus' ), wppa_opt( 'video_width' ) ) . '
													<input
														type="text"
														style="width:50px;margin:0 4px;padding-left:8px!important"
														onchange="wppaAjaxUpdatePhoto( ' . strval( intval( $id ) ) . ', \'videoy\', this.value)"
														value="' . esc_attr( $videoy ) . '"
													/>px H' .
													sprintf( __( '(0=default:%s)', 'wp-photo-album-plus' ), wppa_opt( 'video_height' ) ) );
												}
											wppa_echo( '
											</td>
											<td>' .
												$paths . '
											</td>
											<td>' .
												$urls . '
											</td>
										</tr>' );
									}

									// Audio
									if ( $b_has_audio ) {

										$formats 	= '';
										$sizes 		= '';
										$paths 		= '';
										$urls 		= '';
										foreach ( $has_audio as $fmt ) {
											$formats .= $fmt . '<br>';
											$sizes .= wppa_get_filesize( str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) ) . '<br>';
											$paths .= str_replace( WPPA_UPLOAD_PATH, '.../wppa', str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) ) . '<br>';
											$url = str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, str_replace( 'xxx', $fmt, wppa_get_photo_path( $id, false ) ) );
											$urls .= '<a href="'. $url .'" target="_blank">' . $url . '<br>';
										}

										wppa_echo( '
										<tr>
											<td>' .
												$formats . '
											</td>
											<td>' .
												$sizes . '
											</td>
											<td>' .
												$paths . '
											</td>
											<td>' .
												$urls . '
											</td>
										</tr>' );
									}

									// Pdf
									if ( $is_pdf ) {

										// Source
										$sp 	= wppa_get_source_path( $id );
										$fs 	= wppa_get_filesize( $sp );
										$path 	= str_replace( WPPA_UPLOAD_PATH, '.../wppa', $sp );
										$url 	= str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $sp );
										wppa_echo( '
										<tr>
											<td>' .
												__( 'Document file', 'wp-photo-album-plus' ) . '
											</td>
											<td>' .
												$fs . '
											</td>
											<td>' .
												$path . '
											</td>
											<td>
												<a href="'.$url.'" target="_blank">' .
													$url . '
												</a>
											</td>
										</tr>' );
									}

									// Non pdf source_file
									else {

										// Source
										$sp 	= wppa_get_source_path( $id );
										$o1sp 	= wppa_get_o1_source_path( $id );
										$files 	= [];
										if ( wppa_is_file( $sp ) || wppa_is_file( $o1sp ) ) {

											if ( is_file( $sp ) ) {
												$ima 		= getimagesize( $sp );
												$txt 		= $ima['0'] . ' x ' . $ima['1'] . ' (' . sprintf('%4.2fMp', ( $ima['0'] * $ima['1'] ) / ( 1024 * 1024 ) ) . ') ' . wppa_get_filesize( $sp ) . '.';
												$files[] 	= ['name' => __( 'Source', 'wp-photo-album-plus' ),
															   'path' => str_replace( WPPA_UPLOAD_PATH, '.../wppa', $sp ),
															   'size' => $txt,
															   'url' => str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $sp )];
											}
											if ( is_file( $o1sp ) ) {
												$ima 		= getimagesize( $o1sp );
												$txt 		= $ima['0'] . ' x ' . $ima['1'] . ' (' . sprintf('%4.2fMp', ( $ima['0'] * $ima['1'] ) / ( 1024 * 1024 ) ) . ') ' . wppa_get_filesize( $o1sp ) . '.';
												$files[] 	= ['name' => __( 'Oriented source', 'wp-photo-album-plus' ),
															   'path' => str_replace( WPPA_UPLOAD_PATH, '.../wppa', $o1sp ),
															   'size' => $txt,
															   'url' => str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $o1sp )];
											}

											foreach( $files as $file ) {
												wppa_echo( '
												<tr>
													<td>' .
														$file['name'] . '
													</td>
													<td>' .
														$file['size'] . '
													</td>
													<td>' .
														$file['path'] . '
													</td>
													<td>
														<a href="'.$file['url'].'" target="_blank">' .
															$file['url'] . '
														</a>
													</td>
												</tr>' );
											}
										}
									}

									// Poster
									if ( $is_pdf || $b_is_video ) {

										// Poster
										$sp 	= wppa_fix_poster_ext( wppa_get_source_path( $id ), $id );
										$dsp 	= wppa_fix_poster_ext( wppa_get_photo_path( $id ), $id );
										$files = [];
										if ( wppa_is_file( $sp ) || wppa_is_file( $dsp ) ) {

											if ( wppa_is_file( $sp ) ) {
												$ima 		= getimagesize( $sp );
												$txt 		= $ima['0'] . ' x ' . $ima['1'] . ' (' . sprintf('%4.2fMp', ( $ima['0'] * $ima['1'] ) / ( 1024 * 1024 ) ) . ') ' . wppa_get_filesize( $sp ) . '.';
												$files[] 	= ['name' => __( 'Poster source', 'wp-photo-album-plus' ),
															   'path' => str_replace( WPPA_UPLOAD_PATH, '.../wppa', $sp ),
															   'size' => $txt,
															   'url' => str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $sp )];
											}
											if ( wppa_is_file( $dsp ) ) {
												$ima 		= getimagesize( $dsp );
												$txt 		= $ima['0'] . ' x ' . $ima['1'] . ' (' . sprintf('%4.2fMp', ( $ima['0'] * $ima['1'] ) / ( 1024 * 1024 ) ) . ') ' . wppa_get_filesize( $dsp ) . '.';
												$files[] 	= ['name' => __( 'Poster display', 'wp-photo-album-plus' ),
															   'path' => str_replace( WPPA_UPLOAD_PATH, '.../wppa', $dsp ),
															   'size' => $txt,
															   'url' => str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $dsp )];
											}

											foreach( $files as $file ) {
												wppa_echo( '
												<tr>
													<td>' .
														$file['name'] . '
													</td>
													<td>' .
														$file['size'] . '
													</td>
													<td>' .
														$file['path'] . '
													</td>
													<td>
														<a href="'.$file['url'].'" target="_blank">' .
															$file['url'] . '
														</a>
													</td>
												</tr>' );
											}
										}
									}

									// Non video, non pdf Display
									if ( ! $b_is_video && ! $is_pdf ) {
										$dp 	= wppa_get_photo_path( $id );
										$path 	= str_replace( WPPA_UPLOAD_PATH, '.../wppa', $dp );
										$url 	= str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $dp );

										if ( is_file( $dp ) ) {

											$txt = '
											<span id="photox-' . $id . '">' . wppa_get_photox( $id ) . '</span> x
											<span id="photoy-' . $id . '">' . wppa_get_photoy( $id ) . '</span>:
											(<span id="photofilesize-' . $id . '">' . wppa_get_filesize( $dp ) . '</span>)';

											wppa_echo( '
											<tr>
												<td>' .
													__( 'Display file', 'wp-photo-album-plus' ) . '
												</td>
												<td>' .
													$txt . '
												</td>
												<td>' .
													$path . '
												</td>
												<td>
													<a href="'.$url.'" target="_blank">' .
														$url . '
													</a>
												</td>
											</tr>' );
										}
									}

									// Thumbnail
									if ( true ) {
										$tp = wppa_get_thumb_path( $id );
										if ( is_file( $tp ) ) {
											$txt = '
											<span id="thumbx-' . $id . '">' . wppa_get_thumbx( $id ) . '</span> x
											<span id="thumby-' . $id . '">' . wppa_get_thumby( $id ) . '</span>:
											(<span id="thumbfilesize-' . $id . '">' . wppa_get_filesize( $tp ) . '</span>)
											&nbsp;
											<input
												type="checkbox"' .
												( $thumblock ? ' checked' : '' ) . '
												onchange="wppaAjaxUpdatePhoto( ' . strval( intval( $id ) ) . ', \'thumblock\', jQuery(this).prop(\'checked\') ? 1 : 0 )" /> ' .
											__( 'Locked', 'wp-photo-album-plus' );

											$path 	= str_replace( WPPA_UPLOAD_PATH, '.../wppa', $tp );
											$url 	= str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $tp );

											wppa_echo( '
											<tr>
												<td>' .
													__( 'Thumbnail', 'wp-photo-album-plus' ) . '
												</td>
												<td>' .
													$txt . '
												</td>
												<td>' .
													$path . '
												</td>
												<td>
													<a href="'.$url.'" target="_blank">' .
														$url . '
													</a>
												</td>
											</tr>' );
										}
									}

									// Local CDN
									if ( wppa_cdn( 'admin' ) == 'local' ) {

										$files = wppa_cdn_files( $id );
										if ( !empty( $files ) ) {

											$txt 	= '<span id="cdnfiles-' . $id . '">';
											$paths 	= '';
											$urls 	= '';

											foreach( $files as $file ) {
												if ( basename( $file ) != 'index.php' ) {
													$t = explode( '.', basename( $file ) );
													$t = explode( '-', $t[0] );
													$x = $t[0];
													$y = $t[1];
													$size = filesize( $file );

													$txt .=

														str_replace( '-', ' x ', wppa_strip_ext( basename( $file ) ) ) . ': ' .

													sprintf( '(%4.2fkB)', $size / 1024 ) . '<br>';

													$paths .= str_replace( WPPA_UPLOAD_PATH, '.../wppa', $file ) . '<br>';

													$url = str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $file );
													$urls .= '<a href="'.$url.'" target="_blank">' . $url . '</a><br>';
												}
											}
											$txt .= '</span>';

											wppa_echo( '
												<tr>
													<td style="line-height:20px">' .
														__( 'Local CDN', 'wp-photo-album-plus' ) . '
													</td>
													<td style="line-height:20px">' .
														$txt . '
													</td>
													<td style="line-height:20px">' .
														$paths . '
													</td>
													<td style="line-height:20px">' .
														$urls . '
													</td>
												</tr>' );
										}
									}

									wppa_echo( '
									</tbody>
								</table>' );
								}

							wppa_echo( '</fieldset></div>' );

							// Remake displayfiles / thumbnail
							if ( ! $is_video ) {
								wppa_echo( '
								<div class="wppa-flex">
								<fieldset class="wppa-fieldset">
									<legend class="wppa-legend">' .
										__( 'Actions', 'wp-photo-album-plus' ) . '
									</legend>

									<input
										type="button"
										class="wppa-admin-button button"
										title="' . esc_attr( __( 'Remake display file and thumbnail file', 'wp-photo-album-plus' ) ) . '"
										onclick="wppaAjaxUpdatePhoto( ' . $id . ', \'remake\', 0 )"
										value="' . esc_attr( __( 'Remake files', 'wp-photo-album-plus' ) ) . '"
									/>
									<input
										type="button"
										class="wppa-admin-button button"
										title="' . esc_attr( __( 'Remake thumbnail file', 'wp-photo-album-plus' ) ) . '"
										onclick="wppaAjaxUpdatePhoto( ' . $id . ', \'remakethumb\', 0 )"
										value="' . esc_attr( __( 'Remake thumbnail file', 'wp-photo-album-plus' ) ) . '"
									/>

								</fieldset></div>' );
							}



						// End Tab 2
						wppa_echo( '</div>' );
					}

					// Tab 3 ImageMagick
					if ( wppa_can_admin_magick( $id ) && ! $quick && ( $is_photo || $has_poster ) ) {
						wppa_echo( '
						<div
							id="photomagic-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding:10px;display:none"
							>' );

							wppa_echo( '
							<fieldset class="wppa-fieldset">
								<legend class="wppa-legend">' .
									__( 'Edit image', 'wp-photo-album-plus' ) . '
								</legend>' );

								{

								// Explanation
								wppa_echo( '
								<h2 class="description" style="margin:1em">' .
									__( 'The operations are executed upon the display file.', 'wp-photo-album-plus' ) . ' ' .
									__( 'A new thumbnail image will be created from the display file.', 'wp-photo-album-plus' ) . '
								</h2>' );

								// --- Actions ---
								wppa_echo( '
								<div class="wppa-flex">' );

									{

									// Rotate left
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'magickrotleft\' )"
										value="' . esc_attr( __( 'Rotate left', 'wp-photo-album-plus' ) ) . '"
									/>' );

									// Rotat 180
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'magickrot180\' )"
										value="' . esc_attr( __( 'Rotate 180&deg;', 'wp-photo-album-plus' ) ) . '"
									/>' );

									// Rotate right
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'magickrotright\' )"
										value="' . esc_attr( __( 'Rotate right', 'wp-photo-album-plus' ) ) . '"
									/>' );

									// Flip
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'magickflip\' )"
										value="' . esc_attr( __( 'Flip', 'wp-photo-album-plus' ) ) . '&thinsp;&#8212;"
										title="-flip"
									/>' );

									// Flop
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'magickflop\' )"
										value="' . esc_attr( __( 'Flop', 'wp-photo-album-plus' ) ) . ' |"
										title="-flop"
									/>' );

									// Enhance
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'enhance\' )"
										value="' . esc_attr( __( 'Enhance', 'wp-photo-album-plus' ) ) . '"
										title="-enhance"
									/>' );

									// Sharpen
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'sharpen\' )"
										value="' . esc_attr( __( 'Sharpen', 'wp-photo-album-plus' ) ) . '"
										title="-sharpen 0x1"
									/>' );

									// Blur
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'blur\' )"
										value="' . esc_attr( __( 'Blur', 'wp-photo-album-plus' ) ) . '"
										title="-blur 0x1"
									/>' );

									// Auto gamma
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'auto-gamma\' )"
										value="' . esc_attr( __( 'Auto Gamma', 'wp-photo-album-plus' ) ) . '"
										title="-auto-gamma"
									/>' );

									// Auto level
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'auto-level\' )"
										value="' . esc_attr( __( 'Auto Level', 'wp-photo-album-plus' ) ) . '"
										title="-auto-level"
									/>' );

									// Contrast+
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'contrast-p\' )"
										value="' . esc_attr( __( 'Contrast+', 'wp-photo-album-plus' ) ) . '"
										title="-brightness-contrast 0x5"
									/>' );

									// Contrast-
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'contrast-m\' )"
										value="' . esc_attr( __( 'Contrast-', 'wp-photo-album-plus' ) ) . '"
										title="-brightness-contrast 0x-5"
									/>' );

									// Brightness+
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'brightness-p\' )"
										value="' . esc_attr( __( 'Brightness+', 'wp-photo-album-plus' ) ) . '"
										title="-brightness-contrast 5"
									/>' );

									// Brightness-
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'brightness-m\' )"
										value="' . esc_attr( __( 'Brightness-', 'wp-photo-album-plus' ) ) . '"
										title="-brightness-contrast -5"
									/>' );

									// Despeckle
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'despeckle\' )"
										value="' . esc_attr( __( 'Despeckle', 'wp-photo-album-plus' ) ) . '"
										title="-despeckle"
									/>' );

									// Lenear gray
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'lineargray\' )"
										value="' . esc_attr( __( 'Linear gray', 'wp-photo-album-plus' ) ) . '"
										title="-colorspace gray"
									/>' );

									// Non-linear gray
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'nonlineargray\' )"
										value="' . esc_attr( __( 'Non-linear gray', 'wp-photo-album-plus' ) ) . '"
										title="-grayscale Rec709Luma"
									/>' );

									// Charcoal
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'charcoal\' )"
										value="' . esc_attr( __( 'Charcoal', 'wp-photo-album-plus' ) ) . '"
										title="-charcoal"
									/>'  );

									// Paint
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'paint\' )"
										value="' . esc_attr( __( 'Paint', 'wp-photo-album-plus' ) ) . '"
										title="-paint"
									/>' );

									// Sepia
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'sepia\' )"
										value="' . esc_attr( __( 'Sepia', 'wp-photo-album-plus' ) ) . '"
										title="-sepia-tone 80%"
									/>' );

									// Show/hide wppa-horizon
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaToggleHorizon()"
										value="' . esc_attr( 'Show/hide horizon', 'wp-photo-album-plus' ) . '"
										title="' . esc_attr( 'Toggle horizon reference line on/off', 'wp-photo-album-plus' ) . '"
									/>' );

									// Anticlock 0.5 deg
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'skyleft\' )"
										value="' . esc_attr( '0.5&deg;', 'wp-photo-album-plus' ) . '"
										title="' . esc_attr( 'Rotate image by 0.5&deg; anticlockwise', 'wp-photo-album-plus' ) . '"
									/>' );

									// Clockwise 0.5 deg
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										onclick="wppaTryMagick( ' . $id . ', \'skyright\' )"
										value="' . esc_attr( '-0.5&deg;', 'wp-photo-album-plus' ) . '"
										title="' . esc_attr( 'Rotate image by 0.5&deg; clockwise', 'wp-photo-album-plus' ) . '"
									/>' );

									// Crop
									wppa_echo( '
									<input
										type="button"
										class="wppa-admin-button button"
										id="button-' . $id . '"
										value="Crop"
										title=""
									/>' );

									// Set cropbox aspect ratio
									$ratio = ( $photoy ? ( $photox / $photoy ) : 'NaN' );
									$dflt = wppa_opt( 'image_magick_ratio' );

									wppa_echo( '
									<select
										onchange="wppaCropper[' . $id . '].setAspectRatio(this.value)"
										title="' . __( 'Aspect ratio of cropped image', 'wp-photo-album-plus' ) . '"
										>
										<option value="NaN"' . 				( $dflt == 'NaN' ? 		' selected' : '' ) . '>' . 		__( 'free', 'wp-photo-album-plus' ) . '</option>
										<option value="' . $ratio . '"' . 	( $dflt == 'ratio' ? 	' selected' : '' ) . '>' . 		__( 'original', 'wp-photo-album-plus' ) . '</option>
										<option value="1"' . 				( $dflt == '1' ? 		' selected' : '' ) . '>' . 		__( 'square', 'wp-photo-album-plus' ) . '</option>
										<option value="1.25"' . 			( $dflt == '1.25' ? 	' selected' : '' ) . '>4:5 ' . 	__( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="1.33333"' . 			( $dflt == '1.33333' ? 	' selected' : '' ) . '>3:4 ' . 	__( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="1.5"' . 				( $dflt == '1.5' ? 		' selected' : '' ) . '>2:3 ' . 	__( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="1.6"' . 				( $dflt == '1.6' ? 		' selected' : '' ) . '>5:8 ' . 	__( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="1.77777"' . 			( $dflt == '1.77777' ? 	' selected' : '' ) . '>9:16 ' . __( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="2"' . 				( $dflt == '2' ? 		' selected' : '' ) . '>1:2 ' . 	__( 'landscape', 'wp-photo-album-plus' ) . '</option>
										<option value="0.8"' . 				( $dflt == '0.8' ? 		' selected' : '' ) . '>4:5 ' . 	__( 'portrait', 'wp-photo-album-plus' ) . '</option>
										<option value="0.75"' . 			( $dflt == '0.75' ? 	' selected' : '' ) . '>3:4 ' . 	__( 'portrait', 'wp-photo-album-plus' ) . '</option>
										<option value="0.66667"' . 			( $dflt == '0.66667' ? 	' selected' : '' ) . '>2:3 ' . 	__( 'portrait', 'wp-photo-album-plus' ) . '</option>
										<option value="0.625"' . 			( $dflt == '0.625' ? 	' selected' : '' ) . '>5:8 ' . 	__( 'portrait', 'wp-photo-album-plus' ) . '</option>
										<option value="0.5625"' . 			( $dflt == '0.5625' ? 	' selected' : '' ) . '>9:16 ' . __( 'portrait', 'wp-photo-album-plus' ) . '</option>
										<option value="0.5"' . 				( $dflt == '0.5' ? 		' selected' : '' ) . '>1:2 ' . 	__( 'portrait', 'wp-photo-album-plus' ) . '</option>
									</select>' . $br );

									if ( $dflt == 'ratio' ) {
										$value = $ratio;
									}
									elseif ( $dflt == 'free' ) {
										$value = '';
									}
									else {
										$value = $dflt;
									}

									}

								// End flex div
								wppa_echo( '</div>' );

								// Command stack
								wppa_echo( '
								<h2 class="description" style="margin:1em">' .
									__( '<b>ImageMagick</b> command stack', 'wp-photo-album-plus' ) . ':
									<span
										id="magickstack-' . strval( intval( $id ) ). '"
										style="color:blue"
										>' .
										sanitize_text_field( $magickstack ) . '
									</span>
									<input
										type="button"
										class="wppa-admin-button button"
										id="imstackbutton-' . strval( intval( $id ) ) . '"
										onclick="wppaTryMagick( ' . strval( intval( $id ) ) . ', \'magickundo\' )"
										value="' . esc_attr( __( 'Undo', 'wp-photo-album-plus' ) ) . '"
										title="' . esc_attr( __( 'Undo last Magick command', 'wp-photo-album-plus' ) ) . '"
										style="margin-left:4px;' . ( $magickstack ? 'display:inline;' : 'display:none;' ) . '"
									/>
								</h2>' );

								// Cropper container
								// Fake 'for social media' to use the local file here, not cloudinary. Files from cloudinary do not reload, even with ?ver=...
								wppa( 'for_sm', true );
								wppa_echo( '
								<div
									class="wppa-cropper-container-wrapper">
									<img
										id="fs-img-' . $id . '"
										src="' . esc_url( wppa_get_photo_url( $id ) ) . '"
										style="float:left;max-width:100%"
									/>
								</div>' );

								// Reset switch
								wppa( 'for_sm', false );
								}

							wppa_echo( '</fieldset>' );

						// End Tab 3
						wppa_echo( '</div>' );
					}

					// Tab 5 Photo IPTC
					if ( ! $quick && ! empty( $iptcs ) ) {

						wppa_echo( '
						<div
							id="photoiptc-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding-bottom:12px;padding-left:12px;display:none"
							>' );

							wppa_echo( '
							<table
								id="wppa-iptc-' . $id . '"
								class="wppa-table wppa-photo-table"
								style="clear:both;width:99%;"
								>
								<thead>
									<tr style="font-weight:bold">
										<td style="padding:0 4px">' . __( 'IPTC tag', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Description', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Value', 'wp-photo-album-plus' ) . '</td>
									</tr>
								</thead>
								<tbody>' );

								foreach ( $iptcs as $iptc ) {
									wppa_echo( '
									<tr id="iptc-tr-' . $iptc['id'] . '">
										<td style="padding:0 4px">' . $iptc['tag'] . '</td>
										<td style="padding:0 4px">' . esc_html( wppa_iptc_tagname( $iptc['tag'] ) ) . ':</td>
										<td style="padding:0 4px">
											<input
												type="text"
												style="width:500px"
												value="' . esc_attr( $iptc['description'] ) . '"
												onchange="wppaAjaxUpdateIptc(\'' . $id . '\', \'' . $iptc['id'] . '\', this.value, \'' . $iptc['tag'] . '\')"
											/>
										</td>
									</tr>' );
								}

								wppa_echo( '
								</tbody>
							</table><div style="clear:both"></div>' );
						wppa_echo( '</div>' ); // End tab 5 IPTC
					}

					// Tab 4 Photo EXIF
					if ( ! $quick && ! empty( $exifs ) ) {

						wppa_echo( '
						<div
							id="photoexif-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding-bottom:12px;padding-left:12px;display:none"
							>' );

							$brand = wppa_get_camera_brand( $id );
							wppa_echo( '
							<table
								id="wppa-exif-' . $id . '"
								class="wppa-table wppa-photo-table"
								style="clear:both;width:99%;"
								>
								<thead>
									<tr style="font-weight:bold">
										<td style="padding:0 4px">' . __( 'Exif tag', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Brand', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Description', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Raw value', 'wp-photo-album-plus' ) . '</td>
										<td style="padding:0 4px">' . __( 'Formatted value', 'wp-photo-album-plus' ) . '</td>
									</tr>
								</thead>
								<tbody>' );

								foreach ( $exifs as $exif ) {
									$desc = $exif['description'];
									if ( is_serialized( $desc ) ) {
										$desc = 'Array(' . count( wppa_unserialize( $desc ) ) . ')';
									}
									wppa_echo( '
									<tr id="exif-tr-' . $exif['id'] . '">
										<td style="padding:0 4px">'.$exif['tag'].'</td>' );

										if ( $brand && $exif['brand'] ) {
											wppa_echo( '
											<td style="padding:0 4px">' . $brand . '</td>
											<td style="padding:0 4px">' . wppa_exif_tagname( $exif['tag'], $brand, 'brandonly' ) . ':</td>' );
										}
										else {
											wppa_echo( '
											<td style="padding:0 4px"></td>
											<td style="padding:0 4px">' . wppa_exif_tagname( $exif['tag'] ) . ':</td>' );
										}

										$raw_value = wp_kses( $desc, 'post' );
										if ( strlen( $raw_value ) > 50 ) {
											$raw_value = substr( $raw_value, 0, 47 ) . '...';
										}
										$formatted_value = wp_kses( wppa_format_exif( $exif['tag'], $exif['description'], $brand ), 'post' );
										if ( strlen( $formatted_value ) > 50 ) {
											$formatted_value = substr( $formatted_value, 0, 47 ) . '...';
										}
										wppa_echo( '
										<td style="padding:0 4px">' . $raw_value . '</td>
										<td style="padding:0 4px">' . $formatted_value . '</td>
									</tr>' );
								}

								wppa_echo( '
								</tbody>
							</table><div style="clear:both"></div>' );
						wppa_echo( '</div>' );
					}

					// Tab 6 Used BY
					if ( ! $quick && ! empty( $usedby ) ) {

						wppa_echo( '
						<div
							id="photousedby-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding-bottom:12px;padding-left:12px;display:none"
							>' );

							wppa_echo( '
							<table
								id="wppa-exif-' . $id . '"
								class="wppa-table wppa-photo-table"
								style="clear:both;width:99%;"
								>
								<thead>
									<tr style="font-weight:bold">
										<td style="width:50px;">' . __( 'ID', 'wp-photo-album-plus' ) . '</td>
										<td style="width:50px;">' . __( 'Type', 'wp-photo-album-plus' ) . '</td>
										<td style="width:50px;">' . __( 'Status', 'wp-photo-album-plus' ) . '</td>
										<td style="">' . __( 'Page title', 'wp-photo-album-plus' ) . '</td>
										<td style="">' . __( 'Excerpt', 'wp-photo-album-plus' ) . '</td>
										<td style="width:50px;">' . __( 'View', 'wp-photo-album-plus' ) . '</td>' );
										if ( current_user_can( 'edit_posts' ) ) {
											wppa_echo( '<td style="width:50px;">' . __( 'Edit', 'wp-photo-album-plus' ) . '</td>' );
										}

									wppa_echo( '
									</tr>
								</thead>
								<tbody>' );

								foreach( $usedby as $ID ) {
									if ( wppa_is_posint( $ID ) ) {
										$post = get_post( $ID );
										if ( $post ) {
											wppa_echo( '
											<tr id="usedby-tr-' . $ID . '">
												<td style="width:50px;">' . $ID . '</td>
												<td style="width:50px;">' . $post->post_type . '</td>
												<td style="width:50px;">' . $post->post_status . '</td>
												<td style="">' . $post->post_title . '</td>
												<td style="">' . nl2br( $post->post_excerpt ) . '</td>
												<td style="width:50px;">
													<a href="' . esc_url( get_permalink( $ID ) ) . '" target="_blank">' .
														__( 'View', 'wp-photo-album-plus' ) . '
													</a>
												</td>' );
												if ( current_user_can( 'edit_posts' ) ) {
													wppa_echo( '
													<td style="width:50px;">
														<a href="' . esc_url( admin_url( 'post.php?post=' . $ID . '&action=edit' ) ) . '" target="_blank">' . __( 'Edit', 'wp-photo-album-plus' ) . '</a>
													</td>' );
												}
											wppa_echo('
											</tr>' );
										}
									}
								}

								wppa_echo( '
								</tbody>
							</table><div style="clear:both"></div>' );
						wppa_echo( '</div>' );
					}

					// Comments
					$has_pending_comments = false;
					if ( ! $quick && ! empty( $comments ) ) {

						wppa_echo( '
						<div
							id="photocomment-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding-bottom:12px;padding-left:12px;display:none"
							>' );

							wppa_echo( '
							<table
								class="wppa-table wppa-photo-table"
								style="width:100%"
								>
								<thead>
									<tr style="font-weight:bold">
										<td style="padding:0 4px">#</td>
										<td style="padding:0 4px">User</td>
										<td style="padding:0 4px">Time since</td>
										<td style="padding:0 4px">Status</td>
										<td style="padding:0 4px">Comment</td>
									</tr>
								</thead>
								<tbody>' );

									foreach ( $comments as $comment ) {
										wppa_echo( '
										<tr id="com-tr-' . $comment['id'] . '">
											<td style="padding:0 4px">'.$comment['id'].'</td>
											<td style="padding:0 4px">'.$comment['user'].'</td>
											<td style="padding:0 4px">'.wppa_get_time_since( $comment['timestamp'] ).'</td>' );
											if ( current_user_can( 'wppa_comments' ) || current_user_can( 'wppa_moderate' ) || ( wppa_get_user() == $photo['owner'] && wppa_switch( 'owner_moderate_comment' ) ) ) {
												$p = ( $comment['status'] == 'pending' ) ? ' selected' : '';
												$a = ( $comment['status'] == 'approved' ) ? ' selected' : '';
												$s = ( $comment['status'] == 'spam' ) ? ' selected' : '';
												$t = ( $comment['status'] == 'trash' ) ? ' selected' : '';
												wppa_echo( '
												<td style="padding:0 4px">
													<select
														id="com-stat-' . $comment['id'] . '"
														style="background-color:' . ( $comment['status'] == 'approved' ? '#ffffe0' : '#ffebe8' ) . '"
														onchange="wppaAjaxUpdateCommentStatus( '.$id.', '.$comment['id'].', this.value );wppaSetComBgCol(' . $comment['id'] . ')"
														>
														<option value="pending"' . $p . '>' . __( 'Pending' , 'wp-photo-album-plus' ) . '</option>
														<option value="approved"' . $a . '>' . __( 'Approved' , 'wp-photo-album-plus' ) . '</option>
														<option value="spam"' . $s . '>' . __( 'Spam' , 'wp-photo-album-plus' ) . '</option>
														<option value="trash"' . $t . '>' . __( 'Trash' , 'wp-photo-album-plus' ) . '</option>
													</select >
												</td>' );
											}
											else {
												wppa_echo( '<td style="padding:0 4px">' );
													if ( $comment['status'] == 'pending' ) $s = __( 'Pending' , 'wp-photo-album-plus' );
													elseif ( $comment['status'] == 'approved' ) $s = __( 'Approved' , 'wp-photo-album-plus' );
													elseif ( $comment['status'] == 'spam' ) $s = __( 'Spam' , 'wp-photo-album-plus' );
													elseif ( $comment['status'] == 'trash' ) $s = __( 'Trash' , 'wp-photo-album-plus' );
												wppa_echo( '</td>' );
											}
											wppa_echo( '<td style="padding:0 4px">' . $comment['comment'] . '</td>
										</tr>' );
										if ( $comment['status'] != 'approved' ) {
											$has_pending_comments = true;
										}
									}

								wppa_echo( '
								</tbody>
							</table>
							<div class="clear"></div>
						</div>' );
					}

					// Fix the background color
					$the_js = 'jQuery(document).ready(function(){wppaPhotoStatusChange(' . $id . ')});';

					// When there are moderatable comments, open details and comments tab
					if ( $has_pending_comments && ( current_user_can( 'wppa_comments' ) || current_user_can( 'wppa_moderate' ) || ( ( wppa_get_user() == $photo['owner'] && wppa_switch( 'owner_moderate_comment' ) ) ) ) ) {
						$the_js .= '
						jQuery(document).ready(function(){
							jQuery("#wppa-toplevel-details-'.$id.'").attr("open","open");
							jQuery("#wppa-photoadmin-tab-'.$id.'").trigger("click");
						});';
					}
					wppa_add_inline_script( 'wppa-admin', $the_js, true );

					wppa_echo( '
					<div class="clear"></div>
				</div>
				<div class="clear" style="margin-top:7px"></div>' );
			}

			/* End the actual display area for the photo settings */
			wppa_echo( '</details>');

		} /* foreach photo */

		wppa_admin_pagination( $pagesize, $page, $count, $link, 'bottom' );

	} /* photos not empty */
} /* function */

function wppa_album_photos_bulk( $album, $page_1 = false ) {
	global $wpdb;

	if ( $album == 'moderate' ) {
		// Can i moderate?
		if ( ! current_user_can( 'wppa_moderate' ) ) {
			wp_die( __( 'You do not have the rights to do this' , 'wp-photo-album-plus' ) );
		}
	}

	// Init
	wppa_add_local_js( 'wppa_album_photos_bulk' );
	$count = '0';
	$abort = false;

	if ( wppa_get( 'bulk-action' ) ) {
		check_admin_referer( 'wppa-bulk', 'wppa-bulk' );
		if ( wppa_get( 'bulk-photo' ) ) {
			$ids 		= (array) wppa_get( 'bulk-photo' );
			$newalb 	= wppa_get( 'bulk-album' );
			$status 	= wppa_get( 'bulk-status' );
			$owner 		= wppa_get( 'bulk-owner' );
			$totcount 	= count( $ids );

			if ( is_array( $ids ) ) {
				foreach ( array_keys( $ids ) as $id ) {
					$skip = false;
					switch ( wppa_get( 'bulk-action' ) ) {
						case 'wppa-bulk-delete':
							wppa_delete_photo( $id );
							break;
						case 'wppa-bulk-delete-immediate':
							wppa_delete_photo( $id, true );
							break;
						case 'wppa-bulk-undelete':
							wppa_undelete_photo( $id, false );
							break;
						case 'wppa-bulk-move-to':
							if ( $newalb ) {
								$photo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE id = %s", $id ), ARRAY_A );
								if ( wppa_switch( 'void_dups' ) ) {	// Check for already exists
									$exists = wppa_get_count( WPPA_PHOTOS, ['filename' => $photo['filename'], 'album' => $newalb] );
									if ( $exists ) {	// Already exists
										wppa_error_message ( sprintf ( __( 'A photo with filename %s already exists in album %s.' , 'wp-photo-album-plus' ), $photo['filename'], $newalb ) );
										$skip = true;
									}
								}
								if ( ! $skip ) {
									wppa_invalidate_treecounts( $photo['album'] );		// Current album
									wppa_invalidate_treecounts( $newalb );				// New album
									wppa_update_photo( $id, ['album' => $newalb] );
									wppa_move_source( $photo['filename'], $photo['album'], $newalb );
								}
							}
							else wppa_error_message( 'Unexpected error #4 in wppa_album_photos_bulk().' );
							break;
						case 'wppa-bulk-copy-to':
							if ( $newalb ) {
								$photo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
																		  WHERE id = %d", $id ), ARRAY_A );
								if ( wppa_switch( 'void_dups' ) ) {	// Check for already exists
									$exists = wppa_get_count( WPPA_PHOTOS, ['filename' => $photo['filename'], 'album' => $newalb] );
									if ( $exists ) {	// Already exists
										wppa_error_message ( sprintf ( __( $exists.'A photo with filename %s already exists in album %s.' , 'wp-photo-album-plus' ), $photo['filename'], $newalb ) );
										$skip = true;
									}
								}
								if ( ! $skip ) {
									wppa_copy_photo( $id, $newalb );
									wppa_invalidate_treecounts( $newalb );
								}
							}
							else wppa_error_message( 'Unexpected error #3 in wppa_album_photos_bulk().' );
							break;
						case 'wppa-bulk-status':
							if ( ! in_array( $status, array( 'publish', 'pending', 'featured', 'scheduled', 'gold', 'silver', 'bronze', 'private' ) ) ) {
								wppa_log( 'error', 'Unknown status '.strip_tags( $status ).' found in wppa-photo-admin-autosave.php -> wppa_album_photos_bulk()' );
								$status = 'publish';
							}
							if ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) ) {
								if ( $status == 'publish' || $status == 'pending' || wppa_user_is_admin() || ! wppa_switch( 'ext_status_restricted' ) ) {
									$oldstatus = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM $wpdb->wppa_photos WHERE id = %d", $id ) );
									wppa_update_photo( $id, ['status' => $status] );
									if ( $oldstatus == 'pending' && $status == 'publish' ) {
										wppa_schedule_mailinglist( 'photoapproved', 0, $id );
									}
									wppa_invalidate_treecounts( wppa_get_photo_item( $id, 'album' ) );
								}
								else wp_die( 'Security check failure 2' );
							}
							else wp_die( 'Security check failure 3' );
							break;
						case 'wppa-bulk-owner':
							if ( wppa_user_is_admin() && wppa_switch( 'photo_owner_change' ) ) {
								if ( $owner ) {
									$owner = sanitize_user( $owner );
									$exists = wppa_get_count( $wpdb->users, ['user_login' => $owner] );
									if ( $exists ) {
										wppa_update_photo( $id, ['owner' => $owner] );
									}
									else {
										wppa_error_message( 'A user with login name '.$owner.' does not exist.' );
										$skip = true;
									}
								}
								else wp_die( 'Missing required arg in bulk change owner' );
							}
							else wp_die( 'Security check failure 4' );
							break;
						default:
							wppa_error_message( 'Unimplemented bulk action requested in wppa_album_photos_bulk().' );
							break;
					}
					if ( ! $skip ) $count++;
					if ( wppa_is_time_up() ) {
						wppa_error_message( sprintf( __( 'Time is out after processing %d out of %d items.' , 'wp-photo-album-plus' ), $count, $totcount ) );
						$abort = true;
					}
					if ( $abort ) break;
				}
			}
			else wppa_error_message( 'Unexpected error #2 in wppa_album_photos_bulk().' );
		}
		else {
			wppa_error_message( 'Unexpected error #1 in wppa_album_photos_bulk().' );
//			var_dump($_REQUEST);
		}

		if ( $count && ! $abort ) {
			switch ( wppa_get( 'bulk-action' ) ) {
				case 'wppa-bulk-delete':
					$message = sprintf( __( '%d photos deleted.', 'wp-photo-album-plus' ), $count );
					break;
				case 'wppa-bulk-delete-immediate':
					$message = sprintf( __( '%d photos permanently removed from system.', 'wp-photo-album-plus' ), $count );
					break;
				case 'wppa-bulk-move-to':
					$message = sprintf( __( '%1$s photos moved to album %2$s.', 'wp-photo-album-plus' ), $count, $newalb.': ' . wppa_get_album_name( $newalb ) );
					break;
				case 'wppa-bulk-copy-to':
					$message = sprintf( __( '%1$s photos copied to album %2$s.', 'wp-photo-album-plus' ), $count, $newalb.': ' . wppa_get_album_name( $newalb ) );
					break;
				case 'wppa-bulk-status':
					$message = sprintf( __( 'Changed status to %1$s on %2$s photos.', 'wp-photo-album-plus' ), $status, $count );
					break;
				case 'wppa-bulk-owner':
					$message = sprintf( __( 'Changed owner to %1$s on %2$s photos.', 'wp-photo-album-plus' ), $owner, $count );
					break;
				default:
					$message = sprintf( __( '%d photos processed.', 'wp-photo-album-plus' ), $count );
					break;
			}
			wppa_ok_message( $message );
		}
	}

	$slug = 'photo_bulk';
	$a = wppa_is_int( $album ) ? $album : '0';
	if ( ! $a ) {
		$slug .= '_' . $album;
	}
	$parms 			= wppa_get_paging_parms( $slug, $page_1 );

	$pagesize 		= $parms['pagesize']; // wppa_opt( 'photo_admin_pagesize' ) ? wppa_opt( 'photo_admin_pagesize' ) : '20';

	$next_after 	= wppa_get( 'next-after', '0' ) ? '1' : '0';
	$p 				= $parms['page']; // wppa_get( 'paged', '1' );
	$page 			= $p + $next_after;
	$skip 			= ( $page - '1' ) * $pagesize;

	if ( $album ) {
		if ( $album == 'moderate' ) {
			$count	= wppa_get_count( WPPA_PHOTOS, ['status' => 'pending'] );

			$photos	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE status = 'pending'
														   ORDER BY album DESC, timestamp DESC
														   LIMIT %d, %d", $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos_bulk( $album, true );
				return;
			}

			$link 	= get_admin_url().'admin.php?page=wppa_moderate_photos';
		}
		elseif ( $album == 'search' ) {
			$count 	= wppa_get_edit_search_photos( '', '', 'count_only' );
			$photos = wppa_get_edit_search_photos( $skip, $pagesize );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos_bulk( $album, true );
				return;
			}

			$link 	= get_admin_url().'admin.php?page=wppa_admin_menu&tab=edit&edit-id='.$album.'&wppa-searchstring='.wppa_get( 'searchstring' ).'&bulk=1'.'&wppa-nonce=' . wp_create_nonce('wppa-nonce');
			wppa_show_search_statistics();
		}
		elseif ( $album == 'trash' ) {
			$count 	= wppa_get_count( WPPA_PHOTOS, ['album' => '0'], ['<'] );

			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE album < '0' ORDER BY modified DESC
														   LIMIT %d, %d", $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos_bulk( $album, true );
				return;
			}

			$link 	= get_admin_url() . 'admin.php' .
										'?page=wppa_admin_menu' .
										'&tab=edit' .
										'&edit-id=trash' .
										'&bulk=1' .
										'&wppa-nonce=' . wp_create_nonce('wppa-nonce');
		}
		else {
			$count 	= wppa_get_count( WPPA_PHOTOS, ['album' => $album] );

			$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE album = %s
														   " . wppa_get_photo_order( $album ) . "
														   LIMIT %d, %d", $album, $skip, $pagesize ), ARRAY_A );

			if ( ! count( $photos ) && $parms['page'] > '1' ) {
				wppa_album_photos_bulk( $album, true );
				return;
			}

			$link = get_admin_url().'admin.php?page=wppa_admin_menu&tab=edit&edit-id='.$album.'&bulk=1'.'&wppa-nonce=' . wp_create_nonce('wppa-nonce');
		}

		if ( $photos ) {
			$plink = $link . '&next-after=' . $next_after;

			wppa_admin_pagination( $pagesize, $page, $count, $plink, 'top' );

			$result = '
			<form action="' . $link . '&paged=' . $page . '#manage-photos" method="post">' .
				wp_nonce_field( 'wppa-bulk', 'wppa-bulk' ) . '
				<div>
					<!-- Bulk action -->
					<select id="wppa-bulk-action" name="wppa-bulk-action" onchange="wppaBulkActionChange( this, \'bulk-album\' )">
						<option value="" disabled selected>' . esc_html__( 'Bulk action', 'wp-photo-album-plus' ) . '</option>';
						if ( $album == 'trash' ) {
							$result .= '
							<option value="wppa-bulk-delete-immediate">' . esc_html__( 'Remove permanently', 'wp-photo-album-plus' ) . '</option>
							<option value="wppa-bulk-undelete">' . esc_html__( 'Undelete', 'wp-photo-album-plus' ) . '</option>';
						}
						else {
							$result .= '
							<option value="wppa-bulk-delete">' . esc_html__( 'Delete', 'wp-photo-album-plus' ) . '</option>';
						}
						$result .= '
						<option value="wppa-bulk-move-to">' . esc_html__( 'Move to', 'wp-photo-album-plus' ) . '</option>
						<option value="wppa-bulk-copy-to">' . esc_html__( 'Copy to', 'wp-photo-album-plus' ) . '</option>';

						if ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) ) {
							$result .= '
							<option value="wppa-bulk-status">' . esc_html__( 'Set status to', 'wp-photo-album-plus' ) . '</option>';
						}
						if ( wppa_user_is_admin() && wppa_switch( 'photo_owner_change' ) ) {
							$result .= '
							<option value="wppa-bulk-owner">' . esc_html__( 'Set owner to', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '
					</select>
					<!-- Select album -->' .

					wppa_album_select_a( array( 'checkaccess' 		=> true,
												'path' 				=> true,
												'exclude' 			=> $album,
												'selected' 			=> '0',
												'addpleaseselect' 	=> true,
												'sort' 				=> true,
												'tagopen' 			=> '<select' .
																			' name="wppa-bulk-album"' .
																			' id="wppa-bulk-album"' .
																			' style="display:none"' .
																			' onchange="wppa_setCookie( \'wppa_bulk_album\',this.value,365 )"' .
																			' >',
												'tagname' 			=> 'wppa-bulk-album',
												'tagid' 			=> 'wppa-bulk-album',
												'tagonchange' 		=> 'wppa_setCookie( \'wppa_bulk_album\',this.value,365 );',
												'tagstyle' 			=> 'display:none;cursor:pointer;',
												) );

					if ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) ) {
						$result .= '
						<!-- Select status -->
						<select
							name="wppa-bulk-status"
							id="wppa-bulk-status"
							style="display:none"
							onchange="wppa_setCookie( \'wppa_bulk_status\',this.value,365 )"
							>
							<option value="">' 			. esc_html__( '- select a status -' , 'wp-photo-album-plus' ) 	. '</option>
							<option value="pending">' 	. esc_html__( 'Pending' , 'wp-photo-album-plus' ) 				. '</option>
							<option value="publish">' 	. esc_html__( 'Publish' , 'wp-photo-album-plus' ) 				. '</option>';
							if ( ! wppa_switch( 'ext_status_restricted' ) || wppa_user_is_admin() ) {
								$result .= '
								<option value="featured">' 	. esc_html__( 'Featured' , 'wp-photo-album-plus' ) 	. '</option>
								<option value="gold">' 		. esc_html__( 'Gold' , 'wp-photo-album-plus' ) 		. '</option>
								<option value="silver">' 	. esc_html__( 'Silver' , 'wp-photo-album-plus' ) 	. '</option>
								<option value="bronze">' 	. esc_html__( 'Bronze' , 'wp-photo-album-plus' ) 	. '</option>
								<option value="scheduled">' . esc_html__( 'Scheduled' , 'wp-photo-album-plus' ) . '</option>
								<option value="private">' 	. esc_html__( 'Private' , 'wp-photo-album-plus' ) 	. '</option>';
							}
						$result .= '
						</select>';
					}

					$users = wppa_get_users();

					// Less tha2 250 users
					if ( count( $users ) < 250 ) {
						$result .= '
						<!-- Select user -->
						<select name="wppa-bulk-owner" id="wppa-bulk-owner" style="display:none" onchange="wppa_setCookie( \'wppa_bulk_owner\',this.value,365 )">
							<option value="">' . esc_html__( '- select an owner -' , 'wp-photo-album-plus' ) . '</option>';
							foreach ( $users as $user ) {
								$result .= '<option value="' . esc_attr( $user['user_login'] ) . '">' . htmlspecialchars( $user['display_name'] ) . ' (' . htmlspecialchars( $user['user_login'] ) . ')</option>';
							}
						$result .= '
						</select>';
					}

					// Mor ethan 250 users
					else {
						$result .= '
						<!-- Input user_login -->
						<input name="wppa-bulk-owner" id="wppa-bulk-owner" style="display:none" onchange="wppa_setCookie( \'wppa_bulk_owner\',this.value,365 )" />';
					}

					// Submit
					$result .= '
					<!-- Submit -->
					<input type="submit" onclick="return wppaBulkDoitOnClick()" class="button-primary" value="' . esc_html__( 'Doit!' , 'wp-photo-album-plus' ) . '"/>';
					if ( wppa_is_mobile() ) {
						$result .= '<br>';
					}

					// Net page after selection
					$nextafterselhtml = '
					<select name="next-after">
						<option value="-1"' . ( $next_after == '-1' ? ' selected' : '' ) . '>' . esc_html__( 'the previous page', 'wp-photo-album-plus' ) . '</option>
						<option value="0"' . ( $next_after == '0' ? ' selected' : '' ) . '>' . esc_html__( 'the same page', 'wp-photo-album-plus' ) . '</option>
						<option value="1"' . ( $next_after == '1' ? ' selected' : '' ) . '>' . esc_html__( 'the next page', 'wp-photo-album-plus' ) . '</option>
					</select>';
					$result .= sprintf( __( 'Go to %s after Doit!.', 'wp-photo-album-plus' ), $nextafterselhtml );
					if ( wppa_is_mobile() ) {
						$result .= '<br>';
					}

					// Confirm delete
					$result .= '
					<input
						type="checkbox"
						id="confirm-delete"
						name="confirm-delete"
						checked="checked"
						onchange="wppaToggleConfirmDelete(this)"
					/>
					<label for="confirm-delete">' .
						esc_html__( 'Confirm delete', 'wp-photo-album-plus' ) . '
					</label>';

					// Confirm move
					$result .= '
					<input
						type="checkbox"
						id="confirm-move"
						name="confirm-move"
						checked="checked"
						onchange="wppaToggleConfirmMove(this)"
					/>
					<label for="confirm-move">' .
						esc_html__( 'Confirm move', 'wp-photo-album-plus' ) . '
					</label>';

				$result .= '
				</div>';
				wppa_echo( $result );

				$edit_link = wppa_ea_url( 'single', 'edit' );

				// The table of bulk ediatble items
				$thead_body = '
				<td><input type="checkbox" class="wppa-bulk-photo" onchange="jQuery( \'.wppa-bulk-photo\' ).prop( \'checked\', this.checked )" /></td>
				<td>' . esc_html__( 'ID', 'wp-photo-album-plus' ) 			. '</td>
				<td>' . esc_html__( 'Preview', 'wp-photo-album-plus' ) 		. '</td>
				<td>' . esc_html__( 'Name', 'wp-photo-album-plus' ) 		. '</td>
				<td>' . esc_html__( 'Description', 'wp-photo-album-plus' ) 	. '</td>
				<td>' . esc_html__( 'Status', 'wp-photo-album-plus' ) 		. '</td>
				<td>' . esc_html__( 'Owner', 'wp-photo-album-plus' ) 		. '</td>
				<td>' . esc_html__( 'Remark', 'wp-photo-album-plus' ) 		. '</td>';
				$result = '
				<table class="widefat">
					<thead style="font-weight:bold">' .
						$thead_body . '
					</thead>
					<tbody>';
					wppa_echo( $result );

						foreach ( $photos as $photo ) {
							$id = $photo['id'];

							// Album for moderate
							static $modalbum;
							if ( $album == 'moderate' ) {
								if ( $modalbum != $photo['album'] ) {
									wppa_echo( '
									<tr>
										<td colspan="8" style="background-color:lightgreen">
											<h1 style="margin:0">' .
												sprintf( __( 'Moderate photos from album %s by %s', 'wp-photo-album-plus' ),
															 '<i>' . htmlspecialchars( wppa_get_album_name( $photo['album'] ) ) . '</i>',
															'<i>' . htmlspecialchars( wppa_get_album_item( $photo['album'], 'owner' ) ) . '</i>' ) .
											'</h1>
										</td>
									</tr>' );
									$modalbum = $photo['album'];
								}
							}

							$maxsize = wppa_get_minisize();

							$result = '
							<tr id="photoitem-' . $id . '" class="photoitem">
								<!-- Checkbox -->
								<td>
									<input type="hidden" id="photo-nonce-' . $id . '" value="' . wp_create_nonce( 'wppa-nonce_' . $id ) . '" />
									<input type="checkbox" name="wppa-bulk-photo[' . $id . ']" class="wppa-bulk-photo" />
								</td>
								<!-- ID and delete link -->
								<td>
									<a
										href="' . $edit_link . '&photo=' . $id . '"
										target="_blank"
										>' .
										$id . '
									</a>
									<br>
									<a
										id="wppa-delete-' . $id . '"
										onclick="wppaConfirmAndDelete(' . $id . ', ' . ( $album == 'trash' ? 'true' : 'false' ) . ' )"
										style="color:red;font-weight:bold;cursor:pointer"
										>' .
										( $album == 'trash' ? __( 'Remove permanently', 'wp-photo-album-plus' ) : __( 'Delete', 'wp-photo-album-plus' ) ) . '
									</a>
								</td>
								<!-- Preview -->
								<td style="min-width:240px; text-align:center">';

								if ( wppa_is_video( $photo['id'] ) ) {
									$a = false;
									if ( wppa_is_file( wppa_strip_ext( wppa_get_photo_path( $id ) ) . '.mp4' ) ) {
										$a = true;
										$result .= '
										<a
											href="' . esc_url( str_replace( '.jpg', '.mp4', wppa_get_photo_url( $id ) ) ) . '"
											target="_blank"
											title="' . esc_attr( __( 'Click to see fullsize video', 'wp-photo-album-plus' ) ) . '"
											>' ;
									}

									$result .= wppa_get_video_html( array(
												'id'			=> $id,
												'height' 		=> '160',
												'controls' 		=> false,
												'tagid' 		=> 'pa-id-' . $id,
												'preload' 		=> 'metadata',
												'use_thumb' 	=> true,
												) );
									if ( $a ) {
										$result .= '</a>';
									}
								}
								else {
									$result .= '
									<a
										href="' . esc_url( wppa_get_photo_url( $photo['id'] ) ) . '"
										target="_blank"
										title="' . esc_attr( __( 'Click to see fullsize', 'wp-photo-album-plus' ) ) . '"
										>
										<img
											class="wppa-bulk-thumb"' .
											( wppa_lazy() ? ' data-' : ' ' ) . 'src="' . esc_url( wppa_get_thumb_url( $id ) ) . '"
											style="max-width:' . $maxsize . 'px;max-height:' . $maxsize . 'px"
										/>
									</a>';
								}
								$result .= '
								</td>
								<!-- Name, size, move -->
								<td style="width:25%">
									<!-- Name -->
									<input
										type="text"
										style="width:300px"
										id="pname-' . $id . '"
										onchange="wppaAjaxUpdatePhoto(' . $id . ', \'name\', this.value)"
										value="' . esc_attr( stripslashes( $photo['name'] ) ) . '"
									/>
									<!-- Size -->';
									if ( wppa_is_video( $id ) ) {
										$result .= '<br>' . wppa_get_videox( $id, 'admin' ) . ' x ' . wppa_get_videoy( $id, 'admin' ) . ' px.';
									}
									else {
										$sp = wppa_get_source_path( $id );
										if ( is_file( $sp ) ) {
											$ima = getimagesize( $sp );
											if ( is_array( $ima ) ) {
												$result .= '<br>' . $ima['0'] . ' x ' . $ima['1'] . ' px.';
											}
										}
									}
									$result .= '
									<!-- Move -->';
									$max = wppa_opt( 'photo_admin_max_albums' );
									if ( ! $max || wppa_get_total_album_count() < $max ) {

										// If not done yet, get the album options html with the current album excluded
										if ( ! isset( $album_select[$album] ) ) {
											$album_select[$album] = wppa_album_select_a( array( 	'checkaccess' 		=> true,
																									'path' 				=> true,
																									'exclude' 			=> $album,
																									'selected' 			=> '0',
																									'addpleaseselect' 	=> true,
																									'sort' 				=> true,
																								)
																						);
										}

										$result .= '
										<br>' . __( 'Target album for move to', 'wp-photo-album-plus' ) . '<br>
										<select
											id="target-' . $id . '"
											onchange="wppaTryMove(' . $id . ', ' . ( wppa_is_video( $id ) ? 'true' : 'false' ) . ')"
											style="max-width:300px"
											>' .
											$album_select[$album] . '
										</select>
										<span id="moving-' . $id . '" style="color:red;font-weight:bold"></span>';
									}
								$result .= '
								</td>';
								wppa_echo( $result );

								$result = '
								<!-- Description -->
								<td style="width:25%">
									<textarea
										class="wppa-bulk-desc"
										style="height:50px; width:100%"
										onchange="wppaAjaxUpdatePhoto(' . $id . ', \'description\', this.value)"
										>' .
										esc_textarea( stripslashes( $photo['description'] ) ) . '
									</textarea>
								</td>
								<!-- Status -->
								<td>';
								if ( current_user_can( 'wppa_admin' ) || current_user_can( 'wppa_moderate' ) )  {
									if ( wppa_switch( 'ext_status_restricted' ) && ! wppa_user_is_admin() ) {
										$dis = ' disabled';
									}
									else {
										$dis = '';
									}
									$ps 	= $photo['status'];
									$sel 	= ' selected';
									$result .= '
									<select
										id="status-' . $id . '"
										onchange="wppaAjaxUpdatePhoto(' . $id . ', \'status\', this.value); wppaPhotoStatusChange(' . $id . ')"
										>
										<option value="pending"' 	. ( $ps == 'pending' 	? $sel : '' ) . '>' . esc_html__( 'Pending' , 'wp-photo-album-plus' ) 	. '</option>
										<option value="publish"' 	. ( $ps == 'publish' 	? $sel : '' ) . '>' . esc_html__( 'Publish' , 'wp-photo-album-plus' ) 	. '</option>
										<option value="featured"' 	. ( $ps == 'featured' 	? $sel : '' ) . $dis . '>' 	. esc_html__( 'Featured' , 'wp-photo-album-plus' ) 	. '</option>
										<option value="gold"' 		. ( $ps == 'gold' 		? $sel : '' ) . $dis . '>' 	. esc_html__( 'Gold' , 'wp-photo-album-plus' ) 		. '</option>
										<option value="silver"' 	. ( $ps == 'silver' 	? $sel : '' ) . $dis . '>' 	. esc_html__( 'Silver' , 'wp-photo-album-plus' ) 	. '</option>
										<option value="bronze"' 	. ( $ps == 'bronze' 	? $sel : '' ) . $dis . '>' 	. esc_html__( 'Bronze' , 'wp-photo-album-plus' ) 	. '</option>
										<option value="scheduled"' 	. ( $ps == 'scheduled' 	? $sel : '' ) . $dis . '>' 	. esc_html__( 'Scheduled' , 'wp-photo-album-plus' ) . '</option>
										<option value="private"' 	. ( $ps == 'private' 	? $sel : '' ) . $dis . '>' 	. esc_html__( 'Private' , 'wp-photo-album-plus' ) 	. '</option>
									</select>';
								}
								else {
									$result .= wppa_status_display_name( $photo['status'] );
								}
								$result .= '
								</td>
								<!-- Owner -->
								<td>' .
									$photo['owner'] . '
								</td>
								<!-- Remark -->
								<td id="remark-' . $id . '" style="width:25%">' .
									esc_html__( 'Not modified' , 'wp-photo-album-plus' ) . '
								</td>
							</tr>';
							wppa_echo( $result );

							wppa_add_inline_script( 'wppa-admin', 'jQuert(document).ready(function(){wppaPhotoStatusChange('.$id.')});' );
						}

					$result = '
					</tbody>
					<tfoot style="font-weight:bold">' .
						$thead_body . '
					</tfoot>
				</table>
			</form>';

			wppa_echo( $result );

			wppa_admin_pagination( $pagesize, $page, $count, $plink, 'bottom' );
		}
		else {
			if ( $page == '1' ) {
				if ( wppa_get( 'searchstring' ) ) {
					wppa_echo( '<h1>' . __( 'No photos matching your search criteria.', 'wp-photo-album-plus' ) . ' 2</h1>' );
				}
				elseif ( $album == 'moderate' ) {
					wppa_echo( '<h1>' . __( 'No photos to moderate', 'wp-photo-album-plus' ) . '</h1>' );
				}
				elseif ( $album == 'trash' ) {
					wppa_echo( '<h1>' . __( 'No photos left', 'wp-photo-album-plus' ) . '</h1>' );
				}
				else {
					wppa_echo( '<h1>' . __( 'No photos yet in this album.', 'wp-photo-album-plus' ) . '</h1>' );
				}
			}
			else {
				$page_1 = $page - '1';
				wppa_echo( sprintf( __( 'Page %d is empty, try <a href="%s">page %d</a>.', 'wp-photo-album-plus' ), $page, $link . '&paged=' . $page_1 . '#manage-photos', $page_1 ) );
			}
		}
	}
}

function wppa_album_photos_sequence( $album ) {
global $wpdb;

	wppa_add_local_js( 'wppa_album_photos_sequence' );

	if ( $album ) {
		$photoorder 	= wppa_get_photo_order( $album, 'norandom' );
		$is_descending 	= strpos( strtolower( $photoorder ), 'desc' ) !== false;
		$is_p_order 	= strpos( strtolower( $photoorder ), 'p_order' ) !== false;

		if ( $is_descending && $is_p_order ) {
			wppa_add_inline_script( 'wppa-admin', 'var wppaSeqnoDesc=true;', false );
		}
		else {
			wppa_add_inline_script( 'wppa-admin', 'var wppaSeqnoDesc=false;', false );
		}

		$photos 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE album = %s" . $photoorder, $album ), ARRAY_A );

		$link 			= get_admin_url().'admin.php?page=wppa_admin_menu&tab=edit&edit-id='.$album.'&bulk=1'.'&wppa-nonce=' . wp_create_nonce('wppa-nonce');
		$size 			= '180';

		if ( $photos ) {
			if ( ! $is_p_order ) wppa_warning_message( __( 'Setting photo sequence has only effect if the photo sequence method is set to <b>Sequence #</b>' , 'wp-photo-album-plus' ) );

			$result = '
			<div class="widefat wppa-photo-admin-sortable" style="border-color:#cccccc">
				<ul id="sortable">';
				foreach ( $photos as $photo ) {
					$id = $photo['id'];
					if ( wppa_is_video( $id ) ) {
						$imgs['0'] = wppa_get_videox( $id, 'admin' );
						$imgs['1'] = wppa_get_videoy( $id, 'admin' );
					}
					else {
						$imgs['0'] = wppa_get_thumbx( $id );
						$imgs['1'] = wppa_get_thumby( $id );
					}
					if ( ! $imgs['0'] ) {	// missing thuimbnail, prevent division by zero
						$imgs['0'] = 200;
						$imgs['1'] = 150;
					}
					$mw = $size - '20';
					$mh = $mw * '3' / '4';
					if ( $imgs[1]/$imgs[0] > $mh/$mw ) {	// more portrait than 200x150, y is limit
						$mt = '15';
					}
					else {	// x is limit
						$mt = ( $mh - ( $imgs[1]/$imgs[0] * $mw ) ) / '2' + '15';
					}

					$result .= '
					<li
						id="photoitem-' . $id . '"
						class="ui-state-default-photos wppa-' . $photo['status'] . '"
						style="background-image:none; text-align:center; cursor:move"
						>';
						if ( wppa_is_video( $id ) ) {
							$imgstyle = 'max-width:'.$mw.'px;max-height:'.$mh.'px;margin-top:'.$mt.'px;';
							$result .= wppa_get_video_html( array(
								'id'			=> $id,
								'controls' 		=> false,
								'tagid' 		=> 'pa-id-'.$id,
								'preload' 		=> 'metadata',
								'class' 		=> 'wppa-bulk-thumb',
								'style' 		=> $imgstyle,
								'use_thumb' 	=> true
								) );
						}
						else {
							$result .= '
							<img
								class="wppa-bulk-thumb"' .
								( wppa_lazy() ? ' data-' : ' ' ) . 'src="' . esc_url( wppa_get_thumb_url( $id ) ) . '"
								style="max-width:' . $mw . 'px;max-height:' . $mh . 'px;margin-top:' . $mt . 'px"
							/>';
						}
						$result .= '
						<div
							style="font-size:9px;position:absolute;bottom:24px;text-align:center;width:' . $size . 'px">' .
							wppa_get_photo_name( $id ) . '
						</div>
						<div
							style="text-align:center;width:' . $size . 'px;position:absolute;bottom:8px"
							>
							<span
								style="margin-left:15px;float:left">' .
								esc_html__( 'Id: ' , 'wp-photo-album-plus' ) . $id . '
							</span>';

							if ( wppa_is_video( $id ) ) $result .= __( 'Video', 'wp-photo-album-plus' );
							if ( wppa_has_audio( $id ) ) $result .= __( 'Audio', 'wp-photo-album-plus' );

							$result .= '
							<span style="float:right;margin-right:15px">' .
								esc_html__( 'Seq: ' , 'wp-photo-album-plus' ) . '
								<span id="wppa-seqno-' . $id . '">' .
									$photo['p_order'] . '
								</span>
							</span>
						</div>
						<input type="hidden" id="photo-nonce-' . $id . '" value="' . wp_create_nonce( 'wppa-nonce_' . $id ) . '" />
						<input type="hidden" class="wppa-sort-item" value="' . $id . '" />
						<input type="hidden" class="wppa-sort-seqn" id="wppa-sort-seqn-' . $id . '" value="' . $photo['p_order'] . '" />
					</li>';
				}

				$result .= '
				</ul>
				<div style="clear:both"></div>
			</div>';
			wppa_echo( $result );
		}
		else {
			wppa_echo( '<h1>'.__( 'The album is empty.' , 'wp-photo-album-plus' ).'</h1>' );
		}
	}
	else {
		wppa_log( 'Err', 'Missing required argument in wppa_album_photos() 3' );
	}
}

function wppa_get_edit_search_photos( $skip = '0', $pagesize = '1000', $count_only = false ) {
global $wpdb;
global $wppa_search_stats;

	$doit = false;

	if ( current_user_can( 'wppa_admin' ) && current_user_can( 'wppa_moderate' ) ) $doit = true;
	if ( wppa_opt( 'upload_edit' ) != '-none-' ) $doit = true;
	if ( ! $doit ) {	// Should never get here. Only when url is manipulted manually.
		die('Security check failure #309');
	}

	$words = explode( ',', wppa_get( 'searchstring' ) );

	$wppa_search_stats = array();

	$photo_array = array();

	// First find the id if numeric
	if ( wppa_user_is_admin() ) {
		foreach ( $words as $word ) {
			if ( wppa_is_int( $word ) ) {
				if ( wppa_photo_exists( $word ) ) {
					$photo_array[] = $word;
				}
			}
		}
		asort( $photo_array );
		if ( ! empty ( $photo_array ) ) {
			$ids = count( $photo_array ) == 1 ? 'ID' : 'IDs';
			$wppa_search_stats[] = array( 'word' => $ids, 'count' => count( $photo_array ) );
		}
	}

	// Process normal serch
	foreach( $words as $word ) {

		// Find lines in index db table
		if ( wppa_switch( 'wild_front' ) ) {
			$pword = '%' . $wpdb->esc_like( $word ) . '%';
		}
		else {
			$pword = $wpdb->esc_like( $word ) . '%';
		}
		$pidxs = $wpdb->get_results( $wpdb->prepare( "SELECT slug, photos
													  FROM $wpdb->wppa_index
													  WHERE slug LIKE %s", $pword ), ARRAY_A );
		$photos = array();

		// Accumulate photo ids
		foreach ( $pidxs as $pi ) {

			$delta_arr = wppa_index_string_to_array( trim( $pi['photos'], ',' ) );
			$photos = array_merge( $photos, $delta_arr );

		}
		$photos = array_unique( $photos, SORT_NUMERIC );

		$delta_arr = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE name = %s OR filename = %s", $word, $word ) );
		$photos = array_merge( $photos, $delta_arr );

		// If not admin, remove not owned photos from array
		if ( ! current_user_can( 'wppa_admin' ) || ! current_user_can( 'wppa_moderate' ) ) {
			$u = wppa_get_user();
			foreach( array_keys( $photos ) as $k ) {
				$id = $photos[$k];
				if ( $wpdb->get_var( $wpdb->prepare( "SELECT owner FROM $wpdb->wppa_photos WHERE id = %d", $id ) ) != $u ) {
					unset( $photos[$k] );
				}
			}
		}

		$count = count( $photos );

		$wppa_search_stats[] = array( 'word' => $word, 'count' => $count );

		// Accumulate found ids
		$photo_array = array_merge( $photo_array, $photos );
	}

	// Compute total
	if ( ! empty( $photo_array ) ) {

		$c1 = count( $photo_array );
		$photo_array = array_unique( $photo_array );
		$c2 = count( $photo_array );
		$dups = $c1 - $c2;

		$totcount = count( $photo_array );

		$wppa_search_stats[] = array( 'word' => __( 'Combined', 'wp-photo-album-plus' ), 'count' => $totcount );
		if ( $dups ) {
			$wppa_search_stats[] = array( 'word' => __( 'Duplicates', 'wp-photo-album-plus' ), 'count' => $dups );
		}
		else  {
			$wppa_search_stats[] = array( 'word' => '', 'count' => '' );
		}

		$photos = array();
		sort( $photo_array, SORT_NUMERIC );

		$photo_array = array_reverse( $photo_array );

		// Find the photo data
		$s = $skip;
		$l = $pagesize;

		if ( $count_only ) {
			$photos = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE id IN (".implode(',',$photo_array).") " );
		}
		else {
			$photos = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos WHERE id IN (".implode(',',$photo_array).") LIMIT $skip, $pagesize", ARRAY_A );
		}
	}
	else {
		$photos = array();
	}
	return $photos;
}

function wppa_show_search_statistics() {
global $wppa_search_stats;

	if ( wppa_get( 'searchstring' ) ) {
		wppa_echo( '
		<table>
			<thead>
				<tr>
					<td><b>' .
						__('Word', 'wp-photo-album-plus' ) . '
					</b></td>
					<td><b>' .
						__('Count', 'wp-photo-album-plus' ) . '
					</b></td>
				</tr>
				<tr>
					<td><hr /></td>
					<td><hr /></td>
				</tr>
			</thead>
			<tbody>' );
			$count = empty( $wppa_search_stats ) ? '0' : count( $wppa_search_stats );
			$c = '0';
			$s = '';
			foreach( $wppa_search_stats as $search_item ) {
				$c++;
				if ( $c == ( $count - 1 ) ) {
					wppa_echo( '<tr><td><hr /></td><td><hr /></td></tr>' );
					$s = 'style="font-weight:bold"';
				}
				if ( $search_item['word'] ) {
				wppa_echo( '
					<tr>
						<td '.$s.'>' .
							$search_item['word'] . '
						</td>
						<td '.$s.'>' .
							$search_item['count'] . '
						</td>
					</tr>' );
				}
			}
		wppa_echo( '</table>' );
	}
}

// New style fron-end edit photo
function wppa_fe_edit_photo( $photo ) {

	$items 	= array( 	'upn-name',
						'upn-description',
						'upn-tags',
						'custom_0',
						'custom_1',
						'custom_2',
						'custom_3',
						'custom_4',
						'custom_5',
						'custom_6',
						'custom_7',
						'custom_8',
						'custom_9',
						);
	$titles = array( 	__( 'Name', 'wp-photo-album-plus' ),
						__( 'Description', 'wp-photo-album-plus' ),
						__( 'Tags', 'wp-photo-album-plus' ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_0' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_1' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_2' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_3' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_4' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_5' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_6' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_7' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_8' ) ),
						apply_filters( 'translate_text', wppa_opt( 'custom_caption_9' ) ),
						);
	$types 	= array( 	'text',
						'textarea',
						'text',
						( wppa_opt( 'custom_default_0' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_1' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_2' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_3' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_4' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_5' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_6' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_7' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_8' ) == 'multi' ? 'textarea' : 'text' ),
						( wppa_opt( 'custom_default_9' ) == 'multi' ? 'textarea' : 'text' ),
						);
	$doit 	= array(	wppa_switch( 'fe_edit_name' ),
						wppa_switch( 'fe_edit_desc' ),
						wppa_switch( 'fe_edit_tags' ),
						wppa_switch( 'custom_edit_0' ),
						wppa_switch( 'custom_edit_1' ),
						wppa_switch( 'custom_edit_2' ),
						wppa_switch( 'custom_edit_3' ),
						wppa_switch( 'custom_edit_4' ),
						wppa_switch( 'custom_edit_5' ),
						wppa_switch( 'custom_edit_6' ),
						wppa_switch( 'custom_edit_7' ),
						wppa_switch( 'custom_edit_8' ),
						wppa_switch( 'custom_edit_9' ),
						);

	$nice 		= wppa_is_nice();

	// Open page
	wppa_echo( '
		<div
			id="wppa-fe-edit"
			style="width:100%;
					margin-top:8px;
					padding:8px;
					display:block;
					box-sizing:border-box;
					background-color:#fff;' .
					( $nice ? ' overflow:hidden;' : ' overflow:auto;' ) . '"
			class="wppa-edit-area wppa-modal">' .
			( $nice ? '<div class="wppa-nicewrap" style="padding-bottom:30px">' : '' ) . '
			<h1>
				<img
					style="height:50px"' .
					' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . esc_url( wppa_get_thumb_url( $photo ) ) . '" ' .
					wppa_get_imgalt( $photo ) . '
				/>
				&nbsp;&nbsp;' .
				wppa_opt( 'fe_edit_caption' ) . '
			</h1>' );

	// Open form
	wppa_echo( '
		<form>
			<input
				type="hidden"
				id="wppa-nonce-' . wppa_encrypt_photo( $photo ) . '"
				name="wppa-nonce"
				value="' . wp_create_nonce( 'wppa-nonce-' . $photo ) . '"
			/>' );

	// Get custom data
	$custom = wppa_get_photo_item( $photo, 'custom' );
	if ( $custom ) {
		$custom_data = wppa_unserialize( $custom );
	}
	else {
		$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
	}

	// Items
	foreach ( array_keys( $items ) as $idx ) {

		// If the item has a non-empty title and the edit switch is on (see Advanced settings -> Adsmin -> V ->L Item 5 and Advanced settings -> Custom data -> II)
		if ( $titles[$idx] && $doit[$idx] ) {

			// Caption
			wppa_echo( '<h6>' . $titles[$idx] . '</h6>' );

			// custom item
			if ( wppa_is_int( substr( $items[$idx], -1 ) ) ) {
				$value = stripslashes( $custom_data[substr( $items[$idx], -1 )] );
			}

			// Name, desc or tags
			else {
				$value = wppa_get_photo_item( $photo, str_replace( 'upn-', '', $items[$idx] ) );
				if ( $items[$idx] == 'upn-tags' ) {
					$value = trim( $value, ',' );
				}
			}

			// Either a single line text item...
			if ( $types[$idx] == 'text' ) {
				wppa_echo(
					'<input' .
						' type="text"' .
						' style="width:100%;box-sizing:border-box;"' .
						' id="' . $items[$idx] . '"' .
						' name="' . $items[$idx] . '"' .
						' value="' . esc_attr( $value ) . '"' .
					' />' );
			}

			// Or a multiline text item
			if ( $types[$idx] == 'textarea' ) {

				// Visual editor, see Advanced settings -> Admin -> VI -> Item 14
				if ( wppa_switch( 'use_wp_editor' ) ) {

					wp_editor(  $value,
								$items[$idx],
								array( 	'wpautop' 		=> true,
										'media_buttons' => false,
										'textarea_rows' => '6',
										'tinymce' 		=> true
								)
							);
					static $been_here;
					if ( ! $been_here ) {
						global $wp_scripts;
						$wp_scripts->reset();
						\_WP_Editors::enqueue_scripts(true);
						print_footer_scripts();
						$been_here = true;
					}
					\_WP_Editors::editor_js();
				}

				// Simple textarea
				else {
					wppa_echo( '
						<textarea
							style="width:100%;box-sizing:border-box;"
							id="' . $items[$idx] . '"
							name="' . $items[$idx] . '"
							>' .
							esc_textarea( stripslashes( $value ) ) . '
						</textarea>' );
				}
			}
		}
	}

	// Submit
	wppa_echo( '
		<input
			type="button"
			style="margin-top:8px;margin-right:8px"
			value="' . esc_attr( __( 'Send', 'wp-photo-album-plus' ) ) . '"
			onclick="jQuery(this).attr(\'value\',\''.__('Working...', 'wp-photo-album-plus').'\');
					 wppaUpdatePhotoNew(\'' . wppa_encrypt_photo( $photo ) . '\',\'' . wppa_get( 'occur' ) . '\');"
		/>' );

	// Cancel
	wppa_echo( '
		<input
			type="button"
			style="margin-top:8px"
			value="' . esc_attr( __( 'Cancel', 'wp-photo-album-plus' ) ) . '"
			onclick="jQuery( \'#wppa-modal-container-' . wppa_get( 'occur' ) . '\').dialog(\'close\')"
		/>' );

	// Reload after
	$rela = get_transient( 'wppa_rela_' . wppa_get_user() ) == 'on';
	wppa_echo( '
		<input
			type="checkbox"
			id="upn-reload"
			name="upn-reload"
			style="margin:8px"' .
			( $rela ? ' checked="checked"' : '' ) . '
		/>
		<label for="upn-reload"
			style="display:inline;"
			>' .
			__( 'Reload after edit', 'wp-photo-album-plus' ) . '
		</label>' );

	// Close form
	wppa_echo( '</form>' );
	wppa_echo( '<div style="clear:both"></div>' );


	// Nicescroller
	if ( $nice ) {
		wppa_echo( '</div>' ); 	// close .wppa-nicewrap div
	}

	// Close page
	wppa_echo( '</div>' );

}

// See if this photo needs the ImageMagick features
function wppa_can_admin_magick( $id ) {

	// Is ImageMagick on board?
	if ( ! wppa_can_magick() ) {
		return false;
	}

	// Is it a video?
	if ( wppa_is_video( $id ) ) {
		return false;
	}

	return true;
}
