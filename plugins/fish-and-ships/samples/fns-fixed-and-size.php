<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Advanced',
		'name'      => 'First product pays '.$this->loc_price(120, true).', and next ones pays according his size',
		'case'      => 'Not obvious solution, first product pays always the same: ' . $this->loc_price(120, true) . ' no matter his size, but next ones pay ' . $this->loc_price(30, true) . ' or ' . $this->loc_price(50, true) . ' according his maximum dimension.',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Fixed + size',
						//'tax_status' 				=> 'taxable', // taxable | none 
						'global_group_by' 			=> 'yes',     // yes | no
						'global_group_by_method' 	=> 'all',  // none | id_sku | product_id | class | all
						'rules_charge' 				=> 'all',     // all| max | min
						'free_shipping' 			=> 'no',     // yes | no
						'disallow_other' 			=> 'no',      // yes | no
						'volumetric_weight_factor'  => '',
						'min_shipping_price' 		=> 0,
						'max_shipping_price' 		=> 0,

						'rules'     => array(

											// Regla 1
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'always',
														'values'   => array()
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(90)
																	 )
													)
												),
												
												'actions' => array(),
											),

											// Regla 2
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'max-dimension',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_size(100),
																		'group_by' => array( 'all' ), // Maybe more than one option allowed?!
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'qty',
														'values'  => array(
																		'cost' => $this->loc_price(30)
																	 )
													)
												),
												
												'actions' => array(),
											),

											// Regla 3
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'max-dimension',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_size(100),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'all' ), // Maybe more than one option allowed?!
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'composite',
														'values'  => array(
																		'cost_once'    =>  $this->loc_price(-20),
																		'cost_qty'     =>  $this->loc_price(50),
																		'cost_weight'  =>  0,
																		'cost_group'   =>  0,
																		'cost_percent' =>  0,
																	 )
													)
												),
												
												'actions' => array(),
											),
						),
		),
);
