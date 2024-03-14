<?php

/**
 * @class NJBA_FB_Embed_Module
 */
class NJBA_FB_Embed_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Facebook Embed', 'bb-njba' ),
			'description'     => __( 'A module to embed facebook post.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-facebook-embed/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-facebook-embed/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
	}
}

/**
 * Register the module and its form settings.
 */
$njba_fb_setting = new NJBA_FB_Setting();
FLBuilder::register_module( 'NJBA_FB_Embed_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => $njba_fb_setting->njbaGetFbModuleDesc(),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'Embed', 'bb-njba' ), // Section Title
				//'description'	=> $njba_fb_setting->njba_get_fb_app_id_documentation(),
				'fields' => array( // Section Fields
					'embed_type'            => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'post',
						'options' => array(
							'post'    => __( 'Post', 'bb-njba' ),
							'video'   => __( 'Video', 'bb-njba' ),
							'comment' => __( 'Comment', 'bb-njba' ),
						),
						'toggle'  => array(
							'post'    => array(
								'fields' => array( 'post_url', 'show_text' ),
							),
							'video'   => array(
								'fields' => array( 'video_url', 'show_text', 'video_allowfullscreen', 'video_autoplay', 'show_captions' ),
							),
							'comment' => array(
								'fields' => array( 'comment_url', 'include_parent' ),
							),
						),
					),
					'post_url'              => array(
						'type'        => 'text',
						'label'       => __( 'URL', 'bb-njba' ),
						'placeholder' => __( 'https://www.facebook.com/example', 'bb-njba' ),
						'connections' => array( 'url' ),
					),
					'video_url'             => array(
						'type'        => 'text',
						'label'       => __( 'URL', 'bb-njba' ),
						'placeholder' => __( 'https://www.facebook.com/example', 'bb-njba' ),
						'connections' => array( 'url' ),
					),
					'comment_url'           => array(
						'type'        => 'text',
						'label'       => __( 'URL', 'bb-njba' ),
						'placeholder' => __( 'https://www.facebook.com/example', 'bb-njba' ),
						'connections' => array( 'url' ),
					),
					'include_parent'        => array(
						'type'    => 'select',
						'label'   => __( 'Show Parent Comment', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'show_text'             => array(
						'type'    => 'select',
						'label'   => __( 'Full Post', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'video_allowfullscreen' => array(
						'type'    => 'select',
						'label'   => __( 'Allow Full Screen', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'video_autoplay'        => array(
						'type'    => 'select',
						'label'   => __( 'Auto Play', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'show_captions'         => array(
						'type'    => 'select',
						'label'   => __( 'Show Captions', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'width'                 => array(
						'type'        => 'text',
						'label'       => __( 'Width', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'size'        => 5,
					),
				),
			),
		),
	),
) );
