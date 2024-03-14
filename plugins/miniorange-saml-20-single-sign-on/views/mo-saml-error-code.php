<?php
/**
 * The file displays the error codes and associated details.
 *
 * @package  miniorange-saml-20-single-sign-on/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The function displays table with the required error-codes, cause and its description.
 */
function error_codes() {    ?>
	<div class="bg-main-cstm mo-saml-margin-left mo-saml-bootstrap-pb-5" id="error-codes">
		<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid">
			<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-4">
				<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">

					<div class="mo-saml-bootstrap-row">
						<div class="mo-saml-bootstrap-col-md-6">
							<h4>Error Codes </h4>
						</div>
						<div class="mo-saml-bootstrap-col-md-6 mo-saml-bootstrap-text-end">
							<a href="
							<?php
							if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
								echo esc_url( mo_saml_add_query_arg( array( 'tab' => 'save' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
							}
							?>
							" class="mo-saml-bootstrap-btn btn-cstm mo-saml-bootstrap-ms-3" style="background-color: #d7d7d7!important;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
								</svg>&nbsp; Back To Plugin Configuration</a>
						</div>
					</div>
					<div class="form-head"></div>
					<table class="mo-saml-troubleshoot-table">
						<tr>
							<td class="title-text mo-saml-text-center"><b>Error Code</b></td>
							<td class="title-text mo-saml-text-center"><b>Cause</b></td>
							<td class="title-text mo-saml-text-center"><b>Description</b></td>
						</tr>
						<?php
						foreach ( Mo_Saml_Options_Enum_Error_Codes::$error_codes as $key => $value ) {
							?>
						<tr id="<?php echo esc_attr( $value['code'] ); ?>">
							<td>
								<strong><?php echo esc_html( $value['code'] ); ?></strong>
							</td>
							<td>
								<?php echo esc_html( $value['cause'] ); ?>
							</td>
							<td class="mo-saml-content-td">
								<?php
								echo wp_kses(
									$value['description'],
									array(
										'br' => array(),
										'u'  => array(),
									)
								);
								?>
								<br>
								<strong>Fix: </strong>
								<?php
								echo wp_kses(
									$value['fix'],
									array(
										'b' => array(),
										'a' => array( 'href' => array() ),
									)
								);
								?>
							</td>
						</tr>  
							<?php
						}
						?>
					</table>
					<br>
					<div class="mo-saml-note">
						<h5 class="mo-saml-text-center">Reach out to us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a> if you need any assistance.</h5>
					</div>
				</div>
			</div>
			<?php mo_saml_display_support_form(); ?>
		</div>
	</div>
	<?php
}
?>
