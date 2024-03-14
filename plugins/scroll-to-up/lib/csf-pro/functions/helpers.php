<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_add_field' ) ) {
  function csf_add_field( $field = array(), $value = '', $unique = '', $where = '' ) {

    // Check for unallow fields
    if( ! empty( $field['_notice'] ) ) {

      $field_type = $field['type'];

      $field            = array();
      $field['content'] = sprintf( __( 'Ooops! This field type (%s) can not be used here, yet.', 'csf' ), '<strong>'. $field_type .'</strong>' );
      $field['type']    = 'notice';
      $field['class']   = 'warning';

    }

    $output     = '';
    $depend     = '';
    $sub        = ( ! empty( $field['sub'] ) ) ? 'sub-': '';
    $unique     = ( ! empty( $unique ) ) ? $unique : '';
    $languages  = csf_language_defaults();
    $class      = 'CSF_Field_' . $field['type'];
    $wrap_class = ( ! empty( $field['wrap_class'] ) ) ? ' ' . $field['wrap_class'] : '';
    $el_class   = ( ! empty( $field['title'] ) ) ? sanitize_title( $field['title'] ) : 'no-title';
    $hidden     = ( ! empty( $field['show_only_language'] ) && ( $field['show_only_language'] != $languages['current'] ) ) ? ' hidden' : '';
    $is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' csf-pseudo-field' : '';

    if ( ! empty( $field['dependency'] ) ) {
      $hidden  = ' hidden';
      $depend .= ' data-'. $sub .'controller="'. $field['dependency'][0] .'"';
      $depend .= ' data-'. $sub .'condition="'. $field['dependency'][1] .'"';
      $depend .= ' data-'. $sub .'value="'. $field['dependency'][2] .'"';
    }

    $output .= '<div class="csf-field csf-field-key-'. $el_class .' csf-field-'. $field['type'] . $is_pseudo . $wrap_class . $hidden .'"'. $depend .'>';

    if( ! empty( $field['title'] ) ) {
      $field_desc = ( ! empty( $field['desc'] ) ) ? '<p class="csf-text-desc">'. $field['desc'] .'</p>' : '';
      $output .= '<div class="csf-title"><h4>' . $field['title'] . '</h4>'. $field_desc .'</div>';
    }

    $output .= ( ! empty( $field['title'] ) ) ? '<div class="csf-fieldset">' : '';

    $value   = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
    $value   = ( isset( $field['value'] ) ) ? $field['value'] : $value;

    if( class_exists( $class ) ) {
      ob_start();
      $element = new $class( $field, $value, $unique, $where );
      $element->output();
      $output .= ob_get_clean();
    } else {
      $output .= '<p>'. __( 'This field class is not available!', 'csf' ) .'</p>';
    }

    $output .= ( ! empty( $field['title'] ) ) ? '</div>' : '';
    $output .= '<div class="clear"></div>';
    $output .= '</div>';

    return $output;

  }
}

/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_encode_string' ) ) {
  function csf_encode_string( $string ) {
    return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
  }
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_decode_string' ) ) {
  function csf_decode_string( $string ) {
    return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
  }
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_google_fonts' ) ) {
  function csf_get_google_fonts() {

    global $csf_google_fonts;

    if( ! empty( $csf_google_fonts ) ) {

      return $csf_google_fonts;

    } else {

      ob_start();
      CSF::locate_template( 'fields/typography/google-fonts.json' );
      $json = ob_get_clean();

      $csf_google_fonts = json_decode( $json );

      return $csf_google_fonts;
    }

  }
}

/**
 *
 * Get icon fonts from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_icon_fonts' ) ) {
  function csf_get_icon_fonts( $file ) {

    ob_start();
    CSF::locate_template( 'fields/icon/'. basename( $file ) );
    $json = ob_get_clean();

    return json_decode( $json );

  }
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_array_search' ) ) {
  function csf_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, csf_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_var' ) ) {
  function csf_get_var( $var, $default = '' ) {

    if( isset( $_POST[$var] ) ) {
      return $_POST[$var];
    }

    if( isset( $_GET[$var] ) ) {
      return $_GET[$var];
    }

    return $default;

  }
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_vars' ) ) {
  function csf_get_vars( $var, $depth, $default = '' ) {

    if( isset( $_POST[$var][$depth] ) ) {
      return $_POST[$var][$depth];
    }

    if( isset( $_GET[$var][$depth] ) ) {
      return $_GET[$var][$depth];
    }

    return $default;

  }
}

/**
 *
 * Between Microtime
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_microtime' ) ) {
  function csf_timeout( $timenow, $starttime, $timeout = 30 ) {

    return ( ( $timenow - $starttime ) < $timeout ) ? true : false;

  }
}


/**
 *
 * Getting Custom Options for Fields
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_custom_options' ) ) {
  function csf_get_custom_options() {

    $default = array(
      'key-1' => 'Key 1',
      'key-2' => 'Key 2',
      'key-3' => 'Key 3',
    );

    return $default;

  }
}

/**
 *
 * Get language defaults
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_language_defaults' ) ) {
  function csf_language_defaults() {

    $multilang = array();

    if( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) || function_exists( 'qtrans_getSortedLanguages' ) ) {

      if( class_exists( 'SitePress' ) ) {

        global $sitepress;
        $multilang['default']   = $sitepress->get_default_language();
        $multilang['current']   = $sitepress->get_current_language();
        $multilang['languages'] = $sitepress->get_active_languages();

      } else if( class_exists( 'Polylang' ) ) {

        global $polylang;
        $current    = pll_current_language();
        $default    = pll_default_language();
        $current    = ( empty( $current ) ) ? $default : $current;
        $poly_langs = $polylang->model->get_languages_list();
        $languages  = array();

        foreach ( $poly_langs as $p_lang ) {
          $languages[$p_lang->slug] = $p_lang->slug;
        }

        $multilang['default']   = $default;
        $multilang['current']   = $current;
        $multilang['languages'] = $languages;

      } else if( function_exists( 'qtrans_getSortedLanguages' ) ) {

        global $q_config;
        $multilang['default']   = $q_config['default_language'];
        $multilang['current']   = $q_config['language'];
        $multilang['languages'] = array_flip( qtrans_getSortedLanguages() );

      }

    }

    $multilang = apply_filters( 'csf/language/defaults', $multilang );

    return ( ! empty( $multilang ) ) ? $multilang : false;

  }
}
