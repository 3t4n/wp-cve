<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Database
{
    const TBL_EVENTS = '#__wada_events';
    const TBL_EVENT_INFOS = '#__wada_event_infos';
    const TBL_EXTENSIONS = '#__wada_extensions';
    const TBL_EVENT_NOTIFICATIONS = '#__wada_event_notifications';
    const TBL_EVENT_NOTIFICATION_LOG = '#__wada_event_notification_log';
    const TBL_EVENT_REPLICATIONS = '#__wada_event_replications';
    const TBL_LOGINS = '#__wada_logins';
    const TBL_NOTIFICATIONS = '#__wada_notifications';
    const TBL_NOTIFICATION_QUEUE = '#__wada_notification_queue';
    const TBL_NOTIFICATION_QUEUE_MAP = '#__wada_notification_queue_map';
    const TBL_NOTIFICATION_TARGETS = '#__wada_notification_targets';
    const TBL_NOTIFICATION_TRIGGERS = '#__wada_notification_triggers';
    const TBL_SENSORS = '#__wada_sensors';
    const TBL_SENSOR_OPTIONS = '#__wada_sensor_options';
    const TBL_SETTINGS = '#__wada_settings';
    const TBL_USERS = '#__wada_users';

    public static function tbl_events(){
        return strval(self::assignDatabasePrefix(self::TBL_EVENTS));
    }
    public static function tbl_event_infos(){
        return strval(self::assignDatabasePrefix(self::TBL_EVENT_INFOS));
    }
    public static function tbl_event_notifications(){
        return strval(self::assignDatabasePrefix(self::TBL_EVENT_NOTIFICATIONS));
    }
    public static function tbl_event_notification_log(){
        return strval(self::assignDatabasePrefix(self::TBL_EVENT_NOTIFICATION_LOG));
    }
    public static function tbl_event_replications(){
        return strval(self::assignDatabasePrefix(self::TBL_EVENT_REPLICATIONS));
    }
    public static function tbl_extensions(){
        return strval(self::assignDatabasePrefix(self::TBL_EXTENSIONS));
    }
    public static function tbl_logins(){
        return strval(self::assignDatabasePrefix(self::TBL_LOGINS));
    }
    public static function tbl_notifications(){
        return strval(self::assignDatabasePrefix(self::TBL_NOTIFICATIONS));
    }
    public static function tbl_notification_queue(){
        return strval(self::assignDatabasePrefix(self::TBL_NOTIFICATION_QUEUE));
    }
    public static function tbl_notification_queue_map(){
        return strval(self::assignDatabasePrefix(self::TBL_NOTIFICATION_QUEUE_MAP));
    }
    public static function tbl_notification_targets(){
        return strval(self::assignDatabasePrefix(self::TBL_NOTIFICATION_TARGETS));
    }
    public static function tbl_notification_triggers(){
        return strval(self::assignDatabasePrefix(self::TBL_NOTIFICATION_TRIGGERS));
    }
    public static function tbl_sensors(){
        return strval(self::assignDatabasePrefix(self::TBL_SENSORS));
    }
    public static function tbl_settings(){
        return strval(self::assignDatabasePrefix(self::TBL_SETTINGS));
    }
    public static function tbl_users(){
        return strval(self::assignDatabasePrefix(self::TBL_USERS));
    }

    public static function assignDatabasePrefix($sql, $placeholder='#__'){
        global $wpdb;
        return str_replace($placeholder, $wpdb->prefix, $sql);
    }

    public static function getDateTimeNow(){
        $query = 'SELECT NOW()';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeTodayLastMidnight(){
        $query = 'SELECT concat( CURDATE( ) , \' 00:00:00\' ) ';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeTomorrowNextMidnight(){
        $query = 'SELECT concat( CURDATE( ) , \' 00:00:00\' )  + INTERVAL 1 DAY';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeLastSundayMidnight(){
        $query = 'SELECT concat( DATE( CURDATE( ) + INTERVAL( 1 - DAYOFWEEK( CURDATE( ) ) ) DAY ) , \' 00:00:00\' )';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeNextSundayMidnight(){
        $query = 'SELECT concat( DATE( CURDATE( ) + INTERVAL( 1 - DAYOFWEEK( CURDATE( ) ) ) DAY ) , \' 00:00:00\' ) + INTERVAL 1 WEEK';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeThisMonthLastDayMidnight(){
        $query = 'SELECT concat(LAST_DAY(CURDATE()), \' 00:00:00\')';
        return self::getDateTimeResult($query);
    }

    public static function getDateTimeLastMonthLastDayMidnight(){
        $query = 'SELECT concat(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)), \' 00:00:00\')';
        return self::getDateTimeResult($query);
    }

    protected static function getDateTimeResult($query){
        global $wpdb;
        return $wpdb->get_var( $query );
    }

    /**
     * Get the UTC/Unixtimestamp $date in the format that the DB would change it to when it would be produced through NOW() and the MySQL timezone setting
     * @param $date string Timestamp according to UTC, in Y-m-d H:i:s format
     * @return null|string Timestamp according to the locale of the DB connection
     */
    public static function getTimestampFromDate($date){
        $query = 'SELECT CONVERT_TZ(\''.$date.'\', \'+00:00\',\'SYSTEM\') AS utc_conv_time';
        return self::getDateTimeResult($query);
    }

    public static function getCollation($tableName, $field=null){
        global $wpdb;
        $query = 'SHOW FULL COLUMNS FROM ' . $tableName . ' WHERE 1=1';

        $array = $wpdb->get_results($query, ARRAY_A);
        if(is_null($field)){
            foreach($array as $key=>$column){
                $collation = $column['Collation'];
                if(!is_null($collation) && (strlen($collation) > 0) ){
                    return $collation;
                }
            }
        }else{
            foreach($array as $resultfield) {
                if( $resultfield['Field'] == $field) {
                    return $resultfield['Collation'];
                }
            }
        }
        return null;
    }

    public static function alterCollation($tableName, $field, $newCollation){
        global $wpdb;
        $colExists = false;
        $definition = null;
        $colData = self::getTableColumns($tableName);
        foreach ($colData as $valCol) {
            if ($valCol->Field == $field) {
                $colExists = true;
                WADA_Log::debug(print_r($valCol, true));
                $definition = $valCol->Type . ' ';
                if($valCol->Default){
                    $defaultVal = ' DEFAULT ' . $valCol->Default;
                }else{
                    $defaultVal = ' ';
                }
                if(strtoupper($valCol->Null) === 'YES'){
                    $nullVal = 'NULL';
                }else{
                    $nullVal = 'NOT NULL';
                    if($valCol->Key && $valCol->Key !== ''){
                        $defaultVal = ' ';
                    }
                }
                $definition .= $nullVal . $defaultVal . ' ' . $valCol->Extra;
                break;
            }
        }
        if ($colExists && $definition) {
            $query =  'ALTER TABLE ' . $tableName . ' CHANGE ' . $field . ' '. $field .' ' . $definition .' COLLATE ' .$newCollation;
            $errorMsg = '';
            try {
                $result = $wpdb->query( $query );
            } catch (Exception $e) {
                $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
                WADA_Log::error('Error while changing collation from ' . $field . ' to ' . $newCollation . ' in ' . $tableName . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            if (!$result){
                $errorMsg = $wpdb->last_error;
                WADA_Log::error('Could not change collation from ' . $field . ' to ' . $newCollation . ' in ' . $tableName . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            WADA_Log::info('Changed collation of ' . $field . ' to ' . $newCollation . ' in ' . $tableName );
            return 1;
        }
        WADA_Log::info('' . $field . ' not in ' . $tableName . ', not changing collation to ' . $newCollation);
        return 0;
    }

    public static function isColExisting($tblName, $col){
        $colExists = false;
        global $wpdb;
        $result = $wpdb->query(
            $wpdb->prepare( "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = %s AND COLUMN_NAME = %s", $tblName, $col )
        );
        WADA_Log::debug('isColExisting tbl: '.$tblName.', col: '.$col.', res: '.print_r($result, true));
        if($result !== false && $result !== 0) {
            $colExists = true;
        }
        return $colExists;
    }

    public static function isTableExisting($tblName, $addPrefix=true){
        global $wpdb;
        if($addPrefix){
            $tblName = $wpdb->prefix.$tblName;
        }
        $query = 'SHOW TABLES LIKE \''.$tblName.'\'';
        $results = $wpdb->query( $query );
        WADA_Log::debug('isTableExisting: '.$tblName.', res: '.print_r($results, true));
        if( $results !== false && $results !== 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteCol($tblName, $col){
        global $wpdb;
        $query = 'ALTER TABLE ' . $tblName . ' DROP ' . $col;
        $errorMsg = '';
        try {
            $result = $wpdb->query( $query );
        } catch (Exception $e) {
            $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
            WADA_Log::error('failed: deleteCol for table '.$tblName.' and col '.$col.': '.$errorMsg);
            WADA_Log::error('Query was: '.$query);
            return false;
        }
        if (!$result){
            $errorMsg = $wpdb->last_error;
            WADA_Log::error('failed: deleteCol for table '.$tblName.' and col '.$col.': '.$errorMsg);
            WADA_Log::error('Query was: '.$query);
            return false;
        }
        WADA_Log::debug('deleteCol tbl: '.$tblName.', col: '.$col.' was deleted!');
        return true;
    }

    public static function deleteColIfExists($tblName, $col){
        $colExists = self::isColExisting($tblName, $col);
        if($colExists){
            return self::deleteCol($tblName, $col);
        }
        return 0;
    }

    public static function getTableColumns($tblName){
        global $wpdb;
        $query = 'SHOW COLUMNS FROM '.$tblName;
        $results = $wpdb->get_results( $query );
        if( ! $results ) {
            return -1;
        }
        return $results;
    }

    public static function getTableRowCount($tblName){
        global $wpdb;
        $query = 'SELECT COUNT(*) FROM '.$tblName;
        $errorMsg = '';
        try {
            $result = $wpdb->get_var( $query );
        } catch (Exception $e) {
            $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
            WADA_Log::error('getTableRowCount for table '.$tblName.': '.$errorMsg);
            WADA_Log::error('Query was: '.$query);
            return -1;
        }
        if (is_null($result)){
            $errorMsg = $wpdb->last_error;
            WADA_Log::error('failed: getTableRowCount for table '.$tblName.': '.$errorMsg);
            WADA_Log::error('Query was: '.$query);
            return -1;
        }
        return intval($result);
    }

    public static function alterDefaultValue($table, $col, $newDefault){
        global $wpdb;
        $colExists 	= false;
        $defaultNeedsAltering 	= false;
        $colData = self::getTableColumns($table);
        foreach ($colData as $valCol) {
            if ($valCol->Field == $col) {
                $colExists = true;
                if($valCol->Default){
                    if($valCol->Default != $newDefault){
                        $defaultNeedsAltering = true;
                    }
                }else{
                    $defaultNeedsAltering = true;
                }
            }
        }

        if ($colExists && $defaultNeedsAltering) {
            $query =  $wpdb->prepare(
                'ALTER TABLE %s ALTER %s SET DEFAULT %s',
                $table,
                $col,
                $newDefault
            );

            $errorMsg = '';
            try {
                $result = $wpdb->query( $query );
            } catch (Exception $e) {
                $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
                WADA_Log::error('Error while altering default of ' . $col . ' of table ' . $table . ' to: ' . $newDefault);
                WADA_Log::error('Exception: '.$e->getMessage());
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            if (!$result){
                $errorMsg = $wpdb->last_error;
                WADA_Log::error('Error while altering default of ' . $col . ' of table ' . $table . ' to: ' . $newDefault.', error: '.$errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            WADA_Log::info('Cannot alter default value -> column'  .  $col . ' is not in ' . $table);
            return 1;
        }
        WADA_Log::info('Column ' . $col . ' already in ' . $table);
        return 0;

    }

    public static function addColIfNotExists($table, $col, $atts, $afterCol ) {
        global $wpdb;
        $colExists 	= false;
        $colData = self::getTableColumns($table);
        foreach ($colData as $valCol) {
            if ($valCol->Field == $col) {
                $colExists = true;
                break;
            }
        }
        if (!$colExists) {

            $query = 'ALTER TABLE '.$table.' ADD '.$col.' '.$atts.' AFTER '.$afterCol;
            $errorMsg = '';
            WADA_Log::debug('addColIfNotExists query: '.$query);
            try {
                $result = $wpdb->query( $query );
            } catch (Exception $e) {
                $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
                WADA_Log::error('Error while adding ' . $col . ' to ' . $table . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            if(!$result){
                $errorMsg = $wpdb->last_error;
                WADA_Log::error('Could not add column ' . $col . ' to ' . $table . ', Message: ' . $errorMsg.', query was: '.$query);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            WADA_Log::info('Added ' . $col . ' to ' . $table);
            return 1;
        }
        WADA_Log::info('Column ' . $col . ' already in ' . $table);
        return 0;
    }

    public static function changeColType($table, $col, $newDefinition ) {
        global $wpdb;
        $colExists 	= false;
        $colTypeDifferent = false;
        $previousDefinition = null;
        $colData = self::getTableColumns($table);
        foreach ($colData as $valCol) {
            if ($valCol->Field == $col) {
                $colExists = true;
                $definition = $valCol->Type . ' ';
                if($valCol->Default){
                    $defaultVal = ' DEFAULT ' . $valCol->Default;
                }else{
                    $defaultVal = ' ';
                }
                if(strtoupper($valCol->Null) === 'YES'){
                    $nullVal = 'NULL';
                }else{
                    $nullVal = 'NOT NULL';
                    if($valCol->Key && $valCol->Key !== ''){
                        $defaultVal = ' ';
                    }
                }
                $previousDefinition = trim(strtolower($valCol->Type));
                if(trim(strtolower($valCol->Type)) !== (trim(strtolower($newDefinition)))){
                    WADA_Log::info('changeColType() Difference between ' . trim(strtolower($valCol->Type)) . ' and ' . trim(strtolower($newDefinition)) . ' in ' . $table .', recheck with null value' );
                    if(trim(strtolower($valCol->Type.' '.$nullVal)) !== (trim(strtolower($newDefinition)))){
                        WADA_Log::info('changeColType() Difference between ' . trim(strtolower($valCol->Type.' '.$nullVal)) . ' and ' . trim(strtolower($newDefinition)) . ' in ' . $table );
                        $colTypeDifferent = true;
                    }else{
                        WADA_Log::info('changeColType() No difference between ' . trim(strtolower($valCol->Type.' '.$nullVal)) . ' and ' . trim(strtolower($newDefinition)) . ' in ' . $table );
                    }
                }else{
                    WADA_Log::info('changeColType() No difference between ' . trim(strtolower($valCol->Type)) . ' and ' . trim(strtolower($newDefinition)) . ' in ' . $table );
                }
                $definition .= $nullVal . $defaultVal . ' ' . $valCol->Extra;
                break;
            }
        }
        if ($colExists) {
            if($colTypeDifferent){
                $query =  'ALTER TABLE ' . $table . ' MODIFY ' . $col . ' ' . $newDefinition;
                $result = $wpdb->query( $query );

                if(!$result){
                    WADA_Log::error('Error while modifying column ' . $col . ' to ' . $newDefinition . ' in ' . $table . ', Message: ' . $wpdb->last_error);
                    WADA_Log::error('changeColType() query was: '.$query);
                    return -1;
                }
                WADA_Log::info('Modified ' . $col . ' to ' . $newDefinition . ' (previously: ' .  $previousDefinition . ') in ' . $table );
                return 1;
            }
            WADA_Log::info('' . $col . ' type is already ' . $newDefinition  . ', no need to modify');
            return 0;
        }
        WADA_Log::info('' . $col . ' not in ' . $table . ', not modifying to ' . $newDefinition);
        return 0;
    }


    public static function renameCol($table, $oldName, $newName ) {
        global $wpdb;
        $colExists 	= false;
        $definition = null;
        $colData = self::getTableColumns($table);
        foreach ($colData as $valCol) {
            if ($valCol->Field == $oldName) {
                $colExists = true;
                $definition = $valCol->Type . ' ';
                if($valCol->Default){
                    $defaultVal = ' DEFAULT ' . $valCol->Default;
                }else{
                    $defaultVal = ' ';
                }
                if(strtoupper($valCol->Null) === 'YES'){
                    $nullVal = 'NULL';
                }else{
                    $nullVal = 'NOT NULL';
                    if($valCol->Key && $valCol->Key !== ''){
                        $defaultVal = ' ';
                    }
                }
                $definition .= $nullVal . $defaultVal . ' ' . $valCol->Extra;
                break;
            }
        }
        if ($colExists && $definition) {
            $query =  $wpdb->prepare(
                'ALTER TABLE %1s CHANGE %1s %1s %1s',
                $table,
                $oldName,
                $newName,
                $definition
            );
            $errorMsg = '';
            try {
                $result = $wpdb->query( $query );
            } catch (Exception $e) {
                $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
                WADA_Log::error('Error while renaming ' . $oldName . ' to ' . $newName . ' in ' . $table . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            if(!$result){
                $errorMsg = $wpdb->last_error;
                WADA_Log::error('Error while renaming ' . $oldName . ' to ' . $newName . ' in ' . $table . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            WADA_Log::info('Renamed "' . $oldName . '" to "' . $newName . '" in ' . $table );
            return 1;
        }
        WADA_Log::info('' . $oldName . ' not in ' . $table . ', not renaming to ' . $newName);
        return 0;
    }


    public static function createIndexIfNotExists($table, $indexName, $cols){
        global $wpdb;
        $indexExists = false;
        $query = 'SHOW INDEXES FROM ' . $table;
        $result = $wpdb->get_results( $query );
        for($i=0; $i < count($result); $i++){
            $currIndex = &$result[$i];
            $currIndexName = $currIndex->Key_name;
            if($currIndexName == $indexName){
                $indexExists = true;
                break;
            }
        }

        if(!$indexExists){

            $query = 'ALTER TABLE ' . $table . ' ADD INDEX ' . $indexName . ' ( ' . $cols[0];
            for($i=1; $i < count($cols); $i++){
                $query .= ', ' . $cols[$i];
            }
            $query .= ')';
            $errorMsg = '';
            try {
                $result = $wpdb->query( $query );
            } catch (Exception $e) {
                $errorMsg = 'Error No: ' . $e->getCode() . ', Message: ' . $e->getMessage();
            }
            if(false === $result){
                if($errorMsg == ''){
                    $errorMsg = $wpdb->last_error;
                }
                WADA_Log::error('Error while creating index ' . $indexName . ' in ' . $table . ', columns: ' . print_r($cols, true) . ', Message: ' . $errorMsg);
                WADA_Log::error('Query was: '.$query);
                return -1;
            }
            WADA_Log::info('Created index ' . $indexName . ' in ' . $table );
            return 1;
        }
        WADA_Log::info('Index ' . $indexName . ' already in ' . $table );
        return 0;
    }


}
