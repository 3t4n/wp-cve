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

namespace FPFramework\Base\Conditions\Conditions\EDD;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\EcommerceBase;

class EDDBase extends EcommerceBase
{
	/**
	 * The taxonomy name.
	 * 
	 * @var string
	 */
	protected $taxonomy = 'download_category';
	
    /**
     * The component's Single Page view name
     *
     * @var string
     */
    protected $postTypeSingle = 'download';

    /**
     * Get single page's assosiated categories
     *
     * @param   Integer  The Single Page id
	 * 
     * @return  array
     */
	protected function getSinglePageCategories($id)
	{
		if (!$terms = get_the_terms($id, $this->taxonomy))
		{
			return [];
		}

		if (!is_array($terms))
		{
			return [];
		}

		return array_column($terms, 'term_id');
	}

    /**
	 *  Returns the EDD cart.
	 * 
	 *  @return  array
	 */
	public function getCart()
	{
		if (!function_exists('EDD'))
		{
			return;
		}

		return EDD()->cart;
    }

	/**
	 * Returns the products in the cart
	 * 
	 * @return  array
	 */
	public function getCartProducts()
	{
		if (!$cart = $this->getCart())
		{
			return [];
		}

		return $cart->details ?? [];
	}

	/**
	 * Returns the current product.
	 * 
	 * @return  object
	 */
	protected function getCurrentProduct()
	{
		if (!$this->request->id)
		{
			return;
		}

		if (!function_exists('EDD'))
		{
			return;
		}

		if (!$product = edd_get_download($this->request->id))
		{
			return;
		}

		if ($product->post_type !== $this->postTypeSingle)
		{
			return;
		}

		return $product;
	}

	/**
	 * Returns the current product data.
	 * 
	 * @return  object
	 */
	protected function getCurrentProductData()
	{
		if (!$product = $this->getCurrentProduct())
		{
			return;
		}

		return [
			'id' => $product->ID,
			'price' => (float) edd_get_lowest_price_option($product->ID)
		];
	}

	/**
	 * Returns the product stock.
	 * 
	 * @param   int  $id
	 * 
	 * @return  int
	 */
	public function getProductStock($id = null)
	{
		if (!$id)
		{
			return;
		}

		// We require EDD "Purchase Limit" plugin to be enabled
		if (!function_exists('edd_pl_get_file_purchase_limit'))
		{
			return;
		}

		return (int) edd_pl_get_file_purchase_limit($id);
	}

	/**
	 * Returns the current user's last purchase date in format: d/m/Y H:i:s and in UTC.
	 * 
	 * @param   int     $user_id
	 * 
	 * @return  string
	 */
	protected function getLastPurchaseDate($user_id = null)
	{
		if (!$user_id)
		{
			return;
		}
		
		if (!function_exists('EDD'))
		{
			return;
		}
		
		// Get customer
		if (!$customer = edd_get_customer_by('user_id', $user_id))
		{
			return;
		}

		// Get last purchase
		$last_purchase = edd_get_payments([
			'customer' => $customer->id,
			'status' => 'complete',
			'orderby' => 'date',
			'number'  => 1
		]);

		// Abort if none found
		if (!$last_purchase)
		{
			return;
		}

		return $last_purchase[0]->completed_date;
	}
}