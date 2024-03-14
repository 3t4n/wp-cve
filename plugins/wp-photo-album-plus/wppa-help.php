<?php
/* wppa-help.php
* Pachkage: wp-photo-album-plus
*
* admin help page
* version 8.3.01.001
*/

function _wppa_page_help() {
global $wppa_revno;

	$result = '
	<div class="wrap">
		<h1 style="display:inline;">' .
			get_admin_page_title() . '
		</h1>
		<h3>' . sprintf( __( 'You will find all information and examples on the new %s%s%s site', 'wp-photo-album-plus' ), '<a href="https://wppa.nl/" target="_blank" >', __( 'Docs & Demos', 'wp-photo-album-plus' ) , '</a>' ) .  '</h3>

		<h3>' . __( 'About and credits', 'wp-photo-album-plus' ) . '</h3>
		<p>' .
			__( 'WP Photo Album Plus is extended with many new features and is maintained by J.N. Breetvelt, a.k.a. OpaJaap', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to R.J. Kaplan for WP Photo Album 1.5.1.', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to E.S. Rosenberg for programming tips on security issues.', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to Pavel &#352;orejs for the Numbar code.', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to Alejandro Giraldez Sanches who inspired me to implement the display of spherical panoramic images.', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to Stefan Eggers who pointed me to many typos and other textual errors/inconsistencies as well as giving me various usefull coding suggestions.', 'wp-photo-album-plus' ) . '<br>' .
			__( 'Thanx to the users who reported bugs and asked for enhancements. Without them WPPA should not have been what it is now!', 'wp-photo-album-plus' ) . '<br>
		</p>

		<h3>' . __( 'Licence', 'wp-photo-album-plus' ) . '</h3>
		<p>' .
			__( 'WP Photo Album is released under the', 'wp-photo-album-plus' ) . ' <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPLv2 or later</a> ' . __( 'licence.', 'wp-photo-album-plus' ) . '
		</p>

	</div>';

	wppa_echo( $result );
}
