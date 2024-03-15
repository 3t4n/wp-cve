<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


class UniteGalleryCategory extends UniteElementsBaseUG{
	
	private $titleBase = "New Category";
	
	const SORTBY_CUSTOM = "custom";
	const SORTBY_TITLE_ASC = "title_asc";
	const SORTBY_TITLE_DESC = "title_desc";
	
	const SOURCE_ITEMS = "items";
	const SOURCE_POSTS = "posts";
	
	private $id;
	private $record;
	private $title, $numItems, $params, $sortBy, $source;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		$this->sortBy = self::SORTBY_CUSTOM;
		
		parent::__construct();
	}
	
	
	/**
	 * get sortby array
	 */
	public static function getArrSortby(){
		
		$arrSortby = array();
		$arrSortby[self::SORTBY_CUSTOM] = __("Custom", "unitegallery");
		$arrSortby[self::SORTBY_TITLE_ASC] = __("Title asc", "unitegallery");
		$arrSortby[self::SORTBY_TITLE_DESC] = __("Title desc", "unitegallery");
		
		return($arrSortby);
	}
	
	
	/**
	 * validate inited
	 */
	private function validateInited(){
		
		if(empty($this->id))
			UniteFunctionsUG::throwError("The category is not inited");
	}
	
	/**
	 * validate sortby value
	 */
	private function validateSortby($sortbyVal){
		
		$arrSortby = self::getArrSortby();
		if(array_key_exists($sortbyVal, $arrSortby) == false)
			UniteFunctionsUG::throwError("Sortby value: $sortbyVal don't exists");
	}
	
	
	/**
	 * init category by record
	 */
	public function initByRecord($record){
		
		$this->id = $record["id"];
		
		$this->title = $record["title"];
		$this->numItems = UniteFunctionsUG::getVal($record, "num_items");
		
		//init params
		$params = UniteFunctionsUG::getVal($record, "params");
				
		if(empty($params))
			$params = array();
		else{
			$params = json_decode($params);
			$params = UniteFunctionsUG::convertStdClassToArray($params);
		}
		
		$this->params = $params;
		
		//init sortby
		$this->sortBy = $this->getParam("sortby", self::SORTBY_CUSTOM);
		
		//init source
		$this->source = $this->getParam("source", self::SOURCE_ITEMS);
		
		$this->record = $record;
	}
	
	
	/**
	 * init category by id
	 */
	public function initByID($catID){
	
		UniteFunctionsUG::validateNumeric($catID, "category id");
	
		$catID = (int)$catID;
	
		$record = $this->db->fetchSingle(GlobalsUG::$table_categories,"id=$catID");
		if(empty($record))
			UniteFunctionsUG::throwError("Category with id: $catID not found");
		
		$this->initByRecord($record);
	}
	
	
	/**
	 * get category record
	 */
	public function getRecord(){
		
		$this->validateInited();
		
		return($this->record);
	}
	
	
	/**
	 * get param
	 */
	public function getParam($paramName, $defaultValue=null){
		
		$value = UniteFunctionsUG::getVal($this->params, $paramName, $defaultValue);
		
		return($value);
	}
	
	
	/**
	 * get gallery params
	 */
	public function getParams(){
		
		return($this->params);
	}
	
	
	/**
	 *
	 * get html of category
	 */
	public function getHTMLAdmin($class = ""){
	
		$this->validateInited();
	
		$id = $this->id;
		$title = $this->title;
		$numItems = $this->numItems;
		$sortby = $this->sortBy;
				
		$postsData = null;
		
		$addData = "";
		
		if($this->source == self::SOURCE_POSTS){
			$postsData = $this->getParam("posts_data");
			
			$addData = UniteFunctionsUG::jsonEncodeForHtmlData($postsData, "postsdata");
		}
		
		$showTitle = htmlspecialchars($title);
	
		if(!empty($numItems))
			$showTitle .= " ($numItems)";
		
		$source = esc_attr($this->source);
		
		$html = "";
		$html .= "<li id=\"category_{$id}\" {$class} data-id=\"{$id}\" data-numitems=\"{$numItems}\" data-title=\"{$title}\" data-sortby=\"{$sortby}\" data-source=\"{$source}\" $addData >\n";
		$html .= "	<span class=\"cat_title\">{$showTitle}</span>\n";
		$html .= "</li>\n";
		
		return($html);
	}
	
	
	/**
	 * get sortby property
	 */
	public function getSortby(){
		
		$this->validateInited();
		
		return($this->sortBy);
	}
	
	/**
	 * get ID
	 */
	public function getID(){
		$this->validateInited();
		
		return($this->id);
	}
	
	
	private function ___________ACTIONS_____________(){}
	
	
	/**
	 *
	 * add category
	 */
	public function add($titleBase = null){
		
		$objCategories = new UniteGalleryCategories();
		
		$maxOrder = $objCategories->getMaxOrder();
		if(empty($titleBase))
			$titleBase = $this->titleBase;
		
		//set title
		$arrTitles = $objCategories->getArrCatTitlesAssoc();
		
		
		$title = $titleBase;
		$titleExists = array_key_exists($title, $arrTitles);
		if($titleExists == true){
			$counter = 1;
			do{
				$counter++;
				$title = $titleBase." ".$counter;
				$titleExists = array_key_exists($title, $arrTitles);
			}while($titleExists == true);
		}
	
	
		//prepare insert array
		$arrInsert = array();
		$arrInsert["title"] = $title;
		$arrInsert["ordering"] = $maxOrder+1;
		$arrInsert["params"] = '';
	
		//insert the category
		$catID = $this->db->insert(GlobalsUG::$table_categories,$arrInsert);
	
		if(empty($catID))
			UniteFunctionsUG::throwError("Unable to create category. Please check if your database create autoincriment id's");
		
		//init category
		$arrInsert["id"] = $catID;
		$arrInsert["num_itemd"] = 0;
		$this->initByRecord($arrInsert);
		
		//prepare output
		$returnData = array("id"=>$catID,"title"=>$title);
		return($returnData);
	}
	
	
	/**
	 *
	 * remove the category.
	 */
	public function remove(){
		
		$this->validateInited();
				
		//remove category
		$this->db->delete(GlobalsUG::$table_categories,"id=".$this->id);
	
		//remove items
		$this->db->delete(GlobalsUG::$table_items,"catid=".$this->id);
	}
	
	/**
	 * update params in db
	 */
	private function updateParamsInDB(){
		
		$strParams = json_encode($this->params);
		
		$arrUpdate = array();
		$arrUpdate["params"] = $strParams;
		
		$this->db->update(GlobalsUG::$table_categories, $arrUpdate, array("id"=>$this->id));
	}
	
	
	/**
	 * update param in db
	 */
	private function updateParam($name, $value){
		
		$this->validateInited();
		
		$this->params[$name] = $value;
		
		$this->updateParamsInDB();
	}
	
	
	/**
	 * update params
	 */
	public function updateParams($params){
		
		$this->validateInited();
		
		if(empty($params))
			return(false);
			
		if(is_array($params) == false)
			return(false);
			
		$this->params = array_merge($this->params, $params);
		
		$this->updateParamsInDB();
	}
	
	
	/**
	 * update sortby param
	 * @param $sortbyValue
	 */
	public function updateSortby($sortbyValue){
		
		$this->validateSortby($sortbyValue);
		
		$this->updateParam("sortby", $sortbyValue);
		
	}
	
	
	/**
	 *
	 * update category
	 */
	public function updateTitle($title){
		
		$this->validateInited();
		
		$title = $this->db->escape($title);
		
		$arrUpdate = array();
		$arrUpdate["title"] = $title;
		$this->db->update(GlobalsUG::$table_categories, $arrUpdate, array("id"=>$this->id));
		
	}
	
	
	
}