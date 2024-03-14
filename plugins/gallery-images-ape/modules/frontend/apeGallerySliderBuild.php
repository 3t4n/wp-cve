<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;


apeGalleryHelper::load( 'frontend/slider/classLoader.php', WPAPE_GALLERY_MODULES_PATH );

class apeGallerySliderBuild extends apeGalleryBuildV2{

	public $premium = false;

 	public $id = 0;

 	public $originalId = 0;

 	public $themeId = 0;

 	public $fromIds = '';

 	public $returnHtml = '';

 	
 	public $galleryId = '';
 	public $helper = '';

 	public $imagesSource = null;
 	
 	public $orderby = 'categoryD';
 	public $thumbsource = 'medium';

	public $uniqid = '';
	

 	function __construct($attr, $fromIds = '' ){

 		$this->helper 		= new apeGalleryRenderHelper();

 		$id = 0;

 		if( is_array($attr) ){
 			if( 		isset($attr['id'])  && ( (int) $attr['id'] )   ) 	$id = (int) $attr['id'];
 			else if( 	isset($attr[0]) 	&& ( (int) $attr[0] )  ) 		$id = (int) $attr[0];
 		} 

 		if( $fromIds ){
			$fromIds = explode(',', $fromIds);
			if( is_array($fromIds) && count($fromIds) ){
				$this->fromIds = $fromIds;
				$id = get_option( WPAPE_GALLERY_NAMESPACE.'sourceGallery', '0' );
			}
 		}

 		$this->initThemeId( $id );
 		
 		$this->uniqid 	 = 'wpape_slider_'.uniqid();
 		$this->galleryId = 'wpape_slider_'.$this->originalId;
 	}


 	private function initThemeId( $id = 0 ){
 		
 		if( !$id ) return;

		$this->id = $id;
		
		$this->themeId = $this->getIntMeta( 'themeId', -1 );
		
		if( $this->themeId==-1 ){
			$this->themeId = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 );
		}

		$this->originalId = $this->id;
		$this->id = $this->themeId;

		$this->helper->setId( $this->id );
 	}
 	
 	private function initAssetsFiles(){ 
 		/*  js script */
 		$this->addJsFile(  array(
			'fileName' 	=>	WPAPE_GALLERY_ASSET.'vendor.slider',
			'fileUrl' 	=> 	'assets/slider/slider.min.js',
			'fileDepend'=> array()
 		));
 		$this->addJsFile(  array(
			'fileName' 	=>	WPAPE_GALLERY_ASSET.'script.slider',
			'fileUrl' 	=> 	'assets/js/script.slider.js',
			'fileDepend'=> array( WPAPE_GALLERY_ASSET.'vendor.slider' )
 		));

 		/*  css style */
 		$this->addCssFile(  array(
			'fileName' 	=>	WPAPE_GALLERY_ASSET.'vendor.slider',
			'fileUrl' 	=> 	'assets/slider/slider.min.css',
			'fileDepend'=> 	array()
 		)); 		
 		$this->addCssFile(  array(
			'fileName' 	=>	WPAPE_GALLERY_ASSET.'slider',
			'fileUrl' 	=> 	'assets/css/slider.css',
			'fileDepend'=>	array()
 		));

 		if( get_option( WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest' ) =='include' ){			
			$this->setContent( $this->getCodeAssetsFiles(), 'end' );
		} else {
			add_action( 'get_footer', array( $this, 'includeAssetsFiles' ) );
		}
		
	}
 	
	private function initImages(){
		$this->imagesSource = new apeGallerySource( $this->originalId, $this->themeId, $this->fromIds );
		$width = 244;
		$height = 150;

		$this->orderby =  $this->getMeta('orderby') ? $this->getMeta('orderby') : $this->orderby ;
		$this->thumbsource = $this->getMeta('source');

		$this->imagesSource->setSize( $width , $height, $this->thumbsource, $this->orderby );
		$this->imagesSource->getImages();
	}

	private function isImageCorrect(){
		return  is_array($this->imagesSource->imagesList) && count($this->imagesSource->imagesList);
	}


 	public function getGallery( ){

 		if( !$this->id ) return ' Ape Gallery Error: 4444 Id is empty. Please select gallery and visit this page again'; 

 		$this->initImages();

 		if( !$this->isImageCorrect() ) return 'Ape Gallery Error: 5555 Images of your gallery is empty. Please upload images to the gallery and visit this page again';

 		$this->initAssetsFiles();

		$this->initModules();

		$this->initSize();

		$this->initAutoPlay();
		
		$this->initRTL();

		/*$static = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'static', true );
		$static = false;

		if( !$this->fromIds && $static ){
			$gallery_static =  get_transient( 'ape_gallery_static'. $this->originalId );
			if($gallery_static){
				$gallery_static .= '<!-- static ape gallery -->';
				return $gallery_static;
			}
 		}*/

		$this->initBaseOptions();

		$this->initSliderView();

		$this->initNavigation();

		$this->initScrollbar();

		$this->initPagination();

		$this->initDirection();

		$this->initEffect();

		$this->initPreload();

		$this->makeSlides();

	/*	if( !$this->fromIds && $static ){
			set_transient( 'ape_gallery_static'.$this->id , $this->returnHtml, 7 * 24 * HOUR_IN_SECONDS );
		}*/

		return $this->renderSlider();;
 	}


 	

 	private function initModules(){
 		$this->modules['loader'] = new apeGallerySliderLoader( $this );
	}

	private function initRTL(){
		if ( !is_rtl() ) return ;
		$this->setContent( ' dir="rtl" ', 'attrMainDiv');
	}

 	private function renderSlider(){
 		$sliderHtml = ''
			.$this->getContent('begin')
			.$this->getContent('beforeMainDiv')
			.'<div id="'.$this->uniqid.'" style="'.$this->getContent('styleMainDiv').'" class="wpape-slider-container swiper-container" '.$this->getContent('attrMainDiv').'>'
				.$this->getContent('beginMainDiv')
				.'<div id="'.$this->galleryId.'" data-options="'.$this->galleryId.'" class="wpape-slider-'.$this->galleryId.' swiper-wrapper">'
					.$this->getContent('items')
				.'</div>'
				.$this->getContent('endMainDiv')
			.'</div>'			
			.$this->getContent('afterMainDiv')
			.'<script>'.$this->initJSCode().'</script>'
			.$this->getContent('end');

		return $sliderHtml;
 	}

 	
 	private function initAutoPlay(){
 		if( $this->getBoolMeta('autoplay') == false  )  return ;
		$this->helper->setArrayValue( 
			'autoplay', 
			array(
				'delay' => $this->getIntMeta( 'delay' ),  
				'disableOnInteraction' => false ,
			)
		);
	}


 	private function initSize(){
 		if( !$this->getBoolMeta( 'autoWidth' ) ) {
 			$width = $this->getMeta('width');
			$widthStyle = '100%;';
			if(isset($width['value']) && isset($width['type']) ) $widthStyle = $width['value'].$width['type'];
			$this->setContent( 'width:'.$widthStyle.';', 'styleMainDiv');
		}

		if( !$this->getBoolMeta( 'autoHeight' ) ) {
			$height = $this->getMeta('height');
			$heightStyle = '100vh;';
			if(isset($height['value']) && isset($height['type']) ) $heightCss = $height['value'].$height['type'];
			$this->setContent( 'height:'.$heightCss.';', 'styleMainDiv');
		}

/*		$padding = $this->getMeta( 'padding' );
		if( isset($padding['left']) 	&& $padding['left'] ) 	$this->setContent( 'margin-left:'.	(int) $padding['left'].'px;', 'styleMain1Div'); ;
		if( isset($padding['top']) 		&& $padding['top'] )	$this->setContent( 'margin-top:'.	(int) $padding['top'].'px;', 'styleMain1Div');
		if( isset($padding['right']) 	&& $padding['right'] ) 	$this->setContent( 'margin-right:'.(int) $padding['right'].'px;', 'styleMain1Div');
		if( isset($padding['bottom']) 	&& $padding['bottom'] )	$this->setContent( 'margin-bottom:'.(int) $padding['bottom'].'px;', 'styleMain1Div');*/
 	}


 	private function initPreload(){

 		switch ( $this->getMeta('preload') ) {
 			case 'off':
 					$this->helper->setBoolValue( 'preloadImages', false );
 					$this->helper->setBoolValue( 'updateOnImagesReady', false );
 				break;

 			case 'lazy_white':
 					$this->helper->setBoolValue( 'preloadImages', false );
 					$this->helper->setBoolValue( 'lazy', true );
 					$this->setContent( '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>', 'slide'); 			
 					$this->setContent( ' swiper-lazy', 'slideClass'); 
 				break;
 			case 'lazy':
 					$this->helper->setBoolValue( 'preloadImages', false );
 					$this->helper->setBoolValue( 'lazy', true );
 					$this->setContent( '<div class="swiper-lazy-preloader"></div>', 'slide'); 			
 					$this->setContent( ' swiper-lazy', 'slideClass'); 			
 				break;

 			case 'preload':
 			default:
 					$this->helper->setBoolValue( 'preloadImages', true );
 					$this->helper->setBoolValue( 'updateOnImagesReady', true );
 				break;
 		}
 	}


	private function getSlideDescription($slide){
		$desc = '';
		if( $slide['data']->post_title ) $desc = $slide['data']->post_title;
		//if( $slide['data']->post_excerpt ) $desc .= $slide['data']->post_excerpt;
		//if( $slide['data']->post_content ) $desc .= $slide['data']->post_content;
		if( !$desc ) return '';
		return '<div class="swiper-slide-desc">'.$desc.'</div>';
	}


 	private function makeSlide($slide){

		if( is_array($slide) == false ) return ;

		$imgUrl = $slide['thumb'];
		$item = '';
	    $item .= '<div class="swiper-slide '.$this->getContent('slideClass').'" style="background-image:url('.$imgUrl.')">';
	   	$item .= $this->getSlideDescription($slide);			   	
	   	$item .= $this->getContent('slide');
	    $item .= '</div>';

	    $this->setContent( $item, 'items' );
 	}


 	private function makeSlides(){
 		foreach ( $this->imagesSource->imagesList as $slide){
 			$this->makeSlide( $slide );
 		}
 	}


 	private function initBaseOptions(){
 		$this->helper->setBoolValue( 'loop', true );
 		$this->helper->setBoolValue( 'centeredSlides', true );
 		//$this->helper->setStrValue( 'slidesPerView', 'auto' );
 		//$this->helper->setIntValue( 'slidesPerView', 2 );
 	}


  	private function initSliderView(){
  		$sliderView = $this->getMeta( 'sliderView');
  		if( is_array($sliderView) ){
  			if( isset($sliderView['slidesPerView']) && $sliderView['slidesPerView'] > 0 ) $this->helper->setIntValue('slidesPerView', $sliderView['slidesPerView']);
  			if( isset($sliderView['spaceBetween']) && $sliderView['spaceBetween'] > 0 ) $this->helper->setIntValue('spaceBetween', $sliderView['spaceBetween']);
  		}
 	}

 	


 	private function initEffect(){
 		$effect = $this->getMeta( 'effect');
 		if( in_array( $effect, array('slide', 'fade', 'cube', 'coverflow', 'flip') ) ){
 			$this->helper->setStrValue( 'effect', $effect );
 		}
 	}


 	private function initDirection(){
 		$direction = $this->getMeta( 'direction');
 		if( $direction == 'vertical' || $direction == 'horizontal' ){
 			$this->helper->setStrValue( 'direction', $direction );
 		}
 	}


 	private function initNavigation(){
 		if( $this->getMeta('nav_buttons')=='show' ){
 			$this->setContent( '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>', 'endMainDiv');
 			$this->helper->setArrayValue( 'navigation', array( 'nextEl'=> '.swiper-button-next', 'prevEl'=> '.swiper-button-prev' ) );
 		}
 	}


 	private function initScrollbar(){
 		if( $this->getMeta('nav_scrollbar')=='show' ){
 			$this->setContent( '<div class="swiper-scrollbar"></div>', 'endMainDiv');
 			$this->helper->setArrayValue( 'scrollbar', array( 'el'=> '.swiper-scrollbar', 'draggable'=> true, ) );
 		}
 	}


 	private function initPagination(){
 		$pagination =  $this->getMeta('nav_pagination');
 		
 		if( !$pagination ) return ;

		$this->setContent( '<div class="swiper-pagination"></div>', 'endMainDiv');
		
		$paginationOptions = array( 
			'el'			=> '.swiper-pagination', 
			'type' 			=> $pagination,
			'clickable' 	=> true,
			'dynamicBullets'=>  count($this->imagesSource->imagesList) > 6 
		);
		
		$this->helper->setArrayValue( 'pagination', $paginationOptions );
 	}


 } 