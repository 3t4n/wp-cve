<?php
if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

if ( !class_exists('Animate_admin') ) {

class Animate_admin{

	public static $donate_link;
	public static $wordpress_plugin_page;
	public static $demo_link;

	function __construct(){
		add_action('init', array($this, 'init'), 0);	
		add_action('admin_menu', array($this, 'register_menu_page'));

		add_filter( 'plugin_action_links_' . ANIMATE_SLUG, array( $this, 'add_action_link' ), 10, 2 );
		
		self::$donate_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GX2LMF9946LEE"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" alt="PayPal - The safer, easier way to pay online!" /></a>';
		self::$wordpress_plugin_page = '<a href="https://wordpress.org/plugins/animate/">'.__('Wordpress Plugin Page', 'animate').'</a>';
		self::$demo_link = '<a href="http://animate.tadam.co.il/">'.__('Full Documantation & Demo','animate').'</a>';
	}
	
	public static function init(){
		// load styles
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
		$animate_tinymce = new Animate_TinyMCE();

		// widjet class
                add_action('in_widget_form', array(__CLASS__,'extend_widget_form'),11,3);
                add_filter('widget_update_callback', array(__CLASS__,'update_widget_animation_class'),11,2);
	}

	public static function register_menu_page(){
		$admin_page = add_menu_page( __('Animate', 'animate'), __('Animate', 'animate'), 'manage_options', 'animate_dashboard', array(__CLASS__, 'load_page'), plugins_url('/images/icon-20x20.png', __FILE__), '99.2' );
		/**
                 * Filter: 'animate_manage_options_capability' - Allow changing the capability users need to view the settings pages
                 *
                 * @api string unsigned The capability
                 */
                $manage_options_cap = apply_filters( 'animate_manage_options_capability', 'manage_options' );
		
		//call register settings function
		add_action('admin_init', array(__CLASS__, 'page_init' )); //call register settings function

	}

	public static function load_page() {
		$page = filter_input( INPUT_GET, 'page' );
		switch ( $page ) {
			case 'animate_dashboard':
                        default:
                                require_once( ANIMATE_DIR . 'admin/pages/dashboard.php' );
                                break;
                }
	}

	public static function page_init() {
		register_setting( 'animate-options', 'animate_option_boxClass');
                register_setting( 'animate-options', 'animate_option_animateClass');
                register_setting( 'animate-options', 'animate_option_offset' );
                register_setting( 'animate-options', 'animate_option_mobile' );
                register_setting( 'animate-options', 'animate_option_live' );
                register_setting( 'animate-options', 'animate_option_customCSS' );
 	}

	public static function plugin_row_meta($input, $file){
		if ( ANIMATE_SLUG != $file ) {
                        return $input;
                }
                $links = array(
			self::$wordpress_plugin_page,
			self::$demo_link,
			self::$donate_link,
                );


                $input = array_merge( $input, $links );

                return $input;
	}

	/**
         * Adds form fields to Widget
         * @static
         * @param $widget
         * @param $return
         * @param $instance
         * @return array
         * @since 1.0
         */
        public static function extend_widget_form( $widget, $return, $instance ) {
                if ( !isset( $instance['animateclasses'] ) ) $instance['animateclasses'] = null;
                $fields = '';

		$Lightspeed = Array('lightSpeedIn','lightSpeedOut');
		$Rotating_Entrances = Array('rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight');
		$Rotating_Exits = Array('rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight');
		$Specials = Array('hinge','rollIn','rollOut');
		$Zoom_Entrances = Array('zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp');
		$Zoom_Exits = Array('zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp');

		$preset_values = Array($Lightspeed, $Rotating_Entrances, $Rotating_Exits, $Specials, $Zoom_Entrances, $Zoom_Exits);
		$selectTitles = Array( 	__("Attention Seekers","animate"), 
					__("Bouncing Entrances","animate"),
					__("Bouncing Exits","animate"),
					__("Fading Entrances","animate"),
					__("Fading Exits","animate"),
					__("Flippers","animate"),
					__("Lightspeed","animate"),
					__("Rotating Entrances","animate"),
					__("Rotating Exits","animate"),
					__("Specials","animate"),
					__("Zoom Entrances","animate"),
					__("Zoom Exits","animate"));

                $fields .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-animateclasses'>".apply_filters( 'widget_css_animateclasses_class', esc_html__( 'Animate css class', 'animate' ) ).":</label>\n";
                $fields .= "\t<select name='widget-{$widget->id_base}[{$widget->number}][animateclasses]' id='widget-{$widget->id_base}-{$widget->number}-animateclasses' class='widefat'>\n";
                $fields .= "\t<option value=''>".esc_attr__( 'Select', 'animate' )."</option>\n";

		for ($i = 0; $i < count($preset_values); $i++) {
                        $preset_value = $preset_values[$i];
			$fields .= '<optgroup label="'.$selectTitles[$i].'">';

                        for($j = 0; $j < count($preset_value); $j++) {
				if (!$preset_value[$j]) continue;
				$fields .= "\t<option value='".esc_attr($preset_value[$j])."' ".selected( $instance['animateclasses'], $preset_value[$j], 0 ).">".$preset_value[$j]."</option>\n";
			}
			$fields .= '</optgroup>';
		}
                $fields .= "</select>\n";

                $fields .= "</p>\n";

                do_action( 'widget_css_classes_form', $fields, $instance );

                echo $fields;
                return $instance;
        }

	public static function update_widget_animation_class( $instance, $new_instance ) {
                $instance['animateclasses'] = $new_instance['animateclasses'];
                do_action( 'widget_css_classes_update', $instance, $new_instance );
                return $instance;
        }

	/**
         * Add a link to the settings page to the plugins list
         *
         * @staticvar string $this_plugin holds the directory & filename for the plugin
         *
         * @param array  $links array of links for the plugins, adapted when the current plugin is found.
         * @param string $file  the filename for the current plugin, which the filter loops through.
         *
         * @return array $links
         */
        function add_action_link( $links, $file ) {
                if ( ANIMATE_SLUG === $file ) {
                        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=animate_dashboard' ) ) . '">' . __( 'Settings', 'animate' ) . '</a>';
                        array_unshift( $links, $settings_link );
                }
                return $links;
        }	
}

}
