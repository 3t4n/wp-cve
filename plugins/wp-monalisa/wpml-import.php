<?php
 /**
  * This file is part of the wp-monalisa plugin for wordpress
  *
  * Copyright 2009-2012  Hans Matzen  (email : webmaster at tuxlog.de)
  *
  * This program is free software; you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation; either version 2 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program; if not, write to the Free Software
  * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  *
  * @package wp-monalisa
  */

/**
 * Ajax function which is called when the smilies are imported
 * It imports the smilies
 */
function wpml_import_ajax() {
	// get sql object.
	global $wpdb;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );

	// check nonce.
	if ( ! isset( $_POST['nonce'] ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpm_nonce' ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}

	if ( ! empty( $_POST ) ) {

		// check if current smilies should be deleted.
		$smileydel = ( isset( $_POST['smileydel'] ) ? sanitize_text_field( wp_unslash( $_POST['smileydel'] ) ) : 0 );
		$smileypak = ( isset( $_POST['smileypak'] ) ? sanitize_text_field( wp_unslash( $_POST['smileypak'] ) ) : 0 );

		if ( true == $smileydel ) {
			$result = $wpdb->query( $wpdb->prepare( 'delete from %i;', $wpml_table ) );
			echo esc_attr__( 'Smilies deleted.', 'wp-monalisa' ) . '<br />';
		}

		// insert new smilies.
		$row = 1;
		$handle = fopen( ABSPATH . '/' . $av['icondir'] . '/' . $smileypak, 'r' );
		while ( ( $data = fgetcsv( $handle, 512, ',', "'" ) ) !== false ) {
			$num = count( $data );
			$row++;
			if ( 6 == $num || 7 == $num ) {
				$wpdb->query(
					$wpdb->prepare(
						'insert into %i (tid,emoticon,iconfile,onpost,oncomment) values (0, %s, %s, %s, %s);',
						$wpml_table,
						$data[5],
						$data[0],
						( '1' == $data[3] ? '1' : '0' ),
						( '1' == $data[3] ? '1' : '0' )
					)
				);

				// translators: show the name/emoticon of the smiley.
				echo esc_attr( sprintf( __( 'Smiley %s inserted.', 'wp-monalisa' ), $data[0] ) ) . '<br />';
			} else {
				// translators: show the row and number of the column which is not correct.
				echo esc_attr( sprintf( __( 'Record %1\$d has wrong field count(%2\$d). Ignored.', 'wp-monalisa' ), $row, $num ) . '<br />' );
			}
		}
		fclose( $handle );

		wp_die();
	}
}

/**
 * Function to create the import form.
 */
function wpml_import_form() {
	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );

	$out = '';
	// add log area style.
	$out .= '<style>#message {margin:20px; padding:20px; background:#cccccc; color:#cc0000;}</style>';
	$out .= '<div id="importform" class="wrap" >';
	$out .= '<h2>wp-Monalisa ' . __( 'Import', 'wp-monalisa' ) . '</h2>';
	$out .= '<table class="editform" cellspacing="5" cellpadding="5">';
	$out .= '<tr>';
	$out .= '<th scope="row" valign="top"><label for="pakfile">' . __( 'Select smiley package', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><select name="pakfile" id="pakfile">' . "\n";

	// icon file list on disk.
	$flist = scandir( ABSPATH . $av['icondir'] );
	// file loop.
	$pak_select_html = '';
	foreach ( $flist as $pfile ) {
		if ( substr( $pfile, 0, 1 ) != '.' && substr( $pfile, strlen( $pfile ) - 4, 4 ) == '.pak' ) {
			$pak_select_html .= "<option value='" . $pfile . "' ";
			$pak_select_html .= '>' . $pfile . "</option>\n";
		}
	}
	$out .= $pak_select_html . "</select></td>\n";

	// import mit oder ohne überschreiben.
	$out .= '<tr><th scope="row" valign="top"><label for="pakdelall">' . __( 'Delete current smilies before import', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="pakdelall" id="pakdelall" type="checkbox" value="1" /></td></tr>' . "\n";

	// add submit and close button to form. After close reload page to update smiley list.
	$href = site_url( 'wp-admin' ) . '/admin.php?page=wpml-admin.php';
	$out .= '<input name="wpm_nonce" id="wpm_nonce" type="hidden" value="' . esc_attr( wp_create_nonce( 'wpm_nonce' ) ) . '" />';
	$out .= '<tr><td><p class="submit">';
	$out .= '<input type="submit" name="startimport" id="startimport" value="' . __( 'Start import', 'wp-monalisa' ) . ' &raquo;" onclick="javascript:wpml_import();" />';
	$out .= '<td><p class="submit">';
	$out .= '<input type="submit" name="cancelimport" id="cancelimport" value="' . __( 'Close', 'wp-monalisa' ) . '" onclick="tb_remove();if (importdone) parent.location=\'' . $href . '\'" /></p></td>';
	$out .= '</p></td></tr>' . "\n";
	$out .= '</table><hr />' . "\n";
	// dic container fuer das verarbeitungs log.
	$out .= '<div id="message">' . __( 'Import log', 'wp-monalisa' ) . '</div>';
	$out .= "</div>\n";

	return $out;
}
