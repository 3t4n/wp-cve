<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Flat rate',
		'name'      => 'Flat rate on weekdays',
		'case'      => 'We offer 24h delivery flat rate from monday to friday at 16:00h. The standard price is ' . $this->loc_price(20, true) . ', but until thursday at 14:00h it costs ' . $this->loc_price(30, true) . '.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Flat rate on weekdays',
						//'tax_status' 				=> 'taxable', // taxable | none 
						'global_group_by' 			=> 'yes',     // yes | no
						'global_group_by_method' 	=> 'none',  // none | id_sku | product_id | class | all
						'rules_charge' 				=> 'all',     // all| max | min
						'free_shipping' 			=> 'no',     // yes | no
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
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(0,6),
																		'group_by' => array( 'none' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(5),
																		'group_by' => array( 'none' ),
																	  )
													),
													array(
														'method'   => 'date-time',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '16:00',
																		'max_comp' => 'le',
																		'max' => '23:59',
																		'group_by' => array( 'none' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(5),
																		'group_by' => array( 'none' ),
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
												
												'actions' => array(

													array(
														'method'   => 'break',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(4),
																		'group_by' => array( 'none' ),
																	  )
													),
													array(
														'method'   => 'date-time',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '14:00',
																		'max_comp' => 'le',
																		'max' => '23:59',
																		'group_by' => array( 'none' ),
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
												
												'actions' => array(

													array(
														'method'   => 'break',
														'values'   => array()
													),
												),
											),

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
																		'cost' => $this->loc_price(20)
														)
													)
												),
												
												'actions' => array(
												),
											),

						),
		),
);
