<?php
/**
 * Licensing
 *
 * @package    licensing
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Licensing plans
 */
class MO_OAuth_Client_License_Pricing_Breakdown {

	/**
	 * Standard pricing.
	 *
	 * @var pricing_standard contains the pricing for standard plan.
	 */
	public $pricing_standard;
	/**
	 * Premium pricing.
	 *
	 * @var pricing_premium contains the pricing for premium plan.
	 */
	public $pricing_premium;
	/**
	 * Enterprise pricing.
	 *
	 * @var pricing_enterprise contains the pricing for enterprise plan.
	 */
	public $pricing_enterprise;
	/**
	 * All Inclusive pricing.
	 *
	 * @var pricing_all_inclusive contains the pricing for all inclusive plan.
	 */
	public $pricing_all_inclusive;
	/**
	 * Multisite premium plan pricing.
	 *
	 * @var mul_pricing_premium contains the pricing for multisite premium plan.
	 */
	public $mul_pricing_premium;
	/**
	 * Multisite Enterprise plan pricing.
	 *
	 * @var mul_pricing_enterprise contains the pricing for multisite enterprise plan.
	 */
	public $mul_pricing_enterprise;
	/**
	 * Multisite All inclusive pricing.
	 *
	 * @var mul_pricing_all_inclusive contains the pricing for multisite all inclusive plan.
	 */
	public $mul_pricing_all_inclusive;
	/**
	 * Subsite pricing.
	 *
	 * @var subsite_intances contains the pricing for number of subsites.
	 */
	public $subsite_intances;


	/**
	 * Initialize object on the basis of different licensing plans.
	 */
	public function __construct() {
		$this->pricing_standard = array(
			'1' => '349',
			'2' => '663',
			'3' => '942',
			'4' => '1187',
			'5' => '1396',
		);

		$this->pricing_premium = array(
			'1' => '499',
			'2' => '948',
			'3' => '1347',
			'4' => '1,697',
			'5' => '1,996',
		);

		$this->pricing_enterprise = array(
			'1' => '549',
			'2' => '1,043',
			'3' => '1,482',
			'4' => '1,867',
			'5' => '2,196',
		);

		$this->pricing_all_inclusive = array(
			'1' => '699',
			'2' => '1,328',
			'3' => '1,887',
			'4' => '2,377',
			'5' => '2,796',
		);

		$this->mul_pricing_premium = array(
			'1' => '499',
			'2' => '948',
			'3' => '1347',
			'4' => '1,697',
			'5' => '1,996',
		);

		$this->mul_pricing_enterprise = array(
			'1' => '549',
			'2' => '1,043',
			'3' => '1,482',
			'4' => '1,867',
			'5' => '2,196',
		);

		$this->mul_pricing_all_inclusive = array(
			'1' => '699',
			'2' => '1,328',
			'3' => '1,887',
			'4' => '2,377',
			'5' => '2,796',
		);

		$this->subsite_intances = array(
			'$90 - Upto 3 Subsites / Instance'   => '90',
			'$150 - Upto 5 Subsites / Instance'  => '150',
			'$300 - Upto 10 Subsites / Instance' => '300',
		);

	}
}


