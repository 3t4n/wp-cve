<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_Sensor_Base
{
    const EVT_USER_REGISTRATION = 1;
    const EVT_USER_LOGIN = 2;
    const EVT_USER_LOGOUT = 3;
    const EVT_USER_UPDATE = 4;
    const EVT_USER_DELETE = 5;
    const EVT_POST_CREATE = 6;
    const EVT_POST_UPDATE = 7;
    const EVT_POST_DELETE = 8;
    const EVT_PLG_WADA_SENSOR_UPDATE = 9;
    const EVT_USER_LOGIN_FAILED = 10;
    const EVT_USER_PASSWORD_RESET = 11;
    const EVT_POST_TRASHED = 12;
    const EVT_POST_PUBLISHED = 13;
    const EVT_POST_UNPUBLISHED = 14;
    const EVT_PLG_WADA_SETTINGS_UPDATE = 15;
    const EVT_PLG_PSEUDO = 16;
    const EVT_PLG_WC_PRODUCT_CREATE = 17; // wada-wc extension
    const EVT_PLG_WC_PRODUCT_UPDATE = 18; // wada-wc extension
    const EVT_PLG_WC_PRODUCT_PUBLISHED = 19; // wada-wc extension
    const EVT_PLG_WC_PRODUCT_UNPUBLISHED = 20; // wada-wc extension
    const EVT_PLG_WC_PRODUCT_TRASHED = 21; // wada-wc extension
    const EVT_PLG_WC_PRODUCT_DELETED = 22; // wada-wc extension
    const EVT_PLUGIN_INSTALL = 23;
    const EVT_PLUGIN_DELETE = 24;
    const EVT_PLUGIN_ACTIVATE = 25;
    const EVT_PLUGIN_DEACTIVATE = 26;
    const EVT_THEME_INSTALL = 27;
    const EVT_THEME_DELETE = 28;
    const EVT_THEME_SWITCH = 29;
    const EVT_THEME_UPDATE = 30;
    const EVT_PLUGIN_UPDATE = 31;
    const EVT_CORE_UPDATE = 32;
    const EVT_MEDIA_CREATE = 33;
    const EVT_MEDIA_DELETE = 34;
    const EVT_MEDIA_UPDATE = 35;
    const EVT_SETTING_GENERAL_UPDATE = 36;
    const EVT_SETTING_WRITING_UPDATE = 37;
    const EVT_SETTING_READING_UPDATE = 38;
    const EVT_SETTING_DISCUSSION_UPDATE = 39;
    const EVT_SETTING_MEDIA_UPDATE = 40;
    const EVT_SETTING_PERMALINK_UPDATE = 41;
    const EVT_SETTING_PRIVACY_UPDATE = 42;
    const EVT_PLG_WADA_NOTIFICATION_CREATE = 43;
    const EVT_PLG_WADA_NOTIFICATION_UPDATE = 44;
    const EVT_PLG_WADA_NOTIFICATION_DELETE = 45;
    const EVT_POST_CATEGORY_ASSIGN_UPDATE = 46;
    const EVT_POST_TAG_ASSIGN_UPDATE = 47;
    const EVT_CATEGORY_CREATE = 48;
    const EVT_CATEGORY_UPDATE = 49;
    const EVT_CATEGORY_DELETE = 50;
    const EVT_POST_TAG_CREATE = 51;
    const EVT_POST_TAG_UPDATE = 52;
    const EVT_POST_TAG_DELETE = 53;
    const EVT_COMMENT_CREATE = 54;
    const EVT_COMMENT_UPDATE = 55;
    const EVT_COMMENT_DELETE = 56;
    const EVT_COMMENT_TRASHED = 57;
    const EVT_COMMENT_UNTRASHED = 58;
    const EVT_COMMENT_APPROVED = 59;
    const EVT_COMMENT_UNAPPROVED = 60;
    const EVT_COMMENT_SPAMMED = 61;
    const EVT_MENU_CREATE = 62;
    const EVT_MENU_UPDATE = 63;
    const EVT_MENU_DELETE = 64;
    const EVT_OPTION_CREATE = 65;
    const EVT_OPTION_UPDATE_CORE = 66;
    const EVT_OPTION_UPDATE_OTHER = 67;
    const EVT_OPTION_DELETE = 68;
    const EVT_PLG_WPF_FORM_SUBMISSION = 69; // wada-wpf extension
    const EVT_PLG_WPF_FORM_CREATE = 70; // wada-wpf extension
    const EVT_PLG_WPF_FORM_UPDATE = 71; // wada-wpf extension
    const EVT_PLG_WPF_FORM_DELETE = 72; // wada-wpf extension
    const EVT_PLG_WPF_FORM_TRASHED = 73; // wada-wpf extension
    const EVT_PLG_WPF_FORM_UNTRASHED = 74; // wada-wpf extension
    const EVT_PLG_WPF_GENERAL_SETTINGS_UPDATE = 75; // wada-wpf extension
    const EVT_PLG_CF7_FORM_SUBMISSION = 76; // wada-cf7 extension
    const EVT_PLG_CF7_FORM_CREATE = 77; // wada-cf7 extension
    const EVT_PLG_CF7_FORM_UPDATE = 78; // wada-cf7 extension
    const EVT_PLG_CF7_FORM_DELETE = 79; // wada-cf7 extension
    const EVT_FILE_THEME_FILE_EDIT = 80;
    const EVT_FILE_PLUGIN_FILE_EDIT = 81;
    const EVT_PLG_RDN_REDIRECT_CREATE = 82; // wada-rdn extension
    const EVT_PLG_RDN_REDIRECT_UPDATE = 83; // wada-rdn extension
    const EVT_PLG_RDN_REDIRECT_DELETE = 84; // wada-rdn extension
    const EVT_PLG_RDN_REDIRECT_ENABLE = 85; // wada-rdn extension
    const EVT_PLG_RDN_REDIRECT_DISABLE = 86; // wada-rdn extension
    const EVT_PLG_RDN_SETTINGS_UPDATED = 87; // wada-rdn extension


    const OBJ_TYPE_CORE_USER = 'WPU';
    const OBJ_TYPE_CORE_POST = 'WPP';
    const OBJ_TYPE_CORE_TERM = 'WPT';
    const OBJ_TYPE_CORE_COMMENT = 'WPC';
    const OBJ_TYPE_CORE_MENU = 'WPM';
    const OBJ_TYPE_FILE = 'FILE';
    const OBJ_TYPE_PLG_WADA_SENSOR = 'PLG_WADA_S';
    const OBJ_TYPE_PLG_WADA_NOTIFICATION = 'PLG_WADA_N';

    // for reference purposes only, need to be established (identical) in extension sensor classes extending that Base!
    const OBJ_TYPE_PLG_WC_PRODUCT = 'PLG_WC_P';
    const OBJ_TYPE_PLG_WPF_FORM = 'PLG_WPF_F';
    const OBJ_TYPE_PLG_CF7_FORM = 'PLG_CF7_F';


    const GRP_CORE = 'Core';
    const GRP_COMMENT = 'Comment';
    const GRP_FILE = 'File';
    const GRP_MEDIA = 'Media';
    const GRP_MENU = 'Menu';
    const GRP_OPTION = 'Option';
    const GRP_POST = 'Post';
    const GRP_SETTING = 'Setting';
    const GRP_TAXONOMY = 'Taxonomy';
    const GRP_THEME = 'Theme';
    const GRP_USER = 'User';
    const GRP_PLUGIN = 'Plugin';
    const GRP_PLG_WADA = 'Wada';
    const GRP_PLG_WC_PRODUCT = 'Wooc_Product'; // for reference only
    const GRP_PLG_WPF = 'Wpforms'; // for reference only

    const WADA_PSEUDO_USER_ID = -987654321;

    const CAT_CORE = 'Core';
    const CAT_PLUGIN = 'Plugin';

    public $sensorGroup = null;
    public $activeSensors = array();
    public $eventTracker = array();

    public function __construct($sensorGroup = null){
        if($sensorGroup) {
            $sensorModel = new WADA_Model_Sensor();
            $sensorGroup = $sensorModel->normalizeSensorGroup($sensorGroup);
            $this->sensorGroup = $sensorGroup;
            $sensorModel->loadSensorsOfGroup($sensorGroup);
            foreach($sensorModel->_data AS $sensor){
                if($sensor->active > 0){
                    $this->activeSensors[$sensor->id] = $sensor->name;
                }
            }
        }
    }

    public function isActiveSensor($sensorId){
        return array_key_exists($sensorId, $this->activeSensors);
    }

    protected function skipEvent($sensorId, $becauseInactive = true, $reason = null){
        if($becauseInactive){
            WADA_Log::debug('Skipping event '.$sensorId . ' (active sensors in sensor group '.$this->sensorGroup.': '.implode(',', array_keys($this->activeSensors)).')');
        }else{
            WADA_Log::debug('Skipping event ' . $sensorId . ($reason ? ' ('.$reason.')' : ''));
        }
        return 0;
    }

    abstract function registerSensor();

    public static function getEventInfoElement($key, $value, $prior = null, $onlyIfValueIsNotNull = true){
        if(!is_null($value) || !$onlyIfValueIsNotNull){
            return array('info_key' => $key, 'info_value' => $value, 'prior_value' => $prior);
        }
        return null;
    }

    protected static function cleanupEventInfoArray($infos){
        $cleanedInfos = array();
        WADA_Log::debug('cleanupEventInfoArray infos: '.print_r($infos, true));
        if(!is_array($infos) || (count($infos) == 0)){
            WADA_Log::debug('cleanupEventInfoArray return empty array');
            return $cleanedInfos;
        }
        //WADA_Log::debug('cleanupEventInfoArray infos: '.print_r($infos, true));
        foreach($infos AS $info){
            if($info && is_array($info)
                && array_key_exists('info_key', $info)
                && array_key_exists('info_value', $info)
                && array_key_exists('prior_value', $info)
            ){
                $cleanedInfos[] = $info;
            }
        }
        WADA_Log::debug('cleanupEventInfoArray cleanedInfos: '.print_r($cleanedInfos, true));
        return $cleanedInfos;
    }

    public function logForDev(){ // for development purposes only, to print args of hooks/filters
        $args = func_get_args();
        foreach ($args as $arg){
            $varType = gettype($arg);
            WADA_Log::debug('logForDev [type: '.$varType.']: '.print_r($arg, true));
        }
    }

    /**
     * @param int $sensorId
     * @param object $event
     * @return bool
     */
    protected function storeEvent($sensorId, $event){
        $event->sensor_id = $sensorId;
        $event->infos = (property_exists($event, 'infos') ? self::cleanupEventInfoArray($event->infos) : array());
        $event->check_value_head = WADA_Model_Event::getHeaderCheckValue($event);
        $eventModel = new WADA_Model_Event();
        if($eventModel->store($event)){ // part of storing the eventModel's _data variable is updated
            do_action('wp_admin_audit_new_event', $eventModel->_id, $eventModel->_data);
            return true;
        }
        return false;
    }

    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = null){
        $userName = $userEmail = null;
        if($userId) {
            $wpUser = get_user_by('id', $userId);
        }else{
            $wpUser = wp_get_current_user();
        }

        if($wpUser instanceof WP_User){
            //WADA_Log::debug('getEventDefaults wpUser '.print_r($wpUser, true));
            $userId = $wpUser->ID;
            $userName = $wpUser->user_login;
            $userEmail = $wpUser->user_email ;
        }

        $ipAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : null;
        if($ipAddress && WADA_Settings::isAnonymizeIPAddress()){
            $ipAddress = wp_privacy_anonymize_ip($ipAddress);
        }

        $data = array();
        $data['id'] = null;
        $data['occurred_on'] = WADA_DateUtils::getUTCforMySQLTimestamp();
        $data['site_id'] = (function_exists('get_current_blog_id') ? get_current_blog_id() : 0);
        $data['user_id'] = $userId;
        $data['user_name'] = $userName;
        $data['user_email'] = $userEmail;
        $data['object_type'] = $targetObjectType;
        $data['object_id'] = $targetObjectId;
        $data['source_ip'] = $ipAddress;
        $data['source_client'] = sanitize_text_field(array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null);
        $data['check_value_head'] = null;
        $data['check_value_full'] = null;
        $data['replication_done'] = 0;
        $data['infos'] = null;
        WADA_Log::debug('getEventDefaults data '.print_r($data, true).', context/current_action: '.current_action());

        return $data;
    }

    public static function matchEvent($keyValuesToMatch, $searchArray, $logic = 'AND'){
        $logic = strtoupper($logic);
        if($logic === 'OR'){
            $res = false;
        }else{
            $logic = 'AND';
            $res = true;
        }

        if(count($keyValuesToMatch) && (!$searchArray || !is_array($searchArray))){
            return false; // nothing to find like that
        }
        if(count($keyValuesToMatch) == 0 && count($searchArray) == 0){
            return true; // both empty = all good
        }

        foreach($keyValuesToMatch AS $key => $value){
            $valInSearchArray = (array_key_exists($key, $searchArray)) ? $searchArray[$key] : null;
            if($logic === 'OR'){
                $res = ($res || ($valInSearchArray === $value));
                if($res === true) break;
            }else{
                $res = ($res && ($valInSearchArray === $value));
                if($res === false) break;
            }
        }
        return $res;
    }

}