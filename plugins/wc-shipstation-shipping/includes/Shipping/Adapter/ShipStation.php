<?php

/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Shipping\Adapter;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\ShipStation')):

require_once(__DIR__ . '/AbstractAdapter.php');

class ShipStation extends AbstractAdapter
{
	protected $apiKey;
	protected $apiSecret;

	// we don't want these properties overwritten by settings
	protected $_carriers;
	protected $_services;

	const MAX_DESCRIPTION_LENGTH = 45;

	public function __construct($id, array $settings = array())
	{
		$this->apiKey = '';
		$this->apiSecret = '';

		parent::__construct($id, $settings);

		$this->currencies = array(
			'USD' => __('USD', $this->id)
		);

		$this->statuses = array(
			'ready' => __('Shipping Label Created', $this->id),
		);

		$this->contentTypes = array(
			'merchandise' => __('Merchandise', $this->id),
			'documents' => __('Documents', $this->id),
			'gift' => __('Gift', $this->id),
			'returned_goods' => __('Returned Goods', $this->id),
			'sample' => __('Sample', $this->id),
		);

		$this->_carriers = array();
		$this->_services = array();
		$this->packageTypes = array();
	}

	public function setSettings(array $settings)
	{
		parent::setSettings($settings);

		if (!empty($this->apiKey) && !empty($this->apiSecret)) {
			if ($this->initCarriers()) {
				$this->initServices();
				$this->initPackageTypes();	
			}
		}
	}

	public function getName()
	{
		return 'ShipStation';
	}

	public function hasCustomItemsFeature()
	{
		return true;
	}

	public function hasTariffFeature()
	{
		return true;
	}

	public function hasUseSellerAddressFeature()
	{
		return true;
	}

	public function hasOriginFeature()
	{
		return true;
	}

	public function hasInsuranceFeature()
	{
		return true;
	}

	public function hasSignatureFeature()
	{
		return true;
	}

	public function hasImportShipmentsFeature()
	{
		return true;
	}

	public function hasCreateOrderFeature()
	{
		return true;
	}

	public function validate(array $settings)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__);
		
		$errors = array();

		if (empty($settings['apiKey'])) {
			$errors[] = sprintf('<strong>%s</strong> %s', __('API Key', $this->id), __('is required for the integration to work', $this->id));
		}

		if (empty($settings['apiSecret'])) {
			$errors[] = sprintf('<strong>%s</strong> %s', __('API Secret', $this->id), __('is required for the integration to work', $this->id));
		}

		if (!empty($settings['apiKey']) && !empty($settings['apiSecret'])) {
			$this->setSettings($settings);

			update_option($this->getCarriersCacheKey(), false);

			if ($this->initCarriers()) {
				update_option($this->getServicesCacheKey(), false);
				$this->initServices();

				update_option($this->getPackageTypesCacheKey(), false);
				$this->initPackageTypes();	
			} else {
				$errors[] = __('We were not able to fetch carriers for provided API Key and API Secret', $this->id);
			}
		}

		return $errors;
	}

	public function updateFormFields($formFields)
	{
		$formFields = $this->addFormFieldsAt($formFields, $this->getIntegrationFormFields(), 'integration_title', 1);

		if (isset($formFields['origin_title'])) {
			$formFields['origin_title']['description'] = sprintf(
				'%s<br/><p><strong>%s</strong><br/>- %s<br/>- %s<br/>- %s</p>', 
				__('What is the address of the place from where parcels are going to be shipped?', $this->id),
				__('There are a few limitations:', $this->id),
				__('You can only take advantage of this address when Export Orders feature is off.', $this->id),
				__('Address should be within the same country that is set in your ShipStation account.', $this->id),
				__('Postal Code is required for shipping rates to work.', $this->id)
			);
		}

		if (isset($formFields['insurance'])) {
			$formFields['insurance']['description'] = __('Inclusion of insurance will not affect rates quoted by ShipStation.', $this->id);
		}

		if (isset($formFields['displayTrackingType'])) {
			unset($formFields['displayTrackingType']);
		}

		if (isset($formFields['sandbox'])) {
			unset($formFields['sandbox']);
		}

		if (isset($formFields['useSellerAddress'])) {
			$formFields['useSellerAddress']['description'] = __('Seller\'s Address must it in the same country as the address used in ShipStation account.', $this->id);
		}

		return $formFields;
	}

	public function getIntegrationFormFields()
	{
		$formFields = array(
			'apiTitle' => array(
				'type' => 'title',
				'description' => sprintf('<strong>%s</strong>', __('You must save settings for plugin to load your latest carriers, services and package types.', $this->id))
			),
			'apiKey' => array(
				'title' => __('API Key', $this->id),
				'type' => 'text',
				'description' => sprintf(__('You can find it at %sSettings -> Account -> API Settings%s.', $this->id), '<a href="https://ship.shipstation.com/settings/api" target="_blank">', '</a>'),
			),
			'apiSecret' => array(
				'title' => __('API Secret', $this->id),
				'type' => 'text',
				'description' => sprintf(__('You can find it at %sSettings -> Account -> API Settings%s.', $this->id), '<a href="https://ship.shipstation.com/settings/api" target="_blank">', '</a>'),
			)
		);

		return $formFields;
	}

	public function getRates(array $params)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__);
		
		$cacheKey = $this->getRatesCacheKey($params);
		$response = $this->getCacheValue($cacheKey);
		if (!empty($response)) {
			$this->logger->debug(__FILE__, __LINE__, 'Found previously returned rates with, so return them. Cache Key: ' . $cacheKey);

			return $response;
		}

		$params['function'] = __FUNCTION__;
		$response = array('shipment' => array(), 'response' => array(), 'params' => array());
		
		$errorMessage = '';

		foreach ($this->_carriers as $carrierCode => $carrierName) {
			$params['carrierCode'] = $carrierCode;
			$newResponse = $this->sendRequest('shipments/getrates', 'POST', $params);

			if (isset($newResponse['shipment'])) {
				$response['shipment'] = array_replace_recursive($response['shipment'], $newResponse['shipment']);
			} else if (!empty($newResponse['error']['message'])) {
				if (!empty($errorMessage)) {
					$errorMessage .= "\n";
				}

				$errorMessage .= $newResponse['error']['message'];
			}

			$response['response'][$carrierCode] = $newResponse['response'];
			$response['params'][$carrierCode] = $newResponse['params'];
		}

		if (!empty($response['shipment']['rates'])) {
			$response['shipment']['rates'] = $this->sortRates($response['shipment']['rates']);
		}

		$this->logger->debug(__FILE__, __LINE__, 'Response: '. print_r($response, true));
	
		if (!empty($errorMessage)) {
			$response['error']['message'] = $errorMessage;

			$this->logger->debug(__FILE__, __LINE__, 'Errors: ' . $errorMessage);
		}
		
		if (!empty($response['shipment'])) {
			$this->logger->debug(__FILE__, __LINE__, 'Cache shipment for the future');

			$this->setCacheValue($cacheKey, $response, $this->cacheExpirationInSecs);
		}

		return $response;
	}

	public function getCacheKey(array $params)
	{
		$params['apiKey'] = $this->apiKey;
		return parent::getCacheKey($params);
	}

	protected function getRatesCacheKey(array $params)
	{
		if (isset($params['service'])) {
			unset($params['service']);
		}

		return $this->getCacheKey($params) . '_rates';
	}

	protected function getRequestBody(&$headers, &$params)
	{
		$headers['Content-Type'] = 'application/json';

		return json_encode($params);
	}

	protected function getRatesParams(array $inParams)
	{
		$params = array();

		if (!empty($inParams['carrierCode'])) {
			$params['carrierCode'] = $inParams['carrierCode'];
		}

		if (!empty($inParams['service'])) {
			$params['serviceCode'] = $inParams['service'];
		}

		if (!empty($inParams['type'])) {
			$params['packageCode'] = $inParams['type'];
		}

		if (empty($inParams['origin']) && !empty($this->origin)) {
			$inParams['origin'] = $this->origin;
		}

		if (!empty($inParams['origin']['postcode'])) {
			$params['fromPostalCode'] = $inParams['origin']['postcode'];
		}

		if (!empty($inParams['destination']) && !empty($inParams['destination']['country'])) {
			$params['toCountry'] = $inParams['destination']['country'];
		}

		if (!empty($inParams['destination']) && !empty($inParams['destination']['state'])) {
			$params['toState'] = $inParams['destination']['state'];
		}

		if (!empty($inParams['destination']) && !empty($inParams['destination']['postcode'])) {
			$params['toPostalCode'] = $inParams['destination']['postcode'];
		}

		if (!empty($inParams['destination']) && !empty($inParams['destination']['city'])) {
			$params['toCity'] = $inParams['destination']['city'];
		}

		if (!empty($inParams['destination']) && !empty($inParams['destination']['company'])) {
			$params['residential'] = false;
		} else {
			$params['residential'] = true;
		}

		$params['weight'] = $this->getWeightArray($inParams);
		$params['dimensions'] = $this->getDimensionsArray($inParams);
		$params['confirmation'] = $this->getConfirmation($inParams);

		return $params;
	}

	protected function getConfirmation(array $inParams)
	{
		$confirmation = 'none';

		$signature = $this->signature;
		if (isset($inParams['signature'])) {
			$signature = filter_var($inParams['signature'], FILTER_VALIDATE_BOOLEAN);
		}

		if ($signature) {
			if (!empty($inParams['carrierCode']) && $inParams['carrierCode'] == 'fedex') {
				$confirmation = 'direct_signature';
			} else {
				$confirmation = 'signature';
			}
		}
		
		return $confirmation;
	}

	protected function getWeightArray(array $inParams)
	{
		$weightArray = array();

		if (!empty($inParams['weight'])) {
			$weightArray['value'] = round($inParams['weight'], 3);
		} else {
			$weightArray['value'] = 0;
		}

		$weightUnit = $this->weightUnit;
		if (isset($inParams['weight_unit'])) {
			$weightUnit = $inParams['weight_unit'];
		}

		if ($weightUnit == 'g') {
			$weightArray['units'] = 'grams';
		} else if ($weightUnit == 'kg') {
			$weightArray['units'] = 'grams';
			$weightArray['value'] *= 1000;
		} else if ($weightUnit == 'lbs') {
			$weightArray['units'] = 'pounds';
		} else if ($weightUnit == 'oz') {
			$weightArray['units'] = 'ounces';
		}

		return $weightArray;
	}

	protected function getDimensionsArray(array $inParams)
	{
		$dimensionsArray = array();

		if (isset($inParams['length'])) {
			$dimensionsArray['length'] = round($inParams['length'], 3);
		} else {
			$dimensionsArray['length'] = 0;
		}

		if (isset($inParams['width'])) {
			$dimensionsArray['width'] = round($inParams['width'], 3);
		} else {
			$dimensionsArray['width'] = 0;
		}

		if (isset($inParams['height'])) {
			$dimensionsArray['height'] = round($inParams['height'], 3);
		} else {
			$dimensionsArray['height'] = 0;
		}

		$dimensionUnit = $this->dimensionUnit;
		if (isset($inParams['dimension_unit'])) {
			$dimensionUnit = $inParams['dimension_unit'];
		}

		if ($dimensionUnit == 'cm') {
			$dimensionUnit = 'centimeters';
		} else if ($dimensionUnit == 'm') {
			$dimensionUnit = 'centimeters';
			$dimensionsArray['length'] *= 100;
			$dimensionsArray['width'] *= 100;
			$dimensionsArray['height'] *= 100;
		} else if ($dimensionUnit == 'mm') {
			$dimensionUnit = 'centimeters';
			$dimensionsArray['length'] /= 10;
			$dimensionsArray['width'] /= 10;
			$dimensionsArray['height'] /= 10;
		} else if ($dimensionUnit == 'in') {
			$dimensionUnit = 'inches';
		}

		$dimensionsArray['units'] = $dimensionUnit;
		
		return $dimensionsArray;
	}

	protected function getRequestParams(array $inParams)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__ . ': ' . print_r($inParams, true));

		$params = array();
		if (!empty($inParams['function']) && $inParams['function'] == 'getRates') {
			$params = $this->getRatesParams($inParams);
		} else if (!empty($inParams['function']) && in_array($inParams['function'], array('fetchCarriers', 'fetchCarrierServices', 'fetchCarrierPackageTypes'))) {
			$params = $inParams;
			unset($params['function']);
		}

		return $params;
	}

	protected function parseResponse($response)
	{
		if (is_string($response)) {
			if ($response == '401 Unauthorized') {
				$response = array();
				$response['ExceptionMessage'] = '401 Unauthorized';
			} else {
				$response = json_decode($response, true);
			}
		}

		return $response;
	}

	protected function getErrorMessage($response)
	{
		$message = '';

		if (!empty($response['ExceptionMessage'])) {
			$message = $response['ExceptionMessage'];
		} else if (!empty($response['Message'])) {
			if (!empty($response['ModelState']) && is_array($response['ModelState'])) {
				foreach ($response['ModelState'] as $key => $error) {
					if (!empty($message)) {
						$message .= "\n"; 
					}
					$message .= "$key - " . implode('; ', (array)$error);
				}
			} else {
				$message = $response['Message'];
			}
		}

		return trim($message);
	}

	protected function getRatesResponse($response, array $params)
	{
		if (empty($response) || !is_array($response) || !empty($this->getErrorMessage($response))) {
			return array();
		}

		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__);

		$packageType = 'package';
		if (!empty($params['type'])) {
			$packageType = $params['type'];
		}

		$packageTypeName = 'Package';
		if (!empty($this->packageTypes[$packageType])) {
			$packageTypeName = $this->packageTypes[$packageType];
		}

		$this->logger->debug(__FILE__, __LINE__, 'Package Type Name: ' . $packageTypeName);

		$rates = array();
		$carrierCode = '';
		if (!empty($params['carrierCode'])) {
			$carrierCode = $params['carrierCode'];
		}

		foreach ($response as $rate) {
			if (!isset($rate['serviceName']) || !isset($rate['shipmentCost']) || !isset($rate['otherCost'])) {
				$this->logger->debug(__FILE__, __LINE__, 'Rate does not have serviceName, shipmentCost or otherCost set, so skip might be invalid');
				continue;
			}

			$serviceId = $this->getServiceId($carrierCode, $rate['serviceCode']);
			$serviceName = $rate['serviceName'];

			if (isset($rates[$serviceId]) && strpos($serviceName, '- ' . $packageTypeName) === false) {
				$this->logger->debug(__FILE__, __LINE__, 'Same rate is already found and new one does not have expected package type in its name, so skip it: ' . print_r($rate, true));
				continue;
			}

			$this->logger->debug(__FILE__, __LINE__, 'Use Rate: ' . $serviceId . ', ' . $serviceName);

			if (!empty($this->_services[$serviceId])) {
				$serviceName = $this->_services[$serviceId];
			}

			$rate['service'] = $serviceId;
			$rate['postage_description'] = apply_filters($this->id . '_service_name', $serviceName, $serviceId);
			$rate['cost'] = $rate['shipmentCost'] + $rate['otherCost'];
			$rate['delivery_fee'] = 0;
			$rate['tracking_type_description'] = '';
			$rate['delivery_time_description'] = '';

			$rates[$serviceId] = $rate;
		}
		
		$newResponse['shipment']['rates'] = $rates;

		return $newResponse;
	}

	protected function getResponse($response, array $params)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__);
		
		$newResponse = array('response' => $response, 'params' => $params);

		$errorMessage = $this->getErrorMessage($response);
		if (!empty($errorMessage)) {
			$newResponse['error']['message'] = $errorMessage;

			$this->logger->debug(__FILE__, __LINE__, 'Error Message: ' . $errorMessage);
		}

		$function = null;
		if (!empty($params['function'])) {
			$function = $params['function'];
		}

		if ($function == 'getRates') {
			$newResponse = array_replace_recursive($newResponse, $this->getRatesResponse($response, $params));
		} else if (in_array($function, array('fetchCarriers', 'fetchCarrierServices', 'fetchCarrierPackageTypes'))) {
			if (empty($newResponse['error']['message'])) {
				$newResponse = $response;
			}
		}

		return $newResponse;
	}

	protected function getRouteUrl($route)
	{
		$routeUrl = sprintf('https://ssapi.shipstation.com/%s', $route);

		return $routeUrl;
	}

	protected function addHeadersAndParams(&$headers, &$params)
	{
		$headers['Authorization'] = 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret);
	}

	public function getServices()
	{
		return $this->_services;
	}

	protected function getPackageTypesCacheKey()
	{
		return $this->id . '_' . $this->apiKey . '_packageTypes';
	}

	protected function getCarriersCacheKey()
	{
		return $this->id . '_' . $this->apiKey . '_carriers';
	}

	protected function getServicesCacheKey()
	{
		return $this->id . '_' . $this->apiKey . '_services';
	}

	protected function initCarriers()
	{
		$success = true;

		$carriers = get_option($this->getCarriersCacheKey(), false);
		if (!is_array($carriers)) {
			// backward compatibility
			$carriers = $this->getCacheValue($this->getCarriersCacheKey(), true);
			if (empty($carriers)) {
				// prevent other instances from fetching
				update_option($this->getCarriersCacheKey(), array());

				$carriers = $this->fetchCarriers();
				if (empty($carriers)) {
					$success = false;
					$carriers = array();
				}
			} else {
				$this->deleteCacheValue($this->getCarriersCacheKey());
			}
			update_option($this->getCarriersCacheKey(), $carriers);
		}

		$this->_carriers = $carriers;

		return $success;
	}

	protected function initServices()
	{
		$services = get_option($this->getServicesCacheKey(), false);
		if (!is_array($services)) {
			// backward compatibility
			$services = $this->getCacheValue($this->getServicesCacheKey(), true);
			if (empty($services)) {
				$services = $this->fetchServices();
				if (empty($services)) {
					$services = array();
				}
			} else {
				$this->deleteCacheValue($this->getServicesCacheKey());	
			}
			update_option($this->getServicesCacheKey(), $services);
		}
		
		$this->_services = $services;
	}

	protected function initPackageTypes()
	{
		$packageTypes = get_option($this->getPackageTypesCacheKey(), false);
		if (!is_array($packageTypes)) {
			// backward compatibility
			$packageTypes = $this->getCacheValue($this->getPackageTypesCacheKey(), true);
			if (empty($packageTypes)) {
				$packageTypes = $this->fetchPackageTypes();
				if (empty($packageTypes)) {
					$packageTypes = array();
				}
			} else {
				$this->deleteCacheValue($this->getPackageTypesCacheKey());
			}
			update_option($this->getPackageTypesCacheKey(), $packageTypes);
		}
		
		$this->packageTypes = $packageTypes;
	}

	protected function fetchCarriers()
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__);

		$carriers = array();
		$params = array('function' => __FUNCTION__);
		$theirCarriers = $this->sendRequest('carriers', 'GET', $params);
		if (is_array($theirCarriers) && empty($theirCarriers['error']['message'])) {
			foreach ($theirCarriers as $carrier) {
				if (isset($carrier['code']) && isset($carrier['name'])) {
					$carriers[$carrier['code']] = $carrier['name'];
				}
			}
		}

		$this->logger->debug(__FILE__, __LINE__, 'Carriers: ' . print_r($carriers, true));

		return $carriers;
	}

	protected function fetchCarrierServices($carrierCode)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__ . ': ' . $carrierCode);

		$services = array();
		$params = array('function' => __FUNCTION__, 'carrierCode' => $carrierCode);

		$theirServices = $this->sendRequest('carriers/listservices', 'GET', $params);
		if (is_array($theirServices) && empty($theirServices['error']['message'])) {
			$carrierName = $this->_carriers[$carrierCode];
			foreach ($theirServices as $service) {
				if (isset($service['carrierCode']) && isset($service['code']) && isset($service['name'])) {
					$serviceId = $this->getServiceId($service['carrierCode'], $service['code']);
					$serviceName = $service['name'];

					if (strpos($serviceName, $carrierName) === false) {
						$serviceName = $carrierName . ' ' . $serviceName;
					}

					$services[$serviceId] = $serviceName;
				}
			}

			$this->logger->debug(__FILE__, __LINE__, 'Carrier Services: ' . print_r($services, true));
		}

		return $services;
	}

	protected function fetchServices()
	{
		$services = array();
		if (!empty($this->_carriers) && is_array($this->_carriers)) {
			foreach ($this->_carriers as $carrierCode => $carrierName) {
				$carrierServices = $this->fetchCarrierServices($carrierCode);
				if (!empty($carrierServices)) {
					$services = array_merge($services, $carrierServices);
				}
			}	
		}
		
		return $services;
	}

	protected function fetchCarrierPackageTypes($carrierCode)
	{
		$this->logger->debug(__FILE__, __LINE__, __FUNCTION__ . ': ' . $carrierCode);

		$packageTypes = array();
		$params = array('function' => __FUNCTION__, 'carrierCode' => $carrierCode);
		$theirPackageTypes = $this->sendRequest('carriers/listpackages', 'GET', $params);
		if (is_array($theirPackageTypes) && empty($theirPackageTypes['error']['message'])) {
			foreach ($theirPackageTypes as $packageType) {
				if (isset($packageType['carrierCode']) && isset($packageType['code']) && isset($packageType['name'])) {
					$key = $packageType['code'];
					$packageTypes[$key] = $packageType['name'];
				}
			}

			$this->logger->debug(__FILE__, __LINE__, 'Carrier Package Types: ' . print_r($packageTypes, true));
		}

		return $packageTypes;
	}

	protected function fetchPackageTypes()
	{
		$packageTypes = array();
		if (!empty($this->_carriers) && is_array($this->_carriers)) {
			foreach ($this->_carriers as $carrierCode => $carrierName) {
				$carrierPackageTypes = $this->fetchCarrierPackageTypes($carrierCode);
				if (!empty($carrierPackageTypes)) {
					$packageTypes = array_merge($packageTypes, $carrierPackageTypes);
				}
			}
		}
		return $packageTypes;
	}

	protected function getServiceId($carrierCode, $serviceCode)
	{
		$serviceId = $carrierCode . '|' . $serviceCode;

		return $serviceId;
	}
}

endif;
