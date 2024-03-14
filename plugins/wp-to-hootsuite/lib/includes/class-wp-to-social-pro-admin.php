<?php
/**
 * Administration class.
 *
 * @package WP_To_Social_Pro
 * @author WP Zinc
 */

/**
 * Plugin settings screen and JS/CSS.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 * @version 3.0.0
 */
class WP_To_Social_Pro_Admin {

	/**
	 * Holds the base class object.
	 *
	 * @since   3.2.0
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Holds the success and error messages
	 *
	 * @since   3.2.6
	 *
	 * @var     array
	 */
	public $notices = array(
		'success' => array(),
		'error'   => array(),
	);

	/**
	 * Constructor
	 *
	 * @since   3.0.0
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

		// Actions.
		add_action( 'init', array( $this, 'oauth' ) );
		add_action( 'init', array( $this, 'check_plugin_setup' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_css' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'plugin_action_links_' . $this->base->plugin->name . '/' . $this->base->plugin->name . '.php', array( $this, 'plugin_action_links_settings_page' ) );

	}

	/**
	 * Stores the access token if supplied, showing a success message
	 * Displays any errors from the oAuth process
	 *
	 * @since   3.3.3
	 */
	public function oauth() {

		// Setup notices class.
		$this->base->get_class( 'notices' )->set_key_prefix( $this->base->plugin->filter_name . '_' . wp_get_current_user()->ID );

		/**
		 * Perform any pre-oAuth actions now, such as starting the oAuth process
		 *
		 * @since   4.2.0
		 */
		do_action( $this->base->plugin->filter_name . '_save_settings_auth' );

		// If we've returned from the oAuth process and an error occured, add it to the notices.
		if ( isset( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-error' ] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification
			$oauth_error = sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-error' ] );  // phpcs:ignore WordPress.Security.NonceVerification
			switch ( $oauth_error ) {
				/**
				 * Access Denied
				 * - User denied our app access
				 */
				case 'access_denied':
					$this->base->get_class( 'notices' )->add_error_notice(
						sprintf(
							/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
							__( 'You did not grant our Plugin access to your %1$s account. We are unable to post to %2$s until you do this. Please click on the Authorize Plugin button.', 'wp-to-hootsuite' ),
							$this->base->plugin->account,
							$this->base->plugin->account
						)
					);
					break;

				/**
				 * Invalid Grant
				 * - A parameter sent by the oAuth gateway is wrong
				 */
				case 'invalid_grant':
					$this->base->get_class( 'notices' )->add_error_notice(
						sprintf(
							'%1$s <a href="%2$s" target="_blank">%3$s</a>',
							sprintf(
								/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
								__( 'We were unable to complete authentication with %s.  Please try again, or', 'wp-to-hootsuite' ),
								$this->base->plugin->account
							),
							esc_html( $this->base->plugin->support_url ),
							__( 'contact us for support', 'wp-to-hootsuite' )
						)
					);
					break;

				/**
				 * Expired Token
				 * - The oAuth gateway did not exchange the code for an access token within 30 seconds
				 */
				case 'expired_token':
					$this->base->get_class( 'notices' )->add_error_notice(
						sprintf(
							'%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s',
							__( 'The oAuth process has expired.  Please try again, or', 'wp-to-hootsuite' ),
							esc_html( $this->base->plugin->support_url ),
							__( 'contact us for support', 'wp-to-hootsuite' ),
							__( 'if this issue persists.', 'wp-to-hootsuite' )
						)
					);
					break;

				/**
				 * Other Error
				 */
				default:
					$this->base->get_class( 'notices' )->add_error_notice(
						esc_html( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-error' ] )  // phpcs:ignore WordPress.Security.NonceVerification
					);
					break;
			}
		}

		// If an Access Token is included in the request, store it and show a success message.
		if ( isset( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-access-token' ] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification
			// Define expiry.
			$expiry = sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-expires' ] );  // phpcs:ignore WordPress.Security.NonceVerification
			if ( $expiry > 0 ) {
				$expiry = strtotime( '+' . sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-expires' ] ) . ' seconds' );  // phpcs:ignore WordPress.Security.NonceVerification
			}

			// Setup API.
			$this->base->get_class( 'api' )->set_tokens(
				sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-access-token' ] ), // phpcs:ignore WordPress.Security.NonceVerification
				sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-refresh-token' ] ), // phpcs:ignore WordPress.Security.NonceVerification
				$expiry
			);

			// Fetch Profiles.
			$profiles = $this->base->get_class( 'api' )->profiles( true, $this->base->get_class( 'common' )->get_transient_expiration_time() );

			// If something went wrong, show an error.
			if ( is_wp_error( $profiles ) ) {
				$this->base->get_class( 'notices' )->add_error_notice( $profiles->get_error_message() );
				return;
			}

			// Test worked! Save Tokens and Expiry.
			$this->base->get_class( 'settings' )->update_tokens(
				sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-access-token' ] ), // phpcs:ignore WordPress.Security.NonceVerification
				sanitize_text_field( $_REQUEST[ $this->base->plugin->settingsName . '-oauth-refresh-token' ] ), // phpcs:ignore WordPress.Security.NonceVerification
				$expiry
			);

			// Store success message.
			$this->base->get_class( 'notices' )->enable_store();
			$this->base->get_class( 'notices' )->add_success_notice(
				sprintf(
					/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
					__( 'Thanks! You\'ve connected our Plugin to %1$s. Now select profiles below to enable, and define your statuses to start sending Posts to %2$s', 'wp-to-hootsuite' ),
					$this->base->plugin->account,
					$this->base->plugin->account
				)
			);

			// Redirect to Post tab.
			wp_safe_redirect( 'admin.php?page=' . $this->base->plugin->name . '-settings&tab=post&type=post' );
			die();
		}

	}

	/**
	 * Checks that the oAuth authorization flow has been completed, and that
	 * at least one Post Type with one Social Media account has been enabled.
	 *
	 * Displays a dismissible WordPress notification if this has not been done.
	 *
	 * @since   1.0.0
	 */
	public function check_plugin_setup() {

		// Show an error if cURL hasn't been installed.
		if ( ! function_exists( 'curl_init' ) ) {
			$this->base->get_class( 'notices' )->add_error_notice(
				sprintf(
					/* translators: Plugin Name */
					__( '%s requires the PHP cURL extension to be installed and enabled by your web host.', 'wp-to-hootsuite' ),
					$this->base->plugin->displayName
				)
			);
		}

		// Check the API is connected.
		if ( ! $this->base->get_class( 'validation' )->api_connected() ) {
			// Don't display the notice if this request is for the settings auth screen.
			$screen = $this->base->get_class( 'screen' )->get_current_screen();
			if ( $screen['screen'] === 'settings' && $screen['section'] === 'auth' ) {
				return;
			}

			// Display the notice.
			$this->base->get_class( 'notices' )->add_error_notice(
				sprintf(
					'%1$s <a href="%2$s">%3$s</a>',
					sprintf(
						/* translators: %1$s: Plugin Name, %2$s, %3$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %4$s: URL to Authorize Plugin Screen, %5$s: URL to Register Account with Service */
						esc_html__( '%1$s needs to be authorized with %2$s before you can start sending Posts to %3$s.', 'wp-to-hootsuite' ),
						$this->base->plugin->displayName,
						$this->base->plugin->account,
						$this->base->plugin->account
					),
					admin_url( 'admin.php?page=' . $this->base->plugin->name . '-settings' ),
					esc_html__( 'Click here to Authorize.', 'wp-to-hootsuite' )
				)
			);
		}

	}

	/**
	 * Checks the transient to see if any admin notices need to be output now.
	 *
	 * @since   3.9.6
	 */
	public function admin_notices() {

		// Output notices.
		$this->base->get_class( 'notices' )->set_key_prefix( $this->base->plugin->filter_name . '_' . wp_get_current_user()->ID );
		$this->base->get_class( 'notices' )->output_notices();

	}

	/**
	 * Register and enqueue any JS and CSS for the WordPress Administration
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts_css() {

		global $id, $post;

		// Get current screen.
		$screen = $this->base->get_class( 'screen' )->get_current_screen();

		// CSS - always load.
		// Menu Icon is inline, because when Gravity Forms no conflict mode is ON, it kills all enqueued styles,
		// which results in a large menu SVG icon displaying.
		// However, don't load this on customize.php, as it wrongly outputs above the opening <html> tag.
		if ( $screen['screen'] !== 'customize' ) {
			?>
			<style type="text/css">
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->settingsName ); ?>-settings a div.wp-menu-image, 
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->settingsName ); ?> a div.wp-menu-image, 
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->name ); ?>-settings a div.wp-menu-image,
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->name ); ?> a div.wp-menu-image {
					background: url(<?php echo esc_attr( $this->base->plugin->url ); ?>/lib/assets/images/icons/<?php echo esc_attr( strtolower( $this->base->plugin->account ) ); ?>-light.svg) center no-repeat;
					background-size: 16px 16px;
				}
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->settingsName ); ?>-settings a div.wp-menu-image img, 
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->settingsName ); ?> a div.wp-menu-image img, 
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->name ); ?>-settings a div.wp-menu-image img,
				li.toplevel_page_<?php echo esc_attr( $this->base->plugin->name ); ?> a div.wp-menu-image img {
					display: none;
				}
			</style>
			<?php
		}

		wp_enqueue_style( $this->base->plugin->name, $this->base->plugin->url . 'lib/assets/css/admin.css', array(), $this->base->plugin->version );

		// Don't load anything else if we're not on a Plugin or Post screen.
		if ( ! $screen['screen'] ) {
			return;
		}

		// Determine whether to load minified versions of JS.
		$minified = $this->base->dashboard->should_load_minified_js();

		// Define JS and localization.
		wp_register_script( $this->base->plugin->name . '-bulk-publish', $this->base->plugin->url . 'lib/assets/js/' . ( $minified ? 'min/' : '' ) . 'bulk-publish' . ( $minified ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
		wp_register_script( $this->base->plugin->name . '-log', $this->base->plugin->url . 'lib/assets/js/' . ( $minified ? 'min/' : '' ) . 'log' . ( $minified ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
		wp_register_script( $this->base->plugin->name . '-quick-edit', $this->base->plugin->url . 'lib/assets/js/' . ( $minified ? 'min/' : '' ) . 'quick-edit' . ( $minified ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
		wp_register_script( $this->base->plugin->name . '-settings', $this->base->plugin->url . 'lib/assets/js/' . ( $minified ? 'min/' : '' ) . 'settings' . ( $minified ? '-min' : '' ) . '.js', array( 'jquery', 'wp-color-picker' ), $this->base->plugin->version, true );
		wp_register_script( $this->base->plugin->name . '-statuses', $this->base->plugin->url . 'lib/assets/js/' . ( $minified ? 'min/' : '' ) . 'statuses' . ( $minified ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );

		// Define localization for statuses.
		$localization = array(
			'ajax'                     => admin_url( 'admin-ajax.php' ),

			'clear_log_nonce'          => wp_create_nonce( $this->base->plugin->name . '-clear-log' ),
			'clear_log_completed'      => sprintf(
				/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
				__( 'No log entries exist, or no status updates have been sent to %s.', 'wp-to-hootsuite' ),
				$this->base->plugin->account
			),

			'get_log_nonce'            => wp_create_nonce( $this->base->plugin->name . '-get-log' ),

			'delete_condition_message' => __( 'Are you sure you want to delete this condition?', 'wp-to-hootsuite' ),
			'delete_status_message'    => __( 'Are you sure you want to delete this status?', 'wp-to-hootsuite' ),

			'get_status_row_action'    => $this->base->plugin->filter_name . '_get_status_row',
			'get_status_row_nonce'     => wp_create_nonce( $this->base->plugin->name . '-get-status-row' ),

			'post_id'                  => ( isset( $post->ID ) ? $post->ID : (int) $id ),

			// Plugin specific Status Form Container and Status Form, so statuses.js knows where to look for the form
			// relative to this Plugin.
			'plugin_name'              => $this->base->plugin->name,
			'status_form_container'    => '#' . $this->base->plugin->name . '-status-form-container',
			'status_form'              => '#' . $this->base->plugin->name . '-status-form',
		);

		// If here, we're on a Plugin or Post screen.
		// Conditionally load scripts and styles depending on which section of the Plugin we're loading.
		switch ( $screen['screen'] ) {
			/**
			 * Post
			 */
			case 'post':
				switch ( $screen['section'] ) {
					/**
					 * WP_List_Table
					 */
					case 'wp_list_table':
						break;

					/**
					 * Add/Edit
					 */
					case 'edit':
						// JS.
						wp_enqueue_script( $this->base->plugin->name . '-log' );
						wp_localize_script( $this->base->plugin->name . '-log', 'wp_to_social_pro', $localization );
						break;
				}
				break;

			/**
			 * Settings
			 */
			case 'settings':
				// JS.
				wp_enqueue_script( 'wpzinc-admin-conditional' );
				wp_enqueue_media();
				wp_enqueue_script( 'wpzinc-admin-tabs' );
				wp_enqueue_script( 'wpzinc-admin' );

				switch ( $screen['section'] ) {
					/**
					 * General
					 */
					case 'auth':
						break;

					/**
					 * Post Type
					 */
					default:
						// JS.
						wp_enqueue_script( 'wpzinc-admin-autocomplete' );
						wp_enqueue_script( 'wpzinc-admin-autosize' );
						wp_enqueue_script( 'wpzinc-admin-modal' );
						wp_enqueue_script( 'jquery-ui-sortable' );

						// Plugin JS.
						wp_enqueue_script( $this->base->plugin->name . '-statuses' );

						// Add Post Type, Action and Nonce to allow AJAX saving.
						$localization['post_type']              = $this->get_post_type_tab();
						$localization['prompt_unsaved_changes'] = true;
						$localization['save_statuses_action']   = $this->base->plugin->filter_name . '_save_statuses';
						$localization['save_statuses_modal']    = array(
							'title'         => __( 'Saving', 'wp-to-hootsuite' ),
							'title_success' => __( 'Saved!', 'wp-to-hootsuite' ),
						);
						$localization['save_statuses_nonce']    = wp_create_nonce( $this->base->plugin->name . '-save-statuses' );

						// Localize Statuses.
						wp_localize_script( $this->base->plugin->name . '-statuses', 'wp_to_social_pro', $localization );

						// Localize Autocomplete.
						wp_localize_script( 'wpzinc-admin-autocomplete', 'wpzinc_autocomplete', $this->get_autocomplete_configuration( $localization['post_type'] ) );
						break;
				}
				break;

			/**
			 * Log
			 */
			case 'log':
				// Plugin JS.
				wp_enqueue_script( $this->base->plugin->name . '-log' );

				// Localize.
				wp_localize_script( $this->base->plugin->name . '-log', 'wp_to_social_pro', $localization );
				break;
		}

	}

	/**
	 * Returns configuration for tribute.js autocomplete instances for Tags.
	 *
	 * @since   4.5.7
	 *
	 * @param   string $post_type  Post Type.
	 * @return  array               Javascript  Autocomplete Configuration
	 */
	private function get_autocomplete_configuration( $post_type ) {

		$autocomplete_configuration = array(
			// Tags.
			array(
				'fields'   => array(
					'textarea.message',
					'textarea.text-to-image',

					// Google Business.
					'input#googlebusiness_title',
					'input#googlebusiness_code',
					'input#googlebusiness_terms',
				),
				'triggers' => array(
					// Tags.
					array(
						'trigger' => '{',
						'values'  => $this->base->get_class( 'common' )->get_tags_flat( $post_type ),
					),
				),
			),
		);

		/**
		 * Defines configuration for tribute.js autocomplete instances for Tags, Facebook Pages and Twitter Username mentions.
		 *
		 * @since   4.5.7
		 *
		 * @param   array   $autocomplete_configuration     Javascript  Autocomplete Configuration.
		 * @param   string  $post_type                      Post Type.
		 */
		$autocomplete_configuration = apply_filters( $this->base->plugin->filter_name . '_admin_get_autocomplete_configuration', $autocomplete_configuration );

		// Return.
		return $autocomplete_configuration;

	}

	/**
	 * Add the Plugin to the WordPress Administration Menu
	 *
	 * @since   1.0.0
	 */
	public function admin_menu() {

		// Menus.
		add_menu_page( $this->base->plugin->displayName, $this->base->plugin->displayName, 'manage_options', $this->base->plugin->name . '-settings', array( $this, 'settings_screen' ), $this->base->plugin->url . 'lib/assets/images/icons/' . strtolower( $this->base->plugin->account ) . '-light.svg' );

		// Register Submenu Pages.
		$settings_page = add_submenu_page( $this->base->plugin->name . '-settings', __( 'Settings', 'wp-to-hootsuite' ), __( 'Settings', 'wp-to-hootsuite' ), 'manage_options', $this->base->plugin->name . '-settings', array( $this, 'settings_screen' ) );

		// Logs.
		if ( $this->base->get_class( 'log' )->is_enabled() ) {
			$log_page = add_submenu_page( $this->base->plugin->name . '-settings', __( 'Logs', 'wp-to-hootsuite' ), __( 'Logs', 'wp-to-hootsuite' ), 'manage_options', $this->base->plugin->name . '-log', array( $this, 'log_screen' ) );
			add_action( "load-$log_page", array( $this->base->get_class( 'log' ), 'add_screen_options' ) );
		}

		$upgrade_page = add_submenu_page( $this->base->plugin->name . '-settings', __( 'Upgrade', 'wp-to-hootsuite' ), __( 'Upgrade', 'wp-to-hootsuite' ), 'manage_options', $this->base->plugin->name . '-upgrade', array( $this, 'upgrade_screen' ) );

	}

	/**
	 * Define links to display below the Plugin Name on the WP_List_Table at in the Plugins screen.
	 *
	 * @since   5.0.2
	 *
	 * @param   array $links      Links.
	 * @return  array               Links
	 */
	public function plugin_action_links_settings_page( $links ) {

		// Add link to Plugin settings screen.
		$links['settings'] = sprintf(
			'<a href="%s">%s</a>',
			add_query_arg(
				array(
					'page' => $this->base->plugin->name . '-settings',
				),
				admin_url( 'admin.php' )
			),
			__( 'Settings', 'wp-to-hootsuite' )
		);

		// Return.
		return $links;

	}

	/**
	 * Upgrade Screen
	 *
	 * @since 3.2.5
	 */
	public function upgrade_screen() {
		// We never reach here, as we redirect earlier in the process.
	}

	/**
	 * Outputs the Settings Screen
	 *
	 * @since   3.0.0
	 */
	public function settings_screen() {

		// Setup notices class.
		$this->base->get_class( 'notices' )->set_key_prefix( $this->base->plugin->filter_name . '_' . wp_get_current_user()->ID );

		// Maybe disconnect.
		if ( isset( $_GET[ $this->base->plugin->name . '-disconnect' ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$this->disconnect();
			$this->base->get_class( 'notices' )->add_success_notice(
				sprintf(
					/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
					__( '%s account disconnected successfully.', 'wp-to-hootsuite' ),
					$this->base->plugin->account
				)
			);
		}

		// Maybe save settings.
		$result = $this->save_settings();
		if ( is_wp_error( $result ) ) {
			// Error notice.
			$this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
		} elseif ( $result === true ) {
			// Success notice.
			$this->base->get_class( 'notices' )->add_success_notice( __( 'Settings saved successfully.', 'wp-to-hootsuite' ) );
		}

		// If the Plugin isn't connected to the API, show the screen to do this now.
		if ( ! $this->base->get_class( 'validation' )->api_connected() ) {
			$this->auth_screen();
			return;
		}

		// Authentication.
		$access_token  = $this->base->get_class( 'settings' )->get_access_token();
		$refresh_token = $this->base->get_class( 'settings' )->get_refresh_token();
		$expires       = $this->base->get_class( 'settings' )->get_token_expires();
		if ( ! empty( $access_token ) ) {
			$this->base->get_class( 'api' )->set_tokens( $access_token, $refresh_token, $expires );
		}

		// Profiles.
		$profiles = $this->base->get_class( 'api' )->profiles( true, $this->base->get_class( 'common' )->get_transient_expiration_time() );
		if ( is_wp_error( $profiles ) ) {
			// If the error is a 401, the user revoked access to the plugin.
			// Disconnect the Plugin, and explain why this happened.
			if ( $profiles->get_error_code() === 401 ) {
				// Disconnect the Plugin.
				$this->disconnect();

				// Error notice.
				$this->base->get_class( 'notices' )->add_error_notice(
					sprintf(
						/* translators: %1$s: Plugin Name, %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
						__( 'Hmm, it looks like you revoked access to %1$s through your %2$s account, or your account no longer exists. This means we can no longer post updates to your social networks.  To re-authorize, click the Authorize Plugin button.', 'wp-to-hootsuite' ),
						$this->base->plugin->displayName,
						$this->base->plugin->account
					)
				);
			} else {
				// Some other error.
				$this->base->get_class( 'notices' )->add_error_notice( $profiles->get_error_message() );
			}
		}

		// Get Settings Tab and Post Type we're managing settings for.
		$tab                 = $this->get_tab( $profiles );
		$post_type           = $this->get_post_type_tab();
		$disable_save_button = false;

		// Post Types.
		$post_types = $this->base->get_class( 'common' )->get_post_types();

		// Depending on the screen we're on, load specific options.
		switch ( $tab ) {
			/**
			 * Settings
			 */
			case 'auth':
				// Log Settings.
				$log_levels = $this->base->get_class( 'log' )->get_level_options();

				// Documentation URL.
				$documentation_url = $this->base->plugin->documentation_url . '/authentication-settings';
				break;

			/**
			 * No Profiles
			 */
			case 'profiles-missing':
				// Disable Save button, as there are no settings displayed to save.
				$disable_save_button = true;

				// Documentation URL.
				$documentation_url = $this->base->plugin->documentation_url . '/status-settings';
				break;

			/**
			 * Profiles Error
			 */
			case 'profiles-error':
				// Disable Save button, as there are no settings displayed to save.
				$disable_save_button = true;

				// Documentation URL.
				$documentation_url = $this->base->plugin->documentation_url . '/status-settings';
				break;

			/**
			 * Post Type
			 */
			default:
				// Get original statuses that will be stored in a hidden field so they are preserved if the screen is saved
				// with no changes that trigger an update to the hidden field.
				$original_statuses = $this->base->get_class( 'settings' )->get_settings( $post_type );

				// Get some other information.
				$post_type_object  = get_post_type_object( $post_type );
				$actions_plural    = $this->base->get_class( 'common' )->get_post_actions_past_tense();
				$post_actions      = $this->base->get_class( 'common' )->get_post_actions();
				$documentation_url = $this->base->plugin->documentation_url . '/status-settings';
				$is_post_screen    = false; // Disables the 'specific' schedule option, which can only be used on individual Per-Post Settings.

				// Check if this Post Type is enabled.
				if ( ! $this->base->get_class( 'settings' )->is_post_type_enabled( $post_type ) ) {
					$this->base->get_class( 'notices' )->add_warning_notice(
						sprintf(
							'%1$s <a href="%2$s" target="_blank">%3$s</a>',
							sprintf(
								/* translators: %1$s: Post Type, %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %3$s: Documentation URL */
								__( 'To send %1$s to %2$s, at least one action on the Defaults tab must be enabled with a status defined, and at least one social media profile must be enabled below by clicking the applicable profile name and ticking the "Account Enabled" box.', 'wp-to-hootsuite' ),
								$post_type_object->label,
								$this->base->plugin->account
							),
							$documentation_url,
							__( 'See Documentation', 'wp-to-hootsuite' )
						)
					);
				}
				break;
		}

		// Load View.
		include_once $this->base->plugin->folder . 'lib/views/settings.php';

		// Add footer action to output overlay modal markup.
		add_action( 'admin_footer', array( $this, 'output_modal' ) );

	}

	/**
	 * Outputs the auth screen, allowing the user to begin the process of connecting the Plugin
	 * to the API, without showing other settings.
	 *
	 * @since   4.6.4
	 */
	public function auth_screen() {

		// Load View.
		include_once $this->base->plugin->folder . 'lib/views/settings-auth-required.php';

	}

	/**
	 * Outputs the hidden Javascript Modal and Overlay in the Footer
	 *
	 * @since   1.0.0
	 */
	public function output_modal() {

		// Load view.
		require_once $this->base->plugin->folder . '_modules/dashboard/views/modal.php';

	}

	/**
	 * Outputs the Log Screen
	 *
	 * @since   3.9.6
	 */
	public function log_screen() {

		// Init table.
		$table = new WP_To_Social_Pro_Log_Table( $this->base );
		$table->prepare_items();

		// Load View.
		include_once $this->base->plugin->folder . 'lib/views/log.php';

	}

	/**
	 * Helper method to get the setting value from the plugin settings
	 *
	 * @since   3.0.0
	 *
	 * @param   string $type            Setting Type.
	 * @param   string $key             Setting Key.
	 * @param   mixed  $default_value   Default Value if Setting does not exist.
	 * @return  mixed                   Value
	 */
	public function get_setting( $type = '', $key = '', $default_value = '' ) {

		// Post Type Setting or Bulk Setting.
		if ( post_type_exists( $type ) ) {
			return $this->base->get_class( 'settings' )->get_setting( $type, $key, $default_value );
		}

		// Access token.
		if ( $key === 'access_token' ) {
			return $this->base->get_class( 'settings' )->get_access_token();
		}

		// Refresh token.
		if ( $key === 'refresh_token' ) {
			return $this->base->get_class( 'settings' )->get_refresh_token();
		}

		// Depending on the type, return settings / options.
		switch ( $type ) {
			case 'text_to_image':
			case 'log':
			case 'hide_meta_box_by_roles':
			case 'roles':
			case 'custom_tags':
			case 'repost':
				return $this->base->get_class( 'settings' )->get_setting( $type, $key, $default_value );

			default:
				return $this->base->get_class( 'settings' )->get_option( $key, $default_value );
		}

	}

	/**
	 * Disconnect by removing the access token
	 *
	 * @since   3.0.0
	 *
	 * @return  string Result
	 */
	public function disconnect() {

		return $this->base->get_class( 'settings' )->delete_tokens();

	}

	/**
	 * Helper method to save settings
	 *
	 * @since   3.0.0
	 *
	 * @return  mixed   WP_Error | bool
	 */
	public function save_settings() {

		// Check if a POST request was made.
		if ( ! isset( $_POST['submit'] ) ) {
			return false;
		}

		// Missing nonce.
		if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) {
			return new WP_Error(
				'wp_to_social_pro_admin_save_settings_error',
				__( 'Nonce field is missing. Settings NOT saved.', 'wp-to-hootsuite' )
			);
		}

		// Invalid nonce.
		if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], $this->base->plugin->name ) ) {
			return new WP_Error(
				'wp_to_social_pro_admin_save_settings_error',
				__( 'Invalid nonce specified. Settings NOT saved.', 'wp-to-hootsuite' )
			);
		}

		// Get URL parameters.
		$tab       = $this->get_tab();
		$post_type = $this->get_post_type_tab();

		switch ( $tab ) {
			/**
			 * Authentication
			 */
			case 'auth':
				// oAuth settings are now handled by this class' oauth() function.
				// Save other Settings.

				// General Settings.
				$this->base->get_class( 'settings' )->update_option( 'test_mode', ( isset( $_POST['test_mode'] ) ? 1 : 0 ) );
				$this->base->get_class( 'settings' )->update_option( 'force_trailing_forwardslash', ( isset( $_POST['force_trailing_forwardslash'] ) ? 1 : 0 ) );
				$this->base->get_class( 'settings' )->update_option( 'proxy', ( isset( $_POST['proxy'] ) ? 1 : 0 ) );

				// Log Settings.
				// Always force errors.
				$log = $_POST['log'];
				if ( ! isset( $log['log_level'] ) ) {
					$log['log_level'] = array(
						'error',
					);
				} else {
					// 'Error' is disabled on the form and not sent if another option is chosen.
					// We always want errors to be logged so add it to the log levels now.
					$log['log_level'][] = 'error';
				}
				$this->base->get_class( 'settings' )->update_option( 'log', $log );

				// Done.
				return true;

			/**
			 * Post Type
			 */
			default:
				// Unslash and decode JSON field.
				$settings = json_decode( wp_unslash( $_POST[ $this->base->plugin->name ]['statuses'] ), true );

				// Save Settings for this Post Type.
				return $this->base->get_class( 'settings' )->update_settings( $post_type, $settings );
		}

	}

	/**
	 * Returns the settings tab that the user has selected.
	 *
	 * @since   3.7.2
	 *
	 * @param   mixed $profiles   API Profiles (false|WP_Error|array).
	 * @return  string  Tab
	 */
	private function get_tab( $profiles = false ) {

		$tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'auth' ); // phpcs:ignore WordPress.Security.NonceVerification

		// If we're on the Settings tab, return.
		if ( $tab === 'auth' ) {
			return $tab;
		}

		// If Profiles are an error, show error.
		if ( is_wp_error( $profiles ) ) {
			return 'profiles-error';
		}

		// If no Profiles exist, show error.
		if ( is_array( $profiles ) && ! count( $profiles ) ) {
			return 'profiles-missing';
		}

		// Return tab.
		return $tab;

	}

	/**
	 * Returns the Post Type tab that the user has selected.
	 *
	 * @since   3.7.2
	 *
	 * @return  string  Tab
	 */
	private function get_post_type_tab() {

		return ( isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification

	}

}
