<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Api_Apm_Secret_Service' ) ) {
	return;
}

use Payever\Sdk\Core\Authorization\ApmSecretService;

class WC_Payever_Api_Apm_Secret_Service extends ApmSecretService {

	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * @return string|null
	 */
	public function get() {
		if ( get_option( WC_Payever_Helper::PAYEVER_ENVIRONMENT ) ) {
			return get_option( WC_Payever_Helper::PAYEVER_APM_SECRET_SANDBOX ) ?: null;
		}

		return get_option( WC_Payever_Helper::PAYEVER_APM_SECRET_LIVE ) ?: null;
	}

	/**
	 * @param string $apmSecret
	 * @return self
	 */
	public function save( $apmSecret ) {
		$result = parent::save( $apmSecret );
		if ( empty( $apmSecret ) ) {
			return $result;
		}

		$pathKey = WC_Payever_Helper::PAYEVER_APM_SECRET_LIVE;
		if ( get_option( WC_Payever_Helper::PAYEVER_ENVIRONMENT ) ) {
			$pathKey = WC_Payever_Helper::PAYEVER_APM_SECRET_SANDBOX;
		}

		update_option( $pathKey, $apmSecret );

		return $result;
	}
}
