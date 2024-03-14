<?php
function jewelry_store_customizer_shop( $wp_customize ){

	$option = jewelry_store_reset_data();

		$wp_customize->add_section( 'shop_section' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Shop', 'britetechs-companion' ),
				'panel'       => 'frontpage',
			)
		);
			$wp_customize->add_setting( 'jewelrystore_option[shop_enable]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['shop_enable'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[shop_enable]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Shop Enable', 'britetechs-companion'),
					'section'     => 'shop_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[shop_subtitle]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['shop_subtitle'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[shop_subtitle]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'britetechs-companion'),
					'section'     => 'shop_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[shop_title]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['shop_title'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[shop_title]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'britetechs-companion'),
					'section'     => 'shop_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[shop_desc]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['shop_desc'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[shop_desc]',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Section Subtitle', 'britetechs-companion'),
					'section'     => 'shop_section',
				)
			);

			// container width
            $wp_customize->add_setting( 'jewelrystore_option[shop_container_width]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['shop_container_width'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[shop_container_width]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Container Width', 'britetechs-companion'),
                    'section'     => 'shop_section',
                    'choices' => array(
                    	'container'=> __('Container','britetechs-companion'),
                    	'container-fluid'=> __('Container Full','britetechs-companion')
                    	),
                )
            );

            // column layout
            $wp_customize->add_setting( 'jewelrystore_option[shop_column]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['shop_column'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[shop_column]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Column Layout', 'britetechs-companion'),
                    'section'     => 'shop_section',
                    'choices' => array(
                    	2 => __('2 Column','britetechs-companion'),
                    	3 => __('3 Column','britetechs-companion'),
                    	4 => __('4 Column','britetechs-companion'),
                    	),
                )
            );

}
add_action('customize_register','jewelry_store_customizer_shop');