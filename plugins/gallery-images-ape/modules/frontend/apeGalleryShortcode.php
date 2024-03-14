<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class wpApeGalleryFrontendModuleShortcode extends wpApeGallery_Module{

	function getModuleFileName(){ return __FILE__ ; }

	public function hooks(){
		add_shortcode( 'ape-gallery', array($this, 'doShortcode') );

		add_filter( 'the_content', array($this, 'actionShortcode') );

		if( get_option( WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable', 0 ) ){
			add_action( 'wp_loaded', array($this, 'doCustomShortcode') ) ;
		}
	}

	public function doShortcode( $attr ){
		if( $this->checkAttrOnIdZero($attr)==false  ){
			return __('ApeGallery ShortCode is incorrect', 'gallery-images-ape');
		}
		return apeGalleryHelper::renderGalleryAttr( $attr );
	}


	public function doShortcodeFromIds( $attr ){	 	
		if( $this->checkAttrOnIds($attr)== false ){
			return __('ApeGallery ShortCode is incorrect', 'gallery-images-ape');
		}
		return apeGalleryHelper::renderGalleryAttr( $attr );
	}


	public function doCustomShortcode(){
		$this->overwriteGalleryShortcode();	
		$this->doUserShortcode();
	} 


	private function overwriteGalleryShortcode(){
		remove_shortcode('gallery');
		add_shortcode( 'gallery', array($this, 'doShortcodeFromIds') );
	}


	private function doUserShortcode(  ){
		$shortcode = apeGalleryHelper::clearString( get_option(WPAPE_GALLERY_NAMESPACE.'shortcode', '') );
		if( $shortcode ){
			$shortcode = explode( ',', $shortcode);
			for ($i=0; $i < count($shortcode); $i++) { 
				$shortcode[$i] = trim($shortcode[$i]);
				if($shortcode[$i]) add_shortcode( $shortcode[$i], array($this, 'doShortcodeFromIds') );
			}
		}
	}


	private function checkAttrOnIdZero( $attr ){
		if( !is_array($attr) ) return false;
		if( isset($attr['id']) && (int) $attr['id'] ) return true;
		if( isset($attr[0]) && (int) $attr[0] ) return true;
		return false;
	}


	private function checkAttrOnIds( $attr ){
		if( !is_array($attr) ) return false;
		if( isset($attr['ids']) && (int) $attr['ids'] ) return true;
		return false;
	}

	public function actionShortcode($content){
	    global $post;

	    if ( post_password_required() ) return $content;

	    if( is_main_query() && get_post_type() == WPAPE_GALLERY_POST ){
	    	$content .= do_shortcode("[ape-gallery id={$post->ID}]");
	    }
	    
		return $content;
	}

}

new wpApeGalleryFrontendModuleShortcode();