<?php
/* wppa-edit-tags.php
* Package: wp-photo-album-plus
*
* Version 8.3.01.009
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function _wppa_edit_tags() {

	// Legally here?
	if ( ! current_user_can( 'wppa_edit_tags' ) ) {
		wp_die( 'Insuffucient rights' );
	}

	// Prepare taglist
	$tags = wppa_get_taglist();
	$opts = array( __( '-select a tag-', 'wp-photo-album-plus' ) );
	$vals = array( '' );
	if ( $tags ) foreach( array_keys( $tags ) as $tag ) {
		$opts[] = $tag;
		$vals[] = $tag;
	}

	// Find label and onclick for start button
	$label 	= __( 'Start!', 'wp-photo-album-plus' );
	$me 	= wppa_get_user();
	$user 	= wppa_get_option( 'wppa_edit_tag_user', $me );

	if ( $user && $user != $me ) {
		$label = __( 'Locked!', 'wp-photo-album-plus' );
		$locked = true;
		$onclick = 'alert(\'Is currently being executed by ' . $user . '.\')';
	}
	else {
		$locked = false;
		$onclick = 'if(jQuery(\'#wppa_edit_tag_status\').html()!= \'\' || confirm(\'' . __( 'Are you sure?', 'wp-photo-album-plus' ) . '\') ) wppaMaintenanceProc(\'wppa_edit_tag\', false );';
	}

	$result = '
	<div class="wrap">
		<h1 style="display:inline">' . get_admin_page_title() . '</h1>
		<br><br>
		<input type="hidden" id="wppa-nonce" name="wppa-nonce" value="' . wp_create_nonce( 'wppa-nonce' ) . '" />
		<input type="hidden" name="wppa-key" id="wppa-key" value="" />
		<input type="hidden" name="wppa-sub" id="wppa-sub" value="" />
		<table class="widefat wppa-table wppa-setting-table">
			<thead style="font-weight: bold; " class="wppa_table_8">
				<tr>
					<td>' . esc_html__( 'Name', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Description', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Tag to change', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Change into', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Do it!', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Status', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'To Go', 'wp-photo-album-plus' ) . '</td>
				</tr>
			</thead>
			<tbody class="wppa_table_8">
				<tr class="wppa-setting" style="color:#333;">
					<td>' . esc_html__( 'Edit tag', 'wp-photo-album-plus' ) . '</td>
					<td>' . esc_html__( 'Globally change a tagname.', 'wp-photo-album-plus' ) . '</td>
					<td>' .
						wppa_tag_select( $opts, $vals, '', '', false, '', '600') . '
					</td>
					<td>
						<input
							id="new_tag_value"
							type="text"
							style="float:left;width:75%;height:20px;font-size:11px;margin:0;"
							value="' . esc_attr( trim( wppa_get_option( 'wppa_new_tag_value' ), ',' ) ) . '"
							onchange="wppaAjaxUpdateOptionValue(\'new_tag_value\',this);"
						/>
						<img
							id="img_new_tag_value"
							src="' . esc_url( wppa_get_imgdir() . 'star.ico' ) . '"
							title="' . esc_attr( __( 'Setting unmodified', 'wp-photo-album-plus' ) ) . '"
							style="padding:0 4px;float:left;height:16px;width:16px;"
						/>
					</td>
					<td>
						<input
							id="wppa_edit_tag_button"
							type="button"
							class="button button-secundary"
							style="float:left;border-radius:3px;font-size:11px;height:18px;margin 0 4px;padding: 0 6px"
							value="' . esc_attr( $label ) . '"
							onclick="' . $onclick . '"
						/>
						<input
							id="wppa_edit_tag_continue"
							type="hidden"
							value="no"
						/>
					</td>
					<td>
						<span id="wppa_edit_tag_status" >' . wppa_get_option( 'wppa_edit_tag_status' ) . '</span>
					</td>
					<td>
						<span id="wppa_edit_tag_togo" >' . wppa_get_option( 'wppa_edit_tag_togo' ) . '</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>';

	wppa_echo( $result );

	$the_js = 'jQuery(document).ready(function(){setTimeout(function(){wppaAjaxUpdateTogo(\'wppa_edit_tag\')},1000)});';
	wppa_add_inline_script( 'wppa-admin', $the_js, false );
}


// The tag selection box
function wppa_tag_select( $options, $values ) {

	if ( ! is_array( $options ) ) {
		$result = __( 'There is nothing to select.', 'wp-photo-album-plus' );
		return $result;
	}

	$result = '
	<select
		style="float:left; font-size: 11px; height: 20px; margin: 0px; padding: 0px; max-width:600px;"
		id="tag_to_edit"
		onchange="wppaAjaxUpdateOptionValue(\'tag_to_edit\', this);"
		>';


	$val = wppa_get_option( 'wppa_tag_to_edit' );
	$idx = 0;
	$cnt = count( $options );

	while ( $idx < $cnt ) {

		$result .= '
		<option
			value="' . esc_attr( $values[$idx] ) . '"' .
			( $val == $values[$idx] ? ' selected' : '' ) . '
			>' .
			$options[$idx] . '
		</option>';
		$idx++;
	}

	$result .= '
	</select>
	<img
		id="img_tag_to_edit"
		src="' . wppa_get_imgdir() . 'star.ico"
		style="padding:0 4px; float:left; height:16px; width:16px;"
		/>';

	return $result;
}
