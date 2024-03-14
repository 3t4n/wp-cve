<?php

/**
 *
 */
class Photo_Gallery_CPT_Fields_Helper {

	public static function get_tabs() {

		
		return apply_filters( 'photo_gallery_tabs', array(
			'general' => array(
				'label'       => esc_html__( 'General', 'photo-gallery-builder' ),
				'title'       => esc_html__( 'General Settings', 'photo-gallery-builder' ),
				'description' => 'Select Either Masonry or Grid and Select lightbox and links style',
				"icon"        => "dashicons dashicons-admin-generic",
				'priority'    => 10,
			),
			
			'captions' => array(
				'label'       => esc_html__( 'Captions', 'photo-gallery-builder' ),
				'title'       => esc_html__( 'Caption Settings', 'photo-gallery-builder' ),
				'description' => 'The settings shown below adjust how the image title/description will appear on the front-end gallery 				 layout',
				"icon"        => "dashicons dashicons-menu-alt3",
				'priority'    => 20,
			),
			
			'style' => array(
				'label'       => esc_html__( 'Style', 'photo-gallery-builder' ),
				'title'       => esc_html__( 'Style Settings', 'photo-gallery-builder' ),
				'description' => 'Style the Gallery Images.',
				"icon"        => "dashicons dashicons-admin-customizer",
				'priority'    => 70,
			),

			'phpcode' => array(
				'label'       => esc_html__( 'Shortcode / PHP Code', 'accordion-slider' ),
				'title'       => esc_html__( '', 'accordion-slider' ),
				/*'description' => $phpcode_description,*/
				"icon"        => "dashicons dashicons-editor-code",
				'priority'    => 70,
			),
			
			'customizations' => array(
				'label'       => esc_html__( 'Custom CSS', 'photo-gallery-builder' ),
				'title'       => esc_html__( 'Custom CSS', 'photo-gallery-builder' ),
				'description' => 'Add custom CSS to gallery Layout for advanced modifications',
				"icon"        => "dashicons dashicons-admin-tools",
				'priority'    => 90,
			),
            
		) );

	}

	

	public static function get_fields( $tab ) {

		$fields = apply_filters( 'photo_gallery_fields', 
			array(
				'general' => array(
					'type'           => array(
					"name"        => esc_html__( 'Gallery Type', 'photo-gallery-builder' ),
					"type"        => "select",
					"description" => esc_html__( 'Select the type of gallery which you want to use.', 'photo-gallery-builder' ),
					'default'     => 'custom-grid',
					"values"      => array(
						'creative-gallery' => esc_html__( 'Masonry', 'photo-gallery-builder' ),
						'custom-grid'      => esc_html__( 'Grid', 'photo-gallery-builder' ),
					),
					'priority' => 10,
				),

				"layout" => array(
					"name"		  => esc_html__('Select Layout', 'photo-gallery-builder' ),
					"type"   	  => "select",
					"description" => esc_html__('Select the type of Photo Gallery Layout which you want to use', 'photo-gallery-builder'),
					"default" 	  => 2,
					"values"      => array(
						'1'		  => esc_html__( 'Basic Layout', 'photo-gallery-builder' ),
						'2'       => esc_html__( 'Inner Layout 1', 'photo-gallery-builder' ),
						'3'		  => esc_html__( 'Inner Layout 2', 'photo-gallery-builder' ),	
						'4' 	  => esc_html__( 'Inner layout 3', 'photo-gallery-builder' ),
						'5'		  => esc_html__( 'Outer Layout 1', 'photo-gallery-builder' ),	
						'6'		  => esc_html__( 'Outer Layout 2', 'photo-gallery-builder' ),	
					),
					"priority" 	  => 20,		
				),

				"select_column"   => array(
					"name"		  => esc_html__('Select Column Layout', 'photo-gallery-builder' ),
					"type"   	  => "select",
					"description" => esc_html__('Select the type of Column Layout which you want to use', 'photo-gallery-builder'),
					"default" 	  => 4,
					"values"      => array(
						'6' 	  => esc_html__( '2 Column Layout', 'photo-gallery-builder' ),
						'4'       => esc_html__( '3 Column Layout', 'photo-gallery-builder' ),
						'3'		  => esc_html__( '4 Column Layout', 'photo-gallery-builder' ),	
					),
					"priority" 	  => 21,	
				),

				"gutter"   => array(
					"name"        => esc_html__( 'Margin', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Set the spacing between gallery images', 'photo-gallery-builder' ),
					"min"         => 0,
					"max"         => 100,
					"step"        => 1,
					"default"     => 8,
					'priority'    => 30,
				),
								
				"margin"         => array(
					"name"        => esc_html__( 'Margin', 'photo-gallery-builder' ),
					"type"        => "text",
					'default'     => 8,
					"description" => esc_html__( 'Set the spacing between gallery images in pixels', 'photo-gallery-builder' ),
					'priority' => 60,
				),

			
				"pgb_lightbox"        => array(
					"name"        => esc_html__( 'Lightbox', 'photo-gallery-builder' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Show/Hide lightbox', 'photo-gallery-builder' ),
					'priority'    => 110,
				),
				
			),
			'captions' => array(

				"titleColor"     => array(
					"name"        => esc_html__( 'Title Color', 'photo-gallery-builder' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the title color', 'photo-gallery-builder' ),
					"default"     => "#ffffff",
					'priority'    => 5,
				),

				"captionColor"     => array(
					"name"        => esc_html__( 'Caption Color', 'photo-gallery-builder' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the caption color', 'photo-gallery-builder' ),
					"default"     => "#ffffff",
					'priority'    => 10,
				),

				"contentBg"     => array(
					"name"        => esc_html__( 'Content Background / Hover Color', 'photo-gallery-builder' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the Content Background color / Hover Color', 'photo-gallery-builder' ),
					"default"     => "#38ef7d",
					'priority'    => 20,
				),
				
				"hide_title"        => array(
					"name"        => esc_html__( 'Hide Title', 'photo-gallery-builder' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide image title from gallery layout', 'photo-gallery-builder' ),
					'priority'    => 40,
				),

				"hide_description"        => array(
					"name"        => esc_html__( 'Hide Caption', 'photo-gallery-builder' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide image caption from gallery layout', 'photo-gallery-builder' ),
					'priority'    => 50,
				),
				"titleFontSize"    => array(
					"name"        => esc_html__( 'Title Font Size', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 18,
					"description" => esc_html__( 'Set the title font size in pixels', 'photo-gallery-builder' ),
					'priority'    => 60,
				),
				"captionFontSize"  => array(
					"name"        => esc_html__( 'Caption Font Size', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"min"         => 0,
					"max"         => 50,
					"default"     => 14,
					"description" => esc_html__( 'Set the caption font size in pixels', 'photo-gallery-builder' ),
					'priority'    => 70,
				),
                
			),
			
			
			'style' => array(
				"borderSize"   => array(
					"name"        => esc_html__( 'Border Size', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Set the border size of images in gallery layout', 'photo-gallery-builder' ),
					"min"         => 0,
					"max"         => 10,
					"default"     => 0,
					'priority'    => 10,
				),
				"borderRadius" => array(
					"name"        => esc_html__( 'Border Radius', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Set the border radius of the image in gallery layout', 'photo-gallery-builder' ),
					"min"         => 0,
					"max"         => 100,
					"default"     => 0,
					'priority'    => 20,
				),
				"borderColor"  => array(
					"name"        => esc_html__( 'Border Color', 'photo-gallery-builder' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the Border color of image in gallery layout', 'photo-gallery-builder' ),
					"default"     => "#ffffff",
					'priority'    => 30,
				),
				"shadowSize"   => array(
					"name"        => esc_html__( 'Shadow Size', 'photo-gallery-builder' ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Set the image shadows in gallery layout', 'photo-gallery-builder' ),
					"min"         => 0,
					"max"         => 20,
					"default"     => 0,
					'priority'    => 40,
				),
				"shadowColor"  => array(
					"name"        => esc_html__( 'Shadow Color', 'photo-gallery-builder' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of image shadow in gallery layout', 'photo-gallery-builder' ),
					"default"     => "#ffffff",
					'priority'    => 50,
				),
			),

			'phpcode'  => array(
				"php_short"         => array(
					"name"        => esc_html__( 'Shortcode', 'accordion-slider' ),
					"type"        => "text_short",
					"description" => esc_html__( 'Copy Shortcode from here', 'accordion-slider' ),
					'priority' => 5,
				),

				"php_code"         => array(
					"name"        => esc_html__( 'PHP Code', 'accordion-slider' ),
					"type"        => "text_php",
					"description" => esc_html__( 'Copy PHP Code from here', 'accordion-slider' ),
					'priority' => 10,
				),
			),
			
			'customizations' => array(
				"style"  => array(
					"name"        => esc_html__( 'Custom CSS', 'photo-gallery-builder' ),
					"type"        => "custom_code",
					"syntax"      => 'css',
					"description" => '<strong>' . esc_html__( 'Just write the code without using the &lt;style&gt;&lt;/style&gt; tags', 'photo-gallery-builder' ) . '</strong>',
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
		return apply_filters( 'photo_gallery_lite_default_settings', array(
            'type'                      => 'custom-grid',
            'img_size'                  => 300,
            'margin'                    => '8',
            'pgb_lightbox'              => '1',
            'titleColor'                => '#ffffff',
            'captionColor'              => '#ffffff',
            'contentBg'					=> '#38ef7d',	
            'wp_field_caption'          => 'none',
            'wp_field_title'            => 'none',
            'hide_title'                => 0,
            'hide_description'          => 0,
            'captionFontSize'           => '14',
            'titleFontSize'             => '18',
            'borderColor'               => '#ffffff',
            'borderRadius'              => '0',
            'borderSize'                => '0',
            'shadowColor'               => '#ffffff',
            'shadowSize'                => 0,
            'style'                     => '',
            'gutter'                    => 8,
        ) );
	}

}
