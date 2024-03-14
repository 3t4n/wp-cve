<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Weight or Volumetric weight',
		'name'      => 'Volumetric weight ranges per math formula',
		'case'      => 'Looking volumetric weight, up to ' . $this->loc_weight(10, true) . ' we charge ' . $this->loc_price(0.5, true) . ' per each 2' . $this->unit_weight() . ', from that weight onwards, we charge a fee of ' . $this->loc_price(3, true) . ' + ' . $this->loc_price(1.5, true) . ' per each 4' . $this->unit_weight(),
		'note'		=> 'All in two rules only. Later you can change the ranges, prices etc.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,
						'volumetric_weight_factor'  => $this->loc_volumetric(5000),

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'volumetric-set',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => 0,
																		'max_comp' => 'le',
																		'max' => $this->loc_weight(10),
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
range_vol_weight = '.$this->loc_price(2).';

result = fee + ceil( volumetric_set / range_vol_weight ) * range_price;',
																	  )
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'volumetric-set',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min' => $this->loc_weight(10),
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
range_vol_weight = '.$this->loc_price(4).';

result = fee + ceil( volumetric_set / range_vol_weight ) * range_price;',
																	  )
													),
												),
											),

						),
		),
);
