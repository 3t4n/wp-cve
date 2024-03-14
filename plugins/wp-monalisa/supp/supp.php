<?php
/**
 * Copyright 2009-2022  Hans Matzen  (email : support at tuxlog.de)
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
 * @package tl_supp
 * @since 21.12.2009
 */

if ( ! function_exists( 'tl_add_supp' ) ) {
	/**
	 * Create the support message inckuding links.
	 *
	 * @param bool $echoit boolean flag if the results should be echoed.
	 */
	function tl_add_supp( $echoit = false ) {
		$out  = '';
		$out .= '<div style="text-align:right;">';
		// donation link.
		include_once plugin_dir_path( __FILE__ ) . '/donate.php';
		$out .= tl_add_donation_box();
		// support link.

		$wpl = get_option( 'WPLANG', 'en_US' );

		if ( 'de_DE' === $wpl ) {
			$bt     = 'Supportanfrage stellen';
			$teaser = 'Haben Sie eine Frage?';
		} elseif ( 'fr_FR' === $wpl ) {
			$bt     = 'Envoyez une demande de soutien';
			$teaser = 'Avez-vous une question ?';
		} else {
			$bt     = 'Send support request';
			$teaser = 'Any Questions?';
		}

		$out .= $teaser . '&nbsp;&nbsp;&nbsp;';
		$out .= '<a href="mailto:support@tuxlog.de">' . $bt . '</a>&nbsp;&nbsp;&nbsp;';
		$out .= '</div>';

		if ( $echoit ) {
			echo esc_attr( $out );
		} else {
			return $out;
		}
	}
}
