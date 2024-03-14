<?php
/**
 * Class RTWWDPDL_Adjustment_Set to perform discount query.
 *
 * @since    1.0.0
 */
class RTWWDPDL_Adjustment_Set {
	/**
	 * variable to set rule data.
	 *
	 * @since    1.0.0
	 */
	protected $rtwwdpdl_set_data;
	/**
	 * variable to set rule id.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_set_id;
	/**
	 * variable to set rule name.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_rule_name;
	/**
	 * variable to set rule mode.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_mode;
	/**
	 * variable to set pricing rule.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_pricing_rules;
	/**
	 * variable to set target product.
	 *
	 * @since    1.0.0
	 */
	public $rtwwdpdl_targets;
	/**
	 * construct function.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $rtwwdpdl_set_id, $rtwwdpdl_set_data, $rtwwdpdl_rule_name ) {
		
		$this->rtwwdpdl_set_id   = $rtwwdpdl_set_id;
		$this->rtwwdpdl_set_data = $rtwwdpdl_set_data;
		
		if ( isset( $rtwwdpdl_set_data['mode'] ) && $rtwwdpdl_set_data['mode'] == 'block' ) {
			$this->mode = 'block';

			if ( !empty( $rtwwdpdl_set_data['blockrules'] ) ) {
				$this->rtwwdpdl_pricing_rules = $rtwwdpdl_set_data['blockrules'];
			}
		} else {
			$this->rtwwdpdl_mode = 'bulk';

			$this->rtwwdpdl_pricing_rules = $rtwwdpdl_set_id['rules'];
			$this->rtwwdpdl_rule_name = $rtwwdpdl_rule_name;

		}
	}

	/**
	 * Function to confirm the product.
	 *
	 * @since    1.0.0
	 */
	public function is_targeted_product( $product_id, $variation_id = false ) {
		return false;
	}

	/**
	 * Function to confirm the user.
	 *
	 * @since    1.0.0
	 */
	public function is_valid_for_user() {
		$rtwwdpdl_result             = 0;
		$rtwwdpdl_pricing_conditions = $this->rtwwdpdl_set_data['conditions'];
		
		if ( is_array( $rtwwdpdl_pricing_conditions ) && sizeof( $rtwwdpdl_pricing_conditions ) > 0 ) {
			$rtwwdpdl_conditions_met = 0;

			foreach ( $rtwwdpdl_pricing_conditions as $condition ) {
				switch ( $condition['type'] ) {
					case 'apply_to':
						if ( is_array( $condition['args'] ) && isset( $condition['args']['applies_to'] ) ) {
							if ( $condition['args']['applies_to'] == 'everyone' ) {
								$rtwwdpdl_result = 1;
							} elseif ( $condition['args']['applies_to'] == 'unauthenticated' ) {
								if ( !is_user_logged_in() ) {
									$rtwwdpdl_result = 1;
								}
							} elseif ( $condition['args']['applies_to'] == 'authenticated' ) {
								if ( is_user_logged_in() ) {
									$rtwwdpdl_result = 1;
								}
							} elseif ( $condition['args']['applies_to'] == 'roles' && isset( $condition['args']['roles'] ) && is_array( $condition['args']['roles'] ) ) {
								if ( is_user_logged_in() ) {
									foreach ( $condition['args']['roles'] as $role ) {
										if ( current_user_can( $role ) ) {
											$rtwwdpdl_result = 1;
											break;
										}
									}
								}
							}
						}
						break;
					default:
						$rtwwdpdl_result = 0;
						break;
				}

				$rtwwdpdl_result = apply_filters( 'rtwwdpdl_woocommerce_dynamic_pricing_is_rule_set_valid_for_user', $rtwwdpdl_result, $condition, $this );

				$rtwwdpdl_conditions_met += $rtwwdpdl_result;
			}


			if ( $this->rtwwdpdl_set_data['conditions_type'] == 'all' ) {
				$rtwwdpdl_execute_rules = $rtwwdpdl_conditions_met == count( $rtwwdpdl_pricing_conditions );
			} elseif ( $this->rtwwdpdl_set_data['conditions_type'] == 'any' ) {
				$rtwwdpdl_execute_rules = $rtwwdpdl_conditions_met > 0;
			}
		} else {
			//empty conditions - default match, process price adjustment rules
			$rtwwdpdl_execute_rules = true;
		}

		if ( isset( $this->rtwwdpdl_set_data['date_from'] ) && isset( $this->rtwwdpdl_set_data['date_to'] ) ) {
			// Check date range

			$rtwwdpdl_from_date = empty( $this->rtwwdpdl_set_data['date_from'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $this->rtwwdpdl_set_data['date_from'] ), false ) );
			$rtwwdpdl_to_date   = empty( $this->rtwwdpdl_set_data['date_to'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $this->rtwwdpdl_set_data['date_to'] ), false ) );
			$rtwwdpdl_now       = current_time( 'timestamp' );

			if ( $rtwwdpdl_from_date && $rtwwdpdl_to_date && !( $rtwwdpdl_now >= $rtwwdpdl_from_date && $rtwwdpdl_now <= $rtwwdpdl_to_date ) ) {
				$rtwwdpdl_execute_rules = false;
			} elseif ( $rtwwdpdl_from_date && !$rtwwdpdl_to_date && !( $rtwwdpdl_now >= $rtwwdpdl_from_date ) ) {
				$rtwwdpdl_execute_rules = false;
			} elseif ( $rtwwdpdl_to_date && !$rtwwdpdl_from_date && !( $rtwwdpdl_now <= $rtwwdpdl_to_date ) ) {
				$rtwwdpdl_execute_rules = false;
			}
		}

		return $rtwwdpdl_execute_rules;
	}

	/**
	 * Function to confirm the discounting rule.
	 *
	 * @since    1.0.0
	 */
	public function get_collector() {
		return $this->rtwwdpdl_set_data['collector'];
	}

	/**
	 * Function to get the discounting rule object.
	 *
	 * @since    1.0.0
	 */
	public function get_collector_object() {
		return new RTWWDPDL_Dynamic_Pricing_Collector( $this->rtwwdpdl_set_data['collector'] );
	}

}
