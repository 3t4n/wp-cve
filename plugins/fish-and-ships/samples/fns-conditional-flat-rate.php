<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Flat rate',
		'name'      => 'Conditional Flat rate',
		'case'      => 'We offer flat rate when there isn\'t any product that exceeds ' . $this->loc_size(100, true) . ', the global weight are less than ' . $this->loc_weight(10, true) . ', the global products volume are less than ' . $this->loc_volume(500000,true) . ' and the price of the cart are more than ' . $this->loc_price(100, true) . '. In any other scenario, this method will not be offered.',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Conditional Flat rate',
						//'tax_status' 				=> 'taxable', // taxable | none 
						'global_group_by' 			=> 'yes',     // yes | no
						'global_group_by_method' 	=> 'all',  // none | id_sku | product_id | class | all
						'rules_charge' 				=> 'all',     // all| max | min
						'free_shipping' 			=> 'yes',     // yes | no
						'disallow_other' 			=> 'no',      // yes | no
						'volumetric_weight_factor'  => '',
						'min_shipping_price' 		=> 0,
						'max_shipping_price' 		=> 0,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'max-dimension',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_size(100),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'none' ), // none, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => 0
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'abort',
														'values'  => array()
													)
												),
											),
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_weight(10),
																		'group_by' => array( 'all' ), // all together, required
														)
													),
													array(
														'method'   => 'by-volume',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_volume(500000),
																		'group_by' => array( 'all' ), // all together, required
														)
													),
													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_price(100),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'all' ), // all together, required
														)
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(10)
														)
													)
												),
												'actions' => array(),
											),
						),
		),
);
