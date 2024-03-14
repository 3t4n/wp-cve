<?php
function bc_bizcor_customizer_typography( $wp_customize ){

	global $bizcor_options;

	// Bizcor Typography Panel
	$wp_customize->add_panel( 'bizcor_typography',
		array(
			'priority'       => 41,
			'capability'     => 'edit_theme_options',
			'title'          => esc_html__('Bizcor Typography','bizcor'),
		)
	);

	$sections = array(
		'body',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
	);

	foreach( $sections as $key => $section ){

		// Sections
	    $wp_customize->add_section('bizcor_'.$section.'_section',
	        array(
	            'priority'     => $key,
	            'title'        => sprintf(__('%s','bizcor'),ucfirst($section)),
	            'panel'        => 'bizcor_typography',
	        )
	    );

			// font size
			$wp_customize->add_setting('bizcor_'.$section.'_fontsize',
				array(
					'sanitize_callback' => 'bizcor_sanitize_range_value',
					'priority'          => 2,
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(new Bizcor_Range_Control($wp_customize,'bizcor_'.$section.'_fontsize',
				array(
					'label' 		=> esc_html__('Font Size', 'bizcor'),
					'section' 		=> 'bizcor_'.$section.'_section',
					'type'          => 'range-value',
					'media_query'   => true,
                    'input_attr' => array(
                        'mobile' => array(
                            'min' => 6,
                            'max' => 50,
                            'step' => 1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_fontsize'],
                        ),
                        'tablet' => array(
                            'min' => 6,
                            'max' => 50,
                            'step' => 1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_fontsize'],
                        ),
                        'desktop' => array(
                            'min' => 6,
                            'max' => 50,
                            'step' => 1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_fontsize'],
                        ),
                    ),
				)
			) );

			// line height
			$wp_customize->add_setting('bizcor_'.$section.'_lineheight',
				array(
					'sanitize_callback' => 'bizcor_sanitize_range_value',
					'priority'          => 3,
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(new Bizcor_Range_Control($wp_customize,'bizcor_'.$section.'_lineheight',
				array(
					'label' 		=> esc_html__('Line Height', 'bizcor'),
					'section' 		=> 'bizcor_'.$section.'_section',
					'type'          => 'range-value',
					'media_query'   => true,
                    'input_attr' => array(
                        'mobile' => array(
                            'min' => 0.1,
                            'max' => 3,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_lineheight'],
                        ),
                        'tablet' => array(
                            'min' => 0.1,
                            'max' => 3,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_lineheight'],
                        ),
                        'desktop' => array(
                            'min' => 0.1,
                            'max' => 3,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_'.$section.'_lineheight'],
                        ),
                    ),
				)
			) );

			// text transform
	    	$wp_customize->add_setting('bizcor_'.$section.'_texttransform',
				array(
					'sanitize_callback' => 'bizcor_sanitize_select',
					'default'           => $bizcor_options['bizcor_'.$section.'_texttransform'],
					'priority'          => 5,
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control('bizcor_'.$section.'_texttransform',
				array(
					'type'        => 'select',
					'label'       => esc_html__('Text Transform', 'bizcor'),
					'section'     => 'bizcor_'.$section.'_section',
					'choices'     => bizcor_text_transform(),
				)
			);

	}	

}
add_action('customize_register','bc_bizcor_customizer_typography');

if( !function_exists('bizcor_font_size')):
	function bizcor_font_size(){
		$font_size = array(''=>'-- Select --');
		for( $i=9; $i<=100; $i++ ){		
			$font_size[$i] = $i;		
		}	
		return $font_size;
	}
endif;

if( !function_exists('bizcor_line_height')):
	function bizcor_line_height(){
		$lineheight = array(''=>'-- Select --');
		for( $i=1; $i<=100; $i++ ){		
			$lineheight[$i] = $i;		
		}	
		return $lineheight;
	}
endif;

if( !function_exists('bizcor_text_transform')):
	function bizcor_text_transform(){
		$texttransform = array(
			''=>'-- Select --',
			'none'=>'none',
			'capitalize'=>'capitalize',
			'uppercase'=>'uppercase',
			'lowercase'=>'lowercase',
			'initial'=>'initial',
			'inherit'=>'inherit',
		);	
		return $texttransform;	
	}
endif;