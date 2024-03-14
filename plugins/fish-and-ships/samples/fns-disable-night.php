<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Date & time',
		'priority'  => 10,
		'name'      => 'Disable shipping method at Night',
		'case'      => 'It will disable the method from 17:00 to 07:00h.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 1,

						'rules'     => array(

											// Friday
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'date-time',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => '17:00',
																		'max_comp' => 'le',
																		'max'      => '23:59',
																		'group_by' => array(), // all together, required
																	  )
													),
													array(
														'method'   => 'date-time',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => '00:00',
																		'max_comp' => 'less',
																		'max'      => '07:00',
																		'group_by' => array(), // all together, required
																	  )
													),
													'operators' => array(
														array(
															'method'   => 'logical_operator',
															'values'   => array( 'or' )
														),
													),
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
											
						),
		),

);
