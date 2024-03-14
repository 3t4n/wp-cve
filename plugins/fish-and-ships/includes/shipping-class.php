<?php
/**
 * The WC_Fish_n_Ships class. 
 *
 * This is the shipping class that extends WC
 *
 * @package Fish and Ships
 * @version 1.5
 */

defined( 'ABSPATH' ) || exit;

class WC_Fish_n_Ships extends WC_Shipping_Method {

	public $log_calculate = array();
	public $log_totals = array();
	public $global_cost    = 0;

	public $used_boxes     = array();
	public $extra_boxes    = array();

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		
		global $Fish_n_Ships;

		$this->id                    = $Fish_n_Ships->id;
		$this->instance_id           = absint( $instance_id );
		$this->option_name           = 'woocommerce_'. $Fish_n_Ships->id .'_'. $this->instance_id .'_settings';

		$this->method_title          = $Fish_n_Ships->im_pro() ? 'Fish and Ships Pro' : 'Fish and Ships';

		// Since WC 8.4 the method type has been removed.
		$mt = version_compare( WC()->version, '8.4.0', '<') ? '' : $this->method_title . '. ';

		$this->method_description    = $mt . __('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships');
		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
			//'instance-settings-modal', (surt la configuracio en un popup, caldria desenvolupar de les dues maneres 
		);
		
		$this->init();
		
		// Save the new shipping rules
		add_action( 'woocommerce_update_options_shipping_' . $Fish_n_Ships->id, array( $this, 'process_admin_options' ) );
	}
	
			
	/**
	 * Init user set variables.
	 *
	 * @since 1.0.0
	 * @version 1.2.3
	 */
	public function init() {
		
		$this->instance_form_fields = require WC_FNS_PATH . 'includes/settings-fns.php';

		$this->title                    = $this->get_option( 'title' );
		$this->tax_status               = $this->get_option( 'tax_status' );
		$this->global_group_by          = $this->get_option( 'global_group_by' );
		$this->global_group_by_method   = $this->get_option( 'global_group_by_method' );
		$this->multiple_currency 		= $this->get_option( 'multiple_currency' );
		$this->volumetric_weight_factor = $this->get_option( 'volumetric_weight_factor' );
		$this->rules_charge             = $this->get_option( 'rules_charge' );
		$this->free_shipping            = $this->get_option( 'free_shipping' );
		$this->disallow_other           = $this->get_option( 'disallow_other' );
		$this->min_shipping_price       = $this->get_option( 'min_shipping_price' );
		$this->max_shipping_price       = $this->get_option( 'max_shipping_price' );
		$this->write_logs               = $this->get_option( 'write_logs' );

		$this->shipping_rules           = $this->get_shipping_rules();
		
		if ($this->write_logs == 'everyone') {
			$this->write_logs = true;

		} elseif ($this->write_logs == 'admins' && ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) ) {
			$this->write_logs = true;

		} else {
			$this->write_logs = false;
		}
	}
	
	/**
	 * Get the shipping rules 
	 *
	 * @since 1.0.0
	 */
	public function get_shipping_rules() {
		
		// a bit of performance
		if ($this->instance_id == 0) return array();
		
		$settings = get_option($this->option_name, array());
		if (is_array($settings) && isset($settings['shipping_rules'])) {
			if (is_array($settings['shipping_rules'])) return $settings['shipping_rules'];
		}
		return array();
	}

	/**
	 * The new shipping rules will be saved if we are editing this
	 *
	 * @since 1.0.0
	 * @version 1.5
	 */
	public function process_admin_options(){

		global $Fish_n_Ships, $Fish_n_Ships_Wizard;

		// The standard fields will be saved by WC
		parent::process_admin_options();
		
		// Now we will save the shipping rules table
		if (isset($_GET['instance_id']) && intval($_GET['instance_id']) == $this->instance_id && isset($_POST['shipping_rules'])) {
			
			// Must be sanitized
			$shipping_rules = $Fish_n_Ships->sanitize_shipping_rules($_POST['shipping_rules']);

			$settings = get_option($this->option_name, array() );
			$settings['shipping_rules'] = $shipping_rules;

			update_option($this->option_name , $settings );
			$this->shipping_rules = $shipping_rules;
			
			// Maybe must add samples
			$Fish_n_Ships_Wizard->create_sample_settings( $this );

			// Useful for sample creation
			// error_log( print_r( $settings, true ) );
			
			$Fish_n_Ships->save_translatables( $this->shipping_rules );
		}
		
		// Reset the cached previous shipping costs (since version 1.0.4)
		WC()->shipping()->reset_shipping();
		
		// Reset messages, coupons & disallowed shipping methods list on session.
		if ( $Fish_n_Ships->im_pro() ) {
			global $Fish_n_Ships_PAH;
			$Fish_n_Ships_PAH->reset_session();
		}
	}
				
	/**
	* It generates the shipping rules table HTML.
	*
	* @since 1.0.0
	* @return HTML
	*/
	public function generate_shipping_rules_table_html() {
		require WC_FNS_PATH . 'includes/shipping_rules-table-fns.php';
		return $html;
	}

	/**
	* It generates the logs panel HTML.
	*
	* @since 1.0.0
	* @version 1.2.6
	* @return HTML
	*/
	public function generate_logs_panel_html() {
		
		$instance_id = $this->instance_id;
		
		$html = '</table><div id="logs_wrapper">';

		require WC_FNS_PATH . 'includes/logs-panel.php';

		$html .= '</div><p>' . esc_html ( sprintf(__('* The logs will be deleted after %s days', 'fish-and-ships'), (defined('WC_FNS_DAYS_LOG') ? WC_FNS_DAYS_LOG : 7) ) ) . '</p>';
		
		// Re-open the table
		$html .= '<table class="form-table">';

		return $html;
	}

	/**
	* It generates the freemium panel HTML.
	*
	* @since 1.0.0
	* @return HTML
	*/
	public function generate_freemium_panel_html() {
		require WC_FNS_PATH . 'includes/freemium-panel.php';
		return $html;
	}
	
	/**
	 * Get the right sufix for the currency costs fields.
	 * (empty for the main currency or when not activated, to bring legacy)
	 *
	 * @since 1.1.6
	 *
	 */
	public function get_currency_sufix_fields() {

		global $Fish_n_Ships;

		// If there isn't a compatible plugin, or the option is not active, we will work with the 
		// main currency costs fields (without sufix):
		if ( !$Fish_n_Ships->can_manually_costs_every_currency() || $this->multiple_currency !== 'yes' ) return '';
		
		// The cart is in main currency? The main currency have'nt sufix
		if ( get_option('woocommerce_currency') == get_woocommerce_currency() ) return '';
		
		return '-' . get_woocommerce_currency();			
	}
	
	/**
	 * Calculate the shipping costs.
	 *
	 * @since 1.0.0
	 * @version 1.4.13
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ) {

		global $Fish_n_Ships, $wpdb;
		
		$errors = array();

		if ($this->write_logs === true) {
			
			$this->debug_log('*Starting Fish and Ships ' . ($Fish_n_Ships->im_pro() ? 'Pro' : '(free)') . ' calculation, for method: [' . $this->title . ']. Instance_id: [' . $this->instance_id . '], Local time: [' . current_time( 'mysql' ) . ']', 0);
			
			$this->debug_log('Fish and Ships version: [' . WC_FNS_VERSION . '], WP: [' . get_bloginfo('version') . '], WC: [' . WC()->version . '], Multilingual: ' . $Fish_n_Ships->get_multilingual_info() . ', Multicurrency: ' . $Fish_n_Ships->get_multicurrency_info($this), 0);
		
			$this->log_totals['memory']      = memory_get_usage();
			$this->log_totals['num_queries'] = $wpdb->num_queries;
			$this->log_totals['time_start']  = function_exists('microtime') ? microtime(true) : time();
		}

		$active = false; // Any matching rule will active it.
		$skip_n = 0; 
		
		$jump_up_n     = 0;
		$prevent_crash = 0;
		
		$break_pending = false;
		// $global_cost = 0;
		$post_fees = array();

		// Remove old description if there is one
		$fns_description = WC()->session->get('fns_description');
		if (is_array($fns_description) && isset($fns_description[$this->instance_id])) {
			unset($fns_description[$this->instance_id]);
			WC()->session->set('fns_description', $fns_description );
		}
		
		$this->debug_log('#', 0);
		$this->debug_log('*Cart contents:', 0);

		// Let's work with shippable contents only
		$n_shippable = 0;
		$n_non_shippable = 0;
		$shippable_contents = array();
		foreach ( $package['contents'] as $key => $product ) {
			
			if ($product['data']->needs_shipping()) {
			
				$shippable_contents[$key] = $product;
				$shippable_contents[$key]['to_ship'] = $Fish_n_Ships->get_quantity($product);
				
				// Multilingual? Let's add language information. 
				//   (Products ID not replaced on cart by the default language, haven't translated)
				if ( $Fish_n_Ships->is_wpml() ) $shippable_contents[$key]['lang'] = $Fish_n_Ships->get_lang_info( $product );
				
				$n_shippable += $Fish_n_Ships->get_quantity($product);
			
				$this->debug_log('- ' . $Fish_n_Ships->get_name($product) . ' (' . $Fish_n_Ships->get_quantity($product) . ')', 0);
			
			} else {
				$n_non_shippable += $Fish_n_Ships->get_quantity($product);

				$this->debug_log('- ' . $Fish_n_Ships->get_name($product) . ' ( non-shippable )', 0);
			}
		}
				$this->log_totals['cart_qty'] = ($n_shippable + $n_non_shippable) . ($n_non_shippable == 0 ? ' shippable prods.' : ' prods, ' . $n_shippable . ' shippable');

		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => 0,
			'package' => $package
		);
		$virtual_count   = 0;         // counter for logging only
		$last_rule_type  = 'unknown'; // or empty, or foo, whatever
		$num_matches     = 0; // Rules matching selection criteria
		
		// Global Group by is a must in the free version
		if ( $this->global_group_by == 'no' && !$Fish_n_Ships->im_pro()  ) {
			$errors['global-group-by'] = '1';
			$this->debug_log('*Error: Only the Pro version allow distinct grouping criteria on every selection condition');
		}

		// Backup all shippable contents for extra fees selection conditions
		$all_shippable_contents = $shippable_contents;

		// Since 1.4.13 the foreach is replaced by for, to give support to jump-up. The variable $rule has been renamed as $virtual_count
		// foreach ($this->shipping_rules as $shipping_rule) {
		for ( $rule_pointer = 0; $rule_pointer < count( $this->shipping_rules ); $rule_pointer++ )
		{	
			$shipping_rule = $this->shipping_rules[ $rule_pointer ];
			
			// Fallback rule type for previous releases (normal and extra allowed)
			$rule_type = isset( $shipping_rule['type'] ) ? $shipping_rule['type'] : 'normal';
			if ( !in_array( $rule_type, array ( 'normal', 'extra' ) ) ) $rule_type = 'normal';
			
			// If none of the normal rules match, method is not applicable
			if ( !$active && $rule_type == 'extra' ) break;
			
			$virtual_count++;

			// Restarting rule counter on change rule type section (only visual, for logging)
			if ( $last_rule_type != $rule_type) $virtual_count = 1;
			$last_rule_type = $rule_type;

			// Since 1.1.9, the operator can be AND or OR. AND for legacy
			$logical_operator = $Fish_n_Ships->get_logical_operator($shipping_rule);

			$this->debug_log('*Rule #' . $virtual_count . ($rule_type != 'normal' ? ' [type: ' . $rule_type . ']' : '' ), 1);
			
			// On stop [ignore below rules] (only normal rules can be skipped)
			if ( $break_pending && $rule_type == 'normal' ) {
				$this->debug_log('*- Special action: [ignore below rules]', 1);
				continue; // Maybe there is some extra rule at the end
			}

			// Support for skipping N rules (only normal rules can be skipped)
			if ( $skip_n != 0 && $rule_type == 'normal' ) {
				$skip_n--;
				$this->debug_log('*- Special action: [skip rule]', 1);
				continue;
			}
			if ( $skip_n != 0 && $rule_type != 'normal' ) {
				$this->debug_log('*- Warning! [skip rule] ignored: only normal rules can be skipped', 1);
				$skip_n = 0;
			}
			
			$this->debug_log('Logical operator: [' . mb_strtoupper($logical_operator) . ']', 2);

			// Unknown method? Let's advice about it! (once)
			$idx = 'logical-operator-' . $logical_operator;
			if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
				$known = $Fish_n_Ships->is_known('logical operator', $logical_operator );
				if ($known !== true) {
					$errors[$idx] = '1';
					$this->debug_log('*'.$known, 1);
				}
			}

			// Extra fees selectors will use all products in any case (take no effect the special action "ignore matching prods")
			$shippable_contents_rule = $rule_type == 'extra' ? $all_shippable_contents : $shippable_contents;
			
			// Reference to all group objects will be stored here
			$rule_groups = array();	

			// if some group has been changed, we should repeat the iterations
			$iterations = 0;

			do {
				$iterations++;

				// On first iteration it's superfluous
				$this->unset_groups($rule_groups);
				
				/************************* Check if selection matches *************************/
				
				$selection_match = false;
				
				if (isset($shipping_rule['sel'])) {
					foreach ($shipping_rule['sel'] as $n_key=>$selector) {
						
						// Only key numbers are really selectors
						if ($n_key !== intval($n_key)) continue;
							
						if (is_array($selector) && isset($selector['method'])) {
							
							// Unknown method? Let's advice about it! (only if should write logs and once)
							$idx = 'selection-' . $selector['method'];
							if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
								$known = $Fish_n_Ships->is_known('selection', $selector['method']);
								if ($known !== true) {
									$errors[$idx] = '1';
									$this->debug_log('*'.$known, 1);
								}
							}
							
							// This default values cover the 1.1.4 prior releases legacy
							if ( isset($selector['values']['min']) && !isset($selector['values']['min_comp']) ) {
								$selector['values']['min_comp'] = 'ge';
							}
							if ( isset($selector['values']['max']) && !isset($selector['values']['max_comp']) ) {
								$selector['values']['max_comp'] = 'less';
							}
							
							/************************* Let's group elements *************************/
							$group_by = 'none';
							
							// The auxiliary fields method will be listed in log (if there is anyone)
							$aux_fields_log = '';
							foreach ($selector['values'] as $field=>$value) {
								if ($field != 'group_by') {
									$aux_fields_log .= ', ' . $field . ': [' . (is_array($value) ? implode(', ', $value) : $value) . ']';
								}
							}
							
							// Only this selection methods has group capabilities
							$groupable_sm = apply_filters('wc-fns-groupable-selection-methods', array('by-weight', 'by-price', 'by-volume', 'volumetric', 'volumetric-set', 'quantity', 'n-groups') );
							if ( in_array($selector['method'], $groupable_sm) ) {
								
								if ('yes' === $this->global_group_by) {
									// The global group-by option is enabled
									$group_by = $this->global_group_by_method;
								} else {
									// The selection has his own group_by option
									if (isset($selector['values']['group_by'])) $group_by = $selector['values']['group_by'];
								}
								$this->debug_log('Check matching selection. Method: [' . $selector['method'] . '], Group-by: [' . $group_by . ']' . $aux_fields_log, 2);
							} else {
								
								$this->debug_log('Check matching selection. Method: [' . $selector['method'] . '], Group-by: [none] (This method can\'t be grouped)' . $aux_fields_log, 2);
							}

							//$this->debug_log('[start-collapsable]', 2);

							foreach ($shippable_contents_rule as $key=>$product) {
								
								switch ($group_by) {
									case 'id_sku' :
										$subindex = $Fish_n_Ships->get_sku_safe($product);
										break;
	
									case 'product_id' :
										$subindex = $Fish_n_Ships->get_real_id($product);
										break;
	
									case 'class' :
										$subindex = $product['data']->get_shipping_class_id();
										break;
	
									case 'all' :
										$subindex = '';
										break;
	
									case 'none' :
									default :
										$group_by = 'none';
										// Compatibility with Uni CPO plugin
										$subindex = 'unique-' . $key;
										break;
								}

								// if the group isn't created, let's create it
								if (!isset($rule_groups[$group_by])) $rule_groups[$group_by] = array();
								if (!isset($rule_groups[$group_by][$subindex])) {
									$rule_groups[$group_by][$subindex] = new Fish_n_Ships_group($group_by, $this);

									//$this->debug_log('creating new group: ' . $group_by . ' > ' . $subindex);
								}
								
								// We will add the product in the right group
								$rule_groups[$group_by][$subindex]->add_element($key, $product, false);
							}

							// no matching products? let's create it empty
							if (!isset($rule_groups[$group_by])) $rule_groups[$group_by] = array();
							if (!isset($rule_groups[$group_by][$subindex])) {
								$rule_groups[$group_by][$subindex] = new Fish_n_Ships_group($group_by, $this);
								
								//$this->debug_log('creating new group: ' . $group_by . ' > ' . $subindex);
							}
													
							// Be aware! On 1.4.13 a 6th parameter ($package) has been added
							$rule_groups = apply_filters('wc_fns_check_matching_selection_method', $rule_groups, $selector, $group_by, $this, $logical_operator, $package);
							
							// Only matching contents must be evaluated on the next selection or iteration (if needed)
							// ...in the AND logic, not in the OR logic 
							if ( $logical_operator== 'and') {

								$this->debug_log('*Currently matching products (accumulated checkings result):', 2);
								$shippable_contents_rule = $Fish_n_Ships->get_selected_contents($rule_groups, $this, 'and');
							
							} else if ( $this->write_logs ) {

								$this->debug_log('*Currently matching products (accumulated checkings result):', 2);
								// Only for log purposes: 
								$foo = $Fish_n_Ships->get_selected_contents($rule_groups, $this, 'or');
							}
						}
					} // end rule sel loop
				}

				// If some group has been changed, we should repeat the iterations
				if ( $repeat = $Fish_n_Ships->somegroup_changed($rule_groups) ) {

					$this->debug_log('All match checking must be reevaluated for rule #' . $virtual_count , 2);

					$Fish_n_Ships->reset_groups($rule_groups);
				}

				// Prevent infinite loop on error
				if ($iterations > (defined('WC_FNS_MAX_ITERATIONS') ? WC_FNS_MAX_ITERATIONS : 10) ) {
					
					$this->debug_log('Too much iterations. Break to prevent timeout error' , 1);
					trigger_error('WC Fish and Ships: Too much iterations. Break to prevent timeout error');
					$repeat = false;
				}

			} while ($repeat);
			
			
			// Let's to collect how many products matches at least one (OR logic) selector
			if ( $logical_operator == 'or') {
				// Mute log, last select report has printed the same!
				$shippable_contents_rule = $Fish_n_Ships->get_selected_contents($rule_groups, $this, 'or', true);
			}

			// No products match selectors? Skip this rule
			// (crec que es pot aprofitar) if (!$Fish_n_Ships->somegroup_matching($rule_groups) ) {
			if (count($shippable_contents_rule) == 0) {
			
				$this->debug_log('- No product matches for this rule', 1);
				$this->unset_groups($rule_groups);
				continue;
			}

 			// This rule will be applied.
 			$active = true;
			$num_matches ++;
			
			// Let's calculate the cost of this rule
			$rule_cost = 0;			
			if (isset($shipping_rule['cost'])) {
				foreach ($shipping_rule['cost'] as $cost) {
					if (is_array($cost) && isset($cost['method'])) {

						// Unknown method? Let's advice about it! (only if should write logs and once)
						$idx = 'cost-' . $cost['method'];
						if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
							$known = $Fish_n_Ships->is_known('cost', $cost['method']);
							if ($known !== true) {
								$errors[$idx] = '1';
								$this->debug_log('*'.$known, 1);
							}
						}

						$rule_cost = apply_filters('wc_fns_calculate_cost_rule', $rule_cost, $cost, $shippable_contents_rule, $rule_groups, $this);
					}
				}
			}
			
			/*************************Special actions if there are any *************************/

			if (isset($shipping_rule['actions'])) {
				foreach ($shipping_rule['actions'] as $action) {
					if (is_array($action) && isset($action['method'])) {

						// Unknown method? Let's advice about it! (only if should write logs and once)
						$idx = 'action-' . $action['method'];
						if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
							$known = $Fish_n_Ships->is_known('action', $action['method']);
							if ($known !== true) {
								$errors[$idx] = '1';
								$this->debug_log('*'.$known, 1);
							}
						}
						
						$action_result = array( 
							
							'instance_id' => $this->instance_id,
							
							'abort' => false,			// true will abort this shipping
							'break' => false,			// true will ignore the next rules
							'skip_n' => $skip_n,		// support for skip N rules
							'jump_up_n'                => $jump_up_n, // support for repeat N previous rules

							'rule_cost' => $rule_cost,
							
							// Actions can use and overwrite this
							'rate' => $rate,
							'global_cost'              => $this->global_cost, //this rule_cost not added yet
							'shippable_contents_rule' => $shippable_contents_rule,
							'shippable_contents' => $shippable_contents,
							'rule_groups'              => $rule_groups, //please, unset the group class if you need to unset some register on it
							'post_fees'                => $post_fees
						);
						
						$action_result = apply_filters('wc_fns_apply_action', $action_result, $action, $this);
						
						// Apply the filtered values
						
						if ($action_result['abort']) {
							$active = false;
							$this->debug_log('*- Special action: [Abort method]', 1);
							break 2; // Exit two loops: actions and rules
						}

						$skip_n                   = $action_result['skip_n'];
						$jump_up_n                = $action_result['jump_up_n'];

						$rule_cost                = $action_result['rule_cost'];

						$rate                     = $action_result['rate'];
						$this->global_cost        = $action_result['global_cost'];
						$shippable_contents_rule  = $action_result['shippable_contents_rule'];
						$shippable_contents       = $action_result['shippable_contents'];
						$rule_groups              = $action_result['rule_groups'];
						$post_fees                = $action_result['post_fees'];

						if ($action_result['break']) {
							$break_pending = true;
							break; // Exit first loop only, I need to add the cost
						}
					}
				} // end loop actions

			}
			
			// Let's apply the cost: (bug introduced when extra fees added + cheaper/expensive only, solved on 1.4.3)
			if ($this->rules_charge == 'max' && $rule_type == 'normal') {
				// only most expensive rule
				if ($rule_cost > $this->global_cost) $this->global_cost = $rule_cost;
			
			} elseif ($this->rules_charge == 'min' && $rule_type == 'normal') {
				// only most cheap rule
				if ($rule_cost < $this->global_cost || $num_matches == 1) $this->global_cost = $rule_cost;
			
			} else {
				// all rules will be added
				$this->global_cost += $rule_cost;
			}
			if ( $rule_type != 'extra' ) {
				$this->debug_log('*- Calculated rule #' . $virtual_count . ' cost: ' . $Fish_n_Ships->unabstracted_price( $rule_cost ), 1);
			}
			$this->unset_groups($rule_groups);
			
			// We will jump up rules (rewind)? (since 1.4.13)
			if( $jump_up_n > 0)
			{
				$prevent_crash++;
				if ($prevent_crash > 100) {
					$this->debug_log('*- Error: [jump up rule] infinite loop prevented (ignored after 100 loops).', 1);
				}
				elseif( $skip_n > 0 )
				{
					$this->debug_log('*- Error: [jump up rule] ignored: incompatible with skip N rules', 1);					
				}
				elseif( $rule_pointer - $jump_up_n < -1 )
				{
					$this->debug_log('*- Error: [jump up rule] ignored: not enough rules to jump up (' . $jump_up_n . ')', 1);
				}
				else
				{
					// After normal loop pointer & count will be increased, so we will prevent it subtracting 1 more
					$rule_pointer   -= $jump_up_n + 1;
					$virtual_count  -= $jump_up_n + 1;

					$this->debug_log('*- Special action: [jump up N rules], we jump to rule: ' . ( $virtual_count + 1 ). '', 1);

					$jump_up_n = 0;
				}
					
				
			}
			
		} // end main loop rules
		
		$this->debug_log('#');
		
		// Disallow if shipping rate = 0?
		if ( $this->free_shipping == 'no' && $this->global_cost == 0 ) {
			$active = false;
			$this->debug_log('*Free shipping not allowed for this method.', 0);
		}
		
		if ($active) {
			
			// Main currency (for unsupported MC plugin, nor MC plugin or shipping settings has empty sufix)
			$curr_sufix = $this->get_currency_sufix_fields();
			
			if ( $curr_sufix == '' ) {
				$origin_costs_fields = 'main-currency';
			
			} else {
				// We are getting cart currency that isn't the main, let's update the main/legacy values:
				$origin_costs_fields = 'cart-currency';
				$this->min_shipping_price       = $this->get_option( 'min_shipping_price' . $curr_sufix);
				$this->max_shipping_price       = $this->get_option( 'max_shipping_price' . $curr_sufix );
			}

			// Finally maybe the global cost is less than the minimum or much than the maximum:
			if (trim($this->min_shipping_price) != '') {
				
				$min_shipping_price = $Fish_n_Ships->currency_abstraction ( $origin_costs_fields, $this->min_shipping_price );
				
				if ( $min_shipping_price > $this->global_cost ) {
					$this->global_cost = $min_shipping_price;
					$this->debug_log('Force minimum cost: ' . $this->global_cost, 1);
				}
			}

			// MAX shipping price field set as 0, will be ignored
			if ( trim($this->max_shipping_price) != '' && $this->max_shipping_price != 0 ) {
				
				$max_shipping_price = $Fish_n_Ships->currency_abstraction ( $origin_costs_fields, $this->max_shipping_price );
				
				if ( $max_shipping_price < $this->global_cost) {
					$this->global_cost = $max_shipping_price;
					$this->debug_log('Force maximum cost: ' . $this->global_cost, 1);
				}
			}
			$rate['cost'] += $this->global_cost;
			$this->add_rate( $rate );

			// We will disable other methods?
			if ( $rate['cost'] == 0 && $this->disallow_other == 'yes' && !defined('FNS_DISALLOW_OTHER_SET') ) {
				define ('FNS_DISALLOW_OTHER_SET', 1);
				WC()->session->set('fns_disallow_other', $this->instance_id );
				$this->debug_log('*Other methods will be disabled.', 0);
			}
						
			if ($this->write_logs === true) {
				$this->debug_log('*FINAL COST: ' . $Fish_n_Ships->unabstracted_price( $rate['cost'] ) . ' '
									. ($this->tax_status == 'taxable' ? ' + TAX' : ' [non-taxable]')
									. ($this->rules_charge == 'once' ? ' [only the most expensive rule applied]' : ''), 0);

				$this->log_totals['final_cost'] = $Fish_n_Ships->unabstracted_price( $rate['cost'] ) . ' ' . ($this->tax_status == 'taxable' ? ' + TAX' : ' [non-taxable]');
			}

		} else {
			
			$this->debug_log('*Method not applicable', 0);

			$this->log_totals['final_cost'] = '[non-applicable]';
		}
		
		if ($this->write_logs === true) {

			// There is some error? Let's advice it on summary log
			if ( count($errors) > 0 ) $this->log_totals['final_cost'] = '<strong>[ERROR]</strong> ' . $this->log_totals['final_cost'];
			
			// Calculate total resources used
			$kb = floor((memory_get_usage() - $this->log_totals['memory']) / 100) / 10;
			$secs = function_exists('microtime') ? substr(microtime(true) - $this->log_totals['time_start'], 0, 6) : ( time() - $this->log_totals['time_start']);
			$this->debug_log('Usage on calculation: Memory: [' . $kb . 'KB], DB queries: [' . ($wpdb->num_queries  - $this->log_totals['num_queries']) . '], Time elapsed: [' . $secs . ' sec.]', 0);
		
			// Save the log
			$this->save_debug_log();
		}

	}

	/**
	 * Get the global cost. Temporary, in the middle of the parsing rules process
	 *
	 * @since 1.4
	 *
	 * @param  array $rule_groups
	 */
	public function get_temp_global_cost() {
		
		return $this->global_cost;
	}
	
	/**
	 * The groups must be deleted and memory can be liberated.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $rule_groups
	 */
	public function unset_groups($rule_groups) {
				
		foreach ($rule_groups as $group_by) {
			foreach ($group_by as $group) {
				unset($group);
			}
		}
	}

	/**
	 * Store a new text line into logs array if log are activated.
	 *
	 * @since 1.0.0
	 *
	 * @param  text $message
	 * @param  integer $tab
	 */
	 public function debug_log($message, $tab = 0) {

		 if ($this->write_logs !== true) return;
		 
		 $this->log_calculate[] = str_repeat('  ', $tab) . sanitize_text_field($message);
	 }

	/**
	 * Save the debug log at end shipping calculation process
	 *
	 * @since 1.0.0
	 * @version 1.2.8
	 */
	 public function save_debug_log() {
		 
		 if ($this->write_logs !== true || count($this->log_calculate) == 0) return;
		 
		// Get the main list
		$logs_index = get_option('wc_fns_logs_index', array() );

		// Check if the last log is the same that current log
		$last = end($logs_index);
		if ( is_array($last) &&
			 $last['user_id']     == get_current_user_id() &&
			 $last['instance_id'] == $this->instance_id    &&
			 $last['final_cost']  == $this->log_totals['final_cost'] &&
			 $last['cart_qty']    == $this->log_totals['cart_qty']   &&
			 $last['time'] + 1    >= time() ) 
			 
			 return;
		 
		 // create an unique name
		 do {
			 $name = 'wc_fns_log_' . get_current_user_id() . '_' . $this->instance_id . '_' . rand(0, 9999999);
		} while (false !== get_transient($name) );
		
		// save log in transient
		set_transient($name, $this->log_calculate, DAY_IN_SECONDS * ( defined('WC_FNS_DAYS_LOG') ? WC_FNS_DAYS_LOG : 7 ) );
		
		$logs_index[] = array(
						'time' => time(),
						'user_id' => get_current_user_id(),
						'instance_id' => $this->instance_id,
						'name' => $name,
						'final_cost' => $this->log_totals['final_cost'],
						'cart_qty' => $this->log_totals['cart_qty'],
						);
		
		update_option('wc_fns_logs_index', $logs_index, false);
	 }

} // End WC_Fish_n_Ships class.
