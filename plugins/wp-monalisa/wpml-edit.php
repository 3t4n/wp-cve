<?php
/** This file is part of the wp-monalisa plugin for wordpress
 *
 * Copyright 2009-2018  Hans Matzen  (email : webmaster at tuxlog.de)
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
 * Ajax function ro save the value of the disable-comments-checkbox in classic editor
 */
function wpml_edit_disable_comments_ajax() {
	// check nonce.
	if ( ! isset( $_POST['nonce'] ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpml_tiny_nonce' ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}

	$postid = ( isset( $_POST['postid'] ) ? sanitize_file_name( wp_unslash( $_POST['postid'] ) ) : '' );
	$excludes = unserialize( get_option( 'wpml_excludes' ) );

	if ( ! is_array( $excludes ) ) {
			$excludes = array();
	}

	if ( ! in_array( $postid, $excludes ) ) {
		array_push( $excludes, $postid );
	} else {
		foreach ( $excludes as $key => &$value ) {
			if ( $postid == $value ) {
				unset( $excludes[ $key ] );
			}
		}
	}

	update_option( 'wpml_excludes', serialize( $excludes ) );

	esc_attr_e( 'saved', 'wp-monalisa' );

	wp_die();
}

/**
 * Stellt fest ob wir uns in einem der edit dialoge befinden.
 */
function in_edit() {
	global $pagenow;

	$ie = false;
	if ( is_admin() && (
		 ( 'post.php' == $pagenow ) ||
		 ( 'page.php' == $pagenow ) ||
		 ( 'post-new.php' == $pagenow ) ||
		 ( 'page-new.php' == $pagenow ) )
	) {
		$ie = true;
	}
	return $ie;
}

/**
 * Init funktion fuer die kommentarunterstuetzung.
 * fuegt das javascript stueckchen hinzu
 */
function wpml_edit_init() {
	if ( is_multisite() ) {
		// in case we are on a multisite try get the settings from blog no one.
		$av = unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		// get options.
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	if ( in_edit() && '1' == $av['onedit'] ) {
		// meta boxen hinzufügen für posts und pages.
		add_meta_box(
			'wpml_metabox',
			__( 'wp-Monalisa', 'wp-monalisa' ),
			'wpml_metabox',
			'post',
			'side',
			'default',
			array( '__back_compat_meta_box' => true )
		);

		add_meta_box(
			'wpml_metabox',
			__( 'wp-Monalisa', 'wp-monalisa' ),
			'wpml_metabox',
			'page',
			'side',
			'default',
			array( '__back_compat_meta_box' => true )
		);
	}
}

/**
 * Crete wp-monalisa metabox.
 */
function wpml_metabox() {
	require_once( 'wpml-func.php' );
	global $wpdb,$post;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );

	// in case we are on a multisite try get the settings from blog no one.
	if ( false == $av ) {
		$av = unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	}

	// icons lesen.
	$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile from %i where onpost=1 order by tid;', $wpml_table ) );

	// check if this post is excluded to set the checkbox correctly.
	$excludes = unserialize( get_option( 'wpml_excludes' ) );
	$check = '';
	if ( is_array( $excludes ) && in_array( $post->ID, $excludes ) ) {
		$check = "checked='checked'";
	}

	$out = '';
	// ausgabe der checkbox zum abstellen der smilies aufbauen.
	$out .= "<label for='smileyswitch'>";
	$out .= __( 'Disable comment smilies on this page/post?', 'wp-monalisa' ) . '&nbsp;</label>';
	$out .= "<input type='checkbox' id='smileyswitch' value='1' $check onchange='javascript:wpml_comment_exclude(" . $post->ID . ");'>";
	$out .= '<input id="wpml_tiny_nonce" name="wpml_tiny_nonce" type="hidden" value="' . wp_create_nonce( 'wpml_tiny_nonce' ) . '" />';
	$out .= "<div id='wpml_messages'></div>";

	// ausgabe der icons aufbauen.
	$out .= "<br/>\n\n";

	if ( 0 == $av['showicon'] ) {
		$out .= "<div class='wpml_commentbox_text'>\n";
	} else {
		$out .= "<div class='wpml_commentbox'>\n";
	}

	$double_check = array(); // array um doppelte auszuschliessen.
	foreach ( $results as $res ) {
		// prüfe ob icon schon ausgegeben,
		// wenn ja überspringe es,
		// wenn nein merken.
		if ( in_array( $res->iconfile, $double_check ) ) {
			continue;
		} else {
			$double_check[] = $res->iconfile;
		}

		// prüfe ob eine neue zeile anfängt, bei tabellenausgabe.
		if ( isset( $sm_count ) && ( 0 == $sm_count ||
		   0 == $sm_count % $av['smiliesperrow'] ) &&
		 1 == $av['showastable'] &&
		 1 == $av['showicon']
		) {
			$out .= "<tr class='wpml_smiley_row' >";
		}

		$ico_url = site_url( $av['icondir'] ) . '/' . $res->iconfile;

		if ( 0 == $av['replaceicon'] ) {
			$smile = $res->emoticon;
			$repl = 0;
		} else {
			$smile = $ico_url;
			$repl = 1;
		}

		// tooltip html bauen.
		$ico_tt = '';
		if ( 1 == $av['icontooltip'] ) {
			$ico_tt = " title='" . addslashes( $smile ) . "' ";
		}

		// icon nur als text ausgeben.
		if ( 0 == $av['showicon'] ) {
			$out .= '<div class="wpml_ico_text" onclick="smile2edit(\'content\',\'' .
			addslashes( $smile ) . '\',' . $repl . ');">' . "\n";
			$out .= $res->emoticon . '&nbsp;';
			$out .= '</div>';
		}

		// icon nur als bild ausgeben.
		if ( 1 == $av['showicon'] ) {
			$out .= '<div class="wpml_ico_icon" onclick="smile2edit(\'content\',\'' .
			addslashes( $smile ) . '\',' . $repl . ');">' . "\n";
			$out .= "<img class='wpml_ico' id='icoimg" . $res->tid . "' src='$ico_url' alt='wp-monalisa icon' $ico_tt />&nbsp;";
			$out .= '</div>';
		}

		// icon als bild und text ausgeben.
		if ( 2 == $av['showicon'] ) {
			$out .= '<div class="wpml_ico_both" onclick="smile2edit(\'content\',\'' .
			addslashes( $smile ) . '\',' . $repl . ');">' . "\n";
			$out .= "<img class='wpml_ico' id='icoimg" . $res->tid . "' src='$ico_url' alt='wp-monalisa icon' $ico_tt/>&nbsp;";
			$out .= '<br />' . $res->emoticon;
			$out .= "</div>\n";
		}
	}

	$out .= '</div>';
	$out .= '<div style="clear:both;">&nbsp;</div>';

	echo wp_kses( $out, wpml_allowed_tags() );
}
