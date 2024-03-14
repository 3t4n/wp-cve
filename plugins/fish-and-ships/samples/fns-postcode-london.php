<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Advanced',
		'name'      => 'Riders: ZIP / Post codes',
		'case'      => 'Our pizzeria is just in the middle of London. We offer delivery at ' . $this->loc_price(5, true) .' in the very closest area, and at ' . $this->loc_price(10, true) .' in the immediate concentric area. For addresses further afield we do not offer this service. There is a weight limit to ' . $this->loc_weight(10, true) .' anyway.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Riders delivery',
						//'tax_status' 				=> 'taxable', // taxable | none 
						'global_group_by' 			=> 'no',     // yes | no
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
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(10),
																		'max_comp' => 'le',
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
														'method'   => 'in-postal-codes',
														'values'   => array(
																		'in_postal_codes' => 'WC*',
																		'group_by' => array( 'all' ), // all together, required
														)
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_weight(5),
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'break',
														'values'  => array()
													)
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'in-postal-codes',
														'values'   => array(
																		'in_postal_codes' => 'W1*
NW1*
N1*
EC*
SE1*
SW1*',
																		'group_by' => array( 'all' ), // all together, required
														)
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_weight(10),
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'break',
														'values'  => array()
													)
												),
											),
											
						),
		),
);
