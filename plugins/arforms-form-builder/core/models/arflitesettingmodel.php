<?php
class arflitesettingmodel {
	var $menu;
	var $mu_menu;
	var $custom_stylesheet;
	var $jquery_css;
	var $accordion_js;
	var $submit_value;
	var $admin_permission;
	var $pubkey;
	var $privkey;
	var $re_theme;
	var $re_lang;
	var $re_msg;
	var $current_tab;
	var $use_html;
	var $custom_style;
	var $load_style;
	var $email_to;
	var $reply_to_name;
	var $reply_to;
	var $ar_admin_reply_to_email;
	var $user_nreplyto_email;
	var $reply_to_email;
	var $form_submit_type;
	var $hidden_captcha;
	var $success_msg;
	var $failed_msg;
	var $blank_msg;
	var $unique_msg;
	var $invalid_msg;
	var $smtp_server;
	var $smtp_host;
	var $smtp_port;
	var $smtp_username;
	var $smtp_password;
	var $smtp_encryption;
	var $arf_gmail_api_clientid;
	var $arf_gmail_api_clientsecret;
	var $arf_gmail_api_accesstoken;
	var $gmail_api_connected_gmail;
	var $arflite_gmail_connected_email;
	var $decimal_separator;
	var $arf_success_message_show_time;
	var $arf_css_character_set;
	var $is_smtp_authentication;
	var $arf_email_format;
	var $arf_pre_dup_msg;
	var $arfmainformloadjscss;
	var $arf_load_js_css;
	var $arfviewforms;
	var $arfeditforms;
	var $arfdeleteforms;
	var $arfchangesettings;
	var $arfimportexport;
	var $arfviewentries;
	var $arfcreateentries;
	var $arfdeleteentries;
	var $arfeditdisplays;
	var $arforms_schedular_data;

	function __construct() {
		$this->arflite_set_default_options();
	}

	function arflite_default_options() {
		return array(
			'menu'                          => 'ARForms',
			'mu_menu'                       => 0,
			'use_html'                      => true,
			'jquery_css'                    => false,
			'accordion_js'                  => false,
			'hidden_captcha'                => false,
			're_theme'                      => 'light',
			'success_msg'                   => 'Form is successfully submitted. Thank you!',
			're_msg'                        => 'Invalid reCaptcha. Please try again.',
			'blank_msg'                     => 'This field cannot be blank.',
			'unique_msg'                    => 'This value must be unique.',
			'invalid_msg'                   => 'Problem in submission. Errors are marked below.',
			'failed_msg'                    => 'We\'re sorry. Form is not submitted successfully.',
			'submit_value'                  => 'Submit',
			'admin_permission'              => 'You do not have permission to perform this action',
			'email_to'                      => '[admin_email]',
			'current_tab'                   => 'general_settings',
			'form_submit_type'              => 1,
			'reply_to_name'                 => get_option( 'blogname' ),
			'reply_to'                      => get_option( 'admin_email' ),
			'ar_admin_reply_to_email'       => get_option( 'admin_email' ),
			'user_nreplyto_email'           => get_option( 'admin_email' ),
			'reply_to_email'                => get_option( 'admin_email' ),
			'smtp_server'                   => 'wordpress',
			'smtp_host'                     => '',
			'smtp_port'                     => '',
			'smtp_username'                 => '',
			'smtp_password'                 => '',
			'smtp_encryption'               => 'none',
			'arf_gmail_api_clientid'            => '',
			'arf_gmail_api_clientsecret' 		=> '',
			'arf_gmail_api_accesstoken' 		=> '',
			'gmail_api_connected_gmail' 	=> '',
			'decimal_separator'             => '.',
			'arf_success_message_show_time' => 3,
			'arf_css_character_set'         => '',
			'is_smtp_authentication'        => 1,
			'arf_email_format'              => 'html',
			'arf_pre_dup_msg'               => __( 'You have already submitted this form before. You are not allowed to submit this form again.', 'arforms-form-builder' ),
			'arfmainformloadjscss'          => 0,
			'arf_load_js_css'               => array(),
		);
	}

	function arflitecheckdbstatus() {
		return 'https://reputeinfosystems.net/arforms/wpinfo.php';
	}

	function arflite_set_default_options() {
		global $arflitemainhelper;
		if ( ! isset( $this->load_style ) ) {
			if ( ! isset( $this->custom_style ) ) {
				$this->custom_style = true;
			}

			if ( ! isset( $this->custom_stylesheet ) ) {
				$this->custom_stylesheet = false;
			}

			$this->load_style = ( $this->custom_stylesheet ) ? 'none' : 'all';
		}

		$settings = $this->arflite_default_options();

		foreach ( $settings as $setting => $default ) {
			if ( ! isset( $this->{$setting} ) ) {
				$this->{$setting} = $default;
			}
			unset( $setting );
			unset( $default );
		}

		if ( IS_WPMU && is_admin() ) {
			$mu_menu = get_site_option( 'arfadminmenuname' );
			if ( $mu_menu && ! empty( $mu_menu ) ) {
				$this->menu    = $mu_menu;
				$this->mu_menu = 1;
			}
		}

		foreach ( $this as $k => $v ) {
			$this->{$k} = stripslashes_deep( $v );
			unset( $k );
			unset( $v );
		}
	}

	function arfliteupdate( $params, $cur_tab = '' ) {
		global $wp_roles, $arflitemainhelper;
		if ( $cur_tab == 'general_settings' ) { 

			if ( $this->mu_menu ) {
				update_site_option( 'arfadminmenuname', $this->menu );
			} elseif ( $arflitemainhelper->arflite_is_super_admin() ) {
				update_site_option( 'arfadminmenuname', false );
			}

			$check_allowed_html = arflite_retrieve_attrs_for_wp_kses(true);
			$params['frm_blank_msg'] = wp_kses( $params['frm_blank_msg'], $check_allowed_html );
			$params['frm_invalid_msg'] = wp_kses( $params['frm_invalid_msg'], $check_allowed_html );
			$params['frm_success_msg'] = wp_kses( $params['frm_success_msg'], $check_allowed_html );
			$params['frm_failed_msg'] = wp_kses( $params['frm_failed_msg'], $check_allowed_html );
			$params['frm_submit_value'] = wp_kses( $params['frm_submit_value'], $check_allowed_html );
			$params['frm_smtp_host'] = wp_kses( $params['frm_smtp_host'], $check_allowed_html);
			$params['frm_smtp_port'] = wp_kses( $params['frm_smtp_port'], $check_allowed_html);
			

			$this->pubkey   = trim( $params['frm_pubkey'] );
			$this->privkey  = $params['frm_privkey'];
			$this->re_theme = sanitize_text_field( $params['frm_re_theme'] );
			$this->re_lang  = sanitize_text_field( $params['frm_re_lang'] );

			$settings = $this->arflite_default_options();

			foreach ( $settings as $setting => $default ) {
				if ( isset( $params[ 'frm_' . $setting ] ) ) {
					 
					$this->{$setting} = $params[ 'frm_' . $setting ];
				}
				unset( $setting );
				unset( $default );
			}

			$this->arf_success_message_show_time = isset( $params['arf_success_message_show_time'] ) ? intval( $params['arf_success_message_show_time'] ) : 3;

			$this->jquery_css            = isset( $params['arfmainjquerycss'] ) ? $params['arfmainjquerycss'] : 0;
			$this->accordion_js          = isset( $params['arfmainformaccordianjs'] ) ? $params['arfmainformaccordianjs'] : 0;
			$this->form_submit_type      = isset( $params['arfmainformsubmittype'] ) ? intval( $params['arfmainformsubmittype'] ) : 0;
			$this->hidden_captcha        = isset( $params['arfdisablehiddencaptcha'] ) ? intval( $params['arfdisablehiddencaptcha'] ) : 0;
			$this->arf_css_character_set = isset( $params['arf_css_character_set'] ) ? $params['arf_css_character_set'] : array();

			$this->decimal_separator      = isset( $params['decimal_separator'] ) ? $params['decimal_separator'] : '.';
			$this->is_smtp_authentication = isset( $params['is_smtp_authentication'] ) ? intval( $params['is_smtp_authentication'] ) : 0;
			$this->arf_email_format       = isset( $params['arf_email_format'] ) ? sanitize_text_field( $params['arf_email_format'] ) : 'html';
			$this->arfmainformloadjscss   = isset( $params['frm_arfmainformloadjscss'] ) ? intval( $params['frm_arfmainformloadjscss'] ) : 0;
			$this->arf_load_js_css        = isset( $params['arf_load_js_css'] ) ? $params['arf_load_js_css'] : array();
			$this->reply_to_email         = isset( $params['reply_to_email'] ) ? sanitize_email( $params['reply_to_email'] ) : get_option( 'admin_email' );

			$global_css = isset( $params['arf_global_css'] ) ? sanitize_textarea_field( $params['arf_global_css'] ) : '';

			update_option( 'arflite_global_css', $global_css );

			$opt_data_from_outside = array();
			$opt_data_from_outside = apply_filters( 'arflite_update_global_setting_outside', $opt_data_from_outside, $params );

			if ( is_array( $opt_data_from_outside ) && ! empty( $opt_data_from_outside ) && count( $opt_data_from_outside ) > 0 ) {
				foreach ( $opt_data_from_outside as $key => $optdata ) {
					$this->$key = $optdata;
				}
			}

			$arfroles = $arflitemainhelper->arflite_frm_capabilities();
			$roles    = get_editable_roles();

			foreach ( $arfroles as $arfrole => $arfroledescription ) {

				$this->$arfrole = isset( $params[ $arfrole ] ) ? $params[ $arfrole ] : 'administrator';

				foreach ( $roles as $role => $details ) {
					if ( $this->$arfrole == $role || ( $this->$arfrole == 'editor' && $role == 'administrator' ) || ( $this->$arfrole == 'author' && in_array( $role, array( 'administrator', 'editor' ) ) ) || ( $this->$arfrole == 'contributor' && in_array( $role, array( 'administrator', 'editor', 'author' ) ) ) || $this->$arfrole == 'subscriber' ) {
						$wp_roles->add_cap( $role, $arfrole );
					} else {
						$wp_roles->remove_cap( $role, $arfrole );
					}
				}
			}
		}

		foreach ( $this as $k => $v ) {
			$this->{$k} = stripslashes_deep( $v );
			unset( $k );
			unset( $v );
		}
	}

	function arflitestore( $cur_tab = '' ) {

		global $arfliteformcontroller,$arflitesettingmodel;
		$value_store   = array();
		$value_store_2 = array();

		$value_store   = $arfliteformcontroller->arfliteObjtoArray( $this );
		$value_store_2 = apply_filters( 'arflite_trim_values', $value_store );
		$value_store   = $arfliteformcontroller->arfliteArraytoObj( $value_store_2 );

		$tempObj = new arflitesettingmodel();

		foreach ( $value_store as $k => $v ) {
			$tempObj->$k = $v;
		}

		if ( $cur_tab == 'general_settings' ) {
			update_option( 'arflite_options', $tempObj );
			delete_transient( 'arflite_options' );
			set_transient( 'arflite_options', $tempObj );
		}
	}
}
