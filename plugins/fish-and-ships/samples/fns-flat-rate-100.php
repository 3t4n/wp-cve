<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Cart subtotal',
		'priority'  => 10,
		'name'      => 'Flat rate when +' . $this->loc_price(100, true) . ' in cart',
		'case'      => 'It will add a rule that always give flat rate of ' . $this->loc_price(10, true) . ' when the products in cart SUM ' . $this->loc_price(100, true) . ' or more.',
		'only_pro'  => false,

		'config'    => array(
				
						'priority'  => 5,

						'rules'     => array(
											array(
												'type' => 'normal',
												'sel' => array(
															// Selector 1
															array(
																'method'   => 'by-price',
																'values'   => array(
																				'min_comp'	=> 'ge',
																				'min' 		=> $this->loc_price(100),
																				'max_comp'	=> 'le',
																				'max'		=> 0,
																				'group_by'	=> array( 'all' )
																)
															),
															// Operators for all selectors
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
															array(
																'method'  => 'break',
																'values'  => array()
															)
												),
											)
						),
		),
);
