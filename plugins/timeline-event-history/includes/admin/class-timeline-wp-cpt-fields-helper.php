<?php

/**
 *
 */
class Timline_WP_CPT_Fields_Helper {

	public static function get_tabs() {

		$general_description = '<p>' . esc_html__( 'Choose between creative or custom grid (build your own). Pick your favorite lightbox style and easily design your gallery.', timeline_wp ) . '</p>';
		$caption_description = '<p>' . esc_html__( 'The settings below adjust how the image title/description will appear on the front-end.', timeline_wp ) . '</p>';
		$social_description = '<p>' . esc_html__( 'Here you can add social sharing buttons to your the images in your gallery.', timeline_wp ) . '</p>';
		$loadingeffects_description = '<p>' . esc_html__( 'The settings below adjust the effect applied to the images after the page is fully loaded.', timeline_wp ) . '</p>';
		$hover_description = '<p>' . esc_html__( 'Select how your images will behave on hover. Hover styles for your images.', timeline_wp ) . '</p>';
		$style_description = '<p>' . esc_html__( 'Here you can style the look of your images.', timeline_wp ) . '</p>';
		$customizations_description = '<p>' . esc_html__( 'Use this section to add custom CSS to your gallery for advanced modifications.', timeline_wp ) . '</p>';
		$filters_description = sprintf( '<p><strong>%s</strong><br><br>%s</p>',
			esc_html__( 'Easily let website visitors sort photos in your gallery by adding filters.', timeline_wp ),
			esc_html__( 'Use this tab to create new filters which you can then start assigning filters to by editing images individually or by using the bulk edit option.', timeline_wp )
		);
        $miscellaneous_description = '<p>' . esc_html__( 'Here you can enable copyright protection and Timeline_WP lightbox deeplink functionality', timeline_wp ) . '</p>';

		return apply_filters( 'timeline_wp_gallery_tabs', array(
			'general' => array(
				'label'       => esc_html__( 'General', timeline_wp ),
				'title'       => esc_html__( 'General Settings', timeline_wp ),
				'description' => $general_description,
				"icon"        => "dashicons dashicons-admin-generic",
				'priority'    => 10,
			),

			'style' => array(
				'label'       => esc_html__( 'Style', timeline_wp ),
				'title'       => esc_html__( 'Style Settings', timeline_wp ),
				'description' => $style_description,
				"icon"        => "dashicons dashicons-admin-appearance",
				'priority'    => 70,
			),
	    	'responsive' => array(
				'label'       => esc_html__( 'Responsive', timeline_wp ),
				'title'       => esc_html__( 'Responsive Settings', timeline_wp ),
				"icon"        => "dashicons dashicons-smartphone",
				'priority'    => 90,
			),
			'customizations' => array(
				'label'       => esc_html__( 'Custom CSS', timeline_wp ),
				'title'       => esc_html__( 'Custom CSS', timeline_wp ),
				'description' => $customizations_description,
				"icon"        => "dashicons dashicons-admin-tools",
				'priority'    => 90,
			),
			'phpcode' => array(
				'label'       => esc_html__( 'Shortcode', timeline_wp ),
				'title'       => esc_html__( '', timeline_wp ),
				"icon"        => "dashicons dashicons-media-code",
				'priority'    => 70,
			),
		) );

	}
	public static function get_fields( $tab ) {

		$fields = apply_filters( 'timeline_wp_gallery_fields', array(
			'general' => array(
				'type'           => array(
					"name"        => esc_html__( 'Timeline Style', timeline_wp ),
					"type"        => "select",
					"description" => esc_html__( 'Choose the type of Timeline you want to use.', timeline_wp ),
					'default'     => 'style-one',
					"values"      => array(
						'style-one' => esc_html__( 'Style One', timeline_wp ),
						'style-another'      => esc_html__( 'Style Two', timeline_wp ),
					),
					'priority' => 10,
				),
			),
			'phpcode'  => array(
				"php_short"         => array(
					"name"        => esc_html__( 'Shortcode', timeline_wp ),
					"type"        => "text_short",
					"description" => esc_html__( 'Copy Shortcode from here', timeline_wp ),
					'priority' => 5,
				),
			),
			'style' => array(
				"themeColor"     	=> array(
					"name"        	=> esc_html__( 'Theme Color', timeline_wp ),
					"type"        	=> "color",
					"description" 	=> esc_html__( 'Set the color of Theme.', timeline_wp ),
					"default"     	=> "",
					'priority'    	=> 5,
				),
				"titleColor"     	=> array(
					"name"        	=> esc_html__( 'Title Color', timeline_wp ),
					"type"        	=> "color",
					"description" 	=> esc_html__( 'Set the color of title.', timeline_wp ),
					"default"     	=> "",
					'priority'    	=> 5,
				),
				"iconColor"     	=> array(
					"name"        	=> esc_html__( 'Icon Color', timeline_wp ),
					"type"        	=> "color",
					"description" 	=> esc_html__( 'Set the color of Icon.', timeline_wp ),
					"default"     	=> "",
					'priority'    	=> 5,
				),	
				"dateColor"     	=> array(
					"name"        	=> esc_html__( 'Date Color', timeline_wp ),
					"type"        	=> "color",
					"description" 	=> esc_html__( 'Set the color of Date.', timeline_wp ),
					"default"     	=> "",
					'priority'    	=> 5,
				),
				"captionColor"     	=> array(
					"name"        	=> esc_html__( 'Caption Color', timeline_wp ),
					"type"        	=> "color",
					"description" 	=> esc_html__( 'Set the color of captions.', timeline_wp ),
					"default"     	=> "",
					'priority'    	=> 10,
				),
				"hide_title"        => array(
					"name"        	=> esc_html__( 'Hide Title', timeline_wp ),
					"type"        	=> "toggle",
					"default"     	=> 0,
					"description" 	=> esc_html__( 'Hide image titles from your gallery.', timeline_wp ),
					'priority'    	=> 40,
				),
				"titleFontSize"    	=> array(
					"name"        	=> esc_html__( 'Title Font Size', timeline_wp ),
					"type"        	=> "ui-slider",
					"min"         	=> 0,
					"max"         	=> 100,
					"default"     	=> 0,
					"description" 	=> esc_html__( 'The title font size in pixels (set to 0 to use the theme defaults).', timeline_wp ),
					'priority'    	=> 60,
				),
				"dateFontSize"    	=> array(
					"name"        	=> esc_html__( 'Date Font Size', timeline_wp ),
					"type"        	=> "ui-slider",
					"min"         	=> 0,
					"max"         	=> 100,
					"default"     	=> 0,
					"description" 	=> esc_html__( 'The Date font size in pixels (set to 0 to use the theme defaults).', timeline_wp ),
					'priority'    	=> 60,
				),
				"iconFontSize"    	=> array(
					"name"        	=> esc_html__( 'Icon Font Size', timeline_wp ),
					"type"        	=> "ui-slider",
					"min"         	=> 0,
					"max"         	=> 130,
					"default"     	=> 0,
					"description" 	=> esc_html__( 'The Icon font size in pixels (set to 0 to use the theme defaults).', timeline_wp ),
					'priority'    	=> 60,
				),
				"captionFontSize"  	=> array(
					"name"        	=> esc_html__( 'Caption Font Size', timeline_wp ),
					"type"        	=> "ui-slider",
					"min"         	=> 0,
					"max"         	=> 50,
					"default"     	=> '',
					"description" 	=> esc_html__( 'The caption font size in pixels (set to 0 to use theme defaults).', timeline_wp ),
					'priority'    	=> 70,
				),
			),
			'responsive' 	=> array(
				'enable_responsive' 	=> array(
					"name"        	=> esc_html__( 'Custom responsiveness', timeline_wp ),
					"description" => esc_html__( 'Force the gallery to show a certain number of column on tablet/mobile devices', timeline_wp ),
					"type"        => "toggle",
					"default"     => 0,
					'priority'    => 10,
				),
				'tablet_columns' => array(
					"name"        => esc_html__( 'Tablet Columns', timeline_wp ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Use this slider to adjust the number of columns for gallery on tablets.', timeline_wp ),
					"min"         => 1,
					"max"         => 6,
					"step"        => 1,
					"default"     => 2,
					'priority'    => 20,
				),
				'mobile_columns' => array(
					"name"        => esc_html__( 'Mobile Columns', timeline_wp ),
					"type"        => "ui-slider",
					"description" => esc_html__( 'Use this slider to adjust the number of columns for gallery on mobile devices.', timeline_wp ),
					"min"         => 1,
					"max"         => 6,
					"step"        => 1,
					"default"     => 1,
					'priority'    => 30,
				),
			),
			'customizations' => array(											
				
				"style"  => array(
					"name"        => esc_html__( 'Custom css', timeline_wp ),
					"type"        => "custom_code",
					"syntax"      => 'css',
					"description" => '<strong>' . esc_html__( 'Just write the code without using the &lt;style&gt;&lt;/style&gt; tags', timeline_wp ) . '</strong>',
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
		return apply_filters( 'timeline_wp_lite_default_settings', array(
            'type'                      => 'style-one',
            'titleColor'                => '',
            'iconColor'                	=> '',
            'themeColor'              	=> '',
            'captionColor'              => '',
            'wp_field_caption'          => 'none',
            'wp_field_title'            => 'none',
            'hide_title'                => 0,
            'captionFontSize'           => '',
            'titleFontSize'             => '',
            'mobileCaptionFontSize'     => '',
            'mobileTitleFontSize'       => '',
            'borderColor'               => '',
            'borderRadius'              => '0',
            'borderSize'                => '0',
            'shadowColor'               => '',
            'shadowSize'                => 0,
            'script'                    => '',
            'style'                     => '',
            'columns'                   => 6,
		) );
	}

}