<?php
/**
 * Widget Name: LRW - Heading
 * Description: A custom heading.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Heading extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-heading',
		  	__( 'LRW - Heading', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'A custom heading.', 'lrw-so-widgets-bundle' ),
				'panels_title' => 'title',
			),
		  	array(),
		  	array(
		  		'title' => array(
					'type' => 'text',
					'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
				),

				'heading_type' => array(
					'type' => 'select',
					'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
					'options' => array(
						'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
						'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
						'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
						'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
						'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
						'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
					),
				),

				'fontsize' => array(
					'type' => 'number',
					'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Set a font size or keep the default.', 'lrw-so-widgets-bundle' ),
				),

				'lineheight' => array(
					'type' => 'number',
					'label' => __( 'Line height', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Set a line height or keep the default.', 'lrw-so-widgets-bundle' ),
				),

				'fontweight' => array(
					'type' => 'number',
					'label' => __( 'Font Weight', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Set a font weight or keep the default.', 'lrw-so-widgets-bundle' ),
				),

				'heading_color' => array(
					'type' => 'color',
					'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
				),

				'heading_align' => array(
					'type' => 'select',
					'label' => __( 'Heading align', 'lrw-so-widgets-bundle' ),
					'default' => 'center',
					'options' => array(
						'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
						'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
						'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
					),
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

				'url_active' => array(
					'type' => 'radio',
					'default' => 'no',
					'label' => __( 'Add link?', 'lrw-so-widgets-bundle' ),
					'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'url_active' )
                    ),
                    'options' => array(
						'yes' => __( 'Yes', 'lrw-so-widgets-bundle' ),
						'no' => __( 'No', 'lrw-so-widgets-bundle' )
					),
				),

				'url_settings' => array(
					'type' => 'section',
					'label' => __( 'URL settings', 'lrw-so-widgets-bundle' ),
					'item_name' => __( 'URL', 'lrw-so-widgets-bundle' ),
					'state_handler' => array(
						'url_active[yes]' => array( 'show' ),
						'_else[url_active]' => array( 'hide' ),
					),
					'hide' => true,
					'fields' => array(
						'url' => array(
							'type' => 'link',
							'label' => __( 'Destination URL (optional)', 'lrw-so-widgets-bundle' ),
						),

						'new_window' => array(
							'type' => 'checkbox',
							'default' => false,
							'label' => __( 'Open in a new window', 'lrw-so-widgets-bundle' ),
						),

						'hover' => array(
							'type' => 'checkbox',
							'default' => false,
							'label' => __( 'Add hover effect?', 'lrw-so-widgets-bundle' ),
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

	function get_less_variables( $instance ) {
		$less_vars = array();

		if ( ! empty( $instance['fontsize'] ) ) {
			$less_vars['fontsize'] = $instance['fontsize'] . 'px';
		}

		if ( ! empty( $instance['lineheight'] ) ) {
			$less_vars['lineheight'] = $instance['lineheight'];
		}

		if ( ! empty( $instance['fontweight'] ) ) {
			$less_vars['fontweight'] = $instance['fontweight'];
		}

		if ( ! empty( $instance['heading_color'] ) ) {
			$less_vars['heading_color'] = $instance['heading_color'];
		}

		if ( ! empty( $instance['heading_align'] ) ) {
			$less_vars['heading_align'] = $instance['heading_align'];
		}

		if ( ! empty( $instance['margin_top'] ) ) {
			$less_vars['margin_top'] = $instance['margin_top'];
		}

		if ( ! empty( $instance['margin_bottom'] ) ) {
			$less_vars['margin_bottom'] = $instance['margin_bottom'];
		}

		return $less_vars;
	}
}

siteorigin_widget_register( 'lrw-heading', __FILE__, 'LRW_Widget_Heading' );
