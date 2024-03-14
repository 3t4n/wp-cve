<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Cart subtotal',
		'name'      => 'Disable shipping for small purchases',
		'case'      => 'If the SUM of products price in cart is less than ' . $this->loc_price(50, true) . ', the method will not be offered (aborted).',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => 0,
																		'max_comp' => 'less',
																		'max'      => $this->loc_price(50),
																		'group_by' => array( 'all' ), // all together, required
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												'cost' => array(
													array(
														'method'  => 'once',
														'values'  => array(
																		'cost' => 0
																	 )
													)
												),
												'actions' => array(
													// Action 1
													array(
														'method'   => 'abort',
														'values'   => array()
													),
												),
											),
						),
		),
);
