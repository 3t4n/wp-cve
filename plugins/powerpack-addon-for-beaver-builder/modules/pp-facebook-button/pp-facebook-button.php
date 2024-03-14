<?php

/**
 * @class PPFBButtonModule
 */
class PPFBButtonModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __( 'Facebook Button', 'bb-powerpack' ),
			'description'   	=> __( 'A module for fetch facebook button.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-facebook-button/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-facebook-button/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PPFBButtonModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'description'	=> pp_get_fb_module_desc(),
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'Button', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'button_type'  => array(
						'type'			=> 'select',
						'label'         => __( 'Type', 'bb-powerpack' ),
						'default'       => 'like',
						'options'       => array(
							'like'			=> __( 'Like', 'bb-powerpack' ),
							'recommend'		=> __( 'Recommend', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'recommend'	=> array(
								'fields'	=> array( 'show_share' , 'url_type' ),
							),
							'like'	=> array(
								'fields'	=> array( 'show_share', 'url_type' ),
							),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'layout'  => array(
						'type'			=> 'select',
						'label'         => __( 'Layout', 'bb-powerpack' ),
						'default'       => 'standard',
						'options'       => array(
							'standard'		=> __( 'Standard', 'bb-powerpack' ),
							'button'		=> __( 'Button', 'bb-powerpack' ),
							'button_count'	=> __( 'Button Count', 'bb-powerpack' ),
							'box_count'     => __( 'Box Count', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'standard'	=> array(
								'fields'	=> array( 'show_faces' ),
							),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'size'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Size', 'bb-powerpack' ),
						'default'       => 'small',
						'options'       => array(
							'small'       	=> __( 'Small', 'bb-powerpack' ),
							'large'			=> __( 'Large', 'bb-powerpack' ),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'color_scheme'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Color Scheme', 'bb-powerpack' ),
						'default'       => 'light',
						'options'       => array(
							'light'       	=> __( 'Light', 'bb-powerpack' ),
							'dark'			=> __( 'Dark', 'bb-powerpack' ),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'show_share'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Share Button', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'show_faces'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Faces', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'url_type'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Target URL', 'bb-powerpack' ),
						'default'       => 'current_page',
						'options'       => array(
							'current_page'	=> __( 'Current Page', 'bb-powerpack' ),
							'custom'		=> __( 'Custom', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'custom'	=> array(
								'fields'	=> array( 'url' ),
							),
						),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
					'url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'http://your-link.com', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
						'preview'			=> array(
							'type'				=> 'none'
						)
					),
					'alignment'	=> array(
						'type'		=> 'align',
						'label'		=> __('Alignment', 'bb-powerpack'),
						'default'	=> 'left',
						'preview'	=> array(
							'type'		=> 'css',
							'selector'	=> '.fl-module-content',
							'property'	=> 'text-align'
						)
					)
				),
			),
		),
	),
));
