<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Option extends WADA_Sensor_Base
{
    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_OPTION);
    }

    public function registerSensor(){
        add_action('added_option',   array($this, 'onOptionCreate'), 10, 2);
        add_action('updated_option', array($this, 'onOptionUpdate'), 10, 3);
        add_action('deleted_option', array($this, 'onOptionDelete'), 10, 1);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    public function onOptionCreate($option, $value){
        if(!$this->isActiveSensor(self::EVT_OPTION_CREATE)) return $this->skipEvent(self::EVT_OPTION_CREATE);
        $infos = array();
        $infos[] = self::getEventInfoElement('OPTION_NAME', $option);
        $infos[] = self::getEventInfoElement($option, $value);
        return $this->storeOptionEvent(self::EVT_OPTION_CREATE, array('infos' => $infos));
    }

    /**
     * @param string $option
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function onOptionUpdate($option, $oldValue, $newValue){
        if(!is_scalar($oldValue)){
            $oldValue = print_r($oldValue, true);
        }
        if(!is_scalar($newValue)){
            $newValue = print_r($newValue, true);
        }
     //   WADA_Log::debug('onOptionUpdate '.$option.' '.$oldValue.' -> '.$newValue);
        $sensorId = self::EVT_OPTION_UPDATE_OTHER;
        $eventOptionUpdateCoreIsActive = $this->isActiveSensor(self::EVT_OPTION_UPDATE_CORE);
        $eventOptionUpdateOtherIsActive = $this->isActiveSensor(self::EVT_OPTION_UPDATE_OTHER);
     //   WADA_Log::debug('onOptionUpdate core '.$eventOptionUpdateCoreIsActive.', other: '.$eventOptionUpdateOtherIsActive);

        if(!$eventOptionUpdateCoreIsActive && !$eventOptionUpdateOtherIsActive) return $this->skipEvent($sensorId);

        $coreOptions = $this->getCoreOptions();
        $isCoreOption = false;
        if(in_array($option, $coreOptions)){
            $sensorId = self::EVT_OPTION_UPDATE_CORE;
            $isCoreOption = true;
        }


        if(!$eventOptionUpdateCoreIsActive  && $isCoreOption)  return $this->skipEvent($sensorId);
        if(!$eventOptionUpdateOtherIsActive && !$isCoreOption) return $this->skipEvent($sensorId);

        $infos = array();
        $infos[] = self::getEventInfoElement('OPTION_NAME', $option);
        $infos[] = self::getEventInfoElement($option, $newValue, $oldValue);
        return $this->storeOptionEvent($sensorId, array('infos' => $infos));
    }

    public function onOptionDelete($option){
        if(!$this->isActiveSensor(self::EVT_OPTION_DELETE)) return $this->skipEvent(self::EVT_OPTION_DELETE);
        $value = get_option($option);
        $infos = array();
        $infos[] = self::getEventInfoElement('OPTION_NAME', $option);
        $infos[] = self::getEventInfoElement($option, $value);
        return $this->storeOptionEvent(self::EVT_OPTION_DELETE, array('infos' => $infos));
    }

    /**
     * @return string[]
     */
    protected function getCoreOptions(){
        $coreOptions = array(
            'siteurl',
            'home',
            'blogname',
            'blogdescription',
            'users_can_register',
            'admin_email',
            'start_of_week',
            'use_balanceTags',
            'use_smilies',
            'require_name_email',
            'comments_notify',
            'posts_per_rss',
            'rss_use_excerpt',
            'mailserver_url',
            'mailserver_login',
            'mailserver_pass',
            'mailserver_port',
            'default_category',
            'default_comment_status',
            'default_ping_status',
            'default_pingback_flag',
            'posts_per_page',
            'date_format',
            'time_format',
            'links_updated_date_format',
            'comment_moderation',
            'moderation_notify',
            'permalink_structure',
            'rewrite_rules',
            'hack_file',
            'blog_charset',
            'moderation_keys',
            'active_plugins',
            'category_base',
            'ping_sites',
            'comment_max_links',
            'gmt_offset',
            'default_email_category',
            'recently_edited',
            'template',
            'stylesheet',
            'comment_registration',
            'html_type',
            'use_trackback',
            'default_role',
            'db_version',
            'uploads_use_yearmonth_folders',
            'upload_path',
            'blog_public',
            'default_link_category',
            'show_on_front',
            'tag_base',
            'show_avatars',
            'avatar_rating',
            'upload_url_path',
            'thumbnail_size_w',
            'thumbnail_size_h',
            'thumbnail_crop',
            'medium_size_w',
            'medium_size_h',
            'avatar_default',
            'large_size_w',
            'large_size_h',
            'image_default_link_type',
            'image_default_size',
            'image_default_align',
            'close_comments_for_old_posts',
            'close_comments_days_old',
            'thread_comments',
            'thread_comments_depth',
            'page_comments',
            'comments_per_page',
            'default_comments_page',
            'comment_order',
            'sticky_posts',
            'widget_categories',
            'widget_text',
            'widget_rss',
            'uninstall_plugins',
            'timezone_string',
            'page_for_posts',
            'page_on_front',
            'default_post_format',
            'link_manager_enabled',
            'finished_splitting_shared_terms',
            'site_icon',
            'medium_large_size_w',
            'medium_large_size_h',
            'wp_page_for_privacy_policy',
            // 4.9.8
            'show_comments_cookies_opt_in',
            // 5.3.0
            'admin_email_lifespan',
            // 5.5.0
            'disallowed_keys',
            'comment_previously_approved',
            'auto_plugin_theme_update_emails',
            // 5.6.0
            'auto_update_core_dev',
            'auto_update_core_minor',
            'auto_update_core_major',
            // 5.8.0
            'wp_force_deactivated_plugins'
        );

        // 3.3.0
        if ( ! is_multisite() ) {
            $coreOptions[] = 'initial_db_version';
        }

        // 3.0.0 multisite.
        if ( is_multisite() ) {
            $coreOptions[] = 'blogdescription';
            $coreOptions[] = 'permalink_structure';
        }

        return $coreOptions;
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storeOptionEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}