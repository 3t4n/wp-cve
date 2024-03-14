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
		'name'      => '50% off for orders over ' . $this->loc_price(100, true),
		'case'      => '50% off over the calculated shipping rates when the cart products SUM ' . $this->loc_price(100, true) . ' or more.',
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
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp'	=> 'ge',
																		'min' 		=> $this->loc_price(100),
																		'max_comp'	=> 'le',
																		'max'		=> 0,
																		'group_by'	=> array( 'all' )
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
																		'ship_rate_pct'  => -50,
																	  )
													),
												),
											),
											
						),
		),

);
