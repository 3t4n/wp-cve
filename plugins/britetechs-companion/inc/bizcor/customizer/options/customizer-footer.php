<?php
function bc_bizcor_customizer_footer( $wp_customize ){

	global $bizcor_options;

	// bizcor_footer_bottom_content
	$wp_customize->add_setting('bizcor_footer_bottom_content',array(
			'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
			'transport'         => 'refresh', // refresh or postMessage
			'priority'          => 3,
			'default'           => $bizcor_options['bizcor_footer_bottom_content'],
		) );

	$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_footer_bottom_content',
			array(
				'label'         => esc_html__('Links Content','bizcor'),
				'section'       => 'footer_bottom',
				'live_title_id' => 'title', // apply for unput text and textarea only
				'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
				'max_item'      => 2,
				'limited_msg' 	=> bizcor_upgrade_pro_msg(),
				'fields'    => array(
					'title' => array(
						'title' => esc_html__('Title','bizcor'),
						'type'  =>'textarea',
						'desc'  => '',
					),
					'link' => array(
						'title' => esc_html__('Link','bizcor'),
						'type'  =>'text',
						'desc'  => '',
					),
					'target' => array(
						'title' => esc_html__('Open in new tab?','bizcor'),
						'type'  =>'checkbox',
						'desc'  => '',
					),
				),
			)
		)
	);

}
add_action('customize_register','bc_bizcor_customizer_footer');