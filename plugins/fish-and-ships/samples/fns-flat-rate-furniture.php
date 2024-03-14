<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Flat rate',
		'name'      => 'Flat rate when there are furniture products.',
		'case'      => 'We will send anything within a flat rate of ' . $this->loc_price(100, true) .' when there are furniture, looking for special shipping class to diferentiate it. Otherwise, we will charge for weight ranges',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose your furniture products shipping class:'
						),
					),
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag. <span class="fns-pro-icon">PRO</span>',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Flat rate 4 furnitures',
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

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( 'class' ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(100)
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
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
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
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(1),
																		'max_comp' => 'le',
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
																		'cost' => $this->loc_price(15)
														)
													)
												),
												'actions' => array(),
											),
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(2),
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(3),
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

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(3),
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(5),
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
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(5),
																		'max_comp' => 'le',
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
											)
						),
		),
);
