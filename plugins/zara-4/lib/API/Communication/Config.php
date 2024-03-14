<?php
if ( ! class_exists( 'Zara4_API_Communication_Config' ) ) {


  /**
   * Class Zara4_API_Communication_Config
   */
  class Zara4_API_Communication_Config {

    public static function BASE_URL() {
      if ( ! ZARA4_DEV ) {
        return "https://api.zara4.com";
      } else {
        return "http://api.zara4.dev";
      }
    }

  }

}