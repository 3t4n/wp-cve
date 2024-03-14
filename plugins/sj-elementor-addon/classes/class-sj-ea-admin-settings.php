<?php

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if( !class_exists( 'SJEaAdminSettings' ) ) {

	class SJEaAdminSettings {

		static public $view_actions          = array();
		static public $menu_page_title		 = 'SJ Elementor Addon';
		static public $plugin_slug           = 'sjea';
		static public $is_top_level_page     = false;
		static public $is_multisite_active	 = false;
		static public $network_admin_active	 = false;
		static public $default_menu_position = 'options-general.php';
		static public $parent_page_slug      = 'welcome';
		static public $current_slug          = '';

		function __construct(){

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99);
		}

		static public function init_admin_settings() {

			self::$menu_page_title	=  apply_filters( 'sjea_menu_page_title', 'SJ Elementor Addon' );
			
			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {
				
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
				
				// Let extensions hook into saving.
				do_action( 'sjea_admin_settings_scripts' );
				
				
				self::save_settings();
			}

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );
			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu_rename', 9999 );

			if ( is_multisite() ) {
				
				self::$is_multisite_active   = true;
			
				self::$default_menu_position = 'themes.php';
				
				if ( is_network_admin() ) {
					self::$network_admin_active   = true;
					self::$default_menu_position = 'top';
				}

				add_action( 'network_admin_menu', __CLASS__ . '::add_admin_menu', 99 );
				add_action( 'network_admin_menu', __CLASS__ . '::add_admin_menu_rename', 9999 );
			}

			add_action( 'sjea_menu_welcome_action', __CLASS__ . '::welcome_page' );
			add_action( 'sjea_menu_connection_action', __CLASS__ . '::connection_page' );
		}

		static public function get_view_actions() {
			
			if ( empty( self::$view_actions ) ) {
				
				$actions = array( 
					'welcome'          => array(
											'label'	=> 'Welcome',
											'show'	=> true
										),
					'connection'          => array(
											'label'	=> 'Form Connections',
											'show'	=> !is_network_admin()
										),
				);

				self::$view_actions = apply_filters( 'sjea_menu_options', $actions );
			}

			return self::$view_actions;
		}

		/** 
		 * Save All admin settings here
		 *
		 * @since 0.1.3
		 * @return void
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if(!current_user_can('manage_options')) {
				return;
			}
			
			/* Save General Settings */
			self::connection_settings_save();

			// Let extensions hook into saving.
			do_action( 'sjea_admin_settings_save' );
		}

		static public function connection_settings_save() {

			if ( isset( $_POST['gen-clear-cache-nonce'] ) && wp_verify_nonce( $_POST['gen-clear-cache-nonce'], 'gen-clear-cache' ) ) {

				// Do
			}
		}
		/** 
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 0.1.3
		 * @return void
		 */
		static public function styles_scripts() {
			
			// Styles
			wp_enqueue_style( 'sjea-admin-settings', SJ_EA_URL . 'admin/assets/sjea-admin-settings.css', array(), SJ_EA_VERSION );
			wp_enqueue_script( 'sjea-admin-settings', SJ_EA_URL . 'admin/assets/sjea-admin-settings.js', array(), SJ_EA_VERSION, true );
			wp_enqueue_script( 'sjea-services', SJ_EA_URL . 'admin/assets/sjea-services.js', array(), SJ_EA_VERSION, true );
		}

		/**
		 * Init Nav Menu
		 * @Since 0.1.3
		 */		
		static public function init_nav_menu( $action =  '' ){

			// Menu position
			$position = false;

			if( $position ) {
				self::$default_menu_position = $position;
			}
			
			self::$is_top_level_page = in_array( self::$default_menu_position, array( 'top', 'middle', 'bottom' ), true );

			if( $action !== '' ) {
				self::render_tab_menu( $action );
			}
		}

		/**
		 * Render tab menu
		 *
		 * @since  0.1.3
		 */
		static public function render_tab_menu( $action = '' ) {
			echo '<div id="sjea-menu-page" class="wrap">';
			self::render($action);
			echo '</div>';
		}

		/**
		 * Prints HTML content for tabs	
		 * @since 0.1.3
		 */

		static public function render($action) {
			$output = '<h1 class="nav-tab-wrapper">';

			//$output .= "<span class='sjea-title'>".self::$menu_page_title."</span>";

			//$output .= "<span class='sjea-separator'></span>";	
			
			$view_actions = self::get_view_actions();;

			foreach ( $view_actions as $slug => $data ) {

				if ( !$data['show'] ) {
					continue;					
				}

				$url = self::get_page_url($slug);

				if( $slug == self::$parent_page_slug ) {
					update_option( "sjea_parent_page_url", $url );
				}

				$active = ( $slug == $action ) ? "nav-tab-active" : "";

				$output .= "<a class='nav-tab ".$active."' href='". $url ."'>".$data['label']."</a>";	
			}

			$output .= '</h1>';

			echo $output;
			
			if( isset( $_REQUEST['message'] ) && 'saved' == $_REQUEST['message'] ) {

				$message = __( 'Settings saved successfully.', 'sjea' );

				echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', $message  );
			}
		}

		static public function get_page_url($menu_slug) {

			$plugin_slug = self::$plugin_slug;

			if( self::$is_top_level_page ) {

				if ( self::$network_admin_active ) {
					
					if( $menu_slug == self::$parent_page_slug ) {
						$url = network_admin_url( "admin.php?page=".$plugin_slug );	
					} else {
						$url = network_admin_url( "admin.php?page=".$plugin_slug. "-" .$menu_slug );
					}
				}else{
					
					if( $menu_slug == self::$parent_page_slug ) {
						$url = admin_url( "admin.php?page=".$plugin_slug );	
					} else {
						$url = admin_url( "admin.php?page=".$plugin_slug. "-" .$menu_slug );
					}
				}
					
			} else {

				$parent_page = self::$default_menu_position;

				if( strpos( $parent_page, "?" ) !== false ) {
					$query_var = "&page=". $plugin_slug;
				}  else {
					$query_var = "?page=". $plugin_slug;
				}

				if ( self::$network_admin_active ) {
					$parent_page_url = network_admin_url( $parent_page . $query_var  );
				}else{
					$parent_page_url = admin_url( $parent_page . $query_var  );
				}
				$url = $parent_page_url . "&action=" . $menu_slug;
			}

			return $url;	
		}

		/**
		 * Add main menu
		 * @Since 0.1.3
		 */
		static public function add_admin_menu(){

			self::init_nav_menu('');
			
			$parent_page       = self::$default_menu_position;
			$is_top_level_page = self::$is_top_level_page;

			self::$current_slug = str_replace( "-", "_", self::$parent_page_slug );

			if( $is_top_level_page ) {

				switch ( $parent_page ) {
					case 'top':
						$position = 3; // position of Dashboard + 1
						break;
					case 'bottom':
						$position = ( ++$GLOBALS['_wp_last_utility_menu'] );
						break;
					case 'middle':
					default:
						$position = ( ++$GLOBALS['_wp_last_object_menu'] );
						break;
				}

				$page_title     = self::$menu_page_title;
				$capability     = 'manage_options';
				$page_menu_slug = self::$plugin_slug;
				$page_menu_func = __CLASS__ . '::menu_callback';

				add_menu_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func, 'dashicons-admin-customizer', $position );

				$actions = self::get_view_actions();

				foreach ( $actions as $menu_slug => $menu_data ) {
					
					if ( !$menu_data['show'] ) {
						continue;
					}

					if( $menu_slug !== self::$parent_page_slug ) {
						$callback_function = __CLASS__ . "::menu_callback";
						self::$current_slug  = $menu_slug;

						$page = add_submenu_page(
							self::$plugin_slug,
							__( $menu_data['label'], 'sjea' ),
							__( $menu_data['label'], 'sjea' ),
							"manage_options",
							self::$plugin_slug . "-" . $menu_slug,
							$callback_function
						);

						add_action( 'admin_footer-'. $page, __CLASS__ . '::admin_footer' );
					}
				}

			} else {

				$page_title     = self::$menu_page_title;
				$capability     = 'manage_options';
				$page_menu_slug = self::$plugin_slug;
				$page_menu_func = __CLASS__ . '::menu_callback';

				add_submenu_page( $parent_page, $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func ); 
			}

		}

		static public function menu_callback() {
			if( self::$is_top_level_page ) {
				
				$screen_base = $_REQUEST['page'];
				
				if ( self::$network_admin_active ) {
					$current_slug = str_replace( array( self::$plugin_slug ."-" ), "", $screen_base );
				}else{

					$current_slug = str_replace( array( self::$plugin_slug ."-"  ), "", $screen_base );
				}

				if( $current_slug == "sjea" ) {
					$current_slug = self::$parent_page_slug;
				}
				
			} else {

				$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;
			}

			$active_tab = str_replace( "_", "-", $current_slug );
			$current_slug = str_replace( "-", "_", $current_slug );

			echo '<div class="sjea-menu-page-wrapper">';
			self::init_nav_menu( $active_tab );
			echo '</div>';
			do_action( "sjea_menu_". $current_slug . "_action" );

		}

		static public function welcome_page() {

			require_once SJ_EA_DIR . 'admin/view-welcome.php';
		}

		static public function connection_page() {

			require_once SJ_EA_DIR . 'admin/view-connection.php';
		}
		
		static public function add_admin_menu_rename() {
			global $menu, $submenu;
			if( isset( $submenu[ self::$plugin_slug ][0][0] ) ) {
			    $submenu[ self::$plugin_slug ][0][0] = 'Welcome';
			}
		}

		/**
		 * Add footer link for dashboard.
		 * Since 0.1.3
		 */
		static public function admin_footer() {
		}

		/**
		 * Get Support form
		 * Since 0.1.3
		 */
		static public function get_support_form() {
			$form = '<form name="sjea-support" class="sjea-support-form" id="sjea-support-form" action="" method="post" onsubmit="return false;">';
                $form .=  '<table class="sjea-table">';
                    $form .= '<input type="hidden" name="action" value="sjea_submit_support_form" />';
                    $form .= '<input type="hidden" name="site_url" value="'.site_url().'" />';
                    $form .= '<tr><td><label for="name"><strong>'.__( 'Your Name:', 'sjea').'<span class="required"> *</span></strong></label></td></tr>';
                    $form .= '<tr><td><input required="required" type="text" class="sjea-field" name="name" /></td></tr>';

                    $form .= '<tr><td><label for="email"><strong>'.__( 'Your Email:', 'sjea').'<span class="required"> *</span></strong></label></td></tr>';
                    $form .= '<tr><td><input required="required" type="text" class="sjea-field" name="email" /></td></tr>';

                    $form .= '<tr><td><label for="subject"><strong>'.__( 'Subject:', 'sjea').'</strong></label></td></tr>';
                    $form .= '<tr><td>
                        <select class="sjea-field" name="subject"> 
                           <option value="question"> I have a question </option>
                           <option value="bug"> I found a bug </option>
                           <option value="help"> I need help </option>
                           <option value="professional">  I need professional service </option>
                           <option value="contribute"> I want to contribute my code</option>
                           <option value="other">  Other </option>                        
                        </select>
                     </td></tr>';
                    $form .= '<tr><td><label for="message"><strong>'.__( 'Your Query in Brief:', 'sjea').'</strong></label></td></tr>';
                    $form .= '<tr><td><textarea class="sjea-field sjea-textarea" name="message"></textarea></td></tr>';
                     
                    $form .= '<tr><td><div class="sjea-small-loader sjea-hidden"></div><input id="submit_request" class="button-primary" type="submit" value="Submit Request" /></td></tr>';
                    $form .= '<tr><td><div class="sjea-form-msg sjea-hidden"></div></td></tr>';
                $form .= '</table>';
            $form .= '</form>';

            return $form;
		}
	}

	new SJEaAdminSettings;
}