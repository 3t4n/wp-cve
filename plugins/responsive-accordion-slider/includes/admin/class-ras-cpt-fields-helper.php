<?php

/**
 *
 */
class RESP_ACCORDION_SLIDER_CPT_Fields_Helper {

	public static function resp_accordion_slider_get_tabs() {

		return apply_filters( 'accordion_slider_gallery_tabs', array(

			'selectLayout' => array(
				'label'       => esc_html__( 'Design', 'responsive-accordion-slider' ),
				'title'       => esc_html__( '', 'responsive-accordion-slider' ),
				/*'description' => 'Select the Accordion Slider design',*/
				"icon"        => "dashicons dashicons-layout",
				'priority'    => 5,
			),

			'generalTab' => array(
				'label'       => esc_html__( 'General', 'responsive-accordion-slider' ),
				'title'       => esc_html__( '', 'responsive-accordion-slider' ),
				"icon"        => "dashicons dashicons-admin-generic",
				'priority'    => 10,
			),
			
			'captionsTab' => array(
				'label'       => esc_html__( 'Captions', 'responsive-accordion-slider' ),
				'title'       => esc_html__( '', 'responsive-accordion-slider' ),
				"icon"        => "dashicons dashicons-slides",
				'priority'    => 20,
			),
			
			'shortPhpcode' => array(
				'label'       => esc_html__( 'Shortcode / PHP Code', 'responsive-accordion-slider' ),
				'title'       => esc_html__( '', 'responsive-accordion-slider' ),
				"icon"        => "dashicons dashicons-shortcode",
				'priority'    => 70,
			),
			
			'customCSS' => array(
				'label'       => esc_html__( 'Custom CSS', 'responsive-accordion-slider' ),
				'title'       => esc_html__( '', 'responsive-accordion-slider' ),
				"icon"        => "dashicons dashicons-admin-customizer",
				'priority'    => 90,
			),
            
		) );

	}

	public static function resp_accordion_slider_get_fields( $tab ) {

		$fields = apply_filters( 'accordion_slider_gallery_fields', 
			array(

				/*--------------layout settings-------------*/
				'selectLayout' => array(
					"designName"     => array(
						"name"        => esc_html__( '', 'responsive-accordion-slider' ),
						"type"        => "design-layout",
						"default"     => "design-1",
						'priority'    => 5,
					),
				),
				/*--------------End of layout settings-------------*/

				'generalTab' => array(

					'slider-orientation' => array(
						"name"        => esc_html__( 'Orientation', 'responsive-accordion-slider' ),
						"type"        => "select",
						"description" => esc_html__( 'Select the orientation of Slider', 'responsive-accordion-slider' ),
						'default'     => 'horizontal',
						"values"      => array(
							'horizontal' 	=> esc_html__( 'Horizontal', 'responsive-accordion-slider' ),
							'vertical' 		=> esc_html__( 'Vertical', 'responsive-accordion-slider' ),
						),
						'priority' => 10,
					),

					"slider-width"          => array(
						"name"        => esc_html__( 'Width', 'responsive-accordion-slider' ),
						"type"        => "text",
						"description" => esc_html__( 'Set the width of the Accordion Slider, Can be in pixels.', 'responsive-accordion-slider' ),
						'default'     => '1000px',
						'priority' => 20,
					),

					"slider-height"          => array(
						"name"        => esc_html__( 'Height', 'responsive-accordion-slider' ),
						"type"        => "text",
						"description" => esc_html__( 'Set the Height of the Accordion Slider, Can be in pixels.', 'responsive-accordion-slider' ),
						'default'     => '500px',
						'priority' => 30,
					),


					'font-family' => array(
					"name"        => esc_html__( 'Font Family', 'responsive-accordion-slider' ),
					"type"        => "select",
					"description" => esc_html__( 'Select the font family you want to use', 'responsive-accordion-slider' ),
					'default'     => 'Arial',
					"values"      => array(
						'' => esc_html__( 'Default', 'responsive-accordion-slider' ),
						'Arial' 		  => esc_html__( 'Arial', 'responsive-accordion-slider' ),
						'Arial Black'     => esc_html__( 'Arial Black', 'responsive-accordion-slider' ),
						'Arial Narrow'    => esc_html__( 'Arial Narrow', 'responsive-accordion-slider' ),
						'Calibri'	 	  => esc_html__( 'Calibri', 'responsive-accordion-slider' ),
						'Cambria'	 	  => esc_html__( 'Cambria', 'responsive-accordion-slider' ),
						'Candara'	 	  => esc_html__( 'Candara', 'responsive-accordion-slider' ),
						'Courier'	      => esc_html__( 'Courier', 'responsive-accordion-slider' ),
						'Courier New'	  => esc_html__( 'Courier New', 'responsive-accordion-slider' ),
						'Geneva'		  => esc_html__( 'Geneva', 'responsive-accordion-slider' ),
						'Georgia'		  => esc_html__( 'Georgia', 'responsive-accordion-slider' ),
						'Grande'		  => esc_html__( 'Grande', 'responsive-accordion-slider' ),
						'Helvetica'		  => esc_html__( 'Helvetica', 'responsive-accordion-slider' ),
						'Impact' 		  => esc_html__( 'Impact', 'responsive-accordion-slider' ),
						'Lucida' 		  => esc_html__( 'Lucida', 'responsive-accordion-slider' ),
						'Lucida Grande'   => esc_html__( 'Lucida Grande', 'responsive-accordion-slider' ),
						'Open Sans'       => esc_html__( 'Open Sans', 'responsive-accordion-slider' ),
						'OpenSansBold'    => esc_html__( 'OpenSansBold', 'responsive-accordion-slider' ),
						'Optima'  		  => esc_html__( 'Optima', 'responsive-accordion-slider' ),
						'Palatino Linotype' => esc_html__( 'Palatino', 'responsive-accordion-slider' ),
						'Sans' 			  => esc_html__( 'Sans', 'responsive-accordion-slider' ),
						'sans-serif'	  => esc_html__( 'Sans-serif', 'responsive-accordion-slider' ),
						'Tahom'           => esc_html__( 'Tahom', 'responsive-accordion-slider' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'responsive-accordion-slider' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'responsive-accordion-slider' ),
						'Times New Roman' => esc_html__( 'Times New Roman', 'responsive-accordion-slider' ),
						'Verdana' 		  => esc_html__( 'Verdana', 'responsive-accordion-slider' ),
					),
					'priority' => 40,
				),

				'ras-open-image' => array(
					"name"        => esc_html__( 'Open Accordion Image On', 'responsive-accordion-slider' ),
					"type"        => "select",
					"description" => esc_html__( 'Image will be opened in', 'responsive-accordion-slider' ),
					'default'     => 'hover',
					"values"      => array(
						'hover'   => esc_html__( 'Hover', 'responsive-accordion-slider' ),
						'click'   => esc_html__( 'Click', 'responsive-accordion-slider' ),
					),
					'priority' => 50,
				),

				"ras-opened-image-size"          => array(
					"name"        => esc_html__( 'Max Opened Image Size', 'responsive-accordion-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Open image size of Accordion Slider, Can be in %.', 'responsive-accordion-slider' ),
					'default'     => '80%',
					'priority' => 60,
				),

				"ras-image-distance"          => array(
					"name"        => esc_html__( 'Space Between Images', 'responsive-accordion-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Space between images of Accordion Slider, Can be in pixels.', 'responsive-accordion-slider' ),
					'default'     => '1',
					'priority' => 70,
				),
				

				"ras-visible-images"          => array(
					"name"        => esc_html__( 'Visible Accordion Images', 'responsive-accordion-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Number of images shown in each slide of Accordion Slider.', 'responsive-accordion-slider' ),
					'default'     => '3',
					'priority' => 80,
				),

				
				
				'ras-panel-on-mouse-out' => array(
					"name"        => esc_html__( 'Close Panel on Mouse Out', 'responsive-accordion-slider' ),
					"type"        => "select",
					"description" => esc_html__( 'Close Panel on Mouse Out', 'responsive-accordion-slider' ),
					'default'     => 'hover',
					"values"      => array(
						'true'   => esc_html__( 'True', 'responsive-accordion-slider' ),
						'false'   => esc_html__( 'False', 'responsive-accordion-slider' ),
					),
					'priority' => 90,
				),

				"slider-autoplay"  => array(
					"name"        => esc_html__( 'Autoplay', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Autoplay of Accordion Slider', 'responsive-accordion-slider' ),
					'priority'    => 100,
				),

				"slider-delay"          => array(
					"name"        => esc_html__( 'Autoplay Delay', 'responsive-accordion-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Autoplay Delay in Milliseconds', 'responsive-accordion-slider' ),
					'default'     => '3000',
					'priority' => 110,
				),

				'slider-direction' => array(
					"name"        => esc_html__( 'Autoplay Direction', 'responsive-accordion-slider' ),
					"type"        => "select",
					"description" => esc_html__( 'Autoplay Direction', 'responsive-accordion-slider' ),
					'default'     => 'forward',
					"values"      => array(
						'normal'   => esc_html__( 'Normal', 'responsive-accordion-slider' ),
						'backwards'   => esc_html__( 'Backward', 'responsive-accordion-slider' ),
					),
					'priority' => 120,
				),

				
				"slider-shadow"  => array(
					"name"        => esc_html__( 'Shadow', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Image Shadow of Accordion Slider', 'responsive-accordion-slider' ),
					'priority'    => 130,
				),
				
				"slider-mouse-wheel"   => array(
					"name"        => esc_html__( 'Mouse Wheel', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Image scroll using mouse wheel', 'responsive-accordion-slider' ),
					'priority'    => 140,
				),

				"ras-panel-on-mouse-out"        => array(
					"name"        => esc_html__( 'Close Panel on Mouse Out', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Close Panel on Mouse Out', 'responsive-accordion-slider' ),
					'priority'    => 150,
				),
			
			),
			

			'captionsTab' => array(
				"hide-title"        => array(
					"name"        => esc_html__( 'Hide Title', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show image title from Accordion Slider', 'responsive-accordion-slider' ),
					'priority'    => 5,
				),
				"titleFontSize"    => array(
					"name"        => esc_html__( 'Title Font Size', 'responsive-accordion-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 18,
					"description" => esc_html__( 'Set the title font size in pixels', 'responsive-accordion-slider' ),
					'priority'    => 10,
				),
				"titleColor"     => array(
					"name"        => esc_html__( 'Title Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the title color', 'responsive-accordion-slider' ),
					"default"     => "#ffffff",
					'priority'    => 15,
				),
				"titleBgColor"     => array(
					"name"        => esc_html__( 'Title Background Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the title background color', 'responsive-accordion-slider' ),
					"default"     => "",
					'priority'    => 20,
				),

				"hide-description"        => array(
					"name"        => esc_html__( 'Hide Caption', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show image caption from Accordion Slider', 'responsive-accordion-slider' ),
					'priority'    => 25,
				),
				"captionFontSize"  => array(
					"name"        => esc_html__( 'Caption Font Size', 'responsive-accordion-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 14,
					"description" => esc_html__( 'Set the caption font size in pixels', 'responsive-accordion-slider' ),
					'priority'    => 30,
				),
				"captionColor"     => array(
					"name"        => esc_html__( 'Caption Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the caption color', 'responsive-accordion-slider' ),
					"default"     => "#ffffff",
					'priority'    => 35,
				),
				"captionBgColor"     => array(
					"name"        => esc_html__( 'Caption Background Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the caption background color', 'responsive-accordion-slider' ),
					"default"     => "",
					'priority'    => 40,
				),	

				"hide-button"        => array(
					"name"        => esc_html__( 'Hide Button', 'responsive-accordion-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show Button from Accordion Slider', 'responsive-accordion-slider' ),
					'priority'    => 45,
				),
				"buttonFontSize"  => array(
					"name"        => esc_html__( 'Button Font Size', 'responsive-accordion-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 14,
					"description" => esc_html__( 'Set the Button font size in pixels', 'responsive-accordion-slider' ),
					'priority'    => 50,
				),
				"buttonBorder"  => array(
					"name"        => esc_html__( 'Button Border Radius', 'responsive-accordion-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 10,
					"description" => esc_html__( 'Set the Button Border Radius in pixels', 'responsive-accordion-slider' ),
					'priority'    => 55,
				),
				"buttonTextColor"     => array(
					"name"        => esc_html__( 'Button Text Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the Button Text color', 'responsive-accordion-slider' ),
					"default"     => "#ffffff",
					'priority'    => 60,
				),
				"buttonBgColor"     => array(
					"name"        => esc_html__( 'Button Background Color', 'responsive-accordion-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the Button background color', 'responsive-accordion-slider' ),
					"default"     => "#f4511e",
					'priority'    => 65,
				),	                 
			),

			'shortPhpcode'  => array(

				"ras-php-code"         => array(
					"name"        => esc_html__( 'PHP Code', 'responsive-accordion-slider' ),
					"type"        => "text-php",
					"description" => esc_html__( 'Copy PHP Code from here', 'responsive-accordion-slider' ),
					'priority' => 10,
				),
				"ras-php-short"         => array(
					"name"        => esc_html__( 'Shortcode', 'responsive-accordion-slider' ),
					"type"        => "text-short",
					"description" => esc_html__( 'Copy Shortcode from here', 'responsive-accordion-slider' ),
					'priority' => 20,
				),	
			),
			
			
			'customCSS' => array(
				"style"  => array(
					"name"        => esc_html__( 'Custom CSS', 'responsive-accordion-slider' ),
					"type"        => "custom-code",
					"syntax"      => 'css',
					"description" => '<strong>' . esc_html__( 'Just write the code without using the &lt;style&gt;&lt;/style&gt; tags', 'responsive-accordion-slider' ) . '</strong>',
					'priority' => 20,
				),
			),
		) );

		

		if ( 'all' == $tab ) {
			return $fields;
		}

		if ( isset( $fields[ $tab ] ) ) {
			return $fields[ $tab ];
		} else {
			return array();
		}

	}

	public static function resp_accordion_slider_get_defaults() {
		return apply_filters( 'resp_accordion_slider_default_settings', array(
            'type'                      => 'creative-gallery',
            'slider-width'              => '1000px',
            'slider-height' 		    => '500px',
			'titleColor'                => '#ffffff',
            'captionColor'              => '#ffffff',
            'buttonTextColor'           => '#ffffff',
            'wp_field_caption'          => 'none',
            'wp_field_title'            => 'none',
            'hide-title'                => 0,
            'ras-image-distance'		=> 1,
            'hide-description'          => 0,
            'hide-button'          		=> 0,
            'captionFontSize'           => '14',
            'titleFontSize'             => '18',
            'style'                     => '',
            'gutter'                    => 8,
            'ras-opened-image-size'     => '80%',
            'ras-open-image'			=> 'hover',
            'slider-shadow'		     	=> 1,
            'slider-autoplay'			=> 1,
            'slider-mouse-wheel'		=> 1,
        ) );
	}

}
