<?php
/**
 * Widget Name: LRW - Member
 * Description: A custom team member.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Member extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-member',
		  	__( 'LRW - Member', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'A custom team member.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),

		  	array(
		  	),

		  	array(
				'photo_settings'      => array(
					'type'        => 'section',
					'label'       => __( 'Photo', 'lrw-so-widgets-bundle' ),
					'hide'        => true,
					'description' => __( '', 'lrw-so-widgets-bundle' ),
					'fields'      => array(
						'photo' => array(
					        'type' => 'media',
					        'label' => __( 'Photo', 'lrw-so-widgets-bundle' ),
					        'choose' => __( 'Sets image', 'lrw-so-widgets-bundle' ),
                            'update' => __( 'Select image', 'lrw-so-widgets-bundle' ),
					        'library' => 'image',
					        'fallback' => true
					    ),

					    'photo_size' => array(
							'type' => 'select',
							'label' => __( 'Image size', 'lrw-so-widgets-bundle' ),
							'default' => 'full',
							'options' => array(
								'full' => __( 'Full', 'lrw-so-widgets-bundle' ),
								'large' => __( 'Large', 'lrw-so-widgets-bundle' ),
								'medium' => __( 'Medium', 'lrw-so-widgets-bundle' ),
								'thumb' => __( 'Thumbnail', 'lrw-so-widgets-bundle' ),
							),
						),

						'photo_shape' => array(
							'type' => 'select',
							'label' => __( 'Photo shape', 'lrw-so-widgets-bundle' ),
							'options' => array(
								'none' => __( 'None', 'lrw-so-widgets-bundle' ),
								'circle' => __( 'Circle', 'lrw-so-widgets-bundle' ),
								'square' => __( 'Square', 'lrw-so-widgets-bundle' ),
								'rounded' => __( 'Rounded', 'lrw-so-widgets-bundle' ),
							),
						),
					)
				),

				'name_settings'      => array(
					'type'        => 'section',
					'label'       => __( 'Person name', 'lrw-so-widgets-bundle' ),
					'hide'        => true,
					'description' => __( '', 'lrw-so-widgets-bundle' ),
					'fields'      => array(
						'name' => array(
							'type' => 'text',
							'label' => __( 'Name', 'lrw-so-widgets-bundle' ),
						),

						'name_type' => array(
							'type' => 'select',
							'label' => __( 'Tag HTML', 'lrw-so-widgets-bundle' ),
							'default' => 'h4',
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'name_color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'lrw-so-widgets-bundle' ),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '10px',
						),
					)

				),

				'role_settings'      => array(
					'type'        => 'section',
					'label'       => __( 'Role', 'lrw-so-widgets-bundle' ),
					'hide'        => true,
					'description' => __( '', 'lrw-so-widgets-bundle' ),
					'fields'      => array(
						'role' => array(
							'type' => 'text',
							'label' => __( 'Role', 'lrw-so-widgets-bundle' ),
						),

						'role_type' => array(
							'type' => 'select',
							'label' => __( 'Tag HTML', 'lrw-so-widgets-bundle' ),
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

						'role_color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'lrw-so-widgets-bundle' ),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '10px',
						),
					)

				),

				'resume_settings'      => array(
					'type'        => 'section',
					'label'       => __( 'Resume', 'lrw-so-widgets-bundle' ),
					'hide'        => true,
					'description' => __( '', 'lrw-so-widgets-bundle' ),
					'fields'      => array(
						'resume' => array(
					        'type' => 'tinymce',
					        'label' => __( 'Resume', 'lrw-so-widgets-bundle' ),
					        'rows' => 5
					    ),

					    'resume_color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'lrw-so-widgets-bundle' ),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '10px',
						),
					)
				),

				'social' => array(
                    'type' => 'section',
                    'label' => __( 'Social' , 'lrw-so-widgets-bundle' ),
                    'hide' => true,
                    'fields' => array(

						'profiles' => array(
							'type' => 'repeater',
							'label' => __( 'Profiles', 'lrw-so-widgets-bundle' ),
							'item_name' => __( 'Social profile', 'lrw-so-widgets-bundle' ),
							'item_label' => array(
								'selector' => "[id*='profiles-title']",
								'update_event' => 'change',
								'value_method' => 'val'
							),
							'fields' => array(

								'title' => array(
									'type' => 'text',
									'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
								),

								'icon_shape' => array(
									'type' => 'select',
									'label' => __( 'Icon shape', 'lrw-so-widgets-bundle' ),
									'options' => array(
										'none' => __( 'None', 'lrw-so-widgets-bundle' ),
										'circle' => __( 'Circle', 'lrw-so-widgets-bundle' ),
										'square' => __( 'Square', 'lrw-so-widgets-bundle' ),
										'rounded' => __( 'Rounded', 'lrw-so-widgets-bundle' ),
										'outline-circle' => __( 'Outline circle', 'lrw-so-widgets-bundle' ),
										'outline-square' => __( 'Outline square', 'lrw-so-widgets-bundle' ),
										'outline-rounded' => __( 'Outline rounded', 'lrw-so-widgets-bundle' ),
									),
								),

								'shape_color' => array(
									'type' => 'color',
									'label' => __( 'Shape color', 'lrw-so-widgets-bundle' ),
								),

								'icon' => array(
									'type' => 'icon',
									'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
								),

								'icon_color' => array(
									'type' => 'color',
									'label' => __( 'Icon color', 'lrw-so-widgets-bundle' ),
								),

								'icon_size' => array(
				                    'type' => 'select',
									'label' => __( 'Icon size', 'lrw-so-widgets-bundle' ),
				                    'default' => 'lg',
									'options' => array(
										'lg' => __( 'Mini', 'lrw-so-widgets-bundle' ),
										'2x' => __( 'Small', 'lrw-so-widgets-bundle' ),
										'3x' => __( 'normal', 'lrw-so-widgets-bundle' ),
										'4x' => __( 'Large', 'lrw-so-widgets-bundle' ),
										'5x' => __( 'Extra large', 'lrw-so-widgets-bundle' ),
									),
				                ),

								'url' => array(
									'type' => 'link',
									'label' => __( 'Destination URL (optional)', 'lrw-so-widgets-bundle' ),
								),

								'new_window' => array(
									'type' => 'checkbox',
									'default' => false,
									'label' => __( 'Open in a new window', 'lrw-so-widgets-bundle' ),
								),

							),
						),
					)
				),

				'member_align' => array(
					'type' => 'select',
					'label' => __( 'Alignment', 'lrw-so-widgets-bundle' ),
					'default' => 'center',
					'options' => array(
						'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
						'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
						'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
					),
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

		if ( ! empty( $instance['name_settings'] ) ) {
			$name_settings = $instance['name_settings'];

			if ( ! empty( $name_settings['name_color'] ) ) {
				$less_vars['name_color'] = $name_settings['name_color'];
			}

			if ( ! empty( $name_settings['margin_top'] ) ) {
				$less_vars['name_margin_top'] = $name_settings['margin_top'];
			}

			if ( ! empty( $name_settings['margin_bottom'] ) ) {
				$less_vars['name_margin_bottom'] = $name_settings['margin_bottom'];
			}
		}

		if ( ! empty( $instance['role_settings'] ) ) {
			$role_settings = $instance['role_settings'];

			if ( ! empty( $role_settings['role_color'] ) ) {
				$less_vars['role_color'] = $role_settings['role_color'];
			}

			if ( ! empty( $role_settings['margin_top'] ) ) {
				$less_vars['role_margin_top'] = $role_settings['margin_top'];
			}

			if ( ! empty( $role_settings['margin_bottom'] ) ) {
				$less_vars['role_margin_bottom'] = $role_settings['margin_bottom'];
			}
		}

		if ( ! empty( $instance['resume_settings'] ) ) {
			$resume_settings = $instance['resume_settings'];

			if ( ! empty( $resume_settings['resume_color'] ) ) {
				$less_vars['resume_color'] = $resume_settings['resume_color'];
			}

			if ( !empty( $resume_settings['margin_top'] ) ) {
				$less_vars['resume_margin_top'] = $resume_settings['margin_top'];
			}

			if ( !empty( $resume_settings['margin_bottom'] ) ) {
				$less_vars['resume_margin_bottom'] = $resume_settings['margin_bottom'];
			}
		}

		return $less_vars;
	}
}

siteorigin_widget_register( 'lrw-member', __FILE__, 'LRW_Widget_Member' );
