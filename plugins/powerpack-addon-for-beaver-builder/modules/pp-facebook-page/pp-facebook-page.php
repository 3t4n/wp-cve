<?php

/**
 * @class PPFBPageModule
 */
class PPFBPageModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __( 'Facebook Page', 'bb-powerpack' ),
			'description'   	=> __( 'A module to embed Facebook page.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-facebook-page/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-facebook-page/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PPFBPageModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'description'	=> pp_get_fb_module_desc(),
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'Page', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'page_url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'https://www.facebook.com/example', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
					),
					'layout'  => array(
						'type'			=> 'select',
						'label'         => __( 'Layout', 'bb-powerpack' ),
						'default'       => 'timeline',
						'options'       => array(
							'none'			=> __('None', 'bb-powerpack'),
							'timeline'		=> __( 'Timeline', 'bb-powerpack' ),
							'events'       	=> __( 'Events', 'bb-powerpack' ),
							'messages'      => __( 'Messages', 'bb-powerpack' ),
						),
						'multi-select'  => true,
						'preview'       	=> array(
							'type'      		=> 'none',
						),
					),
					'small_header'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Small Header', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'cover'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Cover', 'bb-powerpack' ),
						'default'       => 'yes',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'profile_photos'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Profile Photos', 'bb-powerpack' ),
						'default'       => 'yes',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'cta'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Custom CTA Button', 'bb-powerpack' ),
						'default'       => 'yes',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'width'	=> array(
						'type'			=> 'unit',
						'label'     	=> __( 'Width', 'bb-powerpack' ),
						'default'		=> '340',
						'units'			=> array( 'px' ),
						'slider'		=> array(
							'min'			=> '1',
							'max'			=> '2000',
							'step'			=> '50'
						),
					),
					'height'	=> array(
						'type'			=> 'unit',
						'label'     	=> __( 'Height', 'bb-powerpack' ),
						'default'		=> '500',
						'units'			=> array( 'px' ),
						'slider'		=> array(
							'min'			=> '1',
							'max'			=> '2000',
							'step'			=> '50'
						),
					),
				),
			),
		),
	),
));
