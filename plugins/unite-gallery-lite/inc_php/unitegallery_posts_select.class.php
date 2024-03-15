<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


class UniteGalleryPostsSelect{
	
	
	/**
	 * get simple post types
	 */
	private function getArrSimplePostTypes($arrPostTypes){
			
		$arrTypesSimple = array();
		
		foreach($arrPostTypes as $arrType){
			
			$postTypeName = UniteFunctionsUG::getVal($arrType, "name");
			$postTypeTitle = UniteFunctionsUG::getVal($arrType, "title");
			
			if(isset($arrTypesSimple[$postTypeTitle]))
				$arrTypesSimple[$postTypeName] = $postTypeName;
			else
				$arrTypesSimple[$postTypeTitle] = $postTypeName;
		}
		
		return($arrTypesSimple);
	}
	
	
	/**
	 * get categories from all post types
	 */
	protected function getCategoriesFromAllPostTypes($arrPostTypes){
		
		if(empty($arrPostTypes))
			return(array());

		$arrAllCats = array();
		$arrAllCats[__("All Categories", "unitegallery")] = "all";
		
		foreach($arrPostTypes as $name => $arrType){
		
			if($name == "page")
				continue;
			
			$postTypeTitle = UniteFunctionsUG::getVal($arrType, "title");
			
			$cats = UniteFunctionsUG::getVal($arrType, "cats");
			
			if(empty($cats))
				continue;
			
			foreach($cats as $catID => $catTitle){
				
				if($name != "post")
					$catTitle = $catTitle." ($postTypeTitle type)";
				
				$arrAllCats[$catTitle] = $catID;
			}
			
		}
		
		
		return($arrAllCats);
	}
	
	
	
	/**
	 * get the posts select settings
	 */
	public function getPostsSelectSettings(){

		$settings = new UniteSettingsUGNEW();

		$arrPostTypes = UniteFunctionsWPUG::getPostTypesWithCats(GlobalsUG::$arrFilterPostTypes);
		
		//fill simple types
		$arrTypesSimple = $this->getArrSimplePostTypes($arrPostTypes);

		$params = array();
		
		$params["datasource"] = "post_type";
		$params[UniteSettingsUGNEW::PARAM_CLASSADD] = "unite-setting-post-type";
		
		$dataCats = UniteFunctionsUG::encodeContent($arrPostTypes);
		
		$params[UniteSettingsUGNEW::PARAM_ADDPARAMS] = "data-arrposttypes='$dataCats' data-settingtype='select_post_type' data-settingprefix=''";
		
		$settings->addMultiSelect("posttype", $arrTypesSimple, esc_html__("Post Types", "unitegallery"), "post", $params);
		
		//----- categories -------
		
		$arrCats = array();
		
		$arrCats = $arrPostTypes["post"]["cats"];
		$arrCats = array_flip($arrCats);
		
		$params = array();
		$params["datasource"] = "post_category";
		$params[UniteSettingsUGNEW::PARAM_CLASSADD] = "unite-setting-post-category";
		
		$settings->addMultiSelect("category", $arrCats, esc_html__("Include By Terms", "unitegallery"), "", $params);
		
		// --------- Include by term relation -------------
		
		$params = array();
				
		$arrRelationItems = array();
		$arrRelationItems["Or"] = "OR";
		$arrRelationItems["And"] = "AND";
		
		$settings->addSelect("category_relation", $arrRelationItems, __("Terms Relation", "unitegallery"), "OR", $params);

		//--------- show children -------------
		
		$params = array();
				
		$settings->addRadioBoolean("terms_include_children", __("Include Terms Children", "unitegallery"), false, "Yes", "No", $params);
		
		//----- hr after terms -------
		
		$params = array();
		
		$settings->addHr("hr_after_terms",$params);
		
		// --------- Include BY some options -------------
		
		$textPosts = "Posts";
		$textPost = "Post";
		
		$arrIncludeBy = array();
		
		$arrIncludeBy["author"] = __("Author", "unitegallery");
		$arrIncludeBy["date"] = __("Date", "unitegallery");
		$arrIncludeBy["parent"] = __("Post Parent", "unitegallery");
		$arrIncludeBy["meta"] = __("Post Meta", "unitegallery");
		$arrIncludeBy["sticky_posts"] = __("Include Sticky Posts", "unitegallery");
		$arrIncludeBy["sticky_posts_only"] = __("Get Only Sticky Posts", "unitegallery");			
		$arrIncludeBy["php_function"] = __("IDs from PHP function","unitegallery");
		$arrIncludeBy["ids_from_meta"] = __("IDs from Post Meta","unitegallery");
		
		$addPostsText = sprintf(__("Add Specific %s", "unitegallery"), $textPosts);
				
		$arrIncludeBy = array_flip($arrIncludeBy);
		
		$params = array();
		$params["select2"] = true;
		$params["placeholder"] = __("Select Options","unitegallery");
		
		$settings->addMultiSelect("includeby", $arrIncludeBy, esc_html__("Include By", "unitegallery"), "", $params);
		
		
		//---- Include By Author -----
		
		$settings->startBulkControl("includeby", "show", "author");
		
			$arrAuthors = UniteFunctionsWPUG::getArrAuthorsShort(true);
			
			$params = array();
			$params["is_multiple"] = true;
			$params["placeholder"] = __("Select one or more authors", "unitegallery");
			$params["select2"] = true;
			
			$arrAuthors = array_flip($arrAuthors);
			
			$settings->addMultiSelect("includeby_authors", $arrAuthors, __("Include By Author", "unitegallery"), "", $params);
		
		$settings->endBulkControl();
		
		//---- Include By Date -----
		$settings->startBulkControl("includeby", "show", "date");

			//----- date select -------
		
			$arrDates = HelperProviderUG::getArrPostsDateSelect();
			
			$arrDates = array_flip($arrDates);
			
			$settings->addSelect("includeby_date", $arrDates, __("Include By Date", "unitegallery"), "all", $params);
		
			//----- date meta field -------
			
			$params = array();
			$params["description"] = __("Optional, Select custom field (like ACF) with date format 20210310 (Ymd). For example: event_date","unitegallery");
			
			$settings->addTextBox("include_date_meta","",__("Date by Meta Field","unitegallery"),$params);
			
			//----- date before and after -------
			
			$params = array();
			$params["description"] = __("Show all the posts published until the chosen date, inclusive.","unitegallery");
			$params["placeholder"] = __("example: 25-12-2022", "unitegallery");
			
			//$arrConditionDateCustom = $arrConditionIncludeByDate;
			//$arrConditionDateCustom[$name."_includeby_date"] = "custom";
			
			$settings->addTextBox("include_date_before","",__("Published Before Date","unitegallery"),$params);
			
			$params["description"] = __("Show all the posts published since the chosen date, inclusive.","unitegallery");
			
			$settings->addTextBox("include_date_after","", __("Published After Date","unitegallery"),$params);
			
			//----- hr after date -------
			
			$params = array();
			
			$settings->addHr("hr_after_date",$params);
			
			
		$settings->endBulkControl();
		
		//---- Include By Post Parent -----
		
		$settings->startBulkControl("includeby", "show", "parent");

			$params = array();
			$params["placeholder"] = __("Select Parents Posts","unitegallery");
			
			$settings->addPostIDSelect("includeby_parent", __("Select Post Parents","unitegallery"), false, $params);
		
		$settings->endBulkControl();
		
		
		// --------- include by post meta -------------
		
		
		$settings->startBulkControl("includeby", "show", "meta");
		
			$params = array();
			$params["placeholder"] = __("Meta Key","unitegallery");
			
			$settings->addTextBox("includeby_metakey", "", esc_html__("Include by Meta Key", "unitegallery"), $params);
			
			// --------- meta compare -------------
			
			$params = array();
			$params["description"] = __("Get only those terms that has the meta key/value. For IN, NOT IN, BETWEEN, NOT BETWEEN compares, use coma saparated values","unitegallery");
			
			$arrItems = HelperProviderUG::getArrMetaCompareSelect();
			
			$arrItems = array_flip($arrItems);
			
			$settings->addSelect("includeby_metacompare", $arrItems, esc_html__("Include by Meta Compare", "unitegallery"), "=", $params);
			
			// --------- include by meta value -------------
				
			$params = array();
			$params["placeholder"] = __("Meta Value","unitegallery");
					
			$settings->addTextBox("includeby_metavalue", "", esc_html__("Include by Meta Value", "unitegallery"), $params);
			$settings->addTextBox("includeby_metavalue2", "", esc_html__("Include by Meta Value 2", "unitegallery"), $params);
			
			$params["description"] = "Special keywords you can use: {current_user_id}";
			
			$settings->addTextBox("includeby_metavalue3", "", esc_html__("Include by Meta Value 3", "unitegallery"), $params);
			
			// --------- Meta Fields Relation -------------
			
			$params = array();
	
			$arrRelations = array();
			$arrRelations["AND"] = "AND";
			$arrRelations["OR"] = "OR";
			
			$settings->addSelect("includeby_meta_relation", $arrRelations, esc_html__("Meta Fields Relation", "unitegallery"), "OR", $params);
		
		$settings->endBulkControl();

		// --------- include by php function -------------
		
		$settings->startBulkControl("includeby", "show", "php_function");
		
			$params = array();
			$params["placeholder"] = __("getMyIDs","unitegallery");
			$params["description"] = __("Get post id's array from php function. \n For example: function getMyIDs(\$arg){return(array(\"32\",\"58\")). This function MUST begin with 'get'. }");
			
			$settings->addTextBox("includeby_function_name", "", esc_html__("PHP Function Name", "unitegallery"), $params);
		
		$settings->endBulkControl();
		
		
		// --------- include by id's from meta -------------
		
		$settings->startBulkControl("includeby", "show", "ids_from_meta");
		
		$textIDsFromMeta = __("Select Post <br> (leave empty for current post)","unitegallery");
		$params = array();
		$params["placeholder"] = __("Select Post","unitegallery");
		
		$settings->addPostIDSelect("includeby_postmeta_postid", $textIDsFromMeta, true, $params);
				
		// --------- include by id's from meta field name -------------
		
		$params = array();
		$params["description"] = __("Choose meta field name that has the post id's on it. Good for acf relationship for example","unitegallery");
		
		$settings->addTextBox("includeby_postmeta_metafield", "", esc_html__("Meta Field Name", "unitegallery"), $params);
		
		$settings->endBulkControl();
		
		// --------- hr after include by -------------
		
		$settings->addHr("hr_after_includeby",$params);
		
		// --------- exclude by -------------
		
		$arrExclude = array();
		
		$arrExclude["no_image"] = sprintf(__("%s Without Featured Image", "unitegallery"),$textPost);
		$arrExclude["terms"] = __("Terms", "unitegallery");		
		$arrExclude["current_post"] = sprintf(__("Current %s", "unitegallery"), $textPosts);
		$arrExclude["specific_posts"] = sprintf(__("Specific %s", "unitegallery"), $textPosts);
		$arrExclude["author"] = __("Author", "unitegallery");
		$arrExclude["current_category"] = sprintf(__("%s with Current Category", "unitegallery"),$textPosts);
		$arrExclude["current_tags"] = sprintf(__("%s With Current Tags", "unitegallery"),$textPosts);
		$arrExclude["offset"] = sprintf(__("Offset", "unitegallery"),$textPosts);

		$arrExclude = array_flip($arrExclude);
		
		
		$params = array();
		$params["select2"] = true;
		$params["placeholder"] = __("Select Options","unitegallery");
		
		$settings->addMultiSelect("excludeby", $arrExclude, esc_html__("Exclude By", "unitegallery"), "", $params);
		

		$settings->startBulkControl("excludeby", "show", "terms");
		
		//------- Exclude By --- TERM --------
		
		$params = array();
		$params["datasource"] = "post_category";
		$params[UniteSettingsUGNEW::PARAM_CLASSADD] = "unite-setting-post-category";
		
		$settings->addMultiSelect("exclude_terms", $arrCats, esc_html__("Exclude By Terms", "unitegallery"), "", $params);
		
		
		//--------- exclude by - children -------------
		
		$params = array();
				
		$settings->addRadioBoolean("terms_exclude_children", __("Exclude Terms With Children", "unitegallery"), false, "Yes", "No", $params);
		
		$settings->endBulkControl();
				
		//------- Exclude By --- AUTHOR --------

		$settings->startBulkControl("excludeby", "show", "author");
		
			$arrAuthors = UniteFunctionsWPUG::getArrAuthorsShort(true);
			
			$params = array();
			$params["is_multiple"] = true;
			$params["placeholder"] = __("Select one or more authors", "unitegallery");
			$params["select2"] = true;
			
			$arrAuthors = array_flip($arrAuthors);
			
			$settings->addMultiSelect("excludeby_authors", $arrAuthors, __("Exclude By Author", "unitegallery"), "", $params);
		
		$settings->endBulkControl();
		
		
		//------- Exclude By --- OFFSET --------
		
		$settings->startBulkControl("excludeby", "show", "offset");
		
			$params = array();
			$params["description"] = __("Use this setting to skip over posts, not showing first posts to the offset given","unitegallery");
			$params["class"] = UniteSettingsOutputUGNEW::INPUT_CLASS_NUMBER;
			
			
			$settings->addTextBox("offset", "0", esc_html__("Offset", "unitegallery"), $params);
		
		$settings->endBulkControl();
			
		
		//------- Exclude By --- SPECIFIC POSTS --------
		
		$settings->startBulkControl("excludeby", "show", "specific_posts");
		
			$params = array();
			$params["placeholder"] = __("Posts To Exclude","unitegallery");
			
			$settings->addPostIDSelect("excludeby_specific_posts", __("Select Posts To Exclude","unitegallery"), false, $params);
			
		$settings->endBulkControl();
		
		
		// --------- hr after exclude by -------------
		
		$settings->addHr("hr_after_excludeby",$params);
		
		
		//------- Post Status --------
		
		$arrStatuses = HelperProviderUG::getArrPostStatusSelect();
		
		$params = array();
		$params["placeholder"] = __("Select one or more statuses", "unitegallery");
		
		$arrStatuses = array_flip($arrStatuses);
		
		$settings->addMultiSelect("status", $arrStatuses, __("Post Status", "unitegallery"), array("publish"), $params);
		
		//------- max items --------
		
		$params = array(
			"unit"=>"posts",
			"class"=>UniteSettingsOutputUGNEW::INPUT_CLASS_NUMBER
		);
		
		$params["placeholder"] = __("100 posts if empty","unitegallery");
		
		$settings->addTextBox("maxitems", 10, sprintf(esc_html__("Max %s", "unitegallery"), $textPosts), $params);
		
		//----- orderby --------
		
		$arrOrder = UniteFunctionsWPUG::getArrSortBy();
		$arrOrder = array_flip($arrOrder);
		
		$arrDir = UniteFunctionsWPUG::getArrSortDirection();
		$arrDir = array_flip($arrDir);
		
		$params = array();
		$settings->addSelect("orderby", $arrOrder, __("Order By", "unitegallery"), "default", $params);
		
		//--- meta value param -------
		
		$params = array();
		$params["class"] = "alias";

		$settings->addTextBox("orderby_meta_key1", "" , __("&nbsp;&nbsp;Custom Field Name","unitegallery"), $params);
		
		$settings->addControl("orderby", "orderby_meta_key1", "show", UniteFunctionsWPUG::SORTBY_META_VALUE.",".UniteFunctionsWPUG::SORTBY_META_VALUE_NUM);
		
		//---- order dir -----
		
		$params = array();
		
		$settings->addSelect("orderdir1", $arrDir, __("&nbsp;&nbsp;Order By Direction", "unitegallery"), "default", $params);
		
		//---- show debug -----
		
		$params = array();
		$params["description"] = __("Show the query for debugging purposes. Don't forget to turn it off before page release", "unitegallery");
		
		$settings->addRadioBoolean("show_query_debug", __("Show Query Debug", "unitegallery"), false, "Yes", "No", $params);
		
		
		
		return($settings);
	}
	
	
	/**
	 * get search text from data
	 */
	private function getSearchFromData($data){
		
		$type = UniteFunctionsUG::getVal($data, "_type");
		
		if($type != "query")
			return(null);
		
		$searchTerm = UniteFunctionsUG::getVal($data, "q");
		
		return($searchTerm);
	}
	
	
	/**
	 * get post list for select2
	 */
	public function getPostListForSelectFromData($data, $addNotSelected = false, $limit = 10){
		
		$search = $this->getSearchFromData($data);
				
		$filterPostType = UniteFunctionsUG::getVal($data, "post_type");
		
		switch($filterPostType){
			case "product":
				$arrTypesAssoc = array("product" => __("Product","unitegallery"));
			break;
			case "elementor_template":
				$arrTypesAssoc = array("elementor_library" => __("Template","unitegallery"));
			break;
			default:
				$arrTypesAssoc = UniteFunctionsWPUG::getPostTypesAssoc(array(), true);
				
				if(empty($search))
					$arrTypesAssoc = array("post"=>"Post");
				
			break;
		}
		
				
		$arrPostTypes = array_keys($arrTypesAssoc);
		
		$strPostTypes = implode("','", $arrPostTypes);
		$strPostTypes = "'$strPostTypes'";
		
		//prepare query
		$db = HelperUG::getDB();
				
		$tablePosts = GlobalsUG::$table_posts;
		
		$search = $db->escape($search);
		
		$where = "post_type in ($strPostTypes)";
		$where .= " and post_status in ('publish','draft','private')";
		
		$isStartWord = (strlen($search) == 1);
		
		$whereStartWord = $where." and post_title like '$search%'";
		
		$whereRegular = $where." and post_title like '%$search%'";
		
		$sqlStartWord = "select * from $tablePosts where $whereStartWord limit $limit";
		
		$sql = "select * from $tablePosts where $whereRegular limit $limit";
		
		if($isStartWord == true){
			
			//start word, then regular
			$response = $db->fetchSql($sqlStartWord);
			
			if(empty($response))
				$response = $db->fetchSql($sql);
			
		}else{
		
			//regular only
			$response = $db->fetchSql($sql);
		}
				
		if(empty($response))
			return(array());
		
		$arrResult = array();
		
		//add empty value
		if($addNotSelected == true){
			$arr = array();
			$arr["id"] = 0;
			$arr["text"] = __("[please select post]", "unitegallery");
			$arrResult[] = $arr;
		}
		
		foreach($response as $post){
			
			$postID = $post["ID"];
			$postTitle = $post["post_title"];
			$postType = $post["post_type"];
			
			$postTypeTitle = UniteFunctionsUG::getVal($arrTypesAssoc, $postType);
			
			if(empty($postTypeTitle))
				$postTypeTitle = $postType;
			
			$title = $postTitle." - ($postTypeTitle)";
			
			$arr = array();
			$arr["id"] = $postID;
			$arr["text"] = $title;
			
			$arrResult[] = $arr;			
		}
		
		
		$arrOutput = array();
		$arrOutput["results"] = $arrResult;
		$arrOutput["pagination"] = array("more"=>false);
				
		
		return($arrOutput);
	}
	
	
	
}