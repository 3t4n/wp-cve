<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_User extends WADA_Model_Base
{
    public function __construct($id = null, $loadActivites=false){
        parent::__construct($id);
        if($loadActivites){
            $this->loadActivities();
        }
    }

    public static function getTable(){
        if(method_exists('WADA_Database', 'tbl_users')){
            return WADA_Database::tbl_users();
        }else{
            global $wpdb;
            return $wpdb->prefix.'wada_users';
        }
    }

    public function getAttributes(){
        return array(
            'user_id', 'last_seen', 'last_login', 'last_pw_change', 'last_pw_change_reminder', 'tracked_since'
        );
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        $userId = intval(($this->_id ? $this->_id : (property_exists($this->_data, 'user_id') ? $this->_data->user_id : 0)));
        if($userId <= 0){
            WADA_Log::error('User->check ID '.$userId.', this: '.print_r($this, true));
            $this->_last_error = __('No user ID provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    protected function save(){
        $userId = intval(($this->_id ? $this->_id : (($this->_data && property_exists($this->_data, 'user_id')) ? $this->_data->user_id : 0)));
        if(empty($this->_data) || !property_exists($this->_data, 'user_id') || !$this->_data->user_id){
            if(empty($this->_data)){
                $this->_data = new stdClass();
            }
            $this->_data->user_id = $userId;
        }
        if(!property_exists($this->_data, 'tracked_since') || is_null($this->_data->tracked_since)){
            $this->_data->tracked_since = WADA_DateUtils::getUTCforMySQLTimestamp();
            WADA_Log::info('User->save ID '.$userId.', assign tracked_since as '.$this->_data->tracked_since);
        }
        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_User::getTable();
        $query .= ' (';
        $query .= 'user_id,';
        $query .= 'last_seen,';
        $query .= 'last_login,';
        $query .= 'last_pw_change,';
        $query .= 'last_pw_change_reminder,';
        $query .= 'tracked_since';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%s,'; // last_seen
        $query .= '%s,'; // last_login
        $query .= '%s,'; // last_pw_change
        $query .= '%s,'; // last_pw_change_reminder
        $query .= '%s'; // tracked_since
        $query .= ') ON DUPLICATE KEY UPDATE last_seen = %s, last_login = %s, last_pw_change = %s, last_pw_change_reminder = %s, tracked_since = COALESCE(tracked_since, %s)';
        $preparedQuery = $wpdb->prepare($query,
            $userId,
            $this->_data->last_seen,
            $this->_data->last_login,
            $this->_data->last_pw_change,
            $this->_data->last_pw_change_reminder,
            $this->_data->tracked_since,
            $this->_data->last_seen, // for ON DUPLICATE KEY UPDATE part
            $this->_data->last_login, // for ON DUPLICATE KEY UPDATE part
            $this->_data->last_pw_change, // for ON DUPLICATE KEY UPDATE part
            $this->_data->last_pw_change_reminder, // for ON DUPLICATE KEY UPDATE part
            $this->_data->tracked_since // for ON DUPLICATE KEY UPDATE part
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('User->save: '.$wpdb->last_error);
            WADA_Log::error('User->save query was: '.$preparedQuery);
            return false;
        }else{
            $this->_id = $userId;
            //$userAfterInsertOrUpdate = $this->loadData(true);
            //WADA_Log::debug('User->save userAfterInsertOrUpdate: '.print_r($userAfterInsertOrUpdate, true));
        }
        return $this->_id;
    }

    protected function initUserEntryIfNecessary(){
        if(empty($this->_data)){
            $this->_data = new stdClass();
            $this->_data->user_id = $this->_id;
            $this->_data->last_seen = null;
            $this->_data->last_login = null;
            $this->_data->last_pw_change = null;
            $this->_data->last_pw_change_reminder = null;
            $this->_data->tracked_since = null;
        }else{
            $this->_data->user_id = $this->_id;
        }
    }

    public function updateLastSeen($lastSeen){
        $this->initUserEntryIfNecessary();
        $this->_data->last_seen = $lastSeen;
       // WADA_Log::debug('updateLastSeen user ID '.$this->_id.', data to save: '.print_r($this->_data, true));
        return $this->store($this->_data);
    }

    public function updateLastLogin($lastLogin){
        $this->initUserEntryIfNecessary();
        $this->_data->last_seen = $lastLogin;
        $this->_data->last_login = $lastLogin;
        WADA_Log::debug('updateLastLogin user ID '.$this->_id.', data to save: '.print_r($this->_data, true));
        return $this->store($this->_data);
    }

    public function updateLastPwChange($lastPwChange){
        $this->initUserEntryIfNecessary();
        $this->_data->last_pw_change = $lastPwChange;
        $this->_data->last_pw_change_reminder = null; // reset
        WADA_Log::debug('updateLastPwChange user ID '.$this->_id.', data to save: '.print_r($this->_data, true));
        return $this->store($this->_data);
    }

    public function updateLastPwChangeReminder($lastPwReminder){
        $this->initUserEntryIfNecessary();
        $this->_data->last_pw_change_reminder = $lastPwReminder;
        WADA_Log::debug('updateLastPwChangeReminder user ID '.$this->_id.', data to save: '.print_r($this->_data, true));
        return $this->store($this->_data);
    }

    public function assignTrackedSinceIfEmpty(){
        $this->initUserEntryIfNecessary();
        if(empty($this->_data->tracked_since)){
            $this->_data->tracked_since = WADA_DateUtils::getUTCforMySQLTimestamp();
            WADA_Log::debug('assignTrackedSinceIfEmpty user ID '.$this->_id.', data to save: '.print_r($this->_data, true));
            return $this->store($this->_data);
        }else{
            WADA_Log::debug('assignTrackedSinceIfEmpty user ID '.$this->_id.', no need to update, already has tracked_since info: '.print_r($this->_data, true));
            return 0;
        }
    }

    protected static function getUserEvents($query, $userId, $limit){
        global $wpdb;
        $query = $wpdb->prepare($query, $userId, $limit);
        $eventIds = $wpdb->get_col($query);
        $events = array();
        foreach($eventIds AS $eventId){
            $event = (new WADA_Model_Event($eventId))->_data;
            if(property_exists($event, 'user')){
                $event->user = 'REMOVED_SINCE_IRRELEVANT';
            }
            $events[] = $event;
        }
        return $events;
    }

    public static function getLastUserActivityEvents($userId, $limit=10){
        $query = "SELECT ev.id"
            ." FROM ".WADA_Database::tbl_events()." ev"
            ." WHERE ev.user_id = %d"
            ." ORDER BY ev.id DESC"
            ." LIMIT 0, %d";
        return self::getUserEvents($query, $userId, $limit);
    }

    public static function getLastUserSubjectEvents($userId, $limit=10){
        $query = "SELECT ev.id"
            . " FROM " . WADA_Database::tbl_events() . " ev"
            . " WHERE object_type ='" . WADA_Sensor_Base::OBJ_TYPE_CORE_USER . "'"
            . " AND ev.object_id = %d"
            . " ORDER BY ev.id DESC"
            . " LIMIT 0, %d";
        return self::getUserEvents($query, $userId, $limit);
    }

    protected function loadActivities(){
        if($this->_data) {
            $this->_data->lastActivityEvents = self::getLastUserActivityEvents($this->_id);
            $this->_data->lastSubjectEvents = self::getLastUserSubjectEvents($this->_id);
        }
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){

            $currUtc = WADA_DateUtils::getUTCforMySQLTimestamp();
            global $wpdb;
            $query = "SELECT GREATEST(COALESCE(last_seen, tracked_since), "
                    ." COALESCE(last_login, tracked_since), COALESCE(last_pw_change, tracked_since)) AS not_seen_since,"
                    ." TIMESTAMPDIFF(DAY, COALESCE(last_pw_change, tracked_since), '".$currUtc."') AS last_pw_change_days_ago,"
                    ." wada_usr.*"
                    ." FROM ".WADA_Model_User::getTable()." wada_usr"
                    ." WHERE wada_usr.user_id = %d";
            $userObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            $wpUserObj = get_userdata($this->_id);
            if($wpUserObj && $userObj){
                $attributesToGet = array(
                                    'user_login', 'user_email',
                                    'user_nicename', 'first_name', 'last_name',
                                    'user_url', 'roles', 'user_registered');
                foreach($attributesToGet AS $attr){
                    $userObj->$attr = $wpUserObj->$attr;
                }

                global $wp_roles;
                if ( ! isset( $wp_roles ) ) { $wp_roles = new WP_Roles(); }
                $wpRoles = $wp_roles->get_names();
                $rolesNiceArray = array();
                foreach($userObj->roles AS $role){
                    if(array($role, $wpRoles)) {
                        $rolesNiceArray[] = $wpRoles[$role];
                    }else{
                        $rolesNiceArray[] = $role; // no "translation" possible
                    }
                }
                $userObj->rolesNiceName = $rolesNiceArray;

            }

            if($userObj){
                $userObj->lastActivityEvents = array(); // will be loaded by loadActivities if applicable
                $userObj->lastSubjectEvents = array(); // will be loaded by loadActivities if applicable
            }

            if($onlyReturnNoInternalUpdate){
                return $userObj;
            }
            $this->_data = $userObj;
            return true;

        }
        return false;
    }


}