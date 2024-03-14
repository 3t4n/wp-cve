<?php
if ( ! class_exists( 'Zara4_WordPress_Validation' ) ) {


  /**
   * Class Zara4_WordPress_Validation
   */
  class Zara4_WordPress_Validation {

    /**
     * Validate that the given id is for a valid image.
     *
     * @param $image_id
     * @throws \Exception
     */
    public static function validate_image_id( $image_id ) {
      /** @noinspection PhpUndefinedFunctionInspection */
      if ( ! wp_attachment_is_image( $image_id ) ) {
        throw new \Exception( $image_id . ' is not a valid image id' );
      }
    }


    /**
     * Validate that the current user has the required capabilities.
     *
     * @throws \Exception
     */
    public static function validate_current_user_capabilities() {
      /** @noinspection PhpUndefinedFunctionInspection */
      //if ( ! current_user_can( 'manage_options' ) ) {
      //  throw new \Exception( 'Your user account doesn\'t have permission to manage images' );
      //}
    }


    /**
     * Validate that the given file path exists.
     *
     * @param $file_path
     * @throws \Exception
     */
    public static function validate_file_path( $file_path ) {
      if ( false === $file_path || ! file_exists( $file_path ) ) {
        throw new \Exception( 'The file requested could not be found.' );
      }
    }

  }

}