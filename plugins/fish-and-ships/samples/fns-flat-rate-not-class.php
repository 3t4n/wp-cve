<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Products type / Products quantity',
		'name'      => 'Flat rate if there are NOT certain product type',
		'case'      => 'If there are NOT products of a certain shipping class the we will offer flat rate. It needs two rules, that must be consecutive.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose the exclusion shipping class:'
						),
					),
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag. <span class="fns-pro-icon">PRO</span>',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 1,

						'rules'     => array(

											array(
											
												'type' => 'normal',
												
												'sel' => array(

													array(
														'method'   => 'in-class',
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
