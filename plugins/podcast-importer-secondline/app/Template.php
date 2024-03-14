<?php

namespace PodcastImporterSecondLine;

class Template {

  public static function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
    ob_start();

    self::load_template( $template_name, $args, $template_path, $default_path );

    $ret = ob_get_contents();

    ob_end_clean();

    return $ret;
  }

  public static function load_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
    if ( $args && is_array( $args ) ) {
      extract( $args );
    }

    $located = self::locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
      _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0.0' );
      return;
    }

    // Allow 3rd party plugin filter template file from their plugin
    $located = apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_get_template', $located, $template_name, $args, $template_path, $default_path );

    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_before_template_part', $template_name, $template_path, $located, $args );

    include( $located );

    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_after_template_part', $template_name, $template_path, $located, $args );
  }

  public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path )
      $template_path = self::template_path();

    if ( ! $default_path )
      $default_path = PODCAST_IMPORTER_SECONDLINE_BASE_PATH . '/templates/';

    $template = locate_template( [ trailingslashit( $template_path ) . $template_name ] );

    // Get default template
    if ( ! $template )
      $template = $default_path . $template_name;

    // Return what we found
    return apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_locate_template', $template, $template_name, $template_path );
  }

  public static function template_path() {
    return apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_template_path', PODCAST_IMPORTER_SECONDLINE_ALIAS . '/' );
  }

}
