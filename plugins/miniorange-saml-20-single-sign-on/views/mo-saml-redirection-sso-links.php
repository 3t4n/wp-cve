<?php
/**
 * File to display sections of Rederection & SSO Links tab.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to display Redirection & SSO Links tab.
 *
 * @return void
 */
function mo_saml_general_login_page() {
	$add_sso_button = get_option( Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON );
	if ( Mo_SAML_Utilities::mo_saml_is_sp_configured() && empty( $add_sso_button ) ) {
		update_option( Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON, 'true' );
		$add_sso_button = 'true';
	}
	?>
	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid" id="redir-sso-tab-form">
		<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-5">
			<?php
			mo_saml_display_sso_button_config( $add_sso_button );
			mo_saml_display_widget_config();
			mo_saml_display_auto_redirection_config();
			mo_saml_display_redirect_from_wp_login_config();
			mo_saml_display_shortcode_config();
			?>

			</div>
			<?php mo_saml_display_support_form(); ?>
		</div>
	<?php
}

/**
 * Function to display the SSO button configurations.
 *
 * @param bool $add_sso_button_wp it adds SSO button according to configurations.
 * @return void
 */
function mo_saml_display_sso_button_config( $add_sso_button_wp ) {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Option 1: Use a Single Sign-On button
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Redirection-SSO#Login-button" rel="noopener noreferrer" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path>
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-4">
			<div class="mo-saml-bootstrap-col-md-7">
				<h6>Add a Single Sign-On button on the WordPress login page</h6>
			</div>
			<div class="mo-saml-bootstrap-col-md-5">
				<form id="mo_saml_add_sso_button_wp_form" method="post" action="">
					<?php wp_nonce_field( 'mo_saml_add_sso_button_wp_option' ); ?>
					<input type="hidden" name="option" value="mo_saml_add_sso_button_wp_option" />
					<input type="checkbox" id="switch-sso-btn" name="mo_saml_add_sso_button_wp" <?php checked( 'true' === $add_sso_button_wp ); ?> class="mo-saml-switch mo-saml-bootstrap-mt-4" onchange="document.getElementById('mo_saml_add_sso_button_wp_form').submit();" value="true" />
					<label class="mo-saml-switch-label" for="switch-sso-btn" title="You can only add a Single Sign On button after saving your Service Provider Settings."></label>
				</form>
			</div>
		</div>
		<div class="prem-info mo-saml-bootstrap-mt-4">
			<div class="prem-icn sso-btn-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
				<p class="prem-info-text sso-btn-prem-text">Customization of SSO/Login button is available in Premium, Enterprise and All-Inclusive versions of the plugin <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
			</div>
			<h5 class="form-head form-head-bar">Customize Single Sign-On Button</h5>
			<table class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-mt-4">
				<tbody>
					<tr>
						<td>
							<b>Shape</b>
						</td>
						<td>
							<b>Theme</b>
						</td>
						<td>
							<b>Size of the Button</b>
						</td>
					</tr>
					<tr>
						<td class="mo-saml-padding-block">
							<input type="radio" name="mo_saml_button_theme" class="mo-saml-bootstrap-d-inline-block" value="circle" disabled=""> Round
						</td>
						<td>
							<table>
								<tbody>
									<tr>
										<td>Button Color:</td>
										<td>
											<input type="text" name="mo_saml_button_color" class="color mo-saml-bootstrap-ms-2 mo-saml-bootstrap-text-white" value="#135e96" style="background-color: #135e96" disabled>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>
							<table>
								<tbody>
									<tr>
										<td>Width: </td>
										<td><input class="mo-saml-btn-size" type="text" name="mo_saml_button_width" value="200" disabled=""></td>
										<td><input type="button" class="button button-primary" value="-" disabled=""></td>
										<td><input type="button" class="button button-primary" value="+" disabled=""></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td class=" mo-saml-padding-block">
							<input type="radio" name="mo_saml_button_theme" class="mo-saml-bootstrap-d-inline-block" value="oval" checked="" disabled="">Rounded Edges
						</td>
						<td>
							<table>
								<tbody>
									<tr>
										<td>Button Text: </td>
										<td>
											<input class="mo-saml-bootstrap-ms-3 mo-saml-bootstrap-bg-light" type="text" name="mo_saml_button_text" value="Login with #IDP#" disabled="">
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>
							<table>
								<tbody>
									<tr class="longButton">
										<td>Height: </td>
										<td><input class="mo-saml-btn-size" type="text" name="mo_saml_button_height" value="50" disabled=""></td>
										<td><input type="button" class="button button-primary" value="-" disabled=""></td>
										<td><input type="button" class="button button-primary" value="+" disabled=""></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td class=" mo-saml-padding-block">
							<input type="radio" name="mo_saml_button_theme" class="mo-saml-bootstrap-d-inline-block" value="square" disabled=""> Square
						</td>
						<td>
							<table>
								<tbody>
									<tr>
										<td>Font Color:</td>
										<td>
											<input type="text" name="mo_saml_font_color" class="color mo-saml-bootstrap-ms-4" value="#ffffff" disabled="">
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>
							<table>
								<tbody>
									<tr class="longButton">
										<td>Curve: </td>
										<td><input class="mo-saml-btn-size" type="text" name="mo_saml_button_curve" value="5" disabled=""></td>
										<td><input type="button" class="button button-primary" value="-" disabled=""></td>
										<td><input type="button" class="button button-primary" value="+" disabled=""></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td class=" mo-saml-padding-block">
							<input type="radio" name="mo_saml_button_theme" class="mo-saml-bootstrap-d-inline-block" disabled=""> Long Button with Text
						</td>
						<td>
							<table>
								<tbody>
									<tr>
										<td>Font Size:</td>
										<td>
											<table>
												<tbody>
													<tr>
														<td><input type="text" class="mo-saml-btn-size mo-saml-bootstrap-ms-4" name="mo_saml_font_size" value="20" disabled=""></td>
														<td><input type="button" class="button button-primary" value="-" disabled=""></td>
														<td><input type="button" class="button button-primary" value="+" disabled=""></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}

/**
 * Function to configure the login widget to be displayed.
 *
 * @return void
 */
function mo_saml_display_widget_config() {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Option 2: Use a Widget
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Redirection-SSO#SSO-Links" rel="noopener noreferrer" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path>
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<h6 class="mo-saml-bootstrap-mt-4">Add the SSO Widget by following the instructions below. This will add the SSO link on your site.</h6>
		<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
			<ol>
				<li>Go to Appearances &gt; <a href="<?php echo esc_url( get_admin_url() . 'widgets.php' ); ?>">Widgets.</a></li>
				<li>Click on Add Block ("+" sign) at the top left corner, besides the heading Widgets.</li>
				<li>In the search box, search for "Login with ", and drag and drop this block to your favourite location.</li>
				<li>Click on the "Update" button at the top right to save the widget settings.</li>
			</ol>
		</div>
	</div>
	<?php
}

/**
 * Function to display auto-redirection configuration to users.
 *
 * @return void
 */
function mo_saml_display_auto_redirection_config() {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Option 3: Auto-Redirection from site
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Redirection-SSO#Auto-Redirection-from-site" rel="noopener noreferrer" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<div class="prem-info mo-saml-bootstrap-mt-4">
			<div class="prem-icn auto-redir-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
				<p class="prem-info-text auto-redir-text">Auto-Redirection from site is configurable in Standard, Premium, Enterprise and All-Inclusive versions of the plugin <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
			</div>
			<h6 class="mo-saml-bootstrap-mt-5">1. Select this option if you want to restrict your site to only logged in users. Selecting this option will redirect the users to your IdP if logged in session is not found.</h6>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-3">
				<div class="mo-saml-bootstrap-col-md-7">
					<p>Redirect to IdP if user not logged in [PROTECT COMPLETE SITE] <span class="mo-saml-bootstrap-text-danger">* </span>: </p>
				</div>
				<div class="mo-saml-bootstrap-col-md-5">
					<input type="checkbox" id="switch" class="mo-saml-switch" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

				</div>
			</div>
			<hr />
			<h6>2. It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</h6>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-3">
				<div class="mo-saml-bootstrap-col-md-7">
					<p>Force authentication with your IdP on each login attempt <span class="mo-saml-bootstrap-text-danger">* </span>: </p>
				</div>
				<div class="mo-saml-bootstrap-col-md-5">
					<input type="checkbox" id="switch" class="mo-saml-switch" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

				</div>
			</div>
		</div>
	</div>

	<?php
}

/**
 * Function to display the redirect-from-wp-login-page configuration.
 *
 * @return void
 */
function mo_saml_display_redirect_from_wp_login_config() {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Option 4: Auto-Redirection from WordPress Login
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Redirection-SSO#Auto-Redirection-from-WP-login" rel="noopener noreferrer" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<div class="prem-info mo-saml-bootstrap-mt-4">
			<div class="prem-icn auto-redir-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
				<p class="prem-info-text auto-redir-wp-text">Auto-Redirection from WordPress is configurable in Standard, Premium, Enterprise and All-Inclusive versions of the plugin <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
			</div>
			<h6 class="mo-saml-bootstrap-mt-5">1. Select this option if you want the users visiting any of the following URLs to get redirected to your configured IdP for authentication:</h6>
			<h6><code class="bg-cstm mo-saml-bootstrap-text-dark mo-saml-bootstrap-rounded"><?php echo esc_url( wp_login_url() ); ?></code> or <code class="bg-cstm mo-saml-bootstrap-text-dark mo-saml-bootstrap-rounded"><?php echo esc_url( admin_url() ); ?></code></h6>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-6">
					<p>Redirect to IdP from WordPress Login Page <span class="mo-saml-bootstrap-text-danger">* </span>: </p>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<input type="checkbox" id="switch" class="mo-saml-switch" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

				</div>
			</div>
			<hr>
			<h6 class=" mt-2">2. Select this option to enable backdoor login if auto-redirect from WordPress Login is enabled.</h6>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-6">
					<p>Checking this option creates a backdoor to login to your Website using WordPress credentials incase you get locked out of your IdP <span class="mo-saml-bootstrap-text-danger">* </span>: </p>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<br>
					<input type="checkbox" id="switch" class="mo-saml-switch" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Function to display the shortcode configurations.
 *
 * @return void
 */
function mo_saml_display_shortcode_config() {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Option 5: Use a ShortCode
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Redirection-SSO#SSO-Links" rel="noopener noreferrer" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path>
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<div class="prem-info mo-saml-bootstrap-mt-4">
			<div class="prem-icn auto-redir-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>" width="35px">
				<p class="prem-info-text shortcode-text">These options are configurable in the Standard, Premium, Enterprise and All-Inclusive version of the plugin. <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top">
				<div class="mo-saml-bootstrap-col-md-6">
					<p>Check this option if you want to add a shortcode to your page <span class="mo-saml-bootstrap-text-danger">* </span> </p>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<input type="checkbox" id="switch" class="mo-saml-switch" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

				</div>
			</div>
		</div>
	</div>
	<?php
}
