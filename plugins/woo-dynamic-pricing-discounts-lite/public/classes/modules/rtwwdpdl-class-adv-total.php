<?php

/**
 * Class RTWWDPDL_Advance_Category to calculate discount according to all discount rule.
 *
 * @since    1.0.0
 */
class RTWWDPDL_Advance_Total extends RTWWDPDL_Advance_Base
{
	/**
	 * variable to set instance of all modules.
	 *
	 * @since    1.0.0
	 */
	private static $instance;
	/**
	 * function to set instance of all modules.
	 *
	 * @since    1.0.0
	 */
	public static function rtwwdpdl_instance()
	{
		if (self::$instance == null)
		{
			self::$instance = new RTWWDPDL_Advance_Total('advanced_totals');
		}
		return self::$instance;
	}
	/**
	 * variable to check rules priority.
	 *
	 * @since    1.0.0
	 */
	public $adjustment_sets;
	public $rtwwdpdl_priority;
	/**
	 * construct function.
	 *
	 * @since    1.0.0
	 */
	public function __construct($module_id)
	{
		parent::__construct($module_id);
		$rtwwdpdl_get_settings = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_i = 0;
		$rtwwdpdl_priority = array();
		if (is_array($rtwwdpdl_get_settings) && !empty($rtwwdpdl_get_settings))
		{
			foreach ($rtwwdpdl_get_settings as $key => $value)
			{
				if ($key == 'cart_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['cart_rule']) && $rtwwdpdl_get_settings['cart_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
				elseif ($key == 'pro_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['pro_rule']) && $rtwwdpdl_get_settings['pro_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
				elseif ($key == 'bogo_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['bogo_rule']) && $rtwwdpdl_get_settings['bogo_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
				elseif ($key == 'tier_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['tier_rule']) && $rtwwdpdl_get_settings['tier_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
				elseif ($key == 'cat_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['cat_rule']) && $rtwwdpdl_get_settings['cat_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
				elseif ($key == 'pay_rule_row')
				{
					if (isset($rtwwdpdl_get_settings['pay_rule']) && $rtwwdpdl_get_settings['pay_rule'] == 1)
					{
						$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
						$rtwwdpdl_i++;
					}
				}
			}
		}
		$this->rtwwdpdl_priority = $rtwwdpdl_priority;
		if (is_array($rtwwdpdl_priority) && !empty($rtwwdpdl_priority))
		{
			$i = 0;
			$id = 1;
			$rtwwdpdl_nam = array();
			foreach ($rtwwdpdl_priority as $key => $value)
			{
				$rtwwdpdl_set_data = array();
				if ($value == 'pro_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$rtwwdpdl_rule = get_option('rtwwdpdl_single_prod_rule');
					$iin = 0;
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['from'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_min'];
							$rtwwdpdl_set_data['rules'][$i]['to'] = '';
							$rtwwdpdl_set_data['rules'][$i]['type'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_discount_type'];
							$rtwwdpdl_set_data['rules'][$i]['amount'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_discount_value'];
							$i++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
							$id++;
							$iin++;
						}
					}
				}
				elseif ($value == 'cart_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$rtwwdpdl_rule = get_option('rtwwdpdl_cart_rule');
					$iin = 0;
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['from'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_min'];
							$rtwwdpdl_set_data['rules'][$i]['to'] =  $rtwwdpdl_rule[$iin]['rtwwdpdl_max'];
							$rtwwdpdl_set_data['rules'][$i]['type'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_discount_type'];
							$rtwwdpdl_set_data['rules'][$i]['amount'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_discount_value'];
							$i++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
							$iin++;
						}
					}
				}
				elseif ($value == 'cat_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$iin = 0;
					$rtwwdpdl_rule = get_option('rtwwdpdl_single_cat_rule');
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['from'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_min_cat'];
							$rtwwdpdl_set_data['rules'][$i]['to'] =  $rtwwdpdl_rule[$iin]['rtwwdpdl_max_cat'];
							$rtwwdpdl_set_data['rules'][$i]['type'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_dscnt_cat_type'];
							$rtwwdpdl_set_data['rules'][$i]['amount'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_dscnt_cat_val'];
							$rtwwdpdl_set_data['rules'][$i]['cat_id'] = isset($rtwwdpdl_rule[$iin]['category_id']) ? $rtwwdpdl_rule[$iin]['category_id'] : '';
							$i++;
							$iin++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
						}
					}
				}
				elseif ($value == 'tier_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$iin = 0;
					$rtwwdpdl_rule = get_option('rtwwdpdl_tiered_rule');
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						$rtwwdpdl_set_data = array();
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['from'] = $rtwwdpdl_rule[$iin]['quant_min'];
							$rtwwdpdl_set_data['rules'][$i]['to'] = $rtwwdpdl_rule[$iin]['quant_max'];
							$rtwwdpdl_set_data['rules'][$i]['type'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_discount_type'];
							$rtwwdpdl_set_data['rules'][$i]['discount_val'] = $rtwwdpdl_rule[$iin]['discount_val'];
							$rtwwdpdl_set_data['rules'][$i]['prod_id'] = $rtwwdpdl_rule[$iin]['products'];
							$i++;
							$iin++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
						}
					}
				}
				elseif ($value == 'bogo_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$iin = 0;
					$rtwwdpdl_rule = get_option('rtwwdpdl_bogo_rule');
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['from'] = '';
							$rtwwdpdl_set_data['rules'][$i]['to'] = '';
							$rtwwdpdl_set_data['rules'][$i]['type'] = '';
							$rtwwdpdl_set_data['rules'][$i]['amount'] = '';
							$rtwwdpdl_set_data['rules'][$i]['prod_id'] = $rtwwdpdl_rule[$iin]['product_id'];
							$rtwwdpdl_set_data['rules'][$i]['free_prod_id'] = $rtwwdpdl_rule[$iin]['rtwbogo'];
							$i++;
							$iin++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
						}
					}
				}
				elseif ($value == 'pay_rule_row')
				{
					$rtwwdpdl_nam[] = $value;
					$iin = 0;
					$rtwwdpdl_rule = get_option('rtwwdpdl_pay_method');
					$i = 0;
					if (isset($rtwwdpdl_rule) && !empty($rtwwdpdl_rule))
					{
						foreach ($rtwwdpdl_rule as $rul => $rule_no)
						{
							$rtwwdpdl_set_data['rules'][$i]['type'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_pay_discount_type'];
							$rtwwdpdl_set_data['rules'][$i]['amount'] = $rtwwdpdl_rule[$iin]['rtwwdpdl_pay_discount_value'];
							$i++;
							$iin++;
							$rtwwdpdl_obj = new RTWWDPDL_Adjustment_Set_Totals($rtwwdpdl_set_data, $rtwwdpdl_rule, $rtwwdpdl_nam);
							$this->adjustment_sets[$id] = $rtwwdpdl_obj;
						}
					}
				}
			}
		}
	}
	/**
	 * Function to perform discounting rules on cart items.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_adjust_cart($rtwwdpdl_temp_cart)
	{
		global $woocommerce;
		global $product;
		if (!is_array($rtwwdpdl_temp_cart) || empty($rtwwdpdl_temp_cart))
		{
			return;
		}
		$rtwwdpdl_setting_pri = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_cart_total = $woocommerce->cart->get_subtotal();
		$rtwwdpdl_cart_prod_count = $woocommerce->cart->cart_contents;
		$rtwwdpdl_prod_count = 0;
		if (is_array($rtwwdpdl_cart_prod_count) && !empty($rtwwdpdl_cart_prod_count))
		{
			foreach ($rtwwdpdl_cart_prod_count as $key => $value)
			{
				$rtwwdpdl_prod_count += $value['quantity'];
			}
		}
		if (is_array($rtwwdpdl_setting_pri) && !empty($rtwwdpdl_setting_pri))
		{
			$rtwwdpdl_num_decimals = apply_filters('rtwwdpdl_get_decimals', (int) get_option('woocommerce_price_num_decimals'));
			$rtwwdpdl_pricing_rules = 0;
			$rtwwdpdl_today_date = current_time('Y-m-d');
			$rtwwdpdl_user = wp_get_current_user();
			$rtwwdpdl_no_oforders = wc_get_customer_order_count(get_current_user_id());
			$rtwwdpdl_args = array(
				'customer_id' => get_current_user_id(),
				'post_status' => 'cancelled',
				'post_type' => 'shop_order',
				'return' => 'ids',
			);
			$rtwwdpdl_numorders_cancelled = 0;
			$rtwwdpdl_numorders_cancelled = count(wc_get_orders($rtwwdpdl_args));
			$rtwwdpdl_no_oforders = $rtwwdpdl_no_oforders - $rtwwdpdl_numorders_cancelled;
			$rtwwdpdl_ordrtotal = wc_get_customer_total_spent(get_current_user_id());
			if ($this->adjustment_sets && count($this->adjustment_sets))
			{
				foreach ($this->adjustment_sets as $set_id => $set)
				{
					$rtwwdpdl_pricing_rules++;
					$rtwwdpdl_matched           = false;
					$rtwwdpdl_pricing_rules     = $set->rtwwdpdl_pricing_rules;
					if (is_array($rtwwdpdl_pricing_rules) && sizeof($rtwwdpdl_pricing_rules) > 0)
					{
						$i = 0;
						foreach ($rtwwdpdl_pricing_rules as $rule)
						{
							if ($rtwwdpdl_setting_pri['rtw_offer_select'] == 'rtw_first_match')
							{
								if (is_array($this->rtwwdpdl_priority) && !empty($this->rtwwdpdl_priority))
								{
									foreach ($this->rtwwdpdl_priority as $kval)
									{
										if ($kval == 'pro_rule_row')
										{
											$rtwwdpdl_pro_rul = get_option('rtwwdpdl_single_prod_rule');
											if (!is_array($rtwwdpdl_pro_rul) || empty($rtwwdpdl_pro_rul))
											{
												continue 1;
											}
											foreach ($rtwwdpdl_pro_rul as $pro => $rul)
											{
												$rtwwdpdl_matched = true;
												if ($rul['rtwwdpdl_single_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_single_to_date'] < $rtwwdpdl_today_date)
												{
													continue 1;
												}
												////////////////////////////////
												$all_ids = array();
												$total_quantities = array();
												$total_prices = array();
												$total_weightss = array();
												if ($rul['rtwwdpdl_rule_on'] == 'rtwwdpd_multiple_products')
												{
													if (isset($rul['rtwwdpd_condition']) && $rul['rtwwdpd_condition'] == 'rtwwdpd_and')
													{
														foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
														{
															if (!in_array($cart_item['data']->get_id(), $all_ids))
															{
																$all_ids[] = $cart_item['data']->get_id();
															}
															if (!empty($cart_item['data']->get_parent_id()) && !in_array($cart_item['data']->get_parent_id(), $all_ids))
															{
																$all_ids[] = $cart_item['data']->get_parent_id();
															}
															if (in_array($cart_item['data']->get_id(), $rul['multiple_product_ids']))
															{
																if (!array_key_exists($cart_item['data']->get_id(), $total_quantities))
																{
																	$total_quantities[$cart_item['data']->get_id()] = $cart_item['quantity'];
																	$total_prices[$cart_item['data']->get_id()] = ($cart_item['quantity'] * $cart_item['data']->get_price());
																	$total_weightss[$cart_item['data']->get_id()] = ($cart_item['quantity'] * $cart_item['data']->get_weight());
																}
																else
																{
																	$total_quantities[$cart_item['data']->get_id()] = $total_quantities[$cart_item['data']->get_id()] + $cart_item['quantity'];
																	$total_prices[$cart_item['data']->get_id()] = $total_prices[$cart_item['data']->get_id()] + ($cart_item['quantity'] * $cart_item['data']->get_price());
																	$total_weightss[$cart_item['data']->get_id()] = $total_weightss[$cart_item['data']->get_id()] + ($cart_item['quantity'] * $cart_item['data']->get_weight());
																}
															}
															if (in_array($cart_item['data']->get_parent_id(), $rul['multiple_product_ids']))
															{
																if (array_key_exists($cart_item['data']->get_parent_id(), $total_quantities))
																{
																	$total_quantities[$cart_item['data']->get_parent_id()] = $total_quantities[$cart_item['data']->get_parent_id()] + $cart_item['quantity'];
																	$total_prices[$cart_item['data']->get_parent_id()] = $total_prices[$cart_item['data']->get_parent_id()] + ($cart_item['quantity'] * $cart_item['data']->get_price());
																	$total_weightss[$cart_item['data']->get_parent_id()] = $total_weightss[$cart_item['data']->get_parent_id()] + ($cart_item['quantity'] * $cart_item['data']->get_weight());
																}
																else
																{
																	$total_quantities[$cart_item['data']->get_parent_id()] = $cart_item['quantity'];
																	$total_prices[$cart_item['data']->get_parent_id()] = ($cart_item['quantity'] * $cart_item['data']->get_price());
																	$total_weightss[$cart_item['data']->get_parent_id()] = ($cart_item['quantity'] * $cart_item['data']->get_weight());
																}
															}
														}
														$reslt = array_diff($rul['multiple_product_ids'], $all_ids);
														if (!empty($reslt))
														{
															continue;
														}
													}
												}
												////////////////////////////////
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													$product = $cart_item['data'];
													if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
													{
														if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
														{
															continue 1;
														}
													}
													$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
													if ($rtwwdpdl_discounted)
													{
														$rtwwdpdl_d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
														if (in_array('advanced_totals', $rtwwdpdl_d['by']))
														{
															continue 1;
														}
													}
													$rtwwdpdl_original_price = $this->rtw_get_price_to_discount($cart_item, $cart_item_key, apply_filters('rtwwdpdl_stack_order_totals', false));
													if ($rtwwdpdl_original_price)
													{
														$rtwwdpdl_amount = apply_filters('rtwwdpdl_get_rule_amount', $rule['amount'], $rule, $cart_item, $this);
														if ($rul['rtwwdpdl_rule_on'] == 'rtwwdpd_products' || $rul['rtwwdpdl_rule_on'] == 'rtwwdpd_cart')
														{
															if ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
															{
																if ($cart_item['quantity'] < $rul['rtwwdpdl_min'] || $cart_item['quantity'] > $rul['rtwwdpdl_max'])
																{
																	continue 1;
																}
															}
															elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
															{
																if ($cart_item['data']->get_price() < $rul['rtwwdpdl_min'] || $cart_item['data']->get_price() > $rul['rtwwdpdl_max'])
																{
																	continue 1;
																}
															}
															elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
															{
																if ($cart_item['data']->get_weight() < $rul['rtwwdpdl_min'] || $cart_item['data']->get_weight() > $rul['rtwwdpdl_max'])
																{
																	continue 1;
																}
															}
														}
														elseif ($rul['rtwwdpdl_rule_on'] == 'rtwwdpd_multiple_products')
														{
															if (isset($rul['rtwwdpd_condition']) && $rul['rtwwdpd_condition'] == 'rtwwdpd_and')
															{
																// $total_quantities = array();
																// $total_prices = array();
																// $total_weightss = array();
																if ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
																{
																	$total_quant = 0;
																	if (is_array($total_quantities) && !empty($total_quantities))
																	{
																		foreach ($total_quantities as $q => $qnt)
																		{
																			$total_quant += $qnt;
																		}
																	}
																	if (isset($total_quant) && $total_quant < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																}
																elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
																{
																	$total_prz = 0;
																	if (is_array($total_prices) && !empty($total_prices))
																	{
																		foreach ($total_prices as $q => $pri)
																		{
																			$total_prz += $pri;
																		}
																	}
																	if ($total_prz < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																	if (isset($rul['rtwwdpd_max']) && !empty($rul['rtwwdpd_max']) && $total_prz > $rul['rtwwdpd_max'])
																	{
																		continue 1;
																	}
																}
																elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
																{
																	$total_weigh = 0;
																	if (is_array($total_weightss) && !empty($total_weightss))
																	{
																		foreach ($total_weightss as $q => $we)
																		{
																			$total_weigh += $we;
																		}
																	}
																	if ($total_weigh < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																	if (isset($rul['rtwwdpd_max']) && $rul['rtwwdpd_max'] != '' && $total_weigh > $rul['rtwwdpd_max'])
																	{
																		continue 1;
																	}
																}
															}
															else
															{
																if ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
																{
																	if ($cart_item['quantity'] < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																	if (isset($rul['rtwwdpd_max']) && $rul['rtwwdpd_max'] != '' && $cart_item['quantity'] > $rul['rtwwdpd_max'])
																	{
																		continue 1;
																	}
																}
																elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
																{
																	if ($cart_item['data']->get_price() < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																	$total_cost = ($cart_item['data']->get_price() * $cart_item['quantity']);
																	if (isset($rul['rtwwdpd_max']) && !empty($rul['rtwwdpd_max']) && $total_cost > $rul['rtwwdpd_max'])
																	{
																		continue 1;
																	}
																}
																elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
																{
																	if ($cart_item['data']->get_weight() < $rul['rtwwdpdl_min'])
																	{
																		continue 1;
																	}
																	if (isset($rul['rtwwdpd_max']) && $rul['rtwwdpd_max'] != '' && $cart_item['data']->get_weight() > $rul['rtwwdpd_max'])
																	{
																		continue 1;
																	}
																}
															}
														}
														$rtwwdpd_parent_id = $cart_item['data']->get_parent_id();
														if ($rul['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
														{
															$rtwwdpdl_amount = $rtwwdpdl_amount / 100;
															$rtwwdpdl_discnted_val = (floatval($rtwwdpdl_amount) * $rtwwdpdl_original_price);
															if ($rtwwdpdl_discnted_val > $rul['rtwwdpdl_max_discount'])
															{
																$rtwwdpdl_discnted_val = $rul['rtwwdpdl_max_discount'];
															}
															$rtwwdpdl_price_adjusted = (floatval($rtwwdpdl_original_price) - $rtwwdpdl_discnted_val);
															// if(isset($rul['product_id']))
															// {	
															// if($rul['product_id'] == $cart_item['product_id'])
															if ($rul['rtwwdpdl_rule_on'] == 'rtwwdpdl_products')
															{
																if (isset($rul['rtwwdpdl_exclude_sale']))
																{
																	if (!$cart_item['data']->is_on_sale())
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		return;
																	}
																}
																else
																{
																	Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																	return;
																}
															}
															// }
															elseif ($rul['rtwwdpdl_rule_on'] == 'rtwwdpdl_cart')
															{
																if (isset($rul['rtwwdpdl_exclude_sale']))
																{
																	if (!$cart_item['data']->is_on_sale())
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																	}
																}
																else
																{
																	Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																}
															}
															elseif ($rul['rtwwdpdl_rule_on'] == 'rtwwdpd_multiple_products')
															{
																if (in_array($cart_item['data']->get_id(), $rul['multiple_product_ids']) || in_array($cart_item['data']->get_parent_id(), $rul['multiple_product_ids']))
																{
																	if (isset($rul['rtwwdpd_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																			// return;
																		}
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		// return;
																	}
																}
															}
														}
														else
														{
															if ($rtwwdpdl_amount > $rul['rtwwdpdl_max_discount'])
															{
																$rtwwdpdl_amount = $rul['rtwwdpdl_max_discount'];
															}
															$rtwwdpdl_price_adjusted = ($rtwwdpdl_original_price - $rtwwdpdl_amount);
															if (isset($rul['product_id']))
															{
																if ($rul['product_id'] == $cart_item['product_id'])
																{
																	if (isset($rul['rtwwdpdl_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																			return;
																		}
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		return;
																	}
																}
															}
															elseif ($rul['rtwwdpdl_rule_on'] == 'rtwwdpdl_cart')
															{
																if (isset($rul['rtwwdpdl_exclude_sale']))
																{
																	if (!$cart_item['data']->is_on_sale())
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																	}
																}
																else
																{
																	Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																}
															}
														}
													}
												}
											}
										}
										elseif ($kval == 'cat_rule_row')
										{
											$check = 0;
											$rtwwdpdl_pro_rul = get_option('rtwwdpdl_single_cat_rule');
											if (!is_array($rtwwdpdl_pro_rul) || empty($rtwwdpdl_pro_rul))
											{
												continue 1;
											}
											foreach ($rtwwdpdl_pro_rul as $pro => $rul)
											{
												$rtwwdpdl_total_weight = 0;
												$rtwwdpdl_total_price = 0;
												$rtwwdpdl_total_quantity = 0;
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													if (isset($cart_item['variation_id']) && !empty($cart_item['variation_id']))
													{
														$rtwwdpdl_catids = wp_get_post_terms($cart_item['data']->get_parent_id(), 'product_cat', array('fields' => 'ids'));
													}
													else
													{
														$rtwwdpdl_catids = wp_get_post_terms($cart_item['data']->get_id(), 'product_cat', array('fields' => 'ids'));
													}
													if (is_array($rtwwdpdl_catids) && !empty($rtwwdpdl_catids) && in_array($rul['category_id'], $rtwwdpdl_catids))
													{
														if ($cart_item['data']->get_weight() != '')
														{
															$rtwwdpdl_total_weight += $cart_item['quantity'] * $cart_item['data']->get_weight();
														}
														$rtwwdpdl_total_price += $cart_item['quantity'] * $cart_item['data']->get_price();
														$rtwwdpdl_total_quantity += $cart_item['quantity'];
													}
												}
												$rtwwdpdl_matched = true;
												if (!isset($rul['rtwwdpdl_from_date']))
												{
													continue 1;
												}
												if (!isset($rul['rtwwdpdl_to_date']))
												{
													continue 1;
												}
												if ($rul['rtwwdpdl_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_to_date'] < $rtwwdpdl_today_date)
												{
													continue 1;
												}
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													if (isset($rul['product_exe_id']) && $rul['product_exe_id'] == $cart_item['product_id'])
													{
														continue 1;
													}
													$product = $cart_item['data'];
													$rtwwdpdl_prod_id = $this->rtwwdpdl_get_prod_cat_ids($product);
													$item_weight = $cart_item['data']->get_weight();
													if (isset($item_weight) && !empty($item_weight))
													{
														$item_weight = $cart_item['data']->get_weight();
													}
													else
													{
														$item_weight = 1;
													}
													if ($rul['rtwwdpdl_check_for_cat'] == 'rtwwdpdl_quantity')
													{
														if ($rtwwdpdl_total_quantity < $rul['rtwwdpdl_min_cat'])
														{
															continue;
														}
														if (isset($cart_item['rtwwdpdl_max_cat']) && $cart_item['rtwwdpdl_max_cat'] != '')
														{
															if ($cart_item['rtwwdpdl_max_cat'] < $rtwwdpdl_total_quantity)
															{
																continue;
															}
														}
													}
													elseif ($rul['rtwwdpdl_check_for_cat'] == 'rtwwdpdl_price')
													{
														if ($rtwwdpdl_total_price < $rul['rtwwdpdl_min_cat'])
														{
															continue;
														}
														if (isset($cart_item['rtwwdpdl_max_cat']) && $cart_item['rtwwdpdl_max_cat'] != '')
														{
															if ($cart_item['rtwwdpdl_max_cat'] < $rtwwdpdl_total_price)
															{
																continue;
															}
														}
													}
													else
													{
														if ($rtwwdpdl_total_weight < $rul['rtwwdpdl_min_cat'])
														{
															continue;
														}
														if (isset($cart_item['rtwwdpdl_max_cat']) && $cart_item['rtwwdpdl_max_cat'] != '')
														{
															if ($cart_item['rtwwdpdl_max_cat'] < $rtwwdpdl_total_weight)
															{
																continue;
															}
														}
													}
													if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
													{
														if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
														{
															continue;
														}
													}
													$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
													if ($rtwwdpdl_discounted)
													{
														$rtwwdpdl_d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
														if (in_array('advanced_totals', $rtwwdpdl_d['by']))
														{
															continue;
														}
													}
													$rtwwdpdl_original_price = $this->rtw_get_price_to_discount($cart_item, $cart_item_key, apply_filters('rtwwdpdl_stack_order_totals', false));
													if ($rtwwdpdl_original_price)
													{
														$rtwwdpdl_amount = apply_filters('rtwwdpdl_get_rule_amount', $rul['rtwwdpdl_dscnt_cat_val'], $rule, $cart_item, $this);
														if ($rul['rtwwdpdl_dscnt_cat_type'] == 'rtwwdpdl_discount_percentage')
														{
															$rtwwdpdl_amount = $rtwwdpdl_amount / 100;
															$rtwwdpdl_discnted_val = (floatval($rtwwdpdl_amount) * $rtwwdpdl_original_price);
															if ($rtwwdpdl_discnted_val > $rul['rtwwdpdl_max_discount'])
															{
																$rtwwdpdl_discnted_val = $rul['rtwwdpdl_max_discount'];
															}
															$rtwwdpdl_price_adjusted = (floatval($rtwwdpdl_original_price) - $rtwwdpdl_discnted_val);
															if (isset($rul['category_id']))
															{
																$rtwwdpdl_catids = $cart_item['data']->get_category_ids();
																if (is_array($rtwwdpdl_catids) && !empty($rtwwdpdl_catids) && in_array($rul['category_id'], $rtwwdpdl_catids))
																{
																	if (isset($rul['rtwwdpdl_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		}
																		$check = 1;
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		$check = 1;
																	}
																}
															}
														}
														else
														{
															if ($rtwwdpdl_amount > $rul['rtwwdpdl_max_discount'])
															{
																$rtwwdpdl_amount = $rul['rtwwdpdl_max_discount'];
															}
															$rtwwdpdl_price_adjusted = ($rtwwdpdl_original_price - $rtwwdpdl_amount);
															if (isset($rul['category_id']))
															{
																$rtwwdpdl_catids = $cart_item['data']->get_category_ids();
																if (is_array($rtwwdpdl_catids) && !empty($rtwwdpdl_catids) && in_array($rul['category_id'], $rtwwdpdl_catids))
																{
																	if (isset($rul['rtwwdpdl_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																			$check = 1;
																		}
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		$check = 1;
																	}
																}
															}
														}
													}
												}
											}
											if ($check == 1)
											{
												return;
											}
										}
										elseif ($kval == 'tier_rule_row')
										{
											$rtwwdpdl_pro_rul = get_option('rtwwdpdl_tiered_rule');
											if (!is_array($rtwwdpdl_pro_rul) || empty($rtwwdpdl_pro_rul))
											{
												continue 1;
											}
											foreach ($rtwwdpdl_pro_rul as $pro => $rul)
											{
												if ($rul['rtwwdpdl_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_to_date'] < $rtwwdpdl_today_date)
												{
													continue;
												}
												$rtwwdpdl_user_role = isset($rul['rtwwdpdl_select_roles']) ?  $rul['rtwwdpdl_select_roles'] : '';
												$rtwwdpdl_role_matched = false;
												if (is_array($rtwwdpdl_user_role) && !empty($rtwwdpdl_user_role))
												{
													foreach ($rtwwdpdl_user_role as $rol => $role)
													{
														if ($role == 'all')
														{
															$rtwwdpdl_role_matched = true;
														}
														if (in_array($role, (array) $rtwwdpdl_user->roles))
														{
															$rtwwdpdl_role_matched = true;
														}
													}
												}
												if ($rtwwdpdl_role_matched == false)
												{
													continue;
												}
												if (isset($rul['rtwwdpdl_min_orders']) && $rul['rtwwdpdl_min_orders'] > $rtwwdpdl_no_oforders)
												{
													continue;
												}
												if (isset($rul['rtwwdpdl_min_spend']) && $rul['rtwwdpdl_min_spend'] > $rtwwdpdl_ordrtotal)
												{
													continue;
												}
												$rtwwdpdl_matched = true;
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													$product = $cart_item['data'];
													$rtwwdpdl_prod_id = $this->rtwwdpdl_get_product_ids($product);
													foreach ($rtwwdpdl_pro_rul as $id => $id_val)
													{
														if ($id_val['products'][0] == $rtwwdpdl_prod_id)
														{
															$i = $id;
														}
													}
													if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
													{
														if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
														{
															continue;
														}
													}
													$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
													if ($rtwwdpdl_discounted)
													{
														$rtwwdpdl_d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
														if (in_array('advanced_totals', $rtwwdpdl_d['by']))
														{
															continue;
														}
													}
													$rtwwdpdl_original_price = $this->rtw_get_price_to_discount($cart_item, $cart_item_key, apply_filters('rtwwdpdl_stack_order_totals', false));
													if ($rtwwdpdl_original_price)
													{
														$pp = 0;
														$rtwwdpdl_amount = 0;
														if (is_array($rul['discount_val']) && !empty($rul['discount_val']))
														{
															foreach ($rul['discount_val'] as $dis => $disval)
															{
																$rtwwdpdl_amount = apply_filters('rtwwdpdl_get_rule_amount', $rul['discount_val'][$dis], $rule, $cart_item, $this);
																if ($rul['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
																{
																	$rtwwdpdl_amount = $rtwwdpdl_amount / 100;
																	$rtwwdpdl_discnted_val = (floatval($rtwwdpdl_amount) * $rtwwdpdl_original_price);
																	if ($rtwwdpdl_discnted_val > $rul['rtwwdpdl_max_discount'])
																	{
																		$rtwwdpdl_discnted_val = $rul['rtwwdpdl_max_discount'];
																	}
																	$rtwwdpdl_price_adjusted = (floatval($rtwwdpdl_original_price) - $rtwwdpdl_discnted_val);
																	if ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['quantity'] && $rul['quant_max'][$dis] >= $cart_item['quantity'])
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																	elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['data']->get_price() && $rul['quant_max'][$dis] >= $cart_item['data']->get_price())
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																	else
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['data']->get_weight() && $rul['quant_max'][$dis] >= $cart_item['data']->get_weight())
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																}
																else
																{
																	if ($rtwwdpdl_amount > $rul['rtwwdpdl_max_discount'])
																	{
																		$rtwwdpdl_amount = $rul['rtwwdpdl_max_discount'];
																	}
																	$rtwwdpdl_price_adjusted = ($rtwwdpdl_original_price - $rtwwdpdl_amount);
																	if ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['quantity'] && $rul['quant_max'][$dis] >= $cart_item['quantity'])
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																	elseif ($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['data']->get_price() && $rul['quant_max'][$dis] >= $cart_item['data']->get_price())
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																	else
																	{
																		if ($rul['quant_min'][$dis] <= $cart_item['data']->get_weight() && $rul['quant_max'][$dis] >= $cart_item['data']->get_weight())
																		{
																			if ($rul['products'][0] == $cart_item['product_id'])
																			{
																				if (isset($rul['rtwwdpdl_exclude_sale']))
																				{
																					if (!$cart_item['data']->is_on_sale())
																					{
																						Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																						return;
																					}
																				}
																				else
																				{
																					Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																					return;
																				}
																			}
																		}
																	}
																}
																$pp++;
															}
														}
													}
												}
											}
										}
										elseif ($kval == 'bogo_rule_row')
										{
											$rtwwdpdl_pro_rul = get_option('rtwwdpdl_bogo_rule');
											if (!is_array($rtwwdpdl_pro_rul) || empty($rtwwdpdl_pro_rul))
											{
												continue 1;
											}
											foreach ($rtwwdpdl_pro_rul as $pro => $rul)
											{
												$rtwwdpdl_matched = true;
												if ($rul['rtwwdpdl_bogo_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_bogo_to_date'] < $rtwwdpdl_today_date)
												{
													continue;
												}
												$rtw_curnt_dayname = date("N");
												$rtwwdpdl_day_waise_rule = false;
												if (isset($rul['rtwwdpdl_enable_day_bogo']) && $rul['rtwwdpdl_enable_day_bogo'] == 'yes')
												{
													if (isset($rul['rtwwdpdl_select_day_bogo']) && !empty($rul['rtwwdpdl_select_day_bogo']))
													{
														if ($rul['rtwwdpdl_select_day_bogo'] == $rtw_curnt_dayname)
														{
															$rtwwdpdl_day_waise_rule = true;
														}
													}
													if ($rtwwdpdl_day_waise_rule == false)
													{
														continue;
													}
												}
												$rtw_pro_idss = array();
												$rtw_p_c 	= array();
												$temp_pro_ids = array();
												foreach ($rtwwdpdl_pro_rul as $ky => $va)
												{
													foreach ($va['combi_quant'] as $ke => $valu)
													{
														$rtw_p_c[] = $valu;
													}
												}
												foreach ($rul['product_id'] as $pro => $proid)
												{
													$rtw_pro_idss[] = $proid;
												}
												foreach ($rtwwdpdl_temp_cart as $cart_item)
												{
													$temp_pro_ids[] = $cart_item['product_id'];
												}
												$rtw_result = array_diff($rtw_pro_idss, $temp_pro_ids);
												if (!empty($rtw_result))
												{
													continue;
												}
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													$product = $cart_item['data'];
													$rtwwdpdl_prod_id = $this->rtwwdpdl_get_product_ids($product);
													if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
													{
														if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
														{
															continue;
														}
													}
													$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
													if ($rtwwdpdl_discounted)
													{
														$d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
														if (in_array('advanced_totals', $d['by']))
														{
															continue;
														}
													}
													$rtwwdpdl_free_prod = $rul['rtwbogo'];
													$rtwwdpdl_p_id = $rul['product_id'][0];
													$cart = $woocommerce->cart;
													if (isset($rul['product_id'][0]))
													{
														$rtw_free_qunt = 0;
														if ($rul['product_id'][0] == $cart_item['product_id'] && $rul['combi_quant'][0] <= $cart_item['quantity'])
														{
															if ($rul['combi_quant'][0] < $cart_item['quantity'])
															{
																$rtw_free_qunt = floor($cart_item['quantity'] / $rul['combi_quant'][0]);
															}
															else
															{
																$rtw_free_qunt = $rul['bogo_quant_free'][0];
															}
															$rtwwdpdl_get_settings = get_option('rtwwdpdl_setting_priority');
															$i = 0;
															$free_i = 0;
															if (is_array($rul['rtwbogo']) && !empty($rul['rtwbogo']))
															{
																foreach ($rul['rtwbogo'] as $k => $val)
																{
																	$rtw_free_p_id = $val;
																	$product_data = wc_get_product($rtw_free_p_id);
																	$rtw_prod_cont = $rul['combi_quant'][$k];
																	if ($rul['product_id'][$free_i] == $rtwwdpdl_p_id)
																	{
																		if ($rtwwdpdl_get_settings['rtw_auto_add_bogo'] == 'rtw_yes')
																		{
																			$found 		= false;
																			//check if product already in cart
																			if (sizeof(WC()->cart->get_cart()) > 0)
																			{
																				foreach (WC()->cart->get_cart() as $cart_item_key => $values)
																				{
																					$_product = $values['data'];
																					if ($_product->get_id() == 'rtw_free_prod' . $rtw_free_p_id)
																						$found = true;
																				}
																				// if product not found, add it
																				if (!$found)
																				{
																					$cart_item_key = 'rtw_free_prod' . $rtw_free_p_id;
																					$cart->cart_contents[$cart_item_key] = array(
																						'product_id' => $rtw_free_p_id,
																						'variation_id' => 0,
																						'variation' => array(),
																						'quantity' => $rtw_free_qunt,
																						'data' => $product_data,
																						'line_total' => 0
																					);
																					return;
																				}
																			}
																		}
																	}
																	else
																	{
																		if ($rtwwdpdl_get_settings['rtw_auto_add_bogo'] == 'rtw_yes')
																		{
																			$found 		= false;
																			//check if product already in cart
																			if (sizeof(WC()->cart->get_cart()) > 0)
																			{
																				foreach (WC()->cart->get_cart() as $cart_item_key => $values)
																				{
																					$_product = $values['data'];
																					if ($_product->get_id() == 'rtw_free_prod' . $rtw_free_p_id)
																						$found = true;
																					// if product not found, add it
																					if ($found && $cart_item_key == ('rtw_free_prod' . $rtw_free_p_id))
																					{
																						$cart_item_key = 'rtw_free_prod' . $rtw_free_p_id;
																						$cart->cart_contents[$cart_item_key]['quantity'] = 1;
																					}
																					elseif (!$found)
																					{
																						$cart_item_key = 'rtw_free_prod' . $rtw_free_p_id;
																						$cart->cart_contents[$cart_item_key] = array(
																							'product_id' => $rtw_free_p_id,
																							'variation_id' => 0,
																							'variation' => array(),
																							'quantity' => $rtw_free_qunt,
																							'data' => $product_data,
																							'line_total' => 0
																						);
																						return;
																					}
																				}
																			}
																		}
																	}
																	$free_i++;
																}
															}
															$free_i = 0;
															$i++;
														}
													}
												}
											}
										}
										elseif ($kval == 'pay_rule_row')
										{
											$rtwwdpdl_pay_rul = get_option('rtwwdpdl_pay_method');
											if (!is_array($rtwwdpdl_pay_rul) || empty($rtwwdpdl_pay_rul))
											{
												continue 1;
											}
											foreach ($rtwwdpdl_pay_rul as $pay => $rul)
											{
												$selectedRoles = $rule['rtwwdpdl_select_roles'] ?? [];
												$rtwwdpdl_role_matched = false;
												foreach ($selectedRoles as $role)
												{
													if ($role === 'all')
													{
														$rtwwdpdl_role_matched = true;
														break;
													}
													if (in_array($role, $user->roles))
													{
														$rtwwdpdl_role_matched = true;
														break;
													}
												}
												//
												// if ($rtwwdpdl_role_matched == false)
												// {
												// 	continue;
												// }
												if (isset($rul['rtwwdpdl_min_prod_cont']) && $rul['rtwwdpdl_min_prod_cont'] > $rtwwdpdl_prod_count)
												{
													continue;
												}
												if (isset($rul['rtwwdpdl_min_spend']) && $rul['rtwwdpdl_min_spend'] > $rtwwdpdl_cart_total)
												{
													continue;
												}
												$rtwwdpdl_matched = true;
												if ($rul['rtwwdpdl_pay_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_pay_to_date'] < $rtwwdpdl_today_date)
												{
													continue;
												}
												foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
												{
													$product = $cart_item['data'];
													$rtwwdpdl_process_discounts = apply_filters('rtwwdpdl_process_product_discounts', true, $cart_item['data'], 'advanced_totals', $this, $cart_item);
													if (!$rtwwdpdl_process_discounts)
													{
														continue;
													}
													if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
													{
														if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
														{
															continue;
														}
													}
													$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
													if ($rtwwdpdl_discounted)
													{
														$d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
														if (in_array('advanced_totals', $d['by']))
														{
															continue;
														}
													}
													$rtwwdpdl_original_price = $this->rtw_get_price_to_discount($cart_item, $cart_item_key, apply_filters('rtwwdpdl_stack_order_totals', false));
													if ($rtwwdpdl_original_price)
													{
														$rtwwdpdl_amount = apply_filters('rtwwdpdl_get_rule_amount', $rul['rtwwdpdl_pay_discount_value'], $rule, $cart_item, $this);
														$rtwwdpdl_cart_prod_count = count(WC()->cart->get_cart());
														$rtwwdpdl_cart_total = $woocommerce->cart->get_subtotal();
														$rtwwdpdl_chosen_gateway = WC()->session->chosen_payment_method;
														$rtwwdpdl_dscnt_on = $rul['allowed_payment_methods'];
														if ($rtwwdpdl_chosen_gateway == $rtwwdpdl_dscnt_on)
														{
															$rtwwdpdl_min_pro_count = isset($rul['rtwwdpdl_min_prod_cont']) ?? 0;
															$rtwwdpdl_min_spend = isset($rul['rtwwdpdl_min_spend']) ?? 0;
															if ($rtwwdpdl_min_pro_count <= $rtwwdpdl_cart_prod_count && $rtwwdpdl_min_spend <= $rtwwdpdl_cart_total)
															{
																if ($rul['rtwwdpdl_pay_discount_type'] == 'rtwwdpdl_discount_percentage')
																{
																	$rtwwdpdl_amount = $rtwwdpdl_amount / 100;
																	$rtwwdpdl_discnted_val = (floatval($rtwwdpdl_amount) * $rtwwdpdl_original_price);
																	if ($rtwwdpdl_discnted_val > $rul['rtwwdpdl_pay_max_discount'])
																	{
																		$rtwwdpdl_discnted_val = $rul['rtwwdpdl_pay_max_discount'];
																	}
																	$rtwwdpdl_price_adjusted = (floatval($rtwwdpdl_original_price) - $rtwwdpdl_discnted_val);
																	if (isset($rul['rtwwdpdl_pay_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																			return;
																		}
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		return;
																	}
																}
																else
																{
																	if ($rtwwdpdl_amount > $rul['rtwwdpdl_pay_max_discount'])
																	{
																		$rtwwdpdl_amount = $rul['rtwwdpdl_pay_max_discount'];
																	}
																	$rtwwdpdl_new_price = $rtwwdpdl_amount / $rtwwdpdl_prod_count;
																	$rtwwdpdl_price_adjusted = $rtwwdpdl_original_price - $rtwwdpdl_new_price;
																	if (isset($rul['rtwwdpdl_pay_exclude_sale']))
																	{
																		if (!$cart_item['data']->is_on_sale())
																		{
																			Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																			return;
																		}
																	}
																	else
																	{
																		Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
																		return;
																	}
																}
															}
														}
													}
												}
											}
										}
										$i++;
									}
								}
							}
						}
					}
					//Only process the first matched rule set
					if ($rtwwdpdl_matched && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
					{
						return;
					}
				}
			}
		}
	}
	/**
	 * Function to get cart total payable amount.
	 *
	 * @since    1.0.0
	 */
	private function rtwwdpdl_get_cart_total($rtwwdpdl_set)
	{
		global $woocommerce;
		$rtwwdpdl_quantity  = 0;
		if (is_array(WC()->cart->cart_contents) && !empty(WC()->cart->cart_contents))
		{
			foreach (WC()->cart->cart_contents as $cart_item)
			{
				$product = $cart_item['data'];
				if ($collector['type'] == 'cat')
				{
					if (!isset($collector['args']))
					{
						return 0;
					}
					$rtwwdpdl_terms = $this->rtwwdpdl_get_product_category_ids($product);
					if (count(array_intersect($collector['args']['cats'], $rtwwdpdl_terms)) > 0)
					{
						$rtwwdpdl_q = $cart_item['quantity'] ? $cart_item['quantity'] : 1;
						if (isset($cart_item['discounts']) && isset($cart_item['discounts']['by']) && $cart_item['discounts']['by'][0] == $this->module_id)
						{
							$rtwwdpdl_quantity += floatval($cart_item['discounts']['price_base']) * $rtwwdpdl_q;
						}
						else
						{
							$rtwwdpdl_quantity += $cart_item['data']->get_price() * $rtwwdpdl_q;
						}
					}
				}
				else
				{
					$rtwwdpdl_process_discounts = apply_filters('rtwwdpdl_process_product_discounts', true, $cart_item['data'], 'advanced_totals', $this, $cart_item);
					if ($rtwwdpdl_process_discounts)
					{
						$rtwwdpdl_q = $cart_item['quantity'] ? $cart_item['quantity'] : 1;
						if (isset($cart_item['discounts']) && isset($cart_item['discounts']['by']) && $cart_item['discounts']['by'] == $this->module_id)
						{
							$rtwwdpdl_quantity += floatval($cart_item['discounts']['price_base']) * $rtwwdpdl_q;
						}
						else
						{
							$rtwwdpdl_quantity += $cart_item['data']->get_price() * $rtwwdpdl_q;
						}
					}
				}
			}
		}
		return $rtwwdpdl_quantity;
	}
	/**
	 * Function to get product category ids.
	 *
	 * @since    1.0.0
	 */
	public static function rtwwdpdl_get_product_category_ids($rtwwdpdl_product)
	{
		if (empty($rtwwdpdl_product))
		{
			return array();
		}
		$rtwwdpdl_id    = isset($rtwwdpdl_product->variation_id) ? $rtwwdpdl_product->parent->get_id() : $rtwwdpdl_product->get_id();
		$rtwwdpdl_terms = wp_get_post_terms($rtwwdpdl_id, 'product_cat', array('fields' => 'ids'));
		return $rtwwdpdl_terms;
	}
	/**
	 * Function to get product ids.
	 *
	 * @since    1.0.0
	 */
	public static function rtwwdpdl_get_product_ids($rtwwdpdl_product)
	{
		if (empty($rtwwdpdl_product))
		{
			return array();
		}
		$rtwwdpdl_id    = isset($rtwwdpdl_product->variation_id) ? $rtwwdpdl_product->get_parent_id() : $rtwwdpdl_product->get_id();
		return $rtwwdpdl_id;
	}
	/**
	 * Function to get product category ids.
	 *
	 * @since    1.0.0
	 */
	public static function rtwwdpdl_get_prod_cat_ids($rtwwdpdl_product)
	{
		if (empty($rtwwdpdl_product))
		{
			return array();
		}
		if (isset($rtwwdpdl_product->variation_id))
		{
			$rtwwdpdl_cat = get_the_terms($rtwwdpdl_product->get_parent_id(), 'product_cat');
			$rtwwdpdl_cat_id = '';
			foreach ($rtwwdpdl_cat as $categoria)
			{
				if ($categoria->parent == 0)
				{
				}
				$rtwwdpdl_cat_id = $categoria->term_id;
			}
		}
		$rtwwdpdl_id    = isset($rtwwdpdl_product->variation_id) ? $rtwwdpdl_cat_id : $rtwwdpdl_product->get_category_ids();
		return $rtwwdpdl_id;
	}
	/**
	 * Function to perform shipping discounting rules.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_shipping_method($rtwwdpdl_temp_cart)
	{
		global $woocommerce;
		$i = 0;
		$rtwwdpdl_today_date = current_time('Y-m-d');
		$rtwwdpdl_pay_rul = get_option('rtwwdpdl_ship_method');
		$rtwwdpdl_matched = true;
		if ($rtwwdpdl_pay_rul[$i]['rtwwdpdl_ship_from_date'] > $rtwwdpdl_today_date || $rtwwdpdl_pay_rul[$i]['rtwwdpdl_ship_to_date'] < $rtwwdpdl_today_date)
		{
			return false;
		}
		if (is_array($rtwwdpdl_temp_cart) && !empty($rtwwdpdl_temp_cart))
		{
			foreach ($rtwwdpdl_temp_cart as $cart_item_key => $cart_item)
			{
				$rtwwdpdl_product = $cart_item['data'];
				if ($collector['type'] == 'cat')
				{
					$rtwwdpdl_process_discounts = false;
					$rtwwdpdl_terms             = $this->rtwwdpdl_get_product_category_ids($rtwwdpdl_product);
					if (count(array_intersect($targets, $rtwwdpdl_terms)) > 0)
					{
						$rtwwdpdl_process_discounts = apply_filters('rtwwdpdl_process_product_discounts', true, $cart_item['data'], 'advanced_totals', $this, $cart_item);
					}
				}
				else
				{
					$rtwwdpdl_process_discounts = apply_filters('rtwwdpdl_process_product_discounts', true, $cart_item['data'], 'advanced_totals', $this, $cart_item);
				}
				if (!$rtwwdpdl_process_discounts)
				{
					return false;
				}
				if (!$this->rtwwdpdl_is_cumulative($cart_item, $cart_item_key))
				{
					if ($this->rtwwdpdl_is_item_discounted($cart_item, $cart_item_key) && apply_filters('rtwwdpdl_stack_order_totals', false) === false)
					{
						return false;
					}
				}
				$rtwwdpdl_discounted = isset(WC()->cart->cart_contents[$cart_item_key]['discounts']);
				if ($rtwwdpdl_discounted)
				{
					$rtwwdpdl_d = WC()->cart->cart_contents[$cart_item_key]['discounts'];
					if (in_array('advanced_totals', $rtwwdpdl_d['by']))
					{
						return false;
					}
				}
				$rtwwdpdl_original_price = $this->rtw_get_price_to_discount($cart_item, $cart_item_key, apply_filters('rtwwdpdl_stack_order_totals', false));
				if ($rtwwdpdl_original_price)
				{
					$rtwwdpdl_amount = apply_filters('rtwwdpdl_get_rule_amount', $rule['amount'], $rule, $cart_item, $this);
					$rtwwdpdl_cart_prod_count = count(WC()->cart->get_cart());
					$rtwwdpdl_cart_total = $woocommerce->cart->get_subtotal();
					$rtwwdpdl_ship_chosen_method = WC()->session->get('chosen_shipping_methods');
					$rtwwdpdl_dscnt_on = $rtwwdpdl_pay_rul[$i]['allowed_shipping_methods'][0];
					$pos = stripos($rtwwdpdl_ship_chosen_method[0], $rtwwdpdl_dscnt_on);
					if ($pos !== false)
					{
						if ($rtwwdpdl_pay_rul[$i]['rtwwdpdl_min_prod_cont'] <= $rtwwdpdl_cart_prod_count && $rtwwdpdl_pay_rul[$i]['rtwwdpdl_min_spend'] <= $rtwwdpdl_cart_total)
						{
							if ($set->pricing_rules[$i]['type'] == 'rtwwdpdl_discount_percentage')
							{
								$rtwwdpdl_amount = $rtwwdpdl_amount / 100;
								$rtwwdpdl_price_adjusted = round(floatval($rtwwdpdl_original_price) - (floatval($rtwwdpdl_amount) * $rtwwdpdl_original_price), (int) $rtwwdpdl_num_decimals);
								Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
							}
							else
							{
								$rtwwdpdl_new_price = $rtwwdpdl_amount / $rtwwdpdl_cart_prod_count;
								$rtwwdpdl_price_adjusted = $rtwwdpdl_original_price - $rtwwdpdl_new_price;
								Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public::rtw_apply_cart_item_adjustment($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_price_adjusted, $this->module_id, $set_id);
							}
						}
					}
				}
			}
		}
	}
}
