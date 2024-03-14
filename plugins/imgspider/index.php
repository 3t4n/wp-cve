<?php
/*
Plugin Name: IMGspider
Plugin URI: http://wordpress.org/plugins/imgspider/
Version: 2.3.9
Description: IMGspider（图片蜘蛛）是一款用于WordPress文章图片抓取的WordPress插件，支持JPG, JPEG, PNG, GIF, BMP, TIF等常见图片爬取下载，实现一键抓取文章内容所有引用图片到本地服务器。
Author: 闪电博
Author URI: http://www.wbolt.com/
*/


if (!defined('ABSPATH')) {
    return;
}

define('IMGSPY_PATH', dirname(__FILE__));
define('IMGSPY_BASE_FILE', __FILE__);
define('IMGSPY_VERSION', '2.3.9');
define('IMGSPY_CODE', 'imgspider-pro');
define('IMGSPY_URI', plugin_dir_url(IMGSPY_BASE_FILE));


require_once IMGSPY_PATH . '/classes/conf.class.php';
require_once IMGSPY_PATH . '/classes/down.class.php';
require_once IMGSPY_PATH . '/classes/image.class.php';
require_once IMGSPY_PATH . '/classes/post.class.php';
require_once IMGSPY_PATH . '/classes/ajax.class.php';
require_once IMGSPY_PATH . '/classes/imgspy.admin.php';
IMGSPY_Admin::init();
