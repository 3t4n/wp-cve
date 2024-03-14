<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Basic_Auth_Settings_Hooks
 */
class HTTP_Basic_Auth_Settings_Hooks {

	/**
	 * @var HTTP_Basic_Auth_Plugin
	 */
	private $plugin;

	/**
	 * Basic_Auth_Settings_Hooks constructor.
	 *
	 * @param HTTP_Basic_Auth_Plugin $plugin
	 */
 	public function __construct( HTTP_Basic_Auth_Plugin $plugin ) {
 		$this->plugin = $plugin;
 	}

	/**
	 *
	 */
 	public function hooks() {
 		$func = str_replace( '-', '_', $this->plugin->get_namespace() );

 		// settings menu
 		add_filter( $func . '_menu', array( $this, 'settings_menu' ) );
 		
 		// settings tabs
 		add_filter( $func . '_settings_tabs', array( $this, 'settings_tabs' ) );
 		
 		// unsavable tabs
 		add_filter( $func . '_unsavable_tabs', array( $this, 'unsavable_tabs' ) );
 			
 		// settings sections
 		add_filter( $func . '_registered_settings_sections', array( $this, 'registered_settings_sections' ) );
 			
 		// settings
 		add_filter( $func . '_registered_settings', array( $this, 'registered_settings' ) );
 			
 		// custom type hook
 		add_action( $func . '_thanks', array( $this, 'custom_type_thanks' ) );

	    add_action( $func . '_settings_tab_bottom_settings_options', array( $this, 'settings_tab_bottom_settings_options' ) );

 	}

	/**
	 *
	 */
 	public function settings_tab_bottom_settings_options() {
 		include( 'views/settings-script.php' );
    }

	/**
	 * @return string
	 */
 	public function get_text_domain() {
 		return $this->plugin->get_text_domain();
 	}

	/**
	 * @param array $menu
	 *
	 * @return array
	 */
 	public function settings_menu( array $menu ) {
 		$menu['type']       = 'submenu';
 		$menu['parent']     = 'options-general.php';
 		$menu['page_title'] = __( 'Basic Auth', $this->get_text_domain() );
 		$menu['show_title'] = true;
 		$menu['menu_title'] = __( 'Basic Auth', $this->get_text_domain() );
 		$menu['capability'] = 'manage_options';
 		$menu['icon']       = '';
 		$menu['position']   = null;
 		return $menu;
 	
 	}

	/**
	 * @return array
	 */
 	public function settings_tabs() {
 		$tabs = array(
		    'settings'      => __( 'Settings', 'basic-auth' ),
		    'contribution'  => __( 'Contribution', 'basic-auth' ),
		    'rating'        => __( 'Rating', 'basic-auth' ),
 		);
 		return $tabs;
 	}

	/**
	 * @return array
	 */
 	public function unsavable_tabs() {
 		$tabs = array(
 			'contribution'
 		);
 		return $tabs;
 	}
 	
 	/*
 	 *
 	 */
 	public function registered_settings_sections() {
 		$sections = array(
 				'settings' => array(
 						'options'   => __( 'Enable Basic HTTP Auth', 'basic-auth' ),
 				),
 				'contribution' => array(
		            'options'   => __( 'Enable Basic HTTP Auth', 'basic-auth' ),
	            ),
			    'rating' => array(
				    'options'   => __( 'Enable Basic HTTP Auth', 'basic-auth' ),
			    ),
 		);
 		return $sections;
 	}

	/**
	 * @param $settings
	 *
	 * @return array
	 */
 	public function registered_settings( $settings ) {
 		$plugin_settings = array(
		    'settings' => array(
			    'options' => array(
				    array(
					    'id'   => 'enabled_header',
					    'name' => __( 'Enabled', 'basic-auth' ),
					    'desc' => '',
					    'type' => 'header'
				    ),
				    array(
					    'id'   => 'enable_basic_auth',
					    'name' => __( 'HTTP Basic Auth', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'realm_header',
					    'name' => __( 'Enabled', 'basic-auth' ),
					    'desc' => '',
					    'type' => 'header'
				    ),
				    array(
					    'id'   => 'realm',
					    'name' => __( 'Message', 'basic-auth' ),
					    'std'  => __( 'Authorization required', 'basic-auth' ),
					    'desc' => sprintf(
					    	__( 'This message will be displayed in auth dialog. %sWill not works in Chrome!%s', 'basic-auth' ),
						   '<a target="_blank" href="https://bugs.chromium.org/p/chromium/issues/detail?id=544244#c32">',
						   '</a>'
					    ),
					    'type' => 'text'
				    ),
				    array(
					    'id'   => 'option_header',
					    'name' => __( 'Authentication data', 'basic-auth' ),
					    'desc' => '',
					    'type' => 'header'
				    ),
				    array(
					    'id'   => 'custom_login',
					    'name' => __( 'Custom login', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'custom_login_description',
					    'desc' => __( 'Enter below user login and password for custom login.', 'basic-auth' ),
					    'type' => 'descriptive_text'
				    ),
				    array(
					    'id'   => 'login',
					    'name' => __( 'Login', 'basic-auth' ),
					    'desc' => __( 'Enter user login for Basic Auth', 'basic-auth' ),
					    'type' => 'text'
				    ),
				    array(
					    'id'   => 'password',
					    'name' => __( 'Password', 'basic-auth' ),
					    'desc' => __( 'Enter password for Basic Auth', 'basic-auth' ),
					    'attributes'    => array( 'autocomplete' => 'new-password' ),
					    'type' => 'password'
				    ),
				    array(
					    'id'   => 'wordpress_login',
					    'name' => __( 'Wordpress user login', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'wordpress_login_description',
					    'desc' => __( 'When Wordpress user login is enabled you can Basic Auth with Wordpress user login and password.', 'basic-auth' ),
					    'type' => 'descriptive_text'
				    ),
				    array(
					    'id'   => 'areas_header',
					    'name' => __( 'Protected areas', 'basic-auth' ),
					    'desc' => '',
					    'type' => 'header'
				    ),
				    array(
					    'id'   => 'protect_admin',
					    'name' => __( 'Admin area', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'protect_login',
					    'name' => __( 'Login page', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'protect_frontend',
					    'name' => __( 'Frontend', 'basic-auth' ),
					    'desc' => __( 'Enable', 'basic-auth' ),
					    'type' => 'checkbox'
				    ),
				    array(
					    'id'   => 'protected_areas_description',
					    'desc' => sprintf(__( 'Admin area is: %sLogin page is: %s', 'basic-auth' ), admin_url() . '<br/>', wp_login_url() ),
					    'type' => 'descriptive_text'
				    ),
			    ),
		    ),
		    'contribution' => array(
			    'options' => array(
				    array(
					    'id'   => 'protected_areas_description',
					    'desc' => sprintf(__( 'Everyone can contribute this plugin here: %s', 'basic-auth' ), '<a target="_blank" href="https://gitlab.com/grola/basic-auth">https://gitlab.com/grola/basic-auth</a>' ),
					    'type' => 'descriptive_text'
				    ),
			    ),
		    ),
		    'rating' => array(
			    'options' => array(
				    array(
					    'id'   => 'rating_description',
					    'desc' => sprintf(__( 'If you like this plugin, rate it for 5 stars :) %sClick here%s', 'basic-auth' ), '<a target="_blank" href="https://wordpress.org/support/plugin/basic-auth/reviews/#new-post">', '</a>' ),
					    'type' => 'descriptive_text'
				    ),
			    ),
		    ),
 		);
		return array_merge( $settings, $plugin_settings );
 	}
 	
}

