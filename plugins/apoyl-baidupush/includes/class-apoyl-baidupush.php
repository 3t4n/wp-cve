<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * @link       http://www.apoyl.com/
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Baidupush {
	
	protected $loader;
	
	protected $plugin_name;
	
	protected $version;
	
	public function __construct() {
	    
		if ( defined( 'APOYL_BAIDUPUSH_VERSION' ) ) {
			$this->version = APOYL_BAIDUPUSH_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'apoyl-baidupush';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
	
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-apoyl-baidupush-loader.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-apoyl-baidupush-i18n.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-apoyl-baidupush-admin.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-apoyl-baidupush-public.php';
		$this->loader = new Apoyl_Baidupush_Loader();
	}
	
	private function set_locale() {
		$plugin_i18n = new Apoyl_Baidupush_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	
	private function define_admin_hooks() {
		$plugin_admin = new Apoyl_Baidupush_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_menu', $plugin_admin, 'menu');
		$this->loader->add_action('save_post', $plugin_admin, 'push');
		$this->loader->add_filter('plugin_action_links_'.APOYL_BAIDUPUSH_PLUGIN_FILE, $plugin_admin, 'links',10, 2);
		
	}

	private function define_public_hooks() {
	    $arr=get_option('apoyl-baidupush-settings');
	    if(isset($arr['site'])&&isset($arr['secret'])){
    		$plugin_public = new Apoyl_Baidupush_Public( $this->get_plugin_name(), $this->get_version() );
    		$this->loader->add_action('wp_enqueue_style', $plugin_public, 'enqueue_styles');
    	    $this->loader->add_action('the_content', $plugin_public, 'push');
    		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    		$this->loader->add_action( 'wp_footer', $plugin_public, 'footer' ); 
    		$this->loader->add_action('wp_ajax_ajaxpush', $plugin_public,'ajaxpush');
    		$this->loader->add_action('wp_ajax_nopriv_ajaxpush', $plugin_public,'ajaxpush');
	    }
	}

	public function run() {
		$this->loader->run();
	}
	
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
?>