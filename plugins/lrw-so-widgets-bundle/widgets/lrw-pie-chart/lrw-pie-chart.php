<?php
/**
 * Widget Name: LRW - Pie Chart
 * Description: Pie Chart.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Pie_Chart extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-pie-chart',
			__( 'LRW - Pie Chart', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Pie Chart.', 'lrw-so-widgets-bundle' ),
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

				'value' => array(
					'type'        => 'slider',
					'label' => __( 'Value', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter value of chart.', 'lrw-so-widgets-bundle' ),
					'min'         => 0,
					'max'         => 100,
					'integer'     => false,
				),

				'unit' => array(
					'type' => 'text',
					'sanitize' => 'unit',
					'label' => __( 'Unit', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'lrw-so-widgets-bundle' )
				),

				'settings' => array(
					'type' => 'section',
				  	'label' => __( 'Settings', 'lrw-so-widgets-bundle' ),
				  	'hide' => true,
				  	'fields' => array(

				  		'e_easing' => array(
							'type' => 'radio',
							'default' => 'yes',
							'label' => __( 'Enable Easing transitions?', 'lrw-so-widgets-bundle' ),
							'state_emitter' => array(
		                        'callback' => 'select',
		                        'args' => array( 'e_easing' )
		                    ),
		                    'options' => array(
								'yes' => __( 'Yes', 'lrw-so-widgets-bundle' ),
								'no' => __( 'No', 'lrw-so-widgets-bundle' )
							)
						),

						'easing' => array(
							'type' => 'select',
							'label' => __( 'Easing', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Easing function or string with the name of a <a href="http://gsgd.co.uk/sandbox/jquery/easing/" target="_blank">jQuery easing function</a>', 'lrw-so-widgets-bundle' ),
							'state_handler' => array(
								'e_easing[yes]' => array( 'show' ),
								'_else[e_easing]' => array( 'hide' ),
							),
							'hide'        => true,
							'options' => array(
								'jswing' 			=> __( 'jswing' ),
								'def' 				=> __( 'def' ),
								'easeInQuad' 		=> __( 'easeInQuad' ),
								'easeOutQuad' 		=> __( 'easeOutQuad' ),
								'easeInOutQuad' 	=> __( 'easeInOutQuad' ),
								'easeInCubic' 		=> __( 'easeInCubic' ),
								'easeOutCubic' 		=> __( 'easeOutCubic' ),
								'easeInOutCubic' 	=> __( 'easeInOutCubic' ),
								'easeInQuart' 		=> __( 'easeInQuart' ),
								'easeOutQuart'		=> __( 'easeOutQuart' ),
								'easeInOutQuart' 	=> __( 'easeInOutQuart' ),
								'easeInQuint' 		=> __( 'easeInQuint' ),
								'easeOutQuint' 		=> __( 'easeOutQuint' ),
								'easeInOutQuint' 	=> __( 'easeInOutQuint' ),
								'easeInSine' 		=> __( 'easeInSine' ),
								'easeOutSine' 		=> __( 'easeOutSine' ),
								'easeInOutSine' 	=> __( 'easeInOutSine' ),
								'easeInExpo' 		=> __( 'easeInExpo' ),
								'easeOutExpo' 		=> __( 'easeOutExpo' ),
								'easeInOutExpo' 	=> __( 'easeInOutExpo' ),
								'easeInCirc' 		=> __( 'easeInCirc' ),
								'easeOutCirc' 		=> __( 'easeOutCirc' ),
								'easeInOutCirc' 	=> __( 'easeInOutCirc' ),
								'easeInElastic' 	=> __( 'easeInElastic' ),
								'easeOutElastic' 	=> __( 'easeOutElastic' ),
								'easeInOutElastic' 	=> __( 'easeInOutElastic' ),
								'easeInBack' 		=> __( 'easeInBack' ),
								'easeOutBack' 		=> __( 'easeOutBack' ),
								'easeInOutBack' 	=> __( 'easeInOutBack' ),
								'easeInBounce' 		=> __( 'easeInBounce' ),
								'easeOutBounce' 	=> __( 'easeOutBounce' ),
								'easeInOutBounce' 	=> __( 'easeInOutBounce' )
							),
						),

						'scalelength' => array(
							'type'        => 'slider',
							'label' => __( 'Scale Length', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Length of the scale lines (note: reduces the radius of the chart).', 'lrw-so-widgets-bundle' ),
							'min'         => 0,
							'max'         => 100,
							'integer'     => false,
						),

						'linecap' => array(
							'type' => 'select',
							'label' => __( 'Line Cap', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Defines how the ending of the bar line looks like.', 'lrw-so-widgets-bundle' ),
							'default' => 'round',
							'options' => array(
								'butt' 	 => __( 'butt' ),
								'round'	 => __( 'round' ),
								'square' => __( 'square' )
							),
						),

						'linewidth' => array(
							'type'        => 'slider',
							'label' => __( 'Line Width', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Width of the bar line in px.', 'lrw-so-widgets-bundle' ),
							'default' => 5,
							'min'         => 0,
							'max'         => 100,
							'integer'     => false,
						),

						'trackwidth' => array(
							'type'        => 'slider',
							'label' => __( 'Track Width', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Width of the track line in px.', 'lrw-so-widgets-bundle' ),
							'default' => 5,
							'min'         => 0,
							'max'         => 100,
							'integer'     => false,
						),

						'size' => array(
							'type' => 'number',
							'label' => __( 'Size', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Size of the pie chart in px. It will always be a square.', 'lrw-so-widgets-bundle' ),
							'default' => 200,
						),

						'rotate' => array(
							'type'        => 'slider',
							'label' => __( 'Rotate', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Rotation of the complete chart in degrees.', 'lrw-so-widgets-bundle' ),
							'min'         => -180,
							'max'         => 360,
							'integer'     => true,
						),

						'animate' => array(
							'type' => 'number',
							'label' => __( 'Animate', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Time in milliseconds for a eased animation of the bar growing, or false to deactivate.', 'lrw-so-widgets-bundle' ),
						),
				  	)
			  	),

				'design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(
						'tag' => array(
							'type' => 'select',
							'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
							'default' => 'span',
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
								'p'  => __( 'p', 'lrw-so-widgets-bundle' ),
								'span'  => __( 'span', 'lrw-so-widgets-bundle' ),
								'div'  => __( 'div', 'lrw-so-widgets-bundle' ),
							),
						),

						'fontsize' => array(
							'type' => 'measurement',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
						),

						'text_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'barcolor' => array(
							'type' => 'color',
							'label' => __( 'Bar color', 'lrw-so-widgets-bundle' ),
							'default' => '#41a9d5',
						),

						'trackcolor' => array(
							'type' => 'color',
							'label' => __( 'Track color', 'lrw-so-widgets-bundle' ),
							'default' => '#f2f2f2',
						),

						'scalecolor' => array(
							'type' => 'color',
							'label' => __( 'Scale color', 'lrw-so-widgets-bundle' ),
							'default' => '#dfe0e0',
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
		if ( ! empty( $instance['trigger']) ) {
			wp_enqueue_script( 'waypoints', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/waypoints.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		if ( isset( $instance['settings']['e_easing'] ) && $instance['settings']['e_easing'] == 'yes' ) {
			wp_enqueue_script( 'easing', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/jquery.easing.1.3.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_scripts(
	        array(
	            array( 'easypiechart', siteorigin_widget_get_plugin_dir_url( 'lrw-pie-chart' ) . 'assets/js/jquery.easypiechart.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
	            array( 'piechartinit', siteorigin_widget_get_plugin_dir_url( 'lrw-pie-chart' ) . 'assets/js/jquery.piechartinit.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
	        )
	    );
	}

	/**
	 * The less variables
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	function get_less_variables( $instance ) {

		$less_vars = array();

		if ( ! empty( $instance['design'] ) ) {
			$design = $instance['design'];

			if ( ! empty( $design['fontsize'] ) && $design['fontsize'] != 'px' ) {
				$less_vars['fontsize'] = $design['fontsize'];
			}

			if ( ! empty( $design['text_color'] ) ) {
				$less_vars['text_color'] = $design['text_color'];
			}
		}

		return $less_vars;
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'trigger' 		=> $instance['trigger'],
			'value'			=> $instance['value'],
			'unit' 			=> $instance['unit'],
			'e_easing'		=> $instance['settings']['e_easing'],
			'easing'		=> $instance['settings']['easing'],
			'scalelength' 	=> $instance['settings']['scalelength'],
			'linecap'		=> $instance['settings']['linecap'],
			'linewidth'		=> $instance['settings']['linewidth'],
			'trackwidth'	=> $instance['settings']['trackwidth'],
			'size'			=> $instance['settings']['size'],
			'rotate'		=> $instance['settings']['rotate'],
			'animate'		=> $instance['settings']['animate'],
			'tag'			=> $instance['design']['tag'],
			'barcolor'		=> ( $instance['design']['barcolor'] ? $instance['design']['barcolor'] : '#41a9d5' ),
			'trackcolor'	=> ( $instance['design']['trackcolor'] ? $instance['design']['trackcolor'] : '#f2f2f2' ),
			'scalecolor'	=> ( $instance['design']['scalecolor'] ? $instance['design']['scalecolor'] : '#dfe0e0' )
        );
    }
}

siteorigin_widget_register( 'lrw-pie-chart', __FILE__, 'LRW_Widget_Pie_Chart' );
