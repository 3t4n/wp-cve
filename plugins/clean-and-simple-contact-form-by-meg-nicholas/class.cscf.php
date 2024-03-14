<?php

class cscf {
	public function __construct() {
		//allow short codes to be added in the widget area
		add_filter( 'widget_text', 'do_shortcode' );

		//add action for loading js files
		add_action( 'wp_enqueue_scripts', array(
			$this,
			'RegisterScripts'
		) );

		add_action( 'admin_enqueue_scripts', array(
			$this,
			'RegisterAdminScripts'
		) );

		add_action( 'plugins_loaded', array(
			$this,
			'RegisterTextDomain'
		) );

		add_filter( 'cscf_spamfilter', array( $this, 'SpamFilter' ) );

		//create the settings page
		$settings = new cscf_settings();

	}

	//load text domain

	static function Log( $message ) {

		if ( WP_DEBUG === true ) {

			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}

	function RegisterTextDomain() {
		//$path = CSCF_PLUGIN_DIR . '/languages';
		$path = '/' . CSCF_PLUGIN_NAME . '/languages';
		load_plugin_textdomain( 'clean-and-simple-contact-form-by-meg-nicholas', false, $path );
	}

	function RegisterScripts() {
		wp_register_script( 'jquery-validate', CSCF_PLUGIN_URL . '/js/jquery.validate.min.js', array(
			'jquery'
		), '1.19.3', true );

		wp_register_script( 'cscf-validate', CSCF_PLUGIN_URL . "/js/jquery.validate.contact.form.js",
			'jquery',
			CSCF_VERSION_NUM, true );

		wp_localize_script( 'cscf-validate', 'cscfvars',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_register_style( 'cscf-bootstrap', CSCF_PLUGIN_URL . '/css/bootstrap-forms.min.css',
			null, CSCF_VERSION_NUM );

		wp_register_script( 'csf-recaptcha2',
			'https://www.google.com/recaptcha/api.js?hl=' . get_locale(), null, null, true );

	}

	function RegisterAdminScripts( $hook ) {
		if ( $hook != 'settings_page_contact-form-settings' ) {
			return;
		}

		wp_register_script( 'cscf-admin-settings', CSCF_PLUGIN_URL . '/js/jquery.admin.settings.js',
			array(
				'jquery-ui-sortable',
			), CSCF_VERSION_NUM, false );

		wp_enqueue_script( 'cscf-admin-settings' );
	}

	function Upgrade( $oldVersion ) {

		//turn on the confirm-email option
		if ( $oldVersion <= "4.2.3" ) {
			$options                  = get_option( CSCF_OPTIONS_KEY );
			$options['confirm-email'] = true;
			update_option( CSCF_OPTIONS_KEY, $options );
		}

		//change namespace of options
		if ( get_option( 'cff_options' ) != '' ) {
			update_option( 'cscf_options', get_option( 'cff_options' ) );
			delete_option( 'cff_options' );
		}
		if ( get_option( 'cff_version' ) != '' ) {
			update_option( 'cscf_version', get_option( 'cff_version' ) );
			delete_option( 'cff_version' );
		}

		$options = get_option( 'cscf_options' );
		$updated = false;

		if ( trim( get_option( 'recaptcha_public_key' ) ) <> '' ) {
			$options['recaptcha_public_key'] = get_option( 'recaptcha_public_key' );
			delete_option( 'recaptcha_public_key' );
			$updated = true;
		}

		if ( trim( get_option( 'recaptcha_private_key' ) ) <> '' ) {
			$options['recaptcha_private_key'] = get_option( 'recaptcha_private_key' );
			delete_option( 'recaptcha_private_key' );
			$updated = true;
		}

		if ( $updated ) {
			update_option( 'cscf_options', $options );
		}

		//delete old array key array_key
		if ( get_option( 'array_key' ) != false ) {
			$options = get_option( 'array_key' );

			//check it was this plugin that created it by checking for a few values
			if ( isset( $options['sent_message_heading'] ) && isset( $options['sent_message_body'] ) ) {
				delete_option( 'array_key' );
			}
		}

		//upgrade to 4.2.3 recipient_email becomes recipient_emails (array) for multiple recipients
		$options = get_option( CSCF_OPTIONS_KEY );
		if ( isset( $options['recipient_email'] ) ) {
			$options['recipient_emails']   = array();
			$options['recipient_emails'][] = $options['recipient_email'];
			update_option( CSCF_OPTIONS_KEY, $options );
		}

	}

	/*
	*This is all we need to do to weed out the spam.
	*If akismet plugin is enabled then it will be hooked into these filters.
	*/

	public function SpamFilter( $contact ) {

		$commentData = apply_filters( 'preprocess_comment', array(
			'comment_post_ID'      => $contact->PostID,
			'comment_author'       => $contact->Name,
			'comment_author_email' => $contact->Email,
			'comment_content'      => $contact->Message,
			'comment_type'         => 'contact-form',
			'comment_author_url'   => '',
		) );


		//If it is spam then log as a comment
		if ( isset( $commentData['akismet_result'] ) && $commentData['akismet_result'] === 'true' ) {
			$commentData['comment_approved'] = 'spam';
			wp_insert_comment( $commentData );
			$contact->IsSpam = true;
		} else {
			$contact->IsSpam = false;
		}

		return $contact;
	}
}

