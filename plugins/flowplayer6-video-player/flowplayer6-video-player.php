<?php
/*
Plugin Name: Flowplayer Video Player
Version: 1.0.5
Plugin URI: https://wphowto.net/flowplayer-6-video-player-for-wordpress-813
Author: naa986
Author URI: https://wphowto.net/
Description: Easily embed a video in WordPress using Flowplayer
Text Domain: flowplayer6-video-player
Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('FLOWPLAYER6_VIDEO_PLAYER')) {

    class FLOWPLAYER6_VIDEO_PLAYER {

        var $plugin_version = '1.0.5';
        var $flowplayer_version = '7.0.4';

        function __construct() {
            define('FLOWPLAYER6_VIDEO_PLAYER_VERSION', $this->plugin_version);
            $this->plugin_includes();
        }

        function plugin_includes() {
            if(is_admin( ) )
            {
                add_filter('plugin_action_links', array($this,'add_plugin_action_links'), 10, 2 );
            }
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('admin_menu', array($this, 'add_options_menu' ));
            add_action('wp_enqueue_scripts', 'flowplayer6_enqueue_scripts');
            add_action('wp_head', 'flowplayer6_video_player_header');
            add_shortcode('flowplayer', 'flowplayer6_video_shortcode');
            //allows shortcode execution in the widget, excerpt and content
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode', 11);
            add_filter('the_content', 'do_shortcode', 11);
        }
        
        function add_plugin_action_links($links, $file)
        {
            if ( $file == plugin_basename( dirname( __FILE__ ) . '/flowplayer6-video-player.php' ) )
            {
                $links[] = '<a href="options-general.php?page=flowplayer6-video-player-settings">'.__('Settings', 'flowplayer6-video-player').'</a>';
            }
            return $links;
        }
        
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('flowplayer6-video-player', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }
        
        function add_options_menu()
        {
            if(is_admin())
            {
                add_options_page(__('Flowplayer', 'flowplayer6-video-player'), __('Flowplayer', 'flowplayer6-video-player'), 'manage_options', 'flowplayer6-video-player-settings', array(&$this, 'display_options_page'));
            }
        }
        
        function display_options_page()
        {           
            $url = "https://wphowto.net/flowplayer-6-video-player-for-wordpress-813";
            $link_text = sprintf(wp_kses(__('Please visit the <a target="_blank" href="%s">Flowplayer Video Player</a> documentation page for usage instructions.', 'flowplayer6-video-player'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));          
            echo '<div class="wrap">';               
            echo '<h2>Flowplayer Video Player - v'.$this->plugin_version.'</h2>';
            echo '<div class="update-nag">'.$link_text.'</div>';
            echo '</div>';   
        }
    }

    $GLOBALS['flowplayer6_video_player'] = new FLOWPLAYER6_VIDEO_PLAYER();
}

function flowplayer6_enqueue_scripts() {
    if (!is_admin()) {
        $plugin_url = plugins_url('', __FILE__);
        wp_enqueue_script('jquery');
        wp_register_script('flowplayer-js', $plugin_url . '/lib/flowplayer.min.js');
        wp_enqueue_script('flowplayer-js');
        wp_register_style('flowplayer-css', $plugin_url . '/lib/skin/skin.css');
        wp_enqueue_style('flowplayer-css');
    }
}

function flowplayer6_video_player_header() {
    if (!is_admin()) {
        $fp_config = '<!-- This content is generated with the Flowplayer Video Player plugin -->';
        $fp_config .= '<script>';
        $fp_config .= 'flowplayer.conf.embed = false;';
        $fp_config .= 'flowplayer.conf.keyboard = false;';
        $fp_config .= '</script>';
        $fp_config .= '<!-- Flowplayer Video Player plugin -->';
        echo $fp_config;
    }
}

function flowplayer6_video_shortcode($atts) {
    $atts = shortcode_atts(array(
        'src' => '',
        'webm' => '',
        'width' => '',
        'height' => '',
        'ratio' => '0.417',
        'autoplay' => 'false',
        'poster' => '',
        'loop' => '',
        'class' => '',
    ), $atts);
    $atts = array_map('sanitize_text_field', $atts);
    extract($atts);
    //autoplay
    if ($autoplay == "true") {
        $autoplay = " autoplay";
    } else {
        $autoplay = "";
    }
    //loop
    if ($loop == "true") {
        $loop = " loop";
    }
    else{
        $loop = "";
    }
    //video src
    $src = '<source type="video/mp4" src="'.esc_url($src).'"/>';
    //add webm video if specified in the shortcode
    if (!empty($webm)) {
        $webm = '<source type="video/webm" src="'.esc_url($webm).'"/>';
        $src = $src.$webm; 
    }
    $player = "fp" . uniqid();
    //poster
    $color = '';
    if (!empty($poster)) {
        $color = 'background: #000 url('.$poster.') 0 0 no-repeat;background-size: 100%;';
    } else {
        $color = 'background-color: #000;';
    }
    //video size
    $size_attr = "";
    if (!empty($width)) {
        $size_attr = "max-width: {$width}px;max-height: auto;";
    }
    //video skin
    $class_array = array('flowplayer', 'minimalist');
    if(!empty($class)){
        $shortcode_class_array = array_map('trim', explode(' ', $class));
        $shortcode_class_array = array_filter( $shortcode_class_array, 'strlen' );
        $shortcode_class_array = array_values($shortcode_class_array);
        if(in_array("functional", $shortcode_class_array) || in_array("playful", $shortcode_class_array)){
            $class_array = array_diff($class_array, array('minimalist'));
        }
        $class_array = array_merge($class_array, $shortcode_class_array);
        $class_array = array_unique($class_array);
        $class_array = array_values($class_array);
    }

    $classes = implode(" ", $class_array);
    $esc_attr = 'esc_attr';
    $styles = <<<EOT
    <style>
        #$player {
            $size_attr
            $color    
        }
    </style>
EOT;
    
    $output = <<<EOT
        <div id="{$esc_attr($player)}" data-ratio="{$esc_attr($ratio)}" data-share="false" class="{$esc_attr($classes)}">
            <video{$autoplay}{$loop}>
               $src   
            </video>
        </div>
        $styles
EOT;
    return $output;
}
