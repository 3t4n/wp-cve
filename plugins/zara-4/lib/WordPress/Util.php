<?php
if ( ! class_exists( 'Zara4_WordPress_Util' ) ) {


  /**
   * Class Zara4_WordPress_Util
   */
  class Zara4_WordPress_Util {

    /**
     * Format a given number of bytes.
     *
     * @param $bytes
     * @return string
     */
    public static function format_bytes( $bytes ) {
      $units = array( 'B', 'KB', 'MB', 'GB', 'TB' );

      $bytes = max( $bytes, 0 );
      $pow = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
      $pow = min( $pow, count( $units ) - 1 );

      $bytes /= pow( 1024, $pow );

      return round( $bytes, 1 ) . ' ' . $units[$pow];
    }


    /**
     *
     *
     * @param $path
     * @return string
     */
    public static function hash_file( $path ) {
      if ( ! file_exists( $path ) ) {
        return false;
      }
      return sha1_file( $path );
    }

  }

}