<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Weight or Volumetric weight',
		'name'      => 'Add extra charge for heavy products',
		'case'      => 'If any product weighs more than ' . $this->loc_weight(50, true) . ' an extra charge of ' . $this->loc_price(30, true) . ' will be added.',
		'only_pro'  => false,
		
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
