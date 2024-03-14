<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Extra rates',
		'name'      => 'Fresh products make shipping rates +50%',
		'case'      => 'Weight ranges based shipping rates, but fresh products increase the shipping, because force us to use an expensive carrier.',
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

						'title' 					=> 'Weight ranges, +50% fresh',
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
											
												'type' => 'extra',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'ship_rate_pct',
														'values'   => array(
																		'ship_rate_pct'  => 50,
																	  )
													),
												),
											),
						),
		),

);
