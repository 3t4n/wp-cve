<?php
/**
 * @class NJBAOpeningTimeModule
 */

class NJBA_Opening_Hours_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Opening Hours', 'bb-njba' ),
			'description'     => __( 'Addon to display Office Opening Hours.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-opening-hours/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-opening-hours/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Opening_Hours_Module', array(
	'content'    => array( // Tab
		'title'    => __( 'Days', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'separator' => array(
				'title'  => '',
				'fields' => array(
					'day_panels' => array(
						'type'         => 'form',
						'label'        => __( 'Day', 'bb-njba' ),
						'form'         => 'timelistform',
						'preview_text' => 'day',
						'multiple'     => true
					),
				),
			),
		)
	),
	'styles'     => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'layout'         => array(
				'title'  => __( '', 'bb-njba' ),
				'fields' => array( // Section Fields
					'layout' => array(
						'type'    => 'select',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 'inline',
						'options' => array(
							'inline'  => __( 'Inline', 'bb-njba' ),
							'stacked' => __( 'Stacked', 'bb-njba' )
						),
						'toggle'  => array(
							'inline'  => array(
								'sections' => array( 'inline_border' )
							),
							'stacked' => array(
								'sections' => array( 'stacked_border' )
							),

						)
					),
				)
			),
			'inline_border'  => array(
				'title'  => __( 'Inline', 'bb-njba' ),
				'fields' => array( // Section Fields
					'border_style' => array(
						'type'    => 'select',
						'label'   => __( 'Border Bottom Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'border_width', 'border_color', 'box_padding' )
							),
							'dotted' => array(
								'fields' => array( 'border_width', 'border_color', 'box_padding' )
							),
							'dashed' => array(
								'fields' => array( 'border_width', 'border_color', 'box_padding' )
							),
							'double' => array(
								'fields' => array( 'border_width', 'border_color', 'box_padding' )
							),
						)
					),

					'border_width' => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => ''
					),
					'box_padding'  => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => ''
						),
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' ),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),

						)
					),
				)
			),
			'stacked_border' => array(
				'title'  => __( 'Stacked', 'bb-njba' ),
				'fields' => array( // Section Fields
					'stacked_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '3',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'stacked_border_height' => array(
						'type'        => 'text',
						'label'       => __( 'Border Height', 'bb-njba' ),
						'default'     => '50',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'stacked_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => ''
					),
					'stacked_box_padding'   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => ''
						),
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' ),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
						)
					),
				)
			)
		)
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'day_typography'  => array(
				'title'  => __( 'Day', 'bb-njba' ),
				'fields' => array(
					'day_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => 'span.njba-opening-day',
						)
					),
					'day_font_size' => array(
						'type'        => 'njba-simplify',
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => 'span.njba-opening-day',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'day_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'span.njba-opening-day',
							'property' => 'color',
						)
					),
					'day_margin'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',

						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
						)
					),
				)
			),
			'time_typography' => array(
				'title'  => __( 'Time', 'bb-njba' ),
				'fields' => array(
					'time_font'      => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-opening-hours span',
						)
					),
					'time_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '15',
							'medium'  => '15',
							'small'   => '15',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-opening-hours span',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'time_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-opening-hours span',
							'property' => 'color',
						)
					),
					'time_padding'   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',

						),
						'description' => __( 'px', 'bb-njba' ),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),

						)
					),
				)
			),
		)
	)
) );
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'timelistform', array(
	'title' => __( 'Days Panel', 'bb-njba' ),
	'tabs'  => array(
		'general' => array( // Tab
			'day'      => __( 'Days', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'content' => array(
					'day'    => '',
					'fields' => array(
						'day'            => array(
							'type'  => 'text',
							'label' => __( 'Day Name', 'bb-njba' ),

						),
						'time'           => array(
							'type'  => 'text',
							'label' => __( 'Time 1', 'bb-njba' ),

						),
						'time_2'         => array(
							'type'  => 'text',
							'label' => __( 'Time 2', 'bb-njba' ),

						),
						'time_separator' => array(
							'type'    => 'select',
							'label'   => __( 'Show Separator', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none' => 'None',
								'/'    => ' / ',
								'|'    => ' | ',
								'-'    => ' - '
							),
						),
					),
				),
			)
		),
	)
) );
