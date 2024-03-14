<?php

/**
 * @author Tomas Vorobjov
 * @version 1.05
 * @date 28 May 2010
 *
 * @file WordpressConnectConstants.php
 *
 * Provides constant values for Wordpress Connect plugin
 */

/**
 * The version of this Wordpress Connect file
 */
define( 'WPC_VERSION', '2.0.3' );

define( 'WPC_TEXT_DOMAIN', 'wordpress-connect-text-domain' );

/* -------------------- ADMIN PANEL START -------------------- */
define( 'WPC_SETTINGS_PAGE', 'wordpress_connect_settings_page' );
define( 'WPC_SETTINGS_COMMENTS_PAGE', 'wordpress_connect_comments_settings_page' );
define( 'WPC_SETTINGS_LIKE_BUTTON_PAGE', 'wordpress_connect_like_button_settings_page' );
define( 'WPC_SETTINGS_LIKE_BOX_PAGE', 'wordpress_connect_like_box_settings_page' );

define( 'WPC_SETTINGS_SECTION_GENERAL', 'wordpress_connect_settings_section_general' );
define( 'WPC_SETTINGS_SECTION_COMMENTS', 'wordpress_connect_settings_section_comments' );
define( 'WPC_SETTINGS_SECTION_COMMENTS_DEFAULTS', 'wordpress_connect_settings_section_comments_default' );
define( 'WPC_SETTINGS_SECTION_COMMENTS_ENABLED', 'wordpress_connect_settings_section_comments_enabled' );
define( 'WPC_SETTINGS_SECTION_COMMENTS_DISPLAY', 'wordpress_connect_settings_section_comments_display' );
define( 'WPC_SETTINGS_SECTION_LIKE_BOX', 'wordpress_connect_settings_section_like_box' );
define( 'WPC_SETTINGS_SECTION_LIKE_BUTTON', 'wordpress_connect_settings_section_like_button' );
define( 'WPC_SETTINGS_SECTION_LIKE_BUTTON_DEFAULTS', 'wordpress_connect_settings_section_like_button_default' );
define( 'WPC_SETTINGS_SECTION_LIKE_BUTTON_ENABLED', 'wordpress_connect_settings_section_like_button_enabled' );
define( 'WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY', 'wordpress_connect_settings_section_like_button_display' );
/* -------------------- ADMIN PANEL END -------------------- */

/* -------------------- OPTIONS START -------------------- */
define( 'WPC_OPTIONS', 'WORDPRESS_CONNECT' );

define( 'WPC_OPTIONS_LANGUAGE', WPC_OPTIONS . '_LANGUAGE' );
define( 'WPC_OPTIONS_APP_ID', WPC_OPTIONS . '_APP_ID' );
define( 'WPC_OPTIONS_APP_ADMINS', WPC_OPTIONS . '_APP_ADMINS' );
define( 'WPC_OPTIONS_IMAGE_URL', WPC_OPTIONS . '_IMAGE_URL' );
define( 'WPC_OPTIONS_DESCRIPTION', WPC_OPTIONS . '_DESCRIPTION' );
define( 'WPC_OPTIONS_THEME', WPC_OPTIONS . '_THEME' );

define( 'WPC_OPTIONS_COMMENTS', 'WORDPRESS_CONNECT' . '_COMMENTS' );
define( 'WPC_OPTIONS_COMMENTS_NUMBER', WPC_OPTIONS_COMMENTS . '_NUMBER' );
define( 'WPC_OPTIONS_COMMENTS_WIDTH', WPC_OPTIONS_COMMENTS . '_WIDTH' );
define( 'WPC_OPTIONS_COMMENTS_POSITION', WPC_OPTIONS_COMMENTS . '_POSITION' );
define( 'WPC_OPTIONS_COMMENTS_ENABLED', WPC_OPTIONS_COMMENTS . '_ENABLED' );


define( 'WPC_OPTIONS_LIKE_BOX', 'WORDPRESS_CONNECT' . '_LIKE_BOX' );
define( 'WPC_OPTIONS_LIKE_BOX_URL', WPC_OPTIONS_LIKE_BOX . '_URL' );

define( 'WPC_OPTIONS_LIKE_BUTTON', 'WORDPRESS_CONNECT' . '_LIKE_BUTTON' );
define( 'WPC_OPTIONS_LIKE_BUTTON_SEND', WPC_OPTIONS_LIKE_BUTTON . '_SEND' );
define( 'WPC_OPTIONS_LIKE_BUTTON_LAYOUT', WPC_OPTIONS_LIKE_BUTTON . '_LAYOUT' );
define( 'WPC_OPTIONS_LIKE_BUTTON_WIDTH', WPC_OPTIONS_LIKE_BUTTON . '_WIDTH' );
define( 'WPC_OPTIONS_LIKE_BUTTON_FACES', WPC_OPTIONS_LIKE_BUTTON . '_FACES' );
define( 'WPC_OPTIONS_LIKE_BUTTON_VERB', WPC_OPTIONS_LIKE_BUTTON . '_VERB' );
define( 'WPC_OPTIONS_LIKE_BUTTON_FONT', WPC_OPTIONS_LIKE_BUTTON . '_FONT' );

define( 'WPC_OPTIONS_LIKE_BUTTON_ENABLED', WPC_OPTIONS_LIKE_BUTTON . '_ENABLED' );
define( 'WPC_OPTIONS_LIKE_BUTTON_POSITION', WPC_OPTIONS_LIKE_BUTTON . '_POSITION' );
define( 'WPC_OPTIONS_LIKE_BUTTON_REF', WPC_OPTIONS_LIKE_BUTTON . '_REF' );

define( 'WPC_OPTIONS_DISPLAY', WPC_OPTIONS_COMMENTS . '_DISPLAY' );
define( 'WPC_OPTIONS_DISPLAY_EVERYWHERE', WPC_OPTIONS_DISPLAY . '_EVERYWHERE' );
define( 'WPC_OPTIONS_DISPLAY_HOMEPAGE', WPC_OPTIONS_DISPLAY . '_HOMEPAGE' );
define( 'WPC_OPTIONS_DISPLAY_POSTS', WPC_OPTIONS_DISPLAY . '_POSTS' );
define( 'WPC_OPTIONS_DISPLAY_PAGES', WPC_OPTIONS_DISPLAY . '_PAGES' );
define( 'WPC_OPTIONS_DISPLAY_CATEGORIES', WPC_OPTIONS_DISPLAY . '_CATEGORIES' );
define( 'WPC_OPTIONS_DISPLAY_TAGS', WPC_OPTIONS_DISPLAY . '_TAGS' );
define( 'WPC_OPTIONS_DISPLAY_SEARCH', WPC_OPTIONS_DISPLAY . '_SEARCH' );
define( 'WPC_OPTIONS_DISPLAY_ARCHIVE', WPC_OPTIONS_DISPLAY . '_ARCHIVE' );
define( 'WPC_OPTIONS_DISPLAY_NOWHERE', WPC_OPTIONS_DISPLAY . '_NOWHERE' );

/* --------------------- OPTIONS END --------------------- */

/* ------------------ OPTION VALUES START ---------------- */
define( 'WPC_LAYOUT_STANDARD', 'standard' );
define( 'WPC_LAYOUT_BUTTON_COUNT', 'button_count' );
define( 'WPC_LAYOUT_BOX_COUNT', 'box_count' );

define( 'WPC_OPTION_DISABLED', 'disabled' );
define( 'WPC_OPTION_ENABLED', 'enabled' );

define( 'WPC_ACTION_LIKE', 'like' );
define( 'WPC_ACTION_RECOMMEND', 'recommend' );

define( 'WPC_FONT_ARIAL', 'arial' );
define( 'WPC_FONT_LUCIDA_GRANDE', 'lucida grande' );
define( 'WPC_FONT_SEGOE_UI', 'segoe ui' );
define( 'WPC_FONT_TAHOMA', 'tahoma' );
define( 'WPC_FONT_TREBUCHET_MS', 'trebuchet ms' );
define( 'WPC_FONT_VERDANA', 'verdana' );
define( 'WPC_FONT_DEFAULT', WPC_FONT_ARIAL );

define( 'WPC_THEME_LIGHT', 'light' );
define( 'WPC_THEME_DARK', 'dark' );
/* ------------------- OPTION VALUES END ----------------- */

/* --------------------- META DATA START ----------------- */
define( 'WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_ENABLE', 'like_button_enable' );
define( 'WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_POSITION', 'like_button_position' );
define( 'WPC_CUSTOM_FIELD_NAME_COMMENTS_ENABLE', 'comments_enabled' );
define( 'WPC_CUSTOM_FIELD_NAME_COMMENTS_POSITION', 'comments_position' );

define( 'WPC_CUSTOM_FIELD_VALUE_POSITION_TOP', 'position_top' );
define( 'WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM', 'position_bottom' );
define( 'WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM', 'position_custom' );
/* --------------------- META DATA END ------------------- */

/* --------------------- WIDGET START -------------------- */
/* --------------------- WIDGET END ---------------------- */