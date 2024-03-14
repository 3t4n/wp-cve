<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) ||  ! defined( 'ABSPATH' ) ) die;

class apeGalleryBuildV2{

 	private $styleFiles 	= array();
 	private $scriptFiles 	= array();

 	private $contentHTML 	= array();

 	/* need check */
 	public $runtimeStyle = '';


 	

 	function getCodeAssetsFiles(){
 		$returnCode = ''; 		
 		if( count($this->scriptFiles) ){
 			foreach ($this->scriptFiles as $fileName => $fileData ) {	
				$returnCode .= '<script type="text/javascript" src="'.WPAPE_GALLERY_URL.$fileData['file'].'"></script>';
 			}
 		}

 		if( count($this->styleFiles) ){
 			foreach ($this->styleFiles as $fileName => $fileData ) {
				$returnCode .= '<link rel="stylesheet" type="text/css" href="'.WPAPE_GALLERY_URL.$fileData['file'].'">';
 			}
 		}
 		return $returnCode;
 	}

 	function includeAssetsFiles( ){

 		if( count($this->scriptFiles) ){
 			foreach ($this->scriptFiles as $fileName => $fileData ) {
 				wp_enqueue_script( $fileName, WPAPE_GALLERY_URL.$fileData['file'], 	$fileData['depend'], WPAPE_GALLERY_VERSION ); 			
 			}
 		}

 		if( count($this->styleFiles) ){
 			foreach ($this->styleFiles as $fileName => $fileData ) {
				wp_enqueue_style( $fileName, WPAPE_GALLERY_URL.$fileData['file'], 	$fileData['depend'], WPAPE_GALLERY_VERSION, 'all' ); 			
 			}
 		}
	}

	function addJsFile( $fileData ){ 	
		$this->addAssetFile( $fileData, 'script'); 
	}

	function addCssFile( $fileData ){ 	
		$this->addAssetFile( $fileData, 'style'); 
	}

 	function addAssetFile( $fileData, $type = '' ){

 		if( !is_array($fileData) ) return ;

 		$defaultValue = array(
 			'fileName' 	=> '',
 			'fileUrl' 	=> '',
 			'fileDepend'=> array(),
 		);

 		$fileData = array_merge( $defaultValue, $fileData );
 		
 		$newEl = array(
 			$fileData['fileName'] => array(
 				'file' 	=> $fileData['fileUrl'],
	 			'depend'=> $fileData['fileDepend'],	
 			)
 		);
	 	
	 	if($type=='script'){
	 		$this->scriptFiles = array_merge( $this->scriptFiles, $newEl );
	 	} else {
	 		$this->styleFiles = array_merge( $this->styleFiles, $newEl );
	 	}
 	}


 	/*  */

 	public function getContent( $point = '' ){
 		if( !$point ) return implode($this->contentHTML);
 		if( isset($this->contentHTML[$point]) && $this->contentHTML[$point] ) return $this->contentHTML[$point];
 		return '';
 	}

 	public function setContent( $content, $point = '', $position = 'after' ){
 		if( !$content || !$point ) return ;
 		
 		if( !isset($this->contentHTML[$point]) ) $this->contentHTML[$point] = '';

 		if( $position=='after' ) $this->contentHTML[$point] .= $content;
 		if( $position=='before' ) $this->contentHTML[$point] = $content . $this->contentHTML[$point];
 	}

 	/*    */


 	public function getIntMeta( $name ){
 		return (int) $this->getMeta( $name );
 	}

 	public function getBoolMeta( $name ){
 		return (bool) $this->getMeta( $name );
 	}

 	public function getMeta( $name ){
 		return get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$name, true );
 	}

	public function initJSCode(){
 		return 'var '.$this->uniqid.' = {'.$this->helper->getOptionList().'}'
 				.$this->initCssCode()
 				.';';
 	}

 	private function initCssCode(){
 		if(!$this->runtimeStyle) return '';
 		return ', '.$this->galleryId.'_css = "'.$this->runtimeStyle.'",'
		.'head = document.head || document.getElementsByTagName("head")[0], '
		.'style = document.createElement("style"); '
		.'style.type = "text/css"; '
		.'if (style.styleSheet) style.styleSheet.cssText = '.$this->galleryId.'_css; '
		.'	else  style.appendChild(document.createTextNode('.$this->galleryId.'_css)); '
		.'head.appendChild(style);';
 	}
}