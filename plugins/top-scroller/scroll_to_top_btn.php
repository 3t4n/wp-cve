<?php

/**
 * Plugin Name:       Top Scroller       
 * Description:       Simply and Safely add Top Scroller button on your website.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Contributors:	  hamzaakram25
 * Author:            Hamza Akram
 * Author URI:        https://hamzaakram.co
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       top-scroller
 * Domain Path:       /languages
**/

// If this file is called directly, abort.

if(!defined("ABSPATH"))
exit;


class WpToTop{

	public $plugins_url = "";

	public function __construct(){

		if (function_exists('register_activation_hook')){

			register_activation_hook(__FILE__,array($this,'activationHook'));
		}

		if (function_exists('register_deactivation_hook')){

			register_deactivation_hook(__FILE__,array($this,'deactivationHook'));
		}

		if (function_exists('register_uninstall_hook')){

			register_uninstall_hook(__FILE__,'uninstallHook');
		}


		// head hook
		add_action('wp_head',array($this,'header_scripts'));

		//init hook
		add_action('init',array($this,'init'));

		// footer hook
		add_action('wp_footer',array($this,'filter_footer'));

		//admin-menu hook
		add_action('admin_menu',array($this,'scroll_to_top_admin_menu'));

		//color picker
		add_action('admin_enqueue_scripts',array($this,'scroll_to_top_admin_scripts') );
		
		//fontawesome
		add_action('wp_enqueue_scripts',array($this,'wpb_load_fa') );

		//footer text
		add_filter('admin_footer_text', array($this, 'admin_footer_text'));
		
}

	// init
	public function init(){

		$this->plugins_url = untrailingslashit(plugins_url('',__FILE__));
	}


	// include admin-menu page
	public function scroll_to_top_admin_menu(){

		add_options_page('Top Scroller','Top Scroller','manage_options','top-scroller',array($this,'scroll_to_top_edit_setting'));

	}


	//Link the admin page

	public function scroll_to_top_edit_setting(){

		include(sprintf("%s/manage/admin.php",dirname(__FILE__)));

	}

	//plugin activation
	public function activationHook(){

		// Input background color of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_btn_color')){

			update_option('scroll_to_top_btn_color','#777777');
		}

		// Input hover color of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_hvr_color')){

			update_option('scroll_to_top_hvr_color','#0a0a0a');
		}


		// Input the speed of "WP Scroll to Top button"

		if(!get_option('scroll_to_top_speed')){

			update_option('scroll_to_top_speed','slow');
		}

		// Icon of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_icon')){

			update_option('scroll_to_top_icon','fa fa-arrow-up');
		}

		// font-size of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_font_size')){

			update_option('scroll_to_top_font_size','18');
		}

		// icon color of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_icon_color')){

			update_option('scroll_to_top_icon_color','#ffffff');
		}

		// icon hover color of the "WP Scroll to Top button"
		if(!get_option('scroll_to_top_hvr_icon_color')){

			update_option('scroll_to_top_hvr_icon_color','#ffffff');
		}

	}


	//plugin deactivation
	public function deactivationHook(){

		delete_option('scroll_to_top_btn_color');
		delete_option('scroll_to_top_speed');
		delete_option('scroll_to_top_hvr_color');
		delete_option('scroll_to_top_icon');
		delete_option('scroll_to_top_font_size');
		delete_option('scroll_to_top_icon_color');
		delete_option('scroll_to_top_hvr_icon_color');
	}


	// plugin uninstall 
	public function uninstallHook(){

		delete_option('scroll_to_top_btn_color');
		delete_option('scroll_to_top_speed');
		delete_option('scroll_to_top_hvr_color');
		delete_option('scroll_to_top_icon');
		delete_option('scroll_to_top_font_size');
		delete_option('scroll_to_top_icon_color');
		delete_option('scroll_to_top_hvr_icon_color');

	}

		//color picker
	function scroll_to_top_admin_scripts( $hook ) {

		//assign wpcolor-picker
		wp_enqueue_style('wp-color-picker');
		

		//include js file
		wp_enqueue_script('scroll_to_top_admin_script', plugins_url('/js/scroll_to_top_colorPicker.js', __FILE__),array('wp-color-picker'), false, true );
		

	}

	 function wpb_load_fa() {
 
 wp_enqueue_style( 'fontawesome-style', plugins_url('/css/all.min.css', __FILE__),array() );

}


// Put Stylesheet in head section


	public function header_scripts(){


		// php files include
		include(sprintf("%s/css/to_top_style.php",dirname(__FILE__)));
	

		// put jquery in the head section
		wp_enqueue_script ('jquery');

		include(sprintf("%s/js/to_top_btn.php",dirname(__FILE__)));


	}


	// Exclude pages and posts

		public function scroll_to_top_excluded_posts() {

			$excluded_name = explode(',', get_option('scroll_to_top_value_exclude'));

			if(is_array($excluded_name)) {

				foreach ($excluded_name as $name) {
					if (null != $name && is_single($name)) {
						return true;
					}
					if (null != $name && is_page($name)) {
						return true;
					}
				}
			}
				return false;
		}


		   public function admin_footer_text($text) {
        
        $reviewLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/top-scroller/reviews/', __('Write a Review', 'top-scroller'));
        $donateLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://www.paypal.me/hamzaakram25', __('Buy me a Tea or Coffee', 'top-scroller'));

        return sprintf(' %s | %s', $reviewLink, $donateLink);
    }


	// Scroll button footer section
	public function filter_footer() {

		if (!$this->scroll_to_top_excluded_posts()) {	

		?>

		<div id="To_top_animation" class="To_top_btn"><a href="#"><i class="<?php echo get_option('scroll_to_top_icon'); ?>"></i></a></div>

		<?php

		}
	}
 
}

$WpToTop = new WpToTop();








