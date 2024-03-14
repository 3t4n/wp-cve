<?php
/**
 * Sports Highlight Theme Info Configurations
 *
 * @package Everest_Toolkit
 */

if ( ! function_exists( 'everest_toolkit_sports_highlight_config' ) ) {

	function everest_toolkit_sports_highlight_config() {

		$config = array(
			'menu_name'           => esc_html__( 'Sports Highlight Info', 'everest-toolkit' ),
			'page_name'           => esc_html__( 'Sports Highlight Info', 'everest-toolkit' ),

			/* translators: theme version */
			'welcome_title'       => sprintf( esc_html__( 'Welcome to %s - ', 'everest-toolkit' ), 'Sports Highlight' ),

			'notification'        => '',

			/* translators: 1: theme name */
			'welcome_content'     => sprintf( esc_html__( 'We hope this page will help you to setup %1$s with few clicks. We believe you will find it easy to use and perfect for your website development.', 'everest-toolkit' ), 'everest-toolkit' ),

			// Quick links.
			'quick_links'         => array(
				'demo_import_url'   => array(
					'text'   => esc_html__( 'Import Demo', 'everest-toolkit' ),
					'url'    => esc_url( admin_url( 'themes.php?page=everest-demo-importer' ) ),
					'button' => 'primary',
				),
				'theme_url'         => array(
					'text' => esc_html__( 'Theme Details', 'everest-toolkit' ),
					'url'  => 'https://demo.everestthemes.com/sports-highlight/demo/',
				),
				'demo_url'          => array(
					'text' => esc_html__( 'View Demo', 'everest-toolkit' ),
					'url'  => 'https://demo.everestthemes.com/sports-highlight/demo/',
				),
				'documentation_url' => array(
					'text' => esc_html__( 'View Documentation', 'everest-toolkit' ),
					'url'  => 'https://demo.everestthemes.com/sports-highlight/demo/',
				),
				'pro_url'           => array(
					'text'   => esc_html__( 'Upgrade To Pro Theme', 'everest-toolkit' ),
					'url'    => 'https://demo.everestthemes.com/sports-highlight/demo/',
					'button' => 'primary',
				),
			),

			// Tabs.
			'tabs'                => array(
				'getting_started'     => esc_html__( 'Getting Started', 'everest-toolkit' ),
				'recommended_actions' => esc_html__( 'Recommended Actions', 'everest-toolkit' ),
				'support'             => esc_html__( 'Support', 'everest-toolkit' ),
			),

			// Getting started.
			'getting_started'     => array(
				array(
					'title'               => esc_html__( 'Import Demo Content', 'everest-toolkit' ),
					'text'                => esc_html__( 'Setup theme easily by importing demo contents.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'Import Demo', 'everest-toolkit' ),
					'button_link'         => esc_url( admin_url( 'themes.php?page=everest-demo-importer' ) ),
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => false,
				),
				array(
					'title'               => esc_html__( 'Theme Documentation', 'everest-toolkit' ),
					'text'                => esc_html__( 'Find step by step instructions with video documentation to setup theme easily.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'View documentation', 'everest-toolkit' ),
					'button_link'         => 'https://demo.everestthemes.com/sports-highlight/demo/',
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),
				array(
					'title'               => esc_html__( 'Recommended Actions', 'everest-toolkit' ),
					'text'                => esc_html__( 'We recommend few steps to take so that you can get complete site like shown in demo.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'Check recommended actions', 'everest-toolkit' ),
					'button_link'         => esc_url( admin_url( 'themes.php?page=sports-highlight-about&tab=recommended_actions' ) ),
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => false,
				),
				array(
					'title'               => esc_html__( 'Customize Everything', 'everest-toolkit' ),
					'text'                => esc_html__( 'Start customizing every aspect of the website with customizer.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'Go to Customizer', 'everest-toolkit' ),
					'button_link'         => esc_url( wp_customize_url() ),
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => false,
				),

				array(
					'title'               => esc_html__( 'Youtube Video Tutorials', 'everest-toolkit' ),
					'text'                => esc_html__( 'Please check our youtube channel for video instructions of everest-toolkit setup and customization.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'Video Tutorials', 'everest-toolkit' ),
					'button_link'         => 'https://www.youtube.com/playlist?list=PLgMh7pEKsDa5hWwJIa2J_gl1Knep5h5d1',
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),

				array(
					'title'               => esc_html__( 'Pro Version', 'everest-toolkit' ),
					'text'                => esc_html__( 'Upgrade to pro version for additional features and options.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'View Pro Version', 'everest-toolkit' ),
					'button_link'         => 'https://demo.everestthemes.com/sports-highlight/demo/',
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),

				'first' => array(
					'title'               => esc_html__( 'Contact Support', 'everest-toolkit' ),
					'text'                => esc_html__( 'If you have any problem, feel free to create ticket on our dedicated Support forum.', 'everest-toolkit' ),
					'button_label'        => esc_html__( 'Contact Support', 'everest-toolkit' ),
					'button_link'         => 'https://everestthemes.com/support-forum/',
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),
			),

			// Recommended actions.
			'recommended_actions' => array(
				'content' => array(
					'omnipress'      => array(
						'title'       => esc_html__( 'Omnipress', 'everest-toolkit' ),
						'description' => esc_html__( 'Omnipress is a ready-made WordPress Design Blocks, similar to the Gutenberg WordPress block editor, that takes a holistic approach to changing your complete site.', 'everest-toolkit' ),
						'check'       => function_exists( 'omnipress_init' ),
						'plugin_slug' => 'omnipress',
						'id'          => 'omnipress',
					),
					'everest-backup' => array(
						'title'       => esc_html__( 'Everest Backup', 'everest-toolkit' ),
						'description' => esc_html__( 'Need Backup Need Backup !!.', 'everest-toolkit' ),
						'check'       => function_exists( 'everest_backup' ),
						'plugin_slug' => 'everest-backup',
						'id'          => 'everest-backup',
						'optional'    => true,
					),
				),
			),

			// Support.
			'support_content'     => array(
				'first'  => array(
					'title'        => esc_html__( 'Contact Support', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-sos',
					'text'         => esc_html__( 'If you have any problem, feel free to create ticket on our dedicated Support forum.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'Contact Support', 'everest-toolkit' ),
					'button_link'  => 'https://everestthemes.com/support-forum/',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'second' => array(
					'title'        => esc_html__( 'Theme Documentation', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-book-alt',
					'text'         => esc_html__( 'Kindly check our theme documentation for detailed information and video instructions.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'View Documentation', 'everest-toolkit' ),
					'button_link'  => 'https://demo.everestthemes.com/sports-highlight/demo/',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'third'  => array(
					'title'        => esc_html__( 'Pro Version', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-products',
					'icon'         => 'dashicons dashicons-star-filled',
					'text'         => esc_html__( 'Upgrade to pro version for additional features and options.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'View Pro Version', 'everest-toolkit' ),
					'button_link'  => 'https://demo.everestthemes.com/sports-highlight/demo/',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'fourth' => array(
					'title'        => esc_html__( 'Youtube Video Tutorials', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-video-alt3',
					'text'         => esc_html__( 'Please check our youtube channel for video instructions of everest-toolkit setup and customization.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'Video Tutorials', 'everest-toolkit' ),
					'button_link'  => '#',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'fifth'  => array(
					'title'        => esc_html__( 'Customization Request', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-admin-tools',
					'text'         => esc_html__( 'We have dedicated team members for theme customization. Feel free to contact us any time if you need any customization service.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'Customization Request', 'everest-toolkit' ),
					'button_link'  => 'https://everestthemes.com/support-forum/',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'sixth'  => array(
					'title'        => esc_html__( 'Child Theme', 'everest-toolkit' ),
					'icon'         => 'dashicons dashicons-admin-customizer',
					'text'         => esc_html__( 'If you want to customize theme file, you should use child theme rather than modifying theme file itself.', 'everest-toolkit' ),
					'button_label' => esc_html__( 'About child theme', 'everest-toolkit' ),
					'button_link'  => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/',
					'is_button'    => true,
					'is_new_tab'   => true,
				),
			),
		);

		Everest_Toolkit_Theme_Info::init( $config );
	}
}

add_action( 'after_setup_theme', 'everest_toolkit_sports_highlight_config' );

