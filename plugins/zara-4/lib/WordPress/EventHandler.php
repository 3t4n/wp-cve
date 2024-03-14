<?php
if ( ! class_exists( 'Zara4_WordPress_EventHandler' ) ) {


  /**
   * Class Zara4_WordPress_EventHandler
   */
  class Zara4_WordPress_EventHandler {


    /**
     * Handle image delete event.
     *
     * @param $attachment_id
     */
    public static function delete_attachment( $attachment_id ) {
      $image = new Zara4_WordPress_Attachment_Attachment( $attachment_id );
      $image->delete();
    }


    /**
     * Handle new image upload event.
     *
     * @param $data
     * @param null $image_id
     * @return mixed
     */
    public static function upload_attachment( $data, $image_id = null ) {
      if ( Zara4_WordPress_Attachment_Attachment::id_is_image( $image_id ) ) {

        if ( array_key_exists( 'sizes', $data ) ) {
          $sizes = $data['sizes'];
          if ( is_array( $sizes ) ) {

            $attachment = new Zara4_WordPress_Attachment_Attachment( $image_id );
            $attachment->handle_upload_event_compress( $sizes );

          }
        }

      }
      return $data;
    }


  }

}