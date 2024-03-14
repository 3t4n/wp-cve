<?php
/**
 * Widget Name: LRW - Empty Space
 * Description: A blank space with custom height.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Empty_Space extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-empty-space',
			__( 'LRW - Empty Space', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'A blank space with custom height.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(
			),
			array(

				'height' => array(
					'type' => 'number',
					'label' => __( 'Height', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter empty space height', 'lrw-so-widgets-bundle' ),
					'default' => '100',
				),

				'visibility' => array(
					'type' => 'radio',
					'default' => 'no',
					'label' => __( 'Visibility breakpoint', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Enter empty space height', 'lrw-so-widgets-bundle' ),
					'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'visibility' )
                    ),
                    'options' => array(
						'yes' => __( 'Yes', 'lrw-so-widgets-bundle' ),
						'no' => __( 'No', 'lrw-so-widgets-bundle' )
					)
				),

				'breakpoints' => array(
					'type' => 'section',
					'label' => __( 'Breakpoint options', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Sets breakpoints for devices visibility.', 'lrw-so-widgets-bundle' ),
					'state_handler' => array(
						'visibility[yes]' => array( 'show' ),
						'_else[visibility]' => array( 'hide' ),
					),
					'hide'        => true,
					'fields' => array(
						'desktop' => array(
							'type' => 'number',
							'label' => __( 'Desktop', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Medium devices (desktops, 992px and up), in pixels', 'lrw-so-widgets-bundle' ),
							'default' => 992,
						),

						'd_height' => array(
							'type' => 'number',
							'label' => __( 'Desktop height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter empty space height. Leave blank to use the main size.', 'lrw-so-widgets-bundle' ),
							'default' => '100',
						),

						'tablet' => array(
							'type' => 'number',
							'label' => __( 'Tablet', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Small devices (tablets, 768px and up), in pixels', 'lrw-so-widgets-bundle' ),
							'default' => 768,
						),

						't_height' => array(
							'type' => 'number',
							'label' => __( 'Tablet height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter empty space height. Leave blank to use the main size or desktop size.', 'lrw-so-widgets-bundle' ),
							'default' => '100',
						),

						'phone' => array(
							'type' => 'number',
							'label' => __( 'Phone', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Small devices (phones, less than 768px), in pixels', 'lrw-so-widgets-bundle' ),
							'default' => 480,
						),

						'p_height' => array(
							'type' => 'number',
							'label' => __( 'Phone height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter empty space height. Leave blank to use the main size, desktop size or tablet size.', 'lrw-so-widgets-bundle' ),
							'default' => '100',
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

	function enqueue_frontend_scripts( $instance ) {
		if ( isset( $instance['visibility'] ) && $instance['visibility'] == 'yes' ) {
			wp_enqueue_script( 'emptyspace', siteorigin_widget_get_plugin_dir_url( 'lrw-empty-space' ) . 'assets/js/jquery.emptyspace.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function get_template_variables( $instance, $args ) {
        return array(
			'height'		=> $instance['height'],
			'visibility'	=> $instance['visibility'],
			'desktop'		=> $instance['breakpoints']['desktop'],
			'd_height'		=> ( $instance['visibility'] == 'yes' ? $instance['breakpoints']['d_height'] : $instance['height'] ),
			'tablet'		=> $instance['breakpoints']['tablet'],
			't_height'		=> ( $instance['visibility'] == 'yes' ? $instance['breakpoints']['t_height'] : $instance['height'] ),
			'phone'			=> $instance['breakpoints']['phone'],
			'p_height'		=> ( $instance['visibility'] == 'yes' ? $instance['breakpoints']['p_height'] : $instance['height'] )
        );
    }
}

siteorigin_widget_register( 'lrw-empty-space', __FILE__, 'LRW_Widget_Empty_Space' );
