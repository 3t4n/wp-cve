<?php
/**
 * URL From where the main editor compiled files and other assets would be served
 */
define("MS_CDN_URL", "https://wpjs.makestories.io/3.0/dashboard/");
define("MS_CDN_EDITOR_URL", "https://wpjs.makestories.io/3.0/editor/");

/**
 * Base server url
 */
define("MS_BASE_SERVER_URL", "https://server.makestories.io/");

/**
 * Base Story CDN url
 */
define("MS_STORY_CDN_URL", "https://wp-cdn.makestories.io/");

/**
 * Player JS SDK url
 */
define("MS_PLAYER_CDN_URL", "https://js.makestories.io/player/StoryPlayer.js");
/**
 * Url for viewing live preview of story being edited.
 */
define("MS_PREVIEW_URL","https://webstories.link/preview/");

/**
 * Placeholder in html to change in order to set a correct page link as canonical while publishing
 */
define("MS_WORDPRESS_CANONICAL_SUBSTITUTION_PLACEHOLDER", "MS_WORDPRESS_CANONICAL_SUBSTITUTION_PLACEHOLDER");

/**
 * Starting point of execution in the editor script s
 */
define("MS_MAIN_SCRIPT_URL", MS_CDN_EDITOR_URL."static/main.js");
define("MS_MAIN_STYLE_URL", MS_CDN_EDITOR_URL."static/css/main.css");
define("MS_VENDOR_SCRIPT_URL", MS_CDN_EDITOR_URL."static/chunks/vendors.js");
define("MS_VENDOR_STYLE_URL", MS_CDN_EDITOR_URL."static/css/vendors.css");


define("MS_DASHBOARD_MAIN_SCRIPT_URL", MS_CDN_URL."index.js");

/**
 * WP Translation text domain - not used for now. Will setup translations later on.
 */
define("MS_TEXT_DOMAIN", "MAKESTORIES");

/**
 * Icon to be shown in Wordpress Leftbar Menu
 */
define("MS_MENU_ICON_URL", "MAKESTORIES");

/**
 * Post type to publish in while working on stories.
 */
define("MS_POST_TYPE", "makestories_story");
define("MS_POST_WIDGET_TYPE", "makestories_widget");
define("MS_TAXONOMY", "ms_story_category");

/**
 * Commn action for checking genuineness of request.
 */
define("MS_NONCE_REFERRER", "ms_wp_plugin_referrer");

/**
 * Router setup for backend
 */
define("MS_ROUTING", [
    "EDITOR" => [
        "slug" => "makestories-editor",
        "icon" => "dashicons-schedule"
    ],
    "DASHBOARD" => [
        "slug" => "makestories-dashboard",
        "icon" => "dashicons-schedule"
    ],
    "Published" =>[
    	"slug"=>"published_stories_slug"
    ]
]);

define("MS_DEFAULT_OPTIONS", [
    "post_slug" => "web-stories",
    "categories_enabled" => false,
    "to_rewrite" => false,
    "site_id" => false,
    "forceUploadMedia" => false,
    "default_category" => "Uncategorized",
    "roles" => ["editor", "author", "administrator"],
    "ms_design" => "1",
    "ms_heading" => "",
    "ms_content" => "",
    "ms_v4TrackingId" => "",
]);

define("MS_CDN_LINK", "cdn.storyasset.link");

define("MS_DOMAINS", [
    "storage.googleapis.com/makestories",
    "storage.googleapis.com/cdn-storyasset-link",
    "makestories.io",
    "images.unsplash.com",
    "storyasset.link",
]);

define("MS_PLUGIN_BASE_FILE_PATH", plugin_dir_path( __DIR__ ));

define("DESIGN_OPTIONS",[
    "ms_design",
    "ms_heading",
    "ms_content",
    "ms_v4TrackingId",
]);