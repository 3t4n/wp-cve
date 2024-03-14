<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_ImageFile_MetaDataDatabase' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_ImageFile_MetaDataDatabase
   */
  class Zara4_WordPress_Attachment_ImageFile_MetaDataDatabase {

    private $image_path;


    /**
     * @param $image_path
     */
    public function __construct( $image_path ) {
      $this->image_path = $image_path;
    }


    /**
     * Read the metadata.
     *
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData|null
     */
    public function read() {

      $database = new Zara4_WordPress_Database();

      //
      // ### Rare error scenario ###
      //
      // For what ever reason the metadata table doesn't exist, so no metadata can be read
      //
      if ( ! $database->table_exists( Zara4_WordPress_Install_Database::FILE_COMPRESSION_METADATA_TABLE_NAME ) ) {
        error_log('ZARA 4 - CANNOT READ METADATA FROM DATABASE TABLE: Table doesn\'t exist and cannot be created');
        return null;
      }

      // --- --- --

      //
      // We know the metadata table exists from this point forward.
      //
      $table_name = $database->get_prefix() . Zara4_WordPress_Install_Database::FILE_COMPRESSION_METADATA_TABLE_NAME;
      $query = $database->prepare( "SELECT * FROM `$table_name` WHERE `file-path-hash` = %s", md5( $this->image_path ) );


      /** @noinspection PhpUndefinedMethodInspection */
      $result = $database->get_row( $query );

      // No metadata record available
      if ( ! $result ) { return null; }


      $result = (array) $result;
      unset( $result['request-id'] );

      return new Zara4_WordPress_Attachment_ImageFile_MetaData( (array) $result );
    }


    /**
     * @param Zara4_WordPress_Attachment_ImageFile_MetaData $metadata
     */
    public function write(Zara4_WordPress_Attachment_ImageFile_MetaData $metadata) {

      global $wpdb;
      $table_name = $wpdb->prefix . Zara4_WordPress_Install_Database::FILE_COMPRESSION_METADATA_TABLE_NAME;

      /** @noinspection PhpUndefinedMethodInspection */
      $wpdb->replace(
        $table_name,
        array(
          'file-path-hash'        => md5( $this->image_path ),
          'file-path'             => $this->image_path,
          'request-id'            => $metadata->get_request_id() ? $metadata->get_request_id() : 'unknown',
          'is-compressed'         => $metadata->get_is_compressed() ? '1' : '0',
          'original-file-size'    => $metadata->get_original_file_size(),
          'compressed-file-size'  => $metadata->get_compressed_file_size(),
          'original-file-hash'    => $metadata->get_original_file_hash(),
          'compressed-file-hash'  => $metadata->get_compressed_file_hash(),
          'bytes-saved'           => $metadata->get_bytes_saved(),
          'percentage-saving'     => $metadata->get_percentage_saving(),
          'no-saving-available'   => $metadata->get_no_saving_available() ? '1' : '0',
          'plugin-version'        => $metadata->get_plugin_version(),
        )
      );
    }


    /**
     *
     */
    public function clear() {

      global $wpdb;
      $table_name = $wpdb->prefix . Zara4_WordPress_Install_Database::FILE_COMPRESSION_METADATA_TABLE_NAME;

      /** @noinspection PhpUndefinedMethodInspection */
      $wpdb->delete( $table_name, array( 'file-path-hash' => md5( $this->image_path ) ) );

    }

  }

}