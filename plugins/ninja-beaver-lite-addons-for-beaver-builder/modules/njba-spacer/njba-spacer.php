<?php

/**
 * @class NJBA_Spacer_Module
 */
class NJBA_Spacer_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Spacer', 'bb-njba' ),
			'description'     => __( 'Addon for leave some space.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-spacer/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-spacer/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => false, // Defaults to false and can be omitted.
		) );
	}

	/**
	 * Use this method to work with settings data before
	 * it is saved. You must return the settings object.
	 *
	 * @method update
	 * @param $settings {object}
	 *
	 * @return object
	 */
	public function update( $settings ) {
		return $settings;
	}

	/**
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
	}
}

FLBuilder::register_module( 'NJBA_Spacer_Module', array(
	'spacer_gap_general' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'spacer_gap_general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'desktop_space' => array(
						'type'        => 'text',
						'label'       => __( 'Desktop', 'bb-njba' ),
						'size'        => '8',
						'placeholder' => '10',
						'class'       => 'njba-spacer-desktop',
						'description' => 'px'
					),
					'medium_device' => array(
						'type'        => 'text',
						'label'       => __( 'Medium Device ( Tabs )', 'bb-njba' ),
						'default'     => '',
						'size'        => '8',
						'class'       => 'njba-spacer-medium-landscape',
						'description' => 'px'
					),
					'small_device'  => array(
						'type'        => 'text',
						'label'       => __( 'Small Device ( Mobile )', 'bb-njba' ),
						'default'     => '',
						'size'        => '8',
						'class'       => 'njba-spacer-mobile',
						'description' => 'px'
					),
				)
			)
		)
	)
) );
