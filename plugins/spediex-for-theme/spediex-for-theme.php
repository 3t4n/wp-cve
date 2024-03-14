<?php
/**
* Plugin Name: Spediex For Theme
* Description: Import all the demos on your site
* Version: 1.0
* Copyright: 2020
* Text Domain: spediex-for-theme
* 
*/
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit();
}
if (!defined('SFT_PLUGIN_NAME')) {
  define('SFT_PLUGIN_NAME', 'Spediex For Theme');
}
if (!defined('SFT_PLUGIN_VERSION')) {
  define('SFT_PLUGIN_VERSION', '2.0.0');
}
if (!defined('SFT_PLUGIN_FILE')) {
  define('SFT_PLUGIN_FILE', __FILE__);
}
if (!defined('SFT_PLUGIN_DIR')) {
  define('SFT_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('SFT_BASE_NAME')) {
    define('SFT_BASE_NAME', plugin_basename(SFT_PLUGIN_FILE));
}
if (!defined('SFT_DOMAIN')) {
  define('SFT_DOMAIN', 'spediex-for-theme');
}
define( 'SFT_PLUGIN_DIR_PATH' , plugin_dir_path( __FILE__ ) );

if (!class_exists('SFT_main')) {

  class SFT_main {

    protected static $SFT_instance;
    
    function includes() {
      include_once('includes/spediex-for-shortcode.php');
      
    	$theme = wp_get_theme();      
		    if( 'SharksDesign' == $theme->name){
		        require_once('inc/free/demo.php');
		    }
        if( 'SharksDesign Pro' == $theme->name){
            require_once('inc/pro/demo.php');
        }
        if( 'PM Oniae' == $theme->name){
            require_once('inc/free/demo.php');
        }
        if( 'PM Oniae Pro' == $theme->name){
            require_once('inc/pro/demo.php');
        }
        if( 'Shuttle Ecommerce' == $theme->name){
            require_once('inc/free/demo.php');
        }
        if( 'Shuttle Ecommerce Pro' == $theme->name){
            require_once('inc/pro/demo.php');
        }
        if( 'Microt Ecommerce' == $theme->name){
            require_once('inc/free/demo.php');
        }
        if( 'Microt Ecommerce Pro' == $theme->name){
            require_once('inc/pro/demo.php');
        }
    }

    function SFT_load_admin_script_style() {
  		wp_enqueue_style( 'SFT-admin-style', SFT_PLUGIN_DIR . '/inc/assets/css/customizer_admin.css', false, '1.0.0');
  	}

    function init(){
  		add_action( 'admin_enqueue_scripts', array($this, 'SFT_load_admin_script_style'));
  	}

    public static function SFT_do_activation() {
      set_transient('SFT-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function SFT_instance() {
      if (!isset(self::$SFT_instance)) {
        self::$SFT_instance = new self();
        self::$SFT_instance->init();
        self::$SFT_instance->includes();
      }
      return self::$SFT_instance;
    }

  }

  add_action('plugins_loaded', array('SFT_main', 'SFT_instance'));

  register_activation_hook(SFT_PLUGIN_FILE, array('SFT_main', 'SFT_do_activation'));
}

add_action('init','default_settings',1);
function default_settings(){
  global $default_setting;
  //featured slider
    $default_setting['featured_slider_text_color']='#ffffff';
    $default_setting['featured_slider_arrow_text_color']='#ffffff';
    $default_setting['featured_slider_arrow_bg_color']='#212428';
    $default_setting['featured_slider_arrow_texthover_color']='#212428';
    $default_setting['featured_slider_arrow_bghover_color']='#c4cfde';

  //featured section
    $default_setting['featured_section_main_bg_color']='#212428';
    $default_setting['featured_section_bg_color']='#16181c';
    $default_setting['featured_section_color']='#c4cfde';
    $default_setting['featured_section_bg_hover_color']='#c4cfde';
    $default_setting['featured_section_text_hover_color']='#16181c';
    $default_setting['featured_section_icon_color']='#c4cfde';
    $default_setting['featured_section_icon_hover_color']='#16181c';
    $default_setting['featured_section_icon_bg_color']='#212428';
    $default_setting['featured_section_icon_bg_hover_color']='#c4cfde';

  //About Us
    $default_setting['about_bg_color']='#16181c';
    $default_setting['about_title_text_color']='#c4cfde';
    $default_setting['about_text_color']='#c4cfde';
    $default_setting['about_link_color']='#c4cfde';
    $default_setting['about_link_hover_color']='#ffffff';

  //Our Portfolio Section
    $default_setting['our_portfolio_bg_color']='#16181c';
    $default_setting['our_portfolio_title_color']='#c4cfde';
    $default_setting['our_portfolio_text_color']='#c4cfde';
    $default_setting['our_portfolio_container_text_color']='#ffffff';
    $default_setting['our_portfolio_icon_bg_color']='#16181c';
    $default_setting['our_portfolio_icon_color']='#c4cfde';
    $default_setting['our_portfolio_container_bg_color']='#c4cfde';

  //Our Services Section
    $default_setting['our_services_bg_color']='#16181c';
    $default_setting['our_services_text_color']='#c4cfde';
    $default_setting['our_services_contain_bg_color']='#212428';
    $default_setting['our_services_contain_text_color']='#ffffff';
    $default_setting['our_services_link_color']='#c4cfde';
    $default_setting['our_services_link_hover_color']='#ff014f';
    $default_setting['our_services_icon_color']='#ffffff';
    $default_setting['our_services_icon_hover_color']='rgb(255,255,255,0.2)';
    $default_setting['our_services_contain_bg_hover_color']='#16181c';

  //Our Team Section
    $default_setting['our_team_bg_color']='#16181c';
    $default_setting['our_team_text_color']='#c4cfde';
    $default_setting['our_team_text_hover_color']='#000000';
    $default_setting['our_team_icon_color']='#c4cfde';
    $default_setting['our_team_icon_hover_color']='#16181c';
    $default_setting['our_team_icon_background_color']='#16181c';
    $default_setting['our_team_icon_bg_hover_color']='#c4cfde';
    $default_setting['our_team_link_color']='#c4cfde';
    $default_setting['our_team_link_hover_color']='#000000';

  //Our Testimonial Section
    $default_setting['our_team_testimonial_bg_color']='#16181c';
    $default_setting['our_testimonial_text_color']='#ffffff';
    $default_setting['our_testimonial_alpha_color_setting']='#212428';
    $default_setting['our_team_testimonial_text_color']='#ffffff';
    $default_setting['our_team_testimonial_image_bg_color']='#212428';
    $default_setting['our_team_testimonial_arrow_bg_color']='#212428';
    $default_setting['our_team_testimonial_arrow_text_color']='#ffffff';

  //Our Sponsors Section
    $default_setting['our_sponsors_text_color']='#c4cfde';
    $default_setting['our_sponsors_bg_color']='#16181c';
    $default_setting['our_sponsors_img_hover_bg_color']='#fff';
    $default_setting['our_sponsors_arrow_color']='#ffffff';
    $default_setting['our_sponsors_arrow_bg_color']='#212428';
}