<?php

add_filter( 'hester_customizer_options', 'hester_customizer_products_options' );
function hester_customizer_products_options( array $options ) {

	if ( ! class_exists( 'WooCommerce' ) ) {

		$options['section']['hester_section_products'] = array(
			'panel'        => 'hester_panel_homepage',
			'class'        => 'Hester_Customizer_Control_Generic_Notice',
			'section_text' =>
			sprintf(
				/* translators: %1$s is Plugin Name */
				esc_html__( 'To have access to a shop section please install and configure %1$s.', 'hester-core' ),
				esc_html__( 'WooCommerce plugin', 'hester-core' )
			),
			'slug'         => 'woocommerce',
			'panel'        => 'hester_panel_homepage',
			'priority'     => 451,
			'capability'   => 'install_plugins',
			'hide_notice'  => (bool) get_option( 'dismissed-hester_info_woocommerce', false ),
			'options'      => array(
				'redirect' => admin_url( 'customize.php' ) . '?autofocus[section]=hester_section_products',
			),
		);

		$options['setting']['hester_section_wocommerce_recommendation'] = array(
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
			'control'           => array(
				'type'    => 'hidden',
				'section' => 'hester_section_products',
			),
		);
	} else {

		// Slider section.
		$options['section']['hester_section_products'] = array(
			'title'          => esc_html__( 'Products Section', 'hester-core' ),
			'panel'          => 'hester_panel_homepage',
			'class'          => 'Hester_Customizer_Control_Section_Hiding',
			'hiding_control' => 'hester_enable_products',
			'priority'       => (int) apply_filters( 'hester_section_priority', 5, 'hester_section_products' ),
		);

		// Schema toggle.
		$options['setting']['hester_enable_products'] = array(
			'transport'         => 'postMessage',
			'sanitize_callback' => 'hester_sanitize_toggle',
			'control'           => array(
				'type'        => 'hester-toggle',
				'label'       => esc_html__( 'Enable Products', 'hester-core' ),
				'description' => esc_html__( 'Show woocommerce product to homepage.', 'hester-core' ),
				'section'     => 'hester_section_products',
			),
			'required'          => array(
				array(
					'control'  => 'hester_section_products',
					'value'    => true,
					'operator' => '==',
				),
			),
		);

		$options['setting']['hester_products_sub_heading'] = array(
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'control'           => array(
				'type'     => 'hester-text',
				'label'    => esc_html__( 'Section Sub Heading', 'hester-core' ),
				'section'  => 'hester_section_products',
				'required' => array(
					array(
						'control'  => 'hester_enable_products',
						'value'    => true,
						'operator' => '==',
					),
				),
			),
		);

		$options['setting']['hester_products_heading'] = array(
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'control'           => array(
				'type'     => 'hester-text',
				'label'    => esc_html__( 'Section Heading', 'hester-core' ),
				'section'  => 'hester_section_products',
				'required' => array(
					array(
						'control'  => 'hester_enable_products',
						'value'    => true,
						'operator' => '==',
					),
				),
			),
			'partial'           => array(
				'selector'            => '#hester-products-heading',
				'render_callback'     => function () {
					return get_theme_mod( 'hester_products_heading' );
				},
				'container_inclusive' => false,
				'fallback_refresh'    => true,
			),
		);

		$options['setting']['hester_products_description'] = array(
			'transport'         => 'postMessage',
			'sanitize_callback' => 'hester_sanitize_textarea',
			'control'           => array(
				'type'     => 'hester-editor',
				'label'    => esc_html__( 'Section Description', 'hester-core' ),
				'section'  => 'hester_section_products',
				'required' => array(
					array(
						'control'  => 'hester_enable_products',
						'value'    => true,
						'operator' => '==',
					),
				),
			),
		);

		$desc = '<ul class="info">
                    <li>' . __( 'Show products group by categories', 'hester-core' ) . '</li>
                    <li>' . __( 'Number of products to show', 'hester-core' ) . '</li>
                    <li>' . __( 'Number of columns to show', 'hester-core' ) . '</li>
                </ul>';

		$options['setting']['hester_products_more'] = array(
			'transport'         => 'refresh',
			'sanitize_callback' => 'hester_sanitize_range',
			'control'           => array(
				'type'        => 'hester-info',
				'label'       => esc_html__( 'Need more features?', 'hester-core' ),
				'description' => wp_kses_post( $desc ),
				'url'         => esc_url_raw( 'https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=products&utm_campaign=upgradeToPro' ),
				'section'     => 'hester_section_products',
				'required'    => array(
					array(
						'control'  => 'hester_enable_products',
						'value'    => true,
						'operator' => '==',
					),
				),
			),
		);
	}
	return $options;
}
