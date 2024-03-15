<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

class UniteGalleryPostsProcessor{

	private $debugText = "";
	
	
	/**
	 * add debug text
	 */
	private function debug($str){
		
		$text = print_r($str, true);
		
		$this->debugText .= "\n<pre>$text</pre>";
	}
	
	
	/**
	 * clear debug
	 */
	private function clearDebug(){
		
		$this->debugText = "";
	}
	
	/**
	 * get the debug text
	 */
	public function getDebug(){
		
		return($this->debugText);
	}
	
	
	/**
	 * get items from posts
	 */
	private function getItemsFromPosts($arrPosts){
		
		if(empty($arrPosts))
			return(array());

		$arrItems = array();
		
		foreach($arrPosts as $post){
			
						
			$item = new UniteGalleryItem();
			$item->initByPost($post);
			
			$arrItems[] = $item;
		}
		
		
		return($arrItems);
	}
	
	
	/**
	 * get date query
	 */
	private function getPostListData_dateQuery($value){
				
		$dateString = UniteFunctionsUG::getVal($value, "includeby_date");
		
		if($dateString == "all")
			return(array());

		$metaField = UniteFunctionsUG::getVal($value, "include_date_meta");
		$metaField = trim($metaField);
		
		$metaFormat = UniteFunctionsUG::getVal($value, "include_date_meta_format");
		
		if(empty($metaFormat))
			$metaFormat = "Ymd";
		
		$arrDateQuery = array();
		$arrMetaQuery = array();	
		
		$after = "";
		$before = "";
		$year = "";
		$month = "";
		$day = "";
		
		$afterMeta = null;
		$beforeMeta = null;
		
		switch($dateString){
			case "today":
				$after = "-1 day";
				
			break;
			case "this_day":
				
				if(!empty($metaField)){
					$afterMeta = date($metaFormat);
					$beforeMeta = date($metaFormat);
				}else{
					
					$year = date("Y");
					$month = date("m");
					$day = date("d");
										
					$arrDateQuery['inclusive'] = true;
				}
				
			break;
			case "past_from_today":
				
				if(!empty($metaField)){
					$beforeMeta = date($metaFormat);					
				}else{
					
					$before = "tomorrow";
					
					$arrDateQuery['inclusive'] = true;
				}
				
			break;
			case "past_from_yesterday":
				
				if(!empty($metaField)){
					$beforeMeta = date($metaFormat,strtotime('-1 day'));					
				}else{
					
					$before = "today";
					
					$arrDateQuery['inclusive'] = false;
				}
				
			break;
			case "yesterday":
				$after = "-2 day";
			break;
			case "week":
				$after = '-1 week';
			break;
			case "month":
				$after = "-1 month";
			break;
			case "three_months":
				$after = "-3 months";
			break;
			case "year":
				$after = "-1 year";
			break;
			case "this_month":
				
				if(!empty($metaField)){
					
					$afterMeta = date('Ym01');
					$beforeMeta = date('Ymt');
				
				}else{
					$year = date("Y");
					$month = date("m");
				}
				
			break;
			case "next_month":
				
				if(!empty($metaField)){
					
					$afterMeta = date($metaFormat,strtotime('first day of +1 month'));
					$beforeMeta = date($metaFormat,strtotime('last day of +1 month'));
				}else{
					
					$time = strtotime('first day of +1 month');
					
					$year = date("Y",$time);
					$month = date("m",$time);
				}
				
			break;
			case "future":
				
				if(!empty($metaField)){
					$afterMeta = date($metaFormat);
				}else{
					
					$after = "today";
					
					$arrDateQuery['inclusive'] = true;
				}
				
			break;
			case "future_tomorrow":
				
				if(!empty($metaField)){
					
					$afterMeta = date($metaFormat,strtotime('+1 day'));
				}else{
					
					$after = "today";
					
					$arrDateQuery['inclusive'] = false;
				}
				
			break;
			case "custom":
				
				$before = UniteFunctionsUG::getVal($value, "include_date_before");
				
				$after = UniteFunctionsUG::getVal($value, "include_date_after");
				
				if(!empty($before) || !empty($after))
					$arrDateQuery['inclusive'] = true;
				
			break;
		}
		
		if(!empty($metaField)){
			
			if(!empty($after) && empty($afterMeta)){
				$afterMeta = date($metaFormat, strtotime($after));
			}
			
			if(!empty($afterMeta))
				$arrMetaQuery[] = array(
		            'key'     => $metaField,
		            'compare' => '>=',
		            'value'   => $afterMeta
        		);				
			
			if(!empty($before) && empty($beforeMeta))
				$beforeMeta = date($metaFormat, strtotime($before));
				
			if(!empty($beforeMeta))
				$arrMetaQuery[] = array(
		            'key'     => $metaField,
		            'compare' => '<=',
		            'value'   => $beforeMeta
        		);				
			
		}else{
			if(!empty($before))
				$arrDateQuery["before"] = $before;
			
			if(!empty($after))
				$arrDateQuery["after"] = $after;
			
			if(!empty($year))
				$arrDateQuery["year"] = $year;
			
			if(!empty($month))
				$arrDateQuery["month"] = $month;
			
			if(!empty($day))
				$arrDateQuery["day"] = $day;
				
		}
		
		
		$response = array();
		if(!empty($arrDateQuery))
			$response["date_query"] = $arrDateQuery;
		
		if(!empty($arrMetaQuery))
			$response["meta_query"] = $arrMetaQuery;
						
		return($response);
	}
	
	/**
	 * modify the meta value, process the special keywords
	 */
	private function modifyMetaValueForCompare($metaValue){
		
		switch($metaValue){
			case "{current_user_id}":
				$userID = get_current_user_id();
				if(empty($userID))
					$userID = "0";
				
				return($userID);
			break;
		}
		
		
		return($metaValue);
	}
	
	/**
	 * get post ids from post meta
	 */
	private function getPostListData_getIDsFromPostMeta($value, $showDebugQuery){
		
		$postIDs = UniteFunctionsUG::getVal($value, "includeby_postmeta_postid");
		
		$metaName = UniteFunctionsUG::getVal($value, "includeby_postmeta_metafield");
		
		$errorMessagePrefix = "Get post ids from meta error: ";
		
		if(empty($metaName)){
				
			if($showDebugQuery == true)
				$this->debug($errorMessagePrefix." no meta field selected");
			
			return(null);
		}
		
		if(!empty($postIDs)){
			if(is_array($postIDs))
				$postID = $postIDs[0];
			else
				$postID = $postIDs;
		}
		else{		//current post
			
			$post = get_post();
			if(empty($post)){
				
				if($showDebugQuery == true)
					$this->debug($errorMessagePrefix." no post found");
					
				return(null);
			}
				
			$postID = $post->ID;
		}
		
		if(empty($postID)){
				
			if($showDebugQuery == true)
				$this->debug($errorMessagePrefix." no post found");
			
			return(null);
		}
		
		//show the post title
		if($showDebugQuery == true){
		
			$post = get_post($postID);
			$title = $post->post_title;
			$postType = $post->post_type;
			
			$this->debug("Getting post id's from meta fields from post: <b>$postID - $title ($postType) </b>");
		}
		
		$arrPostIDs = get_post_meta($postID, $metaName, true);
		
		if(is_array($arrPostIDs) == false){
			$arrPostIDs = explode(",", $arrPostIDs);
		}
		
		$isValidIDs = UniteFunctionsUG::isValidIDsArray($arrPostIDs);
		
		if(empty($arrPostIDs) || $isValidIDs == false){
		
			if($showDebugQuery == true){
				
				$metaKeys = UniteFunctionsWPUG::getPostMetaKeys($postID, null, true);
				if(empty($metaKeys))
					$metaKeys = array();
				
				$this->debug($errorMessagePrefix." no post ids found");
				
				if(array_search($metaName, $metaKeys) === false){
					$this->debug("maybe you intent to use one of those meta keys:");
					$this->debug($metaKeys);
				}
			}
			
			return(null);
		}
		
		
		if($showDebugQuery == true){
			$strPosts = implode(",", $arrPostIDs);
			$this->debug("Found post ids : $strPosts");
		}
		
		return($arrPostIDs);
	}
	
	
	/**
	 * get meta values
	 */
	private function getPostListData_metaValues($arrMetaSubQuery, $metaValue, $metaKey, $metaCompare){
		
		//single - default
		
		if(strpos($metaValue, "||") === false){
			
			$arrMetaSubQuery[] = array(
	            'key' => $metaKey,
	            'value' => $metaValue,
				'compare'=>$metaCompare
			);
			
			return($arrMetaSubQuery);
		}
			
		$arrValues = explode("||", $metaValue);
		
		if(empty($arrValues))
			return($arrMetaSubQuery);
		
		foreach($arrValues as $metaValue){
			
			$arrMetaSubQuery[] = array(
	            'key' => $metaKey,
	            'value' => $metaValue,
				'compare'=>$metaCompare
			);
			
		}
			
		return($arrMetaSubQuery);
	}
	
	/**
	 * get post ids from php function
	 */
	private function getPostListData_getIDsFromPHPFunction($value, $showDebugQuery){
		
		$functionName = UniteFunctionsUG::getVal($value, "includeby_function_name");
		
		$errorTextPrefix = "get post id's by PHP Function error: ";
				
		if(empty($functionName)){
			
			if($showDebugQuery)
				$this->debug($errorTextPrefix."no functon name given");
			
			return(null);
		}

		if(is_string($functionName) == false)
			return(false);
		
		if(strpos($functionName, "get") !== 0){
			
			if($showDebugQuery)
				$this->debug($errorTextPrefix."function <b>$functionName</b> should start with 'get'. like getMyPersonalPosts()");
			
			return(null);
		}
		
		if(function_exists($functionName) == false){
			
			if($showDebugQuery)
				$this->debug($errorTextPrefix."function <b>$functionName</b> not exists.");
			
			return(null);
		}
			
		$argument = UniteFunctionsUG::getVal($value, "includeby_function_addparam");
		
		$arrIDs = call_user_func_array($functionName, array($argument));
		
		$isValid = UniteFunctionsUG::isValidIDsArray($arrIDs);
		
		if($isValid == false){
			
			if($showDebugQuery)
				$this->debug($errorTextPrefix."function <b>$functionName</b> returns invalid id's array.");
			
			return(null);
		}
		
		if($showDebugQuery == true){
			$this->debug("php function <b>$functionName(\"$argument\")</b> output: ");
			$this->debug($arrIDs);
		}
		
		return($arrIDs);
	}
	
	
	/**
	 * get posts from settings
	 */
	public function getPostsItems($value, $offsetArg = null, $limitArg = null){
		
		//from getPostListData_custom
		
		if(empty($value))
			return(array());
		
		if(is_array($value) == false)
			return(array());
		
		$this->clearDebug();
		
		$showDebugQuery = UniteFunctionsUG::getVal($value, "show_query_debug");
		$showDebugQuery = UniteFunctionsUG::strToBool($showDebugQuery);
			
		$source = UniteFunctionsUG::getVal($value, "source");
				
		$postType = UniteFunctionsUG::getVal($value, "posttype", "post");
		
		$orderBy =  UniteFunctionsUG::getVal($value, "orderby");
		$orderDir =  UniteFunctionsUG::getVal($value, "orderdir1");
		$orderByMetaKey = UniteFunctionsUG::getVal($value, "orderby_meta_key1");

		$arrExcludeBy = UniteFunctionsUG::getVal($value, "excludeby", array());
		$arrIncludeBy = UniteFunctionsUG::getVal($value, "includeby", array());
		
		$arrMetaQuery = array();
		
		// --- limit ---- 
		
		$limit = UniteFunctionsUG::getVal($value, "maxitems");
		
		$limit = (int)$limit;
		if($limit <= 0)
			$limit = 100;
		
		if($limit > 1000)
			$limit = 1000;
		
		$args = array();
		
		$filters = array();
		
		//category
				
		$category = UniteFunctionsUG::getVal($value, "category");
		
		if(!empty($category))
			$filters["category"] = $category;
		
		$relation = UniteFunctionsUG::getVal($value, "category_relation");
		
		if(!empty($relation) && !empty($category))
			$filters["category_relation"] = $relation;
		
		$termsIncludeChildren = UniteFunctionsUG::getVal($value, "terms_include_children");
		$termsIncludeChildren = UniteFunctionsUG::strToBool($termsIncludeChildren);
		
		if($termsIncludeChildren === true)
			$filters["category_include_children"] = true;

		//max items
			
		$limit = UniteFunctionsUG::getVal($value, "maxitems");
		
		$limit = (int)$limit;
		if($limit <= 0)
			$limit = 100;
		
		if($limit > 1000)
			$limit = 1000;
		
		$filters["limit"] = $limit;
		
		//get the args from filters
		
		$args = UniteFunctionsWPUG::getPostsArgs($filters);
		
		
		//------ Exclude ---------
		
		if(is_string($arrExcludeBy))
			$arrExcludeBy = array($arrExcludeBy);
		
		if(is_array($arrExcludeBy) == false)
			$arrExcludeBy = array();

		$excludeProductsOnSale = false;
		$excludeSpecificPosts = false;
		$excludeByAuthors = false;
		$arrExcludeTerms = array();
		$offset = null;
		$isAvoidDuplicates = false;
		$arrExcludeIDsDynamic = null;
		
		foreach($arrExcludeBy as $excludeBy){
			
			switch($excludeBy){
				case "current_post":
					$filters["exclude_current_post"] = true;
				break;
				case "terms":
					
					$arrTerms = UniteFunctionsUG::getVal($value, "exclude_terms");

					$arrExcludeTerms = UniteFunctionsUG::mergeArraysUnique($arrExcludeTerms, $arrTerms);
															
					$termsExcludeChildren = UniteFunctionsUG::getVal($value, "terms_exclude_children");
					$termsExcludeChildren = UniteFunctionsUG::strToBool($termsExcludeChildren);
					
					$filters["category_exclude_children"] = $termsExcludeChildren;
					
				break;
				case "specific_posts":
					
					$excludeSpecificPosts = true;
				break;
				case "author":
					
					$excludeByAuthors = true;
				break;
				case "no_image":
					
					$arrMetaQuery[] = array(
						"key"=>"_thumbnail_id",
						"compare"=>"EXISTS"
					);
					
				break;
				case "current_category":
					
					if(empty($post))
						$post = get_post();
										
					$arrCatIDs = UniteFunctionsWPUG::getPostCategoriesIDs($post);
					
					$arrExcludeTerms = UniteFunctionsUG::mergeArraysUnique($arrExcludeTerms, $arrCatIDs);
				break;
				case "current_tag":
					
					if(empty($post))
						$post = get_post();
					
					$arrTagsIDs = UniteFunctionsWPUG::getPostTagsIDs($post);
					
					$arrExcludeTerms = UniteFunctionsUG::mergeArraysUnique($arrExcludeTerms, $arrTagsIDs);
				break;
				case "offset":
										
					$offset = UniteFunctionsUG::getVal($value, "offset");
					$offset = (int)$offset;
					
				break;
				
			}
			
		}
		
		if(!empty($arrExcludeTerms))
			$filters["exclude_category"] = $arrExcludeTerms;
		
		//includeby before filters
		foreach($arrIncludeBy as $includeby){
			
			switch($includeby){
				case "terms_from_dynamic":
										
					$strTermIDs = UniteFunctionsUG::getVal($value, "includeby_terms_dynamic_field");
					
					$arrTermIDs = UniteFunctionsUG::getIDsArray($strTermIDs);
					
					if(!empty($arrTermIDs)){
						if(empty($category))
							$category = array();
							
						$category = array_merge($arrTermIDs, $category);
						$category = array_unique($category);
						
						$filters["category"] = $category;
					}
					
				break;
			}			
			
		}
			
						
		//run custom query if available
		$args = UniteFunctionsWPUG::getPostsArgs($filters);
		
		
		//exclude by authors
		
		if($excludeByAuthors == true){
			
			$arrExcludeByAuthors = UniteFunctionsUG::getVal($value, "excludeby_authors");
						
			$arrExcludeByAuthors = trim($arrExcludeByAuthors);
			
			if(!empty($arrExcludeByAuthors) && is_string($arrExcludeByAuthors))
				$arrExcludeByAuthors = array($arrExcludeByAuthors);
			
			if(empty($arrExcludeByAuthors))
				$arrExcludeByAuthors = array();
							
			foreach($arrExcludeByAuthors as $key => $userID){
				
				if($userID == "uc_loggedin_user"){
					
					$userID = get_current_user_id();
					
					if(empty($userID))
						unset($arrExcludeByAuthors[$key]);
					else
						$arrExcludeByAuthors[$key] = $userID;
				}
				
			}
			
			if(!empty($arrExcludeByAuthors))
				$args["author__not_in"] = $arrExcludeByAuthors;
		}
		
		//exclude by specific posts
		
		$arrPostsNotIn = array();
		
		if($excludeSpecificPosts == true){
						
			$specificPostsToExclude = UniteFunctionsUG::getVal($value, "excludeby_specific_posts");
						
			if(!empty($specificPostsToExclude)){
				
				if(empty($arrPostsNotIn))
					$arrPostsNotIn = $specificPostsToExclude;
				else
					$arrPostsNotIn = array_merge($arrPostsNotIn, $specificPostsToExclude);
			}
			
		}
		
		//exclude from dynamic field
		
		if(!empty($arrExcludeIDsDynamic)){
			
			if(empty($arrExcludeIDsDynamic))
				$arrPostsNotIn = $arrExcludeIDsDynamic;
			else
				$arrPostsNotIn = array_merge($arrPostsNotIn, $arrExcludeIDsDynamic);
		}
		
		
		$args["ignore_sticky_posts"] = true;
		
		$getOnlySticky = false;
		$checkStickyPostsByPlugin = false;
		
		$product = null;
		
		$arrProductsUpSells = array();
		$arrProductsCrossSells = array();
		$arrIDsOnSale = array();
		$arrRecentProducts = array();
		$arrIDsPopular = array();
		$arrIDsPHPFunction = array();
		$arrIDsPostMeta = array();
		$arrTermIDs = array();
		
		$currentTaxQuery = null;
		
		$makePostINOrder = false;
				
		
		foreach($arrIncludeBy as $includeby){
						
			switch($includeby){
				
				case "sticky_posts":
					$args["ignore_sticky_posts"] = false;
					
				break;
				case "sticky_posts_only":
					$getOnlySticky = true;
				break;
				case "author":
					
					$arrIncludeByAuthors = UniteFunctionsUG::getVal($value, "includeby_authors");
					
					if(is_string($arrIncludeByAuthors))
						$arrIncludeByAuthors = explode(",", $arrIncludeByAuthors);
					
					if(empty($arrIncludeByAuthors))
						$arrIncludeByAuthors = array();
											
					//if set to current user, and no user logged in, then get no posts at all
					$authorMakeZero = false;
					foreach($arrIncludeByAuthors as $key=>$userID){
						
						if($userID == "uc_loggedin_user"){
							
							$userID = get_current_user_id();
							$arrIncludeByAuthors[$key] = $userID;
							
							if(empty($userID))
								$authorMakeZero = true;
						}
						
					}
										
					if($authorMakeZero == true)
						$arrIncludeByAuthors = array("0");
					
					if(!empty($arrIncludeByAuthors))
						$args["author__in"] = $arrIncludeByAuthors;
					
				break;
				case "date":
					
					$response = $this->getPostListData_dateQuery($value);
					$arrDateQuery = UniteFunctionsUG::getVal($response, "date_query");
					
					if(!empty($arrDateQuery))
						$args["date_query"] = $arrDateQuery;
					
					$arrDateMetaQuery = UniteFunctionsUG::getVal($response, "meta_query");
					
					if(!empty($arrDateMetaQuery))
						$arrMetaQuery = array_merge($arrMetaQuery, $arrDateMetaQuery);
										
				break;
				case "parent":
					
					$parent =  UniteFunctionsUG::getVal($value, "includeby_parent");
					if(!empty($parent)){
						
						if(is_array($parent) && count($parent) == 1)
							$parent = $parent[0];

						$addParentType = UniteFunctionsUG::getVal($value, "includeby_parent_addparent");
												
						if($addParentType == "start" || $addParentType == "end")
							$addParentIDs = $parent;
													
						if(is_array($parent))
							$args["post_parent__in"] = $parent;
						else
							$args["post_parent"] = $parent;
					}
				break;
				case "meta":
					
					$metaKey = UniteFunctionsUG::getVal($value, "includeby_metakey");
					$metaCompare = UniteFunctionsUG::getVal($value, "includeby_metacompare");
					
					$metaValue = UniteFunctionsUG::getVal($value, "includeby_metavalue");
					$metaValue = $this->modifyMetaValueForCompare($metaValue);
					
					$metaValue2 = UniteFunctionsUG::getVal($value, "includeby_metavalue2");
					$metaValue2 = $this->modifyMetaValueForCompare($metaValue2);
					
					$metaValue3 = UniteFunctionsUG::getVal($value, "includeby_metavalue3");
					$metaValue3 = $this->modifyMetaValueForCompare($metaValue3);
					
					//second key
					
					$metaKeySecond = UniteFunctionsUG::getVal($value, "includeby_second_metakey");
					$metaCompareSecond = UniteFunctionsUG::getVal($value, "includeby_second_metacompare");
					
					$metaValueSecond = UniteFunctionsUG::getVal($value, "includeby_second_metavalue");
					$metaValueSecond = $this->modifyMetaValueForCompare($metaValueSecond);

					$metaRelation = UniteFunctionsUG::getVal($value, "includeby_meta_relation");
					
					$arrMetaSubQuery = array();
					$arrMetaSubQuery2 = array();
					
					if(!empty($metaKey)){
						
						$arrMetaSubQuery = $this->getPostListData_metaValues($arrMetaSubQuery, $metaValue, $metaKey, $metaCompare);
						
						if(!empty($metaValue2))
							$arrMetaSubQuery = $this->getPostListData_metaValues($arrMetaSubQuery, $metaValue2, $metaKey, $metaCompare);
							
						if(!empty($metaValue3))
							$arrMetaSubQuery = $this->getPostListData_metaValues($arrMetaSubQuery, $metaValue3, $metaKey, $metaCompare);
						
						if(count($arrMetaSubQuery) > 1)
							$arrMetaSubQuery["relation"] = "OR";
						
					}
					
					
					if(!empty($metaKeySecond)){
						
						$arrMetaSubQuery2[] = array(
				            'key' => $metaKeySecond,
				            'value' => $metaValueSecond,
							'compare'=>$metaCompareSecond
						);
						
					}
					
					
					if(!empty($arrMetaSubQuery) && !empty($arrMetaSubQuery2)){
						
							if(count($arrMetaSubQuery) == 1){	//both single
								
								$arrMetaSubQuery[] = $arrMetaSubQuery2[0];
								$arrMetaSubQuery["relation"] = $metaRelation;
								
								$arrMetaQuery[] = $arrMetaSubQuery;
								
							}else{							//both - first multiple
								$arrMetaQuery[] = array(
								$arrMetaSubQuery, 
								$arrMetaSubQuery2,
								"relation"=>$metaRelation);
								
							}
						
					}else{
					
						if(!empty($arrMetaSubQuery))
							$arrMetaQuery[] = $arrMetaSubQuery;
						
						if(!empty($arrMetaSubQuery2))
							$arrMetaQuery[] = $arrMetaSubQuery2;
					}
										
					
				break;
				case "php_function":
					
					$arrIDsPHPFunction = $this->getPostListData_getIDsFromPHPFunction($value, $showDebugQuery);
					
				break;
				case "ids_from_meta":
					
					$arrIDsPostMeta = $this->getPostListData_getIDsFromPostMeta($value, $showDebugQuery);
					
				break;
				case "current_terms":
									
					$currentTaxQuery = UniteFunctionsWPUG::getCurrentPageTaxQuery();
					
				break;
			}
			
		}
		
		//add sticky posts only
		$arrStickyPosts = array();
		
		if($getOnlySticky == true){
			
			$arrStickyPosts = get_option("sticky_posts");
			
			$args["ignore_sticky_posts"] = true;
			
			if(!empty($arrStickyPosts) && is_array($arrStickyPosts)){
				$args["post__in"] = $arrStickyPosts;
			}else{
				$args["post__in"] = array("0");		//no posts at all
			}
		}
		
				
		if(!empty($arrIDsPHPFunction)){
			$arrPostInIDs = array_merge($arrPostInIDs, $arrIDsPHPFunction);
			$makePostINOrder = true;
		}
		
		
		if(!empty($arrIDsPostMeta)){
			$arrPostInIDs = $arrIDsPostMeta;
			$makePostINOrder = true;
		}
						
		if(!empty($arrMetaQuery))
			$args["meta_query"] = $arrMetaQuery;
		
		//add exclude specific posts if available
		if(!empty($arrPostsNotIn)){
			$arrPostsNotIn = array_unique($arrPostsNotIn);
			$args["post__not_in"] = $arrPostsNotIn;
		}
		
		
		//add post status
		
		$arrStatuses = UniteFunctionsUG::getVal($value, "status");
		
		if(empty($arrStatuses))
			$arrStatuses = "publish";
		
		if(!empty($offset))
			$args["offset"] = $offset;
		
		if(is_array($arrStatuses) && count($arrStatuses) == 1)
			$arrStatuses = $arrStatuses[0];
			
		$args["post_status"] = $arrStatuses;
		
		
		// -------- add order by ----------
		
		if(!empty($orderBy))
			$args["orderby"] = $orderBy;
		
		if($orderBy == "meta_value" || $orderBy == "meta_value_num")
			$args["meta_key"] = $orderByMetaKey;
		
		if(!empty($orderDir))
			$args["order"] = $orderDir;
		
		
		// -------- add post type -----------
		
			
		$args["post_type"] = $postType;

		//------ set paging options
		
		if(!empty($offsetArg)){
			
			$currentOffset = UniteFunctionsUG::getVal($args, "offset",0);
			if(!empty($currentOffset))
				$offsetArg += $currentOffset;
			
			$args["offset"] = $offsetArg;
			
			if(!empty($limitArg)){
				$args["posts_per_page"] = $limitArg;
			}
				
		}
		
		
		GlobalsUG::$lastPostsQuery = $args;
		GlobalsUG::$lastDebug = $this->debugText;
		
		$query = new WP_Query($args);
		
		$arrPosts = $query->posts;
		
		if($showDebugQuery == true){
			
			$totalPosts = $query->found_posts;
			
			$this->debug("Found <b>$totalPosts</b> posts. ");
		}
		
		$arrPostItems = $this->getItemsFromPosts($arrPosts);
		
		
		return($arrPostItems);
	}

}