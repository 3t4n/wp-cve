<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class es_af_query {
	public static function es_af_count($id = 0) {

		global $wpdb;

		$result = 0;
		if($id > 0) {
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS count FROM ". $wpdb->prefix . ES_AF_TABLE . " WHERE es_af_plugin='es-af' and es_af_id = %d", array($id));
		} else {
			$sSql = "SELECT COUNT(*) AS count FROM ". $wpdb->prefix . ES_AF_TABLE . " WHERE es_af_plugin='es-af'";
		}
		$result = $wpdb->get_var($sSql);
		return $result;
	}

	public static function es_af_select($id = 0) {

		global $wpdb;

		$arrRes = array();
		if($id > 0) {
			$sSql = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix . ES_AF_TABLE . " where es_af_plugin='es-af' and es_af_id = %d", array($id));
		} else {
			$sSql = "SELECT * FROM ". $wpdb->prefix . ES_AF_TABLE . " where es_af_plugin='es-af' order by es_af_id desc";
		}
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}

	public static function es_af_delete($id = 0) {

		global $wpdb;

		$sSql = $wpdb->prepare("DELETE FROM ". $wpdb->prefix . ES_AF_TABLE . " WHERE es_af_plugin='es-af' and es_af_id = %d LIMIT 1", $id);
		$wpdb->query($sSql);
		return true;
	}

	public static function es_af_act($data = array(), $action = "ins") {

		global $wpdb;

		if($action == "ins") {
			$sql = $wpdb->prepare("INSERT INTO ". $wpdb->prefix . ES_AF_TABLE ." 
			(`es_af_title`, `es_af_desc`, `es_af_name`, `es_af_name_mand`, `es_af_email`, `es_af_email_mand`, `es_af_group`, `es_af_group_mand`, `es_af_group_list`, `es_af_plugin`)
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
			array($data["es_af_title"], $data["es_af_desc"], $data["es_af_name"], $data["es_af_name_mand"], 
			$data["es_af_email"], $data["es_af_email_mand"], $data["es_af_group"], $data["es_af_group_mand"], $data["es_af_group_list"], "es-af") );
			$wpdb->query($sql);
			
			return "sus";
		} elseif($action == "ups") {
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix . ES_AF_TABLE." SET `es_af_title` = %s, `es_af_desc` = %s, `es_af_name` = %s, `es_af_name_mand` = %s, `es_af_email` = %s, 
			`es_af_email_mand` = %s, `es_af_group` = %s, `es_af_group_mand` = %s, `es_af_group_list` = %s WHERE es_af_id = %d LIMIT 1", 
			array($data["es_af_title"], $data["es_af_desc"], $data["es_af_name"], $data["es_af_name_mand"], $data["es_af_email"], 
			$data["es_af_email_mand"], $data["es_af_group"], $data["es_af_group_mand"], $data["es_af_group_list"], $data["es_af_id"]) );
			$wpdb->query($sql);

			return "sus";
		} else {
			return "err";
		}
	}
}