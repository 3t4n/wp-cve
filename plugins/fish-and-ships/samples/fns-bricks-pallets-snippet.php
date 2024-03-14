<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Dimensions or volume',
		'name'      => 'Each size of brick in a separate pallet',
		'case'      => 'For each size of brick, there is a different quantity per pallet, and they cannot be mixed. We charge ' . $this->loc_price(200,true) . ' for each pallet, and ' . $this->loc_price(150,true) . ' for each half pallet.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose your big-sized bricks shipping class:'
						),
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose your mid-sized bricks shipping class:'
						),
					),
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag. And also you can modify, duplicate the rules for more sizes, etc.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(0),
																		'group_by' => array( ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'math',
														'values'   => array(
																		'expression'  => 'pallet_price = ' . $this->loc_price(200) . ';
half_pallet_price = ' . $this->loc_price(150) . ';
qty_per_pallet = 40;

full_pallets_qty = round( (qty-1) / qty_per_pallet );
remaining_items = qty - full_pallets_qty * qty_per_pallet;
half_pallets_qty = ceil( remaining_items / ( qty_per_pallet / 2 ) );
result = full_pallets_qty * pallet_price + half_pallets_qty * half_pallet_price;',
																	  )
													),
													array(
														'method'   => 'unset',
														'values'   => array()
													),
												),
											),

											array(
											
												'type' => 'normal',
												
												'sel' => array(
													// Selector 1
													array(
														'method'   => 'in-class',
														'values'   => array(
																		'classes' => array(1),
																		'group_by' => array( ),
																	  )
													),
													'operators' => $this->get_operator_and(),
												),
												
												'cost' => array(),
												
												'actions' => array(

													array(
														'method'   => 'math',
														'values'   => array(
																		'expression'  => 'pallet_price = ' . $this->loc_price(200) . ';
half_pallet_price = ' . $this->loc_price(150) . ';
qty_per_pallet = 30;

full_pallets_qty = round( (qty-1) / qty_per_pallet );
remaining_items = qty - full_pallets_qty * qty_per_pallet;
half_pallets_qty = ceil( remaining_items / ( qty_per_pallet / 2 ) );
result = full_pallets_qty * pallet_price + half_pallets_qty * half_pallet_price;',
																	  )
													),
													array(
														'method'   => 'unset',
														'values'   => array()
													),
												),
											),

						),
		),
);
