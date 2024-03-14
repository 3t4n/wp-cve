<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsAjax{

	public function __construct(){
		$this->hook();
	}

	public function hook(){
		//delete_option( 'yo_gallery_fields_voting1' );
		//delete_option( 'yo_gallery_fields_feedback' );
		add_action('wp_ajax_yo_gallery_fields_saveoption', array( $this, 'saveOption') );
	}

	public function saveOption(){
		if(isset($_POST['feedback']) && $_POST['feedback']==1){
			delete_option( 'yo_gallery_fields_feedback' );
			add_option( 'yo_gallery_fields_feedback', '1' ); 
		} else {
			delete_option( 'yo_gallery_fields_voting1' );
			add_option( 'yo_gallery_fields_voting1', '1' ); 
		}
		echo 'ok';
		wp_die();
	}

}
$fieldAjax = new apeGalleryFieldsAjax();