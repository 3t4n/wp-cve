<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Sanitize custom text
 * Converting a to b
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_sanitize_custom' ) ) {
  function csf_sanitize_custom( $value ) {
    return str_replace( 'a', 'b', $value );
  }
}
