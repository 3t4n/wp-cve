<?php
//namespace Kitpack_Lite_elementor\Modules\Kitpack_Icon;

defined( 'ABSPATH' ) || exit;

class Kitpack_Lite_Fonts {

	

	public function __construct()
	{
		$this->fonts_list = $this->get_fonts_list();
	}

	public function font_groups($font_groups){
		$font_groups['PARSI'] = esc_html__( 'Farsi Fonts', 'kitpack-lite' );
		return $font_groups;
	}

	public function get_fonts_list(){
		//$fonts_list = '';
		$fonts_list = [
			"Anjoman" => "anjoman-font",
			"Vazir" => "vazir-font",
			"VazirFN" => "vazir-font",
			"Samim" => "samim-font",
			"Shabnam" => "samim-font",
			"ShabnamFN" => "shabnam-font",
			"mikhak" => "mikhak-font",
			"kara" => "kara-font",
		];

		//return $fonts_list;
		return apply_filters( 'kitpack-lite/elementor/fonts-list', $fonts_list );
	}
	public function add_fonts($fonts)
	{
		foreach($this->fonts_list as $key => $value){		
			if ( Kitpack_Lite_Admin::kpe_get_option($value)) {
				$fonts[$key] = 'PARSI';
			}
		}
/* 		$fonts['Anjoman'] = 'PARSI';
		$fonts['Vazir'] = 'PARSI';
		$fonts['VazirFN'] = 'PARSI';
		$fonts['Samim'] = 'PARSI';
		$fonts['Shabnam'] = 'PARSI';
		$fonts['ShabnamFN'] = 'PARSI';
		$fonts['mikhak'] = 'PARSI';
		$fonts['kara'] = 'PARSI'; */
		return $fonts;
	}

	public function add_fonts_style()
	{
		
		foreach(array_unique($this->fonts_list) as $key => $value){		
			if ( Kitpack_Lite_Admin::kpe_get_option($value)) {
				wp_enqueue_style( "$value", "https://c751370.parspack.net/c751370/elementor-plus/$value.css" );
			}
		}
/* 		wp_enqueue_style( 'anjoman-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/anjoman-font.css' );

		wp_enqueue_style( 'vazir-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/vazir-font.css' );

		wp_enqueue_style( 'samim-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/samim-font.css' );

		wp_enqueue_style( 'shabnam-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/shabnam-font.css' );

		wp_enqueue_style( 'mikhak-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/mikhak-font.css' );

		wp_enqueue_style( 'kara-font-elementor', 'https://c751370.parspack.net/c751370/elementor-plus/kara-font.css' );
 */
	}
	
}