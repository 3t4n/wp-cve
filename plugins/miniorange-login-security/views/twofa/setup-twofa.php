<?php
/**
 * This file contains plugin's main dashboard UI.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$user               = wp_get_current_user();
	$mo2f_second_factor = momls_get_activated_second_factor( $user );

	global $momlsdb_queries;

	$is_customer_admin_registered = get_site_option( 'mo_2factor_admin_registration_status' );
	$configured_2famethod         = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );

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
	$test_method = $selected_method;
} else {
	$selected_method = momls_decode_2_factor( $mo2f_second_factor, 'servertowpdb' );
	$test_method     = $selected_method;
}

if ( 'NONE' === $test_method ) {
			$test_method = 'Not Configured';
}

if ( 'NONE' !== $selected_method && 0 === get_site_option( 'is_onprem' ) ) {
	$momlsdb_queries->update_user_details(
		$user->ID,
		array(
			'mo2f_configured_2FA_method' => $selected_method,
			'mo2f_' . str_replace( ' ', '', $selected_method ) . '_config_status' => true,
		)
	);
	update_site_option( 'mo2f_configured_2_factor_method', $selected_method );
}

	$is_customer_admin          = current_user_can( 'manage_options' ) && get_site_option( 'mo2f_miniorange_admin' ) === $user->ID;
	$can_display_admin_features = ! $is_customer_admin_registered || $is_customer_admin ? true : false;


	$is_customer_registered = $momlsdb_queries->momls_get_user_detail( 'user_registration_with_miniorange', $user->ID ) === 'SUCCESS' ? true : false;
if ( get_user_meta( $user->ID, 'configure_2FA', true ) ) {

	$current_selected_method = get_user_meta( $user->ID, 'mo2f_2FA_method_to_configure', true );

	echo '<div class="mo2f_table_layout">';
		momls_show_2fa_configuration_screen( $user, $current_selected_method );
	echo '</div>';
} elseif ( get_user_meta( $user->ID, 'mo2f_test_2FA', true ) ) {
	$current_selected_method = get_user_meta( $user->ID, 'mo2f_2FA_method_to_test', true );
	echo '<div class="mo2f_table_layout">';
		momls_show_2fa_test_screen( $user, $current_selected_method );
	echo '</div>';
} elseif ( get_user_meta( $user->ID, 'register_account_popup', true ) && $can_display_admin_features ) {
	momls_display_customer_registration_forms( $user );
} else {

	$is_nc                   = get_site_option( 'mo2f_is_NC' );
	$free_plan_existing_user = array(
		'Security Questions',
		'miniOrange QR Code Authentication',
		'miniOrange Soft Token',
		'miniOrange Push Notification',
		'Google Authenticator',
		'Authy Authenticator',

	);

	$free_plan_new_user = array(
		'Google Authenticator',
		'Security Questions',
		'miniOrange Soft Token',
		'miniOrange QR Code Authentication',
		'miniOrange Push Notification',
	);

	$standard_plan_existing_user = array(
		'',
		'OTP Over Email',
		'OTP Over SMS and Email',
	);

	$standard_plan_new_user = array(
		'',
		'Email Verification',
		'OTP Over SMS',
		'OTP Over Email',
		'OTP Over SMS and Email',
		'Authy Authenticator',
	);

	$premium_plan = array(
		'Hardware Token',
	);

	if ( get_site_option( 'is_onprem' ) ) {
		$free_plan_existing_user = array(
			'Email Verification',
			'Security Questions',
			'Google Authenticator',
		);

		$free_plan_new_user = array(
			'Google Authenticator',
			'Security Questions',
		);
		$premium_plan       = array(
			'Hardware Token',
			'miniOrange QR Code Authentication',
			'miniOrange Soft Token',
			'miniOrange Push Notification',
			'Authy Authenticator',

		);
		$standard_plan_existing_user = array(
			'',
			'OTP Over Email',
			'OTP Over SMS and Email',
			'OTP Over SMS',
		);
		$standard_plan_new_user      = array(
			'',
			'Email Verification',
			'OTP Over SMS',
			'OTP Over Email',
			'OTP Over SMS and Email',
		);
	}

	$free_plan_methods_existing_user     = array_chunk( $free_plan_existing_user, 3 );
	$free_plan_methods_new_user          = array_chunk( $free_plan_new_user, 3 );
	$standard_plan_methods_existing_user = array_chunk( $standard_plan_existing_user, 3 );
	$standard_plan_methods_new_user      = array_chunk( $standard_plan_new_user, 3 );
	$premium_plan_methods_existing_user  = array_chunk( array_merge( $standard_plan_existing_user, $premium_plan ), 3 );
	$premium_plan_methods_new_user       = array_chunk( array_merge( $standard_plan_new_user, $premium_plan ), 3 );
	if ( get_site_option( 'is_onprem' ) ) {
		$selected_method        = get_user_meta( get_current_user_id(), 'currentMethod', true );
		$is_customer_registered = true;
		$test_method            = $selected_method;
		if ( empty( $selected_method ) ) {
			$selected_method = 'NONE';
			$test_method     = 'Not Configured';
		}
	}

	?>
		<div id="wpns_message"></div>
		<div class="mo2f_table_layout">
			<div>
				<div class="mo2f-top-content">
					<div class="mo2f_view_free_plan_auth_methods" onclick="show_free_plan_auth_methods()">
					<?php if ( $can_display_admin_features ) { ?>
								<span><?php esc_html_e( 'CURRENT PLAN', 'miniorange-login-security' ); ?></span>
							<?php } ?>
					</div>
					<button class="test_auth_button button button-primary" id="test" onclick="testAuthenticationMethod('<?php echo esc_html( $selected_method ); ?>');"
						<?php echo $is_customer_registered && ( 'NONE' !== $selected_method ) ? '' : ' disabled '; ?>>Test : <?php echo esc_html( $test_method ); ?> 
					</button>	
			</div>
					<?php
					momls_create_2fa_form( $user, 'free_plan', $is_nc ? $free_plan_methods_new_user : $free_plan_methods_existing_user, $can_display_admin_features );
					?>
			</div>
			<hr>
			<?php if ( $can_display_admin_features ) { ?>
				<div class="mo2f-premium-features">
				<span id="mo2f_premium_plan"> <a class="mo2f_view_premium_plan_auth_methods" onclick="show_premium_auth_methods()">
						<p><?php esc_html_e( 'PREMIUM PLAN', 'miniorange-login-security' ); ?></p></a></span>
					<?php momls_create_2fa_form( $user, 'premium_plan', $is_nc ? $premium_plan_methods_new_user : $premium_plan_methods_existing_user ); ?>

				</div>
				<br>
				<?php } ?>
				<form name="f" method="post" action="" id="mo2f_2factor_test_authentication_method_form">
					<input type="hidden" name="option" value="mo_2factor_test_authentication_method"/>
					<input type="hidden" name="mo2f_configured_2FA_method_test" id="mo2f_configured_2FA_method_test"/>
					<input type="hidden" name="mo_2factor_test_authentication_method_nonce"
							value="<?php echo esc_attr( wp_create_nonce( 'mo-2factor-test-authentication-method-nonce' ) ); ?>"/>
				</form>
				<form name="f" method="post" action="" id="mo2f_2factor_resume_flow_driven_setup_form">
					<input type="hidden" name="option" value="mo_2factor_resume_flow_driven_setup"/>
					<input type="hidden" name="mo_2factor_resume_flow_driven_setup_nonce"
							value="<?php echo esc_attr( wp_create_nonce( 'mo-2factor-resume-flow-driven-setup-nonce' ) ); ?>"/>
				</form>
		</div>
		<div id="EnterEmail" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
			<!--    <span class="close">&times;</span>  -->
				<div class="modal-header">
					<h3 class="modal-title" style="text-align: center; font-size: 20px; color: var(--mo2f-theme-color)">Email Address</h3><span id="closeEnterEmail" class="modal-span-close">X</span>
				</div>
				<div class="modal-body" style="height: auto">
					<h2><i>Enter your Email address :&nbsp;&nbsp;&nbsp;  <input type ='email' id='emailEntered' name='emailEntered' size= '50' required value=<?php echo esc_attr( $email ); ?>></i></h2> 
				</div>
				<div class="modal-footer">
					<button type="button" class="momls_wpns_button momls_wpns_button1 modal-button" id="save_entered_email">Save</button>
				</div>
			</div>
		</div>
		<script>
			jQuery("#mo2f_premium_plan_auth_methods").hide();
		jQuery('#test').click(function(){
				jQuery("#test").attr("disabled", true);
			});
			jQuery('#closeEnterEmail').click(function(){
							window.location.reload();
						});
			jQuery('#save_entered_email').click(function(){
				var email   = jQuery('#emailEntered').val();
				var nonce   = '<?php echo esc_html( wp_create_nonce( 'EmailVerificationSaveNonce' ) ); ?>';
				var user_id = '<?php echo esc_html( get_current_user_id() ); ?>';
				if( email !== '')
				{
					var data = {
					'action'                        : 'momls_two_factor_ajax',
					'momls_2f_two_factor_ajax'         : 'mo2f_save_email_verification', 
					'nonce'                         : nonce,
					'email'                         : email,
					'user_id'                       : user_id
					};
					jQuery.post(ajaxurl, data, function(response) {    
							var response = response.replace(/\s+/g,' ').trim();
							if(response==="settingsSaved")
							{
								jQuery('#mo2f_configured_2FA_method_free_plan').val('EmailVerification');
								jQuery('#mo2f_selected_action_free_plan').val('select2factor');
								jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
							}
							else if(response === "NonceDidNotMatch")
							{
								jQuery('#wpns_message').empty();
				jQuery('#wpns_message').append("<div id='notice_div' class='overlay_success'><div class='popup_text'>&nbsp; &nbsp; An unknown error has occured.</div></div>");
								window.onload = momls_nav_popup();
							}
							else
							{
								jQuery('#wpns_message').empty()
								jQuery('#wpns_message').append("<div id='notice_div' class='overlay_success'><div class='popup_text'>&nbsp; &nbsp; Invalid Email.</div></div>");
								window.onload = momls_nav_popup();
							}    
							close_modal();
						});
				}

			});

			function configureOrSet2ndFactor_free_plan(authMethod, action) {
				if(authMethod === 'EmailVerification')
				{
					var is_onprem       = '<?php echo esc_js( get_site_option( 'is_onprem' ) ); ?>';
					var is_registered   = '<?php echo esc_js( $email_registered ); ?>';
					if(is_onprem === 1 && is_registered!==0 && action !== 'select2factor')
					{
						jQuery('#EnterEmail').css('display', 'block');
						jQuery('.modal-content').css('width', '35%');
					}
					else
					{
						jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
						jQuery('#mo2f_selected_action_free_plan').val(action);
						jQuery('#mo2f_save_free_plan_auth_methods_form').submit();       
					}
				} 
				else
				{
					jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
					jQuery('#mo2f_selected_action_free_plan').val(action);
					jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
				}            
			}

			function testAuthenticationMethod(authMethod) {
				jQuery('#mo2f_configured_2FA_method_test').val(authMethod);
				jQuery('#loading_image').show();

				jQuery('#mo2f_2factor_test_authentication_method_form').submit();
			}

			function resumeFlowDrivenSetup() {
				jQuery('#mo2f_2factor_resume_flow_driven_setup_form').submit();
			}


			function show_free_plan_auth_methods() {
				jQuery("#mo2f_free_plan_auth_methods").slideToggle(1000);                
			}


			function show_premium_auth_methods() {
				jQuery("#mo2f_premium_plan_auth_methods").slideToggle(1000);
			}

			jQuery("#how_to_configure_2fa").hide();

			function show_how_to_configure_2fa() {
				jQuery("#how_to_configure_2fa").slideToggle(700);
			}

function momls_nav_popup() {
document.getElementById("notice_div").style.width = "40%";
setTimeout(function(){ $('#notice_div').fadeOut('slow'); }, 3000);
}
		</script>
<?php } ?>
