<?php
/*
Plugin Name: UsageDD
Plugin URI: https://forum.dion-designs.com/f35/usagedd-support/
Version: 2.1
Author: Dion Designs
Description: Displays usage information to administrators
*/

if (!defined('WPINC')) {
	die('ERROR 000');
}

/*--------------------------------------
	UsageDD
	Copyright © 2023 by Dion Designs.
--------------------------------------*/

/*
	You can change the CSS for the usage display.
	DO NOT USE SINGLE-QUOTE ' CHARACTERS IN YOUR CSS!
	You have been warned.
*/

// CSS Starts after the next line
define('USAGEDD_CSS', '

.usage-dd {
	display: block;
	line-height: 24px;
	background-color: #c4c4c4;
	color: #000;
	font-family: Helvetica,Arial,sans-serif;
	font-size: 18px;
	text-align: center;
}
.usagedd-bs {
	margin: 0 45px;
}
.usagedd-ss {
	margin: 0 4px;
	border-left: 5px solid #999;
}
#usage_dd {
	position: fixed;
	left: 50%;
	bottom: 0;
	z-index: 9999998;
	margin-left: -160px;
	width: 320px;
	height: 24px;
	white-space: nowrap;
	overflow: hidden;
	background-color: rgba(160,160,160,0.5);
}

'); // CSS ends at the previous line. DO NOT CHANGE THIS LINE!

/*
	If you change the following line from false to true, you will see a
	usage line where a call to admin-ajax.php was made. Add up the
	execution times and queries on all the usage lines, and you will
	see why using admin-ajax.php should be avoided at all costs!
*/
define('USAGEDD_AJAX_USAGE', false);

// DO NOT EDIT ANYTHING BEYOND THIS POINT!!!

class UsageDD { private $display = true; private $servertime, $assetdd, $css, $ajax, $db; private $precisn = 1; function __construct($css, $ajax) { global $wpdb; $this->css = $css; $this->ajax = $ajax; $this->db = $wpdb; $this->assetdd = class_exists('AssetDD'); add_action('init', array($this, 'hook_setup'), 9999); add_filter('wp_xmlrpc_server_class', array($this, 'no_usage_display')); add_filter('rest_jsonp_enabled', array($this, 'no_usage_display')); } function hook_setup() { if (!defined('NO_USAGEDD_DISPLAY') && current_user_can('update_core')) { if (defined('WP_ADMIN')) { add_action('admin_init', array($this, 'time_to_first_byte'), 9999); } else { add_action('wp_loaded', array($this, 'time_to_first_byte'), 9999); } if ($this->assetdd) { AssetDD::$css .= $this->css; } else { add_action('wp_head', array($this, 'add_css'), 9999); add_action('admin_head', array($this, 'add_css'), 9999); } } } function no_usage_display($val = false) { $this->display = false; return $val; } function time_to_first_byte() { global $timestart; if (!empty($_SERVER['REQUEST_TIME_FLOAT'])) { $timestart = $_SERVER['REQUEST_TIME_FLOAT']; } if (is_admin()) { global $pagenow; if ($pagenow == 'customize.php') { add_action('customize_controls_print_footer_scripts', array($this, 'setup_usage'), 9999); } else { add_action('admin_footer', array($this, 'setup_usage'), 9999); } $xcss = '.wrap .theme-overlay .theme-wrap,.wrap .theme-overlay .theme-backdrop,.wrap .wp-full-overlay-sidebar-content,#customize-preview iframe{bottom:24px}#customize-preview iframe{height:calc(100% - 24px)}'; if ($this->assetdd) { AssetDD::$css .= $xcss; } else { $this->css .= $xcss; } } else { add_action('wp_footer', array($this, 'setup_usage'), 9999); } $ttfb = microtime(true) - $timestart; if ($ttfb < 0.1) { $this->precisn = 3; } else if ($ttfb < 10.0) { $this->precisn = 2; } $this->servertime = strval(round($ttfb, $this->precisn)) . '<span class="usagedd-ss"></span>'; } function add_css() { echo '<style id="UsageDD" type="text/css">' . str_replace(array("\r","\n","\t"), '', $this->css) . "</style>\n"; } function setup_usage() { if ($this->assetdd) { add_action('assetdd_footer', array($this, 'display_usage'), 1); } else { remove_action('shutdown', 'wp_ob_end_flush_all', 1); add_action('shutdown', array($this, 'footer_output'), 1); ob_start(); } } function footer_output() { $html = str_ireplace('</BODY>', '</body>', strval(@ob_get_clean())); if (strpos($html, '</body>') !== false) { list($front, $html) = explode('</body>', $html); echo $front; $this->display_usage(); echo "\n</body>\n{$html}"; } else { echo $html; } wp_ob_end_flush_all(); } function display_usage() { global $timestart; if ($this->display && !defined('WP_INSTALLING') && (!defined('DOING_AJAX') || (defined('DOING_AJAX') && $this->ajax))) { $precision = 0; $memory_usage = memory_get_peak_usage() / 1048576; if ($memory_usage < 10) { $precision = 2; } else if ($memory_usage < 100) { $precision = 1; } $memory_usage = round($memory_usage, $precision); $time_usage = $this->servertime . round(microtime(true) - $timestart, $this->precisn); echo ((defined('DOING_AJAX')) ? '' : '<div id="usage_dd_spacer"></div>') . '<div class="usage-dd"' . ((defined('DOING_AJAX') && $this->ajax) ? '>' : ' id="usage_dd">') . "{$this->db->num_queries}Q<span class=\"usagedd-bs\">{$time_usage}</span>{$memory_usage}M</div>"; } } } add_action('plugins_loaded', function(){if(!defined('TOOLKITDD')){new UsageDD(USAGEDD_CSS, USAGEDD_AJAX_USAGE);}}, 1); register_activation_hook(__FILE__, function(){if(defined('TOOLKITDD')){die('Please enable UsageDD PRO in ToolkitDD.');}});