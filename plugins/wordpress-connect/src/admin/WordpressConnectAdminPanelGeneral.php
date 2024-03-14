<?php

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 19 Apr 2011
 *
 * @file WordpressConnectAdminPanelGeneral.php
 *
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Wordpress Connect wordpress plugin
 */
class WordpressConnectAdminPanelGeneral {

	/**
	 * Creates a new instance of WordpressConnectAdminPanelGeneral
	 *
	 * @since	2.0
	 */
	function WordpressConnectAdminPanelGeneral(){

		add_action( 'admin_init', array( &$this, 'add_admin_settings' ), 9 );
		add_action( 'admin_menu', array( &$this, 'add_admin_panel' ) );

	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_settings(){

		if ( !current_user_can( 'manage_options' ) ) { return; }		
		
		$options = get_option( WPC_OPTIONS );

		if ( empty( $options[ WPC_OPTIONS_APP_ID ] ) ) {
			add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>" . sprintf( __('Wordpress Connect needs configuration information on its <a href="%s">settings</a> page.', WPC_TEXT_DOMAIN ), admin_url( 'options-general.php?page=' . WPC_SETTINGS_PAGE ) )."</p></div>';" ) );
		}

		register_setting( WPC_OPTIONS, WPC_OPTIONS, array( &$this, 'admin_settings_validate' ) );

		// adds sections
		add_settings_section( WPC_SETTINGS_SECTION_GENERAL, __( 'General Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_general' ), WPC_SETTINGS_PAGE );
		// general settings
		add_settings_field( WPC_OPTIONS_LANGUAGE, __( 'Language', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_language' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );
		add_settings_field( WPC_OPTIONS_APP_ID, __( 'Application ID', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_app_id' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );
		add_settings_field( WPC_OPTIONS_APP_ADMINS, __( 'Application Admins', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_admins' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );
		add_settings_field( WPC_OPTIONS_IMAGE_URL, __( 'Image URL', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_image_url' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );
		add_settings_field( WPC_OPTIONS_DESCRIPTION, __( 'Description', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_description' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );
		add_settings_field( WPC_OPTIONS_THEME, __( 'Theme', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_general_theme' ), WPC_SETTINGS_PAGE, WPC_SETTINGS_SECTION_GENERAL );

	}

	/**
	 * Validates general settings
	 * @param	$input the settings value
	 */
	function admin_settings_validate( $input ){

		$input = apply_filters( WPC_OPTIONS, $input ); // filter to let sub-plugins validate their options too
		return $input;
	}

	/**
	 */
	function admin_section_general(){}

	/**
	 * Renders the language selector
	 */
	function admin_setting_general_language(){

		$options = get_option( WPC_OPTIONS );
		$language = $options[ WPC_OPTIONS_LANGUAGE ];

?>
			<select id="<?php echo WPC_OPTIONS_LANGUAGE; ?>" name="<?php echo WPC_OPTIONS,'[',WPC_OPTIONS_LANGUAGE,']'; ?>">
				<option <?php echo ( $language == 'en_US' ) ? 'selected="selected"' : ''; ?> value="en_US"><?php _e( 'English (US)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'en_GB' ) ? 'selected="selected"' : ''; ?> value="en_GB"><?php _e( 'English (UK)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ar_AR' ) ? 'selected="selected"' : ''; ?> value="ar_AR"><?php _e( 'Arabic', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'de_DE' ) ? 'selected="selected"' : ''; ?> value="de_DE"><?php _e( 'German', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fr_FR' ) ? 'selected="selected"' : ''; ?> value="fr_FR"><?php _e( 'French', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'it_IT' ) ? 'selected="selected"' : ''; ?> value="it_IT"><?php _e( 'Italian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ru_RU' ) ? 'selected="selected"' : ''; ?> value="ru_RU"><?php _e( 'Russian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'es_ES' ) ? 'selected="selected"' : ''; ?> value="es_ES"><?php _e( 'Spanish (Spain)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'af_ZA' ) ? 'selected="selected"' : ''; ?> value="af_ZA"><?php _e( 'Afrikaans', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sq_AL' ) ? 'selected="selected"' : ''; ?> value="sq_AL"><?php _e( 'Albanian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'hy_AM' ) ? 'selected="selected"' : ''; ?> value="hy_AM"><?php _e( 'Armenian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ay_BO' ) ? 'selected="selected"' : ''; ?> value="ay_BO"><?php _e( 'Aymara', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'az_AZ' ) ? 'selected="selected"' : ''; ?> value="az_AZ"><?php _e( 'Azeri', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'be_BY' ) ? 'selected="selected"' : ''; ?> value="be_BY"><?php _e( 'Belarusian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'bn_IN' ) ? 'selected="selected"' : ''; ?> value="bn_IN"><?php _e( 'Bengali', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'bs_BA' ) ? 'selected="selected"' : ''; ?> value="bs_BA"><?php _e( 'Bosnian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'bg_BG' ) ? 'selected="selected"' : ''; ?> value="bg_BG"><?php _e( 'Bulgarian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ca_ES' ) ? 'selected="selected"' : ''; ?> value="ca_ES"><?php _e( 'Catalan', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ck_US' ) ? 'selected="selected"' : ''; ?> value="ck_US"><?php _e( 'Cherokee', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'zh_CN' ) ? 'selected="selected"' : ''; ?> value="zh_CN"><?php _e( 'Chinese (China)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'zh_HK' ) ? 'selected="selected"' : ''; ?> value="zh_HK"><?php _e( 'Chinese (Hong Kong)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'zh_TW' ) ? 'selected="selected"' : ''; ?> value="zh_TW"><?php _e( 'Chinese (Taiwan)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'hr_HR' ) ? 'selected="selected"' : ''; ?> value="hr_HR"><?php _e( 'Croatian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'cs_CZ' ) ? 'selected="selected"' : ''; ?> value="cs_CZ"><?php _e( 'Czech', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'da_DK' ) ? 'selected="selected"' : ''; ?> value="da_DK"><?php _e( 'Danish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'nl_NL' ) ? 'selected="selected"' : ''; ?> value="nl_NL"><?php _e( 'Dutch', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'nl_BE' ) ? 'selected="selected"' : ''; ?> value="nl_BE"><?php _e( 'Dutch (Belgi&euml;)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'en_PL' ) ? 'selected="selected"' : ''; ?> value="en_PL"><?php _e( 'English (Pirate)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'en_UD' ) ? 'selected="selected"' : ''; ?> value="en_UD"><?php _e( 'English (Upside Down)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'eo_EO' ) ? 'selected="selected"' : ''; ?> value="eo_EO"><?php _e( 'Esperanto', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'et_EE' ) ? 'selected="selected"' : ''; ?> value="et_EE"><?php _e( 'Estoninan', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fo_FO' ) ? 'selected="selected"' : ''; ?> value="fo_FO"><?php _e( 'Faroese', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'tl_PH' ) ? 'selected="selected"' : ''; ?> value="tl_PH"><?php _e( 'Filipino', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fi_FI' ) ? 'selected="selected"' : ''; ?> value="fi_FI"><?php _e( 'Finnish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fr_CA' ) ? 'selected="selected"' : ''; ?> value="fr_CA"><?php _e( 'French (Canada)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'gl_ES' ) ? 'selected="selected"' : ''; ?> value="gl_ES"><?php _e( 'Galician', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ka_GE' ) ? 'selected="selected"' : ''; ?> value="ka_GE"><?php _e( 'Georgian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'el_GR' ) ? 'selected="selected"' : ''; ?> value="el_GR"><?php _e( 'Greek', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'gn_PY' ) ? 'selected="selected"' : ''; ?> value="gn_PY"><?php _e( 'Guaran&iacute;', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'gu_IN' ) ? 'selected="selected"' : ''; ?> value="gu_IN"><?php _e( 'Gujarati', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'he_IL' ) ? 'selected="selected"' : ''; ?> value="he_IL"><?php _e( 'Hebrew', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'hi_IN' ) ? 'selected="selected"' : ''; ?> value="hi_IN"><?php _e( 'Hindi', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'hu_HU' ) ? 'selected="selected"' : ''; ?> value="hu_HU"><?php _e( 'Hungarian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'is_IS' ) ? 'selected="selected"' : ''; ?> value="is_IS"><?php _e( 'Icelandic', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'id_ID' ) ? 'selected="selected"' : ''; ?> value="id_ID"><?php _e( 'Indonesian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ga_IE' ) ? 'selected="selected"' : ''; ?> value="ga_IE"><?php _e( 'Irish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ja_JP' ) ? 'selected="selected"' : ''; ?> value="ja_JP"><?php _e( 'Japanese', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'jv_ID' ) ? 'selected="selected"' : ''; ?> value="jv_ID"><?php _e( 'Javanese', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'kn_IN' ) ? 'selected="selected"' : ''; ?> value="kn_IN"><?php _e( 'Kannada', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'kk_KZ' ) ? 'selected="selected"' : ''; ?> value="kk_KZ"><?php _e( 'Kazakh', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'km_KH' ) ? 'selected="selected"' : ''; ?> value="km_KH"><?php _e( 'Khmer', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'tl_ST' ) ? 'selected="selected"' : ''; ?> value="tl_ST"><?php _e( 'Klingon', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ko_KR' ) ? 'selected="selected"' : ''; ?> value="ko_KR"><?php _e( 'Korean', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ku_TR' ) ? 'selected="selected"' : ''; ?> value="ku_TR"><?php _e( 'Kurdish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'la_VA' ) ? 'selected="selected"' : ''; ?> value="la_VA"><?php _e( 'Latin', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'lv_LV' ) ? 'selected="selected"' : ''; ?> value="lv_LV"><?php _e( 'Latvian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fb_LT' ) ? 'selected="selected"' : ''; ?> value="fb_LT"><?php _e( 'Leet Speak', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'li_NL' ) ? 'selected="selected"' : ''; ?> value="li_NL"><?php _e( 'Limburgish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'lt_LT' ) ? 'selected="selected"' : ''; ?> value="lt_LT"><?php _e( 'Lithuanian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mk_MK' ) ? 'selected="selected"' : ''; ?> value="mk_MK"><?php _e( 'Macedonian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mg_MG' ) ? 'selected="selected"' : ''; ?> value="mg_MG"><?php _e( 'Malagasy', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ms_MY' ) ? 'selected="selected"' : ''; ?> value="ms_MY"><?php _e( 'Malay', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ml_IN' ) ? 'selected="selected"' : ''; ?> value="ml_IN"><?php _e( 'Malayalam', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mt_MT' ) ? 'selected="selected"' : ''; ?> value="mt_MT"><?php _e( 'Maltese', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mr_IN' ) ? 'selected="selected"' : ''; ?> value="mr_IN"><?php _e( 'Marathi', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mn_MN' ) ? 'selected="selected"' : ''; ?> value="mn_MN"><?php _e( 'Mongolian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ne_NP' ) ? 'selected="selected"' : ''; ?> value="ne_NP"><?php _e( 'Nepali', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'mg_MG' ) ? 'selected="selected"' : ''; ?> value="mg_MG"><?php _e( 'Malagasy', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ko_KR' ) ? 'selected="selected"' : ''; ?> value="ko_KR"><?php _e( 'Korean', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'nb_NO' ) ? 'selected="selected"' : ''; ?> value="nb_NO"><?php _e( 'Norwegian (bokmal)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'nn_NO' ) ? 'selected="selected"' : ''; ?> value="nn_NO"><?php _e( 'Norwegian (nynorsk)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'se_NO' ) ? 'selected="selected"' : ''; ?> value="se_NO"><?php _e( 'Northern S&aacute;mi', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ps_AF' ) ? 'selected="selected"' : ''; ?> value="ps_AF"><?php _e( 'Pashto', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'fa_IR' ) ? 'selected="selected"' : ''; ?> value="fa_IR"><?php _e( 'Persian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'pl_PL' ) ? 'selected="selected"' : ''; ?> value="pl_PL"><?php _e( 'Polish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'pt_BR' ) ? 'selected="selected"' : ''; ?> value="pt_BR"><?php _e( 'Portugese (Brazil)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'pt_PT' ) ? 'selected="selected"' : ''; ?> value="pt_PT"><?php _e( 'Portugese (Portugal)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'qu_PE' ) ? 'selected="selected"' : ''; ?> value="qu_PE"><?php _e( 'Quechua', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'pa_IN' ) ? 'selected="selected"' : ''; ?> value="pa_IN"><?php _e( 'Punjabi', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ro_RO' ) ? 'selected="selected"' : ''; ?> value="ro_RO"><?php _e( 'Romanian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'rm_CH' ) ? 'selected="selected"' : ''; ?> value="rm_CH"><?php _e( 'Romansh', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sa_IN' ) ? 'selected="selected"' : ''; ?> value="sa_IN"><?php _e( 'Sanskrit', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sr_RS' ) ? 'selected="selected"' : ''; ?> value="sr_RS"><?php _e( 'Serbian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'so_SO' ) ? 'selected="selected"' : ''; ?> value="so_SO"><?php _e( 'Somali', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sk_SK' ) ? 'selected="selected"' : ''; ?> value="sk_SK"><?php _e( 'Slovak', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sl_SL' ) ? 'selected="selected"' : ''; ?> value="sl_SL"><?php _e( 'Slovenian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'es_CL' ) ? 'selected="selected"' : ''; ?> value="es_CL"><?php _e( 'Spanish (Chile)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'es_CO' ) ? 'selected="selected"' : ''; ?> value="es_CO"><?php _e( 'Spanish (Colombia)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'es_MX' ) ? 'selected="selected"' : ''; ?> value="es_MX"><?php _e( 'Spanish (Mexico)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'es_VE' ) ? 'selected="selected"' : ''; ?> value="es_VE"><?php _e( 'Spanish (Venezuela)', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sy_SY' ) ? 'selected="selected"' : ''; ?> value="sy_SY"><?php _e( 'Syriac', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sw_KE' ) ? 'selected="selected"' : ''; ?> value="sw_KE"><?php _e( 'Swahili', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'sv_SE' ) ? 'selected="selected"' : ''; ?> value="sv_SE"><?php _e( 'Swedish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'tg_TJ' ) ? 'selected="selected"' : ''; ?> value="tg_TJ"><?php _e( 'Tajik', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ta_IN' ) ? 'selected="selected"' : ''; ?> value="ta_IN"><?php _e( 'Tamil', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'tt_RU' ) ? 'selected="selected"' : ''; ?> value="tt_RU"><?php _e( 'Tatar', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'te_IN' ) ? 'selected="selected"' : ''; ?> value="te_IN"><?php _e( 'Telugu', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'th_TH' ) ? 'selected="selected"' : ''; ?> value="th_TH"><?php _e( 'Thai', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'tr_TR' ) ? 'selected="selected"' : ''; ?> value="tr_TR"><?php _e( 'Turkish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'uk_UA' ) ? 'selected="selected"' : ''; ?> value="uk_UA"><?php _e( 'Ukrainian', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'ur_PK' ) ? 'selected="selected"' : ''; ?> value="ur_PK"><?php _e( 'Urdu', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'uz_UZ' ) ? 'selected="selected"' : ''; ?> value="uz_UZ"><?php _e( 'Uzbek', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'vi_VN' ) ? 'selected="selected"' : ''; ?> value="vi_VN"><?php _e( 'Vietnamese', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'cy_GB' ) ? 'selected="selected"' : ''; ?> value="cy_GB"><?php _e( 'Welsh', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'xh_ZA' ) ? 'selected="selected"' : ''; ?> value="xh_ZA"><?php _e( 'Xhosa', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'yi_DE' ) ? 'selected="selected"' : ''; ?> value="yi_DE"><?php _e( 'Yiddish', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $language == 'xh_ZA' ) ? 'selected="selected"' : ''; ?> value="zu_ZA"><?php _e( 'Zulu', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
<?php

		echo '<span class="description">', __( 'The default Facebook Connect language.', WPC_TEXT_DOMAIN ), '</span>';
	}

	/**
	 * Renders the app id field
	 */
	function admin_setting_general_app_id(){

		$options = get_option( WPC_OPTIONS );
		echo '<input type="text" id="',WPC_OPTIONS_APP_ID,'" name="',WPC_OPTIONS,'[',WPC_OPTIONS_APP_ID,']" value="',$options[ WPC_OPTIONS_APP_ID ],'" size="48" />';
		echo '<br/>', '<span class="description">';
		_e( 'The ID of the Facebook Connect Application. You can create a new Facebook Application <a href="http://developers.facebook.com/setup/" target="_blank" rel="nofollow">here</a>.', WPC_TEXT_DOMAIN );
		echo '</span>';

	}

	/**
	 * Renders the app admins field
	 */
	function admin_setting_general_admins(){

		$options = get_option( WPC_OPTIONS );
		echo '<input type="text" id="',WPC_OPTIONS_APP_ADMINS,'" name="',WPC_OPTIONS,'[',WPC_OPTIONS_APP_ADMINS,']" value="',$options[ WPC_OPTIONS_APP_ADMINS ],'" size="48" />';
		echo '<br/>', '<span class="description">';
		_e( 'Comma separated Facebook ids - gives the specified users the ability to moderate comments and review like stats.', WPC_TEXT_DOMAIN );
		echo '</span>';

	}

	/**
	 * Renders the image url field
	 */
	function admin_setting_general_image_url(){

		$options = get_option( WPC_OPTIONS );
		echo '<input type="text" id="',WPC_OPTIONS_IMAGE_URL,'" name="',WPC_OPTIONS,'[',WPC_OPTIONS_IMAGE_URL,']" value="',$options[ WPC_OPTIONS_IMAGE_URL ],'" size="96" />';
		echo '<br/>', '<span class="description">';
		_e( 'URL to the thumbnail for Facebook Stream posts. ', WPC_TEXT_DOMAIN );
		_e( 'The image must be at least 50px by 50px and have a maximum aspect ratio of 3:1. ', WPC_TEXT_DOMAIN );
		echo '</span>';

		$image_src = $options[ WPC_OPTIONS_IMAGE_URL ];
		if ( !empty( $image_src ) ){
			echo '<br/><br/><img src="', $image_src ,'" alt="image preview" />';
		}
	}

	/**
	 * Renders the description field
	 */
	function admin_setting_general_description(){

		$options = get_option( WPC_OPTIONS );
		echo '<textarea cols="60" rows="5" id="',WPC_OPTIONS_DESCRIPTION,'" name="',WPC_OPTIONS,'[',WPC_OPTIONS_DESCRIPTION,']">',$options[ WPC_OPTIONS_DESCRIPTION ];
		echo '</textarea><br/>', '<span class="description">';
		_e( 'Description of the blog for Facebook Like and Stream posts.', WPC_TEXT_DOMAIN );
		echo '</span>';

	}

	/**
	 * Renders the language selector
	 */
	function admin_setting_general_theme(){

		$options = get_option( WPC_OPTIONS );
		$theme = $options[ WPC_OPTIONS_THEME ];
?>
			<select id="<?php echo WPC_OPTIONS_THEME; ?>" name="<?php echo WPC_OPTIONS,'[',WPC_OPTIONS_THEME,']'; ?>">
				<option <?php echo ( $theme == WPC_THEME_LIGHT ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_THEME_LIGHT; ?>"><?php _e( 'Light Theme', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $theme == WPC_THEME_DARK ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_THEME_DARK; ?>"><?php _e( 'Dark Theme', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
<?php

		echo '<span class="description">', __( 'The default Facebook theme', WPC_TEXT_DOMAIN ), '</span>';
	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_panel(){

		global $wpc_options_page;

		$wpc_options_page = add_menu_page(
			__( 'WP Connect', WPC_TEXT_DOMAIN ),
			__( 'WP Connect', WPC_TEXT_DOMAIN ),
			'manage_options',
			WPC_SETTINGS_PAGE,
			array( &$this, 'admin_section_page' )
		);

	}

	/**
	 * Prints out the main settings page
	 */
	function admin_section_page(){

?>
		<div class="wrap" style="width:70%">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e( 'Wordpress Connect', WPC_TEXT_DOMAIN ) ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields( WPC_OPTIONS ); ?>
				<table><tr><td>
				<?php do_settings_sections( WPC_SETTINGS_PAGE ); ?>
				</td></tr></table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div>
<?php

	}

	/**
	 * Restores default configuration
	 */
	public static function restoreDefaults(){

		// set the settings controlled by this class to their default values
	}
}

?>