<?php
namespace BZ_RAP;

define('BZRAP_BASE_DIR', 	dirname(__FILE__) . '/');
define('BZRAP_PRODUCT_ID',   'RAMP');
define('BZRAP_VERSION',   	'1.0');
define('BZRAP_DIR_PATH', plugin_dir_path( __DIR__ ));
define('BZ_RAP_NS','BZ_RAP');
define('BZRAP_PLUGIN_FILE', 'rebrand-amelia/rebrand-amelia.php');   //Main base file

class BZRebrandAmeliaSettings  {
	
		public $pageslug 	   = 'amelia-rebrand';
	
		static public $rebranding = array();
		static public $redefaultData = array();
	
		public function init() { 
		
			$blog_id = get_current_blog_id();
			
			self::$redefaultData = array(
				'plugin_name'       	=> '',
				'plugin_desc'       	=> '',
				'plugin_author'     	=> '',
				'plugin_uri'        	=> '',
				
			);
        
			
			
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			} 

		if ( is_plugin_active( 'blitz-rebrand-amelia-pro/blitz-rebrand-amelia-pro.php' ) ) {
			
			deactivate_plugins( plugin_basename(__FILE__) );
			$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'Plugin could not be activated, either deactivate the Lite version or Pro version', 'simplewlv' ). '</p>';
			die($error_message); 
		 
			return;
		}
		$this->bzrap_activation_hooks();	
		}
		
	
		
		/**
		 * Init Hooks
		*/
		public function bzrap_activation_hooks() {
			
			global $blog_id;
			
			$rebranding = $this->bzrap_get_rebranding();
			
			$bzrap_new_text = $translated_text;
			$bzrap_name = isset( $rebranding['plugin_name'] ) && ! empty( $rebranding['plugin_name'] ) ? $rebranding['plugin_name'] : '';
			
			if ( $bzrap_name != '' ) {
				add_filter( 'gettext', 			array($this, 'bzrap_update_label'), 20, 3 );
			}
			
			
			add_filter( 'all_plugins', 		array($this, 'bzrap_plugin_branding'), 10, 1 );

			add_action( 'admin_menu',		array($this, 'bzrap_menu'), 100 );
			add_action( 'admin_enqueue_scripts',				  array($this, 'bzrap_adminloadStyles'));
			add_action( 'admin_init',		array($this, 'bzrap_save_settings'));			
	        add_action( 'admin_head', 		array($this, 'bzrap_branding_styles') );
	        add_action( 'admin_head', 		array($this, 'bzrap_branding_scripts') );
	        add_action( 'elementor/editor/after_enqueue_styles', 			array($this, 'bzrap_branding_front_scripts') );
	        

	        if(is_multisite()){
				if( $blog_id == 1 ) {
					switch_to_blog($blog_id);
						add_filter('screen_settings',			array($this, 'bzrap_hide_rebrand_from_menu'), 20, 2);	
					restore_current_blog();
				}
			} else {
				add_filter('screen_settings',			array($this, 'bzrap_hide_rebrand_from_menu'), 20, 2);
			}
		}
		
	
	
			
		/**
		 * Add screen option to hide/show rebrand options
		*/
		public function bzrap_hide_rebrand_from_menu($rapcurrent, $screen) {

			$rebranding = $this->bzrap_get_rebranding();

			$rapcurrent .= '<fieldset class="admin_ui_menu"> <legend> Rebrand - '.$rebranding['plugin_name'].' </legend><p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>';
			

			if($this->bzrap_getOption( 'rebrand_amelia_screen_option','' )){
				
				$amelia_screen_option = $this->bzrap_getOption( 'rebrand_amelia_screen_option',''); 
				
				if($amelia_screen_option=='show'){
					//$current .='It is showing now. ';
					$rapcurrent .= __('Hide the "','bzrap').$rebranding['plugin_name'].__(' - Rebrand" menu item?','bzrap') .$hide;
					$rapcurrent .= '<style>#adminmenu .toplevel_page_amelia a[href="admin.php?page=wpamelia-rebrand"]{display:block;}</style>';
				} else {
					//$current .='It is disabling now. ';
					$rapcurrent .= __('Show the "','bzrap').$rebranding['plugin_name'].__(' - Rebrand" menu item?','bzrap') .$show;
					$rapcurrent .= '<style>#adminmenu .toplevel_page_amelia a[href="admin.php?page=wpamelia-rebrand"]{display:none;}</style>';
				}		
				
			} else {
					//$current .='It is showing now. ';
					$rapcurrent .= __('Hide the "','bzrap').$rebranding['plugin_name'].__(' - Rebrand" menu item?','bzrap') .$hide;
					$rapcurrent .= '<style>#adminmenu .toplevel_page_amelia a[href="admin.php?page=wpamelia-rebrand"]{display:block;}</style>';
			}	

			$rapcurrent .=' <br/><br/> </fieldset>' ;
			
			return $rapcurrent;
		}
		
		  
		
			
		/**
		* Loads admin styles & scripts
		*/
		public function bzrap_adminloadStyles(){
			
			if(isset($_REQUEST['page'])){
				
				if($_REQUEST['page'] == $this->pageslug){
					
					wp_enqueue_media();
					
					wp_register_style( 'bzrap_css', plugins_url('assets/css/bzrap-main.css', __FILE__) );
					wp_enqueue_style( 'bzrap_css' );
					
					wp_register_script( 'bzrap_js', plugins_url('assets/js/bzrap-main-settings.js', __FILE__ ), '', '', true );
					wp_enqueue_script( 'bzrap_js' );
					
				}
			}
		}	
		
		
		
		
	   public function bzrap_get_rebranding() {
			
			if ( ! is_array( self::$rebranding ) || empty( self::$rebranding ) ) {
				if(is_multisite()){
					switch_to_blog(1);
						self::$rebranding = get_option( 'amelia_rebrand');
					restore_current_blog();
				} else {
					self::$rebranding = get_option( 'amelia_rebrand');	
				}
			}

			return self::$rebranding;
		}
		
		
		
	    /**
		 * Render branding fields.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function bzrap_render_fields() {
		
			if(is_multisite()){
				switch_to_blog(1);
					$branding = get_option( 'amelia_rebrand');
				restore_current_blog();
			} else {
				$branding = get_option( 'amelia_rebrand');	
			}	
			include BZRAP_BASE_DIR . 'admin/bzrap-settings-rebranding.php';
		}
		
		
		
	
		/**
		 * Admin Menu
		*/   
		public function bzrap_menu() {
			
			global $menu, $blog_id;
			global $submenu;	
			
		    $admin_label = __('Rebrand', 'bzrap');
			$rebranding = $this->bzrap_get_rebranding();
			
			if ( current_user_can( 'manage_options' ) ) {    

				$parent_slug = 'amelia';
				$page_title  = __( 'Rebrand', 'bzrap' );
				$menu_title  = __( 'Rebrand', 'bzrap' );
				$capability  = 'manage_options';
				$menu_slug   = $this->pageslug;
				$callback    = array($this, 'bzrap_render');

				if( is_multisite() ) {
					if( $blog_id == 1 ) { 
						$hook = add_submenu_page(
							$parent_slug,
							$page_title,
							$menu_title,
							$capability,
							$menu_slug,
							$callback
						);
					}
				} else {
						$hook = add_submenu_page(
							$parent_slug,
							$page_title,
							$menu_title,
							$capability,
							$menu_slug,
							$callback
						);					
				}
			}	
			
			//~ print_r($menu);
			
			foreach($menu as $custommenusK => $custommenusv ) {  
				if( $menu[$custommenusK][0] == 'Amelia' ) {
					if(isset($rebranding['plugin_name']) && $rebranding['plugin_name'] != '' ) {
						$menu[$custommenusK][0] = $rebranding['plugin_name']; //change menu Label
					}
				}
			}
			
			return $menu;
		}
		
		
		
		
		public function bzrap_render() {
			$this->bzrap_render_fields();
		}
		
	
	
	
	
		public function bzrap_save_settings() {
			
			if ( ! isset( $_POST['ame_wl_nonce'] ) || ! wp_verify_nonce( $_POST['ame_wl_nonce'], 'ame_wl_nonce' ) ) {
				return;
			}

			if ( ! isset( $_POST['submit'] ) ) {
				return;
			}

			$this->bzrap_update_branding();
		}
		
		
	
		public function bzrap_branding_styles() {
			
			global $wpdb;
			
			if ( ! is_user_logged_in() ) {
				return;
			}
			$rebranding = $this->bzrap_get_rebranding();
			echo '<style id="ame-wl-admin-style">';
			include BZRAP_BASE_DIR . 'admin/bzrap-style.css.php';
			echo '</style>';
		}	
	
	
			
		public function bzrap_branding_scripts() {
			
			if ( ! is_user_logged_in() ) {
				return;
			}
			$rebranding = $this->bzrap_get_rebranding();
			
			//~ echo '<pre/>';
			//~ print_r($rebranding);
			
			echo '<script id="ame-wl-admin-script">';
			include BZRAP_BASE_DIR . 'admin/bzrap-script.js.php';
			echo '</script>';
		}	
		
	
	
			
		public function bzrap_branding_front_scripts() {
			
			echo '<style type="text/css">
					#elementor-panel-category-amelia-elementor .elementor-panel-category-items .elementor-element-wrapper .icon i.amelia-logo:before, #elementor-panel-elements-wrapper #elementor-panel-elements .elementor-element-wrapper .elementor-element .icon i.amelia-logo:before, #elementor-panel-page-editor .elementor-control .elementor-control-content .amelia-elementor-content:before {
						background-image: none;
						content: "\f145";
						background-repeat: no-repeat;
						background-size: unset;
						font-style: normal;
						background-position: unset;
					}
					#elementor-panel-category-amelia-elementor .elementor-panel-category-items .elementor-element-wrapper .icon i.amelia-logo, #elementor-panel-elements-wrapper #elementor-panel-elements .elementor-element-wrapper .elementor-element .icon i.amelia-logo, #elementor-panel-page-editor .elementor-control .elementor-control-content .amelia-elementor-content {
						background: none;
						font-family: dashicons;
					}
			';
		    echo '</style>';
		}
		


	    public function bzrap_update_branding() {
			
			if ( ! isset($_POST['ame_wl_nonce']) ) {
				return;
			}

			$data = array(
				'plugin_name'       => isset( $_POST['ame_wl_plugin_name'] ) ? sanitize_text_field( $_POST['ame_wl_plugin_name'] ) : '',
				
				'plugin_desc'       => isset( $_POST['ame_wl_plugin_desc'] ) ? sanitize_text_field( $_POST['ame_wl_plugin_desc'] ) : '',
				
				'plugin_author'     => isset( $_POST['ame_wl_plugin_author'] ) ? sanitize_text_field( $_POST['ame_wl_plugin_author'] ) : '',
				
				'plugin_uri'     => isset( $_POST['ame_wl_plugin_uri'] ) ? sanitize_text_field( $_POST['ame_wl_plugin_uri'] ) : '',
			);

			update_option( 'amelia_rebrand', $data );
		}
    
    
     
    
        public function bzrap_plugin_branding( $all_plugins ) {
			
			
			if (  ! isset( $all_plugins['ameliabooking/ameliabooking.php'] ) ) {
				return $all_plugins;
			}

			$rebranding = $this->bzrap_get_rebranding();
			
			$all_plugins['ameliabooking/ameliabooking.php']['Name']           = ! empty( $rebranding['plugin_name'] )     ? $rebranding['plugin_name']      : $all_plugins['ameliabooking/ameliabooking.php']['Name'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['PluginURI']      = ! empty( $rebranding['plugin_uri'] )      ? $rebranding['plugin_uri']       : $all_plugins['ameliabooking/ameliabooking.php']['PluginURI'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['Description']    = ! empty( $rebranding['plugin_desc'] )     ? $rebranding['plugin_desc']      : $all_plugins['ameliabooking/ameliabooking.php']['Description'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['Author']         = ! empty( $rebranding['plugin_author'] )   ? $rebranding['plugin_author']    : $all_plugins['ameliabooking/ameliabooking.php']['Author'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['AuthorURI']      = ! empty( $rebranding['plugin_uri'] )      ? $rebranding['plugin_uri']       : $all_plugins['ameliabooking/ameliabooking.php']['AuthorURI'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['Title']          = ! empty( $rebranding['plugin_name'] )     ? $rebranding['plugin_name']      : $all_plugins['ameliabooking/ameliabooking.php']['Title'];
			
			$all_plugins['ameliabooking/ameliabooking.php']['AuthorName']     = ! empty( $rebranding['plugin_author'] )   ? $rebranding['plugin_author']    : $all_plugins['ameliabooking/ameliabooking.php']['AuthorName'];
			
			return $all_plugins;
			
		}
	
    	
	
		public function bzrap_update_label( $translated_text, $untranslated_text, $domain ) {
			
			$rebranding = $this->bzrap_get_rebranding();
			
			$bzrap_new_text = $translated_text;
			$bzrap_name = isset( $rebranding['plugin_name'] ) && ! empty( $rebranding['plugin_name'] ) ? $rebranding['plugin_name'] : '';
			
			if ( $bzrap_name != '' ) {
				$bzrap_new_text = str_replace( 'Amelia', $bzrap_name, $bzrap_new_text );
			}
			
			return $bzrap_new_text;
		}
	
	
		
		
		   
		/**
		 * update options
		*/
		public function bzrap_updateOption($key,$value) {
			if(is_multisite()){
				return  update_site_option($key,$value);
			}else{
				return update_option($key,$value);
			}
		}
		
		

		   
		/**
		 * get options
		*/	
		public function bzrap_getOption($key,$default=False) {
			if(is_multisite()){
				switch_to_blog(1);
				$value = get_site_option($key,$default);
				restore_current_blog();
			}else{
				$value = get_option($key,$default);
			}
			return $value;
		}
		
		
		public function get_icon() {
			return 'eicon-favorite';
		}
		
		
	
} //end Class
