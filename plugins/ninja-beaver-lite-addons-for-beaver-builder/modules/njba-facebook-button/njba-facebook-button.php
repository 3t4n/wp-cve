<?php

/**
 * @class NJBA_FB_Button_Module
 */
class NJBA_FB_Button_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Facebook Button', 'bb-njba' ),
			'description'     => __( 'A module for fetch facebook button.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-facebook-button/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-facebook-button/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true
		) );
	}
}

/**
 * Register the module and its form settings.
 */
$njba_fb_setting = new NJBA_FB_Setting();
FLBuilder::register_module( 'NJBA_FB_Button_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => $njba_fb_setting->njbaGetFbModuleDesc(),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'Facebook Button', 'bb-njba' ), // Section Title
				//'description'	=> $njba_fb_setting->njba_get_fb_app_id_documentation(),
				'fields' => array( // Section Fields
					'button_type'  => array(
						'type'    => 'select',
						'label'   => __( 'Button Type', 'bb-njba' ),
						'default' => 'like',
						'options' => array(
							'like'      => __( 'Like', 'bb-njba' ),
							'recommend' => __( 'Recommend', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'layout'       => array(
						'type'    => 'select',
						'label'   => __( 'Button Layout', 'bb-njba' ),
						'default' => 'standard',
						'options' => array(
							'standard'     => __( 'Standard', 'bb-njba' ),
							'button'       => __( 'Button', 'bb-njba' ),
							'button_count' => __( 'Count Button', 'bb-njba' ),
							'box_count'    => __( 'Count Box', 'bb-njba' ),
						),
						'toggle'  => array(
							'standard' => array(
								'fields' => array( 'show_faces' ),
							),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'size'         => array(
						'type'    => 'select',
						'label'   => __( 'Button Size', 'bb-njba' ),
						'default' => 'small',
						'options' => array(
							'small' => __( 'Small', 'bb-njba' ),
							'large' => __( 'Large', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'color_scheme' => array(
						'type'    => 'select',
						'label'   => __( 'Button Color Theme', 'bb-njba' ),
						'default' => 'light',
						'options' => array(
							'light' => __( 'Light', 'bb-njba' ),
							'dark'  => __( 'Dark', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'show_share'   => array(
						'type'    => 'select',
						'label'   => __( 'Share Option', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'show_faces'   => array(
						'type'    => 'select',
						'label'   => __( 'Show Faces', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'url_type'     => array(
						'type'    => 'select',
						'label'   => __( 'Target URL', 'bb-njba' ),
						'default' => 'current_page',
						'options' => array(
							'current_page' => __( 'Current Page', 'bb-njba' ),
							'custom'       => __( 'Custom', 'bb-njba' ),
						),
						'toggle'  => array(
							'custom' => array(
								'fields' => array( 'url' ),
							),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'url'          => array(
						'type'        => 'text',
						'label'       => __( 'URL', 'bb-njba' ),
						'placeholder' => __( 'http://your-link.com', 'bb-njba' ),
						'connections' => array( 'url' ),
						'preview'     => array(
							'type' => 'none'
						)
					)
				)
			)
		)
	)
) );
