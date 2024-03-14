<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_ImageFile_MetaData' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_ImageFile_MetaData
   */
  class Zara4_WordPress_Attachment_ImageFile_MetaData {

    const KEY__PLUGIN_VERSION       = 'plugin-version';
    const KEY__REQUEST_ID           = 'request-id';
    const KEY__IS_COMPRESSED        = 'is-compressed';
    const KEY__ORIGINAL_FILE_SIZE   = 'original-file-size';
    const KEY__COMPRESSED_FILE_SIZE = 'compressed-file-size';
    const KEY__ORIGINAL_FILE_HASH   = 'original-file-hash';
    const KEY__COMPRESSED_FILE_HASH = 'compressed-file-hash';
    const KEY__BYTES_SAVED          = 'bytes-saved';
    const KEY__PERCENTAGE_SAVING    = 'percentage-saving';
    const KEY__NO_SAVING_AVAILABLE  = 'no-saving-available';

    // --- --- --- --- ---

    public $data;


    /**
     * @param $data
     */
    public function __construct( $data = array() ) {
      $this->data = $data;

      $this->set_plugin_version( ZARA4_VERSION );
    }


    /**
     * @return array
     */
    public function to_json_obj() {
      return $this->data;
    }


    /**
     * @return string
     */
    public function to_json() {
      return json_encode( $this->to_json_obj() );
    }


    /**
     * @param $json
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData
     */
    public static function from_json( $json ) {
      return new self( (array) json_decode( $json ) );
    }


    /**
     * @param $key
     * @return mixed|null
     */
    public function get( $key ) {
      if ( ! array_key_exists( $key, $this->data ) ) { return null; }
      return $this->data[$key];
    }


    /**
     * @param string $key
     * @param mixed $value
     */
    public function set( $key, $value ) {
      $this->data[$key] = $value;
    }


    /**
     * @param $key
     */
    public function clear( $key ) {
      unset($this->data[$key]);
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * Get the plugin version.
     *
     * @return string|null
     */
    public function get_plugin_version() {
      return $this->get( self::KEY__PLUGIN_VERSION );
    }


    /**
     * Set the plugin version.
     *
     * @param $plugin_version
     */
    public function set_plugin_version( $plugin_version ) {
      $this->set( self::KEY__PLUGIN_VERSION, $plugin_version );
    }


    // --- --- ---


    /**
     * Get the request id.
     *
     * @return string|null
     */
    public function get_request_id() {
      return $this->get( self::KEY__REQUEST_ID );
    }


    /**
     * Set the request id.
     *
     * @param $request_id
     */
    public function set_request_id( $request_id ) {
      $this->set( self::KEY__REQUEST_ID, $request_id );
    }


    // --- --- ---


    /**
     * Has the image been compressed?
     *
     * @return mixed|null
     */
    public function get_is_compressed() {
      return $this->get( self::KEY__IS_COMPRESSED );
    }


    /**
     * Set whether the image has been compressed.
     *
     * @param $is_compressed
     */
    public function set_is_compressed( $is_compressed ) {
      $this->set( self::KEY__IS_COMPRESSED, $is_compressed );
    }


    // --- --- ---


    /**
     * Get the file size of the original image.
     *
     * @return mixed|null
     */
    public function get_original_file_size() {
      return $this->get( self::KEY__ORIGINAL_FILE_SIZE );
    }


    /**
     * Set the file size of the original image.
     *
     * @param $original_file_size
     */
    public function set_original_file_size( $original_file_size ) {
      $this->set( self::KEY__ORIGINAL_FILE_SIZE, $original_file_size );
    }


    /**
     *
     */
    public function clear_original_file_size() {
      $this->clear( self::KEY__ORIGINAL_FILE_SIZE );
    }


    // --- --- ---


    /**
     * Get the file size of the compressed image.
     *
     * @return mixed|null
     */
    public function get_compressed_file_size() {
      return $this->get( self::KEY__COMPRESSED_FILE_SIZE );
    }


    /**
     * Set the file size of the compressed image.
     *
     * @param $compressed_file_size
     */
    public function set_compressed_file_size( $compressed_file_size ) {
      $this->set( self::KEY__COMPRESSED_FILE_SIZE, $compressed_file_size );
    }


    /**
     *
     */
    public function clear_compressed_file_size() {
      $this->clear( self::KEY__COMPRESSED_FILE_SIZE );
    }


    // --- --- ---


    /**
     * Get the file hash of the original image.
     *
     * @return mixed|null
     */
    public function get_original_file_hash() {
      return $this->get( self::KEY__ORIGINAL_FILE_HASH );
    }


    /**
     * Set the file hash of the original image.
     *
     * @param $original_file_hash
     */
    public function set_original_file_hash( $original_file_hash ) {
      $this->set( self::KEY__ORIGINAL_FILE_HASH, $original_file_hash );
    }


    /**
     *
     */
    public function clear_original_file_hash() {
      $this->clear( self::KEY__ORIGINAL_FILE_HASH );
    }


    // --- --- ---


    /**
     * Get the file hash of the compressed image.
     *
     * @return mixed|null
     */
    public function get_compressed_file_hash() {
      return $this->get( self::KEY__COMPRESSED_FILE_HASH );
    }


    /**
     * Set the file hash of the compressed image.
     *
     * @param $compressed_file_hash
     */
    public function set_compressed_file_hash( $compressed_file_hash ) {
      $this->set( self::KEY__COMPRESSED_FILE_HASH, $compressed_file_hash );
    }


    /**
     *
     */
    public function clear_compressed_file_hash() {
      $this->clear( self::KEY__COMPRESSED_FILE_HASH );
    }


    // --- --- ---


    /**
     * Get the bytes saved by compression.
     *
     * @return mixed|null
     */
    public function get_bytes_saved() {
      return $this->get( self::KEY__BYTES_SAVED );
    }


    /**
     * Set the bytes saved by compression.
     *
     * @param $bytes_saved
     */
    public function set_bytes_saved( $bytes_saved ) {
      $this->set( self::KEY__BYTES_SAVED, $bytes_saved );
    }


    /**
     *
     */
    public function clear_bytes_saved() {
      $this->clear( self::KEY__BYTES_SAVED );
    }


    // --- --- ---


    /**
     * Get the percentage saving.
     *
     * @return mixed|null
     */
    public function get_percentage_saving() {
      return $this->get( self::KEY__PERCENTAGE_SAVING );
    }


    /**
     * Set the percentage saving.
     *
     * @param $percentage_saving
     */
    public function set_percentage_saving( $percentage_saving ) {
      $this->set( self::KEY__PERCENTAGE_SAVING, $percentage_saving );
    }


    /**
     *
     */
    public function clear_percentage_saving() {
      $this->clear( self::KEY__PERCENTAGE_SAVING );
    }


    // --- --- ---


    /**
     * @return mixed|null
     */
    public function get_no_saving_available() {
      return $this->get( self::KEY__NO_SAVING_AVAILABLE );
    }


    /**
     * @param $no_saving_available
     */
    public function set_no_saving_available( $no_saving_available ) {
      $this->set( self::KEY__NO_SAVING_AVAILABLE, $no_saving_available );
    }


    /**
     *
     */
    public function clear_no_saving_available() {
      $this->clear( self::KEY__NO_SAVING_AVAILABLE );
    }

  }

}