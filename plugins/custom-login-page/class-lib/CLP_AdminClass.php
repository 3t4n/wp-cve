<?php

/**
 *
 * Class A5 Custom Login Page Admin
 *
 * @ A5 Custom Login Page
 *
 * building admin page
 *
 */
class CLP_Admin extends A5_OptionPage {
	
	static $options;
	
	function __construct($multisite) {
		
		add_action('admin_init', array($this, 'initialize_settings'));
		add_action('contextual_help', array($this, 'add_help_text'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));	
		
		if ($multisite) :
		
			add_action('network_admin_menu', array($this, 'add_admin_menu'));
				
			self::$options = get_network_option(NULL, 'clp_options');
			
			// in case the CLP Widget export file was posted here.
		
			if (@array_key_exists('clp_widget_options', self::$options)) :
			
				update_network_option(NULL, 'clp_widget_options', self::$options['clp_widget_options']);
				
				unset(self::$options['clp_widget_options']);
			
			endif;
			
		else :
			
			add_action('admin_menu', array($this, 'add_admin_menu'));
		
			self::$options = get_option('clp_options');
			
			// in case the CLP Widget export file was posted here.
		
			if (@array_key_exists('clp_widget_options', self::$options)) :
			
				update_option('clp_widget_options', self::$options['clp_widget_options']);
				
				unset(self::$options['clp_widget_options']);
			
			endif;
			
		endif;
		
	}
	
	/**
	 *
	 * Add options-page for single site
	 *
	 */
	function add_admin_menu() {
		
		add_menu_page('A5 Custom Login', 'A5 Custom Login', 'administrator', 'clp-settings', array($this, 'build_options_page'), plugins_url('custom-login-page/img/a5-icon-16.png'), 62);
		
		add_submenu_page('clp-settings', 'Custom Login Page', 'Custom Login Page', 'administrator', 'clp-settings', array($this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Make all the admin stuff draggable
	 *
	 */
	function enqueue_scripts($hook){
		
		if ('toplevel_page_clp-settings' != $hook) return;
		
		$min = (SCRIPT_DEBUG == false) ? '.min.' : '.';
		
		wp_enqueue_script('dashboard');
		
		if (wp_is_mobile()) wp_enqueue_script('jquery-touch-punch');
		
		// getting the build in iris color picker
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, true );
		
		wp_register_script('a5-color-picker-script', plugins_url('custom-login-page/color-picker'.$min.'js'), array('wp-color-picker'), '1.0', true);
		wp_enqueue_script('a5-color-picker-script');
		
		// getting the media uploader
		
		if ( function_exists( 'wp_enqueue_media' ) ) :
			
			wp_enqueue_media();
			
			wp_register_script( 'a5-media-upload-script', plugins_url('custom-login-page/media-uploader'.$min.'js'), array( 'jquery' ), '1.0', true );
			
			wp_enqueue_script('a5-media-upload-script');
			
		endif;
		
	}
	
	/**
	 *
	 * Adding Contextual Help
	 *
	 */
	function add_help_text() {
		
		$screen = get_current_screen();
		
		if ($screen->id != 'toplevel_page_clp-settings') return;
		
		$content = self::tag_it(__('In these settings you will be guided step by step through the process of styling your login page. The basic options are very foolproof but also very limited.', 'custom-login-page'), 'p');
		$content .= self::tag_it(__('If you are familiar with coding your own css, you can use only the css tab to write your entire style sheet there. Next to the input for the css, you find a help box with all the elements on the page that you can style.', 'custom-login-page'), 'p');
		if (!is_multisite()) $content .= self::tag_it(__('There is also a preview. So, you just can play around a bit and after saving the settings see, how it looks. No need to have two browser windows open.', 'custom-login-page'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-general-help',
			'title'   => __('General'),
			'content' => $content,
		));
		
		$content = self::tag_it(__('One way to make the login page look like the rest of your blog is to include the header and footer of the frontend.', 'custom-login-page'), 'p');
		$content .= self::tag_it(__('Depending on your theme, it could take away some possibilities of styling the login page, though.', 'custom-login-page'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-advanced-help',
			'title'   => __('Advanced'),
			'content' => $content,
		));
		
		$content = self::tag_it(sprintf(__('With the margin of the logo, you can position the logo more precisely. Give a CSS value here, i.e. %s to locate it 180 px left.', 'custom-login-page'), '&#39;0 0 0 -180px&#39;'), 'p');
		$content .= self::tag_it(sprintf(__('With the padding of the logo, you can position the shadow more precisely. Give a CSS value here, i.e. %s to get rid of it completely.', 'custom-login-page'), '&#39;0 0 0 -180px&#39;'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-logo-help',
			'title'   => __('Logo', 'custom-login-page'),
			'content' => $content,
		));
		
		$content = self::tag_it(__('By setting the background size you don&#39;t only change the size of the background image, but also it&#39; behaviour.', 'custom-login-page'), 'p');
		$content .= self::tag_it(sprintf(__('Next to actual sizes in percent or px, the values %s can be used.', 'custom-login-page'), '<strong>auto, cover, contain, initial, inherit</strong>'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-background-help',
			'title'   => __('Background Size', 'custom-login-page'),
			'content' => $content,
		));
		
		$content = self::tag_it(__('In the CSS and HTML textareas, you can use the tab key to format your code.', 'custom-login-page'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-tab-help',
			'title'   => __('CSS & HTML', 'custom-login-page'),
			'content' => $content,
		));
		
	}
	
	/**
	 *
	 * Initialize the admin screen of the plugin
	 *
	 */
	 
	function initialize_settings() {
		
		register_setting('clp_options', 'clp_options', array($this, 'validate'));
		
		// main tab
		
		add_settings_section('clp_options', false, array($this, 'clp_custom_message_section'), 'clp_message');
		
		add_settings_field('clp_logout_custom_message', __('Logout Message', 'custom-login-page'), array($this, 'logout_custom_message_input'), 'clp_message', 'clp_options');
		
		add_settings_field('clp_error_custom_message', __('Error Message', 'custom-login-page'), array($this, 'error_custom_message_input'), 'clp_message', 'clp_options');
		
		add_settings_field('clp_register_custom_message', __('Registration Message', 'custom-login-page'), array($this, 'register_custom_message_input'), 'clp_message', 'clp_options');
		
		add_settings_field('clp_password_custom_message', __('Lost Password Message', 'custom-login-page'), array($this, 'password_custom_message_input'), 'clp_message', 'clp_options');
		
		add_settings_section('clp_options', __('Export Settings', 'custom-login-page'), array($this, 'clp_export_section'), 'clp_export');
		
		add_settings_field('clp_export', __('Download a file with your settings', 'custom-login-page'), array($this, 'export_input'), 'clp_export', 'clp_options');
		
		add_settings_section('clp_options', __('Import Settings', 'custom-login-page'), array($this, 'clp_import_section'), 'clp_import');
		
		add_settings_field('clp_import', __('This will overlay any existing setting, you already have.', 'custom-login-page'), array($this, 'import_input'), 'clp_import', 'clp_options');
		
		add_settings_field('clp_impex_resize', false, array($this, 'impex_resize_field'), 'clp_import', 'clp_options');
		
		// advanced tab
		
		add_settings_section('clp_options', false, array($this, 'clp_blog_section'), 'clp_blog');
		
		add_settings_field('clp_blog_header', __('Blog Header', 'custom-login-page'), array($this, 'blog_header_input'), 'clp_blog', 'clp_options', array(__('Check, to include the header of the frontend into your login page.', 'custom-login-page')));
		
		add_settings_field('clp_blog_footer', __('Blog Footer', 'custom-login-page'), array($this, 'blog_footer_input'), 'clp_blog', 'clp_options', array(__('Check, to include the footer of the frontend into your login page.', 'custom-login-page')));
		
		add_settings_section('clp_options', false, array($this, 'clp_hide_section'), 'clp_hide');
		
		add_settings_field('clp_hide_rememberme', __('Hide &#39;remember me&#39; checkbox.', 'custom-login-page'), array($this, 'hide_rememberme_input'), 'clp_hide', 'clp_options');
		
		add_settings_field('clp_disable_reg', __('Hide register link.', 'custom-login-page'), array($this, 'disable_reg_input'), 'clp_hide', 'clp_options');
		
		add_settings_field('clp_disable_pass', __('Hide lost password link.', 'custom-login-page'), array($this, 'disable_pass_input'), 'clp_hide', 'clp_options');
		
		add_settings_field('clp_hide_backlink', __('Hide back to blog link.', 'custom-login-page'), array($this, 'hide_backlink_input'), 'clp_hide', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_debug_section'), 'clp_debug');
		
		add_settings_field('clp_compress', __('Compress Style Sheet', 'custom-login-page'), array($this, 'compress_field'), 'clp_debug', 'clp_options', array(__('Click here to compress the style sheet.', 'custom-login-page')));
		
		add_settings_field('clp_debug', __('Check, to write styles inline instead of to a virtual CSS file.', 'custom-login-page'), array($this, 'debug_input'), 'clp_debug', 'clp_options');
		
		if (isset(self::$options['inline']) && !empty(self::$options['inline'])) add_settings_field('clp_priority', __('Give a value for the priority of the style inline (this can help with other plugins overriding our styles).', 'custom-login-page'), array($this, 'priority_input'), 'clp_debug', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_custom_redirect_section'), 'clp_redirect');
		
		add_settings_field('clp_custom_redirect', __('Redirect per User Role', 'custom-login-page'), array($this, 'custom_redirect_input'), 'clp_redirect', 'clp_options');
		
		// body and button tab
		
		add_settings_section('clp_options', false, array($this, 'clp_body_section'), 'clp_body');
		
		add_settings_field('clp_body_background', __('Background Picture', 'custom-login-page'), array($this, 'body_background_input'), 'clp_body', 'clp_options');
		
		add_settings_field('clp_body_img_repeat', __('Background Repeat', 'custom-login-page'), array($this, 'body_img_repeat_input'), 'clp_body', 'clp_options');
		
		add_settings_field('clp_body_img_pos', __('Position of the Background Picture', 'custom-login-page'), array($this, 'body_img_pos_input'), 'clp_body', 'clp_options');
		
		add_settings_field('clp_body_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'body_bg_color1_input'), 'clp_body', 'clp_options');
		
		add_settings_field('clp_body_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'body_bg_color2_input'), 'clp_body', 'clp_options');
		
		add_settings_field('clp_body_bg_size', __('Background Size', 'custom-login-page'), array($this, 'body_bg_size_input'), 'clp_body', 'clp_options');	
		
		add_settings_section('clp_options', false, array($this, 'clp_button_section'), 'clp_button');
		
		add_settings_field('clp_button_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'button_bg_color1_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_button_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'button_bg_color2_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_button_text_color', __('Text Colour', 'custom-login-page'), array($this, 'button_text_color_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_button_border_color', __('Border Colour', 'custom-login-page'), array($this, 'button_border_color_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_btn_hover_bg_color1', __('Hover Background Colour', 'custom-login-page'), array($this, 'btn_hover_bg_color1_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_btn_hover_bg_color2', __('Second Hover Background Colour (for Gradient)', 'custom-login-page'), array($this, 'btn_hover_bg_color2_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_btn_hover_text_color', __('Hover Text Colour', 'custom-login-page'), array($this, 'btn_hover_text_color_input'), 'clp_button', 'clp_options');
		
		add_settings_field('clp_btn_hover_border_color', __('Hover Border Colour', 'custom-login-page'), array($this, 'btn_hover_border_color_input'), 'clp_button', 'clp_options');	
		
		// logo tab
		
		add_settings_section('clp_options', false, array($this, 'clp_logo_section'), 'clp_logo');
		
		add_settings_field('clp_hide_logo', __('Hide Logo', 'custom-login-page'), array($this, 'hide_logo_input'), 'clp_logo', 'clp_options', array(__('Check to have no logo at all.', 'custom-login-page')));
		
		add_settings_field('clp_logo_url', __('Logo URL', 'custom-login-page'), array($this, 'logo_url_input'), 'clp_logo', 'clp_options');
		
		add_settings_field('clp_link_url', __('URL to link to', 'custom-login-page'), array($this, 'link_url_input'), 'clp_logo', 'clp_options');
		
		add_settings_field('clp_logo_title', __('Title tag of the logo', 'custom-login-page'), array($this, 'logo_title_input'), 'clp_logo', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_logo_size_section'), 'clp_logo_size');
		
		add_settings_field('clp_logo_width', __('Width of the Logo (in px)', 'custom-login-page'), array($this, 'logo_width_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_field('clp_logo_height', __('Height of the Logo (in px)', 'custom-login-page'), array($this, 'logo_height_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_field('clp_h1_width', __('Width of the Logo Container (in px)', 'custom-login-page'), array($this, 'h1_width_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_field('clp_h1_height', __('Height of the Logo Container (in px)', 'custom-login-page'), array($this, 'h1_height_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_field('clp_h1_margin', __('Margin of the Logo Container (CSS)', 'custom-login-page'), array($this, 'h1_margin_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_field('clp_h1_padding', __('Padding of the Logo Container (CSS)', 'custom-login-page'), array($this, 'h1_padding_input'), 'clp_logo_size', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_logo_style_section'), 'clp_logo_style');
		
		add_settings_field('clp_h1_corner', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'h1_corner_input'), 'clp_logo_style', 'clp_options');
		
		add_settings_field('clp_h1_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'h1_shadow_x_input'), 'clp_logo_style', 'clp_options');
		
		add_settings_field('clp_h1_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'h1_shadow_y_input'), 'clp_logo_style', 'clp_options');
		
		add_settings_field('clp_h1_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'h1_shadow_softness_input'), 'clp_logo_style', 'clp_options');
		
		add_settings_field('clp_h1_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'h1_shadow_color_input'), 'clp_logo_style', 'clp_options');
		
		add_settings_field('clp_h1_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'h1_shadow_inset_input'), 'clp_logo_style', 'clp_options');
		
		// video tab
		
		add_settings_section('clp_options', false, array($this, 'clp_video_section'), 'clp_video');
		
		add_settings_field('clp_video_url', __('Video URL', 'custom-login-page'), array($this, 'video_url_input'), 'clp_video', 'clp_options');
		
		add_settings_field('clp_video_width', __('Width of the Video', 'custom-login-page'), array($this, 'video_width_input'), 'clp_video', 'clp_options');
		
		add_settings_field('clp_video_height', __('Height of the Video', 'custom-login-page'), array($this, 'video_height_input'), 'clp_video', 'clp_options');
		
		add_settings_section('clp_options', __('Additional Parameters', 'custom-login-page'), array($this, 'clp_video_parameter_section'), 'clp_video_parameters');
		
		add_settings_field('clp_video_poster', __('Video Poster', 'custom-login-page'), array($this, 'video_poster_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_poster', __('Video Poster', 'custom-login-page'), array($this, 'video_poster_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_loop', __('Loop Video', 'custom-login-page'), array($this, 'video_loop_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_autoplay', __('Autoplay Video', 'custom-login-page'), array($this, 'video_autoplay_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_preload', __('Video Preload', 'custom-login-page'), array($this, 'video_preload_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_class', __('Class for the Video Container', 'custom-login-page'), array($this, 'video_class_input'), 'clp_video_parameters', 'clp_options');
		
		add_settings_field('clp_video_id', __('ID of the Video Container', 'custom-login-page'), array($this, 'video_id_input'), 'clp_video_parameters', 'clp_options');
		
		// logindiv tab
	
		add_settings_section('clp_options', false, array($this, 'clp_logindiv_section'), 'clp_logindiv');
		
		add_settings_field('clp_logindiv_background', __('Background Picture', 'custom-login-page'), array($this, 'logindiv_background_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_img_repeat', __('Background Repeat', 'custom-login-page'), array($this, 'logindiv_img_repeat_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_img_pos', __('Position of the Background Picture', 'custom-login-page'), array($this, 'logindiv_img_pos_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'logindiv_bg_color1_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'logindiv_bg_color2_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_bg_size', __('Background Size', 'custom-login-page'), array($this, 'logindiv_bg_size_input'), 'clp_logindiv', 'clp_options');	
		
		add_settings_field('clp_logindiv_text_color', __('Text Colour', 'custom-login-page'), array($this, 'logindiv_text_color_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'logindiv_transparency_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_border_style', __('Border Style', 'custom-login-page'), array($this, 'logindiv_border_style_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_border_width', __('Border Width (in px)', 'custom-login-page'), array($this, 'logindiv_border_width_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_border_color', __('Border Colour', 'custom-login-page'), array($this, 'logindiv_border_color_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_border_round', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'logindiv_border_round_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'logindiv_shadow_x_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'logindiv_shadow_y_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'logindiv_shadow_softness_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logindiv_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'logindiv_shadow_color_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_field('clp_logiondiv_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'logindiv_shadow_inset_input'), 'clp_logindiv', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_logindiv_pos_section'), 'clp_logindiv_pos');
		
		add_settings_field('clp_logindiv_left', __('Position (x-direction in px)', 'custom-login-page'), array($this, 'logindiv_left_input'), 'clp_logindiv_pos', 'clp_options');
		
		add_settings_field('clp_logindiv_top', __('Position (y-direction in px)', 'custom-login-page'), array($this, 'logindiv_top_input'), 'clp_logindiv_pos', 'clp_options');
		
		add_settings_field('clp_logindiv_width', __('Width (in px)', 'custom-login-page'), array($this, 'logindiv_width_input'), 'clp_logindiv_pos', 'clp_options');
		
		add_settings_field('clp_logindiv_height', __('Height (in px)', 'custom-login-page'), array($this, 'logindiv_height_input'), 'clp_logindiv_pos', 'clp_options');
		
		add_settings_field('clp_logindiv_padding', __('Padding', 'custom-login-page'), array($this, 'logindiv_padding_input'), 'clp_logindiv_pos', 'clp_options');
		
		add_settings_field('clp_logindiv_margin', __('Margin', 'custom-login-page'), array($this, 'logindiv_margin_input'), 'clp_logindiv_pos', 'clp_options');
		
		// login form tab
		
		add_settings_section('clp_options', false, array($this, 'clp_loginform_section'), 'clp_loginform');
		
		add_settings_field('clp_loginform_background', __('Background Picture', 'custom-login-page'), array($this, 'loginform_background_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_img_repeat', __('Background Repeat', 'custom-login-page'), array($this, 'loginform_img_repeat_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_img_pos', __('Position of the Background Picture', 'custom-login-page'), array($this, 'loginform_img_pos_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'loginform_bg_color1_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'loginform_bg_color2_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_bg_size', __('Background Size', 'custom-login-page'), array($this, 'loginform_bg_size_input'), 'clp_loginform', 'clp_options');	
		
		add_settings_field('clp_loginform_text_color', __('Text Colour', 'custom-login-page'), array($this, 'loginform_text_color_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'loginform_transparency_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_border_style', __('Border Style', 'custom-login-page'), array($this, 'loginform_border_style_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_border_width', __('Border Width (in px)', 'custom-login-page'), array($this, 'loginform_border_width_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_border_color', __('Border Colour', 'custom-login-page'), array($this, 'loginform_border_color_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_border_round', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'loginform_border_round_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_padding', __('Padding', 'custom-login-page'), array($this, 'loginform_padding_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_margin', __('Margin', 'custom-login-page'), array($this, 'loginform_margin_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_x_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_y_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'loginform_shadow_softness_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_loginform_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'loginform_shadow_color_input'), 'clp_loginform', 'clp_options');
		
		add_settings_field('clp_logionform_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'loginform_shadow_inset_input'), 'clp_loginform', 'clp_options');
		
		// message tab
		
		add_settings_section('clp_options', false, array($this, 'clp_logout_message_section'), 'clp_logout_message');
		
		add_settings_field('clp_loggedout_text_color', __('Text Colour', 'custom-login-page'), array($this, 'loggedout_text_color_input'), 'clp_logout_message', 'clp_options');
		
		add_settings_field('clp_loggedout_bg_color', __('Background Colour', 'custom-login-page'), array($this, 'loggedout_bg_color_input'), 'clp_logout_message', 'clp_options');
		
		add_settings_field('clp_loggedout_border_color', __('Border Colour', 'custom-login-page'), array($this, 'loggedout_border_color_input'), 'clp_logout_message', 'clp_options');
		
		add_settings_field('clp_loggedout_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'loggedout_transparency_input'), 'clp_logout_message', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_error_message_section'), 'clp_error_message');
		
		add_settings_field('clp_error_text_color', __('Text Colour', 'custom-login-page'), array($this, 'error_text_color_input'), 'clp_error_message', 'clp_options');
		
		add_settings_field('clp_error_bg_color', __('Background Colour', 'custom-login-page'), array($this, 'error_bg_color_input'), 'clp_error_message', 'clp_options');
		
		add_settings_field('clp_error_border_color', __('Border Colour', 'custom-login-page'), array($this, 'error_border_color_input'), 'clp_error_message', 'clp_options');
		
		add_settings_field('clp_error_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'error_transparency_input'), 'clp_error_message', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_input_section'), 'clp_input');
		
		add_settings_field('clp_input_text_color', __('Text Colour', 'custom-login-page'), array($this, 'input_text_color_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_bg_color', __('Background Colour', 'custom-login-page'), array($this, 'input_bg_color_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_border_color', __('Border Colour', 'custom-login-page'), array($this, 'input_border_color_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'input_shadow_x_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'input_shadow_y_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'input_shadow_softness_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'input_shadow_color_input'), 'clp_input', 'clp_options');
		
		add_settings_field('clp_input_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'input_shadow_inset_input'), 'clp_input', 'clp_options');
		
		// link tab
		
		add_settings_section('clp_options', false, array($this, 'clp_link_section'), 'clp_link');
		
		add_settings_field('clp_link_size', __('Font Size', 'custom-login-page'), array($this, 'link_size_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_text_color', __('Text Colour', 'custom-login-page'), array($this, 'link_text_color_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_textdecoration', __('Text Decoration', 'custom-login-page'), array($this, 'link_textdecoration_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'link_shadow_x_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'link_shadow_y_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'link_shadow_softness_input'), 'clp_link', 'clp_options');
		
		add_settings_field('clp_link_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'link_shadow_color_input'), 'clp_link', 'clp_options');
		
		add_settings_section('clp_options', false, array($this, 'clp_hover_section'), 'clp_hover');
		
		add_settings_field('clp_hover_text_color', __('Text Colour', 'custom-login-page'), array($this, 'hover_text_color_input'), 'clp_hover', 'clp_options');
		
		add_settings_field('clp_hover_textdecoration', __('Text Decoration', 'custom-login-page'), array($this, 'hover_textdecoration_input'), 'clp_hover', 'clp_options');
		
		add_settings_field('clp_hover_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'hover_shadow_x_input'), 'clp_hover', 'clp_options');
		
		add_settings_field('clp_hover_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'hover_shadow_y_input'), 'clp_hover', 'clp_options');
		
		add_settings_field('clp_hover_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'hover_shadow_softness_input'), 'clp_hover', 'clp_options');
		
		add_settings_field('clp_hover_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'hover_shadow_color_input'), 'clp_hover', 'clp_options');
		
		// css tab
		
		add_settings_section('clp_options', __('CSS', 'custom-login-page'), array($this, 'clp_css_section'), 'clp_css');
		
		add_settings_field('clp_css', __('Own CSS', 'custom-login-page'), array($this, 'css_input'), 'clp_css', 'clp_options');
		
		add_settings_field('clp_css_override', __('Override other styles', 'custom-login-page'), array($this, 'override_input'), 'clp_css', 'clp_options', array(__('By checking this, all other styles will be replaced by your CSS. Otherwise, your CSS is additional.', 'custom-login-page')));
		
		add_settings_field('clp_css_resize', false, array($this, 'css_resize_field'), 'clp_css', 'clp_options');
		
		add_settings_section('clp_options', __('SVG', 'custom-login-page'), array($this, 'clp_svg_section'), 'clp_svg');
		
		add_settings_field('clp_svg', __('Some SVG', 'custom-login-page'), array($this, 'svg_input'), 'clp_svg', 'clp_options');
		
		// html tab
		
		add_settings_section('clp_options', __('Aditional html snippets', 'custom-login-page'), array($this, 'clp_html_section'), 'clp_html');
		
		add_settings_field('clp_login_message', __('Above Form', 'custom-login-page'), array($this, 'login_message_input'), 'clp_html', 'clp_options');
		
		add_settings_field('clp_login_form', __('Inside Form', 'custom-login-page'), array($this, 'login_form_input'), 'clp_html', 'clp_options');
		
		add_settings_field('clp_login_footer', __('Beneath Form', 'custom-login-page'), array($this, 'login_footer_input'), 'clp_html', 'clp_options');
		
		add_settings_field('clp_html_resize', false, array($this, 'html_resize_field'), 'clp_html', 'clp_options');
	
	}
	
	// main tab
	
	function clp_custom_message_section() {
	
		self::tag_it(__('You can enter your own logout message here. You can make your blog a bit more personal like that.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Furthermore, you can enter your own error message. By default, Wordpress says that either the username or the password is wrong, which is perhaps a hint to foreigners that you don&#39;t wish to give..', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('If you don&#39;t want to style your login page item by item, you can as well move on to enter a whole style sheet in the css tab.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function logout_custom_message_input() {
		
		a5_text_field('logout_custom_message', 'clp_options[logout_custom_message]', @self::$options['logout_custom_message'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
	
	}
	
	function error_custom_message_input() {
		
		a5_text_field('error_custom_message', 'clp_options[error_custom_message]', @self::$options['error_custom_message'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function password_custom_message_input() {
		
		a5_text_field('password_custom_message', 'clp_options[password_custom_message]', @self::$options['password_custom_message'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function register_custom_message_input() {
		
		a5_text_field('register_custom_message', 'clp_options[register_custom_message]', @self::$options['register_custom_message'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function clp_export_section() {
		
		self::tag_it(__('Export the current A5 Custom Login Page settings and download them as a text file. The content of this text file can be imported into this or another A5 Custom Login Page installation:', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(sprintf(_x('The file will be named %s. After you downloaded it, you can (but don&#39;t need to) rename the file to something more meaningful.', '%s is the file name', 'custom-login-page'), '<code>a5-clp-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('y') . date('m') . date('d') . '.txt</code>'), 'p', 1, false, true);
		
	}
	
	function export_input() {
	
		echo '<a class="button" href="' . get_bloginfo('url') . '/?clpfile=export" id="settings-download"><strong>'. __('Export &amp; Download', 'custom-login-page') .'</strong> A5 Custom Login Page Settings File</a>';
	
	}
	
	function clp_import_section() {
		
		self::tag_it(__('Enter the content of your text file with the settings here.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function import_input() {
	
		a5_textarea('import', 'clp_options[import]', false, false, array('style' => 'height: 200px; min-width: 100%;'));
	
	}
	
	function impex_resize_field() {
		
		a5_resize_textarea('import', true);
		
	}
	
	// advanced tab
	
	function clp_blog_section() {
	
		self::tag_it(__('If you want, you can include the header and footer of your blog&#39;s frontend in your login page.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function blog_header_input($labels) {
		
		a5_checkbox('blog_header', 'clp_options[blog_header]', @self::$options['blog_header'], $labels[0]);
	
	}
	
	function blog_footer_input($labels) {
		
		a5_checkbox('blog_footer', 'clp_options[blog_footer]', @self::$options['blog_footer'], $labels[0]);
		
	}
	
	function clp_hide_section() {
	
		self::tag_it(__('You can hide the links under login form if wanting to.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function hide_rememberme_input() {
		
		a5_checkbox('hide_rememberme', 'clp_options[hide_rememberme]', @self::$options['hide_rememberme']);
		
	}
	
	function disable_reg_input() {
		
		a5_checkbox('disable_reg', 'clp_options[disable_reg]', @self::$options['disable_reg']);
	
	}
	
	function disable_pass_input() {
		
		a5_checkbox('disable_pass', 'clp_options[disable_pass]', @self::$options['disable_pass']);
	
	}
	
	function hide_backlink_input() {
		
		a5_checkbox('hide_backlink', 'clp_options[hide_backlink]', @self::$options['hide_backlink']);
		
	}
	
	function clp_debug_section() {
		
		self::tag_it(__('There seem to be problems with the virtual stylesheet in some environments. By choosing to write the styles inline, those can be avoided.).', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function compress_field($labels) {
		
		a5_checkbox('compress', 'clp_options[compress]', @self::$options['compress'], $labels[0]);
		
	}
	
	function debug_input() {
	
		a5_checkbox('inline', 'clp_options[inline]', @self::$options['inline']);
	
	}
	
	function priority_input() {
	
		a5_number_field('priority', 'clp_options[priority]', @self::$options['priority'], false, array('step' => 10));
	
	}
	
	function clp_custom_redirect_section() {
	
		self::tag_it(__('You can enter redirections for each user role of the blog.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('If you leave a field empty, the plugin will redirect to the default page.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function custom_redirect_input() {
		
		$userroles = get_editable_roles();
		
		$rows = '';
			
		foreach ($userroles as $role => $details) :
		
			$nicename = translate_user_role($details['name']);
			
			$cells = self::tag_it('<label for="custom_redirect-'.$role.'">'.$nicename.'</label>', 'td', 2);
			
			$cells .= self::tag_it(a5_url_field('custom_redirect-'.$role, 'clp_options[custom_redirect]['.$role.']', @self::$options['custom_redirect'][$role], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'http://example.com'), false), 'td', 2);
			
			$cells .= ('administrator' != $role) ? self::tag_it(a5_checkbox('hide_backend-'.$role, 'clp_options[hide_backend]['.$role.']', $role, sprintf(__('Hide backend for %s.', 'custom-login-page'), $nicename), false, @self::$options['hide_backend'][$role], false), 'td', 2) : self::tag_it('&nbsp;', 'td', 2);
			
			$rows .= self::tag_it($cells, 'tr', 1);
			
		endforeach;
		
		self::tag_it($rows, 'table', 0, false, true);
		
	}
	
	// body and button tab
	
	function clp_body_section() {
		
		self::tag_it(__('Just upload any picture via the uploader. Leave it empty, if you don&#39;t want a picture. Background images are tiled by default. You can select the direction of repeating the image or to not repeat it. The position of the image can be something like &#39;100px 50%&#39; or &#39center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the last section, you choose the background colour and the colour of the text in the html body element. If you give two background colours, you can create a gradient. Colour no. 1 will always be up.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function body_background_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-body_background', 'body', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'body_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['body_background'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'body_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-body_background', 'body', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'body_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
		
		a5_url_field('body_url', 'clp_options[body_background]', @self::$options['body_background'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function body_img_repeat_input() {
		
		$options = array(array('no-repeat', 'no-repeat'), array('repeat-x', 'repeat-x'), array('repeat-y', 'repeat-y'));
		
		a5_select('body_img_repeat', 'clp_options[body_img_repeat]', $options, @self::$options['body_img_repeat'], false, __('default', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
			
	}
		
	function body_img_pos_input() {
		
		a5_text_field('body_img_pos', 'clp_options[body_img_pos]', @self::$options['body_img_pos'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function body_bg_color1_input() {
		
		a5_text_field('body_bg_color1', 'clp_options[body_bg_color1]', @self::$options['body_bg_color1'], false, array('class' => 'color-picker'));
		
	}
	
	function body_bg_color2_input() {
		
		a5_text_field('body_bg_color2', 'clp_options[body_bg_color2]', @self::$options['body_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function body_bg_size_input() {
		
		a5_text_field('body_bg_size', 'clp_options[body_bg_size]', @self::$options['body_bg_size'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function clp_button_section() {
		
		self::tag_it(__('Enter the background, text and border colour of the submit button here. The button will look static if you don&#39;t give values for the hover state of it. If you want to have a gradient, enter two background colours. The first one will be up then.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function button_bg_color1_input() {
		
		a5_text_field('button_bg_color1', 'clp_options[button_bg_color1]', @self::$options['button_bg_color1'], false, array('class' => 'color-picker'));
		
	}
	
	function button_bg_color2_input() {
		
		a5_text_field('button_bg_color2', 'clp_options[button_bg_color2]', @self::$options['button_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function button_text_color_input() {
		
		a5_text_field('button_text_color', 'clp_options[button_text_color]', @self::$options['button_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function button_border_color_input() {
		
		a5_text_field('button_border_color', 'clp_options[button_border_color]', @self::$options['button_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_bg_color1_input() {
		
		a5_text_field('btn_hover_bg_color1', 'clp_options[btn_hover_bg_color1]', @self::$options['btn_hover_bg_color1'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_bg_color2_input() {
		
		a5_text_field('btn_hover_bg_color2', 'clp_options[btn_hover_bg_color2]', @self::$options['btn_hover_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_text_color_input() {
		
		a5_text_field('btn_hover_text_color', 'clp_options[btn_hover_text_color]', @self::$options['btn_hover_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_border_color_input() {
		
		a5_text_field('btn_hover_border_color', 'clp_options[btn_hover_border_color]', @self::$options['btn_hover_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	// logo tab
	
	function clp_logo_section() {
		
		self::tag_it(__('Just upload any picture (best is a png or gif with transparent background) via the uploader.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the URL field, you enter the URL to which the logo should link.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function hide_logo_input($labels) {
	
		a5_checkbox('hide_logo', 'clp_options[hide_logo]', @self::$options['hide_logo'], $labels[0]);
	
	}
	
	function logo_url_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-logo', 'logo', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'logo_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['logo'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'logo_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-logo', 'logo', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'logo_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
				
		a5_url_field('logo_url', 'clp_options[logo]', @self::$options['logo'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function link_url_input() {
		
		a5_url_field('url', 'clp_options[url]', @self::$options['url'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => home_url('/')));
		
	}
	
	function logo_title_input() {
		
		a5_text_field('title', 'clp_options[title]', @self::$options['title'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function clp_logo_size_section() {
		
		self::tag_it(__('If your logo is larger than the default WP-logo (84px by 84px), you can enter the width and the height of it here.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('The width and height of the logo-container are by default 84px and 84px. They are used to move the Logo around, since the background-position is always &#39;center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function logo_width_input() {
		
		a5_number_field('logo_width', 'clp_options[logo_width]', @self::$options['logo_width'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function logo_height_input() {
		
		a5_number_field('logo_height', 'clp_options[logo_height]', @self::$options['logo_height'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function h1_width_input() {
		
		a5_number_field('h1_width', 'clp_options[h1_width]', @self::$options['h1_width'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function h1_height_input() {
		
		a5_number_field('h1_height', 'clp_options[h1_height]', @self::$options['h1_height'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function h1_margin_input() {
		
		a5_text_field('h1_margin', 'clp_options[h1_margin]', @self::$options['h1_margin']);
		
	}
	
	function h1_padding_input() {
		
		a5_text_field('h1_padding', 'clp_options[h1_padding]', @self::$options['h1_padding']);
	
	}
	
	function clp_logo_style_section() {
		
		self::tag_it(__('Here you can style the logo a bit. Give it a shadow or round corners if you like.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function h1_corner_input() {
		
		a5_number_field('h1_corner', 'clp_options[h1_corner]', @self::$options['h1_corner'], false, array('step' => 1, 'min' => 0));
		
	}
		
	function h1_shadow_x_input() {
		
		a5_number_field('h1_shadow_x', 'clp_options[h1_shadow_x]', @self::$options['h1_shadow_x'], false, array('step' => 1));
		
	}
	
	function h1_shadow_y_input() {
		
		a5_number_field('h1_shadow_y', 'clp_options[h1_shadow_y]', @self::$options['h1_shadow_y'], false, array('step' => 1));
		
	}
	
	function h1_shadow_softness_input() {
		
		a5_number_field('h1_shadow_softness', 'clp_options[h1_shadow_softness]', @self::$options['h1_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function h1_shadow_color_input() {
		
		a5_text_field('h1_shadow_color', 'clp_options[h1_shadow_color]', @self::$options['h1_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	function h1_shadow_inset_input() {
	
		a5_checkbox('h1_shadow_inset', 'clp_options[h1_shadow_inset]', ' inset', false, false, @self::$options['h1_shadow_inset']);
	
	}
	
	//video tab
	
	function clp_video_section() {
		
		self::tag_it(__('Just upload any video via the uploader.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Give it a width and height if you don&#39;t want to use the default settings of WP.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function video_url_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		global $A5_CustomLoginPage;
		
		if (function_exists('wp_enqueue_media')) :
		
			$video_preview = (empty(self::$options['video'])) ? '' : $A5_CustomLoginPage->print_video();
		
			self::tag_it(a5_button('upload-video', 'video', __('Select Video', 'custom-login-page'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'video_upload', 'style' => 'display: none;'), true);
			
			self::tag_it($video_preview, 'div', 1, array('id' => 'video_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-video', 'video', __('Remove Video', 'custom-login-page'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'video_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
				
		a5_url_field('video_url', 'clp_options[video]', @self::$options['video'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function video_width_input() {
		
		a5_number_field('video_width', 'clp_options[video_width]', @self::$options['video_width'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function video_height_input() {
		
		a5_number_field('video_height', 'clp_options[video_height]', @self::$options['video_height'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function clp_video_parameter_section() {
		
		self::tag_it(__('You can enter a couple of parameters for the video here.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('However, this is absolute in beta stage and I don&#39;t really know what the parameters do.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Leave everything empty to fall back to the default values of WordPress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
		
	function video_poster_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-poster', 'poster', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'poster_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['video_poster'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'poster_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-poster', 'poster', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'poster_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
				
		a5_url_field('poster_url', 'clp_options[video_poster]', @self::$options['video_poster'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function video_loop_input() {
	
		a5_checkbox('video_loop', 'clp_options[video_loop]', @self::$options['video_loop']);
	
	}
	
	function video_autoplay_input() {
	
		a5_checkbox('video_autoplay', 'clp_options[video_autoplay]', @self::$options['video_autoplay']);
	
	}
	
	function video_preload_input() {
		
		$options = array(
			array('auto', 'auto'),
			array('metadata', 'metadata'),
			array('none', 'none')
		);	

		a5_select('video_preload', 'clp_options[video_preload]', $options, @self::$options['video_preload']);
		
	}
	
	function video_class_input() {	

		a5_text_field('video_class', 'clp_options[video_class]', @self::$options['video_class']);
		
	}
	
	function video_id_input() {	

		a5_text_field('video_id', 'clp_options[video_id]', @self::$options['video_id']);
		
	}
	
	// logindiv tab
	
	function clp_logindiv_section() {
		
		self::tag_it(__('Just upload any picture via the uploader. Leave it empty, if you don&#39;t want a picture. Background images are tiled by default. You can select the direction of repeating the image or to not repeat it. The position of the image can be something like &#39;100px 50%&#39; or &#39;center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the next section, you choose the background colour and the colour of the text in the login container. If you give two background colours, you can create a gradient. Colour no. 1 will always be up.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Choose a border, if wanting one. Define style, width and whether or not, you want to have rounded corners (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('At last, give the container a shadow (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function logindiv_background_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-logindiv', 'logindiv', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'logindiv_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['logindiv_background'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'logindiv_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-logindiv', 'logindiv', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'logindiv_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
				
		a5_url_field('logindiv_url', 'clp_options[logindiv_background]', @self::$options['logindiv_background'], false, array('style' => 'min-width: 350px; max-width: 500px;'));	
		
	}
	
	function logindiv_img_repeat_input() {
		
		$options = array(array('no-repeat', 'no-repeat'), array('repeat-x', 'repeat-x'), array('repeat-y', 'repeat-y'));
		
		a5_select('logindiv_img_repeat', 'clp_options[logindiv_img_repeat]', $options, @self::$options['logindiv_img_repeat'], false, __('default', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function logindiv_img_pos_input() {
		
		a5_text_field('logindiv_img_pos', 'clp_options[logindiv_img_pos]', @self::$options['logindiv_img_pos']);
		
	}
		
	function logindiv_bg_color1_input() {
		
		a5_text_field('logindiv_bg_color1', 'clp_options[logindiv_bg_color1]', @self::$options['logindiv_bg_color1'], false, array('class' => 'color-picker'));
		
	}
		
	function logindiv_bg_color2_input() {	

		a5_text_field('logindiv_bg_color2', 'clp_options[logindiv_bg_color2]', @self::$options['logindiv_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function logindiv_bg_size_input() {
		
		a5_text_field('logindiv_bg_size', 'clp_options[logindiv_bg_size]', @self::$options['logindiv_bg_size'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function logindiv_text_color_input() {
	
		a5_text_field('logindiv_text_color', 'clp_options[logindiv_text_color]', @self::$options['logindiv_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function logindiv_transparency_input() {
		
		a5_number_field('logindiv_transparency', 'clp_options[logindiv_transparency]', @self::$options['logindiv_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function logindiv_border_style_input() {
		
		$border_style = array(array('none', 'none'), array('dotted', 'dotted'), array('dashed', 'dashed'), array('solid', 'solid'), array('double', 'double'), array('groove', 'groove'), array('ridge', 'rigde'), array('inset', 'inset'), array('outset', 'outset'));
		
		a5_select('logindiv_border_style', 'clp_options[logindiv_border_style]', $border_style, @self::$options['logindiv_border_style'], false, __('choose a border style', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function logindiv_border_width_input() {
		
		a5_number_field('logindiv_border_width', 'clp_options[logindiv_border_width]', @self::$options['logindiv_border_width'], false, array('step' => 1, 'min' => 1));
		
	}
	
	function logindiv_border_color_input() {
		
		a5_text_field('logindiv_border_color', 'clp_options[logindiv_border_color]', @self::$options['logindiv_border_color'], false, array('class' => 'color-picker'));
	
	}
	
	function logindiv_border_round_input() {
		
		a5_number_field('logindiv_border_round', 'clp_options[logindiv_border_round]', @self::$options['logindiv_border_round'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function logindiv_shadow_x_input() {
		
		a5_number_field('logindiv_shadow_x', 'clp_options[logindiv_shadow_x]', @self::$options['logindiv_shadow_x'], false, array('step' => 1));
		
	}
		
	function logindiv_shadow_y_input() {
		
		a5_number_field('logindiv_shadow_y', 'clp_options[logindiv_shadow_y]', @self::$options['logindiv_shadow_y'], false, array('step' => 1));
		
	}
	
	function logindiv_shadow_softness_input() {
			
		a5_number_field('logindiv_shadow_softness', 'clp_options[logindiv_shadow_softness]', @self::$options['logindiv_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function logindiv_shadow_color_input() {
		
		a5_text_field('logindiv_shadow_color', 'clp_options[logindiv_shadow_color]', @self::$options['logindiv_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function logindiv_shadow_inset_input() {
	
		a5_checkbox('logindiv_shadow_inset', 'clp_options[logindiv_shadow_inset]', ' inset', false, false, @self::$options['logindiv_shadow_inset']);
	
	}
	
	function clp_logindiv_pos_section() {
		
		self::tag_it(__('Here you can give the whole login container a position. If you enter &#39;0&#39; in both of the fields, it will be in the top left corner of the screen.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('The Padding and Margin are given as css value. I.e. &#39;144px 0 0&#39; (which is the default padding of the login container).', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function logindiv_left_input() {
		
		a5_number_field('logindiv_left', 'clp_options[logindiv_left]', @self::$options['logindiv_left'], false, array('step' => 1));
			
	}
	
	function logindiv_top_input() {
		
		a5_number_field('logindiv_top', 'clp_options[logindiv_top]', @self::$options['logindiv_top'], false, array('step' => 1));
		
	}
	
	function logindiv_width_input() {
		
		a5_number_field('logindiv_width', 'clp_options[logindiv_width]', @self::$options['logindiv_width'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function logindiv_height_input() {
		
		a5_number_field('logindiv_height', 'clp_options[logindiv_height]', @self::$options['logindiv_height'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function logindiv_padding_input() {
		
		a5_text_field('logindiv_padding', 'clp_options[logindiv_padding]', @self::$options['logindiv_padding']);
		
	}
	
	function logindiv_margin_input() {
		
		a5_text_field('logindiv_margin', 'clp_options[logindiv_margin]', @self::$options['logindiv_margin']);
		
	}
	
	// loginform tab
	
	function clp_loginform_section() {
		
		self::tag_it(__('Just upload any picture via the uploader. Leave it empty, if you don&#39;t want a picture. Background images are tiled by default. You can select the direction of repeating the image or to not repeat it. The position of the image can be something like &#39;100px 50%&#39; or &#39center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the next section, you choose the background colour and the colour of the text in the login form. If you give two background colours, you can create a gradient. Colour no. 1 will always be up.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Choose a border, if wanting one. Define style, width and whether or not, you want to have rounded corners (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Margin and Padding are given as css values. The form has a left margin of 8px by default and a padding of 26px 24px 46px. By changing the top and the bottom padding, you can stretch the form in its length.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('At last, give the form a shadow (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function loginform_background_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-loginform', 'loginform', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'loginform_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['loginform_background'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'loginform_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-loginform', 'loginform', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'loginform_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
				
		a5_url_field('loginform_url', 'clp_options[loginform_background]', @self::$options['loginform_background'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function loginform_img_repeat_input() {
		
		$options = array(array('no-repeat', 'no-repeat'), array('repeat-x', 'repeat-x'), array('repeat-y', 'repeat-y'));
		
		a5_select('loginform_img_repeat', 'clp_options[loginform_img_repeat]', $options, @self::$options['loginform_img_repeat'], false, __('default', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function loginform_img_pos_input() {
		
		a5_text_field('loginform_img_pos', 'clp_options[loginform_img_pos]', @self::$options['loginform_img_pos']);
		
	}
		
	function loginform_bg_color1_input() {
		
		a5_text_field('loginform_bg_color1', 'clp_options[loginform_bg_color1]', @self::$options['loginform_bg_color1'], false, array('class' => 'color-picker'));
		
	}
		
	function loginform_bg_color2_input() {	

		a5_text_field('loginform_bg_color2', 'clp_options[loginform_bg_color2]', @self::$options['loginform_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function loginform_bg_size_input() {
		
		a5_text_field('loginform_bg_size', 'clp_options[loginform_bg_size]', @self::$options['loginform_bg_size'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function loginform_text_color_input() {
	
		a5_text_field('loginform_text_color', 'clp_options[loginform_text_color]', @self::$options['loginform_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function loginform_transparency_input() {
		
		a5_number_field('loginform_transparency', 'clp_options[loginform_transparency]', @self::$options['loginform_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function loginform_border_style_input() {
		
		$border_style = array(array('none', 'none'), array('dotted', 'dotted'), array('dashed', 'dashed'), array('solid', 'solid'), array('double', 'double'), array('groove', 'groove'), array('ridge', 'rigde'), array('inset', 'inset'), array('outset', 'outset'));
		
		a5_select('loginform_border_style', 'clp_options[loginform_border_style]', $border_style, @self::$options['loginform_border_style'], false, __('choose a border style', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function loginform_border_width_input() {
		
		a5_number_field('loginform_border_width', 'clp_options[loginform_border_width]', @self::$options['loginform_border_width'], false, array('step' => 1, 'min' => 1));
		
	}
	
	function loginform_border_color_input() {
		
		a5_text_field('loginform_border_color', 'clp_options[loginform_border_color]', @self::$options['loginform_border_color'], false, array('class' => 'color-picker'));
	
	}
	
	function loginform_border_round_input() {
		
		a5_number_field('loginform_border_round', 'clp_options[loginform_border_round]', @self::$options['loginform_border_round'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function loginform_padding_input() {
		
		a5_text_field('loginform_padding', 'clp_options[loginform_padding]', @self::$options['loginform_padding']);
		
	}
	
	function loginform_margin_input() {
		
		a5_text_field('loginform_margin', 'clp_options[loginform_margin]', @self::$options['loginform_margin']);
		
	}
	
	function loginform_shadow_x_input() {
		
		a5_number_field('loginform_shadow_x', 'clp_options[loginform_shadow_x]', @self::$options['loginform_shadow_x'], false, array('step' => 1));
		
	}
		
	function loginform_shadow_y_input() {
		
		a5_number_field('loginform_shadow_y', 'clp_options[loginform_shadow_y]', @self::$options['loginform_shadow_y'], false, array('step' => 1));
		
	}
	
	function loginform_shadow_softness_input() {
			
		a5_number_field('loginform_shadow_softness', 'clp_options[loginform_shadow_softness]', @self::$options['loginform_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function loginform_shadow_color_input() {
		
		a5_text_field('loginform_shadow_color', 'clp_options[loginform_shadow_color]', @self::$options['loginform_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function loginform_shadow_inset_input() {
	
		a5_checkbox('loginform_shadow_inset', 'clp_options[loginform_shadow_inset]', ' inset', false, false, @self::$options['loginform_shadow_inset']);
	
	}
	
	// message tab
	
	function clp_logout_message_section() {
		
		self::tag_it(__('This changes the the text container, that appears, when you have successfully logged out.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
		
	function loggedout_text_color_input() {
		
		a5_text_field('loggedout_text_color', 'clp_options[loggedout_text_color]', @self::$options['loggedout_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function loggedout_bg_color_input() {
			
		a5_text_field('loggedout_bg_color', 'clp_options[loggedout_bg_color]', @self::$options['loggedout_bg_color'], false, array('class' => 'color-picker'));
		
	}
		
	function loggedout_border_color_input() {
			
		a5_text_field('loggedout_border_color', 'clp_options[loggedout_border_color]', @self::$options['loggedout_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function loggedout_transparency_input() {
			
		a5_number_field('loggedout_transparency', 'clp_options[loggedout_transparency]', @self::$options['loggedout_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function clp_error_message_section() {
		
		self::tag_it(__('This changes the text container, that appears, when you get an error logging in.', 'custom-login-page'), 'p', 1, false, true);
		
	}
		
	function error_text_color_input() {
		
		a5_text_field('error_text_color', 'clp_options[error_text_color]', @self::$options['error_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function error_bg_color_input() {
			
		a5_text_field('error_bg_color', 'clp_options[error_bg_color]', @self::$options['error_bg_color'], false, array('class' => 'color-picker'));
		
	}
		
	function error_border_color_input() {
			
		a5_text_field('error_border_color', 'clp_options[error_border_color]', @self::$options['error_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function error_transparency_input() {
			
		a5_number_field('error_transparency', 'clp_options[error_transparency]', @self::$options['error_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function clp_input_section() {
		
		self::tag_it(__('This changes the colours of the name and password fields of the log in form.', 'custom-login-page'), 'p', 1, false, true);
		
	}
		
	function input_text_color_input() {
		
		a5_text_field('input_text_color', 'clp_options[input_text_color]', @self::$options['input_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function input_bg_color_input() {
			
		a5_text_field('input_bg_color', 'clp_options[input_bg_color]', @self::$options['input_bg_color'], false, array('class' => 'color-picker'));
		
	}
		
	function input_border_color_input() {
			
		a5_text_field('input_border_color', 'clp_options[input_border_color]', @self::$options['input_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function input_shadow_x_input() {
		
		a5_number_field('input_shadow_x', 'clp_options[input_shadow_x]', @self::$options['input_shadow_x'], false, array('step' => 1));
		
	}
		
	function input_shadow_y_input() {
		
		a5_number_field('input_shadow_y', 'clp_options[input_shadow_y]', @self::$options['input_shadow_y'], false, array('step' => 1));
		
	}
	
	function input_shadow_softness_input() {
			
		a5_number_field('input_shadow_softness', 'clp_options[input_shadow_softness]', @self::$options['input_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function input_shadow_color_input() {
		
		a5_text_field('input_shadow_color', 'clp_options[input_shadow_color]', @self::$options['input_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function input_shadow_inset_input() {
	
		a5_checkbox('input_shadow_inset', 'clp_options[input_shadow_inset]', ' inset', false, false, @self::$options['input_shadow_inset']);
	
	}
	
	// link tab
	
	function clp_link_section() {
	
		self::tag_it(__('Style the links by giving a text colour, text decoration and shadow for the link and the hover style.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(sprintf(__('For the font size, give a css value, such as %1$s or %2$s.', 'custom-login-page'), '<em>&#39;12px&#39;</em>', '<em>&#39;1em&#39;</em>'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function link_size_input() {
		
		a5_text_field('link_size', 'clp_options[link_size]', @self::$options['link_size']);
		
	}
	
	function link_text_color_input() {
			
		a5_text_field('link_text_color', 'clp_options[link_text_color]', @self::$options['link_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function link_textdecoration_input() {
		
		$textdeco = array(array('none', 'none'), array('underline', 'underline'), array('overline', 'overline'), array('line-through', 'line-through'), array('blink', 'blink'));
		
		a5_select('link_textdecoration', 'clp_options[link_textdecoration]', $textdeco, @self::$options['link_textdecoration'], false, false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function link_shadow_x_input() {
		
		a5_number_field('link_shadow_x', 'clp_options[link_shadow_x]', @self::$options['link_shadow_x'], false, array('step' => 1));
		
	}
	
	function link_shadow_y_input() {
		
		a5_number_field('link_shadow_y', 'clp_options[link_shadow_y]', @self::$options['link_shadow_y'], false, array('step' => 1));
		
	}
	
	function link_shadow_softness_input() {
		
		a5_number_field('link_shadow_softness', 'clp_options[link_shadow_softness]', @self::$options['link_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function link_shadow_color_input() {
		
		a5_text_field('link_shadow_color', 'clp_options[link_shadow_color]', @self::$options['link_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	function clp_hover_section() {
	
		self::tag_it(__('The same for the hover state.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function hover_text_color_input() {
			
		a5_text_field('hover_text_color', 'clp_options[hover_text_color]', @self::$options['hover_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function hover_textdecoration_input() {
		
		$textdeco = array(array('none', 'none'), array('underline', 'underline'), array('overline', 'overline'), array('line-through', 'line-through'), array('blink', 'blink'));
		
		a5_select('hover_textdecoration', 'clp_options[hover_textdecoration]', $textdeco, @self::$options['hover_textdecoration'], false, false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function hover_shadow_x_input() {
		
		a5_number_field('hover_shadow_x', 'clp_options[hover_shadow_x]', @self::$options['hover_shadow_x'], false, array('step' => 1));
		
	}
	
	function hover_shadow_y_input() {
		
		a5_number_field('hover_shadow_y', 'clp_options[hover_shadow_y]', @self::$options['hover_shadow_y'], false, array('step' => 1));
		
	}
	
	function hover_shadow_softness_input() {
		
		a5_number_field('hover_shadow_softness', 'clp_options[hover_shadow_softness]', @self::$options['hover_shadow_softness'], false, array('step' => 1, 'min' => 0));
		
	}
	
	function hover_shadow_color_input() {
		
		a5_text_field('hover_shadow_color', 'clp_options[hover_shadow_color]', @self::$options['hover_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	// css tab
	
	function clp_css_section() {
		
		self::tag_it(__('Here you can enter some css. You either can enter an entire style sheet or just some additional css.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('This gives you much more freedom with styling your login widget than the rather foolproof but very limited options .', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can of course copy the style sheet written by the plugin, paste it here and start finetuning.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy'), 'secondary', 'clp_options[copy_stylesheet]', true, array('id' => 'copy_stylesheet'));
		
	}
	
	function css_input() {
	
		a5_textarea('css', 'clp_options[css]', @self::$options['css'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function override_input($labels) {
		
		a5_checkbox('override', 'clp_options[override]', @self::$options['override'], $labels[0]);
		
	}
	
	function clp_svg_section() {
		
		self::tag_it(__('Here you can enter some svg, i.e. filter rules to use in Firefox. In case you wish to use the svg as an image, it will be just above the login form.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function svg_input() {
	
		a5_textarea('svg', 'clp_options[svg]', @self::$options['svg'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function css_resize_field() {
		
		a5_resize_textarea(array('css', 'svg'), true);
		
	}
	
	// html tab
	
	function clp_html_section() {
		
		self::tag_it(__('If you want to have some additional html in your login page, there are three places to put it. Above the form, in the form and under it.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can use the additional css to style the html snippets that you enter here.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function login_message_input() {
	
		a5_textarea('login_message', 'clp_options[login_message]', @self::$options['login_message'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function login_form_input() {
	
		a5_textarea('login_form', 'clp_options[login_form]', @self::$options['login_form'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function login_footer_input() {
	
		a5_textarea('login_footer', 'clp_options[login_footer]', @self::$options['login_footer'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function html_resize_field() {
		
		a5_resize_textarea(array('login_message', 'login_form', 'login_footer'), true);
		
	}
	
	/**
	 *
	 * Actually build the option pages
	 *
	 */
	function build_options_page() {
		
		// tabed browsing
		
		$default_tab = (isset(self::$options['last_open'])) ? self::$options['last_open'] : 'main_tab';
		
		$active = (isset($_GET['tab'])) ? $_GET['tab'] : $default_tab;
		
		// this is only necessary if the plugin is activated for network
		
		if (@$_GET['action'] == 'update') :
		
			$input = $_POST['clp_options'];
			
			self::$options = $this->validate($input);
			
			update_site_option('clp_options', self::$options);
			
			$this->initialize_settings();
		
		endif;
		
		// the main options page begins here
		
		$eol = "\n";
		
		$tab = "\t";
		
		$dtab = $tab.$tab;
		
		// navigation
		
		self::open_page('A5 Custom Login Page', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/a5-custom-login-page', 'custom-login-page'), 'custom-login-page');
		
		settings_errors();
		
		$tabs ['main_tab'] = array( 'class' => ($active == 'main_tab') ? ' nav-tab-active' : '', 'text' => __('General Options', 'custom-login-page'));
		$tabs ['advanced_tab'] = array( 'class' => ($active == 'advanced_tab') ? ' nav-tab-active' : '', 'text' => __('Advanced Options', 'custom-login-page'));
		$tabs ['body_tab'] = array( 'class' => ($active == 'body_tab') ? ' nav-tab-active' : '', 'text' => __('Body & Submit Button', 'custom-login-page'));
		$tabs ['logo_tab'] = array( 'class' => ($active == 'logo_tab') ? ' nav-tab-active' : '', 'text' => __('Logo', 'custom-login-page'));
		$tabs ['video_tab'] = array( 'class' => ($active == 'video_tab') ? ' nav-tab-active' : '', 'text' => __('Video', 'custom-login-page'));
		$tabs ['logindiv_tab'] = array( 'class' => ($active == 'logindiv_tab') ? ' nav-tab-active' : '', 'text' => __('Login Container', 'custom-login-page'));
		$tabs ['loginform_tab'] = array( 'class' => ($active == 'loginform_tab') ? ' nav-tab-active' : '', 'text' => __('Login Form', 'custom-login-page'));
		$tabs ['message_tab'] = array( 'class' => ($active == 'message_tab') ? ' nav-tab-active' : '', 'text' => __('Messages & Input Fields', 'custom-login-page'));
		$tabs ['link_tab'] = array( 'class' => ($active == 'link_tab') ? ' nav-tab-active' : '', 'text' => __('Links', 'custom-login-page'));
		$tabs ['css_tab'] = array( 'class' => ($active == 'css_tab') ? ' nav-tab-active' : '', 'text' => __('CSS', 'custom-login-page'));
		$tabs ['html_tab'] = array( 'class' => ($active == 'html_tab') ? ' nav-tab-active' : '', 'text' => __('Additional HTML', 'custom-login-page'));
		if (!is_multisite()) $tabs ['preview_tab'] = array( 'id' => 'prev', 'class' => ($active == 'preview_tab') ? ' nav-tab-active' : ' thickbox', 'text' => __('Preview'));
		
		$args = array(
			'page' => 'clp-settings',
			'menu_items' => $tabs
		);
		
		self::nav_menu($args);

		$action = (is_plugin_active_for_network(CLP_BASE)) ? '?page=clp-settings&tab='.$active.'&action=update' : 'options.php';
		
		self::open_form($action);
		
		// nonce and stuff which is the same for all tabs
		
		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
		
		a5_hidden_field('tab', 'clp_options[tab]', $active, true);
		
		settings_fields('clp_options');
		
		if (!is_multisite()) add_thickbox();

		// the actual option tabs
		
		if ($active == 'main_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Logged Out and Error Messages', 'custom-login-page'), 'main-options', 'clp_message'));
			
			self::sortable('middle', self::postbox(__('Import / Export', 'custom-login-page'), 'impex', array('clp_export', 'clp_import')));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'advanced_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Include Header & Footer from Frontend', 'custom-login-page'), 'include-frontend', 'clp_blog'));
			
			self::sortable('upper-middle', self::postbox(__('Hide Links', 'custom-login-page'), 'hide-links', 'clp_hide'));
			
			self::sortable('lower-middle', self::postbox(__('Debug dynamical CSS', 'custom-login-page'), 'debug-css', 'clp_debug'));
			
			self::sortable('bottom', self::postbox(__('Custom Redirects', 'custom-login-page'), 'redirect', 'clp_redirect'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'body_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Body', 'custom-login-page'), 'body', 'clp_body'));
			
			self::sortable('middle', self::postbox(__('Submit Button', 'custom-login-page'), 'submit-button', 'clp_button'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'logo_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Logo of the Login Screen', 'custom-login-page'), 'logo', 'clp_logo'));
			
			self::sortable('middle', self::postbox(__('Position and Size of the Logo', 'custom-login-page'), 'logo-pos', 'clp_logo_size'));
			
			self::sortable('bottom', self::postbox(__('Styling of the Logo', 'custom-login-page'), 'logo-style', 'clp_logo_style'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();;
			
		endif;
		
		if ($active == 'video_tab') :
		
			self::open_tab();
			
			a5_hidden_field('ajax_nonce', 'ajax_nonce', wp_create_nonce('zwetschgenbremmel'));
			
			self::sortable('top', self::postbox(__('Video above the Login Lorm', 'custom-login-page'), 'video', 'clp_video'));
			
			self::sortable('middle', self::postbox(__('Additional Parameters', 'custom-login-page'), 'video-paameters', 'clp_video_parameters'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();;
			
		endif;
		
		if ($active == 'logindiv_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Login Container', 'custom-login-page'), 'login', 'clp_logindiv'));
			
			self::sortable('middle', self::postbox(__('Position and Size of the Login Container', 'custom-login-page'), 'login-pos', 'clp_logindiv_pos'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'loginform_tab') :
		
			self::open_tab('clp', 'loginform');
			
			self::sortable('top', self::postbox(__('Login Form', 'custom-login-page'), 'form', 'clp_loginform'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'message_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Logout Message', 'custom-login-page'), 'message-logout', 'clp_logout_message'));
			
			self::sortable('middle', self::postbox(__('Error Message', 'custom-login-page'), 'message-error', 'clp_error_message'));
			
			self::sortable('bottom', self::postbox(__('Input Fields', 'custom-login-page'), 'fields', 'clp_input'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'link_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('Links', 'custom-login-page'), 'link', 'clp_link'));
			
			self::sortable('middel', self::postbox(__('Links Hover', 'custom-login-page'), 'link-hover', 'clp_hover'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'css_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('CSS and SVG', 'custom-login-page'), 'css-svg', array('clp_css', 'clp_svg')));
		
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			$elements = array('body.login', 
				'body.login div#login',
				'body.login div#login h1',
				'body.login div#login h1 a',
				'body.login div#login form#loginform',
				'body.login div#login form#loginform p',
				'body.login div#login form#loginform p label',
				'body.login div#login form#loginform input',
				'body.login div#login form#loginform input#user_login',
				'body.login div#login form#loginform input#user_pass',
				'body.login div#login form#loginform p.forgetmenot',
				'body.login div#login form#loginform p.forgetmenot input#rememberme',
				'body.login div#login form#loginform p.submit',
				'body.login div#login form#loginform p.submit input#wp-submit',
				'body.login div#login p#nav',
				'body.login div#login p#nav a',
				'body.login div#login p#backtoblog',
				'body.login div#login p#backtoblog a');
		
			$content = self::tag_it(__('To be able to use your own css it is important to know, what elements you actually can style on the login page. In the list below you find all neccessary selectors for your style sheet.', 'custom-login-page'), 'p');
			
			$content .= self::tag_it(self::list_it($elements, false, false, false, false), 'b');
			
			$content .= self::tag_it(__('In order to override the original styles, your selectors have to be more precise than the original ones. By using the ones in the list exactly how they are there, you should be fine.', 'custom-login-page'), 'p');
			
			self::column('1');
			
			self::sortable('side_top', self::help_box($content, __('CSS Help', 'custom-login-page')));
			
			$donationtext = self::tag_it(__('If you like the plugin and find it useful, you might think of rewarding the dozens of hours of work that were spent creating it.', 'custom-login-page'), 'p');
			
			self::sortable('side_middle', self::donation_box($donationtext, __('Donations', 'custom-login-page'), '32XGSBKTQNNHA', 'http%3A%2F%2Fwasistlos.waldemarstoffel.com%2Fplugins-fur-wordpress%2Fa5-custom-login-page'));
			
			self::close_tab();
			
		endif;
		
		if ($active == 'html_tab') :
		
			self::open_tab();
			
			self::sortable('top', self::postbox(__('HTML additions', 'custom-login-page'), 'html-additions', 'clp_html'));
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			submit_button();
			
			self::close_tab();
			
		endif;
		
		if ($active == 'preview_tab') :
		
			self::open_tab();
			
			echo self::open_sortable('top');
			
			echo self::open_postbox(__('Preview'), 'preview-result');
			
			echo '<iframe src="'.wp_login_url().'" sandbox="" style="width: 100%; height: 650px;"></iframe>';
		
			echo self::close_postbox();
			
			if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
			echo self::close_sortable();
			
			self::close_tab();
			
		endif;
		
	}
	
	/**
	 *
	 * Validate the options and handle the import - export stuff
	 *
	 */
		
	function validate($input) {
		
		if (isset($input['import']) && !empty($input['import'])) :
		
			$import_options = $input['import'];
			
			$options = json_decode($import_options, true);
			
			if ($options['log'] != 'original A5 CLP file' && $options['log'] != 'original A5 CLP Widget file') :
			
				add_settings_error('clp_options', 'failed-on-import', __('This doesn&#39;t seem to be a valid settings file.', 'custom-login-page'), 'error');
				
				unset($options);
				
				return self::$options;
			
			else:
			
				if ($options['log'] == 'original A5 CLP file') :
			
					unset($options['log']);
					
					$options['multisite'] = self::$options['multisite'];
					
					add_settings_error('clp_options', 'success-on-import', __('Settings successfully imported.', 'custom-login-page'), 'updated');
					
					return $options;
					
				else :
				
					unset($options['log']);
				
					add_settings_error('clp_options', 'success-on-import', __('Settings successfully imported.', 'custom-login-page'), 'updated');
					
					self::$options['clp_widget_options'] = $options;
					
					return self::$options;
					
				endif;
			
				$clp_error = true;
			
			endif;
		
		else :
		
			self::$options['last_open'] = $input['tab'];
			
			switch($input['tab']) :
			
				case 'main_tab' :
				
					self::$options['logout_custom_message'] = trim($input['logout_custom_message']);
					self::$options['error_custom_message'] = trim($input['error_custom_message']);
					self::$options['password_custom_message'] = trim($input['password_custom_message']);
					self::$options['register_custom_message'] = trim($input['register_custom_message']);
					
					break;
					
				case 'advanced_tab' :
				
					self::$options['blog_header'] = (@$input['blog_header']) ? true : false;
					self::$options['blog_footer'] = (@$input['blog_footer']) ? true : false;
					self::$options['hide_rememberme'] = (@$input['hide_rememberme']) ? true : false;
					self::$options['disable_reg'] = (@$input['disable_reg']) ? true : false;
					self::$options['disable_pass'] = (@$input['disable_pass']) ? true : false;
					self::$options['hide_backlink'] = (@$input['hide_backlink']) ? true : false;
					self::$options['compress'] = (@$input['compress']) ? true : false;
					self::$options['inline'] = (@$input['inline']) ? true : false;
					self::$options['priority'] = @trim($input['priority']);
					self::$options['custom_redirect'] = @$input['custom_redirect'];
					self::$options['hide_backend'] = @$input['hide_backend'];
					
					break;
					
				case 'body_tab' :
					
					self::$options['body_background'] = trim($input['body_background']);
					self::$options['body_img_repeat'] = trim($input['body_img_repeat']);
					self::$options['body_img_pos'] = trim($input['body_img_pos']);
					self::$options['body_bg_color1'] = trim($input['body_bg_color1']);
					self::$options['body_bg_color2'] = trim($input['body_bg_color2']);
					self::$options['body_bg_size'] = trim($input['body_bg_size']);
					self::$options['button_bg_color1'] = trim($input['button_bg_color1']);
					self::$options['button_bg_color2'] = trim($input['button_bg_color2']);
					self::$options['button_text_color'] = trim($input['button_text_color']);
					self::$options['button_border_color'] = trim($input['button_border_color']);
					self::$options['btn_hover_bg_color1'] = trim($input['btn_hover_bg_color1']);
					self::$options['btn_hover_bg_color2'] = trim($input['btn_hover_bg_color2']);
					self::$options['btn_hover_text_color'] = trim($input['btn_hover_text_color']);
					self::$options['btn_hover_border_color'] = trim($input['btn_hover_border_color']);
					
					break;
					
				case 'logo_tab' :
				
					self::$options['hide_logo'] = (@$input['hide_logo']) ? true : false;
					self::$options['logo'] = trim($input['logo']);
					self::$options['url'] = trim($input['url']);
					self::$options['title'] = trim($input['title']);
					self::$options['logo_width'] = trim($input['logo_width']);
					self::$options['logo_height'] = trim($input['logo_height']);
					self::$options['h1_width'] = trim($input['h1_width']);
					self::$options['h1_height'] = trim($input['h1_height']);
					self::$options['h1_margin'] = trim($input['h1_margin']);
					self::$options['h1_padding'] = trim($input['h1_padding']);
					self::$options['h1_corner'] = trim($input['h1_corner']);
					self::$options['h1_shadow_x'] = trim($input['h1_shadow_x']);
					self::$options['h1_shadow_y'] = trim($input['h1_shadow_y']);
					self::$options['h1_shadow_softness'] = trim($input['h1_shadow_softness']);
					self::$options['h1_shadow_color'] = trim($input['h1_shadow_color']);
					self::$options['h1_shadow_inset'] = @$input['h1_shadow_inset'];
					
					break;
					
				case 'video_tab' :
				
					self::$options['video'] = trim($input['video']);
					self::$options['video_width'] = trim($input['video_width']);
					self::$options['video_height'] = trim($input['video_height']);
					self::$options['video_poster'] = trim($input['video_poster']);
					self::$options['video_loop'] = (@$input['video_loop']) ? true : NULL;
					self::$options['video_autoplay'] = (@$input['video_autoplay']) ? true : NULL;
					self::$options['video_preload'] = trim($input['video_preload']);
					self::$options['video_class'] = trim($input['video_class']);
					self::$options['video_id'] = trim($input['video_id']);
					
					break;
					
				case 'logindiv_tab' :
				
					self::$options['logindiv_background'] = trim($input['logindiv_background']);
					self::$options['logindiv_img_repeat'] = trim($input['logindiv_img_repeat']);
					self::$options['logindiv_img_pos'] = trim($input['logindiv_img_pos']);
					self::$options['logindiv_bg_color1'] = trim($input['logindiv_bg_color1']);
					self::$options['logindiv_bg_color2'] = trim($input['logindiv_bg_color2']);
					self::$options['logindiv_bg_size'] = trim($input['logindiv_bg_size']);
					self::$options['logindiv_text_color'] = trim($input['logindiv_text_color']);
					self::$options['logindiv_transparency'] = trim($input['logindiv_transparency']);
					self::$options['logindiv_border_style'] = trim($input['logindiv_border_style']);
					self::$options['logindiv_border_width'] = trim($input['logindiv_border_width']);
					self::$options['logindiv_border_color'] = trim($input['logindiv_border_color']);
					self::$options['logindiv_border_round'] = trim($input['logindiv_border_round']);
					self::$options['logindiv_shadow_x'] = trim($input['logindiv_shadow_x']);
					self::$options['logindiv_shadow_y'] = trim($input['logindiv_shadow_y']);
					self::$options['logindiv_shadow_softness'] = trim($input['logindiv_shadow_softness']);
					self::$options['logindiv_shadow_color'] = trim($input['logindiv_shadow_color']);
					self::$options['logindiv_shadow_inset'] = @$input['logindiv_shadow_inset'];
					self::$options['logindiv_left'] = trim($input['logindiv_left']);
					self::$options['logindiv_top'] = trim($input['logindiv_top']);
					self::$options['logindiv_width'] = trim($input['logindiv_width']);
					self::$options['logindiv_height'] = trim($input['logindiv_height']);
					self::$options['logindiv_padding'] = trim($input['logindiv_padding']);
					self::$options['logindiv_margin'] = trim($input['logindiv_margin']);
				
					break;
					
				case 'loginform_tab' :
				
					self::$options['loginform_background'] = trim($input['loginform_background']);
					self::$options['loginform_img_repeat'] = trim($input['loginform_img_repeat']);
					self::$options['loginform_img_pos'] = trim($input['loginform_img_pos']);
					self::$options['loginform_bg_color1'] = trim($input['loginform_bg_color1']);
					self::$options['loginform_bg_color2'] = trim($input['loginform_bg_color2']);
					self::$options['loginform_bg_size'] = trim($input['loginform_bg_size']);
					self::$options['loginform_text_color'] = trim($input['loginform_text_color']);
					self::$options['loginform_transparency'] = trim($input['loginform_transparency']);				
					self::$options['loginform_border_style'] = trim($input['loginform_border_style']);
					self::$options['loginform_border_width'] = trim($input['loginform_border_width']);
					self::$options['loginform_border_color'] = trim($input['loginform_border_color']);
					self::$options['loginform_border_round'] = trim($input['loginform_border_round']);
					self::$options['loginform_margin'] = trim($input['loginform_margin']);
					self::$options['loginform_padding'] = trim($input['loginform_padding']);				
					self::$options['loginform_shadow_x'] = trim($input['loginform_shadow_x']);
					self::$options['loginform_shadow_y'] = trim($input['loginform_shadow_y']);
					self::$options['loginform_shadow_softness'] = trim($input['loginform_shadow_softness']);
					self::$options['loginform_shadow_color'] = trim($input['loginform_shadow_color']);
					self::$options['loginform_shadow_inset'] = @$input['loginform_shadow_inset'];
				
					break;
					
				case 'message_tab' :
				
					self::$options['loggedout_text_color'] = trim($input['loggedout_text_color']);
					self::$options['loggedout_bg_color'] = trim($input['loggedout_bg_color']);
					self::$options['loggedout_border_color'] = trim($input['loggedout_border_color']);
					self::$options['loggedout_transparency'] = trim($input['loggedout_transparency']);
					self::$options['error_text_color'] = trim($input['error_text_color']);
					self::$options['error_bg_color'] = trim($input['error_bg_color']);
					self::$options['error_border_color'] = trim($input['error_border_color']);
					self::$options['error_transparency'] = trim($input['error_transparency']);
					self::$options['input_text_color'] = trim($input['input_text_color']);
					self::$options['input_bg_color'] = trim($input['input_bg_color']);
					self::$options['input_border_color'] = trim($input['input_border_color']);
					self::$options['input_shadow_x'] = trim($input['input_shadow_x']);
					self::$options['input_shadow_y'] = trim($input['input_shadow_y']);
					self::$options['input_shadow_softness'] = trim($input['input_shadow_softness']);
					self::$options['input_shadow_color'] = trim($input['input_shadow_color']);
					self::$options['input_shadow_inset'] = @$input['input_shadow_inset'];
				
					break;
					
				case 'link_tab' :
				
					self::$options['link_text_color'] = trim($input['link_text_color']);
					self::$options['link_textdecoration'] = trim($input['link_textdecoration']);
					self::$options['link_shadow_x'] = trim($input['link_shadow_x']);
					self::$options['link_shadow_y'] = trim($input['link_shadow_y']);
					self::$options['link_shadow_softness'] = trim($input['link_shadow_softness']);
					self::$options['link_shadow_color'] = trim($input['link_shadow_color']);
					self::$options['hover_text_color'] = trim($input['hover_text_color']);
					self::$options['hover_textdecoration'] = trim($input['hover_textdecoration']);
					self::$options['hover_shadow_x'] = trim($input['hover_shadow_x']);
					self::$options['hover_shadow_y'] = trim($input['hover_shadow_y']);
					self::$options['hover_shadow_softness'] = trim($input['hover_shadow_softness']);
					self::$options['hover_shadow_color'] = trim($input['hover_shadow_color']);
					self::$options['link_size'] = trim($input['link_size']);
				
					break;
					
				case 'css_tab' :
				
					if (isset($input['copy_stylesheet'])) :
						
						self::$options['css'] = CLP_DynamicCSS::$custom_css;
						self::$options['override'] = true;
						
						return self::$options;
					
					endif;
				
					self::$options['css'] = trim($input['css']);
					self::$options['override'] = (@$input['override']) ? true : NULL;
					self::$options['svg'] = trim($input['svg']);
				
					break;
					
				case 'html_tab' :
				
					self::$options['login_message'] = trim($input['login_message']);
					self::$options['login_form'] = trim($input['login_form']);
					self::$options['login_footer'] = trim($input['login_footer']);
				
					break;
			
			endswitch;
			
			if (is_plugin_active_for_network(CLP_BASE)) add_settings_error('clp_options', 'settings_updated', __('Settings saved.'), 'updated');
			
			self::$options['css_cache'] = '';
			
			return self::$options;
			
		endif;
	
	}

} // end of class

?>