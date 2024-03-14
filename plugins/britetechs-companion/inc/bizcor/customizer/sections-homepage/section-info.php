<?php
function bizcor_customizer_info_section( $wp_customize ){

	global $bizcor_options;

		// Homepage Info
		$wp_customize->add_section( 'info_section',
			array(
				'priority'    => 2,
				'title'       => esc_html__('Section Info','bizcor'),
				'panel'       => 'bizcor_frontpage',
			)
		);

			// bizcor_info_disable
			$wp_customize->add_setting('bizcor_info_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_info_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_info_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'bizcor'),
					'section'     => 'info_section',
				)
			);

			// bizcor_info_content
			$wp_customize->add_setting('bizcor_info_content',array(
					'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
					'transport'         => 'refresh', // refresh or postMessage
					'priority'          => 2,
					'default'           => $bizcor_options['bizcor_info_content'],
				) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_info_content',
					array(
						'label'         => esc_html__('Info Content','bizcor'),
						'section'       => 'info_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon','bizcor'),
								'type'  =>'select',
								'options' => array(
									'icon' => esc_html__('Icon', 'bizcor'),
									//'image' => esc_html__('image','bizcor'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon','bizcor'),
								'type'  =>'icon',
								'required' => array('icon_type','=','icon'),
							),
							'image'  => array(
								'title' => esc_html__('Image','bizcor'),
								'type'  =>'media',
								'required' => array('icon_type','=','image'),
							),
							'title' => array(
								'title' => esc_html__('Title','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'desc' => array(
								'title' => esc_html__('Description','bizcor'),
								'type'  =>'textarea',
								'desc'  => '',
							),
						),
					)
				)
			);
}
add_action('customize_register','bizcor_customizer_info_section');