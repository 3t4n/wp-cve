<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

if( WPAPE_GALLERY_PREMIUM ){
	include_once( WPAPE_GALLERY_LICENCE_PATH );
} elseif(!class_exists('apeGalleryParent')){
	class apeGalleryParent{ public $premium = 0; }
}

class apeGalleryBuild extends apeGalleryParent{

	public function runPremiumFunction( $functionCallName ){

		if( $this->premium && method_exists( $this, $functionCallName ) ){
			$this->{$functionCallName}();
		}

	}


	function getTemplateItem( $item, $class = '', $template = '', $addClass = '' ){
		$retHtml = ''; 
		if( is_array($item) && count($item) ){

			if( isset($item['enabled']) && $item['enabled'] ){
				if(isset($item['fontSize'])) 		$this->{$class.'Style'} .= ' font-size:'.       (int)$item['fontSize'].'px;'; 

				if(isset($item['fontLineHeight'])) 	$this->{$class.'Style'} .= ' line-height:'.     (int)$item['fontLineHeight'].'%;'; 
				
				if(isset($item['color'])) 			$this->{$class.'Style'} .= ' color:'.			$item['color'].';';
				
				/* check new version */
				if( isset($item['fontStyle']) ){
					$item = $item + $item['fontStyle'];
				}
				if(isset($item['fontBold'])) 		$this->{$class.'Style'} .= ' font-weight:'.		($item['fontBold']		?'bold'		:'normal').';';
				if(isset($item['fontItalic'])) 	 	$this->{$class.'Style'} .= ' font-style:'.		($item['fontItalic']	?'italic'	:'normal').';';
				if(isset($item['fontUnderline'])) 	$this->{$class.'Style'} .= ' text-decoration:'.	($item['fontUnderline'] ?'underline':'none').';';
				
				if(isset($item['colorHover'])) 		$this->{$class.'HoverStyle'} .= 'color:'.$item['colorHover'].';';

				if( $template==1 ){
					if(isset($item['colorBg'])) 	$this->{$class.'Style'} .= 'background:'.$item['colorBg'].';';

					if(isset($item['color']) && isset($item['borderSize']) && $item['borderSize'])
													$this->{$class.'Style'} .= 'border:'.(int)$item['borderSize'].'px solid '.$item['color'].';';
					
					if(isset($item['colorHover']) && isset($item['borderSize']) && $item['borderSize'])		
													$this->{$class.'HoverStyle'} .= 'border:'.(int)$item['borderSize'].'px solid '.$item['colorHover'].';';
					
					if(isset($item['colorBgHover']))$this->{$class.'HoverStyle'} .= 'background:'.$item['colorBgHover'].';';
				}
				if($template==1){
					$retHtml .= '<i class="fa '.$item['iconSelect'].' '.$class.' '.$addClass.'" ></i>';
				} else {
					$retHtml .= '<div class="'.$class.' '.$addClass.'">'.$template.'</div>';
				}
			}
		}
		return $retHtml;
	}

	public function initJSCode(){
 		return
 		'var '.$this->galleryId.' = {'.$this->helper->getOptionList().'}, '.$this->galleryId.'_css = "'.$this->runtimeStyle.'",'
		.'apeGalleryDelay = '.(int)get_option( WPAPE_GALLERY_NAMESPACE.'delay',0).'; '
		.'head = document.head || document.getElementsByTagName("head")[0], '
		.'style = document.createElement("style"); '
		.'style.type = "text/css"; '
		.'if (style.styleSheet) style.styleSheet.cssText = '.$this->galleryId.'_css; '
		.'	else  style.appendChild(document.createTextNode('.$this->galleryId.'_css)); '
		.'head.appendChild(style);';
 	}

 	public function addShadow($nameOptions = ''){
 		$shadowStyle = '';
 		$shadow = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.$nameOptions, true );
		if( isset($shadow['hshadow']) ) 	$shadowStyle.= (int) $shadow['hshadow'].'px ';
		if( isset($shadow['vshadow']) ) 	$shadowStyle.= (int) $shadow['vshadow'].'px ';
		if( isset($shadow['bshadow']) ) 	$shadowStyle.= (int) $shadow['bshadow'].'px ';
		if( isset($shadow['color'])   ) 	$shadowStyle.= $shadow['color'].' ';
		if( $shadowStyle ){
			return 	'-webkit-box-shadow:'.$shadowStyle.';'.
					'-moz-box-shadow: 	'.$shadowStyle.';'.
					'-o-box-shadow: 	'.$shadowStyle.';'.
					'-ms-box-shadow: 	'.$shadowStyle.';'.
					'box-shadow: 		'.$shadowStyle.';';
		} else return '';
 	}

 	public function addWidth( $colums, $index ){
 		$ret = array();
		if( isset($colums['autowidth'.$index]) ){
			$ret[] = '"columnWidth": "auto"';
			if( isset($colums['colums'.$index]) && $colums['colums'.$index] )  $ret[] =  '"columns":'.$colums['colums'.$index];
		} elseif( isset($colums['width'.$index]) && $colums['width'.$index] ){
			$ret[] = '"columnWidth": '.$colums['width'.$index];
		}
		if( count($ret) ){
			switch ($index) {
				case '1': $r = '960'; break;
				case '2': $r = '650'; break;
				case '3': $r = '450'; break;
			}
			$ret[] = '"maxWidth": '.$r;
			return '{'.implode( ' , ', $ret ).'}';
		} else return '';
 	}

 	function getSize( $val = ''){
		$corVal = $val;
		if( strpos( $val, '%')===false && strpos( $val, 'px')===false ) $corVal = $val.'px';
		return $corVal;
	}
}