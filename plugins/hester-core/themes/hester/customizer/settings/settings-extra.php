<?php
add_filter( 'hester_customizer_options', 'hester_customizer_extra_options' );
function hester_customizer_extra_options( array $options ) {
	// About/Extra section

	$options['section']['hester_section_extra'] = array(
		'title'          => esc_html__( 'Extra section', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_extra',
		'priority'       => (int) apply_filters( 'hester_section_priority', 14, 'hester_section_extra' ),
	);

	// Schema toggle.
	$options['setting']['hester_enable_extra'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'    => 'hester-toggle',
			'label'   => esc_html__( 'Enable Extra section', 'hester-core' ),
			'section' => 'hester_section_extra',
		),
	);

	$options['setting']['hester_section_extra_page'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'        => 'hester-select',
			'section'     => 'hester_section_extra',
			'is_select2'  => true,
			'data_source' => 'page',
			'placeholder' => esc_html__( 'Select a page', 'hester-core' ),
			'label'       => esc_html__( 'Page', 'hester-core' ),
			'description' => esc_html__( 'Select a page to display content from in Extra section.', 'hester-core' ),

			'required'    => array(
				array(
					'control'  => 'hester_enable_extra',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$desc = '<ul class="info">
                    <li>' . __( 'Background color and font color options', 'hester-core' ) . '</li>
                    <li>' . __( 'Manage Top/Bottom Spacing', 'hester-core' ) . '</li>
                </ul>';

		$options['setting']['hester_extra_more'] = array(
			'control' => array(
				'type'        => 'hester-info',
				'label'       => esc_html__( 'Upgrade to pro', 'hester-core' ),
				'description' => wp_kses_post( $desc ),
				'url'         => esc_url_raw( 'https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=extra_section&utm_campaign=upgradeToPro' ),
				'section'     => 'hester_section_extra',
				'required'    => array(
					array(
						'control'  => 'hester_enable_extra',
						'value'    => true,
						'operator' => '==',
					),
				),
			),
		);

		return $options;
}
