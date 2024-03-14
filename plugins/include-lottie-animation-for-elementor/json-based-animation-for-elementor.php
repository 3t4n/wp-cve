<?php
/*
* Plugin Name: JSON Based Animation for Elementor
* Plugin URI: http://lottieanimation.kapasias.com/
* Description: Autoplay | Hover | Click | Mouse Over-Out | Parallax Effect using JSON Based Animation for Elementor and proudly developed by KAP ASIAs Team.
* Version: 1.10.9
* Author: KAP ASIAs
* Author URI: http://kapasias.com
* Text Domain: jbafe
* Elementor tested up to: 3.19
* Elementor Pro tested up to: 3.19
*/

// Prevent direct access to files
if (!defined('ABSPATH')) {
    exit;
}
// Plugin version
defined( 'JBAFE_VERSION' ) or define( 'JBAFE_VERSION', '1.10.9' );
define('JBAFE_PATH', plugin_dir_path(__FILE__));
define('JBAFE_URL', plugin_dir_url(__FILE__));

// Check elementor
require_once JBAFE_PATH . 'include/jbafe-plugin-check.php';

class Ka_Json_Based_Animation_Addon {

    private static $_instance = null;

    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function __construct() {
		add_action('plugins_loaded', [$this, 'init']);
    }
	
	//Init
    public function init() {

		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', 'jbafe_addon_load_fail');
			return;
        }        
		
        add_action( 'elementor/init', [ $this, 'add_elementor_category' ] );
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'includes']);
		add_action('elementor/widgets/register', [$this, 'register_widgets']);
		
		add_action('admin_init', [$this, 'display_notice']);		
		add_action( 'admin_enqueue_scripts', [$this, 'admin_includes'] );
		load_plugin_textdomain('jbafe', false, dirname(plugin_basename(__FILE__)) . '/languages' );
	}
	
	// Admin dismiss notice
	public function display_notice() {

		if(isset($_GET['jbafe_dismiss']) && $_GET['jbafe_dismiss'] == 1) {
	        add_option('jbafe_dismiss' , true);
	    }
		
		$dismiss = get_option('jbafe_dismiss');
		
		if(!get_option('jbafe-top-notice')){
			add_option('jbafe-top-notice',strtotime(current_time('mysql')));
		}
		
		if(get_option('jbafe-top-notice') && get_option('jbafe-top-notice') != 0) {
			if( get_option('jbafe-top-notice') < strtotime('-100 days')) {			
				add_action('admin_notices', 			array($this,'jbafe_top_admin_notice'));
				add_action('wp_ajax_jbafe_top_notice',	array($this,'jbafe_top_notice_dismiss'));
			}
		}
	}
	
	// Admin dismiss notice
	public function jbafe_top_notice_dismiss(){
		update_option('jbafe-top-notice',strtotime(current_time('mysql')));
		exit();
	}
	
	// Admin notice
	public function jbafe_top_admin_notice(){
		?>
			<style>.jbafe-notice.notice-success{border-left-color:#d84242;background:rgba(216, 66, 66, 0.15);}</style>
			<div class="jbafe-notice notice notice-success is-dismissible" style="text-align:center;padding:10px 0;display:flex;align-items:center;justify-content:center;flex-direction:column;">
				<p style="width:100%;"><?php echo esc_html__('Enjoying our ').'<strong>'.esc_html__('JSON Based Animation for Elementor?').'</strong>'.esc_html__(' We hope you liked it! If you feel this plugin helped you, You can give us a 5 star rating!').'<br>'. esc_html__('It will motivate us to serve you more !','jbafe'); ?> </p>				
				<div><a href="http://kapasias.com/" class="button button-secondary" target="_blank" style="background:#d84242;color:#fff;border-radius:50px;outline:none;border:1px solid #d84242;">
				<?php echo esc_html__('VISIT NOW','jbafe'); ?></a>
				<a href="https://wordpress.org/support/plugin/include-lottie-animation-for-elementor/reviews/?filter=5" class="button button-secondary" target="_blank" style="background:#d84242;color:#fff;border-radius:50px;outline:none;border:1px solid #d84242;">
				<?php echo esc_html__('RATE US','acfe'); ?></a></div>
			</div>
		<?php
	}
	
	// Elementor category
	public function add_elementor_category() {
			
		$elementor = \Elementor\Plugin::$instance;
		$elementor->elements_manager->add_category('kap-asia', 
			[
				'title' => esc_html__( 'KAP ASIAs', 'jbafe' ),				
			],
			1
		);	
	}
	
	// Register widget
    public function register_widgets() {
        require_once(JBAFE_PATH . 'include/jbafe-json-anim-widget.php');
    }
	
	// Js and Css
    public function includes() {		
		wp_enqueue_script( 'jbafe-third-party-editor-js', JBAFE_URL . 'assets/js/extra/lottie.min.js',array('jquery'), JBAFE_VERSION,true);
		wp_enqueue_script( 'jbafe-editor-js', JBAFE_URL . 'assets/js/main/jbafe_script.min.js',array('jquery'), JBAFE_VERSION,true);
		wp_register_style( 'jbafe-la-css', JBAFE_URL . 'assets/css/jbafe_css.min.css', JBAFE_VERSION,true);
		
        if (isset($_GET['elementor-preview']) || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor')) {
           wp_enqueue_style('jbafe-la-css');
        }
    }
	
	// Admin side notice
	public function admin_includes() {
		wp_enqueue_script( 'jbafe-editor-js-note', JBAFE_URL . 'assets/js/admin/jbafe_script_note.js',array('jquery'), JBAFE_VERSION,true);
    }
    
}

Ka_Json_Based_Animation_Addon::get_instance();

?>