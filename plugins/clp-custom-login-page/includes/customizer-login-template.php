<?php
/**
 * Template Name: Login Customizer Template
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
// require ABSPATH . '/wp-load.php';


function clp_login_header( $title = 'Log In', $message = '', $wp_error = null ) {

	global $error, $interim_login, $action;

	if ( ! is_wp_error( $wp_error ) ) {
		$wp_error = new WP_Error();
	}

	$login_title = get_bloginfo( 'name', 'display' );

	/* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
	$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $login_title );

	/**
	 * Filters the title tag content for login page.
	 *
	 * @since 4.9.0
	 *
	 * @param string $login_title The page title, with extra context added.
	 * @param string $title       The original page title.
	 */
	$login_title = apply_filters( 'login_title', $login_title, $title );

	?><!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<title><?php echo $login_title; ?></title>
	<?php

	wp_enqueue_style( 'login' );

	wp_enqueue_style( 'clp-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css', array(), '5.15.2', 'all' );

	do_action( 'login_enqueue_scripts' );

	do_action( 'login_head' );

	$login_header_url = __( 'https://wordpress.org/' );

	$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

	$login_header_title = '';

	$login_header_title = apply_filters_deprecated(
		'login_headertitle',
		array( $login_header_title ),
		'5.2.0',
		'login_headertext',
		__( 'Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead.' )
	);

	$login_header_text = empty( $login_header_title ) ? __( 'Powered by WordPress' ) : $login_header_title;

	/**
	 * Filters the link text of the header logo above the login form.
	 *
	 * @since 5.2.0
	 *
	 * @param string $login_header_text The login header logo link text.
	 */
	$login_header_text = apply_filters( 'login_headertext', $login_header_text );

	$classes = array( 'login-action-' . $action, 'wp-core-ui' );

	if ( is_rtl() ) {
		$classes[] = 'rtl';
	}

	$classes[] = ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

	/**
	 * Filters the login page body classes.
	 *
	 * @since 3.5.0
	 *
	 * @param array  $classes An array of body classes.
	 * @param string $action  The action that brought the visitor to the login page.
	 */
	$classes = apply_filters( 'login_body_class', $classes, $action );

	?>
	
	<style id="clp-custom-css"></style>
	
	</head>
	<body class="login no-js <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<div class="clp-customizer-actions-wrap">
			<div class="clp-customize-icon">
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_templates', 'a template', 'dashicons-layout'); ?>
			</div>
			<div class="clp-customize-icon">
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_background', 'a background', 'dashicons-images-alt2'); ?>
			</div>
			<div class="clp-customize-icon">
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_layout', 'a layout', 'dashicons-columns'); ?>
			</div>
		</div>
	<script type="text/javascript">
		document.body.className = document.body.className.replace('no-js','js');
	</script>
	<?php
	/**
	 * Fires in the login page header after the body tag is opened.
	 *
	 * @since 4.6.0
	 */
	do_action( 'clp_login_header' );

	?>
	<div id="login">
		<h1><a href="<?php echo esc_url( $login_header_url ); ?>"><?php echo $login_header_text; ?></a></h1>
	<?php
	/**
	 * Filters the message to display above the login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $message Login message text.
	 */
	$message = apply_filters( 'login_message', $message );

	if ( ! empty( $message ) ) {
		echo $message . "\n";
	}

	// In case a plugin uses $error rather than the $wp_errors object.
	if ( ! empty( $error ) ) {
		$wp_error->add( 'error', $error );
		unset( $error );
	}

	if ( $wp_error->has_errors() ) {
		$errors   = '';
		$messages = '';

		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data( $code );
			foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {
				if ( 'message' === $severity ) {
					$messages .= '	' . $error_message . "<br />\n";
				} else {
					$errors .= '	' . $error_message . "<br />\n";
				}
			}
		}

		if ( ! empty( $errors ) ) {
			/**
			 * Filters the error messages displayed above the login form.
			 *
			 * @since 2.1.0
			 *
			 * @param string $errors Login error message.
			 */
			echo '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . "</div>\n";
		}

		if ( ! empty( $messages ) ) {
			/**
			 * Filters instructional messages displayed above the login form.
			 *
			 * @since 2.5.0
			 *
			 * @param string $messages Login messages.
			 */
			echo '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
		}
	}
} // End of login_header().

/**
 * Outputs the footer for the login page.
 *
 * @since 3.1.0
 *
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 *
 * @param string $input_id Which input to auto-focus.
 */
function clp_login_footer( $input_id = '' ) { ?>

		
		<div class="clp-customizer-form-footer">
			<div class="clp-customize-icon">
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_form_footer', 'a form footer');; ?>
			</div>
		</div>

		<p id="backtoblog"><a href="#">
		<?php

		/* translators: %s: Site title. */
		printf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );

		?>
		</a></p>
		<?php

		the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' );


	?>
	</div><?php // End of <div id="login">. ?>

	<?php

	/**
	 * Fires in the login page footer.
	 *
	 * @since 3.1.0
	 */
	echo '<div class="clp-page-footer-customizer">';
		CLP_Helper_Functions::customizer_icon_link( 'clp_page_footer', 'a page footer');
	echo '</div>';
	do_action( 'clp_login_footer' );

	wp_enqueue_script( 'webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array(), null, true );
	wp_footer();

	?>
	<div class="clear"></div>
	
	</body>
	</html>
	<?php
}


//
// Main.
//

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
$errors = new WP_Error();

do_action( 'login_init' );

do_action( "login_form_{$action}" );

$http_post     = ( 'POST' === $_SERVER['REQUEST_METHOD'] );
$interim_login = isset( $_REQUEST['interim-login'] );

/**
 * Filters the separator used between login form navigation links.
 *
 * @since 4.9.0
 *
 * @param string $login_link_separator The separator used between login form navigation links.
 */
$login_link_separator = apply_filters( 'login_link_separator', ' | ' );

switch ( $action ) {

	case 'login':
		clp_login_header( __( 'Log In' ), '', $errors );
		$aria_describedby_error = '';
		$rememberme = false;
		wp_enqueue_script( 'user-profile' );
		?>
		<?php CLP_Helper_Functions::customizer_icon_link( 'clp_inputs', 'an inputs'); ?>
		<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post" autocomplete="off">
			<?php CLP_Helper_Functions::customizer_icon_link( 'clp_form', 'a form'); ?>
			<p>
				<label for="user_login"><?php _e( 'Username or Email Address' ); ?></label>
				<input type="text" name="log" id="user_login"<?php echo $aria_describedby_error; ?> class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" autocapitalize="off" autocomplete="off" />
			</p>

			<div class="user-pass-wrap">
				<label for="user_pass"><?php _e( 'Password' ); ?></label>
				<div class="wp-pwd">
					<input type="password" name="pwd_new" id="user_pass"<?php echo $aria_describedby_error; ?> class="input password-input" value="" size="20" autocomplete="new-password" />
					<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
			</div>
			<?php

			/**
			 * Fires following the 'Password' field in the login form.
			 *
			 * @since 2.1.0
			 */
			do_action( 'login_form' );

			?>
			<p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <label for="rememberme"><?php esc_html_e( 'Remember Me' ); ?></label></p>
			<p class="submit">
				
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In' ); ?>" />
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_button', 'a submit button'); ?>

				<input type="hidden" name="testcookie" value="1" />
				
			</p>
		</form>

		<?php

		if ( ! $interim_login ) {
			?>
			<p id="nav">
				<?php

				if ( ! isset( $_GET['checkemail'] ) || ! in_array( $_GET['checkemail'], array( 'confirm', 'newpass' ), true ) ) {
					if ( get_option( 'users_can_register' ) ) {
						$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

						/** This filter is documented in wp-includes/general-template.php */
						echo apply_filters( 'register', $registration_url );

						echo esc_html( $login_link_separator );
					}

					?>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?' ); ?></a>
					<?php
				}

				?>
			</p>
			<?php
		}

		clp_login_footer();

		wp_enqueue_script('jquery');
		break;

	case 'register':
		clp_login_header( __( 'Registration Form' ), '<p class="message register">' . __( 'Register For This Site' ) . '</p>', $errors );
		?>
		<?php CLP_Helper_Functions::customizer_icon_link( 'clp_inputs', 'an inputs'); ?>
		<form name="registerform" id="registerform" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
			<?php CLP_Helper_Functions::customizer_icon_link( 'clp_form', 'a form'); ?>
			<p>
				<label for="user_login"><?php _e( 'Username' ); ?></label>
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( wp_unslash( $user_login ) ); ?>" size="20" autocapitalize="off" />
			</p>
			<p>
				<label for="user_email"><?php _e( 'Email' ); ?></label>
				<input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25" />
			</p>
			<?php

			/**
			 * Fires following the 'Email' field in the user registration form.
			 *
			 * @since 2.1.0
			 */
			do_action( 'register_form' );

			?>
			<p id="reg_passmail">
				<?php _e( 'Registration confirmation will be emailed to you.' ); ?>
			</p>
			<br class="clear" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Register' ); ?>" />
				<?php CLP_Helper_Functions::customizer_icon_link( 'clp_button', 'a submit button'); ?>
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>
				<?php echo esc_html( $login_link_separator ); ?>
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?' ); ?></a>
		</p>
		<?php 

		clp_login_footer();

		wp_enqueue_script('jquery');
		break;
} // End action switch.
