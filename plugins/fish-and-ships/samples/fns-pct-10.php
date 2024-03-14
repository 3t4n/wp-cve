<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Always',
		'name'      => 'Shipping rate of 10%',
		'case'      => 'It will add a rule that always add 10% of products price. Other conditional rules can add other costs.',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 8,

						'rules'     => array(
											array(
												'type' => 'normal',
												'sel' => array(
															// Selector 1
															array(
																'method'   => 'always',
																'values'   => array()
															),
															// Operators for all selectors
															'operators' => $this->get_operator_and(),
												),
												'cost' => array(
															array(
																'method'  => 'percent',
																'values'  => array(
																				'cost' => 10
																			 )
															)
												),
												
												'actions' => array(),
											)
						),
		),
);
