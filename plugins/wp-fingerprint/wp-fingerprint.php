<?php
/*
Plugin Name: WP Fingerprint
Description: WP Fingerprint adds an additional layer of security to your WordPress website, working to check your plugins for signs of hack or exploit
Version: 2.1.2
Author: 34SP.com
Author URI: https://www.34SP.com
*/
class WPFingerprint_Plugin {

	private $path;
	public $runner;
	private $db_version = 3;

	public function __construct( )
	{
		if ( defined('WP_CLI') && WP_CLI ) {
      //only load if wp-cli is available
	     require_once plugin_dir_path( __FILE__ ) . '/fingerprint-command.php';
     }
		 $this->path = plugin_dir_path( __FILE__ );
		 require_once $this->path . 'inc/class-wpfingerprint-runner.php';
		 if(empty($this->runner)){
		 	$this->runner = new WPFingerprint_Runner($this->path);
		 }
		 /*
		  * Runs our migrations for updates
			*/
		 if($this->db_version > get_option('wpfingerprint_db_version',0))
		 {
			 $this->runner->model_checksums->migrate($this->db_version);
			 $this->runner->model_diffs->migrate($this->db_version);
			 update_option('wpfingerprint_db_version',$this->db_version);
		 }
	}

	public function load( )
	{
		//load additional additional actions
		add_action( 'admin_init', array($this,'admin_init'));
		add_action( 'wpfingerprint_cron', array($this,'cron'));
		add_action( 'wpfingerprint_run_now', array($this,'cron'));
	}

	function admin_init( )
	{
		//Admin settings loading
		$this->runner->load('settings');
		//No point adding the filters to a screen they wont see.
		if( current_user_can('manage_options') ){
			//Only folks who can do something with plugins are alerted
			add_action( 'admin_footer', array('WPFingerprint_Settings', 'admin_bar_footer_js') );
			add_action( 'wp_ajax_wp-fingerprint-recheck', array('WPFingerprint_Settings', 'recheck_callback') );
			add_action( 'admin_bar_menu', array('WPFingerprint_Settings', 'admin_bar_menu'),110 );
		}
		/* Settings and hooks specific to Plugin Page */
		global $pagenow;
		if( current_user_can('activate_plugins') && $pagenow == 'plugins.php'){
			if ( ! function_exists( 'get_plugins' ) ) {
			//Needed as it doesn't always autoload.
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			//Pray this is cached as its resource intensive
			$all_plugins = get_plugins();
			foreach($all_plugins as $key => $value){
				$hook = 'after_plugin_row_'.$key;
				add_action( $hook , array('WPFingerprint_Settings','notices'), 10,3);
			}
		}
	}

	function runner( )
	{
		return $this->runner->run();
	}

	//Trigger the cron
	function cron( )
	{
		if(!defined('WPFINGERPRINT_NOCRON')){
			$this->runner();
		}
	}

	public function activation_hook() {
		add_option('wpfingerprint_last_run', time());
		add_option('wpfingerprint_mode', 'cron');
		add_option('wpfingerprint_fails', 0);
		if (! wp_next_scheduled ( 'wpfingerprint_cron' )) {
			wp_schedule_event(time(), 'hourly', 'wpfingerprint_cron');
    }

	}
	public function deactivation_hook() {
		//Clean up if the plugin is deactivated
		wp_clear_scheduled_hook('wpfingerprint_cron');
		delete_option('wpfingerprint_invalid');
		delete_option('wpfingerprint_checksum');
		delete_option('wpfingerprint_db_version');
		delete_option('wpfingerprint_last_run');
		delete_option('wpfingerprint_fails', 0);
	}
}
//Setup our plugin for running
$wpfingerprint = new WPFingerprint_Plugin();
register_activation_hook( __FILE__, array($wpfingerprint, 'activation_hook'));
register_deactivation_hook( __FILE__, array($wpfingerprint, 'deactivation_hook'));
add_action('wp_loaded', array($wpfingerprint, 'load'));
?>
