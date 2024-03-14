<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Settings extends WADA_Sensor_Base
{

    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_SETTING);
    }

    public function registerSensor(){
        add_action('admin_init', array($this, 'onAdminInit'));
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    public function onAdminInit(){
        $userCanManageOptions = current_user_can( 'manage_options' );
        $scriptName = array_key_exists('SCRIPT_NAME', $_SERVER) ? basename(sanitize_text_field($_SERVER['SCRIPT_NAME']), '.php') : '';
        $optionPage = array_key_exists('option_page', $_POST) ? sanitize_text_field($_POST['option_page']) : '';
        $isOptionsPage = ($scriptName === 'options' );
        $isPermalinkOptionsPage = ($scriptName === 'options-permalink');
        $isPrivacyOptionsPage = ($scriptName === 'options-privacy');
        $isOneOfTheSettingPages = ($isOptionsPage || $isPermalinkOptionsPage || $isPrivacyOptionsPage);
        $action = array_key_exists('action', $_POST) ? sanitize_text_field($_POST['action']) : '';
        $wpNonce = array_key_exists('_wpnonce', $_POST) ? sanitize_text_field($_POST['_wpnonce']) : '';

        if(!$userCanManageOptions || !$isOneOfTheSettingPages){
            return;
        }
        WADA_Log::debug('Settings -> onAdminInit');
        WADA_Log::debug('Settings -> onAdminInit POST: '.print_r($_POST, true));
        WADA_Log::debug('Settings -> onAdminInit SCRIPT_NAME: '.$scriptName);
        WADA_Log::debug('Settings -> onAdminInit option_page: '.$optionPage);
        WADA_Log::debug('Settings -> onAdminInit action: '.$action);

        if($isOptionsPage) {
            WADA_Log::debug('Settings -> onAdminInit -> optionsPage '.$optionPage);
            if ($optionPage === 'general' && wp_verify_nonce($wpNonce, 'general-options')) {
                WADA_Log::debug('Settings -> onAdminInit -> general -> process it!');
                $this->processGeneralSettings($action);
            } elseif ($optionPage === 'writing' && wp_verify_nonce($wpNonce, 'writing-options')) {
                WADA_Log::debug('Settings -> onAdminInit -> writing -> process it!');
                $this->processWritingSettings($action);
            } elseif ($optionPage === 'reading' && wp_verify_nonce($wpNonce, 'reading-options')) {
                WADA_Log::debug('Settings -> onAdminInit -> reading -> process it!');
                $this->processReadingSettings($action);
            } elseif ($optionPage === 'discussion' && wp_verify_nonce($wpNonce, 'discussion-options')) {
                WADA_Log::debug('Settings -> onAdminInit -> discussion -> process it!');
                $this->processDiscussionSettings($action);
            } elseif ($optionPage === 'media' && wp_verify_nonce($wpNonce, 'media-options')) {
                WADA_Log::debug('Settings -> onAdminInit -> media -> process it!');
                $this->processMediaSettings($action);
            }
        }

        if($isPermalinkOptionsPage && wp_verify_nonce($wpNonce, 'update-permalink')){
            WADA_Log::debug('Settings -> onAdminInit -> permalink settings');
            $this->processPermalinkSettings();
        }

        if($isPrivacyOptionsPage && wp_verify_nonce($wpNonce, 'set-privacy-page')){
            WADA_Log::debug('Settings -> onAdminInit -> privacy settings');
            $this->processPrivacySettings();
        }
    }

    protected function getFieldChangeInfoIfApplicable($fieldName, $fieldType='string', $customFieldName=null, $optionName=null){
        if(is_null($optionName)){
            $optionName = $fieldName;
        }
        $prevValue = get_option($optionName);
        $newValue = isset($_POST[$fieldName]) ? sanitize_text_field(stripslashes($_POST[$fieldName])) : '';
        if($newValue === '' && $fieldType === 'bool'){
            if($prevValue !== ''){
                $newValue = '0';
            }
        }
        if(($newValue === 'custom' || $newValue === '\c\u\s\t\o\m') && ($customFieldName)){
            $newValue = isset($_POST[$customFieldName]) ? sanitize_text_field(stripslashes($_POST[$customFieldName])) : '';
        }
        // WADA_Log::debug('getFieldChangeInfoIfApplicable field '.$fieldName.' (new: '.$newValue.', prev: '.$prevValue.')');
        if($prevValue !== $newValue){
            return self::getEventInfoElement($fieldName, $newValue, $prevValue);
        }
        return false;
    }

    protected function getFieldChanges($fields){
        $changes = array();
        // WADA_Log::debug('getFieldChanges for fields: '.print_r($fields, true));
        foreach($fields as $field){
            $chg = $this->getFieldChangeInfoIfApplicable(
                $field['field'],
                $field['type'],
                (array_key_exists('custom_via', $field) ? $field['custom_via'] : null),
                (array_key_exists('option_field', $field) ? $field['option_field'] : null)
            );
            if($chg){
                $changes[] = $chg;
            }
        }
        return $changes;
    }

    protected function processGeneralSettings($action){
        if(!$this->isActiveSensor(self::EVT_SETTING_GENERAL_UPDATE)) return $this->skipEvent(self::EVT_SETTING_GENERAL_UPDATE);
        $fieldsWatched = [
            array('field'=>'blogname', 'type'=>'string'),
            array('field'=>'blogdescription', 'type'=>'string'),
            array('field'=>'siteurl', 'type'=>'string'),
            array('field'=>'home', 'type'=>'string'),
            array('field'=>'new_admin_email', 'type'=>'string'),
            array('field'=>'users_can_register', 'type'=>'bool'),
            array('field'=>'default_role', 'type'=>'string'),
            array('field'=>'WPLANG', 'type'=>'string'),
            array('field'=>'timezone_string', 'type'=>'string'),
            array('field'=>'date_format', 'type'=>'string', 'custom_via'=>'date_format_custom'),
            array('field'=>'time_format', 'type'=>'string', 'custom_via'=>'time_format_custom'),
            array('field'=>'start_of_week', 'type'=>'string')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processGeneralSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_GENERAL_UPDATE, array('infos' => $changes));
    }

    protected function processWritingSettings($action){
        if(!$this->isActiveSensor(self::EVT_SETTING_WRITING_UPDATE)) return $this->skipEvent(self::EVT_SETTING_WRITING_UPDATE);
        $fieldsWatched = [
            array('field'=>'default_category', 'type'=>'string'),
            array('field'=>'default_post_format', 'type'=>'string'),
            array('field'=>'mailserver_url', 'type'=>'string'),
            array('field'=>'mailserver_port', 'type'=>'string'),
            array('field'=>'mailserver_login', 'type'=>'string'),
            array('field'=>'mailserver_pass', 'type'=>'string'),
            array('field'=>'default_email_category', 'type'=>'string')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processWritingSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_WRITING_UPDATE, array('infos' => $changes));
    }

    protected function processReadingSettings($action){
        if(!$this->isActiveSensor(self::EVT_SETTING_READING_UPDATE)) return $this->skipEvent(self::EVT_SETTING_READING_UPDATE);
        $fieldsWatched = [
            array('field'=>'show_on_front', 'type'=>'string'),
            array('field'=>'page_on_front', 'type'=>'string'),
            array('field'=>'page_for_posts', 'type'=>'string'),
            array('field'=>'posts_per_page', 'type'=>'string'),
            array('field'=>'posts_per_rss', 'type'=>'string'),
            array('field'=>'rss_use_excerpt', 'type'=>'string'),
            array('field'=>'blog_public', 'type'=>'bool')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processReadingSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_READING_UPDATE, array('infos' => $changes));
    }

    protected function processDiscussionSettings($action){
        if(!$this->isActiveSensor(self::EVT_SETTING_DISCUSSION_UPDATE)) return $this->skipEvent(self::EVT_SETTING_DISCUSSION_UPDATE);
        $fieldsWatched = [
            array('field'=>'default_pingback_flag', 'type'=>'bool'),
            array('field'=>'default_ping_status', 'type'=>'string'),
            array('field'=>'default_comment_status', 'type'=>'string'),
            array('field'=>'require_name_email', 'type'=>'bool'),
            array('field'=>'comment_registration', 'type'=>'bool'),
            array('field'=>'close_comments_for_old_posts', 'type'=>'bool'),
            array('field'=>'close_comments_days_old', 'type'=>'string'),
            array('field'=>'show_comments_cookies_opt_in', 'type'=>'bool'),
            array('field'=>'thread_comments', 'type'=>'bool'),
            array('field'=>'thread_comments_depth', 'type'=>'string'),
            array('field'=>'page_comments', 'type'=>'bool'),
            array('field'=>'comments_per_page', 'type'=>'string'),
            array('field'=>'default_comments_page', 'type'=>'string'),
            array('field'=>'comment_order', 'type'=>'string'),
            array('field'=>'comments_notify', 'type'=>'bool'),
            array('field'=>'moderation_notify', 'type'=>'bool'),
            array('field'=>'comment_moderation', 'type'=>'bool'),
            array('field'=>'comment_previously_approved', 'type'=>'bool'),
            array('field'=>'comment_max_links', 'type'=>'string'),
            array('field'=>'moderation_keys', 'type'=>'string'),
            array('field'=>'disallowed_keys', 'type'=>'string'),
            array('field'=>'show_avatars', 'type'=>'bool'),
            array('field'=>'avatar_rating', 'type'=>'string'),
            array('field'=>'avatar_default', 'type'=>'string')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processDiscussionSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_DISCUSSION_UPDATE, array('infos' => $changes));

    }

    protected function processMediaSettings($action){
        if(!$this->isActiveSensor(self::EVT_SETTING_MEDIA_UPDATE)) return $this->skipEvent(self::EVT_SETTING_MEDIA_UPDATE);
        $fieldsWatched = [
            array('field'=>'thumbnail_size_w', 'type'=>'string'),
            array('field'=>'thumbnail_size_h', 'type'=>'string'),
            array('field'=>'thumbnail_crop', 'type'=>'bool'),
            array('field'=>'medium_size_w', 'type'=>'string'),
            array('field'=>'medium_size_h', 'type'=>'string'),
            array('field'=>'large_size_w', 'type'=>'string'),
            array('field'=>'large_size_h', 'type'=>'string'),
            array('field'=>'uploads_use_yearmonth_folders', 'type'=>'bool')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processMediaSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_MEDIA_UPDATE, array('infos' => $changes));
    }

    protected function processPermalinkSettings(){
        if(!$this->isActiveSensor(self::EVT_SETTING_PERMALINK_UPDATE)) return $this->skipEvent(self::EVT_SETTING_PERMALINK_UPDATE);
        $fieldsWatched = [
            array('field'=>'permalink_structure', 'type'=>'string'),
            array('field'=>'category_base', 'type'=>'string'),
            array('field'=>'tag_base', 'type'=>'string')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processPermalinkSettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_PERMALINK_UPDATE, array('infos' => $changes));
    }

    protected function processPrivacySettings(){
        if(!$this->isActiveSensor(self::EVT_SETTING_PRIVACY_UPDATE)) return $this->skipEvent(self::EVT_SETTING_PRIVACY_UPDATE);
        $fieldsWatched = [
            array('field'=>'page_for_privacy_policy', 'type'=>'string', 'option_field'=>'wp_page_for_privacy_policy')
        ];
        $changes = $this->getFieldChanges($fieldsWatched);
        WADA_Log::debug('processPrivacySettings changes:'.print_r($changes, true));
        return $this->storeSettingEvent(self::EVT_SETTING_PRIVACY_UPDATE, array('infos' => $changes));
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storeSettingEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}