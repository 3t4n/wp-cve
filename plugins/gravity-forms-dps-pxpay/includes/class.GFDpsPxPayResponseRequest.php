<?php
namespace webaware\gf_dpspxpay;

if (!defined('ABSPATH')) {
	exit;
}

/**
* Windcave payment request response
*/
class GFDpsPxPayResponseRequest extends GFDpsPxPayResponse {

	/**
	* URL to redirect browser to where credit card details can be entered
	* @var string
	*/
	public $URI;

	/**
	* response code when error is returned
	* @var string 2 characters
	*/
	public $Reco;

	/**
	* textual response status, e.g. error message
	* @var string max. 32 characters
	*/
	public $ResponseText;

	/**
	* load Windcave response data as XML string
	* @param string $response Windcave response as a string (hopefully of XML data)
	* @throws GFDpsPxPayException
	*/
	public function loadResponse($response) {
		parent::loadResponse($response);

		if (empty($this->URI) && (!empty($this->Reco) || !empty($this->ResponseText))) {
			$errors = [];
			if (!empty($this->ResponseText)) {
				$errors[] = $this->ResponseText;
			}
			if (!empty($this->Reco)) {
				$errors[] = self::getCodeDescription($this->Reco);
			}
			throw new GFDpsPxPayException(implode("\n", $errors));
		}
	}

	/**
	* get 'invalid response' message for this response class
	* @return string
	*/
	protected function getMessageInvalid() {
		return __('Invalid response from Windcave for payment request', 'gravity-forms-dps-pxpay');
	}

	/**
	* get description for response code
	* @param string $code
	* @return string
	*/
	protected static function getCodeDescription($code) {
		switch ($code) {

			case 'IC':
				$msg = _x('Invalid Key or Username. Also check that if a TxnId is being supplied that it is unique.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'ID':
				$msg = _x('Invalid transaction type. Ensure that the transaction type is either Auth or Purchase.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IK':
				$msg = _x('Invalid UrlSuccess. Ensure that the URL being supplied does not contain a query string.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IL':
				$msg = _x('Invalid UrlFail. Ensure that the URL being supplied does not contain a query string.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IM':
				$msg = _x('Invalid PxPayUserId.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IN':
				$msg = _x('Blank PxPayUserId.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IP':
				$msg = _x('Invalid Access Info. Ensure the PxPayID and/or key are valid.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IQ':
				$msg = _x('Invalid TxnType. Ensure the transaction type being submitted is either "Auth" or "Purchase".', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IT':
				$msg = _x('Invalid currency. Ensure that the CurrencyInput is correct and in the correct format e.g. "USD".', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'IU':
				$msg = _x('Invalid AmountInput. Ensure that the amount is in the correct format e.g. "20.00".', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NF':
				$msg = _x('Invalid Username.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NK':
				$msg = _x('Request not found. Check the key and the mcrypt library if in use.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NL':
				$msg = _x('User not enabled. Contact Windcave.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NM':
				$msg = _x('User not enabled. Contact Windcave.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NN':
				$msg = _x('Invalid MAC.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NO':
				$msg = _x('Request contains non ASCII characters.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NP':
				$msg = _x('Closing Request tag not found.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NQ':
				$msg = _x('User not enabled for PxPay 2.0. Contact Windcave.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			case 'NT':
				$msg = _x('Key is not 64 characters.', 'DPS coded response', 'gravity-forms-dps-pxpay');
				break;

			default:
				$msg = $code;
				break;

		}

		return apply_filters('dps_pxpay_gf_code_description', $msg, $code);
	}

}
