<?php
if ( ! function_exists( 'plz_include_scripts' ) ) :
  function plz_include_scripts() {
    $check_tracker = plz_get_tracking_enable_mode_for_header();

    if ( $check_tracker ) :
      $tracking_code = plz_get_tracking_code_for_header();
      $check_tracker_type = plz_get_tracking_type_enable();

      if ( $tracking_code && ! empty( $tracking_code ) && $check_tracker_type && ! empty( $check_tracker_type ) ) :
        if ( 'v2' === $check_tracker_type ) :
          if ( false !== strpos( $tracking_code, ' src=' ) ) :
            $src = plz_extract_script_src( $tracking_code );
            
            wp_enqueue_script( 'plz-tracking-script', $src, array(), PLZ_VERSION, true );
          else :
            wp_enqueue_script( 'plz-tracking-script', $tracking_code, array(), PLZ_VERSION, true );
          endif;
        else :
          if ( false !== strpos( $tracking_code, ' src=' ) ) :
            $src = plz_extract_script_src( $tracking_code );

            wp_enqueue_script( 'plz-tracking-script', $src, array(), PLZ_VERSION, true );
          endif;
        endif;
      endif;
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_enable_mode_for_header' ) ) :
  function plz_get_tracking_enable_mode_for_header() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_enable'] ) ) :
      if ( ! empty( $options['plz_configuration_tracking_enable'] ) && 'checked' === $options['plz_configuration_tracking_enable'] ) :
        return true;
      else :
        return false;
      endif;
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) ) :
      if ( ! empty( $old_options['plz_configuration_tracker_enable'] ) && 'checked' === $old_options['plz_configuration_tracker_enable'] ) :
        return true;
      else :
        return false;
      endif;
    else :
      return false;
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_enable_mode' ) ) :
  function plz_get_tracking_enable_mode() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_enable'] ) ) :
      if ( ! empty( $options['plz_configuration_tracking_enable'] ) && 'checked' === $options['plz_configuration_tracking_enable'] ) :
        return true;
      elseif ( ! empty( $options['plz_configuration_tracking_enable_manual'] ) && 'checked' === $options['plz_configuration_tracking_enable_manual'] ) :
        return true;
      else :
        return false;
      endif;
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) ) :
      if ( ! empty( $old_options['plz_configuration_tracker_enable'] ) && 'checked' === $old_options['plz_configuration_tracker_enable'] ) :
        return true;
      else :
        return false;
      endif;
    else :
      return false;
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_code_for_header' ) ) :
  function plz_get_tracking_code_for_header() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_enable'] ) && ! empty( $options['plz_configuration_tracking_enable'] ) && 'checked' === $options['plz_configuration_tracking_enable'] && isset( $options['plz_configuration_tracking_code'] ) && ! empty( $options['plz_configuration_tracking_code'] ) ) :
      return $options['plz_configuration_tracking_code'];
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) && ! empty( $old_options['plz_configuration_tracker_enable'] ) && 'checked' === $old_options['plz_configuration_tracker_enable'] && isset( $old_options['plz_configuration_tracker_code'] ) && ! empty( $old_options['plz_configuration_tracker_code'] ) ) :
      return $old_options['plz_configuration_tracker_code'];
    else :
      return false;
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_type_enable' ) ) :
  function plz_get_tracking_type_enable() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_enable'] ) && ! empty( $options['plz_configuration_tracking_enable'] ) && 'checked' === $options['plz_configuration_tracking_enable'] && isset( $options['plz_configuration_tracking_code'] ) && ! empty( $options['plz_configuration_tracking_code'] ) ) :
      return 'v2';
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) && ! empty( $old_options['plz_configuration_tracker_enable'] ) && 'checked' === $old_options['plz_configuration_tracker_enable'] && isset( $old_options['plz_configuration_tracker_code'] ) && ! empty( $old_options['plz_configuration_tracker_code'] ) ) :
      return 'v1';
    else :
      return false;
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_code' ) ) :
  function plz_get_tracking_code() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_code'] ) && ! empty( $options['plz_configuration_tracking_code'] ) ) :
      return $options['plz_configuration_tracking_code'];
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_code'] ) && ! empty( $old_options['plz_configuration_tracker_code'] ) ) :
      return $old_options['plz_configuration_tracker_code'];
    else :
      return '';
    endif;
  }
endif;

if ( ! function_exists( 'plz_get_tracking_date' ) ) :
  function plz_get_tracking_date() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_configuration_tracking_date'] ) && ! empty( $options['plz_configuration_tracking_date'] ) ) :
      return $options['plz_configuration_tracking_date'];
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_date'] ) && ! empty( $old_options['plz_configuration_tracker_date'] ) ) :
      return $old_options['plz_configuration_tracker_date'];
    else :
      return '';
    endif;
  }
endif;

if ( ! function_exists( 'plz_check_tracking_choice' ) ) :
  function plz_check_tracking_choice() {
    $return = array( 'api' => '', 'manual' => '' );
    $options = get_option( 'plz_configuration_tracking_options' );
    $old_options = get_option( 'plz_configuration_options' );

    if ( $options && isset( $options['plz_tracking_choice'] ) && ! empty( $options['plz_tracking_choice'] ) ) :
      $return[ $options['plz_tracking_choice'] ] = 'checked';
    elseif ( $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) && ! empty( $old_options['plz_configuration_tracker_enable'] ) && 'checked' === $old_options['plz_configuration_tracker_enable'] ) :
      $return['api'] = 'checked';
    endif;

    return $return;
	}
endif;

if ( ! function_exists( 'plz_extract_script_src' ) ) :
  function plz_extract_script_src( $tracking_code ) {
    $dom = new DOMDocument();

    @$dom->loadHTML($tracking_code);

    $xpath = new DOMXPath($dom);
    $src = $xpath->evaluate( 'string(//script/@src)' );

    return $src;
	}
endif;
