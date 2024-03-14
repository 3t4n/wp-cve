<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class owlc_cls_dbquery {

	public static function owlc_gallery_view($guid = "", $offset = 0, $limit = 0) {

		global $wpdb;

		$arrRes = array();

		$sSql = "SELECT * FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type = 'GAL' ";

		if($guid <> "") {
			$sSql = $sSql . " and owl_guid='".$guid."'";
		}
		
		$sSql = $sSql . " order by owl_id desc";
		$sSql = $sSql . " LIMIT $offset, $limit";
		
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);

		return $arrRes;
	}
	
	public static function owlc_image_view($guid = "", $offset = 0, $limit = 0) {

		global $wpdb;

		$arrRes = array();

		$sSql = "SELECT * FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type = 'IMG' ";

		if($guid <> "") {
			$sSql = $sSql . " and owl_guid='".$guid."'";
		}
		
		$sSql = $sSql . " order by owl_id desc";
		$sSql = $sSql . " LIMIT $offset, $limit";
		
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);

		return $arrRes;
	}
	
	public static function owlc_gallery_shorcode($id = 0) {

		global $wpdb;

		$arrRes = array();

		if($id <> "" && $id <> "0") {
			$sSql = "SELECT * FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type = 'GAL' and owl_id=".$id;
			$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		}
		return $arrRes;
	}
	
	public static function owlc_image_shorcode($guid = "") {
				
		global $wpdb;
		
		$arrRes = array();

		if($guid <> "") {
			$sSql = "SELECT * FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type = 'IMG' ";
			$sSql = $sSql . " and owl_galleryguid='".$guid."' order by owl_id";
			$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		}
		
		return $arrRes;
	}
	
	public static function owlc_image_viewbycategory($guid = "") {

		global $wpdb;

		$arrRes = array();

		$sSql = "SELECT * FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type = 'IMG' ";

		if($guid <> "") {
			$sSql = $sSql . " and owl_galleryguid='".$guid."'";
		}
		
		$sSql = $sSql . " order by owl_id desc";
		
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);

		return $arrRes;
	}


	public static function owlc_delete($guid = "") {

		global $wpdb;

		if($guid <> "") {
			$sSql = $wpdb->prepare("DELETE FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE `owl_guid` = %s LIMIT 1", $guid);
			$wpdb->query($sSql);
		}
		
		return true;
	}

	public static function owlc_gallery_action($data = array(), $action = "insert") {

		global $wpdb;
		
		$owl_title 		= sanitize_text_field(esc_attr($data["owl_title"]));
		$owl_setting 	= sanitize_text_field(esc_attr($data["owl_setting"]));

		if($action == "insert") {
				$guid = owlc_cls_common::owlc_generate_guid(60);
				$sql = $wpdb->prepare("INSERT INTO `".$wpdb->prefix."owl_carousel_tbl`
						(`owl_guid`, `owl_type`, `owl_title`, `owl_image`, `owl_setting`, `owl_galleryguid`, `owl_order`)
						VALUES(%s, %s, %s, %s, %s, %s, %d)", 
						array($guid, 'GAL', trim($owl_title), "", trim($owl_setting), "", 0));
				$wpdb->query($sql);
				return "sus";
		} elseif($action == "update") {
				$sSql = $wpdb->prepare("UPDATE `".$wpdb->prefix."owl_carousel_tbl` SET `owl_title` = %s, `owl_setting` = %s WHERE owl_guid = %s LIMIT 1", 
						array(trim($owl_title), trim($owl_setting), $data["owl_guid"]));
				$wpdb->query($sSql);
				return "sus";
		}
	}
	
	public static function owlc_image_action($data = array(), $action = "insert") {

		global $wpdb;
		
		$owl_title 		= sanitize_text_field(esc_attr($data["owl_title"]));
		$owl_image 		= sanitize_text_field(esc_attr($data["owl_image"]));
		$owl_galleryguid 	= sanitize_text_field(esc_attr($data["owl_galleryguid"]));
		$owl_order 		= sanitize_text_field(esc_attr($data["owl_order"]));
		
		if($action == "insert") {
				$guid = owlc_cls_common::owlc_generate_guid(60);
				$sql = $wpdb->prepare("INSERT INTO `".$wpdb->prefix."owl_carousel_tbl`
						(`owl_guid`, `owl_type`, `owl_title`, `owl_image`, `owl_setting`, `owl_galleryguid`, `owl_order`)
						VALUES(%s, %s, %s, %s, %s, %s, %d)", 
						array($guid, 'IMG', trim($owl_title), trim($owl_image), "", trim($owl_galleryguid), $owl_order));
				$wpdb->query($sql);
				return "sus";
		} elseif($action == "update") {
				$sSql = $wpdb->prepare("UPDATE `".$wpdb->prefix."owl_carousel_tbl` SET `owl_title` = %s, `owl_image` = %s, 
						`owl_galleryguid` = %s, `owl_order` = %d WHERE owl_guid = %s LIMIT 1", 
						array(trim($owl_title), trim($owl_image), trim($owl_galleryguid), trim($owl_order), $data["owl_guid"]));
				$wpdb->query($sSql);
				return "sus";
		}
	}

	public static function owlc_gallery_count( $guid = "" ) {

		global $wpdb;

		$result = '0';

		if($guid <> "") {
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type='GAL' and `owl_guid` = %s", array($guid));
		} else {
			$sSql = "SELECT COUNT(*) AS `count` FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type='GAL'";
		}

		$result = $wpdb->get_var( $sSql );

		return $result;
	}
	
	public static function owlc_image_count( $guid = "" ) {

		global $wpdb;

		$result = '0';

		if($guid <> "") {
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type='IMG' and `owl_guid` = %s", array($guid));
		} else {
			$sSql = "SELECT COUNT(*) AS `count` FROM `".$wpdb->prefix."owl_carousel_tbl` WHERE owl_type='IMG'";
		}

		$result = $wpdb->get_var( $sSql );

		return $result;
	}

}