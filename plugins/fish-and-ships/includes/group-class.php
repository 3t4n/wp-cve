<?php
/**
 * The auxiliary Fish_n_Ships_group class. 
 *
 * An instance of this class will group elements from the cart to posterior comparison on selection rules.
 * It allow cached calculation and re-calculation when the group content has changed
 *
 * @package Fish and Ships
 * @since 1.0.0
 * @version 1.1.9
 */

defined( 'ABSPATH' ) || exit;

class Fish_n_Ships_group {
	
	public $group_by             = NULL;     // the group_by criterion
	public $elements             = array();  // initially group elements will be here
	public $elements_unmatched   = array();  // but when another this or another group is unmatched, 
											 // coincident elements will be moved here
	
	public $or_flag              = false;    // this group matches in some selection condition,
											 // OR-associated with others in the same rule

	public $totals               = array();  // cached calculation
	public $changed              = false;    // should re-calculate?
	public $match                = true;     // group matching?
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct($group_by_mode, $shipping_class) {

		$this->group_by = $group_by_mode;
		$this->shipping_class = $shipping_class;
		$this->reset_totals();
	}
	
	/**
	 * Reset totals.
	 *
	 */
	public function reset_totals() {
		$this->totals = array();
	}

	public function get_elements() {
		return $this->elements;
	}

	public function is_empty() {
		return count($this->elements) == 0;
	}

	public function is_match() {
		return $this->match;
	}
	
	public function is_changed() {
		return $this->changed && count($this->elements) > 0;
	}

	public function reset_if_changed() {
		
		if ($this->changed) {
			$this->changed = false;
			$this->reset_totals();
		}
	}

	public function add_element($key, $element, $is_change = true) {
		
		// if there is not a change really, we don't do nothing
		if (!isset($this->elements[$key]) || $this->elements[$key] !== $element) {
			$this->elements[$key] = $element;
			$this->reset_totals();
		
			if ($is_change) $this->$changed = true;
		}
	}
	
	/**
	 * This group has been unmatched by selection rule directly
	 *
	 */
	public function unmatch_this_group() {
		
		$this->match = false;
		
		// Now we will move all the elements to unmatched
		// we use the overwrite feature of array_merge on coincident keys to avoid duplications
		$this->elements_unmatched = array_merge($this->elements_unmatched, $this->elements);
		$this->elements = array();
		$this->reset_totals();		
	}

	/**
	 * The elements comes from another group to be unmatched here. 
	 * Some product is removed? We will flag the change control and reset cached calculations.
	 *
	 */
	public function unmatch_elements($unmatched) {
				
		if ( count($this->elements) == 0) return;
		
		foreach ($unmatched as $un_el) {
			foreach ($this->elements as $key=>$el) {
				if ($un_el === $el) {
					$this->elements_unmatched[$key] = $el;
					unset($this->elements[$key]);
					
					//Only if really one element inside the group has been unmatched
					$this->changed = true;
					$this->reset_totals();
				}
			}
		}
	}

	/**
	 * Terms comparison. Maybe cached
	 *
	 */
	public function check_term($what, $taxonomy, $terms) {
		
		$index = $what.'-'.$taxonomy.'_'.implode('-',$terms);
		// not cached? look again
		if (!isset($this->totals[$index])) $this->look_for_term($what, $taxonomy, $terms);
		
		return $this->totals[$index];
	}
	
	public function look_for_term($what, $taxonomy, $terms) {

		global $Fish_n_Ships;

		$value = true;
		
		foreach ($this->elements as $product) {
			
			switch ($what) {

				case 'in-class':
					if ( count($terms)==0 || !$Fish_n_Ships->product_in_term($product, $taxonomy, $terms, $this->shipping_class) ) {
						$value = false;
						break; // stop iteration
					}
					break;

				case 'not-in-class':
					if ( count($terms)!=0 && $Fish_n_Ships->product_in_term($product, $taxonomy, $terms, $this->shipping_class) ) {
						$value = false;
						break; // stop iteration
					}
					break;

				default:
					$value = false;

					break;
			}

		}
		$this->shipping_class->debug_log('. Ungrouped, checking: #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) 
											. ', result: [' . ($value ? 'TRUE' : 'FALSE') . ']' , 3 );

		$index = $what.'-'.$taxonomy.'_'.implode('-',$terms);
		$this->totals[$index] = $value;
	}

	/**
	 * Totals calculation. Maybe cached
	 *
	 */

	public function get_total($what) {
		
		// not cached? recalculate
		if (!isset($this->totals[$what])) $this->calculate($what);
		
		return $this->totals[$what];
	}

	/**
	 * calculate one total
	 *
	 * @since 1.0.0
	 * @version 1.1.2
	 */

	public function calculate($what) {
		
		global $Fish_n_Ships;
		
		$value = 0;
		if ($this->group_by !== 'none') $this->shipping_class->debug_log('Grouped ['.$this->group_by.'] calculating: [' . $what . ']', 3);

		foreach ($this->elements as $product) {

			$qty = $Fish_n_Ships->get_quantity($product);
			
			//if group_by is set to none, the calculations for matching are based on one item
			$forced_to_1 = $qty > 1 && $this->group_by === 'none';
			if ($forced_to_1) $qty = 1;

			switch ($what) {
				
				/* In simple cases we use $item_value as decimal for calculation and logging. 
				   In complex cases, we use $item_value only for log purposes (text into) */
				
				case 'by-weight':
	
					$item_value = $Fish_n_Ships->get_weight($product);

					$value += $item_value * $qty;
					break;

				case 'by-price':

					$item_value = $Fish_n_Ships->get_price($product);
				
					$value += $item_value * $qty;
					break;

				case 'by-volume':
					
					$dimensions = $Fish_n_Ships->get_dimensions_ordered($product); // not needed ordering in this case
					$volume      = $dimensions[0] * $dimensions[1] * $dimensions[2];
					$item_value = $dimensions[0].'x'.$dimensions[1].'x'.$dimensions[2].'='.$volume; // just for log

					$value      += $volume * $qty;
					break;

				case 'min-dimension':

					$dimensions = $Fish_n_Ships->get_dimensions_ordered($product);
					$item_value = $dimensions[2];
					$value      = $item_value;
					break;

				case 'mid-dimension':

					$dimensions = $Fish_n_Ships->get_dimensions_ordered($product);
					$item_value = $dimensions[1];
					$value      = $item_value;
					break;

				case 'max-dimension':

					$dimensions = $Fish_n_Ships->get_dimensions_ordered($product);
					$item_value = $dimensions[0];
					$value      = $item_value;
					break;

				case 'quantity':

					$item_value = 1; // 1 * $qty = $qty (human logic, for log only)
					$value      += $qty;

					break;

				default:
					
					// external call, for 3rd party selection method
					$external = apply_filters('wc_fns_group_external_calculate', array(), $what, $product, $qty);
					
					$item_value  = isset($external['item_value']) ? $external['item_value'] : 'unknown selection method';
					$value       += isset($external['value'])     ? $external['value']      : 0;

					break;

			}

			if ($this->group_by === 'none') {
				
				// Non grouped? Let's show better
				$this->shipping_class->debug_log('. Ungrouped, calculate: #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) . ' (' . $item_value . ' * ' . $qty . ($forced_to_1 ? ' [forced]' : '') . ') accumulated: ' . $value, 3 );
			
			} else {
			
				$this->shipping_class->debug_log('+ #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) . ' (' . $item_value . ' * ' . $qty . ') accumulated: ' . $value, 4 );
			}
		}

		$this->totals[$what] = $value;
	}
}