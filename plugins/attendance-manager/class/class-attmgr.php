<?php
/** 
 *	Attendance Manager
 */

class ATTMGR {
	// Settings
	const TABLEPREFIX             = 'attmgr_';
	const TEXTDOMAIN              = 'attendance-manager';
	const URL                     = 'http://attmgr.com/';
	const PLUGIN_ID               = 'attmgr';
	const PLUGIN_FILE             = 'attendance-manager.php';
	const PLUGIN_VERSION          = '0.6.1';
	const DB_VERSION              = '0.5.0';

	public $mypluginurl           = null;
	public $mypluginpath          = null;
	public $mypluginfile          = null;

	public $user = array(
		'operator'                => null,
		'acting'                  => null,
	);

	public $page = array(
		'post'                    => null,
		'redirect_to'             => null,
		'qs'                      => null,
		'startdate'               => null,
		'ancestor'                => null,
		'begin_date'              => null,
		'midnight'                => null,
	);

	public $option = array(
		'general' => array(
			'editable_term'       => null,
			'starttime'           => null,
			'endtime'             => null,
			'interval'            => null,
			'format_year_month'   => null,
			'format_month_day'    => null,
			'format_time'         => null,
			'format_time_editor'  => null,
			'cron_interval'       => null,
			'preserve_past'       => null,
			'time_style'          => null,
			'use_avatar'          => null,
		),

		'specialpages' => array(
			'staff_scheduler'     => null,
			'admin_scheduler'     => null,
			'login_page'          => null,
		),
	);

	/** 
	 *	CONSTRUCT
	 */
	public function __construct() {
		load_plugin_textdomain( ATTMGR::TEXTDOMAIN, false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages' );
		ATTMGR_Activation::load();
		$this->mypluginurl  = dirname( plugin_dir_url( __FILE__ ) ).'/';
		$this->mypluginpath = dirname( plugin_dir_path( __FILE__ ) ).'/';
		$this->mypluginfile = $this->mypluginpath.ATTMGR::PLUGIN_FILE;
		$this->option = self::get_option();

		add_action( 'plugins_loaded', array( 'ATTMGR_User', 'load' ) );
		add_action( 'plugins_loaded', array( &$this, 'load' ) );
		add_action( 'template_redirect', array( &$this, 'parse_request' ) );
		add_action( 'plugins_loaded', array( 'ATTMGR', 'reload_textdomain' ) );

		add_action( 'plugins_loaded', array( 'ATTMGR_Updation', 'db_update' ) );
		add_action( 'plugins_loaded', array( 'ATTMGR_Function', 'load' ) );
	}
	
	/** 
	 *	Load
	 */
	public function load() {
		$this->current_user();
	}

	/** 
	 *	parse_request
	 */
	public function parse_request( $query_vars ) {
	    if ( session_status() !== PHP_SESSION_ACTIVE ){
	    	session_start();
	    }

		$this->current_page( $query_vars );
		$this->user['operator']->acting( $query_vars );
		$this->user['acting'] = $this->user['operator']->is_acting();

		session_write_close();
	}

	/** 
	 *	Get plugin options
	 */
	public static function get_option( $group = null, $key = null ) {
		$option = get_option( ATTMGR::PLUGIN_ID );
		// Set default option
		if ( empty( $option ) ) {
			$option = self::default_option();
			update_option( ATTMGR::PLUGIN_ID, $option );
		}

		// Group
		if ( ! empty( $group ) ) {
			if ( isset( $option[ $group ] ) ) {
				if ( ! empty( $key ) && isset( $option[ $group ][ $key ] ) ) {
					return $option[ $group ][ $key ];
				} else {
					return $option[ $group ];
				}
			}
		} else {
			return $option;
		}
	}

	/** 
	 *	Set default plugins options
	 */
	public static function default_option(){
		$default_option = array(
			'general' => array(
				'editable_term'       => 7,
				'starttime'           => '09:00',
				'endtime'             => '18:00',
				'interval'            => 30,	// (min)
				'format_year_month'   => 'Y-n',
				'format_month_day'    => 'n/j',
				'format_time'         => 'G:i',
				'format_time_editor'  => 'H:i',
				'cron_interval'       => 'daily',
				'preserve_past'       => '60',	// (days)
				'time_style'          => '24h',
				'use_avatar'          => 0,
			),

			'specialpages' => array(
				'staff_scheduler'     => 'staff_scheduler',
				'admin_scheduler'     => 'admin_scheduler',
				'login_page'          => '',
			),

		);
		return $default_option;
	}

	/**
	 *	Get current page;
	 */
	public function current_page() {
		global $wpdb, $post, $attmgr;

		$page = array();

		// Current post
		$page['post'] = $current_post = $post;

		// Redirect to
		$redirect_info = array();
		if ( ! is_user_logged_in() ) {
			if ( ! empty( $_SERVER['REDIRECT_URL'] ) ) {
				$redirect_url = ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' )
							   .$_SERVER['HTTP_HOST']
							   .$_SERVER['REDIRECT_URL'];
				if ( ! empty( $_SERVER['REDIRECT_QUERY_STRING'] ) ) {
					$redirect_url .= ( strstr( $redirect_url, '?' ) ) ? '&' : '?';
					$redirect_url .= $_SERVER['REDIRECT_QUERY_STRING'];
				}
				$redirect_url = urlencode( $redirect_url );
				$redirect_info = array(
					'redirect_to='.$redirect_url
				);
			}
		}
		$page['redirect_to'] = $redirect_info;

		// Query string
		$page['qs'] = array();
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $page['qs'] );
		}

		// Date
		$currenttime = current_time( 'timestamp' );
		$now_time = date( 'H:i', $currenttime );
		$starttime = $attmgr->option['general']['starttime'];
		$endtime = ATTMGR_Form::time_calc( $attmgr->option['general']['endtime'], 0, false );

		$page['begin_date'] = date('Y-m-d', $currenttime );
		$page['midnight'] = false;
		$page['startdate'] = date( 'Y-m-d', $currenttime );
		if ( ! empty( $page['qs']['date'] ) ||  ! empty( $page['qs']['week'] ) ) {
			$startdate = ( ! empty( $page['qs']['date'] ) ) ? $page['qs']['date'] : $page['qs']['week'];
			if ( preg_match( '/^([2-9][0-9]{3})-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $startdate ) ) {
				$page['startdate'] = $startdate;
			}
		} else {
			// Opening hours past midnight -> Yesterday
			if ( $starttime > $endtime && $now_time < $endtime ) {
				$page['startdate'] = date( 'Y-m-d', $currenttime - 60*60*24 );
			}
		}
		if ( isset( $page['qs']['date'] ) && date( 'Y-m-d', $currenttime ) > $page['qs']['date'] ) {
			list( $cy, $cm, $cd ) = explode('-', $page['begin_date'] );
			list( $py, $pm, $pd ) = explode('-', $page['qs']['date'] );
			$pday = ceil( ( mktime( 0, 0, 0, $cm, $cd, $cy ) - mktime( 0, 0, 0, $pm, $pd, $py ) ) / ( 60*60*24 ) / 7 );
			$page['begin_date'] = date('Y-m-d', mktime( 0, 0, 0, $cm, $cd - 7 * $pday, $cy ) );
			if ( date( 'Y-m-d', $currenttime - 60*60*24 ) == $page['qs']['date'] 
				&& $starttime > $endtime && $now_time < $endtime ) {
				$page['midnight'] = true;
			}
		} elseif ( $starttime > $endtime && $now_time < $endtime ) {
			$page['begin_date'] = date( 'Y-m-d', $currenttime - 60*60*24 );
			if ( date( 'Y-m-d', $currenttime - 60*60*24 ) == $page['startdate'] ) {
				$page['midnight'] = true;
			}
		}

		// Ancestor
		if ( ! empty( $current_post ) ) {
			$post_ancestors = get_post_ancestors( $current_post->ID );
			$ancestor_id = array_pop( $post_ancestors );
			$ancestor = ( $ancestor_id ) ? get_post( $ancestor_id ) : $current_post;
			$page['ancestor'] = array(
				'ID'        => $ancestor->ID,
				'post_name' => urldecode( $ancestor->post_name ),
			);
		}
		else {
			$page['ancestor'] = array();
		}

		$page = apply_filters( ATTMGR::PLUGIN_ID.'_current_page', $page );

		$this->page = $page;
		return;
	}

	/**
	 *	Get current info;
	 */
	public function current_user() {
		global $current_user;
		// Current operator
		if( function_exists( 'wp_get_current_user' ) ){
			$current_user = wp_get_current_user();
		}
		else{
			get_currentuserinfo();
		}
		$this->user['operator'] = new ATTMGR_User();

		return;
	}

	/**
	 *	Reload textdomain
	 */
	public static function reload_textdomain(){
		if ( is_textdomain_loaded( ATTMGR::TEXTDOMAIN ) ) {
			unload_textdomain( ATTMGR::TEXTDOMAIN );
		}
		load_plugin_textdomain( ATTMGR::TEXTDOMAIN, false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages');
	}

}
?>
