<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class owlc_cls_intermediate {
	public static function owlc_gallery() {
		global $wpdb;
		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch($current_page) {
			case 'add':
				require_once(OWLC_DIR.'gallery'.DIRECTORY_SEPARATOR.'gallery-add.php');
				break;
			case 'edit':
				require_once(OWLC_DIR.'gallery'.DIRECTORY_SEPARATOR.'gallery-edit.php');
				break;
			case 'help':
				require_once(OWLC_DIR.'gallery'.DIRECTORY_SEPARATOR.'gallery-help.php');
				break;
			default:
				require_once(OWLC_DIR.'gallery'.DIRECTORY_SEPARATOR.'gallery-show.php');
				break;
		}
	}

	public static function owlc_images() {
		global $wpdb;
		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch($current_page) {
			case 'add':
				require_once(OWLC_DIR.'images'.DIRECTORY_SEPARATOR.'images-add.php');
				break;
			case 'edit':
				require_once(OWLC_DIR.'images'.DIRECTORY_SEPARATOR.'images-edit.php');
				break;
			default:
				require_once(OWLC_DIR.'images'.DIRECTORY_SEPARATOR.'images-show.php');
				break;
		}
	}
}