<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'fullsamples',
		'section'   => 'Other ranges',
		'name'      => 'Length+Girth & weight limit',
		'case'      => 'For each L+G range we charge an amount, but if the weight exceeds a limit, we will charge the next range. This will be applied product per product.',
		'only_pro'  => true,
		'note'		=> 'Length+Girth ( L + 2W + 2H) it\'s a way to measure shipping boxes that some parcel companies use instead of volume.',
		
		'config'    => array(
				
						'priority'  => 10,

						'title' 					=> 'Length+Girth & weight',
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

													array(
														'method'   => 'lgirth-dimensions',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_size(100),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(2),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(8)
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'unset',
														'values'  => array()
													)
												),
											),
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'lgirth-dimensions',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_size(150),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(4),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(12)
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'unset',
														'values'  => array()
													)
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'lgirth-dimensions',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_size(200),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(6),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(18)
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'unset',
														'values'  => array()
													)
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'lgirth-dimensions',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_size(300),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => $this->loc_weight(10),
																		'group_by' => array( 'none' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => $this->loc_price(24)
														)
													)
												),
												'actions' => array(
													array(
														'method'  => 'unset',
														'values'  => array()
													)
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

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
																		'cost' => $this->loc_price(35)
														)
													)
												),
												'actions' => array(),
											),
						),
		),
);
