<?php
 /**
  * This file is part of the wp-monalisa plugin for wordpress
  *
  * Copyright 2009-2024  Hans Matzen  (email : webmaster at tuxlog.de)
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
 * Function to create pak format for all smilies to export.
 */
function export_all_smilies() {
	// get sql object.
	global $wpdb;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );

	//
	// export daten zusammen stellen.
	//
	$export_data = '';

	$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile,onpost,oncomment from %i order by tid;', $wpml_table ) );
	foreach ( $results as $res ) {
		// bildgröße ermitteln.
		$imgsize = getimagesize( ABSPATH . '/' . $av['icondir'] . '/' . $res->iconfile );
		if ( ! is_array( $imgsize ) ) {
			$imgsize = array( 0, 0 );
		}

		// dateiname anhängen.
		$export_data .= "'" . $res->iconfile . "', ";
		// bildgröße anhängen.
		$export_data .= "'" . $imgsize[0] . "', ";
		$export_data .= "'" . $imgsize[1] . "', ";
		// aktiv/inaktiv kennzeichen anhängen.
		$active = ( ( $res->oncomment || $res->onpost ) == 1 ? 1 : 0 );
		$export_data .= "'" . $active . "', ";
		// beschreibung anhängen.
		$export_data .= "'" . $res->emoticon . "', ";
		// emoticon anhängen.
		$export_data .= "'" . $res->emoticon . "'";
		$export_data .= "\n";
	}

	// export ausgeben.
	$out = '';

	// add log area style.
	$out .= '<style>#message {margin:20px; padding:20px; background:#cccccc; color:#cc0000;}</style>';

	$out .= '<div id="exportform" class="wrap" >';
	$out .= '<h2>wp-Monalisa ' . __( 'Export', 'wp-monalisa' ) . '</h2>';
	$out .= '<table class="editform" cellspacing="5" cellpadding="5">';
	$out .= '<tr><td>';

	// dic container fuer das verarbeitungs log.
	$out .= '<textarea name="exportlog" cols="50" rows="25">';
	$out .= $export_data;
	$out .= '</textarea></td></tr>';

	// add close button to form.
	$out .= '<tr><td>';
	$out .= __( 'You can mark the .pak-export with Ctrl-a, copy it with Ctrl-c and paste it to your favourite editor with Ctrl-v.', 'wp-monalisa' );
	$out .= '</td></tr>';
	$out .= '<tr><td><p class="submit">';
	$out .= '<input type="submit" name="close" id="close" value="' . __( 'Close', 'wp-monalisa' ) . '" onclick="tb_remove();" /></p></td>';
	$out .= '</p></td></tr>' . "\n";
	$out .= '</table>' . "\n";

	return $out;
}
