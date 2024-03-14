<?php
class WPFingerprint_Model_Diffs{
  private $table_name = '';

  public function __construct()
  {
    global $wpdb;
    $this->table_name = $wpdb->prefix . 'wpfingerprint_diffs';
    if($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
      //Probably a migration or first run but:
      $this->create();
    }
  }

  public function create()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $this->table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      plugin varchar(55) NOT NULL,
      version varchar(20) NOT NULL,
      filename varchar(500) NOT NULL,
      line_number varchar(6) NOT NULL,
      diff_local text NOT NULL,
      diff_remote text NOT NULL,
      source varchar(25) NOT NULL,
      first_found datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      last_checked datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  public function get($plugin = false, $version = false, $file=false)
  {
    global $wpdb;
    if( !isset($plugin) || !$plugin )
    {
      return $wpdb->get_results("SELECT * FROM $this->table_name" );
    }
    else {
      $sql_version = ";";
      if( $version ) $sql_version = " AND version = '$version';";
      $file_name = '';
      if( $file ) $file_name = " AND filename = '$file'";
      $response = $wpdb->get_results("
      SELECT * FROM $this->table_name
      WHERE plugin = '$plugin' $file_name $sql_version");
      if(empty($response)) return false;
      return $response;
    }
  }

  public function set($plugin, $version, $file, $line, $diffs)
  {

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
      if($get_db_version < 3)
      {
        if($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
          //Probably a migration or first run but:
          $this->create();
        }
      }
      return true;
    }
  }
}
