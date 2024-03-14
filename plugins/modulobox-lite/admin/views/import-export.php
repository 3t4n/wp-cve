<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<div class="mobx-tab-content mobx-import-export-content">';

	echo '<h2>' . esc_html__( 'Import/Export', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'You can either export your ModuloBox Settings or import settings from another configuration or install of ModuloBox below.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Export Settings', 'modulobox' ) . '</h3>';
	echo '<p>';
		esc_html_e( 'Export your plugin settings for this site as a .json file.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'This allows you to easily save/backup your current configuration.', 'modulobox' );
	echo '</p>';

	submit_button( __( 'Export', 'modulobox' ), null, 'export', false );

	echo '<h3>' . esc_html__( 'Import Settings', 'modulobox' ) . '</h3>';
	echo '<p>';
		esc_html_e( 'Import your plugin settings from a .json file.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'This file can be obtained by exporting your settings using the form above.', 'modulobox' );
	echo '</p>';

	echo '<input type="file" class="mobx-file-input" name="import_file">';
	echo '<input type="text" class="mobx-upload-input" placeholder="' . esc_attr__( 'Select a .json file', 'modulobox' ) . '" readonly>';
	echo '<button type="button" class="mobx-upload-button" tabindex="-1">' . esc_html__( 'Browse', 'modulobox' ) . '</button>';
	echo '<br>';

	submit_button( __( 'Import', 'modulobox' ), null, 'import', false );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
