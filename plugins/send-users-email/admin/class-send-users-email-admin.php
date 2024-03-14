<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Send_Users_Email_Admin {
	private $plugin_name;
	private $version;
	public static $social = array(
		'facebook',
		'instagram',
		'linkedin',
		'skype',
		'tiktok',
		'twitter',
		'youtube'
	);
	/**
	 * Add all admin page slugs here ...
	 */
	private $plugin_pages_slug = array(
		'send-users-email',
		'send-users-email-users',
		'send-users-email-roles',
		'send-users-email-settings',
		'send-users-email-pro-features',
		'send-users-email-email-templates',
		'send-users-email-email-queue',
		'send-users-email-error-logs',
		'send-users-email-user-groups',
		'send-users-email-groups'
	);

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		// Add css to this plugin page only
		$page = ( isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "" );

		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_style(
				'sue-bootstrap-5',
				plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css',
				array(),
				'5.2.2',
				'all'
			);
			wp_enqueue_style(
				'sue-bootstrap-5-datatable',
				plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap5.min.css',
				array( 'sue-bootstrap-5' ),
				'1.11.2',
				'all'
			);
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/send-users-email-admin.css',
				array(),
				$this->version,
				'all'
			);
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		// Add JS to this plugin page only
		$page = ( isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "" );

		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_script(
				'bootstrap-js',
				plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
				array( 'jquery' ),
				'5.1.1',
				true
			);
			wp_enqueue_script(
				'datatable-js',
				plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js',
				array( 'jquery' ),
				'1.11.2',
				true
			);
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/send-users-email-admin.js',
				array( 'jquery' ),
				$this->version,
				true
			);
		}

	}

	/**
	 * Register admin menu Items
	 */
	public function admin_menu() {
		add_menu_page(
			__( "Send Users Email", "send-users-email" ),
			__( "Email to Users", "send-users-email" ),
			SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY,
			'send-users-email',
			[ $this, 'admin_dashboard' ],
			'dashicons-email-alt2',
			250
		);
		add_submenu_page(
			'send-users-email',
			__( 'Dashboard', "send-users-email" ),
			__( 'Dashboard', "send-users-email" ),
			SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY,
			'send-users-email',
			[ $this, 'admin_dashboard' ]
		);
		add_submenu_page(
			'send-users-email',
			__( 'Email Users', "send-users-email" ),
			__( 'Email Users', "send-users-email" ),
			SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY,
			'send-users-email-users',
			[ $this, 'users_email' ]
		);
		add_submenu_page(
			'send-users-email',
			__( 'Email Roles', "send-users-email" ),
			__( 'Email Roles', "send-users-email" ),
			SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY,
			'send-users-email-roles',
			[ $this, 'roles_email' ]
		);
		add_submenu_page(
			'send-users-email',
			__( 'Settings', "send-users-email" ),
			__( 'Settings', "send-users-email" ),
			'manage_options',
			'send-users-email-settings',
			[ $this, 'settings' ]
		);
		add_submenu_page(
			'send-users-email',
			__( 'PRO Features', "send-users-email" ),
			__( 'PRO Features', "send-users-email" ),
			SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY,
			'send-users-email-pro-features',
			[ $this, 'pro_features' ]
		);
		add_submenu_page(
			'send-users-email',
			__( 'Email & Error Log', "send-users-email" ),
			__( 'Email & Error Log', "send-users-email" ),
			'manage_options',
			'send-users-email-error-logs',
			[ $this, 'email_and_error_log' ]
		);
	}

	/**
	 * Admin Dashboard page
	 */
	public function admin_dashboard() {
		$users = count_users();
		require_once 'partials/admin-dashboard.php';
	}

	/**
	 * Admin pro features page
	 */
	public function pro_features() {
		require_once 'partials/admin-pro-features.php';
	}

	/**
	 * Handle Email send selecting users
	 */
	public function users_email() {
		$users       = count_users();
		$total_users = $users['total_users'];
		// Get system users
		$templates  = [];
		$blog_users = get_users( array(
			'fields' => array(
				'ID',
				'display_name',
				'user_email',
				'user_login'
			),
		) );
		require_once 'partials/users-email.php';
	}

	/**
	 * Handle Email send selecting roles
	 */
	public function roles_email() {
		$users     = count_users();
		$roles     = $users['avail_roles'];
		$templates = [];
		require_once 'partials/roles-email.php';
	}

	/**
	 * Settings page
	 */
	public function settings() {
		$options              = get_option( 'sue_send_users_email' );
		$logo                 = $options['logo_url'] ?? '';
		$title                = $options['email_title'] ?? '';
		$tagline              = $options['email_tagline'] ?? '';
		$footer               = $options['email_footer'] ?? '';
		$email_from_name      = $options['email_from_name'] ?? '';
		$email_from_address   = $options['email_from_address'] ?? '';
		$reply_to_address     = $options['reply_to_address'] ?? '';
		$email_template_style = $options['email_template_style'] ?? '';
		$roles                = sue_get_roles( [ 'administrator' ] );
		$selected_roles       = sue_get_selected_roles();
		$social               = $options['social'] ?? [];
		require_once 'partials/settings.php';
	}

	/**
	 * Handle Error and Email log page
	 */
	public function email_and_error_log() {
		// Error log file setup
		$errorLog              = null;
		$errorLogSize          = 0;
		$errorFileSizeMB       = 0;
		$errorFileMaxSizeLimit = 8;
		$errorLogFileName      = sue_get_error_log_filename();

		if ( $errorLogFileName && file_exists( sue_log_path( $errorLogFileName ) ) ) {
			$errorLogSize    = filesize( sue_log_path( $errorLogFileName ) );
			$errorFileSizeMB = sue_bytes_to_mb( $errorLogSize );
			$errorLog        = file_get_contents( sue_log_path( $errorLogFileName ) );
		}

		// Delete old log files
		Send_Users_Email_cleanup::cleanEmailLogFiles();
		// Get email log files
		$emailLogFiles = array_diff( scandir( sue_log_path() ), array(
			'..',
			'.',
			$errorLogFileName,
			'.htaccess'
		) );
		$emailLogFiles = sue_remove_non_email_log_filename( $emailLogFiles );
		$emailLogFiles = array_reverse( $emailLogFiles );
		require_once 'partials/email_and_error_log.php';
	}

	/**
	 * Handles request to send user email selecting users
	 */
	public function handle_ajax_admin_user_email() {

		if ( check_admin_referer( 'sue-email-user' ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'send_email_user' && $action == 'sue_user_email_ajax' ) {
				$subject        = ( isset( $_REQUEST['subject'] ) ? sanitize_text_field( $_REQUEST['subject'] ) : "" );
				$message        = ( isset( $_REQUEST['sue_user_email_message'] ) ? wp_kses_post( $_REQUEST['sue_user_email_message'] ) : "" );
				$users          = $_REQUEST['users'] ?? [];
				$users          = array_map( 'sanitize_text_field', $users );
				$email_style    = 'default';
				$message        = sue_remove_caption_shortcode( $message );
				$resMessage     = __( '🚀🚀🚀 Email(s) sent successfully!', 'send-users-email' );
				$warningMessage = '';
				// Validate inputs
				$validation_message = [];
				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.',
						"send-users-email" );
				}
				if ( empty( $message ) ) {
					$validation_message['message'] = __( 'Please provide email content.', "send-users-email" );
				}
				if ( empty( $users ) ) {
					$validation_message['sue-user-email-datatable'] = __( 'Please select users.', "send-users-email" );
				}
				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array(
						'errors'  => $validation_message,
						'success' => false,
					), 422 );
				}
				// Cleanup email progress record
				Send_Users_Email_cleanup::cleanupUserEmailProgress();

				if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
					$current_user_id     = get_current_user_id();
					$total_email_send    = 0;
					$total_email_to_send = count( $users );
					$total_failed_email  = 0;
					$options             = get_option( 'sue_send_users_email' );
					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}
					$options                                                          = get_option( 'sue_send_users_email' );
					$options[ 'email_users_total_email_send_' . $current_user_id ]    = $total_email_send;
					$options[ 'email_users_total_email_to_send_' . $current_user_id ] = $total_email_to_send;
					update_option( 'sue_send_users_email', $options );
					$user_details = get_users( [
						'include' => $users,
						'fields'  => [
							'ID',
							'display_name',
							'user_email',
							'user_login'
						],
					] );
					// Email header setup
					$headers = $this->get_email_headers();
					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$username     = $user->user_login;
						$display_name = $user->display_name;
						$user_email   = sanitize_email( $user->user_email );
						$user_id      = (int) $user->ID;
						$user_meta    = get_user_meta( $user->ID );
						$first_name   = $user_meta['first_name'][0] ?? '';
						$last_name    = $user_meta['last_name'][0] ?? '';
						// Replace placeholder with user content
						$email_body    = $this->replace_placeholder(
							$email_body,
							$username,
							$display_name,
							$first_name,
							$last_name,
							$user_email,
							$user_id
						);
						$email_subject = stripslashes_deep( $subject );
						// Send email
						$email_template = $this->email_template( $email_body, $email_style );

						if ( ! wp_mail(
							$user_email,
							$email_subject,
							$email_template,
							$headers
						) ) {
							$total_failed_email ++;
						} else {
							sue_log_sent_emails( $user_email, $email_subject, $email_body );
						}

						$email_body     = '';
						$email_template = '';
						$total_email_send ++;
						$options[ 'email_users_total_email_send_' . $current_user_id ] = $total_email_send;
						update_option( 'sue_send_users_email', $options );
					}
					// Cleanup email progress record
					Send_Users_Email_cleanup::cleanupUserEmailProgress();
					if ( $total_failed_email > 0 ) {
						$warningMessage = 'Plugin tried to send ' . count( $users ) . ' ' . _n( 'email', 'emails',
								count( $users ) ) . ' but ' . $total_failed_email . ' ' . _n( 'email', 'emails',
								$total_failed_email ) . ' failed to send. Please check logs for possible errors.';
					}
					wp_send_json( array(
						'message' => $resMessage,
						'success' => true,
						'warning' => $warningMessage,
					), 200 );
				}

			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

	/**
	 * Handle users email progress
	 */
	public function handle_ajax_email_users_progress() {

		if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'send_email_user_progress' && $action == 'sue_email_users_progress' ) {
				$user_id             = get_current_user_id();
				$options             = get_option( 'sue_send_users_email' );
				$total_email_send    = $options[ 'email_users_total_email_send_' . $user_id ];
				$total_email_to_send = $options[ 'email_users_total_email_to_send_' . $user_id ];
				$progress            = ( $total_email_to_send ? floor( $total_email_send / $total_email_to_send * 100 ) : 0 );
				wp_send_json( array(
					'progress' => $progress,
				), 200 );
			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

	/**
	 * Handles Ajax request to send user email selecting users
	 */
	public function handle_ajax_admin_role_email() {

		if ( check_admin_referer( 'sue-email-user' ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'send_email_role' && $action == 'sue_role_email_ajax' ) {
				$subject        = ( isset( $_REQUEST['subject'] ) ? sanitize_text_field( $_REQUEST['subject'] ) : "" );
				$message        = ( isset( $_REQUEST['sue_user_email_message'] ) ? wp_kses_post( $_REQUEST['sue_user_email_message'] ) : "" );
				$roles          = $_REQUEST['roles'] ?? [];
				$roles          = array_map( 'sanitize_text_field', $roles );
				$email_style    = 'default';
				$message        = sue_remove_caption_shortcode( $message );
				$roles_string   = implode( ', ', $roles );
				$resMessage     = __( '🚀🚀🚀 Email(s) sent successfully!', 'send-users-email' );
				$warningMessage = '';
				// Validate inputs
				$validation_message = [];
				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.',
						"send-users-email" );
				}
				if ( empty( $message ) ) {
					$validation_message['message'] = __( 'Please provide email content.', "send-users-email" );
				}
				if ( empty( $roles ) ) {
					$validation_message['sue-role-email-list'] = __( 'Please select role(s).', "send-users-email" );
				}
				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array(
						'errors'  => $validation_message,
						'success' => false,
					), 422 );
				}
				// Cleanup email progress record
				Send_Users_Email_cleanup::cleanupRoleEmailProgress();

				if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
					$current_user_id     = get_current_user_id();
					$total_email_send    = 0;
					$total_failed_email  = 0;
					$user_details        = get_users( array(
						'fields'   => array(
							'ID',
							'display_name',
							'user_email',
							'user_login'
						),
						'role__in' => $roles,
					) );
					$total_email_to_send = count( $user_details );
					$options             = get_option( 'sue_send_users_email' );
					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}
					$options                                                          = get_option( 'sue_send_users_email' );
					$options[ 'email_roles_total_email_send_' . $current_user_id ]    = $total_email_send;
					$options[ 'email_roles_total_email_to_send_' . $current_user_id ] = $total_email_to_send;
					update_option( 'sue_send_users_email', $options );
					// Email header setup
					$headers = $this->get_email_headers();
					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$username     = $user->user_login;
						$display_name = $user->display_name;
						$user_email   = sanitize_email( $user->user_email );
						$user_id      = (int) $user->ID;
						$user_meta    = get_user_meta( $user->ID );
						$first_name   = $user_meta['first_name'][0] ?? '';
						$last_name    = $user_meta['last_name'][0] ?? '';
						// Replace placeholder with user content
						$email_body    = $this->replace_placeholder(
							$email_body,
							$username,
							$display_name,
							$first_name,
							$last_name,
							$user_email,
							$user_id
						);
						$email_subject = stripslashes_deep( $subject );
						// Send email
						$email_template = $this->email_template( $email_body, $email_style );

						if ( ! wp_mail(
							$user_email,
							$email_subject,
							$email_template,
							$headers
						) ) {
							$total_failed_email ++;
						} else {
							sue_log_sent_emails( $user_email, $email_subject, $email_body );
						}

						$email_body     = '';
						$email_template = '';
						$total_email_send ++;
						$options[ 'email_roles_total_email_send_' . $current_user_id ] = $total_email_send;
						update_option( 'sue_send_users_email', $options );
					}
					// Cleanup email progress record
					Send_Users_Email_cleanup::cleanupRoleEmailProgress();
					if ( $total_failed_email > 0 ) {
						$warningMessage = 'Plugin tried to send ' . $total_email_to_send . ' ' . _n( 'email', 'emails',
								$total_email_to_send ) . ' but ' . $total_failed_email . ' ' . _n( 'email', 'emails',
								$total_failed_email ) . ' failed to send. Please check logs for possible errors.';
					}
					wp_send_json( array(
						'message' => $resMessage,
						'success' => true,
						'warning' => $warningMessage,
					), 200 );
				}

			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

	/**
	 * Handle users email progress
	 */
	public function handle_ajax_email_roles_progress() {

		if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'send_email_role_progress' && $action == 'sue_email_roles_progress' ) {
				$user_id             = get_current_user_id();
				$options             = get_option( 'sue_send_users_email' );
				$total_email_send    = $options[ 'email_roles_total_email_send_' . $user_id ];
				$total_email_to_send = $options[ 'email_roles_total_email_to_send_' . $user_id ];
				$progress            = ( $total_email_to_send ? floor( $total_email_send / $total_email_to_send * 100 ) : 0 );
				wp_send_json( array(
					'progress' => $progress,
				), 200 );
			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

	/**
	 * Email template
	 */
	private function email_template( $email_body, $style = 'default' ) {
		ob_start();
		$options = get_option( 'sue_send_users_email' );
		$logo    = $options['logo_url'] ?? '';
		$title   = $options['email_title'] ?? '';
		$tagline = $options['email_tagline'] ?? '';
		$footer  = $options['email_footer'] ?? '';
		$styles  = $options['email_template_style'] ?? '';
		$social  = $options['social'] ?? [];
		if ( ! $style ) {
			$style = 'default';
		}
		require 'partials/email-template.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Plugin settings
	 */
	public function handle_ajax_admin_settings() {

		if ( check_admin_referer( 'sue-email-user' ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'sue_settings' && $action == 'sue_settings_ajax' ) {
				$logo                 = ( isset( $_REQUEST['logo'] ) ? esc_url_raw( $_REQUEST['logo'] ) : "" );
				$title                = ( isset( $_REQUEST['title'] ) ? sanitize_text_field( $_REQUEST['title'] ) : "" );
				$tagline              = ( isset( $_REQUEST['tagline'] ) ? sanitize_text_field( $_REQUEST['tagline'] ) : "" );
				$footer               = ( isset( $_REQUEST['footer'] ) ? wp_kses_post( $_REQUEST['footer'] ) : "" );
				$email_from_name      = ( isset( $_REQUEST['email_from_name'] ) ? sanitize_text_field( $_REQUEST['email_from_name'] ) : "" );
				$email_from_address   = ( isset( $_REQUEST['email_from_address'] ) ? sanitize_text_field( $_REQUEST['email_from_address'] ) : "" );
				$reply_to_address     = ( isset( $_REQUEST['reply_to_address'] ) ? sanitize_text_field( $_REQUEST['reply_to_address'] ) : "" );
				$email_template_style = ( isset( $_REQUEST['email_template_style'] ) ? sanitize_text_field( $_REQUEST['email_template_style'] ) : "" );
				$selected_roles       = $_REQUEST['email_send_roles'] ?? [];
				$socials              = $_REQUEST['social'] ?? [];
				// Validate inputs
				$validation_message = [];
				if ( ! empty( $logo ) && ! wp_http_validate_url( $logo ) ) {
					$validation_message['logo'] = __( 'Please provide valid image URL..', "send-users-email" );
				}
				if ( ! empty( $title ) && strlen( $title ) <= 2 ) {
					$validation_message['title'] = __( 'Please provide a bit more title.', "send-users-email" );
				}
				if ( ! empty( $tagline ) && strlen( $tagline ) <= 4 ) {
					$validation_message['tagline'] = __( 'Please provide a bit more tagline.', "send-users-email" );
				}
				if ( ! empty( $footer ) && strlen( $footer ) <= 4 ) {
					$validation_message['footer'] = __( 'Please provide a bit more footer content.',
						"send-users-email" );
				}
				if ( ! empty( $email_from_name ) && strlen( $email_from_name ) <= 2 ) {
					$validation_message['email_from_name'] = __( 'Please provide a bit more email from Name.',
						"send-users-email" );
				}
				if ( ! empty( $email_from_address ) && ! filter_var( $email_from_address, FILTER_VALIDATE_EMAIL ) ) {
					$validation_message['email_from_address'] = __( 'Please provide a valid email from address.',
						"send-users-email" );
				}
				if ( ! empty( $reply_to_address ) && ! filter_var( $reply_to_address, FILTER_VALIDATE_EMAIL ) ) {
					$validation_message['reply_to_address'] = __( 'Please provide a valid reply to address.',
						"send-users-email" );
				}
				if ( ! empty( $email_outgoing_rate ) && ! is_integer( $email_outgoing_rate ) && $email_outgoing_rate < 1 ) {
					$validation_message['email_outgoing_rate'] = __( 'Please provide a valid positive number.',
						"send-users-email" );
				}
				if ( ! empty( $sent_email_save_for ) && ! is_integer( $sent_email_save_for ) && $sent_email_save_for < 0 ) {
					$validation_message['sent_email_save_for'] = __( 'Please provide a valid number. Can be zero or greater.',
						"send-users-email" );
				}
				if ( ! empty( $email_from_address ) && empty( $email_from_name ) ) {
					$validation_message['email_from_name'] = __( 'Please provide a valid email from name.',
						"send-users-email" );
				}
				if ( ! empty( $reply_to_address ) && empty( $email_from_name ) ) {
					$validation_message['email_from_name'] = __( 'Please provide a valid email from name.',
						"send-users-email" );
				}
				if ( ! empty( $save_email_log_till_days ) && $save_email_log_till_days < 1 ) {
					$validation_message['save_email_log_till_days'] = __( 'Please provide a valid positive number.',
						"send-users-email" );
				}
				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array(
						'errors'  => $validation_message,
						'success' => false,
					), 422 );
				}

				if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
					$options = get_option( 'sue_send_users_email' );
					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}
					$options                         = get_option( 'sue_send_users_email' );
					$options['logo_url']             = esc_url_raw( $logo );
					$options['email_title']          = stripslashes_deep( wp_strip_all_tags( $title ) );
					$options['email_tagline']        = stripslashes_deep( wp_strip_all_tags( $tagline ) );
					$options['email_footer']         = stripslashes_deep( $footer );
					$options['email_from_name']      = stripslashes_deep( wp_strip_all_tags( $email_from_name ) );
					$options['email_from_address']   = stripslashes_deep( wp_strip_all_tags( $email_from_address ) );
					$options['reply_to_address']     = stripslashes_deep( wp_strip_all_tags( $reply_to_address ) );
					$options['email_template_style'] = stripslashes_deep( wp_strip_all_tags( $email_template_style ) );
					// Roles array adjustments
					$roles = '';
					foreach ( $selected_roles as $selected_role ) {
						$roles .= wp_strip_all_tags( $selected_role ) . ',';
					}
					$roles = rtrim( $roles, ',' );
					// Social media links
					$socialMedias = [];
					foreach ( $socials as $platform => $url ) {
						$platform = sanitize_text_field( $platform );
						$url      = sanitize_text_field( $url );
						if ( ! empty( $url ) ) {
							$socialMedias[ $platform ] = $url;
						}
					}
					$options['email_send_roles'] = $roles;
					$options['social']           = $socialMedias;
					update_option( 'sue_send_users_email', $options );
					// Add or remove email capacity of role
					sue_add_email_capability_to_roles( $roles );
					wp_send_json( array(
						'message' => 'success',
						'success' => true,
					), 200 );
				}

			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

	/**
	 * Replace placeholder text to content
	 */
	private function replace_placeholder(
		$email_body,
		$username,
		$display_name,
		$first_name,
		$last_name,
		$user_email,
		$user_id
	) {
		$email_body = str_replace( '{{username}}', $username, $email_body );
		$email_body = str_replace( '{{user_display_name}}', $display_name, $email_body );
		$email_body = str_replace( '{{user_first_name}}', $first_name, $email_body );
		$email_body = str_replace( '{{user_last_name}}', $last_name, $email_body );
		$email_body = str_replace( '{{user_email}}', $user_email, $email_body );
		$email_body = str_replace( '{{user_id}}', $user_id, $email_body );

		return wpautop( $email_body );
	}

	/**
	 * @return array
	 */
	private function get_email_headers() {
		$headers[]          = 'Content-Type: text/html; charset=UTF-8';
		$options            = get_option( 'sue_send_users_email' );
		$email_from_name    = $options['email_from_name'] ?? '';
		$email_from_address = $options['email_from_address'] ?? '';
		$reply_to_address   = $options['reply_to_address'] ?? '';
		if ( ! empty( $email_from_name ) && ! empty( $email_from_address ) ) {
			$headers[] = "From: {$email_from_name} <{$email_from_address}>";
		}
		if ( ! empty( $email_from_name ) && ! empty( $reply_to_address ) ) {
			$headers[] = "Reply-To: {$email_from_name} <{$reply_to_address}>";
		}

		return $headers;
	}

	/**
	 * Assign capability to send email to admin users automatically if it is not there
	 */
	public function check_administrator_capability() {
		global $current_user;
		$user_roles = $current_user->roles;
		if ( in_array( 'administrator', $user_roles ) ) {

			if ( ! current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
				$role = get_role( 'administrator' );
				if ( $role ) {
					$role->add_cap( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY );
				}
			}

		}
		// Temporarily cleanup script for user progress bug
		if ( get_option( 'sue_db_version' ) ) {

			if ( get_option( 'sue_db_version' ) != SEND_USERS_EMAIL_VERSION ) {
				$options     = get_option( 'sue_send_users_email' );
				$optionsKeys = [
					'email_users_email_send_start_',
					'email_users_total_email_send_',
					'email_users_total_email_to_send_',
					'email_roles_email_send_start_',
					'email_roles_total_email_send_',
					'email_roles_total_email_to_send_'
				];

				if ( is_array( $options ) ) {
					foreach ( $options as $key => $value ) {
						foreach ( $optionsKeys as $options_key ) {
							if ( strpos( $key, $options_key ) === 0 ) {
								unset( $options[ $key ] );
							}
						}
					}
					update_option( 'sue_send_users_email', $options );
				}

			}

		}
	}

	/**
	 * @param $wp_error
	 *  Log wp_mail error to file
	 *
	 * @return void
	 */
	public function handle_wp_mail_failed_action( $wp_error ) {
		$smtp_error = null;
		$to         = null;
		$subject    = null;

		if ( ! empty( $wp_error ) ) {
			$errors = $wp_error->errors ?? null;

			if ( $errors ) {
				$wp_mail_failed = $errors['wp_mail_failed'] ?? null;
				$smtp_error     = $wp_mail_failed[0] ?? null;
			}

			$error_data = $wp_error->error_data ?? null;

			if ( $error_data ) {
				$smtp_wp_mail_failed = $error_data['wp_mail_failed'] ?? null;

				if ( $smtp_wp_mail_failed ) {
					$toArr = $smtp_wp_mail_failed['to'] ?? null;
					if ( is_array( $toArr ) ) {
						$to = implode( ', ', $toArr );
					}
					$subject = $smtp_wp_mail_failed['subject'] ?? null;
				}

			}

			$message = '[' . date( 'Y-m-d h:i:s' ) . ']: ';
			$message .= 'ERROR';
			if ( $smtp_error ) {
				$message .= ' | ' . $smtp_error;
			}
			if ( $to ) {
				$message .= ' | To: ' . sue_obscure_text( $to );
			}
			if ( $subject ) {
				$message .= ' | Subject: ' . strip_tags( $subject );
			}
			sue_log_wp_mail_failed_error( $message );
		}

	}

	/**
	 *  Delete error log file
	 * @return void
	 */
	public function delete_error_log() {
		if ( isset( $_POST['sue_delete_error_log'] ) ) {
			if ( check_admin_referer( 'sue-delete-error-log' ) ) {
				Send_Users_Email_cleanup::cleanErrorLogFile();
			}
		}
	}

	/**
	 *  Truncate emails table clearing out all sent and unsent emails
	 * @return void
	 */
	public function delete_all_queued_emails() {
		if ( isset( $_POST['sue_delete_all_queued_emails'] ) ) {
			if ( check_admin_referer( 'sue-delete-all-queued-emails' ) ) {
				SUE_Emails::truncateQueueTable();
			}
		}
	}

	/**
	 *  View email log data
	 * @return void
	 */
	public function handle_ajax_view_email_log() {

		if ( check_admin_referer( 'sue-email-log-view' ) ) {
			$param  = ( isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "" );
			$action = ( isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "" );

			if ( $param == 'sue_view_email_log' && $action == 'sue_view_email_log_ajax' ) {
				$filename = ( isset( $_REQUEST['sue_view_email_log_file'] ) ? sanitize_text_field( $_REQUEST['sue_view_email_log_file'] ) : "" );
				$filename = sanitize_file_name( $filename );

				if ( current_user_can( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY ) ) {
					$emailLog     = file_get_contents( sue_log_path( $filename ) );
					$emailLogSize = sue_bytes_to_mb( filesize( sue_log_path( $filename ) ) );
					wp_send_json( array(
						'message'  => $emailLog,
						'filesize' => (double) $emailLogSize,
						'success'  => true,
					), 200 );
				}

			}

		}

		wp_send_json( array(
			'message' => 'Permission Denied',
			'success' => false,
		), 200 );
	}

}