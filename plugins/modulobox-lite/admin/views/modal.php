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

if ( isset( $_GET ) && isset( $_GET['settings-updated'] ) ) {

	switch ( $_GET['settings-updated'] ) {
		case 'no_file':
			$message = __( 'Please upload a .json file to import', 'modulobox' );
			break;
		case 'invalid_file':
			$message = __( 'Sorry, your settings file is empty or not valid', 'modulobox' );
			break;
		case 'import_error':
			$message = __( 'Sorry, an unknown error occurred while importing', 'modulobox' );
			break;
		default:
			$message = null;
	}

	if ( $message ) {

		echo '<div class="mobx-popup-holder">';
			echo '<div class="mobx-popup-msg">';
				echo '<div class="mobx-popup-close"></div>';
				echo esc_html( $message );
				echo '<span class="mobx-popup-confirm">Ok</span>';
			echo '</div>';
		echo '</div>';

	}

}

echo '<div class="mobx-modal-holder">';
	echo '<span class="mobx-modal-icon"></span>';
	echo '<span class="mobx-modal-msg"></span>';
echo '</div>';
