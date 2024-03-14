<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Products type / Products quantity',
		'name'      => 'Disable shipping per certain product type',
		'case'      => 'If there are products of a certain shipping class the shipping method will be not offered.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose the shipping class:'
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
