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
		'name'      => 'Increase shipping rates by 10% on Weekend',
		'case'      => 'It will increase a 10% the calculated shipping rates from 17:00 of friday to 00:00 of monday.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 1,

						'rules'     => array(

											// Friday
											array(
											
												'type' => 'extra',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(5),
																		'group_by' => array(), // Maybe more than one option allowed?!
																	  )
													),
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
													'operators' => $this->get_operator_and(),
												),
												
												'cost' =>  $this->get_cost_zero(),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'ship_rate_pct',
														'values'   => array(
																		'ship_rate_pct'  => 10,
																	  )
													),
												),
											),
											
											// Saturday & Sunday
											array(
											
												'type' => 'extra',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'date-weekday',
														'values'   => array(
																		'date_weekday' => array(0,6),
																		'group_by' => array(), // Maybe more than one option allowed?!
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' =>  $this->get_cost_zero(),
												
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'ship_rate_pct',
														'values'   => array(
																		'ship_rate_pct'  => 10,
																	  )
													),
												),
											),

						),
		),

);
