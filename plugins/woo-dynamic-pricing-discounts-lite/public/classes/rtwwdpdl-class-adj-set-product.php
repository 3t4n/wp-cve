<?php
/**
 * Class RTWWDPDL_Adjustment_Set_Product to perform product rule based query.
 *
 * @since    1.0.0
 */
class RTWWDPDL_Adjustment_Set_Product extends RTWWDPDL_Adjustment_Set {
	/**
	 * variable to get target variation.
	 *
	 * @since    1.0.0
	 */
	public $target_variations;
	/**
	 * construct function.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $rtwwdpdl_set_id, $rtwwdpdl_set_data ) {
		parent::__construct( $rtwwdpdl_set_id, $rtwwdpdl_set_data );
		//Helper code to normalize the possibile variation arguments. 
		$rtwwdpdl_variations = false;
		if ( isset( $rtwwdpdl_set_data['variation_rules'] ) ) {
			$variation_rules = isset( $rtwwdpdl_set_data['variation_rules'] ) ? $rtwwdpdl_set_data['variation_rules'] : array();
			if ( isset( $variation_rules['args']['type'] ) && $variation_rules['args']['type'] == 'variations' ) {
				$rtwwdpdl_variations = isset( $variation_rules['args']['variations'] ) ? $variation_rules['args']['variations'] : array();
			}
		}

		$this->target_variations = apply_filters( 'rtwwdpdl_dynamic_pricing_get_adjustment_set_variations', $rtwwdpdl_variations, $this );
	}

}
