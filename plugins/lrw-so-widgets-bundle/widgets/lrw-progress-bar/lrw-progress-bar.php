<?php
/**
 * Widget Name: LRW - Progress Bar
 * Description: Animated skills progress bar.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Progress_Bar extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-progress-bar',
			__( 'LRW - Progress Bar', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Animate skills bar progress.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(
			),
			array(

				'trigger' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __( 'Trigger on Viewport', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Check this to trigger the counter on viewport or on pageload.', 'lrw-so-widgets-bundle' )
				),

				'values' => array(
					'type' => 'repeater',
					'label' => __( 'Value', 'lrw-so-widgets-bundle' ),
					'item_name' => __( 'Values', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter values for graph - value, title, color and unit.', 'lrw-so-widgets-bundle' ),
					'item_label' => array(
						'selector' => "[id*='values-label']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'fields' => array(

						'label' => array(
							'type' => 'text',
							'sanitize' => 'label',
							'label' => __( 'Label', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter text used as title of bar.', 'lrw-so-widgets-bundle' )
						),

						'label_color' => array(
							'type' => 'color',
							'label' => __( 'Label color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select label color.', 'lrw-so-widgets-bundle' )
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

						'bar_color' => array(
							'type' => 'color',
							'label' => __( 'Bar color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select single bar background color.', 'lrw-so-widgets-bundle' )
						),

						'unit' => array(
							'type' => 'text',
							'sanitize' => 'unit',
							'label' => __( 'Unit', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'lrw-so-widgets-bundle' )
						),

					),
				),

				'design_bar' => array(
					'type' => 'section',
					'label' => __( 'Skill bar options', 'lrw-so-widgets-bundle' ),
					'fields' => array(

						'bar_height' => array(
							'type' => 'measurement',
							'label' => __( 'Bar Height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Define the height for each individual skill bar.', 'lrw-so-widgets-bundle' ),
							'default' => '30px',
						),

						'bar_background' => array(
							'type' => 'color',
							'label' => __( 'Bar Background color', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select bar background color.', 'lrw-so-widgets-bundle' ),
						),

		                'bar_rounding' => array(
							'type' => 'select',
							'label' => __( 'Bar rounding', 'lrw-so-widgets-bundle' ),
							'default' => '0.25',
							'options' => array(
								'0' => __( 'None', 'lrw-so-widgets-bundle' ),
								'0.25' => __( 'Slightly rounded', 'lrw-so-widgets-bundle' ),
								'0.5' => __( 'Very rounded', 'lrw-so-widgets-bundle' ),
								'1.5' => __( 'Completely rounded', 'lrw-so-widgets-bundle' ),
							),
						),

						'bar_margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Bar margin bottom', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Define margin bottom between the skills bars.', 'lrw-so-widgets-bundle' ),
							'default' => '10px',
						),
				  	)
				),

				'design_label' => array(
					'type' => 'section',
					'label' => __( 'Label options', 'lrw-so-widgets-bundle' ),
					'fields' => array(
						'label_fontsize' => array(
							'type' => 'measurement',
							'label' => __( 'Label font size', 'lrw-so-widgets-bundle' ),
							'default' => '100%',
						),

						'label_fontweight' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Label font weight', 'lrw-so-widgets-bundle' ),
							'description' => __( '', 'lrw-so-widgets-bundle' )
						),

						'label_inner' => array(
							'type' => 'radio',
							'default' => 'no',
							'label' => __( 'Label within the bar', 'lrw-so-widgets-bundle' ),
							'state_emitter' => array(
		                        'callback' => 'select',
		                        'args' => array( 'label_inner' )
		                    ),
		                    'options' => array(
								'yes' => __( 'Yes', 'lrw-so-widgets-bundle' ),
								'no' => __( 'No', 'lrw-so-widgets-bundle' )
							),
						),

						'label_width' => array(
							'type' => 'measurement',
							'label' => __( 'Label width', 'lrw-so-widgets-bundle' ),
							'state_handler' => array(
								'label_inner[yes]' => array( 'show' ),
								'_else[label_inner]' => array( 'hide' ),
							),
							'hide'        => true,
							'default' => '120px',
							'description' => __( 'If necessary, set the width for the skill labels. Set 0 to auto width.', 'lrw-so-widgets-bundle' ),
						),

						'label_background_opacity' => array(
							'type' => 'checkbox',
							'default' => true,
							'state_handler' => array(
								'label_inner[yes]' => array( 'show' ),
								'_else[label_inner]' => array( 'hide' ),
							),
							'hide'        => true,
							'label' => __( 'Label background opacity', 'lrw-so-widgets-bundle' ),
							'description' => __( 'This active a background with opacity for label.', 'lrw-so-widgets-bundle' )
						),
					)
				),
			),

			plugin_dir_path( __FILE__ )
		);
	}

	function get_style_name( $instance ) {
		return 'style';
	}

	function get_template_name( $instance ) {
		return 'view';
	}


	function enqueue_frontend_scripts( $instance ) {
		if ( ! empty( $instance['trigger'] ) ) {
			wp_enqueue_script( 'waypoints', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/waypoints.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_scripts(
	        array(
	            array( 'progressbar', siteorigin_widget_get_plugin_dir_url( 'lrw-progress-bar' ) . 'assets/js/jquery.progressbar.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
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

		if ( ! empty( $instance['design_bar'] ) ) {
			$design_bar = $instance['design_bar'];

			if ( ! empty( $design_bar['bar_height'] ) ) {
				$less_vars['bar_height'] = $design_bar['bar_height'];
			}

			if ( ! empty( $design_bar['bar_background'] ) ) {
				$less_vars['bar_background'] = $design_bar['bar_background'];
			}

			if ( ! empty( $design_bar['bar_rounding'] ) ) {
				$less_vars['bar_rounding'] = $design_bar['bar_rounding'] . 'em';
			}
		}

		if ( ! empty( $instance['design_label'] ) ) {
			$design_label = $instance['design_label'];

			if ( ! empty( $design_label['label_fontsize'] ) ) {
				$less_vars['label_fontsize'] = $design_label['label_fontsize'];
			}

			if ( ! empty( $design_label['label_fontweight'] ) ) {
				$less_vars['label_fontweight'] = 'bold';
			} else {
				$less_vars['label_fontweight'] = 'normal';
			}

			if ( ! empty( $design_label['label_width'] ) ) {
				$less_vars['label_width'] = $design_label['label_width'];
			}

			if ( ! empty( $design_label['bar_margin_bottom'] ) ) {
				$less_vars['bar_margin_bottom'] = $design_label['bar_margin_bottom'];
			}

			if ( ! empty( $design_label['label_background_opacity'] ) ) {
				$less_vars['label_background_opacity'] = 'rgba(0, 0, 0, .1)';
			} else {
				$less_vars['label_background_opacity'] = 'transparent';
			}
		}

		return $less_vars;
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'trigger'		=> $instance['trigger'],
			'label_inner'	=> $instance['design_label']['label_inner']

        );
    }

}

siteorigin_widget_register( 'lrw-progress-bar', __FILE__, 'LRW_Widget_Progress_Bar' );
