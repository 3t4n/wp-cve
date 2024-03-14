<?php

class Youzify_Membership_Form {

	function __construct() {

    	// Add "[youzify_complete_registration_page]" Shortcode.
		add_shortcode( 'youzify_complete_registration_page', array( $this, 'get_complete_registration_form' ) );

	}

	/**
	 * Get Complete Registration form.
	 */
	public function get_complete_registration_form() {
		// Render the form.
		return $this->get_page( 'complete_registration' );
	}

	/**
	 * Form Fields
	 */
	function get_page( $form, $attributes = null ) {

		do_action( 'youzify_before_' . $form . '_form' );

		echo '<div id="youzify-membership" class="youzify-membership youzify-membership-page-box youzify-page">';
		$this->get_form( $form, $attributes );
		echo '</div>';

		do_action( 'youzify_after_' . $form . '_form' );

	}

	/**
	 * Form Fields
	 */
	function get_form( $form, $shortcode_attrs = null ) {

		// Get Form Attributes
		$attributes = $this->get_attributes( $form );
		$elements 	= $this->get_form_elements( $form );

		// Get Action Link
		if ( 'login' == $form ) {
			$action = apply_filters( 'youzify_login_url' , wp_login_url() );
		} elseif ( 'register' == $form ) {
			$action = wp_registration_url();
		} elseif ( 'lost_password' == $form && isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {
			$action = site_url( 'wp-login.php?action=resetpass' );
		} elseif ( 'complete_registration' == $form ) {
			$action = youzify_membership_page_url( 'complete-registration' );
		} elseif ( 'lost_password' == $form ) {
			$action = wp_lostpassword_url();
		} else {
			$action = null;
		}

		$action = apply_filters( 'youzify_membership_form_action', $action, $form );

		?>

		<div class="<?php echo $attributes['form_class']; ?>">

			<?php $this->get_form_header( $form ); ?>
			<?php $this->get_form_messages( $attributes ); ?>

			<form id="youzify-membership-form" class="youzify-membership-<?php echo $form; ?>-form" method="post" action="<?php echo $action; ?>">

				<!-- After Form Buttons -->
				<?php do_action( 'youzify_before_' . $form . '_fields', $shortcode_attrs ); ?>

				<?php $this->generate_form_fields( $elements['fields'], $attributes ); ?>
				<?php $this->generate_form_actions( $elements['actions'], $attributes ); ?>

				<!-- After Form Buttons -->
				<?php do_action( 'youzify_after_' . $form . '_buttons', $shortcode_attrs ); ?>

				<input type="hidden" name="youzify-membership-form" value="1">

			</form>

		</div>

		<?php
	}

	/**
	 * Form Attributes.
	 */
	function get_attributes( $form ) {

		$attrs = array();

		switch ( $form ) {

			case 'login':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'login';
				$attrs['form_action'] = 'login';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'login' );
				$attrs['form_class']   = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'form-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'form-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_lostpswd'] = ( false !== strpos( $attrs['action_class'], 'form-lost-pswd' ) ) ? true : false;
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'form-buttons-icons' ) ) ? true : false;

				break;

			case 'register':
			case 'complete_registration':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'signup';
				$attrs['form_action'] = 'signup';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'register' );
				$attrs['form_class'] = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'form-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'form-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'form-buttons-icons' ) ) ? true : false;

				break;

			case 'lost_password':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'login';
				$attrs['form_action'] = 'lost-password';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'login' );
				$attrs['form_class']   = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'form-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'form-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'form-buttons-icons' ) ) ? true : false;

				break;

			default:
				break;
		}

		return $attrs;

	}

	/**
	 * Signup
	 */
	function get_actions_class( $type ) {

		// Array.
		$actions_class = array( 'youzify-membership-form-actions' );

		switch ( $type ) {

			case 'login':

				// Get Actions Layout
				$actions_layout = youzify_option( 'youzify_login_actions_layout', 'form-actions-v1' );

				// Get Form Options Data

				$one_button = array(
					'form-actions-v3', 'form-actions-v6'
				);

				$forgot_password = array(
					'form-actions-v2', 'form-actions-v5', 'form-actions-v9', 'form-actions-v10'
				);

				$use_icons	= array(
					'form-actions-v4', 'form-actions-v5', 'form-actions-v6', 'form-actions-v7',
					'form-actions-v10'
				);

				$full_witdh	= array(
					'form-actions-v1', 'form-actions-v3', 'form-actions-v4', 'form-actions-v6',
					'form-actions-v9', 'form-actions-v10'
				);

				$half_witdh	= array(
					'form-actions-v2', 'form-actions-v5', 'form-actions-v7', 'form-actions-v8'
				);

				// Get One Button Class.
				$actions_class[] = in_array( $actions_layout, $one_button ) ? 'form-one-button' : null;

				// Get Buttons icons Class.
				$actions_class[] = in_array( $actions_layout, $use_icons ) ? 'form-buttons-icons' : null;

				// Get full Width Class.
				$actions_class[] = in_array( $actions_layout, $full_witdh ) ? 'form-fullwidth-button' : null;

				// Get Half Width Class.
				$actions_class[] = in_array( $actions_layout, $half_witdh ) ? 'form-halfwidth-button' : null;

				// Get "Forgot Password" Class.
				$actions_class[] = in_array( $actions_layout, $forgot_password ) ? 'form-lost-pswd' : null;

				// Get Button Border Style.
				$actions_class[] = youzify_option( 'youzify_login_btn_format', 'form-border-radius' );

				// Get Button Icons Position.
				if ( in_array( $actions_layout, $use_icons ) ) {
					$actions_class[] = youzify_option( 'youzify_login_btn_icons_position', 'form-icons-left' );
				}

				break;

			case 'register':

				// Get Actions Layout
				$actions_layout = youzify_option( 'youzify_signup_actions_layout', 'form-regactions-v1' );

				// Get One Button Class.
				if ( in_array( $actions_layout, array( 'form-regactions-v5', 'form-regactions-v6' ) ) ) {
					$actions_class[] = 'form-one-button';
				}

				// Get Buttons icons Class.
				if ( in_array( $actions_layout, array( 'form-regactions-v3', 'form-regactions-v4', 'form-regactions-v6' ) ) ) {
					$actions_class[] = 'form-buttons-icons';
					$actions_class[] = youzify_option( 'youzify_signup_btn_icons_position', 'form-icons-left' );
				}

				// Get full Width Class.
				if ( in_array( $actions_layout, array( 'form-regactions-v1', 'form-regactions-v3', 'form-regactions-v5', 'form-regactions-v6' ) ) ) {
					$actions_class[] = 'form-fullwidth-button';
				}

				// Get Half Width Class.
				if ( in_array( $actions_layout, array( 'form-regactions-v2', 'form-regactions-v4' ) ) ) {
					$actions_class[] = 'form-halfwidth-button';
				}

				// Get Button Border Style.
				$actions_class[] = youzify_option( 'youzify_signup_btn_format', 'form-border-radius' );

				// If BuddyPress
				if ( youzify_is_bp_registration_completed() ) {
					$actions_class[] = 'youzify-membership-bp-registration-completed';
				}

				break;

			default:
				break;
		}

		// Return Action Area Classes
		return youzify_generate_class( $actions_class );
	}

	/**
	 * Form Header
	 */
	function get_form_header( $form ) {

		// Get Form Title.
		if ( 'activate' == $form ) {
			$form_title = __( 'Activate Account', 'youzify' );
			$form_subtitle = __( 'Activate your account', 'youzify' );
		} elseif ( 'lost_password' == $form ) {
			$form_title = youzify_option( 'youzify_lostpswd_form_title', __( 'Forgot your password?', 'youzify' ) );
			$form_subtitle = youzify_option( 'youzify_lostpswd_form_subtitle', __( 'Reset your account password', 'youzify' ) );
		} elseif ( 'register' == $form ) {
			$form_title = youzify_option( 'youzify_signup_form_title', __( 'Sign Up', 'youzify' ) );
			$form_subtitle = youzify_option( 'youzify_signup_form_subtitle', __( 'Create new account', 'youzify' ) );
		} elseif ( 'complete_registration' == $form ) {
			$form_title = __( 'Complete Registration', 'youzify' );
			$form_subtitle = __( 'Complete registration steps', 'youzify' );
		} else {
			$form_title = youzify_option( 'youzify_login_form_title', __( 'Login', 'youzify' ) );
			$form_subtitle 	= youzify_option( 'youzify_login_form_subtitle', __( 'Sign in to your account', 'youzify' ) );
		}

		// Sanitize Form Title & Subtitle
		$form_title = sanitize_text_field( $form_title );
		$form_subtitle = sanitize_text_field( $form_subtitle );

		// Get Form Options
		if ( 'activate' == $form ) {
			$form = 'login';
		} elseif ( 'lost_password' == $form ) {
			$form = 'lostpswd';
		} elseif ( 'register' == $form || 'complete_registration' == $form ) {
			$form = 'signup';
		}

		// Get Cover Data
		$form_cover = esc_url( youzify_option( 'youzify_' . $form . '_cover' ) );
		$cover_class = ! empty( $form_cover ) ? 'youzify-membership-custom-cover' : 'youzify-membership-default-cover';

		// If cover photo not exist use pattern.
		if ( ! $form_cover ) {
			$form_cover = YOUZIFY_ASSETS . 'images/geopattern.png';
		}

		?>

    	<header class="youzify-membership-form-header">
    		<?php do_action( 'youzify_before_' . $form . '_form_cover' ); ?>
	    	<?php if ( 'on' == youzify_option( 'youzify_' . $form . '_form_enable_header', 'on' ) ) : ?>
	    		<div class="youzify-membership-form-cover <?php echo $cover_class; ?>" style="background-image: url( <?php echo apply_filters( 'youzify_' . $form . '_form_cover', $form_cover ); ?> )">
			        <h2 class="form-cover-title"><?php echo $form_title; ?></h2>
	    		</div>
	    	<?php else : ?>
	    		<div class="form-title">
		    		<h2><?php echo $form_title; ?></h2>
		    		<?php if ( ! empty( $form_subtitle ) ) : ?>
		    			<span class="youzify-membership-form-desc"><?php echo $form_subtitle; ?></span>
    				<?php endif; ?>
	    		</div>
    		<?php endif; ?>
    	</header>

	    <?php
	}

	/**
	 * Form Elements
	 */
	function get_form_elements( $form = null ) {

		// New Array's
		$fields = array();
		$actions = array();

		switch ( $form ) :

		case 'login':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-user',
				'label'	=> __( 'Username or Email', 'youzify' ),
				'id'	=> 'user_login',
				'name'	=> 'log',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-lock',
				'label'	=> __( 'Password', 'youzify' ),
				'id'	=> 'user_pass',
				'name'	=> 'pwd',
				'type'	=> 'password'
			);

			$fields[] = array(
				'item' 		=> 'remember-me',
				'label'		=> __( 'Remember Me', 'youzify' )
			);

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-sign-in-alt',
				'name' => 'signin_submit',
				'title' => youzify_option( 'youzify_login_signin_btn_title', __( 'Log In', 'youzify' ) )
			);

			if ( get_option( 'users_can_register' ) ) :

				// Get Custom Registration Link.
				$custom_registration = youzify_option( 'youzify_login_custom_register_link' );

				// Get Registration Link.
				$register_page_link = ! empty( $custom_registration ) ? $custom_registration : youzify_membership_page_url( 'register' );

				$actions[] = array(
					'item' 	=> 'link',
					'icon'	=> 'fas fa-pencil-alt',
					'url'	=> $register_page_link,
					'title' => youzify_option( 'youzify_login_register_btn_title', __( 'Create New Account', 'youzify' ) )
				);
			endif;

			$actions[] = array( 'item' 	=> 'lost_pswd' );

			$actions[] = array( 'item' 	=> 'redirect' );

		break;

		case 'register':

			if ( ! youzify_is_bp_registration_completed() ) {
				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'fas fa-pencil-alt',
					'name'  => 'signup_submit',
					'title' => youzify_option( 'youzify_signup_register_btn_title', __( 'Sign Up', 'youzify' ) )
				);
			}

			$actions[] = array(
				'item' 	=> 'link',
				'icon'	=> 'fas fa-sign-in-alt',
				'url'	=> youzify_get_login_page_url(),
				'title' => youzify_option( 'youzify_signup_signin_btn_title', __( 'Log In', 'youzify' ) )
			);

			break;

		case 'activate':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-key',
				'id'	=> 'key',
				'name'	=> 'key',
				'type'	=> 'text',
				'label'	=> __( 'Activation Key', 'youzify' ),
				'value'	=> esc_attr( bp_get_current_activation_key() )
			);

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-check',
				'title' => __( 'Activate', 'youzify' )
			);

			break;

		case 'complete_registration':

			// Init Vars
			$errors = array();

			// Get Required Fields.
			$required_fields = json_decode( youzify_membership_user_session_data( 'get' ), true );

			if ( isset( $required_fields['email'] ) ) {
				$errors[] = sprintf( __( "- %s didn't provide us with your email.", 'youzify' ), $required_fields['provider'] );
			}

			if ( isset( $required_fields['user_login'] ) ) {
				$errors[] = __( "- We couldn't get your username or its already exist.", 'youzify' );
			}

			$erros_msg =  implode( '<br>', $errors ) ;

			if ( ! isset( $_GET['register-errors'] ) ) {
				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> sprintf( __( "<strong>Note:</strong> We couldn't get the information below : <br> %s", 'youzify' ), $erros_msg )
				);
			}

			// Get Username Field
			if ( isset( $required_fields['user_login'] ) ) {
				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-user',
					'label'	=> __( 'Username', 'youzify' ),
					'id'	=> 'user_login',
					'name'	=> 'signup_username',
					'type'	=> 'text'
				);
			}

			if ( isset( $required_fields['email'] ) ) {
				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'far fa-envelope',
					'label'	=> __( 'Email', 'youzify' ),
					'name'	=> 'signup_email',
					'id'	=> 'email',
					'type'	=> 'email'
				);
			}

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-pencil-alt',
				'title' => __( 'Complete Registration', 'youzify' )
			);

			$fields[] = array(
				'item' 	=> 'hidden',
				'name'	=> 'complete-registration',
				'value'	=> 'true',
			);

			break;

		case 'lost_password':

			if ( isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {

				$fields[] = array(
					'key'	=> 'login',
					'item' 	=> 'hidden',
					'name'	=> 'rp_login',
					'value'	=> isset( $_GET['login'] ) ? sanitize_text_field( $_GET['login'] ) : ''
				);

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'rp_key',
					'key'	=> 'key',
					'value'	=> isset( $_GET['key'] ) ? sanitize_text_field( $_GET['key'] ) : ''
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-lock',
					'label'	=> __( 'New Password', 'youzify' ),
					'id'	=> 'pass1',
					'name'	=> 'pass1',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-lock',
					'label'	=> __( 'Repeat New Password', 'youzify' ),
					'id'	=> 'pass2',
					'name'	=> 'pass2',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> wp_get_password_hint()
				);

				$actions[] = array(
					'icon'	=> 'fas fa-undo',
					'item' 	=> 'submit',
					'title'	=> youzify_option( 'youzify_lostpswd_submit_btn_title', __( 'Reset Password', 'youzify' ) )
				);

			} else {

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> __( "Enter your email address and we'll send you a link you can use to pick a new password.", 'youzify' )
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'far fa-envelope',
					'label'	=> __( 'Email', 'youzify' ),
					'id'	=> 'email',
					'name'	=> 'user_login',
					'type'	=> 'email'
				);

				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'fas fa-undo',
					'title' => youzify_option( 'youzify_lostpswd_submit_btn_title', __( 'Reset Password', 'youzify' ) )
				);

				$actions[] = array(
					'item' 	=> 'link',
					'icon'	=> 'fas fa-sign-in-alt',
					'url'	=> youzify_membership_page_url( 'login' ),
					'title' => youzify_option( 'youzify_signup_signin_btn_title', __( 'Log In', 'youzify' ) )
				);
			}
			break;

		endswitch;

		return apply_filters( 'youzify_login_form_elements', array( 'fields' => $fields, 'actions' => $actions ), $form );
	}

	/**
	 * Form Class
	 */
	function get_form_class( $attributes = null ) {

		// Create New Array();
		$form_class = array();

		// Get Form Type.
		$form_type = $attributes['form_type'];

		// Get Form Options Data

		$silver_icons = array( 'form-field-v2', 'form-field-v5', 'form-field-v10' );

		$silver_inputs = array( 'form-field-v4', 'form-field-v6', 'form-field-v9' );

		$use_labels = array(
			'form-field-v1','form-field-v2', 'form-field-v4', 'form-field-v6', 'form-field-v11'
		);

		$use_icons = array(
			'form-field-v2','form-field-v5', 'form-field-v6', 'form-field-v7',
			'form-field-v8', 'form-field-v9', 'form-field-v10', 'form-field-v11'
		);

		$full_border = array(
			'form-field-v1','form-field-v2', 'form-field-v4', 'form-field-v5','form-field-v6',
			'form-field-v8', 'form-field-v9', 'form-field-v11', 'form-field-v12'
		);

		// Get Form Layout
		$form_layout = youzify_option( 'youzify_' . $form_type . '_form_layout', 'form-field-v1' );

		// Check if header is Enable Or Disabled.
		if ( 'lost-password' == $attributes['form_action'] ) {
			$use_header = youzify_option( 'youzify_lostpswd_form_enable_header', 'on' );
		} else {
			$use_header = youzify_option( 'youzify_' . $form_type . '_form_enable_header', 'on' );
		}

		// Main Form Class
		$form_class[] = 'youzify-membership-form';

		// Add Registration	Incomplete class
		if ( youzify_is_registration_incomplete() ) {
			$form_class[] = 'youzify-membership-complete-registration-page';
		}

		// Add Registration	Incomplete class
		if ( youzify_is_bp_registration_completed() ) {
			$form_class[] = 'youzify-membership-complete-registration-page';
		}

		// Get Page Class Name
		$form_class[] = "youzify-membership-$form_type-page";
		if ( 'lost-password' == $attributes['form_action'] ) {
			$form_class[] = 'youzify-membership-lost-password-page';
		}

		// Get Header Type.
		$form_class[] = ( $use_header == 'on' ) ? 'form-with-header' : 'form-no-header';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $use_labels ) ? 'form-with-labels' : 'form-no-labels';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $silver_inputs ) ? 'form-silver-inputs' : null;

		// Get Icons Type
		$form_class[] = in_array( $form_layout, $use_icons ) ? 'form-fields-icon' : 'form-no-icons';

		// Get Border Type
		$form_class[] = in_array( $form_layout, $full_border ) ? 'form-full-border' : 'form-bottom-border';

		// Get Border Format.
		$form_class[] = youzify_option( 'youzify_' . $form_type . '_fields_format', 'form-border-flat' );

		// Icons Options
		if ( in_array( $form_layout, $use_icons ) ) {
			// Get icons position.
			$form_class[] = youzify_option( 'youzify_' . $form_type . '_icons_position', 'form-icons-left' );
			// Get icons background.
			$form_class[] = in_array( $form_layout, $silver_icons ) ? 'form-silver-icons' : 'form-nobg-icons';
		}

		// Add Error Messages Class
		if ( 'login' == $attributes['form_action'] ) {
			$form_class[] = (
				isset( $attributes['errors'] ) ||
				isset( $attributes['logged_out'] ) ||
				isset( $attributes['registered'] )
			) ? 'youzify-membership-form-msgs' : null;
		} else {
			if ( isset( $attributes['errors'] ) ) {
				$form_class[] = count( $attributes['errors'] ) > 0 ? 'youzify-membership-form-msgs' : null;
			}
		}

		// Return Form Classes.
		return youzify_generate_class( $form_class );
	}

	/**
	 * Form Messages
	 */
	function get_form_messages( $attrs ) {

		do_action( 'youzify_form_notices' ); ?>

		<?php if ( isset( $attrs['registered'] ) && $attrs['registered'] ) : ?>
			<div class="youzify-membership-form-message youzify-membership-success-msg">
				<p>
					<strong><?php _e( 'Done!' , 'youzify' ); ?></strong>
					<?php _e( 'You have successfully registered. We have emailed your password to the email address you entered.', 'youzify' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['logged_out'] ) && $attrs['logged_out'] ) : ?>
			<div class="youzify-membership-form-message youzify-membership-info-msg">
				<p>
					<?php _e( '<strong>You have signed out!</strong> Would you like to sign in again?', 'youzify' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ( isset( $attrs['password_updated'] ) && $attrs['password_updated'] ) || ( isset( $_GET['password'] ) && $_GET['password'] == 'changed' ) ) : ?>
			<div class="youzify-membership-form-message youzify-membership-success-msg">
				<p>
				<strong><?php _e( 'Done!' , 'youzify' ); ?></strong>
					<?php _e( 'Your password has been changed. You can sign into your account now.', 'youzify' ); ?>
				</p>
			</div>
		<?php endif; ?>

	<?php
	}

	/**
	 * Form Fields
	 */
	function get_form_fields( $field, $attrs ) {

		// Get Fields By Type.
		switch ( $field['item'] ) {

			case 'input': ?>
				<div class="youzify-membership-form-item">
		    		<div class="youzify-membership-item-content">
			           	<?php if ( $attrs['use_labels'] ) : ?>
			           		<label for="<?php echo $field['id']; ?>"><?php echo sanitize_text_field( $field['label'] ); ?></label>
			        	<?php endif; ?>
			           <div class="youzify-membership-field-content">
		           			<?php if ( $attrs['use_icons'] ) : ?>
					           <div class="youzify-membership-field-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		        			<?php endif; ?>
				    		<input type="<?php echo $field['type'];?>" name="<?php echo $field['name']; ?>" autocomplete="false" placeholder="<?php if ( ! $attrs['use_labels'] ) { echo sanitize_text_field( $field['label'] ); } ?>" value="<?php if ( isset( $field['value'] ) ) { echo $field['value']; } ?>" required>
			            </div>
		        	</div>
		       	</div>
			<?php	break;

			case 'remember-me': ?>
		    	<div class="youzify-membership-form-item youzify-membership-remember-me">
		    		<div class="youzify-membership-item-content">
			        	<label class="youzify_membership_checkbox_field" ><input name="rememberme" type="checkbox" value="forever"><div class="youzify_membership_field_indication"></div><?php echo $field['label']; ?></label>

		        	</div>
					<?php
						if ( ! $attrs['actions_lostpswd'] ) {
							$this->lost_password_field();
						}
					?>
		        </div>
			<?php break;

			case 'checkbox': ?>
		    	<div class="youzify-membership-form-item youzify-membership-checkbox-item <?php echo $field['class']; ?>">
		    		<div class="youzify-membership-item-content">
			        	<label class="youzify_membership_checkbox_field" ><input name="<?php echo $field['name']; ?>" type="checkbox" value="<?php echo $field['value'];  ?>"><div class="youzify_membership_field_indication"></div><?php echo $field['label']; ?></label>

		        	</div>
		        </div>
			<?php break;

			case 'submit': ?>
				<div class="youzify-membership-action-item youzify-membership-submit-item">
					<div class="youzify-membership-item-inner">
	           			<button type="submit" value="submit" <?php if ( isset( $field['name'] ) ) : ?> name="<?php echo $field['name']; ?>" <?php endif; ?> >
	            			<?php if ( $attrs['actions_icons'] ) : ?>
		           				<div class="youzify-membership-button-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		           			<?php endif; ?>
	           				<span class="youzify-membership-button-title"><?php echo sanitize_text_field( $field['title'] ); ?></span>
	           			</button>
	            	</div>
	            </div>
			<?php break;

			case 'link': ?>
				<div class="youzify-membership-action-item youzify-membership-link-item">
					<div class="youzify-membership-item-inner">
	            		<a href="<?php echo esc_url( $field['url'] ); ?>" class="youzify-membership-link-button" >
	            			<?php if ( $attrs['actions_icons'] ) : ?>
    							<div class="youzify-membership-button-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		           			<?php endif; ?>
	           				<?php echo sanitize_text_field( $field['title'] ); ?>
	            		</a>
	            	</div>
	            </div>
			<?php break;

			case 'lost_pswd':
					if ( $attrs['actions_lostpswd'] ) {
						$this->lost_password_field();
					}
				break;

			case 'redirect': ?>
				<?php if ( isset( $_GET['redirect_to'] ) ) : ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $_GET['redirect_to'] ); ?>">
				<?php endif; ?>
			<?php break;

			case 'note':

				// Init Vars
				$note_class = array();
				$note_class[] = 'youzify-membership-form-note';
				$note_class[] = isset( $field['class'] ) ? $field['class'] : null;

				?>

				<div class="<?php echo youzify_generate_class( $note_class ); ?>">
					<?php echo $field['note']; ?>
				</div>

			<?php break;

			case 'hidden':

				$value = isset( $field['value'] ) ? $field['value'] : $attrs[ $field['key'] ];

			?>
				<input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off">
			<?php break;

		}
	}

	/**
	 * Generate Form Fields
	 */
	function generate_form_fields( $fields, $attributes ) {
		// Print Fields
		foreach ( $fields as $field ) {
			$this->get_form_fields( $field, $attributes );
		}
	}

	/**
	 * Generate Form Actions
	 */
	function generate_form_actions( $actions, $attributes ) {
		// Print Fields
		echo '<div class="' . $attributes['action_class'] . '">';
		foreach ( $actions as $action ) {
			$this->get_form_fields( $action, $attributes );
		}
		echo '</div>';
	}

	/**
	 * Lost Password Link
	 */
	function lost_password_field() {
		$field_title = sanitize_text_field( youzify_option( 'youzify_login_lostpswd_title', __( 'Lost password?', 'youzify'  ) ) );
		$lostpswd = apply_filters( 'youzify_lostpassword_url', wp_lostpassword_url() );
		echo '<a class="youzify-membership-forgot-password" href="' . $lostpswd . '">' . $field_title . '</a>';
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	public function get_error_message( $error_code ) {
		switch ( $error_code ) {

			case 'empty_fields':
				return __( 'Required form field is missing.', 'youzify' );

			case 'username_invalid':
				return __( 'Invalid username!', 'youzify' );

			case 'username_exists':
				return __( 'That username already exists!', 'youzify' );

			case 'username_length':
				return __( 'Username too short. At least 4 characters is required!', 'youzify' );

			case 'email':
				return __( 'The email address you entered is not valid.', 'youzify' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'youzify' );

			case 'first_name':
				return __( 'First name should be alphabetic!', 'youzify' );

			case 'last_name':
				return __( 'Last name should be alphabetic!', 'youzify' );

			case 'registration_closed':
				return __( 'Registering new users is currently not allowed.', 'youzify' );

			case 'empty_username':
				return __( 'You do have an email address, right?', 'youzify' );

			case 'invalid_url':
				return __( 'The requested URL is invalid', 'youzify' );

			case 'empty_password':
				return __( 'You need to enter a password to login.', 'youzify' );

			case 'file_not_found':
				return __( 'Provider functions file not found.', 'youzify' );

			case 'invalid_username':
				return __(
					"We don't have any users with that email address. Maybe you used a different one when signing up?", 'youzify' );

			case 'incorrect_password':
				return __( "The password you entered wasn't quite right.", 'youzify' );

			case 'expiredkey':
			case 'invalidkey':
				return __( 'The password reset link you used is not working.', 'youzify' );

			case 'registration_disabled':
				return __( 'Registering new users is currently not allowed.', 'youzify' );

			case 'network_unavailable':
				return __( 'The chosen network is not available.', 'youzify' );

			case 'social_auth_unavailable':
				return __( 'The social authentication is not available.', 'youzify' );

			case 'cant_connect':
				return __( "We couldn't connect to your account. Please try again!", 'youzify' );

			case 'lost_password_sent':
				return __( 'Check your email for a link to reset your password.', 'youzify' );

			// case 'too_many_retries':

			// global $Youzify_Membership;

			// return $Youzify_Membership->limit->get_lockout_msg();

			case 'registration_needs_activation':

			return __( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'youzify' );

			default:
				break;
		}
		return __( 'An unknown error occurred. Please try again later.', 'youzify' );
	}
}