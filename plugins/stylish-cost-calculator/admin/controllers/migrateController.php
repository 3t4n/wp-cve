<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * *Handles migration data to show in migration view and method is used to show menu
 */

class migrateController {

	protected $db;

	function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}

	/**
	 * @param int $id if no param returns all calculators
	 * @return array arral of calculators with realtions
	 */

	function getCalculatorData( int $id ) {
		$scc_form            = $this->db->get_results( $this->db->prepare( "SELECT id, formname,description,formstored,formtranslate  FROM {$this->db->prefix}scc_forms where id = %d", array( $id ) ) );
		$scc_form_parameters = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_form_parameters where form_id = %d ", array( $id ) ) );
		$quote               = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_quote_submissions where calc_id = %d ", array( $id ) ) );

		$data_response                    = array();
		$scc_form[0]->formstored          = (array) json_decode( stripslashes( stripslashes( $scc_form[0]->formstored ) ) );
		$scc_form[0]->formtranslate       = (array) json_decode( stripslashes( stripslashes( $scc_form[0]->formtranslate ) ) );
		( $quote ) ? $scc_form[0]->quotes = (array) $quote : $scc_form[0]->quotes = array();
		$scc_calc_params                  = (array) json_decode( stripslashes( stripslashes( $scc_form_parameters[0]->parameters ) ) );
		// if parameters return null, try less stripslashing
		if ( empty( $scc_calc_params ) ) {
			$scc_calc_params = (array) json_decode( stripslashes( $scc_form_parameters[0]->parameters ) );
		}
		$scc_form_parameters[0]->parameters = (array) $scc_calc_params;

		$data_response['scc_form']            = (array) $scc_form[0];
		$data_response['scc_form_parameters'] = (array) $scc_form_parameters[0];
		return $data_response;
	}

	/**
	 * @return array of calculatos to loop and migrate
	 */

	function getAllOldCalulator() {
		$query = $this->db->prepare( 'SHOW TABLES LIKE %s', $this->db->esc_like( $this->db->prefix . 'scc_forms' ) );
		if ( ! $this->db->get_var( $query ) == $this->db->prefix . 'scc_forms' ) {
			return array();
		}
		$scc_form = $this->db->get_results( $this->db->prepare( "SELECT id,formname FROM {$this->db->prefix}scc_forms ", array() ) );
		if ( $scc_form ) {
			return $scc_form;
		} else {
			return array();
		}
	}

	/**
	 * @return array coupons
	 */
	function getAllOldCoupons() {
		$scc_coupons = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_coupons ;" ) );
		if ( $scc_coupons ) {
			return $scc_coupons;
		} else {
			return array();
		}
	}


	/**
	 * *Check if old database exists
	 * @return bool
	 */
	function existsOld() {
		$scc_form       = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_forms ;", null ) );
		$scc_parameters = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_form_parameters ;", null ) );
		$scc_coupons    = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_coupons ;" ) );

		return ( $scc_form || $scc_parameters || $scc_coupons ) ? true : false;
	}

	/**
	 * @return array of all coupons
	 */
	function getCouponsData() {
		$query  = $this->db->prepare( "SELECT * FROM {$this->db->prefix}scc_coupons" );
		$result = $this->db->get_results( $query );
		return ( $result ) ? $result : array();
	}

	/**
	 * *gets wp_options
	 * @return object of wp_options
	 */
	protected static function get_wpOptions() {

		$options                   = new stdClass();
		$options->scc_color_scheme = get_option( 'scc_currency' );
		$options->scc_currency     = get_option( 'scc_currency' );
		$options->scc_currency_coversion_manual_selection = get_option( 'scc_currency_coversion_manual_selection' );
		$options->scc_currency_coversion_mode             = get_option( 'scc_currency_coversion_mode' );
		$options->scc_currency_style                      = get_option( 'scc_currency_style' );
		$options->scc_currencytext                        = get_option( 'scc_currencytext' );
		$options->scc_email_logo_image                    = get_option( 'scc_email_logo_image' );
		$options->scc_emailsender                         = get_option( 'scc_emailsender', get_option( 'admin_email' ) );
		$options->scc_emailsubject                        = get_option( 'scc_emailsubject', 'Your Quote Request On ' . get_bloginfo( 'url' ) );
		$options->scc_feedback_invoke                     = get_option( 'scc_feedback_invoke' );
		$options->scc_fontsettings                        = get_option( 'scc_fontsettings' );
		$options->scc_license_key                         = get_option( 'scc_license_key' );
		$options->scc_licensed                            = get_option( 'scc_licensed' );
		$options->scc_messageform                         = get_option( 'scc_messageform', "Hello <customer-name>, <br><br> Attached to this email is a PDF file that contains your quote. <br> If you have any further questions please call us, email us here ____. <br><br> Sincerely,<br> Your Company Name<br><br> <hr><br> <b>Customer's Name</b> l <customer-name> <b>Customer's Phone</b> l <customer-phone> <b>Customer's Emai</b> l <customer-email> <b>Customer's IP</b> l <customer-ip-address> <b>Browser Info</b> l <customer-browser-info ><b>Device</b> l <device> <b> Referral </b> | <customer-referral>" );
		$options->scc_sendername                          = get_option( 'scc_sendername', get_bloginfo() );
		$options->scc_stripe_keys                         = get_option( 'scc_stripe_keys' );
		$options->df_scc_captcha_enablement_status        = get_option( 'scc-captcha-enablement-status' );
		$options->scc_recaptcha_secret_key                = get_option( 'scc-recaptcha-secret-key' );
		$options->scc_recaptcha_site_key                  = get_option( 'scc-recaptcha-site-key' );
		$options->scc_save_count                          = get_option( 'scc-save-count' );
		$options->scclk_opt                               = get_option( 'scclk_opt' );

		return $options;
	}

	/**
	 * *Updates wp_options
	 * @param object $options options to be updated
	 *
	 */
	static function update_wpOptions() {
		$options = self::get_wpOptions();

		if ( $options->scc_color_scheme ) {
			update_option( 'df_scc_color-scheme', $options->scc_color_scheme );
		}
		if ( $options->scc_currency ) {
			update_option( 'df_scc_currency', $options->scc_currency );
		}
		if ( $options->scc_currency_coversion_manual_selection ) {
			update_option( 'df_scc_currency_coversion_manual_selection', $options->scc_currency_coversion_manual_selection );
		}
		if ( $options->scc_currency_coversion_mode ) {
			update_option( 'df_scc_currency_coversion_mode', $options->scc_currency_coversion_mode );
		}
		if ( $options->scc_currency_style ) {
			update_option( 'df_scc_currency_style', $options->scc_currency_style );
		}
		if ( $options->scc_currencytext ) {
			update_option( 'df_scc_currencytext', $options->scc_currencytext );
		}
		if ( $options->scc_email_logo_image ) {
			update_option( 'df_scc_email_logo_image', $options->scc_email_logo_image );
		}
		if ( $options->scc_emailsender ) {
			update_option( 'df_scc_emailsender', $options->scc_emailsender );
		}
		if ( $options->scc_emailsubject ) {
			update_option( 'df_scc_emailsubject', $options->scc_emailsubject );
		}
		if ( $options->scc_feedback_invoke ) {
			update_option( 'df_scc_feedback_invoke', $options->scc_feedback_invoke );
		}
		if ( $options->scc_fontsettings ) {
			update_option( 'df_scc_fontsettings', $options->scc_fontsettings );
		}
		if ( $options->scc_license_key ) {
			update_option( 'df_scc_license_key', $options->scc_license_key );
		}
		if ( $options->scc_licensed ) {
			update_option( 'df_scc_licensed', $options->scc_licensed );
		}
		if ( $options->scc_messageform ) {
			update_option( 'df_scc_messageform', $options->scc_messageform );
		}
		if ( $options->scc_sendername ) {
			update_option( 'df_scc_sendername', $options->scc_sendername );
		}
		if ( $options->scc_stripe_keys ) {
			update_option( 'df_scc_stripe_keys', $options->scc_stripe_keys );
		}
		if ( $options->df_scc_captcha_enablement_status ) {
			update_option( 'df_scc-captcha-enablement-status', $options->df_scc_captcha_enablement_status );
		}
		if ( $options->scc_recaptcha_secret_key ) {
			update_option( 'df_scc-recaptcha-secret-key', $options->scc_recaptcha_secret_key );
		}
		if ( $options->scc_recaptcha_site_key ) {
			update_option( 'df_scc-recaptcha-site-key', $options->scc_recaptcha_site_key );
		}
		if ( $options->scc_save_count ) {
			update_option( 'df_scc-save-count', $options->scc_save_count );
		}
		if ( $options->scclk_opt ) {
			update_option( 'df_scclk_opt', $options->scclk_opt );
		}
	}
}
