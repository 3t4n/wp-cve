<?php
/**
 * Widget Name: LRW - Counter
 * Description: Animate counter number.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Counter extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-counter',
			__( 'LRW - Counter', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Animate counter number.', 'lrw-so-widgets-bundle' ),
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

				'min' => array(
					'type' => 'text',
					'sanitize' => 'min',
					'label' => __( 'Start value', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Start value for increment rolling.', 'lrw-so-widgets-bundle' )
				),

				'max' => array(
					'type' => 'text',
					'sanitize' => 'max',
					'label' => __( 'End value', 'lrw-so-widgets-bundle' ),
					'description' => __( 'End value for increment rolling.', 'lrw-so-widgets-bundle' )
				),

				'decimals' => array(
					'type' => 'number',
					'label' => __( 'Decimal places', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Number of decimal places, default 0.', 'lrw-so-widgets-bundle' )
				),

				'duration' => array(
					'type' => 'number',
					'label' => __( 'Animation duration', 'lrw-so-widgets-bundle' ),
					'description' => __( 'In seconds. Leave a blank for default.', 'lrw-so-widgets-bundle' )
				),

				'title' => array(
					'type' => 'text',
					'label' => __( 'Title counter (optional).', 'lrw-so-widgets-bundle' ),
					'sanitize' => 'title'
				),

				'options' => array(
					'type' => 'section',
					'label' => __( 'Options', 'lrw-so-widgets-bundle' ),
					'fields' => array(

						'easing' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Use Easing', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Toggle easing', 'lrw-so-widgets-bundle' )
						),

						'group' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Use group', 'lrw-so-widgets-bundle' ),
							'description' => __( '1,000,000 vs 1000000', 'lrw-so-widgets-bundle' )
						),

						'separator' => array(
							'type' => 'text',
							'label' => __( 'Separator', 'lrw-so-widgets-bundle' ),
							'default' => ',',
							'sanitize' => 'separator',
							'description' => __( 'Character to use as a separator.', 'lrw-so-widgets-bundle' )
						),

						'decimal' => array(
							'type' => 'text',
							'label' => __( 'Decimal character', 'lrw-so-widgets-bundle' ),
							'default' => '.',
							'sanitize' => 'decimal',
							'description' => __( 'Character to use as a decimal', 'lrw-so-widgets-bundle' )
						),

						'prefix' => array(
							'type' => 'text',
							'label' => __( 'Preffix counter (optional)', 'lrw-so-widgets-bundle' ),
							'sanitize' => 'preffix',
							'description' => __( 'Add a preffix info to counter.', 'lrw-so-widgets-bundle' )
						),

						'suffix' => array(
							'type' => 'text',
							'label' => __( 'Suffix counter (optional)', 'lrw-so-widgets-bundle', 'lrw-so-widgets-bundle' ),
							'sanitize' => 'suffix',
							'description' => __( 'Add a suffix info to counter, eg "%".', 'lrw-so-widgets-bundle' )
						),
				  	)
				),

				'design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'lrw-so-widgets-bundle' ),
					'fields' => array(

						'value_text_size' => array(
							'type' => 'measurement',
							'label' => __( 'Value text size', 'lrw-so-widgets-bundle' ),
							'default' => '30px',
						),

						'value_color' => array(
							'type' => 'color',
							'label' => __( 'Value color', 'lrw-so-widgets-bundle' ),
						),

						'preffix_color' => array(
							'type' => 'color',
							'label' => __( 'Preffix color', 'lrw-so-widgets-bundle' ),
						),

						'suffix_color' => array(
							'type' => 'color',
							'label' => __( 'Suffix color', 'lrw-so-widgets-bundle' ),
						),

						'title_text_size' => array(
							'type' => 'measurement',
							'label' => __( 'Title text size', 'lrw-so-widgets-bundle' ),
							'default' => '15px',
						),

						'title_color' => array(
							'type' => 'color',
							'label' => __( 'Title color', 'lrw-so-widgets-bundle' ),
						),

						'align' => array(
							'type' => 'select',
							'label' => __( 'Alignment', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
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

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_scripts(
	        array(
	            array( 'countup', siteorigin_widget_get_plugin_dir_url( 'lrw-counter' ) . 'assets/js/countUp.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
	            array( 'countinit', siteorigin_widget_get_plugin_dir_url( 'lrw-counter' ) . 'assets/js/jquery.countinit.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
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

			if ( ! empty( $design['value_text_size'] ) ) {
				$less_vars['value_text_size'] = $design['value_text_size'];
			}

			if ( ! empty( $design['value_color'] ) ) {
				$less_vars['value_color'] = $design['value_color'];
			}

			if ( ! empty( $design['preffix_color'] ) ) {
				$less_vars['preffix_color'] = $design['preffix_color'];
			}

			if ( ! empty( $design['suffix_color'] ) ) {
				$less_vars['suffix_color'] = $design['suffix_color'];
			}

			if ( ! empty( $design['title_text_size'] ) ) {
				$less_vars['title_text_size'] = $design['title_text_size'];
			}

			if ( ! empty( $design['title_color'] ) ) {
				$less_vars['title_color'] = $design['title_color'];
			}
		}

		return $less_vars;
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'trigger' 	=> $instance['trigger'],
			'min'		=> $instance['min'],
			'max'		=> $instance['max'],
			'decimals' 	=> $instance['decimals'],
			'duration'	=> $instance['duration'],
			'title'		=> $instance['title'],
			'easing'	=> $instance['options']['easing'],
			'group'		=> $instance['options']['group'],
			'separator'	=> $instance['options']['separator'],
			'decimal'	=> $instance['options']['decimal'],
			'prefix'	=> $instance['options']['prefix'],
			'suffix'	=> $instance['options']['suffix'],
			'align'		=> $instance['design']['align']
        );
    }
}

siteorigin_widget_register( 'lrw-counter', __FILE__, 'LRW_Widget_Counter' );
