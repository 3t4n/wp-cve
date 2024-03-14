<?php
/**
 * @package   sharksdesign
 */

if (!defined('ABSPATH'))
  exit;

if (!class_exists('SFT_admin_menu')) {

	

    class SFT_admin_menu {
    	protected static $SFT_instance;
    		
    		function spediex_pro_activate(){
    			require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/featured-slider.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/featured-section.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/about-section.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/our-portfolio.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/our-services.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/our-team.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/our-testimonial.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/our-sponsors.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/features/section-hide-show.php';

    			require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-slider.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-featured.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-about.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-portfolio.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-services.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-team.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-testimonial.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-sponsors.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/sections/section-product.php';

                require_once SFT_PLUGIN_DIR_PATH . 'inc/pro/custom_cantrol.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/customizer_control.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/customizer_css.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/extras.php';                
    		}

    		function init() {	   
		  		add_action( 'init', array($this, 'spediex_pro_activate'));  
		    }


        public static function SFT_instance() {
            if (!isset(self::$SFT_instance)) {
                self::$SFT_instance = new self();
                self::$SFT_instance->init();
            }
            return self::$SFT_instance;
        }
    }
    SFT_admin_menu::SFT_instance();
}