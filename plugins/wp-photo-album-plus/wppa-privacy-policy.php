<?php
/* wppa-privacy-policy.php
* Package: wp-photo-album-plus
*
* This file contains all procedures related to the privacy policy.
*
* Version 8.2.05.002
*/

function wppa_comment_exporter( $email_address, $page = 1 ) {
global $wpdb;

	// Init
	$number 		= 500; // Limit us to avoid timing out
	$page 			= (int) $page;
	$export_items 	= array();
	$group_id 		= 'wppa-comments';
	$group_label 	= __( 'Comments on photos', 'wp-photo-album-plus' );
	$comments 		= $wpdb->get_results( $wpdb->prepare(
									"SELECT * FROM $wpdb->wppa_comments " .
									"WHERE email = %s " .
									"ORDER BY id " .
									"LIMIT %d,%d", $email_address, ( $page - 1 ) * $number, $number
									), ARRAY_A );

	foreach ( (array) $comments as $comment ) {

		$item_id = "wppa-comment-{$comment['id']}";

		$data = array(
			array(
				'name' 	=> __( 'Photo Name', 'wp-photo-album-plus' ),
				'value' => wppa_get_photo_name( $comment['photo'] )
			),
			array(
				'name' 	=> __( 'Photo Url', 'wp-photo-album-plus' ),
				'value' => make_clickable( wppa_get_photo_url( $comment['photo'] ) )
			),
			array(
				'name' => __( 'Comment', 'wp-photo-album-plus' ),
				'value' => $comment['comment']
			)
		);

		$export_items[] = array(
			'group_id' 		=> $group_id,
			'group_label' 	=> $group_label,
			'item_id' 		=> $item_id,
			'data' 			=> $data,
		);
	}

	// Tell core if we have more comments to work on still
	$done = count( $comments ) < $number;
	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function register_wppa_comment_exporter( $exporters ) {
	$exporters['wppa-comments'] = array(
		'exporter_friendly_name' 	=> __( 'WPPA Comments', 'wp-photo-album-plus' ),
		'callback' 					=> 'wppa_comment_exporter',
	);
	return $exporters;
}

add_filter(
	'wp_privacy_personal_data_exporters',
	'register_wppa_comment_exporter',
	10
);

function wppa_comment_eraser( $email_address, $page = 1 ) {
global $wpdb;

	$count = $wpdb->get_var( $wpdb->prepare(
						"SELECT COUNT(*) FROM $wpdb->wppa_comments " .
						"WHERE email = %s ", $email_address ) );

	$wpdb->query( $wpdb->prepare(
					"DELETE FROM $wpdb->wppa_comments " .
					"WHERE email = %s ", $email_address ) );

	return array( 	'items_removed' => true,
					'items_retained' => false,
					'messages' => array( sprintf( 	_n( '%d comment on photos removed',
														'%d comments on photos removed',
														$count,
														'wp-photo-album-plus' ),
													$count ) ),
					'done' => true,
	);
}

function register_wppa_comment_eraser( $erasers ) {
	$erasers['wppa-comments'] = array(
		'eraser_friendly_name' => __( 'WPPA Comments', 'wp-photo-album-plus' ),
		'callback'             => 'wppa_comment_eraser',
    );
	return $erasers;
}

add_filter(
	'wp_privacy_personal_data_erasers',
	'register_wppa_comment_eraser',
	10
);

function wppa_rating_exporter( $email_address, $page = 1 ) {
global $wpdb;

	// Init
	$number 		= 500; // Limit us to avoid timing out
	$page 			= (int) $page;
	$export_items 	= array();
	$group_id 		= 'wppa-ratings';
	$group_label 	= __( 'Ratings on photos', 'wp-photo-album-plus' );
	$user 			= get_user_by( 'email', $email_address );
	$owner 			= $user->user_login;
	$owner_display	= $user->display_name;
	$ratings 		= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
														   WHERE user = %s
														   OR user = %s
														   ORDER BY id
														   LIMIT %d,%d", $owner, $owner_display, ( $page - 1 ) * $number, $number
									), ARRAY_A );

	foreach ( (array) $ratings as $rating ) {

		$item_id = "wppa-rating-{$rating['id']}";

		$data = array(
			array(
				'name' 	=> __( 'Photo Name', 'wp-photo-album-plus' ),
				'value' => wppa_get_photo_name( $rating['photo'] )
			),
			array(
				'name' 	=> __( 'Photo Url', 'wp-photo-album-plus' ),
				'value' => make_clickable( wppa_get_photo_url( $rating['photo'] ) )
			),
			array(
				'name' 	=> __( 'Rating', 'wp-photo-album-plus' ),
				'value' => $rating['value'] . ( wppa_opt( 'rating_max' ) > 1 ? ' ' . __( 'out of', 'wp-photo-album-plus' ) . ' ' . wppa_opt( 'rating_max' ) : '' )
			)
		);

		$export_items[] = array(
			'group_id' 		=> $group_id,
			'group_label' 	=> $group_label,
			'item_id' 		=> $item_id,
			'data' 			=> $data,
		);
	}

	// Tell core if we have more ratings to work on still
	$done = count( $ratings ) < $number;
	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function register_wppa_rating_exporter( $exporters ) {
	$exporters['wppa-ratings'] = array(
		'exporter_friendly_name' 	=> __( 'WPPA Ratings', 'wp-photo-album-plus' ),
		'callback' 					=> 'wppa_rating_exporter',
	);
	return $exporters;
}

add_filter(
	'wp_privacy_personal_data_exporters',
	'register_wppa_rating_exporter',
	10
);

function wppa_rating_eraser( $email_address, $page = 1 ) {
global $wpdb;

	$user 			= get_user_by( 'email', $email_address );
	$owner 			= $user->user_login;
	$owner_display 	= $user->display_name;
	$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
											  WHERE user = %s
											  OR user = %s", $owner, $owner_display ) );

	$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_rating
								   WHERE user = %s
								   OR user = %s", $owner, $owner_display ) );

	// Need recalc when ratings are removed
	if ( $count ) {
		wppa_schedule_maintenance_proc( 'wppa_rerate' );
	}

	return array( 	'items_removed' => true,
					'items_retained' => false,
					'messages' => array( sprintf( 	_n( '%d rating on photos removed',
														'%d ratings on photos removed',
														$count,
														'wp-photo-album-plus' ),
													$count ) ),
					'done' => true,
	);
}

function register_wppa_rating_eraser( $erasers ) {
	$erasers['wppa-ratings'] = array(
		'eraser_friendly_name' => __( 'WPPA Ratings', 'wp-photo-album-plus' ),
		'callback'             => 'wppa_rating_eraser',
    );
	return $erasers;
}

add_filter(
	'wp_privacy_personal_data_erasers',
	'register_wppa_rating_eraser',
	10
);

function wppa_media_exporter( $email_address, $page = 1 ) {
global $wpdb;

	// Init
	$number 		= 500; // Limit us to avoid timing out
	$page 			= (int) $page;
	$export_items 	= array();
	$group_id 		= 'wppa-media';
	$group_label 	= __( 'Uploaded media items', 'wp-photo-album-plus' );
	$user 			= get_user_by( 'email', $email_address );
	$owner 			= $user->user_login;
	$media_items 	= $wpdb->get_results( $wpdb->prepare(
									"SELECT * FROM $wpdb->wppa_photos " .
									"WHERE owner = %s " .
									"AND album > 0 " .
									"LIMIT %d,%d", $owner, ( $page - 1 ) * $number, $number
									), ARRAY_A );

	$media_export_ids = wppa_get_option( 'wppa-media-export-ids', array() );

	foreach ( (array) $media_items as $media_item ) {

		$id 				= $media_item['id'];
		$item_id 			= "wppa-media-item-{$id}";
		$media_export_ids[] = $id;
		$video_exts 		= wppa_is_video( $id );
		$audio_exts 		= wppa_has_audio( $id );

		// Make image html

		// Video ?
		if ( $video_exts ) {
			$media_type = __( 'Video file', 'wp-photo-album-plus' );

			$media_html = '<video preload="metadata" style="height:150px;float:left" controls >';
			foreach( $video_exts as $ext ) {
				$filename 	= str_replace( '.xxx', '.' . $ext, wppa_get_photo_item( $id, 'filename' ) );
				$mime = str_replace( 'ogv', 'ogg', 'video/'.$ext );
				$media_html .= '<source src="wppa-media/' . $filename . '" type="' . $mime . '" >';
			}
			$media_html .= '</video>';

			$poster_file = wppa_fix_poster_ext( wppa_get_photo_path( $id ), $id );
			if ( is_file( $poster_file ) ) {
				$poster_ext = wppa_get_ext( $poster_file );
				$file = wppa_strip_ext( $filename ) . '.' . $poster_ext;
				$media_html .= 	'<img src="wppa-media/Poster_' . $file . '" style="height:150px;margin-left:12px;" />';
			}
		}

		// Audio ?
		elseif( $audio_exts ) {
			$media_type = __( 'Audio file', 'wp-photo-album-plus' );

			$media_html = '<audio preload="metadata" style="width:250px;" controls >';
			foreach( $audio_exts as $audio_ext ) {
				$filename 	= str_replace( '.xxx', '.' . $audio_ext, wppa_get_photo_item( $id, 'filename' ) );
				$mime = 'audio/'.$ext;
				$media_html .= '<source src="wppa-media/' . $filename . '" type="' . $mime . '" >';
			}
			$media_html .= '</audio>';

			$poster_file = wppa_fix_poster_ext( wppa_get_photo_path( $id ), $id );
			if ( is_file( $poster_file ) ) {
				$poster_ext = wppa_get_ext( $poster_file );
				$file = wppa_strip_ext( $filename ) . '.' . $poster_ext;
				$media_html .= 	'<img src="wppa-media/Poster_' . $file . '" style="height:150px;margin-left:12px;" />';
			}
		}

		// PDF Document ?
		elseif( wppa_get_ext( $media_item['filename'] ) == 'pdf' ) {
			$media_type = __( 'PDF Document', 'wp-photo-album-plus' );
			$filename 	= wppa_get_photo_item( $id, 'filename' );
			$media_html = 	'<a href="wppa-media/' . $filename . '" target="_blank" >' .
								'<img src="wppa-media/' . wppa_strip_ext( $filename ) . '.jpg" style="height:150px;" />' .
							'</a>';
		}

		// Photo
		else {
			$media_type = __( 'Photo', 'wp-photo-album-plus' );
			$filename 	= wppa_get_photo_item( $id, 'filename' );
			$media_html = '<img src="wppa-media/' . $filename . '" style="height:150px;" />';
		}

		// Store Image html
		$data = array(
			array(
				'name' 	=> __( 'Type of media file', 'wp-photo-album-plus' ),
				'value' => $media_type
			),
			array(
				'name' 	=> __( 'Media item Name', 'wp-photo-album-plus' ),
				'value' => wppa_get_photo_name( $id )
			),
			array(
				'name' 	=> __( 'Media file', 'wp-photo-album-plus' ),
				'value' => wppa_fck_filter( $media_html ) // F*ck the formatter removing our preciously composed tags
			),
		);

		// Exifdtm
		$exifdtm = wppa_get_photo_item( $id, 'exifdtm' );
		if ( $exifdtm ) {
			$data[] = array(
				'name'	=> 'Exif date/time',
				'value'	=> $exifdtm
			);
		}

		// Exif GPX
		$exifgpx = wppa_get_photo_item( $id, 'location' );
		if ( $exifgpx ) {
			$data[] = array(
				'name'	=> 'Location',
				'value'	=> $exifgpx
			);
		}

		// Generic exif
		$exifs = $wpdb->get_results( 	"SELECT * FROM $wpdb->wppa_exif " .
										"WHERE photo = " . $id . " " .
										"ORDER BY tag", ARRAY_A );

		if ( is_array( $exifs ) && count( $exifs ) > 0 ) {
			$exif_html = '<small><table><tbody>';
			foreach( $exifs as $exif ) {
				$exif_html .=
				'<tr>' .
					'<th>' .
						( $exif['brand'] ? wppa_exif_tagname( $exif['tag'], $exif['brand'], 'brandonly' ) : wppa_exif_tagname( $exif['tag'] ) ) .
					'</th>' .
					'<td>' .
						$exif['description'] .
					'</td>' .
				'</tr>';
			}
			$exif_html .= '</tbody></table></small>';
			$data[] = array(
				'name' 	=> 'EXIF data',
				'value' => wppa_fck_filter( $exif_html )
			);
		}

		// Generic iptc
		$iptcs 	= $wpdb->get_results( 	"SELECT * FROM $wpdb->wppa_iptc " .
										"WHERE photo = " . $id . " " .
										"ORDER BY tag", ARRAY_A );

		if ( is_array( $iptcs ) && count( $iptcs ) > 0 ) {
			$iptc_html = '<small><table><tbody>';
			foreach( $iptcs as $iptc ) {
				$iptc_html .=
				'<tr>' .
					'<th>' .
						$wpdb->get_var( "SELECT description FROM $wpdb->wppa_iptc " .
										"WHERE photo = 0 AND tag = '" . $iptc['tag'] . "'" ) .
					'</th>' .
					'<td>' .
						$iptc['description'] .
					'</td>' .
				'</tr>';
			}
			$iptc_html .= '</tbody></table></small>';
			$data[] = array(
				'name' 	=> 'IPTC data',
				'value' => wppa_fck_filter( $iptc_html )
			);
		}

		// Add this media item
		$export_items[] = array(
			'group_id' 		=> $group_id,
			'group_label' 	=> $group_label,
			'item_id' 		=> $item_id,
			'data' 			=> $data,
		);

		// Tell the zipper to include this one
		$wppa_media_export_ids[] = $id;

	}

	// Save list of items to append
 	wppa_update_option( 'wppa-media-export-ids', $media_export_ids );

	// Tell core if we have more comments to work on still
	$done = count( (array) $media_items ) < $number;
	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function wppa_fck_filter( $text ) {
	$result = str_replace( array( '<', '>' ), array( '*[', ']*' ), $text );
	return $result;
}

function wppa_unfck_filter( $text ) {
	$result = str_replace( array( '*[', ']*' ), array( '<', '>' ), $text );
	return $result;
}

function wppa_register_media_exporter( $exporters ) {
	$exporters['wppa-media'] = array(
		'exporter_friendly_name' 	=> __( 'WPPA Media items', 'wp-photo-album-plus' ),
		'callback' 					=> 'wppa_media_exporter',
	);
	return $exporters;
}

add_filter(
	'wp_privacy_personal_data_exporters',
	'wppa_register_media_exporter',
	10
);

add_action(
	'wp_privacy_personal_data_export_file_created',
	'wppa_add_media_to_zip',
	10,
	4
);

function wppa_add_media_to_zip( $archive_pathname, $archive_url, $html_report_pathname, $request_id ) {

	$ids = wppa_get_option( 'wppa-media-export-ids', array() );

	if ( ! is_array( $ids ) || count( $ids ) == 0 ) {
		return;
	}

	$zip = new ZipArchive;
	if ( true === $zip->open( $archive_pathname, 1 ) ) {

		// Get, remove, filter and re-insert html file
		if ( $html = $zip->getFromName( 'index.html' ) ) {

			// Remove old version
			$zip->deleteName( 'index.html' );

			// Do our filter
			$html = wppa_unfck_filter( $html ); //str_replace( array( '*[', ']*' ), array( '<', '>' ), $html );

			// Re-insert in zip
			$zip->addFromString( 'index.html', $html );
		}

		if ( ! $zip->getFromName( 'wppa-media' ) ) {
			$zip->addEmptyDir( 'wppa-media' );
		}

		foreach( $ids as $id ) {

			// Media type
			$video_exts = wppa_is_video( $id );
			$audio_exts = wppa_has_audio( $id );
			$is_pdf = wppa_get_ext( wppa_get_photo_item( $id, 'filename' ) ) == 'pdf';

			// PDF ?
			if ( $is_pdf ) {
				$wppa_media_file = wppa_get_source_path( $id );
				$wppa_media_name = wppa_get_photo_item( $id, 'filename' );
				wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name );
				$wppa_media_file = wppa_get_photo_path( $id );
				$wppa_media_name = wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) ) . '.jpg';
				wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name );
			}

			// VIDEO ?
			elseif ( $video_exts ) {
				foreach( $video_exts as $video_ext ) {
					$wppa_media_file = wppa_strip_ext( wppa_get_photo_path( $id ) ) . '.' . $video_ext;
					$wppa_media_name = wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) ) . '.' . $video_ext;
					wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name );
				}
			}

			// AUDIO ?
			elseif ( $audio_exts ) {
				foreach( $audio_exts as $audio_ext ) {
					$wppa_media_file = wppa_strip_ext( wppa_get_photo_path( $id ) ) . '.' . $audio_ext;
					$wppa_media_name = wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) ) . '.' . $audio_ext;
					wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name );
				}
			}

			// Photo
			else {
				$wppa_media_file = wppa_get_photo_path( $id );
				$wppa_media_name = wppa_get_photo_item( $id, 'filename' );
				wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name );
			}

			// Poster?
			if ( $video_exts || $audio_exts ) {
				$wppa_poster_file = wppa_get_photo_path( $id );
				if ( is_file( $wppa_poster_file ) ) {
					$wppa_poster_name = 'Poster_' . wppa_strip_ext( $wppa_media_name ) . '.' . wppa_get_ext( $wppa_poster_file );
					wppa_zip_addfile( $zip, $wppa_poster_file, $wppa_poster_name );
				}
			}
		}
		$zip->close();
	}
	else {
		wppa_log('err', 'Unable to open ' . $archive_pathname );
	}
	delete_option( 'wppa-media-export-ids' );
}

function wppa_zip_addfile( $zip, $wppa_media_file, $wppa_media_name ) {

	if ( is_file( $wppa_media_file ) ) {

		if ( ! $zip->addFile( $wppa_media_file, 'wppa-media/' . $wppa_media_name ) ) {
			$error = __( 'Unable to add data to export file.' ) . ' ' . $wppa_media_file . ' as ' . $wppa_media_name;
			wppa_log( 'err', $error );
		}
	}
	else {
		wppa_log('err', $wppa_media_file . ' does not exist' );
	}
}

function wppa_media_eraser( $email_address, $page = 1 ) {
global $wpdb;

	// Init
	$number 		= 500; // Limit us to avoid timing out
	$page 			= (int) $page;
	$user 			= get_user_by( 'email', $email_address );
	$media_items 	= $wpdb->get_results( $wpdb->prepare(
									"SELECT * FROM $wpdb->wppa_photos " .
									"WHERE owner = %s " .
									"AND album > 0 " .
									"LIMIT %d,%d", $user->user_login, ( $page - 1 ) * $number, $number
									), ARRAY_A );
	$count 			= is_countable( $media_items ) ? count( $media_items ) : 0;
	$items_removed 	= false;

	foreach ( (array) $media_items as $media_item ) {
		wppa_delete_photo( $media_item['id'] );
		$items_removed = true;
	}

	$left_items 	= $wpdb->get_var( $wpdb->prepare(
									"SELECT COUNT(*) FROM $wpdb->wppa_photos " .
									"WHERE owner = %s " .
									"AND album > 0 ",
									$user->user_login
									), ARRAY_A );

	return array( 	'items_removed' => $items_removed,
					'items_retained' => false,
					'messages' => array( sprintf( 	_n( '%d media item scheduled for removal after 1 but within 2 hours',
											'%d media items scheduled for removal after 1 but within 2 hours',
											$count,
											'wp-photo-album-plus' ),
										$count ) ),
					'done' => ( $left_items == 0 ),
	);
}

function wppa_register_media_eraser( $erasers ) {
	$erasers['wppa-media'] = array(
		'eraser_friendly_name' => __( 'WPPA Media', 'wp-photo-album-plus' ),
		'callback'             => 'wppa_media_eraser',
    );
	return $erasers;
}

add_filter(
	'wp_privacy_personal_data_erasers',
	'wppa_register_media_eraser',
	10
);

function wppa_add_privacy_policy_content() {

    if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
        return;
    }

	$result = '';
	if ( wppa_switch( 'show_comments' ) ) {
		$result .= __( 'When you leave a comment on a photo or other media item on this site, we send your name, email address, IP address and comment text to the server.', 'wp-photo-album-plus' ) . ' ';
	}
	if ( wppa_switch( 'rating_on' ) ) {
		$result .= __( 'When you enter a rating on a photo or other media item on this site, we send your (login)name or IP address and your rating to the server.', 'wp-photo-album-plus' ) . ' ';
	}
	if ( wppa_switch( 'user_upload_on' ) ) {
		$result .= __( 'When you upload a photo or other media item on this site, we send your name to the server.', 'wp-photo-album-plus' ) . ' ';
		if ( wppa_switch( 'save_iptc' ) || wppa_switch( 'save_exif' ) ) {
			$result .= __( 'If the photo contains EXIF or IPTC data, this data will be saved on the server.', 'wp-photo-album-plus' ) . ' ';
		}
		else {
			$result .= __( 'If the photo contains GPX location data, this data will be saved on the server.', 'wp-photo-album-plus' ) . ' ';
		}
	}

    wp_add_privacy_policy_content(
        'WP Photo Album Plus',
        wp_kses_post( wpautop( $result, false ) )
    );
}
add_action( 'admin_init', 'wppa_add_privacy_policy_content' );