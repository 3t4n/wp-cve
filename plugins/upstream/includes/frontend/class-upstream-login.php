<?php
/**
 * Handle frontend login
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UpStream_Login
 */
final class UpStream_Login {

	/**
	 * Represent the feedback message for the current action.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @var     string $feedback_message
	 */
	private $feedback_message = '';

	/**
	 * Class constructor.
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		$this->perform_user_login_action();
	}

	/**
	 * Handles the flow of the login/logout process.
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function perform_user_login_action() {
		$get_data                = isset( $_GET ) ? wp_unslash( $_GET ) : array();
		$post_data               = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$action                  = isset( $get_data['action'] ) ? sanitize_text_field( $get_data['action'] ) : null;
		$user_is_trying_to_login = isset( $post_data['login'] );

		if ( 'logout' === $action && ! $user_is_trying_to_login ) {
			self::do_destroy_session();
		} elseif ( $user_is_trying_to_login ) {
			if ( ! isset( $post_data['upstream_login_nonce'] ) || ! wp_verify_nonce(
				sanitize_text_field( $post_data['upstream_login_nonce'] ),
				'upstream-login-nonce'
			) ) {
				return false;
			}

			$data = $this->validate_log_in_post_data();

			if ( is_array( $data ) ) {
				$this->authenticate_data( $data );
			}
		}
	}

	/**
	 * Destroy user's session data.
	 *
	 * @since   1.9.0
	 * @static
	 */
	public static function do_destroy_session() {
		wp_logout();

		$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

		if ( session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['upstream'] ) ) {
			unset( $_SESSION['upstream'] );
		}

		if ( ! empty( $get_data ) && isset( $get_data['action'] ) && sanitize_text_field( $get_data['action'] ) === 'logout' ) {
			unset( $get_data['action'] );
		}
	}

	/**
	 * Validate the login form data by checking if a username and a password were provided.
	 * If data is valid, an array will be returned. The return will be FALSE otherwise.
	 *
	 * @since   1.9.0
	 * @access  private
	 *
	 * @return  array | bool
	 */
	private function validate_log_in_post_data() {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		if ( ! isset( $post_data['upstream_login_nonce'] ) || ! wp_verify_nonce(
			sanitize_text_field( $post_data['upstream_login_nonce'] ),
			'upstream-login-nonce'
		) ) {
			return false;
		}

		$post_data = array(
			'username' => isset( $post_data['user_email'] ) ? trim( sanitize_text_field( $post_data['user_email'] ) ) : '',
			'password' => isset( $post_data['user_password'] ) ? $post_data['user_password'] : '',
		);

		if ( empty( $post_data['username'] ) ) {
			$this->feedback_message = __( 'Email address/username field cannot be empty.', 'upstream' );
		} elseif ( strlen( $post_data['username'] ) < 3 ) {
			$this->feedback_message = __( 'Invalid email address/username.', 'upstream' );
		} else {
			if ( empty( $post_data['password'] ) ) {
				$this->feedback_message = __( 'Password field cannot be empty.', 'upstream' );
			} elseif ( strlen( $post_data['password'] ) < 2 ) {
				$this->feedback_message = __( 'Invalid email address and/or password.', 'upstream' );
			} else {
				return $post_data;
			}
		}

		return false;
	}

	/**
	 * Upstream Get Project Roles
	 */
	private function upstream_get_project_roles() {
		$options = (array) get_option( 'upstream_general' );

		if ( ! isset( $options['project_user_roles'] ) || empty( $options['project_user_roles'] ) ) {
			$roles = array(
				'upstream_manager',
				'upstream_user',
				'administrator',
			);
		} else {
			$roles = (array) $options['project_user_roles'];
		}

		$roles = apply_filters( 'upstream_user_roles_for_projects', $roles );

		return $roles;
	}


	/**
	 * Attempt to authenticate a user against the open project given current email address and password.
	 *
	 * @since   1.9.0
	 * @access  private
	 *
	 * @param   array $data An associative array containing an email (already sanitized) and a raw password.
	 * @throws  \Exception Exception.
	 *
	 * @return  bool
	 */
	private function authenticate_data( $data ) {
		try {
			if ( ! isset( $data['username'] ) || ! isset( $data['password'] ) ) {
				throw new \Exception( __( 'Invalid email address and/or password.', 'upstream' ) );
			}

			$user = get_user_by( 'email', $data['username'] );
			if ( empty( $user ) ) {
				$user = get_user_by( 'login', $data['username'] );
				if ( empty( $user ) ) {
					throw new \Exception( __( 'Invalid user/email address and/or password.', 'upstream' ) );
				}
			}

			$user_roles    = (array) $user->roles;
			$project_roles = array_merge( array( 'administrator', 'upstream_client_user', 'upstream_user', 'upstream_manager' ), $this->upstream_get_project_roles() );

			if ( count(
				array_intersect(
					$user_roles,
					$project_roles
				)
			) === 0 ) {
				throw new \Exception( __( "You don't have enough permissions to log in here.", 'upstream' ) );
			}

			$project_id   = (int) upstream_post_id();
			$can_continue = false;

			// Make sure he can be authenticated if he's an admin/manager.
			if ( count( array_intersect( $user_roles, array( 'administrator', 'upstream_manager' ) ) ) > 0 ) {
				$can_continue = true;
			} elseif ( upstream_is_clients_disabled() ) {
				throw new \Exception( __( 'Invalid email address and/or password.', 'upstream' ) );
			} else {
				// Check if he, as an UpStream User, is a current member of this project.
				if ( in_array( 'upstream_user', $user_roles, true ) ) {
					$meta_key_name = '_upstream_project_members';
				} else {
					// Check if he, as an UpStream Client User, is allowed to log in in this project.
					$meta_key_name = '_upstream_project_client_users';
				}

				$meta = (array) get_post_meta( $project_id, $meta_key_name );
				if ( count( $meta ) > 0 ) {
					$can_continue = in_array( (string) $user->ID, $meta[0] );
				}
			}

			if ( ! $can_continue ) {
				throw new \Exception( __( 'Sorry, you are not allowed to access this project.', 'upstream' ) );
			}

			$user = wp_signon(
				array(
					'user_login'    => $data['username'],
					'user_password' => $data['password'],
					'remember'      => false,
				)
			);

			if ( is_wp_error( $user ) ) {
				throw new \Exception( __( 'Invalid email address and/or password.', 'upstream' ) );
			}

			// Retrieve the project's client id.
			$client_id = (array) get_post_meta( $project_id, '_upstream_project_client' );
			if ( count( $client_id ) > 0 ) {
				$client_id = (int) $client_id[0];
			} else {
				$client_id = 0;
			}

			$_SESSION['upstream'] = array(
				'project_id' => $project_id,
				'client_id'  => $client_id,
				'user_id'    => $user->ID,
			);

			$project_permalink = get_the_permalink( $project_id );
			wp_save_redirect( esc_url( $project_permalink ) );

			return true;
		} catch ( \Exception $e ) {
			$this->feedback_message = $e->getMessage();

			return false;
		}
	}

	/**
	 * Return the current status of the user's login.
	 *
	 * @since   1.0.0
	 * @static
	 *
	 * @return  bool
	 */
	public static function user_is_logged_in() {
		if ( session_status() === PHP_SESSION_NONE ) {
			return false;
		}

		$user_is_logged_in = (
			isset( $_SESSION['upstream'] ) &&
			! empty( $_SESSION['upstream']['client_id'] ) &&
			! empty( $_SESSION['upstream']['user_id'] )
		);

		return $user_is_logged_in;
	}

	/**
	 * Check if there's a feedback message for the current action.
	 *
	 * @since   1.9.0
	 *
	 * @return  bool
	 */
	public function has_feedback_message() {
		$has_feedback_message = ! empty( $this->feedback_message );

		return $has_feedback_message;
	}

	/**
	 * Retrieve the feedback message for the current action.
	 *
	 * @since   1.9.0
	 *
	 * @return  string
	 */
	public function get_feedback_message() {
		$feedback_message = (string) $this->feedback_message;

		$this->feedback_message = '';

		return $feedback_message;
	}

	/**
	 * Logs the user out.
	 *
	 * @since   1.9.0
	 * @access  private
	 */
	private function do_log_out() {
		self::do_destroy_session();

		$this->feedback_message = __( 'You were just logged out.', 'upstream' );
	}
}
