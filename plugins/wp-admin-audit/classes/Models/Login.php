<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_Login extends WADA_Model_Base
{
    public function __construct($id = null){
        parent::__construct($id);
    }

    public static function getTable(){
        return WADA_Database::tbl_logins();
    }

    public function getAttributes(){
        return array(
            'id', 'login_date', 'login_successful',
            'user_login', 'user_login_existing', 'user_id', 'ip_address'
        );
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->login_date){
            $this->_last_error = __('No login date provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->user_login){
            $this->_last_error = __('No user login provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->ip_address){
            $this->_last_error = __('No ip address provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    protected function save(){
        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_Login::getTable();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'login_date,';
        $query .= 'login_successful,';
        $query .= 'user_login,';
        $query .= 'user_login_existing,';
        $query .= 'user_id,';
        $query .= 'ip_address';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%s,'; // login_date
        $query .= '%d,'; // login_successful
        $query .= '%s,'; // user_login
        $query .= '%d,'; // user_login_existing
        $query .= '%d,'; // user_id
        $query .= 'INET6_ATON(%s)'; // ip_address (INET6_ATON returns a binary string of the IPv6 or IPv4 address)
        $query .= ')';
        $preparedQuery = $wpdb->prepare($query,
            null,
            $this->_data->login_date,
            $this->_data->login_successful,
            $this->_data->user_login,
            $this->_data->user_login_existing,
            $this->_data->user_id,
            $this->_data->ip_address
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('Login->save: '.$wpdb->last_error);
            WADA_Log::error('Login->save query was: '.$preparedQuery);
            return false;
        }else{
            $this->_id = $wpdb->insert_id;
            $this->_data->id = $this->_id;
            WADA_Log::debug('Login->save ok inserted id: '.$this->_id);
        }
        return $this->_id;
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = "SELECT id, login_date, login_successful,"
            ." user_login, user_login_existing, user_id, INET6_NTOA(ip_address) AS ip_address"
            ." FROM ".WADA_Model_Login::getTable()." log"
            ." WHERE log.id = %d ";
            $loginObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            if($loginObj){
                $loginObj->user = ($loginObj->user_id > 0) ? get_userdata(absint($loginObj->user_id)) : null;
                $loginObj->login_date_localized = WADA_DateUtils::formatUTCasDatetimeForWP($loginObj->login_date);
            }

            if($onlyReturnNoInternalUpdate){
                return $loginObj;
            }
            $this->_data = $loginObj;
            return true;

        }
        return false;
    }

}