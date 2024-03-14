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
		'name'      => 'Disable shipping method on Summer',
		'case'      => 'It will disable the method from June 21th to September 22th.',
		'note'		=> 'You can change it easily for Spring or Autum.',
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
																		'min' => '06-21',
																		'max_comp' => 'le',
																		'max' => '09-22',
																		'group_by' => array(),
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

						),
		),

);
