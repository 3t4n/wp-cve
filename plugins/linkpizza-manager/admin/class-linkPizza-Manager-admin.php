<?php

define( 'PZZ_OIDC_CLIENT_ID', 'linkpizza-manager-wordpress' );
define( 'PZZ_OIDC_AUTH_URI', 'https://sso.linkpizza.com/auth/realms/linkpizza/protocol/openid-connect/auth' );
define( 'PZZ_OIDC_LOGOUT_URI', 'https://sso.linkpizza.com/auth/realms/linkpizza/protocol/openid-connect/logout' );
define( 'PZZ_OIDC_TOKEN_ENDPOINT', 'https://sso.linkpizza.com/auth/realms/linkpizza/protocol/openid-connect/token' );
define( 'PZZ_OIDC_HOST_HEADER', 'sso.linkpizza.com' );
define( 'PZZ_OIDC_API_HOST_HEADER', 'api.pzz.io' );
define( 'PZZ_OIDC_API_BASE_PATH', 'https://api.pzz.io/affiliate' );
define( 'PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME', 'pzz_oidc_access_token' );
define( 'PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME', 'pzz_oidc_refresh_token' );

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkpizza-manager
 * @subpackage linkpizza-manager/admin
 */
class linkPizza_Manager_Admin {

	/**
	 * Retrieves linkPizza information, now through openID
	 *
	 * @since 5.3
	 * @return void
	 */
	public function start_openid_authentication() {
		$callback_url = admin_url( 'admin.php?page=linkpizza-manager&pzz_callback=oauth' );
		$client       = new OAuth2\Client( PZZ_OIDC_CLIENT_ID, '', OAuth2\Client::AUTH_TYPE_URI, ABSPATH . WPINC . '/certificates/ca-bundle.crt' );
		if ( ! isset( $_GET['code'] ) ) {
			$auth_url = $client->getAuthenticationUrl( PZZ_OIDC_AUTH_URI, $callback_url );
			header( 'Location: ' . $auth_url );
			die( 'Redirect' );
		}
	}

	/**
	 * Removes session from the database and logs the user out through OpenID.
	 *
	 * @return void
	 */
	public function start_openid_logout() {
		$callback_url = admin_url( 'admin.php?page=linkpizza-manager&pzz_callback=logout&pzz_message=logout_success' );
		$client       = new OAuth2\Client( PZZ_OIDC_CLIENT_ID, '', OAuth2\Client::AUTH_TYPE_URI, ABSPATH . WPINC . '/certificates/ca-bundle.crt' );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['code'] ) ) {
			// Delete tokens from database.
			delete_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
			delete_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME );

			// Logout.
			$auth_url = $client->getAuthenticationUrl( PZZ_OIDC_LOGOUT_URI, $callback_url );

			// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			wp_redirect( $auth_url );
			exit;
		}
	}

	/**
	 * Handles the openID callback.
	 *
	 * @return void
	 */
	public function handle_openid_callback() {
		$callback = filter_input( INPUT_GET, 'pzz_callback' );
		if ( isset( $_GET['code'] ) && ! empty( $callback ) && 'oauth' === $callback ) {
			$callback_url  = admin_url( 'admin.php?page=linkpizza-manager&pzz_callback=oauth' );
			$client        = new OAuth2\Client( PZZ_OIDC_CLIENT_ID, '', OAuth2\Client::AUTH_TYPE_URI, ABSPATH . WPINC . '/certificates/ca-bundle.crt' );
			$params        = array(
				'code'         => sanitize_text_field( wp_unslash( $_GET['code'] ) ),
				'redirect_uri' => $callback_url,
			);
			$extra_headers = array( 'Host' => PZZ_OIDC_HOST_HEADER );
			$response      = $client->getAccessToken( PZZ_OIDC_TOKEN_ENDPOINT, 'authorization_code', $params, $extra_headers );
			if ( 200 === $response['code'] && isset( $response['result'] ) && isset( $response['result']['access_token'] ) ) {
				$access_oidc_token   = $response['result']['access_token'];
				$refresh_oidcs_token = $response['result']['refresh_token'];

				if ( get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME ) !== false ) {
					update_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME, $access_oidc_token, 'yes' );
				} else {
					add_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME, $access_oidc_token, '', 'yes' );
				}

				if ( get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME ) !== false ) {
					update_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME, $refresh_oidcs_token, 'yes' );
				} else {
					add_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME, $refresh_oidcs_token, '', 'yes' );
				}

				try {
					$client->setAccessToken( $access_oidc_token );
					$client->setAccessTokenType( 1 );
					$response = $client->fetch( PZZ_OIDC_API_BASE_PATH . '/user/me' );
					if ( 200 === $response['code'] ) {
						$this->update_user_information( $response['result'] );
						header( 'Location: ' . admin_url( 'admin.php?page=linkpizza-manager&pzz_message=login_success' ) );
					} else {
						header( 'Location: ' . admin_url( 'admin.php?page=linkpizza-manager&pzz_message=login_failure' ) );
						die( 'Redirect' );
					}
				} catch ( \OAuth2\Exception $e ) {
					echo 'Caught exception: ',  esc_html( $e->getMessage() ), "\n";
				}
			} else {
				wp_safe_redirect( admin_url( 'admin.php?page=linkpizza-manager&pzz_message=login_failure' ) );
				exit;
			}
		}
	}

	/**
	 * Updates the information from the response of the authentication API call.
	 *
	 * @param array $response information about the result of the of the API Call.
	 * @return void
	 */
	public function update_information( $response ) {
		$this->update_user_information( $response );
	}


	/**
	 * Saves the information from the response in options in the database.
	 *
	 * @param array $response information about the result of the of the API Call.
	 * @return void
	 */
	public function update_user_information( $response ) {
		if ( null !== $response ) {
			if ( get_option( 'pzz_fullname' ) !== false ) {
				update_option( 'pzz_fullname', $response['fullName'], 'yes' );
			} else {
				add_option( 'pzz_fullname', $response['fullName'], '', 'yes' );
			}
			if ( get_option( 'pzz_username' ) !== false ) {
				update_option( 'pzz_username', $response['userName'], 'yes' );
			} else {
				add_option( 'pzz_username', $response['userName'], '', 'yes' );
			}
			if ( get_option( 'pzz_id' ) !== false ) {
				update_option( 'pzz_id', $response['id'], 'yes' );
			} else {
				add_option( 'pzz_id', $response['id'], '', 'yes' );
			}
		}
	}

	/**
	 * Displays error message when LinkPizza can't find a valid API token.
	 *
	 * @since 4.5
	 * @return void
	 */
	public function pzz_admin_notice() {
		$access_oidc_token   = get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
		$refresh_oidcs_token = get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME );
		if ( null === $access_oidc_token || null === $refresh_oidcs_token || '' === $access_oidc_token || '' === $refresh_oidcs_token ) {
			?>
				<div class="notice notice-error">
					<p>
					<?php
						$linkpizza_settings_url = admin_url( 'admin.php?page=linkpizza-manager#pzz-login' );
						printf(
							wp_kses_post(
								// Translators: %s is replaced with the link to the plugin settings.
								__(
									'LinkPizza: You need to <a href="%s">log in</a> to activate the plugin.',
									'linkpizza-manager'
								)
							),
							esc_url( $linkpizza_settings_url )
						)
					?>
					</p>
				</div>
			<?php
		}
		$this->show_pzz_message();
		$this->pzz_bulk_action_admin_notice();
	}

	/**
	 * Show message on the wp-admin pages.
	 *
	 * @return void
	 */
	public function show_pzz_message() {
		$messages     = array(
			'login_success' => array(
				'type'    => 'update',
				'message' => __( 'You have succesfully logged in.', 'linkpizza-manager' ),
			),
			'login_failure' => array(
				'type'    => 'error',
				'message' => __( 'Something went wrong while connecting to the LinkPizza API, the server returned an error. Please check with your hosting provider if connections to pzz.io are not blocked.', 'linkpizza-manager' ),
			),
			'logout_success' => array(
				'type'    => 'update',
				'message' => __( 'You have been logged out.', 'linkpizza-manager' ),
			),
		);
		$message_type = filter_input( INPUT_GET, 'pzz_message' );
		if ( ! empty( $message_type ) && array_key_exists( $message_type, $messages ) ) :
			?>
			<div class="is-dismissible notice <?php echo esc_attr( $messages[ $message_type ]['type'] ); ?>">
				<p><?php echo esc_html( $messages[ $message_type ]['message'] ); ?></p>
			</div>
			<?php
		endif;
	}

	/**
	 * Adds settings field and sections for LinkPizza
	 *
	 * @since 4.5
	 */
	public function pzz_settings_init() {

		register_setting( 'linkPizza_Page', 'pzz_settings' );
		register_setting( 'linkPizza_Page', 'pzz_logged_in_user' );
		register_setting( 'linkPizza_Page', PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
		register_setting( 'linkPizza_Page_advanced', 'pzz_tracking_only_categories' );
		register_setting( 'linkPizza_Page_advanced', 'pzz_tracking_only_on_posts_before' );
		register_setting( 'linkPizza_Page_advanced', 'pzz_indexable_javascript' );
		register_setting( 'linkPizza_Page_advanced', 'pzz_extend_preview_time' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_color' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_width' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_padding' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_width' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_link_color' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_enabled' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_position' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_layout_type' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_tag_background_color' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_use_title_text' );

		add_settings_section(
			'pzz_linkPizza_Page_section',
			__( 'Login Credentials', 'linkpizza-manager' ),
			array( &$this, 'pzz_settings_section_callback' ),
			'linkPizza_Page'
		);

		add_settings_section(
			'pzz_linkPizza_Page_section_link_summary',
			__( 'Link summary', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_settings_section_callback' ),
			'linkPizza_Page_link_summary'
		);

		add_settings_section(
			'pzz_linkPizza_Page_section_advanced',
			__( 'Advanced', 'linkpizza-manager' ),
			array( &$this, 'pzz_advanced_settings_section_callback' ),
			'linkPizza_Page_advanced'
		);

		add_settings_section(
			'pzz_linkPizza_Page_section_help',
			__( 'Help', 'linkpizza-manager' ),
			array( &$this, 'pzz_linkpizza_page_section_help_callback' ),
			'linkPizza_Page_help'
		);

		add_settings_field(
			'pzz_logged_in_user',
			__( 'Logged in user', 'linkpizza-manager' ),
			array( &$this, 'pzz_logged_in_user_render' ),
			'linkPizza_Page',
			'pzz_linkPizza_Page_section'
		);

		add_settings_field(
			PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME,
			__( 'Login', 'linkpizza-manager' ),
			array( &$this, 'pzz_oidc_token_render' ),
			'linkPizza_Page',
			'pzz_linkPizza_Page_section'
		);

		add_settings_field(
			'pzz_tracking_only_categories',
			__( 'Disabled Categories', 'linkpizza-manager' ),
			array( &$this, 'pzz_tracking_only_categories_render' ),
			'linkPizza_Page_advanced',
			'pzz_linkPizza_Page_section_advanced'
		);

		add_settings_field(
			'pzz_tracking_only_on_posts_before',
			__( 'Only convert links on posts published after (dd-mm-yyyy)', 'linkpizza-manager' ),
			array( &$this, 'pzz_tracking_only_on_posts_before_render' ),
			'linkPizza_Page_advanced',
			'pzz_linkPizza_Page_section_advanced'
		);

		add_settings_field(
			'pzz_indexable_javascript',
			__( 'Indexable version of the script (Not recommended)', 'linkpizza-manager' ),
			array( &$this, 'pzz_indexable_javascript_render' ),
			'linkPizza_Page_advanced',
			'pzz_linkPizza_Page_section_advanced'
		);

		add_settings_field(
			'pzz_extend_preview_time',
			__( 'extended preview time when using the <a href="https://wordpress.org/plugins/public-post-preview/">Public Post Preview</a> plugin', 'linkpizza-manager' ),
			array( &$this, 'pzz_extend_preview_time_render' ),
			'linkPizza_Page_advanced',
			'pzz_linkPizza_Page_section_advanced'
		);

		// Link summary.
		add_settings_field(
			'pzz_link_summary_enabled',
			__( 'Enabled', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_enabled_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_width',
			__( 'Width', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_width_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_link_color',
			__( 'Link Color', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_link_color_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_border_color',
			__( 'Border Color', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_border_color_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);
		add_settings_field(
			'pzz_link_summary_border_width',
			__( 'Border Width (put to 0 to hide border)', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_border_width_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);
		add_settings_field(
			'pzz_link_summary_border_padding',
			__( 'Border Padding', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_border_padding_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);
		add_settings_field(
			'pzz_link_summary_position',
			__( 'Position', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_position_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);
		add_settings_field(
			'pzz_link_summary_layout_type',
			__( 'Layout Type', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_layout_type_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_tag_background_color',
			__( 'Tag Background color', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_tag_background_color_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_tag_background_color',
			__( 'Tag Background color', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_tag_background_color_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

		add_settings_field(
			'pzz_link_summary_use_title_text',
			__( 'Use title attribute as label, falls back to text on the link. If both are not there it uses the domain', 'linkpizza-manager' ),
			array( &$this, 'pzz_link_summary_use_title_text_render' ),
			'linkPizza_Page_link_summary',
			'pzz_linkPizza_Page_section_link_summary'
		);

	}

	/**
	 *  Listen for any button clicks
	 *
	 * @since 5.3
	 */
	public function pzz_check_buttons_pressed() {
		$this->authenticate_button_pressed();
	}

	/**
	 * Starts OpenID authentication when the button has been pressed.
	 *
	 * @return void
	 */
	public function authenticate_button_pressed() {
		// TODO: add nonce checking.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['authenticate-linkpizza-shortcut'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( 'submitted' === $_POST['authenticate-linkpizza-shortcut'] ) {
				$this->start_openid_authentication();
			}
		}
	}

	/**
	 * Starts OpenID logout when the logout button has been pressed.
	 *
	 * @return void
	 */
	public function handle_logout() {
		// Security check.
		$nonce = filter_input( INPUT_POST, 'pzz-logout-nonce' );
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'pzz-logout-admin' ) ) {
			wp_die( esc_html_e( 'You are not allowed to logout', 'linkpizza-manager' ) );
		}
		// User check.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You are not allowed to logout', 'linkpizza-manager' ) );
		}
		// Handle logout.
		$this->start_openid_logout();
	}


	/**
	 * Renders the input field for the API token and description below
	 *
	 * @since 4.5
	 */
	public function pzz_logged_in_user_render() {
		?>
		<div id="pzz-preloaded-error-message">
			<p><?php esc_html_e( 'Something went wrong while the plugin tried to reach LinkPizza, please follow this <a href="https://www.elegantthemes.com/blog/tips-tricks/using-the-wordpress-debug-log">guide</a> to activate logging and send the log to us.', 'linkpizza-manager' ); ?></p>
		</div>

		<?php
		$response = pzz_do_oauth_call_with_refresh_check( PZZ_OIDC_API_BASE_PATH . '/user/me', array(), 0 );
		if ( null !== $response ) {
			$this->update_information( $response );

			?>
			<div>
				<p>
					<?php esc_html_e( 'Hi', 'linkpizza-manager' ); ?> <b><?php echo esc_html( $response['fullName'] ); ?></b>,

					<?php esc_html_e( 'Your LinkPizza installation is currently active under account number:', 'linkpizza-manager' ); ?>
					<b><?php echo esc_html( $response['id'] ); ?></b>
				</p>
				<?php $this->pzz_oidc_token_render( true ); ?>
			</div>
		<?php } else { ?>
				<?php $this->pzz_oidc_token_render( false ); ?>
			<?php
		}

		?>
		<script>
			document.getElementById("pzz-preloaded-error-message").style = "display:none;";
		</script>
		<?php
	}

	/**
	 * Renders the authentiation form.
	 *
	 * @return void
	 */
	public function pzz_oidc_token_render( $loggedin = false ) {
		if ( $loggedin ) :
			?>
				<div class="pzz-dashboard-login">
				<form id="pzz_refresh_statistics" method='post' action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="submit" value="<?php esc_attr_e( 'Logout', 'linkpizza-manager' ); ?>" class="button">
					<?php wp_nonce_field( 'pzz-logout-admin', 'pzz-logout-nonce', false, true ); ?>
					<input type="hidden" name="action" value="pzz_logout">
				</form>
				<p>
					<?php esc_html_e( 'If you want to use a different account, please logout first. ', 'linkpizza-manager' ); ?>
				</p>
			</div>
			<?php
		else :
			?>
			<div class="pzz-dashboard-login" id="pzz-login">
				<div class="pzz-dashboard-message">
					<p><?php esc_html_e( 'Log in or register quickly with LinkPizza and earn money thanks to the LinkPizza platform.', 'linkpizza-manager' ); ?></p>
				</div>
				<form id="pzz_refresh_statistics" method='post'>
					<input type="submit" value="<?php esc_attr_e( 'Authenticate with LinkPizza', 'linkpizza-manager' ); ?>" class="button button-primary">
					<input type="hidden" name="authenticate-linkpizza-shortcut" value="submitted">
				</form>
				<a href="https://linkpizza.com/nl/signup/publisher" class="button button-primary button-pzz" target="_blank" title="<?php esc_attr_e( 'Create an account on linkpizza.com', 'linkpizza-manager' ); ?>"><?php esc_html_e( 'Register with LinkPizza', 'linkpizza-manager' ); ?></a>
			</div>
			<?php
		endif;
	}



	/**
	 * Renders the disabled categories option.
	 *
	 * @since 4.6
	 */
	public function pzz_tracking_only_categories_render() {
		$tracking_only_categories = get_option( 'pzz_tracking_only_categories' );
		$categories               = get_categories();
		$tracking_category_ids    = is_array( $tracking_only_categories ) ? $tracking_only_categories : array( $tracking_only_categories );
		foreach ( $categories as $category ) :
			?>
			<input  class="widefat" type="checkbox" name="pzz_tracking_only_categories[<?php echo esc_attr( $category->cat_ID ); ?>]"
					value="<?php echo esc_attr( $category->cat_ID ); ?>" <?php checked( true, in_array( (string) $category->cat_ID, $tracking_category_ids, true ) ); ?> />
			<span><?php echo esc_html( $category->cat_name ); ?></span><br/>
			<?php
			endforeach;
	}

	/**
	 * Renders the input field for the date after which posts should be enabled
	 *
	 * @since 5.2.3
	 * @return void
	 */
	public function pzz_tracking_only_on_posts_before_render() {
		$tracking_only_before_date = get_option( 'pzz_tracking_only_on_posts_before' );
		?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('.custom_date').datepicker({
						changeMonth: true,
						changeYear: true,
						changeDay: true,
						dateFormat : 'dd-mm-yy'
					});
				});
			</script>
			<input type="text" class="custom_date" name="pzz_tracking_only_on_posts_before" value="<?php echo esc_attr( $tracking_only_before_date ); ?>"/>
		<?php

	}

	/**
	 * Renders the checkbox for using the indexable version of the linkPizza script
	 *
	 * @since 5.0.6
	 * @return void
	 */
	public function pzz_indexable_javascript_render() {
		$indexable_javascript = get_option( 'pzz_indexable_javascript' );
		?>
		<input class="widefat" type="checkbox" name="pzz_indexable_javascript" value="1" <?php echo checked( $indexable_javascript, 1, true ); ?>  />
		<?php
	}

	/**
	 * Renders the checkbox for extending preview time when using the public-post-preview plugin
	 *
	 * @since 5.0.6
	 * @return void
	 */
	public function pzz_extend_preview_time_render() {
		$extended_preview_time = get_option( 'pzz_extend_preview_time' );
		?>
		<input class="widefat" type="checkbox" name="pzz_extend_preview_time" value="1" <?php echo checked( $extended_preview_time, 1, true ); ?>  />
		<?php
	}

	/**
	 * Renders the Link Summary width option
	 *
	 * @return void
	 */
	public function pzz_link_summary_width_render() {
		$pzz_link_summary_width = get_option( 'pzz_link_summary_width' );
		?>
		<input type="number" name="pzz_link_summary_width"  min="0" max="100" value="<?php echo esc_attr( empty( $pzz_link_summary_width ) ? '100' : $pzz_link_summary_width ); ?>">%
		<?php
	}

	/**
	 * Renders the Link Summary link color option
	 *
	 * @return void
	 */
	public function pzz_link_summary_link_color_render() {
		$pzz_link_summary_link_color = get_option( 'pzz_link_summary_link_color' );
		?>
		<input type="text" name="pzz_link_summary_link_color" class="pzz-colorpicker" value="<?php echo esc_attr( empty( $pzz_link_summary_link_color ) ? '#3699DC' : $pzz_link_summary_link_color ); ?>">

		<?php
	}

	/**
	 * Renders the Link Summary border color option
	 *
	 * @return void
	 */
	public function pzz_link_summary_border_color_render() {
		$pzz_link_summary_border_color = get_option( 'pzz_link_summary_border_color' );
		?>
		<input type="text" name="pzz_link_summary_border_color" class="pzz-colorpicker" value="<?php echo esc_attr( empty( $pzz_link_summary_border_color ) ? '#D3D3D3' : $pzz_link_summary_border_color ); ?>">

		<?php
	}

	/**
	 * Renders the Link Summary border width option
	 *
	 * @return void
	 */
	public function pzz_link_summary_border_width_render() {
		$pzz_link_summary_border_width = get_option( 'pzz_link_summary_border_width' );
		?>
		<input type="number" name="pzz_link_summary_border_width"  min="0" max="5" value="<?php echo esc_attr( empty( $pzz_link_summary_border_width ) ? '1' : $pzz_link_summary_border_width ); ?>">
		<?php
	}

	/**
	 * Renders the Link Summary border padding option
	 *
	 * @return void
	 */
	public function pzz_link_summary_border_padding_render() {
		$pzz_link_summary_border_padding = get_option( 'pzz_link_summary_border_padding' );
		?>
		<input type="number" name="pzz_link_summary_border_padding"  min="0" max="10" value="<?php echo esc_attr( empty( $pzz_link_summary_border_padding ) ? '6' : $pzz_link_summary_border_padding ); ?>">
		<?php
	}

	/**
	 * Renders the Link Summary enabled option
	 *
	 * @return void
	 */
	public function pzz_link_summary_enabled_render() {
		$pzz_link_summary_enabled = get_option( 'pzz_link_summary_enabled' );
		?>
		<input class="widefat" type="checkbox" name="pzz_link_summary_enabled" value="1" <?php checked( $pzz_link_summary_enabled, '1', true ); ?>  />
		<?php
	}

	/**
	 * Renders the Link Summary postion option
	 *
	 * @return void
	 */
	public function pzz_link_summary_position_render() {
		$pzz_link_summary_position = get_option( 'pzz_link_summary_position' );
		?>
		<input class="widefat" type="radio" name="pzz_link_summary_position" <?php echo checked( $pzz_link_summary_position, '1', true ); ?> value="1" /><?php esc_html_e( 'Left', 'linkpizza-manager' ); ?>
		<input class="widefat" type="radio" name="pzz_link_summary_position" <?php echo checked( $pzz_link_summary_position, '2', true ); ?> value="2" /><?php esc_html_e( 'Center', 'linkpizza-manager' ); ?>
		<input class="widefat" type="radio" name="pzz_link_summary_position" <?php echo checked( $pzz_link_summary_position, '3', true ); ?> value="3" /><?php esc_html_e( 'Right', 'linkpizza-manager' ); ?>
		<?php
	}

	/**
	 * Renders the Link Summary Layout type option
	 *
	 * @return void
	 */
	public function pzz_link_summary_layout_type_render() {
		$pzz_link_summary_layout_type = get_option( 'pzz_link_summary_layout_type' );
		?>
		<input class="widefat" type="radio" name="pzz_link_summary_layout_type" <?php echo checked( $pzz_link_summary_layout_type, '1', true ); ?> value="1" /><?php esc_html_e( 'List', 'linkpizza-manager' ); ?>
		<input class="widefat" type="radio" name="pzz_link_summary_layout_type" <?php echo checked( $pzz_link_summary_layout_type, '2', true ); ?> value="2" /><?php esc_html_e( 'Tags', 'linkpizza-manager' ); ?>
		<?php
	}

	/**
	 * Renders the Link Summary Background Color tag option
	 *
	 * @return void
	 */
	public function pzz_link_summary_tag_background_color_render() {
		$pzz_link_summary_tag_background_color = get_option( 'pzz_link_summary_tag_background_color' );
		?>
		<input type="text" name="pzz_link_summary_tag_background_color" class="pzz-colorpicker" value="<?php echo esc_attr( empty( $pzz_link_summary_tag_background_color ) ? '' : $pzz_link_summary_tag_background_color ); ?>">
		<?php
	}


	/**
	 * Renders the Link Summary use title attribute option
	 *
	 * @return void
	 */
	public function pzz_link_summary_use_title_text_render() {
		$pzz_link_summary_use_title_text = get_option( 'pzz_link_summary_use_title_text' );
		?>
		<input class="widefat" type="checkbox" name="pzz_link_summary_use_title_text" value="1" <?php echo esc_attr( checked( $pzz_link_summary_use_title_text, 1, true ) ); ?>  />
		<?php
	}

	/**
	 * Adds linkPizza option section description
	 *
	 * @since 4.5
	 * @return void
	 */
	public function pzz_settings_section_callback() {
		esc_html_e( 'Log in to your LinkPizza account to get started with this plugin, press the button below to continue.', 'linkpizza-manager' );
	}

	/**
	 * Adds linkPizza disabled categories section description
	 *
	 * @since 4.6
	 * @return void
	 */
	public function pzz_advanced_settings_section_callback() {
		esc_html_e( 'LinkPizza will not change your links in these categories, this also disables monetization for those links.', 'linkpizza-manager' );
	}

	/**
	 * Adds linkPizza disabled categories section description
	 *
	 * @since 4.6
	 * @return void
	 */
	public function pzz_top_link_ads_settings_section_callback() {
		esc_html_e( 'Configuration for the top links', 'linkpizza-manager' );
	}


	/**
	 * Adds linkPizza link summary description
	 *
	 * @since 5.0.0
	 * @return void
	 */
	public function pzz_link_summary_settings_section_callback() {
		esc_html_e( 'Repeat the links you used in your articles at the bottom of the post.', 'linkpizza-manager' );
	}

	/**
	 * Adds linkPizza disabled categories section description
	 *
	 * @since 4.9.5
	 * @return void
	 */
	public function pzz_linkpizza_page_section_help_callback() {
		?>
		<div>
			<p><?php esc_html_e( 'Go directly to your most important pages', 'linkpizza-manager' ); ?></p>
			<ul>
				<li><a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'LinkPizza Link widget', 'linkpizza-manager' ); ?></a> - <?php esc_html_e( 'Add a LinkPizza Link widget to your sidebar or footer', 'linkpizza-manager' ); ?> </li>
				<li><a href="https://app.linkpizza.com/nl/affiliate/statistics"><?php esc_html_e( 'Statistics', 'linkpizza-manager' ); ?></a> - <?php esc_html_e( 'Go to your statistics on our website', 'linkpizza-manager' ); ?></li>
				<li><a href="http://support.linkpizza.com/"><?php esc_html_e( 'FAQ', 'linkpizza-manager' ); ?></a> - <?php esc_html_e( 'Go to our frequently asked questions', 'linkpizza-manager' ); ?> </li>
			</ul>
		</div>
		<div>
			<h2><?php esc_html_e( 'Shortcodes', 'linkpizza-manager' ); ?></h2>
			<p><?php esc_html_e( 'Currently LinkPizza has  a shortcode you can use in your posts to insert a widget', 'linkpizza-manager' ); ?></p>
			<ul>
				<li>[pzzwidget id=(<b><?php esc_html_e( 'ID of the widget, you can find these on ZEEF.com, looking at our examples or be provided one by your account manager', 'linkpizza-manager' ); ?></b>) width=(<b><?php esc_html_e( 'width of the widget', 'linkpizza-manager' ); ?></b>) height=(<b><?php esc_html_e( 'height of the widget', 'linkpizza-manager' ); ?></b>)]</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Adds linkPizza options section
	 *
	 * @since 4.5
	 * @return void
	 */
	public function render_pzz_options_page() {
		$access_oidc_token   = get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
		$refresh_oidcs_token = get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME );
		$logged_in           = ! ( null === $access_oidc_token || null === $refresh_oidcs_token || '' === $access_oidc_token || '' === $refresh_oidcs_token );
		?>
		<div class="pzz-dashboard-account">
		<h2>
			<?php if ( $logged_in ) : ?>
				<?php esc_html_e( 'Account', 'linkpizza-manager' ); ?></h2>
			<?php else : ?>
				<?php esc_html_e( 'You are not making any money (yet)!', 'linkpizza-manager' ); ?></h2>
			<?php endif; ?>
		<?php
		// Check for old SSL version.
		if ( ! ( defined( 'OPENSSL_TLSEXT_SERVER_NAME' ) && OPENSSL_TLSEXT_SERVER_NAME ) ) :
			?>
				<div class="error">
					<p><?php esc_html_e( 'Your version of OpenSSL is outdated, please use at least version 0.9.8j to be able to use LinkPizza', 'linkpizza-manager' ); ?></p>
				</div>
				<?php
				// Return because the login option probably won't work.
				return;
			endif;
		?>

		<?php
		$this->pzz_logged_in_user_render();
		?>
		</div>

		<?php
		$tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
		$this->pzz_admin_tabs( $tab );
		?>
		<form action='options.php' method='post'>
		<?php
		if ( 'link-summary' === $tab ) {
			settings_fields( 'linkPizza_Page_link_summary' );
			do_settings_sections( 'linkPizza_Page_link_summary' );
			submit_button();
		}
		if ( 'advanced' === $tab ) {
			settings_fields( 'linkPizza_Page_advanced' );
			do_settings_sections( 'linkPizza_Page_advanced' );
			submit_button();
		}
		if ( 'general' === $tab ) {
			$this->display_dashboard();
		}
		if ( 'help' === $tab ) {
			do_settings_sections( 'linkPizza_Page_help' );
		}
		?>
		</form>
		<?php
	}

	/**
	 * Creates admin page.
	 *
	 * @param string $current Active tab.
	 * @return void
	 */
	public function pzz_admin_tabs( $current = 'homepage' ) {
		$tabs = array(
			'general'      => esc_html__( 'General', 'linkpizza-manager' ),
			'link-summary' => esc_html__( 'Link Summary', 'linkpizza-manager' ),
			'advanced'     => esc_html__( 'Advanced', 'linkpizza-manager' ),
			'help'         => esc_html__( 'Help', 'linkpizza-manager' ),
		);
		?>
		<div id="icon-themes" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab => $name ) :
			$class   = ( $tab === $current ) ? ' nav-tab-active' : '';
			$url     = admin_url( 'admin.php?page=linkpizza-manager' );
			$tab_url = add_query_arg(
				array( 'tab' => $tab ),
				$url
			);
			?>
			<a class="nav-tab<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $tab_url ); ?>"><?php echo esc_html( $name ); ?></a>
			<?php
			endforeach;
		?>
		</h2>
		<?php
	}

	/**
	 *
	 * Adds linkPizza menu
	 *
	 * @since 4.5
	 * @return void
	 */
	public function pzz_add_admin_menu() {
		add_menu_page(
			'LinkPizza',
			'LinkPizza ' . $this->get_notification(),
			'manage_options',
			'linkpizza-manager',
			array( &$this, 'render_pzz_options_page' ),
			plugins_url( '/assets/Icon-grey.png', __FILE__ )
		);
	}

	/**
	 * Gets notifications to be display in the admin menu bar.
	 *
	 * At this moment, there is only one notification possible.
	 *
	 * @return string html of the notification.
	 */
	public function get_notification() {
		$access_oidc_token   = get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
		$refresh_oidcs_token = get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME );

		$notification       = null === $access_oidc_token || null === $refresh_oidcs_token || '' === $access_oidc_token || '' === $refresh_oidcs_token;
		$notification_count = $notification ? 1 : 0;
		/* translators: %s: number of notifications */
		$notifications = sprintf( _n( '%s notification', '%s notifications', $notification_count, 'linkpizza-manager' ), number_format_i18n( $notification_count ) );

		$counter = sprintf( '<span class="update-plugins count-%1$d"><span class="plugin-count" aria-hidden="true">%1$d</span><span class="screen-reader-text">%2$s</span></span>', $notification_count, $notifications );

		return $counter;

	}

	/**
	 * Changes the page title for the LinkPizza options pages.
	 *
	 * @param string $page_title Default page title.
	 * @return string $page_title with the name of the tab added.
	 */
	public function set_admin_title( $page_title ) {
		// If there is no page variable, return the default page title.
		if ( ! filter_has_var( INPUT_POST, 'page' ) && ! filter_has_var( INPUT_GET, 'page' ) ) {
			return $page_title;
		}

		$page = filter_has_var( INPUT_GET, 'page' )
			? filter_input( INPUT_GET, 'page' )
			: filter_input( INPUT_POST, 'page' );

		// If the page is not the LinkPizza options page,
		// return the default page title.
		if ( 'linkpizza-manager' !== $page ) {
			return $page_title;
		}

		// If the page has no tab input param, return the default page title.
		if ( ! filter_has_var( INPUT_POST, 'tab' ) && ! filter_has_var( INPUT_GET, 'tab' ) ) {
			return $page_title;
		}

		// Get the tab input param, from get or post.
		$tab_input = filter_has_var( INPUT_GET, 'tab' )
			? filter_input( INPUT_GET, 'tab' )
			: filter_input( INPUT_POST, 'tab' );

		// For the default option page, return the default page title.
		if ( 'general' === $tab_input ) {
			return $page_title;
		}

		$tab_name  = $tab_input ?? 'general';
		$titles    = array(
			'link-summary' => __( 'Link Summary', 'linkpizza-manager' ),
			'advanced'     => __( 'Advanced', 'linkpizza-manager' ),
			'help'         => __( 'Help', 'linkpizza-manager' ),
		);
		$tab_title = $titles[ $tab_name ] ?? $titles['general'];

		$page_replace_title = sprintf(
			/* translators: LinkPizza admin page title. %s: Tab name . */
			__( '%s - LinkPizza', 'linkpizza-manager' ),
			$tab_title
		);
		return str_replace( 'LinkPizza', $page_replace_title, $page_title );
	}

	/**
	 * Displays dashboard for plugin settings.
	 *
	 * @return void
	 */
	public function display_dashboard() {
		?>
		</form>
		<div class="pzz-dashboard-container">
			<div class="pzz-dashboard-box pzz-dashboard-info">
				<header><a href="https://wordpress.org/plugins/linkpizza-manager/" title="<?php echo esc_attr_e( 'Go to the plugin page on wordpress.org', 'linkpizza-manager' ); ?>" target="_blank"><img src="<?php echo esc_url( plugins_url( '/assets/header.png', __FILE__ ) ); ?>" alt="<?php echo esc_attr( 'LinkPizza - content & influencers' ); ?>"></a></header>
				<h2><?php esc_html_e( 'What is LinkPizza?', 'linkpizza-manager' ); ?></h2>
				<p>
					<?php
					$linkpizza_url = 'https://linkpizza.com';
					printf(
						wp_kses_post(
							// translators: %s is replaced with linkpizza.com website.
							__(
								'<a href="%s" target="_blank">LinkPizza</a> is a native advertising solution that helps bloggers and publishers monetize their content. It does this by redirecting normal links to monetizable links automatically, without any configuration needed.',
								'linkpizza-manager'
							)
						),
						esc_url( $linkpizza_url )
					);
					?>
				</p>
				<h2><?php esc_html_e( 'What does the plugin do?', 'linkpizza-manager' ); ?></h2>
				<p>
					<?php
					esc_html_e(
						'By signing up for LinkPizza and installing this plugin, your links are automatically monetized when linking to one of our implemented advertisers (30,000+). Our product is mainly aimed at the european market and already includes the major networks. Under your post you can see which links will be monetized and which links we can’t monetize yet, disable the plugin for specific links, disable it for the entire page or maybe even category. We automatically make your links no-follow if we can change it to an affiliate link, so your days of tagging all the links one by one are over.',
						'linkpizza-manager'
					);
					?>
				</p>
			</div>
			<div class="pzz-dashboard-sidebar">
				<div class="pzz-dashboard-box">
					<h2><?php esc_html_e( 'Widgets', 'linkpizza-manager' ); ?></h2>
					<p>
						<?php
							esc_html_e(
								'This plugin ships with 2 link widgets for you to use, an automatic and a manual variant. You can use these link-widgets to create top lists of your advertisers or maybe your favorite shops, it’s all possible.',
								'linkpizza-manager'
							);
						?>
					</p>
					<p>
						<?php
							$widgets_url = admin_url( 'widgets.php' );
							$help_url    = admin_url( 'admin.php?page=linkpizza-manager&tab=help' );
							printf(
								wp_kses_post(
									// translators: %1$s links to the widget page %2$s links to the linkpizza help page.
									__(
										'Go to the <a href="%1$s">Widgets configuration</a> to add a widget or read more about them in the <a href="%2$s">help section</a>.',
										'linkpizza-manager'
									)
								),
								esc_url( $widgets_url ),
								esc_url( $help_url )
							);
						?>
					</p>
				</div>
				<div class="pzz-dashboard-box">
					<h2><?php esc_html_e( 'Questions', 'linkpizza-manager' ); ?></h2>
					<p>
						<?php
							$wp_contact_url = 'https://wordpress.org/support/plugin/linkpizza-manager/';
							printf(
								wp_kses_post(
									// Translators: %s to the WordPress Forum.
									__(
										'If you have any technical questions, please go to the <a href="%s" target="_blank">WordPress Forum</a>.',
										'linkpizza-manager'
									)
								),
								esc_url( $wp_contact_url )
							);
						?>
					</p>
					<p>
						<?php
							$contact_url = 'https://linkpizza.com/company/contact/';
							printf(
								wp_kses_post(
									// Translators: %s links to the linkpizza help page.
									__(
										'For all other questions, you can go to <a href="%s" target="_blank">our site</a>.',
										'linkpizza-manager'
									)
								),
								esc_url( $contact_url )
							);
						?>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Create meta box to be displayed on the post editor screen.
	 *
	 * @since     1.0.0
	 * @return void
	 */
	public function linkpizza_add_post_meta_boxes() {
		$screens = array( 'post', 'page' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'linkpizza-post-enable',      // Unique ID.
				esc_html__( 'LinkPizza', 'linkpizza-manager' ),    // Title.
				array( $this, 'linkpizza_post_enable_meta_box' ),
				$screen,     // Admin page (or post type).
				'normal',         // Context.
				'core',         // Priority.
				array(
					'__block_editor_compatible_meta_box' => true, // Explicitly set Gutenberg compatibility.
				)
			);
		}
		$other_post_types = get_post_types( array( '_builtin' => false ) );
		foreach ( $other_post_types as $other_post_type ) {
			add_meta_box(
				'linkpizza-post-enable',      // Unique ID.
				esc_html__( 'LinkPizza', 'linkpizza-manager' ),    // Title.
				array( $this, 'linkpizza_post_enable_meta_box' ),
				$other_post_type,     // Admin page (or post type).
				'advanced',         // Context.
				'low'         // Priority.
			);
		}
	}

	/**
	 * Hides LinkPizza Metabox when post type is not post or page.
	 *
	 * @param string[] $hidden An array of IDs of hidden meta boxes.
	 * @return string[] An array of IDs of hidden meta boxes.
	 */
	public function custom_hidden_meta_boxes( $hidden ) {
		if ( ! in_array( get_post_type(), array( 'post', 'page' ), true ) ) {
			$hidden[] = 'linkpizza-post-enable';
			return $hidden;
		} else {
			return $hidden;
		}
	}

	/**
	 * Display the post meta box.
	 *
	 * @since     1.0.0
	 * @param WP_Post $object The WP_Post object.
	 * @return void
	 */
	public function linkpizza_post_enable_meta_box( $object ) {
		wp_nonce_field( 'linkPizza_post_custom_box', 'linkPizza_post_enable_nonce' );
		$disabled_domains_meta             = get_post_meta( $object->ID, '_linkpizza_disabled_domains', true );
		$disabled_domains                  = is_array( $disabled_domains_meta ) ? $disabled_domains_meta : array( $disabled_domains_meta );
		$tracking_only_domains_meta        = get_post_meta( $object->ID, '_linkpizza_tracking_only_domains', true );
		$tracking_only_domains             = is_array( $tracking_only_domains_meta ) ? $tracking_only_domains_meta : array( $tracking_only_domains_meta );
		$pzz_link_summary_enabled_globally = get_option( 'pzz_link_summary_enabled', false );
		?>

		<div width="100%">
			<b><?php esc_html_e( 'General', 'linkpizza-manager' ); ?></b>
			<p>
				<input class="widefat" type="checkbox" name="linkpizza-tracking-only" id="linkpizza-tracking-only" value="1" <?php checked( get_post_meta( $object->ID, '_linkPizza_tracking_only', true ), 1 ); ?> size="30" />
				<label for="linkpizza-tracking-only"><?php esc_html_e( "Only track statistics but don't change links on this page to affiliate links.", 'linkpizza-manager' ); ?></label></br>
				<input class="widefat" type="checkbox" name="linkpizza-disabled" id="linkpizza-disabled" value="1" <?php checked( get_post_meta( $object->ID, '_linkPizza_disabled', true ), 1 ); ?> size="30" />
				<label for="linkpizza-disabled"><?php esc_html_e( 'Disable LinkPizza (automatic affiliate links and tracking) for this specific post or page.', 'linkpizza-manager' ); ?></label></br>
			<?php if ( $pzz_link_summary_enabled_globally ) : ?>
					<input class="widefat" type="checkbox" name="pzz_link_summary_disabled_post" id="pzz_link_summary_disabled_post" value="1" <?php checked( get_post_meta( $object->ID, '_pzz_link_summary_disabled_post', true ), 1 ); ?> size="30" />
					<label for="linkpizza-disabled"><?php esc_html_e( 'Disable link summary for this specific post or page.', 'linkpizza-manager' ); ?></label>
				<?php endif; ?>
			</p>
			<b><?php esc_html_e( 'Turn affiliate off for specific links', 'linkpizza-manager' ); ?></b>
			<p>
		<?php
		if ( class_exists( 'DOMDocument' ) ) {
			$dom_document = new DOMDocument();
			libxml_use_internal_errors( true );
			if ( '' !== $object->post_content ) {
				$dom_document->loadHTML( $object->post_content );
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$dom_document->preserveWhiteSpace = false;

				// Use DOMXpath to navigate the html with the DOM.
				$elements = $dom_document->getElementsByTagName( 'a' );
				$domains  = array();
				foreach ( $elements as $link ) {
					// phpcs:ignore PHPCompatibility.LanguageConstructs.NewEmptyNonVariable.Found
					if ( ! empty( wp_parse_url( $link->getAttribute( 'href' ) )['host'] ) && ! in_array( wp_parse_url( $link->getAttribute( 'href' ) )['host'], $domains, true ) ) {
						array_push( $domains, wp_parse_url( $link->getAttribute( 'href' ) )['host'] );
					}
				}

				if ( ! is_null( $domains ) ) :
					?>
					<table class="widefat fixed" cellspacing="2">
						<thead>
						<tr>
							<th id="domain" scope="col"><?php esc_html_e( 'Domain', 'linkpizza-manager' ); ?></th>
							<th id="cb" scope="col"><?php esc_html_e( 'Tracking only *', 'linkpizza-manager' ); ?></th>
							<?php if ( 'empty' !== $disabled_domains && ! empty( $disabled_domains ) ) : ?>
								<th id="cb" scope="col"><?php esc_html_e( 'Disable', 'linkpizza-manager' ); ?>*</th>
							<?php endif; ?>
							<th id="domainMonetizable" scope="col"><?php esc_html_e( 'Monetizable **', 'linkpizza-manager' ); ?></th>
						</tr>
						</thead>
					<?php
					foreach ( $domains as $domain ) :
						?>
						<tr>
							<td>
							<?php
							echo esc_html( $domain );
							?>
							</td>
							<td>
								<input  class="widefat" type="checkbox" name="linkpizza-tracking-only-domains[]"
										value="<?php echo esc_attr( $domain ); ?>" <?php checked( true, in_array( $domain, $tracking_only_domains, true ) ); ?> />
							</td>
							<?php if ( 'empty' !== $disabled_domains && ! empty( $disabled_domains ) ) : ?>

								<td>
									<input  class="widefat" type="checkbox" name="linkpizza-disabled-domains[]"
											value="<?php echo esc_attr( $domain ); ?>" <?php checked( true, in_array( $domain, $disabled_domains, true ) ); ?> />
								</td>
							<?php endif; ?>
							<td>
								<?php
								$response = pzz_do_oauth_call_with_refresh_check( PZZ_OIDC_API_BASE_PATH . '/url/isMonetizable?url=http://' . $domain . '', array(), false );
								if ( $response ) :
									?>
									<img 	src="<?php echo esc_url( plugins_url( '/assets/dollar-sign-orange-small.png', __FILE__ ) ); ?>"
											alt="<?php esc_html_e( 'Monetized', 'linkpizza-manager' ); ?>"
											title="<?php esc_html_e( 'Monetized', 'linkpizza-manager' ); ?>"
											style="margin: -10px;padding-left: 25px;"/>
									<?php
								else :
									?>
									<img src="<?php echo esc_url( plugins_url( '/assets/dollar-sign-small.png', __FILE__ ) ); ?>"
											alt="<?php esc_html_e( 'Not Monetized', 'linkpizza-manager' ); ?>"
											title="<?php esc_html_e( 'Not Monetized', 'linkpizza-manager' ); ?>"
											style="margin: -10px;padding-left: 25px;"/>
									<?php
								endif;
								?>
							</td>
						</tr>
						<?php
					endforeach;
					?>
					</table>
					<div>
						<span><?php esc_html_e( "* Tracking only: Only track statistics but don't change this link to an affiliate link", 'linkpizza-manager' ); ?></span>
						<br/>
						<span><?php esc_html_e( '** Monetizable: LinkPizza can monetize this link automatically for you', 'linkpizza-manager' ); ?></span>
					</div>

					<?php
				endif;
				libxml_clear_errors();
			} else {
				?>
				<?php esc_html_e( "You haven't added any links to your blogpost, maybe you want to add some", 'linkpizza-manager' ); ?>
					<a 	href="https://linkpizza.com/about/our-advertisers"
						target="_blank"> <?php esc_html_e( 'advertiser links', 'linkpizza-manager' ); ?></a>  <?php esc_html_e( 'to monetize this post?', 'linkpizza-manager' ); ?>
				<?php
			}
		} else {
			?>
			<div class="warning">
			<?php esc_html_e( 'It seems your WordPress is missing php-xml, please ask your hosting to install it. Monetized links are disabled untill fixed.', 'linkpizza-manager' ); ?>
			</div>
			<?php
		}
		?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return int id of the post that was saved.
	 */
	public function save( $post_id ) {
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['linkPizza_post_enable_nonce'] ) ) {
			return $post_id;
		}

		$nonce = filter_input( INPUT_POST, 'linkPizza_post_enable_nonce' );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'linkPizza_post_custom_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$object_type = filter_input( INPUT_POST, 'post_type' );

		// Check the user's permissions.
		if ( 'page' === $object_type ) {
			if ( current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, its safe for us to save the data now. */
		if ( isset( $_POST['linkpizza-disabled'] ) ) {
			$linkpizza_disabled = '1';
		} else {
			$linkpizza_disabled = '';
		}

		if ( isset( $_POST['linkpizza-tracking-only'] ) ) {
			$tracking_only = '1';
		} else {
			$tracking_only = '';
		}

		if ( isset( $_POST['pzz_link_summary_disabled_post'] ) ) {
			$value_link_summary = '1';
		} else {
			$value_link_summary = '';
		}

		if ( isset( $_POST['linkpizza-disabled-domains'] ) ) {
			$custom   = filter_input( INPUT_POST, 'linkpizza-disabled-domains', FILTER_SANITIZE_URL, FILTER_REQUIRE_ARRAY );
			$old_meta = get_post_meta( $post_id, '_linkpizza_disabled_domains', true );
			// Update post meta.
			if ( ! empty( $old_meta ) ) {
				update_post_meta( $post_id, '_linkpizza_disabled_domains', $custom );
			} else {
				add_post_meta( $post_id, '_linkpizza_disabled_domains', $custom, true );
			}
		} else {
			update_post_meta( $post_id, '_linkpizza_disabled_domains', 'empty' );
		}

		if ( isset( $_POST['linkpizza-tracking-only-domains'] ) ) {
			$custom   = filter_input( INPUT_POST, 'linkpizza-tracking-only-domains', FILTER_SANITIZE_URL, FILTER_REQUIRE_ARRAY );
			$old_meta = get_post_meta( $post_id, '_linkpizza_tracking_only_domains', true );
			// Update post meta.
			if ( ! empty( $old_meta ) ) {
				update_post_meta( $post_id, '_linkpizza_tracking_only_domains', $custom );
			} else {
				add_post_meta( $post_id, '_linkpizza_tracking_only_domains', $custom, true );
			}
		} else {
			update_post_meta( $post_id, '_linkpizza_tracking_only_domains', 'empty' );
		}

		// Update the meta field.
		update_post_meta( $post_id, '_linkPizza_disabled', $linkpizza_disabled );
		update_post_meta( $post_id, '_linkPizza_tracking_only', $tracking_only );
		update_post_meta( $post_id, '_pzz_link_summary_disabled_post', $value_link_summary );
	}

	/**
	 * Register the scripts for the admin-facing side of the site.
	 *
	 * @since    4.5
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'chartist', '//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js', array( 'jquery' ), PZZ_VERSION, true );
		wp_enqueue_script( 'pzz-admin', plugins_url( '/js/admin.js', __FILE__ ), array( 'wp-color-picker' ), PZZ_VERSION, true );
		wp_enqueue_script( 'jquery-ui-datepicker' );
	}

	/**
	 * Register the stylesheets for the admin-facing side of the site.
	 *
	 * @since    4.5
	 * @return void
	 */
	public function admin_enqueue_style() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.8/themes/smoothness/jquery-ui.css', array(), PZZ_VERSION );
		wp_enqueue_style( 'pzz-admin', plugins_url( '/css/admin.css', __FILE__ ), array(), PZZ_VERSION );
	}

	/**
	 * Extends Public Post Preview preview time.
	 *
	 * @return integer extended preview time in seconds.
	 */
	public function pzz_increase_preview_nonce() {
		$extend_preview_time = get_option( 'pzz_extend_preview_time' );
		if ( '1' === $extend_preview_time ) {
			return 60 * 60 * 24 * 30; // 30 days
		} else {
			return 60 * 60 * 24 * 2; // 48 Hours ( Default ).
		}
	}

	/**
	 * Adds some links in the plugin list view
	 *
	 * @param array  $meta array of items to be displayed.
	 * @param string $file string with the current file name.
	 * @return array array of items.
	 */
	public static function plugin_row_meta( $meta, $file ) {
		if ( PZZ_BASE_FILE === $file ) {
			$meta[] = '<a href="admin.php?page=linkpizza-manager">' . __( 'Settings', 'linkpizza-manager' ) . '</a>';
			$meta[] = '<a href="https://linkpizza.com/signup" target="_blank">' . __( 'Sign up', 'linkpizza-manager' ) . '</a>';
		}
		return $meta;
	}

	/**
	 * Add column to pages/posts page showing is LinkPizza is visible
	 *
	 * @since    5.2
	 * @param array $columns array of columns on the page/post list overview page.
	 * @return array array with added columns.
	 */
	public function pzz_add_posts_pages_list_column( $columns ) {
		$columns['linkpizza'] = __( 'LinkPizza', 'linkpizza-manager' );
		return $columns;
	}

	/**
	 * Render column to pages/posts page showing is LinkPizza is visible
	 *
	 * @since    5.2
	 * @param string $column_name The name of the column.
	 * @param int    $id The id of the object.
	 * @return void
	 */
	public function pzz_render_posts_pages_list_column( $column_name, $id ) {
		switch ( $column_name ) {
			case 'linkpizza':
				$linkpizza_disabled      = get_post_meta( $id, '_linkPizza_disabled', true );
				$linkpizza_tracking_only = get_post_meta( $id, '_linkPizza_tracking_only', true );
				$disabled_domains        = get_post_meta( $id, '_linkpizza_disabled_domains', true );
				$tracking_only_domains   = get_post_meta( $id, '_linkpizza_tracking_only_domains', true );
				if ( $linkpizza_disabled || $linkpizza_tracking_only ) {
					echo 'Off';
				} elseif ( ! empty( $disabled_domains ) && 'empty' !== $disabled_domains ) {
					echo 'disabled: ' . wp_json_encode( $disabled_domains );
				} elseif ( ! empty( $tracking_only_domains ) && 'empty' !== $tracking_only_domains ) {
					echo 'tracking-only: ' . wp_json_encode( $tracking_only_domains );
				} else {
					echo 'On';
				}
				break;
		}
	}

	/**
	 * Registers bulk action dropdown addon.
	 *
	 * @since    5.2
	 * @param array $bulk_actions An array with the actions.
	 * @return array Actions
	 */
	public function register_pzz_bulk_actions( $bulk_actions ) {
		$bulk_actions['disable_linkpizza'] = __( 'Disable LinkPiza', 'linkpizza-manager' );
		$bulk_actions['enable_linkpizza']  = __( '(Re)enable LinkPiza', 'linkpizza-manager' );
		return $bulk_actions;
	}

	/**
	 * Handle bulk actions
	 *
	 * @since    5.2
	 * @param string $redirect_to URL to redirect to.
	 * @param string $action_name Name of the action.
	 * @param array  $post_ids Array with posts ids of the bulk action.
	 * @return string url to redirect to
	 */
	public function pzz_bulk_action_handler( $redirect_to, $action_name, $post_ids ) {
		if ( 'disable_linkpizza' === $action_name ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, '_linkPizza_disabled', '1' );
			}
			$redirect_to = add_query_arg( 'pzz_post_disabled', count( $post_ids ), $redirect_to );
			return $redirect_to;
		} elseif ( 'enable_linkpizza' === $action_name ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, '_linkPizza_disabled', '' );
			}
			$redirect_to = add_query_arg( 'pzz_post_enabled', count( $post_ids ), $redirect_to );
			return $redirect_to;
		} else {
			return $redirect_to;
		}
	}

	/**
	 * Adds admin notice for the bulk action.
	 *
	 * @return void
	 */
	public function pzz_bulk_action_admin_notice() {
		if ( ! empty( $_REQUEST['pzz_post_disabled'] ) ) {
			$post_count = filter_input( INPUT_POST, 'pzz_post_disabled', FILTER_SANITIZE_NUMBER_INT );
			if ( false !== $post_count ) {
				$post_count = filter_input( INPUT_GET, 'pzz_post_disabled', FILTER_SANITIZE_NUMBER_INT );
			}
			?>
			<div class="updated">
				<?php /* translators: %s is replaced with the post_count */ ?>
				<p><?php printf( esc_html__( 'LinkPizza disabled on %s posts/pages', 'linkpizza-manager' ), esc_html( $post_count ) ); ?></p>
			</div>
			<?php
		}
		if ( ! empty( $_REQUEST['pzz_post_enabled'] ) ) {
			$post_count = filter_input( INPUT_POST, 'pzz_post_disabled', FILTER_SANITIZE_NUMBER_INT );

			if ( false !== $post_count ) {
				$post_count = filter_input( INPUT_GET, 'pzz_post_disabled', FILTER_SANITIZE_NUMBER_INT );
			}
			?>
			<div class="updated">
				<?php /* translators: %s is replaced with the post_count */ ?>
				<p><?php printf( esc_html__( 'LinkPizza enabled on %s posts/pages', 'linkpizza-manager' ), esc_html( $post_count ) ); ?></p>
			</div>
			<?php
		}
	}
}
