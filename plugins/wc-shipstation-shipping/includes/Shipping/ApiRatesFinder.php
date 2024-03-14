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

namespace OneTeamSoftware\WooCommerce\Shipping;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\ApiRatesFinder')):

class ApiRatesFinder extends AbstractRatesFinder
{
	protected $adapter;
	protected $mediaMail;
	protected $displayDeliveryTime;
	protected $displayTrackingType;
	protected $requireCompanyName;

	function __construct($id, Adapter\AbstractAdapter $adapter, $settings = array())
	{
		$this->adapter = $adapter;
		$this->mediaMail = null;
		$this->displayDeliveryTime = false;
		$this->displayTrackingType = false;
		$this->requireCompanyName = false;

		parent::__construct($id, $settings);
	}

	public function setSettings(array $settings)
	{
		parent::setSettings($settings);

		$this->adapter->setSettings($settings);
	}

	public function findShippingRates($package = array())
	{
        $this->logger->debug(__FILE__, __LINE__, "findShippingRates");

		if (empty($package['destination']['country'])) {
			$this->error = __('Destination country is required', $this->id);
            $this->logger->debug(__FILE__, __LINE__, "Destination country is required");

			return null;
		}

		if (empty($package['weight'])) {
			$this->error = __('Parcel weight must be larger than 0', $this->id);
            $this->logger->debug(__FILE__, __LINE__, "Parcel weight must be larger than 0");

			return null;
		}
		
		if ($this->requireCompanyName && empty($package['destination']['company'])) {
			$package['destination']['company'] = __('Company Name Is Missing', $this->id);
			$this->logger->debug(__FILE__, __LINE__, "Company name is required but missing, so add fake company name, so we can get rates");
		}
		
		$mediaMail = isset($package['mediaMail']) ? $package['mediaMail'] : $this->mediaMail;

		if (!empty($mediaMail) && $mediaMail == 'only') {
			$package['mediaMail'] = $mediaMail;
		} else {
			$package['mediaMail'] = false;
		}

		$rates = $this->getPackageShippingRates($package);

		if (!empty($mediaMail) && $mediaMail == 'include') {
			$package['mediaMail'] = $mediaMail;

			$mediaRates = $this->getPackageShippingRates($package);
			if (!empty($mediaRates)) {
				$rates = array_merge_recursive($mediaRates, $rates);
			}
		}

		return $rates;
	}

	private function getPackageShippingRates(array $package)
	{
		$this->logger->debug(__FILE__, __LINE__, 'getPackageShippingRates: ' . print_r($package, true));

		$this->error = null;
		$this->validationErrors = array();

		$result = $this->adapter->getRates($package);

		if (!empty($result['validation_errors'])) {
			$this->validationErrors = $result['validation_errors'];
		}

		if (!empty($result['error']['message'])) {
			$this->error = $result['error']['message'];
		}
		if (!isset($result['shipment']['rates'])) {
			$this->logger->debug(__FILE__, __LINE__, 'No rates have been found');
			
			return null;
		}

		$rates = array();
		foreach ($result['shipment']['rates'] as $rate) {
			$rates[] = $this->prepareRate($rate);
		}

		$this->logger->debug(__FILE__, __LINE__, 'rates: ' . print_r($rates, true));

		return $rates;
	}

	private function prepareRate($rate)
	{
		$this->logger->debug(__FILE__, __LINE__, 'prepareRate: ' . print_r($rate, true));

		if (!empty($rate['postage_description']) && empty($rate['cost'])) {
			return null;
		}

		$label = $rate['postage_description'];
		
		$labelSuffix = '';
		if ($this->displayDeliveryTime && !empty($rate['delivery_time_description'])) {
			$labelSuffix .= $rate['delivery_time_description'];
		}

		if ($this->displayTrackingType && !empty($rate['tracking_type_description'])) {
			if (!empty($labelSuffix)) {
				$labelSuffix .= ', ';
			}

			$labelSuffix .= $rate['tracking_type_description'];
		}

		if (!empty($labelSuffix)) {
			$label .= ' (' . $labelSuffix . ')';
		}

		return array(
			'id' => $rate['service'],
			'label' => $label,
			'cost' => $rate['cost']
		);
	}
}

endif;