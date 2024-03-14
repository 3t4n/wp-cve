<?php
/** This file is part of the wp-monalisa plugin for WordPress
 *
 * Copyright 2010  Hans Matzen  (email : webmaster at tuxlog.de)
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
 * Function to backup user files.
 */
function hm_backup() {
	global $wp_filesystem;

	$deficons = array(
		'default.pak',
		'wpml_heart.gif',
		'wpml_scratch.gif',
		'wpml_wink.gif',
		'wpml_bye.gif',
		'wpml_mail.gif',
		'wpml_smile.gif',
		'wpml_yahoo.gif',
		'wpml_cool.gif',
		'wpml_negative.gif',
		'wpml_unsure.gif',
		'wpml_yes.gif',
		'wpml_cry.gif',
		'wpml_rose.gif',
		'wpml_wacko.gif',
		'wpml_good.gif',
		'wpml_sad.gif',
		'wpml_whistle3.gif',
	);

	// name for backup directory.
	$backupdir = $wp_filesystem->wp_content_dir() . '/upgrade/wpml_update/';

	// wenn vorhanden, altes backup verzeichnis löschen.
	if ( is_dir( $backupdir ) ) {
		$wp_filesystem->delete( $backupdir, true );
	}

	// icons ordner in backup verzeichnis kopieren.
	$wp_filesystem->mkdir( $backupdir );
	if ( $wp_filesystem->is_dir( dirname( __FILE__ ) . '/icons' ) ) {
		$wp_filesystem->mkdir( $backupdir . '/icons' );

		$icons = $wp_filesystem->dirlist( dirname( __FILE__ ) . '/icons' );
		foreach ( array_keys( $icons ) as $i ) {
			if ( ! in_array( $i, $deficons, true ) ) {
				$wp_filesystem->copy(
					dirname( __FILE__ ) . '/icons/' . $i,
					$backupdir . '/icons/' . $i
				);
			}
		}
	}

	// individuelle css datei sichern.
	if ( $wp_filesystem->is_file( dirname( __FILE__ ) . '/wp-monalisa.css' ) ) {
		$wp_filesystem->copy(
			dirname( __FILE__ ) . '/wp-monalisa.css',
			$backupdir . '/wp-monalisa.css'
		);
	}

}

/**
 * Copy user files back after plugin update
 */
function hm_recover() {
	global $wp_filesystem;

	$backupdir = $wp_filesystem->wp_content_dir() . '/upgrade/wpml_update/';
	$pdir      = dirname( __FILE__ );

	// individuelle css datei zurück holen.
	if ( $wp_filesystem->is_file( $backupdir . '/wp-monalisa.css' ) ) {
		$wp_filesystem->copy(
			$backupdir . '/wp-monalisa.css',
			$pdir . '/wp-monalisa.css'
		);
	}

	// icons ordner in plugin verzeichnis kopieren.
	$files = $wp_filesystem->dirlist( $backupdir . '/icons/' );
	if ( $files ) {
		foreach ( array_keys( $files ) as $f ) {
			$wp_filesystem->copy(
				$backupdir . '/icons/' . $f,
				$pdir . '/icons/' . $f
			);
		}
	}

	// backup verzeichnis löschen.
	$wp_filesystem->delete( $backupdir, true );
}

// add filter.
add_filter( 'upgrader_pre_install', 'hm_backup', 10, 2 );
add_filter( 'upgrader_post_install', 'hm_recover', 10, 2 );


