<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class apeGallerySource{
	
	public $id 			= 0;
	public $themeId 	= 0;
	public $fromIds 	= '';

	public $imagesList	= array();
	public $categoriesList	= array();

	public $width 		= 0;
	public $height		= 0;
	public $thumbsource = '';
	public $orderby = '';
	public $lazyLoad = 1;

	private $meta = array(
		'type' => '',
		'menuSelfImages' => false,
	);


	function __construct( $id, $themeId, $fromIds = 0 ){
 		if( isset($id) && (int) $id ){
			$this->id = (int) $id;
 		}

 		if( isset($themeId) && (int) $themeId ){
			$this->themeId = (int) $themeId;
 		}

 		$this->fromIds = $fromIds;
 		
 		$this->initGalleryMeta();

 		++$this->lazyLoad;
 	}

 	private function initGalleryMeta(){
 		$this->meta['type'] = get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'type', true );
 		$this->meta['menuSelfImages'] = (int) get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'menuSelfImages', true ) === 1;
 	}

 	public function getMeta( $title ){
 		
 		if( !$title ){
 			return new Exception('apeGallerySource::getMeta - empty title');
 		}

 		if( isset($this->meta[$title])==false ) return null;
 		
 		return $this->meta[$title];
 	}

 	public function setSize( $width , $height, $thumbsource, $orderby ){
 		$this->width 		= $width;
 		$this->height 		= $height;
 		$this->thumbsource 	= $thumbsource;
 		$this->orderby 		= $orderby;
 	}


 	private function getFromIds(){
 		
 		$imgIds = array();

 		if( !$this->fromIds ){
			$imgIds = self::getGalleryImages( $this->id );
 		} else{
			$imgIds = $this->fromIds;
		}
		
		if( isset($imgIds) && !is_array($imgIds)==1 && trim($imgIds)=='' ) $imgIds = array();
		
		if( isset($imgIds) && is_array($imgIds) && isset($imgIds[0]) && count($imgIds)==1 && trim($imgIds[0])=='' ) $imgIds = array();


		if( $this->getMeta('menuSelfImages') == false && $this->getMeta('type')=='grid' ){
			$imgIds = array();
		}
		return $imgIds;
 	}


 	public function getImages(){
 		
 		if(!$this->id) return false;

 		$imgIds = $this->getFromIds();

		for ($i=0; $i < count($imgIds) ; $i++){
			$this->imagesList[] = array( 'id'=> $imgIds[$i], 'catid'=> $this->id );
		}

		$post = get_post($this->id);

		if( get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'menuSelf', true ) && !$this->fromIds ){
			$this->categoriesList[] = array( 
				'id'	=> $this->id, 
				'title'	=> $post->post_title, 
				'name'	=> $post->post_name, 
				'icon' 	=> get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'menuLabel', true ),
				'alter' => get_post_meta( $this->id, WPAPE_GALLERY_NAMESPACE.'menuLabelText', true ) 
			);
		}


		$my_wp_query = new WP_Query();
 		$all_wp_pages = $my_wp_query->query(
 			array(
 				'post_type' => WPAPE_GALLERY_POST,
 				'orderby'   => array( 
 					'menu_order'	=> 'DESC', 
 					'order'			=> 'ASC', 
 					'title'			=> 'DESC' 
 				),
 				'posts_per_page' => 999/*$this->lazyLoad*/,
 			)
 		);
 
		$children = get_page_children( $this->id, $all_wp_pages );

		if( $this->fromIds ) $children = array();

		$tempCatArray  = array();

		for($i=0;$i<count($children);$i++){

			if( isset( $children[$i]->ID) ){

				$imgIds = self::getGalleryImages( $children[$i]->ID );
				
				if( $imgIds && count($imgIds) ){
					$post = get_post($children[$i]->ID); 
					$tempCatArray[] = array( 
							'id'	=> $children[$i]->ID,
							'title'	=> $post->post_title, 
							'name'	=> $post->post_name, 
							'icon' 	=> get_post_meta( $children[$i]->ID, WPAPE_GALLERY_NAMESPACE.'menuLabel', true ),
							'alter' => get_post_meta( $children[$i]->ID, WPAPE_GALLERY_NAMESPACE.'menuLabelText', true )
					);
					for ($j=0; $j < count($imgIds) ; $j++){
						$this->imagesList[] = array( 'id'=> $imgIds[$j], 'catid'=> $children[$i]->ID );
					}
				}

			}
		}

		$tempCatArray = array_reverse ($tempCatArray);
		$this->categoriesList = array_merge($this->categoriesList, $tempCatArray );

		$this->initImagesData();

		$this->initImagesOrder();

 	}
 	
 	private function initImagesOrder(){
 		switch ( $this->orderby ) {
			case 'random':		shuffle( $this->imagesList);											break;

			case 'titleU':		usort( $this->imagesList, array('apeGallerySource','sortTitleASC') );	break;
			case 'titleD':		usort( $this->imagesList, array('apeGallerySource','sortTitleDESC') );	break;

			case 'dateU':		usort( $this->imagesList, array('apeGallerySource','sortDateASC') );	break;
			case 'dateD':		usort( $this->imagesList, array('apeGallerySource','sortDateDESC') );	break;

			case 'categoryU':	$this->imagesList = array_reverse($this->imagesList);					break;
			case 'categoryD':
			default:
				break;
		}
 	}

 	private function initImagesData(){

 		if( !is_array($this->imagesList) ||  count($this->imagesList) == 0  ){
 			$this->imagesList = array();
 			return;
 		}

		foreach( $this->imagesList as $imgKey => $img) {
			$imgId = $img['id'];
			$thumb = wp_get_attachment_image_src( $imgId , $this->thumbsource);
			if( !is_array($thumb) || count($thumb) < 1 ){
				unset($this->imagesList[$imgKey]);
			} else {
				$this->imagesList[$imgKey]['image'] 	= 	wp_get_attachment_url( $imgId );
				$this->imagesList[$imgKey]['thumb'] 	=	( isset($thumb[0]) ) ? $thumb[0] : '';
				$this->imagesList[$imgKey]['sizeW']  	=	( isset($thumb[1]) ) ? $thumb[1] : $this->width;
				$this->imagesList[$imgKey]['sizeH']  	= 	( isset($thumb[2]) ) ? $thumb[2] : $this->height;
				$this->imagesList[$imgKey]['data'] 		=	get_post( $imgId );
				$this->imagesList[$imgKey]['link'] 		=	get_post_meta( $imgId, WPAPE_GALLERY_NAMESPACE.'gallery_link', true );
				$this->imagesList[$imgKey]['typelink'] 	= 	get_post_meta( $imgId, WPAPE_GALLERY_NAMESPACE.'gallery_type_link', true );
				$this->imagesList[$imgKey]['videolink']	= 	get_post_meta( $imgId, WPAPE_GALLERY_NAMESPACE.'gallery_video_link', true );
				$this->imagesList[$imgKey]['col'] 		=	get_post_meta( $imgId, WPAPE_GALLERY_NAMESPACE.'gallery_col', true );
				$this->imagesList[$imgKey]['effect'] 	=	get_post_meta( $imgId, WPAPE_GALLERY_NAMESPACE.'gallery_effect', true );
			}
		}
 	}


 	private static function getGalleryImages( $id ){
 		$imgIds = get_post_meta( $id, WPAPE_GALLERY_NAMESPACE.'galleryImages', true );
 		
 		if( is_array( $imgIds ) == false && is_string($imgIds) ) $imgIds = explode(",", $imgIds);	

 		if( is_array($imgIds) == false ) $imgIds = array();

		return $imgIds;
 	}


 	private static function sortTitleASC( $elem1, $elem2 ){
		return strcasecmp( $elem1['data']->post_title, $elem2['data']->post_title ) * -1;
	}


	private static function sortTitleDESC( $elem1, $elem2 ){
		return strcasecmp( $elem1['data']->post_title, $elem2['data']->post_title );
	}


	private static function sortDateASC( $elem1, $elem2 ){
		if( $elem1['data']->post_date == $elem2['data']->post_date ) return 0;
		if( $elem1['data']->post_date  > $elem2['data']->post_date ) return 1;
			else return -1;
	}


	private static function sortDateDESC( $elem1, $elem2 ){
		if( $elem1['data']->post_date == $elem2['data']->post_date ) return 0;
		if( $elem1['data']->post_date  > $elem2['data']->post_date ) return -1;
			else return 1;
	}
}