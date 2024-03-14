<?php

class Zwt_wp_linkpreviewer_Constants
{

    /* TEXTS */
    public static $TEXT_PLUGIN_NAME = "Beautiful Link Preview";
    public static $TEXT_PLUGIN_PREVIEW_BY = "Preview by %s";
    public static $PLUGIN_NOT_ENABLED = "<em><strong>%s Plugin is disabled!</strong><br>Please enable it inside the settings of the plugin.</em><br>%s";
    public static $PLUGIN_NOT_ENABLED_SETTINGS_LINK = "Settings";

    /* ADMIN TEXTS SETTINGS*/
    public static $ADMIN_TEXT_TAB_INTRO = "Introduction";
    public static $ADMIN_TEXT_TAB_SETTINGS = "Settings";
    public static $ADMIN_TEXT_TAB_LINKS = "Link Previews";


    public static $ADMIN_TEXT_TAB_SETTINGS_ENABLED = "Plugin Enabled";
    public static $ADMIN_TEXT_TAB_SETTINGS_WARNING_DISABLED = "Plugin is disabled - please go to %s and enable it.";

    public static $ADMIN_TEXT_TAB_SETTINGS_ENABLED_LABEL = "Enabled<br>By enabling this plugin you agree to the <a href=\"options-general.php?page=beautiful_link_preview-settings.php&tab=intro#legal\">legal terms &amp; conditions</a>.";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_LAYOUT = "Layout";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_LINK_TARGET = "Link Target";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_REL = "Rel Attribute";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_REL_LABEL = "This should not be changed, unless you know why you are changing it. You can read more about link types <a target=\"_blank\" rel=\"noopener nofollow noreferrer\" href=\"https://developer.mozilla.org/en-US/docs/Web/HTML/Link_types\">here</a>.";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_TITLE_MAX_CHARS = "Title: Max. Characters";
    public static $ADMIN_TEXT_TAB_SETTINGS_DEFAULT_DESCRIPTION_MAX_CHARS = "Description: Max. Characters";
    public static $ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK = "Display Credits Link";
    public static $ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_LABEL = "Support the development of this plugin by displaying the following credits link:<br><em>%s</em>";
    public static $ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE = "<p>If you like this plugin please support my work by donating some coffees.</p>";
    public static $ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE_BUTTON = "PayPal Donate";
    public static $ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE_PAYPAL = "https://zeitwesentech.com/go/wp-beautiful-link-preview-donate";

    public static $ADMIN_TEXT_WP_PLUGIN_DIR_URL = "https://wordpress.org/plugins/beautiful-link-preview";

    public static $ADMIN_TEXT_TAB_SETTINGS_DELETE_DATA_UNINSTALL = "Clear Data On Uninstall";
    public static $ADMIN_TEXT_TAB_SETTINGS_DELETE_DATA_UNINSTALL_LABEL = "Removes all settings and fetched data when you uninstall this plugin.";

    /* ADMIN TEXTS LINKS*/
    public static $ADMIN_TEXT_TAB_LINKS_COL_IMG = "Image";
    public static $ADMIN_TEXT_TAB_LINKS_COL_URL = "URL";
    public static $ADMIN_TEXT_TAB_LINKS_COL_TITLE = "Title";
    public static $ADMIN_TEXT_TAB_LINKS_COL_DESC = "Description";
    public static $ADMIN_TEXT_TAB_LINKS_COL_DATE = "Fetched";

    public static $ADMIN_TEXT_TAB_LINKS_ACTION_REFRESH = "Refresh";
    public static $ADMIN_TEXT_TAB_LINKS_ACTION_DELETE = "Delete";

    public static $ADMIN_TEXT_TAB_LINKS_ACTION_REFRESHED = "Refreshed preview of %s";
    public static $ADMIN_TEXT_TAB_LINKS_ACTION_DELETED = "Deleted preview of <em>%s</em>.<br><strong>Be aware that it will be refetched if you do not remove the shortcode.</strong>";


    /* BASE PLUGIN CONFIG */
    public static $PLUGIN_LINK = "https://zeitwesentech.com/go/wp-beautiful-link-preview";
    public static $SHORTCODE_NAME = 'beautiful_link_preview';

    public static $DB_TABLE_NAME = "zwt_wp_link_previewer";
    public static $OPTION_KEY_TABLE_VERSION = "zwt_wp_link_previewer_db_version";

    public static $SETTINGS_BASE = "options-general.php?page=";
    public static $SETTINGS_SLUG = "beautiful_link_preview-settings.php";

    public static $FETCH_TITLE_MAX_CHARS = 500;
    public static $FETCH_DESC_MAX_CHARS = 2000;
    public static $FETCH_IMG_FULL_SIZE = 1024;
    public static $FETCH_IMG_COMPACT_SIZE = 300;

    public static $REST_NAMESPACE = "zwt_wp_link_previewer/v1";


    /* SHORTCODE ATTS */
    public static $TAG_ATTR_URL = "url";
    public static $TAG_ATTR_LAYOUT = "layout";
    public static $TAG_ATTR_LAYOUT_FULL = "full";
    public static $TAG_ATTR_LAYOUT_COMPACT = "compact";
    public static $TAG_ATTR_TARGET = "target";
    public static $TAG_ATTR_NO_IMG = "no_img";
    public static $TAG_ATTR_NO_TITLE = "no_title";
    public static $TAG_ATTR_NO_DESC = "no_desc";
    public static $TAG_ATTR_MAX_TITLE_CHARS = "max_title_chars";
    public static $TAG_ATTR_MAX_DESC_CHARS = "max_desc_chars";

    /* SETTINGS OPTIONS */
    public static $OPTION_KEY_SETTINGS = "zwt_wp_link_previewer_settings";
    public static $KEY_MAX_TITLE_CHARS = "max_title_chars";
    public static $KEY_TARGET = "target";
    public static $KEY_MAX_DESC_CHARS = "max_desc_chars";
    public static $KEY_LAYOUT= "layout";
    public static $KEY_REL= "rel";
    public static $KEY_SHOW_PREVIEWBY = "show_previewby";
    public static $KEY_ENABLED = "enabled";
    public static $KEY_ON_UNINSTALL_DELETE_DATA = "on_uninstall_delete_data";

    /* OPTION DEFAULTS */
    public static $OPTION_DEFAULT_MAX_TITLE_CHARS = 100;
    public static $OPTION_DEFAULT_MAX_DESC_CHARS = 200;
    public static $OPTION_DEFAULT_REL = 'noopener nofollow noreferrer';
    public static $OPTION_DEFAULT_SHOW_PREVIEWBY = false;
    public static $OPTION_DEFAULT_ON_UNINSTALL_DELETE_DATA = false;

}