<?php
/**
 * Hello Bar: The Original Popup Software (Top Bars, Exit Intents, Sliders, & More to Grow Your Email List!)
 *
 * Easily add your HelloBar to your WordPress blog!
 *
 * @package Hello Bar: The Original Popup Software (Top Bars, Exit Intents, Sliders, & More to Grow Your Email List!)
 *
 * @author  hellobar
 * @version 1.1
 */
/*
Plugin Name: Hello Bar for WordPress
Plugin URI: http://www.hellobar.com/
Description: The Original Popup Software (Top Bars, Exit Intents, Sliders, & More to Grow Your Email List!)
Version: 1.1
Tested up to: 5.6
Author: hellobar
Author URI: http://www.hellobar.com
License: GPL2
Copyright 2018 Hello Bar  (email:support@hellobar.com)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class HelloBarForWordPress
{
    var $longname     =   "Hello Bar for WordPress";
    var $shortname     =   "HelloBar";
    var $namespace     =   'hellobar-for-wordpress';
    var $version     =   '1.1';
    var $defaults     =    array('hellobar_code'=>"",'load_hellobar_in'=>'footer');
    public static function init()
    {
        $class = __CLASS__;
        new $class;
    }
    function __construct()
    {
        $this->url_path = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__));
        if (isset($_SERVER['HTTPS'])) {
            if ((boolean) $_SERVER['HTTPS']===true) {
                $this->url_path = str_replace('http://', 'https://', $this->url_path);
            }
        }
        $this->option_name = '_'.$this->namespace.'--options';
        add_action('admin_menu', array(&$this,'admin_menu'));
        if (is_admin()) {
        } else {
            if ($this->get_option('load_hellobar_in') == 'header') {
                add_action('wp_head', array(&$this,'hellobar_print_script'));
                add_action('wp_head', array(&$this,'hellobar_insert_tags'));
            } else {
                if (function_exists('wp_print_footer_scripts')) {
                    add_action('wp_print_footer_scripts', array(&$this,'hellobar_print_script'));
                    add_action('wp_print_footer_scripts', array(&$this,'hellobar_insert_tags'));
                } else {
                    add_action('wp_footer', array(&$this,'hellobar_print_script'));
                    add_action('wp_footer', array(&$this,'hellobar_insert_tags'));
                }
            }
        }
        /* Loading Admin CSS only for Hellobar option page */
        if (isset($_GET['page']) && $_GET['page']=='hellobar.php') {
            add_action('admin_enqueue_scripts', array($this,'add_css_in_admin'));
            add_action('admin_enqueue_scripts', array($this,'add_js_in_admin'));
        }
    }
    public function add_css_in_admin()
    {
        wp_register_style('hellobaradmincss', $this->url_path.'/assets/css/hellobar-admin.css');
        wp_enqueue_style('hellobaradmincss');
    }
    public function add_js_in_admin()
    {
        wp_register_script('qtipjs', $this->url_path.'/assets/js/jquery.qtip.min.js', array('jquery'), '3.0.3', false);
        wp_enqueue_script('qtipjs');
    }
    public function hellobar_insert_tags()
    {
        if (is_home()) {
            return;
        }
        $posttags = get_the_tags();
        if ($posttags) {
            foreach($posttags as $tag) {
                echo '<script type="text/javascript">window._hellobar_wordpress_tags = window._hellobar_wordpress_tags || []; window._hellobar_wordpress_tags.push("'.strval($tag->name).'"); </script>'; 
            }
        }
    }
    public function hellobar_print_script()
    {
        $hellobar_code     =   $this->get_option('hellobar_code');
        $newapikey         =   get_option('hellobar_api_key', true);
        if ($newapikey) {
            echo '<script src="https://my.hellobar.com/'.$hellobar_code.'.js" type="text/javascript" charset="utf-8" async="async"></script>';
        } else {
            if (!empty($hellobar_code)) {
                $hellobar_code = html_entity_decode($hellobar_code);
                if ($this->get_option('load_hellobar_in')=='header') {
                    $output = preg_replace("/<noscript>(.*)<\/noscript>/ism", "", $hellobar_code);
                } else {
                    $output = $hellobar_code;
                }
                echo "\n".$output;
            }
        }
    }
    public function admin_menu()
    {
        add_menu_page($this->shortname, $this->shortname, 'manage_options', basename(__FILE__), array(&$this,'admin_options_page'), ($this->url_path.'/images/icon.png'));
    }
    public function admin_options_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page');
        }
        if (isset($_POST) && !empty($_POST)) {
            if (wp_verify_nonce($_REQUEST[$this->namespace.'_update_wpnonce'], $this->namespace.'_options')) {
                $data = array();
                foreach ($_POST as $key => $val) {
                    $data[$key] = $this->sanitize_data($val);
                }
                switch($data['form_action']){
                case "update_options":
                    $options = array(
                       'hellobar_code'         => (string) $data['hellobar_code'],
                       'load_hellobar_in'     => (string) @$data['load_hellobar_in']
                    );
                    update_option($this->option_name, $options);
                    $this->options = get_option($this->option_name);
                    break;
                }
            }
        }
        $page_title     =     $this->longname.' Options';
        $namespace         =    $this->namespace;
        $defaults         =    $this->defaults;
        $plugin_path     =    $this->url_path;
        foreach ($this->defaults as $name => $default_value) {
            $$name = $this->get_option($name);
        }
        include dirname(__FILE__).'/views/options.php';
    }
    private function get_option( $option_name )
    {
        // Load option values if they haven't been loaded already
        if (!isset($this->options) || empty($this->options)) {
            $this->options = get_option($this->option_name, $this->defaults);
        }
        if (isset($this->options[$option_name])) {
            return $this->options[$option_name];
        } elseif (isset($this->defaults[$option_name])) {
            return $this->defaults[$option_name];
        }
        return false;
    }
    private function sanitize_data($str="")
    {
        if (!function_exists('wp_kses')) {
            include_once ABSPATH.'wp-includes/kses.php';
        }
        global $allowedposttags;
        global $allowedprotocols;
        if (is_string($str)) {
            $str = htmlentities(stripslashes($str), ENT_QUOTES, 'UTF-8');
        }
        $str = wp_kses($str, $allowedposttags, $allowedprotocols);
        return $str;
    }
    public function is_script()
    {
        $hellobar_code = html_entity_decode($this->get_option('hellobar_code'));
        if ($hellobar_code) {
            $count = preg_match('/src=(["\'])(.*?)\1/', $hellobar_code, $match);
            if ($count === false) {
                return false;
            } else {
                if (!empty($match)) {
                    $jsurl      =   $match[2];
                    $parts      =   parse_url($jsurl);
                    $path       =   $parts['path'];
                    return HelloBarForWordPress::get_api_code($path);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function get_api_code($path)
    {
        $path1  =   str_replace('/', '', $path);
        $path2  =   str_replace('.js', '', $path1);
        return $path2;
    }
    public function reset_old_api($api)
    {
        update_option('hellobar_api_key', $api);
        $options = array(
           'hellobar_code' => $api,
           'load_hellobar_in' => 'footer'
        );
        update_option('_hellobar-for-wordpress--options', $options);
    }
}
/**
* Load Class on Plugin Load
*/
add_action('plugins_loaded', array('HelloBarForWordPress','init'));
?>
