<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Products type / Products quantity',
		'name'      => 'Fragile products pay +10% as insurance',
		'case'      => 'We will add a 10% of the price of the fragile products, as insurance.',
		'choose'    => array(
						array(
							'type'   => 'shipping_class',
							'label'  => 'Choose your fragile products shipping class:'
						),
					),
		'note'		=> 'Later you can change the selection from shipping class to product category or product tag. <span class="fns-pro-icon">PRO</span>',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 5,

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
												'cost' => array(
													array(
														'method'  => 'percent',
														'values'  => array(
																		'cost' => 10
														)
													)
												),
												'actions' => array(),
											),
						),
		),
);
