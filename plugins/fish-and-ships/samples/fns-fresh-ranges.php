<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Weight ranges',
		'name'      => 'Double weight ranges: fresh products pay more',
		'case'      => 'Fresh products will be sent separately, with another weight ranges, looking for shipping class to diferentiate it.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose your fresh products shipping class:'
						),
					),
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag. <span class="fns-pro-icon">PRO</span>',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Double weight ranges',
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
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_weight(1),
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(30)
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
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_weight(1),
																		'max_comp' => 'less',
																		'max'      => $this->loc_weight(2),
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(40)
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
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_weight(2),
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
																		'cost' => $this->loc_price(50)
																	 )
													)
												),
												
												'actions' => array(),
											),

											// Regla 4
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'not-in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_weight(1),
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

											// Regla 5
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'not-in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_weight(1),
																		'max_comp' => 'less',
																		'max'      => $this->loc_weight(2),
																		'group_by' => array( 'all' ), // all together, required
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
												
												'actions' => array(),
											),

											// Regla 6
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'not-in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_weight(2),
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
																		'cost' => $this->loc_price(30)
																	 )
													)
												),
												
												'actions' => array(),
											),
						),
		),

);
