<?php

namespace Ilabs\BM_Woocommerce\Domain\Service\Legacy;

class Importer {

	const LEGACY_ENV_PRODUCTION = 1;

	const LEGACY_ENV_SANDBOX = 2;

	const LEGACY_ENV_UNKNOWN = 3;

	private $legacy_settings = [];
	private $autopay_settings = [];

	/**
	 * @var integer
	 */
	private $legacy_env;

	public function __construct() {
		$this->legacy_settings  = get_option( 'woocommerce_bluemedia_payment_gateway_settings' );
		$this->autopay_settings = get_option( 'woocommerce_bluemedia_settings' );
		$this->legacy_env       = $this->resolve_legacy_env();
	}

	public function handle_import() {
		if ( isset( $_POST['autopay_import_legacy_settings'] ) && '1' === $_POST['autopay_import_legacy_settings'] ) {


			$legacy_hash       = $this->get_legacy_hash_key();
			$legacy_service_id = $this->get_legacy_service_id();


			if ( empty( $legacy_hash ) && empty( $legacy_service_id ) && self::LEGACY_ENV_UNKNOWN === $this->legacy_env ) {
				blue_media()
					->alerts()
					->add_notice( __( 'Autopay: No values found for import',
						'bm-woocommerce' ) );

				return;
			}


			if ( self::LEGACY_ENV_PRODUCTION === $this->legacy_env ) {
				$this->modify_autopay_option( 'testmode', 'no' );
				$this->modify_autopay_option( 'private_key', $legacy_hash );
				$this->modify_autopay_option( 'service_id',
					$legacy_service_id );
			} else {
				$this->modify_autopay_option( 'testmode', 'yes' );
				$this->modify_autopay_option( 'test_private_key',
					$legacy_hash );
				$this->modify_autopay_option( 'test_service_id',
					$legacy_service_id );
			}

			$this->save_autopay_options();
			blue_media()
				->alerts()
				->add_notice( __( 'Autopay: Import completed',
					'bm-woocommerce' ) );
		}
	}

	public function get_legacy_service_id(): ?string {
		$currency = blue_media()->resolve_blue_media_currency_symbol();

		return $this->get_legacy_setting_value( "service_id_$currency" );
	}

	public function get_legacy_hash_key(): ?string {
		$currency = blue_media()->resolve_blue_media_currency_symbol();

		return $this->get_legacy_setting_value( "hash_key_$currency" );
	}

	/**
	 * @return int
	 */
	public function get_legacy_env() {
		return $this->legacy_env;
	}

	private function get_legacy_setting_value( $key ) {
		return $this->legacy_settings[ $key ] ?? null;
	}

	private function resolve_legacy_env(): int {
		$legacy_domain = $this->get_legacy_setting_value( 'payment_domain' );

		if ( 'pay.bm.pl' === $legacy_domain ) {
			return self::LEGACY_ENV_PRODUCTION;
		} elseif ( 'pay-accept.bm.pl' === $legacy_domain ) {
			return self::LEGACY_ENV_SANDBOX;
		}

		return self::LEGACY_ENV_UNKNOWN;
	}

	private function modify_autopay_option( string $key, $value ) {
		$this->autopay_settings[ $key ] = $value;
	}

	private function save_autopay_options() {
		update_option( 'woocommerce_bluemedia_settings',
			$this->autopay_settings );
	}
}

/*

array(24) {
	["whitelabel"]=>
  string(3) "yes"
	["testmode_header"]=>
  string(0) ""
	["testmode"]=>
  string(3) "yes"
	["testmode_info"]=>
  string(0) ""
	["test_service_id"]=>
  string(6) ""
	["test_private_key"]=>
  string(40) ""
	["service_id"]=>
  string(0) ""
	["private_key"]=>
  string(0) ""
	["ga4_tracking_id"]=>
  string(12) "G-LTC07K0XN2"
	["ga4_api_secret"]=>
  string(18) "L24tL^tp#Su$qicXxq"
	["ga4_client_id"]=>
  string(10) "3995440075"
	["wc_payment_statuses"]=>
  string(0) ""
	["wc_payment_status_on_bm_pending"]=>
  string(10) "wc-pending"
	["wc_payment_status_on_bm_success"]=>
  string(12) "wc-completed"
	["wc_payment_status_on_bm_failure"]=>
  string(9) "wc-failed"
	["enabled"]=>
  string(3) "yes"
	["test_publishable_key"]=>
  string(0) ""
	["blik_type"]=>
  string(13) "with_redirect"
	["debug_mode"]=>
  string(3) "yes"
	["wc_payment_status_on_bm_success_virtual"]=>
  string(12) "wc-completed"
	["sandbox_for_admins"]=>
  string(2) "no"
	["autopay_only_for_admins"]=>
  string(2) "no"
	["countdown_before_redirection"]=>
  string(3) "yes"
	["compatibility_with_live_update_checkout"]=>
  string(2) "no"
}*/
