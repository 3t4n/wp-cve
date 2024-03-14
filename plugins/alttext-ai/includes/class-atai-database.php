<?php
/**
 * The file that handles the database-related functionality of the plugin.
 *
 * @link       https://alttext.ai
 * @since      1.1.0
 *
 * @package    ATAI
 * @subpackage ATAI/includes
 */

/**
 * The database handling class.
 *
 * This is used to handle operations related to the plugin's database.
 *
 * @since      1.1.0
 * @package    ATAI
 * @subpackage ATAI/includes
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI_Database {
  /**
   * The version of the database schema.
   *
   * @since    1.1.0
   * @access   private
   *
   * @param    string    $db_version    The version of the database schema.
   */
  private $db_version = '1.1.0';

  /**
   * Update the database schema if necessary.
   *
   * @since    1.1.0
   * @access   public
   */
  public function create_or_update_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . ATAI_DB_ASSET_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      wp_post_id bigint(20) unsigned NOT NULL,
      asset_id varchar(128) NOT NULL UNIQUE KEY,
      updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY  (id),
      KEY idx_wp_posts (wp_post_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  /**
   * Migrate data from WP_PostMeta to the new table.
   *
   * @since    1.1.0
   * @access   private
   */
  private function database_migrate_wp_meta() {
    global $wpdb;
    $table_name = $wpdb->prefix . ATAI_DB_ASSET_TABLE;

    // Move WP_PostMeta asset IDs to new table:
    $data_migration_sql = <<<SQL
INSERT INTO {$table_name} (wp_post_id, asset_id)
SELECT DISTINCT pm.post_id, pm.meta_value
FROM {$wpdb->prefix}postmeta pm
WHERE
  pm.meta_key = '_atai_asset_id'
SQL;

    $wpdb->query( $data_migration_sql );

    // Remove WP_PostMeta data:
    $data_removal_sql = <<<SQL
DELETE FROM {$wpdb->prefix}postmeta
WHERE
  meta_key = '_atai_asset_id' OR meta_key = '_atai_status' OR meta_key = '_atai_date'
SQL;

      $wpdb->query( $data_removal_sql );
  }

  /**
   * Check if the database needs an update.
   *
   * @since    1.1.0
   * @access   public
   */
  public function check_database_schema() {
    $installed_db_version = get_site_option( 'atai_db_version', '' );

    if ( $installed_db_version != $this->db_version ) {
      $this->create_or_update_table();

      // Remember latest database schema update
      add_option( 'atai_db_version', $this->db_version );
    }

    // Migrate old data if we just created the new table:
    if ( empty( $installed_db_version ) ) {
      $this->database_migrate_wp_meta();
    }
  }
}
