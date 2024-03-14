<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_SettingEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-setting-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $title = __('WordPress setting event', 'wp-admin-audit');
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_SETTING_GENERAL_UPDATE:
                $title = __('WordPress general settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_WRITING_UPDATE:
                $title = __('WordPress writing settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_READING_UPDATE:
                $title = __('WordPress reading settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_DISCUSSION_UPDATE:
                $title = __('WordPress discussion settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_MEDIA_UPDATE:
                $title = __('WordPress media settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_PERMALINK_UPDATE:
                $title = __('WordPress permalink settings updated', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_SETTING_PRIVACY_UPDATE:
                $title = __('WordPress privacy settings updated', 'wp-admin-audit');
                break;
        }
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
            // General Settings
            array('info_key' => 'blogname', 'info_key_label' => __('Site title', 'wp-admin-audit')),
            array('info_key' => 'blogdescription', 'info_key_label' => __('Tagline', 'wp-admin-audit')),
            array('info_key' => 'siteurl', 'info_key_label' => __('WordPress Address (URL)', 'wp-admin-audit')),
            array('info_key' => 'home', 'info_key_label' => __('Site Address (URL)', 'wp-admin-audit')),
            array('info_key' => 'new_admin_email', 'info_key_label' => __('Administration Email Address', 'wp-admin-audit')),
            array('info_key' => 'users_can_register', 'info_key_label' => __('Anyone can register', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'default_role', 'info_key_label' => __('New User Default Role', 'wp-admin-audit')),
            array('info_key' => 'WPLANG', 'info_key_label' => __('Site Language', 'wp-admin-audit')),
            array('info_key' => 'timezone_string', 'info_key_label' => __('Timezone', 'wp-admin-audit')),
            array('info_key' => 'date_format', 'info_key_label' => __('Date Format', 'wp-admin-audit')),
            array('info_key' => 'time_format', 'info_key_label' => __('Time Format', 'wp-admin-audit')),
            array('info_key' => 'start_of_week', 'info_key_label' => __('Week Starts On', 'wp-admin-audit')),
            array('info_key' => 'date_format_custom', 'info_key_label' => __('Custom Date Format', 'wp-admin-audit')),
            array('info_key' => 'time_format_custom', 'info_key_label' => __('Custom Time Format', 'wp-admin-audit')),

            // Writing Settings
            array('info_key' => 'default_category', 'info_key_label' => __('Default Post Category', 'wp-admin-audit')),
            array('info_key' => 'default_post_format', 'info_key_label' => __('Default Post Format', 'wp-admin-audit')),
            array('info_key' => 'mailserver_url', 'info_key_label' => __('Mail Server', 'wp-admin-audit')),
            array('info_key' => 'mailserver_port', 'info_key_label' => __('Port', 'wp-admin-audit')),
            array('info_key' => 'mailserver_login', 'info_key_label' => __('Login Name', 'wp-admin-audit')),
            array('info_key' => 'mailserver_pass', 'info_key_label' => __('Password', 'wp-admin-audit')),
            array('info_key' => 'default_email_category', 'info_key_label' => __('Default Mail Category', 'wp-admin-audit')),

            // Reading Settings
            array('info_key' => 'show_on_front', 'info_key_label' => __('Your homepage displays', 'wp-admin-audit')),
            array('info_key' => 'page_on_front', 'info_key_label' => __('Homepage: Homepage', 'wp-admin-audit')),
            array('info_key' => 'page_for_posts', 'info_key_label' => __('Homepage: Posts Page', 'wp-admin-audit')),
            array('info_key' => 'posts_per_page', 'info_key_label' => __('Blog pages show at most', 'wp-admin-audit')),
            array('info_key' => 'posts_per_rss', 'info_key_label' => __('Syndication feeds show the most recent', 'wp-admin-audit')),
            array('info_key' => 'rss_use_excerpt', 'info_key_label' => __('For each post in a feed, include', 'wp-admin-audit')),
            array('info_key' => 'blog_public', 'info_key_label' => __('Search engine visibility', 'wp-admin-audit')),

            // Discussion Settings
            array('info_key' => 'default_pingback_flag', 'info_key_label' => __('Attempt to notify any blogs linked to from the post', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'default_ping_status', 'info_key_label' => __('Allow link notifications from other blogs (pingbacks and trackbacks) on new posts', 'wp-admin-audit')),
            array('info_key' => 'default_comment_status', 'info_key_label' => __('Allow people to submit comments on new posts', 'wp-admin-audit')),
            array('info_key' => 'require_name_email', 'info_key_label' => __('Comment author must fill out name and email', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'comment_registration', 'info_key_label' => __('Users must be registered and logged in to comment', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'close_comments_for_old_posts', 'info_key_label' => __('Automatically close comments on posts older than (yes/no)', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'close_comments_days_old', 'info_key_label' => __('Automatically close comments on posts older than days', 'wp-admin-audit')),
            array('info_key' => 'show_comments_cookies_opt_in', 'info_key_label' => __('Show comments cookies opt-in checkbox, allowing comment author cookies to be set', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'thread_comments', 'info_key_label' => __('Enable threaded (nested) comments', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'thread_comments_depth', 'info_key_label' => __('Threaded (nested) comments depth', 'wp-admin-audit')),
            array('info_key' => 'page_comments', 'info_key_label' => __('Break comments into pages', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'comments_per_page', 'info_key_label' => __('Comments per page', 'wp-admin-audit')),
            array('info_key' => 'default_comments_page', 'info_key_label' => __('Comments default page order', 'wp-admin-audit')),
            array('info_key' => 'comment_order', 'info_key_label' => __('Comments order', 'wp-admin-audit')),
            array('info_key' => 'comments_notify', 'info_key_label' => __('Anyone posts a comment', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'moderation_notify', 'info_key_label' => __('A comment is held for moderation', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'comment_moderation', 'info_key_label' => __('Comment must be manually approved', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'comment_previously_approved', 'info_key_label' => __('Comment author must have a previously approved comment', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'comment_max_links', 'info_key_label' => __('Max links per comment', 'wp-admin-audit')),
            array('info_key' => 'moderation_keys', 'info_key_label' => __('Moderation keywords', 'wp-admin-audit')),
            array('info_key' => 'disallowed_keys', 'info_key_label' => __('Disallowed keywords', 'wp-admin-audit')),
            array('info_key' => 'show_avatars', 'info_key_label' => __('Avatar Display', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'avatar_rating', 'info_key_label' => __('Avatar Rating', 'wp-admin-audit')),
            array('info_key' => 'avatar_default', 'info_key_label' => __('Default Avatar', 'wp-admin-audit')),

            // Media Settings
            array('info_key' => 'thumbnail_size_w', 'info_key_label' => __('Thumbnail width', 'wp-admin-audit')),
            array('info_key' => 'thumbnail_size_h', 'info_key_label' => __('Thumbnail height', 'wp-admin-audit')),
            array('info_key' => 'thumbnail_crop', 'info_key_label' => __('Crop thumbnail to exact dimensions', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),
            array('info_key' => 'medium_size_w', 'info_key_label' => __('Medium size width', 'wp-admin-audit')),
            array('info_key' => 'medium_size_h', 'info_key_label' => __('Medium size height', 'wp-admin-audit')),
            array('info_key' => 'large_size_w', 'info_key_label' => __('Large size width', 'wp-admin-audit')),
            array('info_key' => 'large_size_h', 'info_key_label' => __('Large size height', 'wp-admin-audit')),
            array('info_key' => 'uploads_use_yearmonth_folders', 'info_key_label' => __('Organize my uploads into month- and year-based folders', 'wp-admin-audit'), 'callback' => array($this, 'renderBooleanValueLine')),

            // Permalink Settings
            array('info_key' => 'permalink_structure', 'info_key_label' => __('Permalink structure', 'wp-admin-audit')),
            array('info_key' => 'category_base', 'info_key_label' => __('Category base', 'wp-admin-audit')),
            array('info_key' => 'tag_base', 'info_key_label' => __('Tag base', 'wp-admin-audit')),

            // Permalink Settings
            array('info_key' => 'page_for_privacy_policy', 'info_key_label' => __('Privacy policy page', 'wp-admin-audit'))

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