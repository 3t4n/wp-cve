<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


/**
 * 
 * gallery helper functions class
 *
 */
class HelperHTMLUG{
			
	/**
	 *
	 * get link html
	 */
	public static function getHtmlLink($link,$text,$id="",$class="", $isNewWindow = false){
	
		if(!empty($class))
			$class = " class='$class'";
	
		if(!empty($id))
			$id = " id='$id'";
	
		$htmlAdd = "";
		if($isNewWindow == true)
			$htmlAdd = ' target="_blank"';
	
		$html = "<a href=\"$link\"".$id.$class.$htmlAdd.">$text</a>";
		return($html);
	}

	
	/**
	 *
	 * get select from array
	 */
	public static function getHTMLSelect($arr,$default="",$htmlParams="",$assoc = false, $addData = null, $addDataText = null){
	
		$html = "<select $htmlParams>";
		//add first item
		if($addData == "not_chosen"){
			$selected = "";
			$default = trim($default);
			if(empty($default))
				$selected = " selected='selected' ";
				
			$itemText = $addDataText;
			if(empty($itemText))
				$itemText = "[".esc_html__("not chosen", "unitegallery")."]";
				
			$html .= "<option $selected value=''>{$itemText}</option>";
		}
		
		foreach($arr as $key=>$item){
			$selected = "";
	
			if($assoc == false){
				if($item == $default) 
					$selected = " selected='selected' ";
			}
			else{
				if(trim($key) == trim($default))
					$selected = " selected='selected' ";
			}
			
			$addHtml = "";
			if(strpos($key, "html_select_sap") !== false)
				$addHtml = " disabled";
			
			if($assoc == true)
				$html .= "<option $selected value='$key' $addHtml>$item</option>";
			else
				$html .= "<option $selected value='$item' $addHtml>$item</option>";
		}
		$html.= "</select>";
		return($html);
	}
	
	
	/**
	 * get preview pane html from data
	 */
	public static function getPreviewPaneHTMLFromData($data){
		
		$postsData = UniteFunctionsUG::getVal($data, "posts_data");
		
		if(empty($postsData))
			UniteFunctionsUG::throwError("no posts data found");
		
		$arrData = UniteFunctionsUG::decodeContent($postsData);
		
		$objProcessor = new UniteGalleryPostsProcessor();
		
		$arrItems = $objProcessor->getPostsItems($arrData);
		
		//get debug
		
		$showDebug = UniteFunctionsUG::getVal($arrData, "show_query_debug");
		$showDebug = UniteFunctionsUG::strToBool($showDebug);
		
		
		//get html preview
		$htmlDebug = null;
		
		if($showDebug == true){
			
			$debugText = $objProcessor->getDebug();
			
			$htmlDebug = "<pre>".$debugText.print_r(GlobalsUG::$lastPostsQuery,true)."</pre>";
		}
		
		
		$htmlPreview = "";
		
		if(empty($arrItems)){
			
			$htmlPreview = __("No Posts Found","unitegallery");
			
		}else{
			
			foreach($arrItems as $item){
				
				$htmlItem = $item->getHtmlForAdmin();
				
				$htmlPreview .= $htmlItem;
			}
			
		}
			
		
		$response = array();
		$response["html"] = $htmlPreview;
		
		if(!empty($htmlDebug))
			$response["debug"] = $htmlDebug;
		
		
		return($response);
		
	}
	
}
	