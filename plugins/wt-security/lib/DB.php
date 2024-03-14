<?php
if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
    if (!headers_sent()) {
        /* Report invalid access if possible. */
        header('HTTP/1.1 403 Forbidden');
    }
    exit(1);
}

/**
 * WebTotem Database class for Wordpress.
 */
class WebTotemDB {

    const WTOTEM_TABLE_SETTINGS = 'wtotem_settings';
    const WTOTEM_TABLE_BLOCKED_LIST = 'wtotem_blocked_list';
    const WTOTEM_TABLE_AUDIT_LOGS = 'wtotem_audit_logs';
    const WTOTEM_TABLE_SCAN_LOGS = 'wtotem_scan_logs';
    const WTOTEM_TABLE_CONFIDENTIAL_FILES = 'wtotem_confidential_files';

    /**
     * Creating a database with plugin settings.
     */
    public static function install () {
        global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $settings_table = self::add_prefix(self::WTOTEM_TABLE_SETTINGS);
        if($wpdb->get_var("show tables like '$settings_table'") != $settings_table) {

            $sql = "CREATE TABLE " . $settings_table . " (
              id bigint NOT NULL AUTO_INCREMENT,
              name tinytext NOT NULL,
              value longtext,
              UNIQUE KEY id (id)
            ) 
              DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

            dbDelta($sql);
        }

    $blocked_list_table = self::add_prefix(self::WTOTEM_TABLE_BLOCKED_LIST);
    if($wpdb->get_var("show tables like '$blocked_list_table'") != $blocked_list_table) {

      $sql = "CREATE TABLE " . $blocked_list_table . " (
        id bigint NOT NULL AUTO_INCREMENT,
        ip tinytext NOT NULL,
        reason tinytext,
        blockedTime tinytext,
        UNIQUE KEY id (id)
      )
        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

      dbDelta($sql);
    }

    $audit_logs_table = self::add_prefix(self::WTOTEM_TABLE_AUDIT_LOGS);
    if($wpdb->get_var("show tables like '$audit_logs_table'") != $audit_logs_table) {

      $sql = "CREATE TABLE " . $audit_logs_table . " (
        id bigint NOT NULL AUTO_INCREMENT,
        created_at DATETIME NOT NULL,
        user_name tinytext,
        status tinytext,
        event tinytext,
        title tinytext,
        description text,
        ip tinytext,
        viewed tinytext,
        UNIQUE KEY id (id)
      )
        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

      dbDelta($sql);
    }

    $scan_logs_table = self::add_prefix(self::WTOTEM_TABLE_SCAN_LOGS);
    if($wpdb->get_var("show tables like '$scan_logs_table'") != $scan_logs_table) {

      $sql = "CREATE TABLE " . $scan_logs_table . " (
        id bigint NOT NULL AUTO_INCREMENT,
        created_at DATETIME NOT NULL,
        scan_source tinytext,
        data_type tinytext,
        source tinytext,
        content text,
        is_internal boolean,
        UNIQUE KEY id (id)
      )
        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

      dbDelta($sql);
    }

    $dbname = $wpdb->dbname;
    $is_had_col = $wpdb->get_results(  "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name` = '{$scan_logs_table}' AND `TABLE_SCHEMA` = '{$dbname}' AND `COLUMN_NAME` = 'is_internal'"  );

    if( empty($is_had_col) ){
      $add_status_column = "ALTER TABLE `{$scan_logs_table}` ADD `is_internal` VARCHAR(50) NULL DEFAULT NULL AFTER `content`; ";
      $wpdb->query( $add_status_column );
    }

    $confidential_files_table = self::add_prefix(self::WTOTEM_TABLE_CONFIDENTIAL_FILES);
    if($wpdb->get_var("show tables like '$confidential_files_table'") != $confidential_files_table) {

      $sql = "CREATE TABLE " . $confidential_files_table . " (
        id bigint NOT NULL AUTO_INCREMENT,
        created_at DATETIME NOT NULL,
        path text,
        name text,
        size tinytext,
        modified_at text,
        url text,
        UNIQUE KEY id (id)
      )
        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

      dbDelta($sql);
    }

        return true;
    }

    /**
     * Add (or update) data to the table.
     */
    public static function setData ($options, $table, $where = false) {
        global $wpdb;
        $table_name = self::getTable($table);

        if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
            if($where && $current = self::getData($where, $table)){
                $options['id'] = $current['id'];
            }

            $wpdb->replace( $table_name, $options );
        }
    }

    /**
     * Delete data from the table.
     */
    public static function deleteData ($params, $table) {
        global $wpdb;

    $table_name = self::getTable($table);
    if($params){
      $wpdb->delete( $table_name, $params );
    } else {
        $wpdb->query( "DELETE FROM " . $table_name );
        $wpdb->query( "UPDATE " . $table_name . " SET id = 0" );
        $wpdb->query( "ALTER TABLE " . $table_name . " AUTO_INCREMENT =0;"  );
    }
    }

    /**
     * Getting values from the table.
     *
     * @param array $options
     *    Option name.
     *
     * @return array
     */
    public static function getData ($options, $table) {
        global $wpdb;
        $table_name = self::getTable($table);
        $where = '';

        if($options){
            $where = [];
            foreach ($options as $key => $value){
                $where[] = $key . " = '" . $value . "'";
            }
            $where = 'WHERE ' . implode(' AND ', $where);
        }

        $_options = [];
        if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
            $_options = $wpdb->get_row("SELECT * FROM $table_name $where");
        }

        return (array) $_options ?: [];
    }

    /**
     * Check availability.
     */
    public static function checkAvailability ($table, $values, $field) {
        global $wpdb;
        $table_name = self::getTable($table);
        $result = [];

        if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
            foreach ($values as $value){
                $is_exists = $wpdb->get_row( "SELECT COUNT(*) as count FROM $table_name WHERE $field = '$value'" );
                if($is_exists->count){
                   $result[$value] = __($value, 'wtotem');
                }
            }
        }
        return $result;
    }

    /**
     * Getting rows from the table.
     *
     * @param string $table
     *    Table name.
     * @param string $columns
     *    Columns.
     * @param string $values
     *    Values.
     */
    public static function setRows ($table, $columns, $values) {
        global $wpdb;
        $table_name = self::getTable($table);

        if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            WebTotemDB::install();
        }

        $wpdb->query( "INSERT INTO " . $table_name . " " . $columns . " VALUES " . $values );
    }

    /**
     * Getting rows from the table.
     *
     * @param array $options
     *    Option name.
     *
     * @return array
     */
    public static function getRows ($options, $table, $group_by = false, $pagination = ['limit' => 10, 'page' => 1], $sort = ['order_by' => 'id', 'direction' => 'DESC']) {
        global $wpdb;
        $table_name = self::getTable($table);

        if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            WebTotemDB::install();
        }

        if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
            $where = '';
            if($options){
                if($options[0] == 'AND' or $options[0] == 'OR'){
                    $where = [];
                    foreach ($options[1] as $key => $value){
                        if(is_array($value)){
                            foreach ($value as $val){
                                $where[] = $key . " = '" . $val . "'";
                            }
                        } else {
                            $where[] = $key . " = '" . $value . "'";
                        }
                    }
                    $where = 'WHERE ' . implode(' '.$options[0].' ', $where);
                }
                if($options[0] == 'LIKE'){
                    $where = [];
                    foreach ($options[1] as $key => $value){
                            $where[] = $key . " LIKE '" . $value . "'";
                    }
                    $where = 'WHERE ' . implode(' OR ', $where);
                }
            }

            $_pagination = $pagination == 'all' ? '' : 'LIMIT '. $pagination['limit'] .' OFFSET ' . $pagination['limit'] * ($pagination['page'] - 1);
            $_sort = 'ORDER BY `' . $sort['order_by'] . '` ' . $sort['direction'];

            $_group_by = $group_by ? 'GROUP BY ' . $group_by : '';

            $result['data'] = WebTotem::convertObjectToArray( $wpdb->get_results( "SELECT * FROM $table_name $where $_group_by $_sort $_pagination" ) );

            if($pagination != 'all'){
                if($group_by){
                    $count = $wpdb->get_results( "SELECT COUNT(DISTINCT $group_by) as count FROM $table_name $where" );
                } else {
                    $count = $wpdb->get_results( "SELECT COUNT(*) as count FROM $table_name $where" );
                }
            }

            $result['count'] = !empty($count) ? $count[0]->count : 0;

            if($table == 'audit_logs'){

                // Set viewed mark.
                $ids = implode(",", array_column($result['data'], 'id'));
                if( $ids ) $wpdb->query( "UPDATE $table_name SET viewed = 1 WHERE id in ($ids)" );

                // Get dates count
                $created_at = array_column($result['data'], 'created_at');
                $dates = [];
                foreach ($created_at as $value){
                    $dates[] = date_i18n('Y-m-d', strtotime($value));
                }
                $dates = array_unique($dates);
                foreach ($dates as $date){
                    $count = $wpdb->get_results( "SELECT COUNT(*) as count FROM $table_name WHERE created_at BETWEEN '$date 00:00:00' AND '$date 23:59:59'" );
                    $dates_count[date_i18n('M j, Y', strtotime($date))] = $count[0]->count;
                }
                $result['dates_count'] = $dates_count ?? [];
            }
        }
        return $result ?? ['data' => [], 'count' => 0];
    }

    /**
     * Deleting wtotem tables.
     */
    public static function uninstall() {
        $tables = [
            self::WTOTEM_TABLE_SETTINGS,
            self::WTOTEM_TABLE_BLOCKED_LIST,
            self::WTOTEM_TABLE_AUDIT_LOGS,
            self::WTOTEM_TABLE_SCAN_LOGS,
            self::WTOTEM_TABLE_CONFIDENTIAL_FILES,
        ];
        foreach ($tables as $table) {
            global $wpdb;
            $wpdb->query('DROP TABLE IF EXISTS `' . self::add_prefix($table) . '`');
        }
    }

    /**
     * Returns the table with the site prefix added.
     *
     * @param string $table
     *    Table name.
     * @return string
     */
    public static function add_prefix($table) {
        global $wpdb;
        return $wpdb->base_prefix . $table;
    }

    /**
     * Get table name.
     */
    private static function getTable($name) {
        switch ($name) {
            case 'settings':
                return self::add_prefix(self::WTOTEM_TABLE_SETTINGS);
            case 'blocked_list':
                return self::add_prefix(self::WTOTEM_TABLE_BLOCKED_LIST);
            case 'audit_logs':
                return self::add_prefix(self::WTOTEM_TABLE_AUDIT_LOGS);
            case 'scan_logs':
                return self::add_prefix(self::WTOTEM_TABLE_SCAN_LOGS);
            case 'confidential_files':
              return self::add_prefix(self::WTOTEM_TABLE_CONFIDENTIAL_FILES);
        }

        throw new \OutOfBoundsException('Unknown key: ' . $name);
    }

}