<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_User extends WADA_Sensor_Base
{
    public $metaDataChanges = array();

    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_USER);
    }

    public function registerSensor(){
        add_action('user_register', array($this, 'onUserRegistration'), 10, 2);
        add_action('wp_login', array($this, 'onUserLogin'), 10, 2 );
        add_action('wp_login_failed', array($this, 'onUserLoginFailed'), 10, 2);
        add_action('wp_logout', array($this, 'onUserLogout'));
        add_action('profile_update', array($this, 'onUserUpdate'), 10, 3);
        add_action('personal_options_update', array($this, 'onOwnProfileUpdate'));
        add_action('update_user_meta', array($this, 'onUserMetaUpdate'), 10, 4);
        add_action('current_screen', array($this, 'checkForExpiredPasswordRedirect'), 1);
        add_action('template_redirect', array($this, 'checkForExpiredPasswordRedirect'), 1);
        add_filter('wp_authenticate_user', array($this, 'checkForExpiredPasswordDuringLogin'), 10, 2);
        add_action('delete_user', array($this, 'onUserDelete'), 10, 3);
        add_action('password_reset', array($this, 'onUserPasswordReset'), 10, 2); // hook after_password_reset is more applicable, but does not get fired (sometimes?)
        add_action('wp_admin_audit_loaded_post_sensors', array($this, 'onNewPageLoad'));
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param int $userId
     * @param int|null $reassignUserId
     * @param WP_User $user
     */
    public function onUserDelete($userId, $reassignUserId = null, $user = null){
        if(!$this->isActiveSensor(self::EVT_USER_DELETE)) return $this->skipEvent(self::EVT_USER_DELETE);
        WADA_Log::debug('onUserDelete for userId '.$userId.' reassign user id: '.$reassignUserId.', user: '.print_r($user, true));
        $infos = array();
        if(!$user){
            $user = get_user_by('id', $userId);
        }
        $attributesToRecord = WADA_UserUtils::getTopLevelAttributes();
        $infos[] = self::getEventInfoElement('USER_ID', $userId);
        foreach($attributesToRecord as $attribute){
            if(isset($user->$attribute)) {
                $infos[] = self::getEventInfoElement('DEL_DATA_' . $attribute, $user->$attribute);
            }
        }
        if($reassignUserId && intval($reassignUserId)>0 && $userId != $reassignUserId){
            $infos[] = self::getEventInfoElement('REASSIGN_USER_ID', $reassignUserId);
        }
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $userId;
        return $this->storeUserEvent(self::EVT_USER_DELETE, $eventData, $executingUserId, $targetObjectId);
    }

    /**
     * @param int $userId
     * @param WP_User $oldUserData
     * @param array $userData
     * @return bool
     */
    public function onUserUpdate($userId, $oldUserData, $userData = array(), $executingUserId = 0){
        if(!empty($userData['pass1'])){
            // No matter if it is an active sensor or not, we store last pw change timestamp
            $userModel = new WADA_Model_User($userId);
            $userModel->updateLastPwChange(WADA_DateUtils::getUTCforMySQLTimestamp());
        }

        // Take care of sensor-event handling
        if(!$this->isActiveSensor(self::EVT_USER_UPDATE)) return $this->skipEvent(self::EVT_USER_UPDATE);
        $wpUser = get_user_by('id', $userId);
        $userMetaChanges = array_key_exists($userId, $this->metaDataChanges) ? $this->metaDataChanges[$userId] : array();
        WADA_Log::debug('onUserUpdate for userId '.$userId.' old user data: '.print_r($oldUserData, true).', user data: '.print_r($userData, true).', new user data: '.print_r($wpUser, true));
        WADA_Log::debug('onUserUpdate recorded user meta changes: '.print_r($userMetaChanges, true));
        $changedAttributes = WADA_UserUtils::getChangedAttributes($oldUserData, $wpUser, $userMetaChanges);
        WADA_Log::debug('onUserUpdate changed attributes: '.print_r($changedAttributes, true));
        $eventData = array('infos' => $changedAttributes);
        if($executingUserId == 0){ // introduce to be able to work with WADA_Sensor_Base::WADA_PSEUDO_USER_ID
            $executingUserId = get_current_user_id();
        }
        $targetObjectId = $userId;
        return $this->storeUserEvent(self::EVT_USER_UPDATE, $eventData, $executingUserId, $targetObjectId);
    }


    /**
     * @param int $userId
     */
    public function onOwnProfileUpdate($userId){
        WADA_Log::debug('onOwnProfileUpdate for userId '.$userId);
        if(!empty($_POST['pass1'])){
            // No matter if it is an active sensor or not, we store last pw change timestamp
            $userModel = new WADA_Model_User($userId);
            $userModel->updateLastPwChange(WADA_DateUtils::getUTCforMySQLTimestamp());
        }
    }

    /**
     * @param int $metaId
     * @param int $userId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return bool
     */
    public function onUserMetaUpdate($metaId, $userId, $metaKey, $metaValue){
        if(!array_key_exists($userId, $this->metaDataChanges)){
            $this->metaDataChanges[$userId] = array();
        }
        $prevMetaValue = get_user_meta($userId, $metaKey);
        if(is_array($prevMetaValue) && count($prevMetaValue) == 1){
            $prevMetaValue = $prevMetaValue[0];
        }
        $this->metaDataChanges[$userId][$metaKey] = array('curr' => $metaValue, 'prev' => $prevMetaValue);

        $prevMetaValueStr = is_array($prevMetaValue) ? print_r($prevMetaValue, true) : $prevMetaValue;
        $metaValueAsStr = is_array($metaValue) ? print_r($metaValue, true) : $metaValue;
        WADA_Log::debug('onUserMetaUpdate metaId: '.$metaId.', userId: '.$userId.', metaKey: '.$metaKey.', metaValue: '.$metaValueAsStr.', prevValue: '.$prevMetaValueStr);

        return true;
    }

    /**
     * @param int $userId
     * @param array $userData
     * @return bool
     */
    public function onUserRegistration($userId, $userData){
        // No matter if it is an active sensor or not, we store tracked_since if empty
        $userModel = new WADA_Model_User($userId);
        $userModel->assignTrackedSinceIfEmpty();

        // Take care of sensor-event handling
        if(!$this->isActiveSensor(self::EVT_USER_REGISTRATION)) return $this->skipEvent(self::EVT_USER_REGISTRATION);
        WADA_Log::debug('onUserRegistration for userId '.$userId.' with user data: '.print_r($userData, true));

        $newUserMetaData = get_userdata($userId);
        WADA_Log::debug('onUserRegistration metadata for userId '.$userId.': '.print_r($newUserMetaData, true));

        if($newUserMetaData && property_exists($newUserMetaData, 'roles')){
            $newUserRoles = is_array($newUserMetaData->roles) ?
                implode(', ', $newUserMetaData->roles)
                : (is_string($newUserMetaData->roles) ? $newUserMetaData->roles : null);
        }else{
            $newUserRoles = null;
        }

        $eventData = array('infos' => array(
            self::getEventInfoElement('user_id', $userId), // this is somewhat redundant to target object id, but we are okay with it
            self::getEventInfoElement('user_login', (array_key_exists('user_login', $userData) ? $userData['user_login'] : null)),
            self::getEventInfoElement('user_email', (array_key_exists('user_email', $userData) ? $userData['user_email'] : null)),
            self::getEventInfoElement('roles', $newUserRoles)
        ));

        $targetObjectId = $userId;
        $loggedInUserId = get_current_user_id();
        if($loggedInUserId > 0 && $loggedInUserId != $userId){
            WADA_Log::debug('onUserRegistration User '.$userId.' was created by user '.$loggedInUserId);
            // if the logged-in user is not the user that was created, then we need to care about it
            // that is the admin that created the user via the backend
            $executingUserId = $loggedInUserId;
        }else{
            $executingUserId = $userId;
        }

        return $this->storeUserEvent(self::EVT_USER_REGISTRATION, $eventData, $executingUserId, $targetObjectId);
    }

    protected function storeUserLogin($userLogin, $loginSuccessful, $userId = null){
        $userLoginExisting = ($userId && $userId > 0) ? 1 : 0;
        $ipAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        if(WADA_Settings::isAnonymizeIPAddress()){ // if active, we decouple the address from the user identity
            $userLogin = '___ANONYMIZED___';
            $userId = 0;
        }

        $loginObj = new stdClass();
        $loginObj->login_date = WADA_DateUtils::getUTCforMySQLDate();
        $loginObj->login_successful = $loginSuccessful;
        $loginObj->user_login = $userLogin;
        $loginObj->user_login_existing = $userLoginExisting;
        $loginObj->user_id = $userId;
        $loginObj->ip_address = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $loginModel = new WADA_Model_Login();
        return $loginModel->store($loginObj);
    }

    /**
     * @param string $userLogin
     * @param WP_User $user
     * @return bool
     */
    public function onUserLogin($userLogin, $user){
        // No matter if it is an active sensor or not, we store last login timestamp for user
        $userModel = new WADA_Model_User($user->ID);
        $userModel->updateLastLogin(WADA_DateUtils::getUTCforMySQLTimestamp());

        // No matter if it is an active sensor or not, we store login statistics
        $this->storeUserLogin($userLogin, 1, $user->ID);

        // Take care of sensor-event handling
        if(!$this->isActiveSensor(self::EVT_USER_LOGIN)) return $this->skipEvent(self::EVT_USER_LOGIN);
        WADA_Log::debug('onUserLogin for userLogin '.$userLogin.' with user: '.print_r($user, true));
        return $this->storeUserEvent(self::EVT_USER_LOGIN, array(), $user->ID);
    }

    /**
     * @param string $userLogin
     * @param WP_Error $error
     * @return bool
     */
    public function onUserLoginFailed($userLogin, $error){
        $errorCode = $error->get_error_code();
        $targetUserId = 0;
        if($errorCode === 'incorrect_password'){
            $user = get_user_by('login', $userLogin);
            if($user){
                $targetUserId = $user->ID;
            }
        }
        // No matter if it is an active sensor or not, we store login statistics
        $this->storeUserLogin($userLogin, 0, $targetUserId);

        if(!$this->isActiveSensor(self::EVT_USER_LOGIN_FAILED)) return $this->skipEvent(self::EVT_USER_LOGIN_FAILED);
        WADA_Log::debug('onUserLoginFailed for userLogin '.$userLogin.' with error: '.print_r($error, true));
        $errorMessage = $errorCode . '|' . $error->get_error_message();
        if($errorCode === 'incorrect_password'){
            $errorMessage = __('Incorrect password', 'wp-admin-audit');
        }else if($errorCode === 'invalid_username'){
            $errorMessage = __('Invalid username', 'wp-admin-audit');
        }
        $eventData = array('infos' => array(
            self::getEventInfoElement('username', $userLogin),
            self::getEventInfoElement('error', $errorMessage)
        ));
        return $this->storeUserEvent(self::EVT_USER_LOGIN_FAILED, $eventData, $targetUserId, $targetUserId);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function onUserLogout($userId){
        if(!$this->isActiveSensor(self::EVT_USER_LOGOUT)) return $this->skipEvent(self::EVT_USER_LOGOUT);
        WADA_Log::debug('onUserLogout for userLogin '.$userId);
        return $this->storeUserEvent(self::EVT_USER_LOGOUT, array(), $userId);
    }

    public function onNewPageLoad($ref){
        if ( is_user_logged_in() ) {
            $userModel = new WADA_Model_User(get_current_user_id());
            return $userModel->updateLastSeen(WADA_DateUtils::getUTCforMySQLTimestamp());
        } else {
            return true;
        }
    }

    /**
     * @param WP_User $user
     * @param string $newPassword
     */
    public function onUserPasswordReset($user, $newPassword){
        WADA_Log::debug('onUserPasswordReset for user '.print_r($user, true));
        // No matter if it is an active sensor or not, we store last pw change timestamp
        $userModel = new WADA_Model_User($user->ID);
        $userModel->updateLastPwChange(WADA_DateUtils::getUTCforMySQLTimestamp());

        // Take care of sensor-event handling
        if(!$this->isActiveSensor(self::EVT_USER_PASSWORD_RESET)) return $this->skipEvent(self::EVT_USER_PASSWORD_RESET);
        $loggedInUserId = get_current_user_id();
        return $this->storeUserEvent(self::EVT_USER_PASSWORD_RESET, array(), $loggedInUserId, $user->ID);
    }

    /**
     * @param WP_Screen $current_screen
     */
    public function checkForExpiredPasswordRedirect($current_screen){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

    /**
     * @param WP_User|WP_Error $user
     * @param string $password
     */
    public function checkForExpiredPasswordDuringLogin($user, $password){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */

        return $user; // important to leave in for all product editions, this is a filter being applied!
    }

    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_CORE_USER){
        // change to parent function is that we default to passing in the object type of WP User
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @param int $executingUserId
     * @param int $targetUserId
     * @return bool
     */
    protected function storeUserEvent($sensorId, $eventData = array(), $executingUserId = 0, $targetUserId = 0){
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}