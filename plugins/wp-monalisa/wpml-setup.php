<?php
/**
 * This file is part of the wp-monalisa plugin for wordpress
 *
 * Copyright 2009-2022 Hans Matzen  (email : webmaster at tuxlog dot de)
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
 * This function installs the wp-monalisa database tables and
 * sets up default values and options
 */
function wp_monalisa_install() {
	global $wpdb;

	// table name.
	$wpml_table = $wpdb->prefix . 'monalisa';

	// tabelle pruefen und ggf. anlegen.
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpml_table ) ) != $wpml_table ) {
		// erzeugen der smiley tabelle.
		$results = $wpdb->query(
			$wpdb->prepare(
				'create table %i (
					tid integer not null auto_increment,
					emoticon varchar(25) NOT NULL,
					iconfile varchar(80) NOT NULL,
					onpost tinyint NOT NULL,
					oncomment tinyint NOT NULL,
					width int NOT NULL,
					height int NOT NULL,
					primary key(tid)
				)',
				$wpml_table
			)
		);

		$smilies_init = array(
			':bye:'      => 'wpml_bye.gif',
			':good:'     => 'wpml_good.gif',
			':negative:' => 'wpml_negative.gif',
			':scratch:'  => 'wpml_scratch.gif',
			':wacko:'    => 'wpml_wacko.gif',
			':yahoo:'    => 'wpml_yahoo.gif',
			'B-)'        => 'wpml_cool.gif',
			':heart:'    => 'wpml_heart.gif',
			':rose:'     => 'wpml_rose.gif',
			':-)'        => 'wpml_smile.gif',
			':whistle:'  => 'wpml_whistle3.gif',
			':yes:'      => 'wpml_yes.gif',
			':cry:'      => 'wpml_cry.gif',
			':mail:'     => 'wpml_mail.gif',
			':-('        => 'wpml_sad.gif',
			':unsure:'   => 'wpml_unsure.gif',
			';-)'        => 'wpml_wink.gif',
		);

		// ein paar smilies einfuegen.
		$i = 0;

		foreach ( $smilies_init as $emo => $ico ) {
			// breite und hoehe ermitteln.
			$breite = 0;
			$hoehe = 0;
			$isize = getimagesize( ABSPATH . PLUGINDIR . '/wp-monalisa/icons/' . $ico );
			if ( false != $isize ) {
				$breite = $isize[0];
				$hoehe = $isize[1];
			}
			$i++;

			$results = $wpdb->query(
				$wpdb->prepare(
					'insert into %i values ( %d, %s, %s, 1, 1, %d, %d );',
					$wpml_table,
					$i,
					$wpdb->_real_escape( $emo ),
					$wpdb->_real_escape( $ico ),
					$breite,
					$hoehe
				)
			);
		}
	} else {
		// tabelle schon vorhanden, hier folgen die updates und alters
		// spaltenbreite der spalte emoticon auf 25 verändern.
		$results = $wpdb->query( $wpdb->prepare( 'alter table %i modify column emoticon varchar(25) not null;', $wpml_table ) );
        
		// spaltenbreite der spalte iconfile auf 80 verändern.
		$results = $wpdb->query( $wpdb->prepare( 'alter table %i modify column iconfile varchar(80) not null;', $wpml_table ) );

		// spalten fuer hoehe und breite ergaenzen falls notwendig.
		$results = $wpdb->query( $wpdb->prepare( "show columns from %i like 'width'", $wpml_table ) );

		if ( null == $results ) {
			// neue spalte breite ergaenzen.
			$results = $wpdb->query( $wpdb->prepare( 'alter table %i add column width int not null;', $wpml_table ) );

			// neue spalte hoehe ergaenzen.
			$results = $wpdb->query( $wpdb->prepare( 'alter table %i add column height int not null;', $wpml_table ) );
		}
	}

	// Optionen / Parameter.

	// gibt es bereits eintraege
	// optionen einlesen.
	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	if ( '' == $av ) {
		// verzeichnis fuer die icons.
		$av['icondir']     = PLUGINDIR . '/wp-monalisa/icons';
		// smiliey auswahl im editor anzeigen.
		$av['onedit']      = 1;
		// smiley auswahl im commentform anzeigen.
		$av['oncomment']   = 1;
		// icons zeigen ( 0= nur text, 1= nur icons, 2=beides ).
		$av['showicon']    = 1;
		// text durch img tags ersetzen.
		$av['replaceicon'] = 1;
		// kommentarfeld id.
		$av['commenttextid'] = 'comment';
		// smilies als table struktur anzeigen.
		$av['showastable'] = 0;
		// smilies zum aufklappen anzeigen.
		$av['showaspulldown'] = 0;
		// smilies pro reihe in der tabelle.
		$av['smiliesperrow'] = 15;
		// Anzahl smilies zugeklappt.
		$av['smilies1strow'] = 7;
		// tooltipp fuer icons anzeigen.
		$av['icontooltip'] = 1;
		// smilies fuer buddypress aktivieren.
		$av['wpml4buddypress'] = 0;

		add_option( 'wpml-opts', serialize( $av ) );
	}

	// sets the width and height of icons where width or height = 0 from iconfile using getimagesize.
	set_dimensions( $av );

	// save the current setting for the WordPress option use_smilies which can not be changed via admin dialog since WP 4.3.
	if ( is_multisite() ) {
		$usm = get_blog_option( get_current_blog_id(), 'use_smilies' );
	} else {
		$usm = get_option( 'use_smilies' );
	}
	update_option( 'wpml_use_smilies_backup', $usm );
	update_option( 'use_smilies', false );

}

/**
 * Function to remove all parameters from the database
 * This function is disabled by default to prevent deleting things unintentionally.
 */
function wp_monalisa_deinstall() {
	global $wpdb;

	update_option( 'use_smilies', 1 );
	// to prevent misuse :-)
	// wenn die naechste zeile auskommentiert wird, werden
	// bei daktivierung des plugins alle datenbankeintraege von wpml geloescht.
	return;

	// tabelle pruefen und ggf. löschen.
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %i', $wpml_table ) ) != $wpml_table ) {
		// erzeugen der smiley tabelle.
		// drop tables.
		$results = $wpdb->query( 'drop table %i;', $wpml_table );
	}

	// remove options from wp_options.
	delete_option( 'wpml-opts' );
	delete_option( 'wpml_excludes' );
	// restore former setting for use_smilies.
	$usm = get_option( 'wpml_use_smilies_backup' );
	update_option( 'use_smilies', $usm );
	delete_option( 'wpml_use_smilies_backup' );
}

