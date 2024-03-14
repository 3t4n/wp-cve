<?php
/**
 * Settings
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Settings;

use NovaPoshta\Main;

/**
 * Class Settings
 *
 * @package NovaPoshta\Settings
 */
class Settings {

	/**
	 * Plugin options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Settings constructor.
	 */
	public function __construct() {

		$this->options = get_option( Main::PLUGIN_SLUG, [] );
	}

	/**
	 * API key
	 *
	 * @return string
	 */
	public function api_key(): string {

		return $this->options['api_key'] ?? '';
	}

	/**
	 * Admin phone
	 *
	 * @return string
	 */
	public function phone(): string {

		return $this->options['phone'] ?? '';
	}

	/**
	 * Package description
	 *
	 * @return string
	 */
	public function description(): string {

		return $this->options['description'] ?? 'Товар';
	}

	/**
	 * Admin city_id
	 *
	 * @return string
	 */
	public function city_id(): string {

		return $this->options['city_id'] ?? '';
	}

	/**
	 * Admin warehouse id
	 *
	 * @return string
	 */
	public function warehouse_id(): string {

		return $this->options['warehouse_id'] ?? '';
	}

	/**
	 * Is a shipping cost enable
	 *
	 * @return bool
	 */
	public function is_shipping_cost_enable(): bool {

		return ! empty( $this->options['is_shipping_cost_enable'] );
	}

	/**
	 * Is a shipping in the total
	 *
	 * @return bool
	 */
	public function exclude_shipping_from_total(): bool {

		return ! empty( $this->options['exclude_shipping_from_total'] );
	}

	/**
	 * Default formula for calculate weight of products in order
	 *
	 * @return string
	 */
	public function default_weight_formula(): string {

		return $this->options['default_weight_formula'] ?? '[qty] * 0.5';
	}

	/**
	 * Default formula for calculate width of products in order
	 *
	 * @return string
	 */
	public function default_width_formula(): string {

		return $this->options['default_width_formula'] ?? '[qty] * 0.26';
	}

	/**
	 * Default formula for calculate length of products in order
	 *
	 * @return string
	 */
	public function default_length_formula(): string {

		return $this->options['default_length_formula'] ?? '[qty] * 0.145';
	}

	/**
	 * Default formula for calculate height of products in order
	 *
	 * @return string
	 */
	public function default_height_formula(): string {

		return $this->options['default_height_formula'] ?? '[qty] * 0.1';
	}

	/**
	 * Place for fields on the checkout page.
	 *
	 * @return string
	 */
	public function place_for_fields(): string {

		return $this->options['place_for_fields'] ?? 'shipping_method';
	}
}
