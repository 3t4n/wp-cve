<?php
/**
 * Sanitization Functions
 *
 * @package walker_core
 * 
 */
// Sanitize hex color 
if ( ! function_exists( 'walker_core_sanitize_hex_color' ) ) :
  function walker_core_sanitize_hex_color( $color ) {
    if ( '' === $color ) {
      return '';
    }
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
      return $color;
    }
    return NULL;
  }
endif;

//// Sanitize checkbox 
if ( ! function_exists( 'walker_core_sanitize_checkbox' ) ) :
  function walker_core_sanitize_checkbox( $input ) {
    return ( ( isset( $input ) && true == $input ) ? true : false );
  }
endif;

// Sanitize select
if ( ! function_exists( 'walker_core_sanitize_select' ) ) :
  function walker_core_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
endif;


//Sanitize choice
if ( ! function_exists( 'walker_core_sanitize_choices' ) ) :
  function walker_core_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
  }
endif;


// Sanitize Number Range
if ( ! function_exists( 'walker_core_sanitize_float' ) ) :
  function walker_core_sanitize_float( $input ) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  }
endif;

// Sanitize files
if ( ! function_exists( 'walker_core_sanitize_file' ) ) :
  function walker_core_sanitize_file( $file, $setting ) {
            
    //allowed file types
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png'
    );
      
    //check file type from file name
    $file_ext = wp_check_filetype( $file, $mimes );
      
    //if file has a valid mime type return it, otherwise return default
    return ( $file_ext['ext'] ? $file : $setting->default );
  }
endif;

// Sanitize url
if ( ! function_exists( 'walker_core_sanitize_url' ) ) :
  function walker_core_sanitize_url( $text) {
    $text = esc_url_raw( $text);
    return $text;
  }
  endif;

// Sanitize textarea
if ( ! function_exists( 'walker_core_sanitize_textarea' ) ) :
    function walker_core_sanitize_textarea( $html ) {
        return wp_filter_post_kses( $html );
    }
endif;

// Sanitize text
if ( ! function_exists( 'walker_core_sanitize_text' ) ) :
    function walker_core_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
}
endif;

if ( ! function_exists( 'walker_core_sanitize_number_absint' ) ) :
  function walker_core_sanitize_number_absint( $number, $setting ) {
    // Ensure $number is an absolute integer (whole number, zero or greater).
    $number = absint( $number );

    // If the input is an absolute integer, return it; otherwise, return the default
    return ( $number ? $number : $setting->default );
  }
endif;