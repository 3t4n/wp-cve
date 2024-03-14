<?php
if ( ! class_exists( 'Zara4_WordPress_Settings' ) ) {


  /**
   * Class Zara4_WordPress_Settings
   */
  class Zara4_WordPress_Settings {

    // Use saved file instead of database - for testing
    const SETTINGS_PRIORITISE_FALLBACK  = false;

    // --- --- ---

    const SETTINGS_OPTION_NAME          = '_zara4_settings';
    const SETTINGS_FALLBACK_FILE_NAME   = '.zara4_settings';

    const KEY__API_CLIENT_ID 			      = 'api-client-id';
    const KEY__API_CLIENT_SECRET 	      = 'api-client-secret';
    const KEY__COMPRESS_SIZES			      = 'compress-size';
    const KEY__AUTO_OPTIMISE 			      = 'auto-optimise';
    const KEY__BACK_UP_ORIGINAL_IMAGES  = 'back-up-original-images';
    const KEY__MAINTAIN_EXIF            = 'maintain-exif';
    const KEY__COMPRESS_ALL_FEATURE     = 'compress-all-feature';
    const KEY__DASHBOARD_WIDGET_ENABLED = 'dashboard-widget-enabled';
    const KEY__METADATA_STORAGE_METHOD  = 'metadata-storage-method';


    private $data;


    /**
     *
     */
    public function __construct() {
      $this->reload();
    }


    public function save() {
      return self::write( $this->data );
    }

    public function reload() {
      $this->data = self::read();
    }

    public function __toString() {
      return json_encode($this->data);
    }


    // --- --- ---


    /**
     * Get the absolute path to the fallback settings file.
     *
     * @return string
     */
    private static function fallback_file_path() {
      /** @noinspection PhpUndefinedConstantInspection */
      return ABSPATH.DIRECTORY_SEPARATOR.self::SETTINGS_FALLBACK_FILE_NAME;
    }


    /**
     * Read settings.
     *
     * @return array|null
     */
    public static function read() {

      // Force cache clear
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_cache_delete( self::SETTINGS_OPTION_NAME );

      // Read settings from the WP options database
      /** @noinspection PhpUndefinedFunctionInspection */
      $data = get_option( self::SETTINGS_OPTION_NAME );

      // Fallback settings file if get_option not working
      if ( ( ! $data || self::SETTINGS_PRIORITISE_FALLBACK ) && file_exists( self::fallback_file_path() ) ) {
        $data = json_decode( file_get_contents( self::fallback_file_path() ), true );
      }

      return $data;
    }


    /**
     * Write settings.
     *
     * @param $settings
     * @return bool
     */
    private static function write( $settings ) {

      // Settings unchanged
      $settings_unchanged = json_encode( self::read() ) == json_encode( $settings );

      // Save settings in the WP options database
      /** @noinspection PhpUndefinedFunctionInspection */
      $saved = $settings_unchanged || update_option( self::SETTINGS_OPTION_NAME, $settings, false );

      // Fallback settings file if update_option not working
      $saved = file_put_contents( self::fallback_file_path(), json_encode( $settings ) ) || $saved;

      // Clean $saved into boolean value (file_put_contents returns bytes written)
      return $saved ? true : false;
    }


    /**
     * Clear settings.
     */
    public static function clear() {

      // Force cache clear
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_cache_delete( self::SETTINGS_OPTION_NAME );

      /** @noinspection PhpUndefinedFunctionInspection */
      delete_option( self::SETTINGS_OPTION_NAME );

      // Delete fallback file
      if( file_exists( self::fallback_file_path() ) ) {
        unlink( self::fallback_file_path() );
      }
    }


    /**
     *
     *
     * @return string[]
     */
    public static function thumbnail_size_names() {
      $names = array( 'original', 'thumbnail', 'medium', 'large' );

      global $_wp_additional_image_sizes;
      if ( is_array( $_wp_additional_image_sizes ) ) {
        $names = array_merge( array_keys( $_wp_additional_image_sizes ), $names );
      }

      return $names;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * Read an attribute as a string.
     *
     * @param $key
     * @param null $default
     * @return string
     */
    private function read_attribute_as_string( $key, $default = null ) {
      return is_array( $this->data ) && array_key_exists( $key, $this->data )
        ? (string) $this->data[$key] : $default;
    }


    /**
     * Write an attribute as a string.
     *
     * @param $key
     * @param $value
     */
    private function write_attribute_as_string( $key, $value ) {
      $this->data[$key] = (string) $value;
    }


    /**
     * Read an attribute as a boolean.
     *
     * @param string $key
     * @param bool $default
     * @return bool
     */
    private function read_attribute_as_boolean( $key, $default = true ) {
      return is_array( $this->data ) && array_key_exists( $key, $this->data )
        ? (boolean) $this->data[$key] : $default;
    }


    /**
     * Write an attribute as a boolean.
     *
     * @param $key
     * @param $value
     */
    private function write_attribute_as_boolean( $key, $value ) {
      $this->data[$key] = (boolean) $value;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * Do these settings have API credentials.
     *
     * @return bool
     */
    public function has_api_credentials() {
      return $this->api_client_id() && $this->api_client_secret();
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * Get the API client id
     *
     * @return string
     */
    public function api_client_id() {
      return $this->read_attribute_as_string( self::KEY__API_CLIENT_ID );
    }


    /**
     * Set the API client id
     *
     * @param string $api_client_id
     */
    public function set_api_client_id( $api_client_id ) {
      $this->write_attribute_as_string( self::KEY__API_CLIENT_ID, $api_client_id );
    }


    /**
     * Get the API client secret
     *
     * @return string
     */
    public function api_client_secret() {
      return $this->read_attribute_as_string( self::KEY__API_CLIENT_SECRET );
    }


    /**
     * Set the API client secret
     *
     * @param string $api_client_secret
     */
    public function set_api_client_secret( $api_client_secret ) {
      $this->write_attribute_as_string( self::KEY__API_CLIENT_SECRET, $api_client_secret );
    }


    /**
     * Get whether images should be automatically optimised.
     *
     * @return bool
     */
    public function auto_optimise() {
      return $this->read_attribute_as_boolean( self::KEY__AUTO_OPTIMISE );
    }


    /**
     * Set whether images should be automatically optimised.
     *
     * @param bool $auto_optimise
     */
    public function set_auto_optimise( $auto_optimise ) {
      $this->write_attribute_as_boolean( self::KEY__AUTO_OPTIMISE, $auto_optimise );
    }


    /**
     * @return bool
     */
    public function back_up_original_images() {
      return $this->read_attribute_as_boolean( self::KEY__BACK_UP_ORIGINAL_IMAGES, true );
    }


    /**
     * @param $back_up_original_images
     */
    public function set_back_up_original_images( $back_up_original_images ) {
      $this->write_attribute_as_boolean( self::KEY__BACK_UP_ORIGINAL_IMAGES, $back_up_original_images );
    }


    /**
     * Get whether an image size should be compressed.
     *
     * @param $name
     * @return bool
     */
    public function image_size_should_be_compressed( $name ) {

      // Assume should be compressed if no compression data is set.
      if( ! isset( $this->data[self::KEY__COMPRESS_SIZES] ) ) {
        return true;
      }

      $enabled_sizes = (array) $this->data[self::KEY__COMPRESS_SIZES];
      return (boolean) isset( $enabled_sizes[$name] ) ? $enabled_sizes[$name] : false;
    }


    /**
     * Set whether an image size should be compressed.
     *
     * @param $name
     * @param $should_be_compressed
     */
    public function set_image_size_should_be_compressed( $name, $should_be_compressed ) {

      // Ensure compression data is set.
      if( ! isset( $this->data[self::KEY__COMPRESS_SIZES] ) ) {
        $this->data[self::KEY__COMPRESS_SIZES] = array();
      }

      $this->data[self::KEY__COMPRESS_SIZES][$name] = (boolean) $should_be_compressed;
    }


    // --- --- --- --- ---


    /**
     * Get whether exif data should be maintained.
     *
     * @return bool
     */
    public function maintain_exif() {
      return $this->read_attribute_as_boolean( self::KEY__MAINTAIN_EXIF, false );
    }


    /**
     * Set whether exif data should be maintained.
     *
     * @param $maintain_exif
     */
    public function set_maintain_exif( $maintain_exif ) {
      $this->write_attribute_as_boolean( self::KEY__MAINTAIN_EXIF, $maintain_exif );
    }


    // --- --- --- --- ---


    /**
     * Get whether the compress all feature is enabled. (Enabled by default)
     *
     * @return bool
     */
    public function compress_all_feature() {
      return $this->read_attribute_as_boolean( self::KEY__COMPRESS_ALL_FEATURE, true );
    }


    /**
     * Set whether the compress all feature is enabled.
     *
     * @param $compress_all_feature
     */
    public function set_compress_all_feature( $compress_all_feature ) {
      $this->write_attribute_as_boolean( self::KEY__COMPRESS_ALL_FEATURE, $compress_all_feature );
    }

    // ---

    /**
     * Get whether the dashboard widget is enabled. (Enabled by default)
     *
     * @return bool
     */
    public function dashboard_widget_enabled() {
      return $this->read_attribute_as_boolean( self::KEY__DASHBOARD_WIDGET_ENABLED, true );
    }


    /**
     * Set whether the dashboard widget is enabled.
     *
     * @param $dashboard_widget_enabled
     */
    public function set_dashboard_widget_enabled( $dashboard_widget_enabled ) {
      $this->write_attribute_as_boolean( self::KEY__DASHBOARD_WIDGET_ENABLED, $dashboard_widget_enabled );
    }


    // --- --- --- --- ---


    /**
     * Get the metadata storage method (database by default)
     *
     * @return bool
     */
    public function metadata_storage_method() {
      return $this->read_attribute_as_string( self::KEY__METADATA_STORAGE_METHOD, 'database' );
    }


    /**
     * @return bool
     */
    public function metadata_storage_method_is_database() {
      return $this->metadata_storage_method() == 'database';
    }


    /**
     * @return bool
     */
    public function metadata_storage_method_is_file_storage() {
      return $this->metadata_storage_method() == 'file-storage';
    }


    /**
     * Set the metadata storage method.
     *
     * @param $storage_method
     */
    public function set_metadata_storage_method( $storage_method ) {
      $this->write_attribute_as_string( self::KEY__METADATA_STORAGE_METHOD, $storage_method );
    }


    /**
     * Set the metadata storage method as 'database'
     */
    public function set_metadata_storage_method_as_database() {
      $this->set_metadata_storage_method( 'database' );
    }


    /**
     * Set the metadata storage method as 'file-storage'
     */
    public function set_metadata_storage_method_as_file_storage() {
      $this->set_metadata_storage_method( 'file-storage' );
    }


    // --- --- --- --- ---



    public function standard_compress_thumbnail_size_names() {
      $settings = $this;
      return array_filter(self::thumbnail_size_names(), function( $size ) use ( $settings ) {
        return $settings->image_size_should_be_compressed( $size );
      });
    }



  }

}