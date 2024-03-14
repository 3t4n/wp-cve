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

// sicherheitshalber pruefen, ob wir direkt aufgerufen werden.
$phpself = ( isset( $_SERVER['PHP_SELF'] ) ? sanitize_file_name( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '' );
if ( preg_match( '#' . basename( __FILE__ ) . '#', $phpself ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * Init funktion fuer die kommentarunterstuetzung.
 */
function wpml_comment_init() {
	// optionen einlesen.
	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	if ( ! array_key_exists( 'oncomment', $av ) ) {
		$av['oncomment'] = 0;
	}
	if ( ! array_key_exists( 'wpmlshowbetween', $av ) ) {
		$av['wpmlshowbetween'] = 0;
	}

	// show smileys in commentform if not disabled.
	if ( '1' == $av['oncomment'] && 1 != $av['wpmlshowbetween'] ) {
		add_action( 'comment_form', 'wpml_comment' );
	}

	// show smilies in between of textarea and button if applicable.
	if ( 1 == $av['wpmlshowbetween'] ) {
		add_filter( 'comment_form_defaults', 'wpml_comment_meta' );
	}

	// show smilies in buddypress.
	if ( defined( 'BP_VERSION' ) && '1' == $av['wpml4buddypress'] ) {
		// add smilis to activities.
		add_action( 'bp_after_activity_post_form', 'wpml_comment' );
		add_action( 'bp_activity_entry_comments', 'wpml_comment' );
		// add smilies to messages.
		add_action( 'bp_after_messages_compose_content', 'wpml_comment' );
		// add smilies to forums (bbpress).
		add_action( 'bbp_theme_after_topic_form_content', 'wpml_comment' );
		add_action( 'bbp_theme_after_reply_form_content', 'wpml_comment', 1 );
		add_action( 'groups_forum_new_topic_after', 'wpml_comment' );
		add_action( 'groups_forum_new_reply_after', 'wpml_comment' );
		add_action( 'bp_group_after_edit_forum_topic', 'wpml_comment' );
		add_action( 'bp_after_group_forum_post_new', 'wpml_comment' );
		// add smilies for messages.
		add_action( 'bp_after_messages_compose_content', 'wpml_comment' );
		add_action( 'bp_after_message_reply_box', 'wpml_comment' );
		// add smilies to edit post.
		add_action( 'bp_group_after_edit_forum_post', 'wpml_comment' );

		// for GD bbpress tools signature.
		if ( defined( 'GDBBPRESSTOOLS_CAP' ) ) {
			add_filter( 'bbp_user_edit_signature_info', 'wpml_comment' );
		}
	}

	// show smilies in rtmedia buddypress media plugin.
	if ( defined( 'RTMEDIA_VERSION' ) ) {
		add_action( 'rtmedia_add_comments_extra', 'wpml_comment' );
	}

	// show smilies in bbpress.
	if ( class_exists( 'bbPress' ) && array_key_exists( 'wpml4bbpress', $av ) && '1' == $av['wpml4bbpress'] ) {
		// add smilies to forums (bbpress).
		add_action( 'bbp_theme_after_topic_form_content', 'wpml_comment' );
		add_action( 'bbp_theme_after_reply_form_content', 'wpml_comment', 1 );
	}
}

/**
 * Smilies zwischen textarea und submitt button hinzufügen.
 *
 * @param array $defaults The defulat comment parmaeters.
 */
function wpml_comment_meta( $defaults ) {
	$defaults['comment_notes_after'] = get_wpml_comment();
	return $defaults;
}

/**
 * Print wpml comment.
 *
 * @param int $postid The Post ID.
 */
function wpml_comment( $postid = 0 ) {
	require_once( 'wpml-func.php' );
	echo wp_kses( get_wpml_comment( $postid ), wpml_allowed_tags() );
}

/**
 * Create wpml comment.
 *
 * @param int $postid The Post ID.
 */
function get_wpml_comment( $postid = 0 ) {
	global $wpdb,$post,$wpml_first_preload;

	// if post->ID is not set (like in BuddyPress), we use -1 to get a fals for the comparison for sure.
	$pid = ( isset( $post->ID ) ? $post->ID : -1 );
	$uid = uniqid();
	$out1strow = '';

	// if this post is excluded return nothing :-).
	$excludes = unserialize( get_option( 'wpml_excludes' ) );
	if ( is_array( $excludes ) && in_array( $pid, $excludes ) ) {
		return '';
	}

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// optionen einlesen.
	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	// abfangen wenn wert nicht gesetzt oder 0 ist, dann nehmen wir einfach 1.
	if ( 0 == (int) $av['smiliesperrow'] ) {
		$av['smiliesperrow'] = 1;
	}
	if ( 0 == (int) $av['smilies1strow'] ) {
		$av['smilies1strow'] = 7;
	}

	// icons lesen.
	$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile,width,height from %i where oncomment=1 order by tid;', $wpml_table ) );

	// ausgabe der icons aufbauen.
	$out = "\n\n";
	$loader = '';

	if ( 0 == $av['showicon'] ) {
		$out .= "<div class='wpml_commentbox_text'>\n";
	} else {
		$out .= "<div class='wpml_commentbox'>\n";
	}

	if ( 1 == $av['showastable'] && 1 == $av['showicon'] ) {
		$out .= "<table class='wpml_smiley_table' >";
	}

	$double_check = array(); // array um doppelte auszuschliessen.
	$sm_count = 0;
	foreach ( $results as $res ) {
		// prüfe ob icon schon ausgegeben,
		// wenn ja überspringe es,
		// wenn nein merken.
		if ( in_array( $res->iconfile, $double_check ) ) {
			continue;
		} else {
			$double_check[] = $res->iconfile;
		}

		// prüfe ob eine neue zeile anfängt.
		if ( ( 0 == $sm_count ||
				0 == $sm_count % $av['smiliesperrow'] ) &&
				1 == $av['showastable'] &&
				1 == $av['showicon']
		) {
			$out .= "<tr class='wpml_smiley_row' >";
		}

		// url bauen.
		$ico_url = site_url( $av['icondir'] ) . '/' . $res->iconfile;

		// hohe und breite bauen.
		$dimensions = '';
		if ( 0 != $res->width && 0 != $res->height ) {
			$w = $res->width;
			$h = $res->height;

			if ( isset( $av['wpmlmaxwidth'] ) && $av['wpmlmaxwidth'] > 0 && $w > $av['wpmlmaxwidth'] ) {
				$h = $h * ( $av['wpmlmaxwidth'] / $w );
				$w = $av['wpmlmaxwidth'];
			}

			if ( isset( $av['wpmlmaxheight'] ) && $av['wpmlmaxheight'] > 0 && $h > $av['wpmlmaxheight'] ) {
				$w = $w * ( $av['wpmlmaxheight'] / $h );
				$h = $av['wpmlmaxheight'];
			}

			$dimensions = " width='" . intval( $w ) . "' height='" . intval( $h ) . "' ";
		}

		if ( 1 != $av['replaceicon'] ) {
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
			$out .= '<div class="wpml_ico_text" onclick="smile2comment(\'' .
					$av['commenttextid'] . '\',\'' . addslashes( $smile ) . '\',' . $repl . ');">' . "\n";
			$out .= $res->emoticon . '&nbsp;';
			$out .= "</div>\n";
		}

		// icon nur als bild ausgeben.
		if ( 1 == $av['showicon'] ) {
			if ( 0 == $av['showastable'] ) {

				$out .= '<div class="wpml_ico_icon" id="icodiv-' . $uid . '-' . $res->tid . '" onclick="smile2comment(\'' .
				$av['commenttextid'] . '\',\'' . addslashes( $smile ) . '\',' . $repl . ',\'icodiv-' . $uid . '-' . $res->tid . '\');">' . "\n";
				$out .= "<img class='wpml_ico' " .
						" id='icoimg" . $uid . '-' . $res->tid . "' src='$ico_url' alt='" .
						addslashes( $smile ) . "' $dimensions $ico_tt />&nbsp;";
				$out .= "</div>\n";
			} else // output as a table.
			{
				$out .= '<td class="wpml_ico_icon" id="icodiv-' . $uid . '-' . $res->tid . '" onclick="smile2comment(\'' .
				$av['commenttextid'] . '\',\'' . addslashes( $smile ) . '\',' . $repl . ',\'icodiv-' . $uid . '-' . $res->tid . '\');">' . "\n";
				$out .= "<img class='wpml_ico' " .
						" id='icoimg" . $uid . '-' . $res->tid . "' src='$ico_url' alt='" .
				addslashes( $smile ) . "' $dimensions $ico_tt />&nbsp;";
				$out .= "</td>\n";
			}
		}

		// icon als bild und text ausgeben.
		if ( 2 == $av['showicon'] ) {
			$out .= '<div class="wpml_ico_both" onclick="smile2comment(\'' .
					$av['commenttextid'] . '\',\'' . addslashes( $smile ) . '\',' . $repl . ',\'icodiv-' . $uid . '-' . $res->tid . '\');">' . "\n";

			$out .= "<div class='wpml_ico_both_im'><img class='wpml_ico' name='icoimg" . $res->tid .
			"' id='icoimg" . $res->tid . "' src='$ico_url' alt='" . addslashes( $smile ) . "' $dimensions $ico_tt /></div>&nbsp;";
			$out .= "<div class='wpml_ico_both_tt'>" . $res->emoticon . '</div>';
			$out .= "</div>\n";
		}

		// image dem loader hinzufügen.
		$loader .= "wpml_imglist[$sm_count]='$ico_url';\n";

		// inc smiley count.
		$sm_count++;

		// prüfe ob eine zeile fertig ist.
		if ( ( $sm_count > 0 &&
				0 == $sm_count % $av['smiliesperrow'] ) &&
				1 == $av['showastable'] &&
				1 == $av['showicon']
		) {
			$out .= '</tr>';
		}

		if ( 1 == $av['showaspulldown'] && $av['smilies1strow'] == $sm_count ) {
			$out1strow = $out;
		}
	} // ende foreach.

	if ( 1 == $av['showastable'] && 1 == $av['showicon'] ) {
		$out .= '</table>';
		$out1strow .= '</table>';
	}

	if ( 1 == $av['showaspulldown'] ) {
		$out .= "<div class='wpml_nav' id='buttonl-$uid' onclick='wpml_toggle_smilies(\"$uid\");'>" . __( 'less...', 'wp-monalisa' ) . '</div>';
		$out1strow .= "<div class='wpml_nav' id='buttonm-$uid' onclick='wpml_more_smilies(\"$uid\");wpml_toggle_smilies(\"$uid\");'>" . __( 'more...', 'wp-monalisa' ) . '</div>';
	}

	$out .= "</div>\n";
	$out1strow .= "</div>\n";
	$out .= '<div style="clear:both;">&nbsp;</div>';
	$out1strow .= '<div style="clear:both;">&nbsp;</div>' . "\n";
	// ids tauschen um eindeutigkeit zu gewaehrleisten, da es sonst zu xhtml fehlern kommt.
	$out1strow = str_replace( 'icoimg', 'hicoimg', $out1strow );
	$out1strow = str_replace( 'icodiv-', 'icodiv1-', $out1strow );

	$loaderout = addslashes( str_replace( array( "\n", "\r" ), '', $out ) );

	// die Liste mit den images wird nur beim ersten Mal ausgegeben.
	if ( $wpml_first_preload ) {
		$wpml_first_preload = false;
	} else {
		$loader = '';
	}

	$loader .= "if (typeof wpml_more_html == 'undefined' || !(wpml_more_html instanceof Array)) var wpml_more_html = new Array();\n wpml_more_html['$uid']=\"$loaderout\";\n";
	// $loader  = "<script type='text/javascript'>\nvar wpml_imglist = new Array();\nvar wpml_more_html = new Array();\n$loader\n</script>\n";
	$loader  = "<script type='text/javascript'>\nvar wpml_imglist = new Array();\n$loader\n</script>\n";

	$erg = '';
	if ( 1 != $av['showaspulldown'] ) {
		$erg = $out;
	} else {
		// nur erste zeile ausgeben.
		$erg = "\n$loader\n<div id='smiley1-$uid' >$out1strow</div>\n<div id='smiley2-$uid' style='display:none;'>&nbsp;</div>";
	}

	if ( isset( $av['wpmlpopup'] ) && $av['wpmlpopup'] ) {
		$erg = "<div id='smiley-popup' class='smiley-popup' style='display:none;'>$erg <div style='text-align:center;' onclick='wpml_popup_toggle(\"smiley-popup\");'>" . __( 'Close', 'wp-monalisa' ) . '</div></div>';
		$erg = __( 'Click to smile:', 'wp-monalisa' ) . '<img src="' . site_url( $av['icondir'] ) . '/wpml_smile.gif" onclick="wpml_popup_toggle(\'smiley-popup\');" />' . "$erg\n";
	}

	return $erg;
}

