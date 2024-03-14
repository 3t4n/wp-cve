<?php

/**
 *
 */
class Img_Slider_WP_CPT_Fields_Helper {

	public static function get_tabs() {

		//$src = IMG_SLIDER_IMAGES. 'title.jpg';


		/*$general_description = '<p>' . esc_html__( 'Select General Settings of the Image Slider', 'img-slider' ) . '</p>';*/
		/*$design_description = '<p>' . esc_html__( 'Select One of the Image Slider design', 'img-slider' ) . '</p>';*/
		/*$caption_description = '<p>' . esc_html__( 'The settings shown below adjust how the image title/description will appear on the front-end Image Slider', 'img-slider' ) . '</p>';*/
		/*$phpcode_description = '<p>' . esc_html__( 'Find PHP Code Here', 'img-slider' ) . '</p>';*/
		/*$customizations_description = '<p>' . esc_html__( 'Add custom CSS to Image Slider for advanced modifications', 'img-slider' ) . '</p>';*/
		
		return apply_filters( 'img_slider_gallery_tabs', array(
			'general' => array(
				'label'       => esc_html__( 'General', 'img-slider' ),
				'title'       => esc_html__( 'General Settings', 'img-slider' ),
				'description' => /*'<img src="'. $src .'">',*/ 
								'Select General Settings of the Image Slider',
				"icon"        => "dashicons dashicons-admin-generic",
				'priority'    => 10,
			),

			'design' => array(
				'label'       => esc_html__( 'Design', 'img-slider' ),
				'title'       => esc_html__( 'Design Settings', 'img-slider' ),
				'description' => 'Select One of the Image Slider design',
				"icon"        => "dashicons dashicons-admin-customizer",
				'priority'    => 10,
			),
			
			'captions' => array(
				'label'       => esc_html__( 'Captions', 'img-slider' ),
				'title'       => esc_html__( 'Caption Settings', 'img-slider' ),
				'description' => 'The settings shown below adjust how the image title/description will appear on the front-end Image Slider',
				"icon"        => "dashicons dashicons-menu-alt3",
				'priority'    => 20,
			),

			'sliderControls' => array(
				'label'       => esc_html__( 'Slider Controls', 'img-slider' ),
				'title'       => esc_html__( 'Slider Controls', 'img-slider' ),
				'description' => 'The settings shown below change the color and size of the slider controls',
				"icon"        => "dashicons dashicons-editor-code",
				'priority'    => 30,
			),
			
			'phpcode' => array(
				'label'       => esc_html__( 'PHP Code', 'img-slider' ),
				'title'       => esc_html__( '', 'img-slider' ),
				/*'description' => $phpcode_description,*/
				"icon"        => "dashicons dashicons-media-code",
				'priority'    => 70,
			),
			
			'customizations' => array(
				'label'       => esc_html__( 'Custom CSS', 'img-slider' ),
				'title'       => esc_html__( 'Custom CSS', 'img-slider' ),
				'description' => 'Add custom CSS to Image Slider for advanced modifications',
				"icon"        => "dashicons dashicons-admin-tools",
				'priority'    => 90,
			),
            
		) );

	}

	public static function get_fields( $tab ) {

		$fields = apply_filters( 'img_slider_gallery_fields', 
			array(
				'general' => array(
					'font_family' => array(
					"name"        => esc_html__( 'Font Family', 'img-slider' ),
					"type"        => "select",
					"description" => esc_html__( 'Select the font family you want to use', 'img-slider' ),
					'default'     => 'Arial',
					"values"      => array(
						'Times New Roman' => esc_html__( 'Default', 'img-slider' ),
						'Arial' 		  => esc_html__( 'Arial', 'img-slider' ),
						'Arial Black'     => esc_html__( 'Arial Black', 'img-slider' ),
						'Calibri'	 	  => esc_html__( 'Calibri', 'img-slider' ),
						'Candara'	 	  => esc_html__( 'Candara', 'img-slider' ),
						'Courier New'	  => esc_html__( 'Courier New', 'img-slider' ),
						'Georgia'		  => esc_html__( 'Georgia', 'img-slider' ),
						'Grande'		  => esc_html__( 'Grande', 'img-slider' ),
						'Helvetica'		  => esc_html__( 'Helvetica', 'img-slider' ),
						'Impact' 		  => esc_html__( 'Impact', 'img-slider' ),
						'Lucida' 		  => esc_html__( 'Lucida', 'img-slider' ),
						'Lucida Grande'   => esc_html__( 'Lucida Grande', 'img-slider' ),
						'Open Sans'       => esc_html__( 'Open Sans', 'img-slider' ),
						'OpenSansBold'    => esc_html__( 'OpenSansBold', 'img-slider' ),
						'Optima'  		  => esc_html__( 'Optima', 'img-slider' ),
						'Palatino Linotype' => esc_html__( 'Palatino', 'img-slider' ),
						'Sans' 			  => esc_html__( 'Sans', 'img-slider' ),
						'sans-serif'	  => esc_html__( 'Sans-serif', 'img-slider' ),
						'Tahom'           => esc_html__( 'Tahom', 'img-slider' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'img-slider' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'img-slider' ),
						'Verdana' 		  => esc_html__( 'Verdana', 'img-slider' ),
					),
					'priority' => 30,
				),

				"width"          => array(
					"name"        => esc_html__( 'Width', 'img-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Set the width of the Image Slider, Can be in % or pixels.', 'img-slider' ),
					'default'     => '100%',
					'priority' => 35,
				),

				"hide_navigation"        => array(
					"name"        => esc_html__( 'Hide Navigation', 'img-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show Image Slider Navigation', 'img-slider' ),
					'priority'    => 40,
				),	

				"auto_play"        => array(
					"name"        => esc_html__( 'Autoplay', 'img-slider' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Auto Play Image Slider', 'img-slider' ),
					'priority'    => 45,
				),

				"slide_duration"          => array(
					"name"        => esc_html__( 'Slide Duration', 'img-slider' ),
					"type"        => "text",
					"description" => esc_html__( 'Set the Auto Play Time of the Image Slider, in ms', 'img-slider' ),
					'default'     => '3000',
					'priority' => 50,
				),

				"numOfImages"    => array(
					"name"        => esc_html__( 'Number Of Image In Slider', 'img-slider' ),
					"type"        => "ui-slider",
					"min"         => 1,
					"max"         => 4,
					"default"     => 3,
					"description" => esc_html__( 'Set the title font size in pixels', 'img-slider' ),
					'priority'    => 60,
				),			
			),
			
			/*--------------layout settings-------------*/
				'design' => array(
					"designName"     => array(
						"name"        => esc_html__( '', 'img-slider' ),
						"type"        => "custom_text",
						"default"     => "Boxed_Slider",
						'priority'    => 5,
					),
			),
			/*--------------End of layout settings-------------*/


			'captions' => array(
				"titleColor"     => array(
					"name"        => esc_html__( 'Title Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the title color', 'img-slider' ),
					"default"     => "#ffffff",
					'priority'    => 5,
				),
				"titleBgColor"     => array(
					"name"        => esc_html__( 'Title Background Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the title background color', 'img-slider' ),
					"default"     => "",
					'priority'    => 10,
				),
				"captionColor"     => array(
					"name"        => esc_html__( 'Caption Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the caption color', 'img-slider' ),
					"default"     => "#ffffff",
					'priority'    => 15,
				),
				"captionBgColor"     => array(
					"name"        => esc_html__( 'Caption Background Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the caption background color', 'img-slider' ),
					"default"     => "",
					'priority'    => 20,
				),

				
				"hide_title"        => array(
					"name"        => esc_html__( 'Hide Title', 'img-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show image title from Image Slider', 'img-slider' ),
					'priority'    => 40,
				),
				"hide_description"        => array(
					"name"        => esc_html__( 'Hide Caption', 'img-slider' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide/Show image caption from Image Slider', 'img-slider' ),
					'priority'    => 50,
				),
				"titleFontSize"    => array(
					"name"        => esc_html__( 'Title Font Size', 'img-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 18,
					"description" => esc_html__( 'Set the title font size in pixels', 'img-slider' ),
					'priority'    => 60,
				),
				"captionFontSize"  => array(
					"name"        => esc_html__( 'Caption Font Size', 'img-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 14,
					"description" => esc_html__( 'Set the caption font size in pixels', 'img-slider' ),
					'priority'    => 70,
				),


				"sliderColor"     => array(
					"name"        => esc_html__( 'Content Background Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the slider color', 'img-slider' ),
					"default"     => "#fe1f4e",
					'priority'    => 80,
				),

				"sliderBgColor"     => array(
					"name"        => esc_html__( 'Content Background Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the slider color', 'img-slider' ),
					"default"     => "#222733ba",
					'priority'    => 90,
				),
                
			),

			/*slider controls*/
			'sliderControls' => array(
				"contorlsFontSize"  => array(
					"name"        => esc_html__( 'Controls Font Size', 'img-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 60,
					"default"     => 32,
					"description" => esc_html__( 'Set the conrols font size in pixels', 'img-slider' ),
					'priority'    => 10,
				),

				"controlsColor"     => array(
					"name"        => esc_html__( 'Controls Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the controls color', 'img-slider' ),
					"default"     => "#107eb6",
					'priority'    => 20,
				),

				"controlsBgColor"     => array(
					"name"        => esc_html__( 'Controls Background Color', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the controls color', 'img-slider' ),
					"default"     => "transparent",
					'priority'    => 30,
				),

				"contorlsBgBorderRadius"  => array(
					"name"        => esc_html__( 'Controls Background Border Radius', 'img-slider' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 100,
					"default"     => 0,
					"description" => esc_html__( 'Set the conrols backgroud Border Radius in percent', 'img-slider' ),
					'priority'    => 10,
				),

				"controlsColorOnHover"     => array(
					"name"        => esc_html__( 'Controls Color on Hover', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the controls color on Hover', 'img-slider' ),
					"default"     => "#fff",
					'priority'    => 40,
				),

				"controlsBgColorOnHover"     => array(
					"name"        => esc_html__( 'Controls Background Color on Hover', 'img-slider' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the controls color on Hover', 'img-slider' ),
					"default"     => "#107eb6",
					'priority'    => 50,
				),
                
			),

			'phpcode'  => array(
				"php_short"         => array(
					"name"        => esc_html__( 'Shortcode', 'img-slider' ),
					"type"        => "text_short",
					"description" => esc_html__( 'Copy Shortcode from here', 'img-slider' ),
					'priority' => 5,
				),

				"php_code"         => array(
					"name"        => esc_html__( 'PHP Code', 'img-slider' ),
					"type"        => "text_php",
					"description" => esc_html__( 'Copy PHP Code from here', 'img-slider' ),
					'priority' => 10,
				),
			),
			
			
			'customizations' => array(
				"style"  => array(
					"name"        => esc_html__( 'Custom CSS', 'img-slider' ),
					"type"        => "custom_code",
					"syntax"      => 'css',
					"description" => '<strong>' . esc_html__( 'Just write the code without using the &lt;style&gt;&lt;/style&gt; tags', 'img-slider' ) . '</strong>',
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

	public static function get_defaults() {
		return apply_filters( 'img_slider_lite_default_settings', array(
            'type'                      => 'custom-grid',
            'layout'					=> 'Boxed Slider',	
            'width'                     => '100%',
            'auto_play'					=> 1,
			'designName'				=> 'Boxed_Slider',
			'titleColor'                => '#ffffff',
			'slide_duration'			=> '3000',
            'captionColor'              => '#ffffff',
            'sliderColor' 				=> '#fe1f4e',
            'wp_field_caption'          => 'none',
            'wp_field_title'            => 'none',
            'hide_title'                => 0,
            'hide_description'          => 0,
            'captionFontSize'           => '14',
            'titleFontSize'             => '18',
            'style'                     => '',
            'gutter'                    => 8,
        ) );
	}

}
