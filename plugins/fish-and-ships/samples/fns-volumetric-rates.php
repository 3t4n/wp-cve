<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Weight or Volumetric weight',
		'name'      => 'Volumetric weight ranges',
		'case'      => 'Three rules, one per each volumetric weight range, as example.',
		'note'		=> 'You can add a ranges or modify it after that.',
		'only_pro'  => true,
		
		'config'    => array(
				
						'priority'  => 10,
						'volumetric_weight_factor'  => $this->loc_volumetric(5000),

						'rules'     => array(

										array(
										
											'type' => 'normal',
											
											'sel' => array(

												array(
													'method'   => 'volumetric-set',
													'values'   => array(
																	'min_comp' => 'ge',
																	'min'      => 0,
																	'max_comp' => 'le',
																	'max'      => $this->loc_weight(100),
																	'group_by' => array( 'all' ), // all together, required
																  )
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
											'actions' => array(),
										),
										
										array(
										
											'type' => 'normal',
											
											'sel' => array(

												array(
													'method'   => 'volumetric-set',
													'values'   => array(
																	'min_comp' => 'greater',
																	'min'      => $this->loc_weight(100),
																	'max_comp' => 'le',
																	'max'      => $this->loc_weight(200),
																	'group_by' => array( 'all' ), // all together, required
													)
												),
												'operators' => $this->get_operator_and(),
											),
											'cost' => array(
												array(
													'method'  => 'once',
													'values'  => array(
																	'cost' => $this->loc_price(15)
													)
												)
											),
											'actions' => array(),
										),

										array(
										
											'type' => 'normal',
											
											'sel' => array(

												array(
													'method'   => 'volumetric-set',
													'values'   => array(
																	'min_comp' => 'greater',
																	'min'      => $this->loc_weight(200),
																	'max_comp' => 'le',
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
																	'cost' => $this->loc_price(20)
													)
												)
											),
											'actions' => array(),
										),

						),
		),
);
