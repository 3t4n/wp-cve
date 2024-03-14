<?php
/* wppa-tinymce-shortcodes.php
* Pachkage: wp-photo-album-plus
*
* Version 8.4.06.007
*/

if ( ! defined( 'ABSPATH' ) )
    die( "Can't load this file directly" );

add_action( 'admin_init', 'wppa_tinymce_gallery_action_init' );

function wppa_tinymce_gallery_action_init() {

	// only hook up these filters if we're in the admin panel, and the current user has permission
	// to edit posts or pages, and the feature is enabled
	if ( wppa_user_is_admin() || ( wppa_switch( 'enable_generator' ) && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) ) {

		add_filter( 'mce_buttons', 'wppa_filter_mce_gallery_button' );
		add_filter( 'mce_external_plugins', 'wppa_filter_mce_gallery_plugin' );
	}
}

function wppa_filter_mce_gallery_button( $buttons ) {

	array_push( $buttons, ' ', 'wppa_gallery_button' );
	return $buttons;
}

function wppa_filter_mce_gallery_plugin( $plugins ) {

	// this plugin file will work the magic of our button
	$file = 'js/wppa-tinymce-shortcodes.js';

	$plugins['wppagallery'] = plugin_dir_url( __FILE__ ) . $file;
	return $plugins;
}

function wppa_make_tinymce_dialog() {
global $wpdb;

	// Prepare albuminfo
	if ( wppa_has_many_albums() ) {
		$albums = null;
	}
	else {
		$albums = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums
									   ORDER BY timestamp DESC", ARRAY_A );
		$albums = wppa_add_paths( $albums );
		$albums = wppa_array_sort( $albums, 'name' );
	}

	// Prepare photoinfo
	$photos = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, album, ext
												   FROM $wpdb->wppa_photos
												   ORDER BY timestamp DESC
												   LIMIT %d", wppa_opt( 'generator_max' ) ), ARRAY_A );

	// Get Tags/cats
	$tags 	= wppa_get_taglist();
	$cats 	= wppa_get_catlist();

	// Pages suitable for landing
	$pages = $wpdb->get_results( "SELECT ID, post_title, post_content, post_parent FROM $wpdb->posts
								  WHERE post_type = 'page'
								  AND post_status = 'publish'
								  ORDER BY post_title", ARRAY_A );

	if ( $pages ) {

		// Translate
		foreach ( array_keys( $pages ) as $index ) {
			$pages[$index]['post_title'] = __( stripslashes($pages[$index]['post_title']  ) );
		}

		// Sort alpahbetically
		$pages = wppa_array_sort( $pages, 'post_title' );
	}

	$admins = array();

	if ( wppa_user_is_admin() ) {
		$admins = get_users( array( 'role' => 'administrator' ) );
	}

	// Make the html
	$result = '
	<div id="wppagallery-form" title="' . esc_attr( __( 'Insert gallery', 'wp-photo-album-plus' ) ) . '">
		<table id="wppagallery-table" class="form-table">' .

			// Top type selection
			'<tr>
				<th><label for="wppagallery-top-type">' . __( 'Type of WPPA display:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-top-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a display type', 'wp-photo-album-plus' ) . ' --</option>
						<option value="galerytype">' . __( 'A gallery with covers and/or thumbnails', 'wp-photo-album-plus' ) . '</option>
						<option value="slidestype">' . __( 'A slideshow', 'wp-photo-album-plus' ) . '</option>
						<option value="singletype">' . __( 'A single image', 'wp-photo-album-plus' ) . '</option>
						<option value="searchtype">' . __( 'A search/selection box', 'wp-photo-album-plus' ) . '</option>
						<option value="misceltype">' . __( 'An other box type', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Top type I: gallery sub type
			'<tr id="wppagallery-galery-type-tr" style="display:none;">
				<th><label for="wppagallery-galery-type">' . __( 'Type of gallery display:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-galery-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a gallery type', 'wp-photo-album-plus' ) . ' --</option>
						<option value="cover">' . __( 'The cover(s) of specific album(s)', 'wp-photo-album-plus' ) . '</option>
						<option value="content">' . __( 'The content of specific album(s)', 'wp-photo-album-plus' ) . '</option>
						<option value="covers">' . __( 'The covers of the sub albums of specific album(s)', 'wp-photo-album-plus' ) . '</option>
						<option value="thumbs">' . __( 'The thumbnails of specific album(s)', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Top type II: slide sub type
			'<tr id="wppagallery-slides-type-tr" style="display:none;">
				<th><label for="wppagallery-slides-type">' . __( 'Type of slideshow:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-slides-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a slideshow type', 'wp-photo-album-plus' ) . ' --</option>
						<option value="slide">' . __( 'A fully featured slideshow', 'wp-photo-album-plus' ) . '</option>
						<option value="slideonly">' . __( 'A slideshow without supporting boxes', 'wp-photo-album-plus' ) . '</option>
						<option value="slideonlyf">' . __( 'A slideshow with a filmstrip only', 'wp-photo-album-plus' ) . '</option>
						<option value="filmonly">' . __( 'A filmstrip only', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Top type III: single sub type
			'<tr id="wppagallery-single-type-tr" style="display:none;">
				<th><label for="wppagallery-single-type">' . __( 'Type of single image:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-single-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a single image type', 'wp-photo-album-plus' ) . ' --</option>
						<option value="photo">' . __( 'A plain single photo', 'wp-photo-album-plus' ) . '</option>
						<option value="mphoto">' . __( 'A single photo with caption', 'wp-photo-album-plus' ) . '</option>
						<option value="xphoto">' . __( 'A single photo with extended caption', 'wp-photo-album-plus' ) . '</option>
						<option value="slphoto">' . __( 'A single photo in the style of a slideshow', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>'.

			// Top type IV: search sub type
			'<tr id="wppagallery-search-type-tr" style="display:none;">
				<th><label for="wppagallery-search-type">' . __( 'Type of search:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-search-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a search type', 'wp-photo-album-plus' ) . ' --</option>
						<option value="search">' . __( 'A search box', 'wp-photo-album-plus' ) . '</option>
						<option value="supersearch">' . __( 'A supersearch box', 'wp-photo-album-plus' ) . '</option>
						<option value="tagcloud">' . __( 'A tagcloud box', 'wp-photo-album-plus' ) . '</option>
						<option value="multitag">' . __( 'A multitag box', 'wp-photo-album-plus' ) . '</option>
						<option value="superview">' . __( 'A superview box', 'wp-photo-album-plus' ) . '</option>
						<option value="calendar">' . __( 'A calendar box', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Top type V: other sub type
			'<tr id="wppagallery-miscel-type-tr" style="display:none;">
				<th><label for="wppagallery-miscel-type">' . __( 'Type miscellaneous:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-miscel-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a miscellaneous display', 'wp-photo-album-plus' ) . ' --</option>
						<option value="generic">' . __( 'A generic albums display', 'wp-photo-album-plus' ) . '</option>
						<option value="upload">' . __( 'An upload box', 'wp-photo-album-plus' ) . '</option>
						<option value="landing">' . __( 'A landing page shortcode', 'wp-photo-album-plus' ) . '</option>
						<option value="stereo">' . __( 'A 3D stereo settings box', 'wp-photo-album-plus' ) . '</option>
						<option value="choice">' . __( 'An admins choice box', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Administrators ( for admins choice, show admin only if current user is an admin or superuser )
			'<tr id="wppagallery-admins-tr" style="display:none;">
				<th><label for="wppagallery-admins">' . __( 'Users:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-admins" name="admins" style="color:#070;" multiple onchange="wppaGalleryEvaluate()">
						<option value="" selected style="color:#070;">-- ' . __( 'All', 'wp-photo-album-plus' ) . ' --</option>';
							foreach( $admins as $user ) {
								$result .= '
								<option value="' . $user->data->user_login . '" class="wppagallery-admin" style="color:#070;">' . $user->data->display_name . '</option>';
							}
							$users = wppa_get_option( 'wppa_super_users', array() );
							foreach( $users as $user ) {
								$result .=
								'<option value="' . $user . '" class="wppagallery-admin">' . $user . '</option>';
							}
						$result .= '
					</select>
				</td>
			</tr>' .

			// Real or Virtual albums
			'<tr id="wppagallery-album-type-tr" style="display:none;">
				<th><label for="wppagallery-album-type">' . __( 'Kind of selection:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-album-type" name="type" onchange="wppaGalleryEvaluate()">
						<option value="" selected disabled style="color:#700 !important;">-- ' . __( 'Please select a type of selection to be used', 'wp-photo-album-plus' ) . ' --</option>
						<option value="real">' . __( 'One or more wppa+ albums', 'wp-photo-album-plus' ) . '</option>
						<option value="virtual">' . __( 'A special selection', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Virtual albums
			'<tr id="wppagallery-album-virt-tr" style="display:none;">
				<th><label for="wppagallery-album-virt">' . __( 'The selection to be used:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-album-virt" name="album" class="wppagallery-album" onchange="wppaGalleryEvaluate()">
						<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'Please select a virtual album', 'wp-photo-album-plus' ) . ' --</option>
						<option value="#last">' . __( 'The most recently modified album', 'wp-photo-album-plus' ) . '</option>
						<option value="#topten">' . __( 'The top rated photos', 'wp-photo-album-plus' ) . '</option>
						<option value="#lasten">' . __( 'The most recently uploaded photos', 'wp-photo-album-plus' ) . '</option>
						<option value="#featen">' . __( 'A random selection of featured photos', 'wp-photo-album-plus' ) . '</option>
						<option value="#comten">' . __( 'The most recently commented photos', 'wp-photo-album-plus' ) . '</option>
						<option value="#tags">' . __( 'Photos tagged with certain tags', 'wp-photo-album-plus' ) . '</option>
						<option value="#cat">' . __( 'Albums tagged with a certain category', 'wp-photo-album-plus' ) . '</option>
						<option value="#owner">' . __( 'Photos in albums owned by a certain user', 'wp-photo-album-plus' ) . '</option>
						<option value="#upldr">' . __( 'Photos uploaded by a certain user', 'wp-photo-album-plus' ) . '</option>
						<option value="#all">' . __( 'All photos in the system', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Virtual albums that have covers
			'<tr id="wppagallery-album-virt-cover-tr" style="display:none;">
				<th><label for="wppagallery-album-virt-cover">' . __( 'The selection to be used:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-album-virt-cover" name="album" class="wppagallery-album" onchange="wppaGalleryEvaluate()">
						<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'Please select a virtual album', 'wp-photo-album-plus' ) . ' --</option>
						<option value="#last">' . __( 'The most recently added album', 'wp-photo-album-plus' ) . '</option>
						<option value="#owner">' . __( 'Albums owned by a certain user', 'wp-photo-album-plus' ) . '</option>
						<option value="#cat">' . __( 'Albums tagged with certain categories', 'wp-photo-album-plus' ) . '</option>
						<option value="#all">' . __( 'All albums in the system', 'wp-photo-album-plus' ) . '</option>
					</select>
				</td>
			</tr>' .

			// Real albums
			'<tr id="wppagallery-album-real-tr" style="display:none;">
				<th><label for="wppagallery-album-real">' . __( 'The Album(s) to be used:', 'wp-photo-album-plus' ) . '</label></th>
				<td>';
				if ( wppa_has_many_albums() ) {
					$result .=
					'<input id="wppagallery-album-real" style="max-width:400px;" name="album" onchange="wppaGalleryEvaluate()" />
					<br>' . __( 'Enter one or more album numbers, seperated by commas', 'wp-photo-album-plus' );
				}
				else {
					$result .=
					'<select
						id="wppagallery-album-real"
						style="max-width:400px;"
						name="album"
						multiple
						onchange="wppaGalleryEvaluate()"
						>';
						if ( $albums ) {

							// Please select
							$result .= '<option id="wppagallery-album-0" value="0" disabled selected style="color:#700 !important;">-- ' . __( 'Please select one or more albums', 'wp-photo-album-plus' ) . ' --</option>';

							// All standard albums
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															 'optionclass' 	=> 'wppagallery-album-r',
															] );

						}
						else {
							$result .= '<option value="0" style="color:#700;">' . __( 'There are no albums yet', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '</select>';
				}
				$result .=
				'</td>
			</tr>';
			if ( ! wppa_has_many_albums() ) {
				$result .=
				'<tr id="wppagallery-album-real-search-tr" style="display:none;">
					<th><label for="">' . __( 'Filter album:', 'wp-photo-album-plus' ) . '</label></th>
					<td>
						<input id="wppagallery-album-real-search" type="text" onkeyup="wppaGalleryEvaluate()" />
						<br>
						<small>' .
							__( 'Enter a (part of) the album name to limit the options in the selection box above.', 'wp-photo-album-plus' ) . '
						</small>
					</td>
				</tr>';
			}

			// Real albums optional
			$result .=
			'<tr id="wppagallery-album-realopt-tr" style="display:none;">
				<th><label for="wppagallery-album-realopt">' . __( 'The Album(s) to be used:', 'wp-photo-album-plus' ) . '</label></th>
				<td>';
				if ( wppa_has_many_albums() ) {
					$result .=
					'<input id="wppagallery-album-realopt" style="max-width:400px;" name="album" onchange="wppaGalleryEvaluate()" value="0" />
					<br>' .
					__( 'Optinally enter one or more album numbers, seperated by commas, or 0 for all albums', 'wp-photo-album-plus' );
				}
				else {
					$result .=
					'<select
						id="wppagallery-album-realopt"
						style="max-width:400px;"
						name="album"
						multiple
						onchange="wppaGalleryEvaluate()"
						>';
						if ( $albums ) {

							// Please select
							$result .= '<option id="wppagallery-album-0" class="wppagallery-album-ropt" value="0" selected >--- ' . __( 'All albums', 'wp-photo-album-plus' ) . ' ---</option>';

							// All standard albums
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															] );

						}
						else {
							$result .= '<option value="0">' . __( 'There are no albums yet', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '</select>';
				}
				$result .=
				'</td>
			</tr>' .

			// Owner selection
			'<tr id="wppagallery-owner-tr" style="display:none">
				<th><label for="wppagallery-owner">' . __( 'The album owner:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-owner" name="owner" class="wppagallery-owner" onchange="wppaGalleryEvaluate()">
						<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'Please select a user', 'wp-photo-album-plus' ) . ' --</option>
						<option value="#me">-- ' . __( 'The logged in visitor', 'wp-photo-album-plus' ) . ' --</option>';
						$users = wppa_get_users();
						if ( $users ) foreach ( $users as $user ) {
							$result .= '<option value="' . $user['user_login'] . '">' . $user['display_name'] . '</option>';
						}
						else {	// Too many
							$result .= '<option value="xxx">-- ' . __( 'Too many users, edit manually', 'wp-photo-album-plus' ) . ' --</option>';
						}
					$result .=
					'</select>
				</td>
			</tr>' .

			// Owner Parent album
			'<tr id="wppagallery-owner-parent-tr" style="display:none;">
				<th><label for="wppagallery-owner-parent">' . __( 'Parent album:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select
						id="wppagallery-owner-parent"
						style="color:#070 !important;max-width:400px;"
						name="parentalbum"
						multiple
						onchange="wppaGalleryEvaluate()"
						>';
						if ( $albums ) {

							// Please select
							$result .= '<option class="wppagallery-album-p" value="" selected >--- ' . __( 'No parent specification', 'wp-photo-album-plus' ) . ' ---</option>';

							// Generic
							$result .= '<option class="wppagallery-album-p" value="zero">--- ' . __( 'The generic parent', 'wp-photo-album-plus' ) . ' ---</option>';

							// All standard albums
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															] );
						}
						else {
							$result .= '<option value="0">' . __( 'There are no albums yet', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '
					</select>
				</td>
			</tr>' .

			// Album parent
			'<tr id="wppagallery-album-parent-tr" style="display:none;">
				<th><label for="wppagallery-album-parent">' . __( 'Parent album:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select
						id="wppagallery-album-parent-parent"
						style="color:#070 !important;max-width:400px;"
						name="parentalbum"
						onchange="wppaGalleryEvaluate()"
						>';
						if ( $albums ) {

							// Please select
							$result .= '<option id="wppagallery-album-0" value="0" selected style="color:#700 !important;">-- ' . __( 'The generic parent', 'wp-photo-album-plus' ) . ' --</option>';

							// All standard albums
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															] );
						}
						else {
							$result .= '<option value="0">' . __( 'There are no albums yet', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '
					</select>
				</td>
			</tr>' .

			// Album count
			'<tr id="wppagallery-album-count-tr" style="display:none;">
				<th><label for="wppagallery-album-count">' . __( 'Max Albums:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input id="wppagallery-album-count" type="text" style="color:#070;" value="1" onchange="wppaGalleryEvaluate()">
				</td>
			</tr>' .

			// Photo count
			'<tr id="wppagallery-photo-count-tr" style="display:none;">
				<th><label for="wppagallery-photo-count">' . __( 'Max Photos:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input id="wppagallery-photo-count" type="text" style="color:#070;" value="1" onchange="wppaGalleryEvaluate()">
				</td>
			</tr>' .

			// Albums with certain cats
			'<tr id="wppagallery-albumcat-tr" style="display:none;">
				<th><label for="wppagallery-albumcat">' . __( 'The album cat(s):', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-albumcat" style="color:#700 !important;" onchange="wppaGalleryEvaluate()" multiple>
						<option value="" disabled selected style="color:#700 !important;">' . __( '--- please select category ---', 'wp-photo-album-plus' ) . '</option>';
						if ( $cats ) foreach ( array_keys( $cats ) as $cat ) {
							$result .= '<option class="wppagallery-albumcat" value="' . $cat . '">' . $cat . '</option>';
						}
						$result .= '
					</select>
				</td>
			</tr>' .

			// Photo selection
			'<tr id="wppagallery-photo-tr" style="display:none;">
				<th><label for="wppagallery-photo" class="wppagallery-photo">' . __( 'The Photo to be used:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-photo" name="photo" class="wppagallery-photo" onchange="wppaGalleryEvaluate()">';
						if ( $photos ) {

							// Please select
							$result .= '<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'Please select a photo', 'wp-photo-album-plus' ) . ' --</option>';
							$result .= '<option value="#potd">-- ' . __( 'The photo of the day', 'wp-photo-album-plus' ) . ' --</option>';

							// Most recent 100 photos
							foreach ( $photos as $photo ) {

								$id = $photo['id'];
								$name = wppa_get_photo_item( $id, 'name' );
								if ( strlen( $name ) > '50' ) $name = substr( $name, '0', '50' ) . '...';

								if ( wppa_get_option( 'wppa_file_system' ) == 'flat' ) {
									$val = $id . '.' . $photo['ext'];
								}
								else {
									$val = wppa_expand_id( $id ) . '.' . $photo['ext'];
								}

								if ( wppa_has_audio( $id ) && is_file( WPPA_UPLOAD_PATH . '/' . wppa_fix_poster_ext( $val, $id ) ) ) {
									$value = wppa_fix_poster_ext( $val, $id );
								}
								else {
									$value = $val;
								}

								$result .= 	'
								<option value="' . $value . '">' . $name . ' (' . wppa_get_album_name( $photo['album'] ) . ')</option>';
							}
							$result .=  '
							<option value="#last">-- ' . __( 'The most recently uploaded photo', 'wp-photo-album-plus' ) . ' --</option>
							<option value="#potd">-- ' . __( 'The photo of the day', 'wp-photo-album-plus' ) . ' --</option>';
						}
						else {
							$result .= '
							<option value="0">' . __( 'There are no photos yet', 'wp-photo-album-plus' ) . '</option>';
						}
						$result .= '
					</select>
					<br>
					<small style="display:none;" class="wppagallery-photo">' .
						__( 'Specify the photo to be used', 'wp-photo-album-plus' ) . '<br>' .
						sprintf( __( 'You can select from a maximum of %d most recently added photos', 'wp-photo-album-plus' ), wppa_opt( 'generator_max' ) ) . '<br>
					</small>
				</td>
			</tr>' .

			// Photo preview
			'<tr id="wppagallery-photo-preview-tr" style="display:none;">
				<th><label for="wppagallery-photo-preview">' . __( 'Preview image:', 'wp-photo-album-plus' ) . '</label></th>
				<td id="wppagallery-photo-preview" style="text-align:center;">
				</td >
			</tr>' .

			// Photos with certain tags
			'<tr id="wppagallery-phototags-tr" style="display:none;">
				<th><label for="wppagallery-phototags">' . __( 'The photo tag(s):', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-phototags" style="color:#700 !important;" multiple onchange="wppaGalleryEvaluate()">
						<option value="" disabled selected style="color:#700 !important;">' . __( '--- please select tag(s) ---', 'wp-photo-album-plus' ) . '</option>';
						if ( $tags ) foreach ( array_keys( $tags ) as $tag ) {
							$result .= '<option class="wppagallery-phototags" value="' . $tag . '">' . $tag . '</option>';
						}
						$result .= '
					</select>
				</td>
			</tr>' .

			// Tags and cats additional settings
			'<tr id="wppagallery-tags-cats-tr" style="display:none;">
				<th><label>' . __( 'Or / And:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input id="wppagallery-or" type="radio" name="andor" value="or" onchange="wppaGalleryEvaluate()"/>' . __( 'Meet any', 'wp-photo-album-plus' ) . '&nbsp;
					<input id="wppagallery-and" type="radio" name="andor" value="and" onchange="wppaGalleryEvaluate()"/>' . __( 'Meet all', 'wp-photo-album-plus' ) . '
				</td>
			</tr>' .

			// Search additional settings
			'<tr id="wppagallery-search-tr" style="display:none;">
				<th><label>' . __( 'Additional features:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input id="wppagallery-sub" type="checkbox" name="sub" onchange="wppaGalleryEvaluate()"/>' . __( 'Enable Subsearch', 'wp-photo-album-plus' ) . '&nbsp;
					<input id="wppagallery-root" type="checkbox" name="root" onchange="wppaGalleryEvaluate()"/>' . __( 'Enable Rootsearch', 'wp-photo-album-plus' ) . '
				</td>
			</tr>' .

			// Optional root album
			'<tr id="wppagallery-rootalbum-tr" style="display:none;">'.
				'<th><label>' . __( 'Search root:', 'wp-photo-album-plus' ) . '</label></th>'.
				'<td>'.
					'<select id="wppagallery-rootalbum" onchange="wppaGalleryEvaluate()">'.
						'<option value="0" selected style="color:#070">-- ' . __( 'The generic parent', 'wp-photo-album-plus' ) . ' --</option>';
						if ( $albums ) {
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															] );
						}
						$result .=
					'</select>'.
				'</td>'.
			'</tr>'.

			// Landing page
			'<tr id="wppagallery-landing-tr" style="display:none;">'.
				'<th><label>' . __( 'Landing page:', 'wp-photo-album-plus' ) . '</label></th>'.
				'<td>'.
					'<select id="wppagallery-landing" onchange="wppaGalleryEvaluate()">'.
						'<option value="0" selected >' . __( '--- default ---', 'wp-photo-album-plus' ) . '</option>';
						if ( $pages ) {
							foreach( $pages as $page ) {
								$dis = '';
								if ( strpos( $page['post_content'], '[wppa' ) === false ) {
									$dis = ' disabled';
								}
								$result .= '<option value="'.$page['ID'].'"'.$dis.' >' . __( $page['post_title'] ) . '</option>';
							}
						}
						$result .=
					'</select>'.
				'</td>'.
			'</tr>'.

			// Tagcloud/list additional settings
			'<tr id="wppagallery-taglist-tr" style="display:none;">'.
				'<th><label>' . __( 'Additional features:', 'wp-photo-album-plus' ) . '</label></th>'.
				'<td>'.
					'<input id="wppagallery-alltags" type="checkbox" checked="checked" name="alltags" onchange="wppaGalleryEvaluate()"/>' . __( 'Enable all tags', 'wp-photo-album-plus' ) . '&nbsp;'.
					'<select id="wppagallery-seltags" style="color:#070; display:none;" name="seltags" multiple onchange="wppaGalleryEvaluate()">';
						if ( $tags ) {
							'<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'Please select the tags to show', 'wp-photo-album-plus' ) . ' --</option>';
							foreach( array_keys($tags) as $tag ) {
								$result .= '<option class="wppagallery-taglist-tags" value="' . $tag . '">' . $tag . '</option>';
							}
						}
						else {
							'<option value="" disabled selected style="color:#700 !important;">-- ' . __( 'There are no tags', 'wp-photo-album-plus' ) . ' --</option>';
						}
						$result .= '</select>'.
				'</td>'.
			'</tr>'.

			// Superview additional settings: optional parent
			'<tr id="wppagallery-album-super-tr" style="display:none;">
				<th><label for="wppagallery-album-super">' . __( 'Parent album:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-album-super-parent" style="color:#070;max-width:400px;" name="parentalbum" onchange="wppaGalleryEvaluate()">';
						if ( $albums ) {

							// Please select
							$result .= '<option value="" selected >-- ' . __( 'The generic parent', 'wp-photo-album-plus' ) . ' --</option>';
							$result .= wppa_album_select_a( ['selected' 	=> false,
															 'path' 		=> true,
															 'sort'			=> true,
															 'addnumbers' 	=> true,
															] );
						}
						else {
							$result .= '<option value="0">' . __( 'There are no albums yet', 'wp-photo-album-plus' ) . '</option>';
						}
					$result .= '
					</select>
				</td>
			</tr>' .

			// Calendar
			'<tr id="wppagallery-calendar-tr" style="display:none;">
				<th><label for="wppagallery-calendar">' . __( 'Calendar type:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-calendar-type" style="color:#070;max-width:400px;" onchange="wppaGalleryEvaluate()">
						<option value="realexifdtm">' . __( 'Real calendar By EXIF date', 'wp-photo-album-plus' ) . '</option>
						<option value="realtimestamp">' . __( 'Real calendar By date of upload', 'wp-photo-album-plus' ) . '</option>
						<option value="realmodified">' . __( 'Real calendar By date last modified', 'wp-photo-album-plus' ) . '</option>
						<option value="exifdtm">' . __( 'By EXIF date', 'wp-photo-album-plus' ) . '</option>
						<option value="timestamp">' . __( 'By date of upload', 'wp-photo-album-plus' ) . '</option>
						<option value="modified">' . __( 'By date last modified', 'wp-photo-album-plus' ) . '</option>
					</select>
					<br>
					<span id="wppagallery-calendar-reverse-span">
						<input type="checkbox" id="wppagallery-calendar-reverse" onchange="wppaGalleryEvaluate()">' . __( 'Last date first', 'wp-photo-album-plus' ) . '&nbsp;&nbsp;
					</span>
				</td>
			</tr>' .

			// Size
			'<tr>
				<th><label for="wppagallery-size">' . __( 'The size of the display:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input type="text" id="wppagallery-size" value="" style="color:#070;" onchange="wppaGalleryEvaluate();">
					<br>
					<small>' .
						__( 'Specify the horizontal size in pixels or <span style="color:blue">auto</span>.', 'wp-photo-album-plus' ) . ' '.
						__( 'A value less than <span style="color:blue">100</span> will automatically be interpreted as a <span style="color:blue">percentage</span> of the available space.', 'wp-photo-album-plus' ) .
						__( 'For responsive with a fixed maximum, add the max to auto e.g. <span style="color:blue">auto,550</span>', 'wp-photo-album-plus' ) . '<br>' .
						__( 'Leave this blank for default size', 'wp-photo-album-plus' ) . '
						</small>
				</td>
			</tr>' .

			// Align
			'<tr>
				<th><label for="wppagallery-align">' . __( 'Horizontal alignment:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<select id="wppagallery-align" name="align" style="color:#070;" onchange="wppaGalleryEvaluate()">
						<option value="none">' . __( '--- none ---', 'wp-photo-album-plus' ) . '</option>
						<option value="left">' . __( 'left', 'wp-photo-album-plus' ) . '</option>
						<option value="center">' . __( 'center', 'wp-photo-album-plus' ) . '</option>
						<option value="right">' . __( 'right', 'wp-photo-album-plus' ) . '</option>
					</select>
					<br>
					<small>' . __( 'Specify the alignment to be used or --- none ---', 'wp-photo-album-plus' ) . '</small>
				</td>
			</tr>' .

			// Slideshow timeout
			'<tr id="wppagallery-timeout-tr">
				<th><label for="wppagallery-timeout">' . __( 'Timeout:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input type="number" min="0" max="900" id="wppagallery-timeout" onchange="wppaGalleryEvaluate()" />' . __( 'seconds', 'wp-photo-album-plus' ) . '
				</td>
			</tr>' .

			// Cache
			'<tr id="wppagallery-cache-tr">
				<th><label for="wppagallery-cache">' . __( 'Cache:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input type="checkbox" id="wppagallery-cache" onchange="wppaGalleryEvaluate()" />' . __( 'Cache this shortcode', 'wp-photo-album-plus' ) . '
				</td>
			</tr>' .

			// Delay
			'<tr id="wppagallery-delay-tr">
				<th><label for="wppagallery-delay">' . __( 'Delay:', 'wp-photo-album-plus' ) . '</label></th>
				<td>
					<input type="checkbox" id="wppagallery-delay" onchange="wppaGalleryEvaluate()" />' . __( 'Delay this shortcode', 'wp-photo-album-plus' ) . '
				</td>
			</tr>

		</table>
		<div id="wppagallery-shortcode-preview-container">
			<input type="text" id="wppagallery-shortcode-preview" style="background-color:#ddd; width:100%; height:26px;" value="[wppa]" />
		</div>
		<div><small>' . __( 'This is a preview of the shortcode that is being generated.', 'wp-photo-album-plus' ) . '</small></div>
		<p class="submit">
			<input type="button" id="wppagallery-submit" class="button-primary" value="' . __( 'Insert Shortcode', 'wp-photo-album-plus' ) . '" name="submit" />&nbsp;
			<input type="button" id="wppagallery-submit-notok" class="button button-secundary" value="' . __( 'Insert Shortcode', 'wp-photo-album-plus' ) . '" onclick="alert(\'' . esc_js( __( 'Please complete the shortcode specs', 'wp-photo-album-plus' ) ) . '\')" />&nbsp;
		</p>
	</div>';

	return $result;
}