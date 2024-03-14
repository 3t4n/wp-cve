<?php
/**
 * API
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Shipping_Method\Shipping_Method;
use Exception;
use WC_Log_Levels;
use WC_Logger;
use WC_Product_Simple;
use WC_Shipping;
use Dropp\Models\Model;
use WC_Shipping_Method;

/**
 * API
 */
class Modify_Rate_Cost_By_Weight
{
	use Calculates_Package_Weight;

	protected static self $instance;

	public function __construct(
		protected array $package,
		protected Shipping_Method $shipping_method
	) {
	}

	public static function setup(): void
	{
		add_action('dropp_before_calculate_shipping', __CLASS__.'::register', 10, 2);
		add_action('dropp_after_calculate_shipping', __CLASS__.'::unregister');
	}

	public static function register(array $package, Shipping_Method $shipping_method): void
	{
		self::$instance = new static($package, $shipping_method);
		add_filter(
			sprintf(
				'woocommerce_shipping_%s_instance_option',
				$shipping_method->id
			),
			__CLASS__.'::filter',
			10,
			3
		);
	}

	public static function unregister(): void
	{
		remove_filter(
			sprintf(
				'woocommerce_shipping_%s_option',
				self::$instance->shipping_method->id
			),
			__CLASS__.'::filter'
		);
	}

	public static function filter($option, $key, Shipping_Method $shipping_method)
	{
		if ($key !== 'cost') {
			return $option;
		}

		$cost_tiers = self::$instance->shipping_method->get_cost_tiers();

		$weight = self::$instance->calculate_package_weight();

		$i        = 0;
		$base_key = 'cost';
		$key      = $base_key;

		/** @var Cost_Tier $cost_tier */
		foreach ($cost_tiers as $cost_tier) {
			if ($weight < $cost_tier->weightLimit) {
				break;
			}
			$i++;
			$key = "{$base_key}_$i";
		}

		$value = $option;
		if ($key !== $base_key) {
			$value = self::$instance->shipping_method->get_option($key);
		}

		if ($value === '') {
			return $cost_tiers[$i]->placeholder ?? $value;
		}

		return $value;
	}

}
