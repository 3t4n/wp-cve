<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'EDS_Validate_email' ) ) {
  function EDS_Validate_email( $value, $field ) {

    if ( ! sanitize_email( $value ) ) {
      return __( 'Please write a valid email address!', 'eds-framework' );
    }

  }
  add_filter( 'EDS_Validate_email', 'EDS_Validate_email', 10, 2 );
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'EDS_Validate_numeric' ) ) {
  function EDS_Validate_numeric( $value, $field ) {

    if ( ! is_numeric( $value ) ) {
      return __( 'Please write a numeric data!', 'eds-framework' );
    }

  }
  add_filter( 'EDS_Validate_numeric', 'EDS_Validate_numeric', 10, 2 );
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'EDS_Validate_required' ) ) {
  function EDS_Validate_required( $value ) {
    if ( empty( $value ) ) {
      return __( 'Fatal Error! This field is required!', 'eds-framework' );
    }
  }
  add_filter( 'EDS_Validate_required', 'EDS_Validate_required' );
}
