<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_ImageFile_MetaDataFile' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_ImageFile_MetaDataFile
   */
  class Zara4_WordPress_Attachment_ImageFile_MetaDataFile {

    private $image_path;


    /**
     * @param $image_path
     */
    public function __construct( $image_path ) {
      $this->image_path = $image_path;
    }


    /**
     * Generate the path of the metadata file associated with the given image path.
     *
     * @return string
     */
    private function path() {
      return $this->image_path . '.zara4-metadata';
    }


    /**
     * Read the metadata for the given image path.
     *
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData|null
     */
    public function read() {
      if ( ! $this->exists() ) { return null; }
      return Zara4_WordPress_Attachment_ImageFile_MetaData::from_json( file_get_contents( $this->path() ) );
    }


    /**
     * @param Zara4_WordPress_Attachment_ImageFile_MetaData $metadata
     */
    public function write( Zara4_WordPress_Attachment_ImageFile_MetaData $metadata ) {
      file_put_contents( $this->path(), $metadata->to_json() );
    }


    /**
     * @return bool
     */
    public function exists() {
      return file_exists( $this->path() );
    }


    /**
     *
     */
    public function clear() {
      if ( $this->exists() ) {
        unlink( $this->path() );
      }
    }

  }

}