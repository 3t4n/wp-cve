<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

apeGalleryHelper::load('classImport.php');

class ApeGalleryUpdate {

	private $optionsVersion 		= WPAPE_GALLERY_OPTIONS_VERSION;

	private $namespace 				= WPAPE_GALLERY_NAMESPACE;

	private $keyThemeAssigned 		= '';
	private $keyThemeId 			= '';
	private $keyThemeType 			= '';

	private $galleries = array();
	
	private $themes = array();

	private $installVersion = null;

	private $newVersion = null;

	private $fieldGalleryArray = array(
		'1.0.0' => array( 
			'start' => 0 
		),
	); 

	private $skipFields = array(
		'wpape_menuLabel',
		'wpape_menuLabelText',
		'wpape_menuSelf',
		'wpape_galleryImages',
	);

	private $specialFields = array(
		'wpape_showTitle',
		'wpape_showDesc',
		'wpape_colums',
		'wpape_social',
	);

	public function __construct(){

		$importThemes = new ApeGalleryImportThemes();

		$this->initVariable();

		if( isset($_GET['ape_clear_install']) && $_GET['ape_clear_install'] ){
			$this->clearInstall();
			return ;
		}

		if( !$this->isNeedUpdate() ) return ;

		//if( isset($_GET['ape_run_update']) && $_GET['ape_run_update'] ){
		//	echo 'updated success';
			$this->runUpdate();
			return ;
		//}
	}

	private function initVariable(){ 
		$this->keyThemeAssigned = WPAPE_GALLERY_NAMESPACE.'theme_assigned';
		$this->keyThemeId 		= WPAPE_GALLERY_NAMESPACE.'themeId';
		$this->keyThemeType 	= WPAPE_GALLERY_NAMESPACE.'type';
	}	

	private  function isNeedUpdate(){

		$this->installVersion = get_option( 'wpApeGalleryInstallVersion', 0 );

		$this->newVersion = WPAPE_GALLERY_VERSION;

		apeGalleryHelper::writeLog( "classUpdate installed version ".$this->installVersion.' new version '.$this->newVersion);

		if( $this->installVersion && $this->installVersion == $this->newVersion ) return false;

		return true; 
	}

	private  function runUpdate(){
		apeGalleryHelper::writeLog("classUpdate run Update");

		update_option( 'apeGalleryInstallTime', time() );

		update_option( "wpApeGalleryInstallVersion", $this->newVersion );
		
		$this->loadGalleries();

		$this->convertGalleriesToThemes();

		$this->updateGalleryFields();
	}


	private function loadGalleries(){

		$my_wp_query = new WP_Query();

 		$this->galleries =  $my_wp_query->query(
			array( 
				'post_type' => WPAPE_GALLERY_POST, 
				'posts_per_page' => 9999, 
			)
		);
	}

	private function fieldsGalleryInit( $fields ){

		apeGalleryHelper::writeLog("classUpdate fieldsGalleryInit ");

		for($i=0;$i<count($this->galleries);$i++){

			$galleryId = $this->galleries[$i]->ID;

			foreach($fields as $key => $value){

				add_post_meta( $galleryId, $this->namespace.$key, $value, true );

			}
		}
	}

	private function updateGalleryFields(){
		
		if( !is_array($this->fieldGalleryArray) || !count($this->fieldGalleryArray) ) return ;

		apeGalleryHelper::writeLog("classUpdate updateGalleryFields ");

		foreach($this->fieldGalleryArray as $version => $fields){
			if( 
				version_compare( $version, $this->installVersion, '>') && 
				version_compare( $version, $this->newVersion, '<=') 
			){
				if( !is_array($fields) || !count($fields) ) continue ;
				
				$this->fieldsGalleryInit( $fields );				
			}
		}
	}


	/*  convert part */

	private function convertGalleriesToThemes(){

		apeGalleryHelper::writeLog("classUpdate convertGalleriesToThemes ");

		for( $i=0; $i < count( $this->galleries ); $i++ ){

			$gallery = $this->galleries[$i];

			if ( $gallery instanceof WP_Post ) {

 				$galleryProcessed =  
 					(int) get_post_meta( $gallery->ID, $this->keyThemeAssigned, true ) || 
 					(int) get_post_meta( $gallery->ID, $this->keyThemeId, true )
 				;

				if( !$galleryProcessed ) $this->createThemeForGallery( $gallery );
			}
		}

	}


	private function createThemeForGallery( $gallery ){

		apeGalleryHelper::writeLog("classUpdate createThemeForGallery ");

		$galleryId = $gallery->ID;

		$args = array(
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => $gallery->post_author,
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_name'      => $gallery->post_name,
			'post_parent'    => 0,
			'post_password'  => '',
			'post_status'    => 'publish',
			'post_title'     => __('Theme for ', 'gallery-images-ape') . $gallery->post_title,
			'post_type'      => WPAPE_GALLERY_THEME_POST,
			'to_ping'        => '',
			'menu_order'     => 0,
			'tags_input'      => '',
		);
 
		$newThemeId = (int) wp_insert_post( $args );

		if( $newThemeId == 0 ) return ;
		
		$this->setThemeType( $newThemeId );	

		$this->copyMetaData( $galleryId, $newThemeId );		

		$this->setThemeForGallery( $galleryId, $newThemeId );
	}


	private function copyMetaData( $fromGalleryId, $toThemeId ){

		$keys = get_post_custom_keys($fromGalleryId);
		
		if( !is_array($keys) || !count($keys) ) return ;

		$this->initFields($toThemeId);

		foreach ( $keys as $keyNumber => $key ){

			if( $this->checkMetaDataField($key) == false ) continue ;			
			
			$value = get_post_meta( $fromGalleryId, $key, true );

			

			if( $this->isSpecialFields($key) ) $value = $this->convertSpecialFields( $key, $value );

			if( add_post_meta( $toThemeId, $key, $value, true ) === false ){
				update_post_meta( $toThemeId, $key, $value );
			}		
						
		}
	}


	private function checkMetaDataField( $key ){
		return strpos( $key, $this->namespace) === 0  && !in_array( $key, $this->skipFields);
	}

	private function setThemeForGallery( $GalleryId, $themeId ){
		add_post_meta( $GalleryId, $this->keyThemeId, $themeId, true );
		add_post_meta( $GalleryId, $this->keyThemeAssigned, $themeId, true );
	}

	private function setThemeType( $themeId ){
		add_post_meta( $themeId, $this->keyThemeType, 'grid', true );
	}

	private function isSpecialFields( $field ){
		return  in_array( $field, $this->specialFields );
	}


	private function initFields( $themeId ){

		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'thumbClick', 		0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'hover', 			0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'polaroidOn', 		0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'lazyLoad', 		0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'sizeType', 		0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'menu', 			0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'lightboxSwipe', 	0, true );
		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'arrows', 			0, true );

		add_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'arrows', array(
			'twitter'	=> 0, 
			'facebook'	=> 0, 
			'googleplus'=> 0,
		), true);

	}

	private function convertSpecialFields( $key, $value ){

		if(  
			in_array( $key, array('wpape_showTitle', 'wpape_showDesc' ) )
		){
			$value['fontStyle']= array( 
				'fontUnderline' => isset($value['fontUnderline']) && $value['fontUnderline']== 'underline'	? 1: 0,
				'fontBold'		=> isset($value['fontBold']) 	  && $value['fontBold'] 	== 'bold'		? 1: 0,
				'fontItalic'	=> isset($value['fontItalic']) 	  && $value['fontItalic'] 	== 'italic' 	? 1: 0,
			);
		}

		if( $key == 'wpape_colums' ){
			$value['autowidth']  = isset($value['autowidth'])  && $value['autowidth']=='auto' ? 1 : 0;
			$value['autowidth1'] = isset($value['autowidth1']) && $value['autowidth1']=='auto' ? 1 : 0;
			$value['autowidth2'] = isset($value['autowidth2']) && $value['autowidth2']=='auto' ? 1 : 0;
		}

		if( $key == 'wpape_social' ){
			$value['twitter']  = isset($value['twitter'])  && $value['twitter'] ? 1 : 0;
			$value['facebook']  = isset($value['facebook'])  && $value['facebook'] ? 1 : 0;
			$value['googleplus']  = isset($value['googleplus'])  && $value['googleplus'] ? 1 : 0;
		}

		

		return $value;
	}

	private function clearInstall( ){

		$my_wp_query = new WP_Query();
 		$themes =  $my_wp_query->query(
			array( 
				'post_type' => WPAPE_GALLERY_THEME_POST, 
				'posts_per_page' => 9999, 
			)
		);

		for( $i=0; $i < count( $themes); $i++ ){	
			wp_delete_post( $themes[$i]->ID, true );
		}

		$this->loadGalleries();

		for( $i=0; $i < count( $this->galleries ); $i++ ){			
			delete_post_meta( $this->galleries[$i]->ID, $this->keyThemeId );
			delete_post_meta( $this->galleries[$i]->ID, $this->keyThemeAssigned );
		}		

		update_option( "wpApeGalleryInstallVersion", '1.6.5' );
	}

}