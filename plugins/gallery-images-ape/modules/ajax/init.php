<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class wpAPEGalleryModule_Ajax extends wpApeGallery_Module{

	public $pref = 'wp_ajax_wpape_gallery_';

	function getModuleFileName(){ return __FILE__ ; }

	function load(){
		apeGalleryHelper::load( 'images.ajax.php', $this->modulePath );
	}

	public function hooks(){
    	if( apeGalleryHelper::isAdminArea( $allowAjax = true ) ){
			add_action( $this->pref.'get_images_from_ids', array($this, 'get_images_tags_from_ids') );		
			add_action( $this->pref.'get_gallery_json', array($this, 'getGalleryListJson') );		
    	}
    }


	function get_images_tags_from_ids() { 
		$idStr = isset($_POST['idstring']) ? trim($_POST['idstring']) : '';
		echo wpAPEGalleryModule_Ajax_Images::getImagesTagsFromIdsStr($idStr);;
		wp_die(); 
	}

	function getGalleryListJson() { 
		$galleryId = isset($_REQUEST['galleryid']) ? (int)($_REQUEST['galleryid']) : '';
		
		$query = new WP_Query( 
			array( 
				'post_type' => WPAPE_GALLERY_POST,
				'post_status' => array( 'publish', 'private', 'future' )
			)
		);

		$posts = $query->posts;
		$returnJson = array();
		if( is_array($posts) && count($posts)){
			foreach($posts as $post) {
				$returnJson[] = array(
					'id' 		=> $post->ID,
					'title' 	=> esc_js($post->post_title),
					'parent' 	=> $post->post_parent,
				);
			}
		}

		wp_send_json( $returnJson );
		wp_die();
	}
}

$moduleAjax = new wpAPEGalleryModule_Ajax();