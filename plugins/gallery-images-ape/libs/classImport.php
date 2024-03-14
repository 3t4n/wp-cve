<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class ApeGalleryImportThemes {

	private $themeVersion = WPAPE_GALLERY_OPTIONS_VERSION;	
	
	private $themesImported = array();
	
	private $skipFields = array( 'title', 'name', 'code', 'default', );

	private $themes = array(
		'slidertheme1' => array( 
			'title' => 'Slider Theme',
			'name' => 'slider_theme_1',
			'type' => 'slider',
			'autoplay'  => 1,
			'delay'		=> 2500,

			'autoWidth' => 1,
			'autoHeight' => 0,
			'height' => array(
				'value' => 80,
				'type' => 'vh',
			),
			
			'nav_buttons'		=> 'show',
			'nav_scrollbar'		=> 'show',
			'nav_pagination'		=> 'bullets',

			'direction'		=> 'horizontal',

			'effect'		=> 'slide',

			'preload'		=> 'preload',

			'orderby'		=> 'categoryD',
			
			'source'		=> 'original',
		),

		'gridtheme1' => array(
			'title' => 'Gallery Grid Theme',
			'name' => 'gallery_grid_theme1',
			'type' => 'grid',
			'default' => '1',
			'width-size' => array(
				'width' => 100,
				'widthType' => 0,
			),
			'paddingCustom' => array( 
				'left' => 5,
				'top' => 5,
				'bottom' => 5,
				'right' => 5,
			),

			'colums' => array( 
				'autowidth' => 1,
				'colums'	=> 3,
			),
			
			'orderby' => 'categoryD',

			'source' => 'medium',

			'thumb-options' => array(
				'xspace' => '10',
				'yspace' => '10',
				'radius' => '5',
			),

			'align' => '',

			/* lightBox */
			'lightboxColor' => 'rgb(243,243,243)',
			'lightboxBackground' => 'rgba(11,11,11,0.8)',
			'lightboxSwipe' => '1',
			'arrows' => '0',
			'social' => array(),

			/* hover */
			'thumbClick' => 1,
			'hover' => 1,
			'background' => 'rgba(7,7,7,0.5)',
			'overlayEffect' => 'direction-aware-fade',

			'showTitle' => array(
				'enabled' => 1,
				'fontStyle' => array(),
				'fontSize' => 12,
				'fontLineHeight' => 88,
				'color' => '#ffffff',
				'colorHover' => '#ffffff',
			),

			'showDesc' => array(
				'enabled' => 0,
			),

			'linkIcon' => array(
				'enabled' => 0,
			),

			'zoomIcon' => array(
				'enabled' => 1,
				'iconSelect' => 'fa-search',
				'borderSize' => '0',
				'fontSize' => '22',
				'fontLineHeight' => '100',
				'color' => '#ffffff',
				'colorHover' => '#ffffff',
				'colorBg' => 'rgba(0,0,0,0)',
				'colorBgHover' => 'rgba(0,0,0,0)',
			),

			/* menu */
			'menu' => 1,
			'menuSelfImages' => 1,
			'menuHome' => 'label',
			'menuRootLabel' => 'Home',

			'buttonFill' => 'flat',
			'buttonEffect' => '',
			'buttonShadow' => '',
			'buttonColor' => 'blue',
			'buttonType' => 'normal',
			'buttonSize' => 'normal',
			'buttonAlign' => 'left',
			'paddingMenu' => array(
				'left' => 5,
				'bottom' => 10,
			),

			/* lazy load */
			'lazyLoad' => 1,
			'boxesToLoadStart' => 9,
			'boxesToLoad' => 6,
			'loadingBgColor' => 'rgba(255,255,255,1)',
			'LoadingWord' => 'Gallery images loading',
			'loadMoreWord' => 'More images',
			'noMoreEntriesWord' => 'No images',

			/* polaroid */
			'polaroidOn' => '0',
		),
	);

	public function __construct(){
		
		if( isset($_GET['initTheme']) && $_GET['initTheme']=='true' ){
			delete_option( 'wpApeGalleryThemesImported', array() );
		}

		if( $this->isThemesImported() ) return ;
		
		$this->importThemes();
	}


	private function isThemesImported(){
		
		$this->initThemesImported();

		if( count($this->themesImported) == 0 ) return false;

		foreach ($this->themes as $key => $theme){
			if( !array_key_exists( $key, $this->themesImported ) ) return false;
		}

		return true;
	}


	private function initThemesImported(){

		$this->themesImported = get_option( 'wpApeGalleryThemesImported', array() );

		if( !is_array($this->themesImported) )  $this->themesImported = array();
	}


	private function importThemes(){

		foreach ($this->themes as $key => $theme){
			
			if( array_key_exists( $key, $this->themesImported ) ) continue;

			$theme['code'] = $key;

			$theme['id'] = $this->createTheme( $theme );

			if( !$theme['id'] )  continue;

			$this->setDefaultTheme( $theme );

			$this->setThemeType( $theme );	

			$this->setMetaData( $theme );

			$this->themesImported[$key] = $theme['id'];

		}

		update_option( 'wpApeGalleryThemesImported', $this->themesImported );
	}

	private function createTheme( $theme ){
		$args = array(
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => get_current_user_id( ),
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_name'      => $theme['name'],
			'post_parent'    => 0,
			'post_password'  => '',
			'post_status'    => 'publish',
			'post_title'     => $theme['title'],
			'post_type'      => WPAPE_GALLERY_THEME_POST,
			'to_ping'        => '',
			'menu_order'     => 0,
			'tags_input'      => '',
		);
 
		$newThemeId = (int) wp_insert_post( $args );

		if( !$newThemeId ) return false;

		return $newThemeId;
	}


	private function setThemeType( $theme ){

		add_post_meta( $theme['id'], WPAPE_GALLERY_NAMESPACE.'type', $theme['type'], true );

	}


	private function setMetaData( $theme ){

		foreach ($theme as $key => $value ){

			if( in_array( $key, $this->skipFields ) ) continue ;
			
			add_post_meta( $theme['id'], WPAPE_GALLERY_NAMESPACE.$key, $value, true );
		}

	}


	private function setDefaultTheme( $theme ){

		$isDefault = isset( $theme['default'] ) && (int) $theme['default'];
		
		$isDefaultSetNow =  get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 );

		if( $isDefault && !$isDefaultSetNow ) update_option( WPAPE_GALLERY_PREFIX.'default_theme', $theme['id'] );

	}

}