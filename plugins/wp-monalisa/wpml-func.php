<?php
/** This file is part of the wp-monalisa plugin for wordpress
 *
 * Copyright 2009-2022  Hans Matzen  (email : webmaster at tuxlog dot de)
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

if ( ! function_exists( 'admin_message' ) ) {
	/**
	 * Function to show an admin message on an admin page.
	 *
	 * @param string $msg message to display in the admin area.
	 */
	function admin_message( $msg ) {
		echo "<div class='updated'><p><strong>";
		echo wp_kses( $msg, wpml_allowed_tags() );
		echo "</strong></p></div>\n";
	}
}

/**
 * Enqueue the wp-monalisa stylesheet individual or default
 * if no individual exists.
 */
function wpml_css() {
	$def  = 'wp-monalisa-default.css';
	$user = 'wp-monalisa.css';

	if ( file_exists( WP_PLUGIN_DIR . '/wp-monalisa/' . $user ) ) {
		$def = $user;
	}

	wp_enqueue_style( 'wp-monalisa', plugins_url( $def, __FILE__ ), array(), '9999' );
}

/**
 * The next functions are an adoption of wordpress 2.8 functions
 * to change the behaviour as wanted for wp-monalisa
 * thanks to all who worked on this.
 *
 * This functions maps the emoticons to icons
 * and stores them in an global array
 * it also prepares a global search pattern for all smilies.
 */
function wpml_map_emoticons() {
	 global $wpdb, $wpml_search, $wpml_smilies;

	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// extend array allowedtags with img tag if necessary
	// to make sure the comment smilies dont get lost.
	if ( 1 == $av['replaceicon'] ) {
		global $allowedtags;
		if ( ! array_key_exists( 'img', $allowedtags ) ) {
			$allowedtags['img'] = array(
				'src' => array(),
				'alt' => array(),
				'class' => array(),
				'width' => array(),
				'height' => array(),
			);
		}
	}

	// select all valid smiley entries.
	$sql = "select tid,emoticon,iconfile,width,height from $wpml_table where oncomment=1 or onpost=1 order by tid;";
	$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile,width,height from %i where oncomment=1 or onpost=1 order by tid;', $wpml_table ) );

	// icon url begin including directory.
	$ico_url = site_url( $av['icondir'] );

	foreach ( $results as $res ) {
		// store emoticon mapping to array for smiley translation.
		if ( ! array_key_exists( $res->emoticon, $wpml_smilies ) ) {
			$wpml_smilies[ trim( wptexturize( $res->emoticon ) ) ] = array(
				1 => $ico_url . '/' . $res->iconfile,
				2 => $res->width,
				3 => $res->height,
			);
		}
	}

	/*
	 * Taken from WP 4.7
	 * NOTE: we sort the smilies in reverse key order. This is to make sure
	 * we match the longest possible smilie (:???: vs :?) as the regular
	 * expression used below is first-match.
	 */
	krsort( $wpml_smilies );

	$spaces = wp_spaces_regexp();

	// Begin first "subpattern".
	$wpml_search = '/(?<=' . $spaces . '|^)';

	$subchar = '';
	foreach ( (array) $wpml_smilies as $smiley => $img ) {
		$firstchar = substr( $smiley, 0, 1 );
		$rest = substr( $smiley, 1 );

		// new subpattern?.
		if ( $firstchar != $subchar ) {
			if ( '' != $subchar ) {
				$wpml_search .= ')(?=' . $spaces . '|$)';  // End previous "subpattern".
				$wpml_search .= '|(?<=' . $spaces . '|^)'; // Begin another "subpattern".
			}
			$subchar = $firstchar;
			$wpml_search .= preg_quote( $firstchar, '/' ) . '(?:';
		} else {
			$wpml_search .= '|';
		}
		$wpml_search .= preg_quote( $rest, '/' );
	}

	$wpml_search .= ')(?=' . $spaces . '|$)/m';

	if ( 0 == count( $wpml_smilies ) ) {
		$wpml_search = '';
	}
}

/**
 * Translate an emoticon into a valid img tag.
 *
 * @param string $smiley turn eomitcon into an image tag.
 */
function wpml_translate_emoticon( $smiley ) {
	global $wpml_smilies;

	if ( count( $smiley ) == 0 ) {
		return '';
	}

	$smiley = trim( reset( $smiley ) );
	$img = $wpml_smilies[ $smiley ][1];
	$width = $wpml_smilies[ $smiley ][2];
	$height = $wpml_smilies[ $smiley ][3];
	$smiley_masked = esc_attr( $smiley );

	return " <img src='$img' alt='$smiley_masked' width='$width' height='$height' class='wpml_ico' /> ";
}


/**
 * Convert emoticons to icons in img tags.
 *
 * @param string $text Text to convert smilies in.
 */
function wpml_convert_emoticons( $text ) {
	global $wpml_search;

	// no smilies to change, return original text.
	if ( empty( $wpml_search ) ) {
		return $text;
	}

	// reset output.
	$output = '';

	// taken from wordpress 2.8.
	$textarr = preg_split( '/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	// capture the tags as well as in between.
	$stop = count( $textarr );// loop stuff.
	for ( $i = 0; $i < $stop; $i++ ) {
		$content = $textarr[ $i ];

		if ( ( strlen( trim( $content ) ) > 0 ) ) {

			// If it's not a tag.
			$content = preg_replace_callback(
				$wpml_search,
				'wpml_translate_emoticon',
				$content
			);

		}
		$output .= $content;
	}
	return $output;
}


if ( ! function_exists( 'scandir' ) ) {
	/**
	 * Php4 compatibility functions
	 * rebuilds the scandir function not available with php4
	 * copied from Cory S.N. LaViska, thank you :-).
	 *
	 * @param string $directory the direcotry to scan.
	 * @param int    $sorting_order the order to sort.
	 */
	function scandir( $directory, $sorting_order = 0 ) {
		$dh  = @opendir( $directory );
		$files = array();

		if ( $dh ) {
			while ( false !== ( $filename = readdir( $dh ) ) ) {
				$files[] = $filename;
			}
			if ( 0 == $sorting_order ) {
				sort( $files );
			} else {
				rsort( $files );
			}
		}
		return( $files );
	}
}

/**
 * Funktion zum aktualisieren der Höhe und Breite Einträge in der wp_monaslisa Tabelle
 * hoehe und breite fuer vorhandene eintraege setzen.
 *
 * @param array $av Set of options from wp-monalisa.
 */
function set_dimensions( $av ) {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'select tid, iconfile from %i where width=0 or height=0;', $wpdb->prefix . 'monalisa' ) );

	foreach ( $results as $res ) {
		// breite und hoehe ermitteln.
		$isize = getimagesize( ABSPATH . $av['icondir'] . '/' . $res->iconfile );
		$breite = $isize[0];
		$hoehe = $isize[1];

		$results = $wpdb->query(
			$wpdb->prepare(
				'update %i set width=%d, height=%d where tid=%d;',
				$wpdb->prefix . 'monalisa',
				$breite,
				$hoehe,
				$res->tid
			)
		);
	}
}

/**
 * Function to select the smilies to add to rich editor.
 */
function wpml_get_richedit_smilies() {
	global $wpdb;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// optionen einlesen.
	$av = unserialize( get_option( 'wpml-opts' ) );

	// in case we are on a multisite try get the settings from blog no one.
	if ( false == $av ) {
		$av = unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	}

	// icons lesen.
	if ( is_admin() ) {
		$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile from %i where onpost=1 order by tid;', $wpml_table ) );
	} else {
		$results = $wpdb->get_results( $wpdb->prepare( 'select tid,emoticon,iconfile from %i where oncomment=1 order by tid;', $wpml_table ) );
	}

	$resmilies = array();
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

		$ico_url = site_url( $av['icondir'] ) . '/' . $res->iconfile;

		$resmilies[] = array( $res->tid, trim( $res->emoticon ), $ico_url );
	}

	return $resmilies;
}

/**
 * Funktion, prüft, ob es ein AMP Aufruf ist oder nicht
 * gibt false zurück wenn nicht, true wenn es ein AMP Aufruf ist.
 */
function wpml_is_amp() {
	if ( function_exists( 'amp_is_request' ) ) {
		return amp_is_request();
	} else {
		return false;
	}
}

/**
 * Return an array containing all allowed HTML tags and attributes.
 */
function wpml_allowed_tags() {
	// add display to allowed css attributes for style tag.
	add_filter(
		'safe_style_css',
		function( $styles ) {
			$styles[] = 'display';
			return $styles;
		}
	);

	$allowed_atts = array(
		'action'     => array(),
		'align'      => array(),
		'alt'        => array(),
		'border'     => array(),
		'checked'    => array(),
		'class'      => array(),
		'cols'       => array(),
		'data'       => array(),
		'dir'        => array(),
		'for'        => array(),
		'height'     => array(),
		'href'       => array(),
		'id'         => array(),
		'lang'       => array(),
		'maxlength'  => array(),
		'method'     => array(),
		'name'       => array(),
		'novalidate' => array(),
		'onchange'   => array(),
		'onclick'    => array(),
		'rel'        => array(),
		'rev'        => array(),
		'rows'       => array(),
		'scope'      => array(),
		'selected'   => array(),
		'src'        => array(),
		'style'      => array(),
		'tabindex'   => array(),
		'target'     => array(),
		'title'      => array(),
		'type'       => array(),
		'width'      => array(),
		'value'      => array(),
		'xml:lang'   => array(),
	);

	$allowedposttags['form']     = $allowed_atts;
	$allowedposttags['label']    = $allowed_atts;
	$allowedposttags['input']    = $allowed_atts;
	$allowedposttags['textarea'] = $allowed_atts;
	$allowedposttags['select']   = $allowed_atts;
	$allowedposttags['option']   = $allowed_atts;
	$allowedposttags['iframe']   = $allowed_atts;
	$allowedposttags['script']   = $allowed_atts;
	$allowedposttags['style']    = $allowed_atts;
	$allowedposttags['strong']   = $allowed_atts;
	$allowedposttags['small']    = $allowed_atts;
	$allowedposttags['table']    = $allowed_atts;
	$allowedposttags['span']     = $allowed_atts;
	$allowedposttags['abbr']     = $allowed_atts;
	$allowedposttags['code']     = $allowed_atts;
	$allowedposttags['pre']      = $allowed_atts;
	$allowedposttags['div']      = $allowed_atts;
	$allowedposttags['img']      = $allowed_atts;
	$allowedposttags['h1']       = $allowed_atts;
	$allowedposttags['h2']       = $allowed_atts;
	$allowedposttags['h3']       = $allowed_atts;
	$allowedposttags['h4']       = $allowed_atts;
	$allowedposttags['h5']       = $allowed_atts;
	$allowedposttags['h6']       = $allowed_atts;
	$allowedposttags['ol']       = $allowed_atts;
	$allowedposttags['ul']       = $allowed_atts;
	$allowedposttags['li']       = $allowed_atts;
	$allowedposttags['em']       = $allowed_atts;
	$allowedposttags['hr']       = $allowed_atts;
	$allowedposttags['br']       = $allowed_atts;
	$allowedposttags['tr']       = $allowed_atts;
	$allowedposttags['td']       = $allowed_atts;
	$allowedposttags['p']        = $allowed_atts;
	$allowedposttags['a']        = $allowed_atts;
	$allowedposttags['b']        = $allowed_atts;
	$allowedposttags['i']        = $allowed_atts;
	$allowedposttags['table']    = $allowed_atts;
	$allowedposttags['th']       = $allowed_atts;
	$allowedposttags['thead']    = $allowed_atts;
	$allowedposttags['tbody']    = $allowed_atts;
	$allowedposttags['tfoot']    = $allowed_atts;

	return $allowedposttags;
}
