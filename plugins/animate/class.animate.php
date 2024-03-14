<?php
/*
 * Load on client side
*/

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

if ( !class_exists('Animate') ) {

class Animate{

	public static $options_default_values;

        public function  __construct(){
                add_action('init', array($this, 'init'), 0);
		self::$options_default_values = Array('boxClass' => 'wow', 'animateClass' => 'animated', 'offset' => 0, 'mobile' => true, 'live' => true, 'customCSS' => '');
        }

        public static function settings(){

                load_plugin_textdomain( ANIMATE_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/'.ANIMATE_DOMAIN_DIR);
	}

	public static function set_options(){
		add_option('animate_option_boxClass', 'wow');
		add_option('animate_option_animateClass', 'animated');
		add_option('animate_option_offset', 0);
		add_option('animate_option_mobile', true);
		add_option('animate_option_live', true);
		add_option('animate_option_customCSS', '');
        }
	public static function unset_options(){
		delete_option('animate_option_boxClass');
                delete_option('animate_option_animateClass');
                delete_option('animate_option_offset');
                delete_option('animate_option_mobile');
                delete_option('animate_option_live');
                delete_option('animate_option_customCSS');
	}

        public static function init(){
                // main plugin css
                wp_register_style( 'animate-plugin', ANIMATE_URL.'stylesheets/app.css', array(),  ANIMATE_VERSION, 'all' ) ;
                wp_enqueue_style( 'animate-plugin' );

                // main plugin js
                wp_register_script( 'animate-plugin',  ANIMATE_URL.'js/app.js', array('jquery'), ANIMATE_VERSION, true );
                wp_enqueue_script( 'animate-plugin' );

		add_action( 'wp_footer', Array(__CLASS__, 'script_in_footer'), 30 );
		add_action( 'wp_head', Array(__CLASS__, 'script_in_head'), 30 );
	
                //shortcodes
                $animate_shortcodes = new Animate_shortcodes();

                // allow shortcodes in widgets
                add_filter('widget_text', 'do_shortcode');

		// widjet class
		add_filter('dynamic_sidebar_params', array(__CLASS__,'add_widget_animation_class'));
        }

	public static function script_in_head(){
		$code = '<style type="text/css" id="animate-plugin-header-css">'.get_option('animate_option_customCSS').'</style>';

		echo $code;
	}

	public static function script_in_footer(){
		$animate_option_mobile = (get_option('animate_option_mobile')) ? get_option('animate_option_mobile') : 'false' ;
		$animate_option_live = (get_option('animate_option_live')) ? get_option('animate_option_live') : 'false';
		$code = "<script type='text/javascript'>
		/* <![CDATA[ */
                wow = new WOW(
                      {
                        boxClass:     '".get_option('animate_option_boxClass')."',      	// default wow
                        animateClass: '".get_option('animate_option_animateClass')."',	// default animated
                        offset:       ".get_option('animate_option_offset').",          	// default 0
                        mobile:       ".$animate_option_mobile.",       			// default true
                        live:         ".$animate_option_live."        			// default true
                      }
                    )
                    wow.init();
		/* ]]> */
                </script>";

		echo $code;
	}

	public static function add_widget_animation_class($params){
                global $wp_registered_widgets;
                $widget_id = $params[0]['widget_id'];
                $widget_obj = $wp_registered_widgets[$widget_id];
                $widget_opt = get_option($widget_obj['callback'][0]->option_name);
                $widget_num = $widget_obj['params'][0]['number'];

                if (isset($widget_opt[$widget_num]['animateclasses'])){
                        $animate_animation_class = $widget_opt[$widget_num]['animateclasses']." ".get_option('animate_option_boxClass');
                        $params[0]['before_widget'] = preg_replace('/class="/', 'class=" '.$animate_animation_class.' ',  $params[0]['before_widget'], 1);
                }
                return $params;
        }
}

}

