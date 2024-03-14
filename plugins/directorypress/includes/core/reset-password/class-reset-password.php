<?php
class directorypress_pass_reset_form {
	
	public function __construct() {

		// Redirects
		add_action( 'login_form_rp', array( $this, 'redirect_to_directorypress_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_directorypress_password_reset' ) );

		// Handlers for form posting actions
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		// Other customizations
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

		// Setup
		add_shortcode( 'directorypress-password-reset-form', array( $this, 'render_password_reset_form' ) );
	}

	/**
	 * Plugin activation hook.
	 *
	 * Creates all WordPress pages needed by the plugin.
	 */
	public static function plugin_activated() {
		// Information needed for creating the plugin's pages
		$page_definitions = array(
			'user-password-reset' => array(
				'title' => __( 'Pick a New Password', 'DIRECTORYPRESS' ),
				'content' => '[directorypress-password-reset-form]'
			)
		);

		foreach ( $page_definitions as $slug => $page ) {
			// Check that the page doesn't exist already
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above
				wp_insert_post(
					array(
						'post_content'   => $page['content'],
						'post_name'      => $slug,
						'post_title'     => $page['title'],
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
					)
				);
			}
		}
	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_directorypress_password_reset() {
		global $DIRECTORYPRESS_ADMIN_SETTINGS;
		$login_url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']): wp_login_url();
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( $login_url .'?login=expiredkey' );
				} else {
					wp_redirect( $login_url .'?login=invalidkey' );
				}
				exit;
			}
			
			$redirect_url = home_url( 'member-password-reset' );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'DIRECTORYPRESS' );
		} else {
			if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
				$attributes['login'] = sanitize_text_field($_REQUEST['login']);
				$attributes['key'] = sanitize_text_field($_REQUEST['key']);

				// Error messages
				$errors = array();
				if ( isset( $_REQUEST['error'] ) ) {
					$error_codes = explode( ',', esc_attr($_REQUEST['error']) );

					foreach ( $error_codes as $code ) {
						$errors []= $this->get_error_message( $code );
					}
				}
				$attributes['errors'] = $errors;

				return $this->get_template_html( 'password_reset_form', $attributes );
			} else {
				return __( 'Invalid password reset link.', 'DIRECTORYPRESS' );
			}
		}
	}

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
		if ( ! $attributes ) {
			$attributes = array();
		}

		ob_start();

		do_action( 'personalize_login_before_' . $template_name );

		require( '_html/' . $template_name . '.php');

		do_action( 'personalize_login_after_' . $template_name );

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	//
	// ACTION HANDLERS FOR FORMS IN FLOW
	//

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		global $DIRECTORYPRESS_ADMIN_SETTINGS;
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$rp_key = sanitize_text_field($_REQUEST['rp_key']);
			$rp_login = sanitize_text_field($_REQUEST['rp_login']);

			$user = check_password_reset_key( $rp_key, $rp_login );
			
			if ( ! $user || is_wp_error( $user ) ) {
				
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					$redirect_url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']): wp_lostpassword_url();
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'expiredkey', $redirect_url );
					wp_redirect( $redirect_url );
					
				} else {
					$redirect_url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']): wp_lostpassword_url();
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'invalidkey', $redirect_url );
					wp_redirect( $redirect_url );
				}
				exit;
			}

			if ( isset( $_POST['pass1'] ) ) {
				
				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					// Passwords don't match
					$redirect_url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']): wp_lostpassword_url();

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

					wp_redirect( $redirect_url );
					exit;
				}

				if ( empty( $_POST['pass1'] ) ) {
					// Password is empty
					$redirect_url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_password_reset_page']): wp_lostpassword_url();

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

					wp_redirect( $redirect_url );
					exit;

				}

				
				$url = (isset($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']) && !empty($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']))? get_permalink($DIRECTORYPRESS_ADMIN_SETTINGS['directorypress_submit_login_page']): home_url('/');
				reset_password( $user, $_POST['pass1'] );
				wp_redirect( $url.'?password=changed' );
			} else {
				echo "Invalid request.";
			}

			exit;
		}
	}


	//
	// OTHER CUSTOMIZATIONS
	//

	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
		// Create new message
		$msg  = __( 'Hello!', 'DIRECTORYPRESS' ) . "\r\n\r\n";
		$msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'DIRECTORYPRESS' ), $user_login ) . "\r\n\r\n";
		$msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'DIRECTORYPRESS' ) . "\r\n\r\n";
		$msg .= __( 'To reset your password, visit the following address:', 'DIRECTORYPRESS' ) . "\r\n\r\n";
		$msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
		$msg .= __( 'Thanks!', 'DIRECTORYPRESS' ) . "\r\n";

		return $msg;
	}


	//
	// HELPER FUNCTIONS
	//

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
		switch ( $error_code ) {
			// Reset password

			case 'expiredkey':
				return __( 'The password reset link you used is not valid anymore. Please request new one ', 'DIRECTORYPRESS' ). '<a href="'. home_url( $pass_reset_slug ).'" >'.esc_html__('here', 'DIRECTORYPRESS').'</a>';
				
			case 'invalidkey':
				return __( 'The password reset link you used is not valid anymore. Please request new one ', 'DIRECTORYPRESS' ). '<a href="'. home_url( $pass_reset_slug ).'" >'.esc_html__('here', 'DIRECTORYPRESS').'</a>';

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'DIRECTORYPRESS' );

			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'DIRECTORYPRESS' );

			default:
				break;
		}

		return __( 'An unknown error occurred. Please try again later.', 'DIRECTORYPRESS' );
	}
	

}

// Initialize the plugin
$directorypress_pass_reset_form = new directorypress_pass_reset_form();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'directorypress_pass_reset_form', 'plugin_activated' ) );