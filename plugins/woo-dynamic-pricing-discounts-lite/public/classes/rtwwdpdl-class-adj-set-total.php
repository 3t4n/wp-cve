<?php
/**
 * Class RTWWDPDL_Adjustment_Set_Totals to perform discount rule based query.
 *
 * @since    1.0.0
 */
class RTWWDPDL_Adjustment_Set_Totals extends RTWWDPDL_Adjustment_Set {
	/**
	 * variable to get target product.
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
		parent::__construct( $rtwwdpdl_set_id, $rtwwdpdl_set_data, $rtwwdpdl_rule_name );

		//Normalize the targeted items for version differences.
		$rtwwdpdl_targets = false;
		if ( isset( $rtwwdpdl_set_data['targets'] ) ) {
			$rtwwdpdl_targets = $rtwwdpdl_set_data['targets'];
		} else {
			$rtwwdpdl_targets = array();
		}

		$this->rtwwdpdl_targets = apply_filters( 'rtwwdpdl_get_adjustment_set_targets', $rtwwdpdl_targets, $this );
	}

}
