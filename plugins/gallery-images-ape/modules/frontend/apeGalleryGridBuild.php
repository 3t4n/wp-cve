<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;


class apeGalleryGridBuild extends apeGalleryBuild{

 	public $id = 0;
 	public $originalId = 0;

 	public $themeId = 0;

 	public $fromIds = 0;

 	public $returnHtml = '';
 	public $options = array();
 	
 	public $wpapeBoxStyle = '';
	public $wpapeBoxHoverStyle = '';

	public $wpapeOverlayStyle = '';

	public $wpapeImageLoadingStyle = '';

	public $wpapeLinkIconStyle = '';
	public $wpapeLinkIconHoverStyle = '';

	public $wpapeZoomIconStyle = '';
	public $wpapeZoomIconHoverStyle = '';


	public $wpapeTitleStyle = '';
	public $wpapeTitleHoverStyle = '';

	public $wpapeDescStyle = '';
	public $wpapeDescHoverStyle = '';

	public $wpapeLightboxStyle = '';
	public $wpapeTextLightboxStyle = '';
	public $wpapeArrowsLightboxStyle = '';

	public $wpapeTitleLightboxStyle = '';

	public $wpapeMainDivStyle = '';


 	public $javaScript = '';
 	public $runtimeStyle = '';

 	public $galleryId = '';
 	public $helper = '';

 	public $hover 		= 0;
 	public $linkIcon 	= '';
	public $zoomIcon 	= '';
	public $titleHover 	= '';
	public $descHover 	= '';
	public $templateHover = '';

 	public $selectImages = null;
 	
 	public $orderby = 'categoryD';
 	public $thumbsource = 'medium';

 	public $styleList = array();
 	public $scriptList = array();

 	public $thumbClick = 0;

 	public $touch = 0;

 	public $menu = 1;


 	function __construct($attr, $fromIds = 0){

 		$this->helper 		= new apeGalleryRenderHelper();
 		$this->galleryId 	= 'wpape_gallery_'.uniqid();
 		

 		$id = 0;
 		if( isset($attr) ){
 			if( 		isset($attr['id'])  && ( (int) $attr['id'] )   ) 	$id = (int) $attr['id'];
 			else if( 	isset($attr[0]) 	&& ( (int) $attr[0] )  ) 		$id = (int) $attr[0];
 		} 

 		if($fromIds){
			$fromIds = explode(',', $fromIds);
			if( is_array($fromIds) && count($fromIds) ){
				$this->fromIds = $fromIds;
				$id =  get_option( WPAPE_GALLERY_NAMESPACE.'sourceGallery', '0' );
			}
 		}

 		if( $id ){

			$this->id = $id;
			
			$this->themeId = (int) get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'themeId', true );

			if( $this->themeId == 0 ) $this->themeId = -1;

			if( $this->themeId == -1 ){
				$this->themeId = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 );
			}

			$this->originalId = $this->id;
			$this->id = $this->themeId;

			$this->helper->setId( $this->id );
 		}

 	}
 	
 	function loadCSSFiles(){
 		if( get_option( WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest' )=='include' ){
 			$this->styleList[] = WPAPE_GALLERY_URL.'assets/css/gallery-lightbox.css';
 			$this->styleList[] = WPAPE_GALLERY_URL.'assets/css/gallery.css';
 			$this->styleList[] = WPAPE_GALLERY_URL.'assets/css/gallery-style.css';
 			$this->styleList[] = WPAPE_GALLERY_URL.'assets/button/buttons.css';

 			$this->styleList[] = WPAPE_GALLERY_URL.'assets/fontawesome/css/fontawesome.min.css';
 		} else {
			wp_enqueue_style( 'wpApeGalleryLightboxCss', 	WPAPE_GALLERY_URL.'assets/css/gallery-lightbox.css', 	array(), WPAPE_GALLERY_VERSION, 'all' );
			wp_enqueue_style( 'wpApeGalleryGalleryCss',  	WPAPE_GALLERY_URL.'assets/css/gallery.css', 			array(), WPAPE_GALLERY_VERSION, 'all' );
			wp_enqueue_style( 'wpApeGalleryStyleCss', 		WPAPE_GALLERY_URL.'assets/css/gallery-style.css',		array(), WPAPE_GALLERY_VERSION, 'all' );
			wp_enqueue_style( 'wpApeGalleryButtonCss',		WPAPE_GALLERY_URL.'assets/button/buttons.css', 	array(), WPAPE_GALLERY_VERSION, 'all' );
			
			wp_enqueue_style( 'wpApeGalleryFontCss',		WPAPE_GALLERY_URL.'assets/fontawesome/css/fontawesome.min.css', 	array(), WPAPE_GALLERY_VERSION, 'all' );
			
		}
	}

	function loadJSFiles(){ 
		if(  get_option( WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest' )=='latest'  ){
			wp_enqueue_script( 'jquery', false, array(), false, true);
			wp_enqueue_script( 'wpApeGalleryLightbox', 	WPAPE_GALLERY_URL.'assets/js/lightbox.min.js', 	array( 'jquery' ), WPAPE_GALLERY_VERSION );

			if($this->touch){
				wp_enqueue_script( 'wpApeGallerySwipe', 	WPAPE_GALLERY_URL.'assets/js/swipe.min.js', 		array(), WPAPE_GALLERY_VERSION );	
				wp_enqueue_script( 'wpApeGalleryGridMain', 	WPAPE_GALLERY_URL.'assets/js/ape_gallery_grid.js', 	array( 'jquery', 'wpApeGalleryLightbox', 'wpApeGallerySwipe' ), WPAPE_GALLERY_VERSION );
			} else {
				wp_enqueue_script( 'wpApeGalleryGridMain', 	WPAPE_GALLERY_URL.'assets/js/ape_gallery_grid.js', 	array( 'jquery', 'wpApeGalleryLightbox' ), WPAPE_GALLERY_VERSION );	
			}			

		}else if(get_option( WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest' )=='include') {
			global $apeGalleryLoadJS;
			if(!isset($apeGalleryLoadJS)){
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/noconflict/altjquery.min.js';
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/noconflict/libs.min.js';
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/noconflict/eventie.min.js';
				
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/lightbox.min.js';
				if($this->touch){
					$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/swipe.min.js';
				}
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/grid.min.js';
				$this->scriptList[] = WPAPE_GALLERY_URL.'assets/js/script.js';
			}
			$apeGalleryLoadJS = 1;
		} else {
			wp_enqueue_script( 'wpApeGalleryJquery', 	WPAPE_GALLERY_URL.'assets/js/noconflict/altjquery.min.js', 	array( ), 							WPAPE_GALLERY_VERSION );
			wp_enqueue_script( 'wpApeGalleryEventie', 	WPAPE_GALLERY_URL.'assets/js/noconflict/eventie.min.js', 	array( 'wpApeGalleryJquery' ), 	WPAPE_GALLERY_VERSION );
			wp_enqueue_script( 'wpApeGalleryLibs',  	WPAPE_GALLERY_URL.'assets/js/noconflict/libs.min.js', 		array( 'wpApeGalleryJquery' ), 	WPAPE_GALLERY_VERSION );
			wp_enqueue_script( 'wpApeGalleryLightbox', 	WPAPE_GALLERY_URL.'assets/js/lightbox.min.js', 				array( 'wpApeGalleryJquery' ), 	WPAPE_GALLERY_VERSION );
			if($this->touch){
				wp_enqueue_script( 'wpApeGallerySwipe', 	WPAPE_GALLERY_URL.'assets/js/swipe.min.js', 				array( 'wpApeGalleryLightbox' ), WPAPE_GALLERY_VERSION );
			}
			wp_enqueue_script( 'wpApeGalleryGrid',  		WPAPE_GALLERY_URL.'assets/js/grid.min.js',					array( 'wpApeGalleryJquery' ), 	WPAPE_GALLERY_VERSION );   
			wp_enqueue_script( 'wpApeGalleryScript',		WPAPE_GALLERY_URL.'assets/js/script.js',					array( 'wpApeGalleryJquery' ), 	WPAPE_GALLERY_VERSION );
		}
		
	
	}	

	public function loadRuntimeStyle($styleValue, $classTitle = '', $onHover='1'){
		if(isset($this->{$styleValue.'Style'}) && $this->{$styleValue.'Style'} ){
			$this->runtimeStyle.= ($onHover!=2?'#'.$this->galleryId.' ':'').$classTitle.'{'.$this->{$styleValue.'Style'}.'}';
		}
		if( $onHover==1 && isset($this->{$styleValue.'HoverStyle'}) && $this->{$styleValue.'HoverStyle'} ){
			$this->runtimeStyle.= '#'.$this->galleryId.' '.$classTitle.':hover{'.$this->{$styleValue.'HoverStyle'}.'}';
		}
	}

	

 	public function setBorder($nameOptions = '', $forHover = false){
 		$borderStyle = '';
 		$border = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$nameOptions, true );
		if( isset($border['width'])){
			$borderStyle.= (int) $border['width'].'px ';
			if($nameOptions=='border-options'){
				$this->helper->setValue( 'borderSize',  (int) $border['width'] );
			}
		}
		if( isset($border['style'])) $borderStyle.=  $border['style'].' ';
		
		if($forHover){
			if( isset($border['hover-color'])) $borderStyle.=  $border['hover-color'].' ';
		} else {
			if( isset($border['color'])) $borderStyle.=  $border['color'].' ';	
		}
		
		if( $borderStyle ) return 'border: '.$borderStyle.';';
			else return '';
 	}

 	public function getGallery( ){
 		if( !$this->id ) return ''; 

 		$this->touch 	= get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'lightboxSwipe', true );

 		$this->helper->setValue( 'filterContainer',  '#'.$this->galleryId.'filter', 'string' );

 		if( get_option( WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest' )=='include' ){
			$this->loadCSSFiles();
			$this->loadJSFiles();
		} else {
			add_action( 'get_footer', array($this, 'loadCSSFiles') );
			add_action( 'get_footer', array($this, 'loadJSFiles') );
		}

		$static = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'static', true );
		$static = false;

		if( !$this->fromIds && $static ){
			$gallery_static =  get_transient( 'ape_gallery_static'. $this->originalId );
			if($gallery_static){
				$gallery_static .= '<!-- static ape gallery -->';
				return $gallery_static;
			}
 		}

		$sizeType 	= get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'sizeType', true );

		if($this->touch){
			$this->helper->setValue( 'touch',  1, 'raw' );
		}
		
		$width = 240;  $height = 141;

		$size 		= get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'thumb-size-options', true );

		if( is_array($size) && count($size) ){
			if( isset($size['width'])  ) 	$width = (int) 	$size['width']; else $width = 240;
			//hight => height but need convert
			if( isset($size['hight']) ) 	$height = (int) $size['hight']; 	else $height = 142;
		}
		
		$this->setColumns();

		/*if($this->premium){
			$this->getOrderBy();
			$this->getSource();
		}*/
		$this->orderby = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'orderby', true );
		$this->thumbsource = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'source', true );

		$radius = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'thumb-options', true );
		if( isset($radius['radius']) ){
			$radius =  (int) $radius['radius'];
			$this->wpapeBoxStyle .= ' -webkit-border-radius: '.$radius.'px;'
									.'-moz-border-radius: '.$radius.'px;'
									.'border-radius: '.$radius.'px;';	
		}
		

		if( $thumbBorder =  get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'thumbBorder', true ) ){
			$this->wpapeBoxStyle .= $this->setBorder('border-options');
			if( $thumbBorder==2 ){
				$this->wpapeBoxHoverStyle .= $this->setBorder('border-options', true );
				//$this->wpapeBoxHoverStyle .= $this->setBorder('hover-border-options', true );
			}
		}

		if( $thumbShadow = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'shadow', true ) ){
			$this->wpapeBoxStyle .=$this->addShadow('shadow-options');

			if ( $thumbShadow==2 ){
				$this->wpapeBoxHoverStyle .= $this->addShadow('hover-shadow-options');
			}
		}

		$this->thumbClick = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'thumbClick', true );
		$this->thumbClick = !$this->thumbClick;
		
		$this->selectImages = new apeGallerySource( $this->originalId, $this->themeId, $this->fromIds );

		$this->selectImages->setSize( $width , $height, $this->thumbsource, $this->orderby );

		//if ( $this->premium ) $this->setCCL();

		$this->selectImages->getImages();

		$this->helper->addParam( 'overlayEffect', 'string');

		$boxesToLoadStart = (int) get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'boxesToLoadStart', true );
		if(!$boxesToLoadStart) $boxesToLoadStart = 8;
		$this->helper->setValue( 'boxesToLoadStart',  $boxesToLoadStart );

		$boxesToLoad = (int) get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'boxesToLoad', true );
		if(!$boxesToLoad) $boxesToLoad = 8;
		$this->helper->setValue( 'boxesToLoad',  $boxesToLoad );

		$this->helper->addParam( 'lazyLoad', 'bool');

		$this->helper->addParam( 'LoadingWord', 'string');
		$this->helper->addParam( 'loadMoreWord', 'string');
		$this->helper->addParam( 'noMoreEntriesWord', 'string');

		$loadingBgColor = get_post_meta( $this->id, WPAPE_GALLERY_PREFIX.'loadingBgColor', true );
		if($loadingBgColor) $this->wpapeImageLoadingStyle .=  'background-color: '.$loadingBgColor.';';
			else $this->wpapeImageLoadingStyle .=  'background-color: rgb(255,255,255);';

		$this->helper->setValue( 'loadMoreClass',  $this->getMenuStyle('button') );

		$this->runPremiumFunction('lightboxPremiumSetup');
		
		$thumboptions = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'thumb-options', true );
		if( isset($thumboptions['xspace']) ){
			$this->helper->setValue( 'horizontalSpaceBetweenBoxes', (int) $thumboptions['xspace'], 'int' );
		} else $this->helper->setValue( 'horizontalSpaceBetweenBoxes', 0);
		if( isset($thumboptions['yspace']) ){
			$this->helper->setValue( 'verticalSpaceBetweenBoxes', (int) $thumboptions['yspace'], 'int' );
		} else $this->helper->setValue( 'verticalSpaceBetweenBoxes', 0);

		
		/*if ( $this->premium ) $this->wpapeOverlayStyle .= $this->getOverlayBg();
			else $this->wpapeOverlayStyle .= 'background: rgba(7, 7, 7, 0.5);';*/

		$this->wpapeOverlayStyle .= 'background:'.get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'background', true ).';';

		$polaroidOn = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'polaroidOn', true );
		if($polaroidOn){
			$polaroidBackground = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'polaroidBackground', true );
			$polaroidAlign = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'polaroidAlign', true );
			$polaroidStyle = 'text-align:'.$polaroidAlign.'; background: '.$polaroidBackground.';';
		}

		/*if ( $this->premium ) $this->setupMenu();*/
		$this->menu = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menu', true );

		$polaroid_template = '';
		$polaroidSource = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'polaroidSource', true );
		switch ($polaroidSource) {
			case 'desc':
					$polaroid_template = '@DESC@';
				break;
			case 'caption':
					$polaroid_template = '@CAPTION@';
				break;
			case 'title':
			default:
					$polaroid_template = '@TITLE@';
				break;
		}

		$hover_template = '';
		$desc_template = '';

		if( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'hover', true ) ) $this->hover = 1;
		
		$this->linkIcon 	= $this->getTemplateItem( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'linkIcon', true ), 'wpapeLinkIcon', 1 );
		$this->zoomIcon 	= $this->getTemplateItem( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'zoomIcon', true ), 'wpapeZoomIcon', 1 , ($this->thumbClick?' wpape-lightbox':'') ); 
		$this->titleHover 	= $this->getTemplateItem( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'showTitle', true ), 'wpapeTitle', '@TITLE@' );
		
		
		/*if ( $this->premium ) 	$this->setDescHover();*/
		$this->descHover = $this->getTemplateItem( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'showDesc', true ), 'wpapeDesc', '@DESC@' );

		$this->setLightboxOptions();
		
		
		$this->loadRuntimeStyle('wpapeBox', '.wpape-img-container');
		$this->loadRuntimeStyle('wpapeTitle','.wpapeTitle',1);
		$this->loadRuntimeStyle('wpapeDesc','.wpapeDesc',1);
		$this->loadRuntimeStyle('wpapeOverlay','.thumbnail-overlay',0);
		$this->loadRuntimeStyle('wpapeLinkIcon','.wpapeLinkIcon',1);
		$this->loadRuntimeStyle('wpapeZoomIcon','.wpapeZoomIcon',1);
		$this->loadRuntimeStyle('wpapeImageLoading','.image-with-dimensions',0);

		$this->loadRuntimeStyle('wpapeTextLightbox','body .mfp-title, body .mfp-counter',2);

		$widthSize 		= get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'width-size', true );
		$widthSizeValue = '';
		if( is_array($widthSize) && count($widthSize) ){
			if( isset($widthSize['width'])  ){
				$widthSizeValue = (int) $widthSize['width'];
				if($widthSizeValue){
					if( isset($widthSize['widthType']) && $widthSize['widthType'] ) $widthSizeValue .= 'px';
						else $widthSizeValue .= '%';
				}
			} 	 
		}
		if(!$widthSizeValue) $widthSizeValue = '100%;';

		$this->wpapeMainDivStyle = 'width:'.$widthSizeValue.';';

		switch( get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'align', true ) ){
			case 'left':  	$this->wpapeMainDivStyle .= 'float: left;'; 	break;
			case 'right':  	$this->wpapeMainDivStyle .= 'float: right;'; 	break;
			case 'center':  $this->wpapeMainDivStyle .= 'margin: 0 auto;'; break;
			case '': default: 
		}

		$paddingCustom = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'paddingCustom', true );
		if( isset($paddingCustom['left']) 	&& $paddingCustom['left'] ) 	$this->wpapeMainDivStyle .= 'padding-left:'.	$this->getSize($paddingCustom['left']).';';
		if( isset($paddingCustom['top']) 	&& $paddingCustom['top'] ) 		$this->wpapeMainDivStyle .= 'padding-top:'.		$this->getSize($paddingCustom['top']).';';
		if( isset($paddingCustom['right']) 	&& $paddingCustom['right'] ) 	$this->wpapeMainDivStyle .= 'padding-right:'.	$this->getSize($paddingCustom['right']).';';
		if( isset($paddingCustom['bottom']) && $paddingCustom['bottom'] ) 	$this->wpapeMainDivStyle .= 'padding-bottom:'.	$this->getSize($paddingCustom['bottom']).';';

		if(count($this->selectImages->imagesList)){

			

			for ($i=0; $i<count($this->selectImages->imagesList); $i++) {
				
				if(!isset($this->selectImages->imagesList[$i]) || !is_array($this->selectImages->imagesList[$i]) ) continue ;

				$img = $this->selectImages->imagesList[$i];
								
				$polaroidDesc =  str_replace( 
					array('@TITLE@','@CAPTION@','@DESC@', '@LINK@'), 
					array( 
						$img['data']->post_title,
						$img['data']->post_excerpt,
						$img['data']->post_content,
						$img['link']
					), 
					$polaroid_template
				);

				$link = $img['image'];

				if( $img['link'] && ( !$this->hover || ( $this->hover == 1 && !$this->linkIcon && !$this->zoomIcon  ) )  ){
					$link = $img['link'].'" data-type="'.($img['typelink']?'blank':'').'link';
				} elseif( $img['videolink'] ) {
					$link = $img['videolink'].'" data-type="iframe';
				}

				$lightboxText = $img['data']->post_title;

				$this->returnHtml .= 
					'<div class="wpape-img category'.$img['catid'].'" '.( isset($img['col']) && $img['col'] ?' data-columns="'.$img['col'].'" ' :'').'>'
			            .'<div class="wpape-img-image '.(!$this->thumbClick?' wpape-lightbox':'').'" '.( isset($img['effect']) && $img['effect'] ?' data-overlay-effect="'.$img['effect'].'" ' :'').' >'
			                .'<div data-thumbnail="'.$img['thumb'].'" title="'.$lightboxText.'" data-width="'.( $sizeType ? $width : $img['sizeW'] ).'" data-height="'.($sizeType?$height:$img['sizeH']).'" ></div>'
							.'<div data-popup="'.$link.'"  title="'.$lightboxText.'"></div>'
							.$this->getHover($img)
			            .'</div>'
						.($polaroidDesc && $polaroidOn?'<div class="wpape-img-content" '.($polaroidStyle?' style="'.$polaroidStyle.'" ':'').'>'.$polaroidDesc.'</div>':'')
			        .'</div>';
			}
		}

		if( $this->returnHtml ){
			$this->returnHtml = 
				'<div id="Block_'.$this->galleryId.'" style="'.$this->wpapeMainDivStyle.' display: none;">'
					.( $this->menu && !$this->fromIds ?$this->getCategories():'').
					'<div id="'.$this->galleryId.'" data-options="'.$this->galleryId.'" style="width:100%;" class="wpape_gallery">'
						. $this->returnHtml
					.'</div>'
				.'</div>'
				.'<script>'.$this->initJSCode().'</script>';

				if(count($this->scriptList)){
					for($i=0;$i<count($this->scriptList);$i++){
						$this->returnHtml .= ' <script type="text/javascript" src="'.$this->scriptList[$i].'"></script>';
					}
				}
				if(count($this->styleList)){
					for($i=0;$i<count($this->styleList);$i++){
						$this->returnHtml .= '<link rel="stylesheet" type="text/css" href="'.$this->styleList[$i].'">';
					}
				}
		} 

		if( !$this->fromIds && $static ){
			set_transient( 'ape_gallery_static'.$this->id , $this->returnHtml, 7 * 24 * HOUR_IN_SECONDS );
		}

		return $this->returnHtml;
 	}


 	function getHover( $img ){
			$hoverHTML = '';
			if(!$this->hover) return $hoverHTML;
			if($this->hover == 1){
				$hoverHTML .= $this->titleHover;
				if( $this->linkIcon || $this->zoomIcon ){
					$hoverHTML .= '<div class="wpapeIcons">';
					if($this->linkIcon && $img['link']) $hoverHTML .= '<a href="@LINK@" '.($img['typelink']?'target="_blank"':'').' title="@TITLE@">'.$this->linkIcon.'</a>';
					if($this->zoomIcon) $hoverHTML .= $this->zoomIcon;
					$hoverHTML .= '</div>';
				}
				$hoverHTML .= $this->descHover;
			}

			if($this->templateHover) $hoverHTML = $this->templateHover; 

			if($hoverHTML){				
				$hoverHTML =  str_replace( 
					array('@TITLE@','@CAPTION@','@DESC@', '@LINK@', '@VIDEOLINK@'), 
					array( 
						$img['data']->post_title,
						$img['data']->post_excerpt,
						$img['data']->post_content,
						$img['link'],
						$img['videolink'],
					), 
					$hoverHTML
				);
			}
			$hoverHTML = '<div class="thumbnail-overlay">'.$hoverHTML.'</div>';
			return $hoverHTML;
		}

 	function getCategories(){
 		$retHtml = '';
 		$align = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'buttonAlign', true );
 		if($align) $align = ' wpape_gallery_align_'.$align;
 		$retHtml .= '<div class="wpape_gallery_button'.$align.'"  id="'.$this->galleryId.'filter">';

 		if( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menuGroup', true ) ){
 			$retHtml .= '<div class="apebtn-group">';
 		}

 		$style = '';
 		$paddingMenu = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'paddingMenu', true );
 		if(isset($paddingMenu['left'])){
 			$style .= 'margin-right:'.(int)$paddingMenu['left'].'px;';
 		}
 		if(isset($paddingMenu['bottom'])){
 			$style .= 'margin-bottom:'.(int)$paddingMenu['bottom'].'px;';
 		}
 		
 		$class = $this->getMenuStyle('button');

 		$typeHomeButton = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menuHome', true );
		if(  $typeHomeButton && $typeHomeButton != 'hide' ){
			$retHtml .= '<a class="'.$class.' active" '.($style?'style="'.$style.'"':'').' href="#" data-filter="*">'
				.($typeHomeButton=='icon' || $typeHomeButton=='iconlabel'?'<i class="fa '.get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menuRootIcon', true ).'"></i>': '')
				.($typeHomeButton=='iconlabel'?' ':'')
				.($typeHomeButton=='label' || $typeHomeButton=='iconlabel'?get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menuRootLabel', true ):'')
			.'</a>';
		}
 		
 		for ($i=0; $i < count($this->selectImages->categoriesList); $i++) { 
 			$category = $this->selectImages->categoriesList[$i];

 			$catTitle = esc_attr($category['title']);
 			if(!$catTitle) $catTitle = 'Untitled';
 			$altCatTitle = '';
 			if( isset($category['icon']) && $category['icon'] ){
 				$altCatTitle .='<i class="fa '.esc_attr($category['icon']).'"></i>';
 			}
 			if( isset($category['alter']) && $category['alter'] ){
 				if($altCatTitle) $altCatTitle.=' ';
 				$altCatTitle .= $category['alter'];
 			}
 			if($altCatTitle) $catTitle = $altCatTitle;


 			$retHtml .= 
 			'<a href="#" data-filter=".category'.$category['id'].'" class="'.$class.'" '.($style?'style="'.$style.'"':'').'>'
 				.$catTitle
 			.'</a>';

 		}
 		$retHtml .= '</div>';
 		if( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.'menuGroup', true ) ){
 			$retHtml .= '</div>';
 		}
 		return $retHtml;
 	}

 	public function setLightboxOptions(){
 			/* background */
			$lightboxBackground = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'lightboxBackground', true );
			if($lightboxBackground) $this->wpapeLightboxStyle .=  'background-color: '.$lightboxBackground.';';
			$this->addJavaScriptStyle('wpapeLightbox','body .mfp-ready.mfp-bg',2);
			/* Color */
			$lightboxColor = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'lightboxColor', true );
			if($lightboxColor) $this->wpapeTextLightboxStyle .=  'color: '.$lightboxColor.';';
			/* arrow */ 
			if( !get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'arrows', true )){
				$this->wpapeArrowsLightboxStyle = 'display:none;';
				$this->loadRuntimeStyle('wpapeArrowsLightbox','.mfp-container .mfp-arrow',2);
			}
		}


 	public function	setColumns(){
		$colums = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'colums', true );
		if( is_array($colums) && count($colums)){
			if( isset($colums['autowidth']) ){
				if($colums['colums']) $this->helper->setValue( 'columns',  $colums['colums'], 'int' );
				$this->helper->setValue( 'columnWidth', 'auto' );
			} elseif( isset($colums['width']) ) { 
				$this->helper->setValue( 'columnWidth',  $colums['width'], 'int' );
			}
			$resolutions=array( $this->addWidth($colums, 1), $this->addWidth($colums, 2), $this->addWidth($colums, 3) );
			if( count($resolutions) ){
				$this->helper->setValue( 'resolutions',  '['.implode( ' , ', $resolutions ).']', 'raw' );
			}
		}
	}


 	function getMenuStyle($optionName){
 		$style = ' apebtn ';

 		/*if ( $this->premium ) $style .= $this->getMenuButtonV2($optionName);
 				else $style .= 'apebtn-flat apebtn-primary ';*/

 		$style .= $this->getMenuButtonV2($optionName);
		
		if( get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$optionName.'Effect', true ) ){
			$style .= 'apebtn-'.get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$optionName.'Effect', true ).' ';
 		}

 		if( get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$optionName.'Shadow', true ) ){
			$style .= 'apebtn-'.get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$optionName.'Shadow', true ).' ';
 		}

 		switch ( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.$optionName.'Type', true ) ) {
 			case 'rounded': $style .= 'apebtn-rounded ';break;
 			case 'pill': 	$style .= 'apebtn-pill '; 	break;
 			case 'circle': 	$style .= 'apebtn-circle '; break;
 			case 'square': 	$style .= 'apebtn-square '; break;
 			case 'box': 	$style .= 'apebtn-box '; 	break;
 			case 'normal': default: 					break;
 		}

 		switch ( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.$optionName.'Size', true ) ) {
 			case 'giant': $style .= 'apebtn-giant '; 	break;
 			case 'jumbo': $style .= 'apebtn-jumbo '; 	break;
 			case 'large': $style .= 'apebtn-large '; 	break;
 			case 'small': $style .= 'apebtn-small '; 	break;
 			case 'tiny':  $style .= 'apebtn-tiny '; 		break;
 			case 'normal': default: 					break;
 		}

 		return $style;
 	}

 	public function	getMenuButtonV2($optionName){
			$class = '';
			switch ( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.$optionName.'Fill', true ) ) {
	 			case 'flat': 		$class .= 'apebtn-flat ';		break;
	 			case '3d': 			$class .= 'apebtn-3d '; 		break;
	 			case 'border': 		$class .= 'apebtn-border '; 	break;
	 			case 'borderless': 	$class .= 'apebtn-borderless '; break;
	 			case 'plain': 		$class .= 'apebtn-plain '; 		break;
	 			
	 			case 'normal': 
	 			default: 			$class .= ' '; 					break;
	 		}

	 		
			switch ( get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.$optionName.'Color', true ) ) {
	 			case 'blue': 	$class .= 'apebtn-primary '; 	break;
	 			case 'green': 	$class .= 'apebtn-action '; 	break;
	 			case 'orange': 	$class .= 'apebtn-highlight '; break;
	 			case 'red': 	$class .= 'apebtn-caution '; 	break;
	 			case 'purple': 	$class .= 'apebtn-royal '; 	break;
	 			case 'black': 	$class .= 'apebtn-black '; 	break;
	 			case 'dark': 	$class .= 'apebtn-dark '; 	break;
	 			case 'gray': default: $class .= ' '; 	break;	
	 		}

	 		return $class;
		}

 	public function addJavaScriptStyle( $styleValue, $styleName = '', $hover='1'){
 		$this->loadRuntimeStyle($styleValue,$styleName,$hover);
 	}
 } 