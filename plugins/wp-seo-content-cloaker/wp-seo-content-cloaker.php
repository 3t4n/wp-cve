<?php
/*
Plugin Name:  Wordpress SEO Content Cloaker
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Generate new shortcodes that use RDNS to obfuscate content for GoogleBot and User-Agent Method to obfuscate content for SEO Crawlers
Version:      20190415
Author:       Nicolas TRIMARDEAU
Author URI:   https://www.trimardeau.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

// Inclusion des utilisateurs
include('tools/WPSeoContentCloackerTool.php');

// Inclusion des shortcodes
include('shortcodes/google_bot_hide.php');
include('shortcodes/google_bot_show.php');
include('shortcodes/seo_crawler_hide.php');
include('shortcodes/seo_crawler_show.php');