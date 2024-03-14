<?php
/**
 * Widget Name: LRW - Word Typed
 * Description: Animated word typing.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Word_Typed extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-word-typed',
		  	__( 'LRW - Word Typed', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'Animated word typing.', 'lrw-so-widgets-bundle' ),
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

				'strings' => array(
					'type' => 'textarea',
					'label' => __( 'Strings', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter the text, each on a new line. HTML tags are allowed.', 'lrw-so-widgets-bundle' ),
			        'rows' => 3
				),

				'settings' => array(
					'type' => 'section',
				  	'label' => __( 'Settings', 'lrw-so-widgets-bundle' ),
				  	'hide' => true,
				  	'fields' => array(

				  		'typespeed' => array(
							'type' => 'number',
							'label' => __( 'Typing speed', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Typing speed, in milliseconds.', 'lrw-so-widgets-bundle' ),
							'default' => 0,
						),

						'startdelay' => array(
							'type' => 'number',
							'label' => __( 'Start delay', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Time before typing starts', 'lrw-so-widgets-bundle' ),
							'default' => 0,
						),

						'backspeed' => array(
							'type' => 'number',
							'label' => __( 'Backspacing speed', 'lrw-so-widgets-bundle' ),
							'default' => 0,
						),

						'backdelay' => array(
							'type' => 'number',
							'label' => __( 'Backspacing delay', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Time before backspacing', 'lrw-so-widgets-bundle' ),
							'default' => 500,
						),

						'loop' => array(
							'type' => 'checkbox',
							'default' => false,
							'label' => __( 'Loop', 'lrw-so-widgets-bundle' ),
						),

						'loopcount' => array(
							'type' => 'checkbox',
							'default' => false,
							'label' => __( 'Loop count', 'lrw-so-widgets-bundle' ),
							'description' => __( 'false = infinite.', 'lrw-so-widgets-bundle' )
						),

						'showcursor' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Show cursor', 'lrw-so-widgets-bundle' ),
						),

						'cursorchar' => array(
							'type' => 'text',
							'label' => __( 'Character for cursor', 'lrw-so-widgets-bundle' ),
						),

						'cursortime' => array(
							'label' => __( 'Animation time for cursor', 'lrw-so-widgets-bundle' ),
							'type' => 'slider',
							'min' => 0,
							'max' => 100,
							'default' => 80,
						),
				  	)
			  	),

				'design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'lrw-so-widgets-bundle' ),
					'fields' => array(
						'tag' => array(
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
								'p'  => __( 'p', 'lrw-so-widgets-bundle' ),
								'span'  => __( 'span', 'lrw-so-widgets-bundle' ),
								'div'  => __( 'div', 'lrw-so-widgets-bundle' ),
							),
						),

						'fontsize' => array(
							'type' => 'measurement',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
						),

						'align' => array(
							'type' => 'select',
							'label' => __( 'Align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						'text_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
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
		if ( ! empty( $instance['settings']['trigger'] ) ) {
			wp_enqueue_script( 'waypoints', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/waypoints.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_scripts(
			array(
				array( 'typed', siteorigin_widget_get_plugin_dir_url( 'lrw-word-typed' ) . 'assets/js/typed.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
				array( 'typedinit', siteorigin_widget_get_plugin_dir_url( 'lrw-word-typed' ) . 'assets/js/jquery.typedinit.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
			)
		);
	}

	function get_less_variables( $instance ) {
		if ( empty( $instance ) ) return array();

		return array(
			'fontsize' 		=> $instance['design']['fontsize'],
			'text_color'	=> $instance['design']['text_color'],
			'margin_top' 	=> $instance['design']['margin_top'],
			'margin_bottom' => $instance['design']['margin_bottom'],
		);
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'trigger' 		=> $instance['trigger'],
			'strings' 		=> $instance['strings'],
			'typespeed'		=> $instance['settings']['typespeed'],
			'startdelay'	=> $instance['settings']['startdelay'],
			'backspeed'		=> $instance['settings']['backspeed'],
			'backdelay'		=> $instance['settings']['backdelay'],
			'loop'			=> $instance['settings']['loop'],
			'loopcount'		=> $instance['settings']['loopcount'],
			'showcursor'	=> $instance['settings']['showcursor'],
			'cursorchar'	=> $instance['settings']['cursorchar'],
			'cursortime'	=> $instance['settings']['cursortime'],
			'tag'			=> $instance['design']['tag'],
			'align'			=> $instance['design']['align']
        );
    }
}

siteorigin_widget_register( 'lrw-word-typed', __FILE__, 'LRW_Widget_Word_Typed' );
