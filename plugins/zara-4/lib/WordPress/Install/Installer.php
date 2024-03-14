<?php
if ( ! class_exists( 'Zara4_WordPress_Install_Installer' ) ) {


  /**
   * Class Zara4_WordPress_Install_Installer
   */
  class Zara4_WordPress_Install_Installer {

    const ZARA4_VERSION_OPTION_KEY = 'zara-4_version';


    /**
     * THIS IS CALLED EVERY TIME THE PLUGIN IS LOADED.
     *
     * It is VERY important that this function NEVER causes an error.
     */
    public static function install() {
      try {

        // Read last version installed
        /** @noinspection PhpUndefinedFunctionInspection */
        $current_version_installed = get_option( self::ZARA4_VERSION_OPTION_KEY );

        $first_install = ! $current_version_installed;
        $current_version_less_than__1_2_6 = version_compare( $current_version_installed, '1.2.6', '<' ); // Before v1.2.6
        $new_version_greater_than_or_equal__1_2_6 = version_compare( ZARA4_VERSION, '1.2.6', '>=' );

        // Happens on first install or upgrade from v1.1 to v1.2
        if ( $first_install ) {
          self::preupgrade_backup();
        }

        // --- --- ---

        $database = new Zara4_WordPress_Database();
        $prefix = $database->get_prefix();

        // --- --- ---

        //if ( $first_install || $current_version_less_than__1_2_6 ) {
          if ( ! $database->table_exists( Zara4_WordPress_Install_Database::FILE_COMPRESSION_METADATA_TABLE_NAME ) ) {
            Zara4_WordPress_Install_Database::create_file_compression_metadata_table();
          }
          if ( ! $database->table_exists( Zara4_WordPress_Install_Database::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME ) ) {
            Zara4_WordPress_Install_Database::create_exclude_from_bulk_compression_table();
          }
        //}

        // --- --- ---

        //
        // Migrate data from old table (v1.2.3 - v1.2.5) to new table v1.2.6
        //
        // Note: Old tables are not removed at this time
        //
        if ( $current_version_less_than__1_2_6 && $new_version_greater_than_or_equal__1_2_6 ) {

          // Import image metadata if available
          if ( $database->table_exists( 'zara4_file_compression_metadata' ) ) {
            $query = "INSERT IGNORE INTO `{$prefix}zara4_file_compression_metadata_r1` (`file-path-hash`,`file-path`,`request-id`,`is-compressed`,`original-file-size`,`compressed-file-size`,`original-file-hash`,`compressed-file-hash`,`bytes-saved`,`percentage-saving`,`no-saving-available`,`plugin-version`) SELECT MD5(`file-path`) as `file-path-hash`,`file-path`,`request-id`,`is-compressed`,`original-file-size`,`compressed-file-size`,`original-file-hash`,`compressed-file-hash`,`bytes-saved`,`percentage-saving`,`no-saving-available`,`plugin-version` FROM `{$prefix}zara4_file_compression_metadata`";
            $database->query( $query );
          }

          // Import ignored images if available
          if ( $database->table_exists( 'zara4_exclude_from_bulk_compression' ) ) {
            $query = "INSERT IGNORE INTO `{$prefix}zara4_exclude_from_bulk_compression_r1` (`file-path-hash`,`file-path`) SELECT MD5(`file-path`) as `file-path-hash`,`file-path` FROM `{$prefix}zara4_exclude_from_bulk_compression`";
            $database->query( $query );
          }

        }

        // --- --- ---

        // Bump latest version installed to current version.
        /** @noinspection PhpUndefinedFunctionInspection */
        update_option( self::ZARA4_VERSION_OPTION_KEY, ZARA4_VERSION );


      } catch( Exception $e ) {
        // Something went wrong
      }
    }


    /**
     * Insurance policy in case upgrade from v1.1 to v1.2 goes horribly wrong for some reason.
     */
    private static function preupgrade_backup() {

      /** @noinspection PhpUndefinedConstantInspection */
      $path = ABSPATH.DIRECTORY_SEPARATOR.'.zara4_v1.2_preupgrade-backup';

      if ( ! file_exists( $path ) ) {

        $all_image_ids = Zara4_WordPress_Attachment_Attachment::all_image_ids();

        $backup_records = array();
        foreach($all_image_ids as $image_id) {
          /** @noinspection PhpUndefinedFunctionInspection */
          $backup_records[$image_id] = get_post_meta( $image_id, Zara4_WordPress_Attachment_Attachment::OPTIMISATION_OPTION_NAME, true );
        }


        file_put_contents( $path, json_encode( $backup_records ) );

      }
    }


  }

}