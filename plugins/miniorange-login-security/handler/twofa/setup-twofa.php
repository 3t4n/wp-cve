<?php
/** This file contains functions related to twofa setup,
 *
 * @package miniorange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 */
	$mo2f_dir_name = dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR;
	require $mo2f_dir_name . 'setup-google-authenticator.php';
	require $mo2f_dir_name . 'setup-google-authenticator-onpremise.php';
	require $mo2f_dir_name . 'setup-authy-authenticator.php';
	require $mo2f_dir_name . 'setup-kba-questions.php';
	require $mo2f_dir_name . 'test-twofa-google-authy-authenticator.php';
	require $mo2f_dir_name . 'test-twofa-kba-questions.php';
/**
 * It is use to decode the 2fa methods.
 *
 * @param string $selected_2_factor_method It is carry the selected 2fa method.
 * @param string $decode_type It is carry the decode type .
 * @return string
 */
function momls_decode_2_factor( $selected_2_factor_method, $decode_type ) {

	if ( 'NONE' === $selected_2_factor_method ) {
		return $selected_2_factor_method;
	}
	$wpdb_2fa_methods = array(
		'GoogleAuthenticator' => 'Google Authenticator',
		'AuthyAuthenticator'  => 'Authy Authenticator',
		'SecurityQuestions'   => 'Security Questions',
	);

	$server_2fa_methods = array(
		'Google Authenticator' => 'GOOGLE AUTHENTICATOR',
		'Authy Authenticator'  => 'GOOGLE AUTHENTICATOR',
		'Security Questions'   => 'KBA',
	);

	$server_to_wpdb_2fa_methods = array(
		'GOOGLE AUTHENTICATOR' => 'Google Authenticator',
		'KBA'                  => 'Security Questions',
	);
	$two_factor_methods_doc     = array(
		'Security Questions'   => 'https://developers.miniorange.com/docs/security/wordpress/wp-security/step-by-setup-guide-to-set-up-security-question',
		'Google Authenticator' => 'https://developers.miniorange.com/docs/security/wordpress/wp-security/google-authenticator',
		'Authy Authenticator'  => 'https://developers.miniorange.com/docs/security/wordpress/wp-security/email_verification',
	);

	$two_factor_methods_video = array(
		'Security Questions'   => 'https://www.youtube.com/watch?v=pXPqQ047o-0',
		'Google Authenticator' => 'https://www.youtube.com/watch?v=BS6tY-Goa1Q',
		'Authy Authenticator'  => 'https://www.youtube.com/watch?v=BS6tY-Goa1Q',
	);

	if ( 'wpdb' === $decode_type ) {
		return $wpdb_2fa_methods[ $selected_2_factor_method ];
	} elseif ( 'server' === $decode_type ) {
		return $server_2fa_methods[ $selected_2_factor_method ];
	} elseif ( 'guide' === $decode_type && isset( $two_factor_methods_doc[ $selected_2_factor_method ] ) ) {
		return $two_factor_methods_doc[ $selected_2_factor_method ];
	} elseif ( 'video' === $decode_type ) {
		return $two_factor_methods_video[ $selected_2_factor_method ];

	} else {
		return $server_to_wpdb_2fa_methods[ $selected_2_factor_method ];
	}

}

/**
 * It is help to create 2fa form
 *
 * @param object $user It will carry the user .
 * @param string $category It will carry the category .
 * @param array  $auth_methods It will carry the auth methods .
 * @param string $can_display_admin_features .
 */
function momls_create_2fa_form( $user, $category, $auth_methods, $can_display_admin_features = '' ) {
	global $momlsdb_queries;
	$all_two_factor_methods          = array(
		'Google Authenticator',
		'Security Questions',
		'Authy Authenticator',
	);
	$two_factor_methods_descriptions = array(
		''                                  => '<b>All methods in the FREE Plan in addition to the following methods.</b>',
		'Google Authenticator'              => 'Enter the soft token from the account in your <b>Google/Authy/LastPass Authenticator App</b> to login.',
		'Security Questions'                => 'Answer the three security questions you had set, to login.',
		'Authy Authenticator'               => 'Enter the soft token from the account in your Authy Authenticator App to login.',
		'Email Verification'                => 'Accept the verification link sent to your email to login.',
		'miniOrange QR Code Authentication' => 'Scan the QR code from the account in your miniOrange Authenticator App to login.',
		'miniOrange Soft Token'             => 'Enter the soft token from the account in your miniOrange Authenticator App to login.',
		'miniOrange Push Notification'      => 'Accept a push notification in your miniOrange Authenticator App to login.',
		'Authy Authenticator'               => 'Enter the soft token from the account in your Authy Authenticator App to login.',
		'OTP Over SMS'                      => 'Enter the One Time Passcode sent to your phone to login.',
		'OTP Over Email'                    => 'Enter the One Time Passcode sent to your email to login.',
		'OTP Over SMS and Email'            => 'Enter the One Time Passcode sent to your phone and email to login.',
		'Hardware Token'                    => 'Enter the One Time Passcode on your Hardware Token to login.',
	);

	$two_factor_methods_ec = array_slice( $all_two_factor_methods, 0, 8 );
	$two_factor_methods_nc = array_slice( $all_two_factor_methods, 0, 5 );
	if ( get_site_option( 'is_onprem' ) || 'free_plan' !== $category ) {
		$all_two_factor_methods          = array(
			'Security Questions',
			'Google Authenticator',
			'Authy Authenticator',
		);
		$two_factor_methods_descriptions = array(
			''                                  => '<b>All methods in the FREE Plan in addition to the following methods.</b>',
			'Security Questions'                => 'Answer the three security questions you had set, to login.',
			'Google Authenticator'              => 'Enter the soft token from the account in your <b>Google/Authy/LastPass Authenticator App</b> to login.',
			'Authy Authenticator'               => 'Enter the soft token from the account in your Authy Authenticator App to login.',
			'Email Verification'                => 'Accept the verification link sent to your email to login.',
			'miniOrange QR Code Authentication' => 'Scan the QR code from the account in your miniOrange Authenticator App to login.',
			'miniOrange Soft Token'             => 'Enter the soft token from the account in your miniOrange Authenticator App to login.',
			'miniOrange Push Notification'      => 'Accept a push notification in your miniOrange Authenticator App to login.',
			'Authy Authenticator'               => 'Enter the soft token from the account in your Authy Authenticator App to login.',
			'OTP Over SMS'                      => 'Enter the One Time Passcode sent to your phone to login.',
			'OTP Over Email'                    => 'Enter the One Time Passcode sent to your email to login.',
			'OTP Over SMS and Email'            => 'Enter the One Time Passcode sent to your phone and email to login.',
			'Hardware Token'                    => 'Enter the One Time Passcode on your Hardware Token to login.',
		);
	}

	$is_customer_registered        = $momlsdb_queries->momls_get_user_detail( 'user_registration_with_miniorange', $user->ID ) === 'SUCCESS' ? true : false;
	$can_user_configure_2fa_method = $can_display_admin_features || ( ! $can_display_admin_features && $is_customer_registered );
	$is_nc                         = get_site_option( 'mo2f_is_NC' );
	$is_ec                         = ! $is_nc;
	echo '<form name="f" method="post" action="" id="mo2f_save_' . esc_html( $category ) . '_auth_methods_form">
                        <div id="mo2f_' . esc_html( $category ) . '_auth_methods" >
                            <br>
                            <table class="mo2f_auth_methods_table">';
	$len = count( $auth_methods );
	for ( $i = 0; $i < $len; $i ++ ) {

		echo '<tr>';
		$index = count( $auth_methods[ $i ] );
		for ( $j = 0; $j < $index; $j ++ ) {
			$auth_method             = $auth_methods[ $i ][ $j ];
			$auth_method_abr         = str_replace( ' ', '', $auth_method );
			$configured_auth_method  = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
			$is_auth_method_selected = ( $configured_auth_method === $auth_method ? true : false );

			$is_auth_method_av = false;
			if ( ( $is_ec && in_array( $auth_method, $two_factor_methods_ec, true ) ) ||
				( $is_nc && in_array( $auth_method, $two_factor_methods_nc, true ) ) ) {
				$is_auth_method_av = true;
			}

			$thumbnail_height = $is_auth_method_av && 'free_plan' === $category ? 190 : 100;
			$is_image         = empty( $auth_method ) ? 0 : 1;

			echo '<td style="width:33%;height: 203px;">
                        <div class="mo2f_thumbnail" id="' . esc_html( $auth_method_abr ) . '_thumbnail_2_factor" style="height:' . esc_html( $thumbnail_height ) . 'px;border:0px solid ';
			if ( get_site_option( 'is_onprem' ) ) {
				$iscurrent_method = 0;
				$current_method   = get_user_meta( $user->ID, 'currentMethod', true );
				if ( $current_method === $auth_method ) {
					$iscurrent_method = 1;
				}

				echo $iscurrent_method ? '#48b74b' : 'var(--mo2f-theme-color)';
				echo ';border-top:3px solid ';
				echo $iscurrent_method ? '#48b74b' : 'var(--mo2f-theme-color)';
				echo ';">';
			} else {
				echo $is_auth_method_selected ? '#48b74b' : 'var(--mo2f-theme-color)';
				echo ';border-top:3px solid ';
				echo $is_auth_method_selected ? '#48b74b' : 'var(--mo2f-theme-color)';
				echo ';">';

			}
						echo '<div>
			                    <div class="mo2f_thumbnail_method">
			                        <div style="width: 30%; float:left;">';

			if ( $is_image ) {
				echo '<img src="' . esc_url( plugins_url( 'includes/images/authmethods/' . sanitize_text_field( $auth_method_abr ) . '.png', dirname( dirname( __FILE__ ) ) ) ) . '" style="width: 40px;height: 40px !important; padding: 20px; line-height: 80px;" />';
			}

			echo '</div>
                        <div class="mo2f_thumbnail_method_desc" style="width: 75%;">';
			switch ( $auth_method ) {

				case 'Google Authenticator':
					echo '   <span style="float:right">
						 <a href=' . esc_url( momls_decode_2_factor( $auth_method, 'guide' ) ) . ' target="_blank">
						 <span title="View Setup Guide" class="dashicons dashicons-text-page" style="font-size:19px;color:#413c69;float: right;"></span>
						 </a>
						 <a href=' . esc_url( momls_decode_2_factor( $auth_method, 'video' ) ) . ' target="_blank">
						 <span title="Watch Setup Video" class="dashicons dashicons-video-alt3" style="font-size:18px;color:red;float: right;    margin-right: 5px;"></span>
						 </a>
					 </span>';
					break;

				case 'Security Questions':
					echo '   <span style="float:right">
						 <a href=' . esc_url( momls_decode_2_factor( $auth_method, 'guide' ) ) . ' target="_blank">
						 <span title="View Setup Guide" class="dashicons dashicons-text-page" style="font-size:19px;color:#413c69;float: right;"></span>
						   </a>
						   <a href=' . esc_url( momls_decode_2_factor( $auth_method, 'video' ) ) . ' target="_blank">
						 <span title="Watch Setup Video" class="dashicons dashicons-video-alt3" style="font-size:18px;color:red;float: right;    margin-right: 5px;"></span>
						 </a>

			  
					 </span>';
					break;

				case 'Authy Authenticator':
					echo '   <span style="float:right">
				         	<a href=' . esc_url( momls_decode_2_factor( $auth_method, 'guide' ) ) . ' target="_blank">
				         	<span title="View Setup Guide" class="dashicons dashicons-text-page" style="font-size:19px;color:#413c69;float: right;"></span>
				         	</a>
				         	<a href=' . esc_url( momls_decode_2_factor( $auth_method, 'video' ) ) . ' target="_blank">
				         	<span title="Watch Setup Video" class="dashicons dashicons-video-alt3" style="font-size:18px;color:red;float: right;    margin-right: 5px;"></span>
				         	</a>
				         </span>';
					break;

				default:
					echo '';
					break;
			}
			echo ' <b>' . esc_html( $auth_method ) .
					'</b><br>
                        <p style="padding:0px; padding-left:0px;font-size: 14px;"> ' . wp_kses_post( $two_factor_methods_descriptions[ $auth_method ] ) . '</p>
                        
                        </div>
                        </div>
                        </div>';

			if ( $is_auth_method_av && 'free_plan' === $category ) {
				$is_auth_method_configured = $momlsdb_queries->momls_get_user_detail( 'mo2f_' . sanitize_text_field( $auth_method_abr ) . '_config_status', $user->ID );

				echo '<div style="height:40px;width:100%;position: absolute;bottom: 0;background-color:';
				$iscurrent_method = 0;
				if ( get_site_option( 'is_onprem' ) ) {
					$current_method = get_user_meta( $user->ID, 'currentMethod', true );
					if ( $current_method === $auth_method ) {
						$iscurrent_method = 1;
					}
					echo $iscurrent_method ? '#48b74b' : 'var(--mo2f-theme-color)';
				} else {
					echo $is_auth_method_selected ? '#48b74b' : 'var(--mo2f-theme-color)';
				}
				if ( get_site_option( 'is_onprem' ) ) {

					$can_user_configure_2fa_method = true;

					$is_customer_registered = true;
					$user                   = wp_get_current_user();
					echo ';color:white">';
					$is_auth_method_configured = get_user_meta( $user->ID, $auth_method, true );

					$check = $is_customer_registered ? true : false;
					$show  = 0;
					if ( 'Security Questions' === $auth_method || 'Google Authenticator' === $auth_method ) {

						$show = 1;
					}
					if ( $check ) {
						echo '<div class="mo2f_configure_2_factor">
	                              <button type="button" id="' . esc_html( $auth_method_abr ) . '_configuration" class="mo2f_configure_set_2_factor" onclick="configureOrSet2ndFactor_' . esc_html( $category ) . '(\'' . esc_html( $auth_method_abr ) . '\', \'configure2factor\');"';
						echo $can_user_configure_2fa_method ? '' : ' disabled ';
						echo 1 === $show ? '' : ' disabled ';
						echo '>';
						if ( $show ) {
							echo $is_auth_method_configured ? 'Reconfigure' : 'Configure';
						} else {
							echo 'Available in cloud solution';
						}
						echo '</button></div>';
					}

					if ( ( $is_auth_method_configured && ! $is_auth_method_selected ) || get_site_option( 'is_onprem' ) ) {

						echo '<div class="mo2f_set_2_factor">
	                               <button type="button" id="' . esc_html( $auth_method_abr ) . '_set_2_factor" class="mo2f_configure_set_2_factor" onclick="configureOrSet2ndFactor_' . esc_html( $category ) . '(\'' . esc_html( $auth_method_abr ) . '\', \'select2factor\');"';
						echo $can_user_configure_2fa_method ? '' : ' disabled ';
						echo 1 === $show ? '' : ' disabled ';
						if ( 1 === $show && $is_auth_method_configured && 0 === $iscurrent_method ) {
							echo '>Set as 2-factor</button>
	                              </div>';
						}
					}

					echo '</div>';

				} else {

					echo ';color:white">';
					$check = ! $is_customer_registered ? true : ( 'Email Verification' !== $auth_method ? true : false );
					if ( $check ) {
						echo '<div class="mo2f_configure_2_factor">
	                              <button type="button" id="' . esc_html( $auth_method_abr ) . '_configuration" class="mo2f_configure_set_2_factor" onclick="configureOrSet2ndFactor_' . esc_html( $category ) . '(\'' . esc_html( $auth_method_abr ) . '\', \'configure2factor\');"';
						echo $can_user_configure_2fa_method ? '' : ' disabled ';
						echo '>';
						echo $is_auth_method_configured ? 'Reconfigure' : 'Configure';
						echo '</button></div>';
					}
					if ( ( $is_auth_method_configured && ! $is_auth_method_selected ) || get_site_option( 'is_onprem' ) ) {

						echo '<div class="mo2f_set_2_factor">
	                               <button type="button" id="' . esc_html( $auth_method_abr ) . '_set_2_factor" class="mo2f_configure_set_2_factor" onclick="configureOrSet2ndFactor_' . esc_html( $category ) . '(\'' . esc_html( $auth_method_abr ) . '\', \'select2factor\');"';
						echo $can_user_configure_2fa_method ? '' : ' disabled ';
						echo '>Set as 2-factor</button>
	                              </div>';
					}

					echo '</div>';
				}
			}
			echo '</div></div></td>';
		}

		echo '</tr>';
	}

	echo '</table>';
	if ( 'free_plan' !== $category ) {
		if ( current_user_can( 'administrator' ) ) {
			echo '<div style="background-color: var(--mo2f-theme-color);padding:10px;color:#fff">
                            <p style="font-size:16px;margin-left: 1%">In addition to these authentication methods, for other features in this plan, <a style="color:white;text-decoration:none" href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress-wp-2fa#pricing" target="_blank"><i>Click here.</i></a></p>
                 </div>';
		}
	}

	echo '</div> <input type="hidden" name="miniorange_save_form_auth_methods_nonce"
                   value="' . esc_attr( wp_create_nonce( 'miniorange-save-form-auth-methods-nonce' ) ) . '"/>
                <input type="hidden" name="option" value="mo2f_save_' . esc_html( $category ) . '_auth_methods" />
                <input type="hidden" name="mo2f_configured_2FA_method_' . esc_html( $category ) . '" id="mo2f_configured_2FA_method_' . esc_html( $category ) . '" />
                <input type="hidden" name="mo2f_selected_action_' . esc_html( $category ) . '" id="mo2f_selected_action_' . esc_html( $category ) . '" />
                </form>';

}

/**
 * It will use to activate Second factor
 *
 * @param object $user It will carry the user .
 * @return string
 */
function momls_get_activated_second_factor( $user ) {
	global $momlsdb_queries;
	$user_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $user->ID );
	$is_customer_registered   = $momlsdb_queries->momls_get_user_detail( 'user_registration_with_miniorange', $user->ID ) === 'SUCCESS' ? true : false;
	$useremail                = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );

	if ( 'MO_2_FACTOR_SUCCESS' === $user_registration_status ) {
		// checking this option for existing users.
		$momlsdb_queries->update_user_details( $user->ID, array( 'mobile_registration_status' => true ) );
		$mo2f_second_factor = 'MOBILE AUTHENTICATION';

		return $mo2f_second_factor;
	} elseif ( 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' === $user_registration_status ) {
		return 'NONE';
	} else {
		// for new users.
		if ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $user_registration_status && $is_customer_registered ) {
			$enduser  = new Momls_Two_Factor_Setup();
			$userinfo = json_decode( $enduser->momls_get_userinfo( $useremail ), true );

			if ( json_last_error() === JSON_ERROR_NONE ) {
				if ( 'ERROR' === $userinfo['status'] ) {
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( $userinfo['message'] ) );
					$mo2f_second_factor = 'NONE';
				} elseif ( 'SUCCESS' === $userinfo['status'] ) {
					$mo2f_second_factor = momls_update_and_sync_user_two_factor( $user->ID, $userinfo );
				} elseif ( 'FAILED' === $userinfo['status'] ) {
					$mo2f_second_factor = 'NONE';
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ACCOUNT_REMOVED' ) );
				} else {
					$mo2f_second_factor = 'NONE';
				}
			} else {
				update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_REQ' ) );
				$mo2f_second_factor = 'NONE';
			}
		} else {
			$mo2f_second_factor = 'NONE';
		}

		return $mo2f_second_factor;
	}
}
/**
 * It will update and sync the two factor settings
 *
 * @param string $user_id It will carry the user id .
 * @param object $userinfo It will carry the user info .
 * @return string
 */
function momls_update_and_sync_user_two_factor( $user_id, $userinfo ) {
	global $momlsdb_queries;
	$mo2f_second_factor = isset( $userinfo['authType'] ) && ! empty( $userinfo['authType'] ) ? $userinfo['authType'] : 'NONE';

	if ( 'KBA' === $mo2f_second_factor ) {
		$momlsdb_queries->update_user_details( $user_id, array( 'mo2f_SecurityQuestions_config_status' => true ) );
	} elseif ( 'GOOGLE AUTHENTICATOR' === $mo2f_second_factor ) {
		$app_type = get_user_meta( $user_id, 'mo2f_external_app_type', true );

		if ( 'Google Authenticator' === $app_type ) {
			$momlsdb_queries->update_user_details(
				$user_id,
				array(
					'mo2f_GoogleAuthenticator_config_status' => true,
				)
			);
			update_user_meta( $user_id, 'mo2f_external_app_type', 'Google Authenticator' );
		} elseif ( 'Authy Authenticator' === $app_type ) {
			$momlsdb_queries->update_user_details(
				$user_id,
				array(
					'mo2f_AuthyAuthenticator_config_status' => true,
				)
			);
			update_user_meta( $user_id, 'mo2f_external_app_type', 'Authy Authenticator' );
		} else {
			$momlsdb_queries->update_user_details(
				$user_id,
				array(
					'mo2f_GoogleAuthenticator_config_status' => true,
				)
			);

			update_user_meta( $user_id, 'mo2f_external_app_type', 'Google Authenticator' );
		}
	}

	return $mo2f_second_factor;
}
/**
 * It will help to display the customer registration
 *
 * @param object $user It will to show the.
 * @return void
 */
function momls_display_customer_registration_forms( $user ) {

	global $momlsdb_queries;
	$mo2f_current_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $user->ID );
	$mo2f_message                     = get_site_option( 'mo2f_message' );
	?>

	<div id="smsAlertModal" class="modal" role="dialog" data-backdrop="static" data-keyboard="false" >
		<div class="mo2f_modal-dialog" style="margin-left:30%;">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="mo2f_modal-header">
					<h2 class="mo2f_modal-title">You are just one step away from setting up 2FA.</h2><span type="button" id="mo2f_registration_closed" class="modal-span-close" data-dismiss="modal">&times;</span>
				</div>
				<div class="mo2f_modal-body">
					<span style="color:green;cursor: pointer;float:right;" onclick="show_content();">Why Register with miniOrange?</span><br>
				<div id="mo2f_register" style="background-color:#f1f1f1;padding: 1px 4px 1px 14px;display: none;">
					<p>miniOrange Two Factor plugin uses highly secure miniOrange APIs to communicate with the plugin. To keep this communication secure, we ask you to register and assign you API keys specific to your account.			This way your account and users can be only accessed by API keys assigned to you. Also, you can use the same account on multiple applications and your users do not have to maintain multiple accounts or 2-factors.</p>
				</div>
					<?php if ( $mo2f_message ) { ?>
						<div style="padding:5px;">
							<div class="alert alert-info" style="margin-bottom:0px;padding:3px;">
								<p style="font-size:15px;margin-left: 2%;"><?php wp_kses( $mo2f_message, array( 'b' => array() ) ); ?></p>
							</div>
						</div>
						<?php
					}
					if ( in_array( $mo2f_current_registration_status, array( 'REGISTRATION_STARTED', 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS', 'MO_2_FACTOR_OTP_DELIVERED_FAILURE', 'MOMLS_VERIFY_CUSTOMER' ), true ) ) {
						momls_show_registration_screen( $user );
					}
					?>
				</div>
			</div>
		</div>
		<form name="f" method="post" action="" class="mo2f_registration_closed_form">
			<input type="hidden" name="mo2f_registration_closed_nonce"
							value="<?php echo esc_attr( wp_create_nonce( 'mo2f-registration-closed-nonce' ) ); ?>"/>
			<input type="hidden" name="option" value="mo2f_registration_closed"/>
		</form>
	</div>
	<?php
	wp_register_script( 'mo2f_bootstrap_js', plugins_url( 'includes/js/bootstrap.min.js', dirname( dirname( __FILE__ ) ) ), array(), MO2F_VERSION, false );
	wp_print_scripts( 'mo2f_bootstrap_js' );
	?>
	<script>
		function show_content() {
			jQuery('#mo2f_register').slideToggle();
		}
		jQuery(function () {
			jQuery('#smsAlertModal').modal();
		});

		jQuery('#mo2f_registration_closed').click(function () {
			jQuery('.mo2f_registration_closed_form').submit();
		});
	</script>

	<?php
}
/**
 * It will help to show the registration screen
 *
 * @param object $user .
 * @return void
 */
function momls_show_registration_screen( $user ) {
	global $mo2f_dir_name;

	include $mo2f_dir_name . 'controllers/account.php';

}
/**
 * It will help to show the 2fa screen
 *
 * @param object $user .
 * @param string $selected_2fa_method  .
 * @return void
 */
function momls_show_2fa_configuration_screen( $user, $selected_2fa_method ) {
	global $mo2f_dir_name;
	switch ( $selected_2fa_method ) {
		case 'Google Authenticator':
			if ( get_site_option( 'is_onprem' ) ) {
				include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-momls-google-auth-onpremise.php';
				$obj = new Momls_Google_Auth_Onpremise();
				$obj->momls_gauth_get_details();
			} else {
				Momls_Miniorange_Authentication::momls_get_ga_parameters( $user );
				momls_configure_google_authenticator( $user );
			}
			break;
		case 'Authy Authenticator':
			momls_configure_authy_authenticator( $user );
			break;
		case 'Security Questions':
			momls_configure_for_mobile_suppport_kba( $user );
			break;
	}

}
/**
 * It will help to show the 2fa test screen
 *
 * @param object $user .
 * @param string $selected_2fa_method .
 * @return void
 */
function momls_show_2fa_test_screen( $user, $selected_2fa_method ) {
	switch ( $selected_2fa_method ) {
		case 'Security Questions':
			momls_test_kba_security_questions( $user );
			break;
		default:
			momls_test_google_authy_authenticator( $user, $selected_2fa_method );
	}

}
/**
 * It will help to display the name
 *
 * @param object $user .
 * @param string $mo2f_second_factor .
 * @return string
 */
function momls_method_display_name( $user, $mo2f_second_factor ) {

	if ( 'GOOGLE AUTHENTICATOR' === $mo2f_second_factor ) {
		$app_type = get_user_meta( $user->ID, 'mo2f_external_app_type', true );

		if ( 'Google Authenticator' === $app_type ) {
			$selected_method = 'Google Authenticator';
		} elseif ( 'Authy Authenticator' === $app_type ) {
			$selected_method = 'Authy Authenticator';
		} else {
			$selected_method = 'Google Authenticator';
			update_user_meta( $user->ID, 'mo2f_external_app_type', $selected_method );
		}
	} else {
		$selected_method = Momls_Utility::momls_decode_2_factor( $mo2f_second_factor, 'servertowpdb' );
	}
	return $selected_method;

}
/**
 * It will help to personalization
 *
 * @param string $mo2f_user_email .
 * @return void
 */
function momls_personalization_description( $mo2f_user_email ) {
	?>
	<div id="mo2f_custom_addon">
		<?php if ( get_site_option( 'mo2f_personalization_installed' ) ) { ?>
			<a href="<?php echo esc_url( admin_url() ); ?>plugins.php" id="mo2f_activate_custom_addon"
					class="momls_wpns_button momls_wpns_button1"
					style="float:right; margin-top:2%;"><?php esc_html_e( 'Activate Plugin', 'miniorange-login-security' ); ?></a>
				<?php } ?>
		<?php
		if ( ! get_site_option( 'mo2f_personalization_purchased' ) ) {
			?>
			<a
						onclick="mo2f_addonform('wp_2fa_addon_shortcode')" id="mo2f_purchase_custom_addon"
						class="momls_wpns_button momls_wpns_button1"
						style="float:right;"><?php esc_html_e( 'Purchase', 'miniorange-login-security' ); ?></a>
				<?php } ?>
		<div id="mo2f_custom_addon_hide">
			<br>
			<div id="mo2f_hide_custom_content">
				<div class="mo2f_box">
					<h3><?php esc_html_e( 'Customize Plugin Icon', 'miniorange-login-security' ); ?></h3>
					<hr>
					<p>
						<?php esc_html_e( 'With this feature, you can customize the plugin icon in the dashboard which is useful when you want your custom logo to be displayed to the users.', 'miniorange-login-security' ); ?>
					</p>
					<br>
					<h3><?php esc_html_e( 'Customize Plugin Name', 'miniorange-login-security' ); ?></h3>
					<hr>
					<p>
						<?php esc_html_e( 'With this feature, you can customize the name of the plugin in the dashboard.', 'miniorange-login-security' ); ?>
					</p>

				</div>
				<br>
				<div class="mo2f_box">
					<h3><?php esc_html_e( 'Customize UI of Login Pop up\'s', 'miniorange-login-security' ); ?></h3>
					<hr>
					<p>
						<?php esc_html_e( 'With this feature, you can customize the login pop-ups during two factor authentication according to the theme of your website.', 'miniorange-login-security' ); ?>
					</p>
				</div>

				<br>
				<div class="mo2f_box">
					<h3><?php esc_html_e( 'Custom Email and SMS Templates', 'miniorange-login-security' ); ?></h3>
					<hr>

					<p><?php esc_html_e( 'You can change the templates for Email and SMS which user receives during authentication.', 'miniorange-login-security' ); ?></p>

				</div>
			</div>
		</div>
		<div id="mo2f_custom_addon_show"><?php $x = apply_filters( 'mo2f_custom', 'custom' ); ?></div> 
	</div> 

	<?php
}

?>
