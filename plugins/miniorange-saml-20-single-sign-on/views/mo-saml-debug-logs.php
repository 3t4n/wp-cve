<?php
/**
 * File to display debug logs.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Used to show the UI part of the log feature to user screen.
 *
 * @return void
 */
function mo_saml_display_log_page() {
	$debugging_enabled = Mo_SAML_Logger::mo_saml_is_debugging_enabled();
	mo_saml_display_plugin_header( 'debug' );
	?>  
	<?php
	$active_tab = mo_saml_get_active_debug_tab();
	mo_saml_display_tabs_troubleshoot_page( $active_tab );
	if ( 'debug-logs' === $active_tab ) {
		?>
	<div class="bg-main-cstm mo-saml-margin-left mo-saml-bootstrap-pb-5">
			<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid">
				<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4">
					<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">
						<form action="" method="post" id="mo_saml_logger">
							<?php wp_nonce_field( 'mo_saml_logger' ); ?>
							<input type="hidden" name="option" value="mo_saml_logger" />
							<div class="mo-saml-bootstrap-row">
								<div class="mo-saml-bootstrap-col-md-6">
									<h4>SAML Debug Tools
									</h4>
								</div>
								<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-text-end">
									<?php
									$server_uri = '';
									if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
										$server_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
									}
									?>
									<a href="<?php echo esc_url( mo_saml_add_query_arg( array( 'tab' => 'save' ), $server_uri ) ); ?>" class="mo-saml-bootstrap-btn btn-cstm mo-saml-bootstrap-ms-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
										</svg>&nbsp; Back To Plugin Configuration</a>
								</div>
							</div>
							<div class="form-head"></div>
							<h5 class="mo-saml-bootstrap-mt-4">If you are facing any issues with the SSO, please follow these steps for easier debugging</h5>

							<h6 class="mo-saml-bootstrap-mt-4"><b>Step 1: </b>Enable the Debug Logs option below and reproduce
								the issue</h6>
							<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-top mo-saml-bootstrap-mt-4">
								<div class="mo-saml-bootstrap-col-md-7">
									<h6 class="text-secondary">Enable miniOrange SAML Debug Logs</h6>
								</div>
								<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-ps-0">
									<input type="checkbox" id="mo_saml_enable_debug_logs" name="mo_saml_enable_debug_logs" class="mo-saml-switch" value="true" onchange="submit();" 
									<?php
									if ( $debugging_enabled ) {
										echo ' checked ';}
									?>
									/>
									<label class="mo-saml-switch-label" for="mo_saml_enable_debug_logs"></label>
								</div>
							</div>

							<div class="mo-saml-bootstrap-text-center">
								<input type="submit" class="mo-saml-bootstrap-btn btn-cstm mo-saml-bootstrap-mt-4" name="clear" value="Clear Log Files" 
								<?php
								if ( ! $debugging_enabled ) {
									echo ' title="Enable debug logs first" disabled ';}
								?>
								>
							</div>
							<div class="call-setup-div mo-saml-bootstrap-mt-4">
								<h6 class="call-setup-heading"><strong>
										<span class="mo-saml-bootstrap-text-danger">Note: </span><u>If your wp-config.php is not
											writable</u>, follow the steps below to Enable debug logs Manually
									</strong></h6>

								<h6 class="mo-saml-bootstrap-mt-3">
									Copy this code <code>define('MO_SAML_LOGGING', true);</code>
									and paste it in the <a href="https://wordpress.org/support/article/editing-wp-config-php/">wp-config.php</a>
									file before the line
									<br> <code>/* That's all, stop editing! Happy publishing. */</code> to enable the miniOrange
									SAML logs.
								</h6>

							</div>

							<h6 class="mo-saml-bootstrap-mt-4"><b>Step 2: </b> Download the Debug Log File and Plugin Configurations</h6>

							<div class="mo-saml-bootstrap-text-center mo-saml-bootstrap-mt-4">
								<input type="submit" class="mo-saml-bootstrap-btn btn-cstm mt-4" name="download" value="Download Debug Logs" 
								<?php
								if ( ! $debugging_enabled ) {
									echo ' title="Enable debug logs first" disabled ';
								}
								?>
								>
							</div>
						</form>
					<h6 class="mo-saml-bootstrap-mt-4">Send this file to us at <a class="mo-saml-bootstrap-text-danger" href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a></h6>
					</div>
				</div>
				<?php mo_saml_display_support_form(); ?>
			</div>
	</div>
		<?php
	} elseif ( 'error-codes' === $active_tab ) {
		error_codes();
	} else {
		?>
		<div class="mo-saml-bootstrap-d-flex mo-saml-bootstrap-text-center mo-saml-bootstrap-pt-3 mo-saml-bootstrap-border-bottom mo-saml-bootstrap-justify-content-center">
			<a href="">FAQs</a>
		</div>
		<?php
	}

}
/**
 * This function returns the active tab in troubleshoot sub-menu.
 *
 * @return string
 */
function mo_saml_get_active_debug_tab() {
	 //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter in the URL for checking tab name doesn't require nonce verification.
	if ( isset( $_GET['tab'] ) ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter in the URL for checking tab name doesn't require nonce verification.
		$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
	} else {
		$active_tab = 'debug-logs';
	}
	return $active_tab;
}
/**
 * Display the troubleshoot tab.
 *
 * @param string $active_tab the selected tab in troubleshoot.
 * @return void
 */
function mo_saml_display_tabs_troubleshoot_page( $active_tab ) {
	?>
	<div class="bg-main-cstm mo-saml-bootstrap-pb-4 mo-saml-margin-left" id="container">
		<div class="mo-saml-bootstrap-d-flex mo-saml-bootstrap-text-center mo-saml-bootstrap-pt-3 mo-saml-bootstrap-border-bottom mo-saml-bootstrap-ps-5" id="mo-saml-tabs"> 

		<?php
		$server_uri = '';
		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$server_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
		?>
			<a id="sp-setup-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( 'debug-logs' === $active_tab ? 'mo-saml-nav-tab-active' : '' ); ?>" href="<?php echo esc_url( add_query_arg( array( 'tab' => 'debug-logs' ), $server_uri ) ); ?>"><?php esc_html_e( 'Debug Tools', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="sp-setup-tab" class="mo-saml-nav-tab-cstm <?php echo esc_html( 'error-codes' === $active_tab ? 'mo-saml-nav-tab-active' : '' ); ?>" href="<?php echo esc_url( add_query_arg( array( 'tab' => 'error-codes' ), $server_uri ) ); ?>"><?php esc_html_e( 'Error Codes', 'miniorange-saml-20-single-sign-on' ); ?></a>
			<a id="sp-setup-tab" class="mo-saml-nav-tab-cstm" target="_blank" href="https://faq.miniorange.com/kb/saml-single-sign-on/">FAQs</a>
		</div>
	</div>
	<?php
}
