<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Advanced: Shipping boxes packer & messages',
		'name'      => 'Free shipping over ' . $this->loc_price(50, true) . ' with message from ' . $this->loc_price(30, true),
		'case'      => 'We want to offer free shipping when there are more than ' . $this->loc_price(50, true) . ' of products in cart. From ' . $this->loc_price(30, true) . ' we will show a message encouraging to buy more.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 5,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_price(30),
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
													array(
														'method'  => 'notice',
														'values'  => array(
																		'type' => 'notice',
																		'message' => 'Free shipping on purchases of ' . $this->loc_price(50, true) . ' or more!',
																		'persistence' => 'scrtchk',
																		'allmethods' => 1,
														)
													)
												),
											),
											
											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'by-price',
														'values'   => array(
																		'min_comp' => 'ge',
																		'min'      => $this->loc_price(50),
																		'max_comp' => 'less',
																		'max'      => 0,
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
													array(
														'method'  => 'rename',
														'values'  => array(
																		'name' => 'Free shipping',
														)
													),
													array(
														'method'  => 'break',
														'values'  => array()
													)
												),
											),
						),
		),
);
