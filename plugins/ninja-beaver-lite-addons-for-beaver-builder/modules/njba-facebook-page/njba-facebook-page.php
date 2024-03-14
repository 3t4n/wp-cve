<?php

/**
 * @class NJBA_FB_Page_Module
 */
class NJBA_FB_Page_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Facebook Page', 'bb-njba' ),
			'description'     => __( 'A module to embed Facebook page.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-facebook-page/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-facebook-page/',
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

FLBuilder::register_module( 'NJBA_FB_Page_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => $njba_fb_setting->njbaGetFbModuleDesc(),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'Page', 'bb-njba' ), // Section Title
				//'description'	=> $njba_fb_setting->njba_get_fb_app_id_documentation(),
				'fields' => array( // Section Fields
					'page_url'       => array(
						'type'        => 'text',
						'label'       => __( 'URL', 'bb-njba' ),
						'placeholder' => __( 'https://www.facebook.com/ninjabeaveraddon', 'bb-njba' ),
						'connections' => array( 'url' ),
					),
					'layout'         => array(
						'type'         => 'select',
						'label'        => __( 'Layout', 'bb-njba' ),
						'default'      => 'timeline',
						'options'      => array(
							'timeline' => __( 'Timeline', 'bb-njba' ),
							'events'   => __( 'Events', 'bb-njba' ),
							'messages' => __( 'Messages', 'bb-njba' ),
						),
						'multi-select' => true,
						'preview'      => array(
							'type' => 'none',
						),
					),
					'small_header'   => array(
						'type'    => 'select',
						'label'   => __( 'Small Header', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'cover'          => array(
						'type'    => 'select',
						'label'   => __( 'Show Cover Photo', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'profile_photos' => array(
						'type'    => 'select',
						'label'   => __( 'Show Page Profile Photos', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'cta'            => array(
						'type'    => 'select',
						'label'   => __( 'Show CTA Button', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'width'          => array(
						'type'        => 'text',
						'label'       => __( 'Page Width', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'default'     => '340',
						'size'        => 5,
					),
					'height'         => array(
						'type'        => 'text',
						'label'       => __( 'Page Height', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'default'     => '500',
						'size'        => 5,
					),
				),
			),
		),
	),
) );
