<?php
if( !class_exists('Ws247_aio_ct_button') ):
	class Ws247_aio_ct_button{
		 const FIELDS_GROUP = 'Ws247_aio_ct_button-fields-group'; 
		 
		/**
		 * Constructor
		 */
		function __construct() {
			$this->slug = WS247_AIO_CT_BUTTON_SETTING_PAGE_SLUG;
			$this->option_group = self::FIELDS_GROUP;
			add_action('admin_menu',  array( $this, 'add_setting_page' ) );
			add_action('admin_init', array( $this, 'register_plugin_settings_option_fields' ) );
			add_action('admin_enqueue_scripts', array( $this, 'register_admin_css_js' ));
			add_filter('plugin_action_links', array( $this, 'add_action_link' ), 999, 2 );
			add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}
		
		public function add_action_link( $links, $file  ){
			$plugin_file = basename ( dirname ( WS247_AIO_CT_BUTTON ) ) . '/'. basename(WS247_AIO_CT_BUTTON, '');
			if($file == $plugin_file){
				$setting_link = '<a href="' . admin_url('admin.php?page='.WS247_AIO_CT_BUTTON_SETTING_PAGE_SLUG) . '">'.__( 'Settings' ).'</a>';
				array_unshift( $links, $setting_link );
			}
			return $links;
		}
		
		public function register_admin_css_js(){
			wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_script( 'admin_aio_ct_button', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/admin_aio_ct_button.js', array(), '1.0' );
		}
		
		public function add_setting_page() {
			add_submenu_page( 
				'options-general.php',
				__("Setting", WS247_AIO_CT_BUTTON_TEXTDOMAIN),
				__("Configure Aio Contact", WS247_AIO_CT_BUTTON_TEXTDOMAIN),
				'manage_options',
				$this->slug,
				array($this, 'the_content_setting_page')
			);
		}
		
		public function load_textdomain(){
			load_plugin_textdomain( WS247_AIO_CT_BUTTON_TEXTDOMAIN, false, dirname( plugin_basename( WS247_AIO_CT_BUTTON ) ) . '/languages/' ); 
		}
		
		static function create_option_prefix($field_name){
			return WS247_AIO_CT_BUTTON_PREFIX.$field_name;
		}
		
		public function get_option($field_name){
			return get_option(WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		static function class_get_option($field_name){
			return get_option(WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		public function register_field($field_name){
			register_setting( $this->option_group, WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		public function register_plugin_settings_option_fields() {
			/***
			****register list fields
			****/
			$arr_register_fields = array(
											//-------------------------------general tab
											'shake_hotline', 'hide_shake_hotline', 'shake_hotline_pos',
											'icon_fb_messenger','hide_icon_fb_messenger',
											'company_zalo', 'hide_company_zalo',
											'stt_email', 'hide_stt_email', 'stt_hotline',
											'hide_stt_hotline', 'icon_google_map', 'hide_icon_google_map', 'icons_pos',
											'hide_icons', 'text_fb_messenger','text_icon_google_map','text_stt_hotline',
											'text_stt_email', 'text_company_zalo', 'primary_color', 'hover_color',
											'text_color', 'icons_bottom', 'shake_hotline_bottom','talkto_embed','text_contact',
											'text_contact_color', 'is_zalo_shake_hotline'
										);
			
			if($arr_register_fields){
				foreach($arr_register_fields as $key){
					$this->register_field($key);
				}
			}
		}
		
		public function the_content_setting_page(){
			require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/option-form-template.php';
		}
		
		
		function register_scripts() {
			//Css
			wp_enqueue_style( 'wpshare247.com_aio_ct_button.css', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/aio_ct_button.css', false, '1.0' );
			
			//font-awesome-5.6.1
			wp_enqueue_style( 'wpshare247.com_font-awesome-5.6.1_css', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/js/font-awesome-5.6.1/css/all.min.css', false, '3.5.7' );
			
		}

	//End class--------------	
	}
	
	new Ws247_aio_ct_button();
endif;
