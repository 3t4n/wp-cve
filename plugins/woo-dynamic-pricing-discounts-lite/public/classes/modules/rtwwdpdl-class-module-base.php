<?php
/**
 * Class RTWWDPDL_Module_Base to calculate discount according to Modules.
 *
 * @since    1.0.0
 */
abstract class RTWWDPDL_Module_Base
{
	/**
	 * variable to set module id.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_module_id;
	public $module_id;
	public $module_type;
	/**
	 * variable to set module type.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_module_type;
	/**
	 * construct function.
	 *
	 * @since    1.0.0
	 */
	public function __construct($rtwwdpdl_module_id, $rtwwdpdl_module_type)
	{
		$this->module_id   = $rtwwdpdl_module_id;
		$this->module_type = $rtwwdpdl_module_type;
	}
	/**
	 * Function defined abstract.
	 *
	 * @since    1.0.0
	 */
	public abstract function rtwwdpdl_adjust_cart($rtwwdpdl_cart);
	/**
	 * Function to get product price on which discount is applied.
	 *
	 * @since    1.0.0
	 */
	public function rtw_get_price_to_discount($rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key, $rtw_stack_rules = false)
	{
		global $woocommerce;
		$rtwwdpdl_setting_pri = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_result = false;
		do_action('rtwwdpdl_memberships_discounts_disable_price_adjustments');
		$rtwwdpdl_filter_cart_item = $rtwwdpdl_cart_item;
		if (isset(WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]))
		{
			$rtwwdpdl_filter_cart_item = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key];
			if (isset(WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']))
			{
				if ($this->rtwwdpdl_is_cumulative($rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key) || $rtw_stack_rules)
				{
					$rtwwdpdl_result = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']['price_adjusted'];
				}
				else
				{
					$rtwwdpdl_result = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']['price_base'];
				}
			}
			else
			{
				if (isset($rtwwdpdl_setting_pri['rtw_dscnt_on']) && $rtwwdpdl_setting_pri['rtw_dscnt_on'] == 'rtw_sale_price')
				{
					if (apply_filters('rtwwdpdl_dynamic_pricing_get_use_sale_price', true, $rtwwdpdl_filter_cart_item['data']))
					{
						$rtwwdpdl_result = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['data']->get_price('edit');
					}
					else
					{
						$rtwwdpdl_result = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['data']->get_regular_price('edit');
					}
				}
				else
				{
					$rtwwdpdl_result = WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['data']->get_regular_price('edit');
				}
			}
		}
		return $rtwwdpdl_result;
	}
	/**
	 * Function to check if a product is discounted.
	 *
	 * @since    1.0.0
	 */
	protected function rtwwdpdl_is_item_discounted($rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key)
	{
		global $woocommerce;
		return isset(WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']);
	}
	/**
	 * Function to check if a product is already discounted by the same rule.
	 *
	 * @since    1.0.0
	 */
	protected function rtwwdpdl_is_cumulative($rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key, $rtwwdpdl_default = false)
	{
		//Check to make sure the item has not already been discounted by this module.  This could happen if update_totals is called more than once in the cart. 
		$rtwwdpdl_cart = WC()->cart->get_cart();
		if (isset($rtwwdpdl_cart) && is_array($rtwwdpdl_cart) && isset($rtwwdpdl_cart[$rtwwdpdl_cart_item_key]['discounts']) && in_array($this->module_id, WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']['by']))
		{
			return false;
		}
		else
		{
			return apply_filters('rtwwdpdl_is_cumulative', $rtwwdpdl_default, $this->module_id, $rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key);
		}
	}
	/**
	 * Function to reset cart items.
	 *
	 * @since    1.0.0
	 */
	protected function rtw_reset_cart_item(&$rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key)
	{
		if (isset(WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']) && in_array($this->module_id, WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts']['by']))
		{
			foreach (WC()->cart->cart_contents[$rtwwdpdl_cart_item_key]['discounts'] as $module)
			{
			}
		}
	}
}
