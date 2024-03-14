<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Wacs_Notifications {

	function wacs_success( $msg ) {

		echo '<div class="notice-success notice is-dismissible">';
		echo '<p>'.$msg.'</p>';
		echo '</div>';

	}

	function wacs_error( $msg ) {

        echo '<div class="notice-error notice is-dismissible">';
        echo '<p>'.$msg.'</p>';
        echo '</div>';

	}

	function wacs_notice( $msg ) {

        echo '<div class="notice-warning notice is-dismissible">';
        echo '<p>'.$msg.'</p>';
        echo '</div>';

	}
}