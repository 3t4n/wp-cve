<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_SensorsDB
{
    /**
     * @return array
     */
    public static function getSensors(){
        $sensors = array();
        $sensors[WADA_Sensor_Base::EVT_USER_REGISTRATION] = ['id' => WADA_Sensor_Base::EVT_USER_REGISTRATION,
            'severity' => 2,
            'active' => 1,
            'name' => __('Registration', 'wp-admin-audit'),
            'description' => __('Records when an account is created by a user registration.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_LOGIN] = ['id' => WADA_Sensor_Base::EVT_USER_LOGIN,
            'severity' => 1,
            'active' => 1,
            'name' => __('Login', 'wp-admin-audit'),
            'description' => __('Records when a user or admin logs into your WordPress site.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_LOGOUT] = ['id' => WADA_Sensor_Base::EVT_USER_LOGOUT,
            'severity' => 1,
            'active' => 1,
            'name' => __('Logout', 'wp-admin-audit'),
            'description' => __('Records when a user or admin logs out of your WordPress site.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_UPDATE] = ['id' => WADA_Sensor_Base::EVT_USER_UPDATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('User update', 'wp-admin-audit'),
            'description' => __('Records when a user profile was edited.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_DELETE] = ['id' => WADA_Sensor_Base::EVT_USER_DELETE,
            'severity' => 3,
            'active' => 1,
            'name' => __('User deletion', 'wp-admin-audit'),
            'description' => __('Records when a user account was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_CREATE] = ['id' => WADA_Sensor_Base::EVT_POST_CREATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Post creation', 'wp-admin-audit'),
            'description' => __('Records when a post was created.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_UPDATE] = ['id' => WADA_Sensor_Base::EVT_POST_UPDATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Post update', 'wp-admin-audit'),
            'description' => __('Records when a post was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_DELETE] = ['id' => WADA_Sensor_Base::EVT_POST_DELETE,
            'severity' => 3,
            'active' => 1,
            'name' => __('Post deletion', 'wp-admin-audit'),
            'description' => __('Records when a post was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLG_WADA_SENSOR_UPDATE] = ['id' => WADA_Sensor_Base::EVT_PLG_WADA_SENSOR_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Sensor update (WP Admin Audit)', 'wp-admin-audit'),
            'description' => __('Records when a WP Admin Audit sensor was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLG_WADA,
            'event_category' => WADA_Sensor_Base::CAT_PLUGIN
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_LOGIN_FAILED] = ['id' => WADA_Sensor_Base::EVT_USER_LOGIN_FAILED,
            'severity' => 3,
            'active' => 1,
            'name' => __('Login failed', 'wp-admin-audit'),
            'description' => __('Records when a user or admin fails to login into the site (due to incorrect credentials).', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_USER_PASSWORD_RESET] = ['id' => WADA_Sensor_Base::EVT_USER_PASSWORD_RESET,
            'severity' => 3,
            'active' => 1,
            'name' => __('Password reset', 'wp-admin-audit'),
            'description' => __('Records when a password reset is done.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_USER,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_TRASHED] = ['id' => WADA_Sensor_Base::EVT_POST_TRASHED,
            'severity' => 3,
            'active' => 1,
            'name' => __('Post trashed', 'wp-admin-audit'),
            'description' => __('Records when a post was moved into trash.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_PUBLISHED] = ['id' => WADA_Sensor_Base::EVT_POST_PUBLISHED,
            'severity' => 2,
            'active' => 1,
            'name' => __('Post published', 'wp-admin-audit'),
            'description' => __('Records when a post was published.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_UNPUBLISHED] = ['id' => WADA_Sensor_Base::EVT_POST_UNPUBLISHED,
            'severity' => 2,
            'active' => 1,
            'name' => __('Post unpublished', 'wp-admin-audit'),
            'description' => __('Records when a post was unpublished (moved out of the published status).', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLG_WADA_SETTINGS_UPDATE] = ['id' => WADA_Sensor_Base::EVT_PLG_WADA_SETTINGS_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Settings update (WP Admin Audit)', 'wp-admin-audit'),
            'description' => __('Records when the WP Admin Audit settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLG_WADA,
            'event_category' => WADA_Sensor_Base::CAT_PLUGIN
        ];
        /* No setup of non-core IDs:
            const EVT_PLG_PSEUDO = 16;
            const EVT_PLG_WC_PRODUCT_CREATE = 17;
            const EVT_PLG_WC_PRODUCT_UPDATE = 18;
            const EVT_PLG_WC_PRODUCT_PUBLISHED = 19;
            const EVT_PLG_WC_PRODUCT_UNPUBLISHED = 20;
            const EVT_PLG_WC_PRODUCT_TRASHED = 21;
            const EVT_PLG_WC_PRODUCT_DELETED = 22;
        */
        $sensors[WADA_Sensor_Base::EVT_PLUGIN_INSTALL] = ['id' => WADA_Sensor_Base::EVT_PLUGIN_INSTALL,
            'severity' => 4,
            'active' => 1,
            'name' => __('Plugin installation', 'wp-admin-audit'),
            'description' => __('Records when a new plugin was installed.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLUGIN,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLUGIN_DELETE] = ['id' => WADA_Sensor_Base::EVT_PLUGIN_DELETE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Plugin deletion', 'wp-admin-audit'),
            'description' => __('Records when a plugin was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLUGIN,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLUGIN_ACTIVATE] = ['id' => WADA_Sensor_Base::EVT_PLUGIN_ACTIVATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Plugin activation', 'wp-admin-audit'),
            'description' => __('Records when a plugin was activated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLUGIN,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLUGIN_DEACTIVATE] = ['id' => WADA_Sensor_Base::EVT_PLUGIN_DEACTIVATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Plugin deactivation', 'wp-admin-audit'),
            'description' => __('Records when a plugin was deactivated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLUGIN,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLUGIN_UPDATE] = ['id' => WADA_Sensor_Base::EVT_PLUGIN_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Plugin update', 'wp-admin-audit'),
            'description' => __('Records when a plugin was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLUGIN,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_THEME_INSTALL] = ['id' => WADA_Sensor_Base::EVT_THEME_INSTALL,
            'severity' => 4,
            'active' => 1,
            'name' => __('Theme installation', 'wp-admin-audit'),
            'description' => __('Records when a theme was installed.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_THEME,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_THEME_DELETE] = ['id' => WADA_Sensor_Base::EVT_THEME_DELETE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Theme deletion', 'wp-admin-audit'),
            'description' => __('Records when a theme was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_THEME,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_THEME_SWITCH] = ['id' => WADA_Sensor_Base::EVT_THEME_SWITCH,
            'severity' => 4,
            'active' => 1,
            'name' => __('Theme switch', 'wp-admin-audit'),
            'description' => __('Records when the theme was switched.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_THEME,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_THEME_UPDATE] = ['id' => WADA_Sensor_Base::EVT_THEME_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Theme update', 'wp-admin-audit'),
            'description' => __('Records when a theme was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_THEME,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_CORE_UPDATE] = ['id' => WADA_Sensor_Base::EVT_CORE_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Core update', 'wp-admin-audit'),
            'description' => __('Records when WordPress was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_CORE,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MEDIA_CREATE] = ['id' => WADA_Sensor_Base::EVT_MEDIA_CREATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Media creation', 'wp-admin-audit'),
            'description' => __('Records when a new media item was created.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_MEDIA,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MEDIA_DELETE] = ['id' => WADA_Sensor_Base::EVT_MEDIA_DELETE,
            'severity' => 3,
            'active' => 1,
            'name' => __('Media deletion', 'wp-admin-audit'),
            'description' => __('Records when a media item was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_MEDIA,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MEDIA_UPDATE] = ['id' => WADA_Sensor_Base::EVT_MEDIA_UPDATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Media update', 'wp-admin-audit'),
            'description' => __('Records when a media item was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_MEDIA,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_GENERAL_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_GENERAL_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('General settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress general settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_WRITING_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_WRITING_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Writing settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress writing settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_READING_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_READING_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Reading settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress reading settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_DISCUSSION_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_DISCUSSION_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Discussion settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress discussion settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_MEDIA_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_MEDIA_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Media settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress media settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_PERMALINK_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_PERMALINK_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Permalink settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress permalink settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_SETTING_PRIVACY_UPDATE] = ['id' => WADA_Sensor_Base::EVT_SETTING_PRIVACY_UPDATE,
            'severity' => 4,
            'active' => 1,
            'name' => __('Privacy settings update', 'wp-admin-audit'),
            'description' => __('Records when the WordPress privacy settings were updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_SETTING,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE] = ['id' => WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Notification creation (WP Admin Audit)', 'wp-admin-audit'),
            'description' => __('Records when a WP Admin Audit notification was created.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLG_WADA,
            'event_category' => WADA_Sensor_Base::CAT_PLUGIN
        ];
        $sensors[WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE] = ['id' => WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE,
            'severity' => 3,
            'active' => 1,
            'name' => __('Notification update (WP Admin Audit)', 'wp-admin-audit'),
            'description' => __('Records when a WP Admin Audit notification was updated.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLG_WADA,
            'event_category' => WADA_Sensor_Base::CAT_PLUGIN
        ];
        $sensors[WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE] = ['id' => WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE,
            'severity' => 3,
            'active' => 1,
            'name' => __('Notification deletion (WP Admin Audit)', 'wp-admin-audit'),
            'description' => __('Records when a WP Admin Audit notification was deleted.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_PLG_WADA,
            'event_category' => WADA_Sensor_Base::CAT_PLUGIN
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE] = ['id' => WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Post category update', 'wp-admin-audit'),
            'description' => __("Records when a post's category assignment(s) were updated.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE] = ['id' => WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Post tag update', 'wp-admin-audit'),
            'description' => __("Records when a post's tag assignment(s) were updated.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_POST,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_CATEGORY_CREATE] = ['id' => WADA_Sensor_Base::EVT_CATEGORY_CREATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Category creation', 'wp-admin-audit'),
            'description' => __("Records when a category was created.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_CATEGORY_UPDATE] = ['id' => WADA_Sensor_Base::EVT_CATEGORY_UPDATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Category update', 'wp-admin-audit'),
            'description' => __("Records when a category was updated.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_CATEGORY_DELETE] = ['id' => WADA_Sensor_Base::EVT_CATEGORY_DELETE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Category deletion', 'wp-admin-audit'),
            'description' => __("Records when a category was deleted.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_TAG_CREATE] = ['id' => WADA_Sensor_Base::EVT_POST_TAG_CREATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Tag creation', 'wp-admin-audit'),
            'description' => __("Records when a tag was created.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_TAG_UPDATE] = ['id' => WADA_Sensor_Base::EVT_POST_TAG_UPDATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Tag update', 'wp-admin-audit'),
            'description' => __("Records when a tag was updated.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_POST_TAG_DELETE] = ['id' => WADA_Sensor_Base::EVT_POST_TAG_DELETE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Tag deletion', 'wp-admin-audit'),
            'description' => __("Records when a tag was deleted.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_TAXONOMY,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_CREATE] = ['id' => WADA_Sensor_Base::EVT_COMMENT_CREATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment creation', 'wp-admin-audit'),
            'description' => __("Records when a comment was created.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_UPDATE] = ['id' => WADA_Sensor_Base::EVT_COMMENT_UPDATE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment update', 'wp-admin-audit'),
            'description' => __("Records when a comment was updated.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_DELETE] = ['id' => WADA_Sensor_Base::EVT_COMMENT_DELETE,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment deletion', 'wp-admin-audit'),
            'description' => __("Records when a comment was deleted.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_TRASHED] = ['id' => WADA_Sensor_Base::EVT_COMMENT_TRASHED,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment trashed', 'wp-admin-audit'),
            'description' => __("Records when a comment was trashed.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_UNTRASHED] = ['id' => WADA_Sensor_Base::EVT_COMMENT_UNTRASHED,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment restored', 'wp-admin-audit'),
            'description' => __("Records when a comment was restored from the trash.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_APPROVED] = ['id' => WADA_Sensor_Base::EVT_COMMENT_APPROVED,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment approved', 'wp-admin-audit'),
            'description' => __("Records when a comment was approved.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_UNAPPROVED] = ['id' => WADA_Sensor_Base::EVT_COMMENT_UNAPPROVED,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment unapproved', 'wp-admin-audit'),
            'description' => __("Records when a comment was unapproved.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_COMMENT_SPAMMED] = ['id' => WADA_Sensor_Base::EVT_COMMENT_SPAMMED,
            'severity' => 1,
            'active' => 1,
            'name' => __('Comment spammed', 'wp-admin-audit'),
            'description' => __("Records when a comment was marked as spam.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_COMMENT,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MENU_CREATE] = ['id' => WADA_Sensor_Base::EVT_MENU_CREATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Menu creation', 'wp-admin-audit'),
            'description' => __("Records when a menu was created.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_MENU,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MENU_UPDATE] = ['id' => WADA_Sensor_Base::EVT_MENU_UPDATE,
            'severity' => 2,
            'active' => 1,
            'name' => __('Menu update', 'wp-admin-audit'),
            'description' => __("Records when a menu was updated. This includes changing menu attributes (like the name and slug) as well as adding and removing items from the menu.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_MENU,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_MENU_DELETE] = ['id' => WADA_Sensor_Base::EVT_MENU_DELETE,
            'severity' => 3,
            'active' => 1,
            'name' => __('Menu deletion', 'wp-admin-audit'),
            'description' => __("Records when a menu was deleted.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_MENU,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_OPTION_CREATE] = ['id' => WADA_Sensor_Base::EVT_OPTION_CREATE,
            'severity' => 1,
            'active' => 0, // options are "high-traffic" without white/blacklisting
            'name' => __('Option creation', 'wp-admin-audit'),
            'description' => __("Records when an option was created. Note that on most WordPress systems options get often managed/updated through background processes. Hence having this sensor enabled might be producing hundreds of events in a given hour, even if hardly any user or admin is present.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_OPTION,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_OPTION_UPDATE_CORE] = ['id' => WADA_Sensor_Base::EVT_OPTION_UPDATE_CORE,
            'severity' => 1,
            'active' => 0, // options are "high-traffic" without white/blacklisting
            'name' => __('Option update (WP Core)', 'wp-admin-audit'),
            'description' => __("Records when a WordPress core option was updated. Note that on most WordPress systems options get often managed/updated through background processes. Hence having this sensor enabled might be producing hundreds of events in a given hour, even if hardly any user or admin is present.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_OPTION,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_OPTION_UPDATE_OTHER] = ['id' => WADA_Sensor_Base::EVT_OPTION_UPDATE_OTHER,
            'severity' => 1,
            'active' => 0, // options are "high-traffic" without white/blacklisting
            'name' => __('Option update (Non-WP Core)', 'wp-admin-audit'),
            'description' => __("Records when an (non-WordPress core) option was updated. Note that on most WordPress systems options get often managed/updated through background processes. Hence having this sensor enabled might be producing hundreds of events in a given hour, even if hardly any user or admin is present.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_OPTION,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_OPTION_DELETE] = ['id' => WADA_Sensor_Base::EVT_OPTION_DELETE,
            'severity' => 1,
            'active' => 0, // options are "high-traffic" without white/blacklisting
            'name' => __('Option deletion', 'wp-admin-audit'),
            'description' => __("Records when an option was deleted. Note that on most WordPress systems options get often managed/updated through background processes. Hence having this sensor enabled might be producing hundreds of events in a given hour, even if hardly any user or admin is present.", "wp-admin-audit"),
            'event_group' => WADA_Sensor_Base::GRP_OPTION,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT] = ['id' => WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT,
            'severity' => 3,
            'active' => 1,
            'name' => __('Theme file edit', 'wp-admin-audit'),
            'description' => __('Records when a file of a theme was updated via the theme editor.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_FILE,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];
        $sensors[WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT] = ['id' => WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT,
            'severity' => 3,
            'active' => 1,
            'name' => __('Plugin file edit', 'wp-admin-audit'),
            'description' => __('Records when a file of a plugin was updated via the plugin editor.', 'wp-admin-audit'),
            'event_group' => WADA_Sensor_Base::GRP_FILE,
            'event_category' => WADA_Sensor_Base::CAT_CORE
        ];

        return $sensors;
    }
}