<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class owlc_cls_default {
	public static function owlc_gallery_default() {

		global $wpdb;

		$result = owlc_cls_dbquery::owlc_gallery_count("");
		if ($result == '0') {
			$gallery = array();
			$gallery['owl_title'] 	= "Sample Gallery";
			$gallery['owl_setting']	= "{items_1000: 4},{items_800: 3},{items_600: 2},{items_0: 1},{nav: true},{loop: false},{margin: 30},{autoHeight: true},{autoWidth: true},{autoplay: true},{autoplayTimeout: 3000}";
			owlc_cls_dbquery::owlc_gallery_action($gallery, "insert");
			
			$result = owlc_cls_dbquery::owlc_image_count("");
			if ($result == '0') {
				$galleryguid = array();
				$galleryguid = owlc_cls_dbquery::owlc_gallery_view("", 0, 1);
				$owl_galleryguid = $galleryguid[0]['owl_guid'];
		
				for ($i = 1; $i <= 9; $i++) {
					$images = array();
					$images['owl_galleryguid'] 	= $owl_galleryguid;
					$images['owl_title'] 		= "Sample Image ". $i;
					$images['owl_image'] 		= OWLC_URL . "sample/Sing_".$i.".jpg";
					$images['owl_order'] 		= $i;
					owlc_cls_dbquery::owlc_image_action($images, "insert");
				}
			}
		}
		return true;
	}
}