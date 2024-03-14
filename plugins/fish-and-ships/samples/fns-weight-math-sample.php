<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Weight ranges',
		'name'      => 'Weight ranges per math formula',
		'case'      => 'Define all ranges through two Math formulas: Up to ' . $this->loc_weight(10, true) . ' we charge ' . $this->loc_price(0.5, true) . ' per ' . $this->unit_weight() . ', from that weight onwards, we charge a fee of ' . $this->loc_price(3, true) . ' + ' . $this->loc_price(1.5, true) . ' per each 2' . $this->unit_weight(),
		'note'		=> 'You can change weight to volume, volumetric weight etc. and adjust ranges & prices.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Weight ranges',
						//'tax_status' 				=> 'taxable', // taxable | none 
						'global_group_by' 			=> 'yes',     // yes | no
						'global_group_by_method' 	=> 'all',  // none | id_sku | product_id | class | all
						'rules_charge' 				=> 'all',     // all| max | min
						'free_shipping' 			=> 'yes',     // yes | no
						'disallow_other' 			=> 'no',      // yes | no
						'volumetric_weight_factor'  => 0,
						'min_shipping_price' 		=> 0,
						'max_shipping_price' 		=> 0,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => 0,
																		'max_comp' => 'le',
																		'max' => $this->loc_price(10),
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'math',
														'values'   => array(
																		'expression'  => 'fee = 0;
range_price = '.$this->loc_price(0.5).';
range_weight = '.$this->loc_price(1).';

result = fee + ceil( weight / range_weight ) * range_price;',
																	  )
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min' => $this->loc_price(10),
																		'max_comp' => 'le',
																		'max' => 0,
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'math',
														'values'   => array(
																		'expression'  => 'fee = '.$this->loc_price(3).';
range_price = '.$this->loc_price(1.5).';
range_weight = '.$this->loc_price(2).';

result = fee + ceil( weight / range_weight ) * range_price;',
																	  )
													),
												),
											),

						),
		),
);
