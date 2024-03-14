<?php

class GetYourGuide_Widget_Settings {
	const SETTINGS_PAGE_IDENTIFIER = 'getyourguide';

	const SECTION_WIDGET = 'getyourguide_section_widget';
	const OPTION_NAME_PARTNER_ID = 'getyourguide_partner_id';
	const OPTION_NAME_CURRENCY = 'getyourguide_currency';
	const OPTION_NAME_LOCALE = 'getyourguide_locale';

	const LOCALE_DEFAULT = 'en-US';
	const CURRENCY_DEFAULT = 'automatic';
	const NUMBER_OF_ITEMS_DEFAULT = 10;
	const CAMPAIGN_PARAM_DEFAULT = '';

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'admin_footer', [ $this, 'expose_settings' ] );
	}

	public function admin_menu() {
		add_options_page(
			__( 'GetYourGuide', 'getyourguide-widget' ),
			__( 'GetYourGuide', 'getyourguide-widget' ),
			'manage_options',
			self::SETTINGS_PAGE_IDENTIFIER,
			[ $this, 'display_settings_page' ]
		);
	}

	/**
	 * Setup all the settings (fields, sections ...)
	 */
	public function settings_init() {
		register_setting( self::SETTINGS_PAGE_IDENTIFIER, self::OPTION_NAME_PARTNER_ID, [
			$this,
			'validate_partner_id'
		] );
		register_setting( self::SETTINGS_PAGE_IDENTIFIER, self::OPTION_NAME_CURRENCY, [ $this, 'validate_currency' ] );
		register_setting( self::SETTINGS_PAGE_IDENTIFIER, self::OPTION_NAME_LOCALE, [ $this, 'validate_locale' ] );

		// New section in the 'getyourguide' page.
		add_settings_section(
			self::SECTION_WIDGET,
			__( 'Widget', 'getyourguide-widget' ),
			null,
			self::SETTINGS_PAGE_IDENTIFIER
		);

		add_settings_field(
			self::OPTION_NAME_PARTNER_ID,
			__( 'Partner ID', 'getyourguide-widget' ),
			[ $this, 'display_partner_id_field' ],
			self::SETTINGS_PAGE_IDENTIFIER,
			self::SECTION_WIDGET
		);

		add_settings_field(
			self::OPTION_NAME_CURRENCY,
			__( 'Currency', 'getyourguide-widget' ),
			[ $this, 'display_currency_field' ],
			self::SETTINGS_PAGE_IDENTIFIER,
			self::SECTION_WIDGET
		);

		add_settings_field(
			self::OPTION_NAME_LOCALE,
			__( 'Locale Code', 'getyourguide-widget' ),
			[ $this, 'display_locale_field' ],
			self::SETTINGS_PAGE_IDENTIFIER,
			self::SECTION_WIDGET
		);
	}

	public function display_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include __DIR__ . '/../views/settings.php';
	}

	public function display_partner_id_field() {
		$description = __( 'Become a GetYourGuide partner!', 'getyourguide-widget' );
		$url         = 'https://partner.getyourguide.com/en-us/signup?cmp=wp-widget';
		$partnerId   = get_option( self::OPTION_NAME_PARTNER_ID, '' );
		include __DIR__ . '/../includes/fields/partner_id_field.php';
	}

	public function display_currency_field() {
		$currency = get_option( self::OPTION_NAME_CURRENCY, self::CURRENCY_DEFAULT );
		$values   = $this->getCurrencies();
		include __DIR__ . '/../includes/fields/currency_field.php';
	}

	public function display_locale_field() {
		$locale = get_option( self::OPTION_NAME_LOCALE, self::LOCALE_DEFAULT );
		$values = $this->getLocaleCodes();
		include __DIR__ . '/../includes/fields/locale_field.php';
	}

	/**
	 * Checks that the given currency is valid.
	 *
	 * @param $currency
	 *
	 * @return string
	 */
	public function validate_currency( $currency ) {
		if ( array_key_exists( $currency, $this->getCurrencies() ) ) {
			return $currency;
		}

		return self::CURRENCY_DEFAULT;
	}

	public function validate_locale( $locale ) {
		if ( array_key_exists( $locale, $this->getLocaleCodes() ) ) {
			return $locale;
		}

		return self::LOCALE_DEFAULT;
	}

	public function validate_partner_id( $partnerId ) {
		// Partner ID has definitely no spaces
		return str_replace( ' ', '', $partnerId );
	}

	public function expose_settings() {
		$gyg_data = [
			'currency' => esc_html( get_option( self::OPTION_NAME_CURRENCY, self::CURRENCY_DEFAULT ) ),
			'locale_code' => esc_html( get_option( self::OPTION_NAME_LOCALE, self::LOCALE_DEFAULT ) ),
			'partnerID' => esc_html( get_option( self::OPTION_NAME_PARTNER_ID, '' ) )
		];
		echo "<script>gygData = " . json_encode( $gyg_data ) . "</script>";
	}

	protected function getCurrencies() {
		return [
			'EUR' => __( 'Euro', 'getyourguide-widget' ),
			'GBP' => __( 'British Pound', 'getyourguide-widget' ),
			'USD' => __( 'U.S. Dollar', 'getyourguide-widget' ),
			'AUD' => __( 'Australian dollar', 'getyourguide-widget' ),
			'CAD' => __( 'Canadian dollar', 'getyourguide-widget' ),
			'DKK' => __( 'Danish krone', 'getyourguide-widget' ),
			'NZD' => __( 'New Zealand dollar', 'getyourguide-widget' ),
			'NOK' => __( 'Norwegian krone', 'getyourguide-widget' ),
			'CHF' => __( 'Swiss franc', 'getyourguide-widget' ),
			'AED' => __( 'United Arab Emirates dirham', 'getyourguide-widget' ),
			'PLN' => __( 'Polish zloty', 'getyourguide-widget' ),
			'SEK' => __( 'Swedish krona', 'getyourguide-widget' ),
			'SGD' => __( 'Singapur-Dollar', 'getyourguide-widget' ),
		];
	}

	protected function getLocaleCodes() {
		return [
			'da-DK' => __( 'Danish', 'getyourguide-widget' ),
			'de-DE' => __( 'German - Germany', 'getyourguide-widget' ),
			'de-AT' => __( 'German - Austria', 'getyourguide-widget' ),
			'de-CH' => __( 'German - Switzerland', 'getyourguide-widget' ),
			'en-US' => __( 'English - United States', 'getyourguide-widget' ),
			'en-GB' => __( 'English - Great Britain', 'getyourguide-widget' ),
			'es-ES' => __( 'Spanish', 'getyourguide-widget' ),
			'fr-FR' => __( 'French', 'getyourguide-widget' ),
			'it-IT' => __( 'Italian', 'getyourguide-widget' ),
			'nl-NL' => __( 'Dutch', 'getyourguide-widget' ),
			'no-NO' => __( 'Norwegian', 'getyourguide-widget' ),
			'pl-PL' => __( 'Polish', 'getyourguide-widget' ),
			'pt-PT' => __( 'Portuguese - Portugal', 'getyourguide-widget' ),
			'pt-BR' => __( 'Portuguese - Brazil', 'getyourguide-widget' ),
			'fi-FI' => __( 'Finnish', 'getyourguide-widget' ),
			'sv-SE' => __( 'Swedish', 'getyourguide-widget' ),
			'tr-TR' => __( 'Turkish', 'getyourguide-widget' ),
			'zh-CN' => __( 'Chinese', 'getyourguide-widget' ),
		];
	}
}
