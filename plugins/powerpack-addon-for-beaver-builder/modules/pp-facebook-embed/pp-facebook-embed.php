<?php

/**
 * @class PPFBEmbedModule
 */
class PPFBEmbedModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __( 'Facebook Embed', 'bb-powerpack' ),
			'description'   	=> __( 'A module to embed facebook post.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-facebook-embed/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-facebook-embed/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PPFBEmbedModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'description'	=> pp_get_fb_module_desc(),
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'Embed', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'embed_type'  => array(
						'type'			=> 'select',
						'label'         => __( 'Type', 'bb-powerpack' ),
						'default'       => 'post',
						'options'       => array(
							'post'			=> __( 'Post', 'bb-powerpack' ),
							'video'       	=> __( 'Video', 'bb-powerpack' ),
							'comment'      	=> __( 'Comment', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'post'		=> array(
								'fields'	=> array( 'post_url', 'show_text' ),
							),
							'video'	=> array(
								'fields'	=> array( 'video_url', 'show_text', 'video_allowfullscreen', 'video_autoplay', 'show_captions' ),
							),
							'comment'	=> array(
								'fields'	=> array( 'comment_url', 'include_parent' ),
							),
						),
					),
					'post_url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'https://www.facebook.com/example', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
					),
					'video_url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'https://www.facebook.com/example', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
					),
					'comment_url'	=> array(
						'type'          	=> 'text',
						'label'         	=> __( 'URL', 'bb-powerpack' ),
						'placeholder'		=> __( 'https://www.facebook.com/example', 'bb-powerpack' ),
						'connections'   	=> array( 'url' ),
					),
					'include_parent'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Parent Comment', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'show_text'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Full Post', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'video_allowfullscreen'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Allow Full Screen', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'video_autoplay'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Auto Play', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'show_captions'  => array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Captions', 'bb-powerpack' ),
						'default'       => 'no',
						'options'       => array(
							'yes'       	=> __( 'Yes', 'bb-powerpack' ),
							'no'			=> __( 'No', 'bb-powerpack' ),
						),
					),
					'width'	=> array(
						'type'          	=> 'unit',
						'label'         	=> __( 'Width', 'bb-powerpack' ),
						'units'				=> array( 'px' ),
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
