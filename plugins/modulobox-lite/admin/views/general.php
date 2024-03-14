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

echo '<div class="mobx-tab-content mobx-general-content">';

	echo '<h2>' . esc_html__( 'General Settings', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Configure your galleries behaviour.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Attach Lightbox To', 'modulobox' ) . '</h3>';
	do_settings_sections( 'selector' );

	echo '<h3>' . esc_html__( 'Main Layout', 'modulobox' ) . '</h3>';
	do_settings_sections( 'layout' );

	echo '<h3>' . esc_html__( 'Physical Behaviour', 'modulobox' ) . '</h3>';
	do_settings_sections( 'physical-behaviour' );

	echo '<h3>' . esc_html__( 'Browser Behaviour', 'modulobox' ) . '</h3>';
	do_settings_sections( 'browser' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
