<?php
if( !class_exists('Ws247_piew') ):
	class Ws247_piew{
		 const FIELDS_GROUP = 'Ws247_piew-fields-group'; 
		 
		/**
		 * Constructor
		 */
		function __construct() {
			$this->slug = WS247_PIEW_SETTING_PAGE_SLUG;
			$this->option_group = self::FIELDS_GROUP;
			add_action('admin_menu',  array( $this, 'add_setting_page' ) );
			add_action('admin_init', array( $this, 'register_plugin_settings_option_fields' ) );
			add_action('admin_enqueue_scripts', array( $this, 'register_admin_css_js' ));
			add_filter('plugin_action_links', array( $this, 'add_action_link' ), 999, 2 );
			add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}
		
		public function add_action_link( $links, $file  ){
			$plugin_file = basename ( dirname ( WS247_PIEW ) ) . '/'. basename(WS247_PIEW, '');
			if($file == $plugin_file){
				$setting_link = '<a href="' . admin_url('admin.php?page='.WS247_PIEW_SETTING_PAGE_SLUG) . '">'.__( 'Settings' ).'</a>';
				array_unshift( $links, $setting_link );
			}
			return $links;
		}
		
		public function add_setting_page() {
			add_submenu_page( 
				'woocommerce',
				__("Setting", WS247_PIEW_TEXTDOMAIN),
				__("Configure Piew", WS247_PIEW_TEXTDOMAIN),
				'manage_options',
				$this->slug,
				array($this, 'the_content_setting_page')
			);
		}
		
		public function load_textdomain(){
			load_plugin_textdomain( WS247_PIEW_TEXTDOMAIN, false, dirname( plugin_basename( WS247_PIEW ) ) . '/languages/' ); 
		}
		
		public function register_admin_css_js(){
			wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( 'wp-color-picker');
			
			wp_enqueue_style( 'Ws247_piew_admin_piew.css',  WS247_PIEW_PLUGIN_INC_ASSETS_URL . '/admin_piew.css', false, '1.0' );
			wp_enqueue_script( 'Ws247_piew_admin_piew_js', WS247_PIEW_PLUGIN_INC_ASSETS_URL . '/admin_piew.js', array(), '1.0' );
		}
		
		static function create_option_prefix($field_name){
			return WS247_PIEW_PREFIX.$field_name;
		}
		
		public function get_option($field_name){
			return get_option(WS247_PIEW_PREFIX.$field_name);
		}
		
		static function class_get_option($field_name){
			return get_option(WS247_PIEW_PREFIX.$field_name);
		}
		
		public function register_field($field_name){
			register_setting( $this->option_group, WS247_PIEW_PREFIX.$field_name);
		}
		
		public function register_plugin_settings_option_fields() {
			/***
			****register list fields
			****/
			$arr_register_fields = array(
											//-------------------------------general tab
											'hover_effect', 'gallery_show', 'gallery_radius',
											'gallery_border_color', 'gallery_location',
											'product_border', 'product_border_color',
											'product_shadow', 'effect_bg_color', 'effect_text_color',
											'effect_bg_opacity', 'atc_bg_color', 'atc_color', 'atc_border_color',
											'product_border_radius', 'product_pad_bottom',
											'add_to_cart_radius','add_to_cart_bg', 'add_to_cart_color',
											'add_to_cart_bg_hover', 'add_to_cart_color_hover'
										);
			
			if($arr_register_fields){
				foreach($arr_register_fields as $key){
					$this->register_field($key);
				}
			}
		}
		
		public function the_content_setting_page(){
			require_once WS247_PIEW_PLUGIN_INC_DIR . '/option-form-template.php';
		}
		
		
		function register_scripts() {
			//Css
			wp_enqueue_style( 'wpshare247.com_piew.css', WS247_PIEW_PLUGIN_INC_ASSETS_URL . '/piew.css', false, '1.0' );
			
			//Fancybox3.5.7
			wp_enqueue_style( 'wpshare247.com_piew_jquery.fancybox.min.css', WS247_PIEW_PLUGIN_INC_ASSETS_URL . '/js/fancybox/dist/jquery.fancybox.min.css', false, '3.5.7' );
			wp_enqueue_script( 'wpshare247.com_piew_jquery.fancybox.min.js', WS247_PIEW_PLUGIN_INC_ASSETS_URL . '/js/fancybox/dist/jquery.fancybox.min.js', array('jquery'), '3.5.7' );
		}

	//End class--------------	
	}
	
	new Ws247_piew();
endif;
