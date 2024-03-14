<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Multiple conditions',
		'name'      => 'Disable shipping for large, heavy or bulky',
		'case'      => 'It will add three rules: first check if any product weighs more than ' . $this->loc_weight(50, true) . ', second check if any product measures more than ' . $this->loc_size(100, true) . ', and the third checks if the volume of any product exceeds ' . $this->loc_volume(1000000, true),
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 1,

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
												'cost' =>  $this->get_cost_zero(),
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

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
													'operators' => $this->get_operator_and(),
												),
												'cost' => $this->get_cost_zero(),
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(

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
													'operators' => $this->get_operator_and(),
												),
												'cost' => $this->get_cost_zero(),
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),
						),
		),
);
