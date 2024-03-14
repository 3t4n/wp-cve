<?php
$rtwwdpdl_get_content = get_option('rtwwdpdl_setting_priority');
$rtwwdpdl_text_to_show = 'Get [discounted] Off';
$rtwwdpdl_bogo_text = 'Buy [quantity1] [the-product] Get [quantity2] [free-product]';
$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
$rtwwdpdl_symbol = get_woocommerce_currency_symbol();
$rtwwdpdl_categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
$rtwwdpdl_cats = array();
$rtwwdpdl_weight_unit = get_option('woocommerce_weight_unit');
if (is_array($rtwwdpdl_categories) && !empty($rtwwdpdl_categories))
{
	foreach ($rtwwdpdl_categories as $cat)
	{
		$rtwwdpdl_cats[$cat->term_id] = $cat->name;
	}
}

if (is_array($rtwwdpdl_priority) && !empty($rtwwdpdl_priority))
{
	$rtwwdpdl_match = false;
	foreach ($rtwwdpdl_priority as $rule => $rule_name)
	{

		if ($rule_name == 'cat_rule_row')
		{
			if (isset($rtwwdpdl_offers['cat_rule']))
			{	
				$rtwwdpdl_rule_name = get_option('rtwwdpdl_single_cat_rule');

				if (is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name))
				{
					foreach ($rtwwdpdl_rule_name as $name)
					{
						$rtwwdpdl_date = $name['rtwwdpdl_to_date'];
						if ($rtwwdpdl_date > $rtwwdpdl_today_date)
						{
							if (isset($name['category_id']))
							{	
								$cat = $name['category_id'];
								if ($cat == $rtwwdpdl_product_cat_id && $rtwwdpdl_match == false)
								{
									if ($name['rtwwdpdl_dscnt_cat_type'] == 'rtwwdpdl_discount_percentage')
									{
										$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_dscnt_cat_val"]) ? $name["rtwwdpdl_dscnt_cat_val"] . '%' : '', $rtwwdpdl_text_to_show);

										echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
									}
									else
									{
										$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_dscnt_cat_val"]) ? $rtwwdpdl_symbol . $name["rtwwdpdl_dscnt_cat_val"] : '', $rtwwdpdl_text_to_show);

										echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
									}
									$rtwwdpdl_match = true;
									break 2;
								}
							}
						}
					}
				}
			}
		}
		elseif ($rule_name == 'pro_rule_row')
		{
			if (isset($rtwwdpdl_offers['pro_rule']))
			{
				$rtwwdpdl_rule_name = get_option('rtwwdpdl_single_prod_rule');
				if (is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name))
				{
					foreach ($rtwwdpdl_rule_name as $name)
					{

						$rtwwdpdl_date = $name['rtwwdpdl_single_to_date'];
						if ($rtwwdpdl_date > $rtwwdpdl_today_date)
						{
							if (isset($name['rtwwdpdl_rule_on']) && $name['rtwwdpdl_rule_on'] == 'rtwwdpd_multiple_products')
							{
								foreach ($name['multiple_product_ids'] as $multi_items => $items)
								{
									$rtwwdpdl_id = $items;
									if ($rtwwdpdl_id == $rtwwdpdl_prod_id && $rtwwdpdl_match == false)
									{
										if ($name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
										{
											$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $name["rtwwdpdl_discount_value"] . '%' : '', $rtwwdpdl_text_to_show);

											echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
										}
										else
										{
											$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $rtwwdpdl_symbol . $name["rtwwdpdl_discount_value"] : '', $rtwwdpdl_text_to_show);

											echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
										}
										$rtwwdpdl_match = true;
										break 2;
									}
								}
							}
							elseif (isset($name['product_id']) && $name['rtwwdpdl_rule_on'] == 'rtwwdpdl_products')
							{
								$rtwwdpdl_id = $name['product_id'];
								if ($rtwwdpdl_id == $rtwwdpdl_prod_id && $rtwwdpdl_match == false)
								{
									if ($name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
									{
										$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $name["rtwwdpdl_discount_value"] . '%' : '', $rtwwdpdl_text_to_show);

										echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
									}
									else
									{
										$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $rtwwdpdl_symbol . $name["rtwwdpdl_discount_value"] : '', $rtwwdpdl_text_to_show);

										echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
									}
									$rtwwdpdl_match = true;
									break 2;
								}
							}
							else
							{
								if ($name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
								{
									$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $name["rtwwdpdl_discount_value"] . '%' : '', $rtwwdpdl_text_to_show);

									echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
								}
								else
								{
									$rtwwdpdl_text_to_show = str_replace('[discounted]', isset($name["rtwwdpdl_discount_value"]) ? $rtwwdpdl_symbol . $name["rtwwdpdl_discount_value"] : '', $rtwwdpdl_text_to_show);

									echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_text_to_show) . '</span></div>';
								}
								$rtwwdpdl_match = true;
								break 2;
							}
						}
					}
				}
			}
		}
		elseif ($rule_name == 'bogo_rule_row')
		{

			if (isset($rtwwdpdl_offers['bogo_rule']))
			{
				$rtwwdpdl_rule_name = get_option('rtwwdpdl_bogo_rule');
				if (is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name))
				{
					foreach ($rtwwdpdl_rule_name as $ke => $name)
					{
						$rtwwdpdl_date = $name['rtwwdpdl_bogo_to_date'];

						$rtw_curnt_dayname = date("N");
						$rtwwdpdl_day_waise_rule = false;
						if (isset($name['rtwwdpdl_enable_day_bogo']) && $name['rtwwdpdl_enable_day_bogo'] == 'yes')
						{

							if (isset($name['rtwwdpdl_select_day_bogo']) && !empty($name['rtwwdpdl_select_day_bogo']))
							{
								if ($name['rtwwdpdl_select_day_bogo'] == $rtw_curnt_dayname)
								{
									$rtwwdpdl_day_waise_rule = true;
								}
							}
							if ($rtwwdpdl_day_waise_rule == false)
							{
								continue;
							}
						}

						if ($rtwwdpdl_date > $rtwwdpdl_today_date)
						{
							if (isset($name['product_id']) && is_array($name['product_id']) && !empty($name['product_id']))
							{
								foreach ($name['product_id'] as $no => $ids)
								{
									if ($ids == $rtwwdpdl_prod_id && $rtwwdpdl_match == false)
									{
										$rtwwdpdl_bogo_text = str_replace('[quantity1]', isset($name['combi_quant'][$no]) ? $name['combi_quant'][$no] : '', $rtwwdpdl_bogo_text);

										$rtwwdpdl_bogo_text = str_replace('[quantity2]', isset($name['bogo_quant_free'][$no]) ? $name['bogo_quant_free'][$no] : '', $rtwwdpdl_bogo_text);

										$rtwwdpdl_bogo_text = str_replace('[the-product]', $rtwwdpdl_product->get_name(), $rtwwdpdl_bogo_text);

										$rtwwdpdl_bogo_text = str_replace('[free-product]', isset($name['rtwbogo'][$no]) ? get_the_title($name['rtwbogo'][$no]) : '', $rtwwdpdl_bogo_text);

										echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_bogo_text) . '</span></div>';
										$rtwwdpdl_match = true;
										break 2;
									}
								}
							}
						}
					}
				}
			}
		}
		elseif ($rule_name == 'tier_rule_row')
		{	
			if (isset($rtwwdpdl_offers['tier_rule']))
			{	
				$rtwwdpdl_rule_name = get_option('rtwwdpdl_tiered_rule');
				if (is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name))
				{
					foreach ($rtwwdpdl_rule_name as $name)
					{	
						if ($name['rtwwdpdl_to_date'] > $rtwwdpdl_today_date)
						{		
							if (isset($name['products']) && is_array($name['products']) && !empty($name['products']))
							{	
								foreach ($name['products'] as $keys => $vals)
								{	
									if ($vals == $rtwwdpdl_prod_id && $rtwwdpdl_match == false)
									{	
										if ($name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
										{	
											if ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k] . '%' : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $rtwwdpdl_symbol . $va : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $rtwwdpdl_symbol . $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											elseif ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k] . '%' : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											elseif ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k] . '%' : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va . $rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] . $rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											break 2;
										}
										else
										{
											if ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $rtwwdpdl_symbol . $va : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $rtwwdpdl_symbol . $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											elseif ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											elseif ($name['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
											{
												foreach ($name['quant_min'] as $k => $va)
												{
													if ($k == 1)
													{
														continue 2;
													}
													if (!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
													{
														$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';;
													}
													else
													{
														$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
													}
													$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);
													$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title($name['products'][0]) : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va . $rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

													$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] . $rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

													echo '<div class="rtwwdpdl_show_offer"><span>' . esc_html($rtwwdpdl_tier_text_show) . '</span></div>';
												}
											}
											$rtwwdpdl_match = true;
											break 3;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
