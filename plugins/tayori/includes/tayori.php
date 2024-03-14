<?php
if (!class_exists('Tayori')) {

  global $tayori_db_version;
  $tayori_db_version = '1.0.0';

  class Tayori {

    public function __construct() 
    {
      $domain = 'tayori';
      load_plugin_textdomain( $domain, false, $domain . '/languages' );
    }

    public static function activate()
    {
      global $wpdb;
      $table_name = $wpdb->prefix . 'tayori';
      $charset_collate = $wpdb->get_charset_collate();
      $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        mail varchar(255) DEFAULT NULL,
        button_title varchar(100) NOT NULL,
        button_type smallint(6) NOT NULL DEFAULT '1',
        pop_button_type smallint(6) NOT NULL DEFAULT '1',
        button_color varchar(45) NOT NULL,
        button_position_pc smallint(6) NOT NULL,
        button_position_sp smallint(6) NOT NULL,
        button_icon_type smallint(6) NOT NULL,
        button_font_color varchar(7) DEFAULT NULL,
        form_type_sp smallint(2) DEFAULT '1',
        form_type_pc smallint(2) DEFAULT '1',
        button_icon_transparent_type smallint(6) NOT NULL,
        delete_flag boolean NOT NULL DEFAULT false,
        created_at datetime NOT NULL,
        updated_at datetime DEFAULT NULL,
        UNIQUE KEY id (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

      $data = array(
        'mail' => get_option('admin_email'),
        'button_title' => 'お問い合わせ',
        'button_color' => '#43bfa0',
        'button_position_pc' => 1,
        'button_position_sp' => 1,
        'button_icon_type' => 1,
        'button_font_color' => '#ffffff',
        'button_icon_transparent_type' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      );
      $wpdb->insert($table_name, $data);
      $setting_data = self::get();
      $result = self::tayori_json_save($setting_data);

      add_option( 'tayori_db_version', $tayori_db_version );
    }

    public static function get()
    {
      global $wpdb;
      $table_name = $wpdb->prefix . 'tayori';
      $query = sprintf('SELECT * FROM %s WHERE id = %d', $table_name, 1);
      $data = $wpdb->get_row($query);
      return (array)$data;
    }

    public static function save($data)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . 'tayori';
      $where = array('id' => 1);
      $data['data']['updated_at'] = date('Y-m-d H:i:s');
      $wpdb->query('START TRANSACTION');
      if ($result = $wpdb->update($table_name, $data['data'], $where)) {
        $setting_data = self::get();
        $json_result = self::tayori_json_save($setting_data);
        if ($json_result['status'] == true) {
          $wpdb->query('COMMIT');
          return $json_result;
        }
        else {
          $wpdb->query('ROLLBACK');
          return $json_result;
        }
      }
      else {
        $wpdb->query('ROLLBACK');
        return $json_result;
      }
    }

    public static function tayori_json_save($data)
    {
      $filename = TAYORI_PLUGIN_DIR . '/json/button.json';
      $message = null;
      $status = true;
      if (!file_exists($filename) && !is_writable($filename)
        || !is_writable(dirname($filename))) {
        $message = 'ファイルへの書き込みが出来ないか、ファイルがありません';
        $status = false;
        return array('message' => $message, 'status' => $status);
      }
      if (!$fp = fopen($filename,'wb')) {
        $message = 'ファイルを開けません。JSONファイルの権限などを確認してください。';
        $status = false;
        return array('message' => $message, 'status' => $status);
      }
      fwrite($fp, sprintf(json_encode($data)));
      fclose($fp);
      return array('message' => $message, 'status' => $status);
    }

  }

}
