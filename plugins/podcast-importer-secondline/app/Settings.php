<?php

namespace PodcastImporterSecondLine;

class Settings {

  /**
   * @var Settings;
   */
  protected static $_instance;

  /**
   * @return Settings
   */
  public static function instance(): Settings {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  public $_default_settings = [
    '_admin_notice_dismissed_map'  => [],
    '_did_migration'               => '0.0.0'
  ];

  public $settings = [];

  public function __construct() {
    $this->settings = get_option( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_settings', [] );
  }

  public function get( $key, $default_value = null ) {
    if( isset( $this->settings[ $key ] ) )
      return $this->settings[ $key ];

    if( $default_value !== null )
      return $default_value;

    return ( $this->_default_settings[$key] ?? null );
  }

  public function update( $key, $value ) {
    if( isset( $this->_default_settings[ $key ] ) && $this->_default_settings[ $key ] === $value )
      unset( $this->settings[ $key ] );
    else
      $this->settings[ $key ] = $value;

    update_option( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_settings', $this->settings );
  }

}