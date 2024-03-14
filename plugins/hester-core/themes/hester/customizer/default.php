<?php
add_filter( 'hester_default_option_values', 'hester_core_hester_default_options', 10, 1 );
function hester_core_hester_default_options( $defaults ) {
	// echo '<pre>';print_r($defaults);'</pre>';
	// disable front page sections
	$defaults['hester_enable_front_page'] = false;
	$defaults['hester_sections_order'] = '{"hester_section_info":10,"hester_section_services":15,"hester_section_extra":75,"hester_section_features":80,"hester_section_blog":85,"hester_section_products":90}';
	// Slider default
	$defaults['hester_enable_slider']     = true;
	$defaults['hester_slider_shape']      = 'wave';
	$defaults['hester_slider_style']      = '2';
	$defaults['hester_slider_height']     = array(
		'desktop' => 75,
		'tablet'  => 46,
		'mobile'  => 50,
		'unit'    => 'rem',
	);
	$defaults['hester_slider_title_font'] = hester_typography_defaults(
		array(
			'font-family'         => 'Plus Jakarta Sans',
			'font-weight'         => 800,
			'font-size-desktop'   => '6.15',
			'font-size-tablet'    => '4',
			'font-size-mobile'    => '3',
			'font-size-unit'      => 'rem',
			'line-height-desktop' => '1.24',
			'line-height-tablet'  => '1.2',
		)
	);
	$defaults['hester_slider_slides']     = apply_filters(
		'hester_core_slider_slides_default',
		array(
			array(
				'image'               => array(
					'url' => HESTER_CORE_PLUGIN_URL . '/assets/images/hero.jpg',
				),
				'background'          => array(
					'background-type'           => 'gradient',
					'background-color'          => '#1E293B',

					'gradient-color-1'          => 'rgba(64,30,161,0.81)',
					'gradient-color-1-location' => '0',
					'gradient-color-2'          => 'rgba(23,0,154,0.81)',
					'gradient-color-2-location' => '100',
					'gradient-type'             => 'linear',
					'gradient-linear-angle'     => '75',
					'gradient-position'         => 'center center',

				),
				'accent_color'        => '#17009A',
				'text_color'          => '#ffffff',
				'subtitle'            => wp_kses_post( 'INTRODUCING HESTER' ),
				'title'               => wp_kses_post( 'Build Your Amazing Website Fast.', 'hester-core' ),
				'text'                => wp_kses_post( 'Start building your own website today !', 'hester-core' ),
				'btn_1_text'          => esc_html__( 'Make a Website', 'hester-core' ),
				'btn_1_url'           => esc_html__( '#', 'hester-core' ),
				'btn_1_class'         => 'btn-primary',
				'btn_2_text'          => esc_html__( 'Download', 'hester-core' ),
				'btn_2_url'           => esc_html__( '#', 'hester-core' ),
				'btn_2_class'         => 'btn-white btn-outline',
				'alignment'           => 'start',
				'side_content_source' => '',
				'side_image'          => '',
				'side_shortcode'      => '',
				'open_in_popup'       => false,
				'popup_icon'          => 'fas fa-play',
			),
			array(
				'image'               => array(
					'url' => HESTER_CORE_PLUGIN_URL . '/assets/images/hero.jpg',
				),
				'background'          => array(
					'background-type'           => 'gradient',
					'background-color'          => '#1E293B',

					'gradient-color-1'          => 'rgba(64,30,161,0.81)',
					'gradient-color-1-location' => '0',
					'gradient-color-2'          => 'rgba(23,0,154,0.81)',
					'gradient-color-2-location' => '100',
					'gradient-type'             => 'linear',
					'gradient-linear-angle'     => '75',
					'gradient-position'         => 'center center',

				),
				'accent_color'        => '#17009A',
				'text_color'          => '#ffffff',
				'subtitle'            => wp_kses_post( 'INTRODUCING HESTER' ),
				'title'               => wp_kses_post( 'Build Your Amazing Website Fast.', 'hester-core' ),
				'text'                => wp_kses_post( 'Start building your own website today !', 'hester-core' ),
				'btn_1_text'          => esc_html__( 'Make a Website', 'hester-core' ),
				'btn_1_url'           => esc_html__( '#', 'hester-core' ),
				'btn_1_class'         => 'btn-primary',
				'btn_2_text'          => esc_html__( 'Download', 'hester-core' ),
				'btn_2_url'           => esc_html__( '#', 'hester-core' ),
				'btn_2_class'         => 'btn-white btn-outline',
				'alignment'           => 'center',
				'side_content_source' => '',
				'side_image'          => '',
				'side_shortcode'      => '',
				'open_in_popup'       => false,
				'popup_icon'          => 'fas fa-play',
			),
			array(
				'image'               => array(
					'url' => HESTER_CORE_PLUGIN_URL . '/assets/images/hero.jpg',
				),
				'background'          => array(
					'background-type'           => 'gradient',
					'background-color'          => '#1E293B',

					'gradient-color-1'          => 'rgba(64,30,161,0.81)',
					'gradient-color-1-location' => '0',
					'gradient-color-2'          => 'rgba(23,0,154,0.81)',
					'gradient-color-2-location' => '100',
					'gradient-type'             => 'linear',
					'gradient-linear-angle'     => '75',
					'gradient-position'         => 'center center',

				),
				'accent_color'        => '#17009A',
				'text_color'          => '#ffffff',
				'subtitle'            => wp_kses_post( 'INTRODUCING HESTER' ),
				'title'               => wp_kses_post( 'Build Your Amazing Website Fast.', 'hester-core' ),
				'text'                => wp_kses_post( 'Start building your own website today !', 'hester-core' ),
				'btn_1_text'          => esc_html__( 'Make a Website', 'hester-core' ),
				'btn_1_url'           => esc_html__( '#', 'hester-core' ),
				'btn_1_class'         => 'btn-primary',
				'btn_2_text'          => esc_html__( 'Download', 'hester-core' ),
				'btn_2_url'           => esc_html__( '#', 'hester-core' ),
				'btn_2_class'         => 'btn-white btn-outline',
				'alignment'           => 'end',
				'side_content_source' => '',
				'side_image'          => '',
				'side_shortcode'      => '',
				'open_in_popup'       => false,
				'popup_icon'          => 'fas fa-play',
			),
		)
	);

	// Info default
	$defaults['hester_enable_info']          = true;
	$defaults['hester_info_overlap']         = false;
	$defaults['hester_info_sub_heading']     = '';
	$defaults['hester_info_heading']         = '';
	$defaults['hester_info_description']     = '';
	$defaults['hester_info_slides']          = apply_filters(
		'hester_core_info_slides_default',
		array(
			array(
				'icon'        => 'far fa-building',
				'title'       => esc_html__( 'Startup Business', 'hester-core' ),
				'description' => esc_html__( 'Ante ipsum primis in as enda saepe, aspe.', 'hester-core' ),
				'add_link'    => false,
				'link'        => '',
				'linktext'    => '',
				'is_active'   => false,
			),
			array(
				'icon'        => 'far fa-shopping-bag',
				'title'       => esc_html__( 'Online Shop', 'hester-core' ),
				'description' => esc_html__( 'Ante ipsum primis in as enda saepe, aspe.', 'hester-core' ),
				'add_link'    => false,
				'link'        => '',
				'linktext'    => '',
				'is_active'   => false,
			),
			array(
				'icon'        => 'far fa-edit',
				'title'       => esc_html__( 'Easy Blogging', 'hester-core' ),
				'description' => esc_html__( 'Ante ipsum primis in as enda saepe, aspe.', 'hester-core' ),
				'add_link'    => false,
				'link'        => '',
				'linktext'    => '',
				'is_active'   => false,
			),
			array(
				'icon'        => 'far fa-thumbs-up',
				'title'       => esc_html__( '100% Satisfaction', 'hester-core' ),
				'description' => esc_html__( 'Ante ipsum primis in as enda saepe, aspe.', 'hester-core' ),
				'add_link'    => false,
				'link'        => '',
				'linktext'    => '',
				'is_active'   => false,
			),
		)
	);
	$defaults['hester_info_container_width'] = 'content-width';
	$defaults['hester_info_section_spacing'] = array(
		'desktop' => array(
			'top'    => 0,
			'bottom' => 0,
		),
		'tablet'  => array(
			'top'    => 0,
			'bottom' => 0,
		),
		'mobile'  => array(
			'top'    => 6,
			'bottom' => 6,
		),
		'unit'    => 'rem',
	);
	$defaults['hester_info_column']          = '-3';
	$defaults['hester_info_style']           = '';

	// Services section
	$defaults['hester_enable_services']      = true;
	$defaults['hester_services_sub_heading'] = esc_html__( 'SERVICES', 'hester-core' );
	$defaults['hester_services_heading']     = esc_html__( 'What We Offer', 'hester-core' );
	$defaults['hester_services_description'] = wp_kses_post( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit ui enean et commodo ligula eget dolorenean massa quam felis ultricies nec elit ligula.', 'hester-core' );

	$defaults['hester_services_slides']          = apply_filters(
		'hester_core_services_slides_default',
		array(
			array(
				'icon'        => 'fas fa-pencil-ruler',
				'title'       => esc_html__( 'Website Designing', 'hester-core' ),
				'description' => esc_html__( 'Dolores sit ipsum velit purus aliquet, massa fringilla leo orci sit ipsum euelit amet magnis.', 'hester-core' ),
				'add_link'    => true,
				'link'        => '#',
				'linktext'    => '',
				'add_image'   => false,
				'image'       => '',
			),
			array(
				'icon'        => 'far fa-lightbulb',
				'title'       => esc_html__( 'SEO Optimization', 'hester-core' ),
				'description' => esc_html__( 'Dolores sit ipsum velit purus aliquet, massa fringilla leo orci sit ipsum euelit amet magnis.', 'hester-core' ),
				'add_link'    => true,
				'link'        => '#',
				'linktext'    => '',
				'add_image'   => false,
				'image'       => '',
			),
			array(
				'icon'        => 'far fa-life-ring',
				'title'       => esc_html__( 'Lifetime Updates', 'hester-core' ),
				'description' => esc_html__( 'Dolores sit ipsum velit purus aliquet, massa fringilla leo orci sit ipsum euelit amet magnis.', 'hester-core' ),
				'add_link'    => true,
				'link'        => '#',
				'linktext'    => '',
				'add_image'   => false,
				'image'       => '',
			),
		)
	);
	$defaults['hester_services_container_width'] = 'content-width';
	$defaults['hester_services_column']          = '-4';

	// Extra section
	$defaults['hester_enable_extra']          = false;
	$defaults['hester_enable_extra_page']     = null;
	$defaults['hester_extra_container_width'] = 'content-width';

	// Feature section
	$defaults['hester_enable_features']      = true;
	$defaults['hester_features_sub_heading'] = esc_html__( 'WHY CHOOSE US', 'hester-core' );
	$defaults['hester_features_heading']     = esc_html__( 'Our Features', 'hester-core' );
	$defaults['hester_features_description'] = wp_kses_post( 'Et in risus egestas nec vitae odio ac nibh vestibulum volutpat aliquet aenean erat lobortis non.<br><br>Nibh egestas dictumst cursus est turpis quis tincidunt pulvinar maecenas eget massa vel, ante nam blandit egestas enim id quis sit maecenas.', 'hester-core' );

	$defaults['hester_features_slides']             = apply_filters(
		'hester_core_features_slides_default',
		array(
			array(
				'icon'        => 'fas fa-chart-pie',
				'title'       => esc_html__( 'Market Research', 'hester-core' ),
				'description' => '',
				'add_link'    => true,
				'link'        => '#',
			),
			array(
				'icon'        => 'fas fa-file-alt',
				'title'       => esc_html__( 'Content Writing', 'hester-core' ),
				'description' => '',
				'add_link'    => true,
				'link'        => '#',
			),
			array(
				'icon'        => 'fas fa-search-plus',
				'title'       => esc_html__( 'Keyword Analysis', 'hester-core' ),
				'description' => '',
				'add_link'    => true,
				'link'        => '#',
			),
			array(
				'icon'        => 'fas fa-headset',
				'title'       => esc_html__( '24/7 Support', 'hester-core' ),
				'description' => '',
				'add_link'    => true,
				'link'        => '#',
			),
		)
	);
	$defaults['hester_features_background']         = hester_design_options_defaults(
		array(
			'background' => array(
				'background-type' => 'image',
				'color'           => array(),
				'gradient'        => array(),
				'image'           => array(
					'background-image'         => HESTER_CORE_PLUGIN_URL . 'assets/images/img01.png',
					'background-attachment'    => 'fixed',
					'background-color-overlay' => 'rgba(23,0,154,0.85)',
				),
			),
		)
	);
	$defaults['hester_features_text_color']         = hester_design_options_defaults(
		array(
			'color' => array(
				'text-color' => '#FFFFFF',
			),
		)
	);
	$defaults['hester_features_container_width']    = 'content-width';

	// Home blog
	$defaults['hester_enable_blog']       = true;
	$defaults['hester_blog_sub_heading']  = esc_html__( 'OUR BLOGS', 'hester-core' );
	$defaults['hester_blog_heading']      = esc_html__( 'Latest News', 'hester-core' );
	$defaults['hester_blog_description']  = wp_kses_post( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit ui enean et commodo ligula eget dolorenean massa quam felis ultricies nec elit ligula.', 'hester-core' );
	$defaults['hester_blog_posts_number'] = '3';
	$defaults['hester_blog_column']       = '-4';

	// Home products
	$defaults['hester_enable_products']      = false;
	$defaults['hester_products_sub_heading'] = esc_html__( 'OUR PRODUCTS', 'hester-core' );
	$defaults['hester_products_heading']     = esc_html__( 'Latest Products', 'hester-core' );
	$defaults['hester_products_description'] = wp_kses_post( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit ui enean et commodo ligula eget dolorenean massa quam felis ultricies nec elit ligula.', 'hester-core' );

	return $defaults;
}
