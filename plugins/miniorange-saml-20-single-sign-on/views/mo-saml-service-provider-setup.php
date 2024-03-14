<?php
/**
 * File Name: mo-saml-service-provider-setup.php
 * Description: This file has the frontend of the Service Provider Setup.
 *
 * @package miniorange-saml-20-single-sign-on/views
 */

/**
 * Service Provider Setup Tab
 *
 * @return void
 */
function mo_saml_apps_config_saml() {

	$saml_identity_name                        = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME );
	$saml_login_url                            = get_option( Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL );
	$saml_issuer                               = get_option( Mo_Saml_Options_Enum_Service_Provider::ISSUER );
	$saml_x509_certificate                     = maybe_unserialize( get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) );
	$saml_x509_certificate                     = ! is_array( $saml_x509_certificate ) ? array( 0 => $saml_x509_certificate ) : $saml_x509_certificate;
	$mo_saml_identity_provider_identifier_name = ! empty( trim( get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ) ) ) ? get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ) : '';
	$idp_data                                  = new stdClass();

	if ( ! empty( $mo_saml_identity_provider_identifier_name ) ) {
		if ( ! empty( Mo_Saml_Options_Plugin_Idp_Specific_Ads::$idp_specific_ads[ $mo_saml_identity_provider_identifier_name ] ) ) {
			$idp_array             = Mo_Saml_Options_Plugin_Idp_Specific_Ads::$idp_specific_ads[ $mo_saml_identity_provider_identifier_name ];
			$idp_data->ads_text    = $idp_array['Text'];
			$idp_data->ads_heading = $idp_array['Heading'];
			$idp_data->ads_link    = $idp_array['Link'];
		}
		if ( ! empty( Mo_Saml_Options_Plugin_Idp::$idp_guides[ $mo_saml_identity_provider_identifier_name ] ) ) {
			$idp_guides_array         = Mo_Saml_Options_Plugin_Idp::$idp_guides[ $mo_saml_identity_provider_identifier_name ];
			$idp_key                  = $idp_guides_array[0];
			$idp_data->idp_guide_link = 'https://plugins.miniorange.com/' . $idp_guides_array[1];
			$idp_data->image_src      = Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/idp-guides-logos/' . $idp_key . '.webp';
			if ( ! empty( Mo_Saml_Options_Plugin_Idp_Videos::$idp_videos[ $idp_key ] ) ) {
				$idp_data->idp_video_link = 'https://www.youtube.com/watch?v=' . Mo_Saml_Options_Plugin_Idp_Videos::$idp_videos[ $idp_key ];
			}
		}
	}

	$saml_is_encoding_enabled     = get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) !== false ? get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) : 'checked';
	$saml_assertion_time_validity = get_option( 'mo_saml_assertion_time_validity' ) !== false ? get_option( 'mo_saml_assertion_time_validity' ) : 'checked';

	?>
	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid" id="cstm-idp-section">
		<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-5">
			<?php
			mo_saml_display_idp_selector();
			mo_saml_display_sp_configuration( $saml_identity_name, $saml_login_url, $saml_issuer, $saml_x509_certificate, $mo_saml_identity_provider_identifier_name, $saml_is_encoding_enabled, $idp_data, $saml_assertion_time_validity );
			?>

		</div>
		<script>
			addCertificateErrorClass();
		</script>
		<?php mo_saml_display_support_form(); ?>
	</div>
	<?php
}

/**
 * Select the IDP from dropdown
 *
 * @return void
 */
function mo_saml_display_idp_selector() {
	?>
	<div class="mo-saml-bootstrap-pt-3 mo-saml-bootstrap-pe-5 mo-saml-bootstrap-pb-5 mo-saml-bootstrap-ps-5 shadow-cstm bg-cstm mo-saml-bootstrap-rounded">
		<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-pb-3">
			<div class="mo-saml-bootstrap-col-md-12">
				<input class="idp-search-box mo-saml-bootstrap-rounded-0" id="mo_saml_search_idp_list" type="text" placeholder="Search and select your IDP" value="">
				<span class="idp-search-glass"><span role="img" aria-label="Search"><svg width="24" height="24" viewBox="0 0 24 24" role="presentation">
							<path d="M16.436 15.085l3.94 4.01a1 1 0 01-1.425 1.402l-3.938-4.006a7.5 7.5 0 111.423-1.406zM10.5 16a5.5 5.5 0 100-11 5.5 5.5 0 000 11z" fill="currentColor" fill-rule="evenodd"></path>
						</svg></span></span>
			</div>
		</div>
		<div class="mo-saml-bootstrap-text-center show-msg" style="display: none;">
			<h6>Choose Custom IDP if you don't find your IDP</h6>
		</div>
		<div class="mo-saml-bootstrap-row">
			<div class="mo-saml-bootstrap-col-md-12 mo-saml-bootstrap-text-center mo-saml-bootstrap-rounded mo-saml-scroll-cstm mo-saml-bootstrap-pb-2">
				<div class="mo-saml-bootstrap-row mo-saml-bootstrap-justify-content-center mo-saml-bootstrap-pb-2" id="mo_saml_idps_grid_div">
					<?php
					$image_path = '..' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'idp-guides-logos' . DIRECTORY_SEPARATOR;
					foreach ( Mo_Saml_Options_Plugin_Idp::$idp_guides as $key => $value ) {
						$idp_videos      = Mo_Saml_Options_Plugin_Idp_Videos::$idp_videos;
						$idp_video_index = $idp_videos[ $value[0] ];
						?>
						<div class="mo-saml-bootstrap-col-md-2 logo-saml-cstm" data-idp="<?php echo esc_attr( $idp_video_index ); ?>">
							<a target="_blank" data-idp-value="<?php echo esc_attr( $idp_video_index ); ?>" data-href="https://plugins.miniorange.com/<?php echo esc_attr( $value[1] ); ?>" data-video="https://www.youtube.com/watch?v=<?php echo esc_attr( $idp_video_index ); ?>">
								<img loading="lazy" width="30px" src="<?php echo esc_url( plugins_url( $image_path . $value[0] . '.webp', __FILE__ ) ); ?>">
								<br>
								<h6 class="mt-2" style="color:rgb(33, 37, 41)"><?php echo esc_html( $key ); ?></h6>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="idp_specific_ads" id="idp_specific_ads" value='<?php echo esc_attr( wp_json_encode( Mo_Saml_Options_Plugin_Idp_Specific_Ads::$idp_specific_ads ) ); ?>' />
	<?php
}

/**
 * Display the IDP terms
 *
 * @param string $saml_identity_name Name of IDP.
 * @param string $saml_login_url Login URL of IDP.
 * @param string $saml_issuer SAML Issuer of IDP.
 * @param array  $saml_x509_certificate x509 certificate of IDP.
 * @param string $mo_saml_identity_provider_identifier_name Name of IDP.
 * @param string $saml_is_encoding_enabled Character Encoding of IDP.
 * @param string $idp_data IDP data.
 * @param string $saml_assertion_time_validity Assertion Time Valadity Toggle.
 * @return void
 */
function mo_saml_display_sp_configuration( $saml_identity_name, $saml_login_url, $saml_issuer, $saml_x509_certificate, $mo_saml_identity_provider_identifier_name, $saml_is_encoding_enabled, $idp_data, $saml_assertion_time_validity ) {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4" id="idp_scroll_saml">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Configure Service Provider
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Service-Provider-Setup" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>
		<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-5 mo-saml-bootstrap-mb-5" id="mo_saml_selected_idp_div" style="display: none;">
			<div class="mo-saml-bootstrap-col-md-4">
				<div class="mo-saml-bootstrap-text-center mo-saml-bootstrap-rounded mo-saml-bootstrap-w-50 shadow-cstm mo-saml-bootstrap-p-1" id="mo_saml_selected_idp_icon_div">
					<img width="55" src="" alt="" class="mo-saml-bootstrap-p-1">
				</div>
			</div>
			<div class="mo-saml-bootstrap-col-md-4">
				<a target="_blank" href="" id="saml_idp_guide_link" class="mo-saml-bootstrap-text-white mo-saml-bootstrap-px-4 mo-saml-bootstrap-py-2 mo-saml-bootstrap-rounded mo-saml-bootstrap-bg-info"><svg width="16" height="16" fill="currentColor" class="bi bi-wrench" viewBox="0 0 16 16">
						<path d="M.102 2.223A3.004 3.004 0 0 0 3.78 5.897l6.341 6.252A3.003 3.003 0 0 0 13 16a3 3 0 1 0-.851-5.878L5.897 3.781A3.004 3.004 0 0 0 2.223.1l2.141 2.142L4 4l-1.757.364L.102 2.223zm13.37 9.019.528.026.287.445.445.287.026.529L15 13l-.242.471-.026.529-.445.287-.287.445-.529.026L13 15l-.471-.242-.529-.026-.287-.445-.445-.287-.026-.529L11 13l.242-.471.026-.529.445-.287.287-.445.529-.026L13 11l.471.242z" />
					</svg> &nbsp;Setup Guide</a>
			</div>
			<div class="mo-saml-bootstrap-col-md-4">
				<a target="_blank" href="" id="saml_idp_video_link" class="mo-saml-bootstrap-text-white mo-saml-bootstrap-px-4 mo-saml-bootstrap-py-2 mo-saml-bootstrap-rounded mo-saml-bootstrap-bg-danger"><svg width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
						<path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
					</svg> &nbsp;Video Guide</a>
			</div>
		</div>
		<div class="mo-saml-sp-tab-container mo-saml-bootstrap-mt-4">
			<ul class="switch-tab-sp mo-saml-bootstrap-text-center">
				<li class="mo-saml-current"><a href="#mo-saml-idp-manual-tab" class="mo-saml-bs-btn">Enter IDP Metadata Manually</a></li>
				<li class="mo-saml-bootstrap-col-md-2 or">OR</li>
				<li><a href="#mo-saml-upload-idp-tab" class="mo-saml-bs-btn">Upload IDP Metadata</a></li>
			</ul>

			<div class="mo-saml-sp-tab">
				<input type="hidden" id="mo-saml-test-window-url" value="<?php echo esc_url( mo_saml_get_test_url() ); ?>">
				<input type="hidden" id="mo-saml-attribute-mapping-url" value="<?php echo esc_url( mo_saml_get_attribute_mapping_url() ); ?>">
				<input type="hidden" id="mo-saml-service-provider-url" value="<?php echo esc_url( mo_saml_get_service_provider_url() ); ?>">
				<input type="hidden" id="mo-saml-redirect-sso-url" value="<?php echo esc_url( mo_saml_get_redirection_sso_url() ); ?>">
				<form method="post" action="">
					<?php
					if ( function_exists( 'wp_nonce_field' ) ) {
						wp_nonce_field( 'login_widget_saml_save_settings' );
					}
					?>
					<input type="hidden" name="option" value="login_widget_saml_save_settings" />
					<div id="mo-saml-idp-manual-tab" class="mo-saml-tab-content">
						<input type="hidden" name="mo_saml_identity_provider_identifier_name" id="mo_saml_identity_provider_identifier_name" value="<?php echo esc_attr( $mo_saml_identity_provider_identifier_name ); ?>" />
						<input type="hidden" name="mo_saml_identity_provider_identifier_details" id="mo_saml_identity_provider_identifier_details" value='<?php echo ( isset( $idp_data ) ) ? wp_json_encode( $idp_data ) : ''; ?>' />
						<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-pe-0">
								<h6 class="mo-saml-bootstrap-text-secondary">Identity Provider Name<span style="color: red;">*</span> :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7">
								<input type="text" name="saml_identity_name" placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce" class="mo-saml-bootstrap-w-100" value="<?php echo esc_attr( $saml_identity_name ); ?>" required pattern="\w+" title="Only alphabets, numbers and underscore is allowed">
								<p class="mt-2"><b>Note</b> : Only alphabets, numbers and underscores are allowed as the Identity Provider name.</p>
							</div>
						</div>
						<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">IdP Entity ID or Issuer<span style="color: red;">*</span> :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7">
								<input type="text" title="Please enter a valid value" name="saml_issuer" id="saml_issuer" pattern="[^\s]+\s*$" placeholder="Identity Provider Entity ID or Issuer" class="mo-saml-bootstrap-w-100" value="<?php echo esc_attr( $saml_issuer ); ?>" required="">
								<p class="mt-2"><b>Note</b> : You can find the <b>EntityID</b> in Your IdP-Metadata XML file enclosed in <code>EntityDescriptor</code> tag having attribute as <code>entityID</code></p>
							</div>
						</div>
						<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">SAML Login URL<span style="color: red;">*</span> :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7">
								<input type="url" title="Please enter a valid value" name="saml_login_url" pattern="[^\s]+\s*$" placeholder="Single Sign On Service URL (HTTP-Redirect binding) of your IdP" class="mo-saml-bootstrap-w-100" value="<?php echo esc_attr( $saml_login_url ); ?>" required="">
								<p class="mt-2"><b>Note</b> : You can find the <b>SAML Login URL</b> in Your IdP-Metadata XML file enclosed in <code>SingleSignOnService</code> tag (Binding type: HTTP-Redirect)</p>
							</div>
						</div>
						<?php
						foreach ( $saml_x509_certificate as $key => $value ) {
							?>
							<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
								<div class="mo-saml-bootstrap-col-md-3">
									<h6 class="mo-saml-bootstrap-text-secondary">X.509 Certificate<span style="color: red;">*</span> :</h6>
								</div>
								<div class="mo-saml-bootstrap-col-md-7">
									<textarea rows="4" cols="5" name="saml_x509_certificate[<?php esc_attr( $key ); ?>]" id="saml_x509_certificate" onkeyup="removeCertificateErrorClass();" placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file" class="mo-saml-bootstrap-w-100" required=""><?php echo esc_html( $value ); ?></textarea>


									<span class="mo-saml-error-tip">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffa300" class="bi bi-exclamation-square-fill" viewBox="0 0 16 16">
											<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
										</svg>&nbsp; Invalid Certificate
									</span>

									<p class="mt-2"><b>Note</b> : Format of the certificate - <br><b class="mo-saml-bootstrap-text-secondary">-----BEGIN CERTIFICATE-----<br>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br>-----END
											CERTIFICATE-----</b></p>
								</div>
							</div>
							<?php
						}
						?>
						<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">Character encoding :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-8">

								<input type="checkbox" id="switch" name="mo_saml_encoding_enabled" class="mo-saml-switch" <?php echo esc_attr( $saml_is_encoding_enabled ); ?> /><label class="mo-saml-switch-label" for="switch">Toggle</label>

								<p class="mo-saml-bootstrap-mt-2"><b>Note</b> : Uses iconv encoding to convert X509 certificate into correct encoding.</p>
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">Assertion Time Validity:</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-8">
								<input type="checkbox" id="switch_sync" name="mo_saml_assertion_time_validity" class="mo-saml-switch" <?php echo esc_attr( $saml_assertion_time_validity ); ?> /><label class="mo-saml-switch-label" for="switch_sync">Toggle</label>
								<p class="mo-saml-bootstrap-mt-2"><b>Note</b> : Disable this toggle to disable the check of time validity for SAML assertion.</p>
							</div>
						</div>
						<div class="mo-saml-bootstrap-row align-items-top mt-2">
							<div class="mo-saml-bootstrap-col-md-3"></div>
							<div class="mo-saml-bootstrap-col-md-9">
								<input type="submit" class="mo-saml-bs-bs-btn btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-3 mo-saml-bootstrap-me-3 mo-saml-w-186" name="submit" value="Save">
								<input type="button" class="mo-saml-bs-bs-btn btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-3 mo-saml-w-186" id="test_config" 
								<?php
								if ( ! Mo_SAML_Utilities::mo_saml_is_sp_configured() || ! get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) || ! Mo_SAML_Utilities::mo_saml_is_openssl_installed() ) {
									echo 'disabled';}
								?>
									title="You can only test your Configuration after saving your Service Provider Settings." onclick="mo_saml_show_test_window();" value="Test Configuration">
							</div>
							<div class="mo-saml-bootstrap-col-md-3">

							</div>
						</div>
						<div class="mo-saml-bootstrap-row align-items-top mt-2">
							<div class="mo-saml-bootstrap-col-md-3"></div>
							<div class="mo-saml-bootstrap-col-md-9">
								<input type="button" class="mo-saml-bs-bs-btn btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-3 w-372" name="saml_request" id="export-import-config" 
								<?php
								if ( ! Mo_SAML_Utilities::mo_saml_is_sp_configured() || ! get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) ) {
									echo 'disabled';
								}
								?>
									title="Export Plugin Configuration" value="Export Plugin Configuration" onclick="jQuery('#mo_export').submit();">
							</div>
						</div>
					</div>
				</form>
				<form method="post" action="" name="mo_export" id="mo_export">
					<?php
					wp_nonce_field( 'mo_saml_export' );
					?>
					<input type="hidden" name="option" value="mo_saml_export" />
				</form>

				<div id="mo-saml-upload-idp-tab" class="mo-saml-tab-content">
					<form name="saml_upload_metadata_form" method="post" id="saml_upload_metadata_form" action="<?php echo esc_url( admin_url( 'admin.php?page=mo_saml_settings&tab=save' ) ); ?>" enctype="multipart/form-data">
						<input type="hidden" name="option" value="saml_upload_metadata" />
						<?php wp_nonce_field( 'saml_upload_metadata' ); ?>
						<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3 mo-saml-bootstrap-pe-0">
								<h6 class="mo-saml-bootstrap-text-secondary">Identity Provider Name :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-7">
								<input type="text" name="saml_identity_metadata_provider" placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce" class="mo-saml-bootstrap-w-100" value="" required pattern="\w+" title="Only alphabets, numbers and underscore is allowed">
								<p class="mt-2"><b>Note</b> : Only alphabets, numbers and underscores are allowed as the Identity Provider name.</p>
							</div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">Upload Metadata :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-4">
								<input type="file" id="metadata_file" name="metadata_file" required>
							</div>
							<div class="mo-saml-bootstrap-col-md-4">
								<button type="button" value="Upload" onclick="checkMetadataFile();" class="mo-saml-bs-bs-btn btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-d-flex mo-saml-bootstrap-align-items-center"><svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
										<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
										<path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z" />
									</svg>&nbsp;&nbsp;Upload</button>
							</div>
						</div>
						<div class="mo-saml-bootstrap-text-center">
							<div class="mo-saml-bootstrap-mt-5 form-head form-head-bar form-sep"><span class="mo-saml-bootstrap-bg-secondary mo-saml-bootstrap-rounded-circle mo-saml-bootstrap-p-2 mo-saml-bootstrap-text-white">OR</span></div>
						</div>
						<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-5">
							<div class="mo-saml-bootstrap-col-md-3">
								<h6 class="mo-saml-bootstrap-text-secondary">Enter metadata URL :</h6>
							</div>
							<div class="mo-saml-bootstrap-col-md-4">
								<input type="url" name="metadata_url" onkeypress="checkUploadMetadataFields();" id="metadata_url" placeholder="Enter metadata URL of your IdP" class="mo-saml-bootstrap-w-100" value="" required>
							</div>
							<div class="mo-saml-bootstrap-col-md-4">
								<button type="button" value="Fetch" onclick="checkMetadataUrl();" class="mo-saml-bs-bs-btn btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-d-flex mo-saml-bootstrap-align-items-center"><svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
										<path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z"></path>
										<path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
									</svg>&nbsp;&nbsp;Fetch Metadata</button>
							</div>
						</div>
						<input type="submit" id="metadata-submit-button" style="display:none" />
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
}
