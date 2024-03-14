<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
global $wp_version;
if (version_compare($wp_version, "3.3", "<")){
	exit('This plugin requires WordPress 3.3 or newer, yours is ' . $wp_version);
}

if( ! function_exists('_print_r') ){
	function _print_r($thing)
	{
		echo '<pre>';
		print_r( $thing );
		echo '</pre>';
	}
}

// a nice helper
if( ! function_exists('hc2_wp_get_id_by_shortcode') ){
	function hc2_wp_get_id_by_shortcode( $shortcode )
	{
		global $wpdb;
		$return = array();

		$pages = $wpdb->get_results( 
			"
			SELECT 
				ID 
			FROM $wpdb->posts 
			WHERE 
				( post_type = 'post' OR post_type = 'page' ) 
				AND 
				( post_content LIKE '%[" . $shortcode . "%]%' )
				AND 
				( post_status = 'publish' )
			"
			);

		if( $pages ){
			foreach( $pages as $p ){
				$return[] = $p->ID;
			}
		}

		return $return;
	}
}

if( ! function_exists('hc2_wp_get_id_by_block') ){
	function hc2_wp_get_id_by_block( $block )
	{
		global $wpdb;
		$ret = array();

		$pages = $wpdb->get_results( 
			"
			SELECT 
				ID 
			FROM $wpdb->posts 
			WHERE 
				( post_type = 'post' OR post_type = 'page' ) 
				AND 
				( post_content LIKE '% wp:" . $block . " %' )
				AND 
				( post_status = 'publish' )
			"
			);

		if( $pages ){
			foreach( $pages as $p ){
				$ret[] = $p->ID;
			}
		}

		return $ret;
	}
}

if( ! class_exists('hcWpBase6') )
{
class hcWpBase6
{
	public $_localize_scripts = array();
	public $hcapp = NULL;
	protected $hcappview = NULL;

	public $app = '';
	protected $app_code = '';
	protected $app_short_name = '';
	protected $app_dir = '';
	public $slug = '';
	public $db_prefix = '';
	protected $my_db_prefix = '';
	public $full_path = NULL;

	public $types = array();
	public $dir = '';
	public $pages = array();
	public $page_param = '';

	public $hc_product = '';

	public $happ_path = '';
	public $deactivate_other = array();

	public $premium = NULL;
	public $wrap_output = array();

	public $hcs = 'hcs'; // get/post param to intercept
	public $hca = 'hca'; // get/post param to pass our action

	public function __construct( 
		$app_conf,
		$full_path,
		$hc_product = '',
		$slug = '',
		$db_prefix = FALSE
		)
	{
		$this->wrap_output = array( '<!-- START OF NTS -->', '<!-- END OF NTS -->' );
		$this->full_path = $full_path;

		if( defined('NTS_DEVELOPMENT2') ){
			$this->happ_path = NTS_DEVELOPMENT2;
		}
		else {
			$this->happ_path = dirname($full_path) . '/happ2';
		}

		$dir = dirname( $full_path );
		$this->hc_product = $hc_product;

	/* HC SYSTEM PARAMS */
		if( is_array($app_conf) ){
			$app = array_shift($app_conf);
			$app_code = array_shift($app_conf);
		}
		else {
			$app = $app_conf;
			$app_code = '';
		}

		$app_short_name = 'hc' . $app_code;

		$this->app = $app;
		$this->app_code = $app_code;
		$this->app_short_name = $app_short_name;

		$this->app_dir = dirname($full_path);

		$this->slug = $slug ? $slug : $this->app_short_name;
		$this->dir = $dir;
		$this->page_param = 'page_id';

		if( $db_prefix === FALSE ){
			$this->db_prefix = $this->app_short_name;
		}
		else {
			$this->db_prefix = $db_prefix;
		}

		$file = $this->dir . '/' . $app . '.php';
		if( file_exists($file) ){
			register_activation_hook( $file, array($this, '_install') );
		}

		add_action(	'init',	array($this, '_init') );
		add_action( 'init', array($this, 'check_intercept') );

		// add_action('user_register',			array($this, '_user_sync'), 10);
		// add_action('added_existing_user',	array($this, '_user_sync'), 10);
		// add_action('profile_update',		array($this, '_user_sync'), 10);
		// add_action('deleted_user',			array($this, '_user_sync'), 10);
		// add_action('remove_user_from_blog',	array($this, '_user_sync'), 10);
	}

	function _init()
	{
		if( $this->db_prefix === NULL ){
			$db_params = NULL;
		}
		else {
			$db_conn_id = NULL;
			global $wpdb, $table_prefix;

			if( is_multisite() ){
				$share_database = get_site_option( 'locatoraid_share_database', 0 );
				$wp_prefix = $share_database ? $wpdb->base_prefix : $wpdb->prefix;
			}
			else {
				$wp_prefix = $wpdb->prefix;
			}

			// $myprefix = $table_prefix . $this->db_prefix . '_';
			$myprefix = $wp_prefix . $this->db_prefix . '_';
			$this->my_db_prefix = $myprefix;
		}

		// $app_dirs = array(
			// array($this->app_dir, $this->app_code),
			// $this->happ_path
			// );
		$app_dirs = array( $this->app_dir, $this->happ_path );

		$filter_name = $this->app . '_app_dirs';
		$app_dirs = apply_filters( $filter_name, $app_dirs );

		$extension_modules = array();
		$filter_name = $this->app . '_extension_modules';
		$extension_modules = apply_filters( $filter_name, $extension_modules );

		include_once( $this->happ_path . '/hsystem/index.php' );

		$this->hcapp = new HC_Application(
			$this->app,
			$app_dirs,
			$this->app_code,
			$extension_modules
			);
		// $this->app_short_name = $this->hcapp->app_short_name();

		if( $this->db_prefix !== NULL ){
			include_once( $this->happ_path . '/hsystem/database/index.php' );
			include_once( $this->happ_path . '/hsystem/database/wordpress/engine.php' );

			global $wpdb;

			$db_engine = new HC_Database_Engine_Wordpress( $wpdb );
			$db = new HC_Database( $db_engine, $myprefix ); 
			$this->hcapp->set_db( $db );
		}

		$this->hcapp->web_dir = plugins_url( '', $this->full_path );
		$this->hcapp->add_app_page( $this->slug );

	// text domain
		// $lang_domain = $this->app;
		$lang_domain = $this->slug;
		$lang_dir = plugin_basename($this->dir) . '/languages';
// echo "LOADING LANG DOMAIN: " . $lang_domain . '<br>';
// echo "LANG DIR '$lang_dir'" . '<br>';
// exit;
		$load_result = load_plugin_textdomain( $lang_domain, '', $lang_dir );

		// echo $load_result ? 'LOADOK<br>' : 'LOADFAIL<br>';

//		session_name( $session_name );
		$sessionOptions = array();
		$sessionOptions = array( 'read_and_close' => TRUE );
		if( ! (defined('DOING_CRON') && DOING_CRON) ){
			if( PHP_SESSION_NONE == session_status() ){
				@session_start( $sessionOptions );
			}
		}

		// ob_start();
	}

	public function admin_app_menu()
	{
		$menu_slug = $this->slug;
		$menu_items = $this->hcapp->make('/layout/top-menu')
			->options()
			;

		$my_submenu_count = 0;
		global $submenu;

		foreach( $menu_items as $child_key => $child ){
			if( ! method_exists($child, 'href') ){
				continue;
			}
			if( ! method_exists($child, 'content') ){
				continue;
			}

			$href = $child->href(); // relative
			if( ! strlen($href) ){
				continue;
			}

			$is_outside = FALSE;
			if( method_exists($child, 'is_outside') ){
				$is_outside = $child->is_outside();
			}

		// relative href
			if( ! $is_outside ){
				$pos1 = strpos($href, '?');
				$pos2 = ( $pos1 === FALSE ) ? 
					strrpos($href, '/') : 
					strrpos(substr($href, 0, $pos1), '/')
					;
				$href = substr($href, $pos2 + 1);
			}

			$page_title = '';
			$menu_title = $child->content();
			$menu_title = strip_tags( $menu_title );

			remove_submenu_page( $menu_slug, $href );

			$ret = add_submenu_page(
				$menu_slug,					// parent
				$page_title,				// page_title
				$menu_title,				// menu_title
				'read',						// capability
				$menu_slug . '-' . $child_key,		// menu_slug
				'__return_null'
				);

			if( ! array_key_exists($menu_slug, $submenu) ){
				continue;
			}

			$my_submenu = $submenu[$menu_slug];
			$my_submenu_ids = array_keys($my_submenu);
			$my_submenu_id = array_pop($my_submenu_ids);

			$submenu[$menu_slug][$my_submenu_id][2] = $href;
			$my_submenu_count++;
		}

		if( isset($submenu[$menu_slug][0]) && ($submenu[$menu_slug][0][2] == $menu_slug) ){
			unset($submenu[$menu_slug][0]);
		}

		if( ! $my_submenu_count ){
			remove_menu_page( $menu_slug );
		}
	}

	public function set_current_app_menu( $parent_file )
	{
		global $submenu_file, $current_screen, $pagenow;

		$menu_slug = $this->slug;

		$my = FALSE;
		if( $current_screen->base == 'toplevel_page_' . $menu_slug ){
			$my = TRUE;
		}
		if( substr($current_screen->post_type, 0, strlen($menu_slug)) == $menu_slug ){
			$my = TRUE;
		}

		if( ! $my ){
			return $parent_file;
		}

		switch( $pagenow ){
			case 'edit-tags.php':
				$submenu_file = 'edit-tags.php?taxonomy=' . $current_screen->taxonomy . '&post_type=' . $current_screen->post_type;
				break;

			case 'admin.php':
				$uri = $this->make('/http/uri');

				$top_menu = $this->make('/layout/top-menu');
				$submenu_slug = $top_menu->current();
				if( ! $submenu_slug ){
					$submenu_slug = $uri->slug();
				}

				$submenu_file = 'admin.php?page=' . $menu_slug . '&' . $uri->url_param($submenu_slug);
				break;

			default:
				break;
		}

		$parent_file = $menu_slug;
		return $parent_file;
	}

	public function hcapp_start()
	{
		$lang_domain = $this->slug;
		HCM::$domain = $lang_domain;

		$this->hcapp->start();

	// init mode urls
		$uri = $this->hcapp->make('/http/uri');

	// api
		// $siteUrl = site_url('/');
		// $url = parse_url( $siteUrl );
		// $base_url = $url['scheme'] . '://'. $url['host'] . $url['path'];
		// $api_url = (isset($url['query']) && $url['query']) ? '?' . $url['query'] . '&' : '?';
		// $api_url .= $this->hcs . '=' . $this->slug;
		// $api_url = $base_url . $api_url;

		$siteUrl = site_url();
		$siteUrl = site_url( 'index.php' );
		// $api_url = add_query_arg( array($this->hcs => $this->slug), $siteUrl );
		$api_url = add_query_arg( array($this->hcs => $this->slug, 'hcrand' => rand(1000,9999)), $siteUrl );

	// web
		if( is_admin() ){
			$web_url = get_admin_url() . 'admin.php?page=' . $this->slug;
		}
		else {
			$web_url = $uri->current();
			$uri->from_url( $web_url );
			$web_url = $uri->url();
		}

		$uri
			->set_mode_url( 'api', $api_url )
			->set_mode_url( 'web', $web_url )
			;

		$this->hcapp->bootstrap();
	}

	public function make( $slug )
	{
		if( ! $this->hcapp->is_started() ){
			$this->hcapp_start();
		}
		$return = $this->hcapp->make( $slug );
		return $return;
	}

	public function hcapp()
	{
		return $this->hcapp;
	}

	public function i_can_admin()
	{
		$return = FALSE;

		$wp_user = wp_get_current_user();
		if( isset($wp_user->allcaps) ){
			if( isset($wp_user->allcaps['install_plugins']) && $wp_user->allcaps['install_plugins'] ){
				$return = TRUE;
				return $return;
			}
		}

		if( ! isset($wp_user->roles) ){
			return $return;
		}

		$my_wp_roles = $wp_user->roles;

		$my_conf_table = NULL;
		global $wpdb;
		$search_table = $this->my_db_prefix . '%' . '_conf'; 
		$sql = "SHOW TABLES LIKE '$search_table'";

		$my_conf_tables = $wpdb->get_results($sql, ARRAY_N);
		if( $my_conf_tables ){
			$my_conf_table = array_pop(array_pop($my_conf_tables));
			if( $my_conf_table ){
				$pref = 'wordpress_users:role_';
				$sql = "SELECT name, value FROM $my_conf_table WHERE name LIKE '$pref" . "%'";
				$my_results = $wpdb->get_results($sql, ARRAY_A);
				$my_roles_config = array();
				foreach( $my_results as $mr ){
					$role_name = substr($mr['name'], strlen($pref));
					$my_roles_config[ $role_name ] = $mr['value'];
				}

				foreach( $my_wp_roles as $wp_role ){
					// if( isset($my_roles_config[$wp_role]) && $my_roles_config[$wp_role] != 'none' ){
						// $return = TRUE;
						// break;
					// }
					if( isset($my_roles_config[$wp_role]) ){
						if( ! in_array($my_roles_config[$wp_role], array(0, 'none')) ){
							$return = TRUE;
							break;
						}
					}
				}
			}
		}
		return $return;
	}

	public function _user_sync( $user_id )
	{
		$this->hcapp_start();

		$wum = $this->hcapp->make('wordpress/model/user');
		$result = $wum->sync( $user_id );
	}

	public function _continue_init()
	{
		$this->_localize_scripts = array();

		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );

		add_action( 'admin_menu', array($this, 'admin_app_menu') );

		add_filter( 'parent_file', array($this, 'set_current_app_menu') );

		$submenu = is_multisite() ? 'network_admin_menu' : 'admin_menu';
		add_action( $submenu, array($this, 'admin_submenu') );
	}

	static function uninstall( $prefix, $watch_other = array() )
	{
		global $wpdb, $table_prefix;

		if( ! strlen($prefix) ){
			return;
		}

		$stop = FALSE;
		if( $watch_other ){
			if( ! function_exists('get_plugins')){
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins = get_plugins();
			foreach( $all_plugins as $pl => $pinfo ){
				reset( $watch_other );
				foreach( $watch_other as $w ){
					if( strpos($pl, $w) !== FALSE ){
						$stop = TRUE;
						$stop = $pl;
						break;
					}
				}
			}
		}

		if( $stop ){
//			echo "STOP AS I ENCOUNTERED '$stop'<br>";
			return;
		}

		$mypref = $table_prefix . $prefix . '_';
		$sql = "SHOW TABLES LIKE '$mypref%'";
		$results = $wpdb->get_results( $sql );
		foreach( $results as $index => $value ){
			foreach( $value as $tbl ){
				$sql = "DROP TABLE IF EXISTS $tbl";
				$e = $wpdb->query($sql);
			}
		}
	}

	public function admin_submenu()
	{
		if( $this->premium ){
			$this->premium->admin_submenu();
		}
	}

	public function deactivate_other( $plugins = array() )
	{
		$this->deactivate_other = $plugins;
		add_action( 'admin_init', array($this, 'run_deactivate'), 999 );
	}

	public function run_deactivate()
	{
		if( ! $this->deactivate_other )
			return;

		/* check if we have other activated */
		$deactivate = array();
		$plugins = get_option('active_plugins');
		foreach( $plugins as $pl ){
			reset( $this->deactivate_other );
			foreach( $this->deactivate_other as $d ){
				if( strpos($pl, $d) !== FALSE ){
					$deactivate[] = $pl;
				}
			}
		}

		foreach( $deactivate as $d ){
			if( is_plugin_active($d) ){
				deactivate_plugins($d);
			}
		}
	}

// ACTION AND VIEW FUNCTIONS
	public function admin_view()
	{
		echo $this->hcapp->display_view( $this->hcappview );
	}

	public function admin_menu()
	{
		$app_short_name = $this->app_short_name;
		$menu_slug = $this->slug;

		$default_title = isset($this->hcapp->app_config['nts_app_title']) ? $this->hcapp->app_config['nts_app_title'] : $this->app;
		$title = get_site_option( $menu_slug . '_menu_title', $default_title );
		if( ! strlen($title) ){
			$title = $default_title;
		}

		$page = add_menu_page( 
			$title,
			$title,
			'read',
			$menu_slug,
			array($this, 'admin_view'),
			// 'dashicons-admin-site',
			'dashicons-location-alt',
			30
		);
	}

	public function admin_init()
	{
		if( $this->premium ){
			$this->premium->admin_init();
		}

		if( $this->is_me_admin() ){
			$this->hcapp_start();
			$this->hcappview = $this->hcapp->handle_request();
		}
	}

	function is_me_admin()
	{
		global $post;
		if(
			( isset($post) && in_array($post->post_type, $this->types) )
			OR
			( isset($_REQUEST['post_type']) && in_array($_REQUEST['post_type'], $this->types) )
			){
			$return = TRUE;
		}
		else {
			$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
			if( isset($_REQUEST['page']) ){
				$page = sanitize_text_field($_REQUEST['page']);
			}
			if( $page && ($page == $this->slug) ){
				$return = TRUE;
			}
			else {
				$return = FALSE;
			}
		}
		return $return;
	}

// intercepts if in the front page our slug is given then it's ours
	public function check_intercept()
	{
		if( isset($_GET[$this->hcs]) && ( sanitize_text_field($_GET[$this->hcs]) == $this->slug) ){
			$this->intercept();
		}
		else {
			// continue init
			$this->_continue_init();
		}
	}

// intercepts if in the front page our slug is given then it's ours
	public function intercept()
	{
		$this->hcapp_start();
		$this->hcappview = $this->hcapp->handle_request();
		echo $this->hcapp->display_view( $this->hcappview );
		exit;
	}

// -----------------------------------------

	function strip_p($content)
	{
		// strip only within our output
		$start = stripos( $content, $this->wrap_output[0] );
		if( $start !== FALSE ){
			$end = stripos( $content, $this->wrap_output[1], $start );
			if( $end !== FALSE ){
				$my_content = substr( $content, $start, ($end - $start) );
				$my_content = str_replace( '</p>', '', $my_content );
				$my_content = str_replace( '<p>', '', $my_content );
				$my_content = str_replace( '<br />', '', $my_content );
				$my_content = str_replace( array("&#038;","&amp;"), "&", $my_content ); 

				$content = substr_replace( $content, $my_content, $start, ($end - $start) );
			}
		}

		return $content;
	}

	function localize_script( $id, $var, $options = array() )
	{
		$this->_localize_scripts[ $id ] = array( $var, $options );
	}

// normally overwritten by child classes
	function _install()
	{
	}

	function get_options( $defaults = array() )
	{
		$options = get_option($this->app);
		$return = array_merge( $defaults, $options );
		return $return;
	}

	function get_option( $key )
	{
		$options = $this->get_options();
		$return = isset($options[$key]) ? $options[$key] : NULL;
		return $return;
	}

	function save_option( $key, $value )
	{
		$options = $this->get_options();
		$options[$key] = $value;
		update_option($this->app, $options);
	}

	function check_post( $post_id )
	{
		global $post;
	/* Check if the current user has permission to edit the post. */
		if( $post ){
			$post_type = get_post_type_object( $post->post_type );
			if ( ! current_user_can($post_type->cap->edit_post, $post_id) )
				return FALSE;
		}
		return TRUE;
	}

	function make_input( $start, $props )
	{
		$display = array();
		$display[] = $start;

		if( ! isset($props['id']) ){
			$id = $props['name'];
			$id = str_replace( '[', '_', $id );
			$id = str_replace( ']', '', $id );
			$props['id'] = $id;
		}

		reset( $props );
		foreach( $props as $k => $v ){
			$display[] = $k . '="' . $v . '"';
		}
		$return = '<' . join( ' ', $display ) . '>';
		return $return;
	}

	public function dev_options()
	{
		if( $this->premium ){
			$this->premium->dev_options();
		}
	}
}
}
