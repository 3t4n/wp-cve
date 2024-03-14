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
 * @package tlsupp
 * @since 21.12.2009
 */

// donation meta box.
if ( ! function_exists( 'tl_add_donation_box' ) ) {
	/**
	 * Create the support message including links.
	 */
	function tl_add_donation_box() {
		$wpl = get_option( 'WPLANG', 'en_US' );
		if ( 'de_DE' === $wpl ) {
			$lc     = 'de';
			$teaser = 'Macht Ihnen das Plugin Freude?';
		} elseif ( 'fr_FR' === $wpl ) {
			$lc     = 'fr';
			$teaser = "voulez-vous de m'inviter à prendre un café?";
		} else {
			$lc     = 'en';
			$teaser = 'Wanna buy me a coffee?';
		}

		$img    = "btn_donate_SM_$lc.gif";
		$imgurl = plugins_url( $img, __FILE__ );

		$donateurl = 'https://www.tuxlog.de/unterstuetze-meine-projekte-support-my-projects-soutenir-mes-projets/';
		$ret       = '';
		$ret      .= '<div style="display:inline;">' . esc_attr( $teaser ) . ' ';
		$ret      .= '<a target="_blank" href="' . esc_attr( $donateurl ) . '">';
		$ret      .= '<img style="vertical-align: middle;margin:10px;" src="' . esc_attr( $imgurl ) . '" alt="' . esc_attr( $teaser ) . '"/></a> ';
		$ret      .= '</div>';

		return $ret;
	}
}
// end of meta box.
