<?php
if ( ! class_exists( 'Zara4_WordPress_Install_Database' ) ) {


  /**
   * Class Zara4_WordPress_Install_Database
   */
  class Zara4_WordPress_Install_Database {

    const FILE_COMPRESSION_METADATA_TABLE_NAME = 'zara4_file_compression_metadata_r1';
    const EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME = 'zara4_exclude_from_bulk_compression_r1';


    /**
     *
     */
    public static function create_file_compression_metadata_table() {

      $database = new Zara4_WordPress_Database();


      $table_name = $database->get_prefix() . self::FILE_COMPRESSION_METADATA_TABLE_NAME;
      $charset_collate = $database->get_charset_collate();

      $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `file-path-hash` VARCHAR(32) NOT NULL,
      `file-path` TEXT NOT NULL,
			`request-id` varchar(255) NOT NULL,
			`is-compressed` BOOLEAN NOT NULL DEFAULT FALSE,
			`original-file-size` BIGINT UNSIGNED NULL DEFAULT NULL,
			`compressed-file-size` BIGINT UNSIGNED NULL DEFAULT NULL,
			`original-file-hash` varchar(50) NULL DEFAULT NULL,
			`compressed-file-hash` varchar(50) NULL DEFAULT NULL,
			`bytes-saved` INT UNSIGNED NULL DEFAULT NULL,
			`percentage-saving` DECIMAL(10,7) NULL DEFAULT NULL,
			`no-saving-available` BOOLEAN NOT NULL DEFAULT FALSE,
			`plugin-version` varchar(10) NOT NULL,
			PRIMARY KEY (`id`), UNIQUE (`file-path-hash`)
		  ) " . $charset_collate . ";";

      /** @noinspection PhpUndefinedConstantInspection */
      /** @noinspection PhpIncludeInspection */
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      /** @noinspection PhpUndefinedFunctionInspection */
      dbDelta( $sql );
    }


    /**
     *
     */
    public static function create_exclude_from_bulk_compression_table() {

      global $wpdb;

      $table_name = $wpdb->prefix . self::EXCLUDE_FROM_BULK_COMPRESSION_TABLE_NAME;

      /** @noinspection PhpUndefinedMethodInspection */
      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `file-path-hash` VARCHAR(32) NOT NULL,
			`file-path` TEXT NOT NULL,
			PRIMARY KEY (`id`), UNIQUE (`file-path-hash`)
		  ) " . $charset_collate . ";";

      /** @noinspection PhpUndefinedConstantInspection */
      /** @noinspection PhpIncludeInspection */
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      /** @noinspection PhpUndefinedFunctionInspection */
      dbDelta( $sql );
    }


  }

}