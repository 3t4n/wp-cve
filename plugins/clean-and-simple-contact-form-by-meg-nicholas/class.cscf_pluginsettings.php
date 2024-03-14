<?php

class cscf_PluginSettings
{
    static
    function UseRecaptcha()
    {

        /* @var $options type array */
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['use_recaptcha']) ? true : false;
    }

    static
    function Theme()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['theme']) ? $options['theme'] : 'light';
    }

	static
	function PublicKey() {
		$options = get_option( CSCF_OPTIONS_KEY );

		return isset( $options['recaptcha_public_key'] ) ? $options['recaptcha_public_key'] : null;

	}

	static
	function PrivateKey() {
		$options = get_option( CSCF_OPTIONS_KEY );

		return isset( $options['recaptcha_private_key'] ) ? $options['recaptcha_private_key'] : null;

	}

    static
    function SentMessageHeading()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['sent_message_heading']) ? $options['sent_message_heading'] : esc_html__('Message Sent', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function SentMessageBody()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['sent_message_body']) ? $options['sent_message_body'] : esc_html__('Thank you for your message, we will be in touch very shortly.', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function Message()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['message'])? $options['message'] : esc_html__('Please enter your contact details and a short message below and I will try to answer your query as soon as possible.', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function LoadStyleSheet()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['load_stylesheet']) ? true : false;
    }

    static
    function UseClientValidation()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['use_client_validation']) ? true : false;
    }

	static
	function RecipientEmails() {
		$options = get_option( CSCF_OPTIONS_KEY );
		if ( isset( $options['recipient_emails'] ) && count( $options['recipient_emails'] ) == 0 ) {
			unset( $options['recipient_emails'] );
		}

		return isset( $options['recipient_emails'] ) ? $options['recipient_emails'] : array( get_bloginfo( 'admin_email' ) );
	}

    static
    function Subject()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['subject']) ? $options['subject']: get_bloginfo('name') . esc_html__(' -  Web Enquiry', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function FromEmail()
    {
        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['from-email']) ? $options['from-email'] : "";
    }

    static
    function OverrideFrom()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['override-from']) ? true : false;

    }


    static
    function EmailToSender()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['email-sender']) ? true : false;

    }


    static
    function ContactConsent()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['contact-consent']) ? true : false;

    }

    static
    function ContactConsentMsg()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

	    return isset( $options['contact-consent-msg'] ) ? $options['contact-consent-msg'] : esc_html__( 'I consent to my contact details being stored', 'clean-and-simple-contact-form-by-meg-nicholas' );

    }


    static
    function PhoneNumber()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['phone-number']) ? true : false;

    }

	static
    function PhoneNumberMandatory()
    {

        $options = get_option(CSCF_OPTIONS_KEY);

        return isset($options['phone-number-mandatory']) ? true : false;

    }


	static
    function IsJetPackContactFormEnabled()
    {
        //check for jetpack plugin
        if (!is_plugin_active('jetpack/jetpack.php'))
            return false;

        //check we can use the jetpack method
        if (!method_exists('JetPack', 'get_active_modules'))
            return false;

        //now check if it is in the active modules
        return in_array('contact-form', JetPack::get_active_modules());

    }

    static  function InputIcons()
    {
        return false;
    }

    static function ConfirmEmail()
    {
        $options = get_option(CSCF_OPTIONS_KEY);
        return isset($options['confirm-email']) ? true : false;
    }
}

