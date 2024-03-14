<?php
/**
 * Widget Name: LRW - Progress Bar Vertical
 * Description: A simple vertical animated progress bar.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Progress_Bar_Vertical extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-progress-bar-vert',
		  	__( 'LRW - Progress Bar Vertical', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'A simple vertical animated progress bar.', 'lrw-so-widgets-bundle' ),
				'panels_title' => 'title',
			),
		  	array(),
		  	array(

		  		'trigger' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __( 'Trigger on Viewport', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Check this to trigger the counter on viewport or on pageload.', 'lrw-so-widgets-bundle' )
				),

				'settings' => array(
					'type' => 'section',
					'label' => __( 'Settings', 'lrw-so-widgets-bundle' ),
					'fields' => array(
						'label' => array(
							'type' => 'text',
							'sanitize' => 'label',
							'label' => __( 'Label', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter text used as title of bar.', 'lrw-so-widgets-bundle' )
						),

						'value' => array(
							'type'        => 'slider',
							'label' => __( 'Value', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter value of bar.', 'lrw-so-widgets-bundle' ),
							'min'         => 0,
							'max'         => 100,
							'default'     => 100,
							'integer'     => true,
						),

						'unit' => array(
							'type' => 'text',
							'sanitize' => 'unit',
							'label' => __( 'Unit', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'lrw-so-widgets-bundle' )
						),
					)
				),

				'bar_design' => array(
					'type' => 'section',
					'label' => __( 'Bar design', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(

						'bar_height' => array(
							'type' => 'number',
							'label' => __( 'Bar height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a total bar height, in pixels. Default is 200px.', 'lrw-so-widgets-bundle' ),
						),

						'bar_color' => array(
							'type' => 'color',
							'label' => __( 'Bar color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select single bar background color.', 'lrw-so-widgets-bundle' )
						),

						'bar_background' => array(
							'type' => 'color',
							'label' => __( 'Bar Background color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select bar background color.', 'lrw-so-widgets-bundle' ),
						),

		                'bar_rounding' => array(
							'type' => 'select',
							'label' => __( 'Bar rounding', 'lrw-so-widgets-bundle' ),
							'default' => '0',
							'options' => array(
								'0' => __( 'None', 'lrw-so-widgets-bundle' ),
								'0.25' => __( 'Slightly rounded', 'lrw-so-widgets-bundle' ),
								'0.5' => __( 'Very rounded', 'lrw-so-widgets-bundle' ),
								'1.5' => __( 'Completely rounded', 'lrw-so-widgets-bundle' ),
							),
						)
				  	)
				),

				'value_design' => array(
					'type' => 'section',
					'label' => __( 'Value design', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(
						'vl_color' => array(
							'type' => 'color',
							'label' => __( 'Value text color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select value text color.', 'lrw-so-widgets-bundle' )
						),

						'vl_type' => array(
							'type' => 'select',
							'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
							'default' => 'h3',
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'vl_fontsize' => array(
							'type' => 'number',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a font size or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'vl_lineheight' => array(
							'type' => 'number',
							'label' => __( 'Line height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a line height or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'vl_fontweight' => array(
							'type' => 'number',
							'label' => __( 'Font Weight', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a font weight or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'vl_margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '20px',
						),

						'vl_margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '20px',
						),
				  	)
				),

				'label_design' => array(
					'type' => 'section',
					'label' => __( 'Label design', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(
						'lb_color' => array(
							'type' => 'color',
							'label' => __( 'Label text color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select label text color.', 'lrw-so-widgets-bundle' )
						),

						'lb_type' => array(
							'type' => 'select',
							'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
							'default' => 'h5',
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'lb_fontsize' => array(
							'type' => 'number',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a font size or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'lb_lineheight' => array(
							'type' => 'number',
							'label' => __( 'Line height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a line height or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'lb_fontweight' => array(
							'type' => 'number',
							'label' => __( 'Font Weight', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a font weight or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'lb_align' => array(
							'type' => 'select',
							'label' => __( 'Text align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						'lb_margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '20px',
						),

						'lb_margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '20px',
						),
				  	)
				),
			),

		  	plugin_dir_path( __FILE__ )
		);
	}


	function get_template_name( $instance ) {
		return 'view';
	}

	function get_style_name( $instance ) {
		return 'style';
	}

	function enqueue_frontend_scripts( $instance ) {
		if ( ! empty( $instance['trigger']) ) {
			wp_enqueue_script( 'waypoints', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/waypoints.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_scripts(
	        array(
	            array( 'progressbarvert', siteorigin_widget_get_plugin_dir_url( 'lrw-progress-bar-vert' ) . 'assets/js/jquery.progressbarvert.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
	        )
	    );
	}

	/**
	 * The less variables to control the design of the slider
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	function get_less_variables( $instance ) {

		$less_vars = array();

		if ( ! empty( $instance['bar_design'] ) ) {
			$bar_design = $instance['bar_design'];

			if ( ! empty( $bar_design['bar_height'] ) ) {
				$less_vars['bar_height'] = $bar_design['bar_height'] . 'px';
			}

			if ( ! empty( $bar_design['bar_color'] ) ) {
				$less_vars['bar_color'] = $bar_design['bar_color'];
			}

			if ( ! empty( $bar_design['bar_background'] ) ) {
				$less_vars['bar_background'] = $bar_design['bar_background'];
			}

			if ( ! empty( $bar_design['bar_rounding'] ) ) {
				$less_vars['bar_rounding'] = $bar_design['bar_rounding'] . 'em';
			}
		}

		if ( ! empty( $instance['value_design'] ) ) {
			$value_design = $instance['value_design'];

			if ( ! empty( $value_design['vl_color'] ) ) {
				$less_vars['vl_color'] = $value_design['vl_color'];
			}

			if ( ! empty( $value_design['vl_fontweight'] ) ) {
				$less_vars['vl_fontweight'] = 'bold';
			} else {
				$less_vars['vl_fontweight'] = 'normal';
			}

			if ( ! empty( $value_design['vl_margin_top'] ) ) {
				$less_vars['vl_margin_top'] = $value_design['vl_margin_top'];
			}

			if ( ! empty( $value_design['vl_margin_bottom'] ) ) {
				$less_vars['vl_margin_bottom'] = $value_design['vl_margin_bottom'];
			}
		}

		if ( ! empty( $instance['label_design'] ) ) {
			$label_design = $instance['label_design'];

			if ( ! empty( $label_design['lb_color'] ) ) {
				$less_vars['lb_color'] = $label_design['lb_color'];
			}

			if ( ! empty( $label_design['lb_fontweight'] ) ) {
				$less_vars['lb_fontweight'] = 'bold';
			} else {
				$less_vars['lb_fontweight'] = 'normal';
			}

			if ( ! empty( $label_design['lb_align'] ) ) {
				$less_vars['lb_align'] = $label_design['lb_align'];
			}

			if ( ! empty( $label_design['lb_margin_top'] ) ) {
				$less_vars['lb_margin_top'] = $label_design['lb_margin_top'];
			}

			if ( ! empty( $label_design['lb_margin_bottom'] ) ) {
				$less_vars['lb_margin_bottom'] = $label_design['lb_margin_bottom'];
			}
		}

		return $less_vars;
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'trigger'		=> $instance['trigger'],
			'label'			=> $instance['settings']['label'],
			'value'			=> $instance['settings']['value'],
			'unit'			=> $instance['settings']['unit'],
			'vl_type'		=> $instance['value_design']['vl_type'],
			'vl_fontsize'	=> $instance['value_design']['vl_fontsize'],
			'vl_lineheight'	=> $instance['value_design']['vl_lineheight'],
			'vl_fontweight'	=> $instance['value_design']['vl_fontweight'],
			'lb_type' 		=> $instance['label_design']['lb_type'],
			'lb_fontsize'	=> $instance['label_design']['lb_fontsize'],
			'lb_lineheight'	=> $instance['label_design']['lb_lineheight'],
			'lb_fontweight'	=> $instance['label_design']['lb_fontweight']
        );
    }
}

siteorigin_widget_register( 'lrw-progress-bar-vert', __FILE__, 'LRW_Widget_Progress_Bar_Vertical' );
