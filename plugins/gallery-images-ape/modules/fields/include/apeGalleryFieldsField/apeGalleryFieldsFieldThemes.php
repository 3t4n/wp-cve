<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsFieldThemes extends apeGalleryFieldsField{


	public function getData($value = null){
		$data = parent::getData($value);
		$data['themes'] = $this->getThemes( $data );
		return $data;
	}

	protected function getThemes($values){

		$args = array(

			'posts_per_page'   => 99,
/*			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',*/
			'orderby'          => 'title',
			'order'            => 'asc',
/*			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',*/
			'post_type'        => WPAPE_GALLERY_THEME_POST,
/*			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   => '',
			'author_name'	   => '',*/
			'post_status'      => 'publish',
			'suppress_filters' => true 

		); 
		$themes = get_posts($args);

		if( !count($themes) ) $themes =array();
		
		return $themes;
	}
}
