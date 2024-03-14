<?php
/**
 * This file shows the plugin settings on frontend.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$setup_dirname = dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'link-tracer.php';
require $setup_dirname;

?>

<?php
/**
 * Show the roles and checkboxes to enable/disable 2FA.
 *
 * @param object $current_user current logged in user.
 * @return void
 */
function mls_2_factor_user_roles( $current_user ) {

	global $user_roles;
	if ( ! isset( $user_roles ) ) {
		$user_roles = new WP_Roles();
	}

		print '<div><span style="font-size:16px;">Roles<div style="float:right;">Custom Redirect Login Url <b style = "color:red"> [PREMIUM] </b> </div></span><br /><br />';
	foreach ( $user_roles->role_names as $id => $name ) {
		$setting = get_site_option( 'mo2fa_' . $id );
		?>
				<div>
					<input type="checkbox" name="role" value="<?php echo 'mo2fa_' . esc_attr( $id ); ?>" 
			<?php
			if ( 'administrator' === $id ) {
				if ( get_site_option( 'mo2fa_administrator' ) ) {
					echo 'checked';
				} else {
					echo 'unchecked';
				}
			} else {
				echo 'disabled';
			}
			?>
					/>
				<?php
				echo esc_html( $name );
				if ( 'Administrator' !== $name ) {
					echo " <b style='color:red;padding-left:10px;'> [PREMIUM] </b>";
				}
				?>
					<input type="text" class="mo2f_table_textbox" style="width:50% !important;float:right;" id="<?php echo esc_attr( 'mo2fa_' . $id ); ?>_login_url" value="<?php echo esc_attr( get_site_option( 'mo2fa_' . $id . '_login_url' ) ); ?>" 
				<?php
					echo 'disabled';
				?>
					/>
				</div> 
				<br/>
		<?php
	}
		print '</div>';
}
			$user                 = wp_get_current_user();
			$configured_2famethod = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
			$configured_meth      = array();
			$configured_meth      = array( 'Email Verification', 'Google Authenticator', 'Security Questions', 'Authy Authenticator' );
			$method_exisits       = in_array( $configured_2famethod, $configured_meth, true );
?>
	<?php
	if ( get_site_option( 'is_onprem' ) === 1 && current_user_can( 'administrator' ) ) {
		?>
				<div class="mo2f_table_layout" id="2fa_method">
					<input type="hidden" name="option" value="" />
					<span>
							<h3>Select Roles to enable 2-Factor for Users <b  style="font-size: 70%;color: red;">(Upto 3 users in Free version)</b></h3>
						<span>

							<hr> <a href= '<?php echo esc_attr( $two_factor_premium_doc['Custom Redirect Login Url'] ); ?>' target="_blank">
						<span class="dashicons dashicons-text-page" title="More Information" style="font-size:19px;color:#4a47a3; margin-top:0.9em ;float: right;"></span>
						</a>


							<br>

			<?php
				esc_html( mls_2_factor_user_roles( $current_user ) );
			?>
							<br>
						</span>
						<input type="submit" id="save_role_2FA"  name="submit" value="Save Settings" class="momls_wpns_button momls_wpns_button1" />
					</span>
					<br><br>
					<div id="mo2f_note">
						<b>Note:</b> Selecting the above roles will enable 2-Factor for all users associated with that role.
					</div>
				</div>


	<script>
		jQuery("#save_role_2FA").click(function(){
			var enabledrole = [];
			$.each($("input[name='role']:checked"), function(){            
			enabledrole.push($(this).val());
			});
			var mo2fa_administrator_login_url   =   $('#mo2fa_administrator_login_url').val();
			var nonce = '<?php echo esc_js( wp_create_nonce( 'unlimittedUserNonce' ) ); ?>';
			var data =  {
			'action'                        : 'momls_two_factor_ajax',
			'momls_2f_two_factor_ajax'         : 'mo2f_role_based_2_factor',
			'nonce'                         :  nonce,
			'enabledrole'                   :  enabledrole,                    
			'mo2fa_administrator_login_url' :  mo2fa_administrator_login_url
			};
			jQuery.post(ajaxurl, data, function(response) {
				var response = response.replace(/\s+/g,' ').trim();
				if (response === "true"){
					jQuery('#mo_scan_message').empty();
					jQuery('#mo_scan_message').append("<div id='notice_div' class='overlay_success'><div class='popup_text'>&nbsp&nbsp Settings are saved.</div></div>");
					window.onload =  momls_nav_popup();
				}
			});
		});
	</script>

			<?php
	}
	if ( get_site_option( 'is_onprem' ) === 0 && current_user_can( 'administrator' ) ) {
		?>
		<div id="wpns_message" >
		</div>
		<div class="mo2f_table_layout" id="onpremisediv">
			<p class="modal-body-para" style="text-align: center;">
				<b>Two-Factor Authentication for Multiple Users<span style="color: red;"> [No Payment Needed]</span></b>
			</p>
			<hr>
			<p class="modal-body-para">
			<span  style="font-size: 15px;">
				<b>Current Solution</b>
			</span>
			<ul style="list-style-type:disc; padding-left: 5%;">
				<li style="font-size: 15px;">You are currently using a Cloud Solution for 2-factor Authentication</li>
				<li style="font-size: 15px;">In this solution miniOrange provides you 2-factor authentication free only for one user.</li>
			</ul>
			<br>
			<span  style="font-size: 15px;">
				<b>2FA For Multiple User</b>
			</span>
			<ul style="list-style-type:disc; padding-left: 5%;">
				<li style="font-size: 15px;">If you want to use 2-factor authentication for multiple users, you need to enable the WordPress Solution [On-Premise 2-factor Authentication].</li>
				<li  style="font-size: 15px;">You can get two-factor authentication <b>FREE</b> for <u>upto 3 Administrators</u>.</li> 
				<li  style="font-size: 15px;">By clicking the button below all dependecies will be shifted to WordPress [On-Premise Solution] and there will be no inclusion of any 3rd party not even miniOrange so this will increase the process speed for authentication.</li>
			</ul>
			<br>
			<span  style="font-size: 15px;color: red;">
				<b>Not Supported in WordPress Solution [On-Premise Solution]</b>
			</span>
			<ul style="list-style-type:disc; padding-left: 5%;">
				<li style="font-size: 15px;"><b>2FA Methods</b></li>
			</ul>
			<div style="padding-left: 10%;">
				<ul  style="font-size: 15px; list-style-type:circle;">
			<?php
			if ( get_site_option( 'mo2f_is_NC' ) === 0 ) {
				?>
						<li>OTP Over SMS</li>
				<?php
			}
			?>
					<li>miniOrange QR Code Authentication</li>
					<li>miniOrange Soft Token</li>
					<li>miniOrange Push Notification</li>
				</ul>
		</div>
		<ul style="list-style-type:disc; padding-left: 5%;">
			<li style="font-size: 15px;"><b>Remember Device</b></li>
			<li style="font-size: 15px;"><b>XML-RPC Login</b></li>
		</ul>
		</p>
		<strong style="color: #ff0000">[Note]: By enabling this you will have to reconfigure the second factor and all configuration of previous account will be deleted.</strong>
		<p class="modal-body-para">
			<h2  style="text-align:center"> Enable Two-Factor for all Users
			<label class='momls_wpns_switch' >
			<input type="checkbox" name="unlimittedUser" id="unlimittedUser"/>
			<span class='momls_wpns_slider momls_wpns_round'></span>
			</label>
			</h2>
			<hr>
			<p><i class="momls_wpns_not_bold"><h4> <strong style="color: #ff0000">[WARNING]: </strong> This will disconfigure the two-factor for the current account and you need to configure it again. By enabling it you will not be able to use the cloud solution again.</h4> </i></p>
			</p>


			</div>
<div id="ConfirmOnPrem" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" style="text-align: center; font-size: 20px; color: #ff0000">WARNING</h3>
					<p class="modal-body-para">
					<?php
					if ( $method_exisits && ! empty( $configured_2famethod ) ) {
						if ( $configured_2famethod === $configured_2famethod ) {
							?>
										Current 2FA method:- <b><?php echo esc_html( $configured_2famethod ); ?></b>
										<hr>
										<ul style="list-style-type:circle;font-size: 14px;text-align: left;">
											<li>Authy Authenticator and Google Authenticator are same in the WordPress Solution.</li>
											<li>You will need to reconfigure it if you want to proceed with WordPress Solution.</li>
										</ul>
									<?php
						} else {
							?>
										Current 2FA method:- <b><?php echo esc_html( $configured_2famethod ); ?></b>
										<hr> 
										<ul style="list-style-type:circle;font-size: 14px;text-align: left;">
											<li>You will need to reconfigure it if you want to proceed with WordPress Solution.</li>
										</ul>
									<?php
						}
					} elseif ( ! empty( $configured_2famethod ) ) {
						?>
									Current 2FA method:- <b><?php echo esc_html( $configured_2famethod ); ?></b>
									<hr>
									<p>
									<ul style="list-style-type:circle;font-size: 14px;text-align: left;">
										<li>This method is <b> not supported </b> in WordPress Solution[On-Premise Solution]</li>
										<br>
										<li><b>You can still use other 2FA methods for multiple users by clicking on confirm.</b> </li>
								<?php
					} else {
						?>
									We support only the following 2-Factor Authentication methods in WordPress Solution.
									<br>
									<li>Google Authentication</li>
									<li>Security Questions</li>
						<?php if ( get_site_option( 'mo2f_is_NC' ) === 0 ) { ?>
										<li>Email Verification</li>
									<?php
						}
					}
					?>
				</p>
				<span id="closeConfirmOnPrem" class="modal-span-close">X</span>
				</div>
				<div class="modal-body_multi_user" style="height: auto">		  
				</div>
				<div class="modal-footer">
					<button type="button" class="momls_wpns_button momls_wpns_button1 modal-button" style="width: 40%;" id="ConfirmOnPremButton">Confirm</button>			
				</div>
			</div>
	</div>

	<div id="afterMigrate" class="modal" style="display: none;"  fixed>
		<div  class="modal-content" style="width: 80%;overflow: hidden;" >

		<div class="modal-header">
			<h3 class="modal-title" style="text-align: center; font-size: 20px; color: #2980b9">
			Select a method to set as your 2nd factor.  
			</h3>
		</div>

		<div class="modal-body_multi_user" fixed>
				<?php
				$user                 = wp_get_current_user();
				$configured_2famethod = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
				$configured_meth      = array();
				if ( get_site_option( 'mo2f_is_NC' ) === 0 ) {
					$configured_meth = array( 'Email Verification', 'Google Authenticator', 'Security Questions', 'Authy Authenticator' );
				} else {
					$configured_meth = array( 'Google Authenticator', 'Security Questions', 'Authy Authenticator' );
				}
				$method_exisits = in_array( $configured_2famethod, $configured_meth, true );
				?>
			<p class="modal-body-para">
				<?php
				if ( $method_exisits ) {
					?>
		<p class="modal-body-para">
		Your Current 2FA method : <b> <?php echo esc_html( $configured_2famethod ); ?></b>

			<p class="modal-body-para" style="font-size: 12px;color:#FF0000;padding-top: -5px;" >
					<?php
					if ( 'Email Verification' === $configured_2famethod ) {
						?>
							<b>Please Reconfigure your Email ID.</b>

						<?php
					} else {
						?>
							<b>In order to continue using <?php echo esc_html( $configured_2famethod ); ?> as your 2nd factor for authentication, You will need to reconfigure it
							</b>
						<?php
					}
					?>
					</p>
			<hr>


			<div id="reconfig">
					<?php
					if ( 'Google Authenticator' === $configured_2famethod ) {
						echo '

                <button class="momls_wpns_button momls_wpns_button1" style="width:100%;" onclick ="reconfigGA()" >Click here to Reconfigure <b style="font-weight: 700;">Google/Authy/LassPass Authenticator</b> </button>
                ';
					} elseif ( 'Security Questions' === $configured_2famethod ) {
						echo '
                <button class="momls_wpns_button momls_wpns_button1" style="width:100%;" onclick ="reconfigKBA()" >Click here to Reconfigure <b style="font-weight: 700;">Security Questions</b> </button>
                ';
					}

					?>

			</div>
		</p>
		<div id="reconfigTable">
			<p class="modal-body-para">
			The following are the other 2-Factor Authentication methods that are available in the WordPress[On-Premise] version.
			</p>
			<div>
					<?php
					foreach ( $configured_meth as $value ) {
						if ( $value !== $configured_2famethod ) {
							if ( 'Security Questions' === $value ) {
								echo '
                        <button class="momls_wpns_button momls_wpns_button1" style="width:100%;" onclick ="reconfigKBA()" >Click here to Configure <b style="font-weight: 700;">Security Questions</b> </button>
                        ';
							} elseif ( 'Google Authenticator' === $value ) {
								echo '<button class="momls_wpns_button momls_wpns_button1" style="width:100%;" onclick ="reconfigGA()" >Click here to Configure <b style="font-weight: 700;">Google/Authy/LassPass Authenticator </b></button>';
							}
						}
						echo '<br>';
					}

					?>
			</div>
		</div>
			<div class="mo2f_align_center">
				<table id="Emailreconfig" style="display: none;" >
					<tr>
						<td>
						<b>Enter Your email that you will use as your 2nd factor.</b>
						</td>
					</tr>

					<tr>
						<td>
						<input type="text" name="" value="" id="emalEntered" />
						</td>
					</tr>

					<tr>
						<td>
						<input type="submit" id="save_email" name="" class="momls_wpns_button momls_wpns_button1" value="Save Email">

						<input type="button" id="emailBack" value="Back" class="momls_wpns_button momls_wpns_button1" />
						</td>
					</tr>
				</table>
			</div>
					<?php
				} else {
					?>




	<div class="modal-body_multi_user" fixed>
	<p class="modal-body-para">
					<?php
					if ( ! empty( $configured_2famethod ) ) {
						?>
						Your Current 2FA method : <b> <?php echo esc_html( $configured_2famethod ); ?></b>
	<p class="modal-body-para" style="font-size: 12px;color:#FF0000;padding-top: -5px;" >
	<b>
						<?php echo esc_html( $configured_2famethod ); ?> is not supported for Multiple users, please choose some other method as your 2 factor.
	</b>
	</p>
	<hr>
								<?php
					} else {
						echo '';

					}

					?>
	<div id="msg">
	<p class="modal-body-para">
	The following 2-Factor Authentication methods are available in the WordPress[On-Premise] version.
	</p>
						<?php
						echo '

    <button class="momls_wpns_button momls_wpns_button1" id="google_auth" style="width:100%;" onclick ="reconfigGA()" >Click here to Configure <b style="font-weight: 700;">Google/Authy/LassPass Authenticator</b> </button>
    ';
						echo '<br>';
						?>
						<?php
						echo '<br>';
						echo '
    <button class="momls_wpns_button momls_wpns_button1" id="secu_que" style="width:100%;" onclick ="reconfigKBA()" >Click here to Configure <b style="font-weight: 700;">Security Questions</b> </button>
    ';
						?>
	</div>
	<div class="mo2f_align_center">
		<table id="Emailreconfig" style="display: none;">
			<tr>
				<td>
				<b>Enter Your email that you will use as your 2nd factor.</b>
				</td>
			</tr>

			<tr>
				<td>
				<input type="text" name="" value="" id="emalEntered" />
				</td>
			</tr>

			<tr>
				<td>
				<input type="submit" id="save_email" name="" class="momls_wpns_button momls_wpns_button1" value="Save Email">

				<input type="button" id="emailBack" value="Back" class="momls_wpns_button momls_wpns_button1" />
				</td>
			</tr>
		</table>
	</div>
	</div>


					<?php
				}
				?>

	</p>
	</div>
</div>
	</div>

<script type="text/javascript">

function reconfigKBA(){
	var nonce = '<?php echo esc_js( wp_create_nonce( 'momls_two_factor_nonce' ) ); ?>';
			var data = {
				'action'                    : 'momls_two_factor_ajax',
				'momls_2f_two_factor_ajax'     : 'momls_shift_to_onprem',
				'nonce'                     :  nonce,
			};
			jQuery.post(ajaxurl, data, function(response) {

				if(response === 'true'){

					jQuery('#mo2f_configured_2FA_method_free_plan').val('SecurityQuestions');
					jQuery('#mo2f_selected_action_free_plan').val('configure2factor');
					jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
					openTab2fa(setup_2fa);
				}
			});
		}
function reconfigGA(){
	var nonce = '<?php echo esc_js( wp_create_nonce( 'momls_two_factor_nonce' ) ); ?>';
			var data = {
				'action'                    : 'momls_two_factor_ajax',
				'momls_2f_two_factor_ajax'     : 'momls_shift_to_onprem',
				'nonce'                     : 'momls_two_factor_nonce',
			};
			jQuery.post(ajaxurl, data, function(response) {

				if(response === 'true'){
					jQuery('#mo2f_configured_2FA_method_free_plan').val('GoogleAuthenticator');
					jQuery('#mo2f_selected_action_free_plan').val('configure2factor');
					jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
					openTab2fa(setup_2fa);
				}
			});
		}

</script>

<script type="text/javascript">
jQuery('#closeConfirmOnPrem').click(function(){
				document.getElementById('unlimittedUser').checked = false;
				window.location.reload();
		});
jQuery('#ConfirmOnPremButton').click(function(){
jQuery('#ConfirmOnPrem').hide();
var enableOnPremise = jQuery("input[name='unlimittedUser']:checked").val();
var nonce = '<?php echo esc_js( wp_create_nonce( 'momls_two_factor_nonce' ) ); ?>';
var data = {
			'action'					: 'momls_two_factor_ajax',
			'momls_2f_two_factor_ajax' 	: 'momls_unlimitted_user',
'nonce' :  nonce,
'enableOnPremise' :  enableOnPremise
};
jQuery.post(ajaxurl, data, function(response) {
var response = response.replace(/\s+/g,' ').trim();
if(response ==='OnPremiseActive')
{
jQuery('#wpns_message').empty();
jQuery('#wpns_message').append("<div class= 'notice notice-success is-dismissible' style='height : 25px;padding-top: 10px;  '> Congratulations! Now you can use 2-factor Authentication for your administrators for  free.  ");

jQuery('#onpremisediv').hide();
jQuery('#afterMigrate').show();
}
else if(response ==='OnPremiseDeactive')
{
jQuery('#wpns_message').empty();
jQuery('#wpns_message').append("<div class= 'notice notice-success is-dismissible' style='height : 25px;padding-top: 10px;  '> Cloud Solution deactivated");
close_modal();
}
else
{
jQuery('#wpns_message').empty();
jQuery('#wpns_message').append("<div class= 'notice notice-error is-dismissible' style='height : 25px;padding-top: 10px;  '> An Unknown Error has occured. ");
close_modal();
}
});

});

jQuery('#unlimittedUser').click(function(){
jQuery('#ConfirmOnPrem').css('display', 'block');
			jQuery('.modal-content').css('width', '35%');

});

</script>
<script type="text/javascript">

</script>

			<?php
	}
	?>
