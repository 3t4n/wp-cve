<?php
/**
 * @class TNITProgressBar
 */

class TNITProgressBar extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Progress Bar', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-progress-bar/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-progress-bar/',
				'partial_refresh' => true,
			)
		);

	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {

		// Register and enqueue your own.
		$this->add_js( 'progressbar', $this->url . 'js/jQuery.progressbar.min.js', array(), '1.0.0', true );
	}

	/**
	 * Returns a gradient value string. Must be passed a
	 * gradient setting array from a gradient field.
	 *
	 * @since 1.1.3
	 * @param array $setting
	 * @return string
	 */
	function tnit_form_gradient( $setting ) {
		$gradient = '';
		$values   = array();

		$setting = json_decode( json_encode( $setting ), true );

		if ( ! is_array( $setting ) ) {
			return $gradient;
		}

		foreach ( $setting['colors'] as $i => $color ) {
			$stop = $setting['stops'][ $i ];

			if ( empty( $color ) ) {
				$color = 'rgba(255,255,255,0)';
			}
			if ( ! strstr( $color, 'rgb' ) ) {
				$color = '#' . $color;
			}
			if ( ! is_numeric( $stop ) ) {
				$stop = 0;
			}

			$values[] = $color . ' ' . $stop . '%';
		}

		$values = implode( ', ', $values );

		if ( 'linear' === $setting['type'] ) {
			if ( ! is_numeric( $setting['angle'] ) ) {
				$setting['angle'] = 0;
			}
			$gradient = 'linear-gradient(' . $setting['angle'] . 'deg, ' . $values . ')';
		} else {
			$gradient = 'radial-gradient(at ' . $setting['position'] . ', ' . $values . ')';
		}

		return $gradient;
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITProgressBar',
	array(
		'general'    => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'progressbar_style' => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'xpro-bb-addons' ),
							'default' => 'style-1',
							'options' => array(
								'style-1' => __( 'Style 1', 'xpro-bb-addons' ),
								'style-2' => __( 'Style 2', 'xpro-bb-addons' ),
								'style-3' => __( 'Style 3', 'xpro-bb-addons' ),
								'style-4' => __( 'Style 4', 'xpro-bb-addons' ),
								'style-5' => __( 'Style 5', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'style-1' => array(
									'fields' => array( 'title_spacing' ),
								),
								'style-2' => array(
									'fields' => array( 'title_spacing', 'value_bgcolor' ),
								),
								'style-3' => array(
									'fields' => array( 'value_bgcolor' ),
								),
								'style-4' => array(
									'fields' => array( 'title_spacing' ),
								),
								'style-5' => array(
									'fields' => array( 'value_bgcolor', 'title_spacing' ),
								),


							),
						),
						'progressbar_items' => array(
							'type'         => 'form',
							'label'        => __( 'Progress Bar', 'xpro-bb-addons' ),
							'form'         => 'progressbar_form',
							'preview_text' => 'progress_number',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'spacing_options' => array(
					'title'  => 'Items',
					'fields' => array(
						'items_bg_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'help'       => __( 'Background color for progress bar items.', 'xpro-bb-addons' ),
						),
						'item_spacing'   => array(
							'type'    => 'unit',
							'label'   => __( 'Item Spacing', 'xpro-bb-addons' ),
							'units'   => array( 'px' ),
							'default' => '50',
							'slider'  => true,
							'help'    => __( 'Spacing between progress bar items.', 'xpro-bb-addons' ),
						),
						'items_border'   => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
						),
						'items_padding'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
						),
					),
				),
				'progressbar'     => array(
					'title'  => __( 'Progress Bar', 'xpro-bb-addons' ),
					'fields' => array(
						'progressbar_thickness' => array(
							'type'    => 'unit',
							'label'   => __( 'Thickness', 'xpro-bb-addons' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'  => true,
							'help'    => 'This is the height of Progress Bar.',
						),
						'border'                => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
						),
					),
				),
				'colors'          => array(
					'title'  => 'Colors',
					'fields' => array(
						'progress_color_type'    => array(
							'type'    => 'button-group',
							'label'   => __( 'Progress Color Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'progress_color' ),
								),
								'gradient' => array(
									'fields' => array( 'progress_gradient' ),
								),
							),
						),
						'progress_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Progress Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'help'       => __( 'This color for Progresss Bar', 'xpro-bb-addons' ),
						),
						'progress_gradient'      => array(
							'type'  => 'gradient',
							'label' => __( 'Progress Gradient', 'xpro-bb-addons' ),
						),
						'progressbar_base_color' => array(
							'type'       => 'color',
							'label'      => __( 'Base Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'help'       => __( 'This color for Progress bar Base', 'xpro-bb-addons' ),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'title_typography' => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'title_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Title Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
						),
						'title_spacing'    => array(
							'type'    => 'unit',
							'label'   => __( 'Title Spacing', 'xpro-bb-addons' ),
							'units'   => array( 'px' ),
							'default' => '15',
							'slider'  => true,
							'help'    => 'Spacing between Title and Progress Bar',
						),
					),
				),
				'value_typography' => array(
					'title'  => __( 'Count', 'xpro-bb-addons' ),
					'fields' => array(
						'value_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'value_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Count Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
						),
						'value_bgcolor'    => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'help'       => __( 'This color goes behind the Count number.', 'xpro-bb-addons' ),
						),
					),
				),
				'desc_typography'  => array(
					'title'  => __( 'Description', 'xpro-bb-addons' ),
					'fields' => array(
						'descp_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'desc_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
						),
					),
				),
			),
		),
	)
);

// Form for Progress Bar.
FLBuilder::register_settings_form(
	'progressbar_form',
	array(
		'title' => __( 'Add ProgressBar', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => '',
						'fields' => array(
							'progress_number'   => array(
								'type'    => 'unit',
								'label'   => __( 'Progress Value', 'xpro-bb-addons' ),
								'default' => '80',
								'units'   => array( '%' ),
								'slider'  => true,
							),
							'progressbar_title' => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'xpro-bb-addons' ),
								'default'     => __( 'Progress Bar', 'xpro-bb-addons' ),
								'connections' => array( 'string', 'html' ),
							),
							'progressbar_des'   => array(
								'type'    => 'textarea',
								'label'   => __( 'Description', 'xpro-bb-addons' ),
								'default' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution.',
								'rows'    => '6',
							),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'colors' => array(
						'title'  => 'Colors',
						'fields' => array(
							'progress_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Progress Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'help'       => __( 'This color for Progresss Bar', 'xpro-bb-addons' ),
							),
							'progress_gradient'      => array(
								'type'  => 'gradient',
								'label' => __( 'Progress Gradient', 'xpro-bb-addons' ),
							),
							'progressbar_base_color' => array(
								'type'       => 'color',
								'label'      => __( 'Base Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'help'       => __( 'This color for Progress bar Base', 'xpro-bb-addons' ),
							),
						),
					),
				),
			),
		),
	)
);
