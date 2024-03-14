<?php
/** This file contains the functions to display the registered account information and to display the login and registration pages.
 *
 * @package     miniorange-saml-20-single-sign-on\views
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays the registered customer details on the Account Info page.
 */
function mo_saml_show_customer_details() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$server_url = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	} else {
		$server_url = '';
	}
	?>
	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid" action="" id="account-info-form">
		<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-5">
			<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">
				<h4 class="form-head"><?php esc_html_e( 'Thank you for registering with miniOrange', 'miniorange-saml-20-single-sign-on' ); ?></h4>
				<?php mo_saml_show_plugin_download_steps(); ?>
				<table class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-mt-4">
					<tr style="border: 0.5px solid #fff;background: #e9f0ff;">
						<td style="width:45%; padding: 10px;"><?php esc_html_e( 'miniOrange Account Email', 'miniorange-saml-20-single-sign-on' ); ?></td>
						<td style="width:55%; padding: 10px;"><?php echo esc_html( get_option( mo_saml_customer_constants::ADMIN_EMAIL ) ); ?></td>
					</tr>
					<tr style="border: 0.5px solid #fff;background: #e9f0ff;">
						<td style="width:45%; padding: 10px;"><?php esc_html_e( 'Customer ID', 'miniorange-saml-20-single-sign-on' ); ?></td>
						<td style="width:55%; padding: 10px;"><?php echo esc_html( get_option( mo_saml_customer_constants::CUSTOMER_KEY ) ); ?></td>
					</tr>
				</table>
				<br /><br />

				<table>
					<tr>
						<td>
							<form name="f1" method="post" action="" id="mo_saml_goto_login_form">
								<?php wp_nonce_field( 'change_miniorange' ); ?>
								<input type="hidden" value="change_miniorange" name="option" />
								<input type="submit" value="<?php esc_attr_e( 'Switch Account', 'miniorange-saml-20-single-sign-on' ); ?>" class="mo-saml-bs-btn btn-cstm" />
							</form>
						</td>
						<td>
							<a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank"><input type="button" class="mo-saml-bs-btn btn-cstm" value="<?php esc_attr_e( 'Check Licensing Plans', 'miniorange-saml-20-single-sign-on' ); ?>" /></a>
						</td>
					</tr>
				</table>

				<br />
			</div>
		</div>
		<?php mo_saml_display_support_form(); ?>
	</div>
	<?php
}

/**
 * Function to display steps to download paid plugin.
 */
function mo_saml_show_plugin_download_steps() {
	?>
		<div class="mo_saml_download_paid_plugin_notice">
			<img class="note_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/note.webp' ); ?>">
				<p id="mo_saml_download_paid_plugin_notice">
					<span class="entity-info">Hey! You are using the free version of the plugin. If you have already purchased,
						<a href="<?php echo esc_url( Mo_Saml_External_Links::FAQ_DOWNLOAD_PAID_PLUGIN ); ?>" target="_blank">you can follow these steps to download the paid plugin.</a>
					</span>
				</p>
		</div>
	<?php
}

/**
 * Displays the registration form to create the new account.
 */
function mo_saml_show_new_registration_page_saml() {
	?>
	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-m-4" id="acc-tab-form">
		<div class="mo-saml-bootstrap-p-4 mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">
			<h4 class="form-head">Register with miniOrange</h4>
			<div class="mo-saml-bootstrap-row mo-saml-bootstrap-justify-content-center">
				<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-mt-5">
					<h5 class="mo-saml-bootstrap-text-center mo-saml-why-reg-txt mo_saml_reg_page_header">Why should I register?</h5>
					<h5 class="mo-saml-bootstrap-text-center mo-saml-why-login-txt mo_saml_reg_page_header">Why should I login?</h5>
					<p class="mo-saml-bootstrap-mt-3 mo-saml-why-reg mo-saml-why-reg-txt"> You should register so that in case you need help, we can help you with step by step instructions. We support all known IdPs - ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2 etc. <b>You will also need a miniOrange account to upgrade to the premium version of the plugins.</b> We do not store any information except the email that you will use to register with us.</p>
					<p class="mo-saml-bootstrap-mt-3 mo-saml-why-reg mo-saml-why-login-txt">You should login so that you can easily reach out to us in case you face any issues while setting up the SSO with your IDP. <b>You will also need a miniOrange account to upgrade to the premium version of the plugins.</b> We do not store any information except the email that you will use to register with us.</p>
					<div class="mo-saml-bootstrap-text-center">
						<img src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . '/images/mo-saml-registration-form-bg.webp' ); ?>" width="46%" alt="WordPress saml registration form">
					</div>
				</div>
				<div class="mo-saml-bootstrap-col-md-5 mo-saml-bootstrap-mt-5 mo-saml-bootstrap-rounded reg-form">
					<form name="f" method="post" action="">
						<input type="hidden" name="option" value="mo_saml_register_customer" />
						<?php wp_nonce_field( 'mo_saml_register_customer' ); ?>

						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-mt-4 mo-saml-reg-field">
							<div class="mo-saml-bootstrap-col-md-6">
								<h6 class="mo-saml-bootstrap-text-secondary">Email <span class="mo-saml-bootstrap-text-danger">* </span>:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-ps-0">
								<input type="text" name="registerEmail" placeholder="person@example.com" required value="" class="mo-saml-bootstrap-w-100 mo-saml-reg-text-field">
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-mt-4 mo-saml-reg-field">
							<div class="mo-saml-bootstrap-col-md-6">
								<h6 class="mo-saml-bootstrap-text-secondary">Password <span class="mo-saml-bootstrap-text-danger">* </span>:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-ps-0">
								<input class="mo-saml-bootstrap-w-100 mo-saml-reg-text-field" required type="password" name="password" placeholder="Password (Min. length 6)" minlength="6" pattern="^[(\w)*(!@#$.%^&amp;*-_)*]+$" title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&amp;*) should be present.">
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-mt-4 mo-saml-reg-field">
							<div class="mo-saml-bootstrap-col-md-6">
								<h6 class="mo-saml-bootstrap-text-secondary">Confirm Password <span class="mo-saml-bootstrap-text-danger">* </span>:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-ps-0">
								<input class="mo-saml-bootstrap-w-100 mo-saml-reg-text-field" required type="password" name="confirmPassword" placeholder="Confirm your password" minlength="6" pattern="^[(\w)*(!@#$.%^&amp;*-_)*]+$">
							</div>
						</div>

						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-mt-4 mo-saml-already-reg-field">
							<div class="mo-saml-bootstrap-col-md-5">
								<h6 class="mo-saml-bootstrap-text-secondary">Email <span class="mo-saml-bootstrap-text-danger">* </span>:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7 mo-saml-bootstrap-ps-0">
								<input type="text" name="loginEmail" placeholder="person@example.com" required disabled="true" value="" class="mo-saml-bootstrap-w-100 mo-saml-login-text-field">
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-mt-4 mo-saml-already-reg-field">
							<div class="mo-saml-bootstrap-col-md-5">
								<h6 class="mo-saml-bootstrap-text-secondary">Password <span class="mo-saml-bootstrap-text-danger">* </span>:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7 mo-saml-bootstrap-ps-0">
								<input class="mo-saml-bootstrap-w-100 mo-saml-login-text-field" required type="password" name="password" disabled="true" placeholder="Password (Min. length 6)" minlength="6" pattern="^[(\w)*(!@#$.%^&amp;*-_)*]+$" title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&amp;*) should be present.">
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-mt-4 mo-saml-bootstrap-text-center">
							<div class="mo-saml-bootstrap-col-md-12">
								<input type="submit" name="submit" value="Register" class="mo-saml-bs-btn btn-cstm mo-saml-bootstrap-rounded w-176 mo-saml-bootstrap-me-0" id="mo_saml_reg_btn">
								<input type="submit" name="submit" value="Login" class="mo-saml-bs-btn btn-cstm mo-saml-bootstrap-rounded w-176 mo-saml-bootstrap-me-0" id="mo_saml_reg_login_btn">
							</div>
						</div>
						<div class="mo-saml-bootstrap-row">
							<div class="mo-saml-bootstrap-col-md-12">
							</div>
						</div>
						<div class="mo-saml-bootstrap-text-center">
							<input type="button" name="mo_saml_goto_login" id="mo_saml_goto_login" value="Already have an account?" class="mo-saml-bootstrap-border-0 mo-saml-bootstrap-text-info mt-2 mo-saml-bootstrap-h6 mo-saml-alredy-have-btn">
							<input type="button" name="back" value="Sign Up" class="mo-saml-bootstrap-border-0 mo-saml-bootstrap-text-info mt-2 mo-saml-bootstrap-h6 mo-saml-alredy-have-btn" id="mo_saml_reg_back_btn">
						</div>
						<div class="mo-saml-bootstrap-text-center mo-saml-bootstrap-text-secondary mo-saml-bootstrap-mt-3 mo-saml-bootstrap-pe-4 mo-saml-bootstrap-ps-4">
							<h6 class="mt-2 mo-saml-why-reg mo-saml-bootstrap-border mo-saml-bootstrap-rounded mo-saml-bootstrap-p-3">Need Help? Contact us at <a href="mailto:samlsupport@xecurify.com"><u class="mo-saml-bootstrap-text-info">samlsupport@xecurify.com</u></a> and we'll help you set up SSO with your IdP in no time.</h6>
						</div>
						<?php mo_saml_show_plugin_download_steps(); ?>
					</form>
				</div>
			</div>
		</div>
	</div>   
	<?php
}
