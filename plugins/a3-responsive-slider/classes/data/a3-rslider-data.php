<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Data
{
	
	public static function install_database() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if( ! empty($wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}

		$a3_rslider_images = $wpdb->prefix . "a3_rslider_images";
		if($wpdb->get_var("SHOW TABLES LIKE '$a3_rslider_images'") != $a3_rslider_images){
			$sql = "CREATE TABLE " . $a3_rslider_images . " (
					   	  `id` bigint(20) NOT NULL auto_increment,
						  `slider_id` bigint(20) NOT NULL,
						  `img_url` text NOT NULL,
						  `video_url` text NOT NULL,
						  `is_video` tinyint(1) NOT NULL default 0,
						  `img_title` blob,
						  `img_description` blob,
						  `img_alt` blob,
						  `img_link` text,
						  `open_newtab` tinyint(1) NOT NULL default 0,
						  `show_readmore` tinyint(1) NOT NULL default 0,
						  `img_order` int(11) NOT NULL default 0,
						  PRIMARY KEY  (`id`)
						) $collate ;";

			$wpdb->query($sql);
		}
	}
		
	public static function get_count( $where='', $order='', $limit ='' ) {}
	
	public static function get_row( $id, $where='', $output_type='OBJECT' ) {
		global $wpdb;
		$table_name = $wpdb->prefix. "a3_rslider";
		if(trim($where) != '')
			$where = ' AND '.$where;
		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE slider_id = %d {$where}", $id ), $output_type);
		return $result;
	}
		
	public static function remove_slider_images( $slider_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix. "a3_rslider_images";
		$result = $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_name} WHERE slider_id = %d", $slider_id ) );
		return $result;
	}
	
	public static function get_first_image_slider( $slider_id, $output_type='OBJECT' ) {
		global $wpdb;
		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
		if ( isset( $slider_settings['support_youtube_videos'] ) && $slider_settings['support_youtube_videos'] == 0 ) {
			$sql_query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."a3_rslider_images WHERE slider_id = %d AND is_video = 0 ORDER BY img_order ASC LIMIT 0, 1", $slider_id );
		} else {
			$sql_query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."a3_rslider_images WHERE slider_id = %d ORDER BY img_order ASC LIMIT 0, 1", $slider_id );
		}
		
		$result = $wpdb->get_row( $sql_query, $output_type );
		return $result;
	}
	
	public static function get_all_images_from_slider( $slider_id, $where='' ) {
		global $wpdb;
		if(trim($where) != '')
			$where = " AND {$where} ";
		$rs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."a3_rslider_images WHERE slider_id = %d ".$where." ORDER BY img_order ASC", $slider_id ) );
		if(count($rs) > 0){
			return $rs;
		}else{
			return false;
		}
	}
	
	public static function get_all_images_from_slider_client( $slider_id ) {
		global $wpdb;
		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
		if ( isset( $slider_settings['support_youtube_videos'] ) && $slider_settings['support_youtube_videos'] == 0 ) {
			$sql_query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."a3_rslider_images WHERE slider_id = %d AND is_video = 0 ORDER BY img_order ASC", $slider_id );
		} else {
			$sql_query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."a3_rslider_images WHERE slider_id = %d ORDER BY img_order ASC", $slider_id );
		}
		$rs = $wpdb->get_results( $sql_query );
		if(count($rs) > 0){
			return $rs;
		}else{
			return false;
		}
	}
	
	public static function insert_row_image( $slider_id, $img_url, $img_link, $img_title, $img_description, $img_alt, $img_order, $show_readmore = 1, $open_newtab = 0 ) {
		global $wpdb;
		$table_name = $wpdb->prefix. "a3_rslider_images";
		$img_title = addslashes($img_title);
		$img_description = addslashes($img_description);
		$img_alt = addslashes($img_alt);
		$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpdb->prefix."a3_rslider_images(`id`, `slider_id`, `img_url`, `img_title`, `img_link`, `img_description`, `img_alt`, `img_order`, `show_readmore`, `open_newtab` ) VALUES (NULL,'$slider_id', %s,'$img_title','$img_link','$img_description', '$img_alt', '$img_order', '$show_readmore', '$open_newtab' );", $img_url ) );
	}
	
	public static function insert_row_video( $slider_id, $video_url, $img_link, $img_title, $img_description, $img_order, $show_readmore = 1, $open_newtab = 0 ) {
		global $wpdb;
		$table_name = $wpdb->prefix. "a3_rslider_images";
		$img_title = addslashes($img_title);
		$img_description = addslashes($img_description);
		$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpdb->prefix."a3_rslider_images(`id`, `slider_id`, `video_url`, `is_video`, `img_title`, `img_link`, `img_description`, `img_order`, `show_readmore`, `open_newtab` ) VALUES (NULL,'$slider_id', %s, 1, '$img_title','$img_link','$img_description', '$img_order', '$show_readmore', '$open_newtab' );", $video_url ) );
	}
	
	public static function count_images_in_slider( $slider_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "a3_rslider_images";
		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
		if ( isset( $slider_settings['support_youtube_videos'] ) && $slider_settings['support_youtube_videos'] == 0 ) {
			$sql_query = $wpdb->prepare( "SELECT COUNT(*) FROM {$table_name} WHERE `slider_id` = %d AND is_video = 0", $slider_id );
		} else {
			$sql_query = $wpdb->prepare( "SELECT COUNT(*) FROM {$table_name} WHERE `slider_id` = %d", $slider_id );
		}
		$row = $wpdb->get_var( $sql_query );
		return $row;
	}
}
