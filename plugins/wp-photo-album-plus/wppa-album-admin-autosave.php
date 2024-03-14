<?php
/* wppa-album-admin-autosave.php
* Package: wp-photo-album-plus
*
* create, edit and delete albums
* Version 8.6.04.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function _wppa_admin() {
global $wpdb;
global $q_config;
global $wppa_revno;

	// Add local javascript
	wppa_add_local_js( '_wppa_admin' );

	// From search page or from menu?
	$from = wppa_get_option( 'wppa_search_page', 'wppa_admin_menu' );
	delete_option( 'wppa_search_page' );

	// Create the back to ... link and top of page link html
	{
		if ( $from == 'wppa_admin_menu' ) {
			$back_url = get_admin_url() . 'admin.php?page=wppa_admin_menu';
			$back_title = __( 'Back to album table', 'wp-photo-album-plus' );
		}
		elseif ( $from == 'wppa_search' ) {
			$back_url = get_admin_url() . 'admin.php?page=wppa_search';
			$back_title = __( 'Back to search form', 'wp-photo-album-plus' );

			if ( wppa_get( 'edit-id' ) == 'search' ) {

					if ( wppa_get( 'searchstring' ) ) {
						$back_url .= '&wppa-searchstring=' . wppa_get( 'searchstring' );
					}
					$back_url .= '#wppa-edit-search-tag';
			}
		}
		$back_link_html = '
		<div style="position:fixed;right:20px;background-color:lightblue;top:50px;z-index:3;">
			&nbsp;
			<a href="' . $back_url . '"
				style=""
				>' .
				$back_title . '
			</a>
			&nbsp;
		</div>';

		$top_link_html = '
		<div style="position:fixed;right:20px;background-color:lightblue;bottom:30px;z-index:3;" >
			&nbsp;
			<a href="#manage-photos">' .
				__( 'Top of page', 'wp-photo-album-plus' ) . '
			</a>
			&nbsp;
		</div>';
	}

	// Delete trashed comments
	wppa_del_row( WPPA_COMMENTS, 'status', 'trash' );

	// warn if the uploads directory is no writable
	if ( ! is_writable( WPPA_UPLOAD_PATH ) ) {
		wppa_error_message(
			__( 'Warning:', 'wp-photo-album-plus' ) .
			sprintf( __( 'The uploads directory does not exist or is not writable by the server. Please make sure that %s is writeable by the server.', 'wp-photo-album-plus' ),
			WPPA_UPLOAD_PATH
			) );
	}

	// Get all albums and cache them
	$albs = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums", ARRAY_A );
	wppa_cache_album( 'add', $albs );

	// Fix orphan albums and deleted target pages
	if ( $albs ) {
		foreach ( $albs as $alb ) {
			if ( $alb['a_parent'] > '0' && wppa_get_parentalbumid( $alb['a_parent'] ) <= '-9' ) {	// Parent died?
				wppa_update_album( $alb['id'], ['a_parent' => -1] );
			}
			if ( $alb['cover_linkpage'] > '0' ) {
				$iret = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*)
														 FROM $wpdb->posts
														 WHERE ID = %d
														 AND ( post_type = 'page' OR post_type = 'post' )
														 AND post_status = 'publish'", $alb['cover_linkpage'] ) );

				if ( ! $iret ) {	// Page gone?
					wppa_update_album( $alb['id'], ['cover_linkpage' => 0] );
				}
			}
		}
	}

	// 'tab' set? If so, check nonce and see what we are going to do
	if ( wppa_get( 'tab' ) ) {

		// Check nonce field
		if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
			wp_die('Security check failure 1');
		}

		// album edit page
		if ( wppa_get( 'tab' ) == 'edit' ) {

			// Edit any album, check for type is implemented c.q. existence and rights
			if ( wppa_get( 'edit-id' ) ) {

				// Validate input
				$ei = wppa_get( 'edit-id' );
				if ( $ei != 'new' && $ei != 'search' && $ei != 'trash' && $ei != 'single' && ! is_numeric( $ei ) ) {
					wppa_error_message( sprintf( __( 'Album edit id %s is not implemented', 'wp-photo-album-plus' ), $ei ) );
					return;
				}

				if ( is_numeric( $ei ) ) {
					if ( ! $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE id=%d", $ei ), ARRAY_A ) ) {
						wppa_error_message( sprintf( __( 'Album %d does not exist', 'wp-photo-album-plus' ), $ei ) );
						return;
					}
					if ( ! wppa_have_access( $ei ) ) {
						wppa_error_message( sprintf( __( 'You do not have sufficient rights to edit album %d', 'wp-photo-album-plus' ), $ei ) );
						return;
					}
				}
			}

			// Edit single photo
			if ( wppa_get( 'edit-id' ) == 'single' ) {

				$page_title = wppa_get( 'just-edit', __( 'Edit Single media item', 'wp-photo-album-plus' ) );

				wppa_echo( '<div class="wrap" ><h1 class="wp-heading-inline">' . $page_title . '</h1>' );
				wppa_album_photos( $ei );
				wppa_echo( '</div>' );
				return;
			}

			// Edit by search token
			if ( wppa_get( 'edit-id' ) == 'search' ) {

				$bulk = wppa_get( 'bulk' );

				wppa_echo ( '
				<a name="manage-photos" id="manage-photos" ></a>
				<div class="wrap">' );

					// The page title
					if ( $bulk ) {
						$page_title = sprintf( __( 'Bulk edit media items searched by %s', 'wp-photo-album-plus' ), '<i>' . wppa_get( 'searchstring', '', 'text' ) . '</i>' );
					}
					else {
						$page_title = sprintf( __( 'Edit media items searched by %s', 'wp-photo-album-plus' ), '<i>' . wppa_get( 'searchstring', '', 'text' ) . '</i>' );
					}

					wppa_echo( '
					<h1 class="wp-heading-inline">' . $page_title . '</h1>' .
					$back_link_html . '
					<br><br>' );

					// Do the dirty work
					if ( $bulk ) {
						wppa_album_photos_bulk( $ei );
					}
					else {
						wppa_album_photos( $ei );
					}

					wppa_echo( $top_link_html );//. '<br>' . $back_link_html );
				wppa_echo( '</div>' );
				return;
			}

			// Edit trashed photos
			if ( wppa_get( 'edit-id' ) == 'trash' ) {

				$h2   = __( 'Manage Trashed Photos', 'wp-photo-album-plus' );
				$task = __( 'Edit photo information', 'wp-photo-album-plus' );
				wppa_echo( '<div class="wrap"><a name="manage-photos" id="manage-photos" ></a><h1>' . $h2 . ' - <small><i>' . $task . '</i></small></h1>' );
				if ( wppa_get( 'bulk' ) ) {
					wppa_album_photos_bulk( $ei );
				}
				else {
					wppa_album_photos( $ei );
				}
				wppa_echo( '</div>' );

				return;
			}

			// Edit new album
			if ( wppa_get( 'edit-id' ) == 'new' ) {

				if ( ! wppa_can_create_album() ) {
					wp_die( __( 'You have insufficient rights to create an album', 'wp-photo-album-plus' ) );
				}
				$id = wppa_nextkey( WPPA_ALBUMS );

				// Creating a sub album of a given parent?
				if ( wppa_get( 'parent_id' ) ) {
					$parent = wppa_get( 'parent_id' );
					$name = wppa_get_album_name( $parent ) . '-#' . $id;
					if ( ! current_user_can( 'administrator' ) ) {	// someone creating an album for someone else?
						$parentowner = $wpdb->get_var( $wpdb->prepare( "SELECT owner FROM $wpdb->wppa_albums WHERE id = %s", $parent ) );
						if ( $parentowner !== wppa_get_user() ) {
							wp_die( __( 'You are not allowed to create an album for someone else', 'wp-photo-album-plus' ) );
						}
					}
				}

				// Create album with default parent or toplevel
				else {
					$parent = wppa_opt( 'default_parent' );

					// Default parent still exists?
					if ( ! $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE id = %s", $parent ) ) ) {
						wppa_update_option( 'wppa_default_parent', '0' );
						$parent = '0';
					}
					$name = __( 'New album', 'wp-photo-album-plus' );
					if ( $parent == '0' && ! wppa_can_create_top_album() ) {
						wp_die( __( 'You have insufficient rights to create a top-level album', 'wp-photo-album-plus' ) );
					}
				}

				// Finally, now we can create the album
				$id = wppa_create_album_entry( ['id' 		=> $id,
												'name' 		=> $name,
												'a_parent' 	=> $parent,
												'owner' 	=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
												] );
				if ( ! $id ) {
					wppa_error_message( __( 'Could not create album.', 'wp-photo-album-plus' ) );
					wp_die( __( 'Sorry, cannot continue', 'wp-photo-album-plus' ) );
				}

				// Album created, do housekeeping and go edit it.
				else {
					wppa_set_last_album( $id );
					wppa_invalidate_treecounts( $id );
					wppa_index_update( 'album', $id );
					$sib_id = wppa_get( 'is-sibling-of', '0' );

					if ( $sib_id > 0 ) {

						// Get siblings data to inherit
						$sib_alb = wppa_cache_album( $sib_id );
						wppa_update_album( $id, ['cover_type' => $sib_alb['cover_type'], 'cover_linktype' => $sib_alb['cover_linktype'], 'main_photo' => ( $sib_alb['main_photo'] < '0' ? $sib_alb['main_photo'] : '0' )] );
					}

					wppa_update_message( sprintf( __( 'Album #%d added', 'wp-photo-album-plus' ), $id ) );
					wppa_create_pl_htaccess();
					$edit_id = $id;
				}
			}

			// Edit by album id
			else {
				$edit_id = wppa_get( 'edit-id' );
			}

			// See if this user may edit this album
			$album_owner = $wpdb->get_var( $wpdb->prepare( "SELECT owner FROM $wpdb->wppa_albums WHERE id = %s", $edit_id ) );
			if ( ( $album_owner == '--- public ---' && ! current_user_can( 'wppa_admin' ) ) || ! wppa_have_access( $edit_id ) ) {
				wp_die( __( 'You have insufficient rights to edit this album', 'wp-photo-album-plus' ) );
			}

			/* Start pre-edit actions */

			// Apply new desc
			if ( wppa_get( 'applynewdesc' ) ) {
				if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
					wp_die( __( 'You do not have the rights to do this', 'wp-photo-album-plus' ) );
				}
				$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_photos
													   SET description = %s
													   WHERE album = %d", wppa_opt( 'newphoto_description' ), $edit_id ) );
				wppa_ok_message( sprintf( __( '%d photo descriptions updated', 'wp-photo-album-plus' ), $iret ) );
			}
			
			// Clear Descriptions
			if ( wppa_get( 'cleardesc', '0', 'int' ) ) {
				if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
					wp_die( __( 'You do not have the rights to do this', 'wp-photo-album-plus' ) );
				}
				$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_photos
													   SET description = ''
													   WHERE album = %d", $edit_id ) );
				wppa_ok_message( sprintf( __( '%d item descriptions cleared', 'wp-photo-album-plus' ), $iret ) );
			}

			// Remake album
			if ( wppa_get( 'remakealbum' ) ) {
				if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
					wp_die( __( 'You do not have the rights to do this', 'wp-photo-album-plus' ) );
				}

				// Continue after time up?
				if ( wppa_get_option( 'wppa_remake_start_album_' . $edit_id ) ) {
					wppa_ok_message( __( 'Continuing remake, please wait...', 'wp-photo-album-plus' ) );
				}
				else {
					wppa_update_option( 'wppa_remake_start_album_' . $edit_id, time() );
					wppa_ok_message( __( 'Remaking photofiles, please wait...', 'wp-photo-album-plus' ) );
				}

				// Do the remake
				$iret = wppa_remake_files( $edit_id );
				if ( $iret ) {
					wppa_ok_message( __( 'Photo files remade', 'wp-photo-album-plus' ) );
					wppa_update_option( 'wppa_remake_start_album_' . $edit_id, '0' );
				}
				else {
					wppa_error_message( __( 'Remake of photo files did NOT complete', 'wp-photo-album-plus' ) );
				}
			}

			// Prepare update message
			$remark = sprintf( __( 'Album %s is not modified yet', 'wp-photo-album-plus' ), $edit_id );

			// Set all to pano
			$timeup = false;
			$pano = wppa_get( 'pano-val', '9' );
			if ( in_array( $pano, array( '0', '1', '2' ) ) ) {

				$done = '0';
				$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, photox, photoy, panorama, angle FROM $wpdb->wppa_photos
															 WHERE album = %d

															 AND ext = 'jpg'
															 AND panorama <> %d
															 ORDER BY id", $edit_id, $pano ), ARRAY_A );
				$tot = count( $todo );

				if ( $tot ) foreach( $todo as $item ) {

					// Init this item is not panoramable
					$doit = false;

					// width must be > 1.99 * height
					$id = $item['id'];
					$x 	= $item['photox'];
					$y 	= $item['photoy'];
					if ( $x > 1.99 * $y ) $doit = true;
					if ( ! $doit ) {
						$x = wppa_get_photox( $id, true );
						$y = wppa_get_photoy( $id, true );
						if ( $x > 1.99 * $y ) $doit = true;
					}

					// Source must exist
					$s = wppa_get_source_path( $id );
					if ( ! wppa_is_file( $s ) ) $doit = false;

					// Process this item
					if ( $doit ) {

						// Clear possible existing o1 file
						$o1 = wppa_get_o1_source_path( $id );
						if ( wppa_is_file( $o1 ) ) {
							wppa_unlink( $o1 );
						}

						// Do pano type specific stuff
						switch( $pano ) {

							case '0': // No longer pano
								wppa_update_photo( $id, ['panorama' => '0', 'angle' => '0'] );
								break;

							case '1': // Spheric
								wppa_update_photo( $id, ['panorama' => '1', 'angle' => '360'] );
								wppa_make_360( $id, 360 );
								break;

							case '2': // Flat
								wppa_update_photo( $id, ['panorama' => '2', 'angle' => '0'] );
								break;

							default:

								break;
						}

						// Housekeeping
						wppa_remake_files( '', $id );
						wppa_get_photox( $id, true );
						wppa_get_photoy( $id, true );
						$done++;

						if ( ! in_array( $pano, array( '0', '1', '2' ) ) ) {
							$remark = __( 'No items processed', 'wp-photo-album-plus' );
						}
						elseif ( $done == $tot ) {
							$remark = __( 'All applicable items processed', 'wp-photo-album-plus' );
						}
						else {
							$remark = sprintf( __( '%1d items out of %2d processed', 'wp-photo-album-plus' ), $done, $tot );
						}
					}

					// Check for timeout and not done
					$timeup = wppa_is_time_up() && ( $done != $tot );
					if ( $timeup ) break;
				}
				else {
					$remark = __( 'No items to process', 'wp-photo-album-plus' );
				}

				if ( $timeup ) {
					$remark .= ' ' . __( 'No time left, please reload the page to continue.', 'wp-photo-album-plus' );
				}
			}

			/* End pre-edit operations */

			// Get the album information
			{
			$albuminfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
														  WHERE id = %s", $edit_id ), ARRAY_A );

			// We may not use extract(), so we do something like it here manually, hence controlled.
			$id 			= $albuminfo['id'];
			$crypt 			= $albuminfo['crypt'];
			$timestamp 		= $albuminfo['timestamp'];
			$modified 		= $albuminfo['modified'];
			$views 			= $albuminfo['views'];
			$owner 			= $albuminfo['owner'];
			$a_order 		= $albuminfo['a_order'];
			$p_order_by 	= $albuminfo['p_order_by'];
			$a_parent 		= $albuminfo['a_parent'];
			$suba_order_by 	= $albuminfo['suba_order_by'];
			$name 			= stripslashes( $albuminfo['name'] );
			$description 	= stripslashes( $albuminfo['description'] );
			$alt_thumbsize 	= $albuminfo['alt_thumbsize'];
			$cover_type 	= $albuminfo['cover_type'];
			$main_photo 	= $albuminfo['main_photo'];
			$upload_limit 	= $albuminfo['upload_limit'];
			$tree_limit 	= $albuminfo['upload_limit_tree'];
			$cats 			= stripslashes( trim( $albuminfo['cats'], ',' ) );
			$default_tags 	= trim( $albuminfo['default_tags'], ',' );
			$cover_linktype = $albuminfo['cover_linktype'];
			$cover_link 	= $albuminfo['cover_link'];
			$sname 			= $albuminfo['sname'];
			$zoomable 		= $albuminfo['zoomable'];
			$displayopts 	= $albuminfo['displayopts'];
			$scheduledel 	= $albuminfo['scheduledel'];
			$status 		= $albuminfo['status'];
			$max_children 	= $albuminfo['max_children'];
			$treecounts 	= wppa_get_treecounts_a( $id, true );
			$pviews 		= $treecounts['selfphotoviews'];
			$tpviews 		= $treecounts['treephotoviews'];
			$nsub 			= $treecounts['selfalbums'];
			$has_children  	= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE a_parent = %d", $id ) );
			$indexdtm 		= $albuminfo['indexdtm'];
			$usedby 		= $albuminfo['usedby'] ? explode( ".", trim( $albuminfo['usedby'], '. ' ) ) : array();

			// Get number of allowed uploads, -1 is unlimited
			$a = wppa_allow_uploads( $id );
			$full = $a ? false : true;

			}

			// Local js functions
			$the_js = 'jQuery(document).ready(function(){wppaGetCoverPreview('.$id.', "cover-preview-"+'.$id.')});';
			wppa_add_inline_script( 'wppa-admin', $the_js, false );

			/* The actual page lay-out starts here */

			// Open the photo album admin page
			wppa_echo( '<div class="wrap">' );

				// The spinner to indicate busyness
				wppa_admin_spinner();

				// The nonce field for security
				wppa_echo( '<input type="hidden" id="album-nonce-' . $id . '" value="' . wp_create_nonce( 'wppa-nonce_' . $id ) . '" />' );

				// The header
				if ( wppa_get( 'bulk' ) ) {
					$page_title = __( 'Albums -> Bulk edit -> %s', 'wp-photo-album-plus' );
				}
				elseif ( wppa_get( 'quick' ) ) {
					$page_title = __( 'Albums -> Quick edit -> %s', 'wp-photo-album-plus' );
				}
				elseif ( wppa_get( 'seq' ) ) {
					$page_title = __( 'Albums -> Sequence edit -> %s', 'wp-photo-album-plus' );
				}
				else {
					$page_title = __( 'Albums -> Edit -> %s', 'wp-photo-album-plus' );
				}
				wppa_echo( '<h1 class="wp-heading-inline">' . esc_html( sprintf( $page_title, __( wppa_get_album_name( $id ) ) ) ) . '</h1>' );


				wppa_echo( $back_link_html );

				/* Start header buttons */
				$result = '
				<div id="wppa-action-container">';

					// Goto Upload
					if ( current_user_can( 'wppa_upload' ) ) {

						$onc = ( $full ?
									'alert(\''.__( 'Change the upload limit or remove photos to enable new uploads.', 'wp-photo-album-plus' ).'\')' :
									'document.location = \''.get_admin_url().'/admin.php?page=wppa_upload_photos&wppa-set-album='.$id.'\''
								);
						$val = ( $full ?
									__( 'Album is full', 'wp-photo-album-plus' ) :
									__( 'Upload to this album', 'wp-photo-album-plus' ) . ( $a > '0' ? ' ' . sprintf( __( '(max %d)', 'wp-photo-album-plus' ), $a ) : '' )
								);

						$result .= '
						<input
							type="button"
							class="wppa-admin-button button"
							onclick="' . $onc . '"
							value="' . $val .'"
						/>';
					}

					// Goto Import
					if ( current_user_can( 'wppa_import' ) && ! $full ) {

						$onc = 'document.location = \''.get_admin_url().'admin.php?page=wppa_import_photos&wppa-set-album='.$id.'\'';
						$val = __( 'Import to this album', 'wp-photo-album-plus' ) . ( $a > '0' ? ' ' . sprintf( __( '(max %d)', 'wp-photo-album-plus' ), $a ) : '' );

						$result .= '
						<input
							type="button"
							class="wppa-admin-button button"
							onclick="' . $onc . '"
							value="' . $val .'"
						/>';
					}

					// Download album
					if ( wppa_switch( 'allow_download_album' ) && ( ! wppa_switch( 'download_album_is_restricted' ) || wppa_user_is_admin() ) ) {

						$result .= '
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="wppaAjaxDownloadAlbum( 0, ' . $albuminfo['id'] . ' );"
								value="' . esc_attr( __( 'Download album', 'wp-photo-album-plus' ) ).'"
							/>
							<img
								id="dwnspin-0-' . $albuminfo['id'] . '"
								src="' . wppa_get_imgdir() . 'spinner.gif"
								style="margin-left:6px;display:none;height:18px;position:relative;bottom:-6px"
								alt="spinner"
							/>';
					}

				$result .= '
				</div>'; 	// end action-container
				wppa_echo( $result );
				/* End header */

				/* Main body album admin */
				echo( '
				<details class="wppa-toplevel-details" ' . ( $timestamp > time() - 3600 ? 'open' : '' ) . '>
					<summary
						class="toplevel wppa-summary-toplevel"
						onclick="setTimeout(function(){jQuery(window).trigger(\'resize\');},200);"
						> ' .
						 __( 'the album settings', 'wp-photo-album-plus' ) . '
					</summary>' );

					// Expalanation
					wppa_echo( '
					<h2 class="description" style="margin:1em">' .
						__( 'All modifications are instantly updated on the server, except for those that require a button push.', 'wp-photo-album-plus' ) . ' ' .
						__( 'After entering/modification of text, click outside the textfield to get it updated.', 'wp-photo-album-plus' ) . '
						<br>' . __( 'The <b style="color:#070" >Remark</b> fields keep you informed on the actions taken at the background.', 'wp-photo-album-plus' ) . '
					</h2>' );

					// Status - Remark - field
					wppa_echo( '
					<h3 style="margin-left:1em">' .
						esc_html__( 'Remark', 'wp-photo-album-plus' ) . ':&nbsp;
						<span
							id="albumstatus-' . $id . '"
							style="font-weight:bold;color:#00AA00">' .
							esc_html( $remark ) . '
						</span>
					</h3>' );

					// The tabs
					wppa_echo( '
					<div id="tabs" style="margin-bottom:23px">
						<ul class="widefat wppa-setting-tabs">
							<li class="wppa-albumadmin-tab active" onclick="wppaChangeAlbumAdminTab(this,\'#albumitem-' . $id . '\');">' . __( 'Album', 'wp-photo-album-plus' ) . '</li>
							<li class="wppa-albumadmin-tab" onclick="wppaChangeAlbumAdminTab(this,\'#subalbumitem-' . $id . '\');">' . __( 'Related', 'wp-photo-album-plus' ) . '</li>
							<li class="wppa-albumadmin-tab" onclick="wppaChangeAlbumAdminTab(this,\'#albumactions-' . $id . '\');">' . __( 'Content', 'wp-photo-album-plus' ) . '</li>
							<li class="wppa-albumadmin-tab" onclick="wppaChangeAlbumAdminTab(this,\'#albumcovers-' . $id . '\');">' . __( 'Cover', 'wp-photo-album-plus' ) . '</li>
							<li class="wppa-albumadmin-tab" onclick="wppaChangeAlbumAdminTab(this,\'#itemdisplay-' . $id . '\');">' . __( 'Display', 'wp-photo-album-plus' ) . '</li>' );
							if ( ! empty( $usedby ) ) {
								wppa_echo( '
								<li
									class="wppa-albumadmin-tab"
									onclick="wppaChangeAlbumAdminTab(this,\'#albumusedby-'.$id.'\');">' .
									__( 'Used by', 'wp-photo-album-plus' ) . '
								</li>' );
							}
						wppa_echo( '
						</ul>
						<div style="clear:both"></div>
					</div>');

					// Tab 1: General Album Settings
					wppa_echo( '
					<div
						id="albumitem-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent"
						style="position:relative;background-color:#ddd;padding-bottom:12px;padding-left:12px;"
						>' );

						// Section 0: More or less static data
						{
							wppa_echo( '
							<div class="wppa-flex">' );

							wppa_echo( '
							<fieldset class="wppa-fieldset" style="width:100%">
								<legend class="wppa-legend">' .
									__( 'Unchangeable items', 'wp-photo-album-plus' ) . '
								</legend>' );

								// Album number, crypt, timestamp
								wppa_echo( '
								<div class="left">
									<label>' .
										__( 'Album number', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										$id . '
									</div>
								</div>
								<div class="left">
									<label>' .
										__( 'Encrypted', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										$crypt . '
									</div>
								</div>
								<div class="left">
									<label>' .
										__( 'Created', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' .
										wppa_local_date( '', $timestamp ) . '
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

								// Modified
								wppa_echo( '
								<div class="left">
									<label>' .
										__( 'Modified', 'wp-photo-album-plus' ) . '
									</label><br>
									<div class="wppa-ldi">' );
										if ( $modified > $timestamp ) {
											wppa_echo( wppa_local_date( '', $modified ) );
										}
										else {
											wppa_echo( __( 'Not modified', 'wp-photo-album-plus' ) );
										}
										wppa_echo( '
									</div>
								</div>' );

								// Owner
								if ( ! wppa_user_is_admin() ) {
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Owned by', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' );
											if ( $owner == '--- public ---' ) {
												wppa_echo( __( '--- public ---', 'wp-photo-album-plus' ) );
											}
											else {
												wppa_echo( wppa_get_owner_display( $owner ) );
											}
											wppa_echo( '
										</div>
									</div>' );
								}

								// Views
								if ( wppa_switch( 'track_viewcounts' ) ) {
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Album Views', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											$views . '
										</div>
									</div>
									<div class="left">
										<label>' .
											__( 'Photo views', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											$pviews . '
										</div>
									</div>' );
									if ( $nsub ) {
										wppa_echo( '
										<div class="left">
											<label>' .
												__( 'Inc sub albums', 'wp-photo-album-plus' ) . '
											</label><br>
											<div class="wppa-ldi">' .
												$tpviews . '
											</div>
										</div>' );
									}
								}

								// Clicks
								if ( wppa_switch( 'track_clickcounts' ) ) {
									$click_arr = $wpdb->get_col( "SELECT clicks FROM $wpdb->wppa_photos WHERE album = $id" );
									wppa_echo( '
									<div class="left">
										<label>' .
											__( 'Clicks', 'wp-photo-album-plus' ) . '
										</label><br>
										<div class="wppa-ldi">' .
											array_sum( $click_arr ) . '
										</div>
									</div>' );
								}

								wppa_echo( '</fieldset>' );

							wppa_echo( '</div>' );
						}

						// Section 1: Short settable items
						{
							wppa_echo( '
							<!-- Album Section 1 simple settings -->
							<div class="wppa-flex">' );

							wppa_echo( '
							<fieldset class="wppa-fieldset" style="width:100%">
								<legend class="wppa-legend">' .
									__( 'Changeable items', 'wp-photo-album-plus' ) . '
								</legend>' );

								// Owner
								if ( wppa_user_is_admin() ) {
									$result = '
									<div class="left">
										<label
											for="albumowner">' .
											__( 'Owned by', 'wp-photo-album-plus' ) . '
										</label><br>';

										if ( wppa_get_user_count() > wppa_opt( 'max_users' ) ) {
											$result .= '
											<input
												id="albumowner"
												type="text"
												value="' . esc_attr( $owner ) . '"
												onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'owner\', this )"
											/>';
										}
										else {
											if ( wppa_switch( 'user_upload_on' ) ) {
												if ( $owner == '--- public ---' ) {
													$title = __( 'Frontend upload to this album is open to visitors', 'wp-photo-album-plus' );
												}
												else {
													$title = __( 'Frontend upload to this album is open for the owner and the admin', 'wp-photo-album-plus' );
												}
											}
											else {
												$title = '';
											}
											$result .= '
											<select
												id="albumowner"
												onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'owner\', this )"
												style="max-width:200px;"
												title="' . esc_attr( $title ) . '"
												>' .
												wppa_get_user_select( $owner ) . '
											</select>';
										}

									$result .= '
									</div>';
									wppa_echo( $result );
								}

								// Order # -->
								{
									wppa_echo( '
									<div class="left">
										<label
											for="albumseqno">' .
											__( 'Sequence number', 'wp-photo-album-plus' ) . '
										</label><br>
										<input
											id="albumseqno"
											type="text"
											style="width:50px;cursor:pointer"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'a_order\', this )"
											value="' . esc_attr( $a_order ) . '"' .
											( wppa_opt( 'list_albums_by' ) != '1' && $a_order != '0' ? ' title="' . esc_attr( __( 'Album sequence number has only effect if you set the album sort order method to Order # in the Photo Albums -> Settings screen.', 'wp-photo-album-plus' )) . '"' : '' ) . '
										/>&nbsp;
									</div>' );
								}

								// Status
								{
									$title = __( 'Set the frontend visibility of the album cover and items not including sub albums.', 'wp-photo-album-plus' ) . ' ' .
									__( 'Publish: visible for all, Private: visible for logged in only, Hidden: visible for admin only', 'wp-photo-album-plus' );

									wppa_echo( '
									<div class="left">
										<label
											for="albumstatus">' .
											__( 'Status', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											style="max-width:200px;"
											id="albumstatus"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'status\', this )"
											title="' . esc_attr( $title ) . '" >
											<option value="publish"' . ( $status == 'publish' ? ' selected' : '' ) . '>' .
												__( 'Publish', 'wp-photo-album-plus' ) . '
											</option>
											<option value="private"' . ( $status == 'private' ? ' selected' : '' ) . '>' .
												__( 'Private', 'wp-photo-album-plus' ) . '
											</option>
											<option value="hidden"' . ( $status == 'hidden' ? ' selected' : '' ) . '>' .
												__( 'Hidden', 'wp-photo-album-plus' ) . '
											</option>
										</select>
									</div>' );
								}

								// Parent
								{
									$result = '
									<div class="left">
										<label
											for="wppa-parsel">' .
											__( 'Parent album', 'wp-photo-album-plus' ) . '
										</label><br>';
										if ( wppa_extended_access() ) {
											$result .=
											wppa_album_select_a( array( 'checkaccess' 		=> true,
																		'exclude' 			=> $id,
																		'selected' 			=> $a_parent,
																		'addselected' 		=> true,
																		'addnone' 			=> true,
																		'addseparate' 		=> true,
																		'disableancestors' 	=> true,
																		'path' 				=> true,
																		'sort' 				=> true,
																		'tagopen' 			=> '<select' .
																									' id="wppa-parsel"' .
																									' style="max-width:200px;"' .
																									' onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'a_parent\', this )"' .
																									' >',
																		'tagid' 			=> 'wppa-parsel',
																		'tagonchange' 		=> 'wppaAjaxUpdateAlbum( ' . $id . ', \'a_parent\', this )',
																		'tagstyle' 			=> 'font-size:13px;height:20px;cursor:pointer;',
																		)
																) .
											'</select>';
										}
										else {
											$result .= '
											<select
												id="wppa-parsel"
												style="max-width:200px;"
												onchange="wppaAjaxUpdateAlbum( '. $id . ', \'a_parent\', this )"
												>' .
												wppa_album_select_a( array( 'checkaccess' 		=> true,
																			'exclude' 			=> $id,
																			'selected' 			=> $a_parent,
																			'addnone' 			=> wppa_can_create_top_album(),
																			'addselected' 		=> true,
																			'disableancestors' 	=> true,
																			'path' 				=> true,
																			'sort' 				=> true,
																			)
																	) .
											'</select>';
										}
									$result .= '
									</div>';
									wppa_echo( $result );
								}

								// P-order-by
								if ( ! wppa_switch( 'porder_restricted' ) || wppa_user_is_admin() ) {
									$result = '
									<div class="left"
										style="max-width: 200px;margin-right: 4px;">
										<label
											for="photo-order"
											>' .

											__( 'Photo sequence', 'wp-photo-album-plus' ) . '
										</label><br>';
										$options = array(	__( '--- default ---', 'wp-photo-album-plus' ),
															__( 'Sequence #', 'wp-photo-album-plus' ),
															__( 'Name', 'wp-photo-album-plus' ),
															__( 'Random', 'wp-photo-album-plus' ),
															__( 'Rating mean value', 'wp-photo-album-plus' ),
															__( 'Number of votes', 'wp-photo-album-plus' ),
															__( 'Timestamp', 'wp-photo-album-plus' ),
															__( 'EXIF Date', 'wp-photo-album-plus' ),
															__( 'Sequence # descending', 'wp-photo-album-plus' ),
															__( 'Name descending', 'wp-photo-album-plus' ),
															__( 'Rating mean value descending', 'wp-photo-album-plus' ),
															__( 'Number of votes descending', 'wp-photo-album-plus' ),
															__( 'Timestamp descending', 'wp-photo-album-plus' ),
															__( 'EXIF Date descending', 'wp-photo-album-plus' )
															);
										$values = array(	'0',
															'1',
															'2',
															'3',
															'4',
															'6',
															'5',
															'7',
															'-1',
															'-2',
															'-4',
															'-6',
															'-5',
															'-7'
															);

										$dflt = '';
										$df = wppa_opt( 'list_photos_by' );
										if ( $df == '0' ) $dflt = __( 'not specified', 'wp-photo-album-plus' );
										else foreach( array_keys( $values ) as $key ) {
											if ( $df == $values[$key] ) {
												$dflt = $options[$key];
											}
										}
										$title = sprintf( __( 'The default is set in %s and is currently set to %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'misc', 1, 2 ), $dflt );

										$result .= '
										<select
											id="photo-order"
											style="max-width:200px;"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'p_order_by\', this )"
											title="' . esc_attr( $title ) . '"
											>';
											foreach ( array_keys( $options ) as $key ) {
												$sel = $values[$key] == $p_order_by ? ' selected' : '';
												$result .= '<option value="' . $values[$key] . '"' . $sel . '>' . $options[$key] . '</option>';
											}
										$result .= '
										</select>
									</div>';
									wppa_echo( $result );
								}

								// Alternative thumbnail size
								if ( ! wppa_switch( 'alt_is_restricted' ) || wppa_user_is_admin() ) {
									$title = sprintf( __( 'The alternate thumbnail size is set in %s and is currently set to %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'thumbs', 1, 2 ), wppa_opt( 'thumbsize_alt' ) );

									$sel = ' selected';
									wppa_echo( '
									<div class="left"
										style="max-width: 200px;margin-right: 4px;">
										<label
											for="altthumb">' .
											__( 'Use alt thumbsize', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="altthumb"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'alt_thumbsize\', this )"
											title="' . esc_attr( $title ) . '"
											>
											<option value="0"' . ( $alt_thumbsize ? '' : $sel ) . ' >' .
												__( 'no', 'wp-photo-album-plus' ) . '
											</option>
											<option value="yes"' . ( $alt_thumbsize ? $sel : '' ) . ' >' .
												__( 'yes', 'wp-photo-album-plus' ) . '
											</option>
										</select>
									</div>' );
								}

								// Max children
								{
									$sel = ' selected';
									$val = $albuminfo['max_children'];
									wppa_echo( '
									<div class="left"
										style="max-width: 200px;margin-right: 4px;">
										<label
											for="altthumb">' .
											__( 'Max sub albums', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="maxchild"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'max_children\', this )"
											title="' . esc_attr( 'This setting can only llimit the creation of sub albums at the frontend and looks at direct sub albums only', 'wp-photo-album-plus' ) . '"
											>
											<option value="0"' . ( $val == '0' ? $sel : '' ) . ' >' .
												__( 'unlimited', 'wp-photo-album-plus' ) . '
											</option>
											<option value="-1"' . ( $val == '-1' ? $sel : '' ) . ' >' .
												__( 'none', 'wp-photo-album-plus' ) . '
											</option>
											<option value="1"' . ( $val == '1' ? $sel : '' ) . '>1</option>
											<option value="2"' . ( $val == '2' ? $sel : '' ) . '>2</option>
											<option value="3"' . ( $val == '3' ? $sel : '' ) . '>3</option>
											<option value="4"' . ( $val == '4' ? $sel : '' ) . '>4</option>
											<option value="5"' . ( $val == '5' ? $sel : '' ) . '>5</option>
											<option value="10"' . ( $val == '10' ? $sel : '' ) . '>10</option>
										</select>
									</div>' );
								}

								// Upload limit
								{
									$result = '
									<div class="left">
										<label
											for="upload_limit_count">' .
											__( 'Upload limit', 'wp-photo-album-plus' ) . '
										</label><br>';
										$lims = explode( '/', $upload_limit );
										if ( ! is_array( $lims ) ) {
											$lims = array( '0', '0' );
										}
										if ( wppa_user_is_admin() ) {
											$sel = ' selected';
											$title = __( 'Set the upload limit (0 means unlimited).', 'wp-photo-album-plus' );
											$result .= '
											<input
												type="text"
												id="upload_limit_count"
												value="' . $lims[0] . '"
												style="max-width:50px;cursor:pointer;"
												title="' . esc_attr( $title ) . '"
												onchange="wppaRefreshAfter(); wppaAjaxUpdateAlbum( ' . $id . ', \'upload_limit_count\', this )"
											/>
											<select
												style="max-width:150px;vertical-align:baseline"
												onchange="wppaRefreshAfter(); wppaAjaxUpdateAlbum( ' . $id . ', \'upload_limit_time\', this )" >
												<option value="0"' . ( $lims[1] == '0' ? $sel : '' ) . ' >' . __( 'for ever', 'wp-photo-album-plus' ) . '</option>
												<option value="3600"' . ( $lims[1] == '3600' ? $sel : '' ) . ' >' . __( 'per hour', 'wp-photo-album-plus' ) . '</option>
												<option value="86400"' . ( $lims[1] == '86400' ? $sel : '' ) . ' >' . __( 'per day', 'wp-photo-album-plus' ) . '</option>
												<option value="604800"' . ( $lims[1] == '604800' ? $sel : '' ) . ' >' . __( 'per week', 'wp-photo-album-plus' ) . '</option>
												<option value="2592000"' . ( $lims[1] == '2592000' ? $sel : '' ) . ' >' . __( 'per month', 'wp-photo-album-plus' ) . '</option>
												<option value="31536000"' . ( $lims[1] == '31536000' ? $sel : '' ) . ' >' . __( 'per year', 'wp-photo-album-plus' ) . '</option>
											</select>';
										}
										else {
											$result .= '
											<div class="wppa-ldi">';
											if ( $lims[0] == '0' ) {
												$result .= __( 'Unlimited', 'wp-photo-album-plus' );
											}
											else {
												$result .= $lims[0] . '&nbsp;';
												switch ( $lims[1] ) {
													case '3600': $result .= __( 'per hour', 'wp-photo-album-plus' ); break;
													case '86400': $result .= __( 'per day', 'wp-photo-album-plus' ); break;
													case '604800': $result .= __( 'per week', 'wp-photo-album-plus' ); break;
													case '2592000': $result .= __( 'per month', 'wp-photo-album-plus' ); break;
													case '31536000': $result .= __( 'per year', 'wp-photo-album-plus' ); break;
													default: $result .= sprintf( 'per %d seconds', $lims[1] );
												}
											}
											$result .= '.
											</div>';
										}
										$result .= '
									</div>';
									wppa_echo( $result );
								}

								// Tree limit
								{
									$title = __( 'The upload limit for this album and all its (sub-)sub albums.', 'wp-photo-album-plus' ) . ' ' .
											 __( 'This setting overrules all other limits that may apply to this album or its (sub-)sub albums.', 'wp-photo-album-plus' ) . ' ' .
											 __( '0 means no limit.', 'wp-photo-album-plus' );
									wppa_echo( '
									<div class="left">
										<label
											for="uploadlimittree">' .
											__( 'Tree upload limit', 'wp-photo-album-plus' ) . '
										</label><br>
										<input
											type="text"
											id="uploadlimittree"
											title="' . esc_attr( $title ) . '"
											value="' . $tree_limit . '"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'upload_limit_tree\', this )"
											style="cursor:pointer;max-width:50px;"
										/>
									</div>' );
								}

								// Watermark
								if ( wppa_switch( 'watermark_on' ) ) {

									wppa_echo( '
									<div class="left"
										style="max-width: 200px;margin-right: 4px;">
										<label
											for="watermarkfile">' .
											__( 'Watermark file', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="watermarkfile"
											style="max-width:200px"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'wmfile\', this )" >' .
											wppa_watermark_file_select( 'album', $id ) . '
										</select>
									</div>
									<div class="left"
										style="max-width: 200px;margin-right: 4px;">
										<label
											for="watermarkpos">' .
											__( 'Watermark pos', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="watermarkpos"
											style="max-width:200px"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'wmpos\', this )" >' .
											wppa_watermark_pos_select( 'album', $id ) . '
										</select>
									</div>' );
								}

							wppa_echo( '</fieldset>' );
							wppa_echo( '</div>' );
						}
						// End Section 1: Short settable items, wppa-flex div

						// Section 2: name, description, custom, flex column
						wppa_echo( '
						<div class="wppa-flex-column">' );

							// Name
							{
								wppa_echo( '
								<fieldset class="wppa-fieldset">
									<legend class="wppa-legend">' .
										__( 'Name', 'wp-photo-album-plus' ) . '
									</legend>
									<input
										type="text"
										style="width:100%;"
										onchange="wppaAjaxUpdateAlbum( ' . $id .  ', \'name\', this )"
										value="' . esc_attr( $name ) . '"
									/>
									<br>
									<span class="description" >' .
										__( 'Type the name of the album. Do not leave this empty.', 'wp-photo-album-plus' ) . '
									</span>
									<span style="float:right">' .
										__( 'Name slug', 'wp-photo-album-plus' ) . ': ' . $sname . '
									</span>
								</fieldset>' );
							}

							// Description
							{
								wppa_echo( '
								<fieldset class="wppa-fieldset">
									<legend class="wppa-legend">' .
										__( 'Description', 'wp-photo-album-plus' ) . '
									</legend>' );

									// WP Editor
									if ( wppa_switch( 'use_wp_editor') ) {

										// Echos itsself, has no return option
										wp_editor( 	$description,
													'wppaalbumdesc',
													array( 	'wpautop' 		=> true,
															'media_buttons' => false,
															'textarea_rows' => '6',
															'tinymce' 		=> true
														)
												);

										wppa_echo( '
										<input
											style="margin-top: 8px;"
											type="button"
											class="button button-secondary"
											value="' . esc_attr( __( 'Update Album description', 'wp-photo-album-plus' ) ) . '"
											onclick="wppaAjaxUpdateAlbum( ' . $id .  ', \'description\', wppaGetTinyMceContent(\'wppaalbumdesc\') )"
										/>
										<img
											id="wppa-album-spin"
											src="' . wppa_get_imgdir() . 'spinner.gif"
											alt="Spin"
											style="visibility:hidden"
										/>' );
									}

									// Textarea
									else {
										wppa_echo( '
										<div>
											<label style="font-weight: 600;">' . __( 'Description', 'wp-photo-album-plus' ) . '</label><br>
											<textarea
												style="width:100%;height:60px;"
												onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'description\', this )"
												>' .
												esc_textarea( stripslashes( $description ) ) . '
											</textarea>
										</div>' );
									}
								wppa_echo( '
								</fieldset>' );
							}

							// Categories
							{
								$result = '
								<fieldset class="wppa-fieldset">
                                    <legend class="wppa-legend">' .
										__( 'Categories', 'wp-photo-album-plus' ) . '
									</legend>
									<input
										id="cats"
										type="text"
										style="width:100%;"
										onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'cats\', this )"
										value="' . esc_attr( $cats ) . '"
									/>
									<div style="margin: 8px 0;">
										<span class="description" >' .
											__( 'Separate categories with commas.', 'wp-photo-album-plus' ) . '
										</span>&nbsp;
										<select
											onchange="wppaAddCat( this.value, \'cats\' ); wppaAjaxUpdateAlbum( ' . $id . ', \'cats\', document.getElementById( \'cats\' ) )"
											>';
											$catlist = wppa_get_catlist();
											if ( is_array( $catlist ) ) {
												$result .= '
												<option value="" >' . __( '- select to add -', 'wp-photo-album-plus' ) . '</option>';
												foreach ( $catlist as $cat ) {
													$result .= '
													<option value="' . esc_attr( $cat['cat'] ) . '" >' . htmlspecialchars( $cat['cat'] ) . '</option>';
												}
											}
											else {
												$result .= '
												<option value="0" >' . __( 'No categories yet', 'wp-photo-album-plus' ) . '</option>';
											}
										$result .= '
										</select>
									</div>
								</fieldset>';
								wppa_echo( $result );
							}

							// Custom
							if ( wppa_switch( 'album_custom_fields' ) ) {
								$custom_data = wppa_unserialize( wppa_get_album_item( $edit_id, 'custom' ) );
								if ( ! is_array( $custom_data ) ) {
									$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
								}

								// Open fieldset
								wppa_echo( '
								<fieldset class="wppa-fieldset">
									<legend class="wppa-legend">' .
										__( 'Custom data fields', 'wp-photo-album-plus' ) . '
									</legend>' );

									foreach( array_keys( $custom_data ) as $key ) {
										if ( wppa_opt( 'album_custom_caption_' . $key ) ) {

											wppa_echo( '
											<div>
												<label>
													<span style="float:left">' .
														apply_filters( 'translate_text', wppa_opt( 'album_custom_caption_' . $key ) ) . ' (w#cc' . $key . ')
													</span>
													<span style="float:right">
														(w#cd' . $key . ')
													</span>
												</label><br>
												<input
													type="text"
													style="width:100%"
													id="album_custom_' . $key . '-' . $id . '"
													onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'album_custom_' . $key . '\', this.value)"
													value="' . esc_attr( stripslashes( $custom_data[$key] ) ) . '"
												/>
											<div>' );

										}
									}

								wppa_echo( '</fieldset>' );
							}

							// End Section 2
							wppa_echo( '
							</tbody>
						</table>' );


						// End flex column
						wppa_echo( '</div>' );

					// End Tab 1
					wppa_echo( '
					</div>' );	// Tab 1


					// Tab 2: Related albums
					wppa_echo( '
					<div id="subalbumitem-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent"
						style="position:relative;display:none;background-color:#ddd;padding-bottom:12px;padding-left:12px;"
						>' );

						// Explanation
						wppa_echo( '
						<h2 class="description" style="margin:1em">' .
							__( 'The following buttons perform actions on albums related to this album', 'wp-photo-album-plus' ) . '
						</h2>' );

						// Create sub album
						if ( wppa_can_create_album() ) {
							$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=new&amp;parent_id=' . $albuminfo['id'] . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
							$onc = 'if (confirm(\''.__( 'Are you sure you want to create a sub album?', 'wp-photo-album-plus' ).'\')) document.location=\''.$url.'\';';

							wppa_echo( '
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="' . $onc . '"
								value="' . esc_attr( __( 'Create sub album', 'wp-photo-album-plus' ) ) . '"
							/>' );
						}

						// Create sibling
						if ( $albuminfo['a_parent'] > '0' && wppa_can_create_album() ||
							 $albuminfo['a_parent'] < '1' && wppa_can_create_top_album() ) {
							$url = get_admin_url() .
									'admin.php' .
									'?page=wppa_admin_menu' .
									'&amp;tab=edit' .
									'&amp;edit-id=new' .
									'&amp;parent_id=' . $albuminfo['a_parent'] .
									'&amp;is-sibling-of=' . $albuminfo['id'] .
									'&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
							$onc = 'if (confirm(\''.__( 'Are you sure you want to create a sub album?', 'wp-photo-album-plus' ).'\')) document.location=\''.$url.'\';';

							wppa_echo( '
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="' . $onc . '"
								value="' . esc_attr( __( 'Create sibling', 'wp-photo-album-plus' ) ) . '"
							/>' );
						}

						// Edit parent
						if ( $albuminfo['a_parent'] > '0' && wppa_album_exists( $albuminfo['a_parent'] ) && wppa_have_access( $albuminfo['a_parent'] ) ) {
							$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=' . $albuminfo['a_parent'] . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
							$onc = 'document.location=\''.$url.'\';';
							wppa_echo( '
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="' . $onc . '"
								value="' . esc_attr( __( 'Edit parent', 'wp-photo-album-plus' ) ) . '"
							/>' );
							$onc = 'document.location=\''.$url.'&amp;bulk=1\';';
							wppa_echo( '
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="' . $onc . '"
								value="' . esc_attr( __( 'Bulk Edit parent', 'wp-photo-album-plus' ) ) . '"
							/>' );
						}

						// Inherit cats
						if ( $has_children ) {
							wppa_echo( '
							<input
								type="button"
								class="wppa-admin-button button"
								title="' . esc_attr( __( 'Apply categories to all (sub-)sub albums.', 'wp-photo-album-plus' ) ) . '"
								onclick="wppaTryInheritCats( ' . $id . ' )"
								value="' . esc_attr( __( 'Apply Cats to sub albums', 'wp-photo-album-plus' ) ) . '"
							/>
							<input
								type="button"
								class="wppa-admin-button button"
								title="' . esc_attr( __( 'Add categories to all (sub-)sub albums.', 'wp-photo-album-plus' ) ) . '"
								onclick="wppaTryAddCats( ' . $id . ' )"
								value="' . esc_attr( __( 'Add Cats to sub albums', 'wp-photo-album-plus' ) ) . '"
							/>' );
						}

						// Sub albums sequence
						if ( $has_children ) {
							wppa_album_sequence( $edit_id );
						}

					// End Tab 2
					wppa_echo( '
					</div>' );


					// Tab 3: Album Actions
					wppa_echo( '
					<div
						id="albumactions-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent"
						style="position:relative;display:none;background-color:#ddd;padding-bottom:12px;padding-left:12px;"
						>' );

						// Explanation
						wppa_echo( '
						<h2 class="description" style="margin:1em">' .
							__( 'The following buttons perform actions onto all items in this album', 'wp-photo-album-plus' ) . '
						</h2>' );

						wppa_echo( '<div class="wppa-flex">' );

						// Default photo tags
						{
							wppa_echo( '
							<div>
								<label>' .
									__( 'Default photo tags', 'wp-photo-album-plus' ) . '
								</label><br>
								<input
									type="text"
									id="default_tags"
									value="' . esc_attr( $default_tags ) . '"
									style="width:100%"
									onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'default_tags\', this )"
								/>
								<br>
								<span class="description">' .
									__( 'Enter the tags that you want to be assigned to new photos in this album.', 'wp-photo-album-plus' ) . '
								</span>
							</div>' );
						}

						// Apply default tags
						{
							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									title="' . esc_attr( __( 'Tag all photos in this album with the default tags.', 'wp-photo-album-plus' ) ) . '"
									onclick="wppaTryApplyDeftags( ' . $id . ' )"
									value="' . esc_attr( __( 'Apply default tags', 'wp-photo-album-plus' ) ) . '"
								/>
								<input
									type="button"
									class="wppa-admin-button button"
									title="' . esc_attr( __( 'Add the default tags to all photos in this album.', 'wp-photo-album-plus' ) ) . '"
									onclick="wppaTryAddDeftags( ' . $id . ' )"
									value="' . esc_attr( __( 'Add default tags', 'wp-photo-album-plus' ) ) . '"
								/>
							</div>' );
						}

						// Apply New photo desc
						if ( wppa_switch( 'apply_newphoto_desc') ) {
							$onc = 'if ( confirm(\'Are you sure you want to set the description of all items in this album to \n\n'.esc_js(wppa_opt( 'newphoto_description')).'\')) document.location=\''.wppa_ea_url($albuminfo['id'], 'edit').'&applynewdesc=1\'';
							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="' . $onc . '"
									value="' . esc_attr( __( 'Apply new photo desc', 'wp-photo-album-plus' ) ) . '"
								/>
							</div>' );
						}
						
						// Clear photo desc
						$onc = 'if ( confirm(\'Are you sure you want to clear the description of all items in this album\') ) document.location=\''.wppa_ea_url($albuminfo['id'], 'edit').'&cleardesc=1\'';
						wppa_echo( '
						<div>
							<label>&nbsp;</label><br>
							<input
								type="button"
								class="wppa-admin-button button"
								onclick="' . $onc . '"
								value="' . esc_attr( __( 'Clear descriptions', 'wp-photo-album-plus' ) ) . '"
							/>
						</div>' );

						// Set all to pano
						if ( wppa_switch( 'enable_panorama' ) ) {

							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="wppaTrySetAllPanorama(' . $albuminfo['id'] . ')"
									value="' . esc_attr( __( 'Set all to panorama', 'wp-photo-album-plus' ) ).':"
								/>
								<select id="pano-opt"
									style="">
									<option value="9" disabled selected>' . __( 'Select a mode', 'wp-photo-album-plus' ) . '</option>
									<option value="0">' . __( '- none -', 'wp-photo-album-plus' ) . '</option>
									<option value="1">' . __( '360&deg; Spheric', 'wp-photo-album-plus' ) . '</option>
									<option value="2">' . __( 'Non 360&deg; Flat', 'wp-photo-album-plus' ) . '</option>
								</select>
							</div>' );
						}

						// End wppa-flex div
						wppa_echo( '</div>' );

						// Second line
						wppa_echo( '<div class="wppa-flex">' );

						// Schedule
						{
							wppa_echo( '
							<div style="flex-basis: 30em; ">
								<label
									for="schedule-box">' .
									__( 'Schedule', 'wp-photo-album-plus' ) . '
								</label><br>
								<input
									type="checkbox"
									id="schedule-box"' .
									( $albuminfo['scheduledtm'] ? ' checked="checked"' : '' ) . '
									onchange="wppaChangeScheduleAlbum(' . $id . ', this );"
									style="margin: 5px 4px 9px 0;"
								 />
								<input type="hidden" value="" id="wppa-dummy" style="min-height: 28px;"/>
								<span
									class="wppa-datetime-' . $id . '"' .
									( $albuminfo['scheduledtm'] ? '' : ' style="display:none"' ) . '
									>' .
									wppa_get_date_time_select_html( 'album', $id, true ) . '
								</span>
								<br>
								<span class="description" >' .
									__( 'New photos will be published on the date/time specified here.', 'wp-photo-album-plus' ) . '
								</span>
							</div>' );
						}

						// Schedule all
						{
							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									title="' . esc_attr( __( 'Schedule all photos in this album for later publishing.', 'wp-photo-album-plus' ) ) . '"
									onclick="wppaTryScheduleAll( ' . $id . ' )"
									value="' . esc_attr( __( 'Schedule all', 'wp-photo-album-plus' ) ) . '"
								/>
							</div>' );
						}

						// Reset Ratings
						if ( wppa_switch( 'rating_on') ) {
							$onc = 'if (confirm(\'' . esc_js( __( 'Are you sure you want to clear the ratings in this album?', 'wp-photo-album-plus' ) ) . '\')) { wppaRefreshAfter(); wppaAjaxUpdateAlbum( ' . $id . ', \'clear_ratings\', 0 ); }';
							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="' . $onc . '"
									value="' . esc_attr( __( 'Reset ratings', 'wp-photo-album-plus' ) ) . '"
								/>
							</div>' );
						}

						// Remake all
						if ( wppa_user_is_admin() ) {
							$onc = 'if ( confirm(\'Are you sure you want to remake the files for all photos in this album?\')) document.location=\''.wppa_ea_url($albuminfo['id'], 'edit').'&remakealbum=1\'';
							wppa_echo( '
							<div>
								<label>&nbsp;</label><br>
								<input
									type="button"
									class="wppa-admin-button button"
									onclick="' . $onc . '"
									value="' . esc_attr( __( 'Remake all', 'wp-photo-album-plus' ) ) . '"
								/>
							</div>' );
						}

						// Schedule for delete
						if ( wppa_user_is_admin() || $owner == wppa_get_user() ) {
							$may_change = wppa_user_is_admin() || current_user_can( 'wppa_moderate' );

							wppa_echo( '
							<div style="flex-basis: 30em; ">
								<label
									for="scheduledel">' .
									__( 'Delete at', 'wp-photo-album-plus' ) . '
								</label><br>
								<input
									type="checkbox"
									style="margin: 5px 4px 9px 0;"
									id="scheduledel"' .
									( $scheduledel ? ' checked="checked"' : '' ) .
									( $may_change ? '' : ' disabled' ) . '
									onchange="wppaChangeScheduleDelAlbum( ' . $id . ', this );"
								/>
								<input type="hidden" value="" id="wppa-dummy-del" />
								<span
									class="wppa-datetimedel-' . $id . '"' .
									( $albuminfo['scheduledel'] ? '' : ' style="display:none"' ) . '
									>' .
									wppa_get_date_time_select_html( 'delalbum', $id, $may_change ) . '
								</span>
							</div>' );
						}

						// End wppa-flex div
						wppa_echo( '</div>' );

					// End Tab 3
					wppa_echo( '
					</div>' );

					// Tab 4 ALbum cover settings
					wppa_echo( '
					<div
						id="albumcovers-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent"
						style="position:relative;display:none;background-color:#ddd;padding-bottom:12px;padding-left:12px;"
						>' );

						// Explanation
						wppa_echo( '
						<h2 class="description" style="margin:1em">' .
							__( 'The following settings apply to the album cover', 'wp-photo-album-plus' ) . '
						</h2>' );

						wppa_echo( '<div class="wppa-flex">' );

						// Cover type
						if ( ! wppa_switch( 'covertype_is_restricted' ) || wppa_user_is_admin() ) {
							$sel = ' selected';
							$title = sprintf( __( 'The default is set in %s and is currently set to %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'covers', 3, 4 ), wppa_opt( 'cover_type' ) );

							wppa_echo( '
							<div
								style="max-width: 200px;margin-right: 4px;">
								<label
									for="covertype">' .
									__( 'Cover&nbsp;Type', 'wp-photo-album-plus' ) . '
								</label><br>
								<select
									id="covertype"
									style="max-width:200px;"
									onchange="wppaAjaxUpdateAlbum( '. $id . ', \'cover_type\', this.value )"
									title="' . esc_attr( $title ) . '"
									>
									<option value=""' . ( $cover_type == '' ? $sel : '' ) . ' >' .
										__( '--- default ---', 'wp-photo-album-plus' ) . '
									</option>
									<option value="default"' . ( $cover_type == 'default' ? $sel : '' ) . ' >' .
										__( 'Standard', 'wp-photo-album-plus' ) . '
									</option>
									<option value="longdesc"' . ( $cover_type == 'longdesc' ? $sel : '' ) . ' >' .
										__( 'Long Descriptions', 'wp-photo-album-plus' ) . '
									</option>
									<option value="imagefactory"' . ( $cover_type == 'imagefactory' ? $sel : '' ) . ' >' .
										__( 'Image Factory', 'wp-photo-album-plus' ) . '
									</option>
									<option value="default-mcr"' . ( $cover_type == 'default-mcr' ? $sel : '' ) . ' >' .
										__( 'Standard mcr', 'wp-photo-album-plus' ) . '
									</option>
									<option value="longdesc-mcr"' . ( $cover_type == 'longdesc-mcr' ? $sel : '' ) . ' >' .
										__( 'Long Descriptions mcr', 'wp-photo-album-plus' ) . '
									</option>
									<option value="imagefactory-mcr"' . ( $cover_type == 'imagefactory-mcr' ? $sel : '' ) . ' >' .
										__( 'Image Factory mcr', 'wp-photo-album-plus' ) . '
									</option>
								</select>
							</div>' );
						}

						// Cover photo
						{
							wppa_echo( '
							<div>
								<label>' .
									__( 'Cover&nbsp;Photo', 'wp-photo-album-plus' ) . '
								</label><br>' .
								wppa_main_photo( $main_photo, $cover_type ) . '
							</div>' );
						}

						// Link type
						{
							$result = '
							<div>
								<label>' .
									__( 'Link type', 'wp-photo-album-plus' ) . '
								</label><br>';
								$sel = ' selected';
								$lt = $cover_linktype;
								if ( wppa_switch( 'auto_page' ) ) {
									$title = __( 'If you select "the link page with a clean url", select an Auto Page of one of the photos in this album.', 'wp-photo-album-plus' );
								}
								else {
									$title = __( 'If you select "the link page with a clean url", make sure you enter the correct shortcode on the target page.', 'wp-photo-album-plus' );
								}
								$result .= '
								<select
									title="' . esc_attr( $title ) . '"
									onchange="wppaAjaxUpdateAlbum( '. $id . ', \'cover_linktype\', this )" >
									<option value="content"' . ( $lt == 'content' ? $sel : '' ) . ' >' . __( 'the sub albums and thumbnails', 'wp-photo-album-plus' ) . '</option>
									<option value="albums"' . ( $lt == 'albums' ? $sel : '' ) . ' >' . __( 'the sub albums', 'wp-photo-album-plus' ) . '</option>
									<option value="thumbs"' . ( $lt == 'thumbs' ? $sel : '' ) . ' >' . __( 'the thumbnails', 'wp-photo-album-plus' ) . '</option>
									<option value="slide"' . ( $lt == 'slide' ? $sel : '' ) . ' >' . __( 'the album photos as slideshow', 'wp-photo-album-plus' ) . '</option>
									<option value="page"' . ( $lt == 'page' ? $sel : '' ) . ' >' . __( 'the link page with a clean url', 'wp-photo-album-plus' ) . '</option>
									<option value="none"' . ( $lt == 'none' ? $sel : '' ) . ' >' . __( 'no link at all', 'wp-photo-album-plus' ) . '</option>
									<option value="manual"' . ( $lt == 'manual' ? $sel : '' ) . ' >' . __( 'manually entered', 'wp-photo-album-plus' ) . '</option>
								</select>
								<br>
								<span class="description">';

								$result .= '
								</span>
							</div>';
							wppa_echo( $result );
						}

						// Manually entered link
						if ( ! wppa_switch( 'link_is_restricted' ) || wppa_user_is_admin() ) {
							$result = '
							<div
								id="link-url-tr"
								style="' . ( $cover_linktype == 'manual' ? '' : 'display:none' ) . '"
								>
								<label>' .
									__( 'Link target (url)', 'wp-photo-album-plus' ) . '
								</label><br>';
								$title = __( 'Enter the url you want the title to link to when you selected Link type manually entered.', 'wp-photo-album-plus' );
								$result .= '
								<input
									type="text"
									title="' . $title . '"
									id="cover_link"
									value="' . esc_attr( $cover_link ) . '"
									style="width:100%"
									onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'cover_link\', this )"
								/>
							</div>';
							wppa_echo( $result );
						}

						// Link page
						if ( ! wppa_switch( 'link_is_restricted' ) || wppa_user_is_admin() ) {
							$result = '
							<div
								id="-link-url-tr"
								style="' . ( $cover_linktype != 'manual' ? '' : 'display:none' ) . '"
								>
								<label>' .
									__( 'Link to', 'wp-photo-album-plus' ) . '
								</label><br>';
									$query = "SELECT ID, post_type, post_title, post_date FROM $wpdb->posts
											  WHERE ( post_type = 'page' OR post_type = 'post' )
											  AND post_status = 'publish'
											  ORDER BY post_title ASC";
									$pages = $wpdb->get_results( $query, ARRAY_A );
									if ( empty( $pages ) ) {
										$result .= __( 'There are no posts/pages (yet) to link to.', 'wp-photo-album-plus' );
									}
									else {
										$linkpage = $albuminfo['cover_linkpage'];
										if ( ! is_numeric( $linkpage ) ) {
											$linkpage = '0';
										}
								$title = __( 'If you want, you can link the title to a WP page or post instead of the album\'s content. If so, select the page the title links to.', 'wp-photo-album-plus' ) .

										$result .= '
										<select
											onchange="wppaAjaxUpdateAlbum( '. $id . ' , \'cover_linkpage\', this )"
											title="' . esc_attr( $title ) . '"
											style="max-width:100%;"
											>
											<option value="0"' . ( $linkpage == '0' ? $sel : '' ) . ' >' .
												__( '--- the same page or post ---', 'wp-photo-album-plus' ) .
											'</option>';
											foreach ( $pages as $page ) {
												$result .= '
												<option
													value="' . $page['ID'] . '"' .
													( $linkpage == $page['ID'] ? ' selected' : '' ) . '>' .
													__( htmlspecialchars( $page['post_title'] ) ) .
													( $page['post_type'] == 'post' ? ' (' . htmlspecialchars( $page['post_date'] ) . ')' : '' ) .
												'</option>';
											}
										$result .= '
										</select>';



									}
								$result .= '
								</div>';

							wppa_echo( $result );
						}

						// End wppa-flex div
						wppa_echo( '</div>' );

						wppa_echo( '
						<fieldset class="wppa-fieldset">
							<legend style="padding:0 8px;font-weight: 600;">' .
								__( 'Preview', 'wp-photo-album-plus' ) . '
							</legend>' );

							// wppaGetCoverPreview( albumId, divId )
							// Preview album cover
							wppa_echo( '<div id="cover-preview-'.$id.'" style="clear:both;max-width:100%"></div>' );

						wppa_echo('</fieldset>');

					// End Tab 4
					wppa_echo( '
					</div>' );

					// Tab 5 Display
					wppa_echo( '
					<div
						id="itemdisplay-' . $id . '"
						class="wppa-table-wrap wppa-tabcontent"
						style="position:relative;display:none;background-color:#ddd;padding-bottom:12px;padding-left:12px;"
						>' );

						// Explanation
						wppa_echo( '
						<h2 class="description" style="margin:1em">' .
							__( 'The following settings can overrule systemwide settings on a per album basis', 'wp-photo-album-plus' ) . '
						</h2>' );

						wppa_echo( '<div class="wppa-flex">' );

								// Need this the next 5 items
								{
									$yes 	= __( 'yes', 'wp-photo-album-plus' );
									$no 	= __( 'no', 'wp-photo-album-plus' );
									$def 	= __( 'default', 'wp-photo-album-plus' );
								}

								// Zoomable
								{
									$title 	= __( 'When set other than default, this setting will overrule the default settings.', 'wp-photo-album-plus' ) . ' ' .
									sprintf( __( 'The default is set in %s and is currently %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'photos', 1, 4 ), ( wppa_switch( 'zoom_on' ) ? $yes : $no ) );

									wppa_echo( '
									<div>
										<label
											for="zoomable">' .
											__( 'Photos are zoomable', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="zoomable"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'zoomable\', this )"
											title="' . esc_attr( $title ) . '" >
											<option value=""'.($zoomable==''?' selected': '').'>' . $def . '</option>
											<option value="on"'.($zoomable=='on'?' selected': '').'>' . $yes . '</option>
											<option value="off"'.($zoomable=='off'?' selected': '').'>' . $no . '</option>
										</select>
									</div>' );
								}

								// Overrulable display options / next 4 items
								{
									if ( $displayopts ) {
										$disp_opt = explode( ',', $displayopts );
									}
									for ( $i = 0; $i < 4; $i++ ) {
										if ( ! isset( $disp_opt[$i] ) ) {
											$disp_opt[$i] = '0';
										}
									}
									$title_head = __( 'When set other than default, this setting will overrule the default settings.', 'wp-photo-album-plus' ) . '&#013;' .
												  __( 'The defaults are set in', 'wp-photo-album-plus' ). ':&#013;';
								}

								// Display name
								{
									$title = $title_head .
									wppa_setting_path( 'b', 'slide', 1, 17, wppa_switch( 'show_full_name' ) ? $yes : $no ) . ',&#013;' .
									wppa_setting_path( 'b', 'thumbs', 2, 1, wppa_switch( 'thumb_text_name' ) ? $yes : $no ) . ' ' . __( 'and' , 'wp-photo-album-plus' ) . '&#013;' .
									wppa_setting_path( 'b', 'lightbox', 1, 3, wppa_switch( 'ovl_name' ) ? $yes : $no ) . '.';

									wppa_echo( '
									<div>
										<label
											for="showname">' .
											__( 'Show names', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="showname"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'displayopt0\', this )"
											title="' . esc_attr( $title ) . '">
											<option value="0"'.($disp_opt[0]=='0'?' selected': '').'>' . $def . '</option>
											<option value="1"'.($disp_opt[0]=='1'?' selected': '').'>' . $yes . '</option>
											<option value="-1"'.($disp_opt[0]=='-1'?' selected': '').'>' . $no . '</option>
										</select>
									</div>' );
								}

								// Display description
								{
									$title = $title_head .
									wppa_setting_path( 'b', 'slide', 1, 20, wppa_switch( 'show_full_desc' ) ? $yes : $no ) . ',&#013;' .
									wppa_setting_path( 'b', 'thumbs', 2, 3, wppa_switch( 'thumb_text_desc' ) ? $yes : $no ) . ' ' . __( 'and' , 'wp-photo-album-plus' ) . '&#013;' .
									wppa_setting_path( 'b', 'lightbox', 1, 4, wppa_switch( 'ovl_desc' ) ? $yes : $no ) . '.';

									wppa_echo( '
									<div>
										<label
											for="showdesc">' .
											__( 'Show descriptions', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="showdesc"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'displayopt1\', this )"
											title="' . esc_attr( $title ) . '" >
											<option value="0"'.($disp_opt[1]=='0'?' selected': '').'>' . $def . '</option>
											<option value="1"'.($disp_opt[1]=='1'?' selected': '').'>' . $yes . '</option>
											<option value="-1"'.($disp_opt[1]=='-1'?' selected': '').'>' . $no . '</option>
										</select>
									</div>' );
								}

								// Display rating
								{
									$title = $title_head .
									wppa_setting_path( 'b', 'general', 1, 5,  wppa_switch( 'rating_on' ) ? $yes : $no ) . ',&#013;' .
									wppa_setting_path( 'b', 'thumbs', 2, 3, wppa_switch( 'thumb_text_rating' ) ? $yes : $no ) . ' ' . __( 'and' , 'wp-photo-album-plus' ) . '&#013;' .
									wppa_setting_path( 'b', 'lightbox', 1, 5, wppa_switch( 'ovl_rating' ) ? $yes : $no ) . '.';

									wppa_echo( '
									<div>
										<label
											for="showrating">' .
											__( 'Show rating', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="showrating"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'displayopt2\', this )"
											title="' . esc_attr( $title ) . '" >
											<option value="0"'.($disp_opt[2]=='0'?' selected': '').'>' . $def . '</option>
											<option value="1"'.($disp_opt[2]=='1'?' selected': '').'>' . $yes . '</option>
											<option value="-1"'.($disp_opt[2]=='-1'?' selected': '').'>' . $no . '</option>
										</select>
									</div>' );
								}

								// Display comments
								{
									$title = $title_head .
									wppa_setting_path( 'b', 'general', 1, 4, wppa_switch( 'show_comments' ) ? $yes : $no ) . ' ' . __( 'and' , 'wp-photo-album-plus' ) . '&#013;' .
									wppa_setting_path( 'b', 'thumbs', 2, 4, wppa_switch( 'thumb_text_comcount' ) ? $yes : $no ) . '.';

									wppa_echo( '
									<div>
										<label
											for="showcomments">' .
											__( 'Show comments', 'wp-photo-album-plus' ) . '
										</label><br>
										<select
											id="showcomments"
											onchange="wppaAjaxUpdateAlbum( ' . $id . ', \'displayopt3\', this )"
											title="' . esc_attr( $title ) . '" >
											<option value="0"'.($disp_opt[3]=='0'?' selected': '').'>' . $def . '</option>
											<option value="1"'.($disp_opt[3]=='1'?' selected': '').'>' . $yes . '</option>
											<option value="-1"'.($disp_opt[3]=='-1'?' selected': '').'>' . $no . '</option>
										</select>
									</div>' );
								}


						// End wppa-flex div
						wppa_echo( '</div>' );

					// End Tab 5
					wppa_echo( '
					</div>' );

					// Tab 6 Used by
					if ( ! empty( $usedby ) ) {

						wppa_echo( '
						<div
							id="albumusedby-' . $id . '"
							class="wppa-table-wrap wppa-tabcontent-'.$id.'"
							style="position:relative;padding-bottom:12px;padding-left:12px;display:none"
							>' );

							wppa_echo( '
							<table
								id="wppa-usedby"
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

				// End of outer details block
				wppa_echo( '
				</details>' );

				/* Manage photos section */
				{
				wppa_echo( '<div class="wppa-table-wrap" style="margin-top:20px;clear:both;position:relative;border:none;">' );

				wppa_echo( '<a name="manage-photos" id="manage-photos" ></a>' );

				if ( wppa_get( 'bulk' ) )
					wppa_album_photos_bulk( $edit_id );
				elseif ( wppa_get( 'seq' ) )
					wppa_album_photos_sequence( $edit_id );
				else
					wppa_album_photos( $edit_id );

				wppa_echo( '</div>' );

				}

				/* Footer of the page */
				wppa_echo( '
				<br>' .
				$top_link_html . '
			</div>' );

		} 	// End tab is Edit

		// Comment moderate
		elseif ( wppa_get( 'tab' ) == 'cmod' ) {
			$photo = wppa_get( 'photo' );
			$alb = wppa_get_album_id_by_photo_id( $photo );
			if ( current_user_can( 'wppa_comments' ) && wppa_have_access( $alb ) ) {
				$result = '
				<div class="wrap">
					<h1 class="wp-heading-inline">' .
						__( 'Moderate comment', 'wp-photo-album-plus' ) . '
					</h1>' .
					wppa_album_photos( '', $photo ) . '
				</div>';
				wppa_echo( $result );
			}
			else {
				wp_die( 'You do not have the rights to do this' );
			}
		}

		// Photo moderate
		elseif ( wppa_get( 'tab' ) == 'pmod' || wppa_get( 'tab' ) == 'pedit' ) {
			$photo = wppa_get( 'photo' );
			$alb = wppa_get_album_id_by_photo_id( $photo );
			if ( current_user_can( 'wppa_admin' ) && wppa_have_access( $alb ) ) {
				$result = '
				<div class="wrap">
					<img src="' . WPPA_URL . '/img/page_green.png" />
					<h1 class="wp-heading-inline">' . ( wppa_get( 'tab' ) == 'pmod' ?
														__( 'Moderate photo', 'wp-photo-album-plus' ) :
														__( 'Edit photo', 'wp-photo-album-plus' ) ) . '
					</h1>
					<div style="clear:both"></div>';
					wppa_album_photos( '', $photo ) . '
				</div>';
				wppa_echo( $result );
			}
			else {
				wp_die( 'You do not have the rights to do this' );
			}
		}

		// album delete confirm page
		elseif ( wppa_get( 'tab' ) == 'del' ) {

			$album_owner = $wpdb->get_var($wpdb->prepare( "SELECT owner FROM $wpdb->wppa_albums WHERE id = %s", wppa_get( 'edit-id' ) ) );
			if ( ( $album_owner == '--- public ---' && ! current_user_can( 'administrator' ) ) || ! wppa_have_access( wppa_get( 'edit-id' ) ) ) {
				wp_die( 'You do not have the rights to delete this album' );
			}

			$result = '
			<div class="wrap">
				<h1 class="wp-heading-inline">' . __( 'Delete Album', 'wp-photo-album-plus' ) . '</h1>

				<p>' . __( 'Album', 'wp-photo-album-plus' ) . '<b>' . htmlspecialchars( wppa_get_album_name( wppa_get( 'edit-id' ) ) ) . '</b></p>
				<p>' . __( 'Are you sure you want to delete this album?', 'wp-photo-album-plus' ) . '<br>' .
					__( 'Press Delete to continue, and Cancel to go back.', 'wp-photo-album-plus' ) . '
				</p>
				<form name="wppa-del-form" action="' . esc_url( get_admin_url() . 'admin.php?page=wppa_admin_menu' ) . '" method="post">' .
					wp_nonce_field( 'wppa-nonce', 'wppa-nonce' ) . '
					<h2>' .
						__( 'What would you like to do with photos currently in the album?', 'wp-photo-album-plus' ) . '<br>
						<input type="radio" name="wppa-del-photos" value="delete" checked="checked" />' . __( 'Delete', 'wp-photo-album-plus' ) . '<br>
						<input type="radio" name="wppa-del-photos" value="move" />' . __( 'Move to', 'wp-photo-album-plus' ) . '
						<select name="wppa-move-album">' .
							wppa_album_select_a( array(	'checkaccess' 		=> true,
														'path' 				=> true,
														'selected' 			=> '0',
														'exclude' 			=> strval( intval( wppa_get( 'edit-id' ) ) ),
														'addpleaseselect' 	=> true,
														'sort' 				=> true,
														) ) . '
						</select>
					</h2>

					<input
						type="hidden"
						name="wppa-del-id"
						value="' . wppa_get( 'edit-id' ) . '"
					/>
					<input
						type="button"
						class="button button-primary"
						value="' . __( 'Cancel', 'wp-photo-album-plus' ) . '"
						onclick="parent.history.back()"
					/>
					<input
						type="submit"
						class="button button-primary"
						style="color: red"
						name="wppa-del-confirm"
						value="' . __( 'Delete', 'wp-photo-album-plus' ) . '"
					/>
				</form>
			</div>';
			wppa_echo( $result );
		}

		// Unimplemented
		else {
			wppa_error_message( sprintf( __( 'Album admin action %s is not implemented', 'wp-photo-album-plus' ),
										 '<b>' . wppa_get( 'tab' ) ) . '</b>' );
		}
	}

	//  'tab' not set. default, album manage page.
	else {

		// Delete album
		if ( wppa_get( 'del-confirm' ) ) {

			if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
				wp_die( 'Security check failure' );
			}

			$album_owner = $wpdb->get_var( $wpdb->prepare( "SELECT owner FROM $wpdb->wppa_albums WHERE id = %s", wppa_get( 'del-id' ) ) );
			if ( ( $album_owner == '--- public ---' && ! current_user_can( 'administrator' ) ) || ! wppa_have_access( wppa_get( 'del-id' ) ) ) {
				wp_die( 'You do not have the rights to delete this album' );
			}

			if ( wppa_get( 'del-photos' ) == 'move' ) {
				$move = wppa_get( 'move-album' );
				if ( wppa_have_access( $move ) ) {
					wppa_del_album( wppa_get( 'del-id' ), $move );
				}
				else {
					wppa_error_message( __( 'Unable to move photos. Album not deleted.', 'wp-photo-album-plus' ) );
				}
			} else {
				wppa_del_album( wppa_get( 'del-id' ) );
			}
		}

		// Renew what we have
		$albs = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums", ARRAY_A );

		// Switch to flat / collapsable table
		if ( wppa_get( 'switchto' ) ) {
			wppa_update_option( 'wppa_album_table_'.wppa_get_user(), wppa_get( 'switchto' ) );
		}
		$style = wppa_get_option('wppa_album_table_'.wppa_get_user(), 'flat');	// 'flat' or 'collapsible'
		if ( $style != 'flat' ) $style = 'collapsible';

		/* The album admin table of albums page start */
		wppa_echo( '
		<div class="wrap">' .
			wppa_admin_spinner() . '
			<h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>' );

			// The Create new album button
			if ( wppa_can_create_top_album() ) {
				$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=new&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
				$vfy = __( 'Are you sure you want to create a new album?', 'wp-photo-album-plus' );
				wppa_echo( '
				<a
					onclick="return confirm(\'' . $vfy . '\');"
					href="' . $url . '"
					class="page-title-action">' .
					__( 'Add New', 'wp-photo-album-plus' ) . '
				</a>
				' );
			}

			/* The header buttons / selection boxes */
			{
			$no_albs = count($albs) == 0;
			$header = '
			<div class=="wppa-table-header-box">
				<div id="wppa-action-container">';

					// The switch to flat/collapsable button(s)
					if ( $style == 'flat' ) {
						$header .= '
						<input
							type="button"
							class="button"
							onclick="document.location=\'' . get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;switchto=collapsible\'"
							value="' . esc_attr__( 'Switch to Collapsable table', 'wp-photo-album-plus' ) . '"
							' . ( $no_albs ? ' disabled ' : '' ) . '
						/>';
					}
					else {
						$header .= '
						<input
							type="button"
							class="button"
							onclick="document.location=\'' . get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;switchto=flat\'"
							value="' . esc_attr__( 'Switch to Flat table', 'wp-photo-album-plus' ) . '"
							' . ( $no_albs ? ' disabled ' : '' ) . '
						/>';
					}

					// The open all/close all buttons
					if ( $style != 'flat' ) {
						$header .= '
						<input
							type="button"
							class="button"
							id="wppa-open-all"
							onclick="	jQuery(\'.alb-arrow-on\').trigger(\'click\');
										jQuery(\'#wppa-close-all\').css(\'display\',\'inline\');
										jQuery(this).css(\'display\',\'none\');
										"
							value="' . esc_attr__( 'Open all', 'wp-photo-album-plus' ) . '"
							' . ( $no_albs ? ' disabled ' : '' ) . '
						/>
						<input
							type="button"
							class="button"
							id="wppa-close-all"
							onclick="	jQuery(\'.alb-arrow-off\').trigger(\'click\');
										jQuery(\'#wppa-open-all\').css(\'display\',\'inline\');
										jQuery(this).css(\'display\',\'none\');
										"
							value="' . esc_attr__( 'Close all', 'wp-photo-album-plus' ) . '"
							' . ( $no_albs ? ' disabled ' : '' ) . '
						/>';
					}

					// Edit by id
					if ( wppa_has_many_albums() ) {
						$header .= '
						<input
							id="wppa-edit-albid"
							type="number"
							min="1"
							placeholder="' . __( 'Album id', 'wp-photo-album-plus' ) . '"
							title="' . __( 'Enter the number of the album you want to edit', 'wp-photo-album-plus' ) . '"
							style="width:120px;cursor:pointer"
							' . ( $no_albs ? ' disabled ' : '' ) . '
							/>';
					}
					else {
						$albids = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums ORDER BY id" );
						if ( ! wppa_user_is_admin() ) foreach( array_keys( $albids ) as $key ) {
							if ( ! wppa_have_access( $albids[$key] ) ) {
								unset( $albids[$key] );
							}
						}
						$header .= '
						<select
							id="wppa-edit-albid"
							title="' . __( 'Select the number of the album you want to edit', 'wp-photo-album-plus' ) . '"
							styles="width:120px;cursor:pointer;vertical-align:baseline"
							' . ( $no_albs ? 'disabled' : '' ) . '
							/>
							<option value="" selected disabled>' . __( 'Album id', 'wp-photo-album-plus' ) . '</option>';
							foreach( $albids as $alb ) {
								$header .= '<option value="' . $alb . '"' . ( $no_albs ? ' disabled ' : '' ) . '>' . $alb . '</option>';
							}
						$header .= '
						</select>';
					}
					$header .= '
					<input
						type="button"
						class="button"
						value="' . __( 'Edit album', 'wp-photo-album-plus' ) . '"
						onclick="wppaGoEditAlbNo();"
						' . ( $no_albs ? 'disabled' : '' ) . '
					/>';

					// Filter by searchword
					$opts = $wpdb->get_col( "SELECT slug FROM $wpdb->wppa_index WHERE albums <> '' ORDER BY slug" );
					$f = wppa_get( 'filter' );
					$header .= '
					<select
						id="wppa-edit-filter"
						title="' . __( 'Select an album search word', 'wp-photo-album-plus' ) . '"
						' . ( $no_albs ? 'disabled' : '' ) . '
						>
						<option value="" selected disabled>' . __( 'Filter by', 'wp-photo-album-plus' ) . '</option>';
						foreach( $opts as $opt ) {
							$header .= '<option value="' . $opt . '"' . ( $f == $opt ? ' selected ' : '' ) . ( $no_albs ? ' disabled ' : '' ) . '>' . $opt . '</option>';
						}
					$header .= '
					</select>
					<input
						type="button"
						class="button"
						onclick="wppaGoApplyFilter();"
						value="' . __( 'Apply filter', 'wp-photo-album-plus' ) . '"
						' . ( $no_albs ? 'disabled' : '' ) . '
					/>';

					// Close action container
					$header .= '
				</div>';
			wppa_echo( $header );

			/* End header buttons / selection boxes */
			}

			// No albums yet?
			if ( count($albs) == 0 ) {
				wppa_echo( '<h2>' . __( 'No albums yet. Click the "Add New" button', 'wp-photo-album-plus' ) . '</h2>' );
				wppa_echo( '</div>' );
				return;
			}


			// The table of existing albums. $style = 'flat' or 'collapsible'
			if ( in_array( $style, ['flat', 'collapsible'] ) ) {
				call_user_func( 'wppa_admin_albums_' . $style );
			}

			wppa_echo( '<hr style="background-color:#777;height:3px;margin:20px 0;">' );

			// The drag-drop sequence editor for toplevel albums
			wppa_album_sequence( '0' );

		wppa_echo( '</div>' );
		/* The album admin table of albums page end */
	}
}

// The albums table flat
function wppa_admin_albums_flat( $page_1 = false ) {
global $wpdb;

	// Get paging parameters
	$parms 		= wppa_get_paging_parms( 'album_admin', $page_1 );
	$pagesize 	= $parms['pagesize'];
	$page 		= $parms['page'];
	$skips 		= ( $page - 1 ) * $pagesize;
	$order_by 	= $parms['order'];
	$dir 		= $parms['dir'];

	// Read all albums, pre-ordered
	$albums = $wpdb->get_results( "SELECT * FROM `$wpdb->wppa_albums` ORDER BY `$order_by` $dir", ARRAY_A );

	// Remove non accessible albums
	$temp = $albums;
	$albums = array();
	foreach ( array_keys( $temp ) as $idx ) {
		if ( wppa_have_access( $temp[$idx]['id'] ) ) {
			$albums[] = $temp[$idx];
		}
	}

	// If filter, filter
	$filter = wppa_get( 'filter' );

	if ( $filter ) {
		$filter_albs = $wpdb->get_var( $wpdb->prepare( "SELECT albums FROM `$wpdb->wppa_index` WHERE `slug` = %s LIMIT 1", $filter ) );
		$filter_albs = wppa_index_string_to_array( $filter_albs );

		foreach( array_keys( $albums ) as $key ) {
			if ( ! in_array( $albums[$key]['id'], $filter_albs ) ) {
				unset( $albums[$key] );
			}
		}
	}

	// Potentially total albums
	$total = count( $albums );

	// If page out of range, redo with page = 1
	if ( $total && $skips >= $total ) {
		wppa_admin_albums_flat( true );
		return;
	}

	// If paging: Make new array with selected albums only
	if ( $pagesize ) {
		$albums = array_slice( $albums, $skips, $pagesize );
	}

	// Do the dirty work
	if ( ! empty( $albums ) ) {

		$downimg 	= '<img src="'.wppa_get_imgdir('Down-2.svg').'" alt="down" style="height:1em;" />';
		$upimg   	= '<img src="'.wppa_get_imgdir('Up-2.svg').'" alt="up" style="height:1em;" />';
		$useimg 	= $parms['dir'] == 'desc' ? $upimg : $downimg;
		$show_nl 	= wppa_opt( 'user_create_max_level' ) != '99';
		$reload_url = get_admin_url() . 'admin.php?page=wppa_admin_menu';
		$order 		= $parms['order'];

		wppa_admin_pagination( $pagesize, $page, $total, $reload_url, 'top' );

		wppa_echo( '</div>' ); // Close action contatiner

		$result = '
		<table
			class="wppa-table widefat wppa-setting-table striped"
			style="margin:12px 0;"
			>
			<thead>';
				$thead_body = '
				<tr>
					<td
						style="min-width:75px;cursor:pointer;"
						title="' . ( $show_nl ? esc_attr( __( 'The album id (the nesting level)', 'wp-photo-album-plus' ) ) :
												esc_attr( __( 'The album id', 'wp-photo-album-plus' ) ) ) . '"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'id' ) . '\'"
						>' .
						( $order == 'id' ? $useimg : '' ) . '<br>' .
						( $show_nl ? __( 'ID(nl)', 'wp-photo-album-plus' ) : __( 'ID', 'wp-photo-album-plus' ) ) . '
					</td>
					<td
						style="min-width: 120px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'name' ) . '\'"
						>' .
						( $order == 'name' ? $useimg : '' ) . '<br>' .
						__( 'Name', 'wp-photo-album-plus' ) . '
					</td>
					<td
						style="cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'description' ) . '\'"
						>' .
						( $order == 'description' ? $useimg : '' ) . '<br>' .
						__( 'Description', 'wp-photo-album-plus' ) . '
					</td>';
					if ( current_user_can( 'administrator' ) ) {
						$thead_body .= '
						<td style="min-width: 100px;cursor:pointer;"
							onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'owner' ) . '\'"
							>' .
							( $order == 'owner' ? $useimg : '' ) . '<br>' .
							__( 'Owner', 'wp-photo-album-plus' ) . '
						</td>';
					}
					$thead_body .= '
					<td
						style="min-width: 100px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'a_order' ) . '\'"
						>' .
						( $order == 'a_order' ? $useimg : '' ) . '<br>' .
						__( 'Order', 'wp-photo-album-plus' ) . '
					</td>
					<td
						style="width: 120px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'a_parent' ) . '\'"
						>' .
						( $order == 'a_parent' ? $useimg : '' ) . '<br>' .
						__( 'Parent', 'wp-photo-album-plus' ) . '
					</td>
					<td
						title="' . esc_attr( __( 'Albums/Photos/Moderation required/Scheduled', 'wp-photo-album-plus' ) ) . '"
						>' .
						__( 'A/P/PM/S', 'wp-photo-album-plus' ) . '
					</td>
					<td>' . __( 'Edit', 'wp-photo-album-plus' ) . '</td>
					<td>' . __( 'Quick', 'wp-photo-album-plus' ) . '</td>
					<td>' . __( 'Bulk', 'wp-photo-album-plus' ) . '</td>
					<td>' . ( ! wppa_switch( 'porder_restricted' ) || wppa_user_is_admin() ? __( 'Seq', 'wp-photo-album-plus' ) : '' ) . '</td>
					<td>' . __( 'CovImg', 'wp-photo-album-plus' ) . '</td>' .
					( current_user_can( 'wppa_upload' ) ? '<td>' . __( 'Upload', 'wp-photo-album-plus' ) . '</td>' : '' ) .
					( current_user_can( 'wppa_import' ) ? '<td>' . __( 'Import', 'wp-photo-album-plus' ) . '</td>' : '' ) . '
					<td>' . __( 'Delete', 'wp-photo-album-plus' ) . '</td>' .
					( wppa_can_create_album() ? '<td>' . __( 'Create', 'wp-photo-album-plus' ) . '</td>' : '' ) . '
				</tr>';
				$result .= $thead_body;
				$result .= '
			</thead>';

			// So far, output header
			wppa_echo( $result );

				$alt = ' class="alternate" ';
				$idx = '0';
				$result = '<tbody>';
				$mayseq = ! wppa_switch( 'porder_restricted' ) || wppa_user_is_admin();

				foreach ( $albums as $album ) {

					$id 	= $album['id'];
					$counts = wppa_get_treecounts_a( $id, true );
					$url 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=' . $id;
					$na 	= $counts['selfalbums'];
					$np 	= $counts['selfphotos'];
					$nm 	= $counts['pendselfphotos'];
					$ns 	= $counts['scheduledselfphotos'];
					$covid 	= max( $album['main_photo'], '0' );
					$curl 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=single&amp;photo=' . $covid . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '&amp;just-edit=' . __( 'Edit cover image', 'wp-photo-album-plus' );

					if ( wppa_have_access( $album ) ) {
						$pendcount 	= $counts['pendselfphotos'];
						$url 		= wppa_ea_url( $id );
						$delurl 	= wppa_ea_url( $id, 'del' );
						$creurl 	= get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=new&amp;parent_id=' . $id . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
						$creonc 	= 'if (confirm(\''.esc_js(__( 'Are you sure you want to create a sub album?', 'wp-photo-album-plus' )).'\')) document.location=\''.$creurl.'\';';

						$result .= '
						<tr ' . $alt . ( $pendcount ? 'style="background-color:#ffdddd"' : '' ) . '>
							<td>' . $id . ( $show_nl ? '(' . wppa_get_nesting_level( $id ) . ')' : '' ) . '</td>
							<td>' . esc_html( stripslashes( $album['name'] ) ) . '</td>
							<td><small>' . esc_html( stripslashes( $album['description'] ) ) . '</small></td>' .
							( current_user_can( 'administrator' ) ? '<td>' . __( $album['owner'], 'wp-photo-album-plus' ) . '</td>' : '' ) . '
							<td>' . $album['a_order'] . '</td>
							<td>' . wppa_get_album_name( $album['a_parent'], array( 'extended' => true ) ) . '</td>
							<td>' . $na . '/' . $np . '/' . $nm . '/' . $ns . '</td>
							<td><a href="' . $url . '" class="wppaedit">' . __( 'Edit', 'wp-photo-album-plus' ) . '</a></td>
							<td><a href="' . $url . '&amp;quick=1" class="wppaedit">' . __( 'Quick', 'wp-photo-album-plus' ) . '</a></td>
							<td><a href="' . $url . '&amp;bulk=1#manage-photos" class="wppaedit">' . __( 'Bulk', 'wp-photo-album-plus' ) . '</a></td>
							<td>' . ( $mayseq ? '<a href="' . $url . '&amp;seq=1" class="wppaedit">' . __( 'Seq', 'wp-photo-album-plus' ) . '</a>' : '' ) . '</td>
							<td>' . ( $covid ? '<a href="' . $curl . '" class="wppaedit">' . __( 'CovImg', 'wp-photo-album-plus' ) . '</a>' : '' ) . '</td>';
							if ( current_user_can( 'wppa_upload' ) ) {
								$result .= '<td><a href="' . get_admin_url() . '/admin.php?page=wppa_upload_photos&wppa-set-album=' . $id . '" class="wppaedit" >' . __( 'Upload', 'wp-photo-album-plus' ) . '</a></td>';
							}
							if ( current_user_can( 'wppa_import' ) ) {
								$result .= '<td><a href="' . get_admin_url() . '/admin.php?page=wppa_import_photos&wppa-set-album=' . $id . '" class="wppaedit" >' . __( 'Import', 'wp-photo-album-plus' ) . '</a></td>';
							}
							$result .= '
							<td><a href="' . $delurl . '" class="wppadelete">' . __( 'Delete', 'wp-photo-album-plus' ) . '</a></td>';
							if ( wppa_can_create_album() ) {
								$result .= '<td><a onclick="' . $creonc . '" class="wppacreate">' . __( 'Create', 'wp-photo-album-plus' ) . '</a></td>';
							}
							$result .= '
						</tr>';
						if ( $alt == '' ) { $alt = ' class="alternate" '; } else { $alt = ''; }
					}
					$idx++;
				}
				$result .=
				wppa_search_edit( false ) .
				wppa_trash_edit( false ) . '
			</tbody>';

			// Output tbody
			wppa_echo( $result );

			$result = '
			<tfoot>' .
				$thead_body . '
			</tfoot>
		</table>';

		// Display the album table footer
		wppa_echo( $result );

		// Display pagination
		wppa_admin_pagination( $pagesize, $page, $total, $reload_url, 'bottom' );

		// Display footer
		wppa_echo( wppa_album_admin_footer() );
	}

	// Total = 0
	else {
		wppa_echo( '</div>' ); // Close action contatiner
		wppa_echo( '
		<h2>' .
			__( 'No albums yet that you can edit.', 'wp-photo-album-plus' ) .
			( wppa_can_create_top_album() ? ' ' . __( 'Click the "Add New" button', 'wp-photo-album-plus' ) : '' ) . '
		</h2>' );
	}
}

// The albums table collapsible
function wppa_admin_albums_collapsible( $page_1 = false ) {
global $wpdb;

	// Get paging parameters
	{
	$parms 		= wppa_get_paging_parms( 'album_admin', $page_1 );
	$pagesize 	= $parms['pagesize'];
	$page 		= $parms['page'];
	$skips 		= ( $page - 1 ) * $pagesize;
	$order_by 	= $parms['order'];
	$dir 		= $parms['dir'];
	}

	// Read all albums, pre-ordered
	$albums = $wpdb->get_results( "SELECT * FROM `$wpdb->wppa_albums` ORDER BY `$order_by` $dir", ARRAY_A );

	// Remove non accessible albums
	$temp = $albums;
	$albums = array();
	foreach ( array_keys( $temp ) as $idx ) {
		if ( wppa_have_access( $temp[$idx]['id'] ) ) {
			$albums[] = $temp[$idx];
		}
	}

	// If filter, filter
	$filter = wppa_get( 'filter' );

	if ( $filter ) {
		$filter_albs = $wpdb->get_var( $wpdb->prepare( "SELECT albums FROM `$wpdb->wppa_index` WHERE `slug` = %s LIMIT 1", $filter ) );
		$filter_albs = wppa_index_string_to_array( $filter_albs );

		foreach( array_keys( $albums ) as $key ) {
			if ( ! in_array( $albums[$key]['id'], $filter_albs ) ) {
				unset( $albums[$key] );
			}
		}
	}

	// Potentially total albums
	$total = count( $albums );

	// If page out of range, redo with page = 1
	if ( $total && $skips >= $total ) {
		wppa_admin_albums_collapsible( true );
		return;
	}

	// If paging: Make new array with selected albums only
	if ( $pagesize ) {
		$albums = array_slice( $albums, $skips, $pagesize );
	}

	// Make sure all (grand)parents are in
	$done = false;
	while ( ! $done ) {

		$done = true;
		foreach ( $albums as $a ) {

			$parent = $a['a_parent'];
			if ( $parent > '0' ) {

				$found = false;
				foreach ( $albums as $p ) {

					if ( $p['id'] == $parent ) {
						$found = true;
					}
				}
				if ( ! $found ) {

					$done = false;

					// Add missing parent
					$albums[] = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE id = %d", $parent ), ARRAY_A );
				}
			}
		}
	}

	// Make sure all (grand)children are in when pagination is on
	if ( $pagesize ) {
		$current_ids = implode( '.', array_column( $albums, 'id' ) );
		$all_ids = array_unique( explode( '.', wppa_alb_to_enum_children( $current_ids ) ) );
		$albums = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums
									   WHERE id in (" . implode( ',', $all_ids ) . ")", ARRAY_A );
	}

	// If any albums left, do the dirty work
	if ( ! empty( $albums ) ) {

		// Setup the sequence array
		$seq = array();
		$num = false;
		foreach( $albums as $album ) {
			switch ( $order_by ) {
				case 'name':
					$seq[] = strtolower( __( stripslashes( $album['name'] ) ) );
					break;
				case 'description':
					$seq[] = strtolower( __( stripslashes( $album['description'] ) ) );
					break;
				case 'owner':
					$seq[] = strtolower( $album['owner'] );
					break;
				case 'a_order':
					$seq[] = $album['a_order'];
					$num = true;
					break;
				case 'a_parent':
					$seq[] = strtolower( wppa_get_album_name( $album['a_parent'], array( 'extended' => true ) ) );
					break;
				default:
					$seq[] = $album['id'];
					$num = true;
					break;
			}
		}

		// Sort the seq array
		if ( $num ) asort( $seq, SORT_NUMERIC );
		else asort( $seq, SORT_REGULAR );

		// Reverse ?
		if ( $dir == 'desc' ) {
			$t = $seq;
			$c = count($t);
			$tmp = array_keys($t);
			$seq = false;
			for ( $i = $c-1; $i >=0; $i-- ) {
				$seq[$tmp[$i]] = '0';
			}
		}

		$downimg 	= '<img src="'.wppa_get_imgdir('Down-2.svg').'" alt="down" style="height:1em;" />';
		$upimg  	= '<img src="'.wppa_get_imgdir('Up-2.svg').'" alt="up" style="height:1em;" />';
		$useimg 	= $dir == 'desc' ? $upimg : $downimg;
		$show_nl 	= wppa_opt( 'user_create_max_level' ) != '99';
		$reload_url = get_admin_url() . 'admin.php?page=wppa_admin_menu';
		$order 		= $parms['order'];

		wppa_admin_pagination( $pagesize, $page, $total, $reload_url, 'top' );

		wppa_echo( '</div>' ); // Close action contatiner

		$result = '
		<table class="widefat wppa-table wppa-setting-table" style="margin:12px 0">
			<thead>';
				$thead_body = '
				<tr>
					<td style="width:2em;">
						<br>
						<img src="' . wppa_get_imgdir('Left-6.svg') . '" style="height:1em;" title="' . __( 'Collapse subalbums', 'wp-photo-album-plus' ) . '" />
						<img src="' . wppa_get_imgdir('Right-6.svg') . '" style="height:1em;" title="' . __( 'Expand subalbums', 'wp-photo-album-plus' ) . '" />
					</td>
					<td colspan="6"
						style="min-width:75px;cursor:pointer;"
						title="' . ( $show_nl ? esc_attr( __( 'The album id (the nesting level)', 'wp-photo-album-plus' ) ) :
												esc_attr( __( 'The album id', 'wp-photo-album-plus' ) ) ) . '"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'id' ) . '\'"
						>' .
						( $order == 'id' ? $useimg : '' ) . '<br>' .
						( $show_nl ? __( 'ID(nl)', 'wp-photo-album-plus' ) : __( 'ID', 'wp-photo-album-plus' ) ) . '
					</td>
					<td
						style="min-width: 120px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'name' ) . '\'"
						>' .
						( $order == 'name' ? $useimg : '' ) . '<br>' .
						__( 'Name', 'wp-photo-album-plus' ) . '
					</td>
					<td
						style="cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'description' ) . '\'"
						>' .
						( $order == 'description' ? $useimg : '' ) . '<br>' .
						__( 'Description', 'wp-photo-album-plus' ) . '

					</td>';
					if ( current_user_can( 'administrator' ) ) {
						$thead_body .= '
						<td style="min-width: 100px;cursor:pointer;"
							onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'owner' ) . '\'"
							>' .
							( $order == 'owner' ? $useimg : '' ) . '<br>' .
							__( 'Owner', 'wp-photo-album-plus' ) . '
						</td>';
					}
					$thead_body .= '
					<td
						style="min-width: 100px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'a_order' ) . '\'"
						>' .
						( $order == 'a_order' ? $useimg : '' ) . '<br>' .
						__( 'Order', 'wp-photo-album-plus' ) . '

					</td>
					<td
						style="width: 120px;cursor:pointer;"
						onclick="document.location.href=\'' . wppa_admin_reload_url( 'album_admin', 'a_parent' ) . '\'"
						>' .
						( $order == 'a_parent' ? $useimg : '' ) . '<br>' .
						__( 'Parent', 'wp-photo-album-plus' ) . '
					</td>
					<td
						title="' . esc_attr( __( 'Albums/Photos/Moderation required/Scheduled', 'wp-photo-album-plus' ) ) . '"
						>' .
						__( 'A/P/PM/S', 'wp-photo-album-plus' ) . '
					</td>
					<td>' . __( 'Edit', 'wp-photo-album-plus' ) . '</td>
					<td>' . __( 'Quick', 'wp-photo-album-plus' ) . '</td>
					<td>' . __( 'Bulk', 'wp-photo-album-plus' ) . '</td>
					<td>' . ( ! wppa_switch( 'porder_restricted' ) || wppa_user_is_admin() ? __( 'Seq', 'wp-photo-album-plus' ) : '' ) . '</td>
					<td>' . __( 'CovImg', 'wp-photo-album-plus' ) . '</td>' .
					( current_user_can( 'wppa_upload' ) ? '<td>' . __( 'Upload', 'wp-photo-album-plus' ) . '</td>' : '' ) .
					( current_user_can( 'wppa_import' ) ? '<td>' . __( 'Import', 'wp-photo-album-plus' ) . '</td>' : '' ) . '
					<td>' . __( 'Delete', 'wp-photo-album-plus' ) . '</td>' .
					( wppa_can_create_album() ? '<td>' . __( 'Create', 'wp-photo-album-plus' ) . '</td>' : '' ) . '
				</tr>';
				$result .= $thead_body;
				$result .= '
			</thead>';

			// So far, output header
			wppa_echo( $result );

			// Now the body
			$result = '
			<tbody>';
			wppa_echo( $result );
			wppa_do_albumlist( 'top', '0', $albums, $seq );
			$result =
			wppa_search_edit( true ) .
			wppa_trash_edit( true ) . '
			</tbody>';

			// Output tbody
			wppa_echo( $result );

			$result = '
			<tfoot>' .
				$thead_body . '
			</tfoot>
		</table>';

		// Display the album table footer
		wppa_echo( $result );

		// Display the pagination
		wppa_admin_pagination( $pagesize, $page, $total, $reload_url, 'bottom' );

		// Display the footer
		wppa_echo( wppa_album_admin_footer() );
	}
	else {
		wppa_echo( '</div>' ); // Close action contatiner
		wppa_echo( '
		<h2>' .
			__( 'No albums yet that you can edit.', 'wp-photo-album-plus' ) .
			( wppa_can_create_top_album() ? ' ' . __( 'Click the "Add New" button', 'wp-photo-album-plus' ) : '' ) . '
		</h2>' );
	}
}

// The adnin Search field
function wppa_search_edit( $collapsible = false ) {
global $plugin_page;

	$doit = false;
	if ( current_user_can( 'wppa_admin' ) && current_user_can( 'wppa_moderate' ) ) $doit = true;
	if ( wppa_opt( 'upload_edit' ) != '-none-' ) $doit = true;

	if ( ! $doit ) return;

	$value = wppa_get( 'searchstring' );

	$result = '';

	if ( $plugin_page == 'wppa_options' ) {
		$result = '
		<tr>
			<td colspan="' . ( ( $collapsible ? '20' : '14' ) + ( current_user_can( 'wppa_upload' ) ? '1' : '0' ) + ( current_user_can( 'wppa_import' ) ? '1' : '0' ) ) . '" >
				<em>' .
					__( 'Search for photos to edit', 'wp-photo-album-plus' ) . '
				</em>
				<small>' .
					__( 'Enter search words seperated by commas. Photos will meet all search words by their names, descriptions, translated keywords and/or tags.', 'wp-photo-album-plus' ) . '
				</small>
			</td>
		</tr>';
		}

	$result .= '
	<tr class="alternate" >' .
		( $collapsible ? '<td></td>' : '' ) . '
		<td id="src-alb" >' .
			__( 'Any', 'wp-photo-album-plus' ) . '
		</td>' .
		( $collapsible ? '<td></td><td></td><td></td><td></td><td></td>' : '' ) . '
		<td>' .
			__( 'Search for', 'wp-photo-album-plus' ) . '
		</td>
		<td colspan="4" >
			<a id="wppa-edit-search-tag" />
			<input
				type="text"
				id="wppa-edit-search"
				name="wppa-edit-search"
				style="width:100%;padding:2px;color:black;background-color:#ccffcc;"
				value="' . $value . '"
			/>
		</td>' .
		( current_user_can( 'wppa_admin' ) && current_user_can( 'wppa_moderate' ) ? '<td></td>' : '' ) . '
		<td>
			<a class="wppaedit" onclick="wppaEditSearch(\'' . wppa_ea_url( 'search' ) . '\', \'wppa-edit-search\' )" >
				<span style="font-weight:bold">' . __( 'Edit', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td>
			<a class="wppaedit" onclick="wppaEditSearch(\'' . wppa_ea_url( 'search' ) . '&amp;quick' . '\', \'wppa-edit-search\' )" >
				<span style="font-weight:bold">' . __( 'Quick', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td>
			<a class="wppaedit" onclick="wppaEditSearch(\'' . wppa_ea_url( 'search' ) . '&amp;bulk=1' . '\', \'wppa-edit-search\' )" >
				<span style="font-weight:bold">' . __( 'Bulk', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td></td><td></td><td></td>
		<td colspan="' . strval( 1 + ( current_user_can( 'wppa_upload' ) ? 1 : 0 ) + ( current_user_can( 'wppa_import' ) ? 1 : 0 ) ) . '"></td>
	</tr>';

	return $result;
}

// The admin Trash field
function wppa_trash_edit( $collapsible = false ) {
global $wpdb;

	$doit = false;
	if ( wppa_user_is_admin() ) $doit = true;

	$trashed = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album < '0'" );

	if ( ! $trashed ) $doit = false;

	if ( ! $doit ) return '';

	$result = '
	<tr>' .
		( $collapsible ? '<td></td>' : '' ) . '
		<td>' . __( 'Any', 'wp-photo-album-plus' ) . '</td>' .
		( $collapsible ? '<td colspan="5">' : '' ) . '
		<td colspan="4" >' .
			sprintf( __( 'There are %s trashed photos that can be rescued', 'wp-photo-album-plus' ), $trashed ) . '
		</td>
		<td colspan="2">
		</td>
		<td>
			<a class="wppaedit" onclick="wppaEditTrash( \'' . wppa_ea_url( 'trash' ) . '\' );">
				<span style="font-weight:bold">' . __( 'Edit', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td>
			<a class="wppaedit" onclick="wppaEditTrash( \'' . wppa_ea_url( 'trash' ) . '&amp;quick=1' . '\' );">
				<span style="font-weight:bold">' . __( 'Quick', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td>
			<a class="wppaedit" onclick="wppaEditTrash( \'' . wppa_ea_url( 'trash' ) . '&amp;bulk=1' . '\' );">
				<span style="font-weight:bold">' . __( 'Bulk', 'wp-photo-album-plus' ) . '</span>
			</a>
		</td>
		<td colspan="3"></td>' .
		( current_user_can( 'wppa_upload' ) ? '<td></td>' : '' ) .
		( current_user_can( 'wppa_import' ) ? '<td></td>' : '' ) .
		( wppa_can_create_album() ? '<td></td>' : '' ) . '
	</tr>';

	return $result;
}

// The albumlist
function wppa_do_albumlist( $xparent, $nestinglevel, $albums, $seq ) {
global $wpdb;

	// Init
	$show_nl 	= wppa_opt( 'user_create_max_level' ) != '99';

	$alt = true;

	foreach ( array_keys( $seq ) as $s ) {			// Obey the global sequence
		$album 	= $albums[$s];
		$id 	= $album['id'];
		$parent = $album['a_parent'];
		if ( $parent == $xparent || ( $xparent == 'top' && $parent < '1' ) ) {

			$counts 		= wppa_get_treecounts_a( $id, true );
			$pendcount 		= $counts['pendselfphotos'];
			$schedulecount 	= $counts['scheduledselfphotos'];
			$haschildren 	= wppa_have_accessible_children( $album );
			$pchildren 		= $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = %d", $id ) );
			if ( is_array( $pchildren ) ) foreach( array_keys( $pchildren ) as $key ) {
				if ( ! wppa_have_accessible_children( $pchildren[$key] ) ) {
					unset( $pchildren[$key] );
				}
			}
			else {
				$pchildren = array();
			}

			$class = '';
			if ( $parent > '0' ) {
				$class .= 'wppa-alb-onoff ';
				$class .= 'wppa-alb-on-' . $parent . ' ';
				$par = $parent;
				while ( $par != '0' && $par != '-1' ) {
					$class .= 'wppa-alb-off-' . $par . ' ';
					$par = wppa_get_parentalbumid( $par );
				}
			}
			if ( $alt ) $class .= ' alternate';
			$style = '';
			if ( $pendcount ) $style .= 'background-color:#ffdddd; ';
			if ( $parent > '0' ) $style .= 'display:'.( wppa_is_tree_open( $parent ) ? '' : 'none' );

			$onclickon =
				'jQuery(\'.wppa-alb-on-'.$id.'\').css(\'display\',\'\');' .
				'jQuery(\'#alb-arrow-on-'.$id.'\').css(\'display\',\'none\');' .
				'jQuery(\'#alb-arrow-off-'.$id.'\').css(\'display\',\'\');' .
				'wppa_setCookie(\'alb-arrow-'.$id.'\',\'on\',365);';
			$onclickoff =
				'jQuery(\'.wppa-alb-off-'.$id.'\').css(\'display\',\'none\');' .
				'jQuery(\'#alb-arrow-on-'.$id.'\').css(\'display\',\'\');' .
				'jQuery(\'#alb-arrow-off-'.$id.'\').css(\'display\',\'none\');' .
				'wppa_setCookie(\'alb-arrow-'.$id.'\',\'off\',365);';
				foreach( $pchildren as $c ) {
					$onclickoff .= 'jQuery(\'#alb-arrow-off-' . $c . '\').trigger(\'click\');';
				}

			$indent = $nestinglevel;
			if ( $indent > 5 ) $indent = 5;

			// Open the album line
			$result = '
			<tr
				id="alb-' . $id . '"
				class="' . $class . '"
				style="' . $style . '"
				>';

				// Fillers before the arrow
				$i = 0;
				while ( $i < $indent ) {
					$result .= '<td style="padding:2px"></td>';
					$i++;
				}

				// The arrow image td element
				$result .= '
				<td style="padding:2px; text-align:center">';

				// Only if the album has children the arrow will show up
				if ( $haschildren ) {
					$result .= '
					<img
						id="alb-arrow-off-' . $id . '"
						class="alb-arrow-off"
						style="height:1em;display:'.( wppa_get_cookie( 'alb-arrow-' . $id ) == 'on' ? '' : 'none' ).'"
						src="' . wppa_get_imgdir() . 'Left-6.svg' . '"
						onclick="' . $onclickoff . '"
						title="' . esc_attr( __( 'Collapse sub albums', 'wp-photo-album-plus' ) ) . '"
					/>
					<img
						id="alb-arrow-on-' . $id . '"
						class="alb-arrow-on"
						style="height:1em;display:'.( wppa_get_cookie( 'alb-arrow-' . $id ) == 'on' ? 'none' : '' ).'"
						src="' . wppa_get_imgdir() . 'Right-6.svg' . '"
						onclick="' . $onclickon . '"
						title="' . esc_attr( __( 'Expand sub albums', 'wp-photo-album-plus' ) ) . '"
					/>';
				}

				// Close the arrow image td element
				$result .= '
				</td>';

				// The album id td element
				$result .= '
				<td style="padding:2px">' . $id . ( $show_nl ? '(' . wppa_get_nesting_level( $album['id'] ) . ')' : '' ) . '</td>';

				// Fillers after the arrow
				$i = $indent;
				while ( $i < 5 ) {
					$result .= '<td style="padding:2px"></td>';
					$i++;
				}

				$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=' . $id;
				$na = $counts['selfalbums'];
				$np = $counts['selfphotos'];
				$nm = $counts['pendselfphotos'];
				$ns = $counts['scheduledselfphotos'];

				// The album name and description
				$result .= '
				<td>' . esc_html( stripslashes( $album['name'] ) ) . '</td>
				<td><small>' . esc_html( stripslashes( $album['description'] ) ) . '</small></td>';

				// The owner. Make sure he exists
				if ( current_user_can( 'administrator' ) ) {
					$album_owner = $album['owner'];
					if ( $album_owner == '--- public ---' || get_user_by( 'login', $album_owner ) ) {
						$result .= '
						<td>' . $album_owner . '</td>';
					}
					else {
						$result .= '
						<td>' . __( '--- unknown ---', 'wp-photo-album-plus' ) . '</td>';
					}
				}

				// Order
				$result .= '
				<td>' . $album['a_order'] . '</td>
				<td>' . esc_html( stripslashes( wppa_get_album_name( $album['a_parent'], ['extended' => true] ) ) ) . '</td>
				<td>' . $na . '/' . $np . '/' . $nm . '/' . $ns . '</td>';

				if ( wppa_have_access( $album['id'] ) ) {
					$url = wppa_ea_url($id);

					$result .= '
					<td><a href="' . $url . '" class="wppaedit">' . __( 'Edit', 'wp-photo-album-plus' ) . '</a></td>
					<td><a href="' . $url . '&amp;quick=1" class="wppaedit">' . __( 'Quick', 'wp-photo-album-plus' ) . '</a></td>
					<td><a href="' . $url . '&amp;bulk=1#manage-photos" class="wppaedit">' . __( 'Bulk', 'wp-photo-album-plus' ) . '</a></td>';

					if ( ! wppa_switch( 'porder_restricted' ) || wppa_user_is_admin() ) {
						$result .= '
						<td><a href="' . $url . '&amp;seq=1" class="wppaedit">' . __( 'Seq', 'wp-photo-album-plus' ) . '</a></td>';
					}
					else {
						$result .= '<td></td>';
					}

					$covid = max( $album['main_photo'], '0' );
					if ( $covid ) {
						$curl = get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=single&amp;photo=' . $covid . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '&amp;just-edit=' . __( 'Edit cover image', 'wp-photo-album-plus' );
						$result .= '<td><a href="' . $curl . '" class="wppaedit">' . __( 'CovImg', 'wp-photo-album-plus' ) . '</a></td>';
					}
					else {
						$result .= '<td></td>';
					}
					if ( current_user_can( 'wppa_upload' ) ) {
						$result .= '<td><a href="' . get_admin_url() . '/admin.php?page=wppa_upload_photos&wppa-set-album=' . $id . '" class="wppaedit" >' . __( 'Upload', 'wp-photo-album-plus' ) . '</a></td>';
					}
					if ( current_user_can( 'wppa_import' ) ) {
						$result .= '<td><a href="' . get_admin_url() . '/admin.php?page=wppa_import_photos&wppa-set-album=' . $id . '" class="wppaedit" >' . __( 'Import', 'wp-photo-album-plus' ) . '</a></td>';
					}
					if ( wppa_user_is_admin() || wppa_get_user() == $album['owner'] ) {
						$url = wppa_ea_url( $id, 'del' );
						$result .= '<td><a href="' . $url . '" class="wppadelete" >' . __( 'Delete', 'wp-photo-album-plus' ) . '</a></td>';
						}
					else {
						$result .= '<td></td>';
					}
					if ( wppa_can_create_album() ) {
						$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit-id=new&amp;parent_id=' . $id . '&amp;wppa-nonce=' . wp_create_nonce( 'wppa-nonce' );
						$onc = 'if (confirm(\''.__( 'Are you sure you want to create a sub album?', 'wp-photo-album-plus' ).'\')) document.location=\''.$url.'\';';
						$result .= '<td><a onclick="' . $onc . '" class="wppacreate">' . __( 'Create', 'wp-photo-album-plus' ) . '</a></td>';
					}
				}
				else {
					if ( wppa_can_create_album() ) $result .= '<td></td>';
					if ( current_user_can( 'wppa_upload' ) ) $result .= '<td></td>';
					if ( current_user_can( 'wppa_import' ) ) $result .= '<td></td>';
					$result .= '<td></td><td></td><td></td><td></td><td></td><td></td>';
				}

			$result .= '
			</tr>';
			if ( $alt == '' ) { $alt = ' class="alternate" '; } else { $alt = '';}

			wppa_echo( $result );

			if ( $haschildren ) wppa_do_albumlist( $id, $nestinglevel + '1', $albums, $seq );
		}
	}
}

// See if (grand)parent tree is open
function wppa_is_tree_open( $id ) {

	while ( $id > 0 ) {
		if ( wppa_get_cookie( 'alb-arrow-' . $id ) != 'on' ) {
			return false;
		}
		$id = wppa_get_album_item( $id, 'a_parent' );
	}
	return true;
}

// Find accessable sub albums
function wppa_have_accessible_children( $alb ) {
global $wpdb;

	if ( is_array( $alb ) ) {
		$id = $alb['id'];
	}
	else {
		$id = $alb;
	}
	$albums = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d", $id ), ARRAY_A );

	if ( ! $albums || ! count( $albums ) ) return false;
	foreach ( $albums as $album ) {
		if ( wppa_have_access( $album ) ) return true;
	}
	return false;
}

// delete an album
function wppa_del_album( $id, $move = '-9' ) {
global $wpdb;

	if ( $move > 0 && ! wppa_have_access( $move ) ) {
		wppa_error_message( sprintf( __( 'Unable to move photos to album %s. Album not deleted.', 'wp-photo-album-plus' ), $move ) );
		return false;
	}

	if ( ! wppa_user_is_admin() && ! wppa_get_album_item( $id, 'owner' ) == wppa_get_user() ) {
		wppa_error_message( sprintf( __( 'You do not have sufficient rights to delete album %s. Album not deleted.', 'wp-photo-album-plus' ), $id ) );
		return false;
	}

	if ( $move == '-9' ) {
		$move = - ( $id + '9' );
	}

	// Photos in the album
	$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE album = %s", $id ), ARRAY_A );

	if ( is_array( $photos ) ) {
		foreach ( $photos as $photo ) {
			wppa_update_photo( $photo['id'], ['album' => $move] );

			// Move to trash?
			if ( $move > '0' ) {
				wppa_move_source( $photo['filename'], $photo['album'], $move );
			}
			if ( wppa_is_time_up() ) {
				wppa_error_message( 'Time is out. Please redo this operation' );
				wppa_invalidate_treecounts( $move );
				return;
			}

		}
		if ( $move > '0' ) {
			wppa_invalidate_treecounts( $move );
		}
	}

	// First flush treecounts, otherwise we do not know the parent if any
	wppa_invalidate_treecounts( $id );

	// Now delete the album
	wppa_del_row( WPPA_ALBUMS, 'id', $id );
	wppa_index_update( 'album', $id );
	wppa_clear_catlist();
	wppa_clear_cache( array( 'album' => $id ) );
	wppa_childlist_remove( $id );
	wppa_cache_album('invalidate');

	$msg = __( 'Album deleted.' , 'wp-photo-album-plus' );
	if ( wppa( 'ajax' ) ) {
		wppa_echo( $msg );
	}
	else {
		wppa_update_message( $msg );
	}
}

// select main photo
function wppa_main_photo($cur, $covertype) {
global $wpdb;

	$output = '';
    $a_id = wppa_get( 'edit-id' );
	$photos = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->wppa_photos WHERE album = %s " . wppa_get_photo_order( $a_id ) . " LIMIT 1000", $a_id ), ARRAY_A );

	$cur_in_album = ( $cur < '1' ) || ( wppa_get_photo_item( $cur, 'album' ) == $a_id );

	// Find default
	$setting = wppa_opt( 'main_photo' );
	$dflt = __( 'Unknown', 'wp-photo-album-plus' );
	$opts = array(	__( '--- random ---', 'wp-photo-album-plus' ),
					__( '--- random featured ---', 'wp-photo-album-plus' ),
					__( '--- most recent added ---', 'wp-photo-album-plus' ),
					__( '--- random from (sub-)sub albums ---', 'wp-photo-album-plus' ),
					__( '--- most recent from (sub-)sub albums ---', 'wp-photo-album-plus' ),
					__( '--- according to albums photo sequence ---', 'wp-photo-album-plus' ),
					);
	$vals = array( '-9', '-1', '-2', '-3', '-4', '-5' );
	foreach( array_keys( $vals ) as $i ) {
		if ( $vals[$i] == $setting ) $dflt = $opts[$i];
	}

	$title = sprintf( __( 'The default is set in %s and is currently %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'misc', 1, 3 ), $dflt );

	$output .= '
	<select
		style="max-width:200px;"
		name="wppa-main" onchange="wppaAjaxUpdateAlbum('.$a_id.', \'main_photo\', this)"
		title="' . esc_attr( $title ) . '"
		>';

		if ( ! $cur_in_album ) {
			$output .= '
			<option value="' . $cur . '" >' .
				wppa_get_photo_item( $cur, 'name' ) . ' (' . wppa_get_album_item( wppa_get_photo_item( $cur, 'album' ), 'name' ) . ')' . '
			</option>
			';
		}

		$output .= '
		<option value="0"' . ( $cur == '0' ? ' selected' : '' ) . '>' .
			__( '--- default ---', 'wp-photo-album-plus' ) . '
		</option>';

		foreach( array_keys( $opts ) as $key ) {
			$output .= '
			<option value="' . $vals[$key] . '"' . ( $cur == $vals[$key] ? ' selected' : '' ) . '>' . $opts[$key] . '</option>';
		}

		if ( ! empty($photos) ) foreach($photos as $photo) {
			if ($cur == $photo['id']) {
				$selected = ' selected';
			}
			else {
				$selected = '';
			}
			$name = __(stripslashes($photo['name']), 'wp-photo-album-plus' );
			if ( strlen($name) > 45 ) $name = substr($name, 0, 45).'...';
			if ( ! $name ) $name = __( 'Nameless, filename = ', 'wp-photo-album-plus' ).$photo['filename'];
			$output .= '<option value="'.$photo['id'].'"'.$selected.'>'.$name.'</option>';
		}

	$output .= '
	</select>';

	return $output;
}

// Edit (sub)album sequence
function wppa_album_sequence( $parent ) {
global $wpdb;

	wppa_add_local_js( 'wppa_album_sequence' );

	// Get the albums sort order column and desc flag
	$albumorder_col	= wppa_get_album_order_column( $parent );
	$is_descending = wppa_is_album_order_desc( $parent );

	// If random...
	if ( $albumorder_col == 'random' ) {

		$query  = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %s ORDER BY RAND(%d)";

		$albums = $wpdb->get_results( $wpdb->prepare( $query, $parent, wppa_get_randseed() ), ARRAY_A );
	}

	// Not random, Decending?
	else if ( $is_descending ) {

		switch ( $albumorder_col ) {

			case 'a_order':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY a_order DESC";
				break;
			case 'name':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY name DESC";
				break;
			case 'timestamp':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY timestamp DESC";
				break;
			default:
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY id DESC";

		}

		$albums = $wpdb->get_results( $wpdb->prepare( $query, $parent ), ARRAY_A );
	}

	// Not descending
	else {

		switch ( $albumorder_col ) {

			case 'a_order':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY a_order";
				break;
			case 'name':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY name";
				break;
			case 'timestamp':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY timestamp";
				break;
			default:
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY id";

		}

		$albums = $wpdb->get_results( $wpdb->prepare( $query, $parent ), ARRAY_A );
	}

	// Anything to do here ?
	if ( empty ( $albums ) ) {
		return;
	}

	// Check my access rights
	foreach ( $albums as $album ) {
		if ( ! wppa_have_access( $album['id'] ) ) {
			return;
		}
	}

	// Sub album order
	if ( $parent ) {

		$sel = ' selected';
		$opts = array(
			__( '--- default ---', 'wp-photo-album-plus' ),
			__( 'Random', 'wp-photo-album-plus' ),
			__( 'Sequence #', 'wp-photo-album-plus' ),
			__( 'Sequence # descending', 'wp-photo-album-plus' ),
			__( 'Name', 'wp-photo-album-plus' ),
			__( 'Name descending', 'wp-photo-album-plus' ),
			__( 'Timestamp', 'wp-photo-album-plus' ),
			__( 'Timestamp descending', 'wp-photo-album-plus' ),
			);
		$vals = array( '0', '3', '1', '-1', '2', '-2', '5', '-5' );

		$df = wppa_opt( 'list_albums_by' );
		if ( $df == '0' ) $dflt = __( 'not specified', 'wp-photo-album-plus' );
		else foreach( array_keys( $vals ) as $key ) {
			if ( $df == $vals[$key] ) {
				$dflt = $opts[$key];
			}
		}

		$title = sprintf( __( 'The default is set in %s and is currently set to %s', 'wp-photo-album-plus' ), wppa_setting_path( 'b', 'misc', 1, 1 ), $dflt );
		$suba_order_by = wppa_get_album_item( $parent, 'suba_order_by' );
		$result = '
		<div style="margin-left:6px">
			<label
				for="subalbumorder">' .
				__( 'Sub album sequence method', 'wp-photo-album-plus' ) . '
			</label><br>
			<select
				id="subalbumorder"
				style="max-width:200px;"
				onchange="
					wppaAjaxUpdateAlbum( ' . $parent . ', \'suba_order_by\', this );
					var ord=Math.abs(jQuery(this).val());
					var dft=Math.abs(' . wppa_opt('list_albums_by') . ');
					if (ord == 1 || (ord == 0 && dft == 1)) {
						jQuery(\'#wppa-album-sequence\').show();
					}
					else {
						jQuery(\'#wppa-album-sequence\').hide();
					}"
				title="' . esc_attr( $title ) . '"
				>';
				foreach ( array_keys( $opts ) as $i ) {
					$result .= '<option value="' . esc_attr( $vals[$i] ) . '" ' . ( $suba_order_by == $vals[$i] ? $sel : '' ) . ' >' . $opts[$i] . '</option>';
				}
			$result .= '
			</select>
		</div>';
		wppa_echo( $result );
	}

	// Check album order
	if ( $albumorder_col != 'a_order' ) {
		if ( $parent == '0') {
			$result = '
			<br>' .
			esc_html__( 'You can edit top-level album sequence number here when you set the album sequence method to "Sequence #" or "Sequence # descending"', 'wp-photo-album-plus' );
			if ( current_user_can( 'wppa_settings' ) ) {
				$result .= wppa_see_also( 'misc', 1, 1 );
			}
		}
		else {
			$result = '
			<br>' .
			esc_html__( 'You can edit sub album sequence number here when you set the album sequence to "Sequence #" or "Sequence # descending" in the "Sub album sequence method" selection box above.', 'wp-photo-album-plus' );
		}
		$result .= '<br>';

		wppa_echo( $result );
//		return;
	}

	$result = '
	<div
		style="' . ( $albumorder_col != 'a_order' ? 'display:none' : '' ) . '"
		id="wppa-album-sequence"
		>
	<h2 style="margin:1em">' .
		__( 'Manage album sequence', 'wp-photo-album-plus' ) . '
		-
		<small>
			<i>' .
				__( 'Change album sequence by drag and drop, or use the up/down arrows.', 'wp-photo-album-plus' ) . '
			</i>' .
			__( 'Do not leave this page unless the bar is entirely green.', 'wp-photo-album-plus' ) . '
		</small>
	</h2>

	<table>
		<thead>
			<tr>
				<td>' .
					__( 'Color', 'wp-photo-album-plus' ) . '
				</td>
				<td>' .
					__( 'Meaning', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<div style="background-color:green;height:12px"></div>
				</td>
				<td>' .
					__( 'Up to date', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
			<tr>
				<td>
					<div style="background-color:yellow;height:12px"></div>
				</td>
				<td>' .
					__( 'Updating', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
			<tr>
				<td>
					<div style="background-color:orange;height:12px"></div>
				</td>
				<td>' .
					__( 'Needs update', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
			<tr>
				<td>
					<div style="background-color:red;height:12px"></div>
				</td>
				<td>' .
					__( 'Error', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
		</tbody>
	</table>
	';
	wppa_echo( $result );

	if ( $is_descending && $albumorder_col == 'a_order' ) {
		wppa_add_inline_script( 'wppa-admin', 'var wppaAlbSeqnoDesc=true;', false );
	}
	else {
		wppa_add_inline_script( 'wppa-admin', 'var wppaAlbSeqnoDesc=false;', false );
	}

	$result = '
	<br>
	<div id="wppa-progbar" style="width:100%;height:12px">';

		$c = count( $albums );
		wppa_add_inline_script( 'wppa-admin', 'var wppaAlbumCount='.$c.';', false );

		$l = 100 / $c;
		$i = 0;
		while( $i < $c ) {
			$result .= '
			<div
				id="wppa-pb-' . $i . '"
				style="display:inline;float:left;background-color:green;height:12px;width:' . $l . '%"
				>
			</div>';
			$i++;
		}
	$result .= '
	</div>

	<br>

	<div class="widefat" style="max-width:600px">
		<div id="sortable-albums">';

		foreach ( $albums as $album ) {
			$cover_photo_id = wppa_get_coverphoto_id( $album['id'] );
			$result .= '
			<div
				id="albumitem-' . $album['id'] . '"
				class="ui-state-default-albums"
				style="background-color:#eeeeee;cursor:move;"
				>
				<div
					style="height:100%;width:25%;float:left;text-align:center;overflow:hidden"
					>';
					if ( wppa_is_video( $cover_photo_id ) ) {
						$result .=
						wppa_get_video_html( array( 'id' => $cover_photo_id,
													'height' => '50',
													'margin_top' => '5',
													'margin_bottom' => '5',
													'controls' => false,
													) );
					}
					else {
						$result .= '
						<img
							class="wppa-cover-image"
							src="' . wppa_get_thumb_url( wppa_get_coverphoto_id( $album['id'] ) ) . '"
							style="max-height:50px; margin: 5px;"
						/>';
					}
					$albid = strval( intval( $album['id'] ) );
					$result .= '
					</div>
					<div style="height:100%;width:40%;float:left;font-size:12px;overflow:hidden">
						<b>' . htmlspecialchars( wppa_get_album_name( $albid ) ) . '</b>
						<br>' .
						wppa_get_album_desc( $albid ) . '
					</div>
					<div style="float:right;width:10%">
						<table>
							<tr>
								<td>
									<img
										src="' . wppa_get_imgdir( 'Up-3.svg' ) . '"
										title="' . esc_attr( __( 'To top', 'wp-photo-album-plus' ) ) . '"
										style="cursor:pointer;width:1em"
										onclick="
											jQuery( \'#albumitem-' . $albid . '\' ).parent().prepend(jQuery( \'#albumitem-' . $albid . '\' ));
											wppaDoRenumber();"
									/>
								</td>
							</tr>
							<tr>
								<td>
									<img
										src="' . wppa_get_imgdir( 'Up-2.svg' ) . '"
										title="' . esc_attr( __( 'One up', 'wp-photo-album-plus' ) ) . '"
										style="cursor:pointer;width:1em"
										onclick="
											jQuery( \'#albumitem-' . $albid . '\' ).prev().before(jQuery( \'#albumitem-' . $albid . '\' ));
											wppaDoRenumber();"
									/>
								</td>
							</tr>
							<tr>
								<td>
									<img
										src="' . wppa_get_imgdir( 'Down-2.svg' ) . '"
										title="' . esc_attr( __( 'One down', 'wp-photo-album-plus' ) ) . '"
										style="cursor:pointer;width:1em"
										onclick="
											jQuery( \'#albumitem-' . $albid . '\' ).next().after(jQuery( \'#albumitem-' . $albid . '\' ));
											wppaDoRenumber();"
									/>
								</td>
							</tr>
							<tr>
								<td>
									<img
										src="' . esc_url( wppa_get_imgdir( 'Down-3.svg' ) ) . '"
										title="' . esc_attr( __( 'To bottom', 'wp-photo-album-plus' ) ) . '"
										style="cursor:pointer;width:1em"
										onclick="
											jQuery( \'#albumitem-' . $albid . '\' ).parent().append(jQuery( \'#albumitem-' . $albid . '\' ));
											wppaDoRenumber();"
									/>
								</td>
							</tr>
						</table>
					</div>
					<div style="float:right; width:25%">
						<span> ' . __( 'Id' , 'wp-photo-album-plus' ) . ' ' . $albid . '</span>
						<span> - ' . __( 'Ord' , 'wp-photo-album-plus' ) . '</span>
						<span id="wppa-album-seqno-' . $albid . '" > ' . $album['a_order'] . '</span>
						<br>
						<div style="display:inline-block;width:50%">
							<a href="' . wppa_ea_url( $albid ) . '" style="position:absolute;bottom:0">' . __( 'Edit', 'wp-photo-album-plus' ) . '</a>
						</div>
						<div style="display:inline-block;width:50%">
							<a href="' . wppa_ea_url( $albid ) . '&bulk=1" style="position:absolute;bottom:0">' . __( 'Bulk', 'wp-photo-album-plus' ) . '</a>
						</div>
					</div>
					<input type="hidden" id="album-nonce-' . $albid . '" value="' . wp_create_nonce( 'wppa-nonce_' . $albid ) . '" />
					<input type="hidden" class="wppa-sort-item-albums" value="' . $albid . '" />
					<input type="hidden" class="wppa-sort-seqn-albums" id="wppa-sort-seqn-albums-' . $albid . '" value="' . $album['a_order'] . '" />
				</div>';
			}
		$result .= '
		</div>
		<div style="clear:both;"></div>
	</div>
	</div>';

	wppa_echo( $result );
}

// Search admin menu item
function _wppa_search() {

	// Rememeber where we came from
	wppa_update_option( 'wppa_search_page', 'wppa_search' );

	$result = '
	<div class="wrap" >
		<h1 class="wp-heading-inline">' .
			get_admin_page_title() . '
		</h1>
		<div style="clear:both">&nbsp;</div>
		<h3>' .
			esc_html__( 'Search for photos to edit', 'wp-photo-album-plus' ) . '
		</h3>' .
		esc_html__( 'Enter search words seperated by commas. Photos will meet all search words by their names, descriptions, translated keywords and/or tags.', 'wp-photo-album-plus' ) . '
		<br>
		<table class="wppa-table widefat wppa-setting-table" style="margin-top:12px">
			<thead>
			</thead>
			<colgroup>
				<col style="width:0px; color:transparent">
				<col style="width:5%" >
				<col>
				<col>
				<col>
				<col>
				<col style="width:0px">
				<col style="width:5%">
				<col style="width:5%">
				<col style="width:5%">
				<col style="width:0px">
				<col style="width:0px">
				<col style="width:0px">
				<col style="width:0px">
				<col style="width:0px">
				<col style="width:0px">
			</colgroup>
			<tbody>' .
				wppa_search_edit( false ) . '
			</tbody>
		</table>
	</div>';

	wppa_echo( $result );
}

