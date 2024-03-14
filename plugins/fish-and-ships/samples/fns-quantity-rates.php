<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Products type / Products quantity',
		'name'      => 'Shipping rates according to the quantity',
		'case'      => 'If there are up to 5 products, for every product the shipping rate is ' . $this->loc_price(10, true) . '. But if there are 6 or more, every product pays ' . $this->loc_price(8, true) . ' of shipping.',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'quantity',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'le',
																		'max'      => 5,
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'qty',
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
														'method'   => 'quantity',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 6,
																		'max_comp' => 'le',
																		'max'      => 0,
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'qty',
														'values'  => array(
																		'cost' => $this->loc_price(8)
																	 )
													)
												),
												'actions' => array(),
											),

						),
		),
);
