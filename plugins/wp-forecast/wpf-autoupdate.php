<?php
/**
 * This file is part of the wp-monalisa plugin for WordPress
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
 * @package wp-forecast
 */

/**
 * Backup the files changed by the user
 */
function hm_backup_wpf() {
	global $wp_filesystem;

	// name for backup directory.
	$backupdir = $wp_filesystem->wp_content_dir() . '/upgrade/wpf_update/';

	// wenn vorhanden, altes backup verzeichnis löschen.
	if ( is_dir( $backupdir ) ) {
		$wp_filesystem->delete( $backupdir, true );
	}

	// backupdir anlegen.
	$wp_filesystem->mkdir( $backupdir );

	// individuelle css datei sichern.
	if ( $wp_filesystem->is_file( dirname( __FILE__ ) . '/wp-forecast.css' ) ) {
		$wp_filesystem->copy(
			dirname( __FILE__ ) . '/wp-forecast.css',
			$backupdir . '/wp-forecast.css'
		);
	}

	// individuelle css datei sichern.
	if ( $wp_filesystem->is_file( dirname( __FILE__ ) . '/wp-forecast-nowp.css' ) ) {
		$wp_filesystem->copy(
			dirname( __FILE__ ) . '/wp-forecast-nowp.css',
			$backupdir . '/wp-forecast-nowp.css'
		);
	}

}

/**
 * Restoer files changed by the user
 */
function hm_recover_wpf() {
	 global $wp_filesystem;

	$backupdir = $wp_filesystem->wp_content_dir() . '/upgrade/wpf_update/';
	$pdir      = dirname( __FILE__ );

	// individuelle css datei zurück holen.
	if ( $wp_filesystem->is_file( $backupdir . '/wp-forecast.css' ) ) {
		$wp_filesystem->copy(
			$backupdir . '/wp-forecast.css',
			$pdir . '/wp-forecast.css'
		);
	}

	// individuelle css datei zurück holen.
	if ( $wp_filesystem->is_file( $backupdir . '/wp-forecast-nowp.css' ) ) {
		$wp_filesystem->copy(
			$backupdir . '/wp-forecast-nowp.css',
			$pdir . '/wp-forecast-nowp.css'
		);
	}

	// backup verzeichnis löschen.
	$wp_filesystem->delete( $backupdir, true );
}

// add filter.
add_filter( 'upgrader_pre_install', 'hm_backup_wpf', 10, 2 );
add_filter( 'upgrader_post_install', 'hm_recover_wpf', 10, 2 );


