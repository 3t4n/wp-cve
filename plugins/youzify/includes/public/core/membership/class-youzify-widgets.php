<?php

/**
 * Login Widget
 */
class Youzify_Login_Widget extends WP_Widget {

	function __construct() {


		parent::__construct(
			'youzify_login_widget',
			__( 'Youzify - Login', 'youzify' ),
			array( 'description' => __( 'Youzify login widget', 'youzify' ) )
		);

	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		global $Youzify_Membership;

		// Print Form
		echo '<div class="youzify-membership-login-widget">';
		$Youzify_Membership->form->get_form( 'login' );
		echo '</div>';

	}

	/**
	 * Login Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the login box', 'youzify' ) . '</p>';
	}

}

/**
 * Register Widget
 */

class Youzify_Register_Widget extends WP_Widget {

	function __construct() {

		parent::__construct(
			'youzify_register_widget',
			__( 'Youzify - Register', 'youzify' ),
			array( 'description' => __( 'Youzify register widget', 'youzify' ) )
		);

	}

	/**
	 * Register Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

    	// BuddyPress Registeration Process
		youzify_registration_process();

		// Print Form
		echo '<div class="youzify-membership-register-widget">';
		require_once YOUZIFY_TEMPLATE . 'membership/members/register.php';
		echo '</div>';

	}

	/**
	 * Register Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the register box', 'youzify' ) . '</p>';
	}

}

/**
 * Reset Password Widget
 */

class Youzify_Reset_Password_Widget extends WP_Widget {

	function __construct() {

		parent::__construct(
			'youzify_reset_password_widget',
			__( 'Youzify - Reset Password', 'youzify' ),
			array( 'description' => __( 'Youzify reset password widget', 'youzify' ) )
		);

	}

	/**
	 * Reset Password Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		global $Youzify_Membership;

		// Print Form
		echo '<div class="youzify-membership-reset-password-widget">';
		$Youzify_Membership->form->get_form( 'lost_password' );
		echo '</div>';
	}

	/**
	 * Reset Password Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the reset password box', 'youzify' ) . '</p>';
	}

}