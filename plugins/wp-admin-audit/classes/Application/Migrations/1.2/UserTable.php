<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Migration_UserTable extends WADA_Migration_Base {
    public $applicableBeforeVersion = '1.2';
    protected $usersTableExisting = true;
    protected $pwReminderColExisting = true;
    protected $countOfUsers = 0;

    public function __construct(){
        parent::__construct();
    }

    public function isMigrationApplicable(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        if(version_compare($dbVersion, $this->applicableBeforeVersion, "<")){
            $this->usersTableExisting = WADA_Database::isTableExisting('wada_users');
            $this->pwReminderColExisting = WADA_Database::isColExisting('wada_users', 'last_pw_change_reminder');

            if(!$this->usersTableExisting){
                WADA_Log::warning('UserTable migration is applicable (table missing)');
                return true;
            }elseif(!$this->pwReminderColExisting){
                WADA_Log::warning('UserTable migration is applicable (pw reminder col missing)');
                return true;
            }else{
                $this->countOfUsers = WADA_Database::getTableRowCount($this->wpdb->prefix."wada_users");
                if($this->countOfUsers == 0){
                    WADA_Log::warning('UserTable migration is applicable (no rows yet)');
                    return true;
                }
            }
        }
        WADA_Log::debug('UserTable migration is NOT applicable');
        return false;
    }

    public function doMigration(){
        WADA_Log::info('UserTable doMigration');
        $res = array();
        if(!$this->usersTableExisting){
            $res[] = 'createUsersTable: '.$this->createUsersTable();
        }elseif(!$this->pwReminderColExisting){
            $res[] = 'addPwReminderCol: '.$this->addPwReminderCol();
        }
        $this->countOfUsers = WADA_Database::getTableRowCount($this->wpdb->prefix."wada_users");
        if($this->countOfUsers == 0){
            WADA_Log::info('UserTable no contents yet, init table');
            $res[] = 'doInitialInserts: '.$this->doInitialInserts();

            WADA_Log::info('Pull in from user_meta (sessions) if any');
            $res[] = 'copyLoginTimestampsFromUserMeta: '.$this->copyLoginTimestampsFromUserMeta();
        }
        WADA_Log::info('UserTable migration results: '.print_r($res, true));
        return true;
    }

    protected function createUsersTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_users (
                    user_id INT NOT NULL,
                    last_seen TIMESTAMP NULL,
                    last_login TIMESTAMP NULL,
                    last_pw_change TIMESTAMP NULL,
                    last_pw_change_reminder TIMESTAMP NULL,
                    tracked_since TIMESTAMP NULL,
                    PRIMARY KEY (user_id)
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function addPwReminderCol(){
        return WADA_Database::addColIfNotExists($this->wpdb->prefix.'wada_users', 'last_pw_change_reminder', 'TIMESTAMP NULL', 'last_pw_change');
    }

    protected function doInitialInserts(){
        $currTimestamp = WADA_DateUtils::getUTCforMySQLTimestamp();
        $sql = "INSERT INTO ".$this->wpdb->prefix."wada_users"
                ." (user_id, tracked_since)"
                ." SELECT ID as user_id, '".$currTimestamp."' as tracked_since"
                ." FROM ".$this->wpdb->prefix."users";
        $res = $this->wpdb->query($sql);
        WADA_Log::info('doInitialInserts res: '.$res);
    }

    protected function copyLoginTimestampsFromUserMeta(){
        $usersWithSessions = get_users([
            'meta_key' => 'session_tokens',
            'meta_compare' => 'EXISTS'
        ]);
        $foundLastLogins = array();
        foreach($usersWithSessions AS $user){
            $sessions = get_user_meta($user->ID, 'session_tokens', true);
            //WADA_Log::debug('User: '.print_r($user, true)."\r\n".', Sessions: '.print_r($sessions, true));
            if(is_array($sessions)) {
                foreach ($sessions as $session) {
                    if (is_array($session) && array_key_exists('login', $session)) {
                        $lastLogin = $session['login'];
                        if (array_key_exists($user->ID, $foundLastLogins)) {
                            if ($foundLastLogins[$user->ID] > $lastLogin) {
                                $lastLogin = $foundLastLogins[$user->ID];
                            }
                        }
                        $foundLastLogins[$user->ID] = $lastLogin;
                    }
                }
            }
        }

        $results = array();
        foreach($foundLastLogins AS $userId => $lastLogin){
            $lastLoginTimestamp = WADA_DateUtils::getUTCforMySQLTimestampFromUnixTime($lastLogin);
            WADA_Log::debug('foundLastLogins user '.$userId.', timestamp: '.$lastLogin.' -> '.$lastLoginTimestamp);
            $userModel = new WADA_Model_User($userId);
            $userModel->_data->user_id = $userId;
            $userModel->_data->last_seen = $lastLoginTimestamp;
            $userModel->_data->last_login = $lastLoginTimestamp;
            $results[] = $userModel->store($userModel->_data);
        }
        return implode(',', $results);
    }

}