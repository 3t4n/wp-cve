<?php
/**
 * @package   sharksdesign
 */

if (!defined('ABSPATH'))
  exit;

if (!class_exists('SFT_admin_menu')) {

	

    class SFT_admin_menu {
    	protected static $SFT_instance;
    		
    		function spediex_activate(){
    			require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/featured-slider.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/featured-section.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/about-section.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/our-portfolio.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/our-services.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/our-team.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/our-testimonial.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/our-sponsors.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/features/section-hide-show.php';

    			require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-slider.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-featured.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-about.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-portfolio.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-services.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-team.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-testimonial.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-sponsors.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/sections/section-product.php';

                require_once SFT_PLUGIN_DIR_PATH . 'inc/free/custom_control.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/customizer_control.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/customizer_css.php';
                require_once SFT_PLUGIN_DIR_PATH . 'inc/extras.php';                
    		}

    		function init() {	   
		  		add_action( 'init', array($this, 'spediex_activate'));  
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