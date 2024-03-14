<?php
if (!defined('ABSPATH'))
  exit;

if (!class_exists('SFT_admin_side')) {

    class SFT_admin_side {

        protected static $SFT_instance;        

		function theme_section_shortcode( $atts ) {
			ob_start();

			if(isset($atts['section'])){
				if ( function_exists( $atts['section'] )){
					call_user_func($atts['section']);
				}
			}

			$content = ob_get_clean();
		    return $content;
		}

		function init(){			
    		add_shortcode( 'theme_section', array($this,'theme_section_shortcode' ));    		
    	} 

		public static function SFT_instance() {
            if (!isset(self::$SFT_instance)) {
                self::$SFT_instance = new self();
                self::$SFT_instance->init();
            }
            return self::$SFT_instance;
        }
    }
    SFT_admin_side::SFT_instance();
}


?>