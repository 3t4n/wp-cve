<?php
namespace webaware\gf_dpspxpay;

if (!defined('ABSPATH')) {
	exit;
}

/**
* Class for dealing with a Windcave response
*/
abstract class GFDpsPxPayResponse {

	/**
	* whether it was a successful request
	* @var boolean
	*/
	public $isValid;

	/**
	* load Windcave response data as XML string
	* @param string $response Windcave response as a string (hopefully of XML data)
	* @throws GFDpsPxPayException
	*/
	public function loadResponse($response) {
		// prevent XML injection attacks, and handle errors without warnings
		$oldDisableEntityLoader = PHP_VERSION_ID >= 80000 ? true : libxml_disable_entity_loader(true);
		$oldUseInternalErrors = libxml_use_internal_errors(true);

		try {
			$xml = simplexml_load_string($response);
			if ($xml === false) {
				$errors = [];
				foreach (libxml_get_errors() as $error) {
					$errors[] = $error->message;
				}
				throw new GFDpsPxPayException(implode("\n", $errors));
			}

			// restore old libxml settings
			if (!$oldDisableEntityLoader) {
				libxml_disable_entity_loader($oldDisableEntityLoader);
			}
			libxml_use_internal_errors($oldUseInternalErrors);
		}
		catch (Exception $e) {
			// restore old libxml settings
			if (!$oldDisableEntityLoader) {
				libxml_disable_entity_loader($oldDisableEntityLoader);
			}
			libxml_use_internal_errors($oldUseInternalErrors);

			throw new GFDpsPxPayException(sprintf(__('Invalid response from Windcave: %s', 'gravity-forms-dps-pxpay'), $e->getMessage()));
		}

		if (is_null($response)) {
			throw new GFDpsPxPayException($this->getMessageInvalid());
		}

		$this->isValid = ('1' === ((string) $xml['valid']));

		foreach (get_object_vars($xml) as $name => $value) {
			if (property_exists($this, $name)) {
				switch ($name) {

					case 'AmountSettlement':
					case 'TotalAmount':
					case 'AmountSurcharge':
						$this->$name = (float) $value;
						break;

					case 'Success':
						$this->Success = (bool) $value;
						break;

					default:
						$this->$name = (string) $value;
						break;

				}
			}
		}
	}

	/**
	* get 'invalid response' message for specific response class
	* @return string
	*/
	abstract protected function getMessageInvalid();

}
