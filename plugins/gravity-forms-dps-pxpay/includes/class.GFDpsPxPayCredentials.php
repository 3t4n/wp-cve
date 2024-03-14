<?php
namespace webaware\gf_dpspxpay;

if (!defined('ABSPATH')) {
	exit;
}

/**
* gateway credentials logic
*/
class GFDpsPxPayCredentials {

	public $userID;
	public $userKey;
	public $endpoint;

	const PXPAY_APIV2_URL			= 'https://sec.windcave.com/pxaccess/pxpay.aspx';
	const PXPAY_APIV2_TEST_URL		= 'https://uat.windcave.com/pxaccess/pxpay.aspx';

	/**
	* set gateway credentials for selected feed
	* @param GFPaymentAddOn $addon
	* @param bool $useTest
	*/
	public function __construct($addon, $useTest) {
		if (empty($useTest)) {
			// get defaults from add-on settings
			$this->userID			= $addon->get_plugin_setting('userID');
			$this->userKey			= $addon->get_plugin_setting('userKey');
			$this->endpoint			= self::PXPAY_APIV2_URL;
		}
		else {
			$this->userID			= $addon->get_plugin_setting('testID');
			$this->userKey			= $addon->get_plugin_setting('testKey');
			$this->endpoint			= $addon->get_plugin_setting('testEnv') === 'UAT' ? self::PXPAY_APIV2_TEST_URL : self::PXPAY_APIV2_URL;
		}
	}

	/**
	* check for missing credentials
	* @return bool
	*/
	public function isIncomplete() {
		return empty($this->userID) || empty($this->userKey) || empty($this->endpoint);
	}

}
