<?php

/*
 * creates the settings page for the plugin
*/

use AlanEFPluginDonation\PluginDonation;

class cscf_settings {
	public function __construct() {

		if ( is_admin() ) {
			add_action( 'admin_menu', array(
				$this,
				'add_plugin_page'
			) );
			add_action( 'admin_init', array(
				$this,
				'page_init'
			) );
		}
		$this->donation       = new PluginDonation(
			CSCF_PLUGIN_NAME,
			'settings_page_contact-form-settings',
			'clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php',
			admin_url( 'options-general.php?page=contact-form-settings' ),
			'Clean and Simple Contact Form'
		);
		add_filter( 'plugindonation_lib_strings_' . CSCF_PLUGIN_NAME , array( $this, 'set_strings' ) );

	}
	public function set_strings( $strings ) {
		$strings = array(
			esc_html__( 'Gift a Donation', 'stop-wp-emails-going-to-spam' ),
			// 0
			esc_html__( 'Hi, I\'m Alan and I support this free plugin, I hope it works well for you.', 'stop-wp-emails-going-to-spam' ),
			// 1
			esc_html__( 'It would really help me know that others find it useful and a great way of doing this is to gift me a small donation', 'stop-wp-emails-going-to-spam' ),
			// 2
			esc_html__( 'Gift a donation: select your desired option', 'stop-wp-emails-going-to-spam' ),
			// 3
			esc_html__( 'My Bitcoin donation wallet', 'stop-wp-emails-going-to-spam' ),
			// 4
			esc_html__( 'Gift a donation via PayPal', 'stop-wp-emails-going-to-spam' ),
			// 5
			esc_html__( 'My Bitcoin Cash address', 'stop-wp-emails-going-to-spam' ),
			// 6
			esc_html__( 'My Ethereum address', 'stop-wp-emails-going-to-spam' ),
			// 7
			esc_html__( 'My Dogecoin address', 'stop-wp-emails-going-to-spam' ),
			// 8
			esc_html__( 'Contribute', 'stop-wp-emails-going-to-spam' ),
			// 9
			esc_html__( 'Contribute to the Open Source Project in other ways', 'stop-wp-emails-going-to-spam' ),
			// 10
			esc_html__( 'Submit a review', 'stop-wp-emails-going-to-spam' ),
			// 11
			esc_html__( 'Translate to your language', 'stop-wp-emails-going-to-spam' ),
			// 12
			esc_html__( 'SUBMIT A REVIEW', 'stop-wp-emails-going-to-spam' ),
			// 13
			esc_html__( 'If you are happy with the plugin then we would love a review. Even if you are not so happy feedback is always useful, but if you have issues we would love you to make a support request first so we can try and help.', 'stop-wp-emails-going-to-spam' ),
			// 14
			esc_html__( 'SUPPORT FORUM', 'stop-wp-emails-going-to-spam' ),
			// 15
			esc_html__( 'Providing some translations for a plugin is very easy and can be done via the WordPress system. You can easily contribute to the community and you don\'t need to translate it all.', 'stop-wp-emails-going-to-spam' ),
			// 16
			esc_html__( 'TRANSLATE INTO YOUR LANGUAGE', 'stop-wp-emails-going-to-spam' ),
			// 17
			esc_html__( 'As an open source project you are welcome to contribute to the development of the software if you can. The development plugin is hosted on GitHub.', 'stop-wp-emails-going-to-spam' ),
			// 18
			esc_html__( 'CONTRIBUTE ON GITHUB', 'stop-wp-emails-going-to-spam' ),
			// 19
			esc_html__( 'Get Support', 'stop-wp-emails-going-to-spam' ),
			// 20
			esc_html__( 'WordPress SUPPORT FORUM', 'stop-wp-emails-going-to-spam' ),
			// 21
			esc_html__( 'Hi I\'m Alan and I support the free plugin', 'stop-wp-emails-going-to-spam' ),
			// 22
			esc_html__( 'for you.  You have been using the plugin for a while now and WordPress has probably been through several updates by now. So I\'m asking if you can help keep this plugin free, by donating a very small amount of cash. If you can that would be a fantastic help to keeping this plugin updated.', 'stop-wp-emails-going-to-spam' ),
			// 23
			esc_html__( 'Donate via this page', 'stop-wp-emails-going-to-spam' ),
			// 24
			esc_html__( 'Remind me later', 'stop-wp-emails-going-to-spam' ),
			// 25
			esc_html__( 'I have already donated', 'stop-wp-emails-going-to-spam' ),
			// 26
			esc_html__( 'I don\'t want to donate, dismiss this notice permanently', 'stop-wp-emails-going-to-spam' ),
			// 27
			esc_html__( 'Hi I\'m Alan and you have been using this plugin', 'stop-wp-emails-going-to-spam' ),
			// 28
			esc_html__( 'for a while - that is awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help spread the word and boost my motivation..', 'stop-wp-emails-going-to-spam' ),
			// 29
			esc_html__( 'OK, you deserve it', 'stop-wp-emails-going-to-spam' ),
			// 30
			esc_html__( 'Maybe later', 'stop-wp-emails-going-to-spam' ),
			// 31
			esc_html__( 'Already done', 'stop-wp-emails-going-to-spam' ),
			// 32
			esc_html__( 'No thanks, dismiss this request', 'stop-wp-emails-going-to-spam' ),
			// 33
			esc_html__( 'Donate to Support', 'stop-wp-emails-going-to-spam' ),
			// 34
			esc_html__( 'Settings', 'stop-wp-emails-going-to-spam' ),
			// 35
			esc_html__( 'Help Develop', 'stop-wp-emails-going-to-spam' ),
			// 36
			esc_html__( 'Buy Me a Coffee makes supporting fun and easy. In just a couple of taps, you can donate (buy me a coffee) and leave a message. You don’t even have to create an account!', 'plugin-donation-lib' ),
			// 37
		);

		return $strings;
	}

	public function add_plugin_page() {

		// This page will be under "Settings".
		add_options_page(
			esc_html__( 'Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			esc_html__( 'Contact Form', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			'manage_options',
			'contact-form-settings',
			array(
				$this,
				'create_admin_page',
			)
		);
	}

	public function create_admin_page() {
		?>
        <h2><?php esc_html_e( 'Clean and Simple Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></h2>
        <div style="float:left;padding:20px; max-width: 1200px;margin-right: 10%;" class="postbox">
            <table class="form-table">
                <tbody>
                <?php $this->donation->display(); ?>
                </tbody>
            </table>

			<?php if ( cscf_PluginSettings::IsJetPackContactFormEnabled() ) { ?>
                <p class="highlight">
					<?php esc_html_e( 'NOTICE: You have JetPack\'s Contact Form enabled please deactivate it or use the shortcode [cscf-contact-form] instead.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
                    &nbsp;            </p>
			<?php } ?>

            <p class="howto"><?php esc_html_e( 'Please Note: To add the contact form to your page please add the text', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
                <code>[cscf-contact-form]</code> <?php esc_html_e( 'to your post or page.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
            </p>

            <form method="post" action="options.php">
				<?php
				submit_button();
				/* This prints out all hidden setting fields*/
				settings_fields( 'test_option_group' );
				do_settings_sections( 'contact-form-settings' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function page_init() {
		add_settings_section(
			'section_recaptcha',
			'<h3>' . esc_html__( 'ReCAPTCHA Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>',
			array(
				$this,
				'print_section_info_recaptcha',
			),
			'contact-form-settings'
		);
		register_setting( 'test_option_group', CSCF_OPTIONS_KEY, array(
			$this,
			'check_form'
		) );
		add_settings_field( 'use_recaptcha', esc_html__( 'Use reCAPTCHA :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_recaptcha', array(
			'use_recaptcha'
		) );
		add_settings_field( 'theme', esc_html__( 'reCAPTCHA Theme :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_recaptcha', array(
			'theme'
		) );
		add_settings_field( 'recaptcha_public_key', esc_html__( 'reCAPTCHA Public Key :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_recaptcha', array(
			'recaptcha_public_key'
		) );
		add_settings_field( 'recaptcha_private_key', esc_html__( 'reCAPTCHA Private Key :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_recaptcha', array(
			'recaptcha_private_key'
		) );
		add_settings_section( 'section_message', '<h3>' . esc_html__( 'Message Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>', array(
			$this,
			'print_section_info_message'
		), 'contact-form-settings' );
		add_settings_field( 'recipient_emails', esc_html__( 'Recipient Emails :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'recipient_emails'
		) );
		add_settings_field( 'confirm-email', esc_html__( 'Confirm Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'confirm-email'
		) );
		add_settings_field( 'email-sender', esc_html__( 'Allow users to email themselves a copy :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'email-sender'
		) );
		add_settings_field( 'contact-consent', esc_html__( 'Add a consent checkbox :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'contact-consent'
		) );
		add_settings_field( 'contact-consent-msg', esc_html__( 'Consent message :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'contact-consent-msg'
		) );
		add_settings_field( 'phone-number', esc_html__( 'Add a phone number field :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'phone-number'
		) );
		add_settings_field( 'phone-number-mandatory', esc_html__( 'Phone number is mandatory :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'phone-number-mandatory'
		) );
		add_settings_field( 'override-from', esc_html__( 'Override \'From\' Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'override-from'
		) );
		add_settings_field( 'from-email', esc_html__( '\'From\' Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'from-email'
		) );
		add_settings_field( 'subject', esc_html__( 'Email Subject :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'subject'
		) );
		add_settings_field( 'message', esc_html__( 'Message :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'message'
		) );
		add_settings_field( 'sent_message_heading', esc_html__( 'Message Sent Heading :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'sent_message_heading'
		) );
		add_settings_field( 'sent_message_body', esc_html__( 'Message Sent Content :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_message', array(
			'sent_message_body'
		) );
		add_settings_section( 'section_styling', '<h3>' . esc_html__( 'Styling and Validation', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>', array(
			$this,
			'print_section_info_styling'
		), 'contact-form-settings' );
		add_settings_field( 'load_stylesheet', esc_html__( 'Use the plugin default stylesheet (un-tick to use your theme style sheet instead) :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_styling', array(
			'load_stylesheet'
		) );
		add_settings_field( 'use_client_validation', esc_html__( 'Use client side validation (AJAX) :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields'
		), 'contact-form-settings', 'section_styling', array(
			'use_client_validation'
		) );
	}

	public function check_form(
		$input
	) {

		//recaptcha theme
		if ( isset( $input['theme'] ) ) {
			$input['theme'] = sanitize_text_field( $input['theme'] );
		}

		//recaptcha_public_key
		if ( isset( $input['recaptcha_public_key'] ) ) {
			$input['recaptcha_public_key'] = sanitize_text_field( $input['recaptcha_public_key'] );
		}

		//recaptcha_private_key
		if ( isset( $input['recaptcha_private_key'] ) ) {
			$input['recaptcha_private_key'] = sanitize_text_field( $input['recaptcha_private_key'] );
		}

		//sent_message_heading
		$input['sent_message_heading'] = sanitize_text_field( $input['sent_message_heading'] );

		//sent_message_body
		$input['sent_message_body'] = sanitize_text_field( $input['sent_message_body'] );

		//message
		$input['message'] = sanitize_textarea_field( $input['message'] );


		//consent message
		$input['contact-consent-msg'] = sanitize_text_field( $input['contact-consent-msg'] );

		//recipient_emails
		foreach ( $input['recipient_emails'] as $key => $recipient ) {
			if ( ! filter_var( $input['recipient_emails'][ $key ] ) ) {
				unset( $input['recipient_emails'][ $key ] );
			} else {
				$input['recipient_emails'][ $key ] = sanitize_email( $input['recipient_emails'][ $key ] );
			}
		}

		//from
		if ( ! filter_var( $input['from-email'], FILTER_VALIDATE_EMAIL ) ) {
			unset( $input['from-email'] );
		} else {
			$input['from-email'] = sanitize_email( $input['from-email'] );
		}

		//subject
		$input['subject'] = trim( sanitize_text_field( $input['subject'] ) );
		if ( empty( $input['subject'] ) ) {
			unset( $input['subject'] );
		}

		if ( isset( $_POST['add_recipient'] ) ) {
			$input['recipient_emails'][] = "";
		}

		if ( isset( $_POST['remove_recipient'] ) ) {
			foreach ( $_POST['remove_recipient'] as $key => $element ) {
				unset( $input['recipient_emails'][ $key ] );
			}
		}

		//tidy up the keys
		$tidiedRecipients = array();
		foreach ( $input['recipient_emails'] as $recipient ) {
			$tidiedRecipients[] = $recipient;
		}
		$input['recipient_emails'] = $tidiedRecipients;


		return $input;
	}

	public function print_section_info_recaptcha() {
		print esc_html__( 'Enter your reCAPTCHA settings below :', 'clean-and-simple-contact-form-by-meg-nicholas' );
		print "<p>" . esc_html__( 'To use reCAPTCHA you must get an API key from', 'clean-and-simple-contact-form-by-meg-nicholas' ) . " <a target='_blank' href='" . csf_RecaptchaV2::$signUpUrl . "'>Google reCAPTCHA</a></p>";
	}

	public function print_section_info_message() {
		print esc_html__( 'Enter your message settings below :', 'clean-and-simple-contact-form-by-meg-nicholas' );
	}

	public function print_section_info_styling() {

		//print 'Enter your styling settings below:';

	}

	public function create_fields(
		$args
	) {
		$fieldname        = $args[0];

		switch ( $fieldname ) {
			case 'use_recaptcha':
				$checked = cscf_PluginSettings::UseRecaptcha() === true ? 'checked' : '';
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="use_recaptcha"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[use_recaptcha]"><?php
				break;
			case 'load_stylesheet':
				$checked = cscf_PluginSettings::LoadStyleSheet() === true ? 'checked' : '';
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="load_stylesheet"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[load_stylesheet]"><?php
				break;
			case 'recaptcha_public_key':
				$disabled = cscf_PluginSettings::UseRecaptcha() === false ? 'readonly' : '';
				?><input <?php echo esc_attr( $disabled ); ?> type="text" size="60" id="recaptcha_public_key"
                                                              name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recaptcha_public_key]"
                                                              value="<?php echo esc_attr( cscf_PluginSettings::PublicKey() ); ?>" /><?php
				break;
			case 'recaptcha_private_key':
				$disabled = cscf_PluginSettings::UseRecaptcha() === false ? 'readonly' : '';
				?><input <?php echo esc_attr( $disabled ); ?> type="text" size="60" id="recaptcha_private_key"
                                                              name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recaptcha_private_key]"
                                                              value="<?php echo esc_attr( cscf_PluginSettings::PrivateKey() ); ?>" /><?php
				break;
			case 'recipient_emails':
				?>
                <ul id="recipients"><?php
				foreach ( cscf_PluginSettings::RecipientEmails() as $key => $recipientEmail ) {
					?>
                    <li class="recipient_email" data-element="<?php echo esc_attr($key); ?>">
                        <input class="enter_recipient" type="email" size="50"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recipient_emails][<?php echo esc_attr($key) ?>]"
                               value="<?php echo esc_attr( $recipientEmail ); ?>"/>
                        <input class="add_recipient" title="Add New Recipient" type="submit" name="add_recipient"
                               value="+">
                        <input class="remove_recipient" title="Remove This Recipient" type="submit"
                               name="remove_recipient[<?php echo esc_attr( $key ); ?>]" value="-">
                    </li>

					<?php
				}
				?></ul><?php
				break;
			case 'confirm-email':
				$checked = cscf_PluginSettings::ConfirmEmail() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="confirm-email"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[confirm-email]"><?php
				break;
			case 'override-from':
				$checked = cscf_PluginSettings::OverrideFrom() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="override-from"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[override-from]"><?php
				break;
			case 'email-sender':
				$checked = cscf_PluginSettings::EmailToSender() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="email-sender"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[email-sender]"><?php
				break;
			case 'contact-consent':
				$checked = cscf_PluginSettings::ContactConsent() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="contact-consent"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[contact-consent]"><?php
				break;
			case 'contact-consent-msg':
				?><input type="text" size="60" id="contact-consent-msg"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[contact-consent-msg]"
                         value="<?php echo esc_attr( cscf_PluginSettings::ContactConsentMsg() ); ?>"><?php
				break;
			case 'phone-number':
				$checked = cscf_PluginSettings::PhoneNumber() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="phone-number"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[phone-number]"><?php
				break;
			case 'phone-number-mandatory':
				$checked = cscf_PluginSettings::PhoneNumberMandatory() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="phone-number-mandatory"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[phone-number-mandatory]"><?php
				break;
			case 'from-email':
				$disabled = cscf_PluginSettings::OverrideFrom() === false ? "readonly" : "";
				?><input <?php echo esc_attr($disabled); ?> type="text" size="60" id="from-email"
                                                  name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[from-email]"
                                                  value="<?php echo esc_attr( cscf_PluginSettings::FromEmail() ); ?>" /><?php
				break;
			case 'subject':
				?><input type="text" size="60" id="subject" name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[subject]"
                         value="<?php echo esc_attr( cscf_PluginSettings::Subject() ); ?>" /><?php
				break;
			case 'sent_message_heading':
				?><input type="text" size="60" id="sent_message_heading"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[sent_message_heading]"
                         value="<?php echo esc_attr( cscf_PluginSettings::SentMessageHeading() ); ?>" /><?php
				break;
			case 'sent_message_body':
				?><textarea cols="63" rows="8"
                            name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[sent_message_body]"><?php echo esc_attr( cscf_PluginSettings::SentMessageBody() ); ?></textarea><?php
				break;
			case 'message':
				?><textarea cols="63" rows="8"
                            name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[message]"><?php echo esc_attr( cscf_PluginSettings::Message() ); ?></textarea><?php
				break;
			case 'theme':
				$theme = cscf_PluginSettings::Theme();
				$disabled = cscf_PluginSettings::UseRecaptcha() == false ? "disabled" : "";
				?>
                <select <?php echo esc_attr($disabled); ?> id="theme" name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[theme]">
                    <option <?php echo ( 'light' == $theme ) ? 'selected' : ''; ?>
                            value="light"><?php esc_html_e( 'Light', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></option>
                    <option <?php echo (  'dark' == $theme ) ? 'selected' : ''; ?>
                            value="dark"><?php esc_html_e( 'Dark', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></option>
                </select>
				<?php
				break;
			case 'use_client_validation':
				$checked = cscf_PluginSettings::UseClientValidation() == true ? "checked" : "";
				?><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="use_client_validation"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[use_client_validation]"><?php
				break;
			default:
				break;
		}
	}
}
