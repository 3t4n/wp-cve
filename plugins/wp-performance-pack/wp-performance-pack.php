<?php
/*
	Plugin Name: WP Performance Pack
	Plugin URI: http://wordpress.org/plugins/wp-performance-pack
	Description: Performance optimizations for WordPress. Improve localization performance and image handling, serve images through CDN.  
	Version: 2.5.3
	Text Domain: wp-performance-pack
	Author: Bj&ouml;rn Ahrens
	Author URI: http://www.bjoernahrens.de
	License: GPL2 or later
*/ 

/*
	Copyright 2014-2022 Björn Ahrens (email : bjoern@ahrens.net) 
	This program is free software; you can redistribute it and/or modify 
	it under the terms of the GNU General Public License, version 2 or 
	later, as published by the Free Software Foundation. This program is 
	distributed in the hope that it will be useful, but WITHOUT ANY 
	WARRANTY; without even the implied warranty of MERCHANTABILITY or 
	FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
	for more details. You should have received a copy of the GNU General
	Public License along with this program; if not, write to the Free 
	Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, 
	MA 02110-1301 USA 
*/

/**
 * Base class for all WPPP modules
 * @abstract
 */
abstract class WPPP_Module {
	protected $wppp = NULL;

	/**
	 * Test is the module is available, i.e. are all requirements met in order for the 
	 * module to get activated
	 *
	 * @return bool
	 */
	 public function is_available() { return true; }

	/**
	 * Validate options. Processed options should get unset in input
	 *
	 * @param array	input	Contains actual input values
	 * @param array	output	Contains the output so far
	 * @param array	default	Default values for this module
	 */
	public function validate_options( &$input, $output, $default ) {
		foreach ( $default as $optname => $opt ) {
			$output[ $optname ] = $opt[ 'default' ];
			if ( isset( $input[ $optname ] ) ) {
				if ( $opt[ 'type' ] === 'bool' ) {
					$output[ $optname ] = ( $input[ $optname ] == 'true' ? true : false );
				} elseif ( $opt[ 'type' ] === 'enum' ) {
					$val = trim( sanitize_text_field( $input[ $optname ] ) );
					$output[ $optname ] = ( in_array( $val, $opt[ 'values' ] ) ? $val : $opt[ 'default' ] );
				} elseif ( $opt[ 'type' ] === 'string' ) {
					$output[ $optname ] = trim( sanitize_text_field( $input[ $optname ] ) );
				} elseif ( $opt[ 'type' ] === 'int' ) {
					$val = ( is_numeric( $input[ $optname ] ) ? $input[ $optname ] : $opt[ 'default' ] );
					if ( isset( $opt[ 'min' ] ) && ( $val < $opt[ 'min' ] ) )
						$val = $opt[ 'min' ];
					if ( isset( $opt[ 'max' ] ) && ( $val > $opt[ 'max' ] ) )
						$val = $opt[ 'max' ];
					$output[ $optname ] = $val;
				} elseif ( $opt[ 'type' ] === 'array' ) {
					$val = array();
					foreach ( $input[ $optname ] as $item ) {
						$s = trim( sanitize_text_field( $item ) );
						if ( !empty( $s ) )
							$val[] = $s;
					}
					$output[ $optname ] = $val;
				}
				unset( $input[ $optname ] );
			} else if ( $opt[ 'type' ] === 'bool' ) {
				$output[ $optname ] = false;
			}
		}
		return $output;
	}

	public function __construct( $parent ) { $this->wppp = $parent; }

	/**
	 * Override for initializations at WPPP construction
	 */
	public function early_init() {}

	/**
	 * Override for initializations at "init" action
	 */
	public function init() {}

	/**
	 * Override for initializations at "admin_init" action
	 */
	public function admin_init() {}

	// admin render functions
	public function load_renderer() {}

	public function add_help_tab( $renderer ) {
		$this->load_renderer( $renderer->view );
		if ( $this->renderer !== NULL ) {
			$this->renderer->add_help_tab();
		}
	}
	public function render_options( $renderer ) {
		$this->load_renderer( $renderer->view );
		if ( $this->renderer !== NULL ) {
			$this->renderer->render_options( $renderer );
		}
	}
}

class WP_Performance_Pack {
	/**
	 * WPPP cache group name = wppp + version of last change to cache. This way no cache 
	 * conflicts occur while old cache entries just expire.
	 *
	 * @const string
	 */
	const cache_group = 'wppp1.0';

	/**
	 * WPPP version
	 *
	 * @const string
	 */
	const wppp_version = '2.5.3';

	/**
	 * Name for WPPP options
	 *
	 * @const string
	 */
	const wppp_options_name = 'wppp_option';

	public static $options_default = array(
		'debug' => false,
	);

	public static $modinfo = array(
		'cdn_support' => array(
			'cdn'				=> array( 'default' => false, 'type' => 'enum', 'values' => array( false, 'coralcdn', 'maxcdn', 'customcdn' ) ),
			'cdnurl'			=> array( 'default' => '', 'type' => 'string' ),
			'cdn_images'		=> array( 'default' => 'both', 'type' => 'enum', 'values' => array( 'both', 'front', 'back' ) ),
			'dyn_links' 		=> array( 'default' => false, 'type' => 'bool' ),
			'dyn_links_subst'	=> array( 'default' => false, 'type' => 'bool' ),
		),
		'disable_widgets' => array(
			'disabled_widgets'	=> array( 'default' => array(), 'type' => 'array' ), 
		),
		'dynamic_images' => array( 
			'dynamic_images'				=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_nosave'			=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_thumbfolder'	=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_cache'			=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_rthook'			=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_rthook_force'	=> array( 'default' => false, 'type' => 'bool' ),
			'dynamic_images_exif_thumbs'	=> array( 'default' => false, 'type' => 'bool' ),
			'dynimg_quality'				=> array( 'default' => 80, 'type' => 'int', 'min' => 10, 'max' => 100 ),
			'dynimg_serve_method'			=> array( 'default' => 'wordpress', 'type' => 'enum', 'values' => array( 'short_init', 'use_themes', 'wordpress' ) ),
			'exif_width'					=> array( 'default' => 320, 'type' => 'int' ),
			'exif_height'					=> array( 'default' => 320, 'type' => 'int' ),
			'rewrite_inherit'				=> array( 'default' => false, 'type' => 'bool' ),
		),
		'l10n_improvements' => array(
			'use_mo_dynamic'				=> array( 'default' => true, 'type' => 'bool' ),
			'use_jit_localize'				=> array( 'default' => false, 'type' => 'bool' ),
			'disable_backend_translation'	=> array( 'default' => false, 'type' => 'bool' ),
			'dbt_allow_user_override'		=> array( 'default' => false, 'type' => 'bool' ),
			'dbt_user_default_translated'	=> array( 'default' => false, 'type' => 'bool' ),
			'use_native_gettext'			=> array( 'default' => false, 'type' => 'bool' ),
			'mo_caching'					=> array( 'default' => false, 'type' => 'bool' ),
		),
		'wpfeatures' => array(
			'comments'				=> array( 'default' => true, 'type' => 'bool' ),
			'emojis'				=> array( 'default' => true, 'type' => 'bool' ),
			'editlock'				=> array( 'default' => true, 'type' => 'bool' ),
			'heartbeat_location'	=> array( 'default' => 'default', 'type' => 'enum', 'values' => array( 'default', 'disable_all', 'disable_dashboard', 'allow_post' ) ),
			'heartbeat_frequency'	=> array( 'default' => 'default', 'type' => 'enum', 'values' => array( 'default', '10', '15', '20', '25', '30', '35', '40', '50', '60' ) ),
			'rsd_link'				=> array( 'default' => true, 'type' => 'bool' ),
			'wlwmanifest_link'		=> array( 'default' => true, 'type' => 'bool' ),
			'wp_generator'			=> array( 'default' => true, 'type' => 'bool' ),
			'wp_shortlink_wp_head'	=> array( 'default' => true, 'type' => 'bool' ),
			'feed_links'			=> array( 'default' => true, 'type' => 'bool' ),
			'feed_links_extra'		=> array( 'default' => true, 'type' => 'bool' ),
			'adjacent_posts_links'	=> array( 'default' => true, 'type' => 'bool' ),
			'big_image_scaling'		=> array( 'default' => true, 'type' => 'bool' ),
			'jquery_migrate'		=> array( 'default' => true, 'type' => 'bool' ),
		),
	);

	public $modules = array();

	private $admin_opts = NULL;
	private $late_updates = array();
	public $dbg_textdomains = array ();
	public $is_network = false;
	public $options = NULL;

	function get_options_default () {
		$def_opts = WP_Performance_Pack::$options_default;
		foreach ( static::$modinfo as $modname => $opts ) {
			$def_opts['mod_' . $modname] = false;
			foreach ( $opts as $key => $value ) {
				$def_opts[ $key ] = $value[ 'default' ];
			}
		}
		return $def_opts;
	}

	function load_options () {
		if ( $this->options == NULL ) {
			$this->options = $this->get_option( self::wppp_options_name );
			$def_opts = $this->get_options_default();
			if ( !is_array( $this->options ) )
				$this->options = array();
			
			foreach ( $def_opts as $key => $value ) {
				if ( !isset( $this->options[$key] ) ) {
					$this->options[$key] = $def_opts[$key];
				}
			}
		}
	}

	function get_option( $option_name ) {
		if ( $this->is_network ) {
			return get_site_option( $option_name );
		} else {
			return get_option( $option_name );
		}
	}

	function update_option( $option_name, $data ) {
		if ( $this->is_network ) {
			return update_site_option( $option_name, $data );
		} else {
			return update_option( $option_name, $data );
		}
	}

	public function __construct( $fullinit = true ) {	
		spl_autoload_register( array( $this, 'wppp_autoloader' ) );

		// initialize fields
		global $wp_version;

		if ( $fullinit ) {
			if ( is_multisite() ) {
				if ( !function_exists( 'is_plugin_active_for_network' ) ) {
					require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				}
				$this->is_network = is_plugin_active_for_network( plugin_basename( __FILE__ ) );
			}
			$this->check_update();
			$this->load_options();

			// add actions
			add_action( 'init', array ( $this, 'init' ) );
			add_action( 'admin_init', array ( $this, 'admin_init' ) );
			// check that WPPP is loaded as first plugin
			// do this on wp_loaded after wordpress is fully initialized and all other plugins are loaded
			// so it will load first on next load. otherwise this could conflict with another plugin resorting the
			// plugin order
			// TODO: Maybe add this to settings instead of doing it every time
			add_action( 'wp_loaded', array( $this, 'plugin_load_first' ) );

			// activate and initialize modules
			foreach ( static::$modinfo as $modname => $info ) {
				if ( $this->options[ 'mod_' . $modname ] === true ) {
					$modclass = 'WPPP_' . $modname;
					$this->modules[ $modname ] = new $modclass( $this );
					$this->modules[ $modname ]->early_init();
				}
			}

			if ( $this->options['debug'] )
				add_filter( 'debug_bar_panels', array ( $this, 'add_debug_bar_wppp' ), 10 );
		} else {
			$this->is_network = is_multisite(); // TODO: add is_plugin_active... - removed for now, because fullinit is only set to false from serve-dynamic-images which should not be used when multisite is active (and plugin_basename would cause problems)
			$this->load_options();
		}
	}

	public function init() {
		// execute "late" updates
		foreach ( $this->late_updates as $updatefunc ) {
			call_user_func( $updatefunc );
		}

		if ( is_admin() ) {
			// admin pages
			if ( current_user_can( 'manage_options' ) ) {
				include( sprintf( "%s/admin/class.wppp-admin.php", dirname( __FILE__ ) ) );
				$this->admin_opts = new WPPP_Admin ($this);
			}
		}

		foreach ( $this->modules as $module ) {
			$module->init();
		}
	}

	public function admin_init () {
		foreach ( $this->modules as $module ) {
			$module->admin_init();
		}
	}

	function add_debug_bar_wppp ( $panels ) {
		include( sprintf( "%s/admin/class.debug-bar-wppp.php", dirname( __FILE__ ) ) );
		$panel = new Debug_Bar_WPPP ();
		$panel->textdomains = &$this->dbg_textdomains;
		$panel->plugin_base = plugin_basename( __FILE__ );
		array_push( $panels, $panel );
		return $panels;
	}

	/**
	 * Make sure WPPP is loaded as first plugin. Important for e.g. usage of dynamic MOs with all text domains.
	 */
	public function plugin_load_first() {
		$path = plugin_basename( __FILE__ );

		if ( $plugins = get_option( 'active_plugins' ) ) {
			if ( 0 != ( $key = array_search( $path, $plugins ) ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );
				update_option( 'active_plugins', $plugins );
			}
		}
	}

	public function activate() { 
		// doesn't fire on update, only on manual activation through admin
		// is called after check_update (which is called at construction)

		if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) { 
			deactivate_plugins( basename(__FILE__) ); // Deactivate self - does that really work at this stage?
			wp_die( __( 'WP Performance pack requries PHP version >= 5.3', 'wp-performance-pack' ) );
		}

		// if is active in network of multisite
		if ( is_multisite() && isset( $_GET['networkwide'] ) && 1 == $_GET['networkwide'] ) {
			add_site_option( self::wppp_options_name, self::$options_default );
			add_site_option( 'wppp_version', self::wppp_version );
		} else {
			add_option( self::wppp_options_name, self::$options_default );
			add_option( 'wppp_version', self::wppp_version );
		}
		$this->plugin_load_first();
	}

	public function deactivate() {
		if ( $this->options['dynamic_images'] ) {
			// Delete rewrite rules from htaccess
			WPPP_Dynamic_Images::static_disable_rewrite_rules();
		}

		if ( is_multisite() && isset( $_GET['networkwide'] ) && 1 == $_GET['networkwide'] ) {
			delete_site_option( self::wppp_options_name );
		} else {
			delete_option( self::wppp_options_name );
		}
		delete_option( 'wppp_dynimg_sizes' );
		delete_option( 'wppp_version' );
		
		// restore static links
		WPPP_CDN_Support::restore_static_links();
	}

	function wppp_autoloader ( $class ) {
		$class = strtolower( $class );
		if ( strncmp( $class, 'wppp_', 5 ) === 0 || $class == 'labelsobject' ) {
			if ( file_exists( sprintf( "%s/classes/class.$class.php", dirname( __FILE__ ) ) ) ) {
				include( sprintf( "%s/classes/class.$class.php", dirname( __FILE__ ) ) );
				return;
			} else if (  file_exists( sprintf( "%s/admin/class.$class.php", dirname( __FILE__ ) ) ) ) {
				include( sprintf( "%s/admin/class.$class.php", dirname( __FILE__ ) ) );
				return;
			}
			// search module folders
			foreach ( static::$modinfo as $modname => $module ) {
				if ( file_exists( sprintf( "%s/modules/$modname/class.$class.php", dirname( __FILE__ ) ) ) ) {
					include( sprintf( "%s/modules/$modname/class.$class.php", dirname( __FILE__ ) ) );
					return;
				}
			}
		}
	}

	function check_update () {
		if ( ! $opts = $this->get_option( self::wppp_options_name ) ) {
			// if get_option fails, this is activation, so no update necessary
			return;
		}

		$installed = $this->get_option( 'wppp_version' );
		if ( version_compare( $installed, self::wppp_version, '!=' ) ) {
			// if installed version differs from version saved in options then do update
			// it is assumed that the options-version is always less or equal to the installed version
			if ( $installed === false || empty( $installed ) ) {
				// pre 1.6.3 version didn't have the wppp_version option

				// serve-dynamic-images.php location has changed, so update rewrite-rules
				if ( isset( $opts['dynamic_images'] ) && $opts['dynamic_images'] ) {
					$this->late_updates[] = array( $this, 'update_163' );
				}
				$installed = '1.6.3';
			}

			if ( version_compare( $installed, '1.9' ) == -1 ) {
				// new in version 1.9: url substitution when using dynamic links is optional
				// if dynamic links were used, substitution was active, so keep it that way
				if ( isset( $opts['dyn_links'] ) && $opts['dyn_links'] ) {
					$opts['dyn_links_subst'] = true;
					$this->update_option( self::wppp_options_name, $opts );
				}
				// serve-dynamic-images.php location has changed, so update rewrite-rules
				if ( isset( $opts['dynamic_images'] ) && $opts['dynamic_images'] ) {
					$this->late_updates[] = array( $this, 'update_163' );
				}
				$installed = '1.9';
			}

			$this->update_option ( 'wppp_version', self::wppp_version );
		}
	}
	
	function update_163 () {
		//TODO: if module then flush //WPPP_Dynamic_Images_Base::flush_rewrite_rules( true );
	}
} 

// instantiate the plugin
global $wp_performance_pack;
$wp_performance_pack = new WP_Performance_Pack( !defined( 'WPPP_SERVING_IMAGE' ) );
if ( !defined( 'WPPP_SERVING_IMAGE' ) ) {
	register_activation_hook( __FILE__, array( $wp_performance_pack, 'activate' ) );
	register_deactivation_hook( __FILE__, array( $wp_performance_pack, 'deactivate' ) );
}