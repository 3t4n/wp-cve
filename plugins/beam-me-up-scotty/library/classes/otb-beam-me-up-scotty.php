<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class OTB_Beam_Me_Up_Scotty {

	/**
	 * The single instance of OTB_Beam_Me_Up_Scotty.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The text domain.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_text_domain;
	
	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version 	= $version;
		$this->_text_domain	= 'beam-me-up-scotty';
		
		//update_option( 'otb_new_plugin_super-simple-slider_viewed', false );

		// Load plugin environment variables
		$this->file 	  = $file;
		$this->dir 		  = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'library';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/library/', $this->file ) ) );
		
		$this->tabs['general'] = array(
			'title' => __( 'Settings', 'beam-me-up-scotty' ),
			'type' => 'settings'
		);

		$this->tabs['themes'] = array(
			'title' => __( 'Our Themes', 'beam-me-up-scotty' ),
			'type' => 'content'
		);
		
		$this->themes = array (
			'namaha' => array (
				'slug' => 'namaha',
				'title' => 'Namaha',
				'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2021/04/namaha-imac.png',
				'coming_soon' => false,
				'new' => true
			),
  			'tropicana' => array (
				'slug' => 'tropicana',
				'title' => 'Tropicana',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2018/07/tropicana-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	),
  			'north-shore' => array (
				'slug' => 'north-shore',
				'title' => 'North Shore',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2018/03/north-shore-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	),
  			'citylogic' => array (
				'slug' => 'citylogic',
				'title' => 'CityLogic',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2017/08/citylogic-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	),
  			'shopstar' => array (
				'slug' => 'shopstar',
				'title' => 'Shopstar!',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2015/12/shopstar-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	),
  			'panoramic' => array (
				'slug' => 'panoramic',
				'title' => 'Panoramic',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2015/12/panoramic-imac.png',
    			'coming_soon' => false,
    			'new' => false
			)
		);
		
		// Check if there are any new themes
		foreach ($this->themes as $theme) {
			$theme = (object) $theme;
			$new = true === $theme->new;
			
			if ($new && !get_option( 'otb_new_theme_' .$theme->slug. '_viewed' ) ) {
				add_filter( 'add_menu_classes', array( $this, 'show_notification_bubble' ) );
				
				update_option( 'otb_new_theme', true );
				$this->tabs['themes']['highlighted'] = true;
			}
		}

		$this->tabs['plugins'] = array(
			'title' => __( 'Our Plugins', 'beam-me-up-scotty' ),
			'type' => 'content'
		);

		$this->plugins = array (
			'you-can-quote-me-on-that' => array (
				'slug' => 'you-can-quote-me-on-that',
				'title' => 'You can quote me on that',
				'tagline' => 'The quickest way to create testimonial sliders',
				'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2020/07/you-can-quote-me-on-that-imac.png',
				'coming_soon' => false,
				'new' => true
			),
  			'super-simple-slider' => array (
				'slug' => 'super-simple-slider',
				'title' => 'Super Simple Slider',
  				'tagline' => 'A lightweight, easy-to-use slider plugin',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2019/11/super-simple-slider-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	),
  			'same-but-different' => array (
				'slug' => 'same-but-different',
				'title' => 'Same but Different',
  				'tagline' => 'Display Posts Related by Taxonomy',
			    'thumbnail' => 'https://www.outtheboxthemes.com/wp-content/uploads/2018/07/same-but-different-imac.png',
    			'coming_soon' => false,
    			'new' => false
		  	)
		);
		
		// Check if there are any new plugins
		foreach ($this->plugins as $plugin) {
			$plugin = (object) $plugin;
			$new = true === $plugin->new;
			
			if ($new && !get_option( 'otb_new_plugin_' .$plugin->slug. '_viewed' ) ) {
				add_filter( 'add_menu_classes', array( $this, 'show_notification_bubble' ) );
				
				update_option( 'otb_new_plugin', true );
				$this->tabs['plugins']['highlighted'] = true;
			}
		}
		
		$this->script_suffix = defined( 'OTB_BEAM_ME_UP_SCOTTY_DEBUG' ) && OTB_BEAM_ME_UP_SCOTTY_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), 10, 1 );
		
		// Load front end JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new OTB_Beam_me_up_Scotty_Admin_API();
		}
				
		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
		
		// Add page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		
		// Add the back to top button below the footer 
		add_action('wp_footer', array( $this, 'render_back_to_top_button' ), 0 );		
		
		// Add a link to the plugin listing on the Installed Plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , array( $this, 'add_link_to_plugin_listing' ) );
	}
	
	/**
	 * Add page to admin menu
	 * @return void
	 */
	public function add_menu_item() {
		$badge = "";

		if ( get_option( 'otb_new_theme' ) || get_option( 'otb_new_plugin' ) ) {
			$badge = "<span class='update-plugins update-badge count-1' title='New theme'><span class='update-count'>1</span></span>";
		}

		$page = add_theme_page(
			__( 'Beam me up Scotty', 'beam-me-up-scotty' ),
			__( 'Beam me up Scotty' . $badge, 'beam-me-up-scotty' ),
			'edit_theme_options',
			$this->_text_domain . '_settings',
			array( $this, 'do_action'
		) );
		
		add_action( 'admin_print_styles-' . $page, array( $this->settings, 'settings_assets' ) );
	}
	
	public function show_notification_bubble( $menu ) {
		foreach( $menu as $menu_key => $menu_data ) {
			if ( $menu_data[0] == 'Appearance' ) {
				$menu[$menu_key][0] .= "<span class='update-plugins count-1'><span class='plugin-count'>1</span></span>";
			}
		}
				
		return $menu;
	}
	
	public function get_default_value( $id ) {
		$array = $this->settings->settings['general']['fields'];
	
		foreach ($array as $key => $val) {
			if ($val['id'] === $id) {
				return $val['default'];
			}
		}
		return null;
	}
	
	/**
	 * Render the back to top button in the footer
	 * @return void
	 */
	public function render_back_to_top_button() {
		include($this->assets_dir .'/template-parts/back-to-top-button.php');
	}	
	
	/**
	 * Add an upgrade link to installed plugins list
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_link_to_plugin_listing( $links ) {
		$settings_link = '<a href="themes.php?page=' . $this->_text_domain . '_settings">' . __( 'Settings', 'beam-me-up-scotty' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
		
	}
	
	public function do_action() {
		if ( isset( $_GET['action'] ) ) {
			$action = $_GET['action'];
		} else {
			$action = 'view-page';
		}
	
		switch ( $action ) {
			case 'view-page':
				$tab;
				
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$tab = $_GET['tab'];
				} else {
					reset($this->tabs);
					$tab = key($this->tabs);
				}
				
				$this->render_page( $tab );
	
				break;
		}
	}
	
	/**
	 * Load page content
	 * @return void
	 */
	public function render_page( $tab ) {
		// Build page HTML
		echo '<div class="wrap" id="' . $this->_text_domain . '_settings">' . "\n";
		
		include($this->assets_dir .'/template-parts/tabs.php');
		
		if ( $this->tabs[$tab]['type'] == 'settings' ) {
			include($this->assets_dir .'/template-parts/content-settings.php');
		} elseif ( $this->tabs[$tab]['type'] == 'content' ) {
			include($this->assets_dir .'/template-parts/content-'. $tab .'.php');
		}
	
		echo '</div>' . "\n";
	}
	
	/**
	 * Get the theme data
	 * @return void
	 */
	public function get_theme_data() {
		/*
		$response = wp_remote_get( 'https://www.outtheboxthemes.com/wp-json/wp/v2/themes/' );
		
		if( is_wp_error( $response ) ) {
			return;
		}
		
		$posts = json_decode( wp_remote_retrieve_body( $response ) );
		
		if( empty( $posts ) ) {
			return;
		} else {
			//update_option( 'otb_new_theme_citylogic_viewed', false );
			
			// Check if there are any new themes
			foreach ($posts as $theme) {
				$new = true === $theme->new;
				
				if ($new && !get_option( 'otb_new_theme_' .$theme->slug. '_viewed' ) ) {
					add_filter( 'add_menu_classes', array( $this, 'show_notification_bubble' ) );
					
					update_option( 'otb_new_theme', true );
					$this->tabs['themes']['highlighted'] = true;
				}
			}
			
			return $posts;
		}
		*/
	}
	
	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_admin_styles( $hook = '' ) {
		if ( $hook != 'appearance_page_beam-me-up-scotty_settings' ) {
			return;
		}
		
		wp_enqueue_style( $this->_text_domain .'-admin-style', esc_url( $this->assets_url ). 'css/admin-style.css', array(), $this->_version );
	}

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_admin_scripts( $hook = '' ) {
		if ( $hook != 'appearance_page_beam-me-up-scotty_settings' ) {
			return;
		}
		
		wp_enqueue_script( $this->_text_domain .'-admin-scripts-js', esc_url( $this->assets_url ). 'js/admin-scripts' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
	}

	/**
	 * Load front end CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	
	public function enqueue_front_end_styles( $hook = '' ) {
		wp_enqueue_style( $this->_text_domain .'-style', esc_url( $this->assets_url ). 'css/style.css', array(), $this->_version );

		include($this->assets_dir . '/includes/dynamic-css.php');
		
		wp_enqueue_style( $this->_text_domain .'-otb-font-awesome', esc_url( $this->assets_url ). 'fonts/otb-font-awesome/css/otb-font-awesome.css', array(), '4.7.0' );		
	}

	/**
	 * Load front end Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_front_end_scripts( $hook = '' ) {
		wp_enqueue_script( $this->_text_domain .'-scripts-js', esc_url( $this->assets_url ). 'js/scripts' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );

	    $beam_me_up_scotty_client_side_variables = array(
	    	'hide_delay' => intval( get_option( $this->settings->base . 'hide_delay', $this->get_default_value( 'hide_delay' ) ) )
	    );
		
		wp_localize_script( $this->_text_domain .'-scripts-js', 'otb_beam_me_up_scotty', $beam_me_up_scotty_client_side_variables );
	}

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'beam-me-up-scotty', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	}

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
	    $domain = 'beam-me-up-scotty';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	}
	
	
	public function hex_to_rgb( $hex ) {
	
		// Remove "#" if it was added
		$color = trim( $hex, '#' );
	
		// Return empty array if invalid value was sent
		if ( ! ( 3 === strlen( $color ) ) && ! ( 6 === strlen( $color ) ) ) {
			return array();
		}
	
		// If the color is three characters, convert it to six.
		if ( 3 === strlen( $color ) ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}
	
		// Get the red, green, and blue values
		$red   = hexdec( $color[0] . $color[1] );
		$green = hexdec( $color[2] . $color[3] );
		$blue  = hexdec( $color[4] . $color[5] );
	
		// Return the RGB colors as an array
		return array( 'r' => $red, 'g' => $green, 'b' => $blue );
	}
	
	/**
	 * Main OTB_Beam_me_up_Scotty Instance
	 *
	 * Ensures only one instance of OTB_Beam_me_up_Scotty is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see OTB_Beam_me_up_Scotty()
	 * @return Main OTB_Beam_me_up_Scotty instance
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		update_option( str_replace('-', '_', $this->_text_domain ) . '_version', $this->_version );
	}
	
}