<?php
/* wppa-import.php
* Package: wp-photo-album-plus
*
* Contains all the import pages and functions
* Version 8.3.05.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

require_once 'wppa-import-ajax.php';
require_once 'wppa-import-utils.php';

// import images admin page
function _wppa_page_import() {
global $wppa_revno;
global $wpdb;
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;
global $wppa_session;
global $wppa_import_errors;

	// Security check
	if ( wppa_get( 'import-submit' ) ) {
		if ( ! check_admin_referer( '$wppa_nonce', WPPA_NONCE ) ) {
			wppa_exit();
		}
	}

	// Init
	wppa_add_local_js( '_wppa_page_import' );

	// Get this users current source directory setting
	$user 			= wppa_get_user();
	$source_type 	= wppa_get_option( 'wppa_import_source_type_' . $user, 'local' );		// Local, Remote or from realmedia plugin
	if ( $source_type == 'remote' ) wppa( 'is_remote', true ); 								// Make remote globally known
	$source 		= wppa_get_option( 'wppa_import_source_' . $user, WPPA_DEPOT_PATH ); 	// Current source path
	$ngg_opts 		= wppa_get_option( 'ngg_options', false ); 								// Needed for import fron ngg galleries

	// Update watermark settings for the user if new values supplied
	if ( wppa_switch( 'watermark_on' ) && ( wppa_switch( 'watermark_user' ) || current_user_can( 'wppa_settings' ) ) ) {

		// File
		if ( wppa_get( 'watermark-file' ) ) {

			// Sanitize input
			$watermark_file = wppa_get( 'watermark-file' );
			if ( stripos( $watermark_file, '.png' ) !== false ) {
				$watermark_file = sanitize_file_name( $watermark_file );
			}
			else {
				if ( ! in_array( $watermark_file, array( '--- none ---', '---name---', '---filename---', '---description---', '---predef---' ) ) ) {
					$watermark_file = 'nil';
				}
			}

			// Update setting
			wppa_update_option( 'wppa_watermark_file_' . $user, $watermark_file );
		}

		// Position
		if ( wppa_get( 'watermark-pos' ) ) {

			// Sanitize input
			$watermark_pos = wppa_get( 'watermark-pos' );
			if ( ! in_array( $watermark_pos, array( 'toplft', 'topcen', 'toprht', 'cenlft', 'cencen', 'cenrht', 'botlft', 'botcen', 'botrht' ) ) ) {
				$watermark_pos = 'nil';
			}

			// Update setting
			wppa_update_option( 'wppa_watermark_pos_' . $user, $watermark_pos );
		}
	}


	// Link from album admin (Import to this album) overrules last album
	if ( wppa_get( 'set-album' ) ) {
		wppa_update_option( 'wppa-photo-album-import-' . wppa_get_user(), wppa_get( 'set-album' ) );
		wppa_update_option( 'wppa-video-album-import-' . wppa_get_user(), wppa_get( 'set-album' ) );
		wppa_update_option( 'wppa-audio-album-import-' . wppa_get_user(), wppa_get( 'set-album' ) );
		wppa_update_option( 'wppa-document-album-import-' . wppa_get_user(), wppa_get( 'set-album' ) );
	}

	// Verify last albums still exist
	if ( ! wppa_album_exists( wppa_get_option( 'wppa-photo-album-import-' . wppa_get_user(), '0' ) ) ) {
		wppa_update_option( 'wppa-photo-album-import-' . wppa_get_user(), '0' );
	}
	if ( ! wppa_album_exists( wppa_get_option( 'wppa-video-album-import-' . wppa_get_user(), '0' ) ) ) {
		wppa_update_option( 'wppa-video-album-import-' . wppa_get_user(), '0' );
	}
	if ( ! wppa_album_exists( wppa_get_option( 'wppa-audio-album-import-' . wppa_get_user(), '0' ) ) ) {
		wppa_update_option( 'wppa-audio-album-import-' . wppa_get_user(), '0' );
	}
	if ( ! wppa_album_exists( wppa_get_option( 'wppa-document-album-import-' . wppa_get_user(), '0' ) ) ) {
		wppa_update_option( 'wppa-document-album-import-' . wppa_get_user(), '0' );
	}

	// Set local / remote
	if ( wppa_get( 'local-remote' ) && in_array( wppa_get( 'local-remote' ), array( 'local', 'remote' ) ) ) {
		if ( ! check_admin_referer( '$wppa_nonce', WPPA_NONCE ) ) {
			wp_die( __( 'Security check failure', 'wp-photo-album-plus' ) );
		}
		wppa_update_option( 'wppa_import_source_type_' . $user, wppa_get( 'local-remote' ) );
	}

	// Set import source dir ( when local )
	if ( wppa_get( 'import-set-source-dir', '', 'text' ) && wppa_get( 'source', '', 'text' ) != 'realmedialibrary' && wppa_is_dir( wppa_get( 'source', '', 'text' ) ) ) {
		if ( ! check_admin_referer( '$wppa_nonce', WPPA_NONCE ) ) {
			wp_die( __( 'Security check failure', 'wp-photo-album-plus' ) );
		}
		if ( wppa_get( 'source', '', 'text' ) ) {
			wppa_update_option( 'wppa_import_source_'.$user, wppa_get( 'source', '', 'text' ) );
		}
	}

	// Set import source url ( when remote )
	if ( wppa_get( 'import-set-source-url' ) ) {
		if ( ! check_admin_referer( '$wppa_nonce', WPPA_NONCE ) ) {
			wp_die( __( 'Security check failure', 'wp-photo-album-plus' ) );
		}
		if ( wppa_get( 'source-remote' ) ) {
			wppa_update_option( 'wppa_import_source_url_' . $user, esc_url( wppa_get( 'source-remote' ) ) );
			wppa_update_option( 'wppa_import_source_url_found_' . $user, false );
			wppa_update_option( 'wppa_import_remote_max_' . $user, wppa_get( 'import-remote-max' ) );
			$wppa_session['is_wppa_tree'] = false;
		}
	}

	// Open the Form
	wppa_echo( '<div class="wrap">' );

		// The page title line
		$header = '
		<h1>' .
			get_admin_page_title();
			if ( current_user_can( 'wppa_settings' ) ) {
				$header .= '
				<span style="font-size:13px">' .
					wppa_see_also( 'admin', '3' ) . '
				</span>';
			}
		$header .= '
		</h1>';
		wppa_echo( $header );

		// See if our depot dir has been created
		if ( ! wppa_is_dir( WPPA_DEPOT_PATH ) ) {
			wppa_error_message( sprintf(
			__( 'Your depot directory <b>%s</b> could not be created.<br>Please create it yourself using a ftp program and make sure the filesystem rights are set to 0755',
			'wp-photo-album-plus' ), WPPA_DEPOT_PATH ) );
			wppa_echo( '</div>' );
			wppa_exit();
		}

		// Get this users current source type setting ( local/remote )
		$source_type = wppa_get_option( 'wppa_import_source_type_' . $user, 'local' );
		if ( $source_type == 'realmedia' ) $source_type = 'local'; // for backward compatibility

		// Local. Find data we will going to need
		if ( $source_type == 'local' ) {

			// Get current local dir setting
			$source      = wppa_get_option( 'wppa_import_source_' . $user, WPPA_DEPOT_PATH );
			if ( ! $source || ! wppa_is_dir( $source ) ) {
				$source = WPPA_DEPOT_PATH;
				wppa_update_option( 'wppa_import_source_' . $user, WPPA_DEPOT_PATH );
			}

			// See if the current source is the 'home' directory
			$is_depot 	= ( $source == WPPA_DEPOT_PATH );

			// See if the current source is a subdir of my depot
			$is_sub_depot = ( substr( $source, 0, strlen( WPPA_DEPOT_PATH ) ) == WPPA_DEPOT_PATH );

			// Sanitize system, removes illegal files
			if ( $is_sub_depot ) {
				wppa_sanitize_files();
			}

			// See what's in there, dirs and .csv's only in depot
			$files 			= wppa_get_import_files();
			$zipcount 		= wppa_get_zipcount( $files );
			$albumcount 	= wppa_get_albumcount( $files );
			$photocount 	= wppa_get_photocount( $files );
			$videocount 	= wppa_get_video_count( $files );
			$audiocount 	= wppa_get_audio_count( $files );
			$dircount		= $is_depot ? wppa_get_dircount( $files ) : '0';
			$csvcount 		= $is_depot ? wppa_get_csvcount( $files ) : '0';
			$documentcount 	= wppa_get_documentcount( $files );

			if ( $ngg_opts ) {
				$is_ngg = strpos( $source, $ngg_opts['gallerypath'] ) !== false;	// this is false for the ngg root !!
			}
			else $is_ngg = false;

			// We do realmedia only when in depot
			if ( $is_depot && wppa_has_realmedia() ) {

				// See what's in there
				$rm_itemcount 		= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_realmedialibrary_posts" );//wppa_get_import_files();
				$rm_itemsdone 		= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE rml_id <> ''" );

				$rm_albumcount 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_realmedialibrary" );//wppa_get_albumcount( $files );
				$rm_albumsdone 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE rml_id <> ''" );
			}
		}

		// Remote. Find data we will going to need, photos only (to be extended)
		if ( $source_type == 'remote' ) {
			wppa( 'is_remote', true );
			$source     	= wppa_get_option( 'wppa_import_source_url_' . $user, 'http://' );
			$source_path 	= $source;
			$source_url 	= $source;
			$is_depot 		= false;
			$is_sub_depot 	= false;
			$files 			= wppa_get_import_files();
			$zipcount 		= '0';
			$albumcount 	= '0';
			$photocount 	= $files ? count( $files ) : '0';
			$videocount 	= '0';
			$audiocount 	= '0';
			$dircount		= '0';
			$csvcount 		= '0';
			$documentcount 	= '0';
			$is_ngg 		= false;
			$remote_max 	= wppa_get_option( 'wppa_import_remote_max_'.$user, '10' );
		}

	// The form for selecting local / remote and path to start from
	wppa_echo( '
	<form
		id="wppa-import-form"
		action="' . get_admin_url() . 'admin.php?page=wppa_import_photos"
		method="post"
		>' );

		// Admin and superuser can change import source, other users only if change source not is restricted
		if ( wppa_user_is_admin() || ! wppa_switch( 'chgsrc_is_restricted' ) ) {

			// Local / Remote
			wppa_import_header( __( 'Import from', 'wp-photo-album-plus' ) );
				wp_nonce_field( '$wppa_nonce', WPPA_NONCE );

				wppa_echo( '
				<div class="left">
					<label>' .
						__( 'Select Local or Remote', 'wp-photo-album-plus' ) . '
					</label>
					<br>
					<select
						name="wppa-local-remote"
						onchange="jQuery(\'#rem-rem-0\').show();jQuery(\'#wppa-import-set-source\').trigger(\'click\');"
						>
						<option value="local" ' . ( $source_type == 'local' ? 'selected' : '' ) . '>' . __( 'Local', 'wp-photo-album-plus' ) . '</option>
						<option value="remote" ' . ( $source_type == 'remote' ? 'selected' : '' ) . '>' . __( 'Remote' ,'wp-photo-album-plus' ) . '</option>
					</select>
					<span id="rem-rem-0" style="display:none">' .
						__( 'Working, please wait...', 'wp-photo-album-plus' ) . '
					</span>
					<input
						id="wppa-import-set-source"
						type="submit"
						class="button button-secundary"
						name="wppa-import-set-source"
						style="display:none;"
					/>
				</div>' );

				// Local: dir
				if ( $source_type == 'local' ) {
					wppa_update_option( 'wppa_import_root', ABSPATH . basename( content_url() ) ); // Provider may have changed disk
					wppa_echo( '
					<div class="left">
						<label>' .
							__( 'From local folder', 'wp-photo-album-plus' ) . '
						</label>
						<br>
						<select
							name="wppa-source"
							onchange="jQuery(\'#rem-rem\').show();jQuery(\'#wppa-import-set-source-dir\').trigger(\'click\');"
							>' .
							wppa_abs_walktree( wppa_opt( 'import_root' ), $source ) . '
						</select>
						<input
							id="wppa-import-set-source-dir"
							type="submit"
							class="button button-secundary"
							name="wppa-import-set-source-dir"
							style="display:none;"
						/>
						<span id="rem-rem" style="display:none">' .
							__( 'Working, please wait...', 'wp-photo-album-plus' ) . '
						</span>
					</div>' );
				}

				// Remote: url
				elseif ( $source_type == 'remote' ) {

					wppa_echo( '
					<div class="left">
						<label>' .
							__( 'From url', 'wp-photo-album-plus' ) . '
						</label>
						<br>
						<input
							type="text"
							style="width:500px"
							name="wppa-source-remote"
							value="' . $source . '"
							onchange="jQuery(\'#wppa-import-set-source-url\').trigger(\'click\');"
						/>
					</div>' );

					wppa_echo( '
					<div class="left">
						<label>' .
							__( 'Max:', 'wp-photo-album-plus' ) . '
						</label>
						<br>
						<input
							type="text"
							style="width:50px;"
							name="wppa-import-remote-max"
							value="' . $remote_max . '"
							onchange="jQuery(\'#wppa-import-set-source-url\').trigger(\'click\');"
						/>
						<input
							id="wppa-import-set-source-url"
							type="submit"
							onclick="jQuery( \'#rem-rem\' ).css( \'display\',\'inline\' ); return true;"
							class="button button-secundary"
							name="wppa-import-set-source-url"
							style="display:none;"
						/>
						<span id="rem-rem" style="display:none">' .
							__( 'Working, please wait...', 'wp-photo-album-plus' ) . '
						</span>
					</div>' );

					wppa_echo( '<div style="clear:both">' . __( 'You can enter a full url to an image file like <i>http://mysite.com/wp-content/uploads/wppa/4711.jpg</i>', 'wp-photo-album-plus' ) . '</div>' );
				}

				else {
					wppa_echo( __( 'Unimplemented source type', 'wp-photo-album-plus' ) . ' ' . $source_type );
				}
			wppa_import_footer( '', 'short' );
		}
	wppa_echo( '</form>' );

	// check if albums exist or will be made before allowing upload
	if ( ! wppa_has_albums() && ! $albumcount && ! $dircount && ! $csvcount ) {
			$url = get_admin_url() . 'admin.php?page=wppa_admin_menu';
			wppa_echo( '
			<p>' .
				__( 'No albums exist. You must', 'wp-photo-album-plus' ) . '
				<a href="' . $url . '" >' .
					__( 'create one', 'wp-photo-album-plus' ) . '
				</a> ' .
				__( 'before you can import your photos.', 'wp-photo-album-plus' ) . '<br>' .
				__( 'Alternatively, you can upload album definition (.amf) or compreesed file(s) (.zip) with folders to convert to albums', 'wp-photo-album-plus' ) . '
			</p>' );
	}

	// Something to import?
	if ( $photocount || $albumcount || $zipcount || $dircount || $videocount || $audiocount || $csvcount || $documentcount || ( $is_depot && wppa_has_realmedia() ) ) {

		$idx = '0';

		// Open the form
		wppa_echo( '
		<div>' .
			wp_nonce_field( '$wppa_nonce', WPPA_NONCE, true, false ) );

			// Display the zips
			if ( PHP_VERSION_ID >= 50207 && $zipcount > '0' ) {
				wppa_import_header( __( 'Zip files', 'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d zipfile in the depot', 'There are %d zipfiles in the depot', $zipcount, 'wp-photo-album-plus' ), $zipcount ) );
				wppa_import_header_check( 'del-after-z', __( 'Delete after successful extraction.', 'wp-photo-album-plus' ) );
				wppa_import_header_check( 'del-after-fz', __( 'Delete after failed extraction.', 'wp-photo-album-plus' ) );
				wppa_import_table_start( 'zip', '4' );

					$ct = 0;
					foreach ( $files as $file ) {
						$ext = wppa_get_ext( $file );
						if ( $ext == 'zip' ) {
							wppa_import_item_check( $idx, 'zip', $file );
							wppa_import_fixcount( $ct, 4 ); 	// Writes </tr><tr> after 4 entries
						}
						$idx++;
					}

				wppa_import_footer( 'zip' ); 	// writes table end
			}

			// Dispay the albums ( .amf files )
			if ( $albumcount ) {
				wppa_import_header( __( 'Album definition files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d albumdefinition in the depot', 'There are %d albumdefinitions in the depot', $albumcount, 'wp-photo-album-plus' ), $albumcount ) );
				wppa_import_header_check( 'del-after-a', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ) );
				wppa_import_header_check( 'del-after-fa', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ) );
				wppa_import_table_start( 'amf', '4' );

					$ct = 0;
					foreach ( $files as $file ) {
						$ext = wppa_get_ext( $file );
						if ( $ext == 'amf' ) {
							wppa_import_item_check( $idx, 'amf', $file );
							wppa_import_fixcount( $ct, 4 );
						}
						$idx++;
					}

				wppa_import_footer( 'amf' );

			}

			// Display the videos
			if ( $videocount && wppa_switch( 'enable_video' ) ) {
				wppa_import_header( __( 'Video files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d video in the depot', 'There are %d videos in the depot', $videocount, 'wp-photo-album-plus' ), $videocount ) );
				wppa_import_header_check( 'del-after-v', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ) );
				wppa_import_header_check( 'del-after-fv', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ) );
				wppa_echo( '<small> ' . __( 'Files larger than 64MB will always be removed after successful import.', 'wp-photo-album-plus' ) . '</small>' );
				wppa_import_album_select( 'video' );
				wppa_import_table_start( 'video', '4' );

				$ct = 0;
				if ( is_array( $files ) ) foreach ( $files as $file ) {
					$ext = strtolower( substr( strrchr( $file, "." ), 1 ) );
					if ( in_array( strtolower($ext), $wppa_supported_video_extensions ) ) {
						wppa_import_item_check( $idx, 'video', $file );
						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'video' );
			}

			// Display the audios
			if ( $audiocount && wppa_switch( 'enable_audio' ) ) {
				wppa_import_header( __( 'Audio files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d audio in the depot', 'There are %d audios in the depot', $audiocount, 'wp-photo-album-plus' ), $audiocount ) );
				wppa_import_header_check( 'del-after-u', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ) );
				wppa_import_header_check( 'del-after-fu', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ) );
				wppa_import_album_select( 'audio' );
				wppa_import_table_start( 'audio', '4' );

				$ct = 0;
				if ( is_array( $files ) ) foreach ( $files as $file ) {
					$ext = strtolower( substr( strrchr( $file, "." ), 1 ) );
					if ( in_array( strtolower($ext), $wppa_supported_audio_extensions ) ) {
						wppa_import_item_check( $idx, 'audio', $file );
						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'audio' );
			}

			// Display the document files
			if ( $is_depot && $documentcount ) {
				wppa_import_header( __( 'Document files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d document file in the depot', 'There are %d document files in the depot', $documentcount, 'wp-photo-album-plus' ), $documentcount ) );
				wppa_import_header_check( 'del-after-d', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ) );
				wppa_import_header_check( 'del-after-fd', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ) );
				wppa_import_album_select( 'document' );
				wppa_import_table_start( 'pdf', '4' );

				$ct = 0;
				if ( is_array( $files ) ) foreach( $files as $file ) {
					if ( wppa_is_file( $file ) && strtolower( wppa_get_ext( $file ) ) == 'pdf' ) {
						wppa_import_item_check( $idx, 'pdf', $file );
						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'pdf' );
			}

			// Display the single photos
			if ( $photocount ) {
				if ( $source_type == 'local' ) {
					if ( $is_ngg ) { // Local ngg
						$label = sprintf( _n( 'There is %d photo in the ngg gallery', 'There are %d photos in the ngg gallery', $photocount, 'wp-photo-album-plus' ), $photocount );
					}
					else { // Local normal
						$label = sprintf( _n( 'There is %d photo in the depot', 'There are %d photos in the depot', $photocount, 'wp-photo-album-plus' ), $photocount );
					}
				}
				else { // Remote
					$label = sprintf( _n( 'There is %d possible photo found remote', 'There are %d possible photos found remote', $photocount, 'wp-photo-album-plus' ), $photocount );
				}

				wppa_import_header( __( 'Photo files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( $label );
				if ( $is_sub_depot ) {
					wppa_import_header_check( 'del-after-p', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ) );
					wppa_import_header_check( 'del-after-fp', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ) );
				}
				if ( $is_ngg ) {
					$src = basename( $source );
					wppa_import_header_check( 'use-backup', __( 'Use backup if available', 'wp-photo-album-plus' ) );
					wppa_import_header_check( 'cre-album', __( 'Import into album', 'wp-photo-album-plus' ) . ' ' . $src, ' value="' . esc_attr( $src ) . '"' );
				}
				wppa_import_header_check( 'wppa-update', __( 'Update existing photos', 'wp-photo-album-plus' ) );
				if ( wppa_switch( 'void_dups' ) ) {
					wppa_echo( '<input type="hidden" id="wppa-nodups" name="wppa-nodups" value="1" />' );
				}
				else {
					wppa_import_header_check( 'wppa-nodups', __( 'Do not create duplicates', 'wp-photo-album-plus' ) );
				}
				if ( wppa_switch( 'import_preview' ) ) {
					wppa_import_header_check( 'wppa-zoom', __( 'Zoom previews', 'wp-photo-album-plus' ) );
				}
				wppa_import_album_select( 'photo', __( 'Photos that have (<em>name</em>)[<em>album</em>] will be imported by that <em>name</em> in that <em>album</em>.', 'wp-photo-album-plus' ) );

				// Watermark
				if ( wppa_switch( 'watermark_on' ) && ( wppa_switch( 'watermark_user' ) || current_user_can( 'wppa_settings' ) ) ) {
					wppa_echo( '
					<div class="left">
						<label>' .
							__( 'Apply watermark file:', 'wp-photo-album-plus' ) . '
						</label>
						<br>
						<select name="wppa-watermark-file" id="wppa-watermark-file" >' .
							wppa_watermark_file_select( 'user' ) . '
						</select>
					</div>
					<div class="left">
						<label>' .
							__( 'Position:', 'wp-photo-album-plus' ) . '
						</label>
						<br>
						<select name="wppa-watermark-pos" id="wppa-watermark-pos" >' .
							wppa_watermark_pos_select( 'user' ) . '
						</select>
					</div>' );
				}

				// Display the Photo list
				wppa_import_table_start( 'pho', '4' );

				$ct = 0;
				if ( is_array( $files ) ) foreach ( $files as $file ) {
					$ext = wppa_get_ext( $file );
					$meta =	wppa_strip_ext( $file ).'.PMF';
					if ( ! wppa_is_file( $meta ) ) {
						$meta =	wppa_strip_ext( $file ).'.pmf';
					}
					if ( ! wppa_is_file( $meta ) ) {
						$meta = false;
					}
					if ( in_array( strtolower( $ext ), $wppa_supported_photo_extensions ) ) {
						wppa_echo( '
						<td id="td-file-' . $idx . '" >
							<input
								type="checkbox"
								id="file-' . $idx . '"
								name="file-' . $idx . '"
								title="' . esc_attr( $file ) . '"
								class="wppa-import-item wppa-pho"
								data-type="pho"
							/>
							<span id="label-file-' . $idx . '" ></span>
							<span id="name-file-' . $idx . '" >&nbsp;' );

								if ( wppa( 'is_wppa_tree' ) ) {
									$t = explode( 'uploads/wppa/', $file );
									wppa_echo( wppa_sanitize_file_name( basename( str_replace( '/', '', $t[1] ) ) ) );
								}
								else {
									wppa_echo( wppa_sanitize_file_name( basename( $file ) ) );
								}

								if ( $meta ) {
									wppa_echo( '
									&nbsp;' .
									stripslashes( wppa_get_meta_name( $meta, '( ' ) ) .
									stripslashes( wppa_get_meta_album( $meta, '[' ) ) );
								}
							wppa_echo( '</span>' );

							if ( wppa_switch( 'import_preview' ) ) {
								if ( wppa( 'is_remote' ) ) {
									if ( strpos( $file, '//res.cloudinary.com/' ) !== false ) {
										$img_url = dirname( $file ) . '/h_144/' . basename( $file );
									}
									else {
										$img_url = $file;
									}
								}
								else {
									$img_url = str_replace( ABSPATH, home_url().'/', $file );
									if ( is_ssl() ) {
										$img_url = str_replace( 'http://', 'https://', $img_url );
									}
								}
								wppa_echo( '
								<img src="' . esc_url( $img_url ) . '"
									alt="' . esc_attr( _x( 'n.a.', 'not available', 'wp-photo-album-plus' ) ) . '"
									style="max-height:48px;"
									onmouseover="if (jQuery(\'#wppa-zoom\').prop(\'checked\')) jQuery(this).css(\'max-height\', \'144px\')"
									onmouseout="if (jQuery(\'#wppa-zoom\').prop(\'checked\')) jQuery(this).css(\'max-height\', \'48px\')"
								/>' );
							}

						wppa_echo( '</td>' );

						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'pho' );
			}

			// Display the realmedia albums
			if ( $is_depot && wppa_has_realmedia() ) {
				$rml_albums 	= $wpdb->get_results( "SELECT * FROM $wpdb->wppa_realmedialibrary", ARRAY_A );
				$rm_albumcount 	= is_array( $rml_albums ) ? count( $rml_albums ) : 0;
				if ( $rm_albumcount ) {
					wppa_import_header( __( 'RealMedia albums', 'wp-photo-album-plus' ) );
					wppa_import_header_subtitle( sprintf( _n( 'There is %d RealMedia album', 'There are %d RealMedia albums', $rm_albumcount, 'wp-photo-album-plus' ), $rm_albumcount ) );
					wppa_import_table_start( 'rma', '4' );

					$ct = 0;
					foreach( $rml_albums as $rml_album ) {
						$rml_album_id 	= $rml_album['id'];
						$wppa_album_id 	= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums WHERE rml_id = $rml_album_id LIMIT 1" );
						$rml_album_name = $rml_album['name'];
						wppa_import_item_check( $idx, 'rma', $rml_album_name, ( $wppa_album_id ? true : false ) ); 	// disable when already exists in wppa
						wppa_import_fixcount( $ct, 4 );
						$idx++;
					}

					wppa_import_footer( 'rma' );
				}
			}

			// Display the realmedia items
			if ( $is_depot && wppa_has_realmedia() ) {
				$rml_posts 		= $wpdb->get_results( "SELECT * FROM $wpdb->wppa_realmedialibrary_posts", ARRAY_A );
				$rm_postcount 	= is_array( $rml_posts ) ? count( $rml_posts ) : 0;
				if ( $rm_postcount ) {
					wppa_import_header( __( 'RealMedia items', 'wp-photo-album-plus' ) );
					wppa_import_header_subtitle( sprintf( _n( 'There is %d RealMedia items', 'There are %d RealMedia items in the depot', $rm_postcount, 'wp-photo-album-plus' ), $rm_postcount ) );
					wppa_import_table_start( 'rmi', '4' );

					$ct = 0;
					foreach( $rml_posts as $rml_post ) {
						$attachment 	= $rml_post['attachment'];
						$rml_album 		= $rml_post['fid'];
						$rml_item_name 	= $wpdb->get_var( "SELECT post_title FROM $wpdb->posts WHERE ID = $attachment" );
						$rml_album_name = $wpdb->get_var( "SELECT name FROM $wpdb->wppa_realmedialibrary WHERE id = $rml_album" );
						$dis 			= ! $rml_album_name;
						wppa_import_item_check( $idx, 'rmi', $rml_item_name . ' (' . ( $rml_album_name ? $rml_album_name : '???' ) . ')', $dis );
						wppa_import_fixcount( $ct, 4 );
						$idx++;
					}

					wppa_import_footer( 'rmi' );
				}
			}

			// Display the directories to be imported as albums. Do this in the depot only!!
			if ( $is_depot && $dircount ) {
				wppa_import_header( __( 'Album folders' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d albumdirectory in the depot', 'There are %d albumdirectories in the depot', $dircount, 'wp-photo-album-plus' ), $dircount ) );
				if ( ! wppa_switch( 'keep_import_files' ) ) {
					wppa_import_header_check( 'del-dir-cont', __( 'Remove from depot after import.', 'wp-photo-album-plus' ) );
				}
				wppa_import_header_check( 'del-dir', __( 'Remove empty dirs', 'wp-photo-album-plus' ),
											' onchange="wppa_setCookie(\'removeemptydirs\', this.checked, \'365\')"' .
											( wppa_get_cookie( 'removeemptydirs' ) == 'true' ? ' checked="checked"' : '' ) );
				wppa_import_table_start( 'dir', '4' );

				$ct = 0;
				foreach( $files as $dir ) {
					if ( basename( $dir ) != '.' &&
						 basename( $dir ) != '..' &&
						 wppa_is_dir( $dir ) ) {
						wppa_echo( '
						<td>
							<input
								type="checkbox"
								id="file-' . $idx . '"
								name="file-' . $idx .'"
								class="wppa-import-item wppa-dir"
								title="' . $dir . '"
								data-type="dir"
							/>
							<span id="label-file-' . $idx . '" ></span>
							<span id="name-file-' . $idx . '" >&nbsp;
								<b>' .
									htmlspecialchars( wppa_sanitize_file_name( basename( $dir ) ) ) . '
								</b>' );
								$subdirs 		= wppa_glob( $dir.'/*', WPPA_ONLYDIRS );
								$subfiles 		= wppa_glob( $dir.'/*', WPPA_ONLYFILES );
								$subdircount 	= count( $subdirs );
								$subfilecount 	= count( $subfiles );

								wppa_echo( ' ' .
								sprintf( _n( 'Contains %d file', 'Contains %d files', $subfilecount, 'wp-photo-album-plus' ), $subfilecount ) );
								if ( $subdircount ) {
									wppa_echo( ' ' .
									sprintf( _n( 'and %d subdirectory', 'and %d subdirectories', $subdircount, 'wp-photo-album-plus' ), $subdircount ) );
								}
							wppa_echo( '
							</span>
						</td>' );

						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'dir' );
			}

			// Display the csv files
			if ( $is_depot && $csvcount ) {
				wppa_import_header( __( 'CSV files' ,'wp-photo-album-plus' ) );
				wppa_import_header_subtitle( sprintf( _n( 'There is %d .csv file in the depot', 'There are %d .csv files in the depot', $csvcount, 'wp-photo-album-plus' ), $csvcount ) );
				wppa_import_header_check( 'del-after-c', __( 'Remove from depot after successful import.', 'wp-photo-album-plus' ), 'disabled checked' );
				wppa_import_header_check( 'del-after-fc', __( 'Remove from depot after failed import.', 'wp-photo-album-plus' ), 'disabled checked' );
				wppa_import_table_start( 'csv', '4' );

				$ct = 0;
				if ( is_array( $files ) ) foreach( $files as $file ) {
					if ( wppa_is_file( $file ) && strtolower( wppa_get_ext( $file ) ) == 'csv' ) {
						wppa_import_item_check( $idx, 'csv', $file );
						wppa_import_fixcount( $ct, 4 );
					}
					$idx++;
				}

				wppa_import_footer( 'csv' );
			}

			// The submit button
			wppa_echo( '<p>' );

			if ( ( $photocount || $videocount || $audiocount || $documentcount || $albumcount || $dircount || $zipcount || $csvcount ) ) {
				wppa_echo( '
				<input
					id="wppa-start-ajax"
					type="button"
					onclick="wppaImportRuns=true;jQuery(this).hide();jQuery(\'#wppa-stop-ajax\').show();wppaDoAjaxImport(\'button\')"
					class="button-primary"
					value="' . esc_attr( __( 'Start Import', 'wp-photo-album-plus' ) ) . '"
				/>
				<input
					id="wppa-stop-ajax"
					style="display:none;"
					type="button"
					onclick="wppaStopAjaxImport(\'button\')"
					class="button-primary"
					value="' . esc_attr( __( 'Stop Import', 'wp-photo-album-plus' ) ) . '"
				/>' );
			}
			wppa_echo( '
			</p>
		</div>' );

	}
	else {
		if ( $source_type == 'local' ) {
			wppa_ok_message( __( 'There are no importable files found in directory:', 'wp-photo-album-plus' ).' '.$source );
		}
		else {
			wppa_ok_message( __( 'There are no photos found or left to process at url:', 'wp-photo-album-plus' ).' '.$source_url );
		}
	}

	// Upload section
	wppa_import_header( __( 'Upload files to depot', 'wp-photo-album-plus' ) );
	wppa_ajax_import_upload();
	wppa_import_footer( '', 'short' );

	// Error legenda
	wppa_echo( '
	<br><br>
	<input
		type="button"
		value="' . esc_attr__( 'Show error codes', 'wp-photo-album-plus' ) . '"
		onclick="jQuery(\'#wppa-error-legenda\').show();jQuery(this).hide()"
	/>
	<fieldset class="wppa-fieldset" id="wppa-error-legenda" style="display:none;width:fit-content;">
		<legend>' . __( 'Table of defined error codes', 'wp-photo-album-plus' ) . '</legend>
		<ol>' );
			$i = '1';
			foreach( $wppa_import_errors as $er ) {
				wppa_echo( '<li id="err-'.$i.'">' . $er . '</li>' );
				$i++;
			}

		wppa_echo( '
		</ol>
	</fieldset>'
	);

	// Footer section
	wppa_echo( '
	<br><b>' .
		__( 'You can import the following file types:', 'wp-photo-album-plus' ) . '
	</b><br>' );
	if ( wppa_get_option( 'wppa_import_source_type_' . $user, 'local' ) == 'remote' ) {
		wppa_echo( '
		<br>' .
		__( 'Photo file types:', 'wp-photo-album-plus' ) . '
		.jpg .jpeg .png' );
	}
	else {
		if ( PHP_VERSION_ID >= 50207 ) {
			wppa_echo( '<br>' .
			__( 'Compressed file types: .zip', 'wp-photo-album-plus' ) );
		}

		wppa_echo( '<br>' .
		__( 'Photo file types:', 'wp-photo-album-plus' ) );
		foreach ( $wppa_supported_photo_extensions as $ext ) {
			wppa_echo( ' .' . $ext );
		}

		if ( wppa_switch( 'enable_video' ) ) {
			wppa_echo( '<br>' .
			__( 'Video file types:', 'wp-photo-album-plus' ) );
			foreach ( $wppa_supported_video_extensions as $ext ) {
				wppa_echo( ' .' . $ext );
			}
		}
		if ( wppa_switch( 'enable_audio' ) ) {
			wppa_echo( '<br>' .
			__( 'Audio file types:', 'wp-photo-album-plus' ) );
			foreach ( $wppa_supported_audio_extensions as $ext ) {
				wppa_echo( ' .' . $ext );
			}
		}
		if ( in_array( 'pdf', wppa_get_supported_extensions( 'import' ) ) ) {
			wppa_echo( '<br>' .
			__( 'Document file type: .pdf', 'wp-photo-album-plus' ) );
		}
		wppa_echo( '
		<br>' . __( 'WPPA+ file types: .amf .pmf', 'wp-photo-album-plus' ) . '
		<br>' . __( 'Custom data files of type .csv', 'wp-photo-album-plus' ) . '
		<br>' . __( 'Directories with optional subdirs containig photos and/or other media files', 'wp-photo-album-plus' ) . '
		<br><br>' . __( 'Your depot directory is:', 'wp-photo-album-plus' ) . '
		<b> .../' . WPPA_DEPOT . '/</b>' );
	}

	wppa_echo( '<br><br>' );

	wppa_echo( wppa_album_admin_footer() );

	wppa_echo( '</div><!-- .wrap -->' );
}

/* Parts used in the page display */

// Upload html for the backend import page
function wppa_ajax_import_upload() {
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;

	wppa_add_local_js( 'wppa_ajax_import_upload' );

	// Find max files for the system
	$allow_sys = ini_get( 'max_file_uploads' );

	// Find acceptable extensions
	$supported_file_ext = wppa_get_supported_extensions( 'import' );
	$accept = '.' . implode( ',.', $supported_file_ext );

	// Open wrapper
	$result = '
	<div style="clear:both"></div>
	<form
		id="wppa-uplform"
		action="' . wppa_get_ajaxlink( 'plain' ) . '&amp;wppa-action=do-import-upload"
		method="post"
		enctype="multipart/form-data"
		>';

		// Make caption
		$caption = $allow_sys == '1' ? __( 'Select File', 'wp-photo-album-plus' ) : __( 'Select File(s)', 'wp-photo-album-plus' );

		// The (hidden) functional button
		$result .= '
		<input
			type="file"
			accept="' . $accept . '"' .
			( $allow_sys > '1' ? ' multiple' : '' ) . '
			style="display:none;"
			id="wppa-upload"
			name="wppa-upload[]"
			onchange="
				if ( wppaDisplaySelectedFilesForImport(\'wppa-upload\') ) {
					jQuery( \'#wppa-submit\' ).css( \'display\', \'block\' );
				}
				"
		/>';

		// The displayed button
		$result .= '
		<input
			type="button"
			style="max-width:100%;width:auto;margin-top:8px;margin-bottom:8px;padding-left:6px;padding-right:6px;"
			id="wppa-upload-displayed-button"
			class="wppa-upload-button"
			value="' . esc_attr__( 'Browse...', 'wp-photo-album-plus' ) . '"
			onclick="jQuery(\'#wppa-upload\').click();"
		/>';

		// The selectionlist
		$result .= '
		<div id="wppa-upload-display"></div>';

		// The submit button
		$result .= '
		<div style="height:6px;clear:both">
		</div>
		<input
			type="submit"
			id="wppa-submit"
			style="display:none; margin: 6px 0;"
			class="wppa-submit"
			name="wppa-submit" value="' . esc_attr__( 'Upload file(s)', 'wp-photo-album-plus' ) . '"
		/>
		<div style="height:6px;clear:both;">
		</div>
		<div
			id="progress"
			class="wppa-progress"
			style="width:100%;"
			>
			<div id="bar" class="wppa-bar" style="" ></div>
			<div id="percent" class="wppa-percent">0%</div>
		</div>
		<div id="message" class="wppa-message" ></div>';

	// Done
	$result .= '
	</form>';

	// Ajax upload script
	wppa_add_inline_script( 'wppa-admin', 'jQuery(document).ready(function() {jQuery("#wppa-uplform").ajaxForm(wppaGetUploadOptions());});' );

	wppa_echo( $result );
}

// The actual upload function on the import page
function wppa_do_import_upload() {

	$result 	= '1'; // Assume success
	$ok 		= '0';
	$fail 		= '0';
	$files 		= current( $_FILES ); // ['wppa-upload'];
	$filecount 	= is_array( $files ) ? count( $files['error'] ) : 0;
	$supp 		= wppa_get_supported_extensions( 'import' );
	$supp[] 	= 'csv';
	$supp[] 	= 'amf';
	$supp[] 	= 'pmf';

	if ( $filecount ) {
		for ( $i = 0; $i < $filecount; $i++ ) {

			$error 		= $files['error'][$i];
			$tmp_name 	= $files['tmp_name'][$i];
			$name 		= basename( $files['name'][$i] );
			$type 		= $files['type'][$i];
			$size 		= $files['size'][$i];

			if ( $error ) {
				$fail++;
				$result .= '<br><span style="color:red">' . sprintf( __( '%s upload failed', 'wp-photo-album-plus' ), $name ) . '</span>';
			}
			elseif ( ! in_array( wppa_get_ext( $name ), $supp ) ) {
				$fail++;
				$result .= '<br><span style="color:red">' . sprintf( __( '%s unsupported filetype', 'wp-photo-album-plus' ), $name ) . '</span>';
			}
			else {
				$bret = wppa_copy( $tmp_name, WPPA_DEPOT_PATH . '/' . $name );
				if ( ! $bret ) {
					$fail++;
					$result .= '<br><span style="color:red">' . sprintf( __( '%s copy failed', 'wp-photo-album-plus' ), $name ) . '</span>';
				}
				else {
					$ok++;
					$result .= '<br><span style="color:green">' . sprintf( __( '%s succesfull', 'wp-photo-album-plus' ), $name ) . '</span>';
				}
			}
		}

		$result .= '<br>' . ( $ok ? $ok . ' successfull uploaded, ' : '' )  . ( $fail ? $fail . ' failed.' : '' );

		if ( $ok ) {
			update_option( 'wppa_import_source_type_'.wppa_get_user(), 'local' );
			update_option( 'wppa_import_source_'.wppa_get_user(), WPPA_DEPOT_PATH );
			$result .= '
			<br>
			<div style="font-weight:bold">' .
				__( 'Reloading to include the new files, please stand by...', 'wp-photo-album-plus' ) . '
				<img src="" onerror="wppaImportReload(\'php\')">
			</div>';


	//		<input
	//			type="button"
	//			value="' . esc_attr__( 'Reload to view the new files', 'wp-photo-album-plus' ) . '"
	//			onclick="document.location.reload(true);"
	//		/>';
		}
	}

	else {
		$result = '0' . __( 'Upload failed', 'wp-photo-album-plus' );
	}

	wppa_echo( $result );

}

// Open filetype section
function wppa_import_header( $legend ) {

	$result = '
	<div class="wppa-flex">
		<fieldset class="wppa-fieldset">
			<legend class="wppa-legend">' .
				$legend . '
			</legend>';

	wppa_echo( $result );
}

// Close filetype section
function wppa_import_footer( $type, $short = false ) {

	if ( $short ) {
		$result = '</fieldset></div>';
	}
	else {
		$result = '
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td id="' . $type . '-err" colspan="4">
							</td>
						</tr>
					</tfoot>
				</table>
			</fieldset>
		</div>';
	}

	wppa_echo( $result );
}

// Subtitle per import type
function wppa_import_header_subtitle( $txt ) {

	$result = '
	<div style="display:inline-block;margin-right:2em;">
		<label>' . $txt . '</label>
	</div>';

	wppa_echo( $result );
}

// Checkbox above item tables to set switches on import
function wppa_import_header_check( $id, $label, $extra_attr = '' ) {
static $user;

	if ( ! $user ) {
		$user = wppa_get_user();
	}
	$checked = get_option( $id . '-' . $user, 0 );

	// Delete only allowed when in depot
	$d = substr( $id, 0, 3 ) == 'del';
	if ( $d && ( ! wppa_is_depot() && ! wppa_is_subdepot() ) ) return;

	// Class del only when not del after failure
	$del = $d && substr( $id, 0, 11 ) != 'del-after-f';

	$result = '
	<div style="display:inline-block;margin-right:2em;">
		<input
			type="checkbox"
			id="' . $id . '"
			name="' . $id . '"' .
			( $del ? ' class="wppa-del" ' : '' ) .
			( $checked ? ' checked' : '' ) . '
			style="height:20px;"' .
			( $extra_attr ? ' ' . $extra_attr : '' ) . '
		/>
		<label
			for="' . $id . '"
			style="height:20px;"
			>' . $label . '</label>
	</div>';

	wppa_echo( $result );
}

// Open table including check all button
function wppa_import_table_start( $type, $cols ) {

	$result = '
	<div style="clear:both"></div>
	<table
		class="form-table wppa-table widefat"
		style="margin-top:10px;"
		>
		<thead>
			<tr>
				<td colspan="' . $cols . '">
					<input
						type="checkbox"
						id="all-' . $type . '"
						class="wppa-all"
						onchange="checkAll( \'all-' . $type . '\', \'.wppa-' . $type . '\' )"
						style="margin-left:0"
					/>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>';

	wppa_echo( $result );
}

// Checkbox for file item
function wppa_import_item_check( $idx, $type, $xpath, $dis = false ) {

	$file = htmlspecialchars( wppa_sanitize_file_name( basename( $xpath ) ) );
	if ( $type == 'rmi' ) {
		$brackpos = strpos( $xpath, '(' );
		$path = htmlspecialchars( trim( substr( $xpath, 0, $brackpos ) ) );
	}
	else {
		$path = $xpath;
	}

	$result = '
	<td>
		<input
			type="checkbox"
			id="file-' . $idx . '"
			name="file-' . $idx . '"
			class="wppa-import-item wppa-' . $type . ( $dis ? '-dis' : '' ) . '"
			title="' . esc_attr( $path ) . '"
			data-type="' . esc_attr( $type ) . '"
			' . ( $dis ? 'disabled' : '' ) . '
		/>
		<span id="label-file-' . $idx . '" ></span>
		<span id="name-file-' . $idx . '" >&nbsp;';
		switch( $type ) {
			case 'amf':
				$result .= $file . '&nbsp;' .  wppa_get_meta_name( $file, '( ' );
				break;
			case 'video':
				$result .= $file . ' (' . sprintf( '%3.1f', wppa_filesize( $path ) / 1024 ) . ' kb)';
				break;
			case 'audio':
				$result .= $file . ' (' . sprintf( '%3.1f', wppa_filesize( $path ) / 1024 ) . ' kb)';
				break;
			case 'csv':
				$result .= $file . ' (' . sprintf( '%3.1f', wppa_filesize( $path ) / 1024 ) . ' kb)';
				break;
			case 'rmi':
				$result .= $xpath;
				break;
			default:
				$result .= $file;
				break;
		}
		$result .= '
		</span>
	</td>';

	wppa_echo( $result );
}

// Increment item counter and go to new table row when needed
function wppa_import_fixcount( &$ct, $t ) {

	$ct++;
	if ( $ct == $t ) {
		wppa_echo( '</tr><tr>' );
		$ct = 0;
	}
}

// Target Album selection
function wppa_import_album_select( $type, $extra = '' ) {

	$result = '
	<div style="clear:both"></div>
	<div class="left hideifupdate">
		<label>' .
			__( 'Album to import to:', 'wp-photo-album-plus' ) . '
		</label>
		<br>' .
		wppa_album_select_a( array( 'path' 				=> true,
									'selected' 			=> wppa_get_option( 'wppa-' . $type . '-album-import-'.wppa_get_user(), '0' ),
									'addpleaseselect'	=> true,
									'checkowner' 		=> true,
									'checkupload' 		=> true,
									'sort'				=> true,
									'optionclass' 		=> '',
									'tagopen' 			=> '<select name="wppa-' . $type . '-album" id="wppa-' . $type . '-album" >',
									'tagname' 			=> 'wppa-' . $type . '-album',
									'tagid' 			=> 'wppa-' . $type . '-album',
									'tagonchange' 		=> '',
									'multiple' 			=> false,
									'tagstyle' 			=> '',
								) ) .
		wppa_edit_album_link_button( 'wppa-' . $type . '-album' ) .
		( $extra ? $extra : '' ) . '
	</div>';

	wppa_echo( $result );
}

function wppa_load_import_errors() {
global $wppa_import_errors;

	$wppa_import_errors = [
			__( 'Unknown or missing album', 'wp-photo-album-plus' ),												// 1
			__( 'Could not extract zipfile', 'wp-photo-album-plus' ), 												// 2
			__( 'Photo files could not be updated', 'wp-photo-album-plus' ), 										// 3
			__( 'Item data could not be inserted into db table', 'wp-photo-album-plus' ), 							// 4
			__( 'Video could not be processed', 'wp-photo-album-plus' ), 											// 5
			__( 'Audio file could not be processed', 'wp-photo-album-plus' ), 										// 6
			__( 'Document could not be processed', 'wp-photo-album-plus' ), 										// 7
			__( 'Failed to extract', 'wp-photo-album-plus' ), 														// 8
			__( 'Album already exists', 'wp-photo-album-plus' ), 													// 9
			__( 'Class ZipArchive does not exist. Check your php configuration', 'wp-photo-album-plus' ), 			// 10
			__( 'Zipfile does not exist', 'wp-photo-album-plus' ), 													// 11
			__( 'Invalid header. Field not found in db table', 'wp-photo-album-plus' ), 							// 12
			__( 'Security check failure', 'wp-photo-album-plus' ), 													// 13
			__( 'Missing file', 'wp-photo-album-plus' ),  															// 14
			__( 'Duplicate item while duplicates not allowed', 'wp-photo-album-plus' ), 							// 15
			__( 'Create album failed', 'wp-photo-album-plus' ), 													// 16
			__( 'You have no rights to do this', 'wp-photo-album-plus' ),											// 17
			__( 'Could not open file for reading', 'wp-photo-album-plus' ), 										// 18
			__( 'Could not open file for writing', 'wp-photo-album-plus' ), 										// 19
			__( 'Read error', 'wp-photo-album-plus' ), 																// 20
			__( 'Write error', 'wp-photo-album-plus' ), 															// 21
			__( 'Not supported db table', 'wp-photo-album-plus' ), 													// 22
			__( 'Timeout', 'wp-photo-album-plus' ), 																// 23
			__( 'Header must haver at least 2 items, Did you specify the right separator?', 'wp-photo-album-plus' ),// 24
			/* translators: Do not translate 'name', 'photoname' and 'filename', they are db field names */
			__( 'First item must be \'name\', \'photoname\' or \'filename\'', 'wp-photo-album-plus' ), 				// 25
			/* translators: Do not translate 'filename', it is a db field name */
			__( 'First item must be \'filename\' when importing system data fields', 'wp-photo-album-plus' ), 		// 26
			__( 'All available custom data fields are in use', 'wp-photo-album-plus' ), 							// 27
			__( 'Could not import filestructure to albumstructure', 'wp-photo-album-plus' ),				 		// 28
			__( 'Missing parent album', 'wp-photo-album-plus' ), 													// 29
			__( 'Not implemented', 'wp-photo-album-plus' ), 														// 30
			__( 'Could not create source directory', 'wp-photo-album-plus' ), 										// 31
			__( 'Attachment not found while converting file from RealMedia', 'wp-photo-album-plus' ),				// 32
			__( 'Already converted', 'wp-photo-album-plus' ),														// 33
			__( 'Adding poster to media item failed', 'wp-photo-album-plus' ), 										// 34
			__( 'Not found while trying to import from RealMedia', 'wp-photo-album-plus' ),							// 35

	];
}