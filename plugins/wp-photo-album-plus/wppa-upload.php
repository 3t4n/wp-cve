<?php
/* wppa-upload.php
* Package: wp-photo-album-plus
*
* Contains all the upload pages and functions
* Version 8.6.03.005
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// upload images admin page
function _wppa_page_upload() {
global $target;
global $wppa_revno;
global $upload_album;

	// Maybe it helps...
	@ set_time_limit( 0 );

    // Who am I?
	$user = wppa_get_user();

	// Sanitize album input
	$upload_album = wppa_get( 'album' );

	// Update watermark settings for the user if new values supplied
	if ( wppa_switch( 'watermark_on' ) && ( wppa_switch( 'watermark_user' ) || current_user_can( 'wppa_settings' ) ) ) {

		// File
		if ( wppa_get( 'watermark-file' ) ) {

			// Sanitize input
			$watermark_file = sanitize_file_name( wppa_get( 'watermark-file' ), 'nil' );
			if ( stripos( $watermark_file, '.png' ) === false ) {

				if ( ! in_array( $watermark_file, array( '--- none ---', '---name---', '---filename---', '---description---', '---predef---' ) ) ) {
					$watermark_file = 'nil';
				}
			}

			// Update setting
			wppa_update_option( 'wppa_watermark_file_'.$user, $watermark_file );
		}

		// Position
		if ( wppa_get( 'watermark-pos' ) ) {

			// Sanitize input
			$watermark_pos = wppa_get( 'watermark-pos', 'nil' );

			if ( ! in_array( $watermark_pos, array( 'toplft', 'topcen', 'toprht', 'cenlft', 'cencen', 'cenrht', 'botlft', 'botcen', 'botrht' ) ) ) {
				$watermark_pos = 'nil';
			}

			// Update setting
			wppa_update_option( 'wppa_watermark_pos_'.$user, $watermark_pos );
		}
	}

	// If from album admin set the last album
	if ( wppa_get( 'set-album' ) ) {
		wppa_set_last_album( wppa_get( 'set-album' ) );
	}
	elseif ( wppa_get( 'album' ) ) {
		wppa_set_last_album( strval( intval( wppa_get( 'album' ) ) ) );
	}

	// Do the upload if requested
	if ( wppa_get( 'upload-multiple', false, 'bool' ) ) {
		check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		$iret = wppa_upload_multiple();
		if ( $iret && wppa_get( 'go-edit-multiple', false, 'bool' ) ) {
			if ( current_user_can( 'wppa_admin' ) ) {
				wppa_ok_message( __( 'Connecting to edit album...' , 'wp-photo-album-plus' ) );
				wppa_echo( '
					<img
						src="dummy"
						style="display:none"
						onerror="document.location=\'' . get_admin_url() .
									'admin.php?page=wppa_admin_menu&tab=edit&edit-id=' . $upload_album .
									'&wppa-nonce=' . wp_create_nonce( 'wppa-nonce', 'wppa-nonce' ) . '\'" >' );
			}
			elseif ( wppa_opt( 'upload_edit' ) != '-none-' ) {
				wppa_ok_message( __( 'Connecting to edit photos...' , 'wp-photo-album-plus' ) );
				wppa_echo( '
					<img
						src="dummy"
						style="display:none"
						onerror="document.location=\'' . get_admin_url().'admin.php?page=wppa_edit_photo\'" >' );
			}
		}
	}

	// Open the form
	$result = '
	<div class="wrap">
		<h1>' .
			get_admin_page_title() . '
		</h1>';

		// Get some req'd data
		$max_files = ini_get( 'max_file_uploads' );
		$max_files_txt = $max_files;
		if ( $max_files < '1' ) {
			$max_files_txt = __( 'unknown' , 'wp-photo-album-plus' );
			$max_files = '15';
		}
		$max_size = ini_get( 'upload_max_filesize' );
		$max_tot_size = ini_get( 'post_max_size' );

		$max_tot_size_mbytes = substr( $max_tot_size, 0, strlen( $max_tot_size ) - 1 );
		if ( substr( $max_tot_size, -1 ) == 'G' ) { // May upload gigabytes!!
			$max_tot_size_mbytes *= 1024;
		}
		$max_size_mbytes = substr( $max_size, 0, strlen( $max_size ) - 1 );
		$max_size_bytes = $max_size_mbytes * 1024 * 1024;
		$max_time = ini_get( 'max_input_time' );
		if ( $max_time < '1' ) $max_time = __( 'unknown', 'wp-photo-album-plus' );

		// check if albums exist before allowing upload
		if ( ! wppa_has_albums() ) {

			// User can create
			if ( current_user_can( 'wppa_admin' ) ) {
				$url = get_admin_url().'admin.php?page=wppa_admin_menu';
				$result .= '
				<p>' .
					__( 'No albums exist. You must', 'wp-photo-album-plus' ) . '
					&nbsp;<a href="' . $url . '" >' .
						__( 'create one', 'wp-photo-album-plus' ) . '
					</a> ' .
					__( 'before you can upload your photos.', 'wp-photo-album-plus' ) . '
				</p></div>';
				wppa_echo( $result );
				return;
			}

			// User can not create
			else {
				$result .= '
				<p>' .
					__( 'There are no albums where you are allowed to upload photos to.', 'wp-photo-album-plus' ) . '
					<br>' .
					__( 'Ask your administrator to create at least one album that is accessible for you to upload to, or ask him to give you album admin rights.', 'wp-photo-album-plus' ) . '
				</p>';
				wppa_echo( $result );
				return;
			}
		}

		// The information box
		$multi = wppa_user_is_admin() || ! wppa_switch( 'upload_one_only' );
		if ( $multi ) {
			$result .= '
			<div
				style="
					border:1px solid #ccc;
					padding:10px;
					margin-bottom:10px;
					width:600px;
					background-color:#fffbcc;
					border-color:#e6db55;
					"
				>' .
				sprintf( __( '<b>Notice:</b> your server allows you to upload <b>%s</b> files of maximum <b>%s</b> bytes each and total <b>%s</b> bytes and allows <b>%s</b> seconds to complete.', 'wp-photo-album-plus' ), $max_files_txt, $max_size, $max_tot_size, $max_time ) . ' ' .
				__( 'If your request exceeds these limitations, it will fail, probably without an errormessage.', 'wp-photo-album-plus' ) . ' ' .
				__( 'Additionally your hosting provider may have set other limitations on uploading files.' , 'wp-photo-album-plus' ) . '
				<br>' .
				wppa_check_memory_limit() . '
			</div>';
		}

		// The Upload box
		$result .= '
		<div
			style="
				border:1px solid #ccc;
				padding:10px;
				margin-bottom:10px;
				width: 600px;
				"
			>
			<h3 style="margin-top:0px;">' .
				( $multi ?
					__( 'Select Multiple files' , 'wp-photo-album-plus' ) :
					__( 'Select one file', 'wp-photo-album-plus' )
				) . '
			</h3>' .
			( $multi ?
				sprintf( __( 'You can select up to %s files in one selection and upload them.' , 'wp-photo-album-plus' ), $max_files_txt ) .'<br>' :
				''
			) .
			__( 'Supported filetypes are', 'wp-photo-album-plus' ) . ': ' . implode( ', ', wppa_get_supported_extensions() ) . '
			<br>' .
			( $multi ?
				'<small style="color:blue" >' .
					__( 'You need a modern browser that supports HTML-5 to select multiple files' , 'wp-photo-album-plus' ) . '
				</small>' :
				''
			) . '
			<form
				enctype="multipart/form-data"
				action="' . get_admin_url() . 'admin.php?page=wppa_upload_photos"
				method="post"
				>' .
				wp_nonce_field( '$wppa_nonce', WPPA_NONCE, true, false ) . '
				<input
					id="my_files"
					type="file" ' .
					( $multi ? 'multiple ' : '' ) . '
					name="my_files[]"
					onchange="showit()"
				/>
				<div id="files_list2" >
					<h3>' .
						( $multi ?
							__( 'Selected Files:' , 'wp-photo-album-plus' ) :
							__( 'Selected File:' , 'wp-photo-album-plus' )
						) . '
					</h3>
				</div>';

				$the_js = '
				function showit() {
					var canUpload = true;
					var maxsize = parseInt( \'' . $max_tot_size_mbytes . '\' ) * 1024 * 1024;
					var maxcount = parseInt( \'' . $max_files_txt . '\' );
					var totsize = 0;
					var files = document.getElementById( \'my_files\' ).files;
					var tekst =
					"<h3>' . __( 'Selected Files:', 'wp-photo-album-plus' ) . '</h3>" +
					"<table><thead><tr>" +
						"<td>' . __( 'Name' , 'wp-photo-album-plus' ) . '</td>" +
						"<td>' . __( 'Size' , 'wp-photo-album-plus' ) . '</td>" +
						"<td>' . __( 'Type' , 'wp-photo-album-plus' ) . '</td>" +
						"</tr></thead>" +
						"<tbody>" +
							"<tr><td><hr /></td><td><hr /></td><td><hr /></td></tr>";
								for ( var i=0;i<files.length;i++ ) {
									tekst += "<tr>" +
										"<td>" + files[i].name + "</td>" +
										"<td id=\'file"+i+"size\' >" + files[i].size + "</td>";
										totsize += files[i].size;
										tekst += "<td>" + ( files[i].size > ' . $max_size_bytes . ' ? "<span style=\'color:red\' >' . __( 'Too big!' , 'wp-photo-album-plus' ) . '</span>" : files[i].type ) + "</td>" +
									"</tr>";
								}
								tekst += "<tr><td><hr /></td><td><hr /></td><td><hr /></td></tr>";
							var style1 = "";
							var style2 = "";
							var style3 = "";
							var warn1 = "";
							var warn2 = "";
							var warn3 = "";
							if ( maxcount > 0 && files.length > maxcount ) {
								style1 = "color:red";
								warn1 = "' . __( 'Too many!', 'wp-photo-album-plus' ) . '";
							}
							if ( maxsize > 0 && totsize > maxsize ) {
								style2 = "color:red";
								warn2 = "' . __( 'Too big!', 'wp-photo-album-plus' ) . '";
							}
							if ( warn1 || warn2 ) {
								style3 = "color:green";
								warn3 = "' . __( 'Try again!', 'wp-photo-album-plus' ) . '";
								canUpload = false;
							}
							tekst += "<tr><td style="+style1+" >' . __( 'Total' , 'wp-photo-album-plus' ) . ': "+files.length+" "+warn1+"</td><td style="+style2+" >"+totsize+" "+warn2+"</td><td style="+style3+" >"+warn3+"</td></tr>";
							tekst +=
						"</tbody>";
						tekst +=
					"</table>";
					jQuery( "#files_list2" ).html( tekst );
					if ( canUpload ) {
						jQuery( "#wppa-upload-submit" ).prop( "disabled", false );
					}
					else {
						jQuery( "#wppa-upload-submit" ).prop( "disabled", true );
					}
				}';

				wppa_add_inline_script( 'wppa-admin', $the_js, true );

				$result .= '
				<p>
					<label for="wppa-album">' . __( 'Album:' , 'wp-photo-album-plus' ) . '</label>' .
						wppa_album_select_a( array( 'path' 				=> true,
													'addpleaseselect' 	=> true,
													'checkowner' 		=> true,
													'checkupload' 		=> true,
													'sort' 				=> true,
													'selected' 			=> wppa_get_last_album(),
													'tagopen'			=> '<select name="wppa-album" id="wppa-album-s" style="max-width:100%">',
													'tagid' 			=> 'wppa-album-s',
													'tagname' 			=> 'wppa-album',
													'tagstyle' 			=> 'max-width:100%;',
													) ) . '
				</p>';

				// Watermark?
				if ( wppa_switch( 'watermark_on' ) && ( wppa_switch( 'watermark_user' ) || current_user_can( 'wppa_settings' ) ) ) {
					$result .= '
					<p>' .
						__( 'Apply watermark file:', 'wp-photo-album-plus' ) . '
						<select name="wppa-watermark-file" id="wppa-watermark-file" >' .
							wppa_watermark_file_select( 'user' ) . '
						</select>' .
						__( 'Position:', 'wp-photo-album-plus' ) . '
						<select name="wppa-watermark-pos" id="wppa-watermark-pos" >' .
							wppa_watermark_pos_select( 'user' ) . '
						</select>
					</p>';
				}

				// Submit section
				$result .= '
				<input
					id="wppa-upload-submit"
					type="submit"
					class="button-primary"
					name="wppa-upload-multiple"
					disabled
					value="' . __( 'Upload now', 'wp-photo-album-plus' ) . '"
					onclick="if ( document.getElementById( \'wppa-album-s\' ).value == 0 ) { alert( \'' . __( 'Please select an album' , 'wp-photo-album-plus' ) . '\' ); return false; }"
				/> ';

				if ( current_user_can( 'wppa_admin' ) || wppa_opt( 'upload_edit' ) != 'none' ) {
					$result .= '
					<input
						type="checkbox"
						id="wppa-go-edit-multiple"
						name="wppa-go-edit-multiple"
						onchange="wppaCookieCheckbox( this, \'wppa-go-edit-multiple\' )"' .
						( wppa_get_cookie( 'wppa-go-edit-multiple' ) == 'on' ? ' checked' : '' ) . '
					/>';
				}

				if ( current_user_can( 'wppa_admin' ) ) {
					$result .= __( 'After upload: Go to the <b>Edit Album</b> page.', 'wp-photo-album-plus' );
				}
				elseif ( wppa_opt( 'upload_edit' ) != 'none' ) {
					$result .= __( 'After upload: Go to the <b>Edit Photos</b> page.', 'wp-photo-album-plus' );
				}
			$result .= '
			</form>
		</div>
	</div>';

	wppa_echo( $result );
}

// Upload multiple photos
function wppa_upload_multiple() {
global $warning_given;
global $upload_album;

	$warning_given = false;
	$uploaded_a_file = false;

	$count = '0';
	$any_zip = false;
	foreach( $_FILES as $the_file ) {	// Usually only one item: $_FILES['my_file']
		for ( $i = '0'; $i < count( $the_file['error'] ); $i++ ) {

			$file = array( 	'error' 	=> $the_file['error'][$i],
							'tmp_name' 	=> $the_file['tmp_name'][$i],
							'name' 		=> $the_file['name'][$i],
							'type' 		=> $the_file['type'][$i],
							'size' 		=> $the_file['size'][$i],
							);

			$file_is_ok = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], wppa_get_mime_types() );
			if ( ! $file_is_ok['ext'] || ! $file_is_ok['type'] ) {
				$upload_message =
				__( 'Upload failed', 'wp-photo-album-plus' ) . ' ' .
				__( 'You may not uplaod this file type', 'wp-photo-album-plus' ) .
				' (' . sanitize_file_name( $file['name'] ) . ')';
				wppa_error_message( $upload_message );
				return false;
			}

			if ( wppa_upload_one_item( $file, $upload_album, 'upload' ) ) {
				$count++;
				if ( 'zip' == strtolower( wppa_get_ext( $the_file['name'][$i] ) ) ) {
					$any_zip = true;
				}
			}

			if ( wppa_is_time_up() ) {
				wppa_warning_message( sprintf( __( 'Time out. %s files uploaded in album nr %s.' , 'wp-photo-album-plus' ), $count, $upload_album ) );
				wppa_set_last_album( $upload_album );
				return false;
			}
		}
	}

	if ( $count ) {
		wppa_update_message( $count . ' ' . ( $any_zip ?
												__( 'files processed for album nr', 'wp-photo-album-plus' ) :
												__( 'files uploaded in album nr' , 'wp-photo-album-plus' )
											) . ' ' . $upload_album );
		wppa_set_last_album( $upload_album );
    }

	return true;
}

