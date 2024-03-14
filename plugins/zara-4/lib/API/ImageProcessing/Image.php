<?php
if ( ! class_exists( 'Zara4_API_ImageProcessing_Image' ) ) {


  /**
   * Class Zara4_API_ImageProcessing_Image
   */
  class Zara4_API_ImageProcessing_Image {


    private static function optimise_image( $data ) {
      $url = Zara4_API_Communication_Util::url( '/v1/image-processing/request' );
      return Zara4_API_Communication_Util::post( $url, $data );
    }


    /**
     * Optimise the image at the given file path.
     *
     * @param $file_path
     * @param array $params
     * @return array
     */
    public static function optimise_image_from_file( $file_path, array $params = array() ) {

      //
      // Attach file
      //   - As of 5.5.0  -> @ is depreciated, now use curl_file_create
      //   - Before       -> prefix file full path with @
      //
      if ( function_exists( 'curl_file_create' ) ) {
        $params['file'] = curl_file_create( $file_path );
      } else {
        $params['file'] = '@' . realpath( $file_path ) . ';filename=' . basename( $file_path );
      }


      return self::optimise_image( $params );
    }


  }

}