<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_validate_email' ) ) {
  function csf_validate_email( $args ) {

    $field_value = $args['value'];

    // Getting title of field.
    // $field_title = $args['field']['title']; // getting title of field.

    if ( ! sanitize_email( $field_value ) ) {
      return __( 'Please write a valid email address!', 'csf' );
    }

  }
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_validate_numeric' ) ) {
  function csf_validate_numeric( $args ) {

    if ( ! is_numeric( $args['value'] ) ) {
      return __( 'Please write a numeric data!', 'csf' );
    }

  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_validate_required' ) ) {
  function csf_validate_required( $args ) {

    if ( empty( $args['value'] ) ) {
      return __( 'Fatal Error! This field is required!', 'csf' );
    }

  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_customize_validate_email' ) ) {
  function csf_customize_validate_email( $validity, $value, $wp_customize ) {

    // Getting title of field.
    // $field_title = $wp_customize->manager->get_control( $wp_customize->id )->field['title'];

    if ( ! sanitize_email( $value ) ) {
      $validity->add( 'required', __( 'Please write a valid email address!', 'csf' ) );
    }

    return $validity;

  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_customize_validate_numeric' ) ) {
  function csf_customize_validate_numeric( $validity, $value, $wp_customize ) {

    if ( ! is_numeric( $value ) ) {
      $validity->add( 'required', __( 'Please write a numeric data!', 'csf' ) );
    }

    return $validity;

  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_customize_validate_required' ) ) {
  function csf_customize_validate_required( $validity, $value, $wp_customize ) {

    if ( empty( $value ) ) {
      $validity->add( 'required', __( 'Fatal Error! This field is required!', 'csf' ) );
    }

    return $validity;

  }
}
