<?php
/**
 * File to handle SAML response, generate saml request from WP widget
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once 'mo-saml-import-export.php';
require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum.php';
require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum-error-codes.php';
require_once dirname( __FILE__ ) . '/class-mo-saml-response.php';
require_once dirname( __FILE__ ) . '/class-mo-saml-utilities.php';
require_once 'mo-saml-xmlseclibs.php';

use \RobRichards\XMLSecLibs\Mo_SAML_XML_Security_Key;

/**
 * Class to create WordPress widget
 */
class Mo_SAML_Login_Widget extends WP_Widget {

	/**
	 * Initialize mo_login_wid
	 */
	public function __construct() {
		parent::__construct(
			'Saml_Login_Widget',
			'Login with ' . esc_html( get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ) ),
			array(
				'description'                 => esc_html__( 'This is a miniOrange SAML login widget.', 'miniorange-saml-20-single-sign-on' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Widget UI
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$wid_title = '';
		if ( ! empty( $instance['wid_title'] ) ) {
			$wid_title = $instance['wid_title'];
		}
		$wid_title = apply_filters( 'widget_title', $wid_title );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $args['before_widget'] is html that needs to render on dom escaping will not render html.
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $args['before_widget'] and $args['after_title'] is html that needs to render on dom escaping will not render html.
			echo $args['before_title'] . esc_html( $wid_title ) . $args['after_title'];
		}
		$this->loginForm();
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $args['after_widget'] is html that needs to render on dom escaping will not render html.
		echo $args['after_widget'];
	}

	/**
	 * MiniOrange method to override parent method
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['wid_title'] = isset( $new_instance['wid_title'] ) ? sanitize_text_field( $new_instance['wid_title'] ) : '';
		return $instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$wid_title = '';
		if ( ! empty( $instance['wid_title'] ) ) {
			$wid_title = $instance['wid_title'];
		}
		echo '
		<p><label for="' . esc_attr( $this->get_field_id( 'wid_title' ) ) . ' ">' . esc_html_e( 'Title:' ) . ' </label>
		<input class="widefat" id="' . esc_attr( $this->get_field_id( 'wid_title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'wid_title' ) ) . '" type="text" value="' . esc_attr( $wid_title ) . '" />
		</p>';
	}

	/**
	 * Outputs SSO Login & Logout Buttons in form of a WordPress Widget.
	 *
	 * @return void
	 */
	public function loginForm() {
		if ( ! is_user_logged_in() ) {
			?>
			<script>
				function submitSamlForm() {
					document.getElementById("miniorange-saml-sp-sso-login-form").submit();
				}
			</script>
			<form name="miniorange-saml-sp-sso-login-form" id="miniorange-saml-sp-sso-login-form" method="post" action="">
				<input type="hidden" name="option" value="saml_user_login" />
				<font size="+1" style="vertical-align:top;"> </font>
				<?php
				$identity_provider     = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME );
				$saml_x509_certificate = get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE );
				if ( ! empty( $identity_provider ) && ! empty( $saml_x509_certificate ) ) {
					echo '<a href="#" onClick="submitSamlForm()">Login with ' . esc_html( $identity_provider ) . '</a></form>';
				} else {
					esc_html_e( 'Please configure the miniOrange SAML Plugin first.', 'miniorange-saml-20-single-sign-on' );
				}

				if ( ! Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( get_option( Mo_Saml_Sso_Constants::MO_SAML_REDIRECT_ERROR ) ) ) ) {
					echo '<div></div><div title="Login Error"><font color="red">' . esc_html__( 'We could not sign you in. Please contact your Administrator.', 'miniorange-saml-20-single-sign-on' ) . '</font></div>';
					delete_option( Mo_Saml_Sso_Constants::MO_SAML_REDIRECT_ERROR );
					delete_option( Mo_Saml_Sso_Constants::MO_SAML_REDIRECT_ERROR_REASON );
				}
				?>

				</ul>
			</form>
			<?php
		} else {
			$current_user = wp_get_current_user();
			/* translators: %s: user's display name */
			$link_with_username = sprintf( __( 'Hello, %s', 'miniorange-saml-20-single-sign-on' ), $current_user->display_name );
			?>
			<?php
			echo esc_html( $link_with_username );
			?>
			| <a href="<?php echo esc_url( wp_logout_url( mo_saml_get_current_page_url() ) ); ?>" title="<?php esc_attr_e( 'Logout', 'miniorange-saml-20-single-sign-on' ); ?>"><?php esc_html_e( 'Logout', 'miniorange-saml-20-single-sign-on' ); ?></a></li>
			<?php
		}
	}
}

	/**
	 * Function to handle all incoming request with 'option' & "SAMLResponse Parameter"
	 *
	 * @return void
	 */
function mo_saml_login_validate() {
    //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Metadata url can be URL be used by IDPs. IDP can't generate wordpress nonce.
	if ( isset( $_REQUEST['option'] ) && 'mosaml_metadata' === $_REQUEST['option'] ) {
		Mo_SAML_Service_Provider_Metadata_Handler::download_plugin_metadata();
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
	if ( isset( $_REQUEST['option'] ) && 'export_configuration' === $_REQUEST['option'] ) {
		if ( current_user_can( 'manage_options' ) ) {
			mo_saml_miniorange_import_export( true );
		}
		exit;
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
	if ( isset( $_REQUEST['option'] ) && 'mo_fix_certificate' === $_REQUEST['option'] && is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		$saml_required_certificate = get_option( Mo_Saml_Sso_Constants::MO_SAML_REQUIRED_CERTIFICATE );
		$saml_certificate          = maybe_unserialize( get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) );
		$saml_certificate[0]       = Mo_SAML_Utilities::mo_saml_sanitize_certificate( $saml_required_certificate );
		update_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE, $saml_certificate );
		wp_safe_redirect( '?option=testConfig' );
		exit;
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
	if ( isset( $_REQUEST['option'] ) && 'mo_fix_entity_id' === $_REQUEST['option'] && is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		$saml_required_issuer = get_option( Mo_Saml_Sso_Constants::MO_SAML_REQUIRED_ISSUER );
		update_option( Mo_Saml_Options_Enum_Service_Provider::ISSUER, $saml_required_issuer );
		wp_safe_redirect( '?option=testConfig' );
		exit;
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
	if ( isset( $_REQUEST['option'] ) && 'mo_fix_iconv_cert' === $_REQUEST['option'] && is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		update_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED, 'unchecked' );
		wp_safe_redirect( '?option=testConfig' );
		exit;
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for SSO initiation.
	if ( ( isset( $_REQUEST['option'] ) && 'saml_user_login' === $_REQUEST['option'] ) || ( isset( $_REQUEST['option'] ) && 'testConfig' === $_REQUEST['option'] ) ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
		if ( 'testConfig' === $_REQUEST['option'] ) {
			if ( ! is_user_logged_in() || is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
				return;
			}
		} else {
			if ( is_user_logged_in() ) {
				return;
			}
		}

		if ( Mo_SAML_Utilities::mo_saml_is_sp_configured() ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for test config operation.
			if ( 'testConfig' === $_REQUEST['option'] ) {
				$send_relay_state = 'testValidate';
				//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for SSO redirect parameter.
			} elseif ( isset( $_REQUEST['redirect_to'] ) ) {
				//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignore the nonce verification for SSO redirect parameter.
				$send_relay_state = sanitize_text_field( wp_unslash( $_REQUEST['redirect_to'] ) );
			} else {
				$send_relay_state = mo_saml_get_current_page_url();
			}

			$send_relay_state = mo_saml_get_relay_state( $send_relay_state );
			$send_relay_state = empty( $send_relay_state ) ? '/' : $send_relay_state;

			$send_relay_state = rawurlencode( $send_relay_state );
			$sp_base_url      = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_BASE_URL );
			if ( empty( $sp_base_url ) ) {
				$sp_base_url = site_url();
			}

			$sso_url      = htmlspecialchars_decode( get_option( Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ) );
			$acs_url      = site_url() . '/';
			$issuer       = site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
			$sp_entity_id = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID );
			if ( empty( $sp_entity_id ) ) {
				$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
			}

			$log_message = array(
				'ssoUrl'         => $sso_url,
				'acsUrl'         => $acs_url,
				'spEntityId'     => $sp_entity_id,
				'sendRelayState' => $send_relay_state,
			);
			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_AUTHN_REQUEST', $log_message ), Mo_SAML_Logger::DEBUG );
			$saml_request = Mo_SAML_Utilities::mo_saml_create_authn_request( $acs_url, $sp_entity_id );

			$redirect = $sso_url;

			if ( strpos( $sso_url, '?' ) !== false ) {
				$redirect .= '&';
			} else {
				$redirect .= '?';
			}
			$redirect .= 'SAMLRequest=' . $saml_request . '&RelayState=' . $send_relay_state;

			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_RELAYSTATE_SENT', array( 'redirect' => $redirect ) ), Mo_SAML_Logger::DEBUG );
			header( 'Location: ' . $redirect );
			exit();
		}
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Missing -- SAMLResponse request is sent by IDP, which can't create dynamic nonce to verify for each request.
	if ( ! empty( $_POST['SAMLResponse'] ) ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verification is not required while processing SAMLResponse.
		$saml_response = sanitize_text_field( wp_unslash( $_POST['SAMLResponse'] ) );

		Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_SAML_RESPONSE', array( 'samlResponse' => $saml_response ) ), Mo_SAML_Logger::DEBUG );
			//phpcs:ignore WordPress.Security.NonceVerification.Missing -- RelayState request is sent by IDP, which can't create dynamic nonce to verify for each request.
		if ( ! empty( $_POST['RelayState'] ) && '/' !== $_POST['RelayState'] ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing -- RelayState request is sent by IDP, which can't create dynamic nonce to verify for each request.
			$relay_state = sanitize_text_field( wp_unslash( $_POST['RelayState'] ) );
		} else {
			$relay_state = '';
		}

		Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_RELAYSTATE_RECEIVED', array( 'relayState' => $relay_state ) ), Mo_SAML_Logger::DEBUG );
		update_option( Mo_Saml_Options_Test_Configuration::SAML_RESPONSE, $saml_response );
        //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- SAML response is base64 encoded.
		$saml_response = base64_decode( $saml_response );

		$document = new DOMDocument();
		$document->loadXML( $saml_response );
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- firstChild property is Method of DOMDocument.
		$saml_response_xml = $document->firstChild;
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- documentElement property is Method of DOMDocument.
		$doc   = $document->documentElement;
		$xpath = new DOMXpath( $document );
		$xpath->registerNamespace( 'samlp', 'urn:oasis:names:tc:SAML:2.0:protocol' );
		$xpath->registerNamespace( 'saml', 'urn:oasis:names:tc:SAML:2.0:assertion' );

		$status         = $xpath->query( '/samlp:Response/samlp:Status/samlp:StatusCode', $doc );
		$status_string  = $status->item( 0 )->getAttribute( 'Value' );
		$status_message = $xpath->query( '/samlp:Response/samlp:Status/samlp:StatusMessage', $doc )->item( 0 );
		if ( ! empty( $status_message ) ) {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nodeValue property is Method of DOMDocument.
			$status_message = $status_message->nodeValue;
		}

		$status_array = explode( ':', $status_string );
		if ( ! empty( $status_array[7] ) ) {
			$status = $status_array[7];
		}
		if ( 'Success' !== $status ) {
			mo_saml_show_status_error( $status, $relay_state, $status_message );

			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_INVAILD_SAML_STATUS' ), Mo_SAML_Logger::ERROR );
		}

		$cert_from_plugin = maybe_unserialize( get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) );

		$acs_url                  = site_url() . '/';
		$saml_response            = new Mo_SAML_Response( $saml_response_xml );
		$response_signature_data  = $saml_response->mo_saml_get_signature_data();
		$assertion_signature_data = current( $saml_response->mo_saml_get_assertions() )->mo_saml_get_signature_data();

		if ( empty( $assertion_signature_data ) && empty( $response_signature_data ) ) {

			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_RESPONSE_ASSERATION_NOT_SIGNED' ), Mo_SAML_Logger::ERROR );
			$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR003'];
			if ( 'testValidate' === $relay_state ) {

				$error_cause   = $error_code['cause'];
				$error_message = $error_code['testConfig_msg'];
				mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message );
				mo_saml_download_logs( $error_cause, $error_message );
				exit;
			} else {
				Mo_SAML_Utilities::mo_saml_die( $error_code );
			}
		}
		if ( is_array( $cert_from_plugin ) ) {
			foreach ( $cert_from_plugin as $key => $value ) {
				$plugin_cert         = $value;
				$cert_fp_from_plugin = Mo_SAML_XML_Security_Key::mo_saml_get_raw_thumbprint( $value );

				$cert_fp_from_plugin = mo_saml_convert_to_windows_iconv( $cert_fp_from_plugin );
				$cert_fp_from_plugin = preg_replace( '/\s+/', '', $cert_fp_from_plugin );
				if ( ! empty( $response_signature_data ) ) {
					$valid_signature = Mo_SAML_Utilities::mo_saml_process_response( $acs_url, $cert_fp_from_plugin, $response_signature_data, $saml_response, $key, $relay_state );
				}
				if ( ! empty( $assertion_signature_data ) ) {
					$valid_signature = Mo_SAML_Utilities::mo_saml_process_response( $acs_url, $cert_fp_from_plugin, $assertion_signature_data, $saml_response, $key, $relay_state );
				}
				if ( $valid_signature ) {
					break;
				}
			}
		} else {
			$plugin_cert         = $cert_from_plugin;
			$cert_fp_from_plugin = Mo_SAML_XML_Security_Key::mo_saml_get_raw_thumbprint( $cert_from_plugin );
			$cert_fp_from_plugin = mo_saml_convert_to_windows_iconv( $cert_fp_from_plugin );
			$cert_fp_from_plugin = preg_replace( '/\s+/', '', $cert_fp_from_plugin );
			if ( ! empty( $response_signature_data ) ) {
				$valid_signature = Mo_SAML_Utilities::mo_saml_process_response( $acs_url, $cert_fp_from_plugin, $response_signature_data, $saml_response, 0, $relay_state );
			}

			if ( ! empty( $assertion_signature_data ) ) {
				$valid_signature = Mo_SAML_Utilities::mo_saml_process_response( $acs_url, $cert_fp_from_plugin, $assertion_signature_data, $saml_response, 0, $relay_state );
			}
		}
		if ( $response_signature_data ) {
			$saml_required_certificate = $response_signature_data['Certificates'][0];
		} elseif ( $assertion_signature_data ) {
			$saml_required_certificate = $assertion_signature_data['Certificates'][0];
		}
		update_option( Mo_Saml_Sso_Constants::MO_SAML_REQUIRED_CERTIFICATE, $saml_required_certificate );
		$saml_is_encoding_enabled = get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) ? get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) : 'checked';
		if ( ! $valid_signature ) {

			$desanitized_certificate = Mo_SAML_Utilities::mo_saml_desanitize_certificate( $plugin_cert );
			if ( $saml_required_certificate !== $desanitized_certificate ) {
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_CERT_NOT_MATCHED' ), Mo_SAML_Logger::ERROR );
				$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR004'];
				if ( 'testValidate' === $relay_state ) {
					$error_cause   = $error_code['cause'];
					$error_message = $error_code['testConfig_msg'];
					mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message );
					mo_saml_download_logs( $error_cause, $error_message );
					exit;
				} else {
					Mo_SAML_Utilities::mo_saml_die( $error_code );
				}
			} elseif ( 'checked' === $saml_is_encoding_enabled ) {
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_CERT_NOT_MATCHED_ENCODED' ), Mo_SAML_Logger::ERROR );
				$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR012'];
				if ( 'testValidate' === $relay_state ) {
					$error_cause   = $error_code['cause'];
					$error_message = $error_code['testConfig_msg'];
					mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message );
					mo_saml_download_logs( $error_cause, $error_message );
					exit;
				} else {
					Mo_SAML_Utilities::mo_saml_die( $error_code );
				}
			} else {
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_UNABLE_TO_PROCESS_RESPONSE' ), Mo_SAML_Logger::ERROR );
				wp_die( 'Unable to process the SAML response' );
			}
		}

		$sp_base_url = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_BASE_URL );
		if ( empty( $sp_base_url ) ) {
			$sp_base_url = site_url();
		}
		// verify the issuer and audience from saml response.
		$issuer      = get_option( Mo_Saml_Options_Enum_Service_Provider::ISSUER );
		$sp_enity_id = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID );
		if ( empty( $sp_enity_id ) ) {
			$sp_enity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
		}
		Mo_SAML_Utilities::mo_saml_validate_issuer_and_audience( $saml_response, $sp_enity_id, $issuer, $relay_state );

		try {
			$ssoemail = current( current( $saml_response->mo_saml_get_assertions() )->mo_saml_get_name_id() );
		} catch ( Exception $exception ) {
			wp_die( 'We could not sign you in. Please contact your administrator.', 'Encrypted NameID' );
		}
		$attrs           = current( $saml_response->mo_saml_get_assertions() )->mo_saml_get_attributes();
		$attrs['NameID'] = array( '0' => sanitize_text_field( $ssoemail ) );
		$session_index   = current( $saml_response->mo_saml_get_assertions() )->mo_saml_get_session_index();
		Mo_SAML_Logger::mo_saml_add_log( mo_saml_error_log::mo_saml_write_message( 'ATTRIBUTES_RECEIVED_IN_TEST_CONFIGURATION', array( 'attrs' => $attrs ) ), Mo_SAML_Logger::INFO );
		mo_saml_check_mapping( $attrs, $relay_state, $session_index );
	}
}

/**
 * Map SAML response to WP user data.
 *
 * @param array  $attrs array of attribute node of SAML response.
 *
 * @param string $relay_state  redirect url for post SSO flow.
 *
 * @param string $session_index session index to after authentication.
 *
 * @return void
 */
function mo_saml_check_mapping( $attrs, $relay_state, $session_index ) {
	try {
		// Get encrypted user_email.
		$email_attribute                           = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_EMAIL );
		$mo_saml_identity_provider_identifier_name = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ) ? get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ) : '';
		if ( ! empty( $mo_saml_identity_provider_identifier_name ) && 'Azure B2C' === $mo_saml_identity_provider_identifier_name ) {
			$email_attribute = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress';
		}
		$username_attribute = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_USERNAME );
		$first_name         = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_FIRST_NAME );
		$last_name          = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_LAST_NAME );
		$group_name         = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_GROUP_NAME );
		$default_role       = get_option( Mo_Saml_Options_Enum_Role_Mapping::ROLE_DEFAULT_ROLE );
		$check_if_match_by  = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_ACCOUNT_MATCHER );
		$user_email         = '';
		$user_name          = '';

		// Attribute mapping. Check if Match/Create user is by username/email:.
		if ( ! empty( $attrs ) ) {
			if ( ! empty( $attrs[ $first_name ] ) ) {
				$first_name = $attrs[ $first_name ][0];
			} else {
				$first_name = '';
			}

			if ( ! empty( $attrs[ $last_name ] ) ) {
				$last_name = $attrs[ $last_name ][0];
			} else {
				$last_name = '';
			}

			if ( ! empty( $attrs[ $username_attribute ] ) ) {
				$user_name = $attrs[ $username_attribute ][0];
			} else {
				$user_name = $attrs['NameID'][0];
			}

			if ( ! empty( $attrs[ $email_attribute ] ) ) {
				$user_email = $attrs[ $email_attribute ][0];
			} else {
				$user_email = $attrs['NameID'][0];
			}

			if ( ! empty( $attrs[ $group_name ] ) ) {
				$group_name = $attrs[ $group_name ];
			} else {
				$group_name = array();
			}

			if ( empty( $check_if_match_by ) ) {
				$check_if_match_by = 'email';
			}
		}

		if ( 'testValidate' === $relay_state ) {
			update_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ERROR_LOG, 'Test successful' );
			update_option( Mo_Saml_Sso_Constants::MO_SAML_TEST_STATUS, 1 );
			mo_saml_show_test_result( $first_name, $last_name, $user_email, $group_name, $attrs );
		} else {
			mo_saml_login_user( $user_email, $first_name, $last_name, $user_name, $group_name, $default_role, $relay_state, $check_if_match_by, $session_index, $attrs['NameID'][0] );
		}
	} catch ( Exception $e ) {
		echo sprintf( 'An error occurred while processing the SAML Response.' );
		exit;
	}
}

/**
 * Show test configuration window after SAML response is processed.
 *
 * @param string       $first_name first name of user from the SAML response.
 *
 * @param string       $last_name last name of user from the SAML response.
 *
 * @param string       $user_email email of user from the SAML response.
 *
 * @param array|string $group_name mapped group name of user from the SAML response.
 *
 * @param array        $attrs array of attributes received in the attributes node of the SAML response.
 *
 * @return void
 */
function mo_saml_show_test_result( $first_name, $last_name, $user_email, $group_name, $attrs ) {
	if ( ob_get_contents() ) {
		ob_end_clean();
	}
	echo '<div style="font-family:Calibri;padding:0 3%;">';
	$name_id = $attrs['NameID'][0];
	if ( ! empty( $user_email ) ) {
		update_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ATTRS, $attrs );
		echo '<div style="color: #3c763d;
				background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt; border-radius:10px;margin-top:17px;">TEST SUCCESSFUL</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><svg class="animate" width="100" height="100">
				<filter id="dropshadow" height="">
				  <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"></feGaussianBlur>
				  <feFlood flood-color="rgba(76, 175, 80, 1)" flood-opacity="0.5" result="color"></feFlood>
				  <feComposite in="color" in2="blur" operator="in" result="blur"></feComposite>
				  <feMerge> 
					<feMergeNode></feMergeNode>
					<feMergeNode in="SourceGraphic"></feMergeNode>
				  </feMerge>
				</filter>
				
				<circle cx="50" cy="50" r="46.5" fill="none" stroke="rgba(76, 175, 80, 0.5)" stroke-width="5"></circle>
				
				<path d="M67,93 A46.5,46.5 0,1,0 7,32 L43,67 L88,19" fill="none" stroke="rgba(76, 175, 80, 1)" stroke-width="5" stroke-linecap="round" stroke-dasharray="80 1000" stroke-dashoffset="-220" style="filter:url(#dropshadow)"></path>
			  </svg><style>
			  svg.animate path {
			  animation: dash 1.5s linear both;
			  animation-delay: 1s;
			}
			  @keyframes dash {
			  0% { stroke-dashoffset: 210; }
			  75% { stroke-dashoffset: -220; }
			  100% { stroke-dashoffset: -205; }
			}
			</style></div>';
	} else {
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Some Attributes Did Not Match.</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="' . esc_url( plugin_dir_url( __FILE__ ) ) . 'images/wrong.webp"></div>';
	}

	if ( strlen( $name_id ) > 60 ) {
		echo '<p><font color="#FF0000" style="font-size:14pt;font-weight:bold">Warning: The NameID value is longer than 60 characters. User will not be created during SSO.</font></p>';
	}
	$match_account_by = get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_ACCOUNT_MATCHER ) ? get_option( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_ACCOUNT_MATCHER ) : 'email';
	if ( 'email' === $match_account_by && ! filter_var( $name_id, FILTER_VALIDATE_EMAIL ) ) {
		echo '<p><font color="#FF0000" style="font-size:14pt;font-weight:bold">Warning: The NameID value is not a valid Email ID</font></p>';
	}
	echo '<span style="font-size:14pt;"><b>Hello</b>, ' . esc_html( $user_email ) . '</span>';

	echo '<br/><p style="font-weight:bold;font-size:14pt;margin-left:1%;">Attributes Received:</p>
				<table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;word-break:break-all;">
				<tr style="text-align:center;background:#d3e1ff;border:2.5px solid #ffffff";word-break:break-all;><td style="font-weight:bold;padding:2%;border-top-left-radius: 10px;border:2.5px solid #ffffff">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2.5px solid #ffffff; word-wrap:break-word;border-top-right-radius:10px">ATTRIBUTE VALUE</td></tr>';

	if ( ! empty( $attrs ) ) {
		foreach ( $attrs as $key => $value ) {
			if ( is_array( $value ) ) {
				$attr_values = implode( '<hr>', $value );
			} else {
				$attr_values = esc_html( $value );
			}
			$allowed_html = array( 'hr' => array() );
			echo "<tr><td style='border:2.5px solid #ffffff;padding:2%;background:#e9f0ff;'>" . esc_html( $key ) . "</td><td style='padding:2%;border:2.5px solid #ffffff;background:#e9f0ff;word-wrap:break-word;'>" . wp_kses( $attr_values, $allowed_html ) . '</td></tr>';
		}
	} else {
		echo 'No Attributes Received.';
	}
	echo '</table></div>';
	echo '<div style="margin:3%;display:block;text-align:center;">
		<input style="padding:1%;width:250px;background: linear-gradient(0deg,rgb(14 42 71) 0,rgb(26 69 138) 100%)!important;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"
            type="button" value="Configure Attribute/Role Mapping" onClick="close_and_redirect_to_attribute_mapping();"> &nbsp;
		<input style="padding:1%;width:250px;background: linear-gradient(0deg,rgb(14 42 71) 0,rgb(26 69 138) 100%)!important;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;
		"type="button" value="Configure SSO Settings" onClick="close_and_redirect_to_redir_sso();"></div>
		
		<script>
             function close_and_redirect_to_attribute_mapping(){
                 window.opener.redirect_to_attribute_mapping();
                 self.close();
             }   
             function close_and_redirect() {
               window.opener.redirect_to_service_provider();
                 self.close();
             }
             function close_and_redirect_to_redir_sso() {
               window.opener.redirect_to_redi_sso_link();
                 self.close();
             }
             
            
		</script>';
	exit;
}

/**
 * Change cert fingerprint to correct encoding.
 *
 * @param string $cert_fp_from_plugin IdP certificate from the plugin.
 * @return string
 */
function mo_saml_convert_to_windows_iconv( $cert_fp_from_plugin ) {
	$encoding_enabled = get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) ? get_option( Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ) : 'checked';

	if ( 'checked' === $encoding_enabled && Mo_SAML_Utilities::mo_saml_is_iconv_installed() ) {
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Used to suppress iconv warning.
		return @iconv( Mo_Saml_Options_Enum_Encoding::ENCODING_UTF_8, Mo_Saml_Options_Enum_Encoding::ENCODING_CP1252, $cert_fp_from_plugin );
	}
	return $cert_fp_from_plugin;
}

/**
 * Process SAML response data and Create authentication cookie for users.
 *
 * This functions will Process SAML response data, map the user data received from the SAML response to WP User.
 * If all the parameter are correct then it will check if user exists based on the username and email address received in the SAML response.
 * If user doesn't exists then it will create a new user.
 * If SAML response data is not verified then it will exit the process with WP die.
 *
 * @param string       $user_email email for user from the SAML response ( NameID or Subject Node in the SAML response).
 *
 * @param string       $first_name first name of user from SAML response.
 *
 * @param string       $last_name last name of user from SAML response.
 *
 * @param string       $user_name user name of user from SAML response.
 *
 * @param array|string $group_name group name of user from SAML response.
 *
 * @param string       $default_role default role from the plugin configurations saved in DB.
 *
 * @param string       $relay_state relay state parameter passed by IDP.
 *
 * @param string       $check_if_match_by default username, parameter from which users will be matched.
 *
 * @param string       $session_index session index sent by IDP.
 *
 * @param string       $name_id NameID or subject node from the SAML response.
 *
 * @return void
 */
function mo_saml_login_user( $user_email, $first_name, $last_name, $user_name, $group_name, $default_role, $relay_state, $check_if_match_by, $session_index = '', $name_id = '' ) {
	$user_id = null;
	if ( ( 'username' === $check_if_match_by && username_exists( $user_name ) ) || username_exists( $user_name ) ) {
		$user    = get_user_by( 'login', $user_name );
		$user_id = $user->ID;

		Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_USER_EXISTS', array( 'userName' => $user_name ) ), Mo_SAML_Logger::DEBUG );
	} elseif ( email_exists( $user_email ) ) {

		$user    = get_user_by( 'email', $user_email );
		$user_id = $user->ID;

		Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_EMAIL_EXISTS', array( 'user_email' => $user_email ) ), Mo_SAML_Logger::DEBUG );
	} elseif ( ! username_exists( $user_name ) && ! email_exists( $user_email ) ) {
		$random_password = wp_generate_password( 10, false );
		if ( ! empty( $user_name ) ) {
			$user_id = wp_create_user( $user_name, $random_password, $user_email );
		} else {
			$user_id = wp_create_user( $user_email, $random_password, $user_email );
		}
		if ( is_wp_error( $user_id ) ) {
			if ( strlen( $user_name ) > 60 ) {
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_USERNAME_LENGTH_LIMIT_EXCEEDED' ), Mo_SAML_Logger::ERROR );
				$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR011'];
				wp_die( 'We couldn\'t sign you in. Please contact your administrator with the following error code.<br><br>Error code: <b>' . esc_attr( $error_code['code'] ) . '</b>.', 'Error: Username length limit exceeded.' );
			} else {
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_USER_CREATION_FAILED' ), Mo_SAML_Logger::ERROR );
				$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR005'];
				wp_die( 'We couldn\'t sign you in. Please contact your administrator with the following error code.<br><br>Error code: <b>' . esc_attr( $error_code['code'] ) . '</b>.', 'Error: User not created.' );
			}
			exit();
		}
		Mo_SAML_Logger::mo_saml_add_log(
			Mo_Saml_Error_Log::mo_saml_write_message(
				'LOGIN_WIDGET_NEW_USER',
				array(
					'user_email' => $user_email,
					'user_id'    => $user_id,
				)
			),
			Mo_SAML_Logger::DEBUG
		);

		if ( ! empty( $default_role ) && ! mo_saml_is_administrator_user( get_user_by( 'id', $user_id ) ) ) {
			$user_id = wp_update_user(
				array(
					'ID'   => $user_id,
					'role' => $default_role,
				)
			);

			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_DEFAULT_ROLE', array( 'defaultRole' => $default_role ) ), Mo_SAML_Logger::DEBUG );
		}
	}
	mo_saml_add_firstlast_name( $user_id, $first_name, $last_name, $relay_state );
}

/**
 * Check if given user is administrator or not.
 *
 * @param wp_user $user wp_user object.
 * @return bool
 */
function mo_saml_is_administrator_user( $user ) {
	if ( ! is_null( $user->roles ) && in_array( 'administrator', $user->roles, true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Add first and last name of user.
 *
 * @param int    $user_id ID of the wp_user.
 *
 * @param string $first_name first name of the user.
 *
 * @param string $last_name last name of the user.
 *
 * @param string $relay_state relay state parameter. URL where the user should be redirected to after authentication.
 * @return void
 */
function mo_saml_add_firstlast_name( $user_id, $first_name, $last_name, $relay_state ) {
	if ( ! empty( $first_name ) ) {
		$user_id = wp_update_user(
			array(
				'ID'         => $user_id,
				'first_name' => $first_name,
			)
		);
	}
	if ( ! empty( $last_name ) ) {
		$user_id = wp_update_user(
			array(
				'ID'        => $user_id,
				'last_name' => $last_name,
			)
		);
	}

	Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_COOKIE_CREATED', array( 'user_id' => $user_id ) ), Mo_SAML_Logger::DEBUG );
	wp_set_auth_cookie( $user_id, true );

	if ( ! empty( $relay_state ) ) {
		$redirect_url = $relay_state;
	} else {
		$redirect_url = site_url();
	}

	Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'LOGIN_WIDGET_REDIRECT_URL_AFTER_LOGIN', array( 'redirect_url' => $redirect_url ) ), Mo_SAML_Logger::DEBUG );

	wp_safe_redirect( $redirect_url );
	exit;
}

/**
 * Function to show status error code and status message.
 *
 * @param string $status_code status code from the SAML response.
 *
 * @param string $relay_state relay state parameter from the SAML response.
 *
 * @param string $statusmessage status message returned from the IDP in the SAML response.
 *
 * @return void
 */
function mo_saml_show_status_error( $status_code, $relay_state, $statusmessage ) {
	$status_code   = sanitize_text_field( $status_code );
	$statusmessage = sanitize_text_field( $statusmessage );
	$error_code    = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR006'];
	if ( 'testValidate' === $relay_state ) {
		$error_cause   = $error_code['cause'];
		$error_message = sprintf( $error_code['testConfig_msg'], $status_code );
		mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message, $statusmessage );
		mo_saml_download_logs( $error_cause, $error_message );
		exit;
	} else {
		Mo_SAML_Utilities::mo_saml_die( $error_code );
	}
}

/**
 * Function to return anchor tag html.
 *
 * @param string $title title of the anchor tag.
 *
 * @param string $link link for the anchor tag.
 *
 * @return string
 */
function mo_saml_add_link( $title, $link ) {
	$html = '<a href="' . esc_url( $link ) . '">' . esc_html( $title ) . '</a>';
	return $html;
}

/**
 * Get URL of current page.
 *
 * @return bool|string
 */
function mo_saml_get_current_page_url() {
	//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Don't need to unslash a possible URL.
	$http_host = isset( $_SERVER['HTTP_HOST'] ) ? esc_url_raw( $_SERVER['HTTP_HOST'] ) : '';
	//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Don't need to unslash a possible URL.
	$is_https = ( isset( $_SERVER['HTTPS'] ) && strcasecmp( esc_url_raw( $_SERVER['HTTPS'] ), 'on' ) === 0 );

	if ( filter_var( $http_host, FILTER_VALIDATE_URL ) ) {
		$http_host = wp_parse_url( $http_host, PHP_URL_HOST );
	}
	//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Don't need to unslash a URI.
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '';
	if ( substr( $request_uri, 0, 1 ) === '/' ) {
		$request_uri = substr( $request_uri, 1 );
	}
	if ( strpos( $request_uri, '?option=saml_user_login' ) !== false ) {
		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Don't need to unslash a URI.
		return strtok( esc_url_raw( $_SERVER['REQUEST_URI'] ), '?' );
	}
	$relay_state = 'http' . ( $is_https ? 's' : '' ) . '://' . $http_host . '/' . $request_uri;
	return $relay_state;
}

/**
 * Parse relay state parameter.
 *
 * @param string $relay_state Relay state from the IDP.
 *
 * @return array|bool|int|null|string
 */
function mo_saml_get_relay_state( $relay_state ) {

	if ( 'testValidate' === $relay_state ) {
		return $relay_state;
	}

	$relay_path = wp_parse_url( $relay_state, PHP_URL_PATH );
	if ( wp_parse_url( $relay_state, PHP_URL_QUERY ) ) {
		$relay_query_paramter = wp_parse_url( $relay_state, PHP_URL_QUERY );
		$relay_path           = $relay_path . '?' . $relay_query_paramter;
	}
	if ( wp_parse_url( $relay_state, PHP_URL_FRAGMENT ) ) {
		$relay_fragment_identifier = wp_parse_url( $relay_state, PHP_URL_FRAGMENT );
		$relay_path                = $relay_path . '#' . $relay_fragment_identifier;
	}

	return $relay_path;
}

add_action(
	'widgets_init',
	function () {
		register_widget( 'Mo_SAML_Login_Widget' );}
);

add_action( 'init', 'mo_saml_login_validate' );
