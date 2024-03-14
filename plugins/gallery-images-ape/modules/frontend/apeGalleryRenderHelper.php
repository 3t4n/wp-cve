<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class apeGalleryRenderHelper {
	
	private $optionsArray = array();
	
	private $id = 0;
	private $themeId = 0;


	public function setId( $id){
		$this->id = $id;
	}

	public function setValue( $valName, $value, $type='string'){
		switch ($type) {
			case 'raw':      break;
			case 'string':
				$value = '"'.$value.'"'; break;
			case 'int':
				$value = (int)$value; 	break;
			case 'bool':
				if( $value =='true' || $value == true ) $value = "true";
				else $value = "false";
				break;

			case 'array':
				if(is_array($value)) $value = json_encode($value);
					else $value = '{}';
				break;
		}
		$this->optionsArray[] = '"'.$valName . '": '.$value;
	}

	public function setIntValue($valName, $value){
		$this->setValue($valName, $value, 'int');
	}
	public function setBoolValue($valName, $value){
		$this->setValue($valName, $value, 'bool');
	}
	public function setArrayValue($valName, $value){
		$this->setValue($valName, $value, 'array');
	}
	public function setStrValue($valName, $value){
		$this->setValue($valName, $value, 'string');
	}


	public function addParam( $valName, $type = 'string' , $default = '' ){
		$value = get_post_meta( $this->id,  WPAPE_GALLERY_NAMESPACE.$valName, true );
		if($type=='bool'){
			if($value==1) $value = 'true';
			if(!$value) $value = 'false';
		}
		$this->setValue($valName , $value, $type);
	}


	public function getOptionList(){
		if( !isset($this->optionsArray) || !count($this->optionsArray) ) return '';
		return implode( ', ' , $this->optionsArray);
	}


	public function addWidth( $colums, $index ){
		$ret = array();
		if( isset($colums['autowidth'.$index]) ){
			$ret[] = '"columnWidth": "auto"';
			if($colums['colums'.$index]) $ret[] =  '"columns":'.$colums['colums'.$index];
		} elseif($colums['width'.$index]){
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

}