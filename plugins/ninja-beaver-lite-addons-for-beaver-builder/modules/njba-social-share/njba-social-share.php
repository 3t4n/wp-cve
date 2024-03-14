<?php

/**
 * @class NJBA_Social_Share_Module
 */
class NJBA_Social_Share_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Social Share', 'bb-njba' ),
			'description'     => __( 'Addon for Share page content on social media.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-social-share/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-social-share/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Defaults to false and can be omitted.
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

FLBuilder::register_module( 'NJBA_Social_Share_Module', array(
	'social_shares' => array( // Tab
		'title'    => __( 'Social Share', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'title' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'social_icons' => array(
						'type'         => 'form',
						'label'        => __( 'Social Share', 'bb-njba' ),
						'form'         => 'njba_form_social_share',
						'preview_text' => 'social_share_type',
						'multiple'     => true
					),
				)
			),
		)
	),
	'style'         => array(
		'title'    => __( 'Style', 'bb-njba' ),
		'sections' => array(
			'general_style' => array(
				'title'  => '',
				'fields' => array(
					'icon_size'         => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => ''
						),
						'preview'     => array(
							'type' => 'refresh',
						)
					),
					'icon_line_height'  => array(
						'type'        => 'text',
						'label'       => __( 'Width / Height / Line Height', 'bb-njba' ),
						'placeholder' => '30',
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'preview'     => array(
							'type' => 'refresh',
						),
					),
					'share_icon_pos'    => array(
						'type'    => 'select',
						'label'   => __( 'Icon Structure', 'bb-njba' ),
						'default' => 'horizontal',
						'help'    => __( 'Set Your social share Structure', 'bb-njba' ),
						'options' => array(
							'horizontal' => __( 'Horizontal', 'bb-njba' ),
							'vertical'   => __( 'Vertical', 'bb-njba' )
						)
					),
					'icon_spacing'      => array(
						'type'        => 'text',
						'label'       => __( 'Spacing between element', 'bb-njba' ),
						'placeholder' => '20',
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'preview'     => array(
							'type' => 'refresh',
						),
					),
					'overall_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Position', 'bb-njba' ),
						'default' => 'center',
						'help'    => __( 'Icon Container position', 'bb-njba' ),
						'options' => array(
							'center' => __( 'Center', 'bb-njba' ),
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						)
					),
				)
			)
		)
	)
) );
FLBuilder::register_settings_form( 'njba_form_social_share', array(
	'title' => __( 'Add Social Icon/Image', 'bb-njba' ),
	'tabs'  => array(
		'form_general' => array(
			'title'    => __( 'General', 'bb-njba' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'social_share_type' => array(
							'type'    => 'select',
							'label'   => __( 'Social Share Type', 'bb-njba' ),
							'default' => 'facebook',
							'options' => array(
								'facebook'    => __( 'Facebook', 'bb-njba' ),
								'twitter'     => __( 'Twitter', 'bb-njba' ),
								'google'      => __( 'Google', 'bb-njba' ),
								'pinterest'   => __( 'Pinterest', 'bb-njba' ),
								'linkedin'    => __( 'LinkedIn', 'bb-njba' ),
								'digg'        => __( 'Digg', 'bb-njba' ),
								'blogger'     => __( 'Blogger', 'bb-njba' ),
								'reddit'      => __( 'Reddit', 'bb-njba' ),
								'stumbleupon' => __( 'StumbleUpon', 'bb-njba' ),
								'tumblr'      => __( 'Tumblr', 'bb-njba' ),
								'myspace'     => __( 'Myspace', 'bb-njba' ),
							)
						),
						'icon'              => array(
							'type'        => 'icon',
							'label'       => __( 'Share Icon', 'bb-njba' ),
							'show_remove' => true
						),
					)
				)
			)
		),
		'form_style'   => array( // Tab
			'title'    => __( 'Style', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'colors' => array( // Section
					'title'  => __( 'Colors', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'icon_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),
						'icon_hover_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),

						/* Background Color Dependent on Icon Style **/
						'icon_bg_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),
						'icon_bg_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),
					),
				),
				'transition' => array( // Section
					'title'  => __( 'Transition', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields	
						'icon_transition'     => array(
							'type'        => 'text',
							'label'       => __( 'Transition', 'bb-njba' ),
							'default'     => '0.3',
							'description' => 's',
							'maxlength'   => '3',
							'size'        => '5',
						),
					),
				),
				'border' => array( // Section
					'title'  => __( 'Border', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields	
						'img_icon_show_border'    => array(
							'type'    => 'select',
							'label'   => __( 'Display Border', 'bb-njba' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-njba' ),
								'no'  => __( 'No', 'bb-njba' )
							),
							'toggle'  => array(
								'yes' => array(
									'sections' => array(),
									'fields'   => array(
										'icon_border_width',
										'img_icon_border_radius',
										'img_icon_border_style',
										'icon_border_color',
										'icon_border_hover_color',
										'img_icon_bg_color',
										'img_icon_bg_color_opc',
										'img_icon_bg_hover_color',
										'img_icon_bg_hover_color_opc'
									)
								),
								'no'  => array(
									'sections' => array(),
									'fields'   => array()
								)
							),
						),
						'icon_border_width'       => array(
							'type'        => 'text',
							'label'       => __( 'Border Width', 'bb-njba' ),
							'default'     => '1',
							'description' => 'px',
							'maxlength'   => '3',
							'size'        => '5',
						),
						/*'img_icon_border_radius' => array(
							'type'        => 'text',
							'label'       => __('Border Radius', 'bb-njba'),
							'default'     => '5',
							'description' => 'px',
							'maxlength'   => '3',
							'size'        => '5',
						),*/
						'img_icon_border_radius'  => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Border Radius', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'topleft'     => 0,
								'topright'    => 0,
								'bottomleft'  => 0,
								'bottomright' => 0
							),
							'options'     => array(
								'topleft'     => array(
									'placeholder' => __( 'Top Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'topright'    => array(
									'placeholder' => __( 'Top Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottomleft'  => array(
									'placeholder' => __( 'Bottom Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'bottomright' => array(
									'placeholder' => __( 'Bottom Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)

							)
						),
						'img_icon_border_style'   => array(
							'type'    => 'select',
							'label'   => __( 'Border Style', 'bb-njba' ),
							'default' => 'solid',
							'options' => array(
								'none'   => __( 'None', 'bb-njba' ),
								'solid'  => __( 'Solid', 'bb-njba' ),
								'dotted' => __( 'Dotted', 'bb-njba' ),
								'dashed' => __( 'Dashed', 'bb-njba' ),
								'double' => __( 'Double', 'bb-njba' ),
							)
						),
						'icon_border_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),
						'icon_border_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						)
					)
				)
			)
		),
	)
) );
