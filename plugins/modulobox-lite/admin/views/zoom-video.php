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

echo '<div class="mobx-tab-content mobx-zoom-video-content">';

	echo '<h2>' . esc_html__( 'Zoom &amp; Videos', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Customize the behaviour of the zoom and video functionalities.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Zoom', 'modulobox' ) . '</h3>';
	do_settings_sections( 'zoom' );

	echo '<h3>' . esc_html__( 'Videos', 'modulobox' ) . '</h3>';
	do_settings_sections( 'videos' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
