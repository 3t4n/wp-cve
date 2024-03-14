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
		'name'      => 'Disable shipping method on Winter',
		'case'      => 'It will disable the method from December 21th to March 20th.',

		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 1,

						'rules'     => array(
												
											array(
											
												'type' => 'normal',
												
												'sel' => array(
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '12-21',
																		'max_comp' => 'le',
																		'max' => '12-31',
																		'group_by' => array( 'all' ),
																	  )
													),
													array(
														'method'   => 'date-dayyear',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min' => '01-01',
																		'max_comp' => 'le',
																		'max' => '03-20',
																		'group_by' => array( 'all' ),
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
