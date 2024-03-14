<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_WADAEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-wada-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $title = '';
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_PLG_WADA_SENSOR_UPDATE:
                $title = sprintf(__('WP Admin Audit sensor ID %d updated', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_SETTINGS_UPDATE:
                $title = __('WP Admin Audit settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE:
                $title =  sprintf(__('WP Admin Audit notification created', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE:
                $title =  sprintf(__('WP Admin Audit notification updated', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE:
                $title =  sprintf(__('WP Admin Audit notification deleted', 'wp-admin-audit'), $this->event->object_id);
                break;
        }
        return array($title, $subtitle);
    }

    public function renderSettingsChangeTable(){
        $allSettingsWithNameAsKey = WADA_Settings::getAllSettings(true);
        $innerHtml = '';
        if($this->event && $this->event->infos){
            $innerHtml = '<table class="data wada-detail-table">';
            $innerHtml .= '<tbody>';
            $innerHtml .= '<tr>';
            $innerHtml .= '<th class="label">' . esc_html(__('Setting', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '<th class="label">' . esc_html(__('New value', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '<th class="label">' . esc_html(__('Prior value', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '</tr>';
            foreach ($this->event->infos as $info) {
                $field = $info->info_key;
                $value = $info->info_value;
                $priorValue = $info->prior_value;
                if(array_key_exists($info->info_key, $allSettingsWithNameAsKey)){
                    $setting = $allSettingsWithNameAsKey[$info->info_key];
                    $field = $setting->metaData->label;
                    if($setting->metaData->field === 'checkbox'){
                        if($value == '1'){
                            $value = 'X';
                        }
                        if($priorValue == '1'){
                            $priorValue = 'X';
                        }
                    }else if($setting->metaData->field === 'select'){
                        if(array_key_exists($value, $setting->metaData->selectOptions)){
                            $value = $setting->metaData->selectOptions[$value];
                        }
                        if(array_key_exists($priorValue, $setting->metaData->selectOptions)){
                            $priorValue = $setting->metaData->selectOptions[$priorValue];
                        }
                    }
                }
                $innerHtml .= $this->renderDefaultEventInfosRow($field, $value, $priorValue);
            }
            $innerHtml .= '</tbody>';
            $innerHtml .= '</table>';
        }
        return $innerHtml;
    }

    protected function prepareNotificationExplanations(){
        for($i=0; $i<count($this->event->infos); $i++){
            $explanation = '';
            $info = $this->event->infos[$i];

                switch($info->info_key){
                    case 'Name':
                    case 'name':
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE){
                            $explanation = __('Name was changed', 'wp-admin-audit');
                        }else{
                            $explanation = __('Notification name', 'wp-admin-audit');
                        }
                        $this->event->infos[$i]->info_key = __('Name', 'wp-admin-audit');
                        break;
                    case 'active':
                    case 'Active':
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if (intval($info->info_value) > intval($info->prior_value)) {
                                $explanation = __('Notification was activated', 'wp-admin-audit');
                            } elseif (intval($info->info_value) < intval($info->prior_value)) {
                                $explanation = __('Notification was disabled', 'wp-admin-audit');
                            }
                        }else{
                            $explanation = __('Activation status', 'wp-admin-audit');
                        }
                        $this->event->infos[$i]->info_key = __('Active', 'wp-admin-audit');
                        break;
                    case 'Sensor':
                    case 'Triggers/Sensor':
                        $this->event->infos[$i]->info_key = __('Trigger', 'wp-admin-audit') . ': ' . __('Sensor', 'wp-admin-audit');
                        $val = !is_null($info->info_value) ? $info->info_value :  $info->prior_value;
                        $valArr = explode(', ', $val);
                        $sensorId = 0;
                        if(count($valArr) > 1){
                            $sensorNames = array();
                            $sensorIds = array_map('intval', $valArr);
                            foreach($sensorIds as $sensorId){
                                $sensorModel = new WADA_Model_Sensor($sensorId);
                                $sensorName = $sensorId > 0 ? $sensorModel->_data->name : '';
                                $sensorNames[] = $sensorName;
                            }
                            $sensorName = implode(', ', $sensorNames);
                        }else {
                            $sensorId = (intval($info->info_value) > 0) ? intval($info->info_value) : ((intval($info->prior_value) > 0) ? intval($info->prior_value) : 0);
                            $sensorModel = new WADA_Model_Sensor($sensorId);
                            $sensorName = $sensorId > 0 ? $sensorModel->_data->name : '';
                        }
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if (intval($info->info_value) > intval($info->prior_value)) {
                                $explanation = sprintf(__('Sensor-based trigger added (Sensor ID %d, name: %s)', 'wp-admin-audit'), $sensorId, $sensorName);
                            } elseif (intval($info->info_value) < intval($info->prior_value)) {
                                $explanation = sprintf(__('Sensor-based trigger removed (Sensor ID %d, name: %s)', 'wp-admin-audit'), $sensorId, $sensorName);
                            }
                        }else{
                            if($sensorName) {
                                if(count($valArr) > 1){
                                    $explanation = sprintf(__('Sensors: %s', 'wp-admin-audit'), $sensorName);
                                }else {
                                    $explanation = sprintf(__('Sensor ID %d, name: %s', 'wp-admin-audit'), $sensorId, $sensorName);
                                }
                            }
                        }
                        break;
                    case 'Severity':
                    case 'Triggers/Severity':
                        $this->event->infos[$i]->info_key = __('Trigger', 'wp-admin-audit') . ': ' . __('Severity', 'wp-admin-audit');
                        $val = !is_null($info->info_value) ? $info->info_value :  $info->prior_value;
                        $valArr = explode(', ', $val);
                        if(count($valArr) > 1){
                            $sevLevelNames = array();
                            $sevLevels = array_map('intval', $valArr);
                            foreach($sevLevels as $sevLevel){
                                $sevLevelNames[] = WADA_Model_Sensor::getSeverityNameForLevel($sevLevel, '');
                            }
                            $sevName = implode(', ', $sevLevelNames);
                        }else{
                            $severityLevel = !is_null($info->info_value) ? intval($info->info_value) : intval($info->prior_value);
                            $sevName = WADA_Model_Sensor::getSeverityNameForLevel($severityLevel, '');
                        }
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if(!is_null($info->info_value)){
                                $explanation = sprintf(__('Severity-based trigger added (Severity: %s)', 'wp-admin-audit'), $sevName);
                            }elseif(!is_null($info->prior_value)){
                                $explanation = sprintf(__('Severity-based trigger removed (Severity: %s)', 'wp-admin-audit'), $sevName);
                            }
                        }else{
                            if($sevName){
                                $explanation = sprintf(__('Severity: %s', 'wp-admin-audit'), $sevName);
                            }
                        }
                        break;
                    case 'User':
                    case 'Targets/Users':
                        $this->event->infos[$i]->info_key = __('Target', 'wp-admin-audit') . ': ' . __('User', 'wp-admin-audit');
                        $val = !is_null($info->info_value) ? $info->info_value :  $info->prior_value;
                        $valArr = explode(', ', $val);
                        if(count($valArr) > 1){
                            $userNames = array();
                            $userIds = array_map('intval', $valArr);
                            foreach($userIds as $userId){
                                $userData = get_userdata($userId);
                                if($userData) {
                                    $displayName = ((property_exists($userData, 'display_name') && $userData->display_name && strlen(trim($userData->display_name)) > 0) ? $userData->display_name : $userData->user_login). ' <' . $userData->user_email . '>';
                                } else {
                                    $displayName = sprintf(__('ID %d', 'wp-admin-audit'), $userId);
                                }
                                $userNames[] = $displayName;
                            }
                            $displayName = implode(', ', $userNames);
                        }else {
                            $userId = (intval($info->info_value) > 0) ? intval($info->info_value) : ((intval($info->prior_value) > 0) ? intval($info->prior_value) : 0);
                            $userData = get_userdata($userId);
                            if($userData) {
                                $displayName = ((property_exists($userData, 'display_name') && $userData->display_name && strlen(trim($userData->display_name)) > 0) ? $userData->display_name : $userData->user_login). ' <' . $userData->user_email . '>';
                            } else {
                                $displayName = sprintf(__('ID %d', 'wp-admin-audit'), $userId);
                            }
                        }
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if (intval($info->info_value) > intval($info->prior_value)) {
                                $explanation = sprintf(__('WordPress user %s added to recipients', 'wp-admin-audit'), $displayName);
                            } elseif (intval($info->info_value) < intval($info->prior_value)) {
                                $explanation = sprintf(__('WordPress user %s removed from recipients', 'wp-admin-audit'), $displayName);
                            }
                        }else{
                            if($displayName){
                                $explanation = sprintf(__('User: %s', 'wp-admin-audit'), $displayName);
                            }
                        }
                        break;
                    case 'Role':
                    case 'Targets/Roles':
                        $this->event->infos[$i]->info_key = __('Target', 'wp-admin-audit') . ': ' . __('Roles', 'wp-admin-audit');
                        $roleName = !is_null($info->info_value) ? $info->info_value : $info->prior_value;
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if (!is_null($info->info_value)) {
                                $explanation = sprintf(__('Recipients with user role %s added', 'wp-admin-audit'), $roleName);
                            } elseif (!is_null($info->prior_value)) {
                                $explanation = sprintf(__('Recipients with user role %s removed', 'wp-admin-audit'), $roleName);
                            }
                        }else{
                            if($roleName){
                                $explanation = sprintf(__('Role: %s', 'wp-admin-audit'), $roleName);
                            }
                        }
                        break;
                    case 'Email':
                    case 'Targets/Emails':
                        $this->event->infos[$i]->info_key = __('Target', 'wp-admin-audit') . ': ' . __('Emails', 'wp-admin-audit');
                        $email = !is_null($info->info_value) ? $info->info_value : $info->prior_value;
                        if($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE) {
                            if (!is_null($info->info_value)) {
                                $explanation = sprintf(__('Recipient %s added', 'wp-admin-audit'), $email);
                            } elseif (!is_null($info->prior_value)) {
                                $explanation = sprintf(__('Recipient %s removed', 'wp-admin-audit'), $email);
                            }
                        }else{
                            $explanation = __('Recipient email address', 'wp-admin-audit');
                        }
                        break;
                }

            $this->event->infos[$i]->explanation_value = $explanation;
        }
    }

    public function renderNotificationChangeTable(){
        $innerHtml = '';
        $singleValue = ($this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE || $this->event->sensor_id == WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE);
        $currValueTitle = $singleValue ? __('Value', 'wp-admin-audit') : __('New value', 'wp-admin-audit');
        if($this->event && $this->event->infos){
            $innerHtml = '<table class="data wada-detail-table">';
            $innerHtml .= '<tbody>';
            $innerHtml .= '<tr>';
            $innerHtml .= '<th class="label">' . esc_html(__('Field', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '<th class="label">' . esc_html($currValueTitle) . '</th>';
            if(!$singleValue) {
                $innerHtml .= '<th class="label">' . esc_html(__('Prior value', 'wp-admin-audit')) . '</th>';
            }
            $innerHtml .= '<th class="label">' . esc_html(__('Description / Explanation', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '</tr>';
            $this->prepareNotificationExplanations();
            foreach ($this->event->infos as $info) {
                if($singleValue){
                    $innerHtml .= $this->renderDefaultEventInfosRowWithSingleValueWithExplanation($info->info_key, $info->info_value, $info->explanation_value);
                }else {
                    $innerHtml .= $this->renderDefaultEventInfosRowWithExplanation($info->info_key, $info->info_value, $info->prior_value, $info->explanation_value);
                }
            }
            $innerHtml .= '</tbody>';
            $innerHtml .= '</table>';
        }
        return $innerHtml;
    }

    public function getEventInfoTableRenderMethod(){ // overwriting parent method
        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_PLG_WADA_SENSOR_UPDATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_SETTINGS_UPDATE:
                $method = 'renderSettingsChangeTable';
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE:
                $method = 'renderNotificationChangeTable';
                break;
            default:
                $method = 'renderDefaultEventInfosTable';
        }
        return $method;
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
            $additionalParams = array();

            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys, $additionalParams);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}