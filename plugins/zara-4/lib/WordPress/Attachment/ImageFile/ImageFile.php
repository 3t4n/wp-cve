<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_ImageFile_ImageFile' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_ImageFile_ImageFile
   */
  class Zara4_WordPress_Attachment_ImageFile_ImageFile {

    private $path;


    /**
     *
     *
     * @param $path
     */
    public function __construct( $path ) {
      $this->path = $path;
    }


    /**
     *
     *
     * @param Zara4_WordPress_Settings $settings
     * @param string|null $access_token
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData
     */
    public function compress( Zara4_WordPress_Settings $settings, $access_token = null ) {

      // Catch already compressed
      if( $this->is_compressed() ) {
        return $this->generate_response();
      }

      $metadata = new Zara4_WordPress_Attachment_ImageFile_MetaData();
      $metadata->set_original_file_size( $this->original_file_size() );
      $metadata->set_original_file_hash( Zara4_WordPress_Util::hash_file( $this->path ) );


      //
      // Perform compression
      //
      @set_time_limit( 300 ); // Limit execution to 300 seconds (5 minutes)

      $params = array(
        'maintain-exif' => $settings->maintain_exif() ? 'yes' : 'no',
      );
      if ($access_token) { $params['access_token'] = $access_token; }
      $response = json_decode( Zara4_API_ImageProcessing_Image::optimise_image_from_file( $this->path, $params ) );


      //
      // Handle error
      //
      if( isset( $response->{'error'} ) ) {
        return array(
          'error' => $response->{'error'},
        );
      }


      //
      // Read MetaData from response
      //
      $compression = $response->{'compression'};
      $bytes_saving = $compression->{'bytes-saving'};
      $percentage_saving = $compression->{'percentage-saving'};

      $metadata->set_request_id( $response->{'request-id'} );
      $metadata->set_is_compressed( true );
      $metadata->set_bytes_saved( $bytes_saving );
      $metadata->set_percentage_saving( $percentage_saving );
      $metadata->set_no_saving_available( $bytes_saving < 0 );


      //
      // Download the new file and back up the original (if feature enabled)
      //
      $url = isset( $response->{'generated-images'}->{'urls'}[0] ) ? $response->{'generated-images'}->{'urls'}[0] : null;
      if ( $url ) {

        // Make a back up if enabled
        if ( $settings->back_up_original_images() ) {
          $this->backup();
        }

        // Download compressed image
        unlink( $this->path );
        file_put_contents( $this->path, Zara4_API_Communication_Util::get( $url, array( 'access_token' => $access_token ) ) );

        // Record metadata from downloaded compressed image
        $metadata->set_compressed_file_size( filesize( $this->path ) );
        $metadata->set_compressed_file_hash( Zara4_WordPress_Util::hash_file( $this->path ) );
      }


      //
      // Record MetaData
      //
      $this->meta_data_record()->write( $metadata );

      return $this->generate_response();
    }


    /**
     * @return Zara4_WordPress_Attachment_ImageFile_MetaDataRecord
     */
    public function meta_data_record() {
      return new Zara4_WordPress_Attachment_ImageFile_MetaDataRecord( $this );
    }


    /**
     * @return null|Zara4_WordPress_Attachment_ImageFile_MetaData
     */
    public function meta_data() {
      return $this->meta_data_record()->read();
    }


    /**
     * @return bool|mixed|null
     */
    public function is_compressed() {

      $metadata_record = new Zara4_WordPress_Attachment_ImageFile_MetaDataRecord( $this );
      if ( ! $metadata_record) { return false; }

      $metadata = $metadata_record->read();
      if( ! $metadata ) { return false; }

      return $metadata->get_is_compressed();
    }


    /**
     * The current file size of this image file.
     *
     * @return int
     */
    public function file_size() {
      return filesize( $this->path );
    }


    /**
     * The original (uncompressed) file size of this image.
     *
     * @return int
     */
    public function original_file_size() {
      if ( $this->has_backup() ) {
        return filesize( $this->backup_path() );
      }
      return $this->file_size();
    }


    /**
     * @return mixed
     */
    public function path() {
      return $this->path;
    }


    /*
     *   ____                _        _    _
     *  |  _ \              | |      | |  | |
     *  | |_) |  __ _   ___ | | __   | |  | | _ __
     *  |  _ <  / _` | / __|| |/ /   | |  | || '_ \
     *  | |_) || (_| || (__ |   <    | |__| || |_) |
     *  |____/  \__,_| \___||_|\_\    \____/ | .__/
     *                                       | |
     *                                       |_|
     */

    /**
     * Generate the back up path for this image.
     *
     * @return string
     */
    public function backup_path() {
      return $this->path . '.zara4-backup';
    }


    /**
     * Backup an image.
     *
     * @return bool
     */
    public function backup() {

      $backup_path = $this->backup_path();

      if ( ! file_exists( $this->path ) ) { return false; }
      if ( file_exists( $backup_path ) && ! unlink( $backup_path ) ) { return false; }

      return copy( $this->path, $backup_path );
    }


    /**
     * @return bool
     */
    public function has_backup() {
      return file_exists( $this->backup_path() );
    }


    /**
     * Restore the backed up image.
     *
     * @return bool
     */
    public function restore_backup() {

      $backup_path = $this->backup_path();

      $metadata = $this->meta_data();
      if ( ! $metadata ) { return false; }


      $this->meta_data_record()->clear();

      //$metadata->set_is_compressed( false );
      //$metadata->clear_compressed_file_size();
      //$metadata->clear_compressed_file_hash();
      //$metadata->clear_percentage_saving();
      //$metadata->clear_bytes_saved();
      //$this->meta_data_record()->write( $metadata );

      if ( ! file_exists( $this->path ) || ! file_exists( $backup_path ) ) { return false; }
      if ( ! unlink( $this->path ) ) { return false; }
      if ( ! copy( $backup_path, $this->path ) ) { return false; }

      return unlink( $backup_path );
    }


    /**
     * Delete backup image.
     *
     * @return bool
     */
    public function delete_backup() {
      if ( ! $this->has_backup() ) { return false; }
      return unlink( $this->backup_path() );
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * @return array
     */
    public function generate_response() {
      $response = $this->meta_data()->to_json_obj();
      $response['has-backup'] = $this->has_backup();
      return $response;
    }


  }

}