<?php

defined('UNITEGALLERY_INC') or die('Restricted access');

class HelperProviderUG{

	/**
	 * get select post status
	 */
	public static function getArrPostStatusSelect(){
		
		$arrStatus = array(
			"publish"=>__("Publish","unlimited-elements-for-elementor"),
			"future"=>__("Future","unlimited-elements-for-elementor"),
			"draft"=>__("Draft","unlimited-elements-for-elementor"),
			"pending"=>__("Pending Review","unlimited-elements-for-elementor"),
			"private"=>__("Private","unlimited-elements-for-elementor"),
			"inherit"=>__("Inherit","unlimited-elements-for-elementor"),
		);
		
		return($arrStatus);
	}
	
	
	/**
	 * get date select
	 */
	public static function getArrPostsDateSelect(){
		
		$arrDate = array(
			"all"=>__("All","unitegallery"),
			"today"=>__("Past Day","unitegallery"),
			"yesterday"=>__("Past 2 days","unitegallery"),
			"week"=>__("Past Week","unitegallery"),
			"month"=>__("Past Month","unitegallery"),
			"three_months"=>__("Past 3 Months","unitegallery"),
			"year"=>__("Past Year","unitegallery"),
			"this_month"=>__("This Month","unitegallery"),
			"next_month"=>__("Next Month","unitegallery"),
			"custom"=>__("Custom","unitegallery")
		);
		
		return($arrDate);
	}
	
	/**
	 * get data for meta compare select
	 */
	public static function getArrMetaCompareSelect(){
		
		$arrItems = array();
		$arrItems["="] = "Equals";
		$arrItems["!="] = "Not Equals";
		$arrItems[">"] = "More Then";
		$arrItems["<"] = "Less Then";
		$arrItems[">="] = "More Or Equal";
		$arrItems["<="] = "Less Or Equal";
		$arrItems["LIKE"] = "LIKE";
		$arrItems["NOT LIKE"] = "NOT LIKE";
		
		$arrItems["IN"] = "IN";
		$arrItems["NOT IN"] = "NOT IN";
		$arrItems["BETWEEN"] = "BETWEEN";
		$arrItems["NOT BETWEEN"] = "NOT BETWEEN";
		
		$arrItems["EXISTS"] = "EXISTS";
		$arrItems["NOT EXISTS"] = "NOT EXISTS";
		
		return($arrItems);
	}
	
	
}