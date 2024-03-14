<?php

/**
 * @class PPFBCommentsModule
 */
class PPFBCommentsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __( 'Facebook Comments', 'bb-powerpack' ),
			'description'   	=> __( 'A module to embed facebook comments.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-facebook-comments/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-facebook-comments/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PPFBCommentsModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'description'	=> pp_get_fb_module_desc(),
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'Comments Box', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'comments_number'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'Comment Count', 'bb-powerpack' ),
						'size'				=> 10,
						'default'			=> 10,
						'description' 		=> __( 'Minimum number of comments: 5', 'bb-powerpack' ),
					),
					'order_by'  => array(
						'type'			=> 'select',
						'label'         => __( 'Order By', 'bb-powerpack' ),
						'default'       => 'social',
						'options'       => array(
							'social'			=> __( 'Social', 'bb-powerpack' ),
							'reverse_time'      => __( 'Reverse Time', 'bb-powerpack' ),
							'time'      		=> __( 'Time', 'bb-powerpack' ),
						),
					),
					'url_type'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Target URL', 'bb-powerpack' ),
						'default'       => 'current_page',
						'options'       => array(
							'current_page'      => __( 'Current Page', 'bb-powerpack' ),
							'custom'			=> __( 'Custom', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'custom'	=> array(
								'fields'	=> array( 'url' ),
							),
						),
					),
					'url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'http://your-link.com', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
					),
					'width'	=> array(
						'type'			=> 'unit',
						'label'     	=> __( 'Width', 'bb-powerpack' ),
						'default'		=> '550',
						'units'			=> array( 'px', '%' ),
						'slider'		=> true,
					),
				),
			),
		),
	),
));
