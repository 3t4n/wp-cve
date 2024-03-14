<?php
/* wppa-edit-sequence.php
* Package: wp-photo-album-plus
*
* Contains the admin menu and startups the admin pages
* Version 8.3.01.009
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function _wppa_edit_sequence() {

	$album = wppa_get( 'album', '0' );
	
	$result = '
	<div class="wrap">

		<h1>' . get_admin_page_title() . '</h1><br>

		<select
			id="wppa-edit-sequence-album"
			>' .
			wppa_album_select_a( array( 'addpleaseselect'	=> true,
										'path'				=> true,
										'selected'			=> $album,
										'sort' 				=> true,
										) ) . '
		</select>
		<input
			type="button"
			class="button-primary"
			onclick="wppaGoEditSequence()"
			value="' . esc_attr( __( 'Go edit sequence', 'wp-photo-album-plus' ) ) . '"
		/>

	</div>';

	wppa_echo( $result );

	if ( $album ) {
		wppa_album_photos_sequence( $album );
	}

	$the_js = '
		function wppaGoEditSequence() {
			var album = jQuery(\'#wppa-edit-sequence-album\').val();
			if (album) {
				var url = document.location.href+\'&album=\'+album;
				document.location.href = url;
			}
			else {
				alert(\'Please select an album first\');
			}
		}';
	wppa_add_inline_script( 'wppa-admin', $the_js, true );
}