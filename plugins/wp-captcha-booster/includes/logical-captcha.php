<?php
/**
 * This file contains logical captcha code.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
global $captcha_booster_options, $captcha_plugin_info, $wpdb, $display_settings_data, $meta_data_array, $captcha_type, $captcha_array, $captcha_time, $error_data_array, $display_setting;

// include file where is_plugin_active() function is defined.
if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$error_data              = $wpdb->get_var(
	$wpdb->prepare(
		'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'error_message'
	)
);// db call ok; no-cache ok.
$captcha_booster_options = get_option( 'captcha_option' );
$error_data_array        = maybe_unserialize( $error_data );

if ( ! get_option( 'captcha_option' ) ) {
	$captcha_booster_options = array(
		'plugin_option_version'     => $captcha_plugin_info['Version'],
		'captcha_key'               => array(
			'time' => '',
			'key'  => '',
		),
		'captcha_label_form'        => '',
		'captcha_required_symbol'   => '*',
		'captcha_difficulty_number' => '1',
		'captcha_difficulty_word'   => '0',
	);
	add_option( 'captcha_option', $captcha_booster_options );
}

$display_settings_data = $wpdb->get_var(
	$wpdb->prepare(
		'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'display_settings'
	)
);// db call ok; no-cache ok.

$meta_data_array = maybe_unserialize( $display_settings_data );
$display_setting = explode( ',', isset( $meta_data_array['settings'] ) ? $meta_data_array['settings'] : '' );

$captcha_time = CAPTCHA_BOOSTER_LOCAL_TIME;
$captcha_type = $wpdb->get_results(
	$wpdb->prepare(
		'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'captcha_type'
	)
);// db call ok; no-cache ok.

$captcha_array = array();
foreach ( $captcha_type as $row ) {
	$captcha_array = maybe_unserialize( $row->meta_value );
}

/* This action hooks is used to display and validate captcha on login form */
if ( '1' === $display_setting[0] ) {
	add_action( 'login_form', 'captcha_booster_login_form' );
	add_filter( 'authenticate', 'captcha_booster_login_check', 21, 3 );
} else {
	add_action( 'wp_authenticate', 'captcha_booster_check_user_login_status', 10, 2 );
}

/* This action hook is used to display and validate captcha on registeration form */
if ( '1' === $display_setting[2] ) {
	if ( is_multisite() ) {
		add_action( 'signup_extra_fields', 'captcha_booster_register_form', 10, 2 );
		add_action( 'wpmu_signup_user_notification', 'captcha_booster_register_check', 10, 3 );
	} else {
		add_action( 'register_form', 'captcha_booster_register_form' );
		add_action( 'register_post', 'captcha_booster_register_check', 10, 3 );
	}
}

/* This action hook is used to display and validate captcha on comment form */
if ( '1' === $display_setting[6] ) {
	add_action( 'comment_form_after_fields', 'captcha_booster_comment_form' );
	add_action( 'pre_comment_on_post', 'captcha_booster_comment_form_check' );
}


/* This action Hook is Used to create and validate captcha on Lost-Password form */
if ( '1' === $display_setting[4] ) {
	add_action( 'lostpassword_form', 'captcha_booster_register_form' );
	add_action( 'allow_password_reset', 'captcha_booster_lost_password', 1 );
}
/**
 * This function adds captcha to the login form .
 */
function captcha_booster_login_form() {
	global $captcha_booster_options;
	if ( '' === session_id() ) {// @codingStandardsIgnoreLine.
		@session_start();// @codingStandardsIgnoreLine
	}
	if ( isset( $_SESSION['captch_booster_login'] ) ) {// @codingStandardsIgnoreLine.
		unset( $_SESSION['captch_booster_login'] );// @codingStandardsIgnoreLine.
	}
	echo '<p class=cptch_block>';
	if ( '' !== $captcha_booster_options['captcha_label_form'] ) {
		echo '<label>' . esc_attr( $captcha_booster_options['captcha_label_form'] ) . '<span class=required > ' . esc_attr( $captcha_booster_options['captcha_required_symbol'] ) . '</span></label><br />';
	}

	if ( isset( $_SESSION['captcha_booster_error'] ) ) {// @codingStandardsIgnoreLine.
		echo '<br/><span style="color:red;">' . $_SESSION['captcha_booster_error'] . '</span><br/>';// @codingStandardsIgnoreLine.
		unset( $_SESSION['captcha_booster_error'] );// @codingStandardsIgnoreLine.
	}
	echo '<br/>';
	captcha_booster_display_captcha();
	echo '</p><br/>';
}
/**
 * This function adds captcha to the register form .
 */
function captcha_booster_register_form() {
	global $display_setting;
	if ( '1' === $display_setting[7] ) {
		echo '<div class="register-section" id="profile-details-section">';
	}
	echo '<p class="cptch_block">';
	captcha_booster_display_captcha();
	echo '</p>';
}
/* This action hooks is used to display and validate captcha on admin comment form */
if ( '1' === $display_setting[8] ) {
	add_action( 'comment_form_logged_in_after', 'captcha_booster_comment_form' );
	add_action( 'pre_comment_on_post', 'captcha_booster_comment_form_check' );
}
/**
 * This function adds captcha to the comment form .
 */
function captcha_booster_comment_form() {
	global $display_setting, $wpdb, $current_user;
	if ( is_user_logged_in() ) {
		if ( is_super_admin() ) {
			$cpb_role = 'administrator';
		} else {
			$cpb_role           = $wpdb->prefix . 'capabilities';
			$current_user->role = array_keys( $current_user->$cpb_role );
			$cpb_role           = $current_user->role[0];
		}
		if ( ( 'administrator' === $cpb_role && '1' === $display_setting[8] ) || ( 'administrator' !== $cpb_role && '0' === $display_setting[10] ) ) {
			echo '<p class="cptch_block">';
			captcha_booster_display_captcha();
			echo '</p><br />';
		}
	} else {
		echo '<p class="cptch_block">';
		captcha_booster_display_captcha();
		echo '</p><br />';
		// return true.
	}
}
/**
 * This function checks the captcha posted with a login when login errors are absent .
 *
 * @param string $user .
 * @param string $username .
 * @param string $password .
 */
function captcha_booster_login_check( $user, $username, $password ) {
	global $captcha_booster_options, $wpdb, $error_data_array;

	$ip_address = sprintf( '%u', ip2long( get_ip_address_for_captcha_booster() ) );

	$str_key = $captcha_booster_options['captcha_key']['key'];
	if ( '' === session_id() ) {// @codingStandardsIgnoreLine.
		@session_start();// @codingStandardsIgnoreLine.
	}
	if ( isset( $_SESSION['captch_booster_login'] ) && true === $_SESSION['captch_booster_login'] ) {// @codingStandardsIgnoreLine.
		return $user;
	}

	/* Delete errors, if they set */
	if ( isset( $_SESSION['captcha_booster_error'] ) ) {// @codingStandardsIgnoreLine.
		unset( $_SESSION['captcha_booster_error'] );// @codingStandardsIgnoreLine.
	}

	/* Add error if captcha is empty */
	if ( ( ! isset( $_REQUEST['ux_txt_captcha_input'] ) || '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) && isset( $_REQUEST['loggedout'] ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		$error = new WP_Error();
		$error->add( 'captcha_booster_error', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
		wp_clear_auth_cookie();
		return $error;
	}
	if ( isset( $_REQUEST['captcha_booster_result'] ) && isset( $_REQUEST['ux_txt_captcha_input'] ) && isset( $_REQUEST['captcha_booster_time'] ) ) {// WPCS: CSRF ok, WPCS: input var ok.
		if ( 0 === strcasecmp( trim( captcha_booster_decode( wp_unslash( $_REQUEST['captcha_booster_result'] ), $str_key, wp_unslash( $_REQUEST['captcha_booster_time'] ) ) ), esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			$userdata        = get_user_by( 'login', $username );
			$user_email_data = get_user_by( 'email', $username );
			if ( ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) || ( $user_email_data && wp_check_password( $password, $user_email_data->user_pass ) ) ) {
				/* Captcha was matched */
				$_SESSION['captch_booster_login'] = true;// @codingStandardsIgnoreLine.
				captcha_booster_user_log_in_success( $username, $ip_address );
				return $user;
			} else {
				$_SESSION['captch_booster_login'] = false;// @codingStandardsIgnoreLine.
				captcha_booster_user_log_in_fails( $username, $ip_address );
			}
		} else {
			$_SESSION['captch_booster_login'] = false;// @codingStandardsIgnoreLine.
			captcha_booster_user_log_in_fails( $username, $ip_address );
			wp_clear_auth_cookie();
			/* Add error if captcha is incorrect */
			$error = new WP_Error();
			if ( '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
				$error->add( 'captcha_booster_error', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			} else {
				$error->add( 'captcha_booster_error', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
			}
			return $error;
		}
	} else {
		if ( isset( $_REQUEST['log'] ) && isset( $_REQUEST['pwd'] ) ) {// WPCS: CSRF ok, WPCS: input var ok.
			/* captcha was not found in _REQUEST */
			$error = new WP_Error();
			$error->add( 'captcha_booster_error', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			return $error;
		} else {
			/* it is not a submit */
			return $user;
		}
	}
}
/**
 * This function checks captcha for registeration form.
 *
 * @param string $login .
 * @param string $email .
 * @param string $errors .
 */
function captcha_booster_register_check( $login, $email, $errors ) {
	global $captcha_booster_options, $wpdb, $error_data_array;
	$str_key = $captcha_booster_options['captcha_key']['key'];
	if ( is_multisite() ) {
		if ( isset( $_REQUEST['ux_txt_captcha_input'] ) && '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) {// WPCS: input var ok, CSRF ok, sanitization ok.
			wp_die( '<strong>' . esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . '</strong>: ' . esc_attr( $error_data_array['for_captcha_empty_error'] ) );
		}
		if ( 0 !== strcasecmp( trim( captcha_booster_decode( wp_unslash( isset( $_REQUEST['captcha_booster_result'] ) ? $_REQUEST['captcha_booster_result'] : '' ), $str_key, wp_unslash( isset( $_REQUEST['captcha_booster_time'] ) ? $_REQUEST['captcha_booster_time'] : '' ) ) ), esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) ) {// WPCS: CSRF ok,input var ok, sanitization ok.
			wp_die( '<strong>' . esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . '</strong>: ' . esc_attr( $error_data_array['for_invalid_captcha_error'] ) );
		}
	} else {
		if ( isset( $_REQUEST['ux_txt_captcha_input'] ) && '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			$errors->add( 'captcha_blank', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			return $errors;
		}
		if ( 0 !== strcasecmp( trim( captcha_booster_decode( wp_unslash( $_REQUEST['captcha_booster_result'] ), $str_key, wp_unslash( $_REQUEST['captcha_booster_time'] ) ) ), esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			$errors->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
		}
		return( $errors );
	}
}
/**
 * Functionality of the captcha logic work.
 */
function captcha_booster_display_captcha() {
	global $captcha_booster_options, $captcha_time, $captcha_plugin_info, $wpdb, $captcha_array, $display_setting;

	if ( ! $captcha_plugin_info ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		$captcha_plugin_info = get_plugin_data( __FILE__ );
	}
	if ( ! isset( $captcha_booster_options['captcha_key'] ) ) {
		$captcha_booster_options = get_option( 'captcha_option' );
	}
	if ( '' === $captcha_booster_options['captcha_key']['key'] || $captcha_booster_options['captcha_key']['time'] < CAPTCHA_BOOSTER_LOCAL_TIME - ( 24 * 60 * 60 ) ) {
		captcha_booster_generate_key();
	}
	$str_key = $captcha_booster_options['captcha_key']['key'];
	if ( 'logical_captcha' === $captcha_array['captcha_type_text_logical'] && 'arrange_order' === $captcha_array['mathematical_operations'] ) {
		$arrange_order = explode( ',', isset( $captcha_array['arrange_order'] ) ? $captcha_array['arrange_order'] : '' );
		$arrange_array = captcha_booster_random_numbers( 10, 20, 5 );
		$copy_array    = array();
		$arrange_type  = array();
		if ( 1 === $arrange_order[0] ) {
			$arrange_type[] = 'Ascending';
		}
		if ( 1 === $arrange_order[1] ) {
			$arrange_type[] = 'Descending';
		}
		$rand_arrange_array = rand( 0, count( $arrange_type ) - 1 );
		switch ( $arrange_type[ $rand_arrange_array ] ) {
			case 'Ascending':
				sort( $arrange_array );
				$arr_convert = implode( ',', $arrange_array );
				break;

			case 'Descending':
				rsort( $arrange_array );
				$arr_convert = implode( ',', $arrange_array );
				break;
		}
		$imploded_form          = implode( ',', $copy_array );
		$str_arrange_expretion  = '';
		$str_arrange_expretion .= ( 'Ascending' === $arrange_type[ $rand_arrange_array ] ) ? __( 'Arrange in Ascending Order', 'wp-captcha-booster' ) : __( 'Arrange in Descending Order', 'wp-captcha-booster' );
		$str_arrange_expretion .= '<br>' . __( " (Use ',' to separate the numbers) :", 'wp-captcha-booster' ) . "<span style='color:red'>*</span><br><br>";
		$str_arrange_expretion .= $imploded_form . ' = ';
		$str_arrange_expretion .= '<input id=cptch_input class=cptch_input type=text autocomplete=off name=ux_txt_captcha_input size=10 aria-required=true style="margin-bottom:0;display:inline;font-size: 12px;width: 100px;" />';
		/* Add hidden field with encoding result */
		?>
		<input type="hidden" name="captcha_booster_result" value="<?php echo captcha_booster_encode( $arr_convert, $str_key, $captcha_time );// WPCS: XSS ok. ?>" />
		<input type="hidden" name="captcha_booster_time" value="<?php echo esc_attr( $captcha_time ); ?>" />
		<input type="hidden" value="Version: <?php echo esc_attr( $captcha_plugin_info['Version'] ); ?>" />
		<?php
		echo esc_attr( $str_arrange_expretion );
	} elseif ( 'logical_captcha' === $captcha_array['captcha_type_text_logical'] && 'relational' === $captcha_array['mathematical_operations'] ) {
		$relational_actions = explode( ',', isset( $captcha_array['relational_actions'] ) ? $captcha_array['relational_actions'] : '' );
		$relation_op        = array();
		if ( 1 === $relational_actions[0] ) {
			$relation_op[] = 'Larger';
		}
		if ( 1 === $relational_actions[1] ) {
			$relation_op[] = 'Smaller';
		}
		$rand_relation_op = rand( 0, count( $relation_op ) - 1 );
		$array_number     = array();
		$array_number[0]  = rand( 0, 9 );
		$array_number[1]  = rand( 0, 9 );
		while ( $array_number[0] === $array_number[1] ) {
			$array_number[0] = rand( 0, 9 );
		}
		switch ( $relation_op[ $rand_relation_op ] ) {
			case 'Smaller':
				if ( $array_number[0] < $array_number[1] ) {
					$array_number[2] = $array_number[0];
				} else {
					$array_number[2] = $array_number[1];
				}
				break;

			case 'Larger':
				if ( $array_number[0] > $array_number[1] ) {
					$array_number[2] = $array_number[0];
				} else {
					$array_number[2] = $array_number[1];
				}
				break;
		}
		$str_relational_expretion  = '';
		$str_relational_expretion .= __( 'Solve', 'wp-captcha-booster' ) . " : <span style='color:red'>*</span><br>";
		$str_relational_expretion .= ( 'Smaller' === $relation_op[ $rand_relation_op ] ) ? __( 'Which Number is Smaller ', 'wp-captcha-booster' ) : __( 'Which Number is Larger ', 'wp-captcha-booster' );
		$str_relational_expretion .= $array_number[0] . ' ';
		$str_relational_expretion .= __( ' or ', 'wp-captcha-booster' );
		$str_relational_expretion .= ' ' . $array_number[1] . ' ? ';
		$str_relational_expretion .= "<input id=cptch_input class=cptch_input type=text autocomplete=off name=ux_txt_captcha_input maxlength=2 size=2 onkeypress='validate_digits_frontend_captcha_booster(event)' aria-required=true style=\"display:inline;font-size: 12px;width: 40px;\" />";
		/* Add hidden field with encoding result */
		?>
		<input type="hidden" name="captcha_booster_result" value="<?php echo captcha_booster_encode( $array_number[2], $str_key, $captcha_time );// WPCS: XSS ok. ?>" />
		<input type="hidden" name="captcha_booster_time" value="<?php echo esc_attr( $captcha_time ); ?>" />
		<input type="hidden" value="Version: <?php echo esc_attr( $captcha_plugin_info['Version'] ); ?>" />
		<?php
		echo esc_attr( $str_relational_expretion );
	} else {
		/* The array of math actions */
		$math_actions = array();
		$maths_action = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'captcha_type'
			)
		);// db call ok; no-cache ok.
		$maths_array  = maybe_unserialize( $maths_action );

		$arithmetic_actions = explode( ',', isset( $maths_array['arithmetic_actions'] ) ? $maths_array['arithmetic_actions'] : '' );
		/* If value for Plus on the settings page is set */
		if ( '1' === $arithmetic_actions[0] ) {
			$math_actions[] = '&#43;';
		}
		/* If value for Minus on the settings page is set */
		if ( '1' === $arithmetic_actions[1] ) {
			$math_actions[] = '&minus;';
		}
		/* If value for Increase on the settings page is set */
		if ( '1' === $arithmetic_actions[2] ) {
			$math_actions[] = '&times;';
		}
		/* if value for division on setting page is set */
		if ( '1' === $arithmetic_actions[3] ) {
			$math_actions[] = '&#8260;';
		}

		/* What is math action to display in the form */
		$rand_math_action = rand( 0, count( $math_actions ) - 1 );

		$array_math_expretion = array();
		/* Add first part of mathematical expression */
		$array_math_expretion[0] = rand( 1, 30 );
		/* Add second part of mathematical expression */
		$array_math_expretion[1] = rand( 1, 30 );
		/* Calculation of the mathematical expression result */
		switch ( $math_actions[ $rand_math_action ] ) {
			case '&#43;':
				$array_math_expretion[2] = $array_math_expretion[0] + $array_math_expretion[1];
				break;

			case '&minus;':
				/* Result must not be equal to the negative number */
				if ( $array_math_expretion[0] < $array_math_expretion[1] ) {
					$number                  = $array_math_expretion[0];
					$array_math_expretion[0] = $array_math_expretion[1];
					$array_math_expretion[1] = $number;
				}
				$array_math_expretion[2] = $array_math_expretion[0] - $array_math_expretion[1];
				break;

			case '&times;':
				$array_math_expretion[2] = $array_math_expretion[0] * $array_math_expretion[1];
				break;

			case '&#8260;':
				if ( $array_math_expretion[0] < $array_math_expretion[1] ) {
					$number                  = $array_math_expretion[0];
					$array_math_expretion[0] = $array_math_expretion[1];
					$array_math_expretion[1] = $number;
				}
				while ( 0 !== $array_math_expretion[0] % $array_math_expretion[1] ) {
					$array_math_expretion[0] ++;
				}
				$array_math_expretion[2] = $array_math_expretion[0] / $array_math_expretion[1];
				if ( is_float( $array_math_expretion[2] ) ) {
					$float_value             = round( $array_math_expretion[2], 1 );
					$devision                = explode( '.', $float_value );
					$array_math_expretion[2] = $devision[1] >= 5 ? ceil( $float_value ) : floor( $float_value );
				}
				break;
		}
		/* String for display */
		$str_math_expretion  = '';
		$str_math_expretion .= __( 'Solve', 'wp-captcha-booster' ) . " : <span style='color:red'>*</span> <br>";
		$str_math_expretion .= $array_math_expretion[0];
		/* Add math action */
		$str_math_expretion .= ' ' . $math_actions[ $rand_math_action ];
		$str_math_expretion .= ' ' . $array_math_expretion[1];
		$str_math_expretion .= ' = ';
		$str_math_expretion .= ' <input id="cptch_input" class="cptch_input" type="text" autocomplete="off" name="ux_txt_captcha_input" value="" maxlength="5" size="2" aria-required="true" onkeypress="validate_digits_frontend_captcha_booster(event);"  style="margin-bottom:0;display:inline;font-size: 12px;width: 40px;" />';
		/* Add hidden field with encoding result */
		$str_math_expretion .= '<input type="hidden" name="captcha_booster_result" value="' . captcha_booster_encode( $array_math_expretion[2], $str_key, $captcha_time ) . '" />
			<input type="hidden" name="captcha_booster_time" value="' . $captcha_time . '" />
			<input type="hidden" value="Version: ' . $captcha_plugin_info['Version'] . '" />';
		echo $str_math_expretion;// WPCS: XSS ok.
	}
}
/**
 * This Function generates a key which is used during validation of captcha.
 *
 * @param int $lenght .
 */
function captcha_booster_generate_key( $lenght = 15 ) {
	global $captcha_booster_options;
	$simbols        = get_bloginfo( 'url' ) . CAPTCHA_BOOSTER_LOCAL_TIME;
	$simbols_lenght = strlen( $simbols );
	$simbols_lenght--;
	$str_key = null;
	for ( $x = 1; $x <= $lenght; $x++ ) {
		$position = rand( 0, $simbols_lenght );
		$str_key .= substr( $simbols, $position, 1 );
	}
	$captcha_booster_options['captcha_key']['key']  = md5( $str_key );
	$captcha_booster_options['captcha_key']['time'] = CAPTCHA_BOOSTER_LOCAL_TIME;
	update_option( 'captcha_option', $captcha_booster_options );
}
/**
 * This Function used to check captcha for comment form.
 */
function captcha_booster_comment_form_check() {
	global $captcha_booster_options, $wpdb, $error_data_array;
	$str_key = $captcha_booster_options['captcha_key']['key'];
	if ( isset( $_REQUEST['ux_txt_captcha_input'] ) && '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		wp_die( esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . ':&nbsp' . esc_attr( $error_data_array['for_captcha_empty_error'] ) );
	}
	if ( isset( $_REQUEST['captcha_booster_result'] ) && isset( $_REQUEST['captcha_booster_time'] ) && isset( $_REQUEST['ux_txt_captcha_input'] ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		if ( 0 === strcasecmp( trim( captcha_booster_decode( wp_unslash( $_REQUEST['captcha_booster_result'] ), $str_key, wp_unslash( $_REQUEST['captcha_booster_time'] ) ) ), esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			return;
			/* Captcha was matched */
		} else {
			wp_die( esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . ':&nbsp' . esc_attr( $error_data_array['for_invalid_captcha_error'] ) );
		}
	}
}
/**
 * This function checks the captcha posted with lostpassword form.
 *
 * @param string $user .
 */
function captcha_booster_lost_password( $user ) {
	global $captcha_booster_options, $error_data_array, $errors;
	$str_key = $captcha_booster_options['captcha_key']['key'];

	/* If field 'user login' is empty - return */
	if ( isset( $_REQUEST['user_login'] ) && '' === esc_attr( wp_unslash( $_REQUEST['user_login'] ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		return;
	}

	/* If captcha doesn't entered */
	if ( isset( $_REQUEST['ux_txt_captcha_input'] ) && '' === esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
		return $error;
	}

	/* Check entered captcha */
	if ( isset( $_REQUEST['captcha_booster_result'] ) && isset( $_REQUEST['ux_txt_captcha_input'] ) && isset( $_REQUEST['captcha_booster_time'] ) && 0 === strcasecmp( trim( captcha_booster_decode( wp_unslash( $_REQUEST['captcha_booster_result'] ), $str_key, wp_unslash( $_REQUEST['captcha_booster_time'] ) ) ), esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_input'] ) ) ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
		return $user;
	} else {
		$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
		return $error;
	}
}
/**
 * Function for encoding number.
 *
 * @param string $string .
 * @param string $password .
 * @param string $captcha_time .
 */
function captcha_booster_encode( $string, $password, $captcha_time ) {
	/* Check if key for encoding is empty */
	if ( ! $password ) {
		die( esc_attr( __( 'Encryption password is not set', 'wp-captcha-booster' ) ) );
	}
	$salt   = md5( $captcha_time, true );
	$string = substr( pack( 'H*', sha1( $string ) ), 0, 1 ) . $string;
	$strlen = strlen( $string );
	$seq    = $password;
	$gamma  = '';
	while ( strlen( $gamma ) < $strlen ) {// @codingStandardsIgnoreLine.
		$seq    = pack( 'H*', sha1( $seq . $gamma . $salt ) );
		$gamma .= substr( $seq, 0, 8 );
	}
	return base64_encode( $string ^ $gamma );
}
/**
 * Function for decoding number.
 *
 * @param string $string_orignal .
 * @param string $key .
 * @param string $captcha_time .
 */
function captcha_booster_decode( $string_orignal, $key, $captcha_time ) {
	/* Check if key for encoding is empty */
	if ( ! $key ) {
		die( esc_attr( __( 'Decryption password is not set', 'wp-captcha-booster' ) ) );
	}
	$salt   = md5( $captcha_time, true );
	$strlen = strlen( $string_orignal );
	$seq    = $key;
	$gamma  = '';
	while ( strlen( $gamma ) < $strlen ) {// @codingStandardsIgnoreLine.
		$seq    = pack( 'H*', sha1( $seq . $gamma . $salt ) );
		$gamma .= substr( $seq, 0, 8 );
	}

	$string1        = base64_decode( $string_orignal );
	$string         = $string1 ^ $gamma;
	$decoded_string = substr( $string, 1 );
	$error          = ord( substr( $string, 0, 1 ) ^ substr( pack( 'H*', sha1( $decoded_string ) ), 0, 1 ) );

	if ( $error ) {
		return false;
	} else {
		return $decoded_string;
	}
}
/**
 * This function is used to generate random number.
 *
 * @param string $min .
 * @param string $max .
 * @param string $quantity .
 */
function captcha_booster_random_numbers( $min, $max, $quantity ) {
	$numbers = range( $min, $max );
	shuffle( $numbers );
	return array_slice( $numbers, 0, $quantity );
}
