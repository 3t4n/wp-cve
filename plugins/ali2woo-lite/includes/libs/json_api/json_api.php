<?php

namespace AliNext_Lite;;

if (!defined('A2WL_JSON_API_VERSION')) {
    define('A2WL_JSON_API_VERSION', "1.0.0");
}

if (!defined('A2WL_JSON_API_DIR')) {
    define('A2WL_JSON_API_DIR', dirname(__FILE__));
}

@include_once A2WL_JSON_API_DIR . "/singletons/api.php";
@include_once A2WL_JSON_API_DIR . "/singletons/query.php";
@include_once A2WL_JSON_API_DIR . "/singletons/response.php";

if (!class_exists('Json_Api_Configurator')) {
class Json_Api_Configurator {

    private function __construct() { }

    public static function init($root_menu_slug) {
        $configurator = new Json_Api_Configurator();
        $configurator->root_menu_slug = $root_menu_slug;
        
        add_action('init', array($configurator, 'json_api_init'));
        
        add_action('a2wl_install', array($configurator, 'activation'));
        add_action('a2wl_uninstall', array($configurator, 'deactivation'));
    }

    public function activation() {
        // Add the rewrite rule on activation
        global $wp_rewrite;
        add_filter('rewrite_rules_array', array(new Json_Api_Configurator(),'json_api_rewrites'));
        $wp_rewrite->flush_rules();
    }

    public function deactivation() {
        // Remove the rewrite rule on deactivation
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function json_api_init() {

        global $a2wl_json_api;
        if (phpversion() < 5) {
            add_action('admin_notices', array($this, 'json_api_php_version_warning'));
            return;
        }
        if (!class_exists('AliNext_Lite\JSON_API')) {
            add_action('admin_notices', array($this, 'json_api_class_warning'));
            return;
        }
        
        add_filter('rewrite_rules_array', array($this, 'json_api_rewrites'));
        
        $a2wl_json_api = new JSON_API(empty($this->root_menu_slug)?'':$this->root_menu_slug);
    }

    function json_api_rewrites($wp_rules) {
        $base = get_setting('json_api_base');
        if (empty($base)) {
            return $wp_rules;
        }
        $json_api_rules = array(
            "$base\$" => 'index.php?a2w-json=info',
            "$base/(.+)\$" => 'index.php?a2w-json=$matches[1]'
        );
        return array_merge($json_api_rules, $wp_rules);
    }

    function json_api_php_version_warning() {
        echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Sorry, JSON API requires PHP version 5.0 or greater.</p></div>";
    }

    function json_api_class_warning() {
        echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Oops, JSON_API class not found. If you've defined a A2WL_JSON_API_DIR constant, double check that the path is correct.</p></div>";
    }

}
}