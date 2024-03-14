<?php
/**
 * WPLA_Page
 *
 * This class provides methods for single admin pages
 * 
 */

// helper class for custom screen options - will be removed in future versions
require_once( WPLA_PATH . '/includes/screen-options/screen-options.php' );

class WPLA_Page extends WPLA_Core {
	
	public function __construct() {
		parent::__construct();

		self::$PLUGIN_URL = WPLA_URL;
		self::$PLUGIN_DIR = WPLA_PATH;

		$this->main_admin_menu_label = trim( get_option( 'wpla_admin_menu_label', $this->app_name ) );
		$this->main_admin_menu_label = $this->main_admin_menu_label ? $this->main_admin_menu_label : $this->app_name;
		$this->main_admin_menu_slug  = sanitize_key( str_replace(' ', '-', trim($this->main_admin_menu_label) ) );

		add_action( 'admin_menu', 			array( &$this, 'onWpAdminMenu' ), 20 );
        add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueAdminScripts' ) );

		if ( is_admin() ) {
			add_action( 'plugins_loaded', 	array( &$this, 'handleSubmit' ) );
		}

	}

    /**
     * Load scripts that are used across all WPLA pages
     */
	public function enqueueAdminScripts() {

        wp_register_script( 'wpla', self::$PLUGIN_URL.'js/wpla.js', array( 'jquery' ), WPLA_VERSION );
        wp_enqueue_script( 'wpla' );

        wp_localize_script('wpla', 'wpla_i18n', array(
                'ajax_url' 	=> admin_url('admin-ajax.php'),
                'wpla_ajax_nonce'   => wp_create_nonce('wpla_ajax_nonce')
            )
        );

        if ( ! isset( $_REQUEST['page'] ) ) return;
        if ( substr( $_REQUEST['page'], 0, 4 ) != 'wpla' ) return;

        // jqueryFileTree
        wp_register_script( 'wpla_JobRunner', self::$PLUGIN_URL.'js/classes/JobRunner.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-progressbar' ), WPLA_VERSION );
        wp_enqueue_script( 'wpla_JobRunner' );

        wp_localize_script('wpla_JobRunner', 'wpla_JobRunner_i18n', array(
                'msg_loading_tasks' 	=> __( 'fetching list of tasks', 'wp-lister-for-amazon' ).'...',
                'msg_estimating_time' 	=> __( 'estimating time left', 'wp-lister-for-amazon' ).'...',
                'msg_finishing_up' 		=> __( 'finishing up', 'wp-lister-for-amazon' ).'...',
                'msg_all_completed' 	=> __( 'All {0} tasks have been completed.', 'wp-lister-for-amazon' ),
                'msg_processing' 		=> __( 'processing {0} of {1}', 'wp-lister-for-amazon' ),
                'msg_time_left' 		=> __( 'about {0} remaining', 'wp-lister-for-amazon' ),
                'footer_dont_close' 	=> __('Please don\'t close this window until all tasks are completed.', 'wp-lister-for-amazon'),
                'wpla_ajax_nonce'       => wp_create_nonce('wpla_ajax_nonce')
            )
        );
    }
	
	// these methods can be overriden
	public function onWpAdminMenu() {
	}	
	public function handleSubmit() {
	}	

	public static function do_display( $insView, $inaData = array(), $echo = true ) {
        $sFile = WPLA_PATH . '/views/' . $insView . '.php';

        if ( !is_file( $sFile ) ) {
            wpla_show_message("View not found: ".$sFile, 'error' );
            do_action('wpla_admin_notices');
            return false;
        }

        if ( count( $inaData ) > 0 ) {
            extract( $inaData, EXTR_PREFIX_ALL, 'wpl' );
        }

        ob_start();
        include( $sFile );
        $sContents = ob_get_contents();
        ob_end_clean();

        // change admin footer on wplister pages
        if ( apply_filters( 'wplister_enable_admin_footer', true ) ) {
            add_filter('admin_footer_text', array( __CLASS__, 'change_admin_footer_text') );
            add_filter('update_footer', array( __CLASS__, 'change_admin_footer_version') );
        }

        // MOVED to wp-lister.php
        // fix thickbox display problems caused by other plugins
        // like woocommerce-pip which enqueues media-upload on every admin page
        // if ( did_action( 'init' ) ) wp_dequeue_script( 'media-upload' );

        // filter content before output
        $sContents = apply_filters( 'wpla_admin_page_content', $sContents );

        if ($echo) {
            echo $sContents;
            return true;
        } else {
            return $sContents;
        }
    }

	// display view
	public function display( $insView, $inaData = array(), $echo = true ) {
	    return self::do_display( $insView, $inaData, $echo );
	}

	static function change_admin_footer_text() {  
		// $plugin_name = 'WP-Lister Pro for Amazon';  
		$plugin_name  = WPLA_LIGHT ? 'WP-Lister for Amazon' : 'WP-Lister Pro for Amazon';  
	    echo '<span id="footer-thankyou">';
	    echo sprintf( __( 'Thank you for listing with %s', 'wp-lister-for-amazon' ), '<a href="https://www.wplab.com/plugins/wp-lister/" target="_blank">'.$plugin_name.'</a>' );
	    echo '</span>';
	}  
	static function change_admin_footer_version( $version ) {
		// $plugin_name = 'WP-Lister Pro for Amazon';  
		$plugin_name  = WPLA_LIGHT ? 'WP-Lister Lite for Amazon' : 'WP-Lister Pro for Amazon';  
		$plugin_name .= ' ' . self::get_plugin_version();
		$network_activated = get_option('wplister_is_network_activated') == 1 ? true : false;
		if ( $network_activated ) $plugin_name .= 'n';
	    return $version . ' / ' . $plugin_name;
	}  

	static function get_plugin_version() {
	    
	    if ( ! function_exists( 'get_plugins' ) )
	    	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	    
	    // $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	    // $plugin_file = basename( ( __FILE__ ) );
	    $plugin_folder = get_plugins( '/' . plugin_basename( WPLA_PATH ) );
	    $plugin_file = 'wp-lister-amazon.php';

	    return $plugin_folder[$plugin_file]['Version'];
	}

	function get_i8n_html( $basename )	{
		if ( empty( $basename ) ) return false;
		if ( ! defined( 'WPLANG' ) ) define( 'WPLANG', 'en_US' );

		$lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : substr( WPLANG, 0, 2 ); // WPML COMPATIBILITY
		$lang_folder = trailingslashit(WPLA_PATH) . 'views/lang/'; 

		$default_file    = $lang_folder . $basename . '_en.html';
		$translated_file = $lang_folder . $basename . '_' . $lang . '.html';

		$file = file_exists( $translated_file ) ? $translated_file : $default_file;
		if ( is_readable( $file ) ) return file_get_contents( $file );

		wpla_show_message('file not found: '.$file, 'error' );
		do_action('wpla_admin_notices');
		
		return false;
	}

	function check_wplister_setup( $page = false )	{
		$Setup = new WPLA_Setup();
		$Setup->checkSetup( $page );
	}


	public function print_schedule_info( $schedule ) {

		$next_schedule = wp_next_scheduled( $schedule );
		if ( $next_schedule ) {
			// check if next schedule lies in the past
        	if ( $next_schedule < current_time('timestamp',1) ) {
        		return sprintf( __( '%s ago', 'wp-lister-for-amazon' ), human_time_diff( $next_schedule ) );
        	}
        	// default: next schedule is in the future
			return sprintf( __( 'in %s', 'wp-lister-for-amazon' ), human_time_diff( $next_schedule ) );
		}

		$cron_schedule = self::getOption('cron_schedule');
		if ( 'external' == $cron_schedule ) {
			return __( 'automatically', 'wp-lister-for-amazon' );
		}

		return __( 'manually', 'wp-lister-for-amazon' );
	}



}

