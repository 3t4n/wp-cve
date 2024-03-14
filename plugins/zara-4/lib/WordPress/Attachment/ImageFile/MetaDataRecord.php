<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_ImageFile_MetaDataRecord' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_ImageFile_MetaDataRecord
   */
  class Zara4_WordPress_Attachment_ImageFile_MetaDataRecord {

    private $file_meta_data;
    private $database_meta_data;


    /**
     * @param Zara4_WordPress_Attachment_ImageFile_ImageFile $image_file
     */
    public function __construct( Zara4_WordPress_Attachment_ImageFile_ImageFile $image_file ) {
      $image_file_path = $image_file->path();

      $this->file_meta_data = new Zara4_WordPress_Attachment_ImageFile_MetaDataFile( $image_file_path );
      $this->database_meta_data = new Zara4_WordPress_Attachment_ImageFile_MetaDataDatabase( $image_file_path );
    }


    /**
     * @return null|Zara4_WordPress_Attachment_ImageFile_MetaData
     */
    public function read() {

      $meta_data = $this->database_meta_data->read();

      // Reading database metadata failed for whatever reason, so fallback on file store metadata.
      if ( ! $meta_data ) {
        $meta_data = $this->file_meta_data->read();
      }

      return $meta_data;
    }


    /**
     * @param Zara4_WordPress_Attachment_ImageFile_MetaData $meta_data
     */
    public function write( Zara4_WordPress_Attachment_ImageFile_MetaData $meta_data ) {
      $this->file_meta_data->write( $meta_data );
      $this->database_meta_data->write( $meta_data );
    }


    /**
     *
     */
    public function clear() {
      $this->file_meta_data->clear();
      $this->database_meta_data->clear();
    }

  }

}