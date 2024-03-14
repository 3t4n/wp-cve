<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Reamaze Helper Functions
 *
 * @author      Reamaze
 * @package     Reamaze
 * @version     2.3.2
 */

if ( ! function_exists( 'reamaze_is_ajax' ) ) {
  function reamaze_is_ajax() {
    return defined( 'DOING_AJAX' );
  }
}

function get_reamaze_email() {
  $user = wp_get_current_user();
  if ( ! empty( $user->reamaze_login_email ) ) {
    return $user->reamaze_login_email;
  } else {
    return $user->user_email;
  }
}

include_once ( 'reamaze-api.php' );
