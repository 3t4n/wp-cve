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

echo '<div class="mobx-tab-content mobx-social-sharing-content">';

	echo '<h2>' . esc_html__( 'Social Sharing', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Customize and set up your favorite social media.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Behaviour', 'modulobox' ) . '</h3>';
	do_settings_sections( 'social-sharing' );

	echo '<h3>' . esc_html__( 'Appearance', 'modulobox' ) . '</h3>';
	do_settings_sections( 'social-sharing-tooltip' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
