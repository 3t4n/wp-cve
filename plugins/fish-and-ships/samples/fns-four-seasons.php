<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Advanced',
		'name'      => 'Four seasons sample',
		'case'      => 'Depending on the season of year, the shipping rates will be distinct: Free shipping in Spring, ' . $this->loc_price(20, true) . ' Flat rate in Summer, Weight ranges in Autum, 10% of cart price in Winter.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Four seasons',
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
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '03-21',
																		'max_comp' => 'le',
																		'max' => '06-20',
																		'group_by' => array( 'all' ),
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
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Free shipping in Spring'
														)
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '06-21',
																		'max_comp' => 'le',
																		'max' => '09-22',
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(20)
														)
													)
												),
												
												'actions' => array(

													array(
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Flat rate in Summer'
														)
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '09-23',
																		'max_comp' => 'le',
																		'max' => '12-20',
																		'group_by' => array( 'all' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => 0,
																		'max_comp' => 'le',
																		'max' => $this->loc_weight(1),
																		'group_by' => array( 'all' ),
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
												
												'actions' => array(

													array(
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Autum price'
														)
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '09-23',
																		'max_comp' => 'le',
																		'max' => '12-20',
																		'group_by' => array( 'all' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min' => $this->loc_weight(1),
																		'max_comp' => 'le',
																		'max' => $this->loc_weight(3),
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(15)
														)
													)
												),
												
												'actions' => array(

													array(
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Autum price'
														)
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '09-23',
																		'max_comp' => 'le',
																		'max' => '12-20',
																		'group_by' => array( 'all' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min' => $this->loc_weight(3),
																		'max_comp' => 'le',
																		'max' => 0,
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(20)
														)
													)
												),
												
												'actions' => array(

													array(
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Autum price'
														)
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '12-21',
																		'max_comp' => 'le',
																		'max' => '12-31',
																		'group_by' => array( 'all' ),
																	  )
													),
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '01-01',
																		'max_comp' => 'le',
																		'max' => '03-20',
																		'group_by' => array( 'all' ),
																	  )
													),
													'operators' => array(
														array(
															'method'   => 'logical_operator',
															'values'   => array( 'or' )
														),
													),
												),
												
												'cost' => array(
													array(
														'method'  => 'percent',
														'values'  => array(
																		'cost' => 10
														)
													)
												),
												
												'actions' => array(

													array(
														'method'   => 'rename',
														'values'   => array(
																		'name' => 'Winter price'
														)
													),
												),
											),
						),
		),
);
