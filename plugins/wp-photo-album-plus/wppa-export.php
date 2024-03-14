<?php
/* wppa-export.php
* Package: wp-photo-album-plus
*
* Contains all the export functions
* Version: 8.4.01.003
*
*/

function _wppa_page_export() {
global $wpdb;
global $wppa_try_continue;

	// Export Photos admin page
	$can_zip = PHP_VERSION_ID >= 50207 && class_exists( 'ZipArchive' );

	// See if our depot dir can be or has been created
	if ( ! wppa_is_dir( WPPA_DEPOT_PATH ) ) {
		wppa_mktree( WPPA_DEPOT_PATH );
		if ( ! wppa_is_dir( WPPA_DEPOT_PATH ) ) {

			wppa_error_message( sprintf(
			__( 'Your depot directory <b>%s</b> could not be created.<br>Please create it yourself using a ftp program and make sure the filesystem rights are set to 0755',
			'wp-photo-album-plus' ), WPPA_DEPOT_PATH ) );
			wppa_exit();
		}
	}

	wppa_force_output();

	// Open the page
	wppa_echo( '
	<div class="wrap">
		<h1>' .
			get_admin_page_title() . '
		</h1>' );

	// Do the export if requested
	if ( wppa_get( 'export-submit' ) ) {
		check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_export_photos();
	}

	// Construct inline js
	$the_js = '
	function wppaToggleExportBoxes(elm) {
		if (jQuery(elm).prop("checked")) {
			jQuery(".exbox").prop("checked",true);
		}
		else {
			jQuery(".exbox").prop("checked",false);
		}
	}
	function wppaDeleteExportZips() {
		jQuery("#wppaClearZips").val("' . __( 'Working...', 'wp-photo-album-plus' ) . '");
		jQuery("#wppaClearZips").prop("disabled", true);
		wppaAjaxDeleteExportZips();
	}';
	wppa_add_inline_script( 'wppa-admin', $the_js, true );

	$albums 	= $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums ORDER BY name", ARRAY_A );
	foreach( array_keys( $albums ) as $key ) {
		if ( ! wppa_is_album_visible( $albums[$key]['id'] ) ) {
			unset( $albums[$key] );
		}
	}

	$user = wppa_get_user();

	// Submit button hit?
	if ( wppa_get( 'wppa-export-submit', '', 'text' ) ) {

		// Include album def?
		if ( wppa_get( 'inc-amf', 'off', 'text' ) == 'on' ) {
			$ia = true;
			set_transient( "wppa_inc_amf_$user", 'on', YEAR_IN_SECONDS );
		}
		else {
			$ia = false;
			set_transient( "wppa_inc_amf_$user", 'off', YEAR_IN_SECONDS );
		}

		// Include photo def?
		if ( wppa_get( 'inc-pmf', 'off', 'text' ) == 'on' ) {
			$ip = true;
			set_transient( "wppa_inc_pmf_$user", 'on', YEAR_IN_SECONDS );
		}
		else {
			$ip = false;
			set_transient( "wppa_inc_pmf_$user", 'off', YEAR_IN_SECONDS );
		}

		// Include owner info?
		if ( wppa_get( 'inc-usr', 'off', 'text' ) == 'on' ) {
			$iu = true;
			set_transient( "wppa_inc_usr_$user", 'on', YEAR_IN_SECONDS );
		}
		else {
			$iu = false;
			set_transient( "wppa_inc_usr_$user", 'off', YEAR_IN_SECONDS );
		}
	}

	// No submit, initial entry
	else {
		$ia = get_transient( "wppa_inc_amf_$user" ) == 'on';
		$ip = get_transient( "wppa_inc_pmf_$user" ) == 'on';
		$iu = get_transient( "wppa_inc_usr_$user" ) == 'on';
	}

	wppa_echo( '
		<div style="border:1px solid gray;padding:4px;margin: 3px 0 3px 0;position:relative;">' );

		wppa_echo(
		sprintf( __( 'Photos will be exported to: <b>%s</b>.', 'wp-photo-album-plus' ), WPPA_DEPOT ) . '
			<h2>' .
				__( 'Export photos from albums', 'wp-photo-album-plus' ) );

			if ( $can_zip ) {
				wppa_echo( '
				<input
					type="button"
					id="wppaClearZips"
					class="button-primary"
					style="float:right"
					onclick="wppaDeleteExportZips()"
					value="' . __( 'Clear all zips', 'wp-photo-album-plus' ) . '"
				>' );
			}
		wppa_echo( '</h2>' );

		wppa_echo( '
		<form action="' . esc_url( get_admin_url() . 'admin.php?page=wppa_export_photos' ) . '" method="post"> ' .
			wp_nonce_field( '$wppa_nonce', WPPA_NONCE ) . '
			<table class="form-table wppa-table widefat">
				<thead>
					<tr>
						<td>
							<input type="checkbox" onclick="wppaToggleExportBoxes(this)">&nbsp;' . __( 'Check/uncheck all', 'wp-photo-album-plus' ) . '
						</td>
						<td>
							<input type="checkbox" name="wppa-inc-amf" ' . ( $ia ? 'checked="checked"' : '' ) . '>&nbsp;' . __( 'Include album info', 'wp-photo-album-plus' ) . '
						</td>
						<td>
							<input type="checkbox" name="wppa-inc-pmf" ' . ( $ip ? 'checked="checked"' : '' ) . '>&nbsp;' . __( 'Include photo info', 'wp-photo-album-plus' ) . '
						</td>
						<td>
							<input type="checkbox" name="wppa-inc-usr" ' . ( $iu ? 'checked="checked"' : '' ) . '>&nbsp;' . __( 'Include owner info', 'wp-photo-album-plus' ) . '
						</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
				<tr>' );
					$ct = 0;
					foreach( $albums as $album ) {
						$id = $album['id'];

						// Skip this album if it is not visible for this user
						if ( ! wppa_is_album_visible( $id ) ) continue;

						// Find number of photos in album
						if ( wppa_user_is_admin() ) {
							$numphotos = $wpdb->get_var( $wpdb->prepare(
														 "SELECT COUNT(*)
														  FROM $wpdb->wppa_photos
														  WHERE album = %d
														  AND ext <> 'xxx'
														  AND filename NOT LIKE %s", $id, '%.pdf' ) );
						}
						else {
							$numphotos = $wpdb->get_var( $wpdb->prepare(
														 "SELECT COUNT(*)
														  FROM $wpdb->wppa_photos
														  WHERE album = %d
														  AND ext <> 'xxx'
														  AND filename NOT LIKE %s
														  AND ( status NOT IN ('pending', 'scheduled') OR owner = %s )", $id, '%.pdf', wppa_get_user() ) );
						}


						$line = '&nbsp;' . $id . ':&nbsp;' . __( stripslashes( $album['name'] ) );
						$zipfile = WPPA_DEPOT_PATH . '/album-' . $id . '.zip';

						// Skip this album when its 'empty' for the user
						if ( $numphotos ) {
							if ( is_file( $zipfile ) ) {
								$wppa_zip = new ZipArchive;

								// Find number of photos in zipfile
								$wppa_zip->open( $zipfile, 1 );
								$numfiles = $wppa_zip->numFiles;
								$nin = $numfiles;
								for ( $i = 0; $i < $numfiles; $i++ ) {
									$ext = wppa_get_ext ( $wppa_zip->getNameIndex( $i ) );
									if ( $ext == 'amf' || $ext == 'pmf' ) {
										$nin--;
									}
								}
								$wppa_zip->close();

								if ( $nin < $numphotos ) {
									$xtra = ' ' . __( 'Partial', 'wp-photo-album-plus' ) . ' ' . $nin . '/' . $numphotos;
								}
								else $xtra = '';
								if ( $numphotos ) wppa_echo( '
								<td>' . ( $xtra ? '<input type="checkbox" class="exbox" name="album-' . $album['id'] . '" checked="checked">&nbsp;' : '' ) . '
									<a
										href="' . esc_attr( WPPA_DEPOT_URL . '/album-' . $id . '.zip' ) . '"
										download="' . esc_attr( sanitize_file_name( wppa_get_album_name( $id ) ) ) . '">
										<input type="button" class="button-primary" style="padding:0 10px !important" value="' . esc_attr( __( 'Download', 'wp-photo-album-plus' ) . ' ' . $line ) . $xtra . '">
									</a>
								</td>' );
							}
							else {
								wppa_echo( '
								<td>
									<input type="checkbox" class="exbox" name="album-' . $album['id'] . '"' . ( wppa_get( 'album-' . $id, '0', 'text' ) ? ' checked="checked"' : '' ) . '>&nbsp;' . $line . '
								</td>' );
							}
							if ( $ct == 4 ) {
								wppa_echo( '</tr><tr>' );
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
					}
				wppa_echo( '
				</tr>
				</tbody>
			</table>
			<p>
				<input type="submit" id="exp-submit" class="button-primary" name="wppa-export-submit" value="' . esc_attr( __( 'Export', 'wp-photo-album-plus' ) ) . '">' );
				if ( $wppa_try_continue ) {
					$url = wppa_get_imgdir( 'spinner.gif' );
					$js1 = '
						wppaExpTmr=setInterval(function(){
							var tim=parseInt(jQuery("#extimer").html())-1;
							jQuery(jQuery("#extimer").html(tim));
							if (tim<1) {
								clearInterval(wppaExpTmr);
								jQuery("#exp-submit").trigger("click");
								jQuery(".wppaexpdelta").trigger("click");
								jQuery("#exp-submit").prop("disabled",true);
							}
						}, 1000)';
					$js2 = '
						clearInterval(wppaExpTmr);
						jQuery(".wppaexpdelta").css("display","none")';
					wppa_echo( '
						<img
							class="wppaexpdelta"
							src="'.esc_attr($url).'"
							onload="'.esc_attr($js1).'"
						>
						<span class="wppaexpdelta">' .
							sprintf( __( 'Trying to continue in %s seconds', 'wp-photo-album-plus' ), '<span id="extimer">10</span>' ) . '
						</span>
						<input
							type="button"
							class="wppaexpdelta"
							onclick="'.esc_attr($js2).'"
							value="'.esc_attr(__('Stop', 'wp-photo-album-plus')).'"
						>'
					);
				}
				wppa_echo( '
			</p>
		</form>
		</div>
	</div>' );

}

function wppa_export_photos() {
global $wpdb;
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;
global $wppa_try_continue;

	$wppa_temp_idx 		= 0;
	$wppa_try_continue 	= false;
	$expected_endtime 	= wppa_local_date( 'h:i:s', time() + wppa_time_left( 10 ) );

	$url = esc_attr( wppa_get_imgdir( 'spinner.gif' ) );
	$js  = 'wppaTm=setInterval( function() {
				var t=parseInt(jQuery(".exptmlft").last().html());
				if (t>0) {
					t--;
					jQuery(".exptmlft").last().html(t);
				}
				t=parseInt(jQuery("#tmlft").html());
				if (t>0) {
					t--;
					jQuery("#tmlft").html(t);
				}
				var el = document.getElementById("wppaaction");
				el.scrollTop = el.scrollHeight;
			}, 1000 );
			jQuery(document).ready( function() {
				jQuery(".expspin").css("display","none");
				clearInterval(wppaTm);
			});';

	$tmlft = '<span id="tmlft" style="font-size:1.25em;font-weight:bold;">' . wppa_time_left( 10 ) . '</span>';
	wppa_echo( '
	<h2>' .
		__( 'Exporting', 'wp-photo-album-plus' ) . '...<img class="expspin" src="' . $url . '" onload="' . esc_attr( $js ) . '">
	</h2>' .
	sprintf( __( 'If you do not get a redisplay of the album table within %s seconds, your browser may be timed out.', 'wp-photo-album-plus' ), $tmlft ) . ' ' .
	__( 'In that case, just reopen the export page and try again.', 'wp-photo-album-plus' ) .
	'<br>' );

	if ( PHP_VERSION_ID >= 50207 && class_exists('ZipArchive') ) {
		$can_zip = true;
	}
	else {
		$can_zip = false;
		if ( PHP_VERSION_ID < 50207 ) wppa_warning_message(__('Can export albums and photos, but cannot make a zipfile. Your php version is < 5.2.7.', 'wp-photo-album-plus' ));
		if ( ! class_exists('ZipArchive') ) wppa_warning_message(__('Can export albums and photos, but cannot make a zipfile. Your php version does not support ZipArchive.', 'wp-photo-album-plus' ));
	}

	wppa_echo( '
	<br>
	<div
		id="wppaaction"
		style="
			padding:4px;
			border:1px solid gray;
			margin: 3px 0 3px 0;
			position:relative;
			background-color:lightyellow;
			max-height:250px;overflow:auto;
		">' );

	// The actual export procedure. find the albums
	$albums = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums ORDER BY id" );

	$done 	= 0;
	$abort 	= false;
	foreach( $albums as $id ) {

		// Process this album?
		if ( wppa_get( 'album-' . $id, '0', 'text' ) ) {

			if ( $can_zip ) {
				wppa_echo( '<p>' . __( 'Opening zip output file...', 'wp-photo-album-plus' ) );
				$wppa_zip = new ZipArchive;

				$zipfile = WPPA_DEPOT_PATH . '/album-' . $id . '.zip';
				if ( $wppa_zip->open( $zipfile, 1 ) === TRUE ) {
					$a1 = '<span style="font-weight:bold;font-size:1.25em;">' . basename( $zipfile ) . '</span>';
					$a2 = '<span style="font-weight:bold;font-size:1.25em;">' . wppa_get_album_name( $id ) . '</span>';
					wppa_echo( sprintf( __( 'ok, <br>Filling %1s with data from album %2s', 'wp-photo-album-plus' ), $a1, $a2 ) );
					wppa_write_album_file_by_id( $id );
					$wppa_zip->close();
				} else {
					wppa_echo( __( 'failed', 'wp-photo-album-plus' ) );
					$wppa_zip = false;
				}
			}
			else {
				$wppa_zip = false;
				wppa_write_album_file_by_id( $id );
			}

			wppa_echo( '<br>' . sprintf( __( 'Processing album %d. Time left: %s seconds', 'wp-photo-album-plus' ), $id, '<span class="exptmlft" style="font-weight:bold;font-size:1.25em;">' . ( wppa_time_left( 10 ) ). '</span>' ) );

			$usr = wppa_get_user();
			$from = get_transient( "wppa-album-$id-last-export-$usr" );
			if ( ! $from ) $from = '0';

			if ( wppa_user_is_admin() ) {
				$total = $wpdb->get_var( $wpdb->prepare(
										"SELECT COUNT(*) FROM $wpdb->wppa_photos
										 WHERE album = %d
										 AND ext <> 'xxx'
										 AND filename NOT LIKE %s", $id, '%.pdf' ) );

				$photos = $wpdb->get_results( $wpdb->prepare(
											 "SELECT * FROM $wpdb->wppa_photos
											  WHERE album = %d
											  AND id > %d
											  AND ext <> 'xxx'
											  AND filename NOT LIKE %s
											  ORDER BY id", $id, $from, '%.pdf' ), ARRAY_A );
			}
			else {
				$total = $wpdb->get_var( $wpdb->prepare(
										"SELECT COUNT(*) FROM $wpdb->wppa_photos
										 WHERE album = %d
										 AND ext <> 'xxx'
										 AND filename NOT LIKE %s
										 AND ( status NOT IN ('pending', 'scheduled') OR owner = %s )", $id, '%.pdf' ) );

				$photos = $wpdb->get_results( $wpdb->prepare(
											 "SELECT * FROM $wpdb->wppa_photos
											  WHERE album = %d
											  AND id > %d
											  AND ext <> 'xxx'
											  AND filename NOT LIKE %s
											  AND ( status NOT IN ('pending', 'scheduled') OR owner = %s )
											  ORDER BY id", $id, $from, '%.pdf', wppa_get_user() ), ARRAY_A );
			}

			if ( $total > count( $photos ) ) {
				wppa_echo( '<br>' . sprintf( __( 'Continuing after %d items already processed out of a total of %d', 'wp-photo-album-plus' ), $total - count( $photos ), $total ) );
			}

			$cnt = 0;
			foreach ( $photos as $photo ) {

				// Find photo id
				$photo_id = $photo['id'];

				// Try source first
				$from = wppa_get_source_path( $photo_id );

				// If not source, try display
				if ( ! wppa_is_file( $from ) ) {
					$from = wppa_get_photo_path( $photo_id );
				}

				// If not found, kip this one
				if ( ! wppa_is_file( $from ) ) continue;

				// Find path to depot file
				$to = WPPA_DEPOT_PATH . '/' . $photo_id . '.' . $photo['ext'];

				// If zipfile possible
				if ( $wppa_zip ) {

					$alreadyin = false;

					// Open zip
					$wppa_zip->open( $zipfile, 1 );

					// Add file only when not in yet
					if ( $wppa_zip->locateName( basename( $to ) ) !== false ) {
						$alreadyin = true;
					}
					else {
						$wppa_zip->addFile( $from, basename( $to ) );
					}

					// Close zip
					$wppa_zip->close();

				}

				// No zipfile, just copy to depot
				else {
					wppa_copy( $from, $to );
				}

				// Create the metadata
				if ( ! $alreadyin ) {

					// Open zip
					$wppa_zip->open( $zipfile, 1 );

					// Write the file and optionally add to zip
					$bret = wppa_write_photo_file( $photo );

					// Close zip
					$wppa_zip->close();

					// If meta file failed, quit
					if ( ! $bret ) {
						return false;
					}

					// Increment counter
					else {
						$cnt++;
					}
				}

				// Write a dot to indicate progression
				wppa_echo( '.' );

				if ( wppa_is_time_up( '', 10 ) ) {
					$abort = true;
				}

				set_transient( "wppa-album-$id-last-export-$usr", $photo_id, HOUR_IN_SECONDS );

				if ( $abort ) break;
			}

			if ( $abort && $cnt < $total ) {
				wppa_echo( ' ' . sprintf( __( 'failed. Only %d out of %d photos processed', 'wp-photo-album-plus' ), $cnt, $total ) );
				$wppa_try_continue = true;
			}
			else {
				wppa_echo( ' '. sprintf( __( 'done. %d photos processed', 'wp-photo-album-plus' ), $cnt ) );
				delete_transient( "wppa-album-$id-last-export-$usr" );
			}

			if ( $wppa_zip ) {

				wppa_echo( '<br>' . __( 'Deleting temp files', 'wp-photo-album-plus' ) );

				wppa_delete_export_tempfiles();
			}

			if ( ! $abort ) {
				wppa_echo( '</p>' );
				$done++;
			}

			if ( wppa_is_time_up( '', 10 ) ) break;
		}
	}

	wppa_echo( '<p>' . sprintf( _n( '%d Album exported', '%d Albums exported', $done, 'wp-photo-album-plus' ), $done ) . '</p>' );
	wppa_echo( '</div>' );	// </ wppaaction

}

function wppa_write_album_file_by_id( $id ) {
global $wpdb;
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;

	$inc_amf = wppa_get( 'inc-amf', '', 'text' );
	if ( ! $inc_amf ) return true;

	$inc_usr = wppa_get( 'inc-usr', '', 'text' );

	$album = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
											 WHERE id = %d", $id ), ARRAY_A );

	if ( $album ) {
		$fname 	= WPPA_DEPOT_PATH.'/'.$id.'.amf';
		$file 	= wppa_fopen( $fname, 'wb');
		if ( $file ) {
			$err = ! fwrite( $file,
				"name=" . $album['name'] . "\n" .
				"desc=" . wppa_nl_to_txt( $album['description'] ) . "\n" .
				"aord=" . $album['a_order'] . "\n" .
				"prnt=" . wppa_get_album_name( $album['a_parent'], array( 'raw' => true ) ) . "\n" .
				"pord=" . $album['p_order_by'] . "\n" .
				( $inc_usr ? "ownr=" . $album['owner'] . "\n" : '' ) );

/*
					main_photo bigint(20) NOT NULL,
					cover_linktype tinytext NOT NULL,
					cover_linkpage bigint(20) NOT NULL,
					timestamp tinytext NOT NULL,
					upload_limit tinytext NOT NULL,
					alt_thumbsize tinytext NOT NULL,
					default_tags tinytext NOT NULL,
					cover_type tinytext NOT NULL,
					suba_order_by tinytext NOT NULL,
*/

			if ( $err ) {
				wppa_error_message( sprintf( __( 'Cannot write to file %s.', 'wp-photo-album-plus' ) , $fname ) );
				fclose( $file );
				return false;
			}
			else {
				fclose( $file );
				if ( $wppa_zip ) {
					$wppa_zip->addFile( $fname, basename( $fname ) );
				}
				$wppa_temp[$wppa_temp_idx] = $fname;
				$wppa_temp_idx++;
			}
		}
		else {
			wppa_error_message( __( 'Could not open photo output file.', 'wp-photo-album-plus' ) );
			return false;
		}
	}
	else {
		wppa_error_message( __( 'Could not read album data.', 'wp-photo-album-plus' ) );
		return false;
	}
	return true;
}

function wppa_write_photo_file( $photo )	{
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;

	$inc_pmf = wppa_get( 'inc-pmf', '', 'text' );
	if ( ! $inc_pmf ) return true;

	$inc_usr = wppa_get( 'inc-usr', '', 'text' );

	if ( $photo ) {
		$fname = WPPA_DEPOT_PATH . '/' . $photo['id'] . '.pmf';
		$file = wppa_fopen( $fname, 'wb' );
		if ( $file ) {
			$err = ! fwrite( $file,
				"name=" . $photo['name'] . "\n" .
				"desc=" . wppa_nl_to_txt( $photo['description'] ) . "\n" .
				"pord=" . $photo['p_order'] . "\n" .
				"albm=" . wppa_get_album_name( $photo['album'], array( 'raw' => true ) ) . "\n" .
				"lnku=" . $photo['linkurl']."\n" .
				"lnkt=" . $photo['linktitle']."\n" .
				( $inc_usr ? "ownr=" . $photo['owner'] . "\n" : '' ) );

/*
					ext tinytext NOT NULL,
					mean_rating tinytext NOT NULL,
					linktarget tinytext NOT NULL,
					timestamp tinytext NOT NULL,
					status tinytext NOT NULL,
					rating_count bigint(20) NOT NULL default '0',
					tags tinytext NOT NULL,
					alt tinytext NOT NULL,
					filename tinytext NOT NULL,
					modified tinytext NOT NULL,
					location tinytext NOT NULL,
*/

			if ( $err ) {
				wppa_error_message( sprintf( __( 'Cannot write to file %s.', 'wp-photo-album-plus' ) , $fname ) );
				fclose( $file );
				return false;
			}
			else {
				fclose( $file );
				if ( $wppa_zip ) {
					$wppa_zip->addFile( $fname, basename( $fname ) );
				}
				$wppa_temp[$wppa_temp_idx] = $fname;
				$wppa_temp_idx++;
			}
		}
		else {
			wppa_error_message( __( 'Could not open photo output file.', 'wp-photo-album-plus' ) );
			return false;
		}
	}
	else {
		wppa_error_message( __( 'Could not read photo data.', 'wp-photo-album-plus' ) );
		return false;
	}
	return true;
}

// Export photodata of one item to file for export album
function wppa_export_photo_csv( $id, $alb ) {

	$photo = wppa_cache_photo( $id );
	if ( ! $photo ) {
		wppa_log( 'err', "Photo $id does not exist in wppa_export_photo_csv()" );
		return false;
	}
	$items = ['name' 		=> $photo['name'],
			  'description' => $photo['description'],
			  'album' 		=> $photo['album'],
			  'owner' 		=> $photo['owner'],
			  ];

	// Open outputfile
	$path = WPPA_DEPOT_PATH . '/' . wppa_get_album_item( $alb, 'name' ) . '-photo-metadata.csv';

	// If file already exists, open for append
	if ( wppa_is_file( $path ) ) {
		$file = wppa_fopen( $path, 'ab' );
		if ( ! $file ) {
			wppa_log( 'err', "Could not reopen $path for writing" );
			return false;
		}
	}

	// If new, open for writing
	else {
		$file = wppa_fopen( $path, 'wb' );
		if ( ! $file ) {
			wppa_log( 'err', "Could not open $path for writing" );
			return false;
		}

		// And write header
		$row = array_keys( $items );
		fputcsv( $file, $row, wppa_opt( 'csv_sep' ) );
	}

	// Now write data
	fputcsv( $file, $items, wppa_opt( 'csv_sep' ) );

	// And close the file
	fclose( $file );

	// Done !
	return true;
}

function wppa_delete_export_tempfiles() {
global $wppa_temp;

	if ( is_array( $wppa_temp ) ) {
		foreach ( array_keys( $wppa_temp ) as $key ) {
			$file = $wppa_temp[$key];
			if ( is_file( $file ) ) {
				unlink( $file );
				unset( $wppa_temp[$key] );
			}
			if ( wppa_is_time_up( '', 10 ) ) {
				wppa_echo( '<br>' . __( 'Could not remove all temporary files', 'wp-photo-album-plus' ) );
				return false;
			}
		}
	}
	return true;
}