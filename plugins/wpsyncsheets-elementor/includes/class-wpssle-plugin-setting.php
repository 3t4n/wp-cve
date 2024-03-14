<?php
/**
 * Handle plugin installation upon activation.
 *
 * @package wpsyncsheets-elementor
 */

use ElementorPro\Plugin;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Module;
use WPSyncSheetsElementor\WPSSLE_Google_API_Functions;
/**
 * Class WPSSLE_Plugin_Setting.
 *
 * @since 1.0.0
 */
class WPSSLE_Plugin_Setting {

	/**
	 * Instance of Plugin_Settings
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Instance of WPSSLE_Feed_Settings
	 *
	 * @var $instanceaddon
	 */
	private static $instanceaddon = null;

	/**
	 * Instance of Google_API_Functions
	 *
	 * @var $instance_api
	 */
	private static $instance_api = null;
	/**
	 * Initialization
	 */
	public static function wpssle_initilization() {
		add_action( 'admin_menu', __CLASS__ . '::wpssle_menu_page', 20 );
		add_action( 'elementor/editor/after_enqueue_scripts', __CLASS__ . '::wpssle_load_wp_admin_style' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::wpssle_selectively_enqueue_admin_script' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::wpssle_load_wp_admin_style' );
		add_filter( 'plugin_row_meta', __CLASS__ . '::wpssle_plugin_row_meta', 10, 2 );
		add_action( 'elementor_pro/init', __CLASS__ . '::wpssle_init' );
		add_action( 'elementor/ajax/register_actions', __CLASS__ . '::wpssle_ajax_register_action' );
		add_action( 'elementor/editor/after_save', __CLASS__ . '::wpssle_after_save_settings', 9999, 2 );
		add_action( 'wp_ajax_wpssle_reset_settings', __CLASS__ . '::wpssle_reset_settings' );
		self::wpssle_google_api();
		self::instance();
	}

	/**
	 * Main WPSSLE_Plugin_Settings Instance.
	 *
	 * @since 1.0.0
	 *
	 * @return instance
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Create WPSSLE_Feed_Settings Class Instance.
	 */
	public static function get_addoninstance() {
		if ( null === self::$instanceaddon ) {
			self::$instanceaddon = new \WPSSLE_Feed_Settings();
		}
		return self::$instanceaddon;
	}

	/**
	 * Create Google Api Instance.
	 */
	public static function wpssle_google_api() {

		if ( null === self::$instance_api ) {
			self::$instance_api = new WPSSLE_Google_API_Functions();
		}
		return self::$instance_api;
	}
	/**
	 * Action fire after Save from Elementor Editor.
	 *
	 * @param int   $wpssle_post_id Post ID.
	 * @param array $wpssle_formdata Template Data.
	 */
	public static function wpssle_after_save_settings( $wpssle_post_id, $wpssle_formdata ) {
		global $wpssle_header_list, $wpssle_spreadsheetid, $wpssle_exclude_headertype;

		$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );

		// phpcs:ignore
		if ( ! isset( $_REQUEST['actions'] ) || empty( $_REQUEST['actions'] ) ) {
			return;
		}
		// phpcs:ignore
		$wpssle_data = json_decode( sanitize_text_field( wp_unslash( $_REQUEST['actions'] ) ) , true );

		$wpssle_data = Plugin::elementor()->db->iterate_data(
			$wpssle_data,
			function( $wpssle_element ) use ( &$do_update ) {
				if ( 'form' === (string) $wpssle_element['widgetType'] || 'global' === (string) $wpssle_element['widgetType'] ) {

					global $wpssle_header_list, $wpssle_spreadsheetid, $wpssle_exclude_headertype;
					$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );
					if ( isset( $wpssle_element['settings'] ) && isset( $wpssle_element['settings']['submit_actions'] ) && in_array( 'WPSyncSheets', $wpssle_element['settings']['submit_actions'], true ) ) {
						$wpssle_settings      = $wpssle_element['settings'];
						$wpssle_header_list   = array();
						$wpssle_spreadsheetid = $wpssle_settings['spreadsheetid'];
						$wpssle_sheetname     = $wpssle_settings['sheet_name'];
						$wpssle_sheetheaders  = $wpssle_settings['sheet_headers'];
						$wpssle_freeze_header = $wpssle_settings['freeze_header'];

						foreach ( $wpssle_settings['form_fields'] as $wpssle_form_fields ) {
							if ( ( ! isset( $wpssle_form_fields['field_type'] ) || ( isset( $wpssle_form_fields['field_type'] ) && ! in_array( $wpssle_form_fields['field_type'], $wpssle_exclude_headertype, true ) ) ) && in_array( $wpssle_form_fields['custom_id'], $wpssle_sheetheaders, true ) ) {
								$wpssle_header_list[] = $wpssle_form_fields['field_label'] ? $wpssle_form_fields['field_label'] : ucfirst( $wpssle_form_fields['custom_id'] );
							}
						}
						$wpssle_is_new = 0;
						if ( 'new' === (string) $wpssle_spreadsheetid ) {
							$wpssle_newsheetname = trim( $wpssle_settings['new_spreadsheet_name'] );

							/*
							 *Create new spreadsheet
							 */
							$requestbody          = self::$instance_api->createspreadsheetobject( $wpssle_newsheetname );
							$wpssle_response      = self::$instance_api->createspreadsheet( $requestbody );
							$wpssle_spreadsheetid = $wpssle_response['spreadsheetId'];
							$wpssle_is_new        = 1;
						}

						$wpssle_existingsheetsnames = array();
						$response                   = self::$instance_api->get_sheet_listing( $wpssle_spreadsheetid );
						$wpssle_existingsheetsnames = self::$instance_api->get_sheetname_list( $response );
						if ( ! in_array( $wpssle_sheetname, $wpssle_existingsheetsnames, true ) ) {
							/*
							 *Create new sheet into spreadsheet
							 */
							$wpssle_body = self::$instance_api->createsheetobject( $wpssle_sheetname );
							try {
								$requestobject                  = array();
								$requestobject['spreadsheetid'] = $wpssle_spreadsheetid;
								$requestobject['requestbody']   = $wpssle_body;
								self::$instance_api->formatsheet( $requestobject );
							} catch ( Exception $e ) {
								echo esc_html( $e->getMessage() );
							}

							/*
							 * Insert Sheet Headers into sheet
							 */
							$wpssle_header_list = array_values( array_unique( $wpssle_header_list ) );

							$wpssle_range       = trim( $wpssle_sheetname ) . '!A1';
							$wpssle_requestbody = self::$instance_api->valuerangeobject( array( $wpssle_header_list ) );
							$wpssle_params      = self::$instance->get_row_format();
							$param              = self::$instance_api->setparamater( $wpssle_spreadsheetid, $wpssle_range, $wpssle_requestbody, $wpssle_params );
							self::$instance_api->appendentry( $param );

							if ( $wpssle_is_new ) {
								$wpssle_requestbody             = self::$instance_api->deletesheetobject();
								$requestobject                  = array();
								$requestobject['spreadsheetid'] = $wpssle_spreadsheetid;
								$requestobject['requestbody']   = $wpssle_requestbody;
								self::$instance_api->formatsheet( $requestobject );
							}
						} else {
							$wpssle_range       = trim( $wpssle_sheetname ) . '!A1';
							$wpssle_requestbody = self::$instance_api->valuerangeobject( array( $wpssle_header_list ) );
							$wpssle_params      = self::$instance->get_row_format();
							$param              = self::$instance_api->setparamater( $wpssle_spreadsheetid, $wpssle_range, $wpssle_requestbody, $wpssle_params );
							self::$instance_api->updateentry( $param );
						}
						if ( 'yes' === (string) $wpssle_freeze_header ) {
							$wpssle_freeze = 1;
						} else {
							$wpssle_freeze = 0;
						}
						self::$instance->wpssle_freeze_header( $wpssle_spreadsheetid, $wpssle_sheetname, $wpssle_freeze );
					}
				}
			}
		);

		if ( ! empty( $wpssle_spreadsheetid ) && 'new' !== (string) $wpssle_spreadsheetid ) {
			$wpssle_saved_data = get_post_meta( $wpssle_post_id, '_elementor_data' );

			$wpssle_data = json_decode( $wpssle_saved_data[0], true );
			global $existincurrentpage;
			$existincurrentpage = 'no';
			array_walk_recursive(
				$wpssle_data,
				function ( &$existvalue, $existkey ) {
					if ( 'WPSyncSheets' === (string) $existvalue ) {
						global $existincurrentpage;
						$existincurrentpage = 'yes';
					}
				}
			);
			array_walk_recursive(
				$wpssle_data,
				function ( &$existvalue, $existkey ) {
					if ( 'widgetType' === (string) $key ) {
						global $existincurrentpage;
						if ( 'form' === (string) $value ) {
							$existincurrentpage = 'yes';
						} else {
							$existincurrentpage = 'no';
						}
					}
				}
			);
			array_walk_recursive(
				$wpssle_data,
				function ( &$value, $key ) {

					global $existincurrentpage, $wpssle_spreadsheetid;

					if ( 'yes' === (string) $existincurrentpage ) {

						if ( 'spreadsheetid' === (string) $key ) {
							$value = $wpssle_spreadsheetid;
						}
						if ( 'new_spreadsheet_name' === (string) $key ) {
							$value = '';
						}
					}
				}
			);
			if ( 'yes' === (string) $existincurrentpage ) {
				$wpssle_json_value = wp_slash( wp_json_encode( $wpssle_data ) );
				update_post_meta( $wpssle_post_id, '_elementor_data', $wpssle_json_value );
			}
		}
	}
	/**
	 * Freeze First Row of the Google Spreadsheet.
	 *
	 * @param string $wpssle_spreadsheetname Spreadsheet ID.
	 * @param string $wpssle_sheetname Sheet Name.
	 * @param int    $wpssle_freeze 1 - Freeze Header, 0 - Unfreeze header.
	 */
	public static function wpssle_freeze_header( $wpssle_spreadsheetname, $wpssle_sheetname, $wpssle_freeze ) {
		$response                   = self::$instance_api->get_sheet_listing( $wpssle_spreadsheetname );
		$wpssle_existingsheetsnames = self::$instance_api->get_sheetid_list( $response );

		$wpssle_is_exist = array_search( $wpssle_sheetname, $wpssle_existingsheetsnames, true );
		if ( $wpssle_is_exist ) {
			$requestbody                    = self::$instance_api->freezeobject( $wpssle_is_exist, $wpssle_freeze );
			$requestobject                  = array();
			$requestobject['spreadsheetid'] = $wpssle_spreadsheetname;
			$requestobject['requestbody']   = $requestbody;
			self::$instance_api->formatsheet( $requestobject );
		}
	}

	/**
	 * Initialize Feed Addon.
	 */
	public static function wpssle_init() {
		// Here its safe to include our action class file.
		include_once dirname( __FILE__ ) . '/class-wpssle-form-sheets-action.php';
		// Instantiate the action class.
		$wpssle_action = new WPSSLE_Form_Sheets_Action();
		// Register the action with form widget.
		\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $wpssle_action->get_name(), $wpssle_action );
	}

	/**
	 * Load JS and CSS File.
	 */
	public static function wpssle_load_wp_admin_style() {
		// phpcs:ignore
		if ( isset( $_GET['page'] ) && 'wpsyncsheets-elementor' === (string) sanitize_text_field( $_GET['page'] ) ) {

			wp_register_style( 'wpssle-wp-admin-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/wpssle-admin-style.css', false, WPSSLE_VERSION );
			wp_enqueue_style( 'wpssle-wp-admin-style' );
			wp_register_script( 'wpssle-wp-admin-script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/wpssle-admin-script.js', false, WPSSLE_VERSION, true );
			wp_localize_script(
				'wpssle-wp-admin-script',
				'admin_ajax_object',
				array(
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'sync_nonce_token' => wp_create_nonce( 'sync_nonce' ),
				)
			);
			wp_enqueue_script( 'wpssle-wp-admin-script' );
		}
		// phpcs:ignore
		if ( isset( $_GET['action'] ) && 'elementor' === (string) sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {
			wp_register_script( 'wpssle-wp-customadmin', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/wpssle-custom-elementor.js', false, WPSSLE_VERSION, true );
			wp_localize_script(
				'wpssle-wp-customadmin',
				'customadmin_ajax_object',
				array(
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'sync_nonce_token' => wp_create_nonce( 'sync_nonce' ),
				)
			);
			wp_enqueue_script( 'wpssle-wp-customadmin' );
		}
	}

	/**
	 * Enqueue css and js files
	 */
	public static function wpssle_selectively_enqueue_admin_script() {
		wp_enqueue_script( 'wpssle-general-script', WPSSLE_URL . 'assets/js/wpssle-general.js', WPSSLE_VERSION, true, false );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param  mixed $wpssle_links Plugin Row Meta.
	 * @param  mixed $wpssle_file  Plugin Base file.
	 * @return array
	 */
	public static function wpssle_plugin_row_meta( $wpssle_links, $wpssle_file ) {
		if ( 'wpsyncsheets-elementor/wpsyncsheets-lite-elementor.php' === (string) $wpssle_file ) {

			$wpssle_row_meta = array(
				'docs' => '<a href="' . esc_url( WPSSLE_DOCUMENTATION_URL ) . '" title="' . esc_attr( __( 'View Documentation', 'wpsse' ) ) . '" target="_blank">' . esc_html__( 'View Documentation', 'wpsse' ) . '</a>',
			);
			return array_merge( $wpssle_links, $wpssle_row_meta );
		}
		return (array) $wpssle_links;
	}

	/**
	 * Register a plugin menu page.
	 */
	public static function wpssle_menu_page() {
		global $admin_page_hooks, $_parent_pages;
		if ( ! isset( $admin_page_hooks['wpsyncsheets_lite'] ) ) {
			$wpssle_page = add_menu_page(
				esc_attr__( 'WPSyncSheets Lite', 'wpsse' ),
				'WPSyncSheets Lite',
				'manage_options',
				'wpsyncsheets_lite',
				'',
				WPSSLE_URL . 'assets/images/menu-icon.svg',
				90
			);
		}
		add_submenu_page( 'wpsyncsheets_lite', 'Google Sheets API Settings', 'Google Sheets API Settings', 'manage_options', 'wpsyncsheets_lite', __CLASS__ . '::wpssle_elementor_sheets_plugin_page' );
		add_submenu_page( 'wpsyncsheets_lite', 'WPSyncSheets Lite For Elementor', 'For Elementor', 'manage_options', 'wpsyncsheets-elementor', __CLASS__ . '::wpssle_elementor_sheets_plugin_page', 1 );
		if ( ! isset( $_parent_pages['documentation'] ) ) {
			add_submenu_page( 'wpsyncsheets_lite', 'Documentation', '<div class="wpssle-support">Documentation</div>', 'manage_options', 'documentation', __CLASS__ . '::wpssle_handle_external_redirects', 20 );
		}
		self::remove_duplicate_submenu_page();
	}

	/**
	 * Documentation and Support Page Link.
	 *
	 * Redirect the documentation and support page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wpssle_handle_external_redirects() {
		// phpcs:ignore
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		// phpcs:ignore
		if ( 'documentation' === $_GET['page'] ) {
			// phpcs:ignore
			wp_redirect( WPSSLE_DOC_MENU_URL );
			die;
		}
	}

	/**
	 * Remove duplicate submenu
	 * Submenu page hack: Remove the duplicate WPSyncSheets Plugin link on subpages
	 */
	public static function remove_duplicate_submenu_page() {
		remove_submenu_page( 'wpsyncsheets_lite', 'wpsyncsheets_lite' );
	}

	/**
	 * Plugin Page.
	 */
	public static function wpssle_elementor_sheets_plugin_page() {

		if ( isset( $_POST['submit'] ) ) {
			if ( ! isset( $_POST['wpssle_api_settings'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpssle_api_settings'] ) ), 'save_api_settings' ) ) {
				$wpssle_error = '<strong class="err-msg">' . esc_html__( 'Error: Sorry, your nonce did not verify.', 'wpsse' ) . '</strong>';
			} else {
				if ( isset( $_POST['client_token'] ) ) {
					$wpssle_clienttoken = sanitize_text_field( wp_unslash( $_POST['client_token'] ) );
				} else {
					$wpssle_clienttoken = '';
				}
				if ( isset( $_POST['client_id'] ) && isset( $_POST['client_secret'] ) ) {
					$wpssle_google_settings = array( sanitize_text_field( wp_unslash( $_POST['client_id'] ) ), sanitize_text_field( wp_unslash( $_POST['client_secret'] ) ), $wpssle_clienttoken );
				} else {
					$wpssle_google_settings_value = self::$instance_api->wpssle_option( 'wpsse_google_settings' );
					$wpssle_google_settings       = array( $wpssle_google_settings_value[0], $wpssle_google_settings_value[1], $wpssle_clienttoken );
				}
				self::$instance_api->wpssle_update_option( 'wpsse_google_settings', $wpssle_google_settings );
			}
		}
		if ( isset( $_POST['revoke'] ) ) {
			if ( ! isset( $_POST['wpssle_api_settings'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpssle_api_settings'] ) ), 'save_api_settings' ) ) {
				$wpssle_error = '<strong class="err-msg">' . esc_html__( 'Error: Sorry, your nonce did not verify.', 'wpsse' ) . '</strong>';
			} else {
				$wpssle_google_settings    = self::$instance_api->wpssle_option( 'wpsse_google_settings' );
				$wpssle_google_settings[2] = '';
				self::$instance_api->wpssle_update_option( 'wpsse_google_settings', $wpssle_google_settings );
				self::$instance_api->wpssle_update_option( 'wpsse_google_accessToken', '' );
			}
		}

		$wpssle_google_settings = self::$instance_api->wpssle_option( 'wpsse_google_settings' );

		if ( ! empty( $wpssle_google_settings[2] ) ) {

			if ( ! self::$instance_api->checkcredenatials() ) {
				$wpssle_error = self::$instance_api->getClient( 1 );
				if ( 'Invalid token format' === (string) $wpssle_error ) {
					$wpssle_error = '<div class="error token_error"><p><strong class="err-msg"> ' . esc_html__( 'Error: Invalid Token - Revoke Token with below settings and try again.', 'wpsse' ) . '</strong></p></div>';
				} else {
					$wpssle_error = '<div class="error token_error"><p><strong class="err-msg">Error: ' . $wpssle_error . '</strong></p></div>';
				}
			}
		}

		?>	
		<div class="vertical-tabs">
			<div class="wpssle-logo-section">
				<img src="<?php echo esc_url( WPSSLE_URL . 'assets/images/logo.png' ); ?>">
					<sup>V<?php echo esc_html( WPSSLE_VERSION ); ?></sup>
				<div class="duc-btn1 pro-version">
					<a target="_blank" href="<?php echo esc_url( WPSSLE_PRO_VERSION_URL ); ?>"><?php echo esc_html__( 'Pro Version', 'wpsyncsheets-woocommerce' ); ?></a>
				</div>
				<div class="duc-btn1 ">
					<a target="_blank" href="<?php echo esc_url( WPSSLE_SUPPORT_URL ); ?>"><?php echo esc_html__( 'Support', 'wpsyncsheets-woocommerce' ); ?></a>
				</div>
				<div class="duc-btn">
					<a target="_blank" href="<?php echo esc_url( WPSSLE_DOCUMENTATION_URL ); ?>"><?php echo esc_html__( 'Documentation', 'wpsyncsheets-woocommerce' ); ?></a>
				</div>
			</div>
			<div class="tab">
				<button class="tablinks googleapi-settings" onclick="wpssletab(event, 'googleapi-settings')">
					<span class="tab-icon"></span><?php echo esc_html_e( 'Google API', 'wpssle' ); ?> <br><?php echo esc_html_e( 'Settings', 'wpssle' ); ?></button>
				<button class="tablinks support" onclick="wpssletab(event, 'support')"> <span class="tab-icon"></span><?php echo esc_html_e( 'Pro Features', 'wpssle' ); ?> </button>
			</div>
			<div id="googleapi-settings" class="tabcontent">
				<h3><?php echo esc_html_e( 'Google API Settings', 'wpssle' ); ?></h3>
				<p><?php echo esc_html_e( 'Create new google APIs with Client ID and Client Secret keys to get an access for the google drive and google sheets. Please follow the documentation, login to your Gmail Account and start with', 'wpssle' ); ?> <a href="<?php echo esc_url( WPSSLE_DOC_SHEET_SETTING_URL ); ?>" target="_blank"><?php echo esc_html_e( 'here.', 'wpssle' ); ?></a></p>
				<form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page=wpsyncsheets-elementor' ) ); ?>">
				<?php wp_nonce_field( 'save_api_settings', 'wpssle_api_settings' ); ?>
				<?php
					$google_settings_value = self::$instance_api->wpssle_option( 'wpsse_google_settings' );
				?>
					<div id="universal-message-container woocommerce">
					<br>
						<div class="options">
							<?php
							if ( ! empty( $wpssle_error ) ) {
								$allowed_html = wp_kses_allowed_html( 'post' );
								echo wp_kses( $wpssle_error, $allowed_html );
							}
							if ( isset( $google_settings_value[0] ) ) {
								$client_id = $google_settings_value[0];
							} else {
								$client_id = '';
							}
							if ( isset( $google_settings_value[1] ) ) {
								$client_secret = $google_settings_value[1];
							} else {
								$client_secret = '';
							}
							?>
							<table class="form-table">
							<tr>
								<th> <?php echo esc_html_e( 'Client Id', 'wpssle' ); ?> </th>
								<td class="forminp forminp-text">
									<input type="text" name="client_id" value="<?php echo esc_attr( $client_id ); ?>" size="80" class = "googlesettinginput" placeholder="<?php echo esc_html__( 'Enter Client Id', 'wpsse' ); ?>"
									<?php
									if ( ! empty( $google_settings_value[0] ) ) {
										echo 'readonly';
									}
									?>
									/>
								</td>
							</tr>
							<tr>
								<th><?php echo esc_html_e( 'Client Secret', 'wpssle' ); ?> </th>
								<td class="forminp forminp-text">
									<input type="text" name="client_secret" value="<?php echo esc_attr( $client_secret ); ?>" size="80" class = "googlesettinginput" placeholder="<?php echo esc_html__( 'Enter Client Secret', 'wpsse' ); ?>"
									<?php
									if ( ! empty( $google_settings_value[1] ) ) {
										echo 'readonly';
									}
									?>
									/>
								</td>
							</tr>
							<?php
							if ( ! empty( $google_settings_value[0] ) && ! empty( $google_settings_value[1] ) ) {
									$token_value = $google_settings_value[2];
								?>
							<tr>
								<th><?php echo esc_html_e( 'Client Token', 'wpssle' ); ?></th>
								<?php
								if ( empty( $token_value ) && ! isset( $_GET['code'] ) ) {
									$auth_url = self::$instance_api->getClient();
									?>
								<td id="authbtn">
									<a href="<?php echo esc_url( $auth_url ); ?>" target="_blank" id="authlink"><div class="wpssle-button wpssle-button-secondary"><?php echo esc_html__( 'Click here to generate an Authentication Token', 'wpsse' ); ?></div></a>
								</td>
									<?php
								}
								$woosheets_code = '';
                           		// phpcs:ignore
								if ( isset( $_GET['code'] ) && ! empty( sanitize_text_field( wp_unslash( $_GET['code'] ) ) ) ) {
									// phpcs:ignore
									$woosheets_code = sanitize_text_field( wp_unslash( $_GET['code'] ) );
								}
								?>
								<td id="authtext" 
								<?php
								if ( ! empty( $token_value ) || $woosheets_code ) {
									?>
										class="wpssle-authtext forminp forminp-text" 
									<?php
								} else {
									?>
										class="forminp forminp-text"
										<?php
								}
								?>
									>
									<input type="text" name="client_token" value="<?php echo $token_value ? esc_attr( $token_value ) : esc_attr( $woosheets_code ); ?>" size="80" placeholder="<?php echo esc_html__( 'Please enter authentication code', 'wpsse' ); ?>" id="client_token" class="googlesettinginput" 
										<?php
										if ( ! empty( $google_settings_value[2] ) ) {
											echo 'readonly';
										}
										?>
									/>
								</td>
							</tr>
							<?php }if ( ! empty( $token_value ) ) { ?>
							<tr>
								<td></td>
								<td><input type="submit" name="revoke" id="revoke" value = "<?php echo esc_attr__( 'Revoke Token', 'wpsse' ); ?>" class="wpssle-button wpssle-button-secondary"/></td>
							</tr>
							<?php } ?> 
							</table>
						</div>
						<?php
						if ( isset( $_SERVER['SERVER_NAME'] ) ) {
							$site_url = sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) );
							$site_url = str_replace( 'www.', '', $site_url );
						}
						?>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="wpssle-button wpssle-button-primary" value="<?php echo esc_attr__( 'Save', 'wpsse' ); ?>">
							<?php
							if ( ! empty( $token_value ) || ! empty( $google_settings_value[0] ) || ! empty( $google_settings_value[1] ) ) {
								?>
								<input type="submit" name="reset_settings" id="reset_settings" value = "<?php echo esc_attr__( 'Reset Settings', 'wpsse' ); ?>" class="wpssle-button wpssle-button-primary reset_settings"/>
							<?php } ?>
						</p>
						<table class="copy-url-table" cellpadding="0" cellspacing="0" width="100%" border="0px">
							<tr>
								<td><?php echo esc_html__( 'Authorized Domain : ', 'wpssle' ); ?></td>
								<td><span id="authorized_domain"><?php echo esc_html( $site_url ); ?></span><span class="copy-icon wpssle-button wpssle-button-primary" id="a_domain" onclick="wpssle_copy('authorized_domain','a_domain');"></span></td>
							</tr>
							<tr>
								<td><?php echo esc_html__( 'Authorised redirect URIs : ', 'wpssle' ); ?></td>
								<td><span id="authorized_uri"><?php echo esc_html( admin_url( 'admin.php?page=wpsyncsheets-elementor' ) ); ?></span><span class="copy-icon wpssle-button wpssle-button-primary" onclick="wpssle_copy('authorized_uri','a_uri');" id="a_uri"></span></td>
							</tr>
						</table>
					</div>
				</form>
			</div>             
			<div id="support" class="tabcontent">
				<a href="<?php echo esc_url( WPSSLE_PRO_VERSION_URL ); ?>" target="_blank"><img src="<?php echo esc_url( WPSSLE_URL . 'assets/images/pro-features.png' ); ?>"></a>
			</div> 
		</div>
		<?php
	}

	/**
	 * Prepare Google Spreadsheet list.
	 *
	 * @return array $sheetarray Spreadsheet List.
	 */
	public static function wpssle_list_googlespreedsheet() {
		/* Build choices array. */
		$sheetarray = array(
			'' => esc_html__( 'Select Google Spreeadsheet', 'wpsse' ),
		);

		$sheetarray = self::$instance_api->get_spreadsheet_listing( $sheetarray );
		return $sheetarray;
	}

	/**
	 * Check posted data before processing.
	 *
	 * @param array $wpssle_data Posted Elementor Data.
	 */
	public static function wpssle_ajax_register_action( $wpssle_data ) {
		//phpcs:ignore
		if ( ! isset( $_REQUEST['actions'] ) ) {
			return;
		}
		//phpcs:ignore
		$wpssle_data = json_decode( sanitize_text_field( wp_unslash( $_REQUEST['actions'] ) ), true );

		if ( isset( $wpssle_data['save_builder'] ) ) {
			$wpssle_google_settings = self::$instance_api->wpssle_option( 'wpsse_google_settings' );

			if ( ! empty( $wpssle_google_settings ) ) {

				$wpssle_data = Plugin::elementor()->db->iterate_data(
					$wpssle_data,
					function ( $wpssle_element ) use ( &$do_update ) {

						if ( isset( $wpssle_element['widgetType'] ) && 'form' === (string) $wpssle_element['widgetType'] ) {

							global $wpssle_header_list, $wpssle_exclude_headertype, $wpssle_spreadsheetid;
							$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );
							if ( isset( $wpssle_element['settings'] ) && isset( $wpssle_element['settings']['submit_actions'] ) && in_array( 'WPSyncSheets', $wpssle_element['settings']['submit_actions'], true ) ) {
								$wpssle_settings      = $wpssle_element['settings'];
								$wpssle_header_list   = array();
								$wpssle_spreadsheetid = $wpssle_settings['spreadsheetid'];
								$wpssle_sheetname     = $wpssle_settings['sheet_name'];
								$wpssle_sheetheaders  = $wpssle_element['settings']['sheet_headers'];

								if ( empty( $wpssle_spreadsheetid ) ) {
									$es_error = new WP_Error( esc_html__( '- Please select spreadsheet : WPSyncSheets', 'wpsse' ), '', '' );
									wp_send_json_error( $es_error );
								}

								if ( 'new' === (string) $wpssle_spreadsheetid && ! isset( $wpssle_settings['new_spreadsheet_name'] ) ) {

									$es_error = new WP_Error( esc_html__( '- Please enter spreadsheet name : WPSyncSheets', 'wpsse' ), '', '' );
									wp_send_json_error( $es_error );
								}

								if ( empty( $wpssle_sheetname ) ) {
									$es_error = new WP_Error( esc_html__( '- Please enter sheet name : WPSyncSheets', 'wpsse' ), '', '' );
									wp_send_json_error( $es_error );
								}
								if ( empty( $wpssle_sheetheaders ) && isset( $wpssle_sheetheaders ) && ! is_array( $wpssle_sheetheaders ) ) {
										$es_error = new WP_Error( esc_html__( '- Please select sheet headers : WPSyncSheets', 'wpsse' ), '', '' );
										wp_send_json_error( $es_error );
								}
								foreach ( $wpssle_element['settings']['form_fields'] as $wpssle_form_fields1 ) {
									if ( ( ! isset( $wpssle_form_fields1['field_type'] ) || ( isset( $wpssle_form_fields1['field_type'] ) && ! in_array( $wpssle_form_fields1['field_type'], $wpssle_exclude_headertype, true ) ) ) && in_array( $wpssle_form_fields1['custom_id'], $wpssle_sheetheaders, true ) ) {
										$wpssle_header_list[] = $wpssle_form_fields1['field_label'] ? $wpssle_form_fields1['field_label'] : ucfirst( $wpssle_form_fields1['custom_id'] );
									}
								}

								if ( 'new' !== $wpssle_spreadsheetid ) {
									$response                   = self::$instance_api->get_sheet_listing( $wpssle_spreadsheetid );
									$wpssle_existingsheetsnames = self::$instance_api->get_sheetid_list( $response );
									if ( in_array( $wpssle_sheetname, $wpssle_existingsheetsnames, true ) ) {
										$wpssle_range    = trim( $wpssle_sheetname ) . '!A1:ZZ1';
										$wpssle_response = self::$instance_api->get_row_list( $wpssle_spreadsheetid, $wpssle_range );
										$wpssle_data     = $wpssle_response->getValues();
										$existingheaders = $wpssle_data[0];
										$is_mismatch     = 0;

										$counter         = 0;
										$existingheaders = array_filter( $existingheaders );
										if ( count( $existingheaders ) > count( $wpssle_header_list ) ) {
											$counter = count( $existingheaders );
										} else {
											$counter = count( $wpssle_header_list );
										}
										for ( $i = 0; $i < $counter; $i++ ) {
											if ( isset( $existingheaders[ $i ] ) && isset( $wpssle_header_list[ $i ] ) ) {
												if ( (string) strtolower( $wpssle_header_list[ $i ] ) !== (string) strtolower( $existingheaders[ $i ] ) ) {
													$is_mismatch = 1;
												}
											} else {
												$is_mismatch = 1;
											}
										}

										if ( $is_mismatch ) {
											$es_error = new WP_Error( esc_html__( '- Sheet Headers are not match with spreadsheet, please choose create new spreadsheet option or change sheet name. : WPSyncSheets', 'wpsse' ), '', '' );
											wp_send_json_error( $es_error );
										}
									}
								}
							}
						}
					}
				);
			}
		}
	}
	/**
	 * Reset Google API Settings
	 */
	public static function wpssle_reset_settings() {
		try {
			$wpssle_google_settings = self::$instance_api->wpssle_option( 'wpsse_google_settings' );
			$settings               = array();
			foreach ( $wpssle_google_settings as $key => $value ) {
				$settings[ $key ] = '';
			}
			self::$instance_api->wpssle_update_option( 'wpsse_google_settings', $settings );
			self::$instance_api->wpssle_update_option( 'wpsse_google_accessToken', '' );
		} catch ( Exception $e ) {
			return $e->getMessage(); }
		echo 'successful';
		wp_die();
	}
	/**
	 * Change the row format of spreadsheet.
	 */
	public static function get_row_format() {
		$params = array( 'valueInputOption' => 'RAW' );
		return $params;
	}
}
WPSSLE_Plugin_Setting::wpssle_initilization();
