<?php 

namespace Hurrytimer\Traits;

trait Singleton{

  /** @var static */
  private static $instance;

  /**
   * @return static
   */
  public static function get_instance(){

    if( is_null(static::$instance) ){
      static::$instance = new static;
    }

    return static::$instance;

  }
}