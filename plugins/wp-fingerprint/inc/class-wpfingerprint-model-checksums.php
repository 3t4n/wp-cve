<?php
class WPFingerprint_Model_Checksums{
  private $table_name = '';

  public function __construct()
  {
    global $wpdb;
    $this->table_name = $wpdb->prefix . 'wpfingerprint_checksums';
  }

  public function create()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $this->table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      plugin tinytext NOT NULL,
      version tinytext NOT NULL,
      filename tinytext NOT NULL,
      checksum_local tinytext NOT NULL,
      checksum_remote tinytext NOT NULL,
      source tinytext NOT NULL,
      first_found datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      last_checked datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  public function get($plugin = false, $version = false)
  {
    global $wpdb;
    if( !isset($plugin) || !$plugin )
    {
      return $wpdb->get_results("SELECT * FROM $this->table_name" );
    }
    else {
      $sql_version = ";";
      if( isset($version) && $version ) $sql_version = " AND version = '$version';";
      $response = $wpdb->get_results("
      SELECT * FROM $this->table_name
      WHERE plugin = '$plugin' $sql_version");
      if(empty($response)) return false;
      return $response;
    }
  }

  public function set($plugin, $version, $filename, $checksums)
  {
    if( !is_array($checksums) ) return false;
    global $wpdb;
    $wpdb->insert(
      	$this->table_name,
      	array(
          'plugin' => $plugin,
          'version' => $version,
          'filename' => $filename,
          'checksum_local' => $checksums['local'],
          'checksum_remote' => $checksums['remote'],
          'source' => $checksums['source'],
      		'first_found' => current_time( 'mysql' ),
      		'last_checked' => current_time( 'mysql' ),
      	)
    );
  }
  public function update_last_checked($id)
  {
    global $wpdb;
    return $wpdb->update(
      	$this->table_name,
      	array(
            'last_checked' => current_time( 'mysql' )
        ),
        array(
            'id' => $id
        )
      );
  }

  public function remove($id)
  {
    global $wpdb;
    $wpdb->delete(
      $this->table_name,
      array(
        'id' => $id
      )
    );
  }

  public function clear()
  {
    global $wpdb;
    $sql = "TRUNCATE " . $this->table_name;
	  return $wpdb->query($sql);
  }

  public function destroy()
  {
    //todo
  }

  public function migrate($db_version)
  {
    global $wpdb;
    $get_db_version = get_option('wpfingerprint_db_version',0);
    if($db_version > $get_db_version){
      //We should do some migrations.
      if($get_db_version < 1)
      {
        if($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
          //Probably a migration or first run but:
          $this->create();
        }
      }
      if($get_db_version < 2)
      {
        //Do Database Migration 2
        /*
         * @note: we are clearing the wpfingerprint table due to spurious data entering
         */
        $this->clear();
        //run a check straightaway
        wp_schedule_single_event(time(), 'wpfingerprint_run_now');
      }
      return true;
    }
  }
}
