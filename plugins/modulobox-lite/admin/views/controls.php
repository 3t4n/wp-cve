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

echo '<div class="mobx-tab-content mobx-controls-content">';

	echo '<h2>' . esc_html__( 'Controls', 'modulobox' ) . '</h2>';
	echo '<p>' . esc_html__( 'Customize the user interface of MobuloBox.', 'modulobox' ) . '</p>';

	echo '<h3>' . esc_html__( 'Main Controls', 'modulobox' ) . '</h3>';
	do_settings_sections( 'controls' );

	echo '<h3>' . esc_html__( 'Prev/Next buttons', 'modulobox' ) . '</h3>';
	do_settings_sections( 'prev-next-buttons' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
