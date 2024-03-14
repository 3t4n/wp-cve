<?php
/**
 * This file takes care of rendering the support form.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The function displays the support form in the plugin.
 *
 * @param boolean $display_attrs flag to determine to display attributes or not.
 */
function mo_saml_display_support_form( $display_attrs = false ) {   ?>
	<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ps-0">
		<?php

		if ( $display_attrs && ! empty( get_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ATTRS ) ) ) {
			mo_saml_display_attrs_list();
		} else {

			?>
			<div class="mo-saml-bootstrap-bg-white mo-saml-bootstrap-text-center shadow-cstm mo-saml-bootstrap-rounded contact-form-cstm">
				<form method="post" action="">
					<?php wp_nonce_field( 'mo_saml_contact_us_query_option' ); ?>
					<input type="hidden" name="option" value="mo_saml_contact_us_query_option" />

					<div class="contact-form-head">
						<p class="mo-saml-bootstrap-h5">Feature Request/Contact Us <br> (24*7 Support)</p>
						<p class="mo-saml-bootstrap-h6 mo-saml-bootstrap-mt-3"> Call us at +1 978 658 9387 in case of any help</p>
					</div>
					<div class="contact-form-body mo-saml-bootstrap-p-3">
						<input type="email" id="mo_saml_support_email" placeholder="<?php esc_attr_e( 'Enter your email', 'miniorange-saml-20-single-sign-on' ); ?>" class="mo_saml_table_textbox mo-saml-bootstrap-mt-4" name="mo_saml_contact_us_email" value="<?php echo esc_attr( ( empty( get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL ) ) ) ? get_option( 'admin_email' ) : get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL ) ); ?>" required>
						<input type="tel" id="contact_us_phone" pattern="[\+]?[0-9]{1,4}[\s]?([0-9]{4,12})*" class="mo_saml_table_textbox mo-saml-bootstrap-mt-4" name="mo_saml_contact_us_phone" value="<?php echo esc_attr( empty( get_option( Mo_Saml_Customer_Constants::ADMIN_PHONE ) ) ? '' : get_option( Mo_Saml_Customer_Constants::ADMIN_PHONE ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your phone', 'miniorange-saml-20-single-sign-on' ); ?>">
						<textarea class="mo_saml_table_textbox mo-saml-bootstrap-mt-4"  name="mo_saml_contact_us_query" rows="4" style="resize: vertical;" required placeholder="<?php esc_attr_e( 'Write your query here', 'miniorange-saml-20-single-sign-on' ); ?>" id="mo_saml_query"></textarea>
						<div class="mo-saml-call-setup mo-saml-bootstrap-mt-4 mo-saml-bootstrap-p-3">
							<h6>Setup a Call / Screen-share session with miniOrange Technical Team</h6>
							<hr />
							<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-3">
								<div class="mo-saml-bootstrap-col-md-9">
									<h6 class="mo-saml-bootstrap-text-secondary">Enable this option to setup a call</h6>
								</div>
								<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-ps-0">
									<input type="checkbox" id="saml_setup_call" name="saml_setup_call" class="mo-saml-switch" /><label class="mo-saml-switch-label" for="saml_setup_call"></label>
								</div>
							</div>
							<div id="call_setup_dets" class="call-setup-details">
								<div class="mo-saml-bootstrap-row">
									<div class="mo-saml-bootstrap-col-md-3"><strong><?php esc_html_e( 'TimeZone', 'miniorange-saml-20-single-sign-on' ); ?><font color="#FF0000">*</font>:</strong></div>
									<div class="mo-saml-bootstrap-col-md-9">
										<select id="js-timezone" class="mo-saml-select-timezone" name="mo_saml_setup_call_timezone">
											<?php $zones = Mo_Saml_Time_Zones::$time_zones; ?>
											<option value="" selected disabled>---------<?php esc_html_e( 'Select your timezone', 'miniorange-saml-20-single-sign-on' ); ?>--------</option> 
																											<?php
																											foreach ( $zones as $zone => $value ) {
																												if ( 'Etc/GMT' === $value ) {
																													?>
													<option value="<?php echo esc_attr( $value ); ?>" selected><?php echo esc_attr( $zone ); ?></option>
																													<?php
																												} else {
																													?>
													<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $zone ); ?></option>
																													<?php
																												}
																											}
																											?>
										</select>
									</div>
								</div>
								<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-text-start mo-saml-bootstrap-mt-4">
									<div class="mo-saml-bootstrap-col-md-6 call-setup-datetime">
										<strong> <?php esc_html_e( 'Date', 'miniorange-saml-20-single-sign-on' ); ?><font color="#FF0000">*</font>:</strong><br>
									</div>
									<div class="mo-saml-bootstrap-col-md-6">
										<input type="text" id="datepicker" class="call-setup-textbox mo-saml-bootstrap-ps-2 mo-saml-bootstrap-pt-1 mo-saml-bootstrap-pb-0" placeholder="<?php esc_attr_e( 'Select Date', 'miniorange-saml-20-single-sign-on' ); ?>" autocomplete="off" name="mo_saml_setup_call_date">
									</div>
									<div class="mo-saml-bootstrap-col-md-6 call-setup-datetime mo-saml-bootstrap-mt-3">
										<strong> <?php esc_html_e( 'Time (24-hour)', 'miniorange-saml-20-single-sign-on' ); ?><font color="#FF0000">*</font>:</strong><br>
									</div>
									<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-mt-3">
										<input type="text" id="timepicker" placeholder="<?php esc_attr_e( 'Select Time', 'miniorange-saml-20-single-sign-on' ); ?>" class="call-setup-textbox mo-saml-bootstrap-ps-2 mo-saml-bootstrap-pt-1 mo-saml-bootstrap-pb-0" autocomplete="off" name="mo_saml_setup_call_time">
									</div>
								</div>
								<div>
									<p class="mo-saml-bootstrap-mt-4 mo-saml-bootstrap-text-danger call-setup-notice">
										<?php esc_html_e( 'Call and Meeting details will be sent to your email. Please verify the email before submitting your query.', 'miniorange-saml-20-single-sign-on' ); ?>
									</p>
								</div>
							</div>
						</div>
						<input type="submit" value="Submit" class="mo-saml-bs-btn btn-cstm mo-saml-bootstrap-text-white mo-saml-bootstrap-mt-4 mo-saml-bootstrap-w-50">
					</div>
				</form>
			</div>

			<?php
		}
		//PHPCS:ignore -- WordPress.Security.NonceVerification.Recommended -- GET parameter for checking the current page name from the URL doesn't require nonce verification.
		$page = isset( $_GET['page'] ) ? wp_unslash( $_GET['page'] ) : '';
		mo_saml_display_keep_settings_intact_section();
		mo_saml_display_suggested_idp_integration();
		if ( 'mo_saml_enable_debug_logs' !== $page ) {
			mo_saml_troubleshoot_card();
		}
		mo_saml_display_suggested_add_ons();
		?>
	</div>

	<?php
}
