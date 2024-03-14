<?php
/**
 * This is a sample for the Fish and Ships wizard
 *
 * @package Fish and Ships
 */
 
$sample = array(

		'tab'       => 'snippets',
		'section'   => 'Dimensions or volume',
		'name'      => 'Volume weight ranges',
		'case'      => 'Three rules, one per each volume weight range, as example.',
		'note'		=> 'You can add a ranges or modify it after that.',
		'only_pro'  => false,
		
		'config'    => array(
				
						'priority'  => 10,

						'rules'     => array(

										array(
										
											'type' => 'normal',
											
											'sel' => array(

												array(
													'method'   => 'by-volume',
													'values'   => array(
																	'min_comp' => 'ge',
																	'min'      => 0,
																	'max_comp' => 'le',
																	'max'      => $this->loc_volume(10000),
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
													'method'   => 'by-volume',
													'values'   => array(
																	'min_comp' => 'greater',
																	'min'      => $this->loc_volume(10000),
																	'max_comp' => 'le',
																	'max'      => $this->loc_volume(20000),
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
													'method'   => 'by-volume',
													'values'   => array(
																	'min_comp' => 'greater',
																	'min'      => $this->loc_volume(20000),
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
