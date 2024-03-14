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

echo '<div class="mobx-tab-content mobx-navigation-content">';

	echo '<h2>' . esc_html__( 'Navigation', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Set up events and navigation methods triggered by an user interaction.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Gestures', 'modulobox' ) . '</h3>';
	do_settings_sections( 'gestures' );

	echo '<h3>' . esc_html__( 'Keyboard', 'modulobox' ) . '</h3>';
	do_settings_sections( 'keyboard' );

	echo '<h3>' . esc_html__( 'Mouse Wheel', 'modulobox' ) . '</h3>';
	do_settings_sections( 'mousewheel' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
