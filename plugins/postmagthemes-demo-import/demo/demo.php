<?php
/**
 * Demo configuration
 */
$activate_theme = wp_get_theme();
$themeName      = $activate_theme->get( 'Name' );

if ( $pmdi_plugin->createSlug( $themeName ) == 'best-news' ) {
	$config = array(
		'static_page'    => 'home',
		'menu_locations' => array(
			'primary' => 'primary',
		),
		'pmdi'           => array(
			array(
				'import_file_name'           => esc_html__( 'Import Best News Demo', 'pt-pmdi' ),
				'categories'                 => array( __( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/bestnews/contents.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/bestnews/widgets.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/bestnews/customizer.dat' ),
				'import_notice'              => esc_html__( 'You have activated Best News theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demobestnews/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/bestnews/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/bestnews/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),

		),
	);

}

if ( $pmdi_plugin->createSlug( $themeName ) == 'isha' ) {
	$config = array(
		'static_page'    => 'home',
		'menu_locations' => array(
			'primary' => 'primary',
		),
		'pmdi'           => array(
			array(
				'import_file_name'           => esc_html__( 'Import Isha Demo', 'pt-pmdi' ),
				'categories'                 => array( __( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/isha/ishaportfolio.post.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/isha/demoisha-widgets.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/isha/isha-export-custo.dat' ),
				'import_notice'              => esc_html__( 'You have activated Isha theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demoisha/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/isha/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/isha/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),

		),
	);

}

if ( $pmdi_plugin->createSlug( $themeName ) == 'pro-isha' ) {
	$config = array(
		'static_page'    => 'blog',
		'menu_locations' => array(
			'primary' => 'primary',
		),
		'pmdi'           => array(
			array(
				'import_file_name'           => esc_html__( 'Import Pro Isha Demo', 'pt-pmdi' ),
				'categories'                 => array( __( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/proisha/proisha.WordPress.2020-01-04.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/proisha/www.postmagthemes.com-demoproisha-widgets_2.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/proisha/pro-isha-export.dat' ),
				'import_notice'              => esc_html__( 'You have activated Pro Isha theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demoproisha/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/proisha/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__( 'Coming soon', 'pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/proisha/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),

		),
	);

}



if ( $pmdi_plugin->createSlug( $themeName ) == 'color-newsmagazine' ) {
	$config = array(
		'static_page'    => 'blog',
		'menu_locations' => array(
			'primary' => 'primary',
		),
		'pmdi'           => array(
			array(
				'import_file_name'           => esc_html__( 'Import Color NewsMagazine', 'pt-pmdi' ),
				'categories'                 => array( __( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/contents1.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/widgets1.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/customizer1.dat' ),
				'import_notice'              => esc_html__( 'You have activated Color NewsMagazine theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/democolornewsmagazine/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__( 'Coming soon', 'pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),

		),
	);

}

if($pmdi_plugin->createSlug($themeName) == 'context-blog' ){
	$config = array(
	'static_page'    => 'blog',
	'menu_locations' => array(
		'primary' 	=> 'primary',
		'sidepanel' => 'sidepanel',
	),
	'pmdi'           => array(
		array(
			'import_file_name'             => esc_html__( 'Import Context Blog', 'pt-pmdi' ),
			'categories'                   => array( __('Category A','pt-pmdi') ),
			'import_file_url' 	           => esc_url( 'https://www.postmagthemes.com/download/contextblog/contents1.xml'),
			'import_widget_file_url'	     => esc_url( 'https://www.postmagthemes.com/download/contextblog/widgets1.wie'),
			'import_customizer_file_url'	 => esc_url( 'https://www.postmagthemes.com/download/contextblog/customizer1.dat'),
      		'import_notice'                => __( 'You have activated Context Blog theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
      		'preview_url'                  => esc_url('https://www.postmagthemes.com/democoontextblog/'),
			'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/contextblog/screenshot.png' ),
		),
		array(
			'import_file_name'             => 'Coming soon',
			'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
			'import_notice'                => __( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		
	),
	);
}

if($pmdi_plugin->createSlug($themeName) == 'context-blog-pro' ){
	$config = array(
	'static_page'    => 'blog',
	'menu_locations' => array(
		'primary' 	=> 'primary',
		'sidepanel' => 'sidepanel',
	),
	'pmdi'           => array(
		array(
			'import_file_name'             => esc_html__( 'Import Context Blog Pro', 'pt-pmdi' ),
			'categories'                   => array( __('Category A','pt-pmdi') ),
			'import_file_url' 	           => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/content.xml'),
			'import_widget_file_url'	     => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/widgets.wie'),
			'import_customizer_file_url'	 => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/customizer.dat'),
      		'import_notice'                => __( 'You have activated Context Blog Pro theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
      		'preview_url'                  => esc_url('https://contextblog.postmagthemes.com/ContextblogPro/'),
			'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/screenshot.png' ),
		),
		array(
			'import_file_name'             => 'Coming soon',
			'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
			'import_notice'                => __( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		
	),
	);
}

if ( isset( $config ) ) {
	Theme_Demo_Import::init( apply_filters( 'theme_demo_filter', $config ) );
}
