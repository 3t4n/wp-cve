<?php
/*
Plugin Name: Spider Analyser
Plugin URI: http://wordpress.org/plugins/spider-analyser/
Description: Spider Analyser是一款用于跟踪WordPress网站各种搜索引擎蜘蛛爬行日志，并进行详细的蜘蛛爬行数据统计、蜘蛛行为分析、蜘蛛爬取分析及伪蜘蛛拦截等。
Version: 1.4.0
Author: 闪电博
Author URI: https://www.wbolt.com/
*/

if(!defined('ABSPATH')){
    return;
}

define('WP_SPIDER_ANALYSER_PATH',__DIR__);
define('WP_SPIDER_ANALYSER_BASE_FILE',__FILE__);
define('WP_SPIDER_ANALYSER_VERSION','1.4.0');
define('WP_SPIDER_ANALYSER_URL',plugin_dir_url(__FILE__));
require_once WP_SPIDER_ANALYSER_PATH.'/admin.class.php';
require_once WP_SPIDER_ANALYSER_PATH.'/spider.class.php';

WP_Spider_Analyser::init();