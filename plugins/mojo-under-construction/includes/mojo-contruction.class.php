<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'mojoContruction' ) ) :
	class mojoContruction {
		
		private $_optionsPage = 'underConstructionMainOptions';

		public function __construct() {
			add_action( 'template_redirect', array( $this, 'overrideWP' ) );
			add_action( 'admin_init', array( $this, 'adminOverrideWP' ) );
			add_action( 'wp_login', array( $this, 'adminOverrideWP' ) );
			add_action( 'plugins_loaded', array( $this, 'initTransalation' ) );
			add_action(  'admin_init', array( $this, 'adminInit' ) );
			add_action( 'admin_menu', array( $this, 'adminMenu' ) );

			//add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'pluginLinks') );

			add_option( 'underconstruction_global_notification', 1 );
		}

		public function getMainOptionsPage() {
			return $this->_optionsPage;
		}

		public function adminInit() {
			wp_register_script( 'underConstructionJS', MOJO_UC_BASE_URL . '/underconstruction.min.js' );
		}

		/*
		 * @todo Fix this at some point
		 */
		public function pluginLinks($links) {
			$links[] = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . $mojoContruction->getMainOptionsPage() ) ) . '">' . __( 'Settings' ) . '</a>';
		 	return $links;
		}

		public function changeMesage() {
			require_once( MOJO_UC_BASE_PATH . 'options-page.php' );
		}

		public function adminMenu() {
			/* Register our plugin page */
			$page = add_options_page( 'Under Construction Settings', 'Under Construction', 'activate_plugins', $this->_optionsPage, array( $this, 'changeMesage' ) );

			/* Using registered $page handle to hook script load */
			add_action( 'admin_print_scripts-' . $page, array( $this, 'enqueueScripts' ) );
		}

		public function enqueueScripts() {
			/*
			 * It will be called only on your plugin admin page, enqueue our script here
			 */
			wp_enqueue_script( 'scriptaculous' );
			wp_enqueue_script( 'underConstructionJS' );
		}

		public function initTransalation() {
			load_plugin_textdomain( 'underconstruction', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		public function overrideWP() {
			if ( $this->pluginIsActive() ) {
				if ( ! is_user_logged_in() ) {
					$array = get_option( 'underConstructionIPWhitelist' );
					
					if ( ! is_array( $array ) ) {
						$array = array($array);
					}
					
					if( ! in_array( $_SERVER['REMOTE_ADDR'], $array ) ) {

						// Send a 503 if the setting requires it
						if ( get_option( 'underConstructionHTTPStatus' ) == 503 ) {
							header( 'HTTP/1.1 503 Service Unavailable' );
						}

						// Send a 503 if the setting requires it
						if ( get_option( 'underConstructionHTTPStatus' ) == 301 ) {
							header( "HTTP/1.1 301 Moved Permanently" );
							header( "Location: " . get_option( 'underConstructionRedirectURL' ) );
						}

						// Send the Default Message
						if ( $this->displayStatusCodeIs( 0 ) ) {
							require_once ( MOJO_UC_BASE_PATH . 'defaultMessage.php' );
							displayDefaultComingSoonPage();
							die();
						}

						// They want the default with custom text!
						if ( $this->displayStatusCodeIs( 1 ) ) {
							require_once ( MOJO_UC_BASE_PATH . 'defaultMessage.php' );
							displayComingSoonPage( $this->getCustomPageTitle(), $this->getCustomHeaderText(), $this->getCustomBodyText() );
							die();
						}

						// They want custom HTML!
						if ( $this->displayStatusCodeIs( 2 ) ) {
							echo html_entity_decode( $this->getCustomHTML(), ENT_QUOTES );
							die();
						}
						
						// Try to use the under-contruction.php in the theme file
						if( $this->displayStatusCodeIs( 3 ) ) {
							require_once( get_template_directory() . '/under-construction.php' );
							die();
						}
					}
				}
			}
		}

		public function adminOverrideWP() {
			if( ! $this->pluginIsActive() ) {
				return;
			}

			if( get_option( 'underConstructionRequiredRole' ) && is_user_logged_in() ) {
				global $wp_roles;
				$all_roles = $wp_roles->roles;
						
				$editable_roles = apply_filters( 'editable_roles', $all_roles );

				$required_role = $editable_roles[ get_option( 'underConstructionRequiredRole' ) ];

				$new_privs = array();

				foreach ( $required_role[ 'capabilities' ] as $key => $value ) {
					if( $value == true ) {
						$new_privs[] = $key;
					}
				}

				if( ! current_user_can( $new_privs[0] ) ) {
					wp_logout();
					wp_redirect( get_bloginfo( 'url' ) );
				}
			}
		}

		public function getCustomHTML()	{
			return stripslashes( get_option( 'underConstructionHTML' ) );
		}

		public function activate() {
			if ( get_option( 'underConstructionArchive' ) ) {
				//get all the options back from the archive
				$options = get_option( 'underConstructionArchive' );

				//put them back where they belong
				update_option( 'underConstructionHTML', $options[ 'underConstructionHTML' ] );
				update_option( 'underConstructionActivationStatus', $options[ 'underConstructionActivationStatus' ] );
				update_option( 'underConstructionCustomText', $options[ 'underConstructionCustomText' ] );
				update_option( 'underConstructionDisplayOption', $options[ 'underConstructionDisplayOption' ] );
				update_option( 'underConstructionHTTPStatus', $options[ 'underConstructionHTTPStatus' ] );

				delete_option( 'underConstructionArchive' );
			}
		}

		public function deactivate()	{
			//get all the options. store them in an array
			$options = array();
			$options[ 'underConstructionHTML' ] = get_option( 'underConstructionHTML' );
			$options[ 'underConstructionActivationStatus' ] = get_option( 'underConstructionActivationStatus' );
			$options[ 'underConstructionCustomText' ] = get_option( 'underConstructionCustomText' );
			$options[ 'underConstructionDisplayOption' ] = get_option( 'underConstructionDisplayOption' );
			$options[ 'underConstructionHTTPStatus' ] = get_option( 'underConstructionHTTPStatus' );

			//store the options all in one record, in case we ever reactivate the plugin
			update_option( 'underConstructionArchive', $options );

			//delete the separate ones
			delete_option( 'underConstructionHTML' );
			delete_option( 'underConstructionActivationStatus' );
			delete_option( 'underConstructionCustomText' );
			delete_option( 'underConstructionDisplayOption' );
			delete_option( 'underConstructionHTTPStatus' );
			delete_option( 'underconstruction_global_notification' );
		}

		public function underConstructionPlugin_delete() {
			delete_option( 'underConstructionArchive' );
		}

		public function pluginIsActive() {
			// If it's not set yet
			if ( ! get_option( 'underConstructionActivationStatus' ) ) {
				return false;
			}

			if ( get_option( 'underConstructionActivationStatus' ) == 1 ) {
				return true;
			} else {
				return false;
			}
		}

		public function httpStatusCodeIs($status) {
			// If it's not set yet
			if ( ! get_option( 'underConstructionHTTPStatus' ) ) {
				// Set it
				update_option( 'underConstructionHTTPStatus', 200 );
			}

			if ( get_option( 'underConstructionHTTPStatus' ) == $status ) {
				return true;
			} else {
				return false;
			}

		}

		public function displayStatusCodeIs($status) {
			if ( ! get_option( 'underConstructionDisplayOption' ) ) {
				update_option( 'underConstructionDisplayOption', 0 );
			}

			if ( get_option( 'underConstructionDisplayOption' ) == $status ) {
				return true;
			} else {
				return false;
			}
		}

		public function getCustomPageTitle() {
			if ( get_option( 'underConstructionCustomText' ) != false )	{
				$fields = get_option( 'underConstructionCustomText' );
				return stripslashes( $fields[ 'pageTitle' ] );
			} else {
				return '';
			}
		}

		public function getCustomHeaderText() {
			if ( get_option( 'underConstructionCustomText' ) != false ) {
				$fields = get_option( 'underConstructionCustomText' );
				return stripslashes( $fields[ 'headerText' ] );
			} else {
				return '';
			}
		}

		public function getCustomBodyText() {
			if ( get_option( 'underConstructionCustomText' ) != false ) {
				$fields = get_option( 'underConstructionCustomText' );
				return stripslashes( $fields[ 'bodyText' ] );
			} else {
				return '';
			}
		}

	} // End Class

endif; // End Class if