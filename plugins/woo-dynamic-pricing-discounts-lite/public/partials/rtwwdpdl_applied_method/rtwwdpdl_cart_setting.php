<?php
global $woocommerce;
$rtwwdpdl_rule_name = get_option('rtwwdpdl_cart_rule');
$rtwwdpdl_get_setting_priority = get_option('rtwwdpdl_setting_priority');
$rtwwdpdl_weight_unit = get_option('woocommerce_weight_unit');
$rtwwdpdl_symbol = get_woocommerce_currency_symbol();
$rtwwdpdl_cart_text = isset($rtwwdpdl_get_setting_priority['rtwwdpdl_cart_text_show']) ? $rtwwdpdl_get_setting_priority['rtwwdpdl_cart_text_show'] : 'Buy from [from_quant] to [to_quant] Get [discounted] Off';

if( isset( $rtwwdpdl_get_setting_priority['rtw_offer_on_cart'] ) && $rtwwdpdl_get_setting_priority['rtw_offer_on_cart'] == 'rtw_price_yes')
{
	if( is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name) && isset($rtwwdpdl_get_setting_priority['cart_rule']) )
	{
		foreach ($rtwwdpdl_rule_name as $keys => $name) 
		{	
			$rtwwdpdl_cart_text = isset($rtwwdpdl_get_setting_priority['rtwwdpdl_cart_text_show']) ? $rtwwdpdl_get_setting_priority['rtwwdpdl_cart_text_show'] : 'Buy from [from_quant] to [to_quant] Get [discounted] Off';
			$rtwwdpdl_date = $name['rtwwdpdl_to_date'];
			if($rtwwdpdl_date > $rtwwdpdl_today_date){
				if($name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
				{
					if($name['rtwwdpdl_check_for']=='rtwwdpdl_quantity')
					{ 
						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $name['rtwwdpdl_discount_value']. '%' : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_price')
					{

						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $name['rtwwdpdl_discount_value']. '%' : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_weight')
					{
						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $name['rtwwdpdl_min'] . $rtwwdpdl_weight_unit : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $name['rtwwdpdl_max'] . $rtwwdpdl_weight_unit : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $name['rtwwdpdl_discount_value']. '%' : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_total')
					{
						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $name['rtwwdpdl_discount_value']. '%' : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
				}
				else
				{
					if($name['rtwwdpdl_check_for']=='rtwwdpdl_quantity')
					{
						if($name['rtwwdpdl_max_discount'] < $name['rtwwdpdl_discount_value'])
						{
							$name['rtwwdpdl_discount_value'] = $name['rtwwdpdl_max_discount'];
						}

						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_discount_value'] : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_price')
					{
						if($name['rtwwdpdl_max_discount'] < $name['rtwwdpdl_discount_value'])
						{
							$name['rtwwdpdl_discount_value'] = $name['rtwwdpdl_max_discount'];
						}

						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ?  $rtwwdpdl_symbol . $name['rtwwdpdl_discount_value'] : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_weight')
					{
						if($name['rtwwdpdl_max_discount'] < $name['rtwwdpdl_discount_value'])
						{
							$name['rtwwdpdl_discount_value'] = $name['rtwwdpdl_max_discount'];
						}

						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $rtwwdpdl_weight_unit . $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $rtwwdpdl_weight_unit . $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_discount_value'] : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
					elseif($name['rtwwdpdl_check_for']=='rtwwdpdl_total')
					{
						if($name['rtwwdpdl_max_discount'] < $name['rtwwdpdl_discount_value'])
						{
							$name['rtwwdpdl_discount_value'] = $name['rtwwdpdl_max_discount'];
						}

						$rtwwdpdl_cart_text = str_replace('[from_quant]', isset($name['rtwwdpdl_min']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_min'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[to_quant]', isset($name['rtwwdpdl_max']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_max'] : '', $rtwwdpdl_cart_text);
						$rtwwdpdl_cart_text = str_replace('[discounted]', isset($name['rtwwdpdl_discount_value']) ? $rtwwdpdl_symbol . $name['rtwwdpdl_discount_value'] : '', $rtwwdpdl_cart_text);
						wc_print_notice( $rtwwdpdl_cart_text ,'notice' );
					}
				}
			}
		}
	}
}

if( isset( $rtwwdpdl_get_setting_priority['rtw_tier_offer_on_cart'] ) && $rtwwdpdl_get_setting_priority['rtw_tier_offer_on_cart'] == 'rtw_price_yes' && isset( $rtwwdpdl_get_setting_priority['tier_rule'] ) && $rtwwdpdl_get_setting_priority['tier_rule'] == 1 )
{ 
	$rtwwdpdl_rule_name = get_option('rtwwdpdl_tiered_rule');

	if(is_array($rtwwdpdl_rule_name) && !empty($rtwwdpdl_rule_name))
	{
		$temp_cart = $woocommerce->cart->cart_contents;
		$prods_quant = 0;
		$rtwwdpdl_total_weig = 0;
		$rtwwdpdl_cart_total = $woocommerce->cart->get_subtotal();
		foreach ( $temp_cart as $cart_item_key => $cart_item ) {
			$prods_quant += $cart_item['quantity'];
			if( $cart_item['data']->get_weight() != '' )
			{
				$rtwwdpdl_total_weig += $cart_item['data']->get_weight();
			}
		}
		foreach ( $rtwwdpdl_rule_name as $name ) {
			$match = false;
			if( $name['rtwwdpdl_to_date'] > $rtwwdpdl_today_date ){
				if( isset($name['products'] ) && is_array( $name['products'] ) && !empty( $name['products'] ) )
				{
					foreach ( $name['products'] as $keys => $vals ) 
					{
						foreach ( $temp_cart as $cart_item_key => $cart_item ) 
						{
							if( $vals == $cart_item['product_id'] )
							{
								$prods_quant = $cart_item['quantity'];
								$match = true;
							}
						}
						if( $match == false)
						{
							continue 2;
						}
						if( $name['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage' )
						{
							if( $name['rtwwdpdl_check_for'] == 'rtwwdpdl_price' )
							{
								foreach ( $name['quant_min'] as $k => $va ) {
									if( $va <= $prods_quant )
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}
									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k].'%' : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $rtwwdpdl_symbol . $va : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $rtwwdpdl_symbol .$name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
							}
							elseif( $name['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity' )
							{
								foreach ($name['quant_min'] as $k => $va) {
									if( $va <= $prods_quant )
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k].'%' : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
							}
							elseif($name['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
							{
								foreach ($name['quant_min'] as $k => $va) {
									if( $va <= $prods_quant )
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $name['discount_val'][$k].'%' : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va.$rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k].$rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
							}
							break 2;
						}
						else
						{
							if($name['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
							{
								foreach ($name['quant_min'] as $k => $va) {
									if( $va <= $prods_quant)
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $rtwwdpdl_symbol . $va : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $rtwwdpdl_symbol . $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
							}
							elseif($name['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
							{
								foreach ($name['quant_min'] as $k => $va) {
									if( $va <= $prods_quant )
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k] : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
							}
							elseif($name['rtwwdpdl_check_for'] == 'rtwwdpdl_weight')
							{
								foreach ($name['quant_min'] as $k => $va) {
									if( $va <= $prods_quant )
									{
										continue 1;
									}
									if(!isset($rtwwdpdl_get_content['rtwwdpdl_tier_text_show']) || $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'] == '')
									{
										$rtwwdpdl_tier_text_show = 'Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off';
									}
									else{
										$rtwwdpdl_tier_text_show = $rtwwdpdl_get_content['rtwwdpdl_tier_text_show'];
									}

									$rtwwdpdl_tier_text_show = str_replace('[this_product]', isset($name['products'][0]) ? get_the_title( $name['products'][0] ) : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show = str_replace('[discounted]', isset($name['discount_val'][$k]) ? $rtwwdpdl_symbol . $name['discount_val'][$k] : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[from_quant]', isset($va) ? $va.$rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

									$rtwwdpdl_tier_text_show =	str_replace('[to_quant]', isset($name['quant_max'][$k]) ? $name['quant_max'][$k].$rtwwdpdl_weight_unit : '', $rtwwdpdl_tier_text_show);

									wc_print_notice( $rtwwdpdl_tier_text_show ,'notice' );
								}
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