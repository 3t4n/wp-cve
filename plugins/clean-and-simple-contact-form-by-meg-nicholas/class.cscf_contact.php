<?php

/*
 * class for holding and validating data captured from the contact form
*/

class cscf_Contact {
	var $Name;
	var $Email;
	var $ConfirmEmail;
	var $Message;
	var $EmailToSender;
	var $ErrorMessage;
	var $PhoneNumber;
	var $ContactConsent;
	var $RecaptchaPublicKey;
	var $RecaptchaPrivateKey;
	var $Errors;
	var $PostID;
	var $IsSpam;

	function __construct() {
		$this->Errors = array();

		if ( cscf_PluginSettings::UseRecaptcha() ) {
			$this->RecaptchaPublicKey  = cscf_PluginSettings::PublicKey();
			$this->RecaptchaPrivateKey = cscf_PluginSettings::PrivateKey();
		}

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( isset( $_POST['cscf'] ) ) {
				$cscf = (array) $_POST['cscf'];
				foreach ( $cscf as $key => $value ) {
					switch ( $key ) {
						case 'name':
							$this->Name = sanitize_text_field( $value );
							break;
						case 'email':
							$this->Email = sanitize_email( $value );
							break;
						case 'confirm-email':
							$this->ConfirmEmail = sanitize_email( $value );
							break;
						case 'email-sender':
							$this->EmailToSender = sanitize_text_field( $value );
							break;
						case 'message':
							$this->Message = sanitize_textarea_field( $value );
							break;
						case 'phone-number':
							$this->PhoneNumber = sanitize_text_field( $value );
							break;
						case 'contact-consent':
							$this->ContactConsent = sanitize_text_field( $value );
							break;
						default:
							$cscf[ $key ] = null;  // should never get this but just in case.
					}
				}

				if ( isset( $_POST['post-id'] ) ) {
					$this->PostID = sanitize_text_field( $_POST['post-id'] );
				}

				unset( $_POST['cscf'] );
			}
		}

		$this->IsSpam = false;
	}

	public function IsValid() {
		$this->Errors = array();

		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			return false;
		}

		//check nonce
		if ( ! wp_verify_nonce( $_POST['cscf_nonce'], 'cscf_contact' ) ) {
			return false;
		}

		// email and confirm email are the same
		if ( cscf_PluginSettings::ConfirmEmail() ) {
			if ( $this->Email != $this->ConfirmEmail ) {
				$this->Errors['confirm-email'] = esc_html__( 'Sorry the email addresses do not match.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//email
		if ( strlen( $this->Email ) == 0 ) {
			$this->Errors['email'] = esc_html__( 'Please give your email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//confirm email
		if ( cscf_PluginSettings::ConfirmEmail() ) {
			if ( strlen( $this->ConfirmEmail ) == 0 ) {
				$this->Errors['confirm-email'] = esc_html__( 'Please confirm your email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//name
		if ( strlen( $this->Name ) == 0 ) {
			$this->Errors['name'] = esc_html__( 'Please give your name.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//message
		if ( strlen( $this->Message ) == 0 ) {
			$this->Errors['message'] = esc_html__( 'Please enter a message.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//email invalid address
		if ( strlen( $this->Email ) > 0 && ! filter_var( $this->Email, FILTER_VALIDATE_EMAIL ) ) {
			$this->Errors['email'] = esc_html__( 'Please enter a valid email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//mandatory phone number
		if ( cscf_PluginSettings::PhoneNumber() && cscf_PluginSettings::PhoneNumberMandatory() ) {
			if ( strlen( $this->PhoneNumber ) < 8 ) {
				$this->Errors['confirm-email'] = esc_html__( 'Please enter a valid phone number.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//contact consent
		if ( cscf_PluginSettings::ContactConsent() ) {
			if ( ! $this->ContactConsent ) {
				$this->Errors['contact-consent'] = esc_html__( 'Please give your consent.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//check recaptcha but only if we have keys
		if ( $this->RecaptchaPublicKey <> '' && $this->RecaptchaPrivateKey <> '' ) {
			$resp = csf_RecaptchaV2::VerifyResponse( $_SERVER["REMOTE_ADDR"], $this->RecaptchaPrivateKey, $_POST["g-recaptcha-response"] );

			if ( ! $resp->success ) {
				$this->Errors['recaptcha'] = esc_html__( 'Please solve the recaptcha to continue.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		return count( $this->Errors ) == 0;
	}

	public function SendMail() {
		apply_filters( 'cscf_spamfilter', $this );

		if ( $this->IsSpam === true ) {
			return true;
		}

		$filters = new cscf_Filters;

		if ( cscf_PluginSettings::OverrideFrom() & cscf_PluginSettings::FromEmail() != "" ) {
			$filters->from_email = cscf_PluginSettings::FromEmail();
		} else {
			$filters->from_email = $this->Email;
		}

		$filters->from_name = $this->Name;

		//add filters
		$filters->add( 'wp_mail_from' );
		$filters->add( 'wp_mail_from_name' );

		//headers
		$header = "Content-Type: text/plain\r\n" . "Reply-To: " . $this->Name . " <" . $this->Email . ">\r\n" . "X-Entity-Ref-ID: " . uniqid() . "\r\n";


		//message
		$message = esc_html__( 'From', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ': ' . esc_attr( $this->Name ) . "\n\n";
		$message .= esc_html__( 'Email', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ': ' . esc_attr( $this->Email ) . "\n\n";
		if ( cscf_PluginSettings::PhoneNumber() ) {
			$message .= esc_html__( 'Phone', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ': ' . esc_attr( $this->PhoneNumber ) . "\n\n";
		}
		$message .= esc_html__( 'Page URL', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ': ' . get_permalink( $this->PostID ) . "\n\n";
		$message .= esc_html__( 'Message', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ':' . "\n\n" . esc_html( $this->Message ) . "\n\n";
		if ( cscf_PluginSettings::ContactConsent() ) {
			$message .= cscf_PluginSettings::ContactConsentMsg() . ': ' . ( $this->ContactConsent ? esc_html__( 'yes', 'clean-and-simple-contact-form-by-meg-nicholas' ) : esc_html__( 'no', 'clean-and-simple-contact-form-by-meg-nicholas' ) );
		}
		$emails  = apply_filters( 'cscf_email_emails', cscf_PluginSettings::RecipientEmails() );
		$subject = apply_filters( 'cscf_email_subject', cscf_PluginSettings::Subject() );
		$message = apply_filters( 'cscf_email_message', stripslashes( $message ) );
		$header  = apply_filters( 'cscf_email_header', $header );

		$result = wp_mail( $emails, $subject, $message, $header );

		//remove filters (play nice)
		$filters->remove( 'wp_mail_from' );
		$filters->remove( 'wp_mail_from_name' );

		//send an email to the form-filler
		if ( $this->EmailToSender ) {
			$recipients = cscf_PluginSettings::RecipientEmails();

			if ( cscf_PluginSettings::OverrideFrom() & cscf_PluginSettings::FromEmail() != "" ) {
				$filters->from_email = cscf_PluginSettings::FromEmail();
			} else {
				$filters->from_email = $recipients[0];
			}

			$filters->from_name = get_bloginfo( 'name' );

			//add filters
			$filters->add( 'wp_mail_from' );
			$filters->add( 'wp_mail_from_name' );

			$header  = "Content-Type: text/plain\r\n";
			$message = cscf_PluginSettings::SentMessageBody() . "\n\n";
			$message .= esc_html__( 'Here is a copy of your message :', 'clean-and-simple-contact-form-by-meg-nicholas' ) . "\n\n";
			if ( cscf_PluginSettings::ContactConsent() ) {
				$message .= cscf_PluginSettings::ContactConsentMsg() . ': ' . ( $this->ContactConsent ? esc_html__( 'yes', 'clean-and-simple-contact-form-by-meg-nicholas' ) : esc_html__( 'no', 'clean-and-simple-contact-form-by-meg-nicholas' ) ) . "\n\n";
			}
			if ( cscf_PluginSettings::PhoneNumber() ) {
				$message .= esc_html__( 'Phone', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ': ' . esc_attr( $this->PhoneNumber ) . "\n\n";
			}
			$message .= esc_html__( 'Message', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ':' . "\n\n" . esc_html( $this->Message ) . "\n\n";

			$result = ( wp_mail( $this->Email, cscf_PluginSettings::Subject(), stripslashes( $message ), $header ) );

			//remove filters (play nice)
			$filters->remove( 'wp_mail_from' );
			$filters->remove( 'wp_mail_from_name' );
		}

		return $result;
	}
}
