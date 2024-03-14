<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';

class WC_Gateway_Rapyd_Common extends WC_Rapyd_Payment_Gateway {

	public function __construct() {
		$this->id                  = RAPYD_COMMON_ID;
		$this->title               = RAPYD_COMMON_TITLE;
		$this->method_title        = RAPYD_COMMON_METHOD_TITLE;
		$this->description         = RAPYD_COMMON_DESCRIPTION;
		/* translators: link */
		$this->method_description  = RAPYD_COMMON_METHOD_DESCRIPTION;
		$this->has_fields          = true;
		$this->icon                = 'https://cdn.rapyd.net/plugins/icons/banktra_icon.png';
		$this->constructor_helper();
	}

	public function getCategory() {
		return RAPYD_CATEGORY_COMMON;
	}

	public function is_available() {
		return false;
	}

}
