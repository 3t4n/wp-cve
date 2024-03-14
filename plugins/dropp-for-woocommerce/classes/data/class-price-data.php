<?php
/**
 * Price Data
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Data;

class Price_Data {
	/**
	 * Construct
	 */
	public function __construct(
		public float $price,
		public float $max_weight
	) {
	}

	/**
	 * To array
	 *
	 * @return array Array representation.
	 */
	public function to_array(): array {
		return [
			'price' => $this->price,
			'max_weight' => $this->max_weight,
		];
	}

}
