<?php
if ( ! class_exists( 'NJBA_Row_Sep' ) ) {
	class NJBA_Row_Sep {
		/**
		 * NJBA_Row_Sep constructor.
		 */
		public function __construct() {
			require_once NJBA_MODULE_DIR . 'includes/row-settings.php';
			//add_action( 'wp_enqueue_scripts', [ $this, 'njbaRowStylesScriptsStyles' ] );
			add_action( 'fl_builder_before_render_row', [ $this, 'njbaDoBeforeRenderRow' ], 10, 2 );
			add_filter( 'fl_builder_render_css', [ $this, 'njbaAddRowStyleCss' ], 10, 3 );
			//$global_settings = FLBuilderModel::get_global_settings();

			// get the default settings
			$row_settings = FLBuilderModel::$settings_forms['row'];
			$new_tab      = array(
				'row_separator' => array(
					'title'    => __( 'NJBA Effects', 'bb-njba' ),
					'sections' => array(
						'style'           => array(
							'title'  => __( 'Row Separator', 'bb-njba' ),
							'fields' => array(
								'row_position' => array(
									'type'    => 'select',
									'label'   => __( 'Row Position', 'bb-njba' ),
									'default' => 'none',
									'options' => array(
										'none'   => __( 'None', 'bb-njba' ),
										'top'    => __( 'Top', 'bb-njba' ),
										'bottom' => __( 'Bottom', 'bb-njba' ),
									),
									'toggle'  => array(
										'none'   => array(
											'fields' => array()
										),
										'top'    => array(
											'sections' => array( 'njba_row_option' )
										),
										'bottom' => array(
											'sections' => array( 'njba_row_option' )
										)
									)
								),
							)
						),
						'njba_row_option' => array(
							'title'  => __( 'Row Separator Option', 'bb-njba' ),
							'fields' => array(
								'separator_shape'               => array(
									'type'    => 'select',
									'label'   => __( 'Type', 'bb-njba' ),
									'default' => 'triangle_svg',
									'options' => array(
										'triangle_svg'          => __( 'Triangle', 'bb-njba' ),
										'xlarge_triangle'       => __( 'Big Triangle', 'bb-njba' ),
										'xlarge_triangle_left'  => __( 'Big Triangle Left', 'bb-njba' ),
										'xlarge_triangle_right' => __( 'Big Triangle Right', 'bb-njba' ),
										'circle_svg'            => __( 'Half Circle', 'bb-njba' ),
										'xlarge_circle'         => __( 'Curve Center', 'bb-njba' ),
										'curve_up'              => __( 'Curve Left', 'bb-njba' ),
										'curve_down'            => __( 'Curve Right', 'bb-njba' ),
										'tilt_left'             => __( 'Tilt Left', 'bb-njba' ),
										'tilt_right'            => __( 'Tilt Right', 'bb-njba' ),
										'round_split'           => __( 'Round Split', 'bb-njba' ),
										'waves'                 => __( 'Waves', 'bb-njba' ),
										'clouds'                => __( 'Clouds', 'bb-njba' ),
										'multi_triangle'        => __( 'Multi Triangle', 'bb-njba' ),
										'simple'                => __( 'Simple', 'bb-njba' ),
									),
									'toggle'  => array(
										'triangle_svg'          => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'xlarge_triangle'       => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'xlarge_triangle_left'  => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'xlarge_triangle_right' => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'circle_svg'            => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'xlarge_circle'         => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'curve_up'              => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'curve_down'            => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'tilt_left'             => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'tilt_right'            => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'round_split'           => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'waves'                 => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'clouds'                => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color_opc'
											)
										),
										'multi_triangle'        => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color'
											)
										),
										'simple'                => array(
											'fields' => array(
												'separator_shape_height',
												'separator_shape_height_medium',
												'separator_shape_height_small',
												'njba_row_separator_color',
												'njba_row_separator_color'
											)
										),
									)
								),
								'separator_shape_height'        => array(
									'type'        => 'text',
									'label'       => __( 'Size', 'bb-njba' ),
									'default'     => '60',
									'description' => 'px',
									'maxlength'   => '3',
									'size'        => '6',
									'placeholder' => '60'
								),
								'separator_shape_height_medium' => array(
									'type'        => 'text',
									'label'       => __( 'Medium Device Size', 'bb-njba' ),
									'default'     => '',
									'description' => 'px',
									'maxlength'   => '3',
									'size'        => '6',
								),
								'separator_shape_height_small'  => array(
									'type'        => 'text',
									'label'       => __( 'Small Device Size', 'bb-njba' ),
									'default'     => '',
									'description' => 'px',
									'maxlength'   => '3',
									'size'        => '6',
								),
								'njba_row_separator_color'      => array(
									'type'       => 'color',
									'label'      => __( 'Background', 'bb-njba' ),
									'default'    => 'ffffff',
									'show_reset' => true,
									'help'       => __( 'Mostly, this should be background color of your adjacent row section. (Default - White)', 'bb-njba' ),
								),
								'njba_row_separator_color_opc'  => array(
									'type'        => 'text',
									'label'       => __( 'Opacity', 'bb-njba' ),
									'default'     => '100',
									'placeholder' => '100',
									'description' => '%',
									'maxlength'   => '3',
									'size'        => '5',
								),
							)
						)
					)
				)
			);
			array_insert( $row_settings['tabs'], $new_tab, 1 ); // insert the tab to the set position
			FLBuilder::register_settings_form( 'row', $row_settings );
		}

		/**
		 * enqueue njba row style
		 */
		/*public function njbaRowStylesScriptsStyles() {
			//wp_enqueue_style( 'njba-row-styles-css', NJBA_MODULE_URL . 'includes/row-settings.css', null , 'screen' );
		}*/

		/**
		 * include row settings css dynamic
		 *
		 * @param $css
		 * @param $nodes
		 * @param $global_settings
		 *
		 * @return string
		 */
		public function njbaAddRowStyleCss( $css, $nodes, $global_settings ) {
			ob_start();
			include NJBA_MODULE_DIR . 'includes/row-settings-css.php';
			$css .= ob_get_clean();

			return $css;
		}

		/**
		 * add rowstyle before adding the bg
		 *
		 * @param $row
		 * @param $groups
		 */
		public function njbaDoBeforeRenderRow( $row, $groups ) {
			if ( isset( $row->settings->row_position ) ) {
				add_action( 'fl_builder_before_render_row_bg', [ $this, 'njbaAddRowStyle' ] );
			}
		}

		/**
		 * @param $row_setting
		 */
		public function njbaAddRowStyle( $row_setting ) {
			$row = $row_setting->settings;
			if ( $row->row_position != 'none' ) {
				$row->separator_flag = $row->row_position;
				include NJBA_MODULE_DIR . 'includes/row-settings-html.php';
			}
		}
	}

	new NJBA_Row_Sep();
}
