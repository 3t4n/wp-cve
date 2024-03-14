<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_UserEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-user-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        if($this->event->object_id > 0) {
            $title = sprintf(__('User ID %d', 'wp-admin-audit'), $this->event->object_id);
        }else{
            $title = __('User event', 'wp-admin-audit');
        }
        $subtitle = '';

        $delUserName = $this->extractCurrentOrPriorValueFromInfoArray($this->event->infos, 'DEL_DATA_display_name'); // fallback for deleted entries
        $userName = $this->extractCurrentOrPriorValueFromInfoArray($this->event->infos, 'display_name', $delUserName);
        WADA_Log::debug('UserEvent->getEventTitleAndSubtitle userName: '.$userName);

        if($userName){
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_USER_REGISTRATION:
                    if($this->event->user_id == $this->event->object_id){
                        $title = sprintf(__('User %s (ID %d) registered', 'wp-admin-audit'), $userName, $this->event->user_id);
                    }else{
                        $title = sprintf(__('User %s (ID %d) was created by user ID %d', 'wp-admin-audit'), $userName, $this->event->object_id, $this->event->user_id);
                    }
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGIN:
                    $title = sprintf(__('User %s (ID %d) logged in', 'wp-admin-audit'), $userName, $this->event->user_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGIN_FAILED:
                    $title = sprintf(__('User failed to login: %s', 'wp-admin-audit'), $this->event->infos[0]->info_value);
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGOUT:
                    $title = sprintf(__('User %s (ID %d) logged out', 'wp-admin-audit'), $userName, $this->event->user_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_UPDATE:
                    $title = sprintf(__('User %s (ID %d) was updated', 'wp-admin-audit'), $userName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_PASSWORD_RESET:
                    $title = sprintf(__('Password reset for user %s (ID %d)', 'wp-admin-audit'), $userName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_DELETE:
                    $title = sprintf(__('User %s (ID %d) was deleted', 'wp-admin-audit'), $userName, $this->event->object_id);
                    $reassignUserId = $this->extractValueFromInfoArray($this->event->infos, 'REASSIGN_USER_ID');
                    if($reassignUserId){
                        $subtitle = sprintf(__('User ID %d was reassigned to ID %d', 'wp-admin-audit'), $this->event->object_id, $reassignUserId);
                    }
                    break;
            }
        }else{
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_USER_REGISTRATION:
                    if($this->event->user_id == $this->event->object_id){
                        $title = sprintf(__('User ID %d registered', 'wp-admin-audit'), $this->event->user_id);
                    }else{
                        $title = sprintf(__('User ID %d was created by user ID %d', 'wp-admin-audit'), $this->event->object_id, $this->event->user_id);
                    }
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGIN:
                    $title = sprintf(__('User ID %d logged in', 'wp-admin-audit'), $this->event->user_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGIN_FAILED:
                    $title = sprintf(__('User failed to login: %s', 'wp-admin-audit'), $this->event->infos[0]->info_value);
                    break;
                case WADA_Sensor_Base::EVT_USER_LOGOUT:
                    $title = sprintf(__('User ID %d logged out', 'wp-admin-audit'), $this->event->user_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_UPDATE:
                    $title = sprintf(__('User ID %d was updated', 'wp-admin-audit'), $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_PASSWORD_RESET:
                    $title = sprintf(__('Password reset for user ID %d', 'wp-admin-audit'), $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_USER_DELETE:
                    $title = sprintf(__('User ID %d was deleted', 'wp-admin-audit'), $this->event->object_id);
                    $reassignUserId = $this->extractValueFromInfoArray($this->event->infos, 'REASSIGN_USER_ID');
                    if($reassignUserId){
                        $subtitle = sprintf(__('User ID %d was reassigned to ID %d', 'wp-admin-audit'), $this->event->object_id, $reassignUserId);
                    }
                    break;
            }
        }
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
            array('info_key_prefix' => 'DEL_DATA_', 'callback' => array($this, 'renderDeletedLines')),
            array('info_key' => 'REASSIGN_USER_ID', 'callback' => array($this, 'skipLine'))
        );
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            list($title, $subtitle) = $this->getEventTitleAndSubtitle();
            $specialInfoKeys = $this->getSpecialInfoKeys();
            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}