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

echo '<div class="mobx-tab-content mobx-accessibility-content">';

	echo '<h2>' . esc_html__( 'Accessibility', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Customize labels and messages of ModuloBox.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Buttons Label', 'modulobox' ) . '</h3>';
	do_settings_sections( 'accessibility' );

	echo '<h3>' . esc_html__( 'Error Messages', 'modulobox' ) . '</h3>';
	do_settings_sections( 'error-messages' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
