<?php

namespace Bookit\Classes\Vendor;

use Bookit\Classes\Database\Services;
use Bookit\Helpers\AddonHelper;

class Payments {

	private $redirect_url;
	private $appointment;
	private $payment_method;
	private $service;
	private $className;
	private $token;

	public function __construct( $appointment = array() ) {
		$this->appointment    = $appointment;
		$this->payment_method = $appointment['payment_method'];
		$this->token          = $appointment['token'];
		$this->service        = Services::get( 'id', $this->appointment['service_id'] );
		$this->className      = sprintf(
			'%s\Classes\Payments\%s',
			$this->getPaymentPluginName(),
			ucwords( $this->payment_method )
		);

		$this->{$this->payment_method}();
	}

	/** Used while Bookit Pro is alive */
	private function getPaymentPluginName() {
		$isProInstalled = AddonHelper::checkIsInstalledPlugin( 'bookit-pro/bookit-pro.php' );
		$isProActive    = bookit_pro_active();

		$isPaymentsInstalled = AddonHelper::checkIsInstalledPlugin( 'bookit-payments/bookit-payments.php' );
		$isPaymentsActive    = defined( 'BOOKIT_PAYMENTS_VERSION' );

		if ( $isPaymentsInstalled && $isPaymentsActive ) {
			return 'BookitPayments';
		}

		if ( $isProInstalled && $isProActive ) {
			return 'BookitPro';
		}
	}

	/**
	 * Free
	 */
	public function free() {
		$this->redirect_url = '';
	}

	/**
	 * Pay Locally
	 */
	public function locally() {
		$this->redirect_url = '';
	}

	/**
	 * PayPal
	 */
	public function paypal() {
		$className = $this->className;
		$paypal    = new $className(
			$this->appointment['price'],
			$this->appointment['id'],
			$this->service->title,
			$this->service->id,
			$this->appointment['customer_email'],
			''
		);

		$this->redirect_url = $paypal->generate_payment_url();
	}

	/**
	 * Stripe
	 */
	public function stripe() {
		$className = $this->className;
		$stripe    = new $className(
			$this->token,
			$this->appointment['price'],
			$this->appointment['id']
		);
		$stripe->check_payment();

		$this->redirect_url = '';
	}

	/**
	 * WooCommerce
	 */
	public function woocommerce() {
		$className = $this->className;
		$paypal    = new $className(
			$this->appointment['price'],
			$this->appointment['id'],
			$this->service->title
		);

		$this->redirect_url = $paypal->generate_payment_url();
	}

	/**
	 * @return string
	 */
	public function redirect_url() {
		return $this->redirect_url;
	}

	/**
	 * Currency list used for payments
	 *
	 * @return array
	 */
	public static function get_currency_list() {
		return array(
			array(
				'alias'           => esc_html__( 'United Arab Emirates dirham', 'bookit' ),
				'value'           => 'AED',
				'is_zero_decimal' => false,
				'symbol'          => '&#x62f;.&#x625;',
			),
			array(
				'alias'           => esc_html__( 'Afghan afghani', 'bookit' ),
				'value'           => 'AFN',
				'is_zero_decimal' => false,
				'symbol'          => '&#x60b;',
			),
			array(
				'alias'           => esc_html__( 'Albanian lek', 'bookit' ),
				'value'           => 'ALL',
				'is_zero_decimal' => false,
				'symbol'          => 'L',
			),
			array(
				'alias'           => esc_html__( 'Armenian dram', 'bookit' ),
				'value'           => 'AMD',
				'is_zero_decimal' => false,
				'symbol'          => 'AMD',
			),
			array(
				'alias'           => esc_html__( 'Netherlands Antillean guilder', 'bookit' ),
				'value'           => 'ANG',
				'is_zero_decimal' => false,
				'symbol'          => '&fnof;',
			),
			array(
				'alias'           => esc_html__( 'Angolan kwanza', 'bookit' ),
				'value'           => 'AOA',
				'is_zero_decimal' => false,
				'symbol'          => 'Kz',
			),
			array(
				'alias'           => esc_html__( 'Argentine peso', 'bookit' ),
				'value'           => 'ARS',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Australian dollar', 'bookit' ),
				'value'           => esc_html__( 'AUD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Aruban florin', 'bookit' ),
				'value'           => 'AWG',
				'is_zero_decimal' => false,
				'symbol'          => 'Afl.',
			),
			array(
				'alias'           => esc_html__( 'Azerbaijani manat', 'bookit' ),
				'value'           => 'AZN',
				'is_zero_decimal' => false,
				'symbol'          => 'AZN',
			),
			array(
				'alias'           => esc_html__( 'Bosnia and Herzegovina convertible mark', 'bookit' ),
				'value'           => 'BAM',
				'is_zero_decimal' => false,
				'symbol'          => 'KM',
			),
			array(
				'alias'           => esc_html__( 'Barbadian dollar', 'bookit' ),
				'value'           => 'BBD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Bangladeshi taka', 'bookit' ),
				'value'           => 'BDT',
				'is_zero_decimal' => false,
				'symbol'          => '&#2547;&nbsp;',
			),
			array(
				'alias'           => esc_html__( 'Bulgarian lev', 'bookit' ),
				'value'           => 'BGN',
				'is_zero_decimal' => false,
				'symbol'          => '&#1083;&#1074;.',
			),
			array(
				'alias'           => esc_html__( 'Bahraini dinar', 'bookit' ),
				'value'           => 'BHD',
				'is_zero_decimal' => false,
				'symbol'          => '.&#x62f;.&#x628;',
			),
			array(
				'alias'           => esc_html__( 'Burundian franc', 'bookit' ),
				'value'           => 'BIF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Bermudian dollar', 'bookit' ),
				'value'           => 'BMD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Brunei dollar', 'bookit' ),
				'value'           => 'BND',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Bolivian boliviano', 'bookit' ),
				'value'           => 'BOB',
				'is_zero_decimal' => false,
				'symbol'          => 'Bs.',
			),
			array(
				'alias'           => esc_html__( 'Brazilian real', 'bookit' ),
				'value'           => esc_html__( 'BRL', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#82;&#36;',
			),
			array(
				'alias'           => esc_html__( 'Bahamian dollar', 'bookit' ),
				'value'           => 'BSD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Botswana pula', 'bookit' ),
				'value'           => 'BWP',
				'is_zero_decimal' => false,
				'symbol'          => 'P',
			),
			array(
				'alias'           => esc_html__( 'Belarusian ruble', 'bookit' ),
				'value'           => 'BYN',
				'is_zero_decimal' => false,
				'symbol'          => 'Br',
			),
			array(
				'alias'           => esc_html__( 'Belize dollar', 'bookit' ),
				'value'           => 'BZD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Canadian dollar', 'bookit' ),
				'value'           => esc_html__( 'CAD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Congolese franc', 'bookit' ),
				'value'           => 'CDF',
				'is_zero_decimal' => false,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Swiss franc', 'bookit' ),
				'value'           => esc_html__( 'CHF', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#67;&#72;&#70;',
			),
			array(
				'alias'           => esc_html__( 'Chilean peso', 'bookit' ),
				'value'           => 'CLP',
				'is_zero_decimal' => true,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Chinese yuan', 'bookit' ),
				'value'           => 'CNY',
				'is_zero_decimal' => false,
				'symbol'          => '&yen;',
			),
			array(
				'alias'           => esc_html__( 'Colombian peso', 'bookit' ),
				'value'           => 'COP',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Costa Rican col&oacute;n', 'bookit' ),
				'value'           => 'CRC',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20a1;',
			),
			array(
				'alias'           => esc_html__( 'Cuban convertible peso', 'bookit' ),
				'value'           => 'CUC',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Cuban peso', 'bookit' ),
				'value'           => 'CUP',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Cape Verdean escudo', 'bookit' ),
				'value'           => 'CVE',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Czech koruna', 'bookit' ),
				'value'           => esc_html__( 'CZK', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#75;&#269;',
			),
			array(
				'alias'           => esc_html__( 'Djiboutian franc', 'bookit' ),
				'value'           => 'DJF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Danish krone', 'bookit' ),
				'value'           => esc_html__( 'DKK', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => 'DKK',
			),
			array(
				'alias'           => esc_html__( 'Dominican peso', 'bookit' ),
				'value'           => 'DOP',
				'is_zero_decimal' => false,
				'symbol'          => 'RD&#36;',
			),
			array(
				'alias'           => esc_html__( 'Algerian dinar', 'bookit' ),
				'value'           => 'DZD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x62f;.&#x62c;',
			),
			array(
				'alias'           => esc_html__( 'Egyptian pound', 'bookit' ),
				'value'           => 'EGP',
				'is_zero_decimal' => false,
				'symbol'          => 'EGP',
			),
			array(
				'alias'           => esc_html__( 'Eritrean nakfa', 'bookit' ),
				'value'           => 'ERN',
				'is_zero_decimal' => false,
				'symbol'          => 'Nfk',
			),
			array(
				'alias'           => esc_html__( 'Ethiopian birr', 'bookit' ),
				'value'           => 'ETB',
				'is_zero_decimal' => false,
				'symbol'          => 'Br',
			),
			array(
				'alias'           => esc_html__( 'Euro', 'bookit' ),
				'value'           => 'EUR',
				'is_zero_decimal' => false,
				'symbol'          => '&euro;',
			),
			array(
				'alias'           => esc_html__( 'Fijian dollar', 'bookit' ),
				'value'           => 'FJD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Falkland Islands pound', 'bookit' ),
				'value'           => 'FKP',
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Pound sterling', 'bookit' ),
				'value'           => esc_html__( 'GBP', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Georgian lari', 'bookit' ),
				'value'           => 'GEL',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20be;',
			),
			array(
				'alias'           => esc_html__( 'Guernsey pound', 'bookit' ),
				'value'           => 'GGP',
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Ghana cedi', 'bookit' ),
				'value'           => 'GHS',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20b5;',
			),
			array(
				'alias'           => esc_html__( 'Gibraltar pound', 'bookit' ),
				'value'           => 'GIP',
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Gambian dalasi', 'bookit' ),
				'value'           => 'GMD',
				'is_zero_decimal' => false,
				'symbol'          => 'D',
			),
			array(
				'alias'           => esc_html__( 'Guinean franc', 'bookit' ),
				'value'           => 'GNF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Guatemalan quetzal', 'bookit' ),
				'value'           => 'GTQ',
				'is_zero_decimal' => false,
				'symbol'          => 'Q',
			),
			array(
				'alias'           => esc_html__( 'Guyanese dollar', 'bookit' ),
				'value'           => 'GYD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Hong Kong dollar', 'bookit' ),
				'value'           => esc_html__( 'HKD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Honduran lempira', 'bookit' ),
				'value'           => 'HNL',
				'is_zero_decimal' => false,
				'symbol'          => 'L',
			),
			array(
				'alias'           => esc_html__( 'Croatian kuna', 'bookit' ),
				'value'           => 'HRK',
				'is_zero_decimal' => false,
				'symbol'          => 'kn',
			),
			array(
				'alias'           => esc_html__( 'Haitian gourde', 'bookit' ),
				'value'           => 'HTG',
				'is_zero_decimal' => false,
				'symbol'          => 'G',
			),
			array(
				'alias'           => esc_html__( 'Hungarian forint 1', 'bookit' ),
				'value'           => esc_html__( 'HUF', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#70;&#116;',
			),
			array(
				'alias'           => esc_html__( 'Indonesian rupiah', 'bookit' ),
				'value'           => 'IDR',
				'is_zero_decimal' => false,
				'symbol'          => 'Rp',
			),
			array(
				'alias'           => esc_html__( 'Israeli new shekel', 'bookit' ),
				'value'           => esc_html__( 'ILS', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#8362;',
			),
			array(
				'alias'           => esc_html__( 'Indian rupee', 'bookit' ),
				'value'           => esc_html__( 'INR', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Iraqi dinar', 'bookit' ),
				'value'           => 'IQD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x639;.&#x62f;',
			),
			array(
				'alias'           => esc_html__( 'Iranian rial', 'bookit' ),
				'value'           => 'IRR',
				'is_zero_decimal' => false,
				'symbol'          => '&#xfdfc;',
			),
			array(
				'alias'           => esc_html__( 'Icelandic kr&oacute;na', 'bookit' ),
				'value'           => 'ISK',
				'is_zero_decimal' => false,
				'symbol'          => 'kr.',
			),
			array(
				'alias'           => esc_html__( 'Jamaican dollar', 'bookit' ),
				'value'           => 'JMD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Jordanian dinar', 'bookit' ),
				'value'           => 'JOD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x62f;.&#x627;',
			),
			array(
				'alias'           => esc_html__( 'Japanese yen 1', 'bookit' ),
				'value'           => esc_html__( 'JPY', 'bookit' ),
				'is_zero_decimal' => true,
				'symbol'          => '&yen;',
			),
			array(
				'alias'           => esc_html__( 'Kenyan shilling', 'bookit' ),
				'value'           => 'KES',
				'is_zero_decimal' => false,
				'symbol'          => 'KSh',
			),
			array(
				'alias'           => esc_html__( 'Kyrgyzstani som', 'bookit' ),
				'value'           => 'KGS',
				'is_zero_decimal' => false,
				'symbol'          => '&#x441;&#x43e;&#x43c;',
			),
			array(
				'alias'           => esc_html__( 'Cambodian riel', 'bookit' ),
				'value'           => 'KHR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x17db;',
			),
			array(
				'alias'           => esc_html__( 'Comorian franc', 'bookit' ),
				'value'           => 'KMF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'North Korean won', 'bookit' ),
				'value'           => 'KPW',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20a9;',
			),
			array(
				'alias'           => esc_html__( 'South Korean won', 'bookit' ),
				'value'           => 'KRW',
				'is_zero_decimal' => true,
				'symbol'          => '&#8361;',
			),
			array(
				'alias'           => esc_html__( 'Kuwaiti dinar', 'bookit' ),
				'value'           => 'KWD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x62f;.&#x643;',
			),
			array(
				'alias'           => esc_html__( 'Cayman Islands dollar', 'bookit' ),
				'value'           => 'KYD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Kazakhstani tenge', 'bookit' ),
				'value'           => 'KZT',
				'is_zero_decimal' => false,
				'symbol'          => '&#8376;',
			),
			array(
				'alias'           => esc_html__( 'Lao kip', 'bookit' ),
				'value'           => 'LAK',
				'is_zero_decimal' => false,
				'symbol'          => '&#8365;',
			),
			array(
				'alias'           => esc_html__( 'Lebanese pound', 'bookit' ),
				'value'           => 'LBP',
				'is_zero_decimal' => false,
				'symbol'          => '&#x644;.&#x644;',
			),
			array(
				'alias'           => esc_html__( 'Sri Lankan rupee', 'bookit' ),
				'value'           => 'LKR',
				'is_zero_decimal' => false,
				'symbol'          => '&#xdbb;&#xdd4;',
			),
			array(
				'alias'           => esc_html__( 'Liberian dollar', 'bookit' ),
				'value'           => 'LRD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Lesotho loti', 'bookit' ),
				'value'           => 'LSL',
				'is_zero_decimal' => false,
				'symbol'          => 'L',
			),
			array(
				'alias'           => esc_html__( 'Libyan dinar', 'bookit' ),
				'value'           => 'LYD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x644;.&#x62f;',
			),
			array(
				'alias'           => esc_html__( 'Moroccan dirham', 'bookit' ),
				'value'           => 'MAD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x62f;.&#x645;.',
			),
			array(
				'alias'           => esc_html__( 'Moldovan leu', 'bookit' ),
				'value'           => 'MDL',
				'is_zero_decimal' => false,
				'symbol'          => 'MDL',
			),
			array(
				'alias'           => esc_html__( 'Malagasy ariary', 'bookit' ),
				'value'           => 'MGA',
				'is_zero_decimal' => true,
				'symbol'          => 'Ar',
			),
			array(
				'alias'           => esc_html__( 'Macedonian denar', 'bookit' ),
				'value'           => 'MKD',
				'is_zero_decimal' => false,
				'symbol'          => '&#x434;&#x435;&#x43d;',
			),
			array(
				'alias'           => esc_html__( 'Burmese kyat', 'bookit' ),
				'value'           => 'MMK',
				'is_zero_decimal' => false,
				'symbol'          => 'Ks',
			),
			array(
				'alias'           => esc_html__( 'Mongolian t&ouml;gr&ouml;g', 'bookit' ),
				'value'           => 'MNT',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20ae;',
			),
			array(
				'alias'           => esc_html__( 'Macanese pataca', 'bookit' ),
				'value'           => 'MOP',
				'is_zero_decimal' => false,
				'symbol'          => 'P',
			),
			array(
				'alias'           => esc_html__( 'Mauritian rupee', 'bookit' ),
				'value'           => 'MUR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20a8;',
			),
			array(
				'alias'           => esc_html__( 'Maldivian rufiyaa', 'bookit' ),
				'value'           => 'MVR',
				'is_zero_decimal' => false,
				'symbol'          => '.&#x783;',
			),
			array(
				'alias'           => esc_html__( 'Malawian kwacha', 'bookit' ),
				'value'           => 'MWK',
				'is_zero_decimal' => false,
				'symbol'          => 'MK',
			),
			array(
				'alias'           => esc_html__( 'Mexican peso', 'bookit' ),
				'value'           => esc_html__( 'MXN', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Malaysian ringgit 2', 'bookit' ),
				'value'           => esc_html__( 'MYR', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#82;&#77;',
			),
			array(
				'alias'           => esc_html__( 'Mozambican metical', 'bookit' ),
				'value'           => 'MZN',
				'is_zero_decimal' => false,
				'symbol'          => 'MT',
			),
			array(
				'alias'           => esc_html__( 'Namibian dollar', 'bookit' ),
				'value'           => 'NAD',
				'is_zero_decimal' => false,
				'symbol'          => 'N&#36;',
			),
			array(
				'alias'           => esc_html__( 'Nigerian naira', 'bookit' ),
				'value'           => 'NGN',
				'is_zero_decimal' => false,
				'symbol'          => '&#8358;',
			),
			array(
				'alias'           => esc_html__( 'Nicaraguan c&oacute;rdoba', 'bookit' ),
				'value'           => 'NIO',
				'is_zero_decimal' => false,
				'symbol'          => 'C&#36;',
			),
			array(
				'alias'           => esc_html__( 'Norwegian krone', 'bookit' ),
				'value'           => esc_html__( 'NOK', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#107;&#114;',
			),
			array(
				'alias'           => esc_html__( 'Nepalese rupee', 'bookit' ),
				'value'           => 'NPR',
				'is_zero_decimal' => false,
				'symbol'          => '&#8360;',
			),
			array(
				'alias'           => esc_html__( 'New Zealand dollar', 'bookit' ),
				'value'           => esc_html__( 'NZD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Omani rial', 'bookit' ),
				'value'           => 'OMR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x631;.&#x639;.',
			),
			array(
				'alias'           => esc_html__( 'Panamanian balboa', 'bookit' ),
				'value'           => 'PAB',
				'is_zero_decimal' => false,
				'symbol'          => 'B/.',
			),
			array(
				'alias'           => esc_html__( 'Sol', 'bookit' ),
				'value'           => 'PEN',
				'is_zero_decimal' => false,
				'symbol'          => 'S/',
			),
			array(
				'alias'           => esc_html__( 'Papua New Guinean kina', 'bookit' ),
				'value'           => 'PGK',
				'is_zero_decimal' => false,
				'symbol'          => 'K',
			),
			array(
				'alias'           => esc_html__( 'Philippine peso', 'bookit' ),
				'value'           => esc_html__( 'PHP', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#8369;',
			),
			array(
				'alias'           => esc_html__( 'Pakistani rupee', 'bookit' ),
				'value'           => 'PKR',
				'is_zero_decimal' => false,
				'symbol'          => '&#8360;',
			),
			array(
				'alias'           => esc_html__( 'Polish złoty', 'bookit' ),
				'value'           => esc_html__( 'PLN', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#122;&#322;',
			),
			array(
				'alias'           => esc_html__( 'Paraguayan guaran&iacute;', 'bookit' ),
				'value'           => 'PYG',
				'is_zero_decimal' => true,
				'symbol'          => '&#8370;',
			),
			array(
				'alias'           => esc_html__( 'Qatari riyal', 'bookit' ),
				'value'           => 'QAR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x631;.&#x642;',
			),
			array(
				'alias'           => esc_html__( 'Romanian leu', 'bookit' ),
				'value'           => 'RON',
				'is_zero_decimal' => false,
				'symbol'          => 'lei',
			),
			array(
				'alias'           => esc_html__( 'Serbian dinar', 'bookit' ),
				'value'           => 'RSD',
				'is_zero_decimal' => false,
				'symbol'          => '&#1088;&#1089;&#1076;',
			),
			array(
				'alias'           => esc_html__( 'Russian ruble', 'bookit' ),
				'value'           => esc_html__( 'RUB', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#8381;',
			),
			array(
				'alias'           => esc_html__( 'Rwandan franc', 'bookit' ),
				'value'           => 'RWF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Saudi riyal', 'bookit' ),
				'value'           => 'SAR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x631;.&#x633;',
			),
			array(
				'alias'           => esc_html__( 'Solomon Islands dollar', 'bookit' ),
				'value'           => 'SBD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Seychellois rupee', 'bookit' ),
				'value'           => 'SCR',
				'is_zero_decimal' => false,
				'symbol'          => '&#x20a8;',
			),
			array(
				'alias'           => esc_html__( 'Swedish krona', 'bookit' ),
				'value'           => esc_html__( 'SEK', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#107;&#114;',
			),
			array(
				'alias'           => esc_html__( 'Singapore dollar', 'bookit' ),
				'value'           => esc_html__( 'SGD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Saint Helena pound', 'bookit' ),
				'value'           => 'SHP',
				'is_zero_decimal' => false,
				'symbol'          => '&pound;',
			),
			array(
				'alias'           => esc_html__( 'Sierra Leonean leone', 'bookit' ),
				'value'           => 'SLL',
				'is_zero_decimal' => false,
				'symbol'          => 'Le',
			),
			array(
				'alias'           => esc_html__( 'Somali shilling', 'bookit' ),
				'value'           => 'SOS',
				'is_zero_decimal' => false,
				'symbol'          => 'Sh',
			),
			array(
				'alias'           => esc_html__( 'Surinamese dollar', 'bookit' ),
				'value'           => 'SRD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Syrian pound', 'bookit' ),
				'value'           => 'SYP',
				'is_zero_decimal' => false,
				'symbol'          => '&#x644;.&#x633;',
			),
			array(
				'alias'           => esc_html__( 'Swazi lilangeni', 'bookit' ),
				'value'           => 'SZL',
				'is_zero_decimal' => false,
				'symbol'          => 'L',
			),
			array(
				'alias'           => esc_html__( 'Thai baht', 'bookit' ),
				'value'           => esc_html__( 'THB', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#3647;',
			),
			array(
				'alias'           => esc_html__( 'Tajikistani somoni', 'bookit' ),
				'value'           => 'TJS',
				'is_zero_decimal' => false,
				'symbol'          => '&#x405;&#x41c;',
			),
			array(
				'alias'           => esc_html__( 'Turkmenistan manat', 'bookit' ),
				'value'           => 'TMT',
				'is_zero_decimal' => false,
				'symbol'          => 'm',
			),
			array(
				'alias'           => esc_html__( 'Tongan pa&#x2bb;anga', 'bookit' ),
				'value'           => 'TOP',
				'is_zero_decimal' => false,
				'symbol'          => 'T&#36;',
			),
			array(
				'alias'           => esc_html__( 'Turkish lira', 'bookit' ),
				'value'           => 'TRY',
				'is_zero_decimal' => false,
				'symbol'          => '&#8378;',
			),
			array(
				'alias'           => esc_html__( 'Trinidad and Tobago dollar', 'bookit' ),
				'value'           => 'TTD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'New Taiwan dollar 1', 'bookit' ),
				'value'           => esc_html__( 'TWD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#78;&#84;&#36;',
			),
			array(
				'alias'           => esc_html__( 'Tanzanian shilling', 'bookit' ),
				'value'           => 'TZS',
				'is_zero_decimal' => false,
				'symbol'          => 'Sh',
			),
			array(
				'alias'           => esc_html__( 'Ukrainian hryvnia', 'bookit' ),
				'value'           => 'UAH',
				'is_zero_decimal' => false,
				'symbol'          => '&#8372;',
			),
			array(
				'alias'           => esc_html__( 'Ugandan shilling', 'bookit' ),
				'value'           => 'UGX',
				'is_zero_decimal' => true,
				'symbol'          => 'UGX',
			),
			array(
				'alias'           => esc_html__( 'United States dollar', 'bookit' ),
				'value'           => esc_html__( 'USD', 'bookit' ),
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Uruguayan peso', 'bookit' ),
				'value'           => 'UYU',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'Uzbekistani som', 'bookit' ),
				'value'           => 'UZS',
				'is_zero_decimal' => false,
				'symbol'          => 'UZS',
			),
			array(
				'alias'           => esc_html__( 'Vietnamese &#x111;&#x1ed3;ng', 'bookit' ),
				'value'           => 'VND',
				'is_zero_decimal' => true,
				'symbol'          => '&#8363;',
			),
			array(
				'alias'           => esc_html__( 'Vanuatu vatu', 'bookit' ),
				'value'           => 'VUV',
				'is_zero_decimal' => true,
				'symbol'          => 'Vt',
			),
			array(
				'alias'           => esc_html__( 'Samoan t&#x101;l&#x101;', 'bookit' ),
				'value'           => 'WST',
				'is_zero_decimal' => false,
				'symbol'          => 'T',
			),
			array(
				'alias'           => esc_html__( 'Central African CFA franc', 'bookit' ),
				'value'           => 'XAF',
				'is_zero_decimal' => true,
				'symbol'          => 'CFA',
			),
			array(
				'alias'           => esc_html__( 'East Caribbean dollar', 'bookit' ),
				'value'           => 'XCD',
				'is_zero_decimal' => false,
				'symbol'          => '&#36;',
			),
			array(
				'alias'           => esc_html__( 'West African CFA franc', 'bookit' ),
				'value'           => 'XOF',
				'is_zero_decimal' => true,
				'symbol'          => 'CFA',
			),
			array(
				'alias'           => esc_html__( 'CFP franc', 'bookit' ),
				'value'           => 'XPF',
				'is_zero_decimal' => true,
				'symbol'          => 'Fr',
			),
			array(
				'alias'           => esc_html__( 'Yemeni rial', 'bookit' ),
				'value'           => 'YER',
				'is_zero_decimal' => false,
				'symbol'          => '&#xfdfc;',
			),
			array(
				'alias'           => esc_html__( 'South African rand', 'bookit' ),
				'value'           => 'ZAR',
				'is_zero_decimal' => false,
				'symbol'          => '&#82;',
			),
			array(
				'alias'           => esc_html__( 'Zambian kwacha', 'bookit' ),
				'value'           => 'ZMW',
				'is_zero_decimal' => false,
				'symbol'          => 'ZK',
			),
		);
	}
}
