<?php
/** 
 *	Yes/No Chart
 */

class YESNO {
	// Settings
	const TABLEPREFIX             = 'yesno_';
	const PLUGIN_ID               = 'yesno';
	const PLUGIN_FILE             = 'yesno.php';
	const PLUGIN_VERSION          = '1.0.12';
	const DB_VERSION              = '1.0.0';
	const FEED_URL                = 'https://kohsei-works.com/dev/info/yesno/feed';

	public $mypluginurl           = null;
	public $mypluginpath          = null;
	public $mypluginfile          = null;

	public $page = array(
		'post'                    => null,
		'redirect_to'             => null,
		'qs'                      => null,
		'ancestor'                => null,
	);

	public $options = array(
		'set' => array(
			'list_per_page'       => 0,
		),
		'question' => array(
			'list_per_page'       => 0,
		),
		'version'  => array(
			'plugin'              => null,		// Pluigin version
			'db'                  => null,		// DB version
		),
	);
	public $sid                   = null;

	/** 
	 *	CONSTRUCT
	 */
	public function __construct() {
		load_plugin_textdomain( 'yesno', false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages');
		YESNO_Activation::load();
		$this->mypluginurl  = dirname( plugin_dir_url( __FILE__ ) ).'/';
		$this->mypluginpath = dirname( plugin_dir_path( __FILE__ ) ).'/';
		$this->mypluginfile = $this->mypluginpath.YESNO::PLUGIN_FILE;

		$this->options = self::get_option();

		add_action('plugins_loaded', array('YESNO', 'reload_textdomain') );
		add_action('template_redirect', array( &$this, 'parse_request') );	// Front page
		add_action('admin_init', array( &$this, 'parse_request') );			// Dash board

		add_action('plugins_loaded', array('YESNO_Updation', 'load') );
		add_action('plugins_loaded', array('YESNO_Function', 'load') );
		add_action('plugins_loaded', array('YESNO_Admin_Page', 'load') );
	}
	
	/** 
	 *	parse_request
	 */
	public function parse_request( $query_vars ) {
		$this->current_page( $query_vars );
	}

	/**
	 *	Get current page;
	 */
	public function current_page() {
		global $wpdb, $post, $pagenow, $yesno;

		$page = array();

		// On Dash Board
		if ( is_admin() ) {
			$page['pagenow'] = $pagenow;
		}
		else {
			// Current post
			$page['post'] = $current_post = $post;

			// Redirect to ( Not logged in )
			$redirect_info = array();
			if ( ! is_user_logged_in() ) {
				if ( ! empty( $_SERVER['REDIRECT_URL'] ) ) {
					$redirect_url = ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://')
								   .$_SERVER['HTTP_HOST']
								   .$_SERVER['REDIRECT_URL'];
					if ( ! empty( $_SERVER['REDIRECT_QUERY_STRING'] ) ) {
						$redirect_url .= ( strstr( $redirect_url, '?') ) ? '&' : '?';
						$redirect_url .= $_SERVER['REDIRECT_QUERY_STRING'];
					}
					$redirect_url = urlencode( $redirect_url );
					$redirect_info = array(
						'redirect_to='.$redirect_url
					);
				}
			}
			
			$page['redirect_to'] = $redirect_info;
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
		}

		// Query string
		$page['qs'] = array();
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $page['qs'] );
		}

		$page = apply_filters( YESNO::PLUGIN_ID.'_current_page', $page );

		$this->page = $page;
		return;
	}

	/**
	 *	Get current info;
	 */
	public function current_user() {
		global $current_user;
		// Current operator
		if( function_exists('wp_get_current_user') ){
			$current_user = wp_get_current_user();
		}
		else{
			get_currentuserinfo();
		}
		$this->operator = $current_user;

		return;
	}

	/** 
	 *	Get plugin options
	 */
	public static function get_option( $group = null, $key = null ) {
		$options_key = YESNO::PLUGIN_ID;
		$options = get_option( $options_key );
		if ( empty( $options ) ) {
			$options = self::default_option();
			update_option( $options_key, $options );
		}
		// Group
		if ( ! empty( $group ) ) {
			if ( isset( $options[ $group ] ) ) {
				if ( ! empty( $key ) && isset( $options[ $group ][ $key ] ) ) {
					return $options[ $group ][ $key ];
				} else {
					return $options[ $group ];
				}
			}
		} else {
			return $options;
		}
	}

	/** 
	 *	Set default plugins options
	 */
	public static function default_option(){
		// Default option
		$default_option = array(
			'set' => array(
				'list_per_page'  => 10,
			),
			'question' => array(
				'list_per_page'  => 10,
			),
			'version' => array(
				'plugin'         => YESNO::PLUGIN_VERSION,
				'db'             => YESNO::DB_VERSION
			),
		);
		return $default_option;
	}

	/**
	 *	Reload textdomain
	 */
	public static function reload_textdomain(){
		if ( is_textdomain_loaded( 'yesno') ) {
			unload_textdomain( 'yesno');
		}
		load_plugin_textdomain( 'yesno', false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages');
	}

}
?>