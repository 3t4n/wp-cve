<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Extra rates',
		'name'      => 'Global insurance: 1% over products + 1% over shipping',
		'case'      => 'Over the basic calculation of ' . $this->loc_price(5, true) . ' per product, we will charge the insurance over products and over calculated shipping, using separated rules for each overcharge. We will also add a subtitle about.',
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Global insurance',
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
														'method'   => 'always',
														'values'   => array()
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(
													array(
														'method'  => 'percent',
														'values'  => array(
																		'cost' => 1
																	 )
													)
												),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'description',
														'values'   => array(
																		'description'  => '1% global insurance added.',
																	  )
													),
												),
											),
											
											// Regla 3
											array(
											
												'type' => 'extra',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'always',
														'values'   => array()
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'ship_rate_pct',
														'values'   => array(
																		'ship_rate_pct'  => 1,
																	  )
													),
												),
											),

						),
		),

);
