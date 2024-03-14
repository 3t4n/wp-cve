<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Multiple conditions',
		'name'      => 'Add extra charge for large, heavy or bulky',
		'case'      => 'If any product weighs more than ' . $this->loc_weight(50, true) . ', measures more than ' . $this->loc_size(100, true) . ', or the volume of any product exceeds ' . $this->loc_volume(1000000, true) . ' an extra charge of ' . $this->loc_price(30, true) . ' will be added.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 5,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-weight',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_weight(50),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'none' ), // no grouping, required
																	  )
													),
													array(
														'method'   => 'max-dimension',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_size(100),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'none' ), // no grouping, required
																	  )
													),
													array(
														'method'   => 'by-volume',
														'values'   => array(
																		'min_comp' => 'greater',
																		'min'      => $this->loc_volume(1000000),
																		'max_comp' => 'less',
																		'max'      => 0,
																		'group_by' => array( 'none' ), // no grouping, required
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
