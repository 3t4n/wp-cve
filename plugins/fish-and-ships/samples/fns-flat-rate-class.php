<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Products type / Products quantity',
		'priority'  => 10,
		'name'      => 'Flat rate when all products are of the same type',
		'case'      => 'It will give flat rate when all products belongs to the same shipping class. It needs two rules, that must be consecutive.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose the shipping class:'
						),
					),
		'note'		=> 'You can add a selector for a specific shipping class(es) restriction after that.',
		'only_pro'  => false,

		'config'    => array(
				
						'priority'  => 5,

						'rules'     => array(
						
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'not-in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array(),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' =>  $this->get_cost_zero(),
												'actions' => array(
													array(
														'method'  => 'skip',
														'values'  => array(
																		'steps' => 1
														)
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
																		'cost' => $this->loc_price(10)
														)
													)
												),
												'actions' => array(
													// Selector 1
													array(
														'method'   => 'break',
														'values'   => array()
													),
												),
											),
						),
		),
);
