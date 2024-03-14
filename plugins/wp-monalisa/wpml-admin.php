<?php
/** This file is part of the wp-monalisa plugin for wordpress
 *
 *  Copyright 2009-2024 Hans Matzen  (email : webmaster at tuxlog.de)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package wp-monalisa
 */

// include functions.
require_once( 'wpml-func.php' );
require_once( 'wpml-edit.php' );
require_once( 'wpml-export.php' );
require_once( 'wpml-import.php' );

/**
 * Add menuitem for options menu.
 */
function wpml_admin_init() {
	if ( function_exists( 'add_options_page' ) ) {
		$pagejs = add_menu_page(
			'wp-Monalisa',
			'wp-Monalisa',
			'manage_options',
			basename( __FILE__ ),
			'wpml_admin',
			site_url( '/wp-content/plugins/wp-monalisa' ) . '/smiley.png'
		);
		add_action( 'load-' . $pagejs, 'wp_monalisa_contextual_help' );
		add_action( 'admin_print_styles-' . $pagejs, 'wpml_add_adminjs' );
	}

	// add thickbox and jquery for import interface.
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
	add_action( 'admin_enqueue_scripts', 'wpml_editor_scripts' );
	wp_enqueue_script( 'wpm_import', plugins_url( 'wpml-import.js', __FILE__ ), array( 'jquery' ), '9999' );
}

/**
 * Adds the editor javascript, called.
 *
 * @param string $pagehook The page filename to hook into.
 */
function wpml_editor_scripts( $pagehook ) {
	if ( 'post.php' == $pagehook || 'post-new.php' == $pagehook ) {
		wp_enqueue_script( 'wpml_script', '/' . PLUGINDIR . '/wp-monalisa/wpml_script.js', array( 'jquery' ), '9999' );
	}
}

/**
 * Adds the admin javascript, called.
 */
function wpml_add_adminjs() {
	wp_enqueue_script( 'wpml_admin', '/' . PLUGINDIR . '/wp-monalisa/wpml_admin.js', array(), '9999' );
}

/**
 * Function to show and maintain the emoticons and the options.
 */
function wpml_admin() {
	 // get sql object.
	global $wpdb;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// base url for links.
	$thisform = 'admin.php?page=wpml-admin.php';

	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );
	$av['wpml-linesperpage'] = get_option( 'wpml-linesperpage' );

	// sets the width and height of icons where width or height = 0 from iconfile using getimagesize.
	set_dimensions( $av );
	//
	// post operationen.
	//
	//
	// allgemeine optionen updaten.
	//
	if ( isset( $_POST['action'] ) && 'editopts' == $_POST['action'] ) {
		// check nonce.
		if ( ! isset( $_POST['wpm_nonce'] ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpm_nonce'] ) ), 'wpm_nonce' ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}

		// nonce okay, lets go.
		$av['onedit']           = ( isset( $_POST['onedit'] ) ? intval( $_POST['onedit'] ) : 0 );
		$av['oncomment']        = ( isset( $_POST['oncomment'] ) ? intval( $_POST['oncomment'] ) : 0 );
		$av['showicon']         = ( isset( $_POST['showicon'] ) ? intval( $_POST['showicon'] ) : 0 );
		$av['replaceicon']      = ( isset( $_POST['replaceicon'] ) ? sanitize_text_field( wp_unslash( $_POST['replaceicon'] ) ) : '0' );
		$av['showastable']      = ( isset( $_POST['showastable'] ) ? sanitize_text_field( wp_unslash( $_POST['showastable'] ) ) : '0' );
		$av['smiliesperrow']    = ( isset( $_POST['smiliesperrow'] ) ? intval( $_POST['smiliesperrow'] ) : 0 );
		$av['showaspulldown']   = ( isset( $_POST['showaspulldown'] ) ? intval( $_POST['showaspulldown'] ) : 0 );
		$av['smilies1strow']    = ( isset( $_POST['smilies1strow'] ) ? intval( $_POST['smilies1strow'] ) : 0 );
		$av['icontooltip']      = ( isset( $_POST['icontooltip'] ) ? intval( $_POST['icontooltip'] ) : 0 );
		$av['wpml4buddypress']  = ( isset( $_POST['wpml4buddypress'] ) ? intval( $_POST['wpml4buddypress'] ) : '0' );
		$av['wpml4bbpress']     = ( isset( $_POST['wpml4bbpress'] ) ? intval( $_POST['wpml4bbpress'] ) : '0' );
		$av['wpml4wpforo']      = ( isset( $_POST['wpml4wpforo'] ) ? intval( $_POST['wpml4wpforo'] ) : '0' );
		$av['wpmlmaxwidth']     = ( isset( $_POST['wpmlmaxwidth'] ) ? intval( $_POST['wpmlmaxwidth'] ) : '0' );
		$av['wpmlmaxheight']    = ( isset( $_POST['wpmlmaxheight'] ) ? intval( $_POST['wpmlmaxheight'] ) : '0' );
		$av['wpmlpopup']        = ( isset( $_POST['wpmlpopup'] ) ? intval( $_POST['wpmlpopup'] ) : '0' );
		$av['wpmlscript2footer'] = ( isset( $_POST['wpmlscript2footer'] ) ? intval( $_POST['wpmlscript2footer'] ) : '0' );
		$av['wpmlshowbetween']  = ( isset( $_POST['wpmlshowbetween'] ) ? intval( $_POST['wpmlshowbetween'] ) : '0' );
		$av['richeditor']       = ( isset( $_POST['richeditor'] ) ? intval( $_POST['richeditor'] ) : '0' );

		if ( isset( $_POST['commenttextid'] ) && '' == $_POST['commenttextid'] ) {
			$av['commenttextid'] = 'comment';
		} else {
			$av['commenttextid'] = sanitize_text_field( wp_unslash( $_POST['commenttextid'] ) );
		}

		if ( isset( $_POST['iconpath'] ) && is_dir( ABSPATH . sanitize_text_field( wp_unslash( $_POST['iconpath'] ) ) ) ) {
			$av['icondir']     = sanitize_text_field( wp_unslash( $_POST['iconpath'] ) );
		} else {
			admin_message( __( 'Iconpath is no valid directory, resetting it. Please enter the path relative to the wordpress main directory.', 'wp-monalisa' ) );
		}

		update_option( 'wpml-opts', serialize( $av ) );
		admin_message( __( 'Settings saved', 'wp-monalisa' ) );
	}

	//
	// es sollen datensätze bearbeitet werden werden.
	//
	if ( isset( $_POST['action'] ) && 'editicons' == $_POST['action'] && isset( $_POST['baction'] ) ) {

		// hoechste id ermitteln.
		$maxnum = $wpdb->get_var( $wpdb->prepare( 'select max(tid) from %i;', $wpml_table ) );

		// in Posts aktivieren.
		if ( isset( $_POST['bulkaction'] ) && 'setPon' == $_POST['bulkaction'] ) {
			for ( $i = 1; $i <= $maxnum; $i++ ) {
				// nur fuer gefüllte felder updaten.
				if ( ! isset( $_POST[ 'mark' . $i ] ) ) {
					continue;
				}

				if ( intval( $_POST[ 'mark' . $i ] ) == $i ) {
					$result = $wpdb->query( 'update %i set onpost=1 where tid=%d;', $wpml_table, $i );
				}
			}
			admin_message( __( 'Show in comments set to on', 'wp-monalisa' ) );
		}

		// in Posts deaktivieren.
		if ( 'setPoff' == $_POST['bulkaction'] ) {
			for ( $i = 1; $i <= $maxnum; $i++ ) {
				// nur fuer gefüllte felder updaten.
				if ( ! isset( $_POST[ 'mark' . $i ] ) ) {
					continue;
				}

				if ( intval( $_POST[ 'mark' . $i ] ) == $i ) {
					$result = $wpdb->query( $wpdb->prepare( 'update %i set onpost=0 where tid=%d;', $wpml_table, $i ) );
				}
			}
			admin_message( __( 'Show in posts set to off', 'wp-monalisa' ) );
		}

		// in Kommentaren aktivieren.
		if ( 'setCon' == $_POST['bulkaction'] ) {
			for ( $i = 1; $i <= $maxnum; $i++ ) {
				// nur fuer gefüllte felder updaten.
				if ( ! isset( $_POST[ 'mark' . $i ] ) ) {
					continue;
				}

				if ( intval( $_POST[ 'mark' . $i ] ) == $i ) {
					$result = $wpdb->query( $wpdb->prepare( 'update %i set oncomment=1 where tid=%d;', $wpml_table, $i ) );
				}
			}
			admin_message( __( 'Show in comments set to on', 'wp-monalisa' ) );
		}

		// in Kommentaren deaktivieren.
		if ( 'setCoff' == $_POST['bulkaction'] ) {
			for ( $i = 1; $i <= $maxnum; $i++ ) {
				// nur fuer gefüllte felder updaten.
				if ( ! isset( $_POST[ 'mark' . $i ] ) ) {
					continue;
				}

				if ( intval( $_POST[ 'mark' . $i ] ) == $i ) {
					$result = $wpdb->query( $wpdb->prepare( 'update %i set oncomment=0 where tid=%d;', $wpml_table, $i ) );
				}
			}
			admin_message( __( 'Show in comments set to off', 'wp-monalisa' ) );
		}

		// sätze löschen.
		if ( 'delete' == $_POST['bulkaction'] ) {
			for ( $i = 1; $i <= $maxnum; $i++ ) {
				// nur fuer gefüllte felder updaten.
				if ( ! isset( $_POST[ 'mark' . $i ] ) ) {
					continue;
				}

				if ( intval( $_POST[ 'mark' . $i ] ) == $i ) {
					$result = $wpdb->query( $wpdb->prepare( 'delete from %i where tid=%d;', $wpml_table, $i ) );
				}
			}
			admin_message( __( 'Records deleted ', 'wp-monalisa' ) );
		}
	}

	//
	// icon mapping ändern oder neu anlegen.
	//
	if ( isset( $_POST['action'] ) && 'editicons' == $_POST['action'] && isset( $_POST['updateicons'] ) ) {
		// hoechste satz-id ermitteln bevor ggf. ein neuer satz hinzukommt
		// denn der neue satz darf/muss nicht upgedated werden.
		$maxnum = $wpdb->get_var( $wpdb->prepare( 'select max(tid) from %i', $wpml_table ) );

		// neuen satz anlegen.
		if ( isset( $_POST['NEWemoticon'] ) && '' != trim( sanitize_text_field( wp_unslash( $_POST['NEWemoticon'] ) ) ) ) {
			// pruefen ob bereits ein satz mit dem gleichen emoticon vorhanden ist.
			$vorhanden = $wpdb->get_var( $wpdb->prepare( 'select count(*) from %i where BINARY(emoticon)=BINARY(%s);', $wpml_table, sanitize_text_field( wp_unslash( $_POST['NEWemoticon'] ) ) ) );
			if ( $vorhanden > 0 ) {
				  admin_message( __( 'Emoticon allready used. Record not inserted', 'wp-monalisa' ) );
			} else {
				// hoehe und breite des bildes ermitteln fuer die ausgabe des img tags speichern.
				$breite = 0;
				$hoehe = 0;
				$isize = getimagesize( ABSPATH . $av['icondir'] . '/' . ( isset( $_POST['NEWicon'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['NEWicon'] ) ) ) : '' ) );
				if ( false != $isize ) {
					$breite = $isize[0];
					$hoehe = $isize[1];
				}
				// satz einfuegen.
				$result = $wpdb->query(
					$wpdb->prepare(
						'insert into %i (tid,emoticon,iconfile,onpost,oncomment,width,height) values (0, %s, %s, %d, %d, %d, %d);',
						$wpml_table,
						trim( sanitize_text_field( wp_unslash( $_POST['NEWemoticon'] ) ) ),
						sanitize_text_field( wp_unslash( $_POST['NEWicon'] ) ),
						( array_key_exists( 'NEWonpost', $_POST ) && intval( $_POST['NEWonpost'] ) == '1' ? 1 : 0 ),
                        ( array_key_exists( 'NEWoncomment', $_POST ) && intval( $_POST['NEWoncomment'] ) == '1' ? 1 : 0 ),
						$breite,
						$hoehe
					)
				);
			}
		}

		$i = 0;
		for ( $i = 1; $i <= $maxnum; $i++ ) {
			// nur fuer gefüllte felder updaten.
			if ( ! isset( $_POST[ 'emoticon' . $i ] ) ) {
				continue;
			}
			// pruefen ob bereits ein satz mit dem gleichen emoticon vorhanden ist.
			$vorhanden = 0;
			$j = 0;
			// ermittle wie oft das emoticon eingetragen wurde.
			for ( $j = 1; $j <= $maxnum; $j++ ) {
				  // nur für gefüllte felder prüfen.
				if ( ! isset( $_POST[ 'emoticon' . $j ] ) ) {
					continue;
				}

				if ( $_POST[ 'emoticon' . $j ] == $_POST[ 'emoticon' . $i ] ) {
					++$vorhanden;
				}
			}

			// wenn öfter als einmal, erfolgt kein update.
			if ( $vorhanden > 1 ) {
				admin_message( __( 'Emoticon allready used. Record not updated', 'wp-monalisa' ) );
			} else {
				// datensätze updaten
				// durch das where tid=$i werden nur vorhandene sätze upgedated
				// exitiert kein satz mit tid=$i wird auch kein satz gefunden.

				// hoehe und breite des bildes ermitteln fuer die ausgabe des img tags speichern.
				$breite = 0;
				$hoehe = 0;
				$fname = ABSPATH . $av['icondir'] . '/' .
					( isset( $_POST[ 'icon' . $i ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ 'icon' . $i ] ) ) ) : '' );
				$fext = substr( $fname, strlen( $fname ) - 3 );

				if ( 'svg' == $fext ) {
					$xml = simplexml_load_file( $fname );
					if ( false !== $xml ) {
						$isize = array( 24, 24 );
						$attr = $xml->attributes();
						// can be removed as soon as WP coding standards are supporting php xml parser vars
						// @codingStandardsIgnoreStart
						$viewbox = explode( ' ', $attr->viewBox );
						// @codingStandardsIgnoreEnd
						$isize[0] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[2] : null );
						$isize[1] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[3] : null );
					}
				} else {
					$isize = getimagesize( $fname );
				}

				if ( false != $isize ) {
					$breite = $isize[0];
					$hoehe = $isize[1];
				}

				$result = $wpdb->query(
					$wpdb->prepare(
						'update %i set emoticon=%s, iconfile =%s, onpost=%d, oncomment=%d, width=%d, height=%d where tid=%d;',
						$wpml_table,
						trim( sanitize_text_field( wp_unslash( $_POST[ 'emoticon' . $i ] ) ) ),
						sanitize_text_field( wp_unslash( $_POST[ 'icon' . $i ] ) ),
						( isset( $_POST[ 'onpost' . $i ] ) && intval( $_POST[ 'onpost' . $i ] ) == '1' ? 1 : 0 ),
						( isset( $_POST[ 'oncomment' . $i ] ) && intval( $_POST[ 'oncomment' . $i ] ) == '1' ? 1 : 0 ),
						$breite,
						$hoehe,
						$i
					)
				);
			}
		}
		admin_message( __( 'Records updated', 'wp-monalisa' ) );

	}

	//
	// formular aufbauen ===================================================
	// .
	$out = '';

	$out .= '<div class="wrap"><h2>wp-Monalisa ' . __( 'Settings', 'wp-monalisa' ) . '</h2>';

	// add support link.
	require_once( plugin_dir_path( __FILE__ ) . '/supp/supp.php' );
	$out .= tl_add_supp();

	$out .= '<div id="ajax-response"></div>' . "\n";

	$out .= '<form name="editopts" id="editopts" method="post" action="#">';
	$out .= '<input name="wpm_nonce" id="wpm_nonce" type="hidden" value="' . esc_attr( wp_create_nonce( 'wpm_nonce' ) ) . '" />';

	$out .= '<input type="hidden" name="action" value="editopts" />';

	$out .= '<table class="editform">';
	$out .= '<tr><th scope="row" ><label for="iconpath">' . __( 'Iconpath', 'wp-monalisa' ) . ':</label></th>' . "\n";

	// icon verzeichnis.
	$out .= '<td colspan="3"><input name="iconpath" id="iconpath" type="text" value="' . $av['icondir'] . '" size="70" onchange="alert(\'' . __( 'You are about to change the iconpath.\n Please be careful and make sure the icons are still accessible.\n To update your settings klick Save Settings', 'wp-monalisa' ) . '\');" /></td></tr>' . "\n";

	// anzeige der smilies im editor.
	$out .= '<tr><th scope="row" ><label for="onedit">' . __( 'Show smilies on edit', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="onedit" id="onedit" type="checkbox" value="1" ' . ( '1' == $av['onedit'] ? 'checked="checked"' : '' ) . ' /></td>' . "\n";

	$out .= '<th scope="row" ><label for="richeditor">' . __( 'Show smilies in Rich-Editor', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="richeditor" id="richeditor" type="checkbox" value="1" ' . ( '1' == $av['richeditor'] ? 'checked="checked"' : '' ) . ' /></td></tr>' . "\n";

	// anzeige der smilies für kommentare.
	$out .= '<tr><th scope="row" ><label for="oncomment">' . __( 'Show smilies on comment', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="oncomment" id="oncomment" type="checkbox" value="1" ' . ( '1' == $av['oncomment'] ? 'checked="checked"' : '' ) . '/></td>' . "\n";

	// kommentar textarea id.
	$out .= '<th scope="row" ><label for="commenttextid">' . __( 'Comment Textarea ID', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="commenttextid" id="commenttextid" type="text" value="' . $av['commenttextid'] . '" size="20" onchange="alert(\'' . __( 'You are about to change the id of the textarea of your comment form.\n Please make sure you enter the correct id, to make wp-monalisa work correctly', 'wp-monalisa' ) . '\');" /></td></tr>' . "\n";

	$out .= '<tr><th scope="row" ><label for="replaceicon">' . __( 'Replace emoticons with html-images', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="replaceicon" id="replaceicon" type="checkbox" value="1" ' . ( '1' == $av['replaceicon'] ? 'checked="checked"' : '' ) . ' /></td>' . "\n";

	$out .= '<th scope="row" ><label for="showicon">' . __( 'Show emoticons in selection as', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><select name="showicon" id="showicon" onchange="wpml_admin_switch();" >' . "\n";
	$out .= '<option value="1" ' . ( '1' == $av['showicon'] ? 'selected="selected"' : '' ) . '>' . __( 'Icon', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="0" ' . ( '0' == $av['showicon'] ? 'selected="selected"' : '' ) . '>' . __( 'Text', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="2" ' . ( '2' == $av['showicon'] ? 'selected="selected"' : '' ) . '>' . __( 'Both', 'wp-monalisa' ) . '</option>';
	$out .= "</select></td></tr>\n";

	// smilies als tabelle anzeigen.
	// smiley tabelle.
	$out .= '<tr><th scope="row" ><label for="showastable">' . __( 'Show smilies in a table', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="showastable" id="showastable" type="checkbox" value="1" ' . ( '1' == $av['showastable'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
	$out .= '<th scope="row" ><label for="smiliesperrow">' . __( 'Smilies per row', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="smiliesperrow" id="smiliesperrow" type="text" value="' .
	  $av['smiliesperrow'] . '" size="3" maxlength="3" /></td>' . "\n";
	$out .= "</tr>\n";

	// smilies zum aufklappen.
	// smiley pull-down.
	$out .= '<tr><th scope="row" ><label for="showaspulldown">' . __( 'Show smilies as Pulldown', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="showaspulldown" id="showaspulldown" type="checkbox" value="1" ' . ( '1' == $av['showaspulldown'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
	$out .= '<th scope="row" ><label for="smilies1strow">' . __( 'Smilies in 1st row', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="smilies1strow" id="smilies1strow" type="text" value="' .
	  $av['smilies1strow'] . '" size="3" maxlength="3" /></td>' . "\n";
	$out .= "</tr>\n";

	// smilies maximale groesse.
	$out .= '<tr><th scope="row" ><label for="wpmlmaxwidth">' . __( 'Maximal width for smiley (px)', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="wpmlmaxwidth" id="wpmlmaxwidth" type="text" value="' . $av['wpmlmaxwidth'] . '" size="3" maxlength="3" /></td>' . "\n";
	$out .= '<th scope="row" ><label for="wpmlmaxheight">' . __( 'Maximal height for smiley (px)', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="wpmlmaxheight" id="wpmlmaxheight" type="text" value="' . $av['wpmlmaxheight'] . '" size="3" maxlength="3" /></td>' . "\n";
	$out .= "</tr>\n";

	// tooltips fuer icons anzeigen.
	// und overlay popup fuer icons anzeigen.
	$out .= '<tr><th scope="row" ><label for="icontooltip">' . __( 'Show tooltip for icons', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="icontooltip" id="icontooltip" type="checkbox" value="1" ' . ( '1' == $av['icontooltip'] ? 'checked="checked"' : '' ) . ' /></td>' . "\n";
	$out .= '<th scope="row" ><label for="wpmlpopup">' . __( 'Show icons as popup', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="wpmlpopup" id="wpmlpopup" type="checkbox" value="1" ' . ( '1' == $av['wpmlpopup'] ? 'checked="checked"' : '' ) . ' /></td>' . "\n";
	$out .= "</tr>\n";

	if ( defined( 'BP_VERSION' ) ) {
		// buddypress unterstützung.
		$out .= '<tr><th scope="row" ><label for="wpml4buddypress">' . __( 'Activate Smilies for BuddyPress', 'wp-monalisa' ) . ':</label></th>' . "\n";
		$out .= '<td><input name="wpml4buddypress" id="wpml4buddypress" type="checkbox" value="1" ' . ( '1' == $av['wpml4buddypress'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
		$out .= '<th scope="row" >&nbsp;</th>' . "\n";
		$out .= '<td>&nbsp;</td>' . "\n";
		$out .= "</tr>\n";
	}

	if ( class_exists( 'bbPress' ) ) {
		// bbpress unterstützung.
		$out .= '<tr><th scope="row" ><label for="wpml4bbpress">' . __( 'Activate Smilies for bbPress', 'wp-monalisa' ) . ':</label></th>' . "\n";
		$out .= '<td><input name="wpml4bbpress" id="wpml4bbpress" type="checkbox" value="1" ' . ( '1' == $av['wpml4bbpress'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
		$out .= '<th scope="row" >&nbsp;</th>' . "\n";
		$out .= '<td>&nbsp;</td>' . "\n";
		$out .= "</tr>\n";
	}

	if ( defined( 'WPFORO_VERSION' ) ) {
		// wpforo unterstützung.
		$out .= '<tr><th scope="row" ><label for="wpml4wpforo">' . __( 'Activate Smilies for wpForo', 'wp-monalisa' ) . ':</label></th>' . "\n";
		$out .= '<td><input name="wpml4wpforo" id="wpml4wpforo" type="checkbox" value="1" ' . ( '1' == $av['wpml4wpforo'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
		$out .= '<th scope="row" >&nbsp;</th>' . "\n";
		$out .= '<td>&nbsp;</td>' . "\n";
		$out .= "</tr>\n";
	}

	// script to footer.
	$out .= '<tr><th scope="row" ><label for="wpmlscript2footer">' . __( 'Load javascript in page footer', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="wpmlscript2footer" id="wpmlscript2footer" type="checkbox" value="1" ' . ( '1' == $av['wpmlscript2footer'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
	$out .= '<th scope="row" ><label for="wpmlshowbetween">' . __( 'Show Smilies before Submit Button', 'wp-monalisa' ) . ':</label></th>' . "\n";
	$out .= '<td><input name="wpmlshowbetween" id="wpmlshowbetween" type="checkbox" value="1" ' . ( '1' == $av['wpmlshowbetween'] ? 'checked="checked"' : '' ) . ' onchange="wpml_admin_switch();" /></td>' . "\n";
	$out .= "</tr>\n";

	$out .= '</table>' . "\n";
	$out .= '<script  type="text/javascript">wpml_admin_switch();</script>';

	// add submit button to form.
	$out .= '<p class="submit"><input type="submit" name="updateopts" value="' . __( 'Save Settings', 'wp-monalisa' ) . ' &raquo;" /></p></form>' . "\n";

	// add link to import/export interface.
	$out .= '<div style="text-align:right;padding-bottom:10px;">';
	$out .= '<a class="button-secondary thickbox" href="#TB_inline?height=600&amp;width=400&inlineId=wpml_import" class="thickbox" >' . __( 'Import Smiley-Package', 'wp-monalisa' ) . '</a>&nbsp;&nbsp;&nbsp;' . "\n";
	$out .= '<a class="button-secondary thickbox" href="#TB_inline?height=1000&width=540&inlineId=wpml_export" class="thickbox" >' . __( 'Export Smiley-Package (pak-Format)', 'wp-monalisa' ) . '</a></div>' . "\n";
	$out .= "</div><hr />\n";

	echo wp_kses( $out, wpml_allowed_tags() );

	// output icon table.

	// icon file list on disk.
	$flist = scandir( ABSPATH . $av['icondir'] );

	$out = '';
	$out .= '<div class="wrap">';
	$out .= '<h2>' . __( 'Smilies', 'wp-monalisa' ) . "</h2>\n";

	if ( empty( $flist ) ) {
		admin_message( __( 'Iconpath is empty or invalid', 'wp-monalisa' ) );
	}

	// navigation leiste
	// anzahl der smilies holen.
	$res = $wpdb->get_row( $wpdb->prepare( 'select count(*) as anz from %i;', $wpml_table ) );
	$all_lines = $res->anz;

	// aufgerufene seite auslesen.
	if ( isset( $_GET['activepage'] ) ) {
		$active_page = (int) $_GET['activepage'];
	} else {
		$active_page = 1;
	}

	// zeilen pro seite aus dem formular holen aber nur das geänderte feld.
	$lines_per_page = $av['wpml-linesperpage'];
	if ( isset( $_POST['set_lines_per_page1_x'] ) ||
	   isset( $_POST['set_lines_per_page2_x'] ) ||
	   isset( $_POST['updateicons'] ) ) {
		if ( isset( $_POST['lines_per_page1'] ) && isset( $_POST['lines_per_page2'] ) ) {
			if ( $av['wpml-linesperpage'] == $_POST['lines_per_page1'] ) {
				$lines_per_page = (int) $_POST['lines_per_page2'];
			} else {
				$lines_per_page = (int) $_POST['lines_per_page1'];
			}
			$av['wpml-linesperpage'] = $lines_per_page;
			update_option( 'wpml-linesperpage', $lines_per_page );
			// wenn die anzahl der zeilen veraendert wurde auf erste seite springen.
			$active_page = 1;
		}
	}

	// just in case option is not yet set.
	if ( ! $lines_per_page > 0 ) {
		$lines_per_page = 10;
	}

	$maxpage = ( $all_lines / $lines_per_page );
	if ( $all_lines % $lines_per_page > 0 ) {
		++$maxpage;
	}

	// icons.
	$out .= '<form name="editicons" id="editicons" method="post" action="#">';
	$out .= '<input type="hidden" name="action" value="editicons" />';

	// submit knöpfe ausgeben.
	$out .= '<div class="tablenav">';
	$out .= '<input type="submit" name="updateicons" value="' . __( 'Save', 'wp-monalisa' ) . ' &raquo;" class="button-secondary" />&nbsp;&nbsp;&nbsp;';
	$out .= '<select size="1" style="vertical-align:top;" name="bulkaction" id="bulkaction">';
	$out .= '<option value="none">' . __( 'Bulk Actions', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="setPon">' . __( 'Set in Post - on', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="setPoff">' . __( 'Set in Post - off', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="setCon">' . __( 'Set in Comments - on', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="setCoff">' . __( 'Set in Comments - off', 'wp-monalisa' ) . '</option>';
	$out .= '<option value="delete">' . __( 'Delete', 'wp-monalisa' ) . '</option>';
	$out .= '</select>&nbsp;';
	$out .= '<input type="submit" name="baction" value="' . __( 'Apply', 'wp-monalisa' ) . ' &raquo;" class="button-secondary" />' . "\n";

	// seitennaviagtion ausgeben.
	$out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$out .= "<a href=\"$thisform&amp;activepage=0\">" . __( 'Show all', 'wp-monalisa' ) . '</a>&nbsp;&nbsp;';
	$out .= "<a href=\"$thisform&amp;activepage=" . (string) ( $active_page - 1 < 1 ? 1 : $active_page - 1 ) . '">&lt;</a>&nbsp;';
	for ( $i = 1;$i < ( $all_lines / $lines_per_page ) + 1;$i++ ) {
		if ( $active_page == $i ) {
			$out .= '<b>' . $i . '</b>&nbsp;';
		} else {
			$out .= "<a href=\"$thisform&amp;activepage=$i\">" . $i . '</a>&nbsp;';
		}
	}
	$out .= "<a href=\"$thisform&amp;activepage=" . (string) ( $active_page + 1 > $maxpage ? $active_page : $active_page + 1 ) . '">&gt;</a>&nbsp;&nbsp;';
	$out .= __( 'Lines per Page:', 'wp-monalisa' ) . "<input style=\"font-size:10px\" type='text' name='lines_per_page1' value='" . $lines_per_page . "' size='4' />";
	$naviconfile = site_url( PLUGINDIR . '/wp-monalisa/yes.png' );
	$out .= '<input type="image" name="set_lines_per_page1" src="' . $naviconfile . '" alt="' . __( 'Save', 'wp-monalisa' ) . '" /></div>';

	// icon zeilen ausgeben.
	$out .= "<table class=\"widefat\">\n";
	$out .= "<thead><tr>\n";
	$out .= '<th scope="col" style="text-align:center"><input style="margin-left: 0;" id="markall" type="checkbox" onchange="wpml_markall(\'markall\');" />&nbsp;</th>' . "\n";
	$out .= '<th scope="col">' . __( 'Emoticon', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col" colspan="2" style="text-align: left">' . __( 'Icon', 'wp-monalisa' ) . '<br />(* ' . __( 'not mapped yet', 'wp-monalisa' ) . ')</th>' . "\n";
	$out .= '<th scope="col">' . __( 'On Post', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col">' . __( 'On Comment', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col">&nbsp;</th>' . "\n";
	$out .= '<th scope="col">&nbsp;</th>' . "\n";
	$out .= '</tr></thead>' . "\n";

	// tabellenfuss.
	$out .= "<tfoot><tr>\n";
	$out .= '<th scope="col" style="text-align:center"><input style="margin-left: 0;" id="markall1" type="checkbox" onchange="wpml_markall(\'markall1\');" />&nbsp;</th>' . "\n";
	$out .= '<th scope="col">' . __( 'Emoticon', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col" colspan="2" style="text-align: left">' . __( 'Icon', 'wp-monalisa' ) . '<br />(* ' . __( 'not mapped yet', 'wp-monalisa' ) . ')</th>' . "\n";
	$out .= '<th scope="col">' . __( 'On Post', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col">' . __( 'On Comment', 'wp-monalisa' ) . '</th>' . "\n";
	$out .= '<th scope="col">&nbsp;</th>' . "\n";
	$out .= '<th scope="col">&nbsp;</th>' . "\n";
	$out .= '</tr></tfoot>' . "\n";

	// tabellenbody
	// zeile fuer neueintrag.
	$out .= '<tr><td class="td-center"><b>' . __( 'New Entry', 'wp-monalisa' ) . ':</b></td>';
	$out .= '<td><input name="NEWemoticon" id="NEWemoticon" type="text" value="" size="15" maxlength="25" /></td>' . "\n";
	$out .= '<td>';
	$out .= '<select name="NEWicon" id="NEWicon" onchange="updateImage(\'' . site_url( $av['icondir'] ) . '\',\'NEW\')">' . "\n";
	// build select html for iconfile.
	$icon_select_html = '';
	// fetch compare list to sign unused files.
	$clist = array();
	$notused = '';
	$results = $wpdb->get_results( $wpdb->prepare( 'select iconfile from %i;', $wpml_table ) );
	foreach ( $results as $i ) {
		array_push( $clist, $i->iconfile );
	}

	// file loop.
	foreach ( $flist as $iconfile ) {
		if ( in_array( $iconfile, $clist ) ) {
			$notused = '';
		} else {
			$notused = '*';
		}
		$ext = substr( $iconfile, strlen( $iconfile ) - 3, 3 );
		if ( 'gif' == $ext || 'png' == $ext || 'svg' == $ext ) {
			$icon_select_html .= "<option value='" . $iconfile . "' ";
			$icon_select_html .= '>' . $iconfile . $notused . "</option>\n";
		}
	}
	$out .= $icon_select_html . "</select></td>\n";
	$out .= '<td><img class="wpml_ico wpml_ico_admin" id="icoimg" src="' .
	  site_url( $av['icondir'] ) . '/wpml_smile.gif" alt="wp-monalisa icon"/></td>';
	$out .= '<td><input name="NEWonpost" id="NEWonpost" type="checkbox" value="1" /></td>' . "\n";
	$out .= '<td><input name="NEWoncomment" id="NEWoncomment" type="checkbox" value="1" />' . "\n";
	$out .= '<script type="text/javascript">updateImage("' . site_url( $av['icondir'] ) . '","NEW")</script></td>';
	$out .= "<td colspan='2'>&nbsp;</td></tr>\n";

	// jetzt kommen die vorhandenen eintraege.
	// select all icon entries.

	// die satzgrenzen (erster/letzter)  fuer den select ermitteln.
	if ( $active_page > 0 ) {
		$lstart = ( $active_page - 1 ) * $lines_per_page;
		$lcount = $lines_per_page;
		$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile,onpost,oncomment from %i order by tid limit %d, %d;', $wpml_table, $lstart, $lcount ) );
	} else {
		$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile,onpost,oncomment from %i order by tid;', $wpml_table ) );
	}

	// zaehler um ersten und letzten zu erkennen.
	$lastnum = count( $results ) - 1;
	$count   = 0;
	$tid = 0;
	$alternate = false;
	// icon loop.
	foreach ( $results as $res ) {
		// build select html for iconfile.
		$icon_select_html = '';
		// file loop.
		foreach ( $flist as $iconfile ) {
			$ext = substr( $iconfile, strlen( $iconfile ) - 3, 3 );
			if ( 'gif' == $ext || 'png' == $ext || 'svg' == $ext ) {
				  $icon_select_html .= "<option value='" . $iconfile . "' ";
				if ( $iconfile == $res->iconfile ) {
					$icon_select_html .= 'selected="selected"';
				}
				$icon_select_html .= '>' . $iconfile . "</option>\n";
			}
		}

		$tid = $res->tid;
		// hintegrund farbe für jede zweite zeile.
		if ( $alternate ) {
			$out .= '<tr class="alternate">';
		} else {
			$out .= '<tr>';
		}
		$alternate = ! $alternate;
		$out .= '<td class="td-center"><input class="wpml_mark" name="mark' . $tid . '" id="mark' . $tid . '" type="checkbox" value="' . $tid . '" />&nbsp;</td>';
		$out .= '<td><input name="emoticon' . $tid . '" id="emoticon' . $tid . '" type="text" value="' . $res->emoticon . '" size="15" maxlength="25" /></td>' . "\n";

		$out .= '<td>';
		$out .= '<select name="icon' . $tid . '" id="icon' . $tid .
		'" onchange="updateImage(\'' . site_url( $av['icondir'] ) . "'," . $tid . ')">' . "\n";
		$out .= $icon_select_html . "</select></td>\n";
		$out .= '<td><img class="wpml_ico wpml_ico_admin" id="icoimg' . $tid . '" src="' .
		site_url( $av['icondir'] ) . '/wpml_smile.gif" alt="wp-monalisa icon" />';
		$out .= '<script type="text/javascript">updateImage("' . site_url( $av['icondir'] ) . '","' . $tid . '")</script></td>';

		$out .= '<td><input name="onpost' . $tid . '" id="onpost' . $tid . '" type="checkbox" value="1" ' . ( '1' == $res->onpost ? 'checked="checked"' : '' ) . ' /></td>' . "\n";
		$out .= '<td><input name="oncomment' . $tid . '" id="oncomment' . $tid . '" type="checkbox" value="1" ' . ( '1' == $res->oncomment ? 'checked="checked"' : '' ) . ' /></td>' . "\n";
		// add position buttons.
		if ( 0 != $count ) {
			$out .= '<td><img width="20" src="' . plugins_url() . '/wp-monalisa/up.png" onclick="switch_row(' . $tid . ',\'up\');" alt="down arrow"/></td>';
		} else {
			$out .= '<td>&nbsp;</td>';
		}
		if ( $count != $lastnum ) {
			$out .= '<td><img width="20" src="' . plugins_url() . '/wp-monalisa/down.png" onclick="switch_row(' . $tid . ',\'down\');" alt="up arrow"/></td>';
		} else {
			$out .= '<td>&nbsp;</td>';
		}
		$out .= "</tr>\n";
		$count ++; // zaehler erhöhen.
	}

	$out .= '</table>';

	// submit knöpfe ausgeben.
	$out .= '<div class="tablenav"><input type="submit" name="updateicons" value="' . __( 'Save', 'wp-monalisa' ) . ' &raquo;" class="button-secondary" />' . "\n";

	// seitennaviagtion ausgeben.
	$out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$out .= "<a href=\"$thisform&amp;activepage=0\">" . __( 'Show all', 'wp-monalisa' ) . '</a>&nbsp;&nbsp;';
	$out .= "<a href=\"$thisform&amp;activepage=" . (string) ( $active_page - 1 < 1 ? 1 : $active_page - 1 ) . '">&lt;</a>&nbsp;';
	for ( $i = 1;$i < ( $all_lines / $lines_per_page ) + 1;$i++ ) {
		if ( $active_page == $i ) {
			$out .= '<b>' . $i . '</b>&nbsp;';
		} else {
			$out .= "<a href=\"$thisform&amp;activepage=$i\">" . $i . '</a>&nbsp;';
		}
	}
	$out .= "<a href=\"$thisform&amp;activepage=" . (string) ( $active_page + 1 > $maxpage ? $active_page : $active_page + 1 ) . '">&gt;</a>&nbsp;&nbsp;';
	$out .= __( 'Lines per Page:', 'wp-monalisa' ) . "<input style=\"font-size:10px\" type='text' name='lines_per_page2' value='" . $lines_per_page . "' size='4' />";
	$out .= '<input type="image" name="set_lines_per_page2" src="' . $naviconfile . '" alt="' . __( 'Save', 'wp-monalisa' ) . '" /></div>';

	$out .= '</form></div>' . "\n";

	// Anfang Import Dialog.
	$out .= '<div id="wpml_import" style="display:none">';
	$out .= wpml_import_form();
	$out .= '</div>';
	// Ende Import Dialog.

	// Anfang Export Dialog.
	$out .= '<div id="wpml_export" style="display:none">';
	$out .= export_all_smilies();
	$out .= '</div>';
	// Ende Export Dialog.

	echo wp_kses( $out, wpml_allowed_tags() );
}

/**
 * This function returns the contextual help.
 */
function wp_monalisa_contextual_help() {

	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'wp-monalisa', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	$contextual_help  = '<p>';
	$contextual_help .= __( 'If you are looking for instructions or help on wp-monalisa, please use the following ressources. If you are stuck you can always write an email to', 'wp-monalisa' );
	$contextual_help .= ' <a href="mailto:support@tuxlog.de">support@tuxlog.de</a>.';
	$contextual_help .= '</p>';

	$contextual_help .= '<ul>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2009/wp-monalisa-manual/" target="_blank">';
	$contextual_help .= __( 'English Manual', 'wp-monalisa' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2009/wp-monalisa-handbuch/" target="_blank">';
	$contextual_help .= __( 'German Manual', 'wp-monalisa' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2009/wp-monalisa-in-dmsguestbook-integrieren/" target="_blank">';
	$contextual_help .= __( 'Integrate wp-monalisa with dmsGuestbook (german)', 'wp-monalisa' );
	$contextual_help .= '</a></li>';

	$contextual_help .= '<li><a href="http://www.youtube.com/watch?v=5w8hiteU8gA" target="_blank">';
	$contextual_help .= __( 'Screencast wp-monalisa installation', 'wp-monalisa' );
	$contextual_help .= '</a></li>';

	$contextual_help .= '<li><a href="http://www.youtube.com/watch?v=614Gso38v5g" target="_blank">';
	$contextual_help .= __( 'Screencast wp-monalisa configuration', 'wp-monalisa' );
	$contextual_help .= '</a></li>';

	$contextual_help .= '<li><a href="http://www.youtube.com/watch?v=uHXlELn27ko" target="_blank">';
	$contextual_help .= __( 'Screencast wp-monalisa usage', 'wp-monalisa' );
	$contextual_help .= '</a></li>';

	$contextual_help .= '<li><a href="http://www.youtube.com/watch?v=cedwN0u_XRI" target="_blank">';
	$contextual_help .= __( 'Screencast wp-monalisa import/export of smilies', 'wp-monalisa' );
	$contextual_help .= '</a></li>';

	$contextual_help .= '<li><a href="http://www.wordpress.org/extend/plugins/wp-monalisa" target="_blank">';
	$contextual_help .= __( 'wp-monalisa on WordPress.org', 'wp-monalisa' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wp-monalisa/" target="_blank">';
	$contextual_help .= __( 'German wp-monalisa Site', 'wp-monalisa' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://wordpress.org/plugins/wp-monalisa/changelog/" target="_blank">';
	$contextual_help .= __( 'Changelog', 'wp-monalisa' );
	$contextual_help .= '</a></li></ul>';
	$contextual_help .= '<p>';
	$contextual_help .= __( 'Links will open in new Window/Tab.', 'wp-monalisa' );
	$contextual_help .= '</p>';

	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'    => 'wpml_admin_help_tab',
			'title' => __( 'wp-monalisa Help' ),
			'content'   => $contextual_help,
		)
	);
}


