<?php
/**
 * @package Admin
 * Main class: core.php
 */

/**
 * Core Classfor catchwebtools
 */
class catchwebtools {

	 /**
	 * catchwebtools default constructor
	 * action hooks enabled to add Catch Web Tools to menu
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_settings_menu' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		add_action( 'customize_register', array( $this, 'catchwebtools_customizer_custom_css' ) );

		add_action( 'customize_register', array( $this, 'register_additional_javascript' ) );

		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2);
	}

	 /**
	 * catchwebtools: add_plugin_settings_menu
	 * add Catch Web Tools to menu
	 */
	function add_plugin_settings_menu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page( __('Dashboard', 'catch-web-tools' ), __( 'Catch Web Tools', 'catch-web-tools' ), 'manage_options', 'catch-web-tools', array( $this, 'catch_web_tools_settings_page' ), CATCHWEBTOOLS_URL . 'images/catch-themes-themes-option.png', '99.01564' );

		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_submenu_page( 'catch-web-tools', __( 'Dashboard', 'catch-web-tools' ), __( 'Dashboard', 'catch-web-tools' ), 'manage_options', 'catch-web-tools', array( $this, 'catch_web_tools_settings_page' ) );

		add_submenu_page( 'catch-web-tools', __( 'Webmasters', 'catch-web-tools' ), __( 'Webmasters', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-webmasters', array( $this, 'catch_web_tools_webmaster_page' ) );

		add_submenu_page( 'catch-web-tools', __( 'Catch IDs', 'catch-web-tools' ), __( 'Catch IDs', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-catch-ids', array( $this, 'catch_web_tools_catch_ids_page' ) );

		/**
		 * Do not show Custom CSS option from WordPress 4.7 onwards
		 */
		if ( !function_exists( 'wp_update_custom_css_post' ) ) {
			add_submenu_page( 'catch-web-tools', __( 'Custom CSS', 'catch-web-tools' ), __( 'Custom CSS', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-custom-css', array( $this, 'catch_web_tools_custom_css_page' ) );
		}

		add_submenu_page( 'catch-web-tools', __( 'Social Icons', 'catch-web-tools' ), __( 'Social Icons', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-social-icons', array( $this, 'catch_web_tools_social_icons_page' ) );

		add_submenu_page( 'catch-web-tools', __( 'Open Graph', 'catch-web-tools' ), __( 'Open Graph', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-opengraph', array( $this, 'catch_web_tools_opengraph_page' ) );

		add_submenu_page( 'catch-web-tools', __( 'SEO', 'catch-web-tools' ), __( 'SEO', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-seo', array( $this, 'catch_web_tools_seo_page' ) );

		add_submenu_page( 'catch-web-tools', __( 'To Top', 'catch-web-tools' ), __( 'To Top', 'catch-web-tools' ), 'manage_options', 'catch-web-tools-to-top', array( $this, 'catch_web_tools_to_top_page' ) );
	}

	/**
	 * catchwebtools: catch_web_tools_settings_page
	 * Catch Web Tools Setting function
	 */
	function catch_web_tools_settings_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/dashboard.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_webmaster_page
	 * Catch Web Tools Webmaster Display Function
	 */
	function catch_web_tools_webmaster_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/webmaster.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_catch_ids_page
	 * Catch Web Tools Catch_IDs Display Function
	 */
	function catch_web_tools_catch_ids_page() {

		if ( !current_user_can( 'edit_posts' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/catch-ids.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_opengraph_page
	 * Catch Web Tools Webmaster Display Function
	 */
	function catch_web_tools_opengraph_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/opengraph.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_seo_page
	 * Catch Web Tools SEO Display Function
	 */
	function catch_web_tools_seo_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/seo.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_seo_page
	 * Catch Web Tools To Top Display Function
	 */
	function catch_web_tools_to_top_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		//include( CATCHWEBTOOLS_PATH . '/to-top/to-top.php' );
		include( CATCHWEBTOOLS_PATH . '/admin/modules/to-top.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_social_icons_page
	 * Catch Web Tools Social Icons Display Function
	 */
	function catch_web_tools_social_icons_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/social-icons.php' );
	}

	/**
	 * catchwebtools: catch_web_tools_custom_css_page
	 * Catch Web Tools Custom CSS Display Function
	 */
	function catch_web_tools_custom_css_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( CATCHWEBTOOLS_PATH . '/admin/modules/custom-css.php' );
	}

	/**
	 * catchwebtools: register_settings
	 * Catch Web Tools Register Settings
	 */
	function register_settings() {
		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'webmaster-tools-group',
			'catchwebtools_webmaster',
			array( $this, 'catchwebtools_webmaster_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'opengraph-settings-group',
			'catchwebtools_opengraph',
			array( $this, 'catchwebtools_opengraph_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'custom-css-settings-group',
			'catchwebtools_custom_css',
			array( $this, 'catchwebtools_custom_css_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'seo-settings-group',
			'catchwebtools_seo' ,
			array( $this, 'catchwebtools_seo_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'social-icons-group',
			'catchwebtools_social',
			array( $this, 'catchwebtools_social_icons_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'catchids-settings-group',
			'catchwebtools_catchids' ,
			array( $this, 'catchwebtools_catchids_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'catchupdater-settings-group',
			'catchwebtools_catch_updater' ,
			array( $this, 'catchwebtools_catch_updater_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'to-top-settings-group',
			'catchwebtools_to_top_options' ,
			array( $this, 'catchwebtools_to_top_sanitize_callback' )
		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting(
			'big-image-size-threshold-settings-group',
			'catchwebtools_big_image_size_threshold' ,
			array( $this, 'catchwebtools_big_iamge_size_threshold_sanitize_callback' )
		);
	}

	function add_plugin_meta_links( $meta_fields, $file ){

		if( CATCHWEBTOOLS_BASENAME == $file ) {

			$meta_fields[] = "<a href='https://catchplugins.com/support-forum/forum/catch-web-tools/' target='_blank'>Support Forum</a>";
			$meta_fields[] = "<a href='https://wordpress.org/support/plugin/catch-web-tools/reviews#new-post' target='_blank' title='Rate'>
			        <i class='ct-rate-stars'>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "</i></a>";

			$stars_color = "#ffb900";

			echo "<style>"
				. ".ct-rate-stars{display:inline-block;color:" . $stars_color . ";position:relative;top:3px;}"
				. ".ct-rate-stars svg{fill:" . $stars_color . ";}"
				. ".ct-rate-stars svg:hover{fill:" . $stars_color . "}"
				. ".ct-rate-stars svg:hover ~ svg{fill:none;}"
				. "</style>";
		}

		return $meta_fields;
	}


	/**
	 * catchwebtools: catchwebtools_webmaster_sanitize_callback
	 * Webmaster Sanitization function callback
	 */
	function catchwebtools_webmaster_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		if( !empty( $input['header'] ) ) {
			$input['header'] = wp_kses_stripslashes( force_balance_tags( $input['header'] ) );
		}

		if( !empty( $input['footer'] ) ) {
			$input['footer'] = wp_kses_stripslashes( force_balance_tags( $input['footer'] ) );
		}

		if( !empty( $input['google-site-verification'] ) ) {
			$input['google-site-verification']	= sanitize_text_field( $input['google-site-verification'] );
		}

		if( !empty( $input['msvalidate.01'] ) ) {
			$input['msvalidate.01'] 			= sanitize_text_field( $input['msvalidate.01'] );
		}

		if( !empty( $input['alexaVerifyID'] ) ) {
			$input['alexaVerifyID']				= sanitize_text_field( $input['alexaVerifyID'] );
		}

		if( !empty( $input['feed_uri'] ) ) {
			$input['feed_uri']					= sanitize_text_field( $input['feed_uri'] );
		}

		if( !empty( $input['comments_feed_uri'] ) ) {
			$input['comments_feed_uri']			= sanitize_text_field( $input['comments_feed_uri'] );
		}

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_opengraph_sanitize_callback
	 * Open Graph Sanitization function callback
	 */
	function catchwebtools_opengraph_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		if( !empty( $input['og:image'] ) ) {
			$input['og:image']			=	esc_url_raw ( $input['og:image'] );
		}

		if( !empty( $input['og:default_image'] ) ) {
			$input['og:default_image']	=	esc_url_raw ( $input['og:default_image'] );
		}

		foreach ( $input as $key => $value ) {
			if( !empty( $input[ $key ] ) ) {
				$input[ $key ]	=	 sanitize_text_field( $value );
			}
		}

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_custom_css_sanitize_callback
	 * Custom Css Sanitization function callback
	 */
	function catchwebtools_custom_css_sanitize_callback( $input ){
		if( !empty( $input ) ) {
			$input	=	 wp_strip_all_tags( $input );
		}

		delete_transient( 'catchwebtools_custom_css' );

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_seo_sanitize_callback
	 * Seo Sanitization function callback
	 */
	function catchwebtools_seo_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		$input['title'] = ( $input['title'] != '' ) ? sanitize_text_field( $input['title'] ) : get_bloginfo ( 'name' );

		$input['description'] =	( $input['description'] != '' ) ? sanitize_text_field( $input['description'] ) : get_bloginfo ( 'description' );

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_social_icons_sanitize_callback
	 * Social Icons Sanitization function callback
	 */
	function catchwebtools_social_icons_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		if( !empty( $input['social_icon_size'] ) ) {
			$input['social_icon_size']	=	intval ( $input['social_icon_size'] );
		}

		if( !empty( $input['social_icon_color'] ) ) {
			 $input['social_icon_color'] = (empty( $input['social_icon_color']) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|',  $input['social_icon_color'])) ? '' :  $input['social_icon_color'];
		}

		if( !empty( $input['social_icon__brand_color'] ) ) {
			 $input['social_icon__brand_color'] = (empty( $input['social_icon__brand_color']) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|',  $input['social_icon__brand_color'])) ? '' :  $input['social_icon__brand_color'];
		}

		$non_icon_setting = array(
									'status',
									'social_icon_size',
									'social_icon_color',
									'social_icon_hover_color',
									'social_icon_brand_color',
								);

		foreach ( $input as $key => $value ) {
			if( !in_array( $key, $non_icon_setting ) ) {
				if ( 'mail' == $key  ) {
					$input[ $key ] = sanitize_email( $value );
				}
				else if ( 'skype' == $key  ) {
					$input[ $key ] = esc_attr( $value );
				}
				else if ( 'handset' == $key || 'phone' == $key  ) {
					$input[ $key ]	= sanitize_text_field( $value );
				}
				else {
					$input[ $key ]	= esc_url_raw( $value );
				}
			}
		}

		delete_transient( 'catchwebtools_social_display' );

		delete_transient( 'catchwebtools_custom_css' );

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_catchids_sanitize_callback
	 * Catch Ids Sanitization function callback
	 */
	function catchwebtools_catchids_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_catch_updater_sanitize_callback
	 * Catch Ids Sanitization function callback
	 */
	function catchwebtools_catch_updater_sanitize_callback( $input ){
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_catch_updater_sanitize_callback
	 * Catch Ids Sanitization function callback
	 */
	function catchwebtools_big_image_size_threshold_sanitize_callback( $input ){
		echo '<pre>'; 
		print_r($input); 
		echo '</pre>'; 
		die();
		$input['status'] = ( isset( $input['status'] ) && '1' == $input['status'] ) ? '1' : '0';
		$input['max'] = ( isset( $input['max'] ) && '2560' >= $input['max'] ) ? $input['max'] : '2560';

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_to_top_sanitize_callback
	 * Catch Ids Sanitization function callback
	 */
	function catchwebtools_to_top_sanitize_callback( $input ){
		if ( isset( $input['reset'] ) && $input['reset'] ) {
			//If reset, restore defaults
			return catchwebtools_to_top_default_options();
		}

		//Basic Settings
		if( isset( $input['status'] ) ){
			$input['status']        = absint( $input['status'] );
		}

		if( isset( $input['scroll_offset'] ) ){
			$input['scroll_offset'] = absint( $input['scroll_offset'] );
		}

		if( isset( $input['style'] ) ){
			$input['style']         = sanitize_key( $input['style'] );
		}

		//Icon Settings
		if( isset( $input['icon_opacity'] ) ){
			$input['icon_opacity']  = absint( $input['icon_opacity'] );
		}

		if( isset( $input['icon_color'] ) ){
			$input['icon_color'] 	= (empty( $input['icon_color']) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|',  $input['icon_color'])) ? '' :  $input['icon_color'];
		}

		if( isset( $input['icon_bg_color'] ) ){
			$input['icon_bg_color'] = (empty( $input['icon_bg_color']) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|',  $input['icon_bg_color'])) ? '' :  $input['icon_bg_color'];
		}

		if( isset( $input['icon_size'] ) ){
			$input['icon_size']     = absint( $input['icon_size'] );
		}

		if( isset( $input['border_radius'] ) ){
			$input['border_radius'] = absint( $input['border_radius'] );
		}

		//Image Settings
		if( isset( $input['image'] ) ){
			$input['image']         = esc_url_raw( $input['image'] );
		}

		if( isset( $input['image_width'] ) ){
			$input['image_width']   = absint( $input['image_width'] );
		}

		if( isset( $input['image_alt'] ) ){
			$input['image_alt']     = sanitize_text_field( $input['image_alt'] );
		}

		//Advanced Settings
		if( isset( $input['location'] ) ){
			$input['location']      = sanitize_key( $input['location'] );
		}

		if( isset( $input['margin_x'] ) ){
			$input['margin_x']      = absint( $input['margin_x'] );
		}

		if( isset( $input['margin_y'] ) ){
			$input['margin_y']      = absint( $input['margin_y'] );
		}

		if( isset( $input['show_on_admin'] ) ){
			$input['show_on_admin'] = ( ( isset( $input['show_on_admin'] ) && true == $input['show_on_admin'] ) ? true : false );
		}

		if( isset( $input['enable_autohide'] ) ){
			$input['enable_autohide'] = ( ( isset( $input['enable_autohide'] ) && true == $input['enable_autohide'] ) ? true : false );
		}

		if( isset( $input['autohide_time'] ) ){
			$input['autohide_time']   = absint( $input['autohide_time'] );
		}

		if( isset( $input['enable_hide_small_device'] ) ){
			$input['enable_hide_small_device']= ( ( isset( $input['enable_hide_small_device'] ) && true == $input['enable_hide_small_device'] ) ? true : false );
		}

		if( isset( $input['small_device_max_width'] ) ){
			$input['small_device_max_width']  = absint( $input['small_device_max_width'] );
		}

		return $input;
	}

	/**
	 * catchwebtools: catchwebtools_customizer_custom_css
	 * Add Custom CSS Option to Customizer
	 */
	function catchwebtools_customizer_custom_css( $wp_customize ){
		$wp_customize->add_panel( 'catchwebtools_options', array(
			'description'	=> esc_html__( '' ),
			'priority'		=> 1,
			'title'    		=> esc_html__( 'Catch Web Tools Plugin Options', 'catch-web-tools' ),
		) );
		/**
         * Do not show Custom CSS option from WordPress 4.7 onwards
         */
        if ( !function_exists( 'wp_update_custom_css_post' ) ) {
			$wp_customize->add_section( 'catchwebtools_custom_css', array(
				'description'	=> esc_html__( 'You can just add your Custom CSS and save, it will show up in the frontend head section. Leave it blank if it is not needed.', 'catch-web-tools' ),
				'panel'			=> 'catchwebtools_options',
				'priority'		=> 1,
				'title'    		=> esc_html__( 'Custom CSS', 'catch-web-tools' ),
			) );

			$wp_customize->add_setting( 'catchwebtools_custom_css', array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'wp_strip_all_tags',
				'transport'         => 'postMessage',
				'type'              => 'option'
			) );

			$wp_customize->add_control( 'catchwebtools_custom_css', array(
				'label'    => esc_html__( 'Custom CSS', 'catch-web-tools' ),
				'section'  => 'catchwebtools_custom_css',
				'settings' => 'catchwebtools_custom_css',
				'type'     => 'textarea',
			) );
		}
	}

	function register_additional_javascript( $wp_customize ) {
		$webmaster_settings	=	catchwebtools_get_options( 'catchwebtools_webmaster' );

		$wp_customize->add_setting( 'catchwebtools_webmaster[status]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $webmaster_settings['status'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_webmaster[status]', array(
			'label'    			=> esc_html__( 'Check to enable WebMaster module', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_header_footer_script',
			'settings' 			=> 'catchwebtools_webmaster[status]',
			'type'     			=> 'checkbox',
		) );

		$wp_customize->add_section( 'catchwebtools_header_footer_script', array(
				'description'	=> '',
				'panel'			=> 'catchwebtools_options',
				'priority'		=> 1,
				'title'    		=> esc_html__( 'Header Footer Scripts', 'catch-web-tools' ),
			) );

		$wp_customize->add_setting( 'catchwebtools_webmaster[header]', array(
				'capability' => 'edit_css',
				'default'    => '',
				'transport'  => 'postMessage',
				'type'       => 'option',
			) 
		);

		$header_control = new WP_Customize_Code_Editor_Control(
			$wp_customize, 'catchwebtools_webmaster[header]', array(
				'active_callback' => 'catchwebtools_is_active_webmaster_module',
				'label'           => esc_html__( 'Header Script', 'catch-web-tools' ),
				'code_type'       => 'text/html',
				'settings'        => 'catchwebtools_webmaster[header]',
				'section'         => 'catchwebtools_header_footer_script', // Site Identity section
			)
		);
		$wp_customize->add_control( $header_control );

		$wp_customize->add_setting( 'catchwebtools_webmaster[footer]', array(
				'capability' => 'edit_css',
				'default'    => '',
				'transport'  => 'postMessage',
				'type'       => 'option',
			) 
		);
		$footer_control = new WP_Customize_Code_Editor_Control(
			$wp_customize, 'catchwebtools_webmaster[footer]', array(
				'active_callback' => 'catchwebtools_is_active_webmaster_module',
				'label'           => esc_html__( 'Footer Script', 'catch-web-tools' ),
				'code_type'       => 'text/html',
				'settings'        => 'catchwebtools_webmaster[footer]',
				'section'         => 'catchwebtools_header_footer_script', // Site Identity section
			)
		);
		$wp_customize->add_control( $footer_control );
	}
}

$catch_web_tools = new catchwebtools();
