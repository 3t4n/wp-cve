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

if (!class_exists(__NAMESPACE__ . '\\AbstractRatesFinder')):

abstract class AbstractRatesFinder
{
	protected $id;
	protected $error;
	protected $validationErrors;
	protected $logger;

	public function __construct($id, array $settings = array())
	{
		$this->id = $id;
		$this->error = null;
		$this->validationErrors = array();

		$this->logger = &\OneTeamSoftware\WooCommerce\Logger\LoggerInstance::getInstance($this->id);

		$this->setSettings($settings);
	}

	public function setSettings(array $settings)
	{
		foreach ($settings as $key => $val) {
			if (property_exists($this, $key)) {
				if ($val == 'yes') {
					$this->$key = true;
				} else if ($val == 'no') {
					$this->$key = false;
				} else if (is_bool($val)) {
					$this->$key = boolval($val);
				} else {
					$this->$key = $val;
				}
			}
		}
	}

	public function getValidationErrors()
	{
		return $this->validationErrors;
	}

	public function getError()
	{
		return $this->error;
	}

	abstract public function findShippingRates($package = array());
}

endif;