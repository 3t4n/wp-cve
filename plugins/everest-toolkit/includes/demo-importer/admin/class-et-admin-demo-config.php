<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class ET_Admin_Demo_Config {

	private $theme        = '';
	private $import_class = '';

	public function __construct() {

		$this->theme = wp_get_theme();
		add_filter( 'et-demo-content-import', array( $this, 'import_files' ) );
		add_action( 'et-after-demo-content-import', array( $this, 'after_import' ) );
	}

	private function get_import_class() {

		$supported_themes = $this->supported_themes();
		$demo_class       = '';
		foreach ( $supported_themes as $theme ) {

			$theme_name = isset( $theme['theme_name'] ) ? $theme['theme_name'] : '';

			if ( trim( $theme_name ) === trim( $this->theme ) ) {

				$demo_class = isset( $theme['demo_class'] ) ? $theme['demo_class'] : '';
				break;
			}
		}

		return $demo_class;
	}

	private function supported_themes() {

		return array(

			'everest_news'          => array(

				'theme_name' => 'Everest News',
				'demo_class' => 'ET_Theme_Demo_Everest_News',
			),
			'everest_news_pro'      => array(

				'theme_name' => 'Everest News Pro',
				'demo_class' => 'ET_Theme_Demo_Everest_News_Pro',
			),
			'mocho_blog'            => array(

				'theme_name' => 'Mocho Blog',
				'demo_class' => 'ET_Theme_Demo_Mocho_Blog',
			),
			'mocho_blog_pro'        => array(

				'theme_name' => 'Mocho Blog Pro',
				'demo_class' => 'ET_Theme_Demo_Mocho_Blog_Pro',
			),
			'viable_blog'           => array(

				'theme_name' => 'Viable Blog',
				'demo_class' => 'ET_Theme_Demo_Viable_Blog',
			),
			'viable_blog_pro'       => array(

				'theme_name' => 'Viable Blog Pro',
				'demo_class' => 'ET_Theme_Demo_Viable_Blog_Pro',
			),
			'arya_multipurpose'     => array(

				'theme_name' => 'Arya Multipurpose',
				'demo_class' => 'ET_Theme_Demo_Arya_Multipurpose',
			),
			'arya_multipurpose_pro' => array(

				'theme_name' => 'Arya Multipurpose Pro',
				'demo_class' => 'ET_Theme_Demo_Arya_Multipurpose_Pro',
			),
			'everest_news_lite'     => array(

				'theme_name' => 'Everest News Lite',
				'demo_class' => 'ET_Theme_Demo_Everest_News_Lite',
			),
			'ultra_lite_blog'       => array(

				'theme_name' => 'Ultra Lite Blog',
				'demo_class' => 'ET_Theme_Demo_Ultra_Lite_Blog',
			),
			'gucherry_blog'         => array(

				'theme_name' => 'GuCherry Blog',
				'demo_class' => 'ET_Theme_Demo_Gucherry_Blog',
			),
			'gucherry_blog_pro'     => array(

				'theme_name' => 'GuCherry Blog Pro',
				'demo_class' => 'ET_Theme_Demo_Gucherry_Blog_Pro',
			),
			'gucherry_lite'         => array(

				'theme_name' => 'GuCherry Lite',
				'demo_class' => 'ET_Theme_Demo_Gucherry_Lite',
			),
			'grace_mag'             => array(

				'theme_name' => 'Grace Mag',
				'demo_class' => 'ET_Theme_Demo_Grace_Mag',
			),
			'grace_mag_pro'         => array(

				'theme_name' => 'Grace Mag Pro',
				'demo_class' => 'ET_Theme_Demo_Grace_Mag_Pro',
			),
			'influence_blog'        => array(

				'theme_name' => 'Influence Blog',
				'demo_class' => 'ET_Theme_Demo_Influence_Blog',
			),
			'influence_blog_pro'    => array(

				'theme_name' => 'Influence Blog Pro',
				'demo_class' => 'ET_Theme_Demo_Influence_Blog_Pro',
			),
			'sports_highlight'      => array(
				'theme_name' => 'Sports Highlight',
				'demo_class' => 'ET_Theme_Demo_Sports_Highlight',
			),
		);
	}


	public function import_files() {

		$import_class = $this->get_import_class();

		if ( empty( $import_class ) ) {

			return array();
		}

		return $import_class::import_files();
	}

	public function after_import( $selected_import ) {

		$import_class = $this->get_import_class();

		if ( empty( $import_class ) ) {

			return '';
		}

		$import_class::after_import( $selected_import );
	}
}

new ET_Admin_Demo_Config();
