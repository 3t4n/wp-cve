<?php
/**
 * Init function
 */
if( !function_exists( 'otw_qtsw_widgets_init' ) ){
	
	function otw_qtsw_widgets_init(){
		
		global $otw_components, $wp_filesystem;
		
		if( isset( $otw_components['registered'] ) && isset( $otw_components['registered']['otw_shortcode'] ) ){
			
			$shortcode_components = $otw_components['registered']['otw_shortcode'];
			arsort( $shortcode_components );
			
			if( otw_init_filesystem() ){
				foreach( $shortcode_components as $shortcode ){
					if( $wp_filesystem->is_file( $shortcode['path'].'/widgets/otw_shortcode_widget.class.php' ) ){
						
						include_once( $shortcode['path'].'/widgets/otw_shortcode_widget.class.php' );
						break;
					}
				}
			}
		}
		register_widget( 'OTW_Shortcode_Widget' );
	}
}
/**
 * Init function
 */
if( !function_exists( 'otw_qtsw_init' ) ){
	
	function otw_qtsw_init(){
		
		global $otw_qtsw_plugin_url, $otw_qtsw_plugin_options, $otw_qtsw_shortcode_component, $otw_qtsw_shortcode_object, $otw_qtsw_form_component, $otw_qtsw_validator_component, $otw_qtsw_form_object, $wp_qtsw_cs_items, $otw_qtsw_js_version, $otw_qtsw_css_version, $wp_widget_factory, $otw_qtsw_factory_component, $otw_qtsw_factory_object, $otw_qtsw_plugin_id;
		
		if( is_admin() ){
			
			include_once( 'otw_qtsw_process_actions.php' );
			
			add_action('admin_menu', 'otw_qtsw_init_admin_menu' );
			
			add_action('admin_print_styles', 'otw_qtsw_enqueue_admin_styles' );
			
			add_action('admin_enqueue_scripts', 'otw_qtsw_enqueue_admin_scripts');
			
			add_filter('otwfcr_notice', 'otw_qtsw_factory_message' );
		}
		otw_qtsw_enqueue_styles();
		
		include_once( plugin_dir_path( __FILE__ ).'otw_qtsw_dialog_info.php' );
		
		//shortcode component
		$otw_qtsw_shortcode_component = otw_load_component( 'otw_shortcode' );
		$otw_qtsw_shortcode_object = otw_get_component( $otw_qtsw_shortcode_component );
		$otw_qtsw_shortcode_object->js_version = $otw_qtsw_js_version;
		$otw_qtsw_shortcode_object->css_version = $otw_qtsw_css_version;
		$otw_qtsw_shortcode_object->editor_button_active_for['page'] = true;
		$otw_qtsw_shortcode_object->editor_button_active_for['post'] = true;
		
		$otw_qtsw_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
		
		if( isset( $otw_qtsw_plugin_options['otw_qtsw_theme_css'] ) && strlen( $otw_qtsw_plugin_options['otw_qtsw_theme_css'] ) ){
			
			if( preg_match( "/^http(s)?\:\/\//", $otw_qtsw_plugin_options['otw_qtsw_theme_css'] ) ){
				$otw_qtsw_shortcode_object->add_default_external_lib( 'css', 'theme_style', $otw_qtsw_plugin_options['otw_qtsw_theme_css'], 'live_preview', 11 );
			}else{
				$otw_qtsw_shortcode_object->add_default_external_lib( 'css', 'theme_style', get_stylesheet_directory_uri().'/'.$otw_qtsw_plugin_options['otw_qtsw_theme_css'], 'live_preview', 11 );
			}
		}
		
		$otw_qtsw_shortcode_object->shortcodes['quote'] = array( 'title' => esc_html__('Quote', 'otw_qtsw'),'enabled' => true,'children' => false, 'parent' => false, 'order' => 4,'path' => dirname( __FILE__ ).'/otw_components/otw_shortcode/', 'url' => $otw_qtsw_plugin_url.'include/otw_components/otw_shortcode/', 'dialog_text' => $otw_qtsw_dialog_text  );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_qtsw_shortcode_object.labels.php' );
		$otw_qtsw_shortcode_object->init();
		
		//form component
		$otw_qtsw_form_component = otw_load_component( 'otw_form' );
		$otw_qtsw_form_object = otw_get_component( $otw_qtsw_form_component );
		$otw_qtsw_form_object->js_version = $otw_qtsw_js_version;
		$otw_qtsw_form_object->css_version = $otw_qtsw_css_version;
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_qtsw_form_object.labels.php' );
		$otw_qtsw_form_object->init();
		
		//validator component
		$otw_qtsw_validator_component = otw_load_component( 'otw_validator' );
		$otw_qtsw_validator_object = otw_get_component( $otw_qtsw_validator_component );
		$otw_qtsw_validator_object->init();
		
		$otw_qtsw_factory_component = otw_load_component( 'otw_factory' );
		$otw_qtsw_factory_object = otw_get_component( $otw_qtsw_factory_component );
		$otw_qtsw_factory_object->add_plugin( $otw_qtsw_plugin_id, dirname( dirname( __FILE__ ) ).'/otw_content_manager.php', array( 'menu_parent' => 'otw-qtsw-settings', 'lc_name' => esc_html__( 'License Manager', 'otw_qtsw' ), 'menu_key' => 'otw-qtsw' ) );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_qtsw_factory_object.labels.php' );
		$otw_qtsw_factory_object->init();

		
	}
}

/**
 * include needed styles
 */
if( !function_exists( 'otw_qtsw_enqueue_styles' ) ){
	function otw_qtsw_enqueue_styles(){
		global $otw_qtsw_plugin_url, $otw_qtsw_css_version;
	}
}


/**
 * Admin styles
 */
if( !function_exists( 'otw_qtsw_enqueue_admin_styles' ) ){
	
	function otw_qtsw_enqueue_admin_styles(){
		
		global $otw_qtsw_plugin_url, $otw_qtsw_css_version;
		
		wp_enqueue_style( 'otw_qtsw_admin', $otw_qtsw_plugin_url.'/css/otw_qtsw_admin.css', array( 'thickbox' ), $otw_qtsw_css_version );
	}
}

/**
 * Admin scripts
 */
if( !function_exists( 'otw_qtsw_enqueue_admin_scripts' ) ){
	
	function otw_qtsw_enqueue_admin_scripts( $requested_page ){
		
		global $otw_qtsw_plugin_url, $otw_qtsw_js_version;
		
		switch( $requested_page ){
			
			case 'widgets.php':
					wp_enqueue_script("otw_shotcode_widget_admin", $otw_qtsw_plugin_url.'include/otw_components/otw_shortcode/js/otw_shortcode_widget_admin.js'  , array( 'jquery', 'thickbox' ), $otw_qtsw_js_version );
					
					if(function_exists( 'wp_enqueue_media' )){
						wp_enqueue_media();
					}else{
						wp_enqueue_style('thickbox');
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
					}
				break;
		}
	}
	
}

/**
 * Init admin menu
 */
if( !function_exists( 'otw_qtsw_init_admin_menu' ) ){
	
	function otw_qtsw_init_admin_menu(){
		
		global $otw_qtsw_plugin_url;
		
		add_menu_page(__('Quotes Shortcode And Widget', 'otw_qtsw'), esc_html__('Quotes Shortcode And Widget', 'otw_qtsw'), 'manage_options', 'otw-qtsw-settings', 'otw_qtsw_settings', $otw_qtsw_plugin_url.'images/otw-sbm-icon.png');
		add_submenu_page( 'otw-qtsw-settings', esc_html__('Settings', 'otw_qtsw'), esc_html__('Settings', 'otw_qtsw'), 'manage_options', 'otw-qtsw-settings', 'otw_qtsw_settings' );

	}
}

/**
 * Settings page
 */
if( !function_exists( 'otw_qtsw_settings' ) ){
	
	function otw_qtsw_settings(){
		require_once( 'otw_qtsw_settings.php' );
	}
}



/**
 * Keep the admin menu open
 */
if( !function_exists( 'otw_open_qtsw_menu' ) ){
	
	function otw_open_qtsw_menu( $params ){
		
		global $menu;
		
		foreach( $menu as $key => $item ){
			if( $item[2] == 'otw-cm-settings' ){
				$menu[ $key ][4] = $menu[ $key ][4].' wp-has-submenu wp-has-current-submenu wp-menu-open menu-top otw-menu-open';
			}
		}
	}
}


/**
 * factory messages
 */
if( !function_exists( 'otw_qtsw_factory_message' ) ){
	
	function otw_qtsw_factory_message( $params ){
		
		global $otw_qtsw_plugin_id;
		
		if( isset( $params['plugin'] ) && $otw_qtsw_plugin_id == $params['plugin'] ){
			
			//filter out some messages if need it
		}
		if( isset( $params['message'] ) )
		{
			return $params['message'];
		}
		return $params;
	}
}
?>