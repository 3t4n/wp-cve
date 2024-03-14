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

echo '<div class="mobx-tab-content mobx-gallery-content">';

	echo '<h2>' . esc_html__( 'Gallery Shortcode', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Set up ModuloBox galleries behaviour and appearance.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Behaviour', 'modulobox' ) . '</h3>';
	do_settings_sections( 'gallery' );

	echo '<h3>' . esc_html__( 'Appearance', 'modulobox' ) . '</h3>';
	do_settings_sections( 'gallery-styles' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
