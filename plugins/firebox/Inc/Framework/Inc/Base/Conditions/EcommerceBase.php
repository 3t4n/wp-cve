<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Conditions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class EcommerceBase extends PluginBase
{
	/**
	 * Pass method for "Amount In Cart" condition.
	 * 
	 * @return  bool
	 */
	public function passAmountInCart()
	{
		// Whether we exclude shipping cost
		$exclude_shipping_cost = $this->params->get('exclude_shipping_cost', '0') === '1';

		$amount = 0;
		switch ($this->params->get('total', 'total'))
		{
			case 'total':
				$amount = $this->getCartTotal();
				break;
			
			case 'subtotal':
				$amount = $this->getCartSubtotal();
				break;
		}

		// Calculate shipping total
		$shipping_total = $exclude_shipping_cost && $amount > 0 ? -$this->getShippingTotal() : 0;

		// Calculate final amount
		$amount = $amount + $shipping_total;

		$operator = $this->options->get('operator', 'equal');

		$selection = (float) $this->selection;

		// Range selection
		if ($operator === 'range')
		{
			$selection = [
				'value1' => $selection,
				'value2' => (float) $this->options->get('params.value2', false)
			];
		}

        return $this->passByOperator($amount, $selection, $operator);
	}
	
	/**
	 * Pass method for "Products In Cart" condition.
	 * 
	 * @param   string  $cart_product_item_id_key
	 * 
	 * @return  bool
	 */
	public function passProductsInCart($cart_product_item_id_key = 'id')
	{
		// Get cart products
		if (!$cartProducts = $this->getCartProducts())
		{
			return false;
		}

		// Get condition products
		if (!$conditionProducts = $this->selection)
		{
			return false;
		}

		// Ensure all condition's products exist in the cart
		$foundCartProducts = array_filter(
			$cartProducts,
			function ($prod) use ($conditionProducts, $cart_product_item_id_key)
			{
				// Check the ID first
				$valid = array_filter($conditionProducts, function($item) use ($prod, $cart_product_item_id_key) {
					return isset($item['value']) && (int) $item['value'] === (int) $prod[$cart_product_item_id_key];
				});

				// If not valid, abort
				if (!$valid)
				{
					return;
				}

				// Get valid product
				$valid_product = reset($valid);
				
				// Ensure valid product quantity
				$product_quantity = isset($prod['quantity']) ? (int) $prod['quantity'] : false;
				if (!$product_quantity)
				{
					return $valid;
				}

				// We need an operator other than "any"
				if (!isset($valid_product['quantity_operator']) || $valid_product['quantity_operator'] === 'any')
				{
					return $valid;
				}
			
				// Ensure value 1 is valid
				$quantity_value1 = isset($valid_product['quantity_value1']) ? (int) $valid_product['quantity_value1'] : false;
				if (!$quantity_value1)
				{
					return $valid;
				}

				$quantity_value2 = isset($valid_product['quantity_value2']) ? (int) $valid_product['quantity_value2'] : false;

				// Default selection
				$selection = $quantity_value1;

				// Range selection
				if ($valid_product['quantity_operator'] === 'range')
				{
					$selection = [
						'value1' => $quantity_value1,
						'value2' => $quantity_value2
					];
				}

				return $this->passByOperator($product_quantity, $selection, $valid_product['quantity_operator']);
			}
		);

		return count($foundCartProducts);
	}
	
	/**
	 * Pass method for "Current Product Price" condition.
	 * 
	 * @return  bool
	 */
	public function passCurrentProductPrice()
	{
		// Ensure we are viewing a product page
		if (!$this->isSinglePage())
		{
			return;
		}

		// Get current product data
		if (!$product_data = $this->getCurrentProductData())
		{
			return;
		}

		// Try to find a valid product in the selection
		$valid = array_filter($this->selection, function($item) use ($product_data) {
			return isset($item['value']) && (int) $item['value'] === (int) $product_data['id'];
		});

		// Ensure we found a valid product
		if (!$valid)
		{
			return;
		}

		// Get valid product
		$valid_product = reset($valid);

		// We need an operator
		if (!isset($valid_product['price_operator']))
		{
			return $valid;
		}
	
		// Ensure valid value 1
		$price_value1 = isset($valid_product['price_value1']) ? (float) $valid_product['price_value1'] : false;
		if (!$price_value1)
		{
			return $valid;
		}

		// Default selection
		$selection = $price_value1;

		// Range selection
		if ($valid_product['price_operator'] === 'range')
		{
			$price_value2 = isset($valid_product['price_value2']) ? (float) $valid_product['price_value2'] : false;
	
			$selection = [
				'value1' => $price_value1,
				'value2' => $price_value2
			];
		}

		return $this->passByOperator($product_data['price'], $selection, $valid_product['price_operator']);
	}

	/**
	 * Pass method for "Current Product Stock" condition.
	 * 
	 * @return  bool
	 */
	public function passCurrentProductStock()
	{
		if (!is_array($this->selection)|| !count($this->selection))
		{
			return;
		}
	
		// Ensure we are viewing a product page
		if (!$this->isSinglePage())
		{
			return;
		}
		
		$current_product_id = $this->request->id;

		// Try to find a valid product in the selection
		$valid = array_filter($this->selection, function($item) use ($current_product_id) {
			return isset($item['value']) && (int) $item['value'] === $current_product_id;
		});

		// Ensure we found a valid product
		if (!$valid)
		{
			return;
		}

		// Get valid product
		$valid_product = reset($valid);

		if (!$product_stock = $this->getProductStock($valid_product['value']))
		{
			return;
		}
		
		// We need an operator
		if (!isset($valid_product['stock_operator']))
		{
			return;
		}
	
		// Ensure valid value 1
		$stock_value1 = isset($valid_product['stock_value1']) ? (int) $valid_product['stock_value1'] : false;
		if (!$stock_value1)
		{
			return;
		}

		// Default selection
		$selection = $stock_value1;

		// Range selection
		if ($valid_product['stock_operator'] === 'range')
		{
			$stock_value2 = isset($valid_product['stock_value2']) ? (int) $valid_product['stock_value2'] : false;
	
			$selection = [
				'value1' => $stock_value1,
				'value2' => $stock_value2
			];
		}

		return $this->passByOperator($product_stock, $selection, $valid_product['stock_operator']);
	}

	/**
	 * Pass method for "Last Purchase Date" condition.
	 * 
	 * @return  bool
	 */
	protected function passLastPurchaseDate()
	{
		// User must be logged in
		if (!is_user_logged_in())
		{
			return;
		}

		if (!$purchase_date = $this->getLastPurchaseDate(get_current_user_id()))
		{
			return;
		}

		$purchaseDate = new \DateTime($purchase_date, new \DateTimeZone('UTC'));
		$currentDate = new \DateTime('now', new \DateTimeZone('UTC'));

		$pass = false;

		$operator = $this->options->get('operator', 'within');

		switch ($operator)
		{
			case 'within':
				if (!$within_value = intval($this->options->get('params.within_value')))
				{
					return;
				}
				$within_period = $this->options->get('params.within_period', 'hours');

				$timeframe = strtoupper($within_period[0]);

				// Hours requires a "T"
				if ($timeframe === 'H')
				{
					$within_value = 'T' . $within_value;
				}

				$interval = new \DateInterval("P{$within_value}{$timeframe}");
				$purchaseDateXDaysAgo = (clone $purchaseDate)->add($interval);
				$interval->invert = 1; // Set invert to 1 to indicate past time

				$pass = $purchaseDateXDaysAgo >= $currentDate;

				break;
			
			case 'equal':
				if (!$this->selection)
				{
					return;
				}

				$selectionDate = new \DateTime($this->selection, new \DateTimeZone('UTC'));

				$pass = $purchaseDate->format('Y-m-d') === $selectionDate->format('Y-m-d');
				break;
			case 'before':
				if (!$this->selection)
				{
					return;
				}

				$selectionDate = new \DateTime($this->selection, new \DateTimeZone('UTC'));

				$pass = $purchaseDate < $selectionDate;

				break;
			case 'after':
				if (!$this->selection)
				{
					return;
				}

				$selectionDate = new \DateTime($this->selection, new \DateTimeZone('UTC'));

				$pass = $purchaseDate > $selectionDate;
				break;
			case 'range':
				if (!$secondDate = $this->options->get('params.value2'))
				{
					return;
				}
				
				if (!$this->selection)
				{
					return;
				}

				$startDate = new \DateTime($this->selection, new \DateTimeZone('UTC'));
				$endDate = new \DateTime($secondDate, new \DateTimeZone('UTC'));

				$pass = $purchaseDate >= $startDate && $purchaseDate <= $endDate;
				break;
		}

		return $pass;
	}

	/**
	 * Checks if we are in a category page.
	 * 
	 * @return  bool
	 */
	protected function passCategoryPage()
	{
		$this->params->set('view_category', true);
		$this->params->set('view_single', false);
		
        return $this->passCategories();
	}

    /**
     * Get single page's assosiated categories
     *
     * @param   Integer  The Single Page id
	 * 
     * @return  array
     */
    protected function getSinglePageCategories($id) {}
}