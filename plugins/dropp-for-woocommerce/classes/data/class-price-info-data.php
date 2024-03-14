<?php
/**
 * Price Info
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Data;

use Dropp\Actions\Get_Remote_Price_Info_Action;
use Dropp\Exceptions\Response_Exception;
use Exception;

/**
 * Dropp PDF
 *
 * @property array<array> $items
 */
class Price_Info_Data {
	const TTL = 3600;
	protected static ?self $instance;

	private function __construct(
		protected int $updated_at,
		protected int $expire_at,
		protected array $items = [],
	) {
	}

	public static function flush_cache(): void
	{
		wp_cache_delete('dropp_for_woocommerce_price_info', 'dropp_for_woocommerce');
		$price_info = get_transient('dropp_for_woocommerce_price_info') ?? [];
		if (! empty($price_info)) {
			delete_transient('dropp_for_woocommerce_price_info');
		}
		self::$instance = null;
	}

	public static function get_instance()
	{
		$instance = wp_cache_get('dropp_for_woocommerce_price_info', 'dropp_for_woocommerce');
		if ($instance && $instance->expire_at > time()) {
			return $instance;
		}

		if (isset(self::$instance) && self::$instance->expire_at > time()) {
			return self::$instance;
		}

		// Attempt to get from options
		$price_info = get_transient('dropp_for_woocommerce_price_info') ?? [];
		$items = $price_info['items'] ?? [];
		$expire_at = $price_info['expire_at'] ?? 0;
		$updated_at = $price_info['updated_at'] ?? time();

		// Get from remote when option is empty or expired
		if (empty($items) || $expire_at < time() ) {
			try {
				$items = (new Get_Remote_Price_Info_Action)();
			} catch (Response_Exception|Exception $exception) {
				$items = $price_info['items'] ?? [];
			}
			$updated_at = time();
			$expire_at = time() + self::TTL;

			if (! empty($items)) {
				// Save to options
				set_transient(
					'dropp_for_woocommerce_price_info',
					[
						'items'      => $items,
						'expire_at'  => $expire_at,
						'updated_at' => $updated_at,
					],
					self::TTL
				);
			}
		}

		// Map to Price Data
		$mapped_items = [];
		foreach ($items as $key => $prices) {
			$mapped_prices = array_map(
				fn(array $price) => new Price_Data(
					$price['price'],
					$price['maxweight']
				),
				$prices
			);
			usort(
				$mapped_prices,
				fn(Price_Data $a, Price_Data $b) => $a->max_weight > $b->max_weight ? 1 : -1
			);
			$mapped_items[$key] = $mapped_prices;
		}

		self::$instance = new self(
			$updated_at,
			$expire_at,
			$mapped_items
		);
		wp_cache_set('dropp_for_woocommerce_price_info', self::$instance, 'dropp_for_woocommerce', self::TTL);
		return self::$instance;
	}

	public function get(?string $code): array
	{
		return $this->items[$code] ?? [];
	}
}
