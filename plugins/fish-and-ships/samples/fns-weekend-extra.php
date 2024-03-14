<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Extra rates',
		'name'      => 'Extra fee on weekend and message about',
		'case'      => 'We will charge ' . $this->loc_price(5) . ' per each product. But from 17:00 of friday to 00:00 of monday, we will charge an extra fee of ' . $this->loc_price(3) . ' per each product, and we will advice our customers about.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Weekend extra',
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
														'method'  => 'qty',
														'values'  => array(
																		'cost' => $this->loc_price(5)
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
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(5),
																		'group_by' => array( 'all' ), // Maybe more than one option allowed?!
																	  )
													),
													array(
														'method'   => 'date-time',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => '17:00',
																		'max_comp' => 'le',
																		'max'      => '23:59',
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'qty',
														'values'  => array(
																		'cost' => $this->loc_price(3)
																	 )
													)
												),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'notice',
														'values'   => array(
																		'type'  => 'notice',
																		'message' => 'We add extra charges on weekend.',
																		'persistence' => 'sticky',
																		'allmethods'  => '1',
																	  )
													),
												),
											),
											
											// Regla 3
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(0,6),
																		'group_by' => array( 'all' ), // Maybe more than one option allowed?!
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'qty',
														'values'  => array(
																		'cost' => $this->loc_price(3)
																	 )
													)
												),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'notice',
														'values'   => array(
																		'type'  => 'notice',
																		'message' => 'We add extra charges on weekend.',
																		'persistence' => 'sticky',
																		'allmethods'  => '1',
																	  )
													),
												),
											),

						),
		),

);
