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

echo '<div class="mobx-info-bar">';

	$attr = array(
		'id' => ''
	);

	submit_button( '', null, 'save', false, $attr );
	submit_button( __( 'Reset settings', 'modulobox' ), null, 'reset', false, $attr );
	submit_button( __( 'Preview Lightbox', 'modulobox' ), null, 'preview', false, $attr );

echo '</div>';
