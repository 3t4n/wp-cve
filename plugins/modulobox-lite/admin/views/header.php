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

echo '<div class="mobx-wrap">';

echo '<div class="mobx-header">';

	echo '<span class="mobx-logo"></span>';
	echo '<span class="mobx-name">MODULOBOX LITE</span>';
	echo '<span class="mobx-version">v' . esc_html( MOBX_VERSION ) . '</span>';

echo '</div>';
