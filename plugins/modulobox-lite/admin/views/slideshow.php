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

echo '<div class="mobx-tab-content mobx-slideshow-content">';

	echo '<h2>' . esc_html__( 'Slideshow', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Set up the slideshow behaviour.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Behaviour', 'modulobox' ) . '</h3>';
	do_settings_sections( 'slideshow' );

	echo '<h3>' . esc_html__( 'Count Timer', 'modulobox' ) . '</h3>';
	do_settings_sections( 'count-timer' );

	echo '<h3>' . esc_html__( 'Counter Message', 'modulobox' ) . '</h3>';
	do_settings_sections( 'counter-message' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
