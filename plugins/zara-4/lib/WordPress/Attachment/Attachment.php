<?php
if ( ! class_exists( 'Zara4_WordPress_Attachment_Attachment' ) ) {


  /**
   * Class Zara4_WordPress_Attachment_Attachment
   */
  class Zara4_WordPress_Attachment_Attachment {

    const SETTINGS_OPTION_NAME = '_zara4_settings';
    const OPTIMISATION_OPTION_NAME = '_zara4_optimisation';

    private $id;


    /**
     * @param $attachment_id
     * @return bool
     */
    public static function id_is_image( $attachment_id ) {
      /** @noinspection PhpUndefinedFunctionInspection */
      return wp_attachment_is_image( $attachment_id );
    }


    /**
     * @param $image_id
     * @return mixed
     */
    public static function attachment_metadata( $image_id ) {
      /** @noinspection PhpUndefinedFunctionInspection */
      return wp_get_attachment_metadata( $image_id );
    }


    /**
     * @return mixed
     */
    public static function upload_dir() {
      /** @noinspection PhpUndefinedFunctionInspection */
      $dir = wp_upload_dir();
      return $dir['path'];
    }


    /**
     * Get the path of an image from it's attachment id.
     *
     * @param $image_id
     * @return string
     */
    public static function path_from_id( $image_id ) {
      /** @noinspection PhpUndefinedFunctionInspection */
      return get_attached_file( $image_id );
    }


    /**
     * @param $image_id
     * @return string
     */
    public static function full_size_path_from_id( $image_id ) {
      /** @noinspection PhpUndefinedFunctionInspection */
      $image = get_post( $image_id );
      return self::path_from_id( $image->ID );
    }


    /**
     * @return array
     */
    public static function all_ids() {

      $query_images_args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => - 1,
      );

      /** @noinspection PhpUndefinedClassInspection */
      $query_images = new \WP_Query( $query_images_args );
      /** @noinspection PhpUndefinedFieldInspection */
      $posts = $query_images->posts;

      $image_ids = array();
      foreach ( $posts as $image ) {
        $image_ids[] = $image->ID;
      }

      $image_ids = array_unique( $image_ids );
      sort( $image_ids );

      return $image_ids;
    }


    /**
     * @return array
     */
    public static function all_image_ids() {

      $all_attachment_ids = Zara4_WordPress_Attachment_Attachment::all_ids();

      $image_ids = array();
      foreach ( $all_attachment_ids as $image_id ) {
        if ( Zara4_WordPress_Attachment_Attachment::id_is_image( $image_id ) ) {
          $image_ids[] = $image_id;
        }
      }

      $image_ids = array_unique( $image_ids );
      sort( $image_ids );

      return $image_ids;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * @param $attachment_id
     */
    public function __construct( $attachment_id ) {
      $this->id = $attachment_id;

      $this->enforce_upgrade_legacy___v1_1();
    }


    /**
     * The path to the base directory where this Attachment and any re-sized versions are located.
     *
     * @return string
     */
    public function base_dir() {
      return dirname( self::path_from_id( $this->id ) );
    }


    /**
     * @return null|array
     */
    public function resized_sizes() {
      $metadata = self::attachment_metadata( $this->id );
      return isset( $metadata['sizes'] ) ? $metadata['sizes'] : null;
    }


    /**
     * @param null $resized_sizes
     * @return Zara4_WordPress_Attachment_ImageFile_ImageFile[]
     */
    public function resized_image_files( $resized_sizes = null ) {

      $image_files = array();

      $upload_dir = $this->base_dir();
      $resized_sizes = $resized_sizes ? $resized_sizes : $this->resized_sizes();

      if ( is_array( $resized_sizes ) ) {
        foreach( $resized_sizes as $size_name => $size ) {
          $image_files[$size_name] = new Zara4_WordPress_Attachment_ImageFile_ImageFile( $upload_dir.DIRECTORY_SEPARATOR.$size['file'] );
        }
      }

      return $image_files;
    }


    /**
     * Get the original (full-size) ImageFile.
     *
     * @return Zara4_WordPress_Attachment_ImageFile_ImageFile
     */
    public function original_image_file() {
      $path = self::path_from_id( $this->id );
      return new Zara4_WordPress_Attachment_ImageFile_ImageFile( $path );
    }


    /**
     * Get all the ImageFiles for this WordPressImage (includes original and re-sized, indexed by size)
     *
     * @param null $resized_sizes
     * @return Zara4_WordPress_Attachment_ImageFile_ImageFile[]
     */
    public function all_image_files( $resized_sizes = null ) {
      $image_files = $this->resized_image_files( $resized_sizes );
      $image_files['original'] = $this->original_image_file();
      return $image_files;
    }


    /**
     * Has this Attachment been compressed?
     *
     * @return bool
     */
    public function is_compressed() {
      return $this->original_image_file()->is_compressed();
    }


    /**
     * @return bool
     */
    public function atleast_one_size_is_compressed() {
      $image_files = $this->all_image_files();
      foreach($image_files as $image_file) {
        if ( $image_file->is_compressed() ) {
          return true;
        }
      }
      return false;
    }


    /**
     * Has this WordPressImage been compressed?
     *
     * @return Zara4_WordPress_Attachment_ImageFile_ImageFile[]
     */
    public function compressed_image_files() {
      $compressed_image_files = array();
      $all_image_files = $this->all_image_files();
      foreach($all_image_files as $size => $image_file) {
        if ( $image_file->is_compressed() ) {
          $compressed_image_files[$size] = $image_file;
        }
      }
      return $compressed_image_files;
    }


    /**
     * Has this WordPressImage been compressed?
     *
     * @return Zara4_WordPress_Attachment_ImageFile_ImageFile[]
     */
    public function image_files_with_backup() {
      $image_files_with_backup = array();
      $all_image_files = $this->all_image_files();
      foreach($all_image_files as $size => $image_file) {
        if ( $image_file->has_backup() ) {
          $image_files_with_backup[$size] = $image_file;
        }
      }
      return $image_files_with_backup;
    }


    /**
     * @return int
     */
    public function original_file_size() {
      $original_image = $this->original_image_file();
      return $original_image->original_file_size();
    }


    /**
     * Does this Attachment have a backup.
     *
     * @return bool
     */
    public function has_backup() {
      $images_with_backup = $this->image_files_with_backup();
      return count($images_with_backup) > 0;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /*
     *    _____
     *   / ____|
     *  | |      ___   _ __ ___   _ __   _ __  ___  ___  ___
     *  | |     / _ \ | '_ ` _ \ | '_ \ | '__|/ _ \/ __|/ __|
     *  | |____| (_) || | | | | || |_) || |  |  __/\__ \\__ \
     *   \_____|\___/ |_| |_| |_|| .__/ |_|   \___||___/|___/
     *                           | |
     *                           |_|
     */


    /**
     * Optimise an image from the given WordPress id.
     *
     * @param Zara4_WordPress_Settings $settings
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData[]
     */
    public function compress( Zara4_WordPress_Settings $settings ) {

      Zara4_WordPress_Validation::validate_current_user_capabilities();

      if ( self::id_is_image( $this->id ) ) {

        Zara4_WordPress_Validation::validate_image_id( $this->id );


        //
        // Catch where the image is already optimised.
        //
        if ( $this->is_compressed() ) {

          $response = array();
          $compressed_image_files = $this->compressed_image_files();

          foreach ( $compressed_image_files as $image_file ) {
            $response[] = $image_file->meta_data();
          }
        }


        $r = $this->compress_sizes( $settings->standard_compress_thumbnail_size_names(), $settings );

        // Catch error
        if ( isset( $r['error'] ) ) {
          return $r;
        }

        return $this->generate_response();

      }
      return false;
    }


    /**
     * @param array $sizes
     * @param Zara4_WordPress_Settings $settings
     * @return Zara4_WordPress_Attachment_ImageFile_MetaData[]
     */
    public function compress_sizes( array $sizes, Zara4_WordPress_Settings $settings ) {

      $response = array();

      $access_token = Zara4_WordPress_Zara4::generate_access_token_using_settings( $settings );
      $upload_dir = $this->base_dir();


      if ( in_array( 'original', $sizes ) ) {
        $response['original'] = $this->original_image_file()->compress( $settings, $access_token );
      }


      $resized_sizes = self::resized_sizes();
      foreach( $resized_sizes as $size_name => $size ) {

        if ( ! in_array( $size_name, $sizes ) ) { continue; }

        $path = $upload_dir.DIRECTORY_SEPARATOR.$size['file'];
        if ( file_exists( $path ) !== false ) {
          $image_file = new Zara4_WordPress_Attachment_ImageFile_ImageFile( $path );
          $r = $image_file->compress( $settings, $access_token );

          // Catch error
          if ( isset( $r['error'] ) ) {
            return $r;
          }

          $response[$size_name] = $r;
        }

      }

      return $response;
    }


    /**
     * @param $sizes
     */
    public function handle_upload_event_compress( $sizes ) {

      $settings = new Zara4_WordPress_Settings();
      $access_token = Zara4_WordPress_Zara4::generate_access_token_using_settings( $settings );


      if ( $settings->auto_optimise() && $settings->has_api_credentials() ) {

        //
        // Optimise Thumbnails
        //
        if ( ! empty( $sizes ) ) {
          $base_dir = Zara4_WordPress_Attachment_Attachment::upload_dir();
          foreach ( $sizes as $size_name => $size ) {
            if ( $settings->image_size_should_be_compressed( $size_name ) ) {

              if ( ! is_array( $size ) ) { continue; }
              if ( ! array_key_exists( 'file', $size ) ) { continue; }
              $path = $base_dir . DIRECTORY_SEPARATOR . $size['file'];
              $image_file = new Zara4_WordPress_Attachment_ImageFile_ImageFile( $path );
              $response = (array) $image_file->compress( $settings, $access_token );

              // If we encounter an error stop trying to compress
              if ( isset( $response['error'] ) ) {
                return;
              }

            }
          }
        }


        //
        // Optimise Original
        //
        if ( $settings->image_size_should_be_compressed( 'original' ) ) {
          $original_image_file = $this->original_image_file();
          if ( $original_image_file ) {
            $original_image_file->compress( $settings, $access_token );
          }
        }


      }
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /*
     *   _____             _                        ____                _
     *  |  __ \           | |                      |  _ \              | |
     *  | |__) | ___  ___ | |_  ___   _ __  ___    | |_) |  __ _   ___ | | __ _   _  _ __
     *  |  _  / / _ \/ __|| __|/ _ \ | '__|/ _ \   |  _ <  / _` | / __|| |/ /| | | || '_ \
     *  | | \ \|  __/\__ \| |_| (_) || |  |  __/   | |_) || (_| || (__ |   < | |_| || |_) |
     *  |_|  \_\\___||___/ \__|\___/ |_|   \___|   |____/  \__,_| \___||_|\_\ \__,_|| .__/
     *                                                                              | |
     *                                                                              |_|
     */


    /**
     * Removes Zara 4 images and restores originals (if possible)
     *
     * @throws \Exception
     */
    public function restore_backup() {

      Zara4_WordPress_Validation::validate_current_user_capabilities();

      if ( self::id_is_image( $this->id ) ) {

        Zara4_WordPress_Validation::validate_image_id( $this->id );

        $images_files_with_backup = $this->image_files_with_backup();
        foreach ( $images_files_with_backup as $image_file ) {
          $image_file->restore_backup();
        }

      }
    }


    /**
     * @param array $sizes
     * @return array
     */
    public function restore_backup_for_sizes( array $sizes ) {

      $response = array();
      $upload_dir = $this->base_dir();


      if ( in_array('original', $sizes) ) {
        $response['original'] = $this->original_image_file()->restore_backup();
      }


      $resized_sizes = self::resized_sizes();
      foreach ( $resized_sizes as $size_name => $size ) {
        if ( ! in_array( $size_name, $sizes ) ) { continue; }

        $path = $upload_dir.DIRECTORY_SEPARATOR.$size['file'];

        if ( file_exists( $path ) !== false ) {
          $image_file = new Zara4_WordPress_Attachment_ImageFile_ImageFile( $path );
          $response[$size_name] = $image_file->restore_backup();
        }
      }

      return $response;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /*
     *   _____         _        _            ____                _
     *  |  __ \       | |      | |          |  _ \              | |
     *  | |  | |  ___ | |  ___ | |_  ___    | |_) |  __ _   ___ | | __ _   _  _ __
     *  | |  | | / _ \| | / _ \| __|/ _ \   |  _ <  / _` | / __|| |/ /| | | || '_ \
     *  | |__| ||  __/| ||  __/| |_|  __/   | |_) || (_| || (__ |   < | |_| || |_) |
     *  |_____/  \___||_| \___| \__|\___|   |____/  \__,_| \___||_|\_\ \__,_|| .__/
     *                                                                       | |
     *                                                                       |_|
     */


    /**
     * Removes Zara 4 images and restores originals (if possible)
     *
     * @throws \Exception
     */
    public function delete_backup() {

      Zara4_WordPress_Validation::validate_current_user_capabilities();

      if ( self::id_is_image( $this->id ) ) {

        Zara4_WordPress_Validation::validate_image_id( $this->id );


        $images_files_with_backup = $this->image_files_with_backup();

        foreach($images_files_with_backup as $image_file) {
          $image_file->delete_backup();
        }

      }
    }


    /**
     * @param array $sizes
     * @return array
     */
    public function delete_backup_for_sizes( array $sizes ) {

      $response = array();
      $upload_dir = $this->base_dir();

      // Original file
      if ( in_array('original', $sizes)) {
        $response['original'] = $this->original_image_file()->delete_backup();
      }

      // Resized
      $resized_sizes = self::resized_sizes();
      foreach( $resized_sizes as $size_name => $size ) {
        if ( ! in_array( $size_name, $sizes ) ) { continue; }

        $path = $upload_dir.DIRECTORY_SEPARATOR.$size['file'];

        if ( file_exists( $path ) !== false ) {
          $image_file = new Zara4_WordPress_Attachment_ImageFile_ImageFile( $path );
          $response[$size_name] = $image_file->delete_backup();
        }
      }

      return $response;
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /*
     *   ____          _  _        _____               _             _             __  ______             _             _
     *  |  _ \        | || |      |_   _|             | |           | |           / / |  ____|           | |           | |
     *  | |_) | _   _ | || | __     | |   _ __    ___ | | _   _   __| |  ___     / /  | |__   __  __ ___ | | _   _   __| |  ___
     *  |  _ < | | | || || |/ /     | |  | '_ \  / __|| || | | | / _` | / _ \   / /   |  __|  \ \/ // __|| || | | | / _` | / _ \
     *  | |_) || |_| || ||   <     _| |_ | | | || (__ | || |_| || (_| ||  __/  / /    | |____  >  <| (__ | || |_| || (_| ||  __/
     *  |____/  \__,_||_||_|\_\   |_____||_| |_| \___||_| \__,_| \__,_| \___| /_/     |______|/_/\_\\___||_| \__,_| \__,_| \___|
     *
     */


    /**
     * @return string
     */
    private function exclude_from_bulk_path() {
      return self::full_size_path_from_id( $this->id ) . ".exclude_from_bulk";
    }


    /**
     * Set that this Attachment should be excluded from bulk compression.
     */
    public function exclude_from_bulk_compression() {
      if ( ! $this->should_be_excluded_from_bulk_compression() ) {

        //
        // File Storage
        //
        file_put_contents( $this->exclude_from_bulk_path(), 'exclude-from-bulk' );


        //
        // Database Storage
        //
        global $wpdb;
        $table_name = $wpdb->prefix . Zara4_WordPress_Install_Database::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME;

        $file_path = $this->full_size_path_from_id( $this->id );

        /** @noinspection PhpUndefinedMethodInspection */
        $wpdb->replace(
          $table_name,
          array(
            'file-path'      => $file_path,
            'file-path-hash' => md5( $file_path )
          )
        );

      }
    }


    /**
     * Set that this Attachment should be included in bulk compression.
     */
    public function include_in_bulk_compression() {
      if ( $this->should_be_excluded_from_bulk_compression() ) {

        //
        // File Storage
        //
        unlink( $this->exclude_from_bulk_path() );


        //
        // Database Storage
        //
        global $wpdb;
        $table_name = $wpdb->prefix . Zara4_WordPress_Install_Database::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME;

        /** @noinspection PhpUndefinedMethodInspection */
        $wpdb->delete( $table_name, array( 'file-path-hash' => md5( $this->full_size_path_from_id( $this->id ) ) ) );

      }
    }


    /**
     * Should this Attachment be excluded from bulk compression?
     *
     * @return bool
     */
    public function should_be_excluded_from_bulk_compression() {

      $database = new Zara4_WordPress_Database();


      //
      // ### Rare error scenario ###
      //
      // For what ever reason the table doesn't exist
      //
      if ( ! $database->table_exists( Zara4_WordPress_Install_Database::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME ) ) {
        error_log('ZARA 4 - CANNOT READ EXCLUDE BULK FROM DATABASE TABLE: Table doesn\'t exist and cannot be created');
        return file_exists( $this->exclude_from_bulk_path() );
      }

      // --- --- --

      //
      // We know the metadata table exists from this point forward.
      //
      $table_name = $database->get_prefix() . Zara4_WordPress_Install_Database::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME;
      $query = $database->prepare( "SELECT * FROM `$table_name` WHERE `file-path-hash` = %s", md5( $this->full_size_path_from_id($this->id) ) );


      /** @noinspection PhpUndefinedMethodInspection */
      $result = $database->get_row( $query );

      return $result != null || file_exists( $this->exclude_from_bulk_path() );
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * Call when this Attachment has been deleted.
     * Cleans up any Zara 4 data about this image.
     */
    public function delete() {
      if ( self::id_is_image( $this->id ) ) {

        // Ensure bulk exclusion meta data is removed
        $this->include_in_bulk_compression();

        // For each re-sized image (including the original)...
        $image_files = $this->all_image_files();
        foreach ( $image_files as $image_file ) {

          // ... delete backup if available
          if ( $image_file->has_backup() ) {
            $image_file->delete_backup();
          }

          // ... clear stored metadata
          $image_file->meta_data_record()->clear();
        }
      }
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /**
     * @return array
     */
    public function generate_response() {

      $compressed_image_files = $this->compressed_image_files();

      if ( count( $compressed_image_files ) == 0 ) {
        return array(
          'compressed' => false,
        );
      }

      $best_compressed_image = null;
      $best_percentage_saving = null;

      foreach ( $compressed_image_files as $compressed_image_file ) {
        $percentage_saving = $compressed_image_file->meta_data()->get_percentage_saving();

        if ( ! $best_compressed_image || $percentage_saving > $best_percentage_saving ) {
          $best_compressed_image = $compressed_image_file;
          $best_percentage_saving = $percentage_saving;
          continue;
        }
      }

      // --- --- ---

      $metadata = $best_compressed_image->meta_data();

      $response = array();
      $response['compressed'] = true;
      $response['original-file-size'] = $metadata->get_original_file_size();
      $response['compressed-file-size'] = $metadata->get_compressed_file_size();
      $response['bytes-saved'] = $metadata->get_original_file_size() - $metadata->get_compressed_file_size();
      $response['percentage-saving'] = $metadata->get_percentage_saving();
      $response['has-backup'] = $best_compressed_image->has_backup();

      return $response;
    }




    /*
     *   _    _                                _           ______                          _
     *  | |  | |                              | |         |  ____|                        | |
     *  | |  | | _ __    __ _  _ __  __ _   __| |  ___    | |__  _ __  ___   _ __ ___     | |      ___   __ _   __ _   ___  _   _
     *  | |  | || '_ \  / _` || '__|/ _` | / _` | / _ \   |  __|| '__|/ _ \ | '_ ` _ \    | |     / _ \ / _` | / _` | / __|| | | |
     *  | |__| || |_) || (_| || |  | (_| || (_| ||  __/   | |   | |  | (_) || | | | | |   | |____|  __/| (_| || (_| || (__ | |_| |
     *   \____/ | .__/  \__, ||_|   \__,_| \__,_| \___|   |_|   |_|   \___/ |_| |_| |_|   |______|\___| \__, | \__,_| \___| \__, |
     *          | |      __/ |                                                                           __/ |               __/ |
     *          |_|     |___/                                                                           |___/               |___/
     */

    /**
     * @return array
     */
    public function enforce_upgrade_legacy___v1_1() {

      $original_file_meta_data = self::attachment_metadata( $this->id );
      if ( $original_file_meta_data ) {
        $file = $original_file_meta_data['file'];
        if ( $file ) {

          /** @noinspection PhpUndefinedFunctionInspection */
          $meta = get_post_meta( $this->id, self::OPTIMISATION_OPTION_NAME, true );


          // If we have legacy meta-data, this image has been compressed with a v1.1 plugin.
          if ($meta) {


            $compressed = isset( $meta['bytes_compressed'] );

            $request_id        = isset( $meta['request_id'] ) ? $meta['request_id'] : null;
            $original_size     = isset( $meta['bytes_original'] ) ? $meta['bytes_original'] : null;
            $compressed_size   = isset( $meta['bytes_compressed'] ) ? $meta['bytes_compressed'] : null;
            $bytes_saved       = isset( $meta['bytes_saving'] ) ? $meta['bytes_saving'] : null;
            $percentage_saving = isset( $meta['percentage_saving'] ) ? $meta['percentage_saving'] : null;

            //
            // Correction Corrupt Data
            //
            if ( $original_size != null && $compressed_size == null ) {
              if ( $bytes_saved ) {
                $compressed_size = $original_size - $bytes_saved;
              }
              if ( $percentage_saving ) {
                $compressed_size = ( 1 - $percentage_saving ) * $original_size;
              }
            }
            if ( $compressed_size != null && $original_size == null ) {
              if ( $bytes_saved ) {
                $original_size = $compressed_size + $bytes_saved;
              }
              if ( $percentage_saving ) {
                $original_size = (1 - $percentage_saving) / $compressed_size;
              }
            }
            if ( $original_size != null && $compressed_size != null ) {
              if ( $bytes_saved == null) {
                $bytes_saved = $original_size - $compressed_size;
              }
              if ( $percentage_saving == null ) {
                $percentage_saving = 1 - ($compressed_size / $original_size);
              }
            }

            $compressed = ( $compressed_size != null ) && ( $percentage_saving != null )
              ? $compressed : false;

            // --- --- ---

            $image_file = $this->original_image_file();

            if ( $compressed ) {
              $original_file_meta_data = new Zara4_WordPress_Attachment_ImageFile_MetaData();
              $original_file_meta_data->set_request_id( $request_id );
              $original_file_meta_data->set_original_file_size( $original_size );
              //$original_file_meta_data->set_original_file_hash( Util::hash_file( $image_file->backup_path() ) );
              $original_file_meta_data->set_original_file_hash( "unknown" );
              $original_file_meta_data->set_compressed_file_size( $compressed_size );
              //$original_file_meta_data->set_compressed_file_hash( Util::hash_file( $image_file->path() ) );
              $original_file_meta_data->set_compressed_file_hash( "unknown" );
              $original_file_meta_data->set_is_compressed( $compressed );
              $original_file_meta_data->set_bytes_saved( $bytes_saved );
              $original_file_meta_data->set_percentage_saving( $percentage_saving );
              $original_file_meta_data->set_no_saving_available( false );

              // Clear legacy meta data
              /** @noinspection PhpUndefinedFunctionInspection */
              update_post_meta( $this->id, self::OPTIMISATION_OPTION_NAME, null );

              // Write new meta data
              $image_file->meta_data_record()->write($original_file_meta_data);

            }

            // --- --- ---

            $resized_images = $this->resized_image_files();
            foreach ( $resized_images as $resized_image ) {

              // Assume a re-sized image has not been compressed by default.
              $is_compressed = false;

              // If the image has a backup, it must have been compressed.
              $is_compressed = $is_compressed || $resized_image->has_backup();

              // If neither the original image nor the re-sized image has a backup, but we know the original image has
              // been compressed; there is no way to know if the re-sized image has been compressed or not...
              // So for the sake of preventing double compression, assume the re-sized image has been compressed.
              $is_compressed = $is_compressed || ( $compressed && ! $image_file->has_backup() && ! $resized_image->has_backup() );

              if ( $is_compressed ) {

                $original_file_path = $resized_image->backup_path();
                $compressed_file_path = $resized_image->path();

                $original_file_size = filesize( $original_file_path );
                $compressed_file_size = filesize( $compressed_file_path );

                $meta_data = new Zara4_WordPress_Attachment_ImageFile_MetaData();
                $meta_data->set_request_id( 'unknown' );
                $meta_data->set_original_file_size( $original_file_size );
                //$meta_data->set_original_file_hash( Util::hash_file( $original_file_path ) );
                $meta_data->set_original_file_hash( "unknown" );
                $meta_data->set_compressed_file_size( $compressed_file_size );
                //$meta_data->set_compressed_file_hash( Util::hash_file( $compressed_file_path ) );
                $meta_data->set_compressed_file_hash( "unknown" );
                $meta_data->set_is_compressed( true );
                $meta_data->set_bytes_saved( $original_file_size - $compressed_file_size );
                $meta_data->set_percentage_saving( ( 1 - ( $compressed_file_size / $original_file_size ) ) * 100 );
                $meta_data->set_no_saving_available( false );
                $resized_image->meta_data_record()->write( $meta_data );
              }
            }


          }

        }
      }

    }

  }

}