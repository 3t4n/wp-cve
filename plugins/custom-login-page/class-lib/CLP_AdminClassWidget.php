<?php

/**
 *
 * Class A5 Custom Widget Admin
 *
 * @ A5 Custom Login Page
 *
 * building admin page for styling of the widget
 *
 */
class clp_WidgetAdmin extends A5_OptionPage {
	
	static $options, $multisite;
	
	function __construct($multisite) {
		
		self::$multisite = $multisite;
		
		add_action('admin_init', array($this, 'initialize_settings'));
		add_action('contextual_help', array($this, 'add_help_text'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));	
		
		if ($multisite) :
		
			add_action('network_admin_menu', array($this, 'add_admin_menu'));
				
			self::$options = get_network_option(NULL, 'clp_widget_options');
			
			// in case the CLP export file was posted here.
		
			if (@array_key_exists('clp_options', self::$options)) :
			
				update_network_option(NULL, 'clp_options', self::$options['clp_options']);
				
				unset(self::$options['clp_options']);
			
			endif;
			
		else :
			
			add_action('admin_menu', array($this, 'add_admin_menu'));
		
			self::$options = get_option('clp_widget_options');
			
			// in case the CLP export file was posted here.
		
			if (@array_key_exists('clp_options', self::$options)) :
			
				self::$options['clp_options']['version'] = A5_CustomLoginPage::version;
			
				update_option('clp_options', self::$options['clp_options']);
				
				unset(self::$options['clp_options']);
			
			endif;
			
		endif;
		
	}
	
	/**
	 *
	 * Add options-page for widget
	 *
	 */
	function add_admin_menu() {
		
		add_submenu_page('clp-settings', 'Custom Login Widget', 'Custom Login Widget', 'administrator', 'clp-widget-settings', array($this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Make all the admin stuff draggable
	 *
	 */
	function enqueue_scripts($hook){
		
		if ('a5-custom-login_page_clp-widget-settings' != $hook) return;
		
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
		
		if ($screen->id != 'a5-custom-login_page_clp-widget-settings') return;
		
		$content = self::tag_it(__('In these settings you will be guided step by step through the process of styling your login widget. The basic options are very foolproof but also very limited.', 'custom-login-page'), 'p');
		$content .= self::tag_it(__('If you are familiar with coding your own css, you can use only the css tab to write your entire style sheet there. Next to the input for the css, you find a help box with all the elements in the widget that you can style.', 'custom-login-page'), 'p');
		$content .= self::tag_it(__('In the preview, you can see approximately how the widget will look. A couple of things will be different on your blog since in the admin screen, the style sheet of your theme is not loaded. Nevertheless, you have a good idea on how the widget will be in the frontend.', 'custom-login-page'), 'p');
		
		$screen->add_help_tab( array(
			'id'      => 'clp-general-help',
			'title'   => __('General'),
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
		
		register_setting('clp_widget_options', 'clp_widget_options', array($this, 'validate'));
		
		// main tab
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_main_section'), 'clp_widget_main');
		
		add_settings_field('clp_widget_label_username', __('Label for Username', 'custom-login-page'), array($this, 'label_username_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_field('clp_widget_label_password', __('Label for Password', 'custom-login-page'), array($this, 'label_password_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_field('clp_widget_label_remember', __('Label for Remember Me', 'custom-login-page'), array($this, 'label_remember_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_field('clp_widget_label_login', __('Label for Submit Button', 'custom-login-page'), array($this, 'label_log_in_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_field('clp_widget_hide_remember', __('Hide Remember Me', 'custom-login-page'), array($this, 'hide_remember_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_field('clp_widget_value_remember', __('Check Remember Me by Default', 'custom-login-page'), array($this, 'value_remember_input'), 'clp_widget_main', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_advanced_section'), 'clp_widget_advanced');
		
		add_settings_field('clp_widget_redirect', __('Redirect', 'custom-login-page'), array($this, 'redirect_input'), 'clp_widget_advanced', 'clp_widget_options', array(__('Enter here the site of your blog to which the user should be redirected, after login. By default this would be the same place where the widget is.', 'custom-login-page')));
		
		add_settings_field('clp_widget_form_id', __('Form ID', 'custom-login-page'), array($this, 'form_id_input'), 'clp_widget_advanced', 'clp_widget_options');
		
		add_settings_field('clp_widget_id_username', __('User Name ID', 'custom-login-page'), array($this, 'id_username_input'), 'clp_widget_advanced', 'clp_widget_options');
		
		add_settings_field('clp_widget_id_password', __('Password ID', 'custom-login-page'), array($this, 'id_password_input'), 'clp_widget_advanced', 'clp_widget_options');
		
		add_settings_field('clp_widget_id_remember', __('Remember Me ID', 'custom-login-page'), array($this, 'id_remember_input'), 'clp_widget_advanced', 'clp_widget_options');
		
		add_settings_field('clp_widget_id_submit', __('Submit Button ID', 'custom-login-page'), array($this, 'id_submit_input'), 'clp_widget_advanced', 'clp_widget_options');
		
		add_settings_field('clp_widget_value_username', __('Value for User Name', 'custom-login-page'), array($this, 'value_username_input'), 'clp_widget_advanced', 'clp_widget_options', __('You can write some default value for the user name here. By default, there is nothing.', 'custom-login-page'));
		
		add_settings_section('clp_widget_options', __('Export Settings', 'custom-login-page'), array($this, 'clp_widget_export_section'), 'clp_widget_export');
		
		add_settings_field('clp_widget_export', __('Download a file with your settings', 'custom-login-page'), array($this, 'export_input'), 'clp_widget_export', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', __('Import Settings', 'custom-login-page'), array($this, 'clp_widget_import_section'), 'clp_widget_import');
		
		add_settings_field('clp_widget_import', __('This will overlay any existing setting, you already have.', 'custom-login-page'), array($this, 'import_input'), 'clp_widget_import', 'clp_widget_options');
		
		add_settings_field('clp_widget_impex_resize', false, array($this, 'impex_resize_field'), 'clp_widget_import', 'clp_widget_options');
		
		// widget tab
	
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_container_section'), 'clp_widget_container');
		
		add_settings_field('clp_widget_container_background', __('Background Picture', 'custom-login-page'), array($this, 'container_background_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_img_repeat', __('Background Repeat', 'custom-login-page'), array($this, 'container_img_repeat_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_img_pos', __('Position of the Background Picture', 'custom-login-page'), array($this, 'container_img_pos_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'container_bg_color1_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'container_bg_color2_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_text_color', __('Text Colour', 'custom-login-page'), array($this, 'container_text_color_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'container_transparency_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_border_style', __('Border Style', 'custom-login-page'), array($this, 'container_border_style_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_border_width', __('Border Width (in px)', 'custom-login-page'), array($this, 'container_border_width_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_border_color', __('Border Colour', 'custom-login-page'), array($this, 'container_border_color_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_border_round', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'container_border_round_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'container_shadow_x_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'container_shadow_y_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'container_shadow_softness_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'container_shadow_color_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'container_shadow_inset_input'), 'clp_widget_container', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_container_pos_section'), 'clp_widget_container_pos');
		
		add_settings_field('clp_widget_container_padding', __('Padding', 'custom-login-page'), array($this, 'container_padding_input'), 'clp_widget_container_pos', 'clp_widget_options');
		
		add_settings_field('clp_widget_container_margin', __('Margin', 'custom-login-page'), array($this, 'container_margin_input'), 'clp_widget_container_pos', 'clp_widget_options');
		
		// logo tab
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_logo_section'), 'clp_widget_logo');
		
		add_settings_field('clp_widget_logo_url', __('Logo URL', 'custom-login-page'), array($this, 'logo_url_input'), 'clp_widget_logo', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_url', __('URL to link to', 'custom-login-page'), array($this, 'link_url_input'), 'clp_widget_logo', 'clp_widget_options');
		
		add_settings_field('clp_widget_logo_title', __('Title tag of the logo', 'custom-login-page'), array($this, 'logo_title_input'), 'clp_widget_logo', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_logo_size_section'), 'clp_widget_logo_size');
		
		add_settings_field('clp_widget_h1_margin', __('Margin of the Logo Container (CSS)', 'custom-login-page'), array($this, 'h1_margin_input'), 'clp_widget_logo_size', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_padding', __('Padding of the Logo Container (CSS)', 'custom-login-page'), array($this, 'h1_padding_input'), 'clp_widget_logo_size', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_logo_style_section'), 'clp_widget_logo_style');
		
		add_settings_field('clp_widget_h1_corner', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'h1_corner_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'h1_shadow_x_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'h1_shadow_y_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'h1_shadow_softness_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'h1_shadow_color_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		add_settings_field('clp_widget_h1_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'h1_shadow_inset_input'), 'clp_widget_logo_style', 'clp_widget_options');
		
		// login form tab
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_loginform_section'), 'clp_widget_loginform');
		
		add_settings_field('clp_widget_loginform_background', __('Background Picture', 'custom-login-page'), array($this, 'loginform_background_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_img_repeat', __('Background Repeat', 'custom-login-page'), array($this, 'loginform_img_repeat_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_img_pos', __('Position of the Background Picture', 'custom-login-page'), array($this, 'loginform_img_pos_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'loginform_bg_color1_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'loginform_bg_color2_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_text_color', __('Text Colour', 'custom-login-page'), array($this, 'loginform_text_color_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_transparency', __('Transparency (in percent)', 'custom-login-page'), array($this, 'loginform_transparency_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_border_style', __('Border Style', 'custom-login-page'), array($this, 'loginform_border_style_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_border_width', __('Border Width (in px)', 'custom-login-page'), array($this, 'loginform_border_width_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_border_color', __('Border Colour', 'custom-login-page'), array($this, 'loginform_border_color_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_border_round', __('Rounded Corners (in px)', 'custom-login-page'), array($this, 'loginform_border_round_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_padding', __('Padding', 'custom-login-page'), array($this, 'loginform_padding_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_margin', __('Margin', 'custom-login-page'), array($this, 'loginform_margin_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_x_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_y_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'loginform_shadow_softness_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'loginform_shadow_color_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		add_settings_field('clp_widget_loginform_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'loginform_shadow_inset_input'), 'clp_widget_loginform', 'clp_widget_options');
		
		// input and button tab
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_input_section'), 'clp_widget_input');
		
		add_settings_field('clp_widget_input_text_color', __('Text Colour', 'custom-login-page'), array($this, 'input_text_color_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_bg_color', __('Background Colour', 'custom-login-page'), array($this, 'input_bg_color_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_border_color', __('Border Colour', 'custom-login-page'), array($this, 'input_border_color_input'), 'clp_widget_input', 'clp_widget_options');	
		
		add_settings_field('clp_widget_input_float', __('Float', 'custom-login-page'), array($this, 'input_float_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_x_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'loginform_shadow_y_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'loginform_shadow_softness_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'loginform_shadow_color_input'), 'clp_widget_input', 'clp_widget_options');
		
		add_settings_field('clp_widget_input_shadow_inset', __('Inner Shadow', 'custom-login-page'), array($this, 'loginform_shadow_inset_input'), 'clp_widget_input', 'clp_widget_options');	
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_widget_button_section'), 'clp_widget_button');
		
		add_settings_field('clp_widget_button_bg_color1', __('Background Colour', 'custom-login-page'), array($this, 'button_bg_color1_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_button_bg_color2', __('Second Background Colour (for Gradient)', 'custom-login-page'), array($this, 'button_bg_color2_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_button_text_color', __('Text Colour', 'custom-login-page'), array($this, 'button_text_color_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_button_border_color', __('Border Colour', 'custom-login-page'), array($this, 'button_border_color_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_btn_hover_bg_color1', __('Hover Background Colour', 'custom-login-page'), array($this, 'btn_hover_bg_color1_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_btn_hover_bg_color2', __('Second Hover Background Colour (for Gradient)', 'custom-login-page'), array($this, 'btn_hover_bg_color2_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_btn_hover_text_color', __('Hover Text Colour', 'custom-login-page'), array($this, 'btn_hover_text_color_input'), 'clp_widget_button', 'clp_widget_options');
		
		add_settings_field('clp_widget_btn_hover_border_color', __('Hover Border Colour', 'custom-login-page'), array($this, 'btn_hover_border_color_input'), 'clp_widget_button', 'clp_widget_options');
		
		// link tab
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_link_section'), 'clp_widget_link');
		
		add_settings_field('clp_widget_link_size', __('Font Size', 'custom-login-page'), array($this, 'link_size_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_text_color', __('Text Colour', 'custom-login-page'), array($this, 'link_text_color_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_textdecoration', __('Text Decoration', 'custom-login-page'), array($this, 'link_textdecoration_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'link_shadow_x_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'link_shadow_y_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'link_shadow_softness_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_field('clp_widget_link_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'link_shadow_color_input'), 'clp_widget_link', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', false, array($this, 'clp_hover_section'), 'clp_widget_hover');
		
		add_settings_field('clp_widget_hover_text_color', __('Text Colour', 'custom-login-page'), array($this, 'hover_text_color_input'), 'clp_widget_hover', 'clp_widget_options');
		
		add_settings_field('clp_widget_hover_textdecoration', __('Text Decoration', 'custom-login-page'), array($this, 'hover_textdecoration_input'), 'clp_widget_hover', 'clp_widget_options');
		
		add_settings_field('clp_widget_hover_shadow_x', __('Shadow (x-direction in px)', 'custom-login-page'), array($this, 'hover_shadow_x_input'), 'clp_widget_hover', 'clp_widget_options');
		
		add_settings_field('clp_widget_hover_shadow_y', __('Shadow (y-direction in px)', 'custom-login-page'), array($this, 'hover_shadow_y_input'), 'clp_widget_hover', 'clp_widget_options');
		
		add_settings_field('clp_widget_hover_shadow_softness', __('Shadow (softness in px)', 'custom-login-page'), array($this, 'hover_shadow_softness_input'), 'clp_widget_hover', 'clp_widget_options');
		
		add_settings_field('clp_widget_hover_shadow_color', __('Shadow Colour', 'custom-login-page'), array($this, 'hover_shadow_color_input'), 'clp_widget_hover', 'clp_widget_options');
		
		// css tab
		
		add_settings_section('clp_widget_options', __('CSS', 'custom-login-page'), array($this, 'clp_widget_css_section'), 'clp_widget_css');
		
		add_settings_field('clp_widget_css', __('Own CSS', 'custom-login-page'), array($this, 'css_input'), 'clp_widget_css', 'clp_widget_options');
		
		add_settings_field('clp_widget_css_override', __('Override other styles', 'custom-login-page'), array($this, 'override_input'), 'clp_widget_css', 'clp_widget_options', array(__('By checking this, all other styles will be replaced by your CSS. Otherwise, your CSS is additional.', 'custom-login-page')));
		
		add_settings_field('clp_widget_css_resize', false, array($this, 'css_resize_field'), 'clp_widget_css', 'clp_widget_options');
		
		// html tab
		
		add_settings_section('clp_widget_options', __('Aditional html snippets outside of the form', 'custom-login-page'), array($this, 'clp_widget_html_outside_section'), 'clp_widget_html_outside');
		
		add_settings_field('clp_widget_login_message', __('Above Form', 'custom-login-page'), array($this, 'login_message_input'), 'clp_widget_html_outside', 'clp_widget_options');
		
		add_settings_field('clp_widget_login_footer', __('Beneath Form', 'custom-login-page'), array($this, 'login_footer_input'), 'clp_widget_html_outside', 'clp_widget_options');
		
		add_settings_section('clp_widget_options', __('Aditional html snippets inside of the form', 'custom-login-page'), array($this, 'clp_widget_html_inside_section'), 'clp_widget_html_inside');
		
		add_settings_field('clp_widget_login_form_top', __('Top', 'custom-login-page'), array($this, 'login_form_top_input'), 'clp_widget_html_inside', 'clp_widget_options');
		
		add_settings_field('clp_widget_login_form', __('Middle', 'custom-login-page'), array($this, 'login_form_input'), 'clp_widget_html_inside', 'clp_widget_options');
		
		add_settings_field('clp_widget_login_form_bottom', __('Bottom', 'custom-login-page'), array($this, 'login_form_bottom_input'), 'clp_widget_html_inside', 'clp_widget_options');
		
		add_settings_field('clp_widget_html_resize', false, array($this, 'html_resize_field'), 'clp_widget_html_inside', 'clp_widget_options');
	
	}
	
	// main tab
	
	function clp_widget_main_section() {
	
		self::tag_it(__('You can leave all the fields empty to have the default login form.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function label_username_input() {
		
		a5_text_field('label_username', 'clp_widget_options[label_username]', @self::$options['label_username'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => __('Username')));
	
	}
	
	function label_password_input() {
		
		a5_text_field('label_password', 'clp_widget_options[label_password]', @self::$options['label_password'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => __('Password')));
	
	}
	
	function label_remember_input() {
		
		a5_text_field('label_remember', 'clp_widget_options[label_remember]', @self::$options['label_remember'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => __('Remember Me')));
	
	}
	
	function label_log_in_input() {
		
		a5_text_field('label_log_in', 'clp_widget_options[label_log_in]', @self::$options['label_log_in'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => __('Log In')));
	
	}
	
	function hide_remember_input() {
		
		a5_checkbox('hide_remember', 'clp_widget_options[hide_remember]', @self::$options['hide_remember']);
		
	}
	
	function value_remember_input() {
		
		a5_checkbox('value_remember', 'clp_widget_options[value_remember]', @self::$options['value_remember']);
		
	}
	
	function use_filters_input($labels) {
		
		a5_checkbox('use_filters', 'clp_widget_options[use_filters]', @self::$options['use_filters'], $labels[0]);
		
	}
	
	function clp_widget_advanced_section() {
	
		self::tag_it(__('In most cases, it won&#39;t make sense to change anything here. You might for some reasons want to try something out, though.', 'custom-login-page'), 'p', 1, false, true);
		
	}
		
	function redirect_input($labels) {
		
		a5_url_field('redirect', 'clp_widget_options[redirect]', @self::$options['redirect'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => home_url('/')));
		
		self::tag_it($labels[0], 'p', 1, false, true);
	
	}
	
	function form_id_input() {
		
		a5_text_field('form_id', 'clp_widget_options[form_id]', @self::$options['form_id'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'loginform'));
	
	}
	
	function id_username_input() {
		
		a5_text_field('id_username', 'clp_widget_options[id_username]', @self::$options['id_username'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'user_login'));
	
	}
	
	function id_password_input() {
		
		a5_text_field('id_password', 'clp_widget_options[id_password]', @self::$options['id_password'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'user_pass'));
	
	}
	
	function id_remember_input() {
		
		a5_text_field('id_remember', 'clp_widget_options[id_remember]', @self::$options['id_remember'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'rememberme'));
	
	}
	
	function id_submit_input() {
		
		a5_text_field('id_submit', 'clp_widget_options[id_submit]', @self::$options['id_submit'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => 'wp-submit'));
	
	}
	
	function value_username_input() {
		
		a5_text_field('value_username', 'clp_widget_options[value_username]', @self::$options['value_username'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
	
	}
	
	function clp_widget_export_section() {
		
		self::tag_it(__('Export the current A5 Custom Login Widget settings and download them as a text file. The content of this text file can be imported into this or another A5 Custom Login Widget installation:', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(sprintf(_x('The file will be named %s. After you downloaded it, you can (but don&#39;t need to) rename the file to something more meaningful.', '%s is the file name', 'custom-login-page'), '<code>a5-clp-widget-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('y') . date('m') . date('d') . '.txt</code>'), 'p', 1, false, true);
		
	}
	
	function export_input() {
	
		echo '<a class="button" href="' . get_bloginfo('url') . '/?clpfile=export-widget" id="widget-settings-download"><strong>'. __('Export &amp; Download', 'custom-login-page') .'</strong> A5 Custom Login Widget Settings File</a>';
	
	}
	
	function clp_widget_import_section() {
		
		self::tag_it(__('Enter the content of your text file with the settings here.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function import_input() {
	
		a5_textarea('import', 'clp_widget_options[import]', false, false, array('style' => 'height: 200px; min-width: 100%;'));
	
	}
	
	function impex_resize_field() {
		
		a5_resize_textarea('import', true);
		
	}
	
	// widget tab
	
	function clp_widget_container_section() {
		
		self::tag_it(__('You can enter the url of the background picture, that you want to have on the widget container. Just upload any picture via the uploader on the Media section and copy the url of that file here. Leave it empty, if you don&#39;t want a picture. Background images are tiled by default. You can select the direction of repeating the image or to not repeat it. The position of the image can be something like &#39;100px 50%&#39; or &#39;center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the next section, you choose the background colour and the colour of the text in the widget. If you give two background colours, you can create a gradient. Colour no. 1 will always be up.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Choose a border, if wanting one. Define style, width and whether or not, you want to have rounded corners (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('At last, give the container a shadow (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default widget of your theme.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function container_background_input() {
		
		$label = __('Enter a URL', 'custom-login-page');
		
		if (function_exists('wp_enqueue_media')) :
		
			self::tag_it(a5_button('upload-container_background', 'container', __('Select Image'), false, array('class' => 'button upload-button'), false), 'p', 1, array('id' => 'container_upload', 'style' => 'display: none;'), true);
				
			self::tag_it('<img src="'.@self::$options['container_background'].'" alt="'.__('Preview').'" style="max-width: 320px; height: auto;" />', 'p', 1, array('id' => 'container_preview', 'style' => 'display: none;'), true);
			
			self::tag_it(a5_button('remove-container_background', 'container', __('Remove Image'), false, array('class' => 'button remove-button'), false), 'p', 1, array('id' => 'container_remove', 'style' => 'display: none;'), true);
			
			$label = __('Or enter a URL', 'custom-login-page');
			
		endif;
		
		self::tag_it($label, 'p', false, false, true);
		
		a5_url_field('container_url', 'clp_widget_options[container_background]', @self::$options['container_background'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function container_img_repeat_input() {
		
		$options = array(array('no-repeat', 'no-repeat'), array('repeat-x', 'repeat-x'), array('repeat-y', 'repeat-y'));
		
		a5_select('container_img_repeat', 'clp_widget_options[container_img_repeat]', $options, @self::$options['container_img_repeat'], false, __('default', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function container_img_pos_input() {
		
		a5_text_field('container_img_pos', 'clp_widget_options[container_img_pos]', @self::$options['container_img_pos']);
		
	}
		
	function container_bg_color1_input() {
		
		a5_text_field('container_bg_color1', 'clp_widget_options[container_bg_color1]', @self::$options['container_bg_color1'], false, array('class' => 'color-picker'));
		
	}
		
	function container_bg_color2_input() {	

		a5_text_field('container_bg_color2', 'clp_widget_options[container_bg_color2]', @self::$options['container_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function container_text_color_input() {
	
		a5_text_field('container_text_color', 'clp_widget_options[container_text_color]', @self::$options['container_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function container_transparency_input() {
		
		a5_number_field('container_transparency', 'clp_widget_options[container_transparency]', @self::$options['container_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function container_border_style_input() {
		
		$border_style = array(array('none', 'none'), array('dotted', 'dotted'), array('dashed', 'dashed'), array('solid', 'solid'), array('double', 'double'), array('groove', 'groove'), array('ridge', 'rigde'), array('inset', 'inset'), array('outset', 'outset'));
		
		a5_select('container_border_style', 'clp_widget_options[container_border_style]', $border_style, @self::$options['container_border_style'], false, __('choose a border style', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function container_border_width_input() {
		
		a5_number_field('container_border_width', 'clp_widget_options[container_border_width]', @self::$options['container_border_width'], false, array('step' => 1));
		
	}
	
	function container_border_color_input() {
		
		a5_text_field('container_border_color', 'clp_widget_options[container_border_color]', @self::$options['container_border_color'], false, array('class' => 'color-picker'));
	
	}
	
	function container_border_round_input() {
		
		a5_number_field('container_border_round', 'clp_widget_options[container_border_round]', @self::$options['container_border_round'], false, array('step' => 1));
		
	}
	
	function container_shadow_x_input() {
		
		a5_number_field('container_shadow_x', 'clp_widget_options[container_shadow_x]', @self::$options['container_shadow_x'], false, array('step' => 1));
		
	}
		
	function container_shadow_y_input() {
		
		a5_number_field('container_shadow_y', 'clp_widget_options[container_shadow_y]', @self::$options['container_shadow_y'], false, array('step' => 1));
		
	}
	
	function container_shadow_softness_input() {
			
		a5_number_field('container_shadow_softness', 'clp_widget_options[container_shadow_softness]', @self::$options['container_shadow_softness'], false, array('step' => 1));
		
	}
	
	function container_shadow_color_input() {
		
		a5_text_field('container_shadow_color', 'clp_widget_options[container_shadow_color]', @self::$options['container_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function container_shadow_inset_input() {
		
		a5_checkbox('container_shadow_inset', 'clp_widget_options[container_shadow_inset]', ' inset', false, false, @self::$options['container_shadow_inset']);
	
	}
	
	function clp_widget_container_pos_section() {
		
		self::tag_it(__('Depending on what you do to the widget itself, you might want to change it&#39;s padding and margin as well.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('The Padding and Margin are given as css value. I.e. &#39;144px 0 0&#39;.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function container_padding_input() {
		
		a5_text_field('container_padding', 'clp_widget_options[container_padding]', @self::$options['container_padding']);
		
	}
	
	function container_margin_input() {
		
		a5_text_field('container_margin', 'clp_widget_options[container_margin]', @self::$options['container_margin']);
		
	}
	
	// logo tab
	
	function clp_widget_logo_section() {
		
		self::tag_it(__('You can enter the url of the logo, that you want to have in the widget. Just upload any picture (best is a png or gif with transparent background) via the uploader on the Media section and copy the url of that file here.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the URL field, you enter the URL to which the logo should link.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave the fields empty if you don&#39;t want a logo in the widget. Or just copy the settings from the login page, to have a down-sized version of the logo in the widget.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the settings for the logo from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_logo]', true, array('id' => 'copy_logo'));
		
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
				
		a5_url_field('logo_url', 'clp_widget_options[logo]', @self::$options['logo'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function link_url_input() {
		
		a5_url_field('url', 'clp_widget_options[url]', @self::$options['url'], false, array('style' => 'min-width: 350px; max-width: 500px;', 'placeholder' => home_url('/')));
		
	}
	
	function logo_title_input() {
		
		a5_text_field('title', 'clp_widget_options[title]', @self::$options['title'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function clp_widget_logo_size_section() {
		
		self::tag_it(__('Here you can can give the Logo in the widget a margin and a padding, if necessary.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function h1_margin_input() {
		
		a5_text_field('h1_margin', 'clp_widget_options[h1_margin]', @self::$options['h1_margin']);
		
	}
	
	function h1_padding_input() {
		
		a5_text_field('h1_padding', 'clp_widget_options[h1_padding]', @self::$options['h1_padding']);
	
	}
	
	function clp_widget_logo_style_section() {
		
		self::tag_it(__('Here you can style the logo a bit. Give it a shadow or round corners if you like.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function h1_corner_input() {
		
		a5_number_field('h1_corner', 'clp_widget_options[h1_corner]', @self::$options['h1_corner'], false, array('step' => 1));
		
	}
		
	function h1_shadow_x_input() {
		
		a5_number_field('h1_shadow_x', 'clp_widget_options[h1_shadow_x]', @self::$options['h1_shadow_x'], false, array('step' => 1));
		
	}
	
	function h1_shadow_y_input() {
		
		a5_number_field('h1_shadow_y', 'clp_widget_options[h1_shadow_y]', @self::$options['h1_shadow_y'], false, array('step' => 1));
		
	}
	
	function h1_shadow_softness_input() {
		
		a5_number_field('h1_shadow_softness', 'clp_widget_options[h1_shadow_softness]', @self::$options['h1_shadow_softness'], false, array('step' => 1));
		
	}
	
	function h1_shadow_color_input() {
		
		a5_text_field('h1_shadow_color', 'clp_widget_options[h1_shadow_color]', @self::$options['h1_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	function h1_shadow_inset_input() {
		
		a5_checkbox('h1_shadow_inset', 'clp_widget_options[h1_shadow_inset]', ' inset', false, false, @self::$options['h1_shadow_inset']);
	
	}
	
	// loginform tab
	
	function clp_widget_loginform_section() {
		
		self::tag_it(__('You can enter the url of the background picture, that you want to have in the login form. Just upload any picture via the uploader on the Media section and copy the url of that file here. Leave it empty, if you don&#39;t want a picture. Background images are tiled by default. You can select the direction of repeating the image or to not repeat it. The position of the image can be something like &#39;100px 50%&#39; or &#39center top&#39;.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('In the next section, you choose the background colour and the colour of the text in the login form. If you give two background colours, you can create a gradient. Colour no. 1 will always be up.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Choose a border, if wanting one. Define style, width and whether or not, you want to have rounded corners (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('Margin and Padding are given as css values.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('At last, give the form a shadow (is not supported by all browsers).', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the settings for the login form from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_loginform]', true, array('id' => 'copy_loginform'));
		
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
				
		a5_url_field('loginform_url', 'clp_widget_options[loginform_background]', @self::$options['loginform_background'], false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function loginform_img_repeat_input() {
		
		$options = array(array('no-repeat', 'no-repeat'), array('repeat-x', 'repeat-x'), array('repeat-y', 'repeat-y'));
		
		a5_select('loginform_img_repeat', 'clp_widget_options[loginform_img_repeat]', $options, @self::$options['loginform_img_repeat'], false, __('default', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function loginform_img_pos_input() {
		
		a5_text_field('loginform_img_pos', 'clp_widget_options[loginform_img_pos]', @self::$options['loginform_img_pos']);
		
	}
		
	function loginform_bg_color1_input() {
		
		a5_text_field('loginform_bg_color1', 'clp_widget_options[loginform_bg_color1]', @self::$options['loginform_bg_color1'], false, array('class' => 'color-picker'));
		
	}
		
	function loginform_bg_color2_input() {	

		a5_text_field('loginform_bg_color2', 'clp_widget_options[loginform_bg_color2]', @self::$options['loginform_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function loginform_text_color_input() {
	
		a5_text_field('loginform_text_color', 'clp_widget_options[loginform_text_color]', @self::$options['loginform_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function loginform_transparency_input() {
		
		a5_number_field('loginform_transparency', 'clp_widget_options[loginform_transparency]', @self::$options['loginform_transparency'], false, array('step' => 1, 'min' => 0, 'max' => 100));
		
	}
	
	function loginform_border_style_input() {
		
		$border_style = array(array('none', 'none'), array('dotted', 'dotted'), array('dashed', 'dashed'), array('solid', 'solid'), array('double', 'double'), array('groove', 'groove'), array('ridge', 'rigde'), array('inset', 'inset'), array('outset', 'outset'));
		
		a5_select('loginform_border_style', 'clp_widget_options[loginform_border_style]', $border_style, @self::$options['loginform_border_style'], false, __('choose a border style', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function loginform_border_width_input() {
		
		a5_number_field('loginform_border_width', 'clp_widget_options[loginform_border_width]', @self::$options['loginform_border_width'], false, array('step' => 1));
		
	}
	
	function loginform_border_color_input() {
		
		a5_text_field('loginform_border_color', 'clp_widget_options[loginform_border_color]', @self::$options['loginform_border_color'], false, array('class' => 'color-picker'));
	
	}
	
	function loginform_border_round_input() {
		
		a5_number_field('loginform_border_round', 'clp_widget_options[loginform_border_round]', @self::$options['loginform_border_round'], false, array('step' => 1));
		
	}
	
	function loginform_padding_input() {
		
		a5_text_field('loginform_padding', 'clp_widget_options[loginform_padding]', @self::$options['loginform_padding']);
		
	}
	
	function loginform_margin_input() {
		
		a5_text_field('loginform_margin', 'clp_widget_options[loginform_margin]', @self::$options['loginform_margin']);
		
	}
	
	function loginform_shadow_x_input() {
		
		a5_number_field('loginform_shadow_x', 'clp_widget_options[loginform_shadow_x]', @self::$options['loginform_shadow_x'], false, array('step' => 1));
		
	}
		
	function loginform_shadow_y_input() {
		
		a5_number_field('loginform_shadow_y', 'clp_widget_options[loginform_shadow_y]', @self::$options['loginform_shadow_y'], false, array('step' => 1));
		
	}
	
	function loginform_shadow_softness_input() {
			
		a5_number_field('loginform_shadow_softness', 'clp_widget_options[loginform_shadow_softness]', @self::$options['loginform_shadow_softness'], false, array('step' => 1));
		
	}
	
	function loginform_shadow_color_input() {
		
		a5_text_field('loginform_shadow_color', 'clp_widget_options[loginform_shadow_color]', @self::$options['loginform_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function loginform_shadow_inset_input() {
		
		a5_checkbox('loginform_shadow_inset', 'clp_widget_options[loginform_shadow_inset]', ' inset', false, false, @self::$options['loginform_shadow_inset']);
	
	}
	
	// input and button tab
	
	function clp_widget_input_section() {
		
		self::tag_it(__('This changes the colours of the name and password fields of the log in form.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the settings for the input fields from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_input]', true, array('id' => 'copy_input'));
		
	}
		
	function input_text_color_input() {
		
		a5_text_field('input_text_color', 'clp_widget_options[input_text_color]', @self::$options['input_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function input_bg_color_input() {
			
		a5_text_field('input_bg_color', 'clp_widget_options[input_bg_color]', @self::$options['input_bg_color'], false, array('class' => 'color-picker'));
		
	}
		
	function input_border_color_input() {
			
		a5_text_field('input_border_color', 'clp_widget_options[input_border_color]', @self::$options['input_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function input_float_input() {
		
		$options = array(array('left', 'left'), array('right', 'right'), array('none', 'none'), array('inherit', 'inherit'));
		
		a5_select('input_float', 'clp_widget_options[input_float]', $options, @self::$options['input_float'], false, __('choose a value', 'custom-login-page'), array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
	
	function input_shadow_x_input() {
		
		a5_number_field('input_shadow_x', 'clp_widget_options[input_shadow_x]', @self::$options['input_shadow_x'], false, array('step' => 1));
		
	}
		
	function input_shadow_y_input() {
		
		a5_number_field('input_shadow_y', 'clp_widget_options[input_shadow_y]', @self::$options['input_shadow_y'], false, array('step' => 1));
		
	}
	
	function input_shadow_softness_input() {
			
		a5_number_field('input_shadow_softness', 'clp_widget_options[input_shadow_softness]', @self::$options['input_shadow_softness'], false, array('step' => 1));
		
	}
	
	function input_shadow_color_input() {
		
		a5_text_field('input_shadow_color', 'clp_widget_options[input_shadow_color]', @self::$options['input_shadow_color'], false, array('class' => 'color-picker'));
	
	}
	
	function input_shadow_inset_input() {
		
		a5_checkbox('input_shadow_inset', 'clp_widget_options[input_shadow_inset]', ' inset', false, false, @self::$options['input_shadow_inset']);
	
	}
	
	function clp_widget_button_section() {
		
		self::tag_it(__('Enter the background, text and border colour of the submit button here. The button will look static if you don&#39;t give values for the hover state of it. If you want to have a gradient, enter two background colours. The first one will be up then.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the settings for the button from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_button]', true, array('id' => 'copy_button'));
		
	}
	
	function button_bg_color1_input() {
		
		a5_text_field('button_bg_color1', 'clp_widget_options[button_bg_color1]', @self::$options['button_bg_color1'], false, array('class' => 'color-picker'));
		
	}
	
	function button_bg_color2_input() {
		
		a5_text_field('button_bg_color2', 'clp_widget_options[button_bg_color2]', @self::$options['button_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function button_text_color_input() {
		
		a5_text_field('button_text_color', 'clp_widget_options[button_text_color]', @self::$options['button_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function button_border_color_input() {
		
		a5_text_field('button_border_color', 'clp_widget_options[button_border_color]', @self::$options['button_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_bg_color1_input() {
		
		a5_text_field('btn_hover_bg_color1', 'clp_widget_options[btn_hover_bg_color1]', @self::$options['btn_hover_bg_color1'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_bg_color2_input() {
		
		a5_text_field('btn_hover_bg_color2', 'clp_widget_options[btn_hover_bg_color2]', @self::$options['btn_hover_bg_color2'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_text_color_input() {
		
		a5_text_field('btn_hover_text_color', 'clp_widget_options[btn_hover_text_color]', @self::$options['btn_hover_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function btn_hover_border_color_input() {
		
		a5_text_field('btn_hover_border_color', 'clp_widget_options[btn_hover_border_color]', @self::$options['btn_hover_border_color'], false, array('class' => 'color-picker'));
		
	}
	
	// link tab
	
	function clp_link_section() {
	
		self::tag_it(__('Style the links by giving a text colour, text decoration and shadow for the link and the hover style.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(sprintf(__('For the font size, give a css value, such as %1$s or %2$s.', 'custom-login-page'), '<em>&#39;12px&#39;</em>', '<em>&#39;1em&#39;</em>'), 'p', 1, false, true);
		self::tag_it(__('You can leave any of the fields empty to keep the default settings of Wordpress.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the settings for the links from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_links]', true, array('id' => 'copy_links'));
		
	}
	
	function link_size_input() {
		
		a5_text_field('link_size', 'clp_widget_options[link_size]', @self::$options['link_size']);
		
	}
	
	function link_text_color_input() {
			
		a5_text_field('link_text_color', 'clp_widget_options[link_text_color]', @self::$options['link_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function link_textdecoration_input() {
		
		$textdeco = array(array('none', 'none'), array('underline', 'underline'), array('overline', 'overline'), array('line-through', 'line-through'), array('blink', 'blink'));
		
		a5_select('link_textdecoration', 'clp_widget_options[link_textdecoration]', $textdeco, @self::$options['link_textdecoration'], false, false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function link_shadow_x_input() {
		
		a5_number_field('link_shadow_x', 'clp_widget_options[link_shadow_x]', @self::$options['link_shadow_x'], false, array('step' => 1));
		
	}
	
	function link_shadow_y_input() {
		
		a5_number_field('link_shadow_y', 'clp_widget_options[link_shadow_y]', @self::$options['link_shadow_y'], false, array('step' => 1));
		
	}
	
	function link_shadow_softness_input() {
		
		a5_number_field('link_shadow_softness', 'clp_widget_options[link_shadow_softness]', @self::$options['link_shadow_softness'], false, array('step' => 1));
		
	}
	
	function link_shadow_color_input() {
		
		a5_text_field('link_shadow_color', 'clp_widget_options[link_shadow_color]', @self::$options['link_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	function clp_hover_section() {
	
		self::tag_it(__('The same for the hover state.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function hover_text_color_input() {
			
		a5_text_field('hover_text_color', 'clp_widget_options[hover_text_color]', @self::$options['hover_text_color'], false, array('class' => 'color-picker'));
		
	}
	
	function hover_textdecoration_input() {
		
		$textdeco = array(array('none', 'none'), array('underline', 'underline'), array('overline', 'overline'), array('line-through', 'line-through'), array('blink', 'blink'));
		
		a5_select('hover_textdecoration', 'clp_widget_options[hover_textdecoration]', $textdeco, @self::$options['hover_textdecoration'], false, false, array('style' => 'min-width: 350px; max-width: 500px;'));
		
	}
		
	function hover_shadow_x_input() {
		
		a5_number_field('hover_shadow_x', 'clp_widget_options[hover_shadow_x]', @self::$options['hover_shadow_x'], false, array('step' => 1));
		
	}
	
	function hover_shadow_y_input() {
		
		a5_number_field('hover_shadow_y', 'clp_widget_options[hover_shadow_y]', @self::$options['hover_shadow_y'], false, array('step' => 1));
		
	}
	
	function hover_shadow_softness_input() {
		
		a5_number_field('hover_shadow_softness', 'clp_widget_options[hover_shadow_softness]', @self::$options['hover_shadow_softness'], false, array('step' => 1));
		
	}
	
	function hover_shadow_color_input() {
		
		a5_text_field('hover_shadow_color', 'clp_widget_options[hover_shadow_color]', @self::$options['hover_shadow_color'], false, array('class' => 'color-picker'));
		
	}
	
	// css tab
	
	function clp_widget_css_section() {
		
		self::tag_it(__('Here you can enter some css. You either can enter an entire style sheet or just some additional css.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('This gives you much more freedom with styling your login widget than the rather foolproof but very limited options .', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can of course copy the style sheet written by the plugin, paste it here and start finetuning.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy'), 'secondary', 'clp_widget_options[copy_stylesheet]', true, array('id' => 'copy_stylesheet'));
		
	}
	
	function css_input() {
	
		a5_textarea('css', 'clp_widget_options[css]', @self::$options['css'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function css_resize_field() {
		
		a5_resize_textarea(array('css'), true);
		
	}
	
	function override_input($labels) {
		
		a5_checkbox('override', 'clp_widget_options[override]', @self::$options['override'], $labels[0]);
		
	}
	
	// html tab
	
	function clp_widget_html_outside_section() {
		
		self::tag_it(__('If you want to have some additional html outside your login form, there are two places to put it. Above the form and under it. This is done for conformity with the login page and it&#39;s filters.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can use the additional css to style the html snippets that you enter here.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can as well copy the html from the login page.', 'custom-login-page'), 'p', 1, false, true);
		
		submit_button(__('Copy Settings', 'custom-login-page'), 'secondary', 'clp_widget_options[copy_html]', true, array('id' => 'copy_html'));
		
	}
	
	function login_message_input() {
	
		a5_textarea('login_message', 'clp_widget_options[login_message]', @self::$options['login_message'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function login_footer_input() {
	
		a5_textarea('login_footer', 'clp_widget_options[login_footer]', @self::$options['login_footer'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function clp_widget_html_inside_section() {
		
		self::tag_it(__('If you want to have some additional html inside your login form, there are three places to put it. On top, in the middle and at the bottom. Those are the original filters for the form.', 'custom-login-page'), 'p', 1, false, true);
		self::tag_it(__('You can use the additional css to style the html snippets that you enter here.', 'custom-login-page'), 'p', 1, false, true);
		
	}
	
	function login_form_top_input() {
	
		a5_textarea('login_form_top', 'clp_widget_options[login_form_top]', @self::$options['login_form_top'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function login_form_input() {
	
		a5_textarea('login_form', 'clp_widget_options[login_form]', @self::$options['login_form'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function login_form_bottom_input() {
	
		a5_textarea('login_form_bottom', 'clp_widget_options[login_form_bottom]', @self::$options['login_form_bottom'], false, array('style' => 'height: 200px; min-width: 100%;', 'class' => 'tabbed'));
	
	}
	
	function html_resize_field() {
		
		a5_resize_textarea(array('login_message', 'login_form', 'login_footer', 'login_form_top', 'login_form_bottom'), true);
		
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
		
			$input = $_POST['clp_widget_options'];
			
			self::$options = $this->validate($input);
			
			update_site_option('clp_widget_options', self::$options);
			
			$this->initialize_settings();
		
		endif;
		
		// the main options page begins here
		
		$eol = "\n";
		
		$tab = "\t";
		
		$dtab = $tab.$tab;
		
		// navigation
		
		self::open_page('A5 Custom Login Widget', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/a5-custom-login-page', 'custom-login-page'), 'custom-login-page');
		
		settings_errors();
		
		$tabs ['main_tab'] = array( 'class' => ($active == 'main_tab') ? ' nav-tab-active' : '', 'text' => __('General Options', 'custom-login-page'));
		$tabs ['widget_tab'] = array( 'class' => ($active == 'widget_tab') ? ' nav-tab-active' : '', 'text' => __('Widget', 'custom-login-page'));
		$tabs ['logo_tab'] = array( 'class' => ($active == 'logo_tab') ? ' nav-tab-active' : '', 'text' => __('Logo', 'custom-login-page'));
		$tabs ['loginform_tab'] = array( 'class' => ($active == 'loginform_tab') ? ' nav-tab-active' : '', 'text' => __('Login Form', 'custom-login-page'));
		$tabs ['button_tab'] = array( 'class' => ($active == 'button_tab') ? ' nav-tab-active' : '', 'text' => __('Input Fields & Submit Button', 'custom-login-page'));
		$tabs ['link_tab'] = array( 'class' => ($active == 'link_tab') ? ' nav-tab-active' : '', 'text' => __('Links', 'custom-login-page'));
		$tabs ['css_tab'] = array( 'class' => ($active == 'css_tab') ? ' nav-tab-active' : '', 'text' => __('CSS', 'custom-login-page'));
		$tabs ['html_tab'] = array( 'class' => ($active == 'html_tab') ? ' nav-tab-active' : '', 'text' => __('Additional HTML', 'custom-login-page'));
		
		$args = array(
			'page' => 'clp-widget-settings',
			'menu_items' => $tabs
		);
		
		self::nav_menu($args);

		$action = (is_plugin_active_for_network(CLP_BASE)) ? '?page=clp-widget-settings&tab='.$active.'&action=update' : 'options.php';
		
		self::open_form($action);
		
		// nonce and stuff which is the same for all tabs
		
		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
		
		a5_hidden_field('tab', 'clp_widget_options[tab]', $active, true);
		
		settings_fields('clp_widget_options');
		
		// the actual option tabs
		
		if ($active == 'main_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Basic Options for the Login Form', 'custom-login-page'), 'basic', 'clp_widget_main'));
			
			self::sortable('middle', self::postbox(__('Advanced Options for the Login Form', 'custom-login-page'), 'advanced', 'clp_widget_advanced'));
			
			self::sortable('bottom', self::postbox(__('Import / Export', 'custom-login-page'), 'impex', array('clp_widget_export', 'clp_widget_import')));
			
		endif;
		
		if ($active == 'widget_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Widget Container', 'custom-login-page'), 'widget-container', 'clp_widget_container'));
			
			self::sortable('bottom', self::postbox(__('Padding & Margin', 'custom-login-page'), 'widget-container-pos', 'clp_widget_container_pos'));
			
		endif;
		
		if ($active == 'logo_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Logo of the Login Screen', 'custom-login-page'), 'logo', 'clp_widget_logo'));
			
			self::sortable('middle', self::postbox(__('Padding & Margin', 'custom-login-page'), 'logo-pos', 'clp_widget_logo_size'));
			
			self::sortable('bottom', self::postbox(__('Styling of the Logo', 'custom-login-page'), 'logo-style', 'clp_widget_logo_style'));
			
		endif;
		
		if ($active == 'loginform_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Login Form', 'custom-login-page'), 'form', 'clp_widget_loginform'));
			
		endif;
		
		if ($active == 'button_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Input Fields', 'custom-login-page'), 'input-fields', 'clp_widget_input'));
			
			self::sortable('middle', self::postbox(__('Submit Button', 'custom-login-page'), 'submit-button', 'clp_widget_button'));
		endif;
		
		if ($active == 'link_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('Links', 'custom-login-page'), 'link', 'clp_widget_link'));
			
			self::sortable('middel', self::postbox(__('Links Hover', 'custom-login-page'), 'link-hover', 'clp_widget_hover'));
			
		endif;
		
		if ($active == 'css_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('CSS', 'custom-login-page'), 'css-additions', 'clp_widget_css'));
			
			$elements = array('.widget_custom_login_widget[id^="custom_login_widget"]', 
				'.widget_custom_login_widget[id^="custom_login_widget"] h3',
				'.widget_custom_login_widget[id^="custom_login_widget"] form',
				'.widget_custom_login_widget[id^="custom_login_widget"] p',
				'.widget_custom_login_widget[id^="custom_login_widget"] label',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-username',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-password',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-remember',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-submit',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-username label',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-password label',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-remember label',
				'.widget_custom_login_widget[id^="custom_login_widget"] p.login-submit label',
				'.widget_custom_login_widget[id^="custom_login_widget"] input',
				'.widget_custom_login_widget[id^="custom_login_widget"] input.button-primary',
				'.widget_custom_login_widget[id^="custom_login_widget"] a'
				);
		
			$content = self::tag_it(__('To be able to use your own css it is important to know, what elements you actually can style in the login widget. In the list below you find all neccessary selectors for your style sheet.', 'custom-login-page'), 'p');
			
			$content .= self::tag_it(self::list_it($elements, false, false, false, false), 'b');
			
			self::sortable('middle', self::help_box($content, __('CSS Help', 'custom-login-page')));
			
		endif;
		
		if ($active == 'html_tab') :
		
			self::open_tab(2);
			
			self::sortable('top', self::postbox(__('HTML additions', 'custom-login-page'), 'html-additions', 'clp_widget_html_outside'));
			
			self::sortable('middle', self::postbox(__('HTML additions', 'custom-login-page'), 'html-additions', 'clp_widget_html_inside'));
			
		endif;
		
		if (WP_DEBUG === true) self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'custom-login-page')));
			
		submit_button();
		
		echo '</form>';
		
		self::column('1');
			
		self::preview_widget();
		
		$donationtext = self::tag_it(__('If you like the plugin and find it useful, you might think of rewarding the dozens of hours of work that were spent creating it.', 'custom-login-page'), 'p');
		
		self::sortable('side_middle', self::donation_box($donationtext, __('Donations', 'custom-login-page'), '32XGSBKTQNNHA', 'http%3A%2F%2Fwasistlos.waldemarstoffel.com%2Fplugins-fur-wordpress%2Fa5-custom-login-page'));
		
		self::close_tab();
		
		echo '</div>';
		
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
			
			if ($options['log'] != 'original A5 CLP Widget file' && $options['log'] != 'original A5 CLP file') :
			
				add_settings_error('clp_widget_options', 'failed-on-import', __('This doesn&#39;t seem to be a valid settings file.', 'custom-login-page'), 'error');
				
				unset($options);
				
				return self::$options;
			
			else:
			
				if ($options['log'] == 'original A5 CLP Widget file') :
			
					unset($options['log']);
					
					add_settings_error('clp_widget_options', 'success-on-import', __('Settings successfully imported.', 'custom-login-page'), 'updated');
					
					return $options;
					
				else :
				
					unset($options['log']);
					
					$options['multisite'] = self::$multisite;
					
					add_settings_error('clp_widget_options', 'success-on-import', __('Settings successfully imported.', 'custom-login-page'), 'updated');
					
					self::$options['clp_options'] = $options;
					
					return self::$options;
				
				endif;
			
			endif;
		
		else :
		
			self::$options['last_open'] = $input['tab'];
			
			switch($input['tab']) :
			
				case 'main_tab' :
				
					self::$options['label_username'] = trim($input['label_username']);
					self::$options['label_password'] = trim($input['label_password']);
					self::$options['label_remember'] = trim($input['label_remember']);
					self::$options['label_log_in'] = trim($input['label_log_in']);
					self::$options['hide_remember'] = (@$input['hide_remember']) ? true : false;
					self::$options['value_remember'] = (@$input['value_remember']) ? true : false;
					self::$options['redirect'] = trim($input['redirect']);
					self::$options['form_id'] = trim($input['form_id']);
					self::$options['id_username'] = trim($input['id_username']);
					self::$options['id_password'] = trim($input['id_password']);
					self::$options['id_remember'] = trim($input['id_remember']);
					self::$options['id_submit'] = trim($input['id_submit']);
					self::$options['value_username'] = trim($input['value_username']);
					
					break;
					
				case 'widget_tab' :
				
					self::$options['container_background'] = trim($input['container_background']);
					self::$options['container_img_repeat'] = trim($input['container_img_repeat']);
					self::$options['container_img_pos'] = trim($input['container_img_pos']);
					self::$options['container_bg_color1'] = trim($input['container_bg_color1']);
					self::$options['container_bg_color2'] = trim($input['container_bg_color2']);
					self::$options['container_text_color'] = trim($input['container_text_color']);
					self::$options['container_transparency'] = trim($input['container_transparency']);
					self::$options['container_border_style'] = trim($input['container_border_style']);
					self::$options['container_border_width'] = trim($input['container_border_width']);
					self::$options['container_border_color'] = trim($input['container_border_color']);
					self::$options['container_border_round'] = trim($input['container_border_round']);
					self::$options['container_shadow_x'] = trim($input['container_shadow_x']);
					self::$options['container_shadow_y'] = trim($input['container_shadow_y']);
					self::$options['container_shadow_softness'] = trim($input['container_shadow_softness']);
					self::$options['container_shadow_color'] = trim($input['container_shadow_color']);
					self::$options['container_shadow_inset'] = @$input['container_shadow_inset'];
					self::$options['container_padding'] = trim($input['container_padding']);
					self::$options['container_margin'] = trim($input['container_margin']);
				
					break;
					
				case 'logo_tab' :
				
					if (isset($input['copy_logo'])) :

						$settings = array('logo', 'url', 'title', 'h1_margin', 'h1_padding', 'h1_corner', 'h1_shadow_x', 'h1_shadow_y', 'h1_shadow_softness', 'h1_shadow_color');

						self::copy_settings($settings);
					
					else : 
					
						self::$options['logo'] = trim($input['logo']);
						self::$options['url'] = trim($input['url']);
						self::$options['title'] = trim($input['title']);
						self::$options['h1_margin'] = trim($input['h1_margin']);
						self::$options['h1_padding'] = trim($input['h1_padding']);
						self::$options['h1_corner'] = trim($input['h1_corner']);
						self::$options['h1_shadow_x'] = trim($input['h1_shadow_x']);
						self::$options['h1_shadow_y'] = trim($input['h1_shadow_y']);
						self::$options['h1_shadow_softness'] = trim($input['h1_shadow_softness']);
						self::$options['h1_shadow_color'] = trim($input['h1_shadow_color']);
						self::$options['h1_shadow_inset'] = @$input['h1_shadow_inset'];
						
					endif;
					
					break;	
					
				case 'loginform_tab' :
				
					if (isset($input['copy_loginform'])) :

						$settings = array('loginform_background', 'loginform_img_repeat', 'loginform_img_pos', 'loginform_bg_color1', 'loginform_bg_color2', 'loginform_text_color', 'loginform_transparency', 'loginform_border_style', 'loginform_border_width', 'loginform_border_color', 'loginform_border_round', 'loginform_margin', 'loginform_padding', 'loginform_shadow_x', 'loginform_shadow_y', 'loginform_shadow_softness', 'loginform_shadow_color');

						self::copy_settings($settings);
					
					else : 
				
						self::$options['loginform_background'] = trim($input['loginform_background']);
						self::$options['loginform_img_repeat'] = trim($input['loginform_img_repeat']);
						self::$options['loginform_img_pos'] = trim($input['loginform_img_pos']);
						self::$options['loginform_bg_color1'] = trim($input['loginform_bg_color1']);
						self::$options['loginform_bg_color2'] = trim($input['loginform_bg_color2']);
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
						
					endif;
				
					break;
					
				case 'button_tab' :
				
					if (isset($input['copy_input'])) :

						$settings = array('input_text_color', 'input_bg_color', 'input_border_color', 'input_shadow_x', 'input_shadow_y', 'input_shadow_softness', 'input_shadow_color', 'input_shadow_inset');

						self::copy_settings($settings);
						
					else :
					
						self::$options['input_text_color'] = trim($input['input_text_color']);
						self::$options['input_bg_color'] = trim($input['input_bg_color']);
						self::$options['input_border_color'] = trim($input['input_border_color']);
						self::$options['input_shadow_x'] = trim($input['input_shadow_x']);
						self::$options['input_shadow_y'] = trim($input['input_shadow_y']);
						self::$options['input_shadow_softness'] = trim($input['input_shadow_softness']);
						self::$options['input_shadow_color'] = trim($input['input_shadow_color']);
						self::$options['input_shadow_inset'] = @$input['input_shadow_inset'];
					
					endif;
					
					self::$options['input_float'] = trim($input['input_float']);
					
					if (isset($input['copy_button'])) :

						$settings = array('button_bg_color1', 'button_bg_color2', 'button_text_color', 'button_border_color', 'btn_hover_bg_color1', 'btn_hover_bg_color2', 'btn_hover_text_color', 'btn_hover_border_color');

						self::copy_settings($settings);
						
					else :
					
						self::$options['button_bg_color1'] = trim($input['button_bg_color1']);
						self::$options['button_bg_color2'] = trim($input['button_bg_color2']);
						self::$options['button_text_color'] = trim($input['button_text_color']);
						self::$options['button_border_color'] = trim($input['button_border_color']);
						self::$options['btn_hover_bg_color1'] = trim($input['btn_hover_bg_color1']);
						self::$options['btn_hover_bg_color2'] = trim($input['btn_hover_bg_color2']);
						self::$options['btn_hover_text_color'] = trim($input['btn_hover_text_color']);
						self::$options['btn_hover_border_color'] = trim($input['btn_hover_border_color']);
						
					endif;
					
					break;
					
				case 'link_tab' :
				
					if (isset($input['copy_links'])) :
					
						$settings = array('link_text_color', 'link_textdecoration', 'link_shadow_x', 'link_shadow_y', 'link_shadow_softness', 'link_shadow_color', 'hover_text_color', 'hover_textdecoration', 'hover_shadow_x', 'hover_shadow_y', 'hover_shadow_softness', 'hover_shadow_color', 'link_size');

						self::copy_settings($settings);
				
					else:
				
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
						
					endif;
				
					break;	
					
				case 'css_tab' :
				
					if (isset($input['copy_stylesheet'])) :
					
						self::$options['css'] = CLP_DynamicCSS::$widget_css;
						self::$options['override'] = true;
						
						return self::$options;
					
					endif;
				
					self::$options['css'] = trim($input['css']);
					self::$options['override'] = (@$input['override']) ? true : NULL;
				
					break;
					
				case 'html_tab' :
				
					if (isset($input['copy_html'])) :
					
						$settings = array('login_message', 'login_form', 'login_footer');

						self::copy_settings($settings);
						
					else :
				
						self::$options['login_message'] = trim($input['login_message']);
						self::$options['login_form'] = trim($input['login_form']);
						self::$options['login_footer'] = trim($input['login_footer']);
						
					endif;
					
					self::$options['login_form_top'] = trim($input['login_form_top']);
					self::$options['login_form_bottom'] = trim($input['login_form_bottom']);
				
					break;
			
			endswitch;
			
			if (is_plugin_active_for_network(CLP_BASE)) add_settings_error('clp_widget_options', 'settings_updated', __('Settings saved.'), 'updated');
			
			self::$options['css_cache'] = '';
			
			return self::$options;
			
		endif;
	
	}
	
	/**
	 *
	 * Output widget for preview
	 *
	 */
	private static function preview_widget() {
		
		$eol = "\n";
		
		echo self::open_sortable('side_top');
		
		echo self::open_postbox(__('Preview'), 'preview-box');
		
		echo '<div id="custom_login_widget" class="widget_custom_login_widget" style="margin: 5px; padding: 20px;">'.$eol;
		
		echo '<div class="widget-title"><h3>'.__('Testing Widget', 'custom-login-page').'</h3></div>'.$eol;
		
		$formargs['redirect'] = (isset(self::$options['redirect']) && !empty(self::$options['redirect'])) ? self::$options['redirect'] : site_url( $_SERVER['REQUEST_URI'] );
		$formargs['form_id'] = (isset(self::$options['form_id']) && !empty(self::$options['form_id'])) ? self::$options['form_id'] : 'loginform';
		$formargs['label_username'] = (isset(self::$options['label_username']) && !empty(self::$options['label_username'])) ? self::$options['label_username'] : __('Username');
		$formargs['label_password'] = (isset(self::$options['label_password']) && !empty(self::$options['label_password'])) ? self::$options['label_password'] : __('Password');
		$formargs['label_remember'] = (isset(self::$options['label_remember']) && !empty(self::$options['label_remember'])) ? self::$options['label_remember'] : __('Remember Me');
		$formargs['label_log_in'] = (isset(self::$options['label_log_in']) && !empty(self::$options['label_log_in'])) ? self::$options['label_log_in'] : __('Log In');
		$formargs['id_username'] = (isset(self::$options['id_username']) && !empty(self::$options['id_username'])) ? self::$options['id_username'] : 'user_login';
		$formargs['id_password'] = (isset(self::$options['id_password']) && !empty(self::$options['id_password'])) ? self::$options['id_password'] : 'user_pass';
		$formargs['id_remember'] = (isset(self::$options['id_remember']) && !empty(self::$options['id_remember'])) ? self::$options['id_remember'] : 'rememberme';
		$formargs['id_submit'] = (isset(self::$options['id_submit']) && !empty(self::$options['id_submit'])) ? self::$options['id_submit'] : 'wp-submit';
		$formargs['remember'] = (isset(self::$options['hide_remember']) && !empty(self::$options['hide_remember'])) ? false : true;
		$formargs['value_username'] = (isset(self::$options['value_username']) && !empty(self::$options['value_username'])) ? self::$options['value_username'] : NULL;
		$formargs['value_remember'] = (isset(self::$options['value_remember']) && !empty(self::$options['value_remember'])) ? true : false;
		
		if (isset(self::$options['title']) && !empty(self::$options['title'])) $title_tag = ' title="'.self::$options['title'].'"';
		
		if (isset(self::$options['logo']) && !empty(self::$options['logo'])) $img_tag = '<img src="'.self::$options['logo'].'"'.@$title_tag.' />';
		
		if (isset($img_tag)) echo (isset(self::$options['url']) && !empty(self::$options['url'])) ? '<a href="'.self::$options['url'].'"'.@$title_tag.'>'.$img_tag.'</a>' : $img_tag;
			
		if (isset(self::$options['login_message']) && !empty(self::$options['login_message'])) echo self::$options['login_message'];
		
		wp_login_form($formargs);
		
		if (isset(self::$options['login_footer']) && !empty(self::$options['login_footer'])) echo self::$options['login_footer'];
		
		wp_loginout(home_url());
				
		echo ' | ';
		
		wp_register('Hubbel', 'Nubbel');
		
		echo '</div>'.$eol;
		
		echo self::close_postbox();
		
		echo self::close_sortable();
		
	}
	
	/**
	 *
	 * Copy settings from login page
	 *
	 */
	private static function copy_settings($settings) {
		
		$page_options = (is_plugin_active_for_network(CLP_BASE)) ? get_network_option(NULL, 'clp_options') : get_option('clp_options'); 
					
		foreach ($settings as $setting) self::$options[$setting] = $page_options[$setting];
		
	}

} // end of class

?>