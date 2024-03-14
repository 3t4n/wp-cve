<?php
/**
 * The Pluggable table rules stuff 
 *
 * @package Fish and Ships
 * @version 1.5
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter to get all selection methods
 *
 * @since 1.0.0
 * @version 1.5
 *
 * @param $methods (array) maybe incomming a pair method-id / method-name array
 *
 * @return $methods (array) a pair method-id / method-name array
 *
 */

add_filter('wc_fns_get_selection_methods', 'wc_fns_get_selection_methods_fn', 10, 1);

function wc_fns_get_selection_methods_fn($methods = array()) {

	if (!is_array($methods)) $methods = array();

	$scope_all     = array ('normal', 'extra');
	$scope_normal  = array ('normal');
	$scope_extra   = array ('extra');
																			// will be HTML escaped later
	$methods['by-price']           = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Price', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['by-weight']          = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Weight', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['by-volume']          = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Volume', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['volumetric']         = array('onlypro' => true,  'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Volumetric', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['volumetric-set']     = array('onlypro' => true,  'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Volumetric set', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['min-dimension']      = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Min dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['mid-dimension']      = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Mid dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['max-dimension']      = array('onlypro' => false, 'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Max dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['lwh-dimensions']     = array('onlypro' => true,  'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Length+Width+Height', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['lgirth-dimensions']  = array('onlypro' => true,  'group' => 'Product data', 'scope' => $scope_all,   'label' => _x('Length+Girth (L+2W+2H)', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['in-category']        = array('onlypro' => true,  'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('In category', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-category']    = array('onlypro' => true,  'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('NOT In category', 'shorted, select-by conditional', 'fish-and-ships'));
	
	$methods['in-tag']             = array('onlypro' => true,  'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('Tagged as', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-tag']         = array('onlypro' => true,  'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('NOT Tagged as', 'shorted, select-by conditional', 'fish-and-ships'));
	
	$methods['in-class']           = array('onlypro' => false, 'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('In shipping class', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-class']       = array('onlypro' => false, 'group' => 'Product kind', 'scope' => $scope_all,   'label' => _x('NOT In shipping class', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['quantity']           = array('onlypro' => false, 'group' => 'Advanced', 'scope' => $scope_all,   'label' => _x('Cart items', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['cart-total']         = array('onlypro' => true,  'group' => 'Advanced',     'scope' => $scope_all,   'label' => _x('Cart total (excl. tax)', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['cart-total-tax']     = array('onlypro' => true,  'group' => 'Advanced',     'scope' => $scope_all,   'label' => _x('Cart total (incl. tax)', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['n-groups']           = array('onlypro' => false, 'group' => 'Advanced',     'scope' => $scope_all,   'label' => _x('Number of groups', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['user-role']          = array('onlypro' => true,  'group' => 'Advanced',     'scope' => $scope_all,   'label' => _x('User role', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['shipping-rate']      = array('onlypro' => true, 'scope' => $scope_extra, 'label' => _x('Calculated shipping rate', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['always']             = array('onlypro' => false, 'scope' => $scope_all,   'label' => _x('Always (all match)', 'shorted, select-by conditional', 'fish-and-ships'));

	return $methods;
}


/**
 * Filter to get the HTML selection fields for one method (centralised for all methods)
 *
 * @since 1.0.0
 * @version 1.2.11
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the rule number
 * @param sel_nr (integer) the selection number inside rule or total?
 * @param $method_id (mixed) the method-id
 * @param $values (array) the saved values 
 * @param $previous (boolean) true: JS array of empty fields | false: real field or AJAX insertion
 *
 * @return $html (HTML) the HTML selection fields
 *
 */

add_filter('wc_fns_get_html_details_method', 'wc_fns_get_html_details_method_fn', 10, 6);

function wc_fns_get_html_details_method_fn($html, $rule_nr, $sel_nr, $method_id, $values, $previous) {

	global $Fish_n_Ships;

	switch ($method_id) {
		
		case 'by-weight':
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, get_option('woocommerce_weight_unit'), $values, 'sel', 'selection', 'val_info', 'ge', 'less')
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'by-price':
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, get_woocommerce_currency_symbol(), $values, 'sel', 'selection', 'val_info',  'ge', 'less', true )
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;
			
		case 'by-volume':
			$unit = get_option('woocommerce_dimension_unit') . '<sup style="font-size:0.75em; vertical-align:0.25em">3</sup>';
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, $unit, $values, 'sel', 'selection', 'val_info', 'ge', 'less')
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;
			
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, get_option('woocommerce_dimension_unit'), $values, 'sel', 'selection', 'val_info', 'ge', 'less')
					. $Fish_n_Ships->cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'quantity':
			
			/* LEGACY for old shipping methods based on quantity:
			From 1.1.4, default MAX comparison for quantity is LE, but previous saved shipping methods
			should continue working and shown as LESS THAN */
			if ( isset( $values['max'] ) && !isset( $values['max_comp'] ) ) $values['max_comp'] = 'less';
			
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, _x('El.', 'Elements, shorted'), $values, 'sel', 'selection', 'val_info', 'ge', 'le')
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'n-groups':
						
			$html .= $Fish_n_Ships->get_min_max_comp_html($rule_nr, $sel_nr, $method_id, _x('G.', 'Groups, shorted'), $values, 'sel', 'selection', 'val_info', 'ge', 'le')
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'in-class':
		case 'not-in-class':

			if ($previous) {
				// Will be loaded ajaxfied in the JS array
				$html = '<div class="wc-fns-ajax-fields" data-type="selector" data-method-id="'.$method_id.'"><span class="wc-fns-spinner"></span></div>';
			} else {
				$values = is_array($values) && isset($values['classes']) ? $values['classes'] : array();
	
				$html .= $Fish_n_Ships->get_multiple_html($rule_nr, $sel_nr, $method_id, 'product_shipping_class', $values, 'classes')
						. $Fish_n_Ships->cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			}
			break;
	}
	
	return $html;
}

/**
 * Filter to sanitize one selection criterion and his auxiliary fields prior to save in the database (centralised for all methods)
 *
 * @since 1.0.0
 * @version 1.5
 *
 * @param $rule_sel (array) 
 *
 * @return $rule_sel sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_selection_fields', 'wc_fns_sanitize_selection_fields_fn', 10, 1);

function wc_fns_sanitize_selection_fields_fn($rule_sel) {
	
	//Prior failed?
	if (!is_array($rule_sel)) return $rule_sel;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_selection_methods', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_sel['method']) || !in_array($rule_sel['method'], $allowed_methods_ids, true)) return false;

	// Only allowed auxiliary fields
	$allowed = false;

	switch ($rule_sel['method']) {

		case 'by-price':
						
			$allowed = array( 'min_comp', 'max_comp', 'group_by' );
			
			if ($rule_sel['method'] == 'cart-total-tax' || $rule_sel['method'] == 'cart-total' || $rule_sel['method'] == 'shipping-rate') unset( $allowed[2] );

			// Let's allow a min and max field for every currency
			$currencies = $Fish_n_Ships->get_currencies();
			$n = 0;
			foreach ( $currencies as $currency => $symbol ) {
				
				$n++;
				// Main currency haven't sufix, it brings legacy with previous releases
				$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;

				$allowed [] = 'min' . $curr_sufix;
				$allowed [] = 'max' . $curr_sufix;
			}
			break;
		
		case 'n-groups':
		case 'by-weight':
		case 'by-volume':
		case 'quantity':
			$allowed = array('min','max', 'min_comp', 'max_comp', 'group_by' );
			break;
			
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
			$allowed = array('min','max', 'min_comp', 'max_comp' );
			break;


		case 'in-class':
		case 'not-in-class':
			$allowed = array('classes');
			break;
	}
	

	if (is_array($allowed)) {
		
		// form fields comes always lower-cased, let's check it and reinstate if needed
		foreach ($allowed as $fieldname) {
			if ( !isset($rule_sel['values'][$fieldname]) && isset($rule_sel['values'][sanitize_key($fieldname)]) 

				 // security check, malign fieldname can't pass this:
				 && sanitize_key($fieldname) == strtolower($fieldname) ) {
				
				// Create the new not lower-cased, the lowercased will be unset below
				$rule_sel['values'][$fieldname] = $rule_sel['values'][strtolower($fieldname)];
			}
		}

		foreach ($rule_sel['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_sel['values'][$field]);
		}

		// sanitize expected values
		switch ($rule_sel['method']) {

			case 'n-groups':
			case 'by-price':
			case 'by-weight':
			case 'by-volume':
			case 'quantity':
				
				// by-price, cart-total, cart-total-tax and shipping-rate has one min and max fields for every currency
				if ( $rule_sel['method'] == 'by-price' || $rule_sel['method'] == 'cart-total' || $rule_sel['method'] == 'cart-total-tax' || $rule_sel['method'] == 'shipping-rate' ) {

					$n = 0;
					foreach ( $currencies as $currency => $symbol ) {

						$n++;
						// Main currency haven't sufix, it brings legacy with previous releases
						$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;
						
						$min = isset( $rule_sel['values']['min' . $curr_sufix] ) ? $rule_sel['values']['min' . $curr_sufix] : 0;
						$max = isset( $rule_sel['values']['max' . $curr_sufix] ) ? $rule_sel['values']['max' . $curr_sufix] : 0;

						$rule_sel['values']['min' . $curr_sufix] = $Fish_n_Ships->sanitize_number($min, 'positive-decimal');
						$rule_sel['values']['max' . $curr_sufix] = $Fish_n_Ships->sanitize_number($max, 'positive-decimal');
					}
				
				} else {

					$rule_sel['values']['min'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['min'], 'positive-decimal');
					$rule_sel['values']['max'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['max'], 'positive-decimal');
				}
				
				$rule_sel['values']['min_comp'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['min_comp'],
																					array ( 'ge', 'greater' ) );
				$rule_sel['values']['max_comp'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['max_comp'],
																					array ( 'less', 'le' ) );
				
				if ( $rule_sel['method'] != 'cart-total' && $rule_sel['method'] != 'cart-total-tax' && $rule_sel['method'] != 'shipping-rate' ) {
				$rule_sel['values']['group_by'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['group_by'],
																					array_keys($Fish_n_Ships->get_group_by_options()));
				}
				break;

			case 'min-dimension':
			case 'mid-dimension':
			case 'max-dimension':
				$rule_sel['values']['min'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['min'], 'positive-decimal');
				$rule_sel['values']['max'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['max'], 'positive-decimal');
				$rule_sel['values']['min_comp'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['min_comp'],
																					array ( 'ge', 'greater' ) );
				$rule_sel['values']['max_comp'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['max_comp'],
																					array ( 'less', 'le' ) );
				break;

			case 'in-class':
			case 'not-in-class':
	
				if( ! isset( $rule_sel['values']['classes'] ) )
					$rule_sel['values']['classes'] = array();
	
				if ( !is_array($rule_sel['values']['classes']) ) {
					unset ( $rule_sel['values']['classes'] );
				} else {
					foreach ($rule_sel['values']['classes'] as $key=>$val) {
						$rule_sel['values']['classes'][$key] = $Fish_n_Ships->sanitize_number($rule_sel['values']['classes'][$key], 'id');
					}
				}
	
				break;
		}
	}
	
	return $rule_sel;
}

/**
 * Filter to sanitize the selection operators
 *
 * @since 1.1.9
 *
 * @param $rule_sel (array) 
 *
 * @return $rule_sel sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_selection_operators', 'wc_fns_sanitize_selection_operators_fn', 10, 1);

function wc_fns_sanitize_selection_operators_fn($rule_sel) {

	//Prior failed?
	if (!is_array($rule_sel)) return $rule_sel;

	// Only known operators
	$allowed_operators = apply_filters('wc_fns_get_selection_operators', array('logical_operator'));
	if (!is_array($allowed_operators) || !isset($rule_sel['method']) || !in_array($rule_sel['method'], $allowed_operators, true)) return false;

	switch ($rule_sel['method']) {

		case 'logical_operator':
			
			// Only one AND or OR is allowed, and we will give legacy support (always AND before 1.1.9)
			if ( $rule_sel['values'] !== array('or') )  $rule_sel['values'] = array('and');
			break;
	}

	return $rule_sel;
}
	
/**
 * Filter to check matching elements for selection method
 *
 * @since 1.0.0
 * @version 1.4.13
 *
 * Be aware! Since 1.1.9, a 5th parameter was added
 *
 * @param $rule_groups (array) all the groups of current rule
 * @param $selector (array) the selector criterion
 * @param $group_by (mixed) the group method 
 * @param $shipping_class (reference) the class reference 
 * @param $logical_operator and | or
 *
 * @return $rule_groups (array)
 *
 */

add_filter('wc_fns_check_matching_selection_method', 'wc_fns_check_matching_selection_method_fn', 10, 5);

function wc_fns_check_matching_selection_method_fn($rule_groups, $selector, $group_by, $shipping_class, $logical_operator = 'and') {

	global $Fish_n_Ships;

	// Main currency (for unsupported MC plugin, nor MC plugin or shipping settings has empty sufix)
	$curr_sufix = $shipping_class->get_currency_sufix_fields();
	$origin_costs_fields = ( $curr_sufix == '' ) ? 'main-currency' : 'cart-currency';

	// Prepare the selection auxiliary fields
	switch ($selector['method']) {
		
		// Prepare the variables for comparison
		case 'by-weight':
		case 'by-price':
		case 'by-volume':
		case 'n-groups':
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
		case 'quantity':
			
			// Only price-related min/max fields can have currency suffix (fixed on v1.2.3)
			if ( $selector['method'] == 'by-price' || $selector['method'] == 'cart-total' || $selector['method'] == 'cart-total-tax' || $selector['method'] == 'shipping-rate' ) {
				$min = 0; if (isset($selector['values']['min'.$curr_sufix])) $min = $selector['values']['min'.$curr_sufix];
				$max = '*'; if (isset($selector['values']['max'.$curr_sufix])) $max = $selector['values']['max'.$curr_sufix];
			} else {
				$min = 0; if (isset($selector['values']['min'])) $min = $selector['values']['min'];
				$max = '*'; if (isset($selector['values']['max'])) $max = $selector['values']['max'];
			}

			if ( trim($min) == '' ) $min = 0;
			
			// MAX field set as 0, will be taken as wildcard
			if ( trim($max) == '' || $max==0 ) $max = '*';
			
			// MIN/MAX will be currency abstracted before group comparison loop (change on 1.4.7)
			if ( $selector['method'] == 'by-price' || $selector['method'] == 'cart-total' || $selector['method'] == 'cart-total-tax' || $selector['method'] == 'shipping-rate' )
			{
									$min   = $Fish_n_Ships->currency_abstraction ($origin_costs_fields, $min);
				if ($max !== '*') 	$max   = $Fish_n_Ships->currency_abstraction ($origin_costs_fields, $max);
			}
			
			// This default values cover the 1.1.4 prior releases legacy
			$min_comp = 'ge';   if (isset($selector['values']['min_comp'])) $min_comp = $selector['values']['min_comp'];
			$max_comp = 'less'; if (isset($selector['values']['max_comp'])) $max_comp = $selector['values']['max_comp'];

			break;
	}

	// Getting group number after looping and maybe unmatching groups
	$groups_number = count( $rule_groups[$group_by] );
	
	// Let's iterate in his group_by groups
	$cart_total = null;
	$cart_total_tax  = null;
	foreach ($rule_groups[$group_by] as $group) {
		
		// empty or previously unmatched? bypass for performance
		if ($group->is_empty() || !$group->is_match()) continue;

		switch ($selector['method']) {
				
			case 'always':

				// No products to unmatch, in any case ;)
				break;

			case 'by-price':
			case 'by-weight':
			case 'by-volume':
			case 'min-dimension':
			case 'mid-dimension':
			case 'max-dimension':
			case 'quantity':
			
				$value = $group->get_total($selector['method']);
				
			case 'n-groups':
			
				// Setting value as number of groups
				if ( $selector['method'] == 'n-groups' ) $value = $groups_number;
				
				// Prices will be currency abstracted before comparison
				if ( $selector['method'] == 'by-price' || $selector['method'] == 'cart-total' ||  $selector['method'] == 'cart-total-tax' || $selector['method'] == 'shipping-rate' )
				{
					$value = $Fish_n_Ships->currency_abstraction ('cart-currency', $value);
				}

				// The MIN/MAX comparison 
				if (
						(// Min field comparison
							$min != 0 // not wildcard
							&& 
							(
								(
									$min_comp == 'greater' && $min >= $value
								) || (
									$min_comp != 'greater' && $min > $value // ge, by default
								)
							) 
						) || (
							(// Max field comparison
								$max !== '*' // not wildcard
								&&
								(
									(
										$max_comp == 'le' && $max < $value
									) || (
										$max_comp != 'le' && $max <= $value // less, by default
									)
								)
							)
						)
				) {
					if ($logical_operator == 'and')
						// unmatch this group
						$Fish_n_Ships->unmatch_group($group, $rule_groups);
					
				}
				break;


			case 'in-class':
			case 'not-in-class':

				$classes = ( isset($selector['values']['classes']) && is_array($selector['values']['classes']) ) ? $selector['values']['classes'] : array();

				if (!$group->check_term($selector['method'], 'product_shipping_class', $classes)) {

					if ($logical_operator == 'and')
						// unmatch this group
						$Fish_n_Ships->unmatch_group($group, $rule_groups);
					
				}
				break;
	
		}
	}
	
	return $rule_groups;
}

/**
 * Filter to get all cost methods
 *
 * @since 1.0.0
 *
 * @param $cost_methods (array) maybe incomming  a pair action-id / action-name array
 *
 * @return $cost_methods (array) a pair action-id / action-name array
 *
 */

add_filter('wc_fns_get_cost_methods', 'wc_fns_get_cost_methods_fn', 10, 1);

function wc_fns_get_cost_methods_fn($cost_methods = array()) {

	if (!is_array($cost_methods)) $cost_methods = array();
													// Will be HTML escaped later
	$cost_methods['once']       = array('label' => _x('(once)', 'very shorted, once price application', 'fish-and-ships'));
	$cost_methods['qty']        = array('label' => '* [qty]');
	$cost_methods['weight']     = array('label' => sprintf(_x('* weight (%s)', 'shorted, per weight price application', 'fish-and-ships'), get_option('woocommerce_weight_unit')) );
	$cost_methods['group']      = array('label' => '* [group]');
	$cost_methods['percent']    = array('label' => '%');
	$cost_methods['composite']  = array('label' => _x('composite', 'VERY shorted, composite price application', 'fish-and-ships'));
	
	return $cost_methods;
}

/**
 * Filter to get the HTML fields for price rules
 *
 * @since 1.0.0
 * @version 1.4.9
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the rule number
 * @param $values (array) the saved values 
 *
 * @return $html (HTML) the HTML price fields
 *
 */
add_filter('wc_fns_get_html_price_fields', 'wc_fns_get_html_price_fields_fn', 10, 3);

function wc_fns_get_html_price_fields_fn($html, $rule_nr, $values) {
	
	global $Fish_n_Ships;

	// Securing output
	$rule_nr = intval( $rule_nr );
	
	$currencies = $Fish_n_Ships->get_currencies();

	$html .= '<span class="field cost_simple"><span class="currency-switcher-fns-wrapper">';

	$n = 0;
	foreach ( $currencies as $currency=>$symbol ) {
		
		$n++;
		// Main currency haven't sufix, it brings legacy with previous releases
		$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;

		$value = 0; if ( isset($values[ 'cost' . $curr_sufix ]) ) $value = $values[ 'cost' . $curr_sufix ];

		$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
		$html .= '<input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost'.$curr_sufix.'][]" value="' . esc_attr( $Fish_n_Ships->format_number( $value, 'decimal' ) ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost" autocomplete="off">';
		$html .= '</span>';
	}
	$html .= '</span></span>';

	$html .= '<div class="cost_composite">';
	
	// Five fields, let's do it with a loop:
	$loop_data = array(
				'cost_once'		=> _x('(once)', 'very shorted, once price application', 'fish-and-ships'),
				'cost_qty'		=> _x('* [qty]', 'very shorted, per quantity price application', 'fish-and-ships'),
				'cost_weight'	=> sprintf( _x('* weight (%s)', 'very shorted, per weight price application', 'fish-and-ships'),
											get_option('woocommerce_weight_unit')),
				'cost_group'	=> _x('* [group]', 'very shorted, per group price application', 'fish-and-ships'),
				'cost_percent'	=> '%'
	);

	foreach ( $loop_data as $field_name => $label) {

		$html .= '<span class="field_wrapper"><span class="currency-switcher-fns-wrapper">';

		$n = 0;
		foreach ( $currencies as $currency=>$symbol ) {
			
			$n++;
			// Main currency haven't sufix, it brings legacy with previous releases
			$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;

			$value = 0; if ( isset($values[ $field_name . $curr_sufix ]) ) $value = $values[ $field_name . $curr_sufix ];

			$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
			$html .= '<input type="text" name="shipping_rules[' . $rule_nr . '][cost][' . $field_name . $curr_sufix . '][]" value="' . esc_attr( $Fish_n_Ships->format_number( $value, 'decimal' ) ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-'.$field_name.'" autocomplete="off"> ' . esc_html( ($field_name == 'cost_percent' ? '' : $symbol . ' ') . $label );
			$html .= '</span>';
		}
		$html .= '</span></span>';
	}
	$html .= '</div>';

	return $html;
}

/**
 * Filter to sanitize cost
 *
 * @since 1.0.0
 * @version 1.2.7
 *
 * @param $rule_cost (array) 
 *
 * @return $rule_cost sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_cost', 'wc_fns_sanitize_cost_fn', 10, 1);

function wc_fns_sanitize_cost_fn($rule_cost) {
		
	//Prior failed?
	if (!is_array($rule_cost)) return $rule_cost;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_cost_methods', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_cost['method']) || !in_array($rule_cost['method'], $allowed_methods_ids, true)) return false;

	// Only allowed price fields
	$allowed = false;

	$currencies = $Fish_n_Ships->get_currencies();

	switch ($rule_cost['method']) {
		
		case 'once':
		case 'qty':
		case 'weight':
		case 'group':
		case 'percent':

			$n = 0; $allowed = array();
			foreach ( $currencies as $currency=>$symbol ) {
		
				$n++;
				// Main currency haven't sufix, it brings legacy with previous releases
				$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;

				$allowed[] = 'cost' . $curr_sufix;
			}
			break;

		case 'composite':
			
			$n = 0; $allowed = array();
			foreach ( $currencies as $currency=>$symbol ) {
		
				$n++;
				// Main currency haven't sufix, it brings legacy with previous releases
				$curr_sufix = ''; if ( $n > 1 ) $curr_sufix = '-' . $currency;
				
				foreach ( array('cost_once', 'cost_qty', 'cost_weight', 'cost_group', 'cost_percent') as $field_name ) {
					$allowed[] = $field_name . $curr_sufix;
				}
			}
			break;
	}
	if (is_array($allowed)) {

		// form fields comes always lower-cased, let's check it and reinstate if needed
		foreach ($allowed as $fieldname) {
			if ( !isset($rule_cost['values'][$fieldname]) && isset($rule_cost['values'][sanitize_key($fieldname)]) 

				 // security check, malign fieldname can't pass this:
				 && sanitize_key($fieldname) == strtolower($fieldname) ) {
				
				// Create the new not lower-cased, the lowercased will be unset below
				$rule_cost['values'][$fieldname] = $rule_cost['values'][strtolower($fieldname)];
			}
		}

		foreach ($rule_cost['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_cost['values'][$field]);
		}

		// sanitize expected values
		foreach ($allowed as $field) {

			$value = isset( $rule_cost['values'][$field] ) ? $rule_cost['values'][$field] : 0;

			$rule_cost['values'][$field] = $Fish_n_Ships->sanitize_number($value, 'decimal');
		}
	}
	

	return $rule_cost;
}

/**
 * Filter to calculate the shipping cost rule
 *
 * @since 1.0.0
 * @version 1.4.13
 *
 * @param $prev_cost (integer) 0 or maybe the previous filtered cost
 * @param $cost (array) The rule cost
 * @param $shippable_contents_rule (array) the contents to looking for
 * @param $rule_groups (array) all groups of current rule
 * @param $shipping_class (reference) the class reference 
 *
 * @return $cost (integer) The calculated cost of the rule
 *
 */

add_filter('wc_fns_calculate_cost_rule', 'wc_fns_calculate_cost_rule_fn', 10, 5);

function wc_fns_calculate_cost_rule_fn($prev_cost, $cost, $shippable_contents_rule, $rule_groups, $shipping_class) {
	
	global $Fish_n_Ships;
	
	// Main currency (for unsupported MC plugin, nor MC plugin or shipping settings has empty sufix)
	$curr_sufix = $shipping_class->get_currency_sufix_fields();
	$origin_costs_fields = ( $curr_sufix == '' ) ? 'main-currency' : 'cart-currency';
	
	$calculated_cost = 0;

	$cost_field = 0; if (isset($cost['values']['cost'.$curr_sufix])) $cost_field = floatval($cost['values']['cost'.$curr_sufix]);

	// We need calculate the matched products quantity
	if ($cost['method'] == 'qty' || $cost['method'] == 'composite') {

		$qty = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			//$qty += $Fish_n_Ships->get_quantity($product);
			$qty += $product[ 'to_ship' ];
		}
	}


	// We need calculate the matched products total price
	if ($cost['method'] == 'percent' || $cost['method'] == 'composite') {

		$total_price = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			//$total_price += $Fish_n_Ships->get_price($product) * $Fish_n_Ships->get_quantity($product);
			$total_price += $Fish_n_Ships->get_price($product) * $product[ 'to_ship' ];
		}
	}


	// We need calculate the matched products total weight
	if ($cost['method'] == 'weight' || $cost['method'] == 'composite') {

		$weight = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			$weight += $Fish_n_Ships->get_weight($product) * $Fish_n_Ships->get_quantity($product);
		}
	}


	// We need calculate the number of matched groups
	if ($cost['method'] == 'group' || $cost['method'] == 'composite') {

		$matched_groups = $Fish_n_Ships->get_matched_groups($rule_groups);
	}
	
	switch ($cost['method']) {
		
		case 'once':
			// nothing to calculate
			$calculated_cost = $Fish_n_Ships->currency_abstraction($origin_costs_fields, $cost_field);
			break;

		case 'qty':
			
			$calculated_cost = $Fish_n_Ships->currency_abstraction($origin_costs_fields, $cost_field) * $qty;
			break;

		case 'group':

			$calculated_cost = $Fish_n_Ships->currency_abstraction($origin_costs_fields, $cost_field) * $matched_groups;
			break;

		case 'weight':

			$calculated_cost = $Fish_n_Ships->currency_abstraction($origin_costs_fields, $cost_field) * $weight;
			break;

		case 'percent':
			// The percentage comes into humnan format: 0-100%
			// percent is the unique not-currency user value, and total price comes from cart
			$calculated_cost = $cost_field * $Fish_n_Ships->currency_abstraction('cart-currency', $total_price) * 0.01; 
			break;
			
		case 'composite':

			$cost_once     = 0; if (isset($cost['values']['cost_once'.$curr_sufix])) 
									$cost_once     = $Fish_n_Ships->currency_abstraction($origin_costs_fields, floatval($cost['values']['cost_once'.$curr_sufix]) );
								
			$cost_qty      = 0; if (isset($cost['values']['cost_qty'.$curr_sufix])) 
									$cost_qty      = $Fish_n_Ships->currency_abstraction($origin_costs_fields, floatval($cost['values']['cost_qty'.$curr_sufix]) );

			$cost_weight   = 0; if (isset($cost['values']['cost_weight'.$curr_sufix])) 
									$cost_weight   = $Fish_n_Ships->currency_abstraction($origin_costs_fields, floatval($cost['values']['cost_weight'.$curr_sufix]) );
								
			$cost_group    = 0; if (isset($cost['values']['cost_group'.$curr_sufix])) 
									$cost_group    = $Fish_n_Ships->currency_abstraction($origin_costs_fields, floatval($cost['values']['cost_group'.$curr_sufix]) );
								
			$cost_percent  = 0; if (isset($cost['values']['cost_percent'.$curr_sufix]))
									$cost_percent  = floatval($cost['values']['cost_percent'.$curr_sufix]);
			
			// total price comes from cart
			$total_price = $Fish_n_Ships->currency_abstraction('cart-currency', $total_price);

			// Waha! all methods together
			$calculated_cost  = $cost_once;
			$calculated_cost += $cost_qty      * $qty;
			$calculated_cost += $cost_weight   * $weight;
			$calculated_cost += $cost_group    * $matched_groups;
			// The percentage comes into humnan format: 0-100%
			$calculated_cost += $cost_percent  * $total_price * 0.01; 

			$shipping_class->debug_log('Composite cost (once + qty + weight + group + %): ' . $cost_once . ' + ' . $cost_qty . '*' . $qty . ' + ' . $cost_weight . '*' . $weight . ' + ' . $cost_group . '*' . $matched_groups . ' + ' . $total_price . '*' . $cost_percent . '% = ' . $calculated_cost, 2);
			
			break;
	}
	
	return $prev_cost + $calculated_cost;
}


/**
 * Filter to get all actions
 *
 * @since 1.0.0
 * @version 1.4.13
 *
 * @param $actions (array) maybe incomming  a pair action-id / only-pro, scope (optional), action-name array
 *
 * @return $actions (array) a pair action-id / action-name array
 *
 */

add_filter('wc_fns_get_actions', 'wc_fns_get_actions_fn', 10, 1);

function wc_fns_get_actions_fn($actions = array()) {
	
	/* Translators: on v1.2.1 two special actions has been renamed:
	
	Ignore below rules                => Stop (ignore below rules)
	Unset match prods for next rules  => Matching prods skip below rules
	*/

	if (!is_array($actions)) $actions = array();
	
	$scope_all     = array ('normal', 'extra');
	$scope_normal  = array ('normal');
	$scope_extra   = array ('extra');
																	// will be HTML scaped later 
	$actions['abort']           = array('onlypro' => false, 'scope' => $scope_normal, 'label' => _x('Abort shipping method', 'shorted, action name', 'fish-and-ships'));
	$actions['skip']            = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Skip N rules', 'shorted, action name', 'fish-and-ships'));
	$actions['reset']           = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Reset previous costs', 'shorted, action name', 'fish-and-ships'));
	$actions['break']           = array('onlypro' => false, 'scope' => $scope_normal, 'label' => _x('Stop (ignore below rules)', 'shorted, action name (renamed on v1.2.1)', 'fish-and-ships'));
	$actions['min_max']         = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Set min/max rule costs', 'shorted, action name', 'fish-and-ships'));
	$actions['unset']           = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Matching prods skip below rules', 'shorted, action name (renamed on v1.2.1)', 'fish-and-ships'));
	
	$actions['notice']          = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Show notice message', 'shorted, action name', 'fish-and-ships'));
	$actions['rename']          = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Rename method title', 'shorted, action name', 'fish-and-ships'));
	$actions['description']     = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Add subtitle (text under)', 'shorted, action name', 'fish-and-ships'));
	$actions['total-messages']  = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Change cart totals messages', 'shorted, action name', 'fish-and-ships'));
	
	
	$actions['coupon']          = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Auto-apply coupon', 'shorted, action name', 'fish-and-ships'));
	$actions['math']            = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Math expression', 'shorted, action name', 'fish-and-ships'));
	$actions['disable_others']  = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Hide other shipping methods', 'shorted, action name', 'fish-and-ships'));

	$actions['ship_rate_pct']   = array('onlypro' => true,  'scope' => $scope_extra,  'label' => _x('Shipping rate +/- %', 'shorted, action name', 'fish-and-ships'));
	$actions['ship_rate_fix']   = array('onlypro' => true,  'scope' => $scope_extra,  'label' => _x('Shipping rate +/- fixed', 'shorted, action name', 'fish-and-ships'));

	return $actions;
}

/**
 * Filter to get the HTML action details for one action (centralised for all actions)
 *
 * @since 1.0.0
 * @version 1.1.6
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the ordinal rule number (starts at 0)
 * @param $action_nr (integer) the action ordinal inside rule (starts at 0)
 * @param $action_id (mixed) the method-id
 * @param $values (array) the saved values 
 *
 * @return $html (HTML) the HTML selection fields
 *
 */

add_filter('wc_fns_get_html_details_action', 'wc_fns_get_html_details_action_fn', 10, 5);

function wc_fns_get_html_details_action_fn($html, $rule_nr, $action_nr, $action_id, $values) {
	
	return $html;
}


/**
 * Filter to sanitize one action and his auxiliary fields prior to save in the database (centralised for all methods)
 *
 * @since 1.0.0
 * @version 1.1.6
 *
 * @param $rule_sel (array) 
 *
 * @return $rule_sel sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_action', 'wc_fns_sanitize_action_fn', 10, 1);

function wc_fns_sanitize_action_fn($rule_action) {
		
	//Prior failed?
	if (!is_array($rule_action)) return $rule_action;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_actions', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_action['method']) || !in_array($rule_action['method'], $allowed_methods_ids, true)) return false;

	// Only allowed auxiliary fields
	$allowed = false;

	switch ($rule_action['method']) {

		case 'abort':
		case 'break':
			$allowed = array();
			break;

	}
	
	if (is_array($allowed)) {
		
		// form fields comes always lower-cased, let's check it and reinstate if needed
		foreach ($allowed as $fieldname) {
			if ( !isset($rule_action['values'][$fieldname]) && isset($rule_action['values'][sanitize_key($fieldname)]) 

				 // security check, malign fieldname can't pass this:
				 && sanitize_key($fieldname) == strtolower($fieldname) ) {
				
				// Create the new not lower-cased, the lowercased will be unset below
				$rule_action['values'][$fieldname] = $rule_action['values'][strtolower($fieldname)];
			}
		}

		foreach ($rule_action['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_action['values'][$field]);
		}

	}
	
	return $rule_action;
}

/**
 * Filter to get translatable texts for actions (centralised for all methods)
 *
 * @since 1.0.0
 *
 * @param $action_id (text) 
 *
 * @return $array with translatable field names if there is any
 *
 */

add_filter('wc_fns_get_translatable_action', 'wc_fns_get_translatable_action_fn', 10, 2);

function wc_fns_get_translatable_action_fn($translatables, $action_id) {
	
	if (!is_array($translatables)) $translatables = array();
		
	switch ($action_id) {

	}
	return $translatables;
}


/**
 * Filter to perfom the special actions
 *
 * @since 1.0.0
 * @version 1.3
 *
 * @param $action_result (array) data array that can be modified by the action
 * @param $action (array) action parameters
 * @param $shipping_class (reference) reference to the shipping class
 *
 * @return $action_result (array) maybe modified
 *
 */

add_filter('wc_fns_apply_action', 'wc_fns_apply_action_fn', 10, 3);

function wc_fns_apply_action_fn($action_result, $action, $shipping_class) {

	global $Fish_n_Ships;

	if ( $Fish_n_Ships->im_pro() ) global $Fish_n_Ships_PAH;

	switch ($action['method']) {
		
		case 'abort':
			$action_result['abort'] = true;
			break;

		case 'break':
			$action_result['break'] = true;
			break;

	}
	
	return $action_result;
}

