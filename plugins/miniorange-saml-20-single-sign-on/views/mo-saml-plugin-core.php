<?php
/**
 * This file contains the functions to display the plugin's core components.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Displays warnings for missing dependencies.
 *
 * @return void
 */
function mo_saml_display_plugin_dependency_warning() {
	if ( ! Mo_SAML_Utilities::mo_saml_is_curl_installed() ) {
		?>
		<p><span style="color: #FF0000; ">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank" rel="noopener noreferrer">PHP
					cURL extension</a> is not installed or disabled)</span></p>
		<?php
	}

	if ( ! Mo_SAML_Utilities::mo_saml_is_openssl_installed() ) {
		?>
		<p><span style="color: #FF0000; ">(Warning: <a href="http://php.net/manual/en/openssl.installation.php" target="_blank" rel="noopener noreferrer">PHP
					openssl extension</a> is not installed or disabled)</span></p>
		<?php
	}

	if ( ! Mo_SAML_Utilities::mo_saml_is_dom_installed() ) {
		?>
		<p><span style="color: #FF0000; ">(Warning: PHP
				dom extension is not installed or disabled)</span></p>
		<?php
	}
}

/**
 * Displays the plugin welcome page showing list of IDPs.
 *
 * @return void
 */
function mo_saml_display_welcome_page() {
	?>
	<input type="hidden" value="<?php echo esc_attr( get_option( Mo_Saml_Options_Enum::NEW_USER ) ); ?>" id="mo_modal_value">
	<div id="mo-saml-getting-started" class="modal">
		<div class="modal-dialog mo-saml-bootstrap-modal-dialog-centered" role="document">

			<div class="modal-content mo-saml-bootstrap-mt-3">
				<span id="mo_saml_modal_dismiss" class="mo-saml-bootstrap-pt-2" style="cursor: pointer" onclick="document.forms['mo_saml_welcome_form'].submit();"><i class="dashicons dashicons-dismiss mo-saml-bootstrap-float-end"></i></span>
				<div class="mo-saml-bootstrap-modal-header mo-saml-bootstrap-d-block mo-saml-bootstrap-text-center">
					<h2 class="h1 mo-saml-bootstrap-text-info" style="margin-top: -25px;"><?php esc_html_e( 'Let\'s get started!', 'miniorange-saml-20-single-sign-on' ); ?></h2>
					<div class="bg-cstm mo-saml-bootstrap-p-3 mo-saml-bootstrap-mt-3 mo-saml-bootstrap-rounded">
						<p class="mo-saml-bootstrap-h6"><b><?php esc_html_e( 'Hey, Thank you for installing miniOrange SSO using SAML 2.0 plugin', 'miniorange-saml-20-single-sign-on' ); ?></b>.</p>
						<p class="mo-saml-bootstrap-h6">
						<?php
						esc_html_e( 'We support all SAML 2.0 compliant Identity Providers. ', 'miniorange-saml-20-single-sign-on' );

										wp_kses( __( 'Please find some of the well-known <b>IdP configuration guides</b> below.' ), array( 'b' => array() ) );
										wp_kses( __( ' If you do not find your IDP guide here, do not worry! mail us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a>' ), array( 'a' => array( 'href' => array() ) ) );
						?>
										</p>
						<p class="mo-saml-bootstrap-h6">
							<?php esc_html_e( 'Make sure to check out the list of supported', 'miniorange-saml-20-single-sign-on' ); ?> 
							<a id="mo_saml_modal_dismiss" onclick="document.forms['mo_saml_welcome_form'].submit();" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'addons' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'add-ons', 'miniorange-saml-20-single-sign-on' ); ?></a> 
							<?php esc_html_e( 'to increase the functionality of your WordPress site.', 'miniorange-saml-20-single-sign-on' ); ?>
						</p>
					</div>
				</div>

				<div class="modal-body">
					<?php
					$index = 0;
					foreach ( Mo_Saml_Options_Plugin_Idp::$idp_guides as $key => $value ) {

						$url_string = 'https://plugins.miniorange.com/' . trim( $value[1] );

						if ( 0 === $index % 5 ) {
							?>
							<div class="idp-guides-btns">
							<?php } ?>
							<button class="guide-btn" onclick="window.open('<?php echo esc_url( $url_string ); ?>','_blank')"><img class="idp-guides-logo <?php echo esc_attr( $key ); ?>" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/idp-guides-logos/' . $value[0] . '.webp' ); ?>" /><?php echo esc_html( $key ); ?></button>
						<?php
						if ( 4 === $index % 5 ) {
							echo '</div>';
							$index = -1;
						}
						$index++;
					}

					?>
							</div>

				</div>
				<div class="modal-footer mo-saml-bootstrap-d-block" style="position: sticky;">
					<button onclick="document.forms['mo_saml_welcome_form'].submit();" type="button" class="btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-3" id="mo_saml_modal_dismiss"><?php esc_html_e( 'Configure Your IDP Now', 'miniorange-saml-20-single-sign-on' ); ?></button>
				</div>
			</div>

		</div>

	</div>
	<form method="post" action="" id="mo_saml_welcome_form">
		<?php wp_nonce_field( 'mo_saml_welcome_form' ); ?>
		<input type="hidden" name="option" value="mo_saml_welcome_form">
	</form>
	<script>
		document.onkeydown = function(evt) {
			evt = evt || window.event;
			if (evt.keyCode == 27) {
				document.forms['mo_saml_welcome_form'].submit();
			}
		};
	</script>
	<?php
}

/**
 * Renders the Plugin header.
 *
 * @param string $active_tab Contains the id of the current tab.
 * @return void
 */
function mo_saml_display_plugin_header( $active_tab ) {

	$sandbox_url = 'https://sandbox.miniorange.com/?mo_plugin=mo_saml&referer=' . site_url();
	?>
	<div class="wrap shadow-cstm mo-saml-bootstrap-p-3 mo-saml-bootstrap-me-0 mo-saml-bootstrap-mt-0 mo-saml-margin-left">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-4 mo-saml-bootstrap-h3 mo-saml-bootstrap-ps-4">
				<?php esc_html_e( 'miniOrange SSO using SAML 2.0', 'miniorange-saml-20-single-sign-on' ); ?>
			</div>
			<div class="mo-saml-bootstrap-col-md-4 mo-saml-bootstrap-text-end mo-saml-bootstrap-d-flex mo-saml-bootstrap-align-items-center">
				<a class="mo-saml-bootstrap-pb-3 mo-saml-bootstrap-pt-3 mo-saml-bootstrap-ps-4 mo-saml-bootstrap-pe-4 pop-up-btns" target="_blank" href="<?php echo esc_url( $sandbox_url ); ?>">Get a Free Trial</a>
			</div>
			<div class="mo-saml-bootstrap-col-md-4 mo-saml-bootstrap-text-end mo-saml-bootstrap-d-flex mo-saml-bootstrap-align-items-center mo-saml-bootstrap-justify-content-end">
				<a id="license_upgrade" class="mo-saml-bootstrap-text-white mo-saml-bootstrap-ps-4 mo-saml-bootstrap-pe-4 mo-saml-bootstrap-pt-2 mo-saml-bootstrap-pb-2 btn-prem prem-btn-cstm" href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank"><?php esc_html_e( 'Premium Plans | Upgrade Now', 'miniorange-saml-20-single-sign-on' ); ?></a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Renders the plugin tabs.
 *
 * @param string $active_tab Contains the id of the current tab.
 * @return void
 */
function mo_saml_display_plugin_tabs( $active_tab ) {
	?>
	<div class="bg-main-cstm mo-saml-bootstrap-pb-4 mo-saml-margin-left" id="container">
		<span id="mo-saml-message"></span>
		<div id="mo-saml-tabs" class="mo-saml-bootstrap-d-flex mo-saml-bootstrap-text-center mo-saml-bootstrap-pt-3 mo-saml-bootstrap-border-bottom mo_saml_padding_left_2">
			<a id="sp-setup-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'save' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'save' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Service Provider Setup', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="sp-meta-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'config' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'config' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Service Provider Metadata', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="attr-role-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'opt' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'opt' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Attribute/Role Mapping', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="redir-sso-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'general' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'general' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Redirection & SSO Links', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="addon-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'addons' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'addons' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Add-Ons', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="acc-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( ( 'account-setup' === $active_tab ? 'mo-saml-nav-tab-active' : '' ) ); ?>" href="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( add_query_arg( array( 'tab' => 'account-setup' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><?php esc_html_e( 'Account Setup', 'miniorange-saml-20-single-sign-on' ); ?></a>
		</div>
		<?php
		if ( 'save' === $active_tab ) {
			mo_saml_apps_config_saml();
		} elseif ( 'opt' === $active_tab ) {
			mo_saml_save_optional_config();
		} elseif ( 'config' === $active_tab ) {
			mo_saml_configuration_steps();
		} elseif ( 'general' === $active_tab ) {
			mo_saml_general_login_page();
		} elseif ( 'addons' === $active_tab ) {
			mo_saml_show_addons_page();
		} elseif ( 'account-setup' === $active_tab ) {
			if ( mo_saml_is_customer_registered_saml() ) {
				mo_saml_show_customer_details();
			} else {
				mo_saml_show_new_registration_page_saml();
			}
		} else {
			mo_saml_apps_config_saml();
		}
		?>
		<a class="contact-us-cstm mo-saml-bootstrap-d-none">
			<span class="mo-saml-bootstrap-d-flex mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-align-items-center mo-saml-bootstrap-pt-3 mo-saml-bootstrap-text-white">
				<svg width="16" height="16" fill="currentColor" class="mo-saml-bootstrap-mt-1" viewBox="0 0 16 16">
					<path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5z" />
				</svg> &nbsp;&nbsp;miniOrange Support
			</span>
		</a>
	</div>
	<?php
}

/**
 * Displays the troubleshoot section.
 *
 * @return void
 */
function mo_saml_troubleshoot_card() {
	?>
	<div class="mo-saml-bootstrap-bg-white mo-saml-bootstrap-text-center shadow-cstm mo-saml-bootstrap-rounded contact-form-cstm mo-saml-bootstrap-mt-4 mo-saml-bootstrap-p-4">
		<div class="mo-saml-call-setup mo-saml-bootstrap-p-3">
			<h6>Facing issues? Check out the Troubleshooting options available in the plugin</h6>
			<hr />
			<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-3 mo-saml-bootstrap-justify-content-center">
				<a href="?page=mo_saml_enable_debug_logs&tab=debug-logs" class="mo-saml-bs-btn btn-cstm mo-saml-bootstrap-text-white mo-saml-bootstrap-w-50">Troubleshoot</a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Displays the Keep Configuration Intact section.
 *
 * @return void
 */
function mo_saml_display_keep_settings_intact_section() {
	?>
	<div class="mo-saml-bootstrap-bg-white mo-saml-bootstrap-text-center shadow-cstm mo-saml-bootstrap-rounded contact-form-cstm mo-saml-bootstrap-mt-4 mo-saml-bootstrap-p-4" id="mo_saml_keep_configuration_intact">
		<div class="mo-saml-call-setup mo-saml-bootstrap-p-3">
			<h6 class="mo-saml-bootstrap-text-center">Keep configuration Intact</h6>
			<form name="f" method="post" action="" id="settings_intact">
				<?php wp_nonce_field( 'mo_saml_keep_settings_on_deletion' ); ?>
				<input type="hidden" name="option" value="mo_saml_keep_settings_on_deletion" />
				<hr>
				<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-3">
					<div class="mo-saml-bootstrap-col-md-9">
						<h6 class="mo-saml-bootstrap-text-secondary">Enabling this would keep your settings intact when plugin is uninstalled</h6>
					</div>
					<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-ps-0">
						<input type="checkbox" id="mo-saml-switch-keep-config" name="mo_saml_keep_settings_intact" class="mo-saml-switch" <?php checked( get_option( 'mo_saml_keep_settings_on_deletion' ) === 'true' ); ?> onchange="document.getElementById('settings_intact').submit();">
						<label class="mo-saml-switch-label" for="mo-saml-switch-keep-config"></label>
					</div>
				</div>
			</form>
		</div>
		<blockquote class="mo-saml-bootstrap-mt-3 mo-saml-bootstrap-mb-0">Please enable this option when you are updating to a Premium version</blockquote>
	</div>
	<?php
}

/**
 * Displays IDP related integration based on the selected IDP.
 *
 * @return void
 */
function mo_saml_display_suggested_idp_integration() {
	?>
	<div class="mo-saml-card-glass mo-saml-bootstrap-mt-4" id="mo-saml-ads-text">
		<div class="mo-saml-ads-text">
			<h5 class="mo-saml-bootstrap-text-center" id="mo-saml-ads-head">Wait! You have more to explore</h5>
			<hr />
			<ul class="mo-saml-bootstrap-ps-1">
				<p id="mo-saml-ads-cards-text"></p>
				<a target="_blank" rel="noopener noreferrer" href="" class="mo-saml-bootstrap-text-warning" id="ads-text-link">Azure AD / Office 365 Sync</a>
				<a target="_blank" rel="noopener noreferrer" href="" class="mo-saml-bootstrap-text-warning mo-saml-bootstrap-float-end" id="ads-knw-more-link">Azure AD / Office 365 Sync</a>
			</ul>
		</div>
	</div>
	<?php

}

/**
 * Displays recommended add-ons based on the installed plugins.
 *
 * @return void
 */
function mo_saml_display_suggested_add_ons() {
	$suggested_addons = Mo_Saml_Options_Suggested_Add_Ons::$suggested_addons;

	foreach ( $suggested_addons as $addon ) {
		?>

		<div class="mo-saml-card-glass mo-saml-bootstrap-mt-4">
			<div class="mo-saml-ads-text">
				<h5 class="mo-saml-bootstrap-text-center"><?php echo esc_html( $addon['title'] ); ?></h5>
				<hr />
				<ul class="mo-saml-bootstrap-ps-1">
					<p><?php echo esc_html( $addon['text'] ); ?></p>
					<a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( $addon['link'] ); ?>" class="mo-saml-bootstrap-text-warning">Download</a>
					<a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( $addon['knw-link'] ); ?>" class="mo-saml-bootstrap-text-warning mo-saml-bootstrap-float-end">Know More</a>
				</ul>
			</div>
		</div>
		<?php
	}
}
